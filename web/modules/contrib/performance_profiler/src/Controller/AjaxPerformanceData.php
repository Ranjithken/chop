<?php

namespace Drupal\performance_profiler\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an AjaxPerformanceData API to get performance data.
 */
class AjaxPerformanceData extends ControllerBase {

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new BlockController instance.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The store factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, RequestStack $request_stack, RendererInterface $renderer) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->requestStack = $request_stack;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('request_stack'),
      $container->get('renderer')
    );
  }

  /**
   * AJAX callback for main performance data.
   */
  public function get() {
    $uuid = session_id();
    $tempstore = $this->tempStoreFactory->get("performance_profiler_$uuid");
    $storage = $tempstore->get('storage');
    $tempstore->set('storage', NULL);
    $storage = is_array($storage ?? FALSE) ? $storage : [];
    $path = $this->requestStack->getCurrentRequest()->query->get('path');

    $message = $this->t('No queries to show, check settings');
    $count = count($storage);
    if ($count) {
      $main_element = ($storage[$path] ?? FALSE)
        ? ($storage[$path]['short'] ?? '')
        : ($storage[array_key_last($storage)]['short'] ?? '');
      $message = "[{$count}] $main_element";
    }

    $markup = [
      '#theme' => 'performance_profiler_toolbar',
      '#data' => $storage,
      '#path' => $path,
      '#short_message' => $message,
    ];

    // Return response.
    return new Response($this->renderer->render($markup));
  }

}
