<?php
function ds_uikit_horizontal_image_col() {
  return array(
    'label' => t('UI KIT horizontal list with image'),
    'regions' => array(
      'left' => t('Figure'),
      'right' => t('Article'),
    ),
    'image' => TRUE,
  );
}
