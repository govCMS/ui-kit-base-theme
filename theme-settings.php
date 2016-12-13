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

  // UI KIT settings.
  $form['uikit_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('UI KIT settings'),
    '#description' => t('Layout and component settings for UI KIT.'),
  );

  // Select list for sidebar position.
  $form['uikit_settings']['sidebar_position'] = array(
    '#type' => 'select',
    '#title' => t('Sidebar position'),
    '#options' => array(
      'left' => t('Left'),
      'right' => t('Right'),
    ),
    '#default_value' => is_null(theme_get_setting('sidebar_position')) ? 'right' : theme_get_setting('sidebar_position'),
    '#description' => t('Indicating the position of the sidebar.'),
  );
}
