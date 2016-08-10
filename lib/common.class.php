<?php
namespace RussellsLevitatingSocialShareButtons;

class Common{
    public $version = 0.1;
    public $slug = 'rlssb';
    
    public function getVersion() {
        return $this->version;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function getActivePostTypes() {
        return get_option( $this->getSlug() . '_active_post_types' );
    }
    
    public function getActivenetworks() {
        return get_option( $this->getSlug() . '_active_networks' );
    }
    
    public function getCustomOrder() {
        return get_option( $this->getSlug() . '_custom_order' );
    }
}
