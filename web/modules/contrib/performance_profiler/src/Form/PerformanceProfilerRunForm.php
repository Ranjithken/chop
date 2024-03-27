<?php

namespace Drupal\performance_profiler\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Renderer;
use Drupal\performance_profiler\PerformanceBenchmark;
use Drupal\performance_profiler\PerformanceDatabaseActions;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Memory profiler module settings.
 *
 * @internal
 */
class PerformanceProfilerRunForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * The performance database actions service.
   *
   * @var \Drupal\performance_profiler\PerformanceDatabaseActions
   */
  protected $dbActions;

  /**
   * The performance benchmark service.
   *
   * @var \Drupal\performance_profiler\PerformanceDatabaseActions
   */
  protected $benchmarkService;

  /**
   * Class construct.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   * @param \Drupal\performance_profiler\PerformanceDatabaseActions $db_actions
   *   The performance database actions service.
   * @param \Drupal\performance_profiler\PerformanceBenchmark $benchmark
   *   The performance benchmark service.
   */
  public function __construct(Connection $database, MessengerInterface $messenger, Renderer $renderer, PerformanceDatabaseActions $db_actions, PerformanceBenchmark $benchmark) {
    $this->database = $database;
    $this->messenger = $messenger;
    $this->renderer = $renderer;
    $this->dbActions = $db_actions;
    $this->benchmarkService = $benchmark;
  }

  /**
   * Factory method for dependency injection container.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container.
   *
   * @return static
   *   Return static.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('messenger'),
      $container->get('renderer'),
      $container->get('performance_profiler.database_actions'),
      $container->get('performance_profiler.benchmark')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'performance_profiler_run';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div id="result-message-php"></div><div id="result-message-db"></div>',
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['bench_php'] = [
      '#type' => 'button',
      '#value' => $this->t('Run PHP benchmark'),
      '#ajax' => [
        'callback' => '::benchmarkPhpAjax',
      ],
    ];

    $form['actions']['bench_database'] = [
      '#type' => 'button',
      '#value' => $this->t('Run Database benchmark'),
      '#ajax' => [
        'callback' => '::benchmarkDbAjax',
      ],
    ];

    return $form;
  }

  /**
   * Submit handler for PHP benchmark AJAX.
   */
  public function benchmarkPhpAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $result = [];
    $this->benchmarkService->run($result);
    $markup = [
      '#theme' => 'performance_profiler_benchmark_php',
      '#value' => $result,
    ];
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        '#result-message-php',
        $this->renderer->render($markup)
      )
    );
    return $response;
  }

  /**
   * Submit handler for Database benchmark AJAX.
   */
  public function benchmarkDbAjax(array &$form, FormStateInterface $form_state): ?AjaxResponse {
    return $this->dbActions->run(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
