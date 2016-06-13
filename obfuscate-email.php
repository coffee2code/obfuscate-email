<?php
/**
 * Plugin Name: Obfuscate Email
 * Version:     3.5.1
 * Plugin URI:  http://coffee2code.com/wp-plugins/obfuscate-email/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * Text Domain: obfuscate-email
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Obfuscate email addresses to deter email harvesting spammers, while retaining the appearance and functionality of hyperlinks.
 *
 * Compatible with WordPress 4.1+ through 4.5+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/obfuscate-email/
 *
 * @package Obfuscate_Email
 * @author  Scott Reilly
 * @version 3.5.1
 */

/*
 * TODO:
 *
 * - Hook or constant to prevent inline CSS from being output. If so, add FAQ about it and show example default CSS rules.
 *   If not, add an FAQ showing the code snippet to disable output of default inline CSS.
 * - Filter to override class names used 'oe_textdirection', 'oe_displaynone'
 * - Consider obscuring "mailto:" as well?
 * - Have regexp account for possible spaces around email in attrib. i.e. href="mailto: joe@example.com "
 * - Add help tabs explaining the different obfuscation methods
 * - Add support for JS ROT13 method?
 *   = ROT13 encryption =
 *   *How does it work?* The email addresses are displayed using ROT13 encryption (replacing each letter with the 13th letter that follows it). JavaScript is used to decode the strings so that visitors see the email properly. Email scrapers don't typically utilize a JavaScript engine to help determine how text would look onscreen.
 *   *Uses CSS?* No.
 *   *Uses JavasScript?* Yes, which means if a visitor does not have JavaScript enabled, the emails will appear garbled.
 *   *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have JavaScript disabled).
 *   *Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes.
 *   *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.
 */

