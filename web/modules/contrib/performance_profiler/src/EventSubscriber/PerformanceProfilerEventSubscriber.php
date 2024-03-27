<?php

namespace Drupal\performance_profiler\EventSubscriber;

use Drupal\Component\Utility\Timer;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Subscribes to the kernel request event to register a shutdown function.
 */
class PerformanceProfilerEventSubscriber implements EventSubscriberInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the PerformanceProfilerEventSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Set a low value to start as early as possible.
    $events[KernelEvents::REQUEST][] = ['onRequest', -100];

    return $events;
  }

  /**
   * Register performance_profiler_shutdown function.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The event response.
   */
  public function onRequest(RequestEvent $event) {
    Timer::start('performance_profiler');

    $config = $this->configFactory->get('performance_profiler.settings');
    if ($config->get('database')) {
      Database::startLog('performance_profiler_db');
    }

    drupal_register_shutdown_function('performance_profiler_shutdown');
  }

}
