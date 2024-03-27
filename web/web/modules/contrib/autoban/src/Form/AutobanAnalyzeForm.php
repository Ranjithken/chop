<?php

namespace Drupal\autoban\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\Html;
use Drupal\autoban\AutobanUtils;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\autoban\Controller\AutobanController;

/**
 * Analyze watchdog entries for IP addresses for ban.
 */
class AutobanAnalyzeForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The autoban object.
   *
   * @var \Drupal\autoban\Controller\AutobanController
   */
  protected $autoban;

  /**
   * Construct the AutobanAnalyzeForm.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\autoban\Controller\AutobanController $autoban
   *   Autoban object.
   */
  public function __construct(Connection $connection, EntityTypeManager $entity_type_manager, AutobanController $autoban) {
    $this->connection = $connection;
    $this->entityTypeManager = $entity_type_manager;
    $this->autoban = $autoban;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('autoban')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autoban_analyze_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $rows = [];
    $header = [
      ['data' => $this->t('Count'), 'field' => 'cnt', 'sort' => 'desc'],
      ['data' => $this->t('Type'), 'field' => 'type'],
      ['data' => $this->t('Message raw'), 'field' => 'message'],
      $this->t('Operations'),
    ];

    // Run analyze query.
    $threshold_analyze = $this->config('autoban.settings')->get('autoban_threshold_analyze') ?: 5;
    $dblog_type_exclude = $this->config('autoban.settings')->get('autoban_dblog_type_exclude') ?: "autoban\ncron\nphp\nsystem\nuser";
    $dblog_type_exclude_msg = implode(', ', explode("\n", $dblog_type_exclude));

    $result = $this->getAnalyzeResult($header, $threshold_analyze, $dblog_type_exclude);
    if (count($result)) {
      $destination = $this->getDestinationArray();
      $url_destination = $destination['destination'];

      foreach ($result as $item) {
        $row = [$item->cnt, $item->type, $item->message];

        $links = [];
        $query = [
          'query' => [
            'type' => $item->type,
            'message' => Html::escape($item->message),
            'destination' => $url_destination,
          ],
        ];

        $links['add_rule'] = [
          'title' => $this->t('Add rule'),
          'url' => Url::fromRoute('entity.autoban.add_form', [], $query),
        ];
        $links['test'] = [
          'title' => $this->t('Test'),
          'url' => Url::fromRoute('autoban.test', ['rule' => AutobanUtils::AUTOBAN_FROM_ANALYZE], $query),
        ];

        $row[] = [
          'data' => [
            '#type' => 'operations',
            '#links' => $links,
          ],
        ];

        $rows[] = $row;
      }

      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Bulk add rules'),
        '#suffix' => '<div><small>' . $this->t('Automatic creation autoban rules for checked rows.') . '</small></div>',
      ];
    }

    $form['info'] = [
      '#type' => 'details',
      '#title' => $this->t('Settings info'),
      '#open' => FALSE,
    ];

    $settings_url = Url::fromRoute('autoban.settings');
    $form['info']['title'] = [
      '#markup' => $this->t('<label>Threshold:</label> @threshold <label>Exclude types:</label> @exclude. <a href="@settings">Change</a>', [
        '@threshold' => $threshold_analyze,
        '@exclude' => $dblog_type_exclude_msg,
        '@settings' => $settings_url->toString(),
      ]),
      '#allowed_tags' => ['label', 'a'],
    ];

    $form['analyze_table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $rows,
      '#empty' => $this->t('No data for ban.'),
      '#weight' => 120,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $rules = [];
    foreach ($form_state->getValue('analyze_table') as $key => $value) {
      if ($value != NULL) {
        $rules[$key] = [
          'type' => $form['analyze_table']['#options'][$key][1],
          'message' => $form['analyze_table']['#options'][$key][2],
        ];
      }
    }

    if (!empty($rules)) {
      // Providers list.
      $providers = [];
      $controller = $this->autoban;
      $banManagerList = $controller->getBanProvidersList();
      if (!empty($banManagerList)) {
        foreach ($banManagerList as $id => $item) {
          $providers[$id] = $item['name'];
        }
      }
      else {
        $this->messenger()->addMessage(
          $this->t('List ban providers is empty. You have to enable at least one Autoban providers module.'),
          'warning'
        );
        return;
      }

      $provider = NULL;
      if (count($providers) == 1) {
        $provider = array_keys($providers)[0];
      }
      else {
        $last_provider = $this->config('autoban.settings')->get('autoban_provider_last');
        if ($last_provider && isset($providers[$last_provider])) {
          $provider = $last_provider;
        }
      }
      if (empty($provider)) {
        $provider = array_keys($providers)[0];
      }

      // Threshold.
      $threshold = $this->config('autoban.settings')->get('autoban_threshold_analyze') ?: 5;
      $thresholds_config = $this->config('autoban.settings')->get('autoban_thresholds');
      $thresholds = !empty($thresholds_config) ?
        explode("\n", $thresholds_config)
        : [1, 2, 3, 5, 10, 20, 50, 100];

      if (!in_array($threshold, $thresholds)) {
        $threshold = max(array_filter($thresholds, function ($v) use ($threshold) {
          return $v < $threshold;
        }));
      }

      // Window.
      $window = $this->config('autoban.settings')->get('autoban_window_default') ?: 'none';
      if ($window !== 'none') {
        $windows_config = $this->config('autoban.settings')->get('autoban_windows');
        $windows = !empty($windows_config) ?
          explode("\n", $windows_config)
          : [
            '1 hour ago',
            '1 day ago',
            '1 week ago',
            '1 month ago',
            '1 year ago',
          ];

        if (!in_array($window, $windows)) {
          $windows = array_filter($windows, function ($v) use ($window) {
            return strtotime($v) > strtotime($window);
          });
          $window = array_pop($windows);
        }
      }

      // Create automatic rules.
      foreach ($rules as $key => $value) {
        $value['provider'] = $provider;
        $value['threshold'] = $threshold;
        $value['window'] = $window;
        $value['id'] = "a_" . uniqid();
        $value['rule_type'] = AutobanUtils::AUTOBAN_RULE_AUTO;

        $autoban = $this->entityTypeManager->getStorage('autoban')->create($value);
        $autoban->save();
        $this->messenger()->addMessage($this->t('Create rule %label', ['%label' => $autoban->id()]));
      }

      $this->messenger()->addMessage($this->t('Created rules: @count', ['@count' => count($rules)]));
    }
    else {
      $this->messenger()->addMessage($this->t('No rules for generate'), 'warning');
    }
  }

  /**
   * Get analyze result.
   *
   * @param array $header
   *   Query header.
   * @param int $threshold
   *   Threshold for watchdog entries which added to result.
   * @param string $dblog_type_exclude
   *   Exclude dblog types events for log analyze.
   *
   * @return array
   *   Watchdog table data as query result.
   */
  private function getAnalyzeResult(array $header, $threshold, $dblog_type_exclude) {
    $query = $this->connection->select('watchdog', 'log');
    $query->fields('log', ['message', 'type']);
    $query->addExpression('COUNT(*)', 'cnt');
    $query->condition('log.type', explode("\n", $dblog_type_exclude), 'NOT IN');
    $query->groupBy('log.message');
    $query->groupBy('log.type');
    $query->having('COUNT(*) >= :threshold', [':threshold' => $threshold]);
    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->orderByHeader($header);

    $result = $table_sort->execute()->fetchAll();
    return $result;
  }

}
