<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 *
 * @ingroup themeable
 */
?><!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" id="maintenance-page">

  <header class="header" id="header" role="banner">
    <section class="page-header">
      <div class="wrapper">
        <?php print render($header); ?>
      </div>
    </section>
  </header>

  <main id="container" role="main">

    <?php if ($sidebar_left): ?>
    <aside class="sidebar__left" role="complementary">
      <?php print $sidebar_left; ?>
    </aside>
    <?php endif; ?>

    <article id="content" class="content-main">

      <div id="main">

        <div id="content" class="column">

          <?php if (!empty($title)): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
          <?php if (!empty($messages)): print $messages; endif; ?>
          <?php print $content; ?>

        </div>

      </div>

    </article>

    <?php if ($sidebar_right): ?>
    <aside class="sidebar__right" role="complementary">
      <?php print $sidebar_right; ?>
    </aside>
    <?php endif; ?>

  </main>

  <footer role="contentinfo">
    <div class="wrapper">
      <section class="footer-top">
        <?php print render($page['footer_top']); ?>
      </section>
      <section class="footer-bottom">
        <?php print render($page['footer_bottom']); ?>
      </section>
      <section class="page-bottom">
        <?php print render($page['bottom']); ?>
      </section>
    </div>
  </footer>

</body>
</html>
