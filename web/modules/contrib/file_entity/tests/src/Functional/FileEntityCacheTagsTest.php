<?php

namespace Drupal\Tests\file_entity\Functional;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Drupal\Tests\system\Functional\Cache\AssertPageCacheContextsAndTagsTrait;

/**
 * Create a file and test file edit functionality.
 *
 * @group file_entity
 */
class FileEntityCacheTagsTest extends FileEntityTestBase {

  use AssertPageCacheContextsAndTagsTrait;

  /**
   * @var array
   */
  protected static $modules = ['node', 'views'];

  protected $adminUser;

  function setUp(): void {
    parent::setUp();

    $this->enablePageCaching();
  }

  /**
   * Check file edit functionality.
   */
  function testFileEntityEdit() {
    // Create two files.
    $file1 = $this->createFileEntity();
    $file2 = $this->createFileEntity();

    $content_type = $this->drupalCreateContentType();
    $field_storage = FieldStorageConfig::create(array(
      'field_name' => 'used_file',
      'entity_type' => 'node',
      'type' => 'file',
      'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
    ));
    $field_storage->save();
    $field_instance = FieldConfig::create(array(
      'field_storage' => $field_storage,
      'entity_type' => 'node',
      'bundle' => $content_type->id(),
    ));
    $field_instance->save();

    // Create three nodes, the first has file 1, the second both files, the
    // third only the second.
    $node1 = Node::create(array(
      'title' => 'An article that uses a file',
      'promote' => 1,
      'type' => $content_type->id(),
      'used_file' => array(
        'target_id' => $file1->id(),
        'display' => 1,
        'description' => '',
      ),
    ));
    $node1->save();

    $node2 = Node::create(array(
      'title' => 'An article that uses a file',
      'promote' => 1,
      'type' => $content_type->id(),
      'used_file' => array(
        array(
          'target_id' => $file1->id(),
          'display' => 1,
          'description' => '',
        ),
        array(
          'target_id' => $file2->id(),
          'display' => 1,
          'description' => '',
        ),
      ),
    ));
    $node2->save();

    $node3 = Node::create(array(
      'title' => 'An article that uses a file',
      'promote' => 1,
      'type' => $content_type->id(),
      'used_file' => array(
        'target_id' => $file2->id(),
        'display' => 1,
        'description' => '',
      ),
    ));
    $node3->save();

    // Check cache tags.
    $contexts = ['languages:language_interface', 'user.permissions', 'theme', 'timezone', 'url.query_args:_wrapper_format', 'url.site'];
    if (\version_compare(\Drupal::VERSION, '9.3', '<')) {
      $contexts[] = 'user.roles:anonymous';
    }
    $this->assertPageCacheContextsAndTags($node1->toUrl(), $contexts, [
      'node:' . $node1->id(),
      'node_view',
      'rendered',
      'user:0',
      'user_view',
      'config:user.role.anonymous',
      'http_response',
    ]);
    $this->assertPageCacheContextsAndTags($node2->toUrl(), $contexts, [
      'node:' . $node2->id(),
      'node_view',
      'rendered',
      'user:0',
      'user_view',
      'config:user.role.anonymous',
      'http_response',
    ]);
    $this->assertPageCacheContextsAndTags($node3->toUrl(), $contexts, [
      'node:' . $node3->id(),
      'node_view',
      'rendered',
      'user:0',
      'user_view',
      'config:user.role.anonymous',
      'http_response',
    ]);

    // Save the first file to invalidate cache tags.
    $file1->save();
    $this->verifyPageCache($node1->toUrl(), 'MISS');
    $this->verifyPageCache($node2->toUrl(), 'MISS');
    $this->verifyPageCache($node3->toUrl(), 'HIT');
  }

  /**
   * Verify that when loading a given page, it's a page cache hit or miss.
   *
   * @param \Drupal\Core\Url $url
   *   The page for this URL will be loaded.
   * @param string $hit_or_miss
   *   'HIT' if a page cache hit is expected, 'MISS' otherwise.
   *
   * @param array|FALSE $tags
   *   When expecting a page cache hit, you may optionally specify an array of
   *   expected cache tags. While FALSE, the cache tags will not be verified.
   */
  protected function verifyPageCache(Url $url, $hit_or_miss, $tags = FALSE) {
    $this->drupalGet($url);
    $this->assertSession()->responseHeaderEquals('X-Drupal-Cache', $hit_or_miss);
    if ($hit_or_miss === 'HIT' && is_array($tags)) {
      $absolute_url = $url->setAbsolute()->toString();
      $cid_parts = array($absolute_url, 'html');
      $cid = implode(':', $cid_parts);
      $cache_entry = \Drupal::cache('render')->get($cid);
      sort($cache_entry->tags);
      $tags = array_unique($tags);
      sort($tags);
      $this->assertSame($cache_entry->tags, $tags);
    }
  }

}
