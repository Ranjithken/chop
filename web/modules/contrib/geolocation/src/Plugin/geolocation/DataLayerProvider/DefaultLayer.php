<?php

namespace Drupal\geolocation\Plugin\geolocation\DataLayerProvider;

use Drupal\geolocation\DataLayerProviderBase;
use Drupal\geolocation\DataLayerProviderInterface;
use Drupal\geolocation\MapProviderInterface;

/**
 * Provides default layer.
 *
 * @DataLayerProvider(
 *   id = "geolocation_default_layer",
 *   name = @Translation("Map Default"),
 *   description = @Translation("This is the content of the map itself without any additional data."),
 * )
 */
class DefaultLayer extends DataLayerProviderBase implements DataLayerProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function getLayerRenderData(string $data_layer_option_id = 'default', array $settings = [], MapProviderInterface $mapProvider = NULL, array $context = NULL): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function getLayerId(string $data_layer_option_id = 'default'): string {
    return 'default';
  }

  /**
   * {@inheritdoc}
   */
  protected function getJavascriptModulePath(): string {
    return '';
  }

}
