<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Admin{
    public function init() {
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'admin_init', array( $this, 'registerSettings' ), 10 );
        add_action( 'admin_menu', array( $this, 'registerMenu' ), 10 );
        add_filter( 'rlssb_post_types' , array( $this, 'addCPTsToPostTypes' ) );
        add_filter( 'rlssb_post_types' , array( $this, 'addPostsToPostTypes' ) );
        add_filter( 'rlssb_post_types' , array( $this, 'addPagesToPostTypes' ) );
       
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
    
    /** registerMenu registers the settings w. wp menu
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
            array( $this, 'createAdminPage' )
        );
    }
    
    /**
     * Options page callback
     * @todo refactor
     */
    public function createAdminPage()
    {
        // Set class property
        $this->options = $this->common->getSettings();
        ?>
        <div class="wrap">
            <h1><?php _e('Russell\'s Levitating Social Sharing Buttons Settings', $this->common->getSlug() ); ?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
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
        register_setting( $this->common->getSlug() , $this->common->getSlug() . '_settings' , array( $this, 'settingsValidate') );
        add_settings_section( $this->common->getSlug() . '_main' , __('Configure the social sharing buttons by setting the options below.' , $this->common->getSlug() ), array( $this, 'settingsSectionCallback') , $this->common->getSlug() );
        add_settings_field( $this->common->getSlug() . '_post_types',   'Include on Post Types',    array( $this, 'postTypeFieldCallback' ) , $this->common->getSlug() , $this->common->getSlug() . '_main' );
        add_settings_field( $this->common->getSlug() . '_networks',     'Networks to use',          array( $this, 'networksFieldCallback' ) , $this->common->getSlug() , $this->common->getSlug() . '_main' );
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
        //nothing yet
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
    
    public function addCPTsToPostTypes( $existing_types = array() ) {
        return array_merge( $existing_types, get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) );
    }
    
    public function addPoststoPostTypes( $existing_types = array() ) {
        return array_merge( $existing_types, get_post_types( array( 'name' => 'post' ), 'objects' ) );
    }
    
    public function addPagestoPostTypes( $existing_types = array() ) {
return array_merge( $existing_types, get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) );
        
    }
    
    /** 
     * postTypesFieldCallback is the display output for the actual fields
     * @since 0.1
     * @author Russell Fair
     */
    public function postTypeFieldCallback() {
        echo "<input id='plugin_text_string' name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
    } 
    
    /** 
     * postTypesFieldCallback is the display output for the actual fields
     * @since 0.1
     * @author Russell Fair
     */
    public function networksFieldCallback() {
        
    }
}
