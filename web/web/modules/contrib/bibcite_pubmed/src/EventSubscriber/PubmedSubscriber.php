<?php

namespace Drupal\bibcite_pubmed\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
// use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class PubmedSubscriber.
 *
 * @package Drupal\bibcite_pubmed\EventSubscriber
 */
class PubmedSubscriber implements EventSubscriberInterface {

  /**
   * Register content type formats on the request object.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The Event to process.
   */
  // public function onKernelRequest(GetResponseEvent $event) {
    public function onKernelRequest(RequestEvent $event) {
    $event->getRequest()->setFormat('xml', ['application/x-pubmed-refer']);
  }

  /**
   * Implements \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents().
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
    return $events;
  }

}
