/**
 * @file
 * The performance_profiler behavior.
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.performanceProfiler = {
    attach: function (context, settings) {
      $('.performance-profiler-short div', context).click(function () {
        var $wrapper = $(this).closest('div.performance-profiler-short');
        $wrapper.toggleClass('open');
        $wrapper.next('.performance-profiler-long').toggleClass('hidden');
      });

      $('.performance-profiler-long a.show-details', context).click(function () {
        $('div.db-details:not(.hidden)').remove();
        var $dbDetails = $('div.db-details.hidden', $(this).closest('.db-details-wrapper')).clone();
        $dbDetails.removeClass('hidden').addClass('db-details-shown');
        $('body').append($dbDetails);
        var scrollTop = $(document).height() - $dbDetails.innerHeight();
        $('html, body').animate({ scrollTop: scrollTop - 80 }, 800);
        Drupal.attachBehaviors(document.getElementById('db-details'));
        $('#performance-profiler-dynamic').hide();
      });

      $('.db-details-inner a.hide-details', context).click(function () {
        $(this).closest('div.db-details').remove();
        $('#performance-profiler-dynamic').show();
      });
    }
  };

})(jQuery, Drupal);
