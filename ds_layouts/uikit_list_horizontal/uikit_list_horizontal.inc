<?php
function ds_uikit_list_horizontal() {
  return array(
    'label' => t('UI KIT: List - Horizontal'),
    'regions' => array(
      'figure'  => t('Figure'),
      'title' => t('Title'),
      'meta' => t('Meta'),
      'main' => t('Content'),
      'footer' => t('Footer'),
    ),
    'image' => TRUE,
  );
}
