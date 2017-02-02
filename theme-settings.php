<?php
/**
 * @file
 * Theme settings.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function uikit_base_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // The maximum height for the logo in the header
  $form['logo']['logo_max_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Maximum height'),
    '#default_value' => theme_get_setting('logo_max_height'),
    '#field_suffix' => 'px',
    '#size' => 5,
    '#description' => t('Limit the height of the logo in the header, aspect ratio will be maintained.'),
  );

  // UI KIT settings.
  $form['uikit_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('UI KIT settings'),
    '#description' => t('Layout and component settings for UI KIT.'),
  );

  // Footer layout
  $options = array(
    'horizontal' => t('Horizontal'),
    'vertical' => t('Vertical'),
  );

  $form['footer_layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Footer content layout'),
    '#description' => t('These settings control how the contents of the footer top and bottom regions are laid out.'),
  );

  $form['footer_layout']['footer_top_layout'] = array(
    '#type' => 'radios',
    '#title' => t('Footer top content layout'),
    '#options' => $options,
    '#default_value' => theme_get_setting('footer_top_layout'),
  );

  $form['footer_layout']['footer_bottom_layout'] = array(
    '#type' => 'radios',
    '#title' => t('Footer bottom content layout'),
    '#options' => $options,
    '#default_value' => theme_get_setting('footer_bottom_layout'),
  );
}
