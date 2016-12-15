<?php
/**
 * @file
 * Returns the HTML for a single Drupal page.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728148
 */

// Render region if there's content in theme.
$navigation  = render($page['navigation']);
$hero  = render($page['hero']);
$sidebar  = render($page['sidebar']);
?>

<header class="header" id="header" role="banner">
  <section class="page-header">
    <div class="wrapper">

      <a href="<?php print url('<front>'); ?>" class="logo">
        <div class="page-header__logo">
          <?php print $site_name; ?>
        </div>
      </a>

      <?php print render($page['header']); ?>

      <?php if ($navigation): ?>
        <div class="site-nav">
          <div class="wrapper">
            <nav class="site-nav__wrapper">
              <?php print render($page['navigation']); ?>
            </nav>
          </div>
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

  <?php if ($sidebar): ?>
    <aside class="sidebars sidebar" role="complementary">
      <?php print $sidebar; ?>
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

</main>

<footer role="contentinfo">
  <section class="page-footer">
    <div class="wrapper">
      <section class="footer-top">
        <nav>
          <?php print render($page['footer']); ?>
        </nav>
      </section>
    </div>
  </section>
  <section class="page-bottom">
    <div class="wrapper">
      <?php print render($page['bottom']); ?>
    </div>
  </section>
</footer>
