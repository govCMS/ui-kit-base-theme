<?php

/**
 * @file
 * Contains the theme's functions to override markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/** Core hooks ****************************************************************/

/**
 * Implements THEME_form_alter().
 */
function uikit_base_form_alter(&$form, &$form_state, $form_id) {

  // If this form is a search api form, we want to remove the size attribute
  // on the text input, it makes styling difficult. We also update the
  // placeholder and apply a class to the form for targeting in JS.
  if (strpos($form_id, 'search_api') !== FALSE) {
    $search_api_form_id = $form['id']['#value'];
    unset($form['keys_' . $search_api_form_id]['#size']);
    unset($form['keys_' . $search_api_form_id]['#attributes']['placeholder']);
    $form['#attributes']['class'] = 'search-form';
  }

}


/** Core pre-process functions ************************************************/

/**
 * Implements THEME_preprocess_html().
 */
function uikit_base_preprocess_html(&$variables) {
  drupal_add_css('https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&subset=latin-ext', array('type' => 'external'));
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
 * Implements THEME_preprocess_node().
 */
function uikit_base_preprocess_node(&$variables) {
  // Add UI KIT class to author and date information.
  $variables['submitted'] = '<div class="meta">' . t('Submitted by !author on <time>!date</time>', array('!date' => $variables['date'], '!author' => $variables['name']));

  // Add UI KIT class to readmore link in teaser view mode.
  if (!empty($variables['content']['links']['node']['#links']['node-readmore'])) {
    $variables['content']['links']['node']['#links']['node-readmore']['attributes']['class'] = 'see-more';
  }
}

/**
 * Implements THEME_preprocess_page().
 */
function uikit_base_preprocess_page(&$variables) {
  $variables['page']['header'] = _uikit_base_preprocess_region_header($variables['page']['header']);
}

/**
 * Implements THEME_preprocess_maintenance_page().
 */
function uikit_base_preprocess_maintenance_page(&$variables) {
  $variables['header'] = _uikit_base_preprocess_region_header();
}

/**
 * Implements THEME_preprocess_block().
 */
function uikit_base_preprocess_block(&$variables) {

  $block = $variables['block'];

  // Add some classes to the block title and content wrapper
  $variables['title_attributes_array']['class'] = 'block__title';
  $variables['content_attributes_array']['class'] = 'block__content content';

  // Drupal menu blocks and the Menu Block module's blocks share the same
  // template file to apply the <nav> element.  We also switch template file if
  // the block is in a sidebar.
  if (
    in_array($block->module, array('menu', 'menu_block'))
    || ($block->module == 'system' && $block->delta == 'main-menu')
  ) {
    if (in_array($block->region, array('sidebar_left', 'sidebar_right'))) {
      array_unshift($variables['theme_hook_suggestions'], 'block__menu_generic_sidebar');
    }
    else {
      array_unshift($variables['theme_hook_suggestions'], 'block__menu_generic');
    }
  }

}

/**
 * Implements THEME_preprocess_region().
 */
function uikit_base_preprocess_region(&$variables) {
  // Drop in the footer layout classes
  if (in_array($variables['region'], array('footer_top', 'footer_bottom'))) {
    $variables['classes_array'][] = 'region--' . theme_get_setting($variables['region'] . '_layout');
  }
}


/** Theme functions ***********************************************************/

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
    foreach ($breadcrumb as $link) {
      $breadcrumb_list .= '<li>' . $link . '</li>';
    }
    $breadcrumb_list .= '</ul>';

    // Add UI KIT tag and style to breadcrumb.
    $output .= '<nav class="breadcrumbs" aria-label="breadcrumb"><div class="wrapper">' . $breadcrumb_list . '</div></nav>';
    return $output;
  }
}

/**
 * Implements THEME_menu_local_tasks().
 */
