<?php

namespace Drupal\cirp_profiles\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Drupal\Core\Entity\Query\QueryInterface;

/**
 * Provides route responses for the Example module.
 *
 * TODO: team, Please fix the indentations and maintain coding standards.
 */
class UserModuleController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   *  
   */
   /**
        * Return user progress block
        *
    */
  public function progress_block() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $book_progress = '';
    $By="<a href=#>By</a>";
    if($node){
            $typeName = $node->bundle();
            if($typeName == 'blog_post') {
                $usernodes= \Drupal::database()->select('node__field_external_url', 'n')
                    ->fields('n', array('field_external_url_title','field_external_url_uri'))				
                    ->condition('entity_id', $node->id())
                    ->execute()->fetchAll();  
                $count = count($usernodes);
                if($count >= 1){
                    for($i = 0; $i < $count; $i++) {
                        $delimeter = '';
                            if($i != 0) {
                                if($i == ($count-1)) {
                                $delimeter = ' and ';
                                } else {
                                    $delimeter = ', ';
                                }
                            }
                        $title = $usernodes[$i]->field_external_url_title;
                        $url = $usernodes[$i]->field_external_url_uri;
                        $book_progress .= $delimeter."<a target='_blank' href='".$url."'> $title</a>";
                    
                    }
                } else {
                    $uid = $node->getOwnerId();
                    $user = \Drupal\user\Entity\User::load($uid);
					// get the profile linked to the user
                    $user_profile_node_id=$user->field_profile_link->target_id;
					//check linked profile exist or not
					$values = \Drupal::entityQuery('node')  
                    ->accessCheck(TRUE)
                    ->condition('nid', $user_profile_node_id)
                    ->execute();
                    $profile_exists = !empty($values);
                    $name = $user->getDisplayName(); 
                    if($profile_exists){
                        $book_progress .= "<a target='_blank' href='/node/".$user_profile_node_id."'> $name</a>";
                    } else{
                        $book_progress .= "<a target='_blank' href='/user/".$uid."'> $name</a>";
                    }
                    // kint($user);
                    // exit;
                }
            }
        }
        $book_progress=$By.$book_progress;
        return $book_progress;
    }
}