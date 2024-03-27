<?php

namespace Drupal\cpce_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;
use Drupal\node\Entity\Node;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\file\Entity\File;

/**
 * Class EventsAPIController.
 */
class EventsAPIController extends ControllerBase {

  /**
   * Fetch Events API.
   *
   * @return string
   *   Return array.
   */
  public function fetchEventsapi() {
  // Source for Events Data  
	$url = "https://www.research.chop.edu/export/clinical-futures-events";
  // Load XML File
  $xml = simpleXML_load_file($url,"SimpleXMLElement",LIBXML_NOCDATA);
  // Decode XML File Data
	$response = json_decode(json_encode($xml), true);
//  echo"<pre>";print_r($response);"</pre>";exit;

	// Extract Events ids and events data from the decoded response
	foreach ($response['item'] as $key => $value) {

      $events[$value['nid']] = $value;
      $event_ids[$key] = $value['nid'];
	
	}
	
    return [
      'event_ids' => $event_ids,
	  'events' => $events,
    ];
  // echo"<pre>";print_r($event_ids);"</pre>";exit;
  }

  /**
   * Fetch matched nodes wtih API events ids.
   *
   * @return string
   *   Return array.
   */
  public function getMatchedevents($event_ids = []) {
    if (!empty($event_ids)) {
      $query = \Drupal::entityQuery('node');
      $query->accessCheck(TRUE);
      $query->condition('type', 'events');
      $query->condition('field_event_id', $event_ids, 'IN');
      return $query->execute();
    }
    return NULL;
  }

  /**
   * Seperate data to find out which nodes(events) should be updated.
   *
   * @return string
   *   Return array.
   */
  public function seperateData($ids = []) {
    $nodes = Node::loadMultiple($ids);
    $matched = $updated = null;
    foreach ($nodes as $node) {
      $matched[$node->id()] = $node->get('field_event_id')->getString();
      if ($node->get('field_from_api')->getString() == 1) {
        $updated[$node->id()] = $node->get('field_event_id')->getString();
      }
    }
    return [
      'matched' => $matched,
      'updated' => $updated,
    ];
  }

  /**
   * Update events.
   */

