<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */

// Render region if there's content in theme.
$navigation     = render($page['navigation']);
$hero           = render($page['hero']);
$sidebar_left   = render($page['sidebar_left']);
$sidebar_right  = render($page['sidebar_right']);
$content_before = render($page['content_before']);
$content_after  = render($page['content_after']);

$main_classes = '';
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

      <div class="page-header__branding">
        <?php print render($page['header']); ?>
      </div>

      <?php if ($navigation): ?>
        <?php print $navigation; ?>
      <?php endif; ?>

    </div>
  </section>
</header>

<?php if ($hero): ?>
  <section class="hero">
    <div class="wrapper">
      <?php print $hero; ?>
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

      <div class="column">

        <a href="#skip-link" id="skip-content" class="element-invisible">Go to top of page</a>

        <a id="main-content"></a>

        <?php if ($content_before): ?>
          <section class="content-before">
            <div class="wrapper">
              <?php print $content_before; ?>
            </div>
          </section>
        <?php endif; ?>

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

<?php if ($content_after): ?>
<section class="content-after">
  <div class="wrapper">
    <?php print $content_after; ?>
  </div>
</section>
<?php endif; ?>

<footer role="contentinfo">
  <div class="wrapper">
    <?php if($page['footer_top']): ?>
      <section class="footer-top">
        <?php print render($page['footer_top']); ?>
      </section>
    <?php endif; ?>
    <section class="footer-bottom">
      <div class="footer-logo">
        <img alt="<?php print t('Australian Government Coat of Arms');?>" src="<?php print base_path() . drupal_get_path('theme', 'uikit_base'); ?>/images/coat-of-arms.png">
      </div>
      <?php print render($page['footer_bottom']); ?>
    </section>
    <section class="page-bottom">
      <?php print render($page['bottom']); ?>
    </section>
  </div>
</footer>
