<?php

namespace Drupal\cirp_profiles\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cirp_profiles\Controller\UserModuleController;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'Progress' Block.
 *
 * @Block(
 *   id = "Progress_block",
 *   admin_label = @Translation("Progress block"),
 *   category = @Translation("Hello World"),
 * )
 */
class Progress extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $controller_variable = new UserModuleController;
    $rendering_in_block = $controller_variable->progress_block();
    $block = [
      '#type' => 'markup',
      '#markup' => $rendering_in_block,
      '#cache' => array(
        'tags' => $this->getCacheTags(),
        'contexts' => $this->getCacheContexts(),
      ),
    ];
    return $block;

  }
   /**
     * {@inheritdoc}
     */
    public function getCacheMaxAge() {
      return 0;
  }

  public function getCacheTags() {
    //With this when your node change your block will rebuild
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      //if there is node add its cachetag
      return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
    } else {
      //Return default tags instead.
      return parent::getCacheTags();
    }
  }

  public function getCacheContexts() {
    //if you depends on \Drupal::routeMatch()
    //you must set context of this block with 'route' context tag.
    //Every new route this block will rebuild
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

}