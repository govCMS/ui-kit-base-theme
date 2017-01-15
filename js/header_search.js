/**
 * @file
 * JavaScript related to the site header search form
 */

(function ($, Drupal, window, document, undefined) {

  Drupal.behaviors.uiKitBaseThemeHeaderSearch = {

    attach: function(context, settings) {

      // Stash the original button label from Drupal
      this.originalSearchButtonValue = $('header .search-form .form-submit').attr('value');

      // Update the form on load and window resize.
      this.updateSearchBarButton();
      $(window).resize(function () {
        Drupal.behaviors.uiKitBaseThemeHeaderSearch.updateSearchBarButton();
      });

    },

    /**
     * Update the label of the search button when the widow is re-sized.
     *
     * The search button label is only shown on desktop. On mobile and tablet
     * an icon is shown on the button to save on space.
     */
    updateSearchBarButton: function () {
      var value = '';
      if (Drupal.settings.uiKitBaseTheme.breakpoint == 'desktop') {
        value = this.originalSearchButtonValue;
      }
      $('header .search-form .form-submit').attr('value', value);
    },

    /**
     * Contains the original value of the search submit button
     */
    originalSearchButtonValue: ''

  };

})(jQuery, Drupal, this, this.document);
