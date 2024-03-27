<?php
namespace Drupal\custom_menu_breadcrumbs\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Driver\Exception\Exception;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;

class CustomMenuBreadcrumbsBuilder implements BreadcrumbBuilderInterface{

  private $typeName;
  private $menuName;
  private $menuId;
  private $config;
  private $prefix;


  public function __construct() {
    $this->config = \Drupal::config('custom_menu_breadcrumbs.settings');
    $this->prefix = 'type_';
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $attributes) {
    $parameters = $attributes->getParameters()->all();
    $type = key($parameters);
	$route_name = $attributes->getRouteName();
    if (in_array($type, ["user", "node"])) {
      foreach ($this->config->get('custom_menu_breadcrumbs') as $key => $value) {
        if (empty($value)) continue;
        if (substr($key, 0, 5) !== $this->prefix) continue;
		 if ($type == "node" && method_exists($parameters[$type], 'getType') !== TRUE) {
           continue;
          }
		if($route_name == 'entity.node.revision' || $route_name == 'node.revision_revert_confirm') continue;
        if ($type =="node" && $parameters[$type]->getType() == str_replace($this->prefix, '', $key) ||
          ($type =="user" && $type == str_replace($this->prefix, '', $key))) {
          $this->typeName = str_replace($this->prefix, '', $key);
          $this->menuId = $value;
          return true;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {

    $request = \Drupal::request();
    $menu_link_manager = \Drupal::service('plugin.manager.menu.link');

    $this->menuName = $this->config->get('custom_menu_breadcrumbs.menu_name');

    try {
      $breadcrumb = new Breadcrumb();

      // get starting menu object and iterate to get all parents
      $plugin_id = str_replace("{$this->menuName}:", '', $this->menuId);

      if (count(explode(':', $plugin_id)) == 1) return $breadcrumb;
      
      // Iterate to get all parents
      $menu_tree = [];
      $id = $plugin_id;


      do {
        $parent_menu = $menu_link_manager->createInstance($id);
        if ($parent_menu) {
          $menu_tree[$parent_menu->getTitle()] = $parent_menu->getUrlObject();
          $obj = $this->getMenuObject($parent_menu);
          $links[] = Link::createFromRoute($obj['title'], $obj['url'], $obj['url_param']);
          $id = $parent_menu->getPluginId();
        }
      } while (!empty($id = $menu_link_manager->createInstance($id)->getParent()));

      // Home
      $links[] = Link::createFromRoute('Home', '<front>');

      $reversed = array_reverse($links);
      $breadcrumb->setLinks($reversed);

      // Finally, attach current page title and make it nolink
      $page_title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
      $breadcrumb->addLink(Link::createFromRoute($page_title, '<nolink>'));
    } catch (\Exception $e) {
      // log to watchdog
      $breadcrumb->addLink(Link::createFromRoute($e->getMessage(), '<nolink>'));
      \Drupal::logger('php')->notice('custom_menu_breadcrumb error: %message',
        ['%message' =>  $e->getMessage()]);
      return $breadcrumb;
    }

    // This breadcrumb builder is based on a route parameter, and hence it
    // depends on the 'route' cache context.
    $breadcrumb->addCacheContexts(['route']);

    return $breadcrumb;
  }


  private function getMenuObject(MenuLinkContent $instance) {
    $object=[
      'title' => $instance->getTitle(),
      'url' => $instance->getUrlObject()->getRouteName(),
      'url_param' => $instance->getUrlObject()->getRouteParameters(),
    ];
    return $object;
  }
}

