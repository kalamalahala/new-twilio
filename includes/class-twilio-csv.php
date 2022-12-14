<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 */

require_once(plugin_dir_path(__FILE__) . '../vendor/autoload.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-contact.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-menu.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-settings.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-upload-handler.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-message.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-recruits.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-sendgrid-handler.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-schedule.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-programmable-messages.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-briefing.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-interview.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class/twilio-csv-roles.php');
require_once(plugin_dir_path(__FILE__) . '../includes/class-twilio-csv-user.php');

use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\RestException;
use TwilioCSV\TwilioCSVRole;

class TwilioCSV
{

	#region Properties, Dependencies and Hooks
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      TwilioCSV_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $roles;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('TWILIOCSV_VERSION')) {
			$this->version = TWILIOCSV_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'twilio-csv';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->hook_old_shortcodes();
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - TwilioCSV_Loader. Orchestrates the hooks of the plugin.
	 * - TwilioCSV_i18n. Defines internationalization functionality.
	 * - TwilioCSV_Admin. Defines all hooks for the admin area.
	 * - TwilioCSV_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-twilio-csv-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-twilio-csv-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-twilio-csv-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-twilio-csv-public.php';

		$this->loader = new TwilioCSV_Loader();

		$this->roles = new TwilioCSVRole();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the TwilioCSV_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new TwilioCSV_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new TwilioCSV_Admin($this->get_plugin_name(), $this->get_version());

		$action_hooks = array(
			array(									// Stylesheets
				'hook' => 'admin_enqueue_scripts',
				'component' => $plugin_admin,
				'callback' => 'enqueue_styles',
			),
			array(									// Javascript
				'hook' => 'admin_enqueue_scripts',
				'component' => $plugin_admin,
				'callback' => 'enqueue_scripts',
			),
			array(									// Register Settings
				'hook' => 'admin_init',
				'component' => $plugin_admin,
				'callback' => '_do_settings',
			),
			array(									// Create admin menu
				'hook' => 'admin_menu',
				'component' => $plugin_admin,
				'callback' => '_admin_menu',
			),
			array(									// Trigger when Messaging Service is selected
				'hook' => 'update_option_twilio_messaging_service_sid',
				'component' => $plugin_admin,
				'callback' => '_update_messaging_service_sid',
			),
			array(									// Admin AJAX Router
				'hook' => 'wp_ajax_twilio_csv_ajax',
				'component' => $plugin_admin,
				'callback' => '_ajax_router',
			),
			array(
				'hook' => 'rest_api_init',			// Twilio Webhook
				'component' => $plugin_admin,
				'callback' => '_rest_api',
			)
		);

		foreach ($action_hooks as $action_hook) {
			$this->loader->add_action($action_hook['hook'], $action_hook['component'], $action_hook['callback']);
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new TwilioCSV_Public($this->get_plugin_name(), $this->get_version());

		/**
		 * New public hooks for version 1.2.5 and forward
		 */

		$filter_hooks = [
			[
				// Show template for page /twilio-csv/
				'filter' => 'template_include',
				'component' => $plugin_public,
				'callback' => 'twilio_csv_show_template',
			]
		];

		$action_hooks = [
			[	// CSS
				'hook' => 'wp_enqueue_scripts',
				'component' => $plugin_public,
				'callback' => 'enqueue_styles',
			],
			[	// Javascript
				'hook' => 'wp_enqueue_scripts',
				'component' => $plugin_public,
				'callback' => 'enqueue_scripts',
			],
			[	// AJAX
				'hook' => 'wp_ajax_twilio_csv_ajax_public',
				'component' => $plugin_public,
				'callback' => '_ajax_router',
			],
			[	// nopriv AJAX
				'hook' => 'wp_ajax_nopriv_twilio_csv_ajax_public',
				'component' => $plugin_public,
				'callback' => '_ajax_router_nopriv',
			],
		];

		$shortcodes = [
			[
				'shortcode' => 'recruiting-letter-accept',
				'component' => $plugin_public,
				'callback' => 'recruiting_letter_accept_shortcode',
			]
		];


		// Do filters
		foreach ($filter_hooks as $filter_hook) {
			$this->loader->add_filter($filter_hook['filter'], $filter_hook['component'], $filter_hook['callback']);
		}

		// Do actions
		foreach ($action_hooks as $action_hook) {
			$this->loader->add_action($action_hook['hook'], $action_hook['component'], $action_hook['callback']);
		}

		// Do shortcodes
		if (count($shortcodes) > 0) { // remove this logic once we have shortcodes
			foreach ($shortcodes as $shortcode) {
				$this->loader->add_shortcode($shortcode['shortcode'], $shortcode['component'], $shortcode['callback']);
			}
		}
	}

	public function hook_old_shortcodes() {

		$plugin_public = new TwilioCSV_Public($this->get_plugin_name(), $this->get_version());
		/* Old hooks for version 1.2.4 and below */

		// $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		// $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		// Add Webhook Here?
		$this->loader->add_action('rest_api_init', $plugin_public, 'register_TwilioCSV_route');
		// $this->loader->add_action( 'rest_api_init', $plugin_public, 'register_twilio_action_route');

		// Embed javascript on recruiting page by ID
		// $this->loader->add_action( 'wp_head', $plugin_public, 'TwilioCSV_add_javascript');

		// Use this loader function to create embeddable shortcodes.
		// Format: shortcode name // public class object // function that calls WP add_shortcode inside public class
		$this->loader->add_shortcode('create_csv_upload_form', $plugin_public, 'TwilioCSV_register_shortcodes_create');
		$this->loader->add_shortcode('select_uploaded_csv_files', $plugin_public, 'TwilioCSV_register_shortcodes_select');
		$this->loader->add_shortcode('TwilioCSV_show_results', $plugin_public, 'TwilioCSV_register_shortcodes_send');
		$this->loader->add_shortcode('send_single_message', $plugin_public, 'TwilioCSV_register_shortcodes_send_single');
		$this->loader->add_shortcode('msg_handler', $plugin_public, 'TwilioCSV_register_shortcodes_handle');
		$this->loader->add_shortcode('update_handler', $plugin_public, 'TwilioCSV_register_gravity_view_update_handler');
		// TwilioCSV_display_upload_form
		$this->loader->add_shortcode('TwilioCSV_display_upload_form', $plugin_public, 'TwilioCSV_register_display_upload_form');
		$this->loader->add_shortcode('TwilioCSV_reports', $plugin_public, 'TwilioCSV_register_shortcodes_reports');
		// LL_shortcodes
		$this->loader->add_shortcode('ll_csv_upload_form', $plugin_public, 'll_register_shortcode_upload_form');
		$this->loader->add_shortcode('ll_display_uploaded_files', $plugin_public, 'll_register_shortcode_display_uploads');
		$this->loader->add_shortcode('ll_show_results', $plugin_public, 'll_register_shortcode_show_results');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    TwilioCSV_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
	#endregion Properties, Dependencies and Hooks

	#region Templates

	public static function navbar()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/navbar.php';
		echo ob_get_clean();
	}

	public static function navbar_public()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/navbar-public.php';
		echo ob_get_clean();
	}

	public static function modals()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/modals.php';
		echo ob_get_clean();
	}

	public static function disposition_form()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/disposition_form.php';
		echo ob_get_clean();
	}

	public static function disposition_form_inline()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/disposition_form_inline.php';
		echo ob_get_clean();
	}

	public static function begin_interview_form()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/begin_interview_form.php';
		echo ob_get_clean();
	}

	public static function final_interview_form()
	{
		ob_start();
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/final_interview_form.php';
		echo ob_get_clean();
	}

	public static function snip_merge_tags()
	{
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/merge_tag_selector.php';
		echo ob_get_clean();
	}

	public static function snip_programmable_messages_individual()
	{
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/programmable_messages_individual.php';
		echo ob_get_clean();
	}

	public static function snip_programmable_messages_bulk()
	{
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/programmable_messages_bulk.php';
		echo ob_get_clean();
	}

	public static function update_recruit_form()
	{
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/update_recruit_form.php';
		echo ob_get_clean();
	}

	public static function add_contact_modal_form()
	{
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/add_contact_modal_form.php';
		echo ob_get_clean();
	}

	public static function callback_form_inline() {
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'includes/layouts/snips/callback_form_inline.php';
		echo ob_get_clean();
	}
	#endregion Templates

	#region Contacts methods

	public static function get_contacts()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
		$current_user = wp_get_current_user();
		$user_role = $current_user->roles[0];
		switch ($user_role) {
			case 'administrator':
				$query = "SELECT * FROM $table_name";
				break;
			case 'twilio_csv_admin':
				$query = "SELECT * FROM $table_name";
				break;
			case 'twilio_csv_manager':
				$query = "SELECT * FROM $table_name WHERE _user_id = $current_user->ID";
				break;
			case 'twilio_csv_user':
				$query = "SELECT * FROM $table_name WHERE _user_id = $current_user->ID";
				break;
			default:
				return false;
				break;
			}
		$results = $wpdb->get_results($query, ARRAY_A);
		if (empty($results)) {
			return false;
		} else {
			return $results;
		}
	}

	public static function get_active_contacts()
	{
		/* Get all contacts except _disposition in list:
		*  Not Interested
		*  Not Qualified
		*  Not In Service
		*  Wrong Number
		*  Spam
		*  Duplicate
		*  Other - See Notes
		*  Do Not Call
		*  New Recruit
		*/

		global $wpdb;
		$table = TwilioCSVContact::table();
		$query = "SELECT * FROM $table WHERE _disposition NOT IN ('Not Interested', 'Not Qualified', 'Not In Service', 'Wrong Number', 'Spam', 'Duplicate', 'Other - See Notes', 'Do Not Call', 'New Recruit', 'Deleted Recruit')";
		$prepare = $wpdb->prepare($query);
		$results = $wpdb->get_results($prepare, ARRAY_A);
		if (empty($results)) {
			return false;
		} else {
			return $results;
		}
	}

	public static function get_pending_contacts()
	{
		// get all contacts with disposition Zoom Meeting Scheduled
		global $wpdb;
		$table = TwilioCSVContact::table();
		$query = "SELECT * FROM $table WHERE _disposition = 'Zoom Meeting Scheduled'";
		$prepare = $wpdb->prepare($query);
		$results = $wpdb->get_results($prepare, ARRAY_A);
		if (empty($results)) {
			return false;
		} else {
			return $results;
		}
	}
	#endregion

	#region Users methods
	#region Get users

	public static function get_all_twilio_csv_users()
	{
		$users = [];
		$all_users = get_users(array('role' => 'twilio_csv_user'));
		$all_admins = get_users(array('role' => 'twilio_csv_admin'));
		$all_managers = get_users(array('role' => 'twilio_csv_manager'));

		foreach ($all_users as $user) {
			// add property to WP_User object
			$user->full_name = $user->first_name . ' ' . $user->last_name;
			$users[] = $user;
		}
		
		foreach ($all_admins as $user) {
			$user->full_name = $user->first_name . ' ' . $user->last_name;
			$users[] = $user;
		}
		
		foreach ($all_managers as $user) {
			$user->full_name = $user->first_name . ' ' . $user->last_name;
			$users[] = $user;
		}

		return $users;
	}

	public static function get_twilio_csv_users_by_role($role)
	{
		$users = get_users(array(
			'role' => $role,
		));
		return $users;
	}

	public static function get_twilio_csv_admins()
	{
		$users = get_users(array(
			'role' => 'twilio_csv_admin',
		));
		return $users;
	}

	public static function get_twilio_csv_managers()
	{
		$users = get_users(array(
			'role' => 'twilio_csv_manager',
		));
		return $users;
	}

	public static function get_twilio_csv_users()
	{
		$users = get_users(array(
			'role' => 'twilio_csv_user',
		));
		return $users;
	}

	#endregion
	#endregion

	
	/**
	 * send_sms
	 *
	 * @param array $sms - array keys: to, from, body, contact-id
	 * @return array
	 */
	public static function send_sms($sms = []): array
	{
		error_log('send_sms()');
		$sid = self::option('twilio_account_sid');
		$token = self::option('twilio_auth_token');
		$msid = self::option('twilio_messaging_service_sid');


		$current_user_id = get_current_user_id();
        $twilio_csv_user = new TwilioCSVUser($current_user_id);
        $sending_number = $twilio_csv_user->get_sending_number();
		$schedule = (isset($sms['schedule']) && $sms['schedule'] != '') ? $sms['schedule'] : false;

		$payload = [];
		// error_log('send_sms: ' . print_r($sms, true));
		$formatted_body = TwilioCSVMessage::format_sms_body($sms);
		// error_log('formatted_body: ' . $formatted_body);
		// gather data and options
		$options = [];
		$options['messagingServiceSid'] = $msid;
		$options['body'] = $formatted_body;
		if ($schedule) {
			$options['sendAt'] = $schedule;
			$options['scheduleType'] = 'fixed';
		}
		if ($sending_number) {
			$options['from'] = $sending_number;
		}

		// set callback to rest api, /wp-json/twiliocsv/v1/sms
		$options['statusCallback'] = get_site_url() . '/wp-json/twiliocsv/v1/sms';

		$twilio = new TwilioClient($sid, $token);
		try {
			$message = $twilio->messages->create(
				$sms['to'],
				$options
			);
		} catch (\Twilio\Exceptions\RestException $e) {
			$payload = array(
				'error' => true,
				'statusCode' => $e->getStatusCode(),
				'moreInfo' => $e->getMoreInfo(),
				'details' => $e->getDetails(),
				'message' => $e->getMessage(),
				'query' => '',
				'success' => false,
			);
			// error_log(print_r($payload, true));
			return $payload;
		}

		error_log('sent message: ' . print_r($message, true));


		// save message to db
		if ($message) {
			$outbound = new TwilioCSVMessage();
			$sms['is-scheduled'] = ($schedule) ? 1 : 0;
			$sms['schedule-status'] = ($schedule) ? 'scheduled' : 'sent';

			$log = $outbound->_add_outbound_message($sms);
			// return $log;
			$payload = array(
				'error' => $log['error'],
				'statusCode' => $message->status,
				'message' => $log['message'],
				'log' => $log,
				'query' => $log['query'],
				'success' => $log['success'],
			);

			// error_log('Added outbound message to db');
			// error_log(print_r($payload, true));
		}
		return $payload;
	}

	public static function send_bulk_sms($data)
	{
		/**
		 * Object { "contact-id-list": "1,2", "contact-count": "0", 
		 * body: "Hi {{FIRSTNAME}} ", action: "twilio_csv_ajax",
		 *  nonce: "6bd0b122fb", method: "send_bulk_sms" }
		 *
		 * 	action: "twilio_csv_ajax"
		 *	body: 
		 *	"contact-count": "0"
		 *	"contact-id-list": "1,2"
		 *  "30-day-override": "0"
		 */

		// Explode the contact id list into an array
		$contact_id_list = explode(',', $data['contact-id-list']);

		// Get the contacts from the database
		$contacts = self::get_contacts();

		$output = [];
		$override = (isset($data['30-day-override']) && $data['30-day-override'] == '1') ? true : false;

		// Loop through the contacts
		foreach ($contacts as $contact) {
			// If the contact id is in the list, send the sms
			if (in_array($contact['id'], $contact_id_list)) {

				// If the contact has been messaged within 30 days, skip
				$last_message = new TwilioCSVMessage;
				$query = $last_message->_get_messages_by_id($contact['id']);
				
				if (count($query) > 0) { // if there are messages
					$last_message = $query[0]['_created'];
					$last_message = new DateTime($last_message);
					$now = new DateTime();
					$interval = $last_message->diff($now);
					$days = $interval->format('%a');
					if ($days < 30 && !$override) { // skip if no override
						$output['skipped-30-days'][] = $contact['id'];
						continue;
					}
				}

				// Send the sms
				$sms = [
					'to' => $contact['_phone'],
					'body' => $data['body'],
					'contact-id' => $contact['id'],
				];
				$formatted_body = TwilioCSVMessage::format_sms_body($sms);
				$sms['body'] = $formatted_body;
				$send = self::send_sms($sms);
				$output[] = $send;
			} else {
				continue;
			}
		}

		return $output;
	}

	public static function service_information(): array
	{
		$service = [];
		$sid = self::option('twilio_account_sid');
		$token = self::option('twilio_auth_token');
		if (is_null($sid) || is_null($token) || $sid == '' || $token == '') {
			$service['error'] = true;
			$service['message'] = 'Please enter your Twilio Account SID and Auth Token in the settings.';
			return $service;
		}

		// if no SSL certificate is installed, return false
		$https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ? true : false;
		if (!$https) {
			$service['error'] = true;
			$service['message'] = 'Please install an SSL certificate on your site to use Twilio SMS services.';
			return $service;
		}

		// $msid = self::option('twilio_messaging_service_sid');
		$twilio = new TwilioClient($sid, $token);
		try {
			$services = $twilio->messaging->v1->services->read();
		} catch (RestException $e) {
			$error = array(
				'error' => true,
				'statusCode' => $e->getStatusCode(),
				'moreInfo' => $e->getMoreInfo(),
				'details' => $e->getDetails(),
				'message' => $e->getMessage(),
			);
			error_log(print_r($error, true));
			return $error;
		}
		$output = [];
		foreach ($services as $record) {
			array_push($output, array(
				'sid' => $record->sid,
				'friendlyName' => $record->friendlyName,
				'inboundRequestUrl' => $record->inboundRequestUrl
			));
		}
		return $output;
	}

	public static function sending_numbers(string $msid): array
	{
		$sid = self::option('twilio_account_sid');
		$token = self::option('twilio_auth_token');
		$twilio = new TwilioClient($sid, $token);
		$service = $twilio->messaging->v1->services($msid);
		$numbers = $service->phoneNumbers->read();
		$output = [];

		/**
		 * example json response:
		 * 
		 * {
		 *	"meta": {
		 *		"page": 0,
		 *		"page_size": 50,
		 *		"first_page_url": "https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers?PageSize=50&Page=0",
		 *		"previous_page_url": "https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers?PageSize=50&Page=0",
		 *		"next_page_url": "https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers?PageSize=50&Page=1",
		 *		"key": "phone_numbers",
		 *		"url": "https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers?PageSize=50&Page=0"
		 *	},
		 *	"phone_numbers": [
		 *		{
		 *		"account_sid": "ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
		 *		"service_sid": "MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
		 *		"sid": "PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
		 *		"date_created": "2015-07-30T20:12:31Z",
		 *		"date_updated": "2015-07-30T20:12:33Z",
		 *		"phone_number": "+987654321",
		 *		"country_code": "US",
		 *		"capabilities": [],
		 *		"url": "https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers/PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
		 *		}
		 *	]
		 *	}
		 */
		foreach ($numbers as $number) {
			$output[] = array(
				'sid' => $number->sid,
				'phoneNumber' => $number->phoneNumber,
				'countryCode' => $number->countryCode,
				'capabilities' => $number->capabilities,
				'dateCreated' => $number->dateCreated,
				'dateUpdated' => $number->dateUpdated,
			);
		}
		return $output;
	}

	public static function set_inboundRequestUrl()
	{
		$sid = self::option('twilio_account_sid');
		$token = self::option('twilio_auth_token');
		$msid = self::option('twilio_messaging_service_sid');
		$twilio = new TwilioClient($sid, $token);
		$service = $twilio->messaging->v1->services($msid)->fetch();
		$service->update(array(
			'inboundRequestUrl' => get_site_url() . '/wp-json/twiliocsv/v1/sms'
		));

		return $service->inboundRequestUrl;
	}

	public static function get_inboundRequestUrl($sid)
	{
		$sid = self::option('twilio_account_sid');
		$token = self::option('twilio_auth_token');
		$msid = self::option('twilio_messaging_service_sid');
		$twilio = new TwilioClient($sid, $token);
		$service = $twilio->messaging->v1->services($msid)->fetch();
		return $service->inboundRequestUrl;
	}

	public static function get_all_messages()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . TWILIOCSV_MESSAGES_TABLE;
		$sql = "SELECT * FROM $table_name where _SmsStatus = 'received' ORDER BY _created DESC";
		$messages = $wpdb->get_results($sql, ARRAY_A);

		$output = [];
		/**
		 * contact_id (_contact_id)
		 * Date Created (_created)
		 * Date Updated (_updated)
		 * First Name (TwilioCSVContact::get_first_name())
		 * Last Name (TwilioCSVContact::get_last_name())
		 * Phone Number _From
		 * Email (TwilioCSVContact::get_email())
		 * Message _Body
		 */

		foreach ($messages as $message) {
			$contact = new TwilioCSVContact($message['_contact_id']);
			$first_name = $contact->get_first_name();
			$last_name = $contact->get_last_name();
			$email = $contact->get_email();
			$phone = $message['_From'];
			$body = $message['_Body'];
			$created = $message['_created'];
			$updated = $message['_updated'];
			$contact_id = $message['_contact_id'];
			$output[] = array(
				'id' => $contact_id,
				'_first_name' => $first_name,
				'_last_name' => $last_name,
				'_email' => $email,
				'_phone' => $phone,
				'_Body' => $body,
				'_created' => $created,
				'_updated' => $updated,
			);
		}

		return $output;
	}

	public static function user_name(string $mode): string
	{
		// return First and Last name of currently logged in user
		$current_user = wp_get_current_user();
		$first_name = $current_user->user_firstname;
		$last_name = $current_user->user_lastname;
		$full_name = '';

		if (empty($first_name)) {
			$full_name = $last_name;
		} elseif (empty($last_name)) {
			$full_name = $first_name;
		} else {
			//both first name and last name are present
			$full_name = "{$first_name} {$last_name}";
		}

		switch ($mode) {
			case 'first':
				return $first_name;
				break;
			case 'last':
				return $last_name;
				break;
			case 'full':
				return $full_name;
				break;
			default:
				return $full_name;
				break;
		}
	}

	public static function _user_name(int $id, string $mode = 'full_name') {
		$user = get_user_by('id', $id);
		$first_name = $user->user_firstname;
		$last_name = $user->user_lastname;
		$full_name = '';

		if (empty($first_name)) {
			$full_name = $last_name;
		} elseif (empty($last_name)) {
			$full_name = $first_name;
		} else {
			//both first name and last name are present
			$full_name = "{$first_name} {$last_name}";
		}

		switch ($mode) {
			case 'first_name':
				return $first_name;
				break;
			case 'last_name':
				return $last_name;
				break;
			case 'full_name':
				return $full_name;
				break;
			default:
				return $full_name;
				break;
		}
	}

	public static function table_exists($table_name)
	{
		global $wpdb;
		// $table_name = $wpdb->prefix . $table_name;
		return $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
	}

	public static function localized_date()
	{
		$timezone = get_option('twilio_csv_timezone');
		$timezone = $timezone['twilio_csv_timezone'] ? $timezone['twilio_csv_timezone'] : 'America/New_York';
		$timezone = new DateTimeZone($timezone);
		$date = new DateTime('now', $timezone);
		return $date->format('Y-m-d H:i:s');
	}

	public static function timezone()
	{
		$timezone = get_option('twilio_csv_timezone');
		$timezone = $timezone['twilio_csv_timezone'] ? $timezone['twilio_csv_timezone'] : 'America/New_York';
		return $timezone;
	}

	public static function version()
	{
		return TWILIOCSV_VERSION;
	}


	// TJG Helper Functions
	public static function has_profile_picture($user_id)
	{
		$profile_picture = get_user_meta($user_id, 'profile_pic_url', true);
		if (empty($profile_picture)) {
			return false;
		} else {
			return true;
		}
	}

	public static function get_profile_picture($user_id)
	{
		$profile_picture = get_user_meta($user_id, 'profile_pic_url', true);
		if (empty($profile_picture)) {
			return false;
		} else {
			return $profile_picture;
		}
	}

	public static function dump(mixed $item): void
	{
		echo '<div style="max-height: 500px; overflow-y: scroll;"><pre>';
		var_dump($item);
		echo '</pre></div>';
	}

	public static function _wpdb_debug(): array
	{
		$response = [];
		global $wpdb;
		$response['query'] = $wpdb->last_query;
		$response['error'] = $wpdb->last_error;
		$response['result'] = $wpdb->last_result;

		return $response;
	}

	public static function option(string $option_name): string|bool
	{
		$option = get_option($option_name);
		if (is_array($option)) {
			return $option[$option_name];
		} else {
			return $option;
		}
	}

	public static function is_admin(): bool
	{
		if (current_user_can('twilio_csv_admin') || current_user_can('administrator')) {
			return true;
		}
		return false;
	}
}
