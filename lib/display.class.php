<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Display{
    
    public $has_done_title = false;
    
    public function init(){ 
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'wp', array( $this, 'addSharingFilters' ) );
    }
    
    /**
     * getCommon returns the common class instance
     * @since 0.1
     * @author Russell Fair
     */
    public function getCommon() {
        return $this->common;
    }
    
    public function maybeShowSharing( $show = false ){
        if( is_singular() ){
            $show = in_array( get_post_type() , $this->common->getActivePostTypes() );
            if( ! $show ) //no point in continuing if not active on post type...
                return false;
        }

        return $show;        
    }
    
    public function addSharingFilters() {
        if( $this->maybeShowSharing() ){
            $locations = $this->common->getActiveLocations();
            foreach( $locations as $location => $location_args ) {
                if( isset( $location_args['filter'] ) )
                    add_filter( $location_args['filter'] , array( $this, $location_args['filter'] ), 10 );
                elseif( isset( $location_args['action'] ) )
                    add_action( $location_args['action'] , array( $this, $location_args['action'] ), 10 );
            }
        }
    }
    
    public function the_title( $title ){
        if( $this->has_done_title )
            return $title;
            
        global $wp_query;
        if( ! in_the_loop() ){
            return $title;
        }
        $this->has_done_title = true;
        return $title . $this->generateShareBarMarkup();
    } 
        
    public function generateShareBarMarkup() {
        return 'I would like a share bar here plase';
    }
    
    
    
}
