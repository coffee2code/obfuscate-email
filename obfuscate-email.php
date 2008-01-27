<?php
/*
Plugin Name: Obfuscate E-mail
Version: 2.0
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Obfuscate e-mail addresses in text and links via random hex and ASCII code substitutions while retaining the appearance and functionality of hyperlinks.

Any textual occurrence of an e-mail address in a post body, excerpt, or comment (and when admin and user emails are retrieved for display) will be obfuscated.

By default, the entire e-mail address will be obfuscated using random hex and ASCII code substitutions.  For example, 

`<a href="mailto:person@example.com">person@example.com</a>`

Would be translated into something like this:

`<a href="mailto:&#112;&#101;&#114;&#115;&#x6F;&#110;&#x40;&#101;&#x78;&#x61;&#x6D;&#112;&#x6C;&#101;&#x2E;&#x63;om">&#112;&#x65;&#x72;&#x73;&#111;&#110;&#64;&#x65;&#x78;&#x61;&#109;&#x70;&#108;&#x65;&#x2E;&#99;om</a>`

However, in your browser it would appear to you as it does prior to obfuscation, and the link for the e-mail would still work.  Theoretically, however, spammers would have a somewhat more difficult time harvesting the e-mails you display or link to in your posts.

Via the plugin's admin options page located at `Options` -> `Obfuscate Emails` you can opt to only partially obfuscate an e-mail address.  By defining custom replacements for the `@` and `.` characters, you can have e-mails display like:

`<a href="mailto:person[at]example[dot]com">person[at]example[dot]com</a>`

or

`<a href="mailto:person@DELETETHIS.com">person@DELETETHISexample.com</a>`

(Only when using the custom replacement feature will visitors need to modify the e-mail address for use in their e-mail program.)

Compatible with WordPress 2.2+, and 2.3+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/obfuscate-email.zip and unzip it into your 
/wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/obfuscate-email.phps ) into a file called 
obfuscate-email.php, and put that file into your /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Go to the new Options -> Obfuscate Email admin options page.  Optionally customize the options.

*/

