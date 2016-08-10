<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Admin{
    public function init() {
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'admin_init', array( $this, 'registerSettings' ), 10 );
        add_action( 'admin_menu', array( $this, 'registerMenu' ), 10 );

        add_filter( 'rlssb_post_types' , array( $this, 'addPostsToPostTypes' ) , 10, 1 );
        add_filter( 'rlssb_post_types' , array( $this, 'addPagesToPostTypes' ) , 10, 1 );
        add_filter( 'rlssb_post_types' , array( $this, 'addCPTsToPostTypes' ) , 15, 1 );      
    
        add_filter( 'rlssb_available_networks', array( $this, 'addBuiltinNetworks' ) , 10, 1 );
    }
    
    /**
     * getCommon gets the current common class instance
     * @since 0.1
     * @author Russell Fair
     * @return object $common
     */
    public function getCommon() {
        return $this->common;
    }
    
    /** 
     * registerMenu registers the settings w. wp menu
     * @since 0.1
     * @author Russell Fair
     */
    public function registerMenu() {
         // This page will be under "Settings"
        add_options_page(
            __('Russells Levitating Social Sharing Buttons', $this->common->getSlug() ),
            __('Sharing Buttons', $this->common->getSlug() ), 
            'manage_options', 
            'russells-levitating-social-sharing-buttons', 
            array( $this, 'adminPageDisplay' )
        );
    }
    
    /**
     * adminPageDisplay shows the form fields for the custom admin settings page
     * @since 0.1
     * @author Russell Fair
     * @todo remove the silly var dump 
     */
    public function adminPageDisplay()
    {
        echo var_dump( $this->common->getSettings() );
        ?>
        <div class="wrap">
            <h1><?php _e('Russell\'s Levitating Social Sharing Buttons Settings', $this->common->getSlug() ); ?></h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( $this->common->getSlug() );
                do_settings_sections( $this->common->getSlug() );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }
    /**
     * registerSettings registers the needed settings
     * @since 0.1
     * @author Russell Fair
     */
    public function registerSettings() {
        //register_setting( 'plugin_options', 'plugin_options', 'plugin_options_validate' );
        register_setting( $this->common->getSlug() , $this->common->getSlug(), array( $this, 'settingsValidate') );
        add_settings_section( $this->common->getSlug() . '_main' , __('Configure the social sharing buttons by setting the options below.' , $this->common->getSlug() ), array( $this, 'settingsSectionCallback') , $this->common->getSlug() );
        add_settings_field( $this->common->getSlug() . '_post_types',       'Include on Post Types',    array( $this, 'postTypeFieldCallback' ) ,       $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_networks',         'Networks to use',          array( $this, 'networksFieldCallback' ) ,       $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_custom_order',     'Set Custom Order',         array( $this, 'customOrderFieldCallback' ) ,    $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_display_settings', 'Set Button Appearance',    array( $this, 'displaySettingsFieldCallback' ) ,    $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
    }
    /**
     * settingsSectionCallback handles the settings section output
     * @since 0.1
     * @author Russell Fair
     */
    public function settingsSectionCallback() {
        echo 'what exactly is the deal with this section: ? foo';
    }
    /** 
     * settingsValidate validates the settings on save
     * @param $options the posted options
     * @since 0.1
     * @author Russell Fair
     * @return (bool) if updated
     */
    public function settingsValidate( $options ){
        $valid_options = array();
        
        if( isset( $options['post_types'] ) ) {
            $valid_options['post_types'] = array_values( $options['post_types'] );
        } 
        
        if( isset( $options['active_networks'] ) ) {
            $valid_options['active_networks'] = array_values( $options['active_networks'] );
        } 
        
        if( isset( $options['custom_order'] ) ) {
            $valid_options['custom_order'] = $options['custom_order'];
        } 
        
        if( isset( $options['display_settings'] ) ) {
            $valid_options['display_settings'] = $options['display_settings'];
        } 
        
        return $valid_options;
    }
    
    /** 
     * getRegisteredTypes gets all of the public, non-builtin post types
     * @param $options the posted options
     * @since 0.1
     * @author Russell Fair
     * @return (bool) if updated
     */
    public function getRegisteredPostTypes() {
        return apply_filters( 'rlssb_post_types', array() );
    }
    
    /** 
     * getRegisteredNetworks gets all of the available networks
     * @param $options the posted options
     * @since 0.1
     * @author Russell Fair
     * @return (bool) if updated
     */
    public function getRegisteredNetworks() {
        return apply_filters( 'rlssb_available_networks', array() );
    }
    
    /** 
     * addCPTsToPostTypes adds any registered custom post types to available array
     * @since 0.1
     * @author Russell Fair
     * @param array $expected_types incoming types to merge with
     */
    public function addCPTsToPostTypes( $existing_types = array() ) {
        return array_merge( $existing_types, get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) );
    }
    /** 
     * addPostsToPostTypes adds posts to available array
     * @since 0.1
     * @author Russell Fair
     * @param array $expected_types incoming types to merge with
     */
    public function addPoststoPostTypes( $existing_types = array() ) {
        return array_merge( $existing_types, get_post_types( array( 'name' => 'post' ), 'objects' ) );
    }
    
    /** 
     * addPagesToPostTypes adds any registered custom post types to available array
     * @since 0.1
     * @author Russell Fair
     * @param array $expected_types incoming types to merge with
     */
    public function addPagestoPostTypes( $existing_types = array() ) {
        return array_merge( $existing_types, get_post_types( array( 'name' => 'page' ), 'objects' ) );
    }
    
    /** 
     * addBuiltinNetworks adds the default networks array
     * @since 0.1
     * @author Russell Fair
     * @param (array) $networks incoming networks (if any) 
     * @return (array) merged array of defaults and incoming
     */
    public function addBuiltinNetworks( $networks = array() ){
        return array_merge( $networks, $this->common->getDefaultNetworks() );
    }
    
    /** 
     * postTypesFieldCallback is the display output for the post type checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function postTypeFieldCallback() {
        $post_types = $this->getRegisteredPostTypes();
        $current_types = $this->common->getActivePostTypes();

        foreach ( $post_types as $type => $type_args ){
            echo $this->generateCheckboxMarkup( 'post_types', $type, $type_args->labels->name, ( is_array( $current_types ) ) ? in_array( $type, $current_types ) : false  );
        }
    } 
    
    /** 
     * networksFieldCallback is the display output for the network checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function networksFieldCallback() {
        $networks = $this->getRegisteredNetworks();
        $current_networks = $this->common->getActiveNetworks();

        foreach ( $networks as $network => $network_args ){
            echo $this->generateCheckboxMarkup( 'active_networks', $network, $network_args['name'], ( is_array( $current_networks ) ) ? in_array( $network, $current_networks ) : false  );
        }
    }
    
    /**
     * displaySettingsFieldCallback outputs the custom display settings
     * @since 0.1
     * @author Russell Fair
     */
    public function displaySettingsFieldCallback() {
        
    }
    
    /** 
     * customOrderFieldCallback is the display output for the actual fields
     * @since 0.1
     * @author Russell Fair
     */
    public function customOrderFieldCallback() {
        
    }
    
    /** 
     * generateCheckboxMarkup generates the HTML output for checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function generateCheckboxMarkup( $name, $value, $label, $checked = false ) {
        return sprintf( "<input type='checkbox' name='%s[%s][%s]' value='%s'%s><label>%s</label>", esc_attr( $this->common->getSlug() ), esc_attr( $name ), esc_attr( $value ), esc_attr( $value ), checked($checked, 1, false), esc_html( $label ) );
    }
    
}
