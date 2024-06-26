<?php

/**
 * @file
 * Preprocess functions for Views RSS: Core Elements module.
 */

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;
use Drupal\file\FileInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\video_embed_field\Plugin\Field\FieldType\VideoEmbedField;

/**
 * Preprocess function for channel <title> element.
 */
function views_rss_core_preprocess_channel_title(&$variables) {
  $config = \Drupal::config('system.site');

  if ($variables['view']->display_handler->getOption('sitename_title')) {
    $title = $config->get('name');
  }
  else {
    $title = $variables['view']->getTitle();
  }

  $variables['elements'][0]['value'] = strip_tags($title);
}

/**
 * Preprocess function for channel <link> element.
 */
function views_rss_core_preprocess_channel_link(&$variables) {
  // The link element identifies the URL of the web site associated with
  // the feed (and not the feed's URL, as Drupal makes you think).
  // See http://www.rssboard.org/rss-profile#element-channel-link
  $variables['elements'][0]['value'] = views_rss_link_to_front_page();
}

/**
 * Preprocess function for channel <atom:link> element.
 */
function views_rss_core_preprocess_channel_atom_link(&$variables) {
  // Use the request path not the view path here due to possible exposed filters
  // changing the effective path for views.
  $url = Url::createFromRequest(\Drupal::request())
    ->setOption('absolute', TRUE)
    ->setOption('query', \Drupal::request()->query->all())
    ->toString();
  $variables['elements'][0]['attributes'] = [
    'rel' => 'self',
    'href' => $url,
  ];
}

/**
 * Preprocess function for channel <language> element.
 */
function views_rss_core_preprocess_channel_language(&$variables) {
  if (empty($variables['elements'][0]['value'])) {
    $variables['elements'][0]['value'] = Html::escape(\Drupal::languageManager()->getCurrentLanguage()->getId());
  }
}

/**
 * Preprocess function for channel <category> element.
 */
function views_rss_core_preprocess_channel_category(&$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }
  $elements = [];
  $categories = explode(',', $variables['elements'][0]['value']);
  foreach ($categories as $category) {
    $elements[] = [
      'key' => 'category',
      'value' => trim($category),
    ];
  }
  $variables['elements'] = $elements;
}

/**
 * Preprocess function for channel <image> element.
 */
function views_rss_core_preprocess_channel_image(&$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }

  $image_path = $variables['elements'][0]['value'];

  // Get value of channel <title> element from its preprocess function.
  views_rss_core_preprocess_channel_title($variables);
  $title = $variables['elements'][0]['value'];

  // Create subelements array.
  $variables['elements'][0]['value'] = [
    // The image's url element identifies the URL of the image.
    'url' => [
      '#type' => 'html_tag',
      '#tag' => 'url',
      '#value' => \Drupal::service('file_url_generator')->generateAbsoluteString($image_path),
    ],
  ];
  // The image's title element SHOULD have the same text as the channel's title
  // element and be suitable for use as the alt attribute of the img tag in an
  // HTML rendering.
  if (!empty($title)) {
    $variables['elements'][0]['value']['title'] = [
      '#type' => 'html_tag',
      '#tag' => 'title',
      '#value' => $title,
    ];
  }

  // The image's link element identifies the URL of the web site represented by
  // the image, not the feed URL. The first problem is that Drupal's normal Twig
  // filtering strips out LINK tag values as it is supposed to be a self-closing
  // tag, e.g. "<link src="something" />" and not "<link>something</link>". The
  // second problem is that LINK tags are also normally filtered out entirely
  // because a LINK tag is normally considered a security problem. Therefore,
  // output the tag with a custom tag name that is then adjusted in the template
  // file. Yes, this is super hacky, but there aren't too many ways around it.
  // @see \Drupal\Core\Render\Element\HtmlTag::$voidElements
  // @see \Drupal\Core\Render\Renderer::xssFilterAdminIfUnsafe()
  // @see views-view-rss.html.twig
  $variables['elements'][0]['value']['link'] = [
    '#type' => 'html_tag',
    '#tag' => 'imagelink',
    '#value' => views_rss_link_to_front_page(),
  ];

  // Use the site slogan as the image's description.
  $site_slogan = \Drupal::config('system.site')->get('slogan');
  if (!empty($site_slogan)) {
    $variables['elements'][0]['value']['description'] = [
      '#type' => 'html_tag',
      '#tag' => 'description',
      '#value' => $site_slogan,
    ];
  }

  // Get image's width and height.
  if (strpos($image_path, 'https://') !== FALSE && strpos($image_path, 'http://') !== FALSE) {
    $image = Drupal::service('image.factory')->get($image_path);
    if (!$image->isValid()) {
      $variables['elements'][0]['value']['width'] = [
        '#type' => 'html_tag',
        '#tag' => 'width',
        '#value' => $image->getWidth(),
      ];
      $variables['elements'][0]['value']['height'] = [
        '#type' => 'html_tag',
        '#tag' => 'height',
        '#value' => $image->getHeight(),
      ];
    }
  }
}

