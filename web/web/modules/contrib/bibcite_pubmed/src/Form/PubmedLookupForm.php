<?php

namespace Drupal\bibcite_pubmed\Form;

use Drupal\bibcite_entity\Entity\Reference;
use Drupal\bibcite_pubmed\PubmedClientInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStore;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * PubMed lookup form.
 */
class PubmedLookupForm extends FormBase {

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * Module temp store.
   *
   * @var \Drupal\user\PrivateTempStore
   */
  protected $tempStore;

  /**
   * PubMed client service.
   *
   * @var \Drupal\bibcite_pubmed\PubmedClientInterface
   */
  protected $pubmedClient;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * PubMed lookup form constructor.
   *
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   Import plugins manager.
   * @param \Drupal\Core\TempStore\PrivateTempStore $temp_store
   *   Module temp store.
   * @param \Drupal\bibcite_pubmed\PubmedClientInterface $pubmed_client
   *   PubMed client service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(SerializerInterface $serializer, PrivateTempStore $temp_store, PubmedClientInterface $pubmed_client, ModuleHandlerInterface $module_handler) {
    $this->serializer = $serializer;
    $this->tempStore = $temp_store;
    $this->pubmedClient = $pubmed_client;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer'),
      $container->get('tempstore.private')->get('bibcite_pubmed_lookup'),
      $container->get('bibcite_pubmed.client'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_pubmed_lookup';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['pmid'] = [
      '#type' => 'textfield',
      '#title' => t('PubMed ID'),
      '#required' => TRUE,
      '#default_value' => '',
      '#description' => t('Enter a PubMed ID'),
      '#size' => 60,
      '#maxlength' => 255,
      '#weight' => -4,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Populate using PubMed'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $pmid = $form_state->getValue('pmid');

    try {
      $data = $this->pubmedClient->fetch($pmid);

      // Respect contributor and keyword deduplication settings.
      // @todo This should not depend on the bibcite_import module existence.
      if ($this->moduleHandler->moduleExists('bibcite_import')) {
        $config = \Drupal::config('bibcite_import.settings');
        $denormalize_context = [
          'contributor_deduplication' => $config->get('settings.contributor_deduplication'),
          'keyword_deduplication' => $config->get('settings.keyword_deduplication'),
        ];
      }
      else {
        $denormalize_context = [];
      }

      $decoded = $this->serializer->decode($data, 'pubmed');
      $entity = $this->serializer->denormalize(reset($decoded), Reference::class, 'pubmed', $denormalize_context);
      $form_state->setValue('entity', $entity);
    }
    catch (\Exception $exception) {
      $err_string = $this->t('Error has occured:<br>%ex', ['%ex' => $exception->getMessage()]);
      $form_state->setErrorByName('pmid', $err_string);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $form_state->getValue('entity');

    if ($entity) {
      $this->tempStore->set(\Drupal::currentUser()->id(), $entity);

      $redirect_url = Url::fromRoute("entity.bibcite_reference.add_form", [
        'bibcite_reference_type' => $entity->bundle(),
      ]);
      $form_state->setRedirectUrl($redirect_url);
    }
  }

}
