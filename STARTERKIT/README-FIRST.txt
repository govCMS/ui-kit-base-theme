WELCOME TO UI-KIT SUB-THEME
--------------

Before continuing please ensure that you have read the /uikit_base/README-FIRST.txt instructions.

This details the steps required before developing a sub-theme, including:

  1.  Setting up the location for your new sub-theme.
  2.  Setting up the basic information for your sub-theme.
  3.  Editing your sub-theme to use the proper function names.

If you have completed these steps, you should now be working within a sub-theme folder, such as 'foo' that exists at the same level as the base theme, such as:

  sites/all/themes/uikit_base (for the base theme); and
  sites/all/themes/foo (for the sub-theme) - this folder

All further steps should take place within the sub-theme folder.


WHAT IS THIS?
--------------

This sub-theme is two things:

 1. A sub-theme for govCMS that uses the base theme
 2. A local prototype package for building pages, layouts and components

This sub-theme (and the prototype) use the DTA's Ui-kit to provide all styling, components and layouts. Find out more at https://gov-au-ui-kit.apps.staging.digital.gov.au


RELATIONSHIP WITH THE BASE THEME
------------

To prevent duplication, the sub-theme extends the base theme. The UI-kit exists only in the base theme (to facilitate updates). All references to it from the sub-theme are to the base theme. Therefore, the base theme must exist to develop the sub-theme and associated prototype.

The base theme and associated local prototype include the raw, uncompiled UI-kit providing access to Sass variables and mixins, as well as JavaScript and images.


INSTALLATION
------------

There are two installations associated with this sub-theme:

 1. SUB-THEME INSTALLATION
    Installing the theme within govCMS.

 2. LOCAL BUILD INSTALLATION
    Installing associated tooling and dependencies to permit prototype and theme development outside of govCMS, based on the UI-kit.


SUB-THEME INSTALLATION
------------

Log in as an administrator on your Drupal site and go to the Appearance page at admin/appearance. You will see the UI KIT base theme listed under the Disabled Themes. Select as default.


LOCAL BUILD INSTALLATION
------------

 1. Install the system dependency Node.js v6.0.0+ (http://node.js/) if not already installed (this should already be installed if you have built the base theme/prototype package).

 2. Then install the sub-theme/prototype package via:

      npm install

 4. Finally, compile the sub-theme/protype via:

      gulp build

 The package is now prototype development and govCMS-ready.

 When building a sub-theme for govCMS, it is necessary to clear cache to view changes.

