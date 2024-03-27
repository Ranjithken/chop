<?php

namespace Drupal\performance_profiler\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Configure Memory profiler module settings.
 *
 * @internal
 */
class PerformanceProfilerForm extends ConfigFormBase {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'performance_profiler_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['performance_profiler.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $site_config = $this->config('performance_profiler.settings');

    $form['appearance'] = [
      '#type' => 'details',
      '#title' => $this->t('Appearance'),
      '#open' => TRUE,
    ];
    $form['appearance']['watchdog'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log statistics into watchdog'),
      '#default_value' => $site_config->get('watchdog'),
      '#description' => $this->t('If checked, the message will be logged via watchdog system.'),
    ];
    $form['appearance']['toolbar'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log statistics into Toolbar'),
      '#default_value' => $site_config->get('toolbar'),
      '#description' => $this->t('If checked, the message will be printed at the Toolbar header.'),
    ];
    if (!$this->moduleHandler->moduleExists('toolbar')) {
      $form['appearance']['toolbar']['#attributes'] = ['disabled' => 'disabled'];
      $form['appearance']['toolbar']['#default_value'] = 0;
    }
    $form['appearance']['popup'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log statistics into Popup'),
      '#default_value' => $site_config->get('popup'),
      '#description' => $this->t('If checked, the message will be printed at the bottom right corner.'),
    ];
    $form['appearance']['anonymous'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log statistics for anonymous user'),
      '#default_value' => $site_config->get('anonymous'),
      '#description' => $this->t('If checked, each request from anonymous user will be logged.'),
    ];

    $form['entries'] = [
      '#type' => 'details',
      '#title' => $this->t('Log entries'),
      '#open' => TRUE,
    ];
    $form['entries']['database'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log Database queries'),
      '#default_value' => $site_config->get('database'),
      '#description' => $this->t('Log DB queries statistics (count of read and write queries, execution time).'),
    ];
    $form['entries']['self'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log self AJAX query'),
      '#default_value' => $site_config->get('self'),
      '#description' => $this->t('Log query to get data from Performance profiler, usually should be disabled.'),
    ];
    $form['entries']['ajax'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log AJAX queries'),
      '#default_value' => $site_config->get('ajax'),
      '#description' => $this->t('Log XHR queries (contextual, overlay, etc).'),
    ];

    $form['track'] = [
      '#type' => 'details',
      '#title' => $this->t('Track'),
      '#open' => TRUE,
    ];
    $form['track']['memory'] = [
      '#type' => 'number',
      '#title' => $this->t('Min memory usage to track'),
      '#default_value' => $site_config->get('memory'),
      '#description' => $this->t('If not empty or more then 0, will be tracked only higher values.'),
      '#field_suffix' => 'Mb',
      '#size' => 10,
      '#min' => 0,
      '#step' => 0.1,
    ];
    $form['track']['time'] = [
      '#type' => 'number',
      '#title' => $this->t('Min execution time to track'),
      '#default_value' => $site_config->get('time'),
      '#description' => $this->t('If not empty or more then 0, will be tracked only higher values (e.g. 4 or 5.5).'),
      '#field_suffix' => 'seconds',
      '#size' => 10,
      '#min' => 0,
      '#step' => 0.1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('performance_profiler.settings')
      ->set('ajax', $form_state->getValue('ajax'))
      ->set('anonymous', $form_state->getValue('anonymous'))
      ->set('database', $form_state->getValue('database'))
      ->set('memory', $form_state->getValue('memory'))
      ->set('popup', $form_state->getValue('popup'))
      ->set('self', $form_state->getValue('self'))
      ->set('time', $form_state->getValue('time'))
      ->set('toolbar', $form_state->getValue('toolbar'))
      ->set('watchdog', $form_state->getValue('watchdog'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