   public function updateEvents($api_events, $node_data) {

    foreach ($node_data as $nid => $event_id) {

      // XML Date = "November 3, 2021 - 12:00 pm to 1:00 pm"
      // convert the XML Date to fomatted date (Y-m-d\TH:i:s)
      // Output = array("event_start_date_time" =>"Y-m-d\TH:i:s", "event_end_date_time" => "Y-m-d\TH:i:s")
      $event_date_times = $this->getEventStartEndTime($api_events[$event_id]['field_event_end_time']);

      // Seperate Link and Text
      // <field_registration_link> Input XML DATA = "Virtual BlueJeans Meeting https://bluejeans.com/528917057"
      // Output = array("link_text" =>"Virtual BlueJeans Meeting", "register_link" => "https://bluejeans.com/528917057")

      if (count($api_events[$event_id]['field_registration_link']) != 0) {

       $register_link = $this->seperateLinkText($api_events[$event_id]['field_registration_link']);

      }else {

        $register_link['link_text'] = $register_link['register_link'] = null;

      }
      // Event Detailed Image
      if (!empty($api_events[$event_id]['field_image'])) {
        // Process XML Image data and get deatils for file processing
        // XML DATA = <img src="/sites/default/files/2021-07/07_16_21_CPCE_FEATURED.jpg" width="350" height="200" alt="Clinical Futures Seminar Series With David Weber, MD, MSCE" title="Clinical Futures Seminar Series With David Weber, MD, MSCE" loading="lazy" typeof="foaf:Image" />
        // Output = array("img_title" =>"Image Title", "alt_title" => "Image alt title", "img_url"=> "https://www.research.chop.edu/sites/default/files/")
        $image_details = $this->getImageDetails($api_events[$event_id]['field_image']);
  
        if (count($image_details) != 0) {
          // Process Image deatils and create an file entry in drupal
          // Returns File ID after processing
          $image_file_details = $this->createFile($image_details);
          
        }
      }
      // Event Featured Image( Post Thumbnail)
      if (!empty($api_events[$event_id]['field_featured_image'])) {
        // Process XML Image data and get deatils for file processing
        // XML DATA = <img src="/sites/default/files/2021-07/07_16_21_CPCE_FEATURED.jpg" width="350" height="200" alt="Clinical Futures Seminar Series With David Weber, MD, MSCE" title="Clinical Futures Seminar Series With David Weber, MD, MSCE" loading="lazy" typeof="foaf:Image" />
        // Output = array("img_title" =>"Image Title", "alt_title" => "Image alt title", "img_url"=> "https://www.research.chop.edu/sites/default/files/")
        $image_postthumbnail_details = $this->getImageDetails($api_events[$event_id]['field_featured_image']);
  
        if (count($image_postthumbnail_details) != 0) {
          // Process Image deatils and create an file entry in drupal
          // Returns File ID after processing
          $thumb_file_details = $this->createFile($image_postthumbnail_details);
  
        }
          
      }
      
      // Update Node (type = "events") Details
      $node = Node::load($nid);
      $node->set('title', $api_events[$event_id]['title']);
      $node->field_short_description->value = $api_events[$event_id]['field_short_description'];
      $node->body->value = $api_events[$event_id]['field_event_description'];
      $node->body->format = 'full_html';
      $node->field_audience->value = $api_events[$event_id]['field_audience'];
      $node->field_audience->format = 'full_html';
      $node->field_non_chop_location->value = count($api_events[$event_id]['field_non_chop_location']) != 0 ? $api_events[$event_id]['field_non_chop_location'] : null;
      $node->field_non_chop_location->format = 'full_html';
      $node->field_event_type->value = strlen($api_events[$event_id]['field_event_']) != 0 ? $api_events[$event_id]['field_event_'] : null;
      $node->field_event_type->format = 'full_html';
      $node->field_event_location->value = strlen($api_events[$event_id]['field_location']) != 0 ? $api_events[$event_id]['field_location'] : null;
      $node->field_event_location->format = 'full_html';
      $node->field_related_people->value = count($api_events[$event_id]['field_related_person']) != 0 ? $api_events[$event_id]['field_related_person'] : null;
      $node->field_related_people->format = 'full_html';
      $node->field_event_topic->value = strlen($api_events[$event_id]['field_related_departments']) != 0 ? $api_events[$event_id]['field_related_departments'] : null;
      $node->field_event_topic->format = 'full_html';
      $node->field_event_category->value = strlen($api_events[$event_id]['field_tags']) != 0 ? $api_events[$event_id]['field_tags'] : null;
      $node->field_event_category->format = 'full_html';
      $node->set('field_contact_email', $api_events[$event_id]['field_staff_contact_e_mail']);
      $node->field_time_of_event->value = $event_date_times['event_start_date_time'];
      $node->field_time_of_event->end_value = $event_date_times['event_end_date_time'];
      $node->field_webinar_link->uri = $register_link['register_link'];
      $node->field_webinar_link->title = $register_link['link_text'];
      $node->field_postthumbnail->target_id = $thumb_file_details;
      $node->field_postthumbnail->alt = $image_postthumbnail_details['alt_title'];
      $node->field_postthumbnail->title = $image_postthumbnail_details['img_title'];
      $node->field_image->target_id = $image_file_details;
      $node->field_image->alt = $image_details['alt_title'];
      $node->field_image->title = $image_details['img_title'];
      $node->save();
    }
  }


