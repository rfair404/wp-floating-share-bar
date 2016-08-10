<?php
namespace RussellsLevitatingSocialShareButtons;

class Common{
    public $version = 0.1;
    public $slug = 'rlssb';
    
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
    
    /**
     * getActivePostTypes returns the currently active post types
     * @return mixed (array|bool) $post_types
     * @since 0.1
     * @author Russell Fair
     */
    public function getActivePostTypes() {
        return get_option( $this->getSlug() . '_active_post_types' );
    }
    
    /**
     * getActiveNetworks returns the currently active networks
     * @return mixed (array|bool) $networks
     * @since 0.1
     * @author Russell Fair
     */
    public function getActivenetworks() {
        return get_option( $this->getSlug() . '_active_networks' );
    }
    
    /**
     * getCustomORder returns the custom order
     * @return mixed (array|bool) $custom_order
     * @since 0.1
     * @author Russell Fair
     */
    public function getCustomOrder() {
        return get_option( $this->getSlug() . '_custom_order' );
    }
}
