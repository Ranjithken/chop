<?php

namespace Drupal\performance_profiler;

use Drupal\Component\Utility\Timer;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\Renderer;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides a PerformanceDatabaseActions service.
 */
class PerformanceDatabaseActions {

  use StringTranslationTrait;

  /**
   * Table prefix.
   *
   * @var string
   */
  public const TABLE_PREFIX = 'performance_profiler_db_test_';

  /**
   * Available query types.
   *
   * @var array
   */
  public const QUERY_TYPES = [
    'select',
    'select_sort',
    'select_sort_no_index',
    'select_like',
    'join',
    'join_two',
  ];

  /**
   * Collecting total time for all requests executed.
   *
   * @var float
   */
  private $totalTime = 0;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Logger channel instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Messenger instance.
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
   * Class construct.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   State interface service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   Link generator service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer service.
   */
  public function __construct(Connection $database, LoggerChannelFactoryInterface $logger, MessengerInterface $messenger, Renderer $renderer) {
    $this->database = $database;
    $this->logger = $logger->get('performance_profiler_db');
    $this->messenger = $messenger;
    $this->renderer = $renderer;
  }

  /**
   * Execute selected query.
   *
   * @param string $query_type
   *   The query type.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  public function runQuery(string $query_type, bool $is_ajax): array {
    try {
      $main_table = self::TABLE_PREFIX . 'main';
      $ref_by_uuid_table = self::TABLE_PREFIX . 'ref_by_uuid';
      $ref_by_id_table = self::TABLE_PREFIX . 'ref_by_id';

      if (strpos($query_type, 'select') === 0) {
        $query = "SELECT * FROM {$main_table}";
        switch ($query_type) {
          case 'select_sort':
            $query .= ' ORDER BY field_varchar';
            break;

          case 'select_sort_no_index':
            $query .= ' ORDER BY field_long_text';
            break;

          case 'select_like':
            $query .= " WHERE field_long_text LIKE '%ab%'";
            break;

          case 'select_group_by':
            $query .= " GROUP BY field_tiny_int";
            break;
        }

        if ($query_type != 'select') {
          $query .= ' LIMIT 1000';
        }
      }

      if ($query_type == 'join') {
        $query = "SELECT m.* FROM `{$main_table}` m JOIN `{$ref_by_uuid_table}` r ON r.field_uuid = m.field_uuid";
      }
      elseif ($query_type == 'join_two') {
        $query = "SELECT m.* FROM `{$main_table}` m JOIN `{$ref_by_uuid_table}` r ON r.field_uuid = m.field_uuid JOIN `{$ref_by_id_table}` ri ON ri.id = m.id";
      }

      $timer = 'mysql_benchmark_run_query_' . $query_type;
      $this->startTimer($timer);
      if (!empty($query)) {
        $this->database->query($query)->fetchAll();
      }

      return $this->stopAndLogTimer($timer, $is_ajax, "Query {$query_type}");
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query {$query_type}, {$e->getMessage()}");
    }

    return [
      'message' => '',
      'time' => 0,
    ];
  }

  /**
   * Helper function to create mySQL table.
   *
   * @param string $table
   *   The table id.
   * @param array $specification
   *   The table specification.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  public function createTable(string $table, array $specification, bool $is_ajax): array {
    try {
      $table_name = self::TABLE_PREFIX . $table;

      $columns_string = [];
      foreach ($specification['columns'] as $id => $column) {
        $columns_string[] .= "`{$id}` {$column}";
      }
      $columns_string = implode(', ', $columns_string);

      $query = "CREATE TABLE IF NOT EXISTS `{$table_name}` ";
      $query .= "({$columns_string}";
      if (!empty($specification['primary_key'])) {
        array_walk($specification['primary_key'], function ($value) {
          return "`{$value}`";
        });

        $primary_key = implode(', ', $specification['primary_key']);
        $query .= ", PRIMARY KEY ({$primary_key})";
      }
      if (!empty($specification['unique_key'])) {
        $column = array_key_first($specification['unique_key']);
        $alias = array_pop($specification['unique_key']);
        $query .= ", UNIQUE KEY `{$table_name}_{$column}` (`{$alias}`)";
      }
      if (!empty($specification['unique_key'])) {
        $column = array_key_first($specification['key']);
        $alias = array_pop($specification['key']);
        $query .= ", KEY `{$table_name}_{$column}` (`{$alias}`)";
      }
      $query .= ") ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

      $timer = 'mysql_create_benchmark_' . $table_name;
      $this->startTimer($timer);
      $this->database->query($query);
      return $this->stopAndLogTimer($timer, $is_ajax, "Create {$table_name}");
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query, {$e->getMessage()}");
    }

    return [
      'message' => '',
      'time' => 0,
    ];
  }

  /**
   * Helper function to truncate mySQL table.
   *
   * @param string $table
   *   The table id.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  public function truncateTable(string $table, bool $is_ajax): array {
    try {
      $table_name = self::TABLE_PREFIX . $table;

      $timer = 'mysql_drop_benchmark_' . $table_name;
      $this->startTimer($timer);
      $this->database->query("TRUNCATE TABLE `{$table_name}`");
      return $this->stopAndLogTimer($timer, $is_ajax, "Truncate {$table_name}");
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query, {$e->getMessage()}");
    }

    return [
      'message' => '',
      'time' => 0,
    ];
  }

  /**
   * Helper function to drop mySQL table.
   *
   * @param string $table
   *   The table id.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  public function dropTable(string $table, bool $is_ajax): array {
    try {
      $table_name = self::TABLE_PREFIX . $table;

      $timer = 'mysql_drop_benchmark_' . $table_name;
      $this->startTimer($timer);
      $this->database->query("DROP TABLE `{$table_name}`");
      return $this->stopAndLogTimer($timer, $is_ajax, "Drop {$table_name}");
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query, {$e->getMessage()}");
    }

    return [
      'message' => '',
      'time' => 0,
    ];
  }

  /**
   * Helper function to insert into mySQL table.
   *
   * @param string $table
   *   The table id.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   * @param string $insert_type
   *   The insert type.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  public function fillTable(string $table, bool $is_ajax, string $insert_type = 'one_operation'): array {
    try {
      $table_name = self::TABLE_PREFIX . $table;

      if ($this->database->query("SELECT id FROM {$table_name} LIMIT 1")->fetchField()) {
        $this->messenger->addWarning("Skipped table {$table_name}, because it already has values.");
        return 0;
      }

      $timer = 'mysql_fill_generation_benchmark';
      $this->startTimer($timer);
      $query_values = $this->generateValues($table, $insert_type == 'one_operation');
      $columns = implode(', ', array_keys(self::tables()[$table]['columns']));

      if ($insert_type == 'one_operation') {
        $this->database->query("INSERT INTO `{$table_name}` ({$columns}) VALUES {$query_values}");
      }
      elseif (!empty($query_values) && is_array($query_values)) {
        if ($insert_type == 'transaction') {
          $transaction = $this->database->startTransaction();
        }
        try {
          foreach ($query_values as $values) {
            $this->database->query("INSERT INTO `{$table_name}` ({$columns}) VALUES {$values}");
          }
        }
        catch (\Exception $exception) {
          if ($insert_type == 'transaction') {
            $transaction->rollBack();
          }
        }
        if ($insert_type == 'transaction') {
          unset($transaction);
        }
      }
      return $this->stopAndLogTimer($timer, $is_ajax, "Generate and insert {$table_name}");
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query, {$e->getMessage()}");
    }

    return [
      'message' => '',
      'time' => 0,
    ];
  }

  /**
   * Profile list of tables and their specification.
   */
  public static function tables(): array {
    $field_types = [
      'auto_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
      'int' => "int unsigned DEFAULT NULL",
      'varchar' => "varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL COMMENT 'Field varchar comment.'",
      'varchar_uuid' => "varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL",
      'varchar_255' => "varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL",
      'long_text' => "longtext NOT NULL",
      'tiny_int' => "tinyint NOT NULL DEFAULT '0'",
    ];

    return [
      'main' => [
        'columns' => [
          'id' => $field_types['auto_id'],
          'field_int' => $field_types['int'],
          'field_varchar' => $field_types['varchar'],
          'field_uuid' => $field_types['varchar_uuid'],
          'field_long_text' => $field_types['long_text'],
          'field_tiny_int' => $field_types['tiny_int'],
        ],
        'primary_key' => ['id'],
        'unique_key' => ['field__uuid__value' => 'field_uuid'],
        'key' => ['field__varchar' => 'field_varchar'],
      ],
      'ref_by_uuid' => [
        'columns' => [
          'id' => $field_types['auto_id'],
          'field_uuid' => $field_types['varchar_uuid'],
          'field_varchar' => $field_types['varchar'],
          'field_varchar_2' => $field_types['varchar_255'],
        ],
        'primary_key' => ['id'],
        'unique_key' => ['field__uuid__value' => 'field_uuid'],
        'key' => ['field__varchar' => 'field_varchar'],
      ],
      'ref_by_id' => [
        'columns' => [
          'id' => $field_types['auto_id'],
          'field_uuid' => $field_types['varchar_uuid'],
          'field_varchar' => $field_types['varchar_255'],
        ],
        'primary_key' => ['id'],
      ],
    ];
  }

