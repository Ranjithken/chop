<?php

namespace Drupal\Tests\bibcite_pubmed\Kernel;

use Drupal\bibcite_pubmed\Encoder\PubmedEncoder;
use Drupal\Tests\bibcite_import\Kernel\FormatDecoderTestBase;

/**
 * @coversDefaultClass \Drupal\bibcite_pubmed\Encoder\PubmedEncoder
 * @group bibcite
 */
class PubmedDecodeTest extends FormatDecoderTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  // public static $modules = [
    
  protected static $modules = [
    'system',
    'user',
    'serialization',
    'bibcite',
    'bibcite_entity',
    'bibcite_pubmed',
  ];

  /**
   * {@inheritdoc}
   */
  // public function setUp() {
    
  public function setUp(): void {
    parent::setUp();

    $this->installConfig([
      'system',
      'user',
      'serialization',
      'bibcite',
      'bibcite_entity',
      'bibcite_pubmed',
    ]);

    $this->encoder = new PubmedEncoder();
    $this->format = 'pubmed';
    $this->resultDir = __DIR__ . '/../../data/decoded';
    $this->inputDir = __DIR__ . '/../../data/encoded';
  }

}
