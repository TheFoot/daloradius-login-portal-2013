Dalo Radius Responsive UAM Guide
=========================================

Overview
--------------------

This mini-app is bootstrapped via the inclusion of the `/php/app.php` file. This file takes care of initialising configuration and utility libraries.

The template is based on the [Twitter Bootstrap](http://twitter.github.com/bootstrap/) framework, which is well documented and easy to customise. The core layout is based on a fluid and responsive 12-column grid.

App Configuration
--------------------

Configuration specific to Dalo Radius has been left in the file `/php/daloradius.conf.php`. App configuration is stored in `/php/_config.php` - this contains configuration specific to the site instance.

Customising the theme
--------------------

In the folder `/css/themes/` are a bunch of pre-defined twitter bootstrap base themes that can serve as starting points for your custom theme.  Choose one by changing the theme base include in `/views/html-header.php`.

To then customise your site fully, you should override existing styles in the file `/css/themes/c2013.css`. You can include whatever additional stylesheets you need, but make sure they are included *after* all other stylesheets.

JavaScript
--------------------

The following libraries are included:

* Twitter Bootstrap Core
* jQuery 1.9.1

The following plug-ins and shims are also included:

* HTML5 Shim (For making older IE understand HTMl5 tags)
* jQuery Cookie Plugin (For storing the auth info for auto-login)

The file `/js/app.js` is the application main file. It creates the global namespace "consega" which contains all functionality pertaining to this app.

Languages
-------------------

All scripts and templates are language-tagged. The only currently available language is English, and the language files can be found in `/lang/`;

To create a new language, simply make a copy of the `/lang/en.php` file, translate it and save it using the 2-level ISO country code, e.g. `/lang/fr.php`. You will then need to set the `LANG` constant in the configuration file.

Creating additional locally-hosted pages
--------------------

In order to create arbitrary pages under the same structure, it is recommended to store the page in the `/pages/` folder. `/pages/consumer-hub.php` is an example of how to tie a separate page into the app theme.

Support
--------------------

If you need any more help, my contact details are:

* Email: barry@onalldevices.com
* Tel: 07887 513256
* Skype: thefootonline
