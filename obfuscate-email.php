<?php
/**
 * @package Obfuscate_Email
 * @author Scott Reilly
 * @version 3.1
 */
/*
Plugin Name: Obfuscate E-mail
Version: 3.1
Plugin URI: http://coffee2code.com/wp-plugins/obfuscate-email/
Author: Scott Reilly
Author URI: http://coffee2code.com/
Text Domain: obfuscate-email
Domain Path: /lang/
Description: Obfuscate e-mail addresses to deter e-mail harvesting spammers, while retaining the appearance and functionality of hyperlinks.


Compatible with WordPress 3.1+, 3.2+, 3.3+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/obfuscate-email/

TODO:
	* Add test suite
	* Filter to override class names used 'oe_textdirection', 'oe_displaynone'
	* Allow settings to be configured via define() and settings page deactivated for MS use
	* Consider obscuring "mailto:" as well?
	* Have regexp account for possible spaces around email in attrib. i.e. href="mailto: joe@example.com "
	* Add help tabs explaining the different obfuscation methods
	* Add support for JS ROT13 method?
	= ROT13 encryption =
	*How does it work?* The email addresses are displayed using ROT13 encryption (replacing each letter with the 13th letter that follows it).  JavaScript is used to decode the strings so that visitors see the email properly.  Email scrapers don't typically utilize a JavaScript engine to help determine how text would look onscreen.
	*Uses CSS?* No.
	*Uses JavasScript?* Yes, which means if a visitor does not have JavaScript enabled, the emails will appear garbled.
	*Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have JavaScript disabled).
	*Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes.
	*How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

*/

/*
Copyright (c) 2005-2012 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( ! class_exists( 'c2c_ObfuscateEmail' ) ) :

require_once( 'c2c-plugin.php' );

class c2c_ObfuscateEmail extends C2C_Plugin_034 {

	public static $instance;

	// public until filters are added
	public $css_text_direction  = 'oe_textdirection';
	public $css_display_none    = 'oe_displaynone';

	private $custom_options = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->c2c_ObfuscateEmail();
	}

	public function c2c_ObfuscateEmail() {
		// Be a singleton
		if ( ! is_null( self::$instance ) )
			return;

		parent::__construct( '3.1', 'obfuscate-email', 'c2c', __FILE__, array() );
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
		self::$instance = $this;
	}

	/**
	 * Handles activation tasks, such as registering the uninstall hook.
	 *
	 * @since 3.0
	 *
	 * @return void
	 */
	public function activation() {
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * Handles uninstallation tasks, such as deleting plugin options.
	 *
	 * This can be overridden.
	 *
	 * @since 3.0
	 *
	 * @return void
	 */
	public function uninstall() {
		delete_option( 'c2c_obfuscate_email' );
	}

	/**
	 * Initializes the plugin's config data array.
	 *
	 *
	 * @since 3.0
	 *
	 * @return void
	 */
	public function load_config() {
		$this->name      = __( 'Obfuscate E-mail', $this->textdomain );
		$this->menu_name = __( 'Obfuscate E-mail', $this->textdomain );

		$this->config = array(
			'encode_everything' => array( 'input' => 'checkbox', 'default' => true,
					'label' => __( 'Obfuscate entire e-mail address?', $this->textdomain ),
					'help' => __( 'All characters of the e-mail address will be obfuscated using hexadecimal HTML entity substitution.', $this->textdomain ) ),
			'at_replace' => array('input' => 'text', 'default' => '',
					'label' => __( 'Replacement for \'<code>@</code>\'', $this->textdomain ),
					'help' => __( 'Only applicable if \'Obfuscate entire e-mail address?\' is not checked.<br />Ex. set this to \'[at]\' to get person[at]email.com<br />If applicable but not defined, then <code>&amp;#064;</code> (which is the encoding for &#064;) will be used.', $this->textdomain ) ),
			'dot_replace' => array( 'input' => 'text', 'default' => '',
					'label' => __( 'Replacement for \'<code>.</code>\'', $this->textdomain ),
					'help' => __( 'Only applicable if \'Obfuscate entire e-mail address?\' is not checked.<br />Ex. set this to \'[dot]\' to get person@email[dot]com<br />If applicable but not defined, then <code>&amp;#046;</code> (which is the encoding for &#046;) will be used.', $this->textdomain ) ),
			'use_text_direction' => array( 'input' => 'checkbox', 'default' => true,
					'label' => __( 'Utilize CSS text direction technique?', $this->textdomain ),
					'help' => __( 'This reverses the e-mail address strings as they appear in the markup and utilizes CSS to reverse the text back to the correct direction for visitors to see/use.', $this->textdomain ) ),
			'use_display_none' => array( 'input' => 'checkbox', 'default' => true,
					'label' => __( 'Utilize CSS display:none technique?', $this->textdomain ),
					'help' => __( 'This embeds extraneous text within e-mail address strings and then utilizes CSS to hide them so they don\'t appear to visitors.', $this->textdomain ) ),
		);
	}

	/**
	 * Override the plugin framework's register_filters() to actually actions against filters.
	 *
	 * @since 3.0
	 *
	 * @return void
	 */
	public function register_filters() {
		$filters = (array) apply_filters( 'c2c_obfuscate_email_filters', array(
		 'link_description', 'link_notes', 'bloginfo', 'nav_menu_description',
		 'term_description', 'the_title', 'the_content', 'get_the_excerpt', 'comment_text', 'list_cats', 'widget_text',
		 'the_author_email', 'get_comment_author_email' ) );
		foreach( $filters as $filter )
			add_filter( $filter, array( &$this, 'obfuscate_email' ), 15 );

		add_action( 'wp_head', array( &$this, 'add_css' ) );
	}

	/**
	 * Outputs CSS
	 */
	function add_css() {
		echo <<<CSS
		<style type="text/css">
		span.{$this->css_text_direction} { unicode-bidi:bidi-override; direction: rtl; }
		span.{$this->css_display_none} { display:none; }
		</style>

CSS;
	}

	/**
	 * Obfuscate plaintext emails.
	 *
	 * @param string $text The text containing emails to obfuscate
	 * @param array $args An array of configuration options, each element of which will override the plugin's corresponding default setting.
	 * @return string The text with emails obfuscated.
	 */
	function obfuscate_email( $text, $args = array() ) {
		$options = $this->get_options();

		if ( ! empty( $args ) )
			$options = $this->options = wp_parse_args( $args, $options );

		$text = ' ' . $text . ' ';

		$cb               = array( &$this, 'obfuscate_email_cb' );
		$cb_for_attribute = array( &$this, 'obfuscate_email_in_attribute_cb' );

		// pre-3.0 regex : "#(([a-z0-9\-_\.]+?)@([^\s,{}\(\)\[\]]+\.[^\s.,{}\(\)\[\]]+))#iesU"

		// This isn't making any attempts to only recognize valid email address
		// syntax. Basically anything roughly like x@y.zz looks like an email.
		$email_regex = '([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})';

		// This matches emails except for those appearing in tag attributes.
		// (only need that refinement if wrapping text in tags)
		if ( $options['use_text_direction'] || $options['use_display_none'] )
			$text = preg_replace_callback( "#(?!<.*?)$email_regex(?![^<>]*?>)#i", $cb, $text );

		// If checking again, then the only emails that are left are those in
		// attributes. Is the first check if use_text_direction is false.
		$text = preg_replace_callback( "#$email_regex#i", $cb_for_attribute, $text );

		return trim( $text );
	}

	/**
	 * Obfuscate plaintext emails callback for email address appearing in tag
	 * attribute.
	 *
	 * @since 3.1
	 *
	 * @param string $email The email to obfuscate
	 * @return string The obfuscated email.
	 */
	function obfuscate_email_in_attribute_cb( $matches ) {
		return $this->obfuscate_email_cb( $matches, false );
	}

	/**
	 * Obfuscate plaintext emails callback.
	 *
	 * @param string $email The email to obfuscate
	 * @return string The obfuscated email.
	 */
	function obfuscate_email_cb( $matches, $allow_markup = true ) {
		$options = $this->get_options();

		$email_name   = $matches[1];
		$email_host   = $matches[2];

		if ( $allow_markup && $options['use_text_direction'] ) {
			$email_name = strrev( $email_name );
			$email_host = strrev( $email_host );
		}

		if ( $options['encode_everything'] ) {
			$email_name = substr( chunk_split( bin2hex( " $email_name" ), 2, ";&#x" ), 3, -3 );
			$email_host = substr( chunk_split( bin2hex( " $email_host" ), 2, ";&#x" ), 3, -3 );
			$at = '&#x40;';
		} else {
			$at  = empty( $options['at_replace'] )  ? '&#064;' : $options['at_replace'];
			$dot = empty( $options['dot_replace'] ) ? '&#046;' : $options['dot_replace'];

			if ( ! $allow_markup ) {
				$at  = esc_attr( trim( $at ) );
				$dot = esc_attr( trim( $dot ) );
			}

			$email_host = str_replace( '.', $dot, $email_host );
		}

		if ( $allow_markup && $options['use_display_none'] ) {
			$none = '<span class="' . $this->css_display_none . '">null</span>';
			if ( $options['use_text_direction'] )
				$at = $none . $at;
			else
				$at .= $none;
		}

		if ( $allow_markup && $options['use_text_direction'] )
			$email = $email_host . $at . $email_name;
		else
			$email = $email_name . $at . $email_host;

		if ( $allow_markup && $options['use_text_direction'] )
			$email = '<span class="' . $this->css_text_direction . '">' . $email . '</span>';

		return $email;
	}

} // end c2c_ObfuscateEmail

