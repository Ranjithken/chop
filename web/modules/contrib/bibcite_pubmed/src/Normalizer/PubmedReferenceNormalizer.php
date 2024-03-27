<?php

namespace Drupal\bibcite_pubmed\Normalizer;

use Drupal\bibcite_entity\Normalizer\ReferenceNormalizerBase;

/**
 * Denormalizes reference entity from PubMed format.
 */
class PubmedReferenceNormalizer extends ReferenceNormalizerBase {

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []): array|string|int|float|bool|\ArrayObject|NULL {
    return $this->extractFields($reference, 'pubmed');
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []): mixed  {
    $contributor_key = $this->getContributorKey();
    if (!empty($data[$contributor_key])) {
      $contributors = (array) $data[$contributor_key];
      $categories = array_column($contributors, 'category');
      $data[$contributor_key] = array_column($contributors, 'name');
    }

    $entity = parent::denormalize($data, $class, 'pubmed', $context);

    if (!empty($contributors)) {
      $author_field = $entity->get('author');
      for ($i = 0; $i < $author_field->count(); $i++) {
        $author = $author_field->get($i);
        $category = $author->getProperties()['category'];
        $category->setValue($categories[$i]);
      }
    }
    return $entity;
  }

}