  /**
   * Start execution time tracking.
   *
   * @param string $timer_machine_name
   *   Unique machine name under which to register the timer.
   */
  private function startTimer(string $timer_machine_name): void {
    Timer::start($timer_machine_name);
  }

  /**
   * Stop started execution timer.
   *
   * @param string $timer_machine_name
   *   Unique machine name under which timer is registered.
   * @param bool $is_ajax
   *   Function used in AJAX or not.
   * @param string $additional_message
   *   Additional text attached to message.
   *
   * @return array
   *   The "time" value in seconds, rounded to 3 decimal digits and "message".
   */
  private function stopAndLogTimer(string $timer_machine_name, bool $is_ajax, string $additional_message = ''): array {
    Timer::stop($timer_machine_name);

    $run_time = round(Timer::read($timer_machine_name) / 1000, 3);
    $this->totalTime += $run_time;
    $message = str_pad($additional_message, 60) . ' : ' . str_pad($run_time, 5) . 's';

    return [
      'message' => $message,
      'time' => $run_time,
    ];
  }

  /**
   * Generate UUID.
   *
   * @return string|void
   *   Random UUID.
   *
   * @throws \Exception
   */
  private function guidv4($id) {
    // Convert current number to 16 bytes (128 bits).
    $data = decbin($id);
    if (strlen($data) < 16) {
      $data = str_repeat('0', 16 - strlen($data)) . $data;
    }
    assert(strlen($data) == 16);

    // Set version to 0100.
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

    // Set bits 6-7 to 10.
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }

