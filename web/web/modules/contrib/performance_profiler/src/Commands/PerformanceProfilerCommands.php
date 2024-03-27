<?php

namespace Drupal\performance_profiler\Commands;

use Drupal\Core\Messenger\Messenger;
use Drupal\performance_profiler\PerformanceDatabaseActions;
use Drush\Commands\DrushCommands;
use Drupal\performance_profiler\PerformanceBenchmark;

/**
 * Provides drush commands necessary for Performance profiler module.
 */
class PerformanceProfilerCommands extends DrushCommands {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * The performance benchmark service.
   *
   * @var \Drupal\performance_profiler\PerformanceBenchmark
   */
  protected $benchmarkService;

  /**
   * The performance database actions service.
   *
   * @var \Drupal\performance_profiler\PerformanceBenchmark
   */
  protected $dbActions;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   The messenger service.
   * @param \Drupal\performance_profiler\PerformanceBenchmark $benchmark
   *   The performance benchmark service.
   * @param \Drupal\performance_profiler\PerformanceDatabaseActions $db_actions
   *   The performance database actions service.
   */
  public function __construct(
    Messenger $messenger,
    PerformanceBenchmark $benchmark,
    PerformanceDatabaseActions $db_actions
  ) {
    parent::__construct();
    $this->messenger = $messenger;
    $this->benchmarkService = $benchmark;
    $this->dbActions = $db_actions;
  }

  /**
   * Performance profiler run PHP benchmark.
   *
   * @command pp-php
   * @aliases pp-php
   */
  public function benchmark() {
    foreach ($this->benchmarkService->run() as $value) {
      $this->messenger->addMessage($value);
    }
  }

  /**
   * Performance profiler run Database actions.
   *
   * @command pp-db
   * @aliases pp-db
   */
  public function benchmarkDatabase() {
    foreach ($this->dbActions->run() as $value) {
      $this->messenger->addMessage($value);
    }
  }

}