  /**
   * Create events.
   */
  public function createEvents($events, $api_profiles) {
    // print_r($api_profiles);exit;

    // \Drupal::logger('CIRP')->notice('<pre><code>' . print_r($api_profiles, TRUE) . '</code></pre>');
    foreach ($api_profiles as $value) {

    // XML Date = "November 3, 2021 - 12:00 pm to 1:00 pm"
    // convert the XML Date to fomatted date (Y-m-d\TH:i:s)
    // Output = array("event_start_date_time" =>"Y-m-d\TH:i:s", "event_end_date_time" => "Y-m-d\TH:i:s")
		
		$event_date_times = $this->getEventStartEndTime($events[$event_id]['field_event_end_time']);
    print_r($event_date_times);exit;
    // Seperate Link and Text
    // <field_registration_link> Input XML DATA = "Virtual BlueJeans Meeting https://bluejeans.com/528917057"
    // Output = array("link_text" =>"Virtual BlueJeans Meeting", "register_link" => "https://bluejeans.com/528917057")

    if (count($events[$event_id]['field_registration_link']) != 0) {

    $register_link = $this->seperateLinkText($events[$event_id]['field_registration_link']);

    }else {

      $register_link['link_text'] = $register_link['register_link'] = null;

    }
    // Event Detailed Image
    if (!empty($events[$event_id]['field_image'])) {

      // Process XML Image data and get deatils for file processing
      // XML DATA = <img src="/sites/default/files/2021-07/07_16_21_CPCE.jpg" width="350" height="200" alt="Clinical Futures Seminar Series With David Weber, MD, MSCE" title="Clinical Futures Seminar Series With David Weber, MD, MSCE" loading="lazy" typeof="foaf:Image" />
      // Output = array("img_title" =>"Image Title", "alt_title" => "Image alt title", "img_url"=> "https://www.research.chop.edu/sites/default/files/")

      $image_details = $this->getImageDetails($events[$event_id]['field_image']);
      //print_r($image_details);
      //exit;
      \Drupal::logger('node')->notice('The value of $current_path is @path', ['@path' => $image_details]);

      if (count($image_details) != 0) {

        // Process Image deatils and create an file entry in drupal
        // Returns File ID after processing

        $image_file_details = $this->createFile($image_details);
        
      }
    }
    // Event Featured Image( Post Thumbnail)
    if (!empty($events[$event_id]['field_featured_image'])) {

      // Process XML Image data and get deatils for file processing
      // XML DATA = <img src="/sites/default/files/2021-07/07_16_21_CPCE_FEATURED.jpg" width="350" height="200" alt="Clinical Futures Seminar Series With David Weber, MD, MSCE" title="Clinical Futures Seminar Series With David Weber, MD, MSCE" loading="lazy" typeof="foaf:Image" />
      // Output = array("img_title" =>"Image Title", "alt_title" => "Image alt title", "img_url"=> "https://www.research.chop.edu/sites/default/files/")

      $image_postthumbnail_details = $this->getImageDetails($events[$event_id]['field_featured_image']);

    
      if (count($image_postthumbnail_details) != 0) {

        // Process Image deatils and create an file entry in drupal
        // Returns File ID after processing

        $thumb_file_details = $this->createFile($image_postthumbnail_details);


      }
        
    }
      // Create Nodes (type = "events")
      $node = Node::create([
        'type' => 'events',
        'title' => $events[$event_id]['title'],
        'field_event_id' => $event_id,
        'field_short_description' => ['value' => $events[$event_id]['field_short_description']],
		    'body' => ['value' => $events[$event_id]['field_event_description'], 'format' => 'full_html'],
        'field_audience' => ['value' => $events[$event_id]['field_audience'], 'format' => 'full_html'],
        'field_non_chop_location' => ['value' => count($events[$event_id]['field_non_chop_location']) != 0 ? $events[$event_id]['field_non_chop_location'] : null,
        'format' => 'full_html'],
        'field_contact_email' => $events[$event_id]['field_staff_contact_e_mail'],
		    'field_event_type' => ['value' => strlen($events[$event_id]['field_event_']) != 0 ? $events[$event_id]['field_event_'] : null, 'format' => 'full_html'],
		    'field_event_location' => ['value' => strlen($events[$event_id]['field_location']) != 0 ? $events[$event_id]['field_location'] : null, 'format' => 'full_html'],
        'field_related_people' => ['value' => count($events[$event_id]['field_related_person']) != 0 ? $events[$event_id]['field_related_person'] : null, 'format' => 'full_html'],
        'field_event_topic' => ['value' => strlen($events[$event_id]['field_related_departments']) != 0 ? $events[$event_id]['field_related_departments'] : null, 'format' => 'full_html'],
		    'field_event_category' => ['value' => strlen($events[$event_id]['field_tags']) != 0 ? $events[$event_id]['field_tags'] : null, 'format' => 'full_html'],
        'field_time_of_event' => [
          'value'=> $event_date_times['event_start_date_time'],
          'end_value' => $event_date_times['event_end_date_time']
        ],
        'field_webinar_link' => [
          'uri'=> $register_link['register_link'],
          'title' => $register_link['link_text']
        ],
        'field_postthumbnail' => [
          'target_id'=>  $thumb_file_details,
          'alt' => $image_postthumbnail_details['alt_title'],
          'title' => $image_postthumbnail_details['img_title'],
        ],
        'field_image' => [
          'target_id'=>  $image_file_details,
          'alt' => $image_details['alt_title'],
          'title' => $image_details['img_title'],
        ],
        'field_from_api' => 1,
        'status' => 1,
        'uid' => 1,
      ]);
      
//\Drupal::logger('node')->notice('The value of $current_path is @path', ['@path' => $image_details['img_title']]);

//exit;
      // print_r($node);
      $node->save();
      //\Drupal::logger('node')->notice('The value of $current_path is @path', ['@path' => $node]);
    }

  }
  
