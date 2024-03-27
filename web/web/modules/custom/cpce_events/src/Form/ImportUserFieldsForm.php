<?php

namespace Drupal\cpce_events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cpce_events\Controller\EventsAPIController;

/**
 * ImportUserFieldsForm File Doc Comment.
 *
 * @category ImportUserFieldsForm
 */
class ImportUserFieldsForm extends FormBase {

  /**
   * Form ID.
   */
  public function getFormId() {
    return 'import_user_fields';
  }

  /**
   * Import user form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['import_events'] = [
      '#type' => 'submit',
      '#value' => $this->t('Sync Events from the Events API'),
    ];
    return $form;
  }

  /**
   * Submit Form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $api = new EventsAPIController();
    $event_data = $api->fetchEventsapi();
    $event_ids = $event_data['event_ids'];
    $events = $event_data['events'];
    $data = $api->getMatchedevents($event_ids);
    $matched = [];
    $tobe_updated = NULL;
    if ($data != NULL) {
      $seperated_data = $api->seperateData($data);
      $matched = $seperated_data['matched'];
      $tobe_updated = $seperated_data['updated'];
    }
    $tobe_inserted = array_diff($event_ids, $matched);
    $chunk_count = 50;
    $chunks = array_chunk($tobe_inserted, $chunk_count, TRUE);
    $num_operations = count($tobe_inserted);
    $batch = [
      'title' => t('Importing CPCE Events...'),
      'operations' => [],
      'init_message'     => t('Commencing'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\cpce_events\ImportFields::importUserFieldsFinishedCallback',
    ];
    foreach ($chunks as $chunk) {
      $batch['operations'][] = ['\Drupal\cpce_events\ImportFields::importUserFields', [$events, $chunk,
      [
        'max' => $num_operations,
        'chunk' => $chunk_count,
      ],
      ],
      ];
    }
    if ($tobe_updated != NULL) {
      $num_update_operations = count($tobe_updated);
      $update_chunks = array_chunk($tobe_updated, $chunk_count, TRUE);
      foreach ($update_chunks as $update_chunk) {
        $batch['operations'][] = ['\Drupal\cpce_events\ImportFields::updateUserFields', [$events, $update_chunk,
        [
          'max' => $num_update_operations,
          'chunk' => $chunk_count,
        ],
        ],
        ];
      }
    }

    batch_set($batch);
  }

}