/*
	Copyright (c) 2005-2016 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_ObfuscateEmail' ) ) :

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'c2c-plugin.php' );

final class c2c_ObfuscateEmail extends c2c_ObfuscateEmail_Plugin_044 {

	/**
	 * The one true instance.
	 *
	 * @var c2c_ObfuscateEmail
	 */
	private static $instance;

	/**
	 * Default CSS class to indicate text direction enabled (public until filter is added).
	 *
	 * @var string
	 */
	public $css_text_direction  = 'oe_textdirection';

	/**
	 * CSS class to indicate non-display (public until filter is added).
	 *
	 * @var string
	 */
	public $css_display_none    = 'oe_displaynone';

	/**
	 * Get singleton instance.
	 *
	 * @since 3.2
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct( '3.5.1', 'obfuscate-email', 'c2c', __FILE__, array() );
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );

		return self::$instance = $this;
	}

	/**
	 * Handles activation tasks, such as registering the uninstall hook.
	 *
	 * @since 3.0
	 */
	public static function activation() {
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * Handles uninstallation tasks, such as deleting plugin options.
	 *
	 * @since 3.0
	 */
	public static function uninstall() {
		delete_option( 'c2c_obfuscate_email' );
	}

	/**
	 * Initializes the plugin's config data array.
	 *
	 *
	 * @since 3.0
	 */
	public function load_config() {
		$this->name      = __( 'Obfuscate E-mail', 'obfuscate-email' );
		$this->menu_name = __( 'Obfuscate E-mail', 'obfuscate-email' );

		$this->config = array(
			'encode_everything' => array(
				'input'   => 'checkbox',
				'default' => true,
				'label'   => __( 'Obfuscate entire email address?', 'obfuscate-email' ),
				'help'    => __( 'All characters of the email address will be obfuscated using hexadecimal HTML entity substitution.', 'obfuscate-email' ),
			),
			'at_replace' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Replacement for \'<code>@</code>\'', 'obfuscate-email' ),
				'help'    => __( 'Only applicable if \'Obfuscate entire email address?\' is not checked.<br />Ex. set this to \'[at]\' to get person[at]email.com<br />If applicable but not defined, then <code>&amp;#064;</code> (which is the encoding for &#064;) will be used.', 'obfuscate-email' ),
			),
			'dot_replace' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Replacement for \'<code>.</code>\'', 'obfuscate-email' ),
				'help'    => __( 'Only applicable if \'Obfuscate entire email address?\' is not checked.<br />Ex. set this to \'[dot]\' to get person@email[dot]com<br />If applicable but not defined, then <code>&amp;#046;</code> (which is the encoding for &#046;) will be used.', 'obfuscate-email' ),
			),
			'use_text_direction' => array(
				'input'   => 'checkbox',
				'default' => false,
				'label'   => __( 'Utilize CSS text direction technique?', 'obfuscate-email' ),
				'help'    => __( 'This reverses the email address strings as they appear in the markup and utilizes CSS to reverse the text back to the correct direction for visitors to see/use. Note that copying-and-pasting of unlinked text email addresses will result in the pasted text being reversed. Copying the email address (via right-click menu) will work properly.', 'obfuscate-email' ),
			),
			'use_display_none' => array(
				'input'   => 'checkbox',
				'default' => true,
				'label'   => __( 'Utilize CSS display:none technique?', 'obfuscate-email' ),
				'help'    => __( 'This embeds extraneous text within email address strings and then utilizes CSS to hide them so they don\'t appear to visitors.', 'obfuscate-email' ),
			),
		);
	}

	/**
	 * Override the plugin framework's register_filters() to actually hook actions/filters.
	 *
	 * @since 3.0
	 * @since 3.4 Don't do anything if in the admin area.
	 */
	public function register_filters() {
		// Don't obfuscate email addresses in the admin area.
		if ( is_admin() ) {
			return;
		}

		$filters = (array) apply_filters(
			'c2c_obfuscate_email_filters',
			array(
				'link_description', 'link_notes', 'bloginfo', 'nav_menu_description',
				'term_description', 'the_title', 'the_content', 'get_the_excerpt', 'comment_text', 'list_cats', 'widget_text',
				'the_author_email', 'get_comment_author_email',
			)
		);

		foreach( $filters as $filter ) {
			add_filter( $filter, array( $this, 'obfuscate_email' ), 15 );
		}

		add_action( 'wp_head', array( $this, 'add_css' ) );
	}

	/**
	 * Output CSS.
	 */
	public function add_css() {
		echo <<<HTML
		<style type="text/css">
		span.{$this->css_text_direction} { unicode-bidi: bidi-override; direction: rtl; }
		span.{$this->css_display_none} { display: none; }
		</style>

HTML;
	}

	/**
	 * Obfuscate plaintext emails.
	 *
	 * @param string  $text The text containing emails to obfuscate.
	 * @param array   $args An array of configuration options, each element of which will override the plugin's corresponding default setting.
	 * @return string The text with emails obfuscated.
	 */
	public function obfuscate_email( $text, $args = array() ) {
		$options = $this->get_options();

		if ( $args ) {
			$options = $this->options = wp_parse_args( $args, $options );
		}

		$text = ' ' . $text . ' ';

		$cb               = array( $this, 'obfuscate_email_cb' );
		$cb_for_attribute = array( $this, 'obfuscate_email_in_attribute_cb' );

		// pre-3.0 regex : "#(([a-z0-9\-_\.]+?)@([^\s,{}\(\)\[\]]+\.[^\s.,{}\(\)\[\]]+))#iesU"

		// This isn't making any attempts to only recognize valid email address
		// syntax. Basically anything roughly like x@y.zz looks like an email address.
		$email_regex = '([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})';

		// This matches emails except for those appearing in tag attributes.
		// (only need that refinement if wrapping text in tags)
		if ( $options['use_text_direction'] || $options['use_display_none'] ) {
			$text = preg_replace_callback( "#(?!<.*?)$email_regex(?![^<>]*?>)#i", $cb, $text );
		}

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
	 * @param string  $email The email to obfuscate.
	 * @return string The obfuscated email.
	 */
	public function obfuscate_email_in_attribute_cb( $matches ) {
		return $this->obfuscate_email_cb( $matches, false );
	}

	/**
	 * Obfuscate plaintext emails callback.
	 *
	 * @param string  $email The email to obfuscate.
	 * @return string The obfuscated email.
	 */
	public function obfuscate_email_cb( $matches, $allow_markup = true ) {
		$options = $this->get_options();

		$email_name = $matches[1];
		$email_host = $matches[2];

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

			// If reversing text, also reverse the AT and DOT replacements unless they
			// look like they are HTML encoded entities. Very crude.
			if ( $options['use_text_direction'] ) {
				if ( false === strpos( $at, '&' ) ) {
					$at = strrev( $at );
				}
				if ( false === strpos( $dot, '&' ) ) {
					$dot = strrev( $dot );
				}
			}

			$email_host = str_replace( '.', $dot, $email_host );
		}

		if ( $allow_markup && $options['use_display_none'] ) {
			$none = '<span class="' . $this->css_display_none . '">null</span>';
			if ( $options['use_text_direction'] ) {
				$at = $none . $at;
			} else {
				$at .= $none;
			}
		}

		if ( $allow_markup && $options['use_text_direction'] ) {
			$email = $email_host . $at . $email_name;
		} else {
			$email = $email_name . $at . $email_host;
		}

		if ( $allow_markup && $options['use_text_direction'] ) {
			$email = '<span class="' . $this->css_text_direction . '">' . $email . '</span>';
		}

		return $email;
	}

} // end c2c_ObfuscateEmail

c2c_ObfuscateEmail::instance();

endif; // end if !class_exists()


if ( ! function_exists( 'c2c_obfuscate_email' ) ) :
/**
 * Text to parse for plaintext emails to be obfuscated.
 *
 * @param string  $text  The text to parse for email addresses to obfuscate.
 * @param array   $args  An array of configuration options, each element of which will override the plugin's corresponding default setting.
 * @return string The text with email addresses obfuscated.
 */
function c2c_obfuscate_email( $text, $args = array(), $deprecated_1 = null, $deprecated_2 = null, $deprecated_3 = null ) {
	if ( ! is_array( $args ) || $deprecated_1 || $deprecated_2 || $deprecated_3 ) {
		_deprecated_argument( __FUNCTION__, '3.0', 'Use $args array argument to override default settings.' );
	}

	return c2c_ObfuscateEmail::instance()->obfuscate_email( $text, $args );
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
	 * Obfuscate plaintext emails.
	 *
	 * @deprecated since 3.0 Use c2c_obfuscate_email()
	 *
	 * @param string  $email The email to obfuscate.
	 * @return string The obfuscated email.
	 */
	function c2c_email_obfuscator( $email, $encode_everything = true, $at_replace = '&#064;', $dot_replace = '&#046;' ) {
		_deprecated_function( __FUNCTION__, '3.0', 'c2c_obfuscate_email' );
		return c2c_obfuscate_email( $email );
	}
	endif;
