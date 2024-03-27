<?php

namespace Drupal\performance_profiler\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\performance_profiler\PerformanceDatabaseActions;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Database benchmark form.
 */
class PerformanceProfilerDbForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Performance profiler Database actions service.
   *
   * @var \Drupal\performance_profiler\PerformanceDatabaseActions
   */
  protected $dbActions;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'performance_profiler_database';
  }

  /**
   * Class construct.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   State interface service.
   * @param \Drupal\performance_profiler\PerformanceDatabaseActions $db_actions
   *   Performance profiler Database actions service.
   */
  public function __construct(Connection $database, PerformanceDatabaseActions $db_actions) {
    $this->database = $database;
    $this->dbActions = $db_actions;
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
      $container->get('performance_profiler.database_actions'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Make sure we do not accidentally cache this form.
    $form['#cache']['max-age'] = 0;

    $form['benchmarks'] = [
      '#type' => 'container',
    ];

    $form['benchmarks']['action'] = [
      '#type' => 'select',
      '#title' => $this->t('Action'),
      '#required' => TRUE,
      '#options' => [
        'create' => $this->t('Create table'),
        'fill' => $this->t('Fill table'),
        'query' => $this->t('Run query'),
        'truncate' => $this->t('Truncate table'),
        'drop' => $this->t('Drop table'),
      ],
      '#empty_option' => $this->t('- Select action -'),
    ];

    $tables = PerformanceDatabaseActions::tables();

    $tables = array_keys($tables);
    $tables = array_combine($tables, $tables);
    $tables_to_operate = $tables;
    foreach ($tables_to_operate as $table => $label) {
      $table_name = PerformanceDatabaseActions::TABLE_PREFIX . $table;
      $tables_to_operate[$table] = $table_name;
      $tables[$table] = $table_name;
      if (!$this->database->schema()->tableExists($table_name)) {
        unset($tables_to_operate[$table]);
      }
      else {
        unset($tables[$table]);
      }
    }

    $form['benchmarks']['tables_create'] = [
      '#type' => 'item',
      '#markup' => $this->t('All tables already created.'),
      '#states' => [
        'visible' => [
          [':input[name="action"]' => ['value' => 'create']],
        ],
      ],
    ];

    if (!empty($tables)) {
      unset($form['benchmarks']['tables_create']['#markup']);
      $form['benchmarks']['tables_create']['#type'] = 'checkboxes';
      $form['benchmarks']['tables_create']['#title'] = $this->t('Tables');
      $form['benchmarks']['tables_create']['#options'] = $tables;
    }

    $form['benchmarks']['tables'] = [
      '#type' => 'item',
      '#markup' => $this->t('There are no created tables, please run create action first.'),
      '#states' => [
        'visible' => [
          [':input[name="action"]' => ['value' => 'fill']],
          [':input[name="action"]' => ['value' => 'truncate']],
          [':input[name="action"]' => ['value' => 'drop']],
        ],
      ],
    ];

    if (!empty($tables_to_operate)) {
      unset($form['benchmarks']['tables']['#markup']);
      $form['benchmarks']['tables']['#type'] = 'checkboxes';
      $form['benchmarks']['tables']['#title'] = $this->t('Tables');
      $form['benchmarks']['tables']['#options'] = $tables_to_operate;

      $form['benchmarks']['cycle_insert'] = [
        '#type' => 'radios',
        '#title' => $this->t('Insert type'),
        '#options' => [
          'one_operation' => $this->t('Insert values by one operation'),
          'cycle' => $this->t('Insert values in cycle one by one'),
          'transaction' => $this->t('Insert values in cycle one by one using transaction'),
        ],
        '#default_value' => 'one_operation',
        '#states' => [
          'visible' => [
            [':input[name="action"]' => ['value' => 'fill']],
          ],
        ],
      ];
    }

    $query_titles = PerformanceDatabaseActions::getQueryTitles();
    $form['benchmarks']['query'] = [
      '#type' => 'select',
      '#title' => $this->t('Query'),
      '#options' => [
        'all' => $this->t('Run all'),
        'Main table operations' => [
          'select' => $query_titles['select'],
          'select_sort' => $query_titles['select_sort'],
          'select_sort_no_index' => $query_titles['select_sort_no_index'],
          'select_like' => $query_titles['select_like'],
          'select_group_by' => $query_titles['select_group_by'],
        ],
        'Several tables operations' => [
          'join' => $query_titles['join'],
          'join_two' => $query_titles['join_two'],
        ],
      ],
      '#states' => [
        'visible' => [
          [':input[name="action"]' => ['value' => 'query']],
        ],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['execute'] = [
      '#type' => 'submit',
      '#value' => $this->t('Execute'),
      '#button_type' => 'primary',
    ];

    if ($form_state->get('results')) {
      $form['results'] = $form_state->get('results');
    }

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $result = [];
    $action = $form_state->getValue('action');

    $action_title = $form['benchmarks']['action']['#options'][$action];
    if (is_object($action_title)) {
      $action_title = $action_title->__toString();
    }

    switch ($action) {
      case 'create':
        $tables = $form_state->getValue('tables_create');
        if (!empty($tables) && is_array($tables)) {
          $tables = array_filter($tables);
        }
        if (!empty($tables)) {
          $specifications = PerformanceDatabaseActions::tables();
          foreach ($tables as $table => $value) {
            $output = $this->dbActions->createTable($table, $specifications[$table], TRUE);
            $result[$action_title][$table] = $output['time'];
          }
        }
        break;

      case 'truncate':
      case 'drop':
      case 'fill':
        $tables = $form_state->getValue('tables');
        if (!empty($tables) && is_array($tables)) {
          $tables = array_filter($tables);
        }
        if (!empty($tables)) {
          if ($action == 'fill') {
            $one_operation = $form_state->getValue('cycle_insert');
          }
          $callback = $action . 'Table';
          foreach ($tables as $table => $value) {
            $output = $this->dbActions->{$callback}($table, TRUE, $one_operation ?? NULL);
            $result[$action_title][$table] = $output['time'];
          }
        }
        break;

      case 'query':
        $query_type = $form_state->getValue('query');
        $query_titles = PerformanceDatabaseActions::getQueryTitles();
        if ($query_type == 'all') {
          foreach (PerformanceDatabaseActions::QUERY_TYPES as $query_type) {
            $query_title = $query_titles[$query_type];
            if (is_object($query_title)) {
              $query_title = $query_title->__toString();
            }
            $output = $this->dbActions->runQuery($query_type, TRUE);
            $result[$action_title][$query_title] = $output['time'];
          }
        }
        else {
          $query_title = $query_titles[$query_type];
          if (is_object($query_title)) {
            $query_title = $query_title->__toString();
          }
          $output = $this->dbActions->runQuery($query_type, TRUE);
          $result[$action_title][$query_title] = $output['time'];
        }
        break;
    }

    $this->dbActions->memoryUsage(TRUE);

    $markup = [
      '#theme' => 'performance_profiler_benchmark_db_actions',
      '#value' => $result,
    ];

    $form_state->set('results', $markup);
    $form_state->setRebuild();
  }

}
