/**show-details
 * @file
 * The performance_profiler behavior.
 */

 (function (Drupal, $, drupalSettings) {
    'use strict';

    var triggered = false;

    Drupal.behaviors.performanceProfilerDynamic = {
      attach: function (context, settings) {
        if (!triggered) {
          $("<div>", {
            id: "performance-profiler-dynamic",
            rel: drupalSettings.performanceProfiler.path,
            content: 'Updating...'
          }).appendTo('body');

          $.ajax({
            url: Drupal.url('performance-profiler/ajax/performance-data'),
            type: 'GET',
            data: {
              path: drupalSettings.performanceProfiler.path
            },
            success: function (results) {
              if (results) {
                $('#performance-profiler-dynamic').html(results);
                Drupal.attachBehaviors(document.getElementById('performance-profiler-dynamic'));
              }
            }
          });

          triggered = true;
        }
      }
    };

  })(Drupal, jQuery, drupalSettings);
