<?php
function ds_uikit_list_vertical() {
  return array(
    'label' => t('UI KIT: List - Vertical'),
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
