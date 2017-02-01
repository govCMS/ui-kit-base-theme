<?php

/**
 * @file
 * Panels brick layout with 2 columns using UI Kit grid.
 *
 * +-----------+
 * +-----------+
 * |     |     |
 * +-----------+
 * +-----------+
 * |     |     |
 * +-----------+
 * +-----------+
 */

$human_name   = '2 col bricks';
$machine_name = 'uikit_2_col_bricks';

$rows_cols = array(
  array(
    'top'           => array('name' => t('Top'),           'grid' => 'col-md-12'),
  ),
  array(
    'top_left'      => array('name' => t('Top left'),      'grid' => 'col-md-6'),
    'top_right'     => array('name' => t('Top right'),     'grid' => 'col-md-6'),
  ),
  array(
    'middle'        => array('name' => t('Middle'),        'grid' => 'col-md-12'),
  ),
  array(
    'bottom_left'   => array('name' => t('Bottom left'),   'grid' => 'col-md-6'),
    'bottom_right'  => array('name' => t('Bottom right'),  'grid' => 'col-md-6'),
  ),
  array(
    'bottom'        => array('name' => t('Bottom'),        'grid' => 'col-md-12'),
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
