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
        echo var_dump( $locations );
    }
    
    public function addSharingToTitle() {
        
    }
    
    
    
}
