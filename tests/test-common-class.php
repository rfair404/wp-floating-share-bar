<?php

class TestCommonClassObject extends WP_UnitTestCase{
    
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
    
    function testCommonGetActivePostTypesReturnsArrayWhenTypesSet(){
        update_option( $this->common->getSlug() . '_active_post_types', array( 'post', 'page', 'custom' ) );
        $this->assertTrue( is_array( $this->common->getActivePostTypes() ) );
        $active_posts = $this->common->getActivePostTypes();
        $this->assertTrue( in_array( 'post',    $active_posts ) );
        $this->assertTrue( in_array( 'page',    $active_posts ) );
        $this->assertTrue( in_array( 'custom',  $active_posts ) );
        delete_option( $this->common->getSlug() . '_active_post_types' );
    }
    
    function testCommonGetActivePostTypesReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() . '_active_post_types' );
        $this->assertFalse( $this->common->getActivePostTypes() );
    }
    
    function testCommonGetActiveNetworksReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() . '_active_networks', array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'linkedin', 'whatsapp' ) );
        $this->assertTrue( is_array( $this->common->getActiveNetworks() ) );
        $active_networks = $this->common->getActiveNetworks();
        $this->assertTrue( in_array( 'facebook',    $active_networks ) );
        $this->assertTrue( in_array( 'twitter',     $active_networks ) );
        $this->assertTrue( in_array( 'googleplus',  $active_networks ) );
        $this->assertTrue( in_array( 'pinterest',   $active_networks ) );
        $this->assertTrue( in_array( 'linkedin',    $active_networks ) );
        $this->assertTrue( in_array( 'whatsapp',    $active_networks ) );
        delete_option( $this->common->getSlug() . '_active_networks' );
    }
    
    function testCommonGetActiveNetworksReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() . '_active_network' );
        $this->assertFalse( $this->common->getActiveNetworks() );
    }
    
    function testCommonGetCustomOrderReturnsArrayWhenSet(){
        update_option( $this->common->getSlug() . '_custom_order', array( 'twitter', 'facebook', 'pinterest', 'googleplus', 'whatsapp', 'linkedin' ) );
        $this->assertTrue( is_array( $this->common->getCustomOrder() ) );
        $custom_order = $this->common->getCustomOrder();
        //fix up
        $this->assertEquals( 'twitter',     $custom_order[0] );
        $this->assertEquals( 'facebook',    $custom_order[1] );
        $this->assertEquals( 'pinterest',   $custom_order[2] );
        $this->assertEquals( 'googleplus',  $custom_order[3] );
        $this->assertEquals( 'whatsapp',    $custom_order[4] );
        $this->assertEquals( 'linkedin',    $custom_order[5] );
        delete_option( $this->common->getSlug() . '_custom_order' );
    }
    
    function testCommonGetCustomOrderReturnsFalseWhenNotSet(){
        delete_option( $this->common->getSlug() . '_custom_order' );
        $this->assertFalse( $this->common->getCustomOrder() );
    }
}