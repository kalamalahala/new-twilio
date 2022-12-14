<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    TwilioCSV
 * @subpackage TwilioCSV/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TwilioCSV
 * @subpackage TwilioCSV/admin
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 */

// Import Twilio Functionality
require_once(plugin_dir_path(__FILE__) . '/../vendor/autoload.php');

use Twilio\Rest\Client;
use TwilioCSV\TwilioCSVRole;
use TwilioCSVMenu as Menu;
use TwilioCSVSettings as Settings;

class TwilioCSV_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Database tables from defined constants.
	 * 
	 * Contacts
	 * Recruits
	 * Conversations
	 * Messages
	 * Email Campaigns
	 * SMS Campaigns
	 * Logs
	 */
	private $contacts_table;
	private $recruits_table;
	private $conversations_table;
	private $messages_table;
	private $email_campaigns_table;
	private $sms_campaigns_table;
	private $logs_table;
	private $schedule_table;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Define database tables
		global $wpdb;
		$prefix = $wpdb->prefix;
		$this->contacts_table 			= $prefix . TWILIOCSV_CONTACTS_TABLE;
		$this->recruits_table 			= $prefix . TWILIOCSV_RECRUITS_TABLE;
		$this->conversations_table 		= $prefix . TWILIOCSV_CONVERSATIONS_TABLE;
		$this->messages_table 			= $prefix . TWILIOCSV_MESSAGES_TABLE;
		$this->email_campaigns_table 	= $prefix . TWILIOCSV_EMAIL_CAMPAIGNS_TABLE;
		$this->sms_campaigns_table 		= $prefix . TWILIOCSV_SMS_CAMPAIGNS_TABLE;
		$this->logs_table 				= $prefix . TWILIOCSV_LOGS_TABLE;
		$this->schedule_table 			= $prefix . TWILIOCSV_SCHEDULE_TABLE;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TwilioCSV_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TwilioCSV_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $pagenow;
		if ($pagenow != 'admin.php') return;
		if (isset($_GET['page']) && str_contains($_GET['page'], 'twilio-csv')) {
			$rand = rand(0, 100000);
			// bootstrap.min.css Bootstrap 4
			wp_enqueue_style('bootstrap 4 styling', plugin_dir_url(__FILE__) . '../includes/css/bootstrap.min.css', array(), $this->version, 'all');
			// datatables.min.css DataTables
			wp_enqueue_style('datatables styling', plugin_dir_url(__FILE__) . '../includes/datatables/datatables.min.css', array(), $this->version, 'all');
			// jquery.datetimepicker.min.css DateTimePicker
			wp_enqueue_style('datetimepicker styling', plugin_dir_url(__FILE__) . '../includes/css/jquery.datetimepicker.min.css', array(), $this->version, 'all');
			// font-awesome.min.css Font Awesome
			wp_enqueue_style('font awesome styling', plugin_dir_url(__FILE__) . '../includes/font-awesome/css/font-awesome.min.css', array(), $this->version, 'all');
			// plugin styles
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/twilio-csv-admin.css', array(), $rand, 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TwilioCSV_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TwilioCSV_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $pagenow;
		if ($pagenow != 'admin.php') return;
		if (isset($_GET['page']) && str_contains($_GET['page'], 'twilio-csv')) {
			// bootstrap.min.js Bootstrap 4
			wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . '../includes/js/bootstrap.bundle.js', array('jquery'), $this->version, false);
			// datatables.min.js DataTables
			wp_enqueue_script('datatables.min.js', plugin_dir_url(__FILE__) . '../includes/datatables/datatables.min.js', array('jquery'), $this->version, false);
			// plugin javascript - modals
			// wp_enqueue_script($this->plugin_name . '-modals', plugin_dir_url(__FILE__) . 'js/twilio-csv-modals.js', array('jquery'), $this->version, false);
			// plugin jquery - datetimepicker
			// wp_enqueue_script($this->plugin_name . '-datetimepicker', plugin_dir_url(__FILE__) . '../includes/js/jquery.datetimepicker.full.min.js', array('jquery'), $this->version, false);
			// plugin javascript
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/twilio-csv-admin.js', array('jquery'), $this->version, false);

			// load upload-contacts.js if on the upload contacts page
			if (str_contains($_GET['page'], 'upload')) {
				wp_enqueue_script('twilio-csv-upload', plugin_dir_url(__FILE__) . 'js/twilio-csv-upload-contacts.js', array('jquery'), $this->version, false);
			}
			// load view-contacts.js if on twilio-csv-contacts page
			if (str_contains($_GET['page'], 'twilio-csv-contacts')) {
				wp_enqueue_script('twilio-csv-contacts', plugin_dir_url(__FILE__) . 'js/twilio-csv-view-contacts.js', array('jquery'), $this->version, false);
			}
			// load view-active-contacts.js if on twilio-csv-active-contacts page
			if (str_contains($_GET['page'], 'twilio-csv-active-contacts')) {
				wp_enqueue_script('twilio-csv-active-contacts', plugin_dir_url(__FILE__) . 'js/twilio-csv-view-active-contacts.js', array('jquery'), $this->version, false);
			}
			// load twilio-csv-recruits.js if on twilio-csv-recruits page
			if (str_contains($_GET['page'], 'twilio-csv-recruits')) {
				wp_enqueue_script('twilio-csv-recruits', plugin_dir_url(__FILE__) . 'js/twilio-csv-recruits.js', array('jquery'), $this->version, false);
			}
			// // load conversation.js if on twilio-csv-conversations page
			// if (str_contains($_GET['page'], 'twilio-csv-conversations')) {
			// 	wp_enqueue_script('twilio-csv-conversation', plugin_dir_url(__FILE__) . 'js/twilio-csv-conversation.js', array('jquery'), $this->version, false);
			// }
			// load view-messages.js if on twilio-csv-view-messages page
			if (str_contains($_GET['page'], 'twilio-csv-view-messages')) {
				wp_enqueue_script('twilio-csv-view-messages', plugin_dir_url(__FILE__) . 'js/twilio-csv-view-messages.js', array('jquery'), $this->version, false);
			}
			// load programmable-messages.js if on twilio-csv-programmable-messages page
			if (str_contains($_GET['page'], 'twilio-csv-programmable-messages')) {
				wp_enqueue_script('twilio-csv-programmable-messages', plugin_dir_url(__FILE__) . 'js/twilio-csv-programmable-messages.js', array('jquery'), $this->version, false);
			}
			// load scheduled-briefings.js if on twilio-csv-scheduled-briefings page
			if (str_contains($_GET['page'], 'twilio-csv-scheduled-briefings')) {
				wp_enqueue_script('twilio-csv-scheduled-briefings', plugin_dir_url(__FILE__) . 'js/twilio-csv-scheduled-briefings.js', array('jquery'), $this->version, false);
			}

			// load pending-hires.js if on twilio-csv-pending-hires page
			if (str_contains($_GET['page'], 'twilio-csv-pending-hires')) {
				wp_enqueue_script('twilio-csv-pending-hires', plugin_dir_url(__FILE__) . 'js/twilio-csv-pending-hires.js', array('jquery'), $this->version, false);
			}

			// webpack testing
			wp_enqueue_script('main', plugin_dir_url(__FILE__) . '../dist/main.js', array('jquery'), $this->version, false);

			$all_users = TwilioCSV::get_all_twilio_csv_users();
			// foreach ($all_users as $user)

			// localize AJAX Router
			wp_localize_script($this->plugin_name, 'twilio_csv_ajax', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('twilio_csv_ajax_nonce'),
				'action' => 'twilio_csv_ajax',
				'users' => TwilioCSV::get_all_twilio_csv_users()
			));
		}
	}

	/**
	 *  Register the administration menu for this plugin into the WordPress Dashboard
	 * @since    1.0.0
	 */

	public function _admin_menu()
	{
		$plugin_name = $this->plugin_name;
		$version = $this->version;
		$menu = new Menu($plugin_name, $version);

		// Submenus: View Contacts, Upload Contacts, Recruits, Conversations, Email Campaigns, SMS Campaigns, Logs, Settings
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Active Contacts',
				'menu_title' => 'View Active Contacts',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-active-contacts',
				'function' => array($menu, 'twilio_csv_active_contacts_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'All Contacts',
				'menu_title' => 'View All Contacts',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-contacts',
				'function' => array($menu, 'twilio_csv_contacts_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Upload Contacts',
				'menu_title' => 'Upload Contacts',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-upload-contacts',
				'function' => array($menu, 'twilio_csv_upload_contacts_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Pending Hires',
				'menu_title' => 'Pending Hires',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-pending-hires',
				'function' => array($menu, 'twilio_csv_pending_hires_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Recruits',
				'menu_title' => 'Recruits',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-recruits',
				'function' => array($menu, 'twilio_csv_recruits_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Conversations',
				'menu_title' => 'Conversations',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-conversations',
				'function' => array($menu, 'twilio_csv_conversations_page')
			)
		);
		// scheduled_callbacks_page
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Scheduled Callbacks',
				'menu_title' => 'Scheduled Callbacks',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-scheduled-callbacks',
				'function' => array($menu, 'twilio_csv_scheduled_callbacks_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'All Messages',
				'menu_title' => 'All Messages',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-view-messages',
				'function' => array($menu, 'twilio_csv_view_messages_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Programmable SMS',
				'menu_title' => 'Programmable SMS',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-programmable-messages',
				'function' => array($menu, 'twilio_csv_programmable_messages_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Scheduled Briefings',
				'menu_title' => 'Scheduled Briefings',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-scheduled-briefings',
				'function' => array($menu, 'twilio_csv_scheduled_briefings_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Email Campaigns',
				'menu_title' => 'Email Campaigns',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-email-campaigns',
				'function' => array($menu, 'twilio_csv_email_campaigns_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'SMS Campaigns',
				'menu_title' => 'SMS Campaigns',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-sms-campaigns',
				'function' => array($menu, 'twilio_csv_sms_campaigns_page')
			)
		);
		// $menu->add_submenu_item(
		// 	array(
		// 		'parent_slug' => $plugin_name,
		// 		'page_title' => 'Interviews',
		// 		'menu_title' => 'Interviews',
		// 		'capability' => 'manage_options',
		// 		'menu_slug' => 'twilio-csv-interview',
		// 		'function' => array($menu, 'twilio_csv_interview_page')
		// 	)
		// 	);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Logs',
				'menu_title' => 'Logs',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-logs',
				'function' => array($menu, 'twilio_csv_logs_page')
			)
		);
		$menu->add_submenu_item(
			array(
				'parent_slug' => $plugin_name,
				'page_title' => 'Settings',
				'menu_title' => 'Settings',
				'capability' => 'manage_options',
				'menu_slug' => 'twilio-csv-settings',
				'function' => array($menu, 'twilio_csv_settings_page')
			)
		);

		// Adjust first submenu item label to 'Dashboard'
		global $submenu;
		$submenu[$plugin_name][0][0] = __('Dashboard', 'text-domain');
	}

	public function _do_settings()
	{
		$settings = new Settings($this->plugin_name, $this->version);

		/**
		 * Option Group: twilio_csv_settings
		 * Twilio Settings:
		 * 
		 * - Account SID (text) (required)
		 * - Auth Token (password) (required)
		 * - Messaging Service SID (text) (required)
		 * - Phone Number (text) (required)
		 * 
		 */

		$twilio_settings = array(
			'twilio_account_sid' => array(
				'title' => 'Account SID',
				'type' => 'text',
				'description' => 'Your Twilio Account SID',
				'required' => true
			),
			'twilio_auth_token' => array(
				'title' => 'Auth Token',
				'type' => 'password',
				'description' => 'Your Twilio Auth Token',
				'required' => true
			),
			'twilio_messaging_service_sid' => array(
				'title' => 'Messaging Service SID',
				'type' => 'text',
				'description' => 'Your Twilio Messaging Service SID',
				'required' => true
			),
			'twilio_phone_number' => array(
				'title' => 'Phone Number',
				'type' => 'text',
				'description' => 'Your Twilio Phone Number',
				'required' => true
			)
		);

		$sendgrid_settings = array(
			'sendgrid_api_key' => array(
				'title' => 'API Key',
				'type' => 'text',
				'description' => 'Your SendGrid API Key',
				'required' => true
			),
			'sendgrid_from_email' => array(
				'title' => 'From Email',
				'type' => 'email',
				'description' => 'Your SendGrid From Email',
				'required' => true
			),
			'sendgrid_from_name' => array(
				'title' => 'From Name',
				'type' => 'text',
				'description' => 'Your SendGrid From Name',
				'required' => true
			),
			'sendgrid_reply_to_email' => array(
				'title' => 'Reply To Email',
				'type' => 'email',
				'description' => 'Your SendGrid Reply To Email',
				'required' => true
			),
			'sendgrid_template_id' => array(
				'title' => 'Template ID',
				'type' => 'text',
				'description' => 'Your SendGrid Template ID',
				'required' => true
			)
		);

		// Timezone settings field
		$timezone_settings = array(
			'twilio_csv_timezone' => array(
				'title' => 'Timezone',
				'type' => 'select',
				'description' => 'Your Timezone',
				'required' => true,
			)
		);

		// Register Twilio Settings using Settings->option_group

		$settings->settings_section(
			array(
				'id' => 'twilio_settings',
				'title' => 'Twilio Settings',
				'callback' => array($settings, 'settings_section_callback'),
				'page' => 'twilio-csv-twilio-settings',
			)
		);

		// Register Sendgrid Settings using Settings->option_group

		$settings->settings_section(
			array(
				'id' => 'sendgrid_settings',
				'title' => 'SendGrid Settings',
				'callback' => array($settings, 'settings_section_callback'),
				'page' => 'twilio-csv-sendgrid-settings',
			)
		);

		// Register Timezone Settings using Settings->option_group
		$settings->settings_section(
			array(
				'id' => 'timezone_settings',
				'title' => 'Timezone Settings',
				'callback' => array($settings, 'settings_section_callback'),
				'page' => 'twilio-csv-misc-settings',
			)
		);

		// Register and create fields for Twilio Settings
		foreach ($twilio_settings as $key => $value) {
			$settings->register_settings('twilio_csv_settings', $key);

			$settings->settings_field(
				array(
					'id' => $key,
					'title' => $value['title'],
					'callback' => array($settings, 'settings_field_callback'),
					'page' => 'twilio-csv-twilio-settings',
					'section' => 'twilio_settings',
					'description' => $value['description'],
					'required' => $value['required'],
					'args' => array(
						'id' => $key,
						'title' => $value['title'],
						'option_name' => $key,
						'type' => $value['type'],
						'placeholder' => $value['title'],
						'label_for' => $key,
						'description' => $value['description'],
					)
				)
			);
		}

		// Register and create fields for Sendgrid Settings
		foreach ($sendgrid_settings as $key => $value) {
			$settings->register_settings('twilio_csv_settings', $key);

			$settings->settings_field(
				array(
					'id' => $key,
					'title' => $value['title'],
					'callback' => array($settings, 'settings_field_callback'),
					'page' => 'twilio-csv-sendgrid-settings',
					'section' => 'sendgrid_settings',
					'description' => $value['description'],
					'required' => $value['required'],
					'args' => array(
						'id' => $key,
						'title' => $value['title'],
						'option_name' => $key,
						'type' => $value['type'],
						'placeholder' => $value['title'],
						'label_for' => $key,
						'description' => $value['description'],
					)
				)
			);
		}

		// Register and create field for Timezone Settings
		foreach ($timezone_settings as $key => $value) {
			$settings->register_settings('twilio_csv_settings', $key);

			$settings->settings_field(
				array(
					'id' => $key,
					'title' => $value['title'],
					'callback' => array($settings, 'specify_timezone_setting_field'),
					'page' => 'twilio-csv-misc-settings',
					'section' => 'timezone_settings',
					'description' => $value['description'],
					'required' => $value['required'],
					'args' => array(
						'id' => $key,
						'title' => $value['title'],
						'option_name' => $key,
						'type' => $value['type'],
						'placeholder' => $value['title'],
						'label_for' => $key,
						'description' => $value['description'],
					)
				)
			);
		}
	}

	public function _ajax_router()
	{
		// Check if the nonce is valid
		$nonce = $_POST['nonce'] ?? $_GET['nonce'] ?? null;
		if (!wp_verify_nonce($nonce, 'twilio_csv_ajax_nonce')) {
			wp_send_json_error('Invalid nonce', 400);
		}

		// Switch between the different ajax actions via 'method'.
		$method = $_POST['method'] ?? $_GET['method'] ?? null;

		// Begin routing based on selected method in request
		switch ($method) {
			case 'parse_sheet':
				// wp_send_json($_FILES, 400);
				$file = TwilioCSVUploadHandler::_handle_upload($_FILES['file']);
				$parse = TwilioCSVUploadHandler::_parse_headers($file);
				unlink($file['file']);
				wp_send_json($parse, 200);
				break;
			case 'upload_contacts':
				$file = TwilioCSVUploadHandler::_handle_upload($_FILES['file']);
				$indexes = TwilioCSVUploadHandler::_parse_indexes($_POST);
				$insert = TwilioCSVUploadHandler::_handle_contacts($file, $indexes);
				unlink($file['file']);
				wp_send_json($insert, 200);
				break;
			case 'add_contact':
				$contact = TwilioCSVUploadHandler::_insert_contact($_POST);
				$this->ajax_complete($contact);
				break;
			case 'get_contacts':
				$contacts = TwilioCSV::get_contacts();
				wp_send_json($contacts, 200);
				break;
			case 'get_active_contacts':
				$contacts = TwilioCSV::get_active_contacts();
				wp_send_json($contacts, 200);
				break;
			case 'get_pending_contacts':
				$contacts = TwilioCSV::get_pending_contacts();
				wp_send_json($contacts, 200);
				break;
			case 'get_all_messages':
				$messages = TwilioCSV::get_all_messages();
				wp_send_json($messages, 200);
				break;
			case 'delete_contact':
				// check if contact is a Recruit
				$recruit = TwilioCSVRecruit::_get_recruits_by_contact_id($_POST['id']);
				if ($recruit) {
					TwilioCSVRecruit::_delete_recruit($recruit[0]->id);
				}
				$contact = new TwilioCSVContact($_POST['id']);
				$contact = $contact->_delete();
				wp_send_json($contact, 200);
				break;
			case 'delete_recruit':
				// wp_send_json($_POST, 400);
				$recruit = TwilioCSVRecruit::_delete_recruit($_POST['id']);
				if ($recruit != false) {
					wp_send_json($recruit, 200);
				} else {
					wp_send_json($recruit['error'], 400);
				}
				break;
			case 'update_disposition':
				// wp_send_json($_POST, 200);
				$disposition = TwilioCSVContact::_set_disposition($_POST['update-disposition'], $_POST['contact-id']);
				$this->ajax_complete($disposition);
				break;
			case 'send_sms':
				error_log('send_sms');
				$message = TwilioCSV::send_sms($_POST);
				$this->ajax_complete($message);
				break;
			case 'send_bulk_sms':
				$outgoing = new TwilioCSVMessage();
				$messages = $outgoing->bulk_message_scheduling($_POST);
				$process_messages = $outgoing->process_scheduled_bulk_sms($messages['messages']);
				$process_messages['skipped_contacts'] = $messages['skipped_contacts'];
				$this->ajax_complete($process_messages);
				break;
			case 'send_email':
				$handler = new TwilioCSVEmailHandler();
				$email = $handler->send();
				wp_send_json($email, 200);
				break;
			case 'get_conversation':
				$messages = new TwilioCSVMessage();
				$messages = $messages->_get_messages_by_id($_POST['contact_id']);
				wp_send_json($messages, 200);
				break;
			case 'send_final_interview':
				// wp_send_json($_POST, 200);
				$data = [
					'candidateEmail' => $_POST['candidateEmail'],
					'full-name' => $_POST['full-name'],
					'id' => $_POST['id'],
				];

				$acceptance_email = TwilioCSVEmailHandler::send_acceptance_email($data);
				$this->ajax_complete($acceptance_email);
				break;
			case 'get_recruits':
				$recruits = TwilioCSVRecruit::_get_recruits();
				wp_send_json($recruits, 200);
				break;
			case 'update_recruit':
				$recruit = new TwilioCSVRecruit($_POST['id']);
				$recruit = $recruit->_update_recruit($_POST);
				wp_send_json($recruit, 200);
				break;
			case 'get_all_scheduled_items':
				$scheduled_items = TwilioCSVSchedule::_get_schedules();
				wp_send_json($scheduled_items, 200);
				break;
			case 'get_contact_scheduled_items':
				$scheduled_items = TwilioCSVSchedule::_get_schedules_by_contact_id($_POST['id']);
				wp_send_json($scheduled_items, 200);
				break;
			case 'get_scheduled_item':
				$scheduled_item = TwilioCSVSchedule::_get_schedule($_POST['id']);
				wp_send_json($scheduled_item, 200);
				break;
			case 'get_scheduled_callbacks':
				$scheduled_item = TwilioCSVSchedule::_get_scheduled_call_backs();
				wp_send_json($scheduled_item, 200);
				break;
			case 'set_schedule_status':
				$scheduled_item = TwilioCSVSchedule::_set_schedule_status($_POST['id'], $_POST['status']);
				wp_send_json($scheduled_item, 200);
				break;
			case 'create_scheduled_item':
				$scheduled_item = TwilioCSVSchedule::_create_scheduled_item($_POST['id'], $_POST['type'], $_POST['_schedule_date']);
				$status_code = ($scheduled_item) ? 200 : 400;
				wp_send_json($scheduled_item, $status_code);
				break;
			case 'delete_scheduled_item':
				$scheduled_item = TwilioCSVSchedule::_delete_scheduled_item($_POST['id']);
				$status_code = ($scheduled_item) ? 200 : 400;
				wp_send_json($scheduled_item, $status_code);
				break;
			case 'create_programmable_message':
				$message = new TwilioCSVProgrammableMessages();
				$message = $message->create_message($_POST['_title'], $_POST['_body'], $_POST['_type']);
				$http_status = ($message != false) ? 200 : 400;
				wp_send_json($message, $http_status);
				break;
			case 'get_programmable_messages':
				$messages = new TwilioCSVProgrammableMessages();
				$messages = $messages->get_messages();
				wp_send_json($messages, 200);
				break;
			case 'get_programmable_message':
				$message = new TwilioCSVProgrammableMessages();
				$message = $message->get_message($_GET['id']);
				$http_status = ($message != false) ? 200 : 400;
				wp_send_json($message, $http_status);
				break;
			case 'update_programmable_message':
				$message = new TwilioCSVProgrammableMessages();
				$message = $message->update_message($_POST['id'], $_POST['_title'], $_POST['_body'], $_POST['_type']);
				$http_status = ($message['success'] != false) ? 200 : 400;
				wp_send_json($message, $http_status);
				break;
			case 'delete_programmable_message':
				$message = new TwilioCSVProgrammableMessages();
				$message = $message->delete_message($_POST['id']);
				$http_status = ($message['success'] != false) ? 200 : 400;
				wp_send_json($message, $http_status);
				break;
			case 'get_all_briefings':
				$briefings = TwilioCSVBriefing::get_all_briefings();
				wp_send_json($briefings, 200);
				break;
			case 'schedule_briefing':
				$response = [];
				$briefing = new TwilioCSVBriefing();
				$briefing = $briefing->create_briefing($_POST['_title'], $_POST['_weblink'], $_POST['_body'], $_POST['_scheduled']);
				$response['success'] = $briefing['success'];
				$response['message'] = $briefing['message'];
				$status_code = ($response['success'] >= 1) ? 200 : 400;
				wp_send_json($response, $status_code);
				break;
			case 'update_briefing':
				$response = [];
				$briefing = new TwilioCSVBriefing();
				$briefing = $briefing->get_briefing($_POST['_id']);
				// rename $_POST key _id to id
				$_POST['id'] = $_POST['_id'];
				unset($_POST['_id']);
				$briefing = $briefing->save($_POST);
				wp_send_json($briefing, 200);
			case 'delete_briefing':
				$response = [];
				$briefing = new TwilioCSVBriefing($_POST['briefing_id']);
				$delete = $briefing->delete();
				$status_code = ($delete) ? 200 : 400;
				wp_send_json($delete, $status_code);
				break;
			case 'submit_begin_interview_form':
				$response = [];
				$updated_disposition = TwilioCSVContact::handle_interview($_POST);
				$response['success'] = $updated_disposition['success'];
				$response['message'] = $updated_disposition['message'];
				$status_code = ($response['success'] >= 1) ? 200 : 400;
				wp_send_json($response, $status_code);
				break;
			case 'assign_contact_to_user':
				$response = TwilioCSVContact::assign_contacts_to_user($_POST['selectedUser'], $_POST['selectedRows']);
				$status_code = ($response['success'] >= 1) ? 200 : 400;
				$this->ajax_complete($response);
				break;
			case 'get_user_details':
				$user = new TwilioCSVUser($_GET['userID']);
				$get_user = $user->get_user_details();
				if ($get_user) {
					$response['success'] = true;
					$response['message'] = 'User details retrieved';
					$response['user'] = $get_user;
				} else {
					$response['success'] = false;
					$response['message'] = 'User details not retrieved';
				}
				$this->ajax_complete($response);
				break;
			case 'update_user_details':
				$user = new TwilioCSVUser($_POST['select-user']);
				$update = $user->handle_admin_form_submit($_POST);
				$this->ajax_complete($update);
				break;
			default:
				wp_send_json_error('Invalid method', 400);
				break;
		}
	}

	/**
	 * ajax_complete
	 *
	 * Required array keys:
	 * - success
	 * - message
	 * - query
	 * - errorconsole.log('key: ', key, 'value: ', value);
	 * 
	 * @param array $data
	 * @return void
	 */
	public function ajax_complete(array $data): void
	{
		// Require the following keys to be present in the data array
		$required_keys = ['success', 'message', 'query', 'error'];
		// Check if all required keys are present
		$missing_keys = array_diff($required_keys, array_keys($data));
		
		// Add empty values for missing keys
		foreach ($missing_keys as $key) {
			$data[$key] = '';
		}

		$status_code = ($data['success'] >= 1 || $data['success'] === true) ? 200 : 400;
		wp_send_json($data, $status_code);
	}

	/**
	 * REST API Router
	 */
	public function _rest_api()
	{
		$namespace = 'twiliocsv/v1';
		register_rest_route($namespace, '/sms/', array(
			'methods' => 'POST',
			'callback' => array($this, '_twilio_rest_api'),
			'permission_callback' => '__return_true',
			'args' => array(
				'context' => array(
					'default' => 'view',
				),
			),
		));

		register_rest_route($namespace, '/recruiting/accept/', array(
			'methods' => 'POST',
			'callback' => array($this, 'accept_recruitment_letter'),
			'permission_callback' => '__return_true',
			'args' => array(
				'context' => array(
					'default' => 'view',
				),
			),
		));
	}

	public static function define_callback_url(string $msid) {
		$url = get_site_url() . 'wp-json/twiliocsv/v1/sms';
		$sid = TwilioCSV::option('twilio_account_sid');
		$token = TwilioCSV::option('twilio_auth_token');

		$client = new Client($sid, $token);
		$service = $client->messaging->v1->services($msid);
		$request = $service->update(array(
			'inbound_request_url' => $url,
			'status_callback' => $url,
			'inbound_method' => 'POST'
		));

		
		return $request;

	}

	/**
	 * REST API Callback
	 */
	public function _twilio_rest_api($request)
	{
		$message = new TwilioCSVMessage;
		error_log(print_r($_POST, true));
		$status = $_POST['SmsStatus'] ?? null;
		if ($_POST['SmsStatus'] === 'received') $update = $message->_add_inbound_message($_POST);
		if ($status === "delivered" || $status === "delivered\\n") $update = $message->_update_outbound_message($_POST);
		// echo json_encode($update);
		die;
	}

	public function _update_messaging_service_sid()
	{
		$sid = TwilioCSV::option('twilio_messaging_service_sid') ?? null;
		if (!$sid) return false;
		if ($sid && TwilioCSV::option('twilio_messaging_service_sid') !== '') {
			TwilioCSV::set_inboundRequestUrl();
		}
	}

	public function accept_recruitment_letter($request)
	{
		// Get the contact ID from the query string
		if (isset($_POST['id'])) {
			$contact_id = $_POST['id'];
		} else {
			// If no contact ID is present, return an error
			return new WP_Error('no_contact_id', 'No contact ID was provided', array('status' => 400));
		}

		$new_recruit = TwilioCSVContact::_create_new_recruit_from_contact($_POST['id']);
		if (!$new_recruit) { // recruit exists, pull existing recruit
			$recruit = new TwilioCSVRecruit($_POST['id']);
			$recruit = $recruit->_update_recruit($_POST);
			} else { // recruit doesn't exist, create new recruit
				$recruit = new TwilioCSVRecruit($new_recruit);
				$recruit = $recruit->_update_recruit($_POST);
			}

		echo $recruit;
		die;
	}


	#region Debugging Functions
	/**
	 * Designs for displaying Notices
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var $message - String - The message we are displaying
	 * @var $status   - Boolean - its either true or false
	 */
	public static function admin_notice($message, $status = true)
	{
		$class =  ($status) ? 'notice notice-success' : 'notice notice-error';
		$message = __($message, 'sample-text-domain');

		printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
	}

	/**
	 * Displays Error Notices
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public static function DisplayError($message = "Aww!, there was an error.")
	{
		add_action('admin_notices', function () use ($message) {
			self::admin_notice($message, false);
		});
	}

	/**
	 * Displays Success Notices
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public static function DisplaySuccess($message = "Successful!")
	{
		add_action('admin_notices', function () use ($message) {
			self::admin_notice($message, true);
		});
	}
	#endregion Debugging Functions
}
