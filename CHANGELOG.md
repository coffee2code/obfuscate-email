# Changelog

## _(in-progress)_
* New: Add README.md file
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add GitHub link to readme
* Unit tests:
    * Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
* Change: Note compatibility through WP 5.1+
* Change: Drop compatibility with version of WP older than 4.7
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 3.5.1 _(2016-06-13)_
* Change: Update plugin framework to 044:
    * 044
    * Add `reset_caches()` to clear caches and memoized data. Use it in `reset_options()` and `verify_config()`.
    * Add `verify_options()` with logic extracted from `verify_config()` for initializing default option attributes.
    * Add  `add_option()` to add a new option to the plugin's configuration.
    * Add filter `sanitized_option_names` to allow modifying the list of whitelisted option names.
    * Change: Refactor `get_option_names()`.
    * 043
    * Disregard invalid lines supplied as part of hash option value.
    * 042
    * Update `disable_update_check()` to check for HTTP and HTTPS for plugin update check API URL.
    * Translate "Donate" in footer message.
    * Note compatibility through WP 4.5.
* Change: Use "email" instead of "e-mail" in plugin's name, description, and documentation.
* Bugfix: Add appropriate spacing so v3.5's changelog entry gets properly parsed. Props szepeviktor.

## 3.5 _(2016-04-04)_

### Highlights:

This release adds support for language packs and has some minor behind-the-scenes changes.

### Details:

* Change: Update plugin framework to 041:
    * Change class name to `c2c_ObfuscateEmail_Plugin_041` to be plugin-specific.
    * Set textdomain using a string instead of a variable.
    * Don't load textdomain from file.
    * Change admin page header from 'h2' to 'h1' tag.
    * Add `c2c_plugin_version()`.
    * Formatting improvements to inline docs.
* Change: Add support for language packs:
    * Set textdomain using a string instead of a variable.
    * Remove .pot file and /lang subdirectory.
    * Remove 'Domain Path' from plugin header.
* New: Add LICENSE file.
* New: Add empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Minor code reformatting.
* Change: Note compatibility through WP 4.5+.
* Change: Dropped compatibility with version of WP older than 4.1.
* Change: Update copyright date (2016).

## 3.4 _(2015-04-22)_
* Enhancement: Prevent email obfuscation from occurring in the admin area
* Update: Add more unit tests
* Update: Note compatibility through WP 4.2+
* Update: Minor code formatting changes (spacing)

## 3.3 _(2015-03-02)_
* Fix when using multi-character AT or DOT replacements in conjunction with text direction technique when not encoding everything
* Use full path when requiring plugin framework file
* Add unit tests
* Update plugin framework to 039
* Explicitly declare `activation()` and `uninstall()` static
* Explicitly declare all function public
* Reformat plugin header
* Minor code reformatting (spacing, bracing)
* Change documentation links to wp.org to be https
* Minor documentation improvements and spacing changes throughout
* Note compatibility through WP 4.1+
* Drop compatibility with version of WP older than 3.6
* Update copyright date (2015)
* Add plugin icon
* Change donate link
* Update screenshot
* Regenerate .pot

## 3.2
* Disable text direction technique by default (doesn't change existing setting value)
* Update plugin framework to 036
* Better singleton implementation:
    * Add `instance()` static method for returning/creating singleton instance
    * Made static variable 'instance' private
    * Made constructor protected
    * Made class final
    * Additional related changes in plugin framework (protected constructor, erroring `__clone()` and `__wakeup()`)
* Add checks to prevent execution of code if file is directly accessed
* Regenerate .pot
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Discontinue use of PHP4-style constructor
* Discontinue use of explicit pass-by-reference for objects
* Remove ending PHP close tag
* Minor documentation improvements
* Minor code reformatting (spacing)
* Note compatibility through WP 3.6+
* Update copyright date (2013)
* Move screenshots into repo's assets directory
* Add banner

## 3.1
* Fix bug where display:none technique was ignored if text direction technique was not active
* Fix bug where display:none and text direction techniques were erroneously applied to email addresses in tag attributes when mid-string
* Update plugin framework to 034
* Note compatibility through WP 3.3+
* Drop compatibility with versions of WP older than 3.1
* Change parent constructor invocation
* Create 'lang' subdirectory and move .pot file into it
* Regenerate .pot
* Add 'Domain Path' directive to top of main plugin file
* Add link to plugin directory page to readme.txt
* Tweak installation instructions in readme.txt
* Changed description
* Documentation changes
* Update screenshots for WP 3.3
* Update copyright date (2012)

## 3.0
* Significant rewrite of entire plugin
* Use plugin framework v026, which among other things adds support for:
    * Reset of options to default values
    * Better sanitization of input values
    * Offload core/basic functionality to generic plugin framework
    * Additional hooks for various stages/places of plugin operation
    * Easier localization support
* Apply obfuscation to additional filters: link_description, link_notes, bloginfo, nav_menu_description, term_description, the_title, list_cats, widget_text
* Deprecate function `c2c_email_obfuscator()`
* Change `c2c_obfuscate_email()`
    * Second argument is now an array of arguments to override plugin settings
    * All previously existing arguments (except first) have been deprecated
* Add filter `c2c_obfuscate_email_filters`
* Full localization support
* Fix to properly register activation and uninstall hooks
* Save a static version of itself in class variable $instance
* Rename class from `ObfuscateEmail` to `c2c_ObfuscateEmail`
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.1+ and WP 3.2+
* Drop compatibility with versions of WP older than 3.0
* Explicitly declare all class functions public
* Add PHPDoc documentation
* Add package info to top of plugin file
* Add 'Text Domain' header tag
* Add Template Tags, Filters, Changelog, and Upgrade Notice sections to readme.txt
* Add screenshot
* Update copyright date (2011)
* Add .pot file

## 2.0
* Overhauled and added a bunch of new code
* Encapsulated a majority of functionality in a class
* Added admin options page for the plugin, under Options -> Obfuscate E-mail (or in WP 2.5: Settings &rarr; Obfuscate E-mail) so that default e-mail obfuscation can be easily configured via WP admin
* Packaged plugin into its own directory, now including a readme.txt and screenshots
* Maintained (though renamed) existing primary functions (which have remained non-classed) for others to use directly
* Added options to define replacements for `@` and `.` characters in e-mails for partial obfuscation
* Filter 'get_the_excerpt' instead of 'the_excerpt'
* Additionally filter `the_author_email` and `get_comment_author_email`
* trim() before returning instead of `substr()`
* Tweaked description, installation instructions, and examples
* Updated copyright date and version to 2.0
* Added readme.txt and screenshot image to distribution zip
* Tested compatibility with WP 2.3+ and 2.5

## 0.9
* Initial release
