<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

/**
 * Implements THEME_menu_local_tasks().
 */
function uikit_base_menu_local_tasks(&$variables) {
  $output = '';

  // Add UI KIT class to the tabs.
  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<nav class="inline-tab-nav"><ul class="tabs primary">';
    $variables['primary']['#suffix'] = '</ul></nav>';

    $output .= drupal_render($variables['primary']);

  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<nav class="inline-tab-nav"><ul class="tabs secondary">';
    $variables['secondary']['#suffix'] = '</ul></nav>';
    $output .= drupal_render($variables['secondary']);
  }

  // Process tabs.
  $output = _uikit_base_process_local_tasks($output);

  return $output;
}

/**
 * Implements THEME_breadcrumb().
 */
function uikit_base_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    // Process breadcrumb for UI KIT format.
    $breadcrumb_list = '<ul>';
    foreach($breadcrumb as $link) {
      $breadcrumb_list .= '<li>' . $link . '</li>';
    }
    $breadcrumb_list .= '</ul>';

    // Add UI KIT tag and style to breadcrumb.
    $output .= '<nav class="breadcrumbs" aria-label="breadcrumb"><div class="wrapper">' . $breadcrumb_list . '</div></nav>';
    return $output;
  }
}

/**
 * Implements THEME_preprocess_page().
 */
function uikit_base_preprocess_page(&$variables) {  
  // Get classes for <main> together
  $variables['main_classes'] = array('main');
  // Position sidebar based on theme settings
  if (theme_get_setting('sidebar_position') == 'left') {
    $variables['main_classes'][] = 'sidebar-has-controls';
  }
  $variables['main_classes'] = implode(' ', $variables['main_classes']);
}

/**
 * Implements THEME_preprocess_block().
 */
function uikit_base_preprocess_block(&$variables) {
  // Drupal menu blocks, and Menu Block's blocks, share the same template file
  // to apply the <nav> element.
  if (in_array($variables['block']->module, array('menu', 'menu_block'))) {
    array_unshift($variables['theme_hook_suggestions'], 'block__menu_generic');
  }
}

/**
 * Implements THEME_preprocess_region().
 */
function uikit_base_preprocess_region(&$variables) {

  // Add UI KIT nav menu class
  if ($variables['region'] == 'sidebar') {
    $variables['classes_array'][] = 'local-nav';
  }

  // Drop in the footer layout classes
  if (in_array($variables['region'], array('footer_top', 'footer_bottom'))) {
    $variables['classes_array'][] = theme_get_setting($variables['region'] . '_layout');
  }

}

/**
 * Implements THEME_preprocess_node().
 */
function uikit_base_preprocess_node(&$variables) {
  // Apply the UI KIT list horizontal style to single node display by default.
  $variables['classes_array'][] = 'list-horizontal';

  // Add UI KIT class to author and date information.
  $variables['submitted'] = '<div class="meta">' . t('Submitted by !author on !date', array('!date' => '<time>' . $variables['date'] .'</time>', '!author' => $variables['name']));

  // Add UI KIT class to readmore link in teaser view mode.
  if (!empty($variables['content']['links']['node']['#links']['node-readmore'])) {
    $variables['content']['links']['node']['#links']['node-readmore']['attributes']['class'] = 'see-more';
  }
}

/**
 * Implements THEME_preprocess_field().
 */
function uikit_base_preprocess_field(&$variables) {
  if ($variables['element']['#field_name'] == 'field_tags') {
    $variables['classes_array'][] = 'tags';
  }
}

/**
 * Implements THEME_preprocess_views_view_table().
 */
function uikit_base_preprocess_views_view_table(&$vars) {
  // Add UI KIT table class to views table.
  $vars['classes_array'][] = 'content-table';
}

/**
 * Implements THEME_preprocess_form_element().
 */
function uikit_base_preprocess_form_element(&$variables) {
  $variables['element']['#children'] = str_replace('required error', 'required error invalid', $variables['element']['#children']);
}

/**
 * Implements THEME_pager().
 */
function uikit_base_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }

    // Swap the core pager class with UI KIT inline-links class.
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('inline-links')),
    ));
  }
}

/**
 * Implements THEME_status_messages().
 */
function uikit_base_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );

  // Map the UI Kit classes to drupal
  $ui_kit_statuses = array(
    'status' => 'callout--success',
    'error' => 'callout--error',
    'warning' => 'callout--warning',
  );

  foreach (drupal_get_messages($display) as $type => $messages) {
    // Add UI KIT index-link class to the message div.
    $output .= "<div class=\"messages $ui_kit_statuses[$type] index-links\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= reset($messages);
    }
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Helper function to add is-current class to the active link.
 *
 * @param $children
 *   The origin link html.
 *
 * @return mixed
 *   The processed link html.
 */
function _uikit_base_process_local_tasks($children) {
  $output = str_replace('class="active"', 'class="active is-current"', $children);

  return $output;
}
