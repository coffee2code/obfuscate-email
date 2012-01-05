=== Obfuscate E-mail ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: email, obfuscation, security, spam, coffee2code
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 3.1
Version: 3.1

Obfuscate e-mail addresses to deter e-mail harvesting spammers, while retaining the appearance and functionality of hyperlinks.

== Description ==

Obfuscate e-mail addresses to deter e-mail harvesting spammers, while retaining the appearance and functionality of hyperlinks.

"Obfuscation" simply means that techniques are employed to modify e-mail address strings that appear on your site in such a way that bots scraping your site are unable to identify those addresses; however, at the same time those e-mails addresses should still look and work correctly for visitors, as much as possible.

The plugin allows for use of one or more (or all!) of three proven techniques for email protection.  While techniques abound for e-mail obfuscation, the three techniques included empirically provide you with the best balance of e-mail address protection with minimal impact on visitors.  You can decide on a technique by technique basis which ones you'd like to employ as some have potential drawbacks.  The plugin's settings page allows you select which techniques to use.

See Filters section for `c2c_obfuscate_email_filters` for complete list of filters that are processed.

Please read the Details section of this documentation to learn more about the techniques employed.


== Details ==

The e-mail obfuscation techniques included in this plugin were chosen for their effectiveness and general applicability with minimal impact on users.  I urge you to read about an [experiment](http://techblog.tilllate.com/2008/07/20/ten-methods-to-obfuscate-e-mail-addresses-compared/) performed by Silvan Mühlemann in which he protected e-mail addresses using nine different techniques.  He ensured the page containing those e-mail addresses got indexed by Google and then waited 1.5 years.  During that time he measured the amount of spam received to each of the e-mail addresses.

Three techniques stood out as having received *zero* spam e-mails during that time.  Two of those three techniques are included in this plugin.  The fourth technique is also included even though it did get a very small amount of spam -- the technique was still very effective and more importantly does not rely on users to have CSS or JavaScript enabled.

The techniques are as follows. All three are enabled by default. Weigh the requirements against what you're comfortable requiring of visitors in order for them to see and make use of e-mail addresses you post on your site.

(For all the examples below, assume you have the link `<a href="mailto:person@example.com">person@example.com</a>` in your post.)

= Changing text direction with CSS =

* *How does it work?* The email addresses are sent reversed in the markup. Using CSS, the text gets reversed so that visitors see the email addresses as intended. Email scrapers don't recognize the emails in their reversed form and don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear backwards to them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, but a "copy link address" will work properly.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*:

    `<a href="mailto:person@example.com"><span class="codedirection">moc.elpmaxe@nosrep</span></a>`

= Using CSS display:none =

* *How does it work?* Garbage text, wrapped in span tags, is inserted into any displayed email addresses.  Using CSS, the text gets hidden so that visitors see the email addresses as intended.  Email scrapers don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear with extra text in them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have CSS disabled).

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*

    `<a href="mailto:person@example.com">person@<span class="displaynone">null</span>example.com</a>`

= Replacing the `@` and `.` characters =

* *How does it work?*  The `@` and `.` characters are replaced with alternative strings, such as `AT` and `DOT`, respectively.  The exact replacements are configurable on the plugin's settings page.  By default, if you don't specify custom replacements, the plugin will use entity substitution (`@` becomes `&#064;` and `.` becomes `&#046;`).

* *Uses CSS?* No.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, though it should (hopefully) be clear to the user what they need to replace.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes, though if you specify custom replacement strings visitors clicking on a mailto link will have to modify the email address that shows up in their mail program.

* *How effective is this?* In the aforementioned experiment, almost no spam emails were received when using just this technique.  However, this technique does not require the support of any particular client-side techniques (CSS or JavaScript).

* *Examples*
    * Custom AT and DOT replacements
        * `<a href="mailto:personATexampleDOTcom">personATexampleDOTcom</a>`
        * `<a href="mailto:person@DELETETHIS.com">person@DELETETHISexample.com</a>`
    * Everything encoded (aka hexadecimal HTML entity substitution)
`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;">&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a>`

= How it looks =

If all techniques are enabled at once, the resulting obfuscation of the example link above is:

`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;"><span class="codedirection">&#x6d;&#x6f;&#x63;&#x2e;&#x65;&#x6c;&#x70;&#x6d;&#x61;&#x78;&#x65;<span class="displaynone">null</span>&#x40;&#x6e;&#x6f;&#x73;&#x72;&#x65;&#x70;</span></a>`


