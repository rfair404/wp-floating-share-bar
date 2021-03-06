<?php
namespace RussellsLevitatingSocialShareButtons;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Common{
    public $version     = '0.0.5';
    public $slug        = 'rlssb';
    
    /**
     * getVersion returns the current version
     * @return float $version
     * @since 0.1
     * @author Russell Fair
     */
    public function getVersion() {
        return $this->version;

    }
    
    /**
     * getSlug returns the current slug
     * @return string $slug
     * @since 0.1
     * @author Russell Fair
     */
    public function getSlug() {
        return $this->slug;
    }
    
    public function getSettings() {
        return get_option( $this->getSlug() );
    }
    
    public function setDefaults() {
        return update_option( $this->defaultSettings() );
    }
    
    public function defaultSettings() {
        $defaults = array(
            'active_networks'   => array_keys( $this->getDefaultNetworks() ),
            'post_types'        => array( 'post', 'page' ), 
            'active_locations'  => array( 'after_content' => array( 'filter' => 'the_content', 'name' => 'After Content' ) ),
            //'sort_order'        => array_keys( $this->getDefaultNetworks() ),
            'display_settings'  => array( 'color_type' => 'default', 'size' => 'small' ),
        );
        return $defaults;
    }
    
    /**
     * getActivePostTypes returns the currently active post types
     * @return mixed (array|bool) $post_types
     * @since 0.1
     * @author Russell Fair
     */
    public function getActivePostTypes() {
        $settings = $this->getSettings();
        return isset( $settings['post_types'] ) ? $settings['post_types'] : false ;
    }
    
    /**
     * getActiveNetworks returns the currently active networks
     * @return mixed (array|bool) $networks
     * @since 0.1
     * @author Russell Fair
     */
    public function getActivenetworks() {
        $settings = $this->getSettings();
        return isset( $settings['active_networks'] ) ? $settings['active_networks'] : false ;
    }
    
    /**
     * getCustomOrder returns the custom order
     * @return mixed (array|bool) $custom_order
     * @since 0.1
     * @author Russell Fair
     */
    public function getCustomOrder() {
        $settings = $this->getSettings();
        return ( isset( $settings['sort_order'] ) ) ? $settings['sort_order'] : false ;
    }
    
    /**
     * getDisplaySettings returns the relevant settings for displaying the buttons
     * @since 0.1
     * @author Russell Fair
     * @return mixed (bool|array) 
     */
    public function getDisplaySettings() {
        $settings = $this->getSettings();
        return isset( $settings['display_settings'] ) ? $settings['display_settings'] : array( 'size' => '16x16', 'default' => true ) ;
    }
    
    /**
     * getLocationSettings returns the relevant settings the location(s) of the buttons
     * @since 0.1
     * @author Russell Fair
     * @return (array) 
     */
    public function getActiveLocations() {
        $settings = $this->getSettings();
        return ( isset( $settings['active_locations'] ) ) ? $settings['active_locations'] : false  ;
    }
     
    /**
     * getDefaultNetworks returns the builtin networks
     * @since 0.1
     * @author Russell Fair
     * @return mixed (bool|array) 
     */
    public function getDefaultNetworks() {
         return array(
            'twitter' => array(
                'name' => __('Twitter', $this->getSlug() ),
                'icon_base' => 'fa-twitter',
                'share_url' => 'https://twitter.com/home?status=%s',
            ),
            'facebook' => array(
                'name'      => __('Facebook',   $this->getSlug() ),
                'icon_base' => 'fa-facebook',
                'share_url' => 'https://www.facebook.com/sharer/sharer.php?u=%s',
            ),
            'googleplus' => array(
                'name'      => __('Google+',    $this->getSlug() ),
                'icon_base' => 'fa-google',
                'share_url' => 'https://plus.google.com/share?url=%s',
            ),
            'pinterest' => array(
                'name'      => __('Pinterest',  $this->getSlug() ),
                'icon_base' => 'fa-pinterest', 
                'share_url' => 'https://pinterest.com/pin/create/button/?url=%s&media=%s&description=%s',
            ), 
            'linkedin' => array(
                'name'      => __('LinkedIn',   $this->getSlug() ),
                'icon_base' => 'fa-linkedin',
                'share_url' => 'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s&summary=%s&source=%s'
            ),
            'whatsapp' => array(
                'name'      => __('Whatsapp',   $this->getSlug() ),
                'icon_base' => 'fa-whatsapp',
                'share_url' => 'whatsapp://send?text=%s" data-action="share/whatsapp/share"'
            )
        );  
    }
     
    /**
     * getDefaultLocations returns the default locations
     * @since 0.1
     * @author Russell Fair
     * @return mixed (bool|array) 
     */
    public function getDefaultLocations() {
         return array( 
            'after_title'   => array( 
                'name'      => __( 'After Title', $this->getSlug() ),
                'filter'    => 'the_title' 
            ), 
            'featured_image'=> array( 
                'name'      => __( 'Featured Image', $this->getSlug() ),
                'filter'    => 'post_thumbnail_html' 
            ),  
            'after_content' => array( 
                'name'      => __( 'After Content', $this->getSlug() ),
                'filter'    => 'the_content'
            ),
            'floating_left' => array( 
                'name'      => __( 'Floating Left', $this->getSlug() ),
                'action'    => 'wp_print_footer_scripts'
            )
        );
    }
    
}
