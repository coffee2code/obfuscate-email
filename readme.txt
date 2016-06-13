=== Obfuscate Email ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: email, obfuscate, obfuscation, security, spam, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.1
Tested up to: 4.5
Stable tag: 3.5.1

Obfuscate email addresses to deter email harvesting spammers, while retaining the appearance and functionality of hyperlinks.

== Description ==

Obfuscate email addresses to deter email harvesting spammers, while retaining the appearance and functionality of hyperlinks.

"Obfuscation" simply means that techniques are employed to modify email address strings that appear on your site in such a way that bots scraping your site are unable to identify those addresses; however, at the same time those emails addresses should still look and work correctly for visitors, as much as possible.

The plugin allows for use of one or more (or all!) of three proven techniques for email protection. While techniques abound for email obfuscation, the three techniques included empirically provide you with the best balance of email address protection with minimal impact on visitors. You can decide on a technique by technique basis which ones you'd like to employ as some have potential drawbacks. The plugin's settings page allows you select which techniques to use.

See Filters section for `c2c_obfuscate_email_filters` for complete list of filters that are processed.

Please read the Details section of this documentation to learn more about the techniques employed.


== Details ==

The email obfuscation techniques included in this plugin were chosen for their effectiveness and general applicability with minimal impact on users. I urge you to read about an [experiment](http://techblog.tilllate.com/2008/07/20/ten-methods-to-obfuscate-e-mail-addresses-compared/) performed by Silvan Mühlemann in which he protected email addresses using nine different techniques. He ensured the page containing those email addresses got indexed by Google and then waited 1.5 years. During that time he measured the amount of spam received to each of the email addresses.

Three techniques stood out as having received *zero* spam emails during that time. Two of those three techniques are included in this plugin. The fourth of his techniques is also included even though it did get a very small amount of spam -- the technique was still very effective and more importantly does not rely on users to have CSS or JavaScript enabled.

The techniques are as follows. Two are enabled by default. Weigh the requirements against what you're comfortable requiring of visitors in order for them to see and make use of email addresses you post on your site.

(For all the examples below, assume you have the link `<a href="mailto:person@example.com">person@example.com</a>` in your post.)

= Changing text direction with CSS (not enabled by default) =

* *How does it work?* The email addresses are sent reversed in the markup. Using CSS, the text gets reversed so that visitors see the email addresses as intended. Email scrapers don't recognize the emails in their reversed form and don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear backwards to them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, text copied in such a manner will be reversed. However, a right-click -> "copy link/email address" will work properly for linked email addresses.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*:

    `<a href="mailto:person@example.com"><span class="codedirection">moc.elpmaxe@nosrep</span></a>`

= Using CSS display:none =

* *How does it work?* Garbage text, wrapped in span tags, is inserted into any displayed email addresses. Using CSS, the text gets hidden so that visitors see the email addresses as intended. Email scrapers don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear with extra text in them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have CSS disabled).

* *Does this protect email addresses appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*

    `<a href="mailto:person@example.com">person@<span class="displaynone">null</span>example.com</a>`

= Replacing the `@` and `.` characters =

* *How does it work?*  The `@` and `.` characters are replaced with alternative strings, such as `AT` and `DOT`, respectively. The exact replacements are configurable on the plugin's settings page. By default, if you don't specify custom replacements, the plugin will use entity substitution (`@` becomes `&#064;` and `.` becomes `&#046;`).

* *Uses CSS?* No.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, though it should (hopefully) be clear to the user what they need to replace.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes, though if you specify custom replacement strings visitors clicking on a mailto link will have to modify the email address that shows up in their mail program.

* *How effective is this?* In the aforementioned experiment, almost no spam emails were received when using just this technique. However, this technique does not require the support of any particular client-side techniques (CSS or JavaScript).

* *Examples*
    * Custom AT and DOT replacements
        * `<a href="mailto:personATexampleDOTcom">personATexampleDOTcom</a>`
        * `<a href="mailto:person@DELETETHISexample.com">person@DELETETHISexample.com</a>`
    * Everything encoded (aka hexadecimal HTML entity substitution)
`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;">&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a>`

= How it looks =

