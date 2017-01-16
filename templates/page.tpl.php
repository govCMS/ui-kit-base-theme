<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */

// Render region if there's content in theme.
$navigation    = render($page['navigation']);
$hero          = render($page['hero']);
$sidebar_left  = render($page['sidebar_left']);
$sidebar_right = render($page['sidebar_right']);

if ($sidebar_left && $sidebar_right) {
  $main_classes .= ' page--sidebar-left-right';
}
elseif ($sidebar_left) {
  $main_classes .= ' page--sidebar-left';
}
elseif ($sidebar_right) {
  $main_classes .= ' page--sidebar-right';
}

?>

<header class="header" id="header" role="banner">
  <section class="page-header">
    <div class="wrapper">

      <?php print render($page['header']); ?>

      <?php if ($navigation): ?>
        <div class="site-nav">
          <nav class="site-nav__wrapper">
            <?php print render($page['navigation']); ?>
          </nav>
        </div>
      <?php endif; ?>

    </div>
  </section>
</header>

<?php if ($hero): ?>
  <section class="hero">
    <div class="wrapper">
      <?php print render($page['hero']); ?>
    </div>
  </section>
<?php endif; ?>

<?php print $breadcrumb; ?>

<main id="page" role="main" class="<?php print $main_classes; ?>">

  <?php if ($sidebar_left): ?>
  <aside class="sidebar__left" role="complementary">
    <?php print $sidebar_left; ?>
  </aside>
  <?php endif; ?>

  <article id="content" class="content-main">

    <div id="main">

      <div id="content" class="column">

        <a href="#skip-link" id="skip-content" class="element-invisible">Go to top of page</a>

        <a id="main-content"></a>
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
          <h1 class="page__title title" id="page-title"><?php print $title; ?></h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php print $messages; ?>
        <?php print render($tabs); ?>
        <?php print render($page['help']); ?>
        <?php if ($action_links): ?>
          <ul class="action-links"><?php print render($action_links); ?></ul>
        <?php endif; ?>
        <?php print render($page['content']); ?>
        <?php print $feed_icons; ?>
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