However, in your browser it would appear to you as it does prior to obfuscation, and the link for the e-mail would still work.  Theoretically, however, spammers would have a somewhat more difficult time harvesting the e-mails you display or link to in your posts.

NOTE: (Only when using the custom replacement feature will visitors need to modify the e-mail address for use in their e-mail program.)

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/obfuscate-email/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/obfuscate-email/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Whether installing or updating, whether this plugin or any other, it is always advisable to back-up your data before starting
1. Unzip `obfuscate-email.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to `Settings` -> `Obfuscate E-mail` admin options page (which you can also get to via the Settings link next to the plugin on the Manage Plugins page) and optionally customize the settings.


== Frequently Asked Questions ==

= So it'll be impossible for spammers to harvest my site for e-mail addresses? =

Of course nothing is guaranteed.  By its very definition, "obfuscate" means "to make obscure or unclear", and that's all it's really doing.  It's some degree of basic protection, which is better than nothing.  Much as how locks in real-life at best provide some measure of deterrent for a would be criminal rather than absolute security from a determined and capable individual.  That said, some testing (as described elsewhere in this documentation) indicates using one or more of the supplied techniques are extremely effective.

= Aren't there better methods of e-mail obfuscation? =

Nothing short of not actually displaying e-mail addresses can guarantee that e-mail addresses can't get harvested.  Some methods are more aggressive and therefore have compatibility and/or usability issues.  This plugin can be very compatible and usable by all visitors to your site, but also has allowances for greater protection with minimal impact (though how minimal is for you to judge).

= Does this plugin make use of JavaScript as other e-mail obfuscators do?

No.  This makes this plugin's implementation of obfuscation more compatible and usable by more visitors.  This may leave out techniques that some could argue are aggressively protective, but I feel (based on the aforementioned study and personal experience) that the included techniques are just as effective.

= This plugin provides multiple techniques for e-mail obfuscation; can I apply more than one at once for even greater protection? =

Yes, all techniques can be activated at once (and they are by default).

= Does this plugin modify the post content in the database? =

No.  The plugin filters post content on-the-fly.  E-mails will remain unchanged in the database.


== Screenshots ==

1. A screenshot of the plugin's admin options page.


== Template Tags ==

The plugin provides one optional template tag for use in your theme templates.

= Functions =

* `function c2c_obfuscate_email( $text, $args = array() )`

= Arguments =

* `$text`
Required argument.  The text and/or HTML that contains e-mail addresses that you want to be obfuscated.

* `$args`
Optional argument.  An array of configuration options, each element of which will override the plugin's corresponding default setting.
    * encode_everything (boolean) : Encode all characters in the e-mail address using hexadecimal HTML entity substitution?
    * use_text_direction (boolean) : Utilize CSS text direction technique?
    * use_display_none (boolean) : Utilize CSS display:none technique?
    * at_replace (string) : String to use in place of `@` in e-mail addresses (used only if encode_everything is false)
    * dot_replace (string) : String to use in place of `.` in e-mail addresses (used only if encode_everything is false)

= Examples =

* Basic usage. Obfuscate e-mail addresses in $text according to current plugin settings.

`<?php echo c2c_obfuscate_email( $text ); ?>`

* Override all plugin default settings when obfuscating e-mail addresses in $text and just use text direction technique.

`<?php echo c2c_obfuscate_email( $text, array(
  array('use_text_direction' => true, 'use_display_none' => false, 'encode_everything' => false, 'at_replace' => '', 'dot_replace' => '')
) ); ?>`


== Filters ==

The plugin exposes one filter for hooking.  Typically, customizations utilizing this hook would be put into your active theme's functions.php file, or used by another plugin.

= c2c_obfuscate_email_filters (filter) =

The 'c2c_obfuscate_email_filters' filter allows you to customize what filters to hook to be filtered with email obfuscation.  The following filters are all filtered by default:

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
// Also obfuscate emails appearing in custom field values
add_filter( 'c2c_obfuscate_email_filters', 'change_c2c_obfuscate_email_filters' );
function change_c2c_obfuscate_email_filters( $filters ) {
	$filters[] = 'the_meta';
	return $filters;
}
`


== Changelog ==

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

= 3.1 =
Recommended update. Fixed a number of bugs; noted WP 3.3 compatibility; dropped support for versions of WP older than 3.1; updated plugin framework; and more.

= 3.0 =
Recommended update.  Major rewrite. Everything has changed and been improved.  You want this.  Compatible with WP 3.0 through 3.2+.