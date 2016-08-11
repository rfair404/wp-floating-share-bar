<?php

class TestCommonClass extends WP_UnitTestCase{
    
    public function setUp(){
        parent::setUp();
        require_once( dirname( dirname( __FILE__ ) ) . '/lib/common.class.php' );
        $this->common = new RussellsLevitatingSocialShareButtons\Common;
    }
    
    function testCommonGetVersionisInt(){
        $this->assertTrue( is_float( $this->common->getVersion() ) );
    }
    
    function testCommonGetSlug_isString(){
        $this->assertTrue( is_string( $this->common->getSlug() ) );
    }
    
    function testCommonGetSettingsReturnsArrayOfAllSettings() {
        update_option( $this->common->getSlug() , array( 'active_post_types' => array( 'post', 'page' ) ) ); 
        $this->assertTrue( is_array( $this->common->getSettings() ) );
    }
    
    function testCommonGetActivePostTypesReturnsArrayWhenTypesSet(){
        update_option( $this->common->getSlug() , array( 'post_types' => array( 'post', 'page', 'custom' ) ) );
        $this->assertTrue( is_array( $this->common->getActivePostTypes() ) );
        $active_posts = $this->common->getActivePostTypes();
        $this->assertTrue( in_array( 'post',    $active_posts ) );
        $this->assertTrue( in_array( 'page',    $active_posts ) );
        $this->assertTrue( in_array( 'custom',  $active_posts ) );
        delete_option( $this->common->getSlug() );
    }
    
    function testCommonGetActivePostTypesReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() );
        $this->assertFalse( $this->common->getActivePostTypes() );
    }
    
    function testCommonGetActiveNetworksReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() , array( 'active_networks' => array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'linkedin', 'whatsapp' ) ) );
        $this->assertTrue( is_array( $this->common->getActiveNetworks() ) );
        $active_networks = $this->common->getActiveNetworks();
        $this->assertTrue( in_array( 'facebook',    $active_networks ) );
        $this->assertTrue( in_array( 'twitter',     $active_networks ) );
        $this->assertTrue( in_array( 'googleplus',  $active_networks ) );
        $this->assertTrue( in_array( 'pinterest',   $active_networks ) );
        $this->assertTrue( in_array( 'linkedin',    $active_networks ) );
        $this->assertTrue( in_array( 'whatsapp',    $active_networks ) );
        delete_option( $this->common->getSlug() );
    }
    
    function testCommonGetActiveNetworksReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() );
        $this->assertFalse( $this->common->getActiveNetworks() );
    }
    
    function testCommonGetCustomOrderReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() , array( 'custom_order' => array( 'twitter', 'facebook', 'pinterest', 'googleplus', 'whatsapp', 'linkedin' ) ) );
        $this->assertTrue( is_array( $this->common->getCustomOrder() ) );
        $custom_order = $this->common->getCustomOrder();
        $this->assertEquals( 'twitter',     $custom_order[0] );
        $this->assertEquals( 'facebook',    $custom_order[1] );
        $this->assertEquals( 'pinterest',   $custom_order[2] );
        $this->assertEquals( 'googleplus',  $custom_order[3] );
        $this->assertEquals( 'whatsapp',    $custom_order[4] );
        $this->assertEquals( 'linkedin',    $custom_order[5] );
        delete_option( $this->common->getSlug() );
    }
    
    function testCommonGetCustomOrderReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() );
        $this->assertFalse( $this->common->getCustomOrder() );
    }
    
    function testCommonGetLocationSettingsReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() , array( 'active_locations' => array( 'after_title' => array() , 'featured_image' => array() ) ) );
        $this->assertTrue( is_array( $this->common->getLocationSettings() ) );
        $location_settings = $this->common->getLocationSettings();
        $this->assertTrue( isset( $location_settings['after_title'] ) );
        $this->assertTrue( isset( $location_settings['featured_image'] ) );
        delete_option( $this->common->getSlug() );
    }
    
    function testCommonGetLocationSettingsReturnsAfterContentWhenNotSet(){
        delete_option( $this->common->getSlug() );
        $this->assertTrue( is_array( $this->common->getLocationSettings() ) );
        $location_settings = $this->common->getLocationSettings();
        $this->assertTrue( isset( $location_settings['after_content'] ) );
        delete_option( $this->common->getSlug() );
    }
    
    
   
   
    function testCommonGetDisplaySettingsReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() , array( 'display_settings' => array( 'size' => '32x32', 'default' => false, 'color' => '#C0FFEE' ) ) );
        $this->assertTrue( is_array( $this->common->getDisplaySettings() ) );
        $display_settings = $this->common->getDisplaySettings();
        $this->assertEquals( $display_settings['size'], '32x32' );
        $this->assertEquals( $display_settings['color'], '#C0FFEE' );
        $this->assertFalse( $display_settings['default'] );
        delete_option( $this->common->getSlug() );
    }
    
    function testCommonGetDisplaySettingsReturnsDefaultWhenNotSet(){
        delete_option( $this->common->getSlug() );
        $display_settings = $this->common->getDisplaySettings();
        $this->assertEquals( $display_settings['size'], '16x16' );
        $this->assertEquals( $display_settings['default'], true );
        $this->assertFalse( isset( $display_settings['custom'] ) );
    }
    
    function testCommonGetDefaultNetworksReturnsDefaults() {
        $default_networks = $this->common->getDefaultNetworks();
        $this->assertTrue( isset( $default_networks['twitter'] ) );
        $this->assertTrue( isset( $default_networks['facebook'] ) );
        $this->assertTrue( isset( $default_networks['googleplus'] ) );
        $this->assertTrue( isset( $default_networks['pinterest'] ) );
        $this->assertTrue( isset( $default_networks['linkedin'] ) );
        $this->assertTrue( isset( $default_networks['whatsapp'] ) );
    }
    
     function testCommonGetDefaultLocationsReturnsDefaults() {
        $default_locations = $this->common->getDefaultLocations();
        $this->assertTrue( isset( $default_locations['after_title'] ) );
        $this->assertTrue( isset( $default_locations['featured_image'] ) );
        $this->assertTrue( isset( $default_locations['after_content'] ) );
        $this->assertTrue( isset( $default_locations['floating_left'] ) );
    }
    
    

}