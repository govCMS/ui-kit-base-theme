<?php

/**
 * @file
 * Panels layout with 2 columns using UI Kit grid.
 *
 * +-----------+
 * |     |     |
 * |     |     |
 * |     |     |
 * |     |     |
 * |     |     |
 * |     |     |
 * +-----------+
 */

$human_name   = '2 col';
$machine_name = 'uikit_2_col';

$rows_cols = array(
  array(
    'left'  => array('name' => t('Left'),  'grid' => 'col-md-6'),
    'right' => array('name' => t('Right'), 'grid' => 'col-md-6'),
  ),
);

/******************************************************************************/

// All Panels layouts are prepared and rendered by helper functions in
// template.php. We include template.php file manually if the functions are not
// defined as the panel preview can be rendered in the admin theme.
if (!function_exists('_uikit_base_render_panel_layout')) {
  include_once(DRUPAL_ROOT . '/' . drupal_get_path('theme', 'uikit_base') . '/template.php');
}

$plugin = _uikit_base_prepare_panel_layout_array($human_name, $machine_name, $rows_cols);
