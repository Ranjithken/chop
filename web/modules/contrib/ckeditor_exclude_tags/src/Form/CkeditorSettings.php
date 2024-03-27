<?php

namespace Drupal\ckeditor_exclude_tags\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class CkeditorSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ckeditor_exclude_tags_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ckeditor_exclude_tags.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ckeditor_exclude_tags.settings');

    $form['tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Filters'),
    ];

    // Loop through all text formats.
    $filter_formats = filter_formats();

    foreach ($filter_formats as $filter_name => $filter_format) {
      $editor = editor_load($filter_name);
      if (is_null($editor)) {
        continue;
      }
      $editor_name = $editor->getEditor();
      $editor_readable_name = $filter_format->get('name');
      // Only proceed if the editor is 'ckeditor'.
      if ($editor_name == 'ckeditor') {
        $form[$filter_name] = [
          '#type' => 'details',
          '#title' => $editor_readable_name,
          '#group' => 'tabs',
        ];
        $form[$filter_name][$filter_name] = [
          '#type' => 'textarea',
          '#default_value' => $config->get($filter_name),
          '#title' => $editor_readable_name,
          '#description' => $this->t('Add multiple tags with space.<br>Example: div span script'),
        ];
      }
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Loop through all text formats.
    $filter_formats = filter_formats();

    foreach ($filter_formats as $filter_name => $filter_format) {
      $editor = editor_load($filter_name);
      if (is_null($editor)) {
        continue;
      }
      $this->config('ckeditor_exclude_tags.settings')
        ->set($filter_name, trim($form_state->getValue($filter_name)))
        ->save();

    }
    parent::submitForm($form, $form_state);
  }

}
