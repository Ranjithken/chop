/**
 * @file
 * The performance_profiler behavior.
 */

(function (Drupal, $) {
  'use strict';

  var triggered = false;

  Drupal.behaviors.performanceProfilerToolbar = {
    attach: function (context, settings) {
      if (!triggered) {
        $.ajax({
          url: Drupal.url('performance-profiler/ajax/performance-data'),
          type: 'GET',
          data: {
            path: $('.performance-profiler', context).attr('rel')
          },
          success: function (results) {
            if (results) {
              $('.performance-profiler').html(results);
              $.each(document.getElementsByClassName('performance-profiler'), function (index, el) {
                Drupal.attachBehaviors(el);
              });
            }
          }
        });

        triggered = true;
      }
    }
  };

})(Drupal, jQuery);
