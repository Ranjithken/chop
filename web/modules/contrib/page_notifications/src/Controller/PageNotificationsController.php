<?php

namespace Drupal\page_notifications\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
//use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller routines for menu example routes.
 */
class PageNotificationsController extends ControllerBase {

  //use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'page_notifications';
  }

  /**
   * Page callback for the simplest introduction menu entry.
   *
   * The controller callback defined page_notifications.routing.yml file,
   * maps the path 'admin/page-notifications' to this method.
   *
   * @throws \InvalidArgumentException
   */
  public function basicInstructions() {
    return [
      $this->description(),
    ];
  }

  /**
   * Show a menu link in a menu other than the default "Navigation" menu.
   */
  public function alternateMenu() {
    return [
      '#markup' => $this->t('This will be in the Main menu instead of the default Tools menu'),
    ];

  }

  /**
   * A menu entry with simple permissions using 'access protected menu example'.
   *
   * @throws \InvalidArgumentException
   */
  public function permissioned() {
    $url = Url::fromRoute('page_notifications.permissioned_controlled');
    return [
      '#markup' => $this->t('A menu item that requires the "access protected menu example" permission is at @link', [
        '@link' => Link::createFromRoute($url->getInternalPath(), $url->getRouteName())->toString(),
      ]),
    ];
  }

  /**
   * Only accessible when the user will be granted with required permission.
   *
   * The permission is defined in file page_notifications.permissions.yml.
   */
  public function permissionedControlled() {
    return [
      '#markup' => $this->t('This menu entry will not show and the page will not be accessible without the "access protected menu example" permission to current user.'),
    ];
  }

  /**
   * Demonstrates the use of custom access check in routes.
   *
   * @throws \InvalidArgumentException
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::customAccessPage()
   */
  public function customAccess() {
    $url = Url::fromRoute('page_notifications.custom_access_page');
    return [
      '#markup' => $this->t('A menu item that requires the user to posess a role of "authenticated" is at @link', [
        '@link' => Link::createFromRoute($url->getInternalPath(), $url->getRouteName())->toString(),
      ]),
    ];
  }

  /**
   * Content will be displayed only if access check is satisfied.
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::customAccess()
   */
  public function customAccessPage() {
    return [
      '#markup' => $this->t('This menu entry will not be visible and access will result
        in a 403 error unless the user has the "authenticated" role. This is
        accomplished with a custom access check plugin.'),
    ];
  }

  /**
   * Give the user a link to the route-only page.
   *
   * @throws \InvalidArgumentException
   */
  public function routeOnly() {
    $url = Url::fromRoute('page_notifications.route_only.callback');
    return [
      '#markup' => $this->t('A menu entry with no menu link is at @link', [
        '@link' => Link::createFromRoute($url->getInternalPath(), $url->getRouteName())->toString(),
      ]),
    ];
  }

  /**
   * Such callbacks can be user for creating web services in Drupal 8.
   */
  public function routeOnlyCallback() {
    return [
      '#markup' => $this->t('The route entry has no corresponding menu links entry, so it provides a route without a menu link, but it is the same in every other way to the simplest example.'),
    ];
  }

  /**
   * Uses the path and title to determine the page content.
   *
   * This controller is mapped dynamically based on the 'route_callbacks:' key
   * in the routing YAML file.
   *
   * @param string $path
   *   Path/URL of menu item.
   * @param string $title
   *   Title of menu item.
   *
   * @return array
   *   Controller response.
   *
   * @see Drupal\page_notifications\Routing\PageNotificationsDynamicRoutes
   */
  public function tabsPage($path, $title) {
    $secondary = substr_count($path, '/') > 2 ? 'secondary ' : '';
    return [
      '#markup' => $this->t('This is the @secondary tab "@tabname" in the "basic tabs" example.', ['@secondary' => $secondary, '@tabname' => $title]),
    ];
  }

  /**
   * Demonstrates use of optional URL arguments in for menu item.
   *
   * @param string $arg1
   *   First argument of URL.
   * @param string $arg2
   *   Second argument of URL.
   *
   * @return array
   *   Controller response.
   *
   * @see https://www.drupal.org/docs/8/api/routing-system/parameters-in-routes
   */
  public function urlArgument($arg1, $arg2) {
    // Perpare URL for single arguments.
    $url_single = Url::fromRoute('page_notifications.use_url_arguments', ['arg1' => 'one']);

    // Prepare URL for multiple arguments.
    $url_double = Url::fromRoute('page_notifications.use_url_arguments', ['arg1' => 'one', 'arg2' => 'two']);

    // Add these argument links to the page content.
    $markup = $this->t('This page demonstrates using arguments in the url. For example, access it with @link_single for single argument or @link_double for two arguments in URL', [
      '@link_single' => Link::createFromRoute($url_single->getInternalPath(), $url_single->getRouteName(), $url_single->getRouteParameters())->toString(),
      '@link_double' => Link::createFromRoute($url_double->getInternalPath(), $url_double->getRouteName(), $url_double->getRouteParameters())->toString(),
    ]);

    // Process the arguments if they're provided.
    if (!empty($arg1)) {
      $markup .= '<div>' . $this->t('Argument 1 = @arg', ['@arg' => $arg1]) . '</div>';
    }
    if (!empty($arg2)) {
      $markup .= '<div>' . $this->t('Argument 2 = @arg', ['@arg' => $arg2]) . '</div>';
    }

    // Finally return the markup.
    return [
      '#markup' => $markup,
    ];
  }

  /**
   * Demonstrate generation of dynamic creation of page title.
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::backTitle()
   */
  public function titleCallbackContent() {
    return [
      '#markup' => $this->t('The title of this page is dynamically changed by the title callback for this route defined in menu_example.routing.yml.'),
    ];
  }

  /**
   * Generates title dynamically.
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::titleCallback()
   */
  public function titleCallback() {
    return [
      '#markup' => $this->t('The new title is your username: @name', [
        '@name' => $this->currentUser()->getDisplayName(),
      ]),
    ];
  }

  /**
   * Demonstrates how you can provide a placeholder url arguments.
   *
   * @throws \InvalidArgumentException
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::placeholderArgsDisplay()
   * @see https://www.drupal.org/docs/8/api/routing-system/using-parameters-in-routes
   */
  public function placeholderArgs() {
    $url = Url::fromRoute('page_notifications.placeholder_argument.display', ['arg' => 3343]);
    return [
      '#markup' => $this->t('Demonstrate placeholders by visiting @link', [
        '@link' => Link::createFromRoute($url->getInternalPath(), $url->getRouteName(), $url->getRouteParameters())->toString(),
      ]),
    ];
  }

  /**
   * Displays placeholder argument supplied in URL.
   *
   * @param int $arg
   *   URL argument.
   *
   * @return array
   *   URL argument.
   *
   * @see \Drupal\page_notifications\Controller\PageNotificationsController::placeholderArgs()
   */
  public function placeholderArgsDisplay($arg) {
    return [
      '#markup' => $arg,
    ];

  }

  /**
   * Demonstrate how one can alter the existing routes.
   */
  public function pathOverride() {
    return [
      '#markup' => $this->t('This menu item was created strictly to allow the RouteSubscriber class to have something to operate on. page_notifications.routing.yml defined the path as admin/menu-example/menu-original-path. The alterRoutes() changes it to /admin/menu-example/menu-altered-path. You can try navigating to both paths and see what happens!'),
    ];
  }

}
