<?php
/**
 * @file
 * Display Suite layout for the UI Kit small list style.
 *
 * @see http://guides.service.gov.au/design-guide/components/list-styles/#small-list-style
 */
?>

<?php if (!empty($title_suffix['contextual_links'])): ?>
  <?php print render($title_suffix['contextual_links']); ?>
<?php endif; ?>

<?php if (!empty($meta)): ?>
    <div class="meta">
      <?php print $meta; ?>
    </div>
<?php endif; ?>

<?php print $main; ?>