/**
 * Preprocess function for channel <lastBuildDate> element.
 */
function views_rss_core_preprocess_channel_last_build_date(&$variables) {
  $variables['elements'][0]['value'] = date('r');
}

/**
 * Preprocess function for channel <skipHours> and <skipDays> elements.
 */
function views_rss_core_preprocess_channel_skip(&$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }
  $elements = [];
  $skips = strip_tags($variables['elements'][0]['value']);
  if (!empty($skips)) {
    foreach (explode(',', $skips) as $skip_value) {
      $elements[] = [
        'key' => ($variables['elements'][0]['key'] == 'skipHours') ? 'hour' : 'day',
        'value' => trim($skip_value),
      ];
    }
  }
  $variables['elements'][0]['value'] = $elements;
}

/**
 * Preprocess function for channel <cloud> element.
 */
function views_rss_core_preprocess_channel_cloud(&$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }
  if ($url = parse_url($variables['elements'][0]['value'])) {
    $variables['elements'][0]['value'] = NULL;
    $variables['elements'][0]['attributes'] = array_filter([
      'domain' => $url['host'] ?? '',
      'port' => $url['port'] ?? '',
      'path' => $url['path'] ?? '',
      'registerProcedure' => $url['fragment'] ?? '',
      'protocol' => $url['scheme'] ?? '',
    ]);
  }
}

/**
 * Preprocess function for item <category> element.
 *
 * See http://www.rssboard.org/rss-profile#element-channel-item-category
 * for RSS Advisory Board requirements/recommendations.
 *
 * @see RSSCategoryFormatter::viewElements()
 */
function views_rss_core_preprocess_item_category(&$variables) {
  // No raw values = no preprocessing.
  if (empty($variables['raw']['items'])) {
    return;
  }

  $variables['elements'] = [];
  foreach ($variables['raw']['items'] as $item) {
    $term = FALSE;

    // When 'Content: <vocabulary_name>' was selected for source of category
    // element, we will get all we need in raw values.
    if (!empty($item['raw']) && is_object($item['raw']) && $item['raw']->target_id) {
      $term = $item['raw']->entity;
    }

    // On the other hand, when 'Content: All taxonomy terms' field was added
    // as a source for category element, we won't have raw values, instead
    // an array containing only tid, name, vocabulary_vid and vocabulary keys,
    // so we need to load full term object first before processing it further.
    elseif (!empty($item['tid'])) {
      $term = Term::load($item['tid']);
    }

    // According to RSS Advisory Board, the category's value should be
    // a slash-delimited string that identifies a hierarchical position
    // in the taxonomy.
    $values = [];

    // Load parent term objects (this includes original term as well).
    if (!empty($term)) {
      $parents = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->loadAllParents($term->id());
      if (!empty($parents)) {
        foreach ($parents as $parent) {
          $values[] = $parent->label();
        }
      }
    }

    $element = [
      'key' => 'category',
      'value' => implode('/', array_reverse($values)),
      // Drupal uses term URL for domain attribute. RSS Best Practices say that
      // a domain attribute identifies the category's taxonomy - which suggests
      // either vocabulary name or its URL. We don't have any safe way to know
      // public vocabulary URL, could use its name instead though? @TODO?
      'attributes' => [
        'domain' => $term->toUrl('canonical', ['absolute' => TRUE])->toString(),
      ],
    ];

    $variables['elements'][] = $element;
  }
}

