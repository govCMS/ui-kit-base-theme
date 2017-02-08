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

  // When using SVG logos, we need the user to set the maximum width for the
  // logo so that it's not made too small by the other header elements. If the
  // container is smaller than this, the logo will scale (mobile for example).
  $form['logo']['logo_max_width'] = array(
    '#type' => 'textfield',
    '#title' => t('Maximum width'),
    '#default_value' => theme_get_setting('logo_max_width'),
    '#field_suffix' => 'px',
    '#size' => 5,
    '#description' => t('The maximum width of the logo in the header, aspect ratio will be maintained.'),
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

  $form['full_width_pages'] = array(
    '#type' => 'textarea',
    '#title' => t('Full width pages'),
    '#default_value' => theme_get_setting('full_width_pages'),
    '#description' => t('Enter a list of paths on which the main content area should take up 100% of available width.  Enter one path per line, wildcards are allowed.'),
  );
}
