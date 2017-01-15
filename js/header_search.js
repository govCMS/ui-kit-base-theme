/**
 * @file
 * JavaScript related to the site header search form
 */

(function ($, Drupal, window, document, undefined) {

  Drupal.behaviors.uiKitBaseThemeHeaderSearch = {

    attach: function(context, settings) {

      // Stash the original button label and placeholder from Drupal
      this.originalSearchButtonValue = $('header .search-form .form-submit').attr('value');
      this.originalPlaceholderValue = $('header .search-form [type=text]').attr('placeholder');

      // Update the form on load and window resize.
      this.updateSearchForm();
      $(window).resize(function () {
        Drupal.behaviors.uiKitBaseThemeHeaderSearch.updateSearchForm();
      });

    },

    /**
     * Update the label of the search button when the widow is re-sized.
     *
     * The search button label is only shown on desktop. On mobile and tablet
     * an icon is shown on the button to save on space.
     */
    updateSearchForm: function () {
      var button_label = '';
      var placeholder  = this.originalPlaceholderValue;

      if (Drupal.settings.uiKitBaseTheme.breakpoint == 'desktop') {
        button_label  = this.originalSearchButtonValue;
          placeholder = Drupal.t('Enter your keywords');
      }

      $('header .search-form .form-submit').attr('value', button_label);
      $('header .search-form [type=text]').attr('placeholder', placeholder);
    },

    /**
     * Contains the original value of the search submit button
     */
    originalSearchButtonValue: '',

    /**
     * Contains the original value of the search bar placeholder
     */
    originalPlaceholderValue: ''

  };

})(jQuery, Drupal, this, this.document);
