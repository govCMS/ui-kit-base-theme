WELCOME TO UI-KIT BASE THEME
--------------

WHAT IS THIS?
--------------

This theme is two things:

 1. A base theme for govCMS
 2. A local prototype package for building pages, layouts and components 

This theme (and the prototype) use the DTA's Ui-kit to provide all styling, components and layouts. Find out more at https://gov-au-ui-kit.apps.staging.digital.gov.au

The govCMS theme and local prototype include the raw, uncompiled UI-kit providing access to Sass variables and mixins, as well as JavaScript and images.


INSTALLATION
------------

There are two installations associate with this theme:

 1. THEME INSTALLATION
    Installing the theme within govCMS.

 2. LOCAL BUILD INSTALLATION
    Installing associated tooling and dependencies to permit prototype 
    and theme development outside of govCMS, based on the UI-kit.


THEME INSTALLATION
------------

 1. Download the base theme from https://github.com/govCMS/ui-kit-base-theme

 2. Place the base theme folder in your Drupal installation under sites/all/themes.

 3. Log in as an administrator on your Drupal site and go to the Appearance page
    at admin/appearance. You will see the UI KIT base theme listed under the Disabled
    Themes. Select as default.


LOCAL BUILD INSTALLATION
------------

 1. Install the system dependency Node.js v6.0.0+ (http://node.js/)

 2. Then install the prototype package via:

      npm install

 3. The UI-kit will be installed locally as a dependency to the node_modules folder. To copy it to both prototype build and theme locations, run: 

      gulp ui-kit.install

 4. Finally, compile the UI-kit for both the prototype and theme via:

      gulp build

 The package is now prototype development and govCMS-ready.

 When building a theme for govCMS, it is necessary to clear cache to view changes. 


SUB-THEME DEVELOPMENT
------------

1. Setup the location for your new sub-theme.

    Copy the STARTERKIT folder out of the uikit_base/ folder and rename it to be your
    new sub-theme. IMPORTANT: The name of your sub-theme must start with an
    alphabetic character and can only contain lowercase letters, numbers and
    underscores.

    For example, copy the sites/all/themes/uikit_base/STARTERKIT folder and rename it
    as sites/all/themes/foo.

      Why? Each theme should reside in its own folder. To make it easier to
      upgrade thr base theme, sub-themes should reside in a folder separate from the base
      theme.

      *******
      IMPORTANT:

      The base theme and sub-theme must be located at the same level with the parent folder.
      For example:

      sites/all/themes/uikit_base for the base theme; and
      sites/all/themes/foo for the sub-theme

      This is because the sub-theme will inherit the UI-kit from the base theme. Using a different folder structure or deleting the base theme will break required references.


 2. Setup the basic information for your sub-theme.

    In your new sub-theme folder, rename the STARTERKIT.info.txt file to include
    the name of your new sub-theme and remove the ".txt" extension. Then edit
    the .info file by editing the name and description field.

    For example, rename the foo/STARTERKIT.info file to foo/foo.info. Edit the
    foo.info file and change "name = UI KIT base theme" to "name = Foo"
    and "description = govCMS base theme ..." to "description = A sub-theme ...".

      Why? The .info file describes the basic things about your theme: its
      name, description, features, template regions, CSS files, and JavaScript
      files. See the Drupal Theme Guide for more info:
      https://www.drupal.org/documentation/theme

    Remember to visit your site's Appearance page at admin/appearance to refresh
    Drupal 7's cache of .info file data.

 3. Edit your sub-theme to use the proper function names.

    Edit the template.php and theme-settings.php files in your sub-theme's
    folder; replace ALL occurrences of "STARTERKIT" with the name of your
    sub-theme.

    For example, edit foo/template.php and foo/theme-settings.php and replace
    every occurrence of "STARTERKIT" with "foo".

    It is recommended to use a text editing application with search and
    "replace all" functionality.

 4. Set your website's default theme.

    Log in as an administrator on your Drupal site, go to the Appearance page at
    admin/appearance and click the "Enable and set default" link next to your
    new sub-theme.