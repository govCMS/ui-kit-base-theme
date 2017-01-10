# UI Kit govCMS theme

## What is this?

This is a package that applies the Australian Digital Transformation Agency UI-kit (http://guides.service.gov.au/design-guide/) to a base theme for govCMS. 

### This package is two things:

 1. A base theme for govCMS
 2. A local prototype package for building pages, layouts and components 

This theme (and the prototype) use the UI-kit to provide all styling, components and layouts.

The govCMS theme and local prototype include the raw, uncompiled UI-kit providing access to Sass variables and mixins, as well as JavaScript and images.

## Install the prototype/theme build package
 
Download or clone this repo to your govCMS installation under sites/all/themes.

Download or clone this repo and install the system dependencies:

* Node.js v6.0.0+

Then install the theme/prototype package via:
```
npm install
```

The UI-kit will be installed locally as a dependency to the `node_modules` folder. To copy it to both prototype build and theme locations, run: 

```
gulp ui-kit.install
```

Finally, compile the UI-kit for both the prototype and theme via:

```
gulp build
```

The package is now prototype development and govCMS-ready.

When building a theme for govCMS, it is necessary to clear cache to view changes. 

### Enable the theme in govCMS

Log in as an administrator on your govCMS site and go to the Appearance page at admin/appearance. You will see the UI KIT base theme listed under the Disabled Themes. Select as default.

## Prototyping

All prototyping should take place in the `/build/src` folder.

Pages can be created within this folder and handlebars 'snippets' created with `/build/src/partials`. These support modular prototyping and are included via `{{> name}}` where name = name.hbs.

A number of gulp tasks are available in `gulpfile.js`. The most common ones for prototyping are:

```
gulp build:proto
```
This will compile all sources to `/build/dist`

```
gulp watch
```
This will compile all sources and load the prototype index page via [http://localhost:3000](http://localhost:3000). Changes made to the prototype source files will be auto-compiled and the browser refreshed.


## Theming

Prototype and theme assets (including the UI-kit) are maintained seperately.

This permits theme-specific changes as required by govCMS to be made without affected prototyping (and vice versa).

Most gulp tasks are written to permit theme and prototype-only building. Alternatively, ommitting the scope will run against both. For example:

```
gulp build
```
or seperately via:
```
gulp build:proto
gulp build:theme
```


## Sub-theme development

The base theme includes a `STARTERKIT` folder for the creation of a sub-theme.

This approach allows the UI-kit to be used as a base (while remaining unchanged to support upgrades) for further theme development to meet specific needs.

Sub-theme installation and development instructions are available in `README-FIRST.txt`

    
## Updating the UI-kit

The UI-kit can be updated by running:

```
npm update gov-au-ui-kit
```
This will pull the latest version but specific versions can be acquired by using a version hash. For example:

```
npm update https://github.com/AusDTO/gov-au-ui-kit.git#1.8
```