/*
Copyright (c) 2005-2008 by Scott Reilly (aka coffee2code)

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

if ( !class_exists('ObfuscateEmail') ) :

class ObfuscateEmail {
	var $admin_options_name = 'c2c_obfuscate_email';
	var $nonce_field = 'update-obfuscate_email';
	var $show_admin = true;	// Change this to false if you don't want the plugin's admin page shown.
	var $config = array();

	function ObfuscateEmail() {
		add_action('admin_menu', array(&$this, 'admin_menu'));

		add_filter('the_content', array(&$this, 'obfuscate_email'), 15);
		add_filter('get_the_excerpt', array(&$this, 'obfuscate_email'), 15);
		add_filter('comment_text', array(&$this, 'obfuscate_email'), 15);
		add_filter('the_author_email', array(&$this, 'obfuscate_email'), 15);
		add_filter('get_comment_author_email', array(&$this, 'obfuscate_email'), 15);
		
		$this->config = array(
			'encode_everything' => array('input' => 'checkbox', 'default' => true,
					'label' => 'Obfuscate entire email? '),
			'at_replace' => array('input' => 'text', 'default' => '',
					'label' => 'Replacement for \'<code>@</code>\'',
					'help' => 'Only applicable if \'Obfuscate entire email\' is not checked.<br />
								Ex. set this to \'[at]\' to get person[at]email.com<br />
								If applicable but not defined, then <code>&amp;#064;</code> (which is the encoding for &#064;) will be used.'),
			'dot_replace' => array('input' => 'text', 'default' => '',
					'label' => 'Replacement for \'<code>.</code>\'',
					'help' => 'Only applicable if \'Obfuscate entire email\' is not checked.<br />
								Ex. set this to \'[dot]\' to get person@email[dot]com<br />
								If applicable but not defined, then <code>&amp;#046;</code> (which is the encoding for &#046;) will be used.')
		);
	}

	function install() {
		$options = $this->get_options();
		update_option($this->admin_options_name, $options);
	}

	function admin_menu() {
		if ($this->show_admin)
			add_options_page('Obfuscate E-mail', 'Obfuscate E-mail', 9, basename(__FILE__), array(&$this, 'options_page'));
	}

	function get_options() {
		// Derive options from the config
		$options = array();
		foreach (array_keys($this->config) as $opt) {
			$options[$opt] = $this->config[$opt]['default'];
		}
        $existing_options = get_option($this->admin_options_name);
        if (!empty($existing_options)) {
            foreach ($existing_options as $key => $option)
                $options[$key] = $option;
        }            
        return $options;
	}

	function options_page() {
		$options = $this->get_options();
		// See if user has submitted form
		if ( isset($_POST['submitted']) ) {
			check_admin_referer($this->nonce_field);

			foreach (array_keys($options) AS $opt) {
				$options[$opt] = $_POST[$opt];
				if (($this->config[$opt]['input'] == 'checkbox') && !$options[$opt])
					$options[$opt] = 0;
			}
			// Remember to put all the other options into the array or they'll get lost!
			update_option($this->admin_options_name, $options);

			echo "<div class='updated'><p>Plugin settings saved.</p></div>";
		}

		$action_url = $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);

		echo <<<END
		<div class='wrap'>\n
			<h2>Obfuscate E-mail Plugin Options</h2>\n
			<p>Obfuscate e-mail addresses that appear as text in your blog in an effort to deter e-mail harvesting spammers.</p>
			
			<form name="obfuscate_email" action="$action_url" method="post">	
END;
				wp_nonce_field($this->nonce_field);
		echo '<fieldset class="option"><table width="100%" cellspacing="2" cellpadding="5" class="optiontable editform">';
				foreach (array_keys($options) as $opt) {
					$input = $this->config[$opt]['input'];
					if ($input == 'none') continue;
					$label = $this->config[$opt]['label'];
					$value = $options[$opt];
					if ($input == 'checkbox') {
						$checked = ($value == 1) ? 'checked=checked ' : '';
						$value = 1;
					} else {
						$checked = '';
					};
					echo "<tr valign='top'><th width='50%' scope='row'>$label : </th>";
					echo "<td><input name='$opt' type='$input' id='$opt' value='$value' $checked/>";
					if ($this->config[$opt]['help']) {
						echo "<br /><span style='color:#777; font-size:x-small;'>";
						echo $this->config[$opt]['help'];
						echo "</span>";
					}
					echo "</td></tr>";
				}
		echo <<<END
			</table>
			</fieldset>
			<input type="hidden" name="submitted" value="1" />
			<div class="submit"><input type="submit" name="Submit" value="Update Options &raquo;" /></div>
		</form>
			</div>
END;
		echo <<<END
		<div class='wrap' style="text-align:center; color:#888;">This plugin brought to you by <a href="http://coffee2code.com" title="coffee2code.com">Scott Reilly, aka coffee2code</a>.<br /><span style="font-size:x-small;"><a href="http://coffee2code.com/donate">Did you find this plugin useful?</a></span></div>
END;
	}

	function obfuscate_email($email) {
		$options = $this->get_options();
		return c2c_obfuscate_email($email,$options['encode_everything'],$options['at_replace'],$options['dot_replace']);
	}
} // end ObfuscateEmail

endif; // end if !class_exists()
if ( class_exists('ObfuscateEmail') ) :
	// Get the ball rolling
	$obfuscate_email = new ObfuscateEmail();
	// Actions and filters
	if (isset($obfuscate_email)) {
		register_activation_hook( __FILE__, array(&$obfuscate_email, 'install') );
	}
endif;


function c2c_email_obfuscator( $email, $encode_everything = 1, $at_replace = '&#064;', $dot_replace = '&#046;' ) {
	if( !$encode_everything ) {
		if (!$at_replace) $at_replace = '&#064;';
		if (!$dot_replace) $dot_replace = '&#046;';
		$email = str_replace(array('@', '.'), array($at_replace, $dot_replace), $email);
	} else {
		$new_email = '';
		for( $i=0; $i < strlen($email); ++$i ) {
			$n = rand(0,1);
			if( $n )
				$new_email .= '&#x'. sprintf("%X",ord($email{$i})) . ';';
			else
				$new_email .= '&#' . ord($email{$i}) . ';';
		}
		$email = $new_email;
	}
	return $email;
}

function c2c_obfuscate_email( $text, $encode_everything = 1, $at_replace = '&#064;', $dot_replace = '&#046;' ) {
	$text = ' ' . $text . ' ';
	if (!$at_replace) $at_replace = '&#064;';
	if (!$dot_replace) $dot_replace = '&#046;';
	$text = preg_replace("#(([a-z0-9\-_\.]+?)@([^\s,{}\(\)\[\]]+\.[^\s.,{}\(\)\[\]]+))#iesU",
		"c2c_email_obfuscator(\"$1\", $encode_everything, \"$at_replace\", \"$dot_replace\")",
		$text);
	return trim($text);
}

?>