<?php

namespace Drupal\geolocation_search_api\Plugin\geolocation\DataProvider;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\geolocation\DataProviderBase;
use Drupal\geolocation\DataProviderInterface;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Plugin\views\field\SearchApiEntityField;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Provides Google Maps.
 *
 * @DataProvider(
 *   id = "search_api",
 *   name = @Translation("Search API"),
 *   description = @Translation("Search API indexed fields support, works with Search API Location module too."),
 * )
 */
class SearchAPI extends DataProviderBase implements DataProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function isViewsGeoOption(FieldPluginBase $viewsField): bool {
    if ($viewsField instanceof SearchApiEntityField) {
      $index_id = str_replace('search_api_index_', '', $viewsField->table);
      $index = Index::load($index_id);
      if (empty($index)) {
        return FALSE;
      }

      /** @var \Drupal\search_api\Item\FieldInterface $search_api_field */
      $search_api_field = $index->getField($viewsField->field);
      if (empty($search_api_field)) {
        return FALSE;
      }
      elseif ($search_api_field->getType() == 'location') {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isFieldGeoOption(FieldDefinitionInterface $fieldDefinition): bool {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getPositionsFromViewsRow(ResultRow $row, FieldPluginBase $viewsField = NULL): array {
    $positions = [];

    if (!($viewsField instanceof SearchApiEntityField)) {
      return [];
    }

    foreach ($viewsField->getItems($row) as $item) {
      if (!empty($item['value'])) {
        $pieces = explode(',', $item['value']);
        if (count($pieces) != 2) {
          continue;
        }

        $positions[] = [
          'lat' => $pieces[0],
          'lng' => $pieces[1],
        ];
      }
      elseif (!empty($item['raw'])) {
        /** @var \Drupal\geolocation\Plugin\Field\FieldType\GeolocationItem $geolocation_item */
        $geolocation_item = $item['raw'];
        $positions[] = [
          'lat' => $geolocation_item->get('lat')->getValue(),
          'lng' => $geolocation_item->get('lng')->getValue(),
        ];
      }
    }

    return $positions;
  }

}