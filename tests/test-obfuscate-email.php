<?php

defined( 'ABSPATH' ) or die();

class Obfuscate_Email_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->set_option();
	}


	//
	//
	// DATA PROVIDERS
	//
	//


	public static function get_default_filters() {
		return array(
			array( 'link_description' ),
			array( 'link_notes' ),
			array( 'bloginfo' ),
			array( 'nav_menu_description' ),
			array( 'term_description' ),
			array( 'the_title' ),
			array( 'the_content' ),
			array( 'get_the_excerpt' ),
			array( 'comment_text' ),
			array( 'list_cats' ),
			array( 'widget_text' ),
			array( 'the_author_email' ),
			array( 'get_comment_author_email' ),
		);
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function set_option( $settings = array() ) {
		$defaults = array(
			'encode_everything'  => true,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => true,
		);
		$settings = wp_parse_args( $settings, $defaults );
		c2c_ObfuscateEmail::instance()->update_option( $settings, true );
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_ObfuscateEmail' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '3.5.1', c2c_ObfuscateEmail::instance()->version() );
	}

	public function test_plugin_framework_class_name() {
		$this->assertTrue( class_exists( 'c2c_ObfuscateEmail_Plugin_044' ) );
	}

	public function test_plugin_framework_version() {
		$this->assertEquals( '044', c2c_ObfuscateEmail::instance()->c2c_plugin_version() );
	}

	public function test_instance_object_is_returned() {
		$this->assertTrue( is_a( c2c_ObfuscateEmail::instance(), 'c2c_ObfuscateEmail' ) );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_hooks_default_filters( $filter ) {
		$this->assertNotFalse( has_filter( $filter, array( c2c_ObfuscateEmail::instance(), 'obfuscate_email' ), 15 ) );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_obfuscation_applies_to_default_filters( $filter ) {
		$email = 'test@example.com';
		$expected = '&#x74;&#x65;&#x73;&#x74;&#x40;<span class="oe_displaynone">null</span>&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;';

		$this->assertEquals( $expected, c2c_obfuscate_email( $email ) );
	}

	public function test_at_and_dot_default_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'test&#064;example&#046;com', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_custom_at_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => 'AT',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'testATexample&#046;com', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_custom_dot_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => 'DOT',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'test&#064;exampleDOTcom', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_text_direction() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => true,
			'use_display_none'   => false,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">moc&#046;elpmaxe&#064;tset</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_display_none() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'test&#064;<span class="oe_displaynone">null</span>example&#046;com',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_display_none_and_text_direction_and_at_and_dot_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">mocTODelpmaxe<span class="oe_displaynone">null</span>TAtset</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_encode_everything() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals(
			'&#x74;&#x65;&#x73;&#x74;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_everything_enabled() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">&#x6d;&#x6f;&#x63;&#x2e;&#x65;&#x6c;&#x70;&#x6d;&#x61;&#x78;&#x65;<span class="oe_displaynone">null</span>&#x40;&#x74;&#x73;&#x65;&#x74;</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_uninstall_deletes_option() {
		$option = 'c2c_obfuscate_email';
		c2c_ObfuscateEmail::instance()->get_options();

		c2c_ObfuscateEmail::uninstall();

		$this->assertFalse( get_option( $option ) );
	}

	/***
	 * ALL ADMIN AREA RELATED TESTS NEED TO FOLLOW THIS FUNCTION
	 ***/

	public function test_turn_on_admin() {
		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', true );
		}

		// Unhook existing hooks. Then re-register them.
		// Necessary due to the singleton nature of the object and where the
		// is_admin() occurs.
		foreach ( $this->get_default_filters() as $filter ) {
			remove_filter( $filter[0], array( c2c_ObfuscateEmail::instance(), 'obfuscate_email' ), 15 );
		}
		remove_action( 'wp_head', array( c2c_ObfuscateEmail::instance(), 'add_css' ) );

		c2c_ObfuscateEmail::instance()->register_filters();

		$this->assertTrue( is_admin() );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_does_not_hook_default_filters_in_admin( $filter ) {
		$this->test_turn_on_admin();

		$this->assertFalse( has_filter( $filter, array( c2c_ObfuscateEmail::instance(), 'obfuscate_email' ) ) );
	}

	public function test_nothing_obfuscated_in_admin_even_with_everything_enabled() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->test_turn_on_admin();

		$text = 'test@example.com';

		$this->assertEquals(
			wpautop( $text ),
			apply_filters( 'the_content', $text )
		);
	}

}
