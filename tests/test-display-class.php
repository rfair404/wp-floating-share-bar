<?php

class TestDisplayClass extends WP_UnitTestCase {
    
    public function setUp(){
        parent::setUp();
        require_once( dirname( dirname( __FILE__ ) ) . '/lib/display.class.php' );
        $this->display = new RussellsLevitatingSocialShareButtons\Display;
        $this->display->init();
    }
    
    function testDisplayInitSetsCommon(){
        $this->assertInstanceOf( 'RussellsLevitatingSocialShareButtons\Common', $this->display->getCommon() );
    }
    
    function testDisplayMaybeShowSharingIsFalseByDefault(){
        $this->assertFalse( $this->display->maybeShowSharing() );
    }
    
    function testShowSharingHasFilterForSingularOnly(){
        $this->assertEquals( 10, has_filter( 'rlssb_show_sharing' , array( $this->display, 'addSingularCondition') ) );
    }
    
    function testShowSharingHasFilterForLocations(){
        $this->assertEquals( 15, has_filter( 'rlssb_show_sharing' , array( $this->display, 'addLocationCondition') ) );
    } 
    
    function testDisplayMaybeShowSharingReturnsTrueWhenSingularPostAndSetInOption(){
        update_option( $this->display->common->getSlug() , array( 'post_types' => array( 'post' ) ) ); 
        //using the factory->mock won't didn't allow for post_types other than post
        $post = wp_insert_post( array( 
            'post_title'    => 'testPOST',
            'post_status'   => 'publish',
            'post_type'     => 'post', 
            'post_content'  => 'this is the content of a post, it should be much longer in practice.'
        ) ) ;

        $this->go_to( get_permalink( $post ) );
        global $wp_query;
        $this->assertTrue( $wp_query->is_singular('post') );
        $this->assertTrue( $this->display->maybeShowSharing() );
    }
    
    function testDisplayMaybeShowSharingReturnsTrueWhenSinglePageAndSetInOption(){
        update_option( $this->display->common->getSlug() , array( 'post_types' => array( 'page' ) ) ); 
        $post = wp_insert_post( array( 
            'post_title'    => 'testPAGE',
            'post_status'   => 'publish',
            'post_type'     => 'page', 
            'post_content'  => 'this is the content of a page, it should be much longer in practice.'
        ) ) ;

        $this->go_to( get_permalink( $post ) );
        global $wp_query;
        $this->assertTrue( $wp_query->is_page() );
        $this->assertTrue( $this->display->maybeShowSharing() );
    }
    
    function testDisplayMaybeShowSharingReturnsTrueWhenSingularCPTAndSetInOption(){
        update_option( $this->display->common->getSlug() , array( 'post_types' => array( 'foo' ) ) ); 
        register_post_type( 'foo', array( 'public' => true ) ); 
        $post = wp_insert_post( array( 
            'post_title'    => 'testCPT',
            'post_status'   => 'publish',
            'post_type'     => 'foo', 
            'post_content'  => 'this is the content of a foo, it should be much longer in practice.'
        ) ) ;

        $this->go_to( get_permalink( $post ) );
        global $wp_query;
        $this->assertTrue( $wp_query->is_singular('foo') );
        $this->assertTrue( $this->display->maybeShowSharing() );
    }
    
    function testDisplayLocationConditionFalseWhenNoLocations() {
        delete_option( $this->display->common->getSlug() );
    }
    
    function testDisplayLocationConditionTrueWhenActivePostTypesANDLocations() {
        update_option( $this->display->common->getSlug() , array( 'locations' => array( 'page' ) ) ); 
    }

}