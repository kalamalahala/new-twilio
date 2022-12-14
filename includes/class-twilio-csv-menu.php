<?php

/**
 * Class for registering and displaying the admin menu.
 * 
 * @package     TwilioCSV
 * @subpackage  TwilioCSV/menu
 * @since       1.1.75
 * @version     1.2.0
 * 
 * @author      Tyler Karle <tyler.karle@icloud.com>
 * 
 * @see         https://developer.wordpress.org/plugins/settings/custom-settings-page/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

class TwilioCSVMenu {

    protected $plugin_name;
    protected $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->add_admin_menu();
    }

    /**
     * Add field to settings page and register it.
     * 
     * @param array $args
     * $args = array(
     *      'id' => '',
     *      'title' => '',
     *      'callback' => '',
     *      'page' => '',
     *      'section' => '',
     *      'args' => array()
     * );
     * 
     */
    public static function add_field(array $args): void
    {
        $args = wp_parse_args($args, array(
            'id' => '',
            'title' => '',
            'callback' => '',
            'page' => '',
            'section' => 'default',
            'args' => array(),
        ));

        add_settings_field(
            $args['id'],
            $args['title'],
            $args['callback'],
            $args['page'],
            $args['section'],
            $args['args']
        );

        register_setting(
            $args['page'],
            $args['id']
        );
    }

    public static function add_section(array $args): void
    {
        $args = wp_parse_args($args, array(
            'id' => '',
            'title' => '',
            'callback' => '',
            'page' => '',
        ));

        add_settings_section(
            $args['id'],
            $args['title'],
            $args['callback'],
            $args['page']
        );
    }

    private function add_admin_menu(): void
    {
        $plugin_name = $this->plugin_name;
        add_menu_page(
            'TwilioCSV',
            'TwilioCSV',
            'manage_options',
            $plugin_name,
            array($this, 'twilio_csv_admin_page'),
            'dashicons-phone',
            100
        );
    }

    public function add_submenu_item($args): void
    {
        $args = wp_parse_args($args, array(
            'parent_slug' => '',
            'page_title' => '',
            'menu_title' => '',
            'capability' => '',
            'menu_slug' => '',
            'function' => '',
        ));

        foreach ($args as $key => $value) {
            $args[$key] = __( $value, 'text-domain' );
        }

        add_submenu_page(
            $args['parent_slug'],
            $args['page_title'],
            $args['menu_title'],
            $args['capability'],
            $args['menu_slug'],
            $args['function']
        );
    }

    #region Admin Page Callbacks for Menu Items

		/**
		 * Menu items:
		 * Contacts,
         * Upload Contacts,
		 * Recruits,
		 * Conversations,
		 * Email Campaigns,
		 * SMS Campaigns,
		 * Logs,
		 * Settings
		 */

    public function twilio_csv_admin_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . '../admin/partials/twilio-csv-admin-display.php';
        echo ob_get_clean();
    }

    public function twilio_csv_active_contacts_page() {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-active-contacts.php';
        echo ob_get_clean();
    }

    public function twilio_csv_contacts_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-contacts.php';
        echo ob_get_clean();
    }

    public function twilio_csv_upload_contacts_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-upload-contacts.php';
        echo ob_get_clean();
    }

    //pending hires
    public function twilio_csv_pending_hires_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-pending-hires.php';
        echo ob_get_clean();
    }

    public function twilio_csv_recruits_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-recruits.php';
        echo ob_get_clean();
    }

    public function twilio_csv_conversations_page():void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-conversations.php';
        echo ob_get_clean();
    }

    public function twilio_csv_view_messages_page():void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-view-all-replies.php';
        echo ob_get_clean();
    }

    public function twilio_csv_programmable_messages_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-programmable-messages.php';
        echo ob_get_clean();
    }

    public function twilio_csv_scheduled_briefings_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-scheduled-briefings.php';
        echo ob_get_clean();
    }

    public function twilio_csv_email_campaigns_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-email-campaigns.php';
        echo ob_get_clean();
    }

    public function twilio_csv_sms_campaigns_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-sms-campaigns.php';
        echo ob_get_clean();
    }

    public function twilio_csv_interview_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-interview.php';
        echo ob_get_clean();
    }

    public function twilio_csv_logs_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-logs.php';
        echo ob_get_clean();
    }

    public function twilio_csv_settings_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-settings.php';
        echo ob_get_clean();
    }

    public function twilio_csv_scheduled_callbacks_page(): void
    {
        ob_start();
        include_once plugin_dir_path(__FILE__) . 'layouts/twilio-csv-scheduled-callbacks.php';
        echo ob_get_clean();
    }

    
    
}