// Access the instance object via: c2c_ObfuscateEmail::$instance
new c2c_ObfuscateEmail();

endif; // end if !class_exists()


if ( ! function_exists( 'c2c_obfuscate_email' ) ) :
/**
 * Text to parse for plaintext emails to be obfuscated
 *
 * @param string $email The text to parse for e-mail addresses to obfuscate.
 * @param array $args An array of configuration options, each element of which will override the plugin's corresponding default setting.
 * @return string The text with e-mail addresses obfuscated.
 */
function c2c_obfuscate_email( $text, $args = array(), $deprecated_1 = null, $deprecated_2 = null, $deprecated_3 = null ) {
	if ( ! is_array( $args ) || $deprecated_1 || $deprecated_2 || $deprecated_3 )
		_deprecated_argument( __FUNCTION__, '3.0', 'Use $args array argument to override default settings.' );

	return c2c_ObfuscateEmail::$instance->obfuscate_email( $text, $args );
}
endif;


/**
 * *******************
 * DEPRECATED FUNCTIONS
 * *******************
 */

	// To be removed in v3.1 of plugin
	if ( ! function_exists( 'c2c_email_obfuscator' ) ) :
	/**
	 * Obfuscate plaintext emails
	 *
	 * @deprecated since 3.0 Use c2c_obfuscate_email()
	 *
	 * @param string $email The email to obfuscate
	 * @return string The obfuscated email.
	 */
	function c2c_email_obfuscator( $email, $encode_everything = true, $at_replace = '&#064;', $dot_replace = '&#046;' ) {
		_deprecated_function( __FUNCTION__, '3.0', 'c2c_obfuscate_email' );
		return c2c_obfuscate_email( $email );
	}
	endif;

?>