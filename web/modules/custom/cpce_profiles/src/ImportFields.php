<?php

namespace Drupal\cpce_profiles;

use Drupal\cpce_profiles\Controller\ProfileAPIController;

/**
 * To import fields from Profile API.
 */
class ImportFields {

  /**
   * To import custom user fields from user profile (HCT).
   */
  public static function importUserFields($profiles, $tobe_inserted, $context) {
    $message = 'Importing User Data...';
    $api = new ProfileAPIController();
    $api->createProfiles($profiles, $tobe_inserted);
    $context['message'] = $message;
  }

  /**
   * To update user fields from user profile.
   */
  public static function updateUserFields($profiles, $tobe_updated, $context) {
    $message = 'Updating User Data...';
    $api = new ProfileAPIController();
    $api->updateProfiles($profiles, $tobe_updated);
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