  /**
   * Generate random string.
   *
   * @param int $length
   *   Length of string, default 30.
   *
   * @return false|string
   *   Generated random sting.
   */
  private function generateRandomString(int $length = 30) {
    return substr(
      str_shuffle(
        str_repeat(
          $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ',
          ceil($length / strlen($x))
        )
      ),
      1,
      $length
    );
  }

  /**
   * Generate mySQL test table insert values.
   *
   * @param string $table
   *   The table, for which to generate values.
   * @param bool $one_operation
   *   If FALSE all generated data will be returned as an array.
   * @param int $number_of_rows
   *   Number of rows to generate, default 10000, max 65535.
   * @param int $start
   *   Start from exact row number.
   *
   * @return mixed
   *   Generated insert string or array of values.
   *
   * @throws \Exception
   */
  private function generateValues(string $table, bool $one_operation = TRUE, int $number_of_rows = 10000, int $start = 1) {
    $data = [];

    if ($number_of_rows > 65535) {
      $number_of_rows = 65535;
    }

    for ($id = $start; $id <= $number_of_rows; $id++) {
      $uuid = $this->guidv4($id);
      switch ($table) {
        case 'ref_by_uuid':
          $format = "(%d, '%s', '%s', '%s')";
          $values = [
            $id,
            $uuid,
            $this->generateRandomString(),
            $this->generateRandomString(255),
          ];
          break;

        case 'ref_by_id':
          $format = "(%d, '%s', '%s')";
          $values = [
            $id,
            $uuid,
            $this->generateRandomString(255),
          ];
          break;

        default:
          $format = "(%d, %d, '%s', '%s', '%s', %d)";
          $values = [
            $id,
            abs(round(cos($id) * 100)),
            $this->generateRandomString(),
            $uuid,
            $this->generateRandomString(400),
            $id % 2,
          ];
      }

      $data[] = sprintf($format, ...$values);
    }

    return $one_operation ? implode(',', $data) : $data;
  }

