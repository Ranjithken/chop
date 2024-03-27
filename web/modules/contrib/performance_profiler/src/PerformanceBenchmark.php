<?php

namespace Drupal\performance_profiler;

/*
// PHP Benchmark Performance Script
// � 2010 Code24 BV
// -
// Author      : Alessandro Torrisi
// Company     : Code24 BV, The Netherlands
// Date        : July 31, 2010
// version     : 1.0.1
// License     : Creative Commons CC-BY license
// Website     : http://www.php-benchmark-script.com.
 */

/**
 * Provides a PerformanceBenchmark Class to run tests.
 */
class PerformanceBenchmark {

  /**
   * Provides test scripts.
   */
  private static function testMath($count = 140000) {
    $time_start = microtime(TRUE);
    $math_functions = [
      "abs",
      "acos",
      "asin",
      "atan",
      "floor",
      "exp",
      "sin",
      "tan",
      "is_finite",
      "is_nan",
      "sqrt",
    ];
    foreach ($math_functions as $key => $function) {
      if (!function_exists($function)) {
        unset($math_functions[$key]);
      }
    }
    for ($i = 0; $i < $count; $i++) {
      foreach ($math_functions as $function) {
        switch ($function) {
          case 'bindec':
            call_user_func($function, $i % 2);
            break;

          case 'pi':
            call_user_func($function);
            break;

          default:
            call_user_func_array($function, [$i]);
        }
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Performs string manipulation tests.
   */
  private static function testStringManipulation($count = 130000) {
    $time_start = microtime(TRUE);
    $string_functions = [
      "addslashes",
      "chunk_split",
      "metaphone",
      "strip_tags",
      "md5",
      "sha1",
      "strtoupper",
      "strtolower",
      "strrev",
      "strlen",
      "soundex",
      "ord",
    ];
    foreach ($string_functions as $key => $function) {
      if (!function_exists($function)) {
        unset($string_functions[$key]);
      }
    }
    $string = "the quick brown fox jumps over the lazy dog";
    for ($i = 0; $i < $count; $i++) {
      foreach ($string_functions as $function) {
        call_user_func_array($function, [$string]);
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Provides test loops.
   */
  private static function testLoops($count = 19000000) {
    $time_start = microtime(TRUE);
    for ($i = 0; $i < $count; ++$i) {
      ;
    }
    $i = 0;
    while ($i < $count) {
      ++$i;
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Provides if/else tests.
   */
  private static function testIfElse($count = 9000000) {
    $time_start = microtime(TRUE);
    for ($i = 0; $i < $count; $i++) {
      if ($i == -1) {
      }
      elseif ($i == -2) {
      }
      else {
        if ($i == -3) {
        }
      }
    }
    return number_format(microtime(TRUE) - $time_start, 3);
  }

  /**
   * Runs the tests.
   */
  public function run(&$return_results = []) {
    $total = 0;
    $methods = get_class_methods(self::class);
    $return = [];
    foreach ($methods as $method) {
      if (preg_match('/^test/', $method)) {
        $total += $result = $this->$method();
        $return_results[$method] = $result;
        $method = str_replace(
          'test',
          '',
          $method);
        $return[] = str_pad($method, 20) . ' : ' . str_pad($result, 5) . "s\n";
      }
    }
    $return_results['total'] = $total;
    $total_message = str_pad("Total time", 20) . ' : ' . str_pad($total, 5) . "s\n";
    array_unshift($return, $total_message);
    return $return;
  }

}