  /**
   * Get Event Start and End date & time.
   */

  public function getEventStartEndTime($api_date){
	  // Remove "-" in XML Date (November 3, 2021 - 12:00 pm to 1:00 pm)
    
    // $api_event_date = $api_date ?? str_replace("-","",$api_date);
    $api_event_date = str_replace("-","",$api_date ?? '');
    // Split Start time and End time from the XML Date
    $break_start_end_date = explode(" to ",$api_event_date);
    // Get Timestamp
    $start_date_time = strtotime($break_start_end_date[0]);
    // Get Event Date
    $event_date = date("d-m-Y", $start_date_time);
    // Get Event End Date
    $end_date_time = $event_date." ".$break_start_end_date[1];
    
    $event_date_times['event_end_date_time'] = gmdate("Y-m-d\TH:i:s", strtotime($end_date_time));

    // Get Event Start Date
    
    $event_date_times['event_start_date_time'] = gmdate("Y-m-d\TH:i:s", $start_date_time);
  
	  return $event_date_times;
	
  }

  /**
   * Get Event Register Link and Text.
   * Link Text should not be same everytime, it may be ("Register", "Virtual Meeting","Other")
   * So can't define that as static, this method useful to seperate both anchor tag href link and text
   * Input XML DATA = "Virtual BlueJeans Meeting https://bluejeans.com/528917057"
   * Output = array("link_text" =>"Virtual BlueJeans Meeting", "register_link" => "https://bluejeans.com/528917057")
   */

  public function seperateLinkText($field_registration_link)
  {
	  
  $register_link = array();

  // link may have https or http

	if (strpos($field_registration_link, "https://")) {

    $seperate_link_text = explode('https://', $field_registration_link);

    // Link Text
    
    $register_link['link_text'] = $seperate_link_text[0];

    // Link URL
    
    $register_link['register_link'] = "https://".$seperate_link_text[1];

    // In case of http://

	}elseif (strpos($field_registration_link, "http://")) { 

	$seperate_link_text = explode('http', $field_registration_link);

  // Link Text 

	$register_link['link_text'] = $seperate_link_text[0];

  // Link URL

	$register_link['register_link'] = "http://".$seperate_link_text[1];

	}

    return $register_link;
	
  }
 
  /**
   * Process Image and get deatils.
   * Process XML Image data and get deatils for file processing
   * XML DATA = <img src="/sites/default/files/2021-07/07_16_21_CPCE_FEATURED.jpg" width="350" height="200" alt="Clinical Futures Seminar Series With David Weber, MD, MSCE" title="Clinical Futures Seminar Series With David Weber, MD, MSCE" loading="lazy" typeof="foaf:Image" />
   * Output = array("img_title" =>"Image Title", "alt_title" => "Image alt title", "img_url"=> "https://www.research.chop.edu/sites/default/files/")
   */

  public function getImageDetails($image_data)
  {
   // Extract Image URL
   preg_match('/ src=(".*?"|\'.*?\'|.*?)[ >]/i', $image_data, $matches);

   $extracted_img_data['img_url'] = $matches[1];

   if (!empty($extracted_img_data['img_url'])) {
   // Extract Image Title
   preg_match('/ title=(".*?"|\'.*?\'|.*?)[ >]/i', $image_data, $matches);

   $extracted_img_data['img_title'] = $matches[1];
   
   $extracted_img_data['alt_title'] = $matches[1];

   $extracted_img_data['img_url'] = str_replace("\"", "", $extracted_img_data['img_url'] ?? '');

   // Image Source is External, and it is from "https://www.research.chop.edu". add that to Image URL

   $extracted_img_data['img_url'] = "https://www.research.chop.edu".$extracted_img_data['img_url'];

   }
   
   return $extracted_img_data;
	
  }

  /**
   * Get Event Start and End date & time.
   * Returns File Id after succesful entry
   */

  public function createFile($image_details){

  // check first if the file exists for the uri
  $files = \Drupal::entityTypeManager()
  ->getStorage('file')
  ->loadByProperties(['uri' => $image_details['img_url']]);
  $file = reset($files);

  //\Drupal::logger('node')->notice('The value of $current_path is @path', ['@path' => $image_details]);

  //exit;
  // if not create a file
  if (!$file) {
    $file = File::create([
      'uri' => $image_details['img_url'],
    ]);
    $file->setPermanent();
    // print_r($file);
    
    $file->save();
  }
  // Get the File ID
  $fid = $file->id();

  return $fid;


  }
}