  /**
   * Print message about memory usage.
   */
  public function memoryUsage($include_memory = FALSE): void {
    $message = str_pad($this->t('Total time'), 60) . ' : ' . str_pad($this->totalTime, 5) . ' sec.';

    if ($include_memory) {
      $memory_peak = round(
        memory_get_peak_usage() / 1024 / 1024,
        2
      );
      $message .= ' ' . $this->t('Memory peak usage: @memory Mb.', [
        '@memory' => $memory_peak,
      ]);
    }

    $this->messenger->addStatus(Markup::create($message));
  }

  /**
   * Submit handler for Database benchmark AJAX.
   */
  public function run(bool $is_ajax = FALSE) {
    try {
      $result = $messages = [];
      $total = 0;
      $tables = PerformanceDatabaseActions::tables();
      $table_ids = array_keys($tables);
      // Drop tables if exists.
      $phase = 'initial_drop';
      foreach ($table_ids as $table) {
        $table_name = PerformanceDatabaseActions::TABLE_PREFIX . $table;
        if ($this->database->schema()->tableExists($table_name)) {
          $output = $this->dropTable($table, $is_ajax);
          if (!$is_ajax) {
            $messages[] = $output['message'];
          }
        }
      }

      $phase = 'create_tables';
      $result[$phase] = 0;
      // Create tables if not exists.
      foreach ($tables as $table => $specification) {
        $output = $this->createTable($table, $specification, $is_ajax);
        if (!$is_ajax) {
          $messages[] = $output['message'];
        }
        else {
          $result[$phase] += $output['time'];
        }
      }
      $total += $result[$phase];

      $phase = 'fill_tables';
      $result[$phase] = 0;
      // Fill tables if not exists.
      $table_ids = array_keys($tables);
      foreach ($table_ids as $table) {
        $output = $this->fillTable($table, $is_ajax);
        if (!$is_ajax) {
          $messages[] = $output['message'];
        }
        else {
          $result[$phase] += $output['time'];
        }
      }
      $total += $result[$phase];

      $phase = 'run_queries';
      $result[$phase] = 0;
      // Run queries.
      foreach (PerformanceDatabaseActions::QUERY_TYPES as $query_type) {
        $output = $this->runQuery($query_type, $is_ajax);
        if (!$is_ajax) {
          $messages[] = $output['message'];
        }
        else {
          $result[$phase] += $output['time'];
        }
      }
      $total += $result[$phase];

      $phase = 'drop_tables';
      $result[$phase] = 0;
      // Drop tables at the end.
      $table_ids = array_keys($tables);
      foreach ($table_ids as $table) {
        $output = $this->dropTable($table, $is_ajax);
        if (!$is_ajax) {
          $messages[] = $output['message'];
        }
        else {
          $result[$phase] += $output['time'];
        }
      }
      $total += $result[$phase];
      $result['total'] = $total;
      if (!$is_ajax) {
        $this->memoryUsage();
        return $messages;
      }
      else {
        $markup = [
          '#theme' => 'performance_profiler_benchmark_db',
          '#value' => $result,
        ];
        $response = new AjaxResponse();
        $markup = $this->renderer->executeInRenderContext(new RenderContext(), function () use ($markup) {
          return $this->renderer->render($markup, TRUE);
        });
        $response->addCommand(
          new HtmlCommand(
            '#result-message-db',
            $markup
          )
        );
        return $response;
      }
    }
    catch (\Exception $e) {
      $this->messenger->addWarning("Failed executing query (phase: {$phase}), {$e->getMessage()}");
    }
  }

  /**
   * Provides list of titles for available query options.
   *
   * @return array
   *   The titles of available queries.
   */
  public static function getQueryTitles():array {
    return [
      'select' => t('Select all items'),
      'select_sort' => t('Select 1000 items, sorted by indexed column'),
      'select_sort_no_index' => t('Select 1000 items, sorted by non-indexed column'),
      'select_like' => t('Select 1000 items using LIKE "%ab%" condition'),
      'select_group_by' => t('Select 1000 items with group by'),
      'join' => t('Select all items using join by uuid'),
      'join_two' => t('Select all items using two joined tables'),
    ];
  }

}
