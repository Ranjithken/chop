<?php

namespace Drupal\cirp_profiles\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\node\Entity\Node;

/**
 * Class ProfileAPIController.
 */
class ProfileAPIController extends ControllerBase {

  /**
   * Fetch Profile API.
   *
   * @return string
   *   Return array.
   */
  public function fetchProfileapi() {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://profiles-api.research.chop.edu/profile",
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Basic Qmh3bDV0eUBZJnMzMzVjTjpmVzAqMElAazlkdFZvQ0Bq",
      ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    // Convert JSON object to an array.
    $decoded = Json::decode($response);
    foreach ($decoded as $key => $value) {
      $profiles[$value['person_id']] = $value;
      $person_ids[$key] = $value['person_id'];
    }
    return [
      'profiles' => $profiles,
      'person_ids' => $person_ids,
    ];
  }

  /**
   * Fetch matched nodes wtih API profile ids.
   *
   * @return string
   *   Return array.
   */
  public function getMatchedprofiles($profile_ids = []) {
    if (!empty($profile_ids)) {
      $query = \Drupal::entityQuery('node');
	  $query->accessCheck(FALSE);
      $query->condition('type', 'profile');
      $query->condition('field_profile_id', $profile_ids, 'IN');
      return $query->execute();
    }
    return NULL;
  }

  /**
   * Seperate data to find out which nodes should me updated.
   *
   * @return string
   *   Return array.
   */
  public function seperateData($ids = []) {
    $nodes = Node::loadMultiple($ids);
    $matched = $updated = NULL;
    foreach ($nodes as $node) {
      $matched[$node->id()] = $node->get('field_profile_id')->getString();
      if ($node->get('field_from_api')->getString() == 1) {
        $updated[$node->id()] = $node->get('field_profile_id')->getString();
      }
    }
    return [
      'matched' => $matched,
      'updated' => $updated,
    ];
  }

  /**
   * Update profiiles.
   */
  public function updateProfiles($api_profiles, $node_data) {
    \Drupal::logger('CIRP')->notice('<pre><code>' . print_r($node_data, TRUE) . '</code></pre>');
    foreach ($node_data as $nid => $person_id) {
      $node = Node::load($nid);
      $node->set('title', $api_profiles[$person_id]['display_name']);
      $node->body->value = $api_profiles[$person_id]['bio'];
      $node->body->format = 'full_html';
      $node->set('field_first_name', $api_profiles[$person_id]['drupal_title']);
      $node->set('field_last_name', $api_profiles[$person_id]['last_name']);
      $node->field_short_bio->value = $api_profiles[$person_id]['short_description'];
      $node->field_short_bio->format = 'full_html';
      $node->set('field_external_url', $api_profiles[$person_id]['cta_link']);
      $node->set('field_pubmed_url', $api_profiles[$person_id]['pubmed_url']);
      $node->set('field_email', $api_profiles[$person_id]['email']);
      $node->set('field_phone_number', $api_profiles[$person_id]['phone']);
      $node->field_links_of_interest->value = $api_profiles[$person_id]['links_of_interest'];
	  $node->field_links_of_interest->format = 'full_html';
      $node->field_education_and_training->value = $api_profiles[$person_id]['education_or_training'];
      $node->field_education_and_training->format = 'full_html';
      $node->field_titles_and_academic_titles->value = $api_profiles[$person_id]['titles_and_academic_titles'];
      $node->field_titles_and_academic_titles->format = 'full_html';
      $node->field_professional_memberships->value = $api_profiles[$person_id]['professional_memberships'];
      $node->field_professional_memberships->format = 'full_html';
      $node->field_professional_awards->value = $api_profiles[$person_id]['professional_awards'];
      $node->field_professional_awards->format = 'full_html';
	  $node->field_active_grants_contracts->value = $api_profiles[$person_id]['active_grants_contracts'];
      $node->field_active_grants_contracts->format = 'full_html';
      $node->save();
    }
  }

  /**
   * Create profiiles.
   */
  public function createProfiles($profiles, $api_profiles) {
    foreach ($api_profiles as $person_id) {
      $node = Node::create([
        'type' => 'profile',
        'title' => $profiles[$person_id]['display_name'],
        'field_profile_id' => $person_id,
        'field_first_name' => $profiles[$person_id]['drupal_title'],
        'field_last_name' => $profiles[$person_id]['last_name'],
        'field_short_bio' => ['value' => $profiles[$person_id]['short_description'], 'format' => 'full_html'],
        'field_external_url' => $profiles[$person_id]['cta_link'],
        'field_pubmed_url' => $profiles[$person_id]['pubmed_url'],
        'field_email' => $profiles[$person_id]['email'],
        'field_phone_number' => $profiles[$person_id]['phone'],
        'field_education_and_training' => ['value' => $profiles[$person_id]['education_or_training'], 'format' => 'full_html'],
        'field_links_of_interest' => ['value' => $profiles[$person_id]['links_of_interest'], 'format' => 'full_html'],
        'field_titles_and_academic_titles' => ['value' => $profiles[$person_id]['titles_and_academic_titles'], 'format' => 'full_html'],
        'field_professional_memberships' => ['value' => $profiles[$person_id]['professional_memberships'], 'format' => 'full_html'],
        'field_professional_awards' => ['value' => $profiles[$person_id]['professional_awards'], 'format' => 'full_html'],
		'field_active_grants_contracts' => ['value' => $profiles[$person_id]['active_grants_contracts'], 'format' => 'full_html'],
        'body' => ['value' => $profiles[$person_id]['bio'], 'format' => 'full_html'],
        'field_from_api' => 1,
        'status' => 0,
        'uid' => 1,
      ]);
      $node->save();
    }

  }

}
