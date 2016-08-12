<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

if ( ! defined( 'ABSPATH' ) ) exit; 


class Display{
    
    public $has_done_title = false;
    public $has_done_content = false;

    public function init(){ 
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'wp',   array( $this, 'addSharingFilters' ) );
        add_action( 'init', array( $this, 'registerScripts' ) );
        add_action( 'wp_print_scripts', array( $this, 'enqueueScripts' ) );
        add_shortcode( 'sharebar', array( $this, 'sharebarShortcode' ) );
        add_filter( 'rlssb_share_bar_markup' , array( $this, 'shareBarBefore' ), 5, 2 );
        add_filter( 'rlssb_share_bar_markup' , array( $this, 'styledWrapperBefore' ), 7, 2 );
        add_filter( 'rlssb_share_bar_markup' , array( $this, 'shareBarInner' ), 10, 2 );
        add_filter( 'rlssb_share_bar_markup' , array( $this, 'styledWrapperAfter' ), 13, 2 );
        add_filter( 'rlssb_share_bar_markup' , array( $this, 'shareBarAfter' ), 15, 2 );
    }
    
    /**
     * getCommon returns the common class instance
     * @since 0.1
     * @author Russell Fair
     */
    public function getCommon() {
        return $this->common;
    }
    
    /** 
     * registerScripts handles registration of our script and style assets
     * @since 0.2
     * @author Russell Fair
     */
    public function registerScripts() {
        wp_register_style( $this->common->getSlug() , plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/main.min.css', array(), $this->common->getVersion(), 'all' );
        // wa wp_register_script( $this->common->getSlug() . '-whatsapp', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/vendor/whatsapp-sharing/dist/whatsapp-button.js', array(), $this->common->getVersion() );
        wp_register_script( $this->common->getSlug() , plugin_dir_url( dirname( __FILE__ ) ) . 'assets/scripts/display.min.js', array( 'jquery' ), $this->common->getVersion());
    }
    
    /** 
     * enqueueScripts handles enqueueing of our script and style assets
     * @since 0.2
     * @author Russell Fair
     */
    public function enqueueScripts(){
        wp_enqueue_style( $this->common->getSlug() );
        wp_enqueue_script( $this->common->getSlug() );
        // wa wp_enqueue_script( $this->common->getSlug() . '-whatsapp' );
        wp_localize_script( $this->common->getSlug(), $this->common->getSlug() . '_display_settings', $this->common->getDisplaySettings() );
    }
    
    /** maybeShowSharing determines if the share bar should be shown at all
     * @since 0.1
     * @author Russell Fair
     */ 
    public function maybeShowSharing( $show = false ){
        if( is_singular() ){
            $show = in_array( get_post_type() , $this->common->getActivePostTypes() );
            if( ! $show ) //no point in continuing if not active on post type...
                return false;
        }
        
        if ( ! $this->common->getActivenetworks() )
            return false;

        return $show;        
    }
    /**
     * sharebarShortcode does the shortcode within the post content
     * @since 0.5
     * @Russell Fair
     */
    public function sharebarShortcode( $atts ){
        if( $this->maybeShowSharing() ){
            return $this->getShareBarMarkup( __FUNCTION__ );
        } 
        else {
            return;
        }
    }
    
    /** 
     * addSharingFilters iterates through the active locations and adds an action or filter to the apropriate place
     * @since 0.1
     * @author Russell Fair
     */
    public function addSharingFilters() {
        if( $this->maybeShowSharing() ){
            $locations = $this->common->getActiveLocations();
            if( is_array( $locations ) ){
                foreach( $locations as $location => $location_args ) {
                    if( isset( $location_args['filter'] ) )
                        add_filter( $location_args['filter'] , array( $this, $location_args['filter'] ), 10, 1 );
                    elseif( isset( $location_args['action'] ) )
                        add_action( $location_args['action'] , array( $this, $location_args['action'] ), 10, 1 );
                }
            }
        }
    }
    /** the_title is a filter outputting the share bar directly after the title
     * @since 0.1
     * @author Russell Fair
     */
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
    
    /** post_thumbnail_html is a filter for outputting share bar over post thumbnail
     * @since 0.1
     * @author Russell Fair
     */
    public function post_thumbnail_html( $html ){
        global $wp_query;
        if( ! in_the_loop() ){
        //    return $html; 
        }
        return $html . $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
    /** 
     * the_content is a filter for outputting share bar after the content
     * @since 0.1
     * @author Russell Fair
     */
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
    
    /** wp_print_footer_scripts is a function to display the share bar in the footer (way, way down, deeper and depper)
     * @since 0.1
     * @author Russell Fair
     */
    public function wp_print_footer_scripts(){
            echo $this->getShareBarMarkup( __FUNCTION__ );
    } 
    
    /** 
     * makeButtons generates "all" of the activated buttons, their html and inner html that contains the url
     * @since 0.1
     * @author Russell Fair
     */
    public function makeButtons( $caller = false ) {
        $active_networks = $this->common->getActivenetworks();
        $default_networks = $this->common->getDefaultNetworks();
        $custom_order = $this->common->getCustomOrder();
        if( ! $custom_order ){
            $custom_order = array_values( $active_networks );
        }
        $button_html = '<span class="rlssb-buttons-wrap">';
        foreach( $custom_order as $network ){
            if( in_array( $network, $active_networks ) )
                $button_html .= $this->makeButton( $network, $default_networks[$network], $caller ); 
        }
        
        $button_html .= '</span>';
        return $button_html;
    } 
    
    /**
     * makeButton generates the html for a single button, wrapped around the share url
     * @since 0.1
     * @author Russell Fair
     */
    public function makeButton( $network, $default_network_args, $caller = false ){
        return sprintf(' <span class="rlssb-button %s caller-%s"><a href="%s" rel="external" target="_blank" title="%s %s"><i class="fa %s"></i>%s</a></span>', esc_attr( $network ), $caller , $this->getLinkByContext( $network, $default_network_args ), _x('Share on' , $network,  $this->common->getSlug() ), $default_network_args['name'], $default_network_args['icon_base'] , $default_network_args['name'], $default_network_args['name'] );
    } 
    /**
     * getLinkByContext returns the share url for the individual networks
     * @since 0.1
     * @author Russell Fair
     */
    public function getLinkByContext( $network, $default_network_args ) {
        switch ($network){
            case 'twitter':
                return esc_url(sprintf($default_network_args['share_url'] , urlencode( sprintf( '%s - %s', get_the_title(), get_permalink() ) ) ) ); 
            case 'facebook':
            case 'googleplus':
                return esc_url( sprintf($default_network_args['share_url'] , urlencode( get_permalink() ) ) ); 
            case 'pinterest':
                return esc_url( sprintf($default_network_args['share_url'] , urlencode( get_permalink() ), ( has_post_thumbnail() ) ? urlencode( get_the_post_thumbnail_url() ) : '' , urlencode( get_the_title() ) ) ); 
            case 'linkedin':
                return esc_url( sprintf($default_network_args['share_url'] , urlencode( get_permalink() ), urlencode( get_the_title() ), urlencode( 'a description eh? ' ), urlencode( get_bloginfo('name') ) ) ); 
            case 'whatsapp':
                return sprintf($default_network_args['share_url'], urlencode( get_permalink() ) ); 
            default: 
                return '#nolink';
            break;
        } 
    }
    
    /** 
     * getShareBarMarkup is a filter for altering the share bar markup. 
     * @since 0.1
     * @author Russell Fair
     */
    public function getShareBarMarkup( $caller = false ) {
        return apply_filters( 'rlssb_share_bar_markup' , '', $caller );
    }
    
    /** 
     * shareBarBefore filter for adding (ro removing) markup from html before the bar is output.
     * @since 0.1
     * @author Russell Fair
     */
    public function shareBarBefore( $markupBefore = '', $caller ){
        $thisMarkup = sprintf( '<br /><span class="rlssb-share-bar caller-%s">', $caller );
        return $markupBefore . $thisMarkup;    
    }
    
    /**
     * styledWrapperBefore adds the style wrapper markup from html before the bar is output.
     * @since 0.3
     * @author Russell Fair
     */
    public function styledWrapperBefore( $markupBefore ){
        $styles = $this->common->getDisplaySettings();
        $thisMarkup = sprintf( '<span class="rlssb-share-bar-styled button-style-%s button-size-%s">', $styles['color_type'], $styles['size']);
        return $markupBefore . $thisMarkup;
    }
    
    
    
    /**
     * shareBarInner does the"inside" of the share bar, including the buttons themselves
     * @since 0.1
     * @author Russell Fair
     */
    public function shareBarInner( $markupBefore = '', $caller = false ){
        return $markupBefore . $this->makeButtons( $caller );
    }
    
    /** 
     * styledWrapperAfter a filter for altering the html after the share bar
     * @since 0.3
     * @author Russell Fair
     */
    public function styledWrapperAfter( $markupBefore = '', $caller ){
        $thisMarkup = sprintf( '</span><!-- .eof_%s_rlssb-->', 'styled-wrapper' );
        return  $markupBefore . $thisMarkup;    
    }
    
    /** 
     * shareBarAfter a filter for altering the html after the share bar
     * @since 0.1
     * @author Russell Fair
     */
    public function shareBarAfter( $markupBefore = '', $caller ){
        $thisMarkup = sprintf( '</span><!-- .eof_%s_rlssb-->', $caller );
        return  $markupBefore . $thisMarkup;    
    }
        
    
}
