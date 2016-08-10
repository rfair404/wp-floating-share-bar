<?php
namespace RussellsLevitatingSocialShareButtons;
use RussellsLevitatingSocialShareButtons\Common as Common;

class Admin{
    public function init() {
        require_once( dirname( __FILE__ ) . '/common.class.php' );
        $this->common = new Common;
        add_action( 'admin_init', array( $this, 'registerSettings' ), 10 );
        add_action( 'admin_menu', array( $this, 'registerMenu' ), 10 );
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
                settings_fields( $this->common->getSlug() . '_settings' );
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
        add_settings_field( $this->common->getSlug() . '_settings', 'Plugin Text Input', array( $this, 'settingsFieldCallback' ) , $this->common->getSlug() , $this->common->getSlug() );
        
        
    }
    /**
     * settingsSectionCallback handles the settings section output
     * @since 0.1
     * @author Russell Fair
     */
    public function settingsSectionCallback() {
        echo 'foo';
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
     * settingsFieldCallback is the display output for the actual fields
     * @since 0.1
     * @author Russell Fair
     */
    public function settingsFieldCallback() {
        //still nothing here
    }
}