/**
 * Preprocess function for item <enclosure> element.
 */
function views_rss_core_preprocess_item_enclosure(&$variables) {
  // No raw values = no file preprocessing.
  if (empty($variables['raw']['items'])) {
    return;
  }

  $variables['elements'] = [];
  foreach ($variables['raw']['items'] as $item) {
    $file = FALSE;

    // File fields.
    if (!empty($item['rendered']['#file'])) {
      $file = $item['rendered']['#file'];
    }

    // Image fields.
    elseif (!empty($item['rendered']['#item']->entity)) {
      $file = $item['rendered']['#item']->entity;
    }

    // Start building RSS element.
    $element = [
      'key' => 'enclosure',
      'attributes' => [],
    ];

    // Handle the situation where we are dealing with an embedded YouTube video.
    if ($item['raw'] instanceof VideoEmbedField) {
      // @todo This doesn't work because FieldItemBase::value() is protected.
      $item['rendered']['#markup'] = $item['raw']->value;
      $element['attributes']['type'] = 'application/x-shockwave-flash';
      $element['attributes']['length'] = 'unknown';
    }

    // File entity found.
    if (!empty($file) && $file instanceof FileInterface) {
      // Image style is defined, need to link to resized version.
      if (!empty($item['rendered']['#image_style'])) {
        $image_style_name = $item['rendered']['#image_style'];
        $image_uri = $file->getFileUri();
        $image_style = \Drupal::service('entity_type.manager')->getStorage('image_style')->load($image_style_name);
        $image_style_uri = $image_style->buildUri($image_uri);

        // If the derivative doesn't exist yet, we won't be able to get its size
        // to add it to the 'length' attribute, so we need to create it first.
        $check_file_size = TRUE;
        if (!file_exists($image_style_uri)) {
          $check_file_size = $image_style->createDerivative($image_uri, $image_style_uri);
        }

        $element['attributes'] = [
          'url' => $image_style->buildUrl($image_uri),
          'type' => $file->getMimeType(),
        ];
        if ($check_file_size) {
          $element['attributes']['length'] = filesize($image_style_uri);
        }
      }
      // Normal image size.
      else {
        $element['attributes'] = [
          'url' => $file->createFileUrl(FALSE),
          'length' => $file->getSize(),
          'type' => $file->getMimeType(),
        ];
      }
    }
    // No file entity found, but something still was assigned to be displayed
    // as enclosure, so we just put its value in the url attribute.
    elseif (!empty($item['rendered']['#markup'])) {
      // Hack for using CDN files.
      // @todo make this more generalized to handle file and image entities.
      _convert_to_external_file($item['rendered']['#markup'], TRUE);

      $original_file = $item['rendered']['#markup'];

      $element['attributes']['url'] = $item['rendered']['#markup'];

      // Load the file length and type.
      $headers = get_headers($original_file, $format = 1);
      if (empty($element['attributes']['length']) && !empty($headers['Content-Length'])) {
        $element['attributes']['length'] = $headers['Content-Length'];
      }
      if (empty($element['attributes']['type']) && !empty($headers['Content-Type'])) {
        $element['attributes']['type'] = $headers['Content-Type'];
      }
    }

    $variables['elements'][] = $element;
  }
}

/**
 * Preprocess function for item <guid> element.
 */
