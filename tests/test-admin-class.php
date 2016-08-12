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
        $this->assertEquals( 15, has_filter( 'rlssb_post_types', array( $this->admin, 'addCPTsToPostTypes') ) );
    }
    
    function testAdminCPTFilterAddsPostsToPostTypes() {
        $this->assertEquals( 10, has_filter( 'rlssb_post_types', array( $this->admin, 'addPostsToPostTypes') ) );
    }
    
    function testAdminCPTFilterAddsPagesToPostTypes() {
        $this->assertEquals( 10, has_filter( 'rlssb_post_types', array( $this->admin, 'addPagesToPostTypes') ) );
    }
    
    
    function testAdminGetRegisteredPostTypesReturnsArrayOfObjects() {
        $this->assertTrue( is_array( $this->admin->getRegisteredPostTypes() ) );
    }
    
    function testAdminDefaultNetworksAddsBuiltinNetworksFilter(){
        $this->assertEquals( 10, has_filter( 'rlssb_available_networks', array( $this->admin, 'addBuiltinNetworks' ) ) );
    }
    
    function testAdminSizesFilterAddsDefaultSizesToAvailable(){
        $this->assertEquals( 10, has_filter( 'rlssb_available_sizes', array( $this->admin, 'addBuiltinSizes') ) );
    }
    
    function testAdminLocationsFilterAddsDefaultLocationsToAvailable(){
        $this->assertEquals( 10, has_filter( 'rlssb_available_locations', array( $this->admin, 'addBuiltinLocations') ) );
    }
    
    function testAdminGetRegisterdNetworksReturnsArrayOfNetworks(){
        $this->assertTrue( is_array( $this->admin->getRegisteredNetworks() ) );
    }
    
    function testAdminGetRegisterdNetworksReturnsDefaults() {
        $default_networks = $this->admin->getRegisteredNetworks();
        $this->assertTrue( isset( $default_networks['twitter'] ) );
        $this->assertTrue( isset( $default_networks['facebook'] ) );
        $this->assertTrue( isset( $default_networks['googleplus'] ) );
        $this->assertTrue( isset( $default_networks['pinterest'] ) );
        $this->assertTrue( isset( $default_networks['linkedin'] ) );
        $this->assertTrue( isset( $default_networks['whatsapp'] ) );
    }
    
    function testAdminGetRegisteredPostTypeIncludesPostsAndPages() {
        $registered_types = $this->admin->getRegisteredPostTypes();
        $this->assertTrue( isset( $registered_types['post']  ) );
        $this->assertTrue( isset( $registered_types['page']  ) );
    }
    
    function testAdminGetRegisteredPostTypeIncludesCustomPostTypes() {
        register_post_type( 'rf_test', array( 'public' => true ) );
        $registered_types = $this->admin->getRegisteredPostTypes();
        $this->assertTrue( isset( $registered_types['rf_test']  ) );
    }
    
    function testAdminGetRegisterdLocationsReturnsArrayOfLocations(){
        $this->assertTrue( is_array( $this->admin->getRegisteredLocations() ) );
    }
    
    function testAdminGenerateCheckboxProducesHTML() {
        $mock_field1 = $this->admin->generateCheckboxMarkup( 'testNAME', 'testVALUE', 'testLABEL', false );
        $this->assertEquals( "<input class='testNAME' type='checkbox' name='" . $this->admin->common->getSlug() . "[testNAME][testVALUE]' value='testVALUE'><label>testLABEL</label><br />", $mock_field1 );
        $mock_field2 = $this->admin->generateCheckboxMarkup( 'test2NAME', 'test2VALUE', 'test2LABEL', true );
        $this->assertEquals( "<input class='test2NAME' type='checkbox' name='" . $this->admin->common->getSlug() . "[test2NAME][test2VALUE]' value='test2VALUE' checked='checked'><label>test2LABEL</label><br />", $mock_field2 );
    }
    
    function testAdminGenerateSelectOPtionProducesHTML() {
        $mock_field3 = $this->admin->generateSelectOptionMarkup( 'test3VALUE', 'test3LABEL', false );
        $this->assertEquals( "<option value='test3VALUE'>test3LABEL</option>", $mock_field3 );
        $mock_field4 = $this->admin->generateSelectOptionMarkup( 'test4VALUE', 'test4LABEL', true );
        $this->assertEquals( "<option value='test4VALUE' selected='selected'>test4LABEL</option>", $mock_field4 );
    }
    
    function testAdminSettingsValidateReturnsEmptyArrayWhenInvalidOptionsPassed() {
        $invalid = array( 'foo' => 'bar' );
        $this->assertEquals( array() , $this->admin->settingsValidate( $invalid ) );
    }
    
    function testAdminSettingsValidateReturnsPostTypesAsArray() {
        $post_types = array( 'post_types' => array( 'post' => 'post', 'page' => 'page' ) );
        $this->assertEquals( array( 'post_types' => array( 'post', 'page' ) ) , $this->admin->settingsValidate( $post_types ) );
    }
    
    function testAdminSettingsValidateReturnsNetworksAsArray() {
        $networks = array( 'active_networks' => array( 'facebook' => 'facebook', 'twitter' => 'twitter' ) );
        $this->assertEquals( array( 'active_networks' => array( 'facebook', 'twitter' ) ) , $this->admin->settingsValidate( $networks ) );
    }
    
    function testAdminSettingsValidateReturnsCustomOrderAsArray() {
        $custom_order = array( 'sort_order' => 'twitter,facebook' );
        $this->assertEquals( array( 'sort_order' => array( 'twitter', 'facebook' ) ) , $this->admin->settingsValidate( $custom_order ) );
    }
    
    function testAdminSettingsValidateReturnsDisplaySettingsArray() {
        $display_settings = array( 'display_settings' => array( 'size' => 'small', 'background_color' => '#C0FFEE' , 'text_color' => '#111111' ) );
        $this->assertEquals( array( 'display_settings' => array( 'size' => 'small', 'background_color' => '#C0FFEE' , 'text_color' => '#111111' ) ), $this->admin->settingsValidate( $display_settings ) );
    }
    
    function testAdminSettingsValidateReturnsLocationWithAction() {
        $location_settings = array( 'active_locations' => array( 'floating_left' => array() ) );
        $this->assertEquals( array( 'active_locations' => array( 'floating_left' => array( 'action' => 'wp_print_footer_scripts' ) ) ) , $this->admin->settingsValidate( $location_settings ) );
    }
    
    function testAdminSettingsValidateMergesLocationWithFilter() {
        $location_settings = array( 'active_locations' => array( 'after_content' => array() ) );
        $this->assertEquals( array( 'active_locations' => array( 'after_content' => array( 'filter' => 'the_content' ) ) ) , $this->admin->settingsValidate( $location_settings ) );
    }
    
    function testAdminGetRegisteredSizesReturnsArrayOfSizes() {
        $sizes = $this->admin->getRegisteredSizes();
        $this->assertTrue( is_array( $sizes ) );
    }
    
    function testAdminGetRegisterdSizesIncludesDefaults() {
        $sizes = $this->admin->getRegisteredSizes();
        $this->assertTrue( isset( $sizes['small'] ) );  
        $this->assertTrue( isset( $sizes['medium'] ) );  
        $this->assertTrue( isset( $sizes['large'] ) );  
    }
    
}