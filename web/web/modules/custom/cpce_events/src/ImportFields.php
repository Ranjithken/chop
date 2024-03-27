<?php

namespace Drupal\cpce_events;

use Drupal\cpce_events\Controller\EventsAPIController;

/**
 * To import fields from Profile API.
 */
class ImportFields {

  /**
   * To import custom user fields from user profile (HCT).
   */
  public static function importUserFields($events, $tobe_inserted, $context) {
    $message = 'Importing Events Data...';
    $api = new EventsAPIController();
    $api->createEvents($events, $tobe_inserted);
    $context['message'] = $message;
  }

  /**
   * To update user fields from user profile.
   */
  public static function updateUserFields($events, $tobe_updated, $context) {
    $message = 'Updating Events Data...';
    $api = new EventsAPIController();
    $api->updateEvents($events, $tobe_updated);
    $context['message'] = $message;
  }

  /**
   * Finish callback (HCT).
   */
  public static function importUserFieldsFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addStatus($message);
  }


}
