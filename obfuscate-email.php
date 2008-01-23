<?php
/*
Plugin Name: Obfuscate E-mail
Version: 1.0
Plugin URI: http://www.coffee2code.com/wp-plugins/
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Obfuscate e-mail addresses in text and links via random hex and ASCII code substitution while retaining the appearance and functionality of hyperlinks.

=>> Visit the plugin's homepage for more information and latest updates  <<=

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/obfuscate-email.zip and unzip it into your /wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/obfuscate-email.phps ) into a file called obfuscate-email.php, and put that file into your /wp-content/plugins/ directory.
2. Activate the plugin from your WordPress admin 'Plugins' page.
3. Optional: If you don't want full obfuscation, you can change the value of $encode_everything to 0, in which case only "@" and "." get obfuscated
(i.e. person@example.com becomes person&#064;example&#046;com).

Example (of full obfuscation):

<a href="mailto:person@example.com">person@example.com</a>

Would be translated into something like this:
<a href="mailto:&#112;&#101;&#114;&#115;&#x6F;&#110;&#x40;&#101;&#x78;&#x61;&#x6D;&#112;&#x6C;&#101;&#x2E;&#x63;om">&#112;&#x65;&#x72;&#x73;&#111;&#110;&#64;&#x65;&#x78;&#x61;&#109;&#x70;&#108;&#x65;&#x2E;&#99;om</a>

However, in your browser it would appear to you as it does prior to obfuscation, and the link for the e-mail would still work.  Theorectically, however,
spammers would have a somewhat more difficult time harvesting the e-mails you display  or link to in your posts.

*/

/*
Copyright (c) 2005 by Scott Reilly (aka coffee2code)

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

function c2c_email_obfuscator( $email, $encode_everything = 1 ) {
	if( !$encode_everything )
		$email = str_replace(array('@', '.'), array('&#064;', '&#046;'), $email);
	else {
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

function c2c_obfuscate_email( $text, $encode_everything = 1 ) {
	$text = ' ' . $text . ' ';
	$text = preg_replace("#(([A-Za-z0-9\-_\.]+?)@([^\s,{}\(\)\[\]]+\.[^\s.,{}\(\)\[\]]+))#iesU",
		"c2c_email_obfuscator(\"$1\", $encode_everything)",
		$text);

        return substr($text,1,strlen($text)-2);
}

add_filter('the_content', 'c2c_obfuscate_email', 15);
add_filter('get_the_excerpt', 'c2c_obfuscate_email', 15);
add_filter('comment_text', 'c2c_obfuscate_email', 15);
add_filter('the_author_email', 'c2c_obfuscate_email', 15);
add_filter('get_comment_author_email', 'c2c_obfuscate_email', 15);

?>
