<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Display{
    
    public $has_done_title = false;
    public $has_done_content = false;
    public $has_done_body = false;
    
    public function init(){ 
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'wp', array( $this, 'addSharingFilters' ) );
    
        add_filter('rlssb_share_bar_markup' , array( $this, 'shareBarBefore' ), 10, 2 );
        add_filter('rlssb_share_bar_markup' , array( $this, 'shareBarInner' ), 10, 2 );
        add_filter('rlssb_share_bar_markup' , array( $this, 'shareBarAfter' ), 10, 2 );
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
                    add_filter( $location_args['filter'] , array( $this, $location_args['filter'] ), 10, 1 );
                elseif( isset( $location_args['action'] ) )
                    add_action( $location_args['action'] , array( $this, $location_args['action'] ), 10, 1 );
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
        return $title . $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
    public function post_thumbnail_html( $html ){
        global $wp_query;
        if( ! in_the_loop() ){
        //    return $html; 
        }
        return $html . $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
     public function the_content( $content ){
        if( $this->has_done_content )
            return $content;
            
        global $wp_query;
        if( ! in_the_loop() ){
            return $content;
        }
        $this->has_done_content = true;
        return $content . $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
    public function wp_print_footer_scripts(){
            echo $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
      
    public function makeButtons( $caller = false ) {
        $active_networks = $this->common->getActivenetworks();
        $default_networks = $this->common->getDefaultNetworks();
        $button_html = '<ul>';
        foreach( $active_networks as $network ){
           $button_html .= $this->makeButton( $network, $default_networks[$network], $caller ); 
        }
        
        $button_html .= '<ul>';
        return $button_html;
    } 
    
    public function makeButton( $network, $default_network_args, $caller = false ){
        return sprintf('<span class="rlssb-button %s %s"><a href="%s" title="%s %s">%s</a></span>', esc_attr( $network ), $caller , $this->getLinkByContext( $network, $default_network_args ), _x('Share on' , $network,  $this->common->getSlug() ), $default_network_args['name'], $default_network_args['name'] );
    }
    
    public function getLinkByContext( $network, $default_network_args ) {
        switch ($network){
            case 'twitter':
                return urlencode( sprintf($default_network_args['share_url'] , sprintf( '%s - %s', get_the_title(), get_permalink() ) ) ); 
            case 'facebook':
            case 'googleplus':
                return urlencode( sprintf($default_network_args['share_url'] , get_permalink() ) ); 
            case 'pinterest':
                return urlencode( sprintf($default_network_args['share_url'] , get_permalink(), ( has_post_thumbnail() ) ? get_the_post_thumbnail_url() : '' , get_the_title() ) ); 
            case 'linkedin':
                return urlencode( sprintf($default_network_args['share_url'] , get_permalink(), get_the_title(), 'a description eh? ', get_bloginfo('name') ) ); 
            case 'whatsapp':
                return sprintf($default_network_args['share_url'] , get_the_title(), get_permalink() ); 
                //return 'whatsapp://send" data-text="Take a look at this awesome website:" data-href="http://dev.io" class="wa_btn wa_btn_s" style="display:none';
            default: 
                return '#nolink';
            break;
        } 
    }
    
    public function getShareBarMarkup( $caller = false ) {
        return apply_filters( 'rlssb_share_bar_markup' , '', $caller );
    }
    
    public function shareBarBefore( $markupBefore = '', $caller ){
        $thisMarkup = sprintf( '<span class="%s">', $caller );
        return $markupBefore . $thisMarkup;    
    }
    
    public function shareBarInner( $markupBefore = '', $caller = false ){
        return $markupBefore . $this->makeButtons( $caller );
    }
    
    public function shareBarAfter( $markupBefore = '', $caller ){
        $thisMarkup = sprintf( '</span><!-- .eof_%s_rlssb-->', $caller );
        return  $markupBefore . $thisMarkup;    
    }
        
    
}