function uikit_base_menu_local_tasks($variables) {
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
 * Implements THEME_link().
 */
function uikit_base_link($variables) {
  // Check link classes.
  if (isset($variables['options']['attributes']['class'])) {
    $classes = $variables['options']['attributes']['class'];

    // Compose the class array if single string given.
    if (!is_array($classes)) {
      $classes = array($classes);
    }

    // The class pairs we need to add.
    $class_pairs = array(
      'active' => 'is-current',
      'active-trail' => 'is-active',
    );

    // Add additional UI KIT classes.
    $variables['options']['attributes']['class'] = _uikit_base_active_link($class_pairs, $classes);
  }

  // Default theme_link() function.
  return '<a href="' . check_plain(url($variables['path'], $variables['options'])) . '"' .
  drupal_attributes($variables['options']['attributes']) . '>' . ($variables['options']['html'] ?
    $variables['text'] : check_plain($variables['text'])) . '</a>';
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
    $output .= "<div class=\"messages $ui_kit_statuses[$type]\">\n";
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
 * Implements THEME_form_element_label().
 */
function uikit_base_form_element_label($variables) {
  $element = $variables['element'];

  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  $title = filter_xss_admin($element['#title']);

  // If the element is not required, add (optional) to the end of the label, but
  // not to elements that a are children of another element (like single radios
  // in a radio group) and not for disabled fields.
  $optional_label = '';
  if (empty($element['#required']) && empty($element['#disabled'])) {

    // Field it not required, so we'll start with the normal optional label.
    $optional_label = '(optional)';

    // If this form element has multiple parents, then any label would be
    // applied to the parent element so we don't apply it here.
    if (count($element['#array_parents']) > 1) {
      $optional_label = '';
    }

    // If the label ends with a period, we need to put (optional) before that
    // period or it will look strange.
    if (!empty($optional_label) && substr($title, -1) == '.') {
      $title =  substr($title, 0, -1);
      $optional_label .= '.';
    }

  }

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label' . drupal_attributes($attributes) . '>' . $t('!title !optional', array('!title' => $title, '!optional' => $optional_label)) . "</label>\n";
}

/**
 * Implements THEME_form_element().
 */
function uikit_base_form_element($variables) {
  $element = &$variables['element'];

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array('#title_display' => 'before');

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  // Add a class to the form wrapper if this element has attracted an error
  if (strpos($element['#children'], 'error') !== FALSE) {
    $attributes['class'][] = 'form-error';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // Add UI Kit error classes to the form element if it has attracted an error
  $element['#children'] = str_replace('error', 'error invalid', $element['#children']);

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  $description = '';
  if (!empty($element['#description'])) {
    $id = !empty($element['#id']) ? ' id="hint-' . $element['#id'] . '"' : '';
    $description = '<span class="hint" ' . $id . '>' . $element['#description'] . "</span>\n";
  }
  $description_position = 'before';
  if (in_array($element['#type'], array('radio', 'checkbox'))) {
    $description_position = 'after';
  }

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix;
      if ($description_position == 'before') {
        $output .= $description;
      }
      $output .= $element['#children'];
      if ($description_position == 'after') {
        $output .= $description;
      }
      $output .= $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix;
      if ($description_position == 'before') {
        $output .= $description;
      }
      $output .= $element['#children'];
      $output .= $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      if ($description_position == 'after') {
        $output .= $description;
      }
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix;
      if ($description_position == 'before') {
        $output .= $description;
      }
      $output .= $element['#children'];
      if ($description_position == 'after') {
        $output .= $description;
      }
      $output .= $suffix . "\n";
      break;
  }

  $output .= "</div>\n";

  return $output;
}

/**
 * Implements THEME_fieldset().
 */
function uikit_base_fieldset($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id'));
  _form_set_class($element);

  $output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';
  if (!empty($element['#title'])) {
    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';
  }
  $output .= '<div class="fieldset-wrapper">';
  if (!empty($element['#description'])) {
    $output .= '<div class="fieldset-description hint">' . $element['#description'] . '</div>';
  }
  $output .= $element['#children'];
  if (isset($element['#value'])) {
    $output .= $element['#value'];
  }
  $output .= '</div>';
  $output .= "</fieldset>\n";
  return $output;
}


/** Contrib Theme functions ***************************************************/

/**
 * Implement THEME_toc_filter().
 */
function uikit_base_toc_filter($variables) {
  $output = '';
  $output .= '<nav class="index-links">';
  $output .= '<h2 id="index-links">' . t('Contents') . '</h2>';
  $output .= $variables['content'];
  $output .= '</nav>';
  return $output;
}

/**
 * Implements THEME_toc_filter_back_to_top().
 */
function uikit_base_toc_filter_back_to_top($variables) {
  return '<span class="back-to-index-link"><a href="#index-links">' . t('Back to contents ↑') . '</a></span>';
}


/** Helper functions **********************************************************/

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
  preg_replace('/(?:class="[^"]*?\b)(active)\b/i', 'active is-current', $children);
  return $children;
}

/**
 * Get's the header content together.
 *
 * Turn the logo from a URL into an image within a link, and also scale it so
 * that it's no taller than specified in the theme settings.
 *
 * This may be useful to users who do not have the ability to adjust the image
 * size. It also allows the use of svg images where the ability set the image
 * size to a maximum height is useful.
 *
 * This can be overridden in CSS at various breakpoints if required for those
 * users who want to customise the theme.
 *
 * @param string $header_content
 *   The content that should go in the header's content region.
 *
 * @return string
 *   The header region content.
 *
 * @see uikit_base_preprocess_page().
 */
function _uikit_base_preprocess_region_header($header_content = '') {
  $site_name   = variable_get('site_name', '');
  $site_slogan = variable_get('site_slogan', '');
  $output      = '';

  // Do we want to show a logo?
  if (theme_get_setting('toggle_logo')) {

    $logo = theme_get_setting('logo');

    // Attempt to get the width and height of the logo.
    $max_height = theme_get_setting('logo_max_height');
    list($width, $height) = getimagesize($logo);

    // If we're dealing with an SVG, the width and height will be null, so we set
    // a height and get the browser to pick up the width.
    if (is_null($width) && is_null($height)) {
      $height = $max_height;
    }

    // Bitmap images will give us values.
    elseif ($height > $max_height) {
      $ratio  = $width / $height;
      $height = $max_height;
      $width  = round($height * $ratio);
    }

    // Create the image using theme_image().
    $logo = theme('image', array(
      'path'   => $logo,
      'alt'    => t('@site_name logo', array('@site_name' => $site_name)),
      'title'  => filter_xss($site_name),
      'width'  => $width,
      'height' => $height,
    ));

    // Inline styling to prevent SVG container from collapsing and making the
    // logo smaller or distorting it.
    $output .= '<div class="page-header__logo" style="min-width: ' . $width . 'px">';
    $output .= l($logo, '<front>', array('html' => TRUE));
    $output .= '</div>';

  }

  // Do we need to show additional info?
  $show_site_name   = theme_get_setting('toggle_name');
  $show_site_slogan = theme_get_setting('toggle_slogan');

  $output .= '<div class="page-header__site-info">';

  // Do we want to show a site name?
  if ($show_site_name) {
    $output .= '<div class="page-header__site-title">' . l($site_name, '<front>') . '</div>';
  }
  // Do we want to show a site slogan?
  if (!empty($site_slogan) && $show_site_slogan) {
    $output .= '<div class="page-header__site_slogan">' . filter_xss($site_slogan) . '</div>';
  }

  $output .= '</div>';

  $output .= '<div class="page-header__content">';
  if (is_array($header_content)) {
    $output .= drupal_render($header_content);
  }
  else {
    $output .= $header_content;
  }
  $output .= '</div>';

  return $output;
}

/**
 * Helper function to add UI KIT link class to link.
 *
 * @param $class_pairs
 *   The pairs of Drupal class and UI KIT class.
 *
 * @param $classes
 *   Origin class array from Drupal.
 *
 * @return array
 *   Class array.
 */
function _uikit_base_active_link($class_pairs, $classes) {
  foreach ($class_pairs as $needle => $additional_class) {
    if (in_array($needle, $classes)) {
      $classes[] = $additional_class;
    }
  }
  return $classes;
}

/**
 * Renders all of the grid based Panel layouts.
 *
 * This function is called from the Panels layout's include files.
 *
 * @param array $variables
 *   The $variables array made available in the layout template file.
 *
 * @return string
 *   The panel markup
 */
function _uikit_base_render_panel_layout($variables) {
  $attributes = array('class' => 'layout__' . $variables['classes']);
  if (!empty($variables['css_id'])) {
    $attributes['id'] = $variables['css_id'];
  }

  $output  = '';
  $output .= '<div' . drupal_attributes($attributes) . '>';
  $output .= _uikit_base_render_panel_layout_build_grid($variables['layout']['grid'], $variables['content']);
  $output .= '</div>';

  return $output;
}

/**
 * Builds Panel layout markup based on Bootstrap classes
 *
 * @see _uikit_base_render_panel_layout().
 *
 * @param array $grid
 *
 * @param array $content
 *
 * @return string
 */
function _uikit_base_render_panel_layout_build_grid($grid, $content) {
  $output  = '';

  foreach ($grid as $row => $columns) {

    $output .= '  <div class="row">';

    foreach ($columns as $key => $data) {

      // $data is an array of child rows/cols and grid info, $key is a delta
      if (is_array($data)) {
        $output .= '<div class="' . $data['grid'] . '">';
        $output .= _uikit_base_render_panel_layout_build_grid($data['children'], $content);
        $output .= '</div>';
      }

      // $data is grid class, $key is the panel machine name
      else {
        $attributes = array(
          'class' => array(
            'layout__region',
            'layout__region--' . $key,
            $data,
          ),
        );

        $output .= '    <div' . drupal_attributes($attributes) . '>';
        $output .= $content[$key];
        $output .= '    </div>';
      }
    }

    $output .= '  </div>';

  }

  return $output;
}

/**
 * Prepares a panels layout plugin array.
 *
 * @param string $human_name
 *   The human readable name of the layout
 *
 * @param string $machine_name
 *   The machine name of the layout
 *
 * @param array $rows_cols
 *   The region definitions in a nested array of rows and columns.
 *
 * @param string $category
 *   The category this layout belongs to, defaults to 'UI Kit'.
 *
 * @return array
 *   The Panels plugin definition
 */
function _uikit_base_prepare_panel_layout_array($human_name, $machine_name, $rows_cols, $category = null) {
  if (empty($category)) {
    $category = t('UI Kit');
  }

  $plugin = array(
    'title'     => $human_name,
    'category'  => $category,
    'icon'      => $machine_name . '.png',
    'theme'     => $machine_name,
    'regions'   => array(),
    'bootstrap' => array(),
  );

  $data = _uikit_base_prepare_panel_layout_array_extract_layout($rows_cols);

  $plugin = array_merge($plugin, $data);

  return $plugin;
}

/**
 * Extracts the region and grid configuration from a nested Panels layout
 * declaration.
 *
 * @see _uikit_base_prepare_panel_layout_array().
 *
 * @param array $rows_cols
 *   An nested array of row and column data
 *
 * @return array
 *   An array with two keys 'regions' and 'grid'.
 */
function _uikit_base_prepare_panel_layout_array_extract_layout($rows_cols) {

  $retval = array(
    'regions' => array(),
    'grid'    => array(),
  );

  foreach ($rows_cols as $delta => $row) {

    $retval['grid'][$delta] = array();

    foreach ($row as $key => $data) {

      // If data contains a name key, this is a panel pane
      if (!empty($data['name'])) {
        $retval['regions'][$key] = $data['name'];
      }

      // If data contains a grid key, this is part of the grid
      if (!empty($data['grid'])) {
        $retval['grid'][$delta][$key] = $data['grid'];
      }

      // if data contains children, there is a sub-grid
      if (!empty($data['children'])) {
        $returned = _uikit_base_prepare_panel_layout_array_extract_layout($data['children']);
        $retval['grid'][$delta][$key] = array(
          'grid'     => $retval['grid'][$delta][$key],
          'children' => array($returned['grid'][$delta]),
        );
        $retval['regions'] += $returned['regions'];
      }
    }
  }

  return $retval;
}
