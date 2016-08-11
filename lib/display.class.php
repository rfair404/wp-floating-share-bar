<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Display{
    public function init(){ 
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_filter( 'rlssb_show_sharing', array( $this, 'addSingularCondition' ), 10, 1 );
        add_action( 'init', array( $this, 'addSharingFilters' ) );
    }
    
    /**
     * getCommon returns the common class instance
     * @since 0.1
     * @author Russell Fair
     */
    public function getCommon() {
        return $this->common;
    }
    
    public function maybeShowSharing( ){
        return apply_filters( 'rlssb_show_sharing', false );
    }
    
    public function addSingularCondition( $show = false ){
        if( is_singular() ){
            $show = in_array( get_post_type() , $this->common->getActivePostTypes() );
        }
        return $show;
    }
    
    public function addSharingFilters() {
        $locations = $this->common->getActiveLocations();
        foreach( $locations as $location => $location_args ) {
            if( isset( $location_args['filter'] ) )
                add_filter( $location_args['filter'] , array( $this, 'doSharingBar' ) );
            elseif( isset( $location_args['action'] ) )
                add_action( $location_args['action'] , array( $this, 'doSharingBar' ) );
        }
    }
    
    public function doSharingBar( $incoming ){
        return $incoming . $this->generateShareBarMarkup();
    } 
        
    public function generateShareBarMarkup() {
        return 'I would like a share bar here plase';
    }
    
    
    
}
