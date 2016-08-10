<?php

class TestAdminClass extends WP_UnitTestCase{
    public function setUp(){
        parent::setUp();
        require_once( dirname( dirname( __FILE__ ) ) . '/lib/admin.class.php' );
        $this->admin = new RussellsLevitatingSocialShareButtons\Admin;
        $this->admin->init();
    }
    
    function testAdminInitSetsCommon(){
        $this->assertTrue( is_a( $this->admin->getCommon(), 'RussellsLevitatingSocialShareButtons\Common' ) );
    }
    
    function testAdminInitAddsRegisterMenuToInit() {
        $this->assertEquals( 10, has_action( 'admin_menu', array( $this->admin, 'registerMenu') ) );
    }
    
    function testAdminInitAddsRegisterSettingsToInit() {
        $this->assertEquals( 10, has_action( 'admin_init', array( $this->admin, 'registerSettings') ) );
    }
    
    function testAdminRegisterSettingsRegistersPostTypesSetting() {
        $this->assertTrue(true);
    }
}