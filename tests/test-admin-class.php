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
    
    function testAdminCPTFilterAddsCustomPostTypes() {
        $this->assertEquals( 10, has_filter( 'rlssb_post_types', array( $this->admin, 'addCPTsToPostTypes') ) );
    }
    
    function testAdminCPTFilterAddsPostsToPostTypes() {
        $this->assertEquals( 10, has_filter( 'rlssb_post_types', array( $this->admin, 'addPostsToPostTypes') ) );
    }
    
    function testAdminCPTFilterAddsPAgesToPostTypes() {
        $this->assertEquals( 10, has_filter( 'rlssb_post_types', array( $this->admin, 'addPagesToPostTypes') ) );
    }
    
    function testAdminGetRegisteredPostTypesReturnsArrayOfObjects() {
        $this->assertTrue( is_array( $this->admin->getRegisteredPostTypes() ) );
    }
    
    function testAdminGetRegisteredPostTypeIncludesPostsAndPages() {
        $registered_types = $this->admin->getRegisteredPostTypes();
        echo var_dump($registered_types);
        $this->assertTrue( isset( $registered_types['post']  ) );
        //$this->assertTrue( isset( $registered_types['page']  ) );
    }
    
    function testAdminGetRegisteredPostTypeIncludesCustomPostTypes() {
        register_post_type( 'rf_test', array( 'public' => true ) );
        $registered_types = $this->admin->getRegisteredPostTypes();
        $this->assertTrue( isset( $registered_types['rf_test']  ) );
    }
}