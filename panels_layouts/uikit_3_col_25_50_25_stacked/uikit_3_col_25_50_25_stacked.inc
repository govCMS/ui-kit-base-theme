<?php

/**
 * @file
 * Panels layout with 3 stacked columns the left and right 25% wide and the
 * center column 50% wide using UI Kit grid.
 *
 * +-----------+
 * |           |
 * +-----------+
 * |  |     |  |
 * |  |     |  |
 * +-----------+
 * |           |
 * +-----------+
 */

$human_name   = '3 col stacked - 25% 50% 25%';
$machine_name = 'uikit_3_col_25_50_25_stacked';

$rows_cols = array(
  array(
    'top'    => array('name' => t('Top'),    'grid' => 'col-md-12'),
  ),
  array(
    'left'   => array('name' => t('Left'),   'grid' => 'col-md-3'),
    'middle' => array('name' => t('Middle'), 'grid' => 'col-md-6'),
    'right'  => array('name' => t('Right'),  'grid' => 'col-md-3'),
  ),
  array(
    'bottom' => array('name' => t('Bottom'), 'grid' => 'col-md-12'),
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
