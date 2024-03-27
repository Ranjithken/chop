<?php

namespace Drupal\bibcite_pubmed\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Drupal\bibcite_pubmed\PubmedClientInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Pubmed id format encoder.
 */
class PubmedIdEncoder implements DecoderInterface, EncoderInterface {

  /**
   * The format that this encoder supports.
   *
   * @var array
   */
  protected static $format = 'pubmed_id';

  /**
   * The Pubmed client to get data by id.
   *
   * @var \Drupal\bibcite_pubmed\PubmedClientInterface
   */
  protected $pubmedClient;

  /**
   * PubmedIdEncoder constructor.
   *
   * @param \Drupal\bibcite_pubmed\PubmedClientInterface $pubmed_client
   */
  public function __construct(PubmedClientInterface $pubmed_client) {
    $this->pubmedClient = $pubmed_client;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format) {
    return $format === self::$format;
  }

  /**
   * {@inheritdoc}
   */
  public function decode($data, $format, array $context = []) {
    /** @var \Symfony\Component\Serializer\Serializer $serializer */
    $serializer = \Drupal::service('serializer');

    $pmids = explode("\n", $data);
    $chunks = array_chunk($pmids, 50);
    $result = [];
    foreach ($chunks as $chunk) {
      $record = $this->pubmedClient->fetch($chunk);
      $result = array_merge($result, $serializer->decode($record, 'pubmed'));
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []): string {
    $pids = array_column($data, 'PMID');
    return implode("\n", $pids);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsEncoding($format): bool {
    return $format === self::$format;
  }
}