If all techniques are enabled at once, the resulting obfuscation of the example link above is (for the full effect, view this in the page's source):

`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;"><span class="codedirection">&#x6d;&#x6f;&#x63;&#x2e;&#x65;&#x6c;&#x70;&#x6d;&#x61;&#x78;&#x65;<span class="displaynone">null</span>&#x40;&#x6e;&#x6f;&#x73;&#x72;&#x65;&#x70;</span></a>`


However, in your browser it would appear to you as it does prior to obfuscation, and the link for the email would still work. Theoretically, however, spammers would have a somewhat more difficult time harvesting the emails you display or link to in your posts.

NOTE: (Only when using the custom replacement feature will visitors need to modify the email address for use in their email program.)

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/obfuscate-email/) | [Plugin Directory Page](https://wordpress.org/plugins/obfuscate-email/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Whether installing or updating, whether this plugin or any other, it is always advisable to back-up your data before starting
1. Unzip `obfuscate-email.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to `Settings` -> `Obfuscate E-mail` admin options page (which you can also get to via the Settings link next to the plugin on the Manage Plugins page) and optionally customize the settings.


== Frequently Asked Questions ==

= So it'll be impossible for spammers to harvest my site for email addresses? =

Of course nothing is guaranteed. By its very definition, "obfuscate" means "to make obscure or unclear", and that's all it's really doing. It's some degree of basic protection, which is better than nothing. Much as how locks in real-life at best provide some measure of deterrent for a would-be criminal rather than absolute security from a determined and capable individual. That said, some testing (as described elsewhere in this documentation) indicates using one or more of the supplied techniques are extremely effective.

= Aren't there better methods of email obfuscation? =

Nothing short of not actually displaying email addresses can guarantee that email addresses can't get harvested. Some methods are more aggressive and therefore have compatibility and/or usability issues. This plugin can be very compatible and usable by all visitors to your site, but also has allowances for greater protection with minimal impact (though how minimal is for you to judge).

= Does this plugin make use of JavaScript as other email obfuscators do?

No. This makes this plugin's implementation of obfuscation more compatible and usable by more visitors. This may leave out techniques that some could argue are aggressively protective, but I feel (based on the aforementioned study and personal experience) that the included techniques are just as effective.

= This plugin provides multiple techniques for email obfuscation; can I apply more than one at once for even greater protection? =

Yes, all techniques can be activated at once (and multiple ones are by default).

= Does this plugin modify the post content in the database? =

No. The plugin filters post content on-the-fly. E-mails will remain unchanged in the database.

= Why don't I see any obfuscation when viewing the source for the page (or a selection) via my browser's inspector? =

The web browser's inspector tool will process certain techniques (such as HTML hexadecimal substitution) before showing the source in the inspector. You should "View Source" to see the raw markup sent to the browser.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the plugin's admin options page.


== Template Tags ==

The plugin provides one optional template tag for use in your theme templates.

= Functions =

* `function c2c_obfuscate_email( $text, $args = array() )`

= Arguments =

* `$text`
Required argument. The text and/or HTML that contains email addresses that you want to be obfuscated.

* `$args`
Optional argument. An array of configuration options, each element of which will override the plugin's corresponding default setting.
    * encode_everything (boolean) : Encode all characters in the email address using hexadecimal HTML entity substitution?
    * use_text_direction (boolean) : Utilize CSS text direction technique?
    * use_display_none (boolean) : Utilize CSS display:none technique?
    * at_replace (string) : String to use in place of `@` in email addresses (used only if encode_everything is false)
    * dot_replace (string) : String to use in place of `.` in email addresses (used only if encode_everything is false)

= Examples =

* Basic usage. Obfuscate email addresses in $text according to current plugin settings.

`<?php echo c2c_obfuscate_email( $text ); ?>`

* Override all plugin default settings when obfuscating email addresses in $text and just use text direction technique.

`<?php echo c2c_obfuscate_email( $text, array(
  array('use_text_direction' => true, 'use_display_none' => false, 'encode_everything' => false, 'at_replace' => '', 'dot_replace' => '')
) ); ?>`


== Filters ==

The plugin exposes one filter for hooking. Typically, customizations utilizing this hook would be put into your active theme's functions.php file, or used by another plugin.

= c2c_obfuscate_email_filters (filter) =

The 'c2c_obfuscate_email_filters' filter allows you to customize what filters to hook to be filtered with email obfuscation. The following filters are all filtered by default:

* link_description
* link_notes
* bloginfo
* nav_menu_description
* term_description
* the_title
* the_content
* get_the_excerpt
* comment_text
* list_cats
* widget_text
* the_author_email
* get_comment_author_email

Arguments:

* array $filters : the default array of filters

Example:

`
/**
 * Also obfuscate emails appearing in custom field values.
 *
 * @param array $filters Filters that get filtered to obfuscate email addresses.
 * @return array
 */
function change_c2c_obfuscate_email_filters( $filters ) {
	$filters[] = 'the_meta';
	return $filters;
}
add_filter( 'c2c_obfuscate_email_filters', 'change_c2c_obfuscate_email_filters' );
`


== Changelog ==

= 3.5.1 (2016-06-13) =
* Change: Update plugin framework to 044:
    * 044
    * Add `reset_caches()` to clear caches and memoized data. Use it in `reset_options()` and `verify_config()`.
    * Add `verify_options()` with logic extracted from `verify_config()` for initializing default option attributes.
    * Add  `add_option()` to add a new option to the plugin's configuration.
    * Add filter 'sanitized_option_names' to allow modifying the list of whitelisted option names.
    * Change: Refactor `get_option_names()`.
    * 043
    * Disregard invalid lines supplied as part of hash option value.
    * 042
    * Update `disable_update_check()` to check for HTTP and HTTPS for plugin update check API URL.
    * Translate "Donate" in footer message.
    * Note compatibility through WP 4.5.
* Change: Use "email" instead of "e-mail" in plugin's name, description, and documentation.
* Bugfix: Add appropriate spacing so v3.5's changelog entry gets properly parsed. Props szepeviktor.

= 3.5 (2016-04-04) =
Highlights:

* This release adds support for language packs and has some minor behind-the-scenes changes.

Details:

* Change: Update plugin framework to 041:
    * Change class name to c2c_ObfuscateEmail_Plugin_041 to be plugin-specific.
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

= 3.4 (2015-04-22) =
* Enhancement: Prevent email obfuscation from occurring in the admin area
* Update: Add more unit tests
* Update: Note compatibility through WP 4.2+
* Update: Minor code formatting changes (spacing)

= 3.3 (2015-03-02) =
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

= 3.2 =
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

= 3.1 =
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

= 3.0 =
* Significant rewrite of entire plugin
* Use plugin framework v026, which among other things adds support for:
    * Reset of options to default values
    * Better sanitization of input values
    * Offload core/basic functionality to generic plugin framework
    * Additional hooks for various stages/places of plugin operation
    * Easier localization support
* Apply obfuscation to additional filters: link_description, link_notes, bloginfo, nav_menu_description, term_description, the_title, list_cats, widget_text
* Deprecate function c2c_email_obfuscator()
* Change c2c_obfuscate_email()
    * Second argument is now an array of arguments to override plugin settings
    * All previously existing arguments (except first) have been deprecated
* Add filter 'c2c_obfuscate_email_filters'
* Full localization support
* Fix to properly register activation and uninstall hooks
* Save a static version of itself in class variable $instance
* Rename class from 'ObfuscateEmail' to 'c2c_ObfuscateEmail'
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

= 2.0 =
* Overhauled and added a bunch of new code
* Encapsulated a majority of functionality in a class
* Added admin options page for the plugin, under Options -> Obfuscate E-mail (or in WP 2.5: Settings &rarr; Obfuscate E-mail) so that default e-mail obfuscation can be easily configured via WP admin
* Packaged plugin into its own directory, now including a readme.txt and screenshots
* Maintained (though renamed) existing primary functions (which have remained non-classed) for others to use directly
* Added options to define replacements for "@" and "." characters in e-mails for partial obfuscation
* Filter 'get_the_excerpt' instead of 'the_excerpt'
* Additionally filter 'the_author_email' and 'get_comment_author_email'
* trim() before returning instead of substr()
* Tweaked description, installation instructions, and examples
* Updated copyright date and version to 2.0
* Added readme.txt and screenshot image to distribution zip
* Tested compatibility with WP 2.3+ and 2.5

= 0.9 =
* Initial release


== Upgrade Notice ==

= 3.5.1 =
Minor update: updated plugin framework to 044; fixed display of changelog; used "email" instead of "e-mail" everywhere

= 3.5 =
Minor update: improved support for localization; minor widget code changes; verified compatibility through WP 4.5; dropped compatibility with WP older than 4.1; updated copyright date (2016)

= 3.4 =
Minor update: prevented email obfuscation from occurring in the admin area; noted compatibility through WP 4.2+

= 3.3 =
Minor update: minor fix; added unit tests; updated plugin framework to 039; noted compatibility through WP 4.1+; updated copyright date (2015); added plugin icon

= 3.2 =
Recommended update. Highlights: disabled text direction technique by default; updated plugin framework; noted compatibility through WP 3.6+; and more.

= 3.1 =
Recommended update. Fixed a number of bugs; noted WP 3.3 compatibility; dropped support for versions of WP older than 3.1; updated plugin framework; and more.

= 3.0 =
Recommended update. Major rewrite. Everything has changed and been improved. You want this. Compatible with WP 3.0 through 3.2+.
