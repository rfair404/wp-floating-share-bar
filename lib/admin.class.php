<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

if ( ! defined( 'ABSPATH' ) ) exit; 

class Admin{
    public function init() {
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'admin_init', array( $this, 'registerSettings' ), 10 );
        add_action( 'init', array( $this, 'registerScripts' ) );
        add_action( 'admin_print_scripts', array( $this, 'printScripts' ) );
        add_action( 'admin_menu', array( $this, 'registerMenu' ), 10 );
        add_filter( 'rlssb_post_types', array( $this, 'addPostsToPostTypes' ), 10, 1 );
        add_filter( 'rlssb_post_types', array( $this, 'addPagesToPostTypes' ), 10, 1 );
        add_filter( 'rlssb_post_types', array( $this, 'addCPTsToPostTypes' ), 15, 1 );      
        add_filter( 'rlssb_available_networks', array( $this, 'addBuiltinNetworks' ) , 10, 1 );
        add_filter( 'rlssb_available_sizes', array( $this, 'addBuiltinSizes' ), 10, 1 );
        add_filter( 'rlssb_available_locations', array( $this, 'addBuiltinLocations' ), 10, 1 );
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
            __('Russell\'s Levitating Social Sharing Buttons', $this->common->getSlug() ),
            __('Sharing Buttons', $this->common->getSlug() ), 
            'manage_options', 
            'russells-levitating-social-sharing-buttons', 
            array( $this, 'adminPageDisplay' )
        );
    }
    
    /**
     * registerScript registers our admin specific js
     * dependencys jquery, jquery-ui-sortable, iris
     * @since 0.4
     * @author Russell Fair
     * */
    public function registerScripts(){
        wp_register_style( $this->common->getSlug() . '-admin' , plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/main.min.css', array(), $this->common->getVersion(), 'all' );
        wp_register_script( $this->common->getSlug() . '-admin' , plugin_dir_url( dirname( __FILE__ ) ) . 'assets/scripts/admin.min.js', array( 'jquery', 'jquery-ui-sortable', 'iris' ), $this->common->getVersion());
    }
    /**
     * printScript registers our admin specific js
     * dependencys jquery, jquery-ui-sortable, iris
     * @since 0.4
     * @author Russell Fair
     * */
    public function printScripts() {
        global $pagenow, $_REQUEST;
        if( $pagenow == 'options-general.php' && $_REQUEST['page'] == 'russells-levitating-social-sharing-buttons' ) {
            wp_enqueue_style( $this->common->getSlug() . '-admin' );
            wp_enqueue_script( $this->common->getSlug() . '-admin' );
        }
    }
    
    /**
     * adminPageDisplay shows the form fields for the custom admin settings page
     * @since 0.1
     * @author Russell Fair
     * @todo remove the silly var dump 
     */
    public function adminPageDisplay()
    {
        // echo var_dump( $this->common->getSettings() );
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
        add_settings_field( $this->common->getSlug() . '_locations',        'Show in Locations',        array( $this, 'locationsFieldCallback' ) ,       $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_custom_order',     'Set Custom Order',         array( $this, 'customOrderFieldCallback' ) ,    $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_display_settings', 'Set Button Appearance',    array( $this, 'displaySettingsFieldCallback' ) ,    $this->common->getSlug() ,  $this->common->getSlug() . '_main' );
    }
    /**
     * settingsSectionCallback handles the settings section output
     * @since 0.1
     * @author Russell Fair
     */
    public function settingsSectionCallback() {
        _e( 'Use the checkboxes below to configure how and where you want the sharing buttons to appear on your site', $this->common->getSlug() );
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
        
        if( isset( $options['sort_order'] ) ) {
            $valid_options['sort_order'] = explode(',', $options['sort_order'] );
        } 
        
        if( isset( $options['active_locations'] ) ) {
            $merged_locations = array();
            $all_locations = apply_filters( 'rlssb_available_locations', array() );
            foreach( $options['active_locations'] as $location => $location_args ) {
                $merged_locations[$location] = array();
                if( isset( $all_locations[$location]['filter'] ) )
                    $merged_locations[$location]['filter'] = $all_locations[$location]['filter'];
                elseif( isset( $all_locations[$location]['action'] ) )
                    $merged_locations[$location]['action'] = $all_locations[$location]['action'];
                
            }
            $valid_options['active_locations'] = $merged_locations;
        } 
   
        if( isset( $options['display_settings'] ) ) {
            if( isset( $options['display_settings']['size'] ) ){
                $valid_options['display_settings']['size'] = $options['display_settings']['size'];
            }
            if( isset( $options['display_settings']['color_type'] ) )
                $valid_options['display_settings']['color_type'] = $options['display_settings']['color_type'];

            if( isset( $options['display_settings']['background_color'] ) ){
                $valid_options['display_settings']['background_color'] = $options['display_settings']['background_color'];
            }
            if( isset( $options['display_settings']['text_color'] ) ){
                $valid_options['display_settings']['text_color'] = $options['display_settings']['text_color'];
            }
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
     * getRegisteredSizes gets all of the available sizes
     * @param $options the posted options
     * @since 0.1
     * @author Russell Fair
     * @return (bool) if updated
     */
    public function getRegisteredSizes() {
        return apply_filters( 'rlssb_available_sizes' , array() );
    }
    
    /** 
     * getRegisteredLocations gets all of the available locations
     * @param $options the posted options
     * @since 0.1
     * @author Russell Fair
     * @return (bool) if updated
     */
    public function getRegisteredLocations() {
        return apply_filters( 'rlssb_available_locations' , array() );
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
     * addBuiltinSizes adds the default sizes array
     * @since 0.1
     * @author Russell Fair
     * @param (array) $sizes incoming sizes (if any) 
     * @return (array) merged array of defaults and incoming
     */
    public function addBuiltinSizes( $sizes = array() ) {
        return array_merge( $sizes, array( 
            'small'     => array( 'name' => __('Small', $this->common->getSlug() ),     'width' => 16 , 'height' => 16 ), 
            'medium'    => array( 'name' => __('Medium', $this->common->getSlug() ),    'width' => 32 , 'height' => 32 ),  
            'large'     => array( 'name' => __('Large', $this->common->getSlug() ),     'width' => 64 , 'height' => 64 ),
        ) );
    }
    
    /** 
     * addBuiltinSizes adds the default sizes array
     * @since 0.1
     * @author Russell Fair
     * @param (array) $sizes incoming sizes (if any) 
     * @return (array) merged array of defaults and incoming
     */
    public function addBuiltinLocations( $locations = array() ) {
        return array_merge( $locations, $this->common->getDefaultLocations() );
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
     * locationsFieldCallback is the display output for the locations checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function locationsFieldCallback() {
        $locations = $this->getRegisteredLocations();
        $current_locations = $this->common->getActiveLocations();
        foreach ( $locations as $location => $location_args ){
            echo $this->generateCheckboxMarkup( 'active_locations', $location, $location_args['name'], ( is_array( $current_locations ) ) ? isset( $current_locations[$location] ) : false  );
        }
    }
    
    /**
     * displaySettingsFieldCallback outputs the custom display settings
     * @since 0.1
     * @author Russell Fair
     * @todo refactor and add unit test coverage
     */
    public function displaySettingsFieldCallback() {
        $sizes = $this->getRegisteredSizes();
        $display_settings = $this->common->getDisplaySettings();
        //sizes
        printf( '<select name="%s[display_settings][size]">', $this->common->getSlug() );
        foreach ( $sizes as $size => $size_args ){
            echo $this->generateSelectOptionMarkup( $size, $size_args['name'],  ( isset( $display_settings['size'] ) && $display_settings['size'] == $size ) );
        }
        echo '</select>';
        printf( '<label>%s</label><br />' , __('Button Size', $this->common->getSlug() ) );
        //color type
        printf( '<select id="rlssb-color-chooser" name="%s[display_settings][color_type]">', $this->common->getSlug() );
        $types = array( 'custom' => array( 'name' => __('Custom', $this->common->getSlug() ) ) , 'default' => array( 'name' => __( 'Default' ) ) , 'inverted' => array( 'name' => __( 'Inverted' ) ) );  
        foreach ( $types as $type => $type_args ){
            echo $this->generateSelectOptionMarkup( $type, $type_args['name'],  ( isset( $display_settings['color_type'] ) && $display_settings['color_type'] == $type ) );
        }
        echo '</select>';

        printf( '<label>%s</label><br />' , __('Button Color Type', $this->common->getSlug() ) );
        
        $hidden = ( $display_settings['color_type'] == 'custom' ) ? '' : 'style="display: none;"';
        printf( '<span class="rlssb-color-pickers" %s>', $hidden );
            printf( '<input name="%s[display_settings][background_color]" type="color" value="%s" />', $this->common->getSlug(), ( isset( $display_settings['background_color'] ) ) ? $display_settings['background_color'] : '#4433dd' );
            printf( '<label>%s</label><br />' , __('Button Background Color', $this->common->getSlug() ) );
        
            printf( '<input name="%s[display_settings][text_color]" type="color" value="%s" />', $this->common->getSlug(), ( isset( $display_settings['text_color'] ) ) ? $display_settings['text_color'] : '#f0f0f0' );
            printf( '<label>%s</label><br />' , __('Button Text Color', $this->common->getSlug() ) );
        echo '</span>';
        
    }
    
    /** 
     * customOrderFieldCallback is the display output for the actual fields
     * @since 0.1
     * @author Russell Fair
     */
    public function customOrderFieldCallback() {
        $registered_networks = $this->getRegisteredNetworks();
        $current_networks = $this->common->getActiveNetworks();
        
        $custom_order = $this->common->getCustomOrder();
        $current_networks_csv = join(',', array_values( $custom_order) );
             
        printf ("<input type='hidden' id='rlssb-sort-order' name='%s[%s]' value='%s'>", $this->common->getSlug(), 'sort_order', $current_networks_csv );
        echo '<span class="rlssb-share-bar"><span class="rlssb-buttons-wrap rlssb-share-bar-styled button-size-medium"><span id="rlssb-sortable">';
        
        foreach ( $custom_order as $network ){
            $hidden = ( in_array( $network , $current_networks ) ) ? '' : 'rlssb-hidden' ;
            echo $this->generatePreviewMarkup( 'custom_order', $network, $registered_networks[$network], $hidden );
        }
        echo '</span></span></span>';
    }
    
    /** 
     * generateCheckboxMarkup generates the HTML output for checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function generateCheckboxMarkup( $name, $value, $label, $checked = false ) {
        return sprintf( "<input class='%s' type='checkbox' name='%s[%s][%s]' value='%s'%s><label>%s</label><br />", esc_attr( $name ), esc_attr( $this->common->getSlug() ), esc_attr( $name ), esc_attr( $value ), esc_attr( $value ), checked($checked, 1, false), esc_html( $label ) );
    }
    
    /** 
     * generateSelectOptionMarkup generates the HTML output for checkboxes
     * @since 0.1
     * @author Russell Fair
     */
    public function generateSelectOptionMarkup( $value, $label, $selected = false ) {
        return sprintf( "<option value='%s'%s>%s</option>", esc_attr( $value ), selected($selected, 1, false), esc_html( $label ) );
    }
    
    /** 
     * generatePreviewMarkup generates the HTML output for checkboxes
     * @since 0.4
     * @author Russell Fair
     */
    public function generatePreviewMarkup( $name, $value, $args, $hidden ) {
        return sprintf( "<span class='rlssb-button %s %s'><a id='%s' href='#'><i class='fa %s'></i>%s</a></span></input>",  esc_attr( $value ), esc_attr( $hidden ), esc_attr( $value ), esc_attr( $args['icon_base'] ),  $args['name'] );
    }
    

    
}
