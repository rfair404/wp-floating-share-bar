<?php

/**
 * Class FileSystemTests
 * Some basic tests to ensure that the basic file structure is in tact
 * @package 
 */

/**
 * Sample test case.
 */
class FileSystemTests extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_plugin_files_exist() {
		// tests that each of the required files actually exist
		$this->assertTrue( file_exists( dirname( dirname( __FILE__ ) ) . '/russell-fair.php' ) );
		$this->assertTrue( file_exists( dirname( dirname( __FILE__ ) ) . '/lib/common.class.php' ) );
		$this->assertTrue( file_exists( dirname( dirname( __FILE__ ) ) . '/lib/admin.class.php' ) );
		$this->assertTrue( file_exists( dirname( dirname( __FILE__ ) ) . '/lib/display.class.php' ) );
	}
	
	function test_classes_exist() {
		// tests that each of the classes exist
		require_once( dirname( dirname( __FILE__ ) ) . '/russell-fair.php' );
		require_once( dirname( dirname( __FILE__ ) ) . '/lib/common.class.php' );
		require_once( dirname( dirname( __FILE__ ) ) . '/lib/admin.class.php' );
		require_once( dirname( dirname( __FILE__ ) ) . '/lib/display.class.php' );
	
		$this->assertTrue( class_exists( 'RussellsLevitatingSocialShareButtons\Common' ) );
		$this->assertTrue( class_exists( 'RussellsLevitatingSocialShareButtons\Admin' ) );
		$this->assertTrue( class_exists( 'RussellsLevitatingSocialShareButtons\Display' ) );
		
	}
}

