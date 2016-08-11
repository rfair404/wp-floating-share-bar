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
    
    function testDisplayInitAddsFilters(){
        $this->assertEquals( 10, has_action( 'wp', array( $this->display, 'addSharingFilters') ) );
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
    
    function testDisplayAddSharingFiltersFalseWhenNoLocationsSet(){
        delete_option( $this->display->common->getSlug() );
        $this->display->addSharingFilters();
        $this->assertFalse( has_filter( 'the_title' ,           array( $this->display, 'doSharingBar') ) );
        // this is the default option and is forced on...
        // $this->assertEquals( 10, has_filter( 'the_content' ,    array( $this->display, 'doSharingBar') ) );
        $this->assertFalse( has_action( 'get_footer' ,          array( $this->display, 'doSharingBar') ) );
        $this->assertFalse( has_filter( 'post_thumbnail_html' , array( $this->display, 'doSharingBar') ) );
    }
    
    function testDisplayAddSharingFiltersTrueWhenLocationSet() {
        update_option( $this->display->common->getSlug() , array( 
            'post_types' => array( 'post' ),
            'active_locations' => array( 
                'featured_image'=> array( 
                    'filter'    => 'post_thumbnail_html' 
                ),  
                'after_content' => array( 
                    'filter'    => 'the_content'
                ),
                'floating_left' => array( 
                    'action'    => 'get_footer'
                )
            )
        ) ); 

        $post = wp_insert_post( array( 
            'post_title'    => 'testPOST',
            'post_status'   => 'publish',
            'post_type'     => 'post', 
            'post_content'  => 'this is the content of a post, it should be much longer in practice.'
        ) ) ;

        $this->go_to( get_permalink( $post ) );
        global $wp_query;
        $this->display->addSharingFilters();
        
        $this->assertEquals( 10, has_filter( 'post_thumbnail_html' ,    array( $this->display, 'doSharingBar') ) );
        $this->assertEquals( 10, has_filter( 'the_content' ,            array( $this->display, 'doSharingBar') ) );
        $this->assertEquals( 10, has_action( 'get_footer' ,             array( $this->display, 'doSharingBar') ) );
    }

}