function views_rss_core_preprocess_item_guid(&$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }
  // Hack for using CDN files.
  // @todo make this into a hook of some kind
  _convert_to_external_file($variables['elements'][0]['value']);

  $is_permalink = 'false';
  if (
    !empty($variables['item']['views_rss_core']['link'])
    && UrlHelper::isValid($variables['elements'][0]['value'], TRUE)
  ) {
    $is_permalink = 'true';
  }
  $variables['elements'][0]['attributes']['isPermaLink'] = $is_permalink;
}

/**
 * Preprocess function for item <source> element.
 */
function views_rss_core_preprocess_item_source(&$variables) {
  $config = \Drupal::config('system.site');

  // Allow override of title based on view->args settings.
  // @todo find a better way of overriding title
  if (isset($variables['view']->args['title'])) {
    $title = $variables['view']->args['title'];
  }
  elseif ($variables['view']->display_handler->getOption('sitename_title')) {
    $title = $config->get('name');
    if ($slogan = $config->get('slogan')) {
      $title .= ' - ' . $slogan;
    }
  }
  else {
    $title = $variables['view']->getTitle();
  }

  $url_options = ['absolute' => TRUE];
  $input = $variables['view']->getExposedInput();
  if ($input) {
    $url_options['query'] = $input;
  }

  // @todo handle all url situations including exposed input
  $variables['elements'][0]['value'] = strip_tags($title);
  $url = Url::createFromRequest(\Drupal::request())
    ->setOption('absolute', TRUE)
    ->setOption('query', \Drupal::request()->query->all())
    ->toString();
  $variables['elements'][0]['attributes']['url'] = $url;
}

/**
 * Preprocess function for item <description> element.
 */
function views_rss_core_preprocess_item_description(&$variables) {
  // Echo "DESCRIPTION " . $variables['elements'][0]['value'];.
}

/**
 * Convert CDN internal name to external name.
 */
function _convert_to_external_file(&$filename, $force_http = TRUE) {
  // Strip /sites/ if it exists at the beginning of the filename.
  if (preg_match('/^\/sites\/\//', $filename)) {
    $filename = preg_replace('/^\/sites\//', '', $filename);
  }

  // If the filename begins with / then add the url.
  if (preg_match('/^\/[^\/]/', $filename)) {
    $filename = $GLOBALS['base_url'] . $filename;
  }

  // If the filename begins with // then add the scheme.
  if (preg_match('/^\/\//', $filename)) {
    $scheme = \Drupal::request()->getScheme();
    if ($force_http) {
      $scheme = 'http';
    }

    $filename = $scheme . ":" . $filename;
  }
}

/**
 * Preprocess function for item <link> element.
 */
function views_rss_core_preprocess_item_link(&$variables) {
  // Hack for using CDN files.
  // @todo make this more generalized to handle file and image entities.
  _convert_to_external_file($variables['elements'][0]['value']);
}

/**
 * Preprocess function for item <link> element.
 */
function views_rss_core_preprocess_item_author(&$variables) {
  // @todo move this into hooks.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }

  $authors = $variables['elements'][0]['value'];

  $authors = str_replace('Author', '', $authors);
  $author_array = explode(',', $authors);

  $elements = [];
  foreach ($author_array as $dc_created) {
    // Strip Role and everything after.
    $dc_created = preg_replace('/Role(.*)$/s', '', $dc_created);

    $elements[] = [
      'key' => 'author',
      'value' => trim($dc_created),
    ];

    // Just do one for now.
    break;
  }

  $variables['elements'] = $elements;
}

/**
 * Preprocess function for fixing RSS date values.
 */
function views_rss_core_preprocess_item_pubdate(array &$variables) {
  // No value = no preprocessing.
  if (empty($variables['elements'][0]['value'])) {
    return;
  }

  // This is required because in Drupal 10.1 the node date field is wrapped in a
  // <time> tag, which throws off the normal output processing.
  foreach ($variables['elements'] as &$item) {
    $item['value'] = strip_tags($item['value']);
  }
}
