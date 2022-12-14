<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    twilio_csv
 * @subpackage twilio_csv/public
 */

// require_once(plugin_dir_path(__FILE__) . '/../vendor/autoload.php');

use Twilio\Rest\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Twilio\Exceptions\TwilioException;

if (!function_exists('wp_handle_upload')) {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    twilio_csv
 * @subpackage twilio_csv/public
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 */
class TwilioCSV_Public
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in twilio_csv_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The twilio_csv_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$endpoint = $this->registered_page();
		if ($endpoint === get_the_ID()) {
			$rand = rand(0, 1000000);
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/twilio-csv-public.css', array(), $rand, 'all');
			
			// bootstrap.min.css
			// wp_enqueue_style('bootstrap.min.css', plugin_dir_url(__FILE__) . '../includes/css/bootstrap.min.css', array(), $rand, 'all');
			
			// datatables.min.css
			wp_enqueue_style('datatables.min.css', plugin_dir_url(__FILE__) . '../includes/datatables/datatables.min.css', array(), $rand, 'all');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in twilio_csv_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The twilio_csv_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$endpoint = $this->registered_page();
		if ($endpoint === get_the_ID()) {

			// generate random number to append to script name to prevent caching
			$rand = rand(0, 1000000);
			
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/twilio-csv-public.js', array('jquery'), (string)$rand, false);
			// twilio_csv_ajax object
			wp_localize_script($this->plugin_name, 'twilio_csv_ajax', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('twilio_csv_ajax_nonce'),
				'action' => 'twilio_csv_ajax_public'
			));
			wp_enqueue_script($this->plugin_name . '-main', plugin_dir_url(__FILE__) . '../dist/main.js', array('jquery'), (string)$rand, true);
			wp_localize_script($this->plugin_name . '-main', 'twilio_csv_ajax', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('twilio_csv_ajax_nonce'),
				'action' => 'twilio_csv_ajax_public',
				'current_user_role' => $this->get_current_user_role(),
				'current_user_id' => get_current_user_id(),
				'users' => $this->get_users(),
			));
			// datatables.min.js
			wp_enqueue_script('datatables.min.js', plugin_dir_url(__FILE__) . '../includes/datatables/datatables.min.js', array('jquery'), (string)$rand, false);
		}
	}

	/**
	 * twilio_csv_endpoint
	 * 
	 * Prevents loading of scripts and stylesheets unless on /recruiting/ page
	 *
	 * @return boolean
	 */
	public function twilio_csv_endpoint(): bool
	{
		$current_page = get_page_link();
		$endpoint = site_url() . '/recruiting';
		
		return str_contains($current_page, $endpoint);
	}

	public function registered_page(): int|bool
	{
		$page = TwilioCSVSettings::_db_get_option('page');
		
		return ((int)$page > 0) ? $page : false;
	}
		
	public static function get_footer()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/footer.php';
			$footer = ob_get_contents();
			ob_end_clean();
			return $footer;
		}
	}

	public function get_users()
	{
		$users = get_users();
		$users_array = array();
		foreach ($users as $user) {
			$full_name = (isset($user->first_name) && isset($user->last_name)) ? $user->first_name . ' ' . $user->last_name : $user->display_name;
			$role = $this->get_user_role($user->ID);
			if ($role !== 'administrator' && !str_contains($role, 'twilio')) {
				continue;
			}
			$users_array[] = array(
				'id' => $user->ID,
				'name' => $user->display_name,
				'full_name' => $full_name,
				'role' => $role,
			);
		}
		return $users_array;
	}

	public function get_user_role($id)
	{
		$user = new WP_User($id);
		if (!empty($user->roles) && is_array($user->roles)) {
			return $user->roles[0];
		}
	}

	public function get_current_user_role()
	{
		if (is_user_logged_in()) {
			$user = wp_get_current_user();
			$role = (array) $user->roles;
			return $role[0];
		} else {
			return false;
		}
	}

	public static function debug_pane() {
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/debug-pane.php';
			$debug_pane = ob_get_contents();
			ob_end_clean();
			return $debug_pane;
		}
	}

	public static function upload_contacts_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/upload-contacts-pane.php';
			$upload_contacts_pane = ob_get_contents();
			ob_end_clean();
			return $upload_contacts_pane;
		}
	}

	public static function conversation_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/conversation-pane.php';
			$conversation_pane = ob_get_contents();
			ob_end_clean();
			return $conversation_pane;
		}
	}

	public static function datatables_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/datatables-pane.php';
			$datatables_pane = ob_get_contents();
			ob_end_clean();
			return $datatables_pane;
		}
	}

	public static function profile_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/profile-pane.php';
			$profile_pane = ob_get_contents();
			ob_end_clean();
			return $profile_pane;
		}
	}

	public static function settings_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/settings-pane.php';
			$settings_pane = ob_get_contents();
			ob_end_clean();
			return $settings_pane;
		}
	}

	public static function pending_hires_pane()
	{
		// return if role is not Twilio CSV Admin
		if (!TwilioCSV::is_admin()) {
			return;
		}
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/pending-hires-pane.php';
			$pending_hires_pane = ob_get_contents();
			ob_end_clean();
			return $pending_hires_pane;
		}
	}

	public static function recruits_pane()
	{
		// return if role is not Twilio CSV Admin
		if (!TwilioCSV::is_admin()) {
			return;
		}
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/recruits-pane.php';
			$recruits_pane = ob_get_contents();
			ob_end_clean();
			return $recruits_pane;
		}
	}

	public static function programmable_messages_pane()
	{
		// return if role is not Twilio CSV Admin
		if (!TwilioCSV::is_admin()) {
			return;
		}
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/programmable-messages-pane.php';
			$programmable_messages_pane = ob_get_contents();
			ob_end_clean();
			return $programmable_messages_pane;
		}
	}

	public static function scheduled_briefings_pane()
	{
		// return if role is not Twilio CSV Admin
		if (!TwilioCSV::is_admin()) {
			return;
		}
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/scheduled-briefings-pane.php';
			$scheduled_briefings_pane = ob_get_contents();
			ob_end_clean();
			return $scheduled_briefings_pane;
		}
	}

	public static function scheduled_callbacks_pane()
	{
		// Do not load footer unless inside /recruiting/ page or any
		// child of /recruiting/
		if (strpos($_SERVER['REQUEST_URI'], '/recruiting/') !== false) {
			// get footer
			ob_start();
			require_once plugin_dir_path(__FILE__) . 'template/components/scheduled-callbacks-pane.php';
			$scheduled_callbacks_pane = ob_get_contents();
			ob_end_clean();
			return $scheduled_callbacks_pane;
		}
	}

	public function _ajax_router()
	{
		// Check nonce
		check_ajax_referer('twilio_csv_ajax_nonce', 'nonce');
		$pass_to_admin = new TwilioCSV_Admin($this->plugin_name, $this->version);
		$pass_to_admin->_ajax_router();
		die;

		// GET or POST?
		// $method = $_SERVER['REQUEST_METHOD'];

		// Get 'method' from $_REQUEST
		$method = $_REQUEST['method'];

		// only allow methods that are in the $allowed_methods array
		$allowed_methods = array(
			'get_contacts',
			'get_contact',
			'get_programmable_messages',
			'send_sms',
			'send_bulk_sms',
			'update_disposition',
			'send_final_interview'
		);

		// Check if method is set
		if (isset($method) && in_array($method, $allowed_methods)) {
			// Check if method is valid
			if (method_exists($this, $method)) {
				// Call the method
				$this->{$method}();
			} else {
				// Method does not exist
				self::_ajax_response('Method does not exist', 400);
			}
		} else {
			// Method not set
			self::_ajax_response('No Method in AJAX Call', 400);
		}

		// Exit
		self::_ajax_response('Method not found', 404);
	}

	public function get_contacts()
	{
		// Get the data
		$data = TwilioCSV::get_contacts();

		// Return the data
		self::_ajax_response($data, 200);
	}

	public function send_sms()
	{
		// pass $_POST to TwilioCSV::send_sms()
		$data = TwilioCSV::send_sms($_POST);
		self::_ajax_response($data, 200);
	}

	public function send_bulk_sms()
	{
		// pass $_POST to TwilioCSV::send_bulk_sms()
		$data = TwilioCSV::send_bulk_sms($_POST);
		self::_ajax_response($data, 200);
	}

	public function update_disposition()
	{
		$disposition = TwilioCSVContact::_set_disposition($_POST['update-disposition'], $_POST['contact-id']);
		if ($disposition) {
			self::_ajax_response($disposition, 200);
		} else {
			self::_ajax_response('Unable to update disposition', 400);
		}
	}

	public function send_final_interview()
	{
		$new_recruit = TwilioCSVContact::_create_new_recruit_from_contact($_POST['id']);
		$payload['results']['create'] = $new_recruit;

		if (!$new_recruit) { // recruit exists, pull existing recruit
			$recruit = new TwilioCSVRecruit($_POST['id']);
			$payload['results']['existing'] = 'Recruit already exists';

			// update recruit
			$recruit = $recruit->_update_recruit($_POST);
		} else { // recruit doesn't exist, create new recruit
			$recruit = new TwilioCSVRecruit($new_recruit);
			$payload['results']['new'] = 'New recruit created';

			// update recruit
			$recruit = $recruit->_update_recruit($_POST);
		}

		$payload['recruit'] = $recruit;
		$payload['questions'] = $_POST['questionAnswerArray'];

		self::_ajax_response($payload, 200);
	}

	public function create_scheduled_item()
	{
		$scheduled_item = TwilioCSVSchedule::_create_scheduled_item($_POST['id'], $_POST['type'], $_POST['_schedule_date']);
		$status_code = ($scheduled_item) ? 200 : 400;
		self::_ajax_response($scheduled_item, $status_code);
	}

	public function create_programmable_message()
	{
		$message = new TwilioCSVProgrammableMessages();
		$message = $message->create_message($_POST['_title'], $_POST['_body'], $_POST['_type']);
		$http_status = ($message != false) ? 200 : 400;
		self::_ajax_response($message, $http_status);
	}

	public function get_programmable_message()
	{
		$message = new TwilioCSVProgrammableMessages();
		$message = $message->get_message($_GET['id']);
		$http_status = ($message != false) ? 200 : 400;
		self::_ajax_response($message, $http_status);
	}

	public function update_programmable_message()
	{
		$message = new TwilioCSVProgrammableMessages();
		$message = $message->update_message($_POST['id'], $_POST['_title'], $_POST['_body'], $_POST['_type']);
		$http_status = ($message != false) ? 200 : 400;
		self::_ajax_response($message, $http_status);
	}

	public function recruiting_letter_accept_shortcode()
	{
		// check if $_GET['agreement'] is set
		// if not, do nothing
		if (!isset($_GET['agreement'])) {
			return;
		} else {
			// if so, set $contact_agreement to $_GET['agreement']
			$contact_id = $_GET['agreement'];
		}


		$wp_json_url = get_site_url() . '/wp-json/twiliocsv/v1/recruiting/accept/';
		// send POST to TwilioCSV plugin's REST API
		wp_remote_post(
			$wp_json_url,
			array(
				'method' => 'POST',
				'body' => array(
					'id' => $contact_id,
				),
			)
		);

		return;
	}



	/**
	 * Include template for twilio_csv_show_template add_filter
	 * 
	 * @since 1.0.0
	 */
	public function twilio_csv_show_template($template)
	{

		// page url is get_site_url() . '/recruiting/' exclusively with no children or parents
		$endpoint = get_site_url() . '/recruiting/';
		if (get_page_link() !== $endpoint) {
			return $template;
		}
		if (get_page_link() === $endpoint) {
			// If user is not logged in, redirect to login page
			// if (!is_user_logged_in()) {
			// 	// Redirect to home page
			// 	wp_redirect(home_url());
			// 	exit;
			// }
			$template = plugin_dir_path(__FILE__) . 'template/twilio-csv-public-display.php';
		}
		return $template;
	}

	//commented line

	/**
	 * _ajax_response
	 * 
	 * Send ajax response to the WP handler with error or success codes
	 *
	 * @param mixed $content
	 * @param int $status_code
	 * @return void
	 */
	public static function _ajax_response($content, $status_code)
	{
		// Set status code
		http_response_code($status_code);

		// Send response
		wp_send_json($content, $status_code);
	}

	/**
	 * Original Plugin Functions
	 */
	#region Original Twilio CSV Functionality

	// Return clean formatted cell phone number
	function strip_phone_extras($strip_number)
	{
		$remove_items = array('-', '(', ')', '+', ' ');
		$stripped_number = str_replace($remove_items, '', $strip_number);
		return $stripped_number;
	}

	function process_pending_messages($contact_data, $num_entries, $file_data, $mode = 'recruiting')
	{
		if (!$contact_data) {
			return false;
		}

		$return_html = '<ul>';
		$new_contacts = 0;
		$contact_decoded = json_decode($contact_data);


		global $wpdb;
		$csv_table = ($mode == 'recruiting') ? $wpdb->prefix . 'twilio_csv_entries' : $wpdb->prefix . 'twilio_csv_entries_ll';
		$contact_table = ($mode == 'recruiting') ? $wpdb->prefix . 'twilio_csv_contacts' : $wpdb->prefix . 'twilio_csv_contacts_ll';

		$csv_data = array(
			'id' => '',
			'date' => $file_data['date'],
			'contact_data' => $contact_data,
			'num_entries' => $num_entries,
			'send_count' => 0,
			'success_count' => 0,
			'fail_count' => 0,
			'file_name' => $file_data['file_name'],
			'file_type' => $file_data['type']
		);

		$insert_csv = $wpdb->insert($csv_table, $csv_data, null);
		$query_status = ($insert_csv) ? true : false;
		$existing_ids = array();

		try {
			$get_ids = $wpdb->get_results('SELECT * FROM ' . $contact_table);
		} catch (Exception $error) {
			echo $error . '<br>Unable to get results';
			die();
		}

		foreach ($get_ids as $entry) {
			array_push($existing_ids, $entry->unique_id);
		}

		// Display contents of uploaded file to be processed

		if ($file_data['type'] == 'office') {
			$uploaded_file_type = 'RMS';
		} else if ($file_data['type'] == 'record') {
			$uploaded_file_type = 'RMS2';
		} else if ($file_data['type'] == 'ormond') {
			$uploaded_file_type = 'ORMOND';
		} else {
			print 'Error';
			die;
		}

		// echo '<h2>Uploaded File Type: ' . $uploaded_file_type . '</h2>';

		foreach ($contact_decoded as $contact) {
			// Handle First name / Last Name formatting
			if ($uploaded_file_type === 'RMS') {
				if (is_null($contact->CellPhone)) {
					error_log('No Cell Phone Number in array, skipping creating contact');
					$return_html .= '<li>No Cell Phone Number in array, skipping creating contact</li>';
					continue;
				}
				$full_name = $contact->{'First Name'} . $contact->{'Last Name'} . $contact->CellPhone;
				$unique_id = hash('sha256', $full_name);
				$contact_entry = array(
					'id' => '',
					'first_name' => $contact->{'First Name'} ?? '',
					'last_name' => $contact->{'Last Name'} ?? '',
					'phone_number' => $contact->CellPhone,
					'email' => $contact->EmailAddress,
					'unique_id' => $unique_id
				);
			} else if ($uploaded_file_type === 'RMS2') {
				if (is_null($contact->Telephone)) {
					error_log('No Cell Phone Number in array, skipping creating contact');
					$return_html .= '<li>No Cell Phone Number in array, skipping creating contact</li>';
					continue;
				}
				$name_salt = $contact->Name . $contact->Telephone;
				$unique_id = hash('sha256', $name_salt);
				$first_and_last = explode(' ', $contact->Name);
				$first_name = $first_and_last[0];

				// collect remaining names into $surnames
				$surnames = '';
				foreach ($first_and_last as $key => $value) {
					if ($key > 0) {
						$surnames .= $value . ' ';
					}
				}

				$contact_entry = array(
					'id' => '',
					'first_name' => $first_name,
					'last_name' => $surnames,
					'phone_number' => $contact->Telephone,
					'email' => $contact->Email,
					'unique_id' => $unique_id
				);
			} else if ($uploaded_file_type == 'ORMOND') {
				if (is_null($contact->{'Phone Number'})) {
					error_log('No Cell Phone Number in array, skipping creating contact');
					$return_html .= '<li>No Cell Phone Number in array, skipping creating contact</li>';
					continue;
				}
				$name_salt = $contact->{'First Name'} . $contact->{'Last Name'} . $contact->{'Phone Number'};
				// remove special characters from phone number
				$phone_number = preg_replace('/[^0-9]/', '', $contact->{'Phone Number'});
				$unique_id = hash('sha256', $name_salt);
				$contact_entry = array(
					'id' => '',
					'first_name' => $contact->{'First Name'} ?? '',
					'last_name' => $contact->{'Last Name'} ?? '',
					'phone_number' => $phone_number,
					'email' => $contact->{'Email Address'} ?? '',
					'unique_id' => $unique_id
				);
			} else {
				error_log('Data format not recognized, contact creation skipped');
				$return_html .= '<li>Data format not recognized, contact creation skipped</li>';
				continue;
			}

			// echo '<pre>';
			// print_r($contact_entry);
			// echo '</pre>';
			// die;

			if (in_array($unique_id, $existing_ids)) {
				$return_html .= '<li>' . $contact_entry['first_name'] . ' ' . $contact_entry['last_name'] . ' skipped creating <em>Caller ID</em> , contact exists.</li>';
			} else {
				try {
					$add_contact = $wpdb->insert($contact_table, $contact_entry, null);
					$return_html .= '<li>' . $contact_entry['first_name'] . ' ' . $contact_entry['last_name'] . ' added to contact list</li>';
					$new_contacts++;
				} catch (Exception $error) {
					error_log($error . '<br>Unable to add contact to database');
				}
			}
		}



		$return_html .= '<li>Processing complete.</li></ul>';


		if ($query_status && $new_contacts > 0) {
			return $return_html;
		} else if ($query_status) {
			return $return_html . '<p>.XLSX File added to database, no contacts created.</p>';
		} else {
			return 'Submission failure.';
		}
		return false;
	}


	public function TwilioCSV_public_shortcodes()
	{

		function print_some_stuff($atts)
		{
			$atts = shortcode_atts(array(
				'content' => 'blank or not really'
			), $atts, 'print_some_stuff');

			$content = (isset($atts['content'])) ? $atts['content'] : 'but actually blank or something idk';
			return $content;
		}
	}


	// this is now the shortcode function registered in the public class
	// this is the HTML Layout for the form since it doesn't like to be included, although script tags could be used as require/include()
	public function create_csv_upload_form($atts)
	{
		// init settings
		$atts = shortcode_atts(array(
			'pagination' => 10
		), $atts, 'create_csv_upload_form');
		$list_csv_contents = '';
		$allowable_headers = array('Office', 'Record Type', 'First Name');

		// begin parse if file exists
		if (isset($_FILES['csv-upload'])) {

			// Check file extension and abort if not xlsx
			$extension = ucfirst(pathinfo($_FILES['csv-upload']['name'], PATHINFO_EXTENSION));
			if ($extension !== 'Xlsx') {
				return 'File not in .xlsx format.';
			}

			// save uploaded file and return array
			$wp_uploaded_file = wp_handle_upload($_FILES['csv-upload'], array('test_form' => FALSE));

			try {
				$file_type = IOFactory::identify($wp_uploaded_file['file']) ?? '';
			} catch (Exception $identify_error) {
				echo 'Identify Error: ' . $identify_error;
				die;
			}

			try {
				$reader = IOFactory::createReader($file_type);
			} catch (Exception $read_error) {
				echo 'Reader Error: ' . $read_error;
				die;
			}

			try {
				$parsed_file = $reader->load($wp_uploaded_file['file']);
			} catch (Exception $parse_error) {
				echo 'Parse Error: ' . $parse_error;
				die;
			}

			try {
				$file_info = $reader->listWorksheetInfo($wp_uploaded_file['file']);
			} catch (Exception $parse_error) {
				echo 'File Info failed: ' . $parse_error;
				die;
			}

			if ($parsed_file) {
				$header_values = $json_rows = [];
				$phone_numbers_listed = array();
				$parsed_rows = 0;
				$skipped_rows = 0;
				$file_type = '';

				$upload_array = $parsed_file->getActiveSheet()->toArray();
				$first_cell = $upload_array[0][0];

				if (!in_array($first_cell, $allowable_headers)) {
					return '<div class="alert alert-danger">Unrecognized headers. Contact admin.</div>';
				}

				if ($first_cell == 'Office') {
					$file_type = 'office';
					foreach ($upload_array as $row => $cell) {
						if ($row === 0) {
							$header_values = $cell;
							continue;
						}

						// Hard Code RMS XLSX Files for now till new form is created

						for ($i = 13; $i >= 16; $i++) {
							if (!empty($cell[$i])) {
								$this->strip_phone_extras($cell[$i]);
							}
							if ($cell[$i][0] == '1' && strlen($cell[$i]) == '10') {
								$cell[$i] = substr($cell[$i], 1);
							}
						}

						if (empty($cell[14])) {
							$cell[14] = $cell[16] ?? $cell[13] ?? $cell[15] ?? '';
						}

						$listed_cell_phone_number = $cell[16] ?? $cell[14] ?? $cell[13] ?? $cell[15] ?? '';

						if (!in_array($listed_cell_phone_number, $phone_numbers_listed)) {
							array_push($phone_numbers_listed, $listed_cell_phone_number);
							$json_rows[] = array_combine($header_values, $cell);
							$parsed_rows++;
						} else {
							// Cell phone already exists in temporary array, skip this one
							$skipped_rows++;
							continue;
						}
					}
				} else if ($first_cell == 'Record Type') {
					$file_type = 'record';
					foreach ($upload_array as $row => $cell) {
						if ($row === 0) {
							$header_values = $cell;
							continue;
						}

						$listed_cell_phone_number = $cell[5] ?? '';
						// Regex to remove all special characters from cell[1]
						// $cell[1] = preg_replace('/(?:[^a-z0-9 ]|(?<=[\'\"])s)/', '', $cell[1]);

						// Remove all special characters from Name field
						$cell[1] = preg_replace('/[^A-Za-z0-9\-\s]/', ' ', $cell[1]);
						$cell[1] = str_replace(array('  ', '   '), ' ', $cell[1]);

						if (!in_array($listed_cell_phone_number, $phone_numbers_listed) && !empty($listed_cell_phone_number)) {
							array_push($phone_numbers_listed, $listed_cell_phone_number);
							$json_rows[] = array_combine($header_values, $cell);
							$parsed_rows++;
						} else {
							// Cell phone already exists in temporary array, skip this one
							$skipped_rows++;
							continue;
						}
					}
				} else if ($first_cell == 'First Name') {
					$file_type = 'ormond';
					foreach ($upload_array as $row => $cell) {
						if ($row === 0) {
							$header_values = $cell;
							continue;
						}

						$listed_cell_phone_number = $cell[9] ?? '';
						// remove special characters from phone number field
						$cell[9] = preg_replace('/[^0-9]/', '', $listed_cell_phone_number);


						// Remove all special characters from Name field
						// $cell[1] = preg_replace('/[^A-Za-z0-9\-\s]/', ' ', $cell[1]);
						// $cell[1] = str_replace(array('  ', '   '), ' ', $cell[1]);

						if (!in_array($listed_cell_phone_number, $phone_numbers_listed) && !empty($listed_cell_phone_number)) {
							array_push($phone_numbers_listed, $listed_cell_phone_number);
							$json_rows[] = array_combine($header_values, $cell);
							$parsed_rows++;
						} else {
							// Cell phone already exists in temporary array, skip this one
							$skipped_rows++;
							continue;
						}
					}
				} else {
					$list_csv_contents .= '<div class="alert alert-danger">Unrecognized headers. Contact admin.</div>';
				}


				$trim_rows = count($json_rows);

				$file_data = array();
				$file_data['file_name'] = $_FILES['csv-upload']['tmp_name'];
				$file_data['rows'] = $trim_rows;
				$file_data['date'] = date('g:i:s A m/d/Y', strtotime('now -4 hours'));
				$file_data['type'] = $file_type;

				// echo '<pre>';
				// echo 'Parsed Rows: ' . $parsed_rows;
				// echo '<br>';
				// echo 'Skipped Rows: ' . $skipped_rows;
				// echo '<br>';
				// echo 'Total Rows: ' . $trim_rows;
				// echo '<br>';
				// echo 'File Name: ' . $file_data['file_name'];
				// echo '<br>';
				// echo 'Date: ' . $file_data['date'];
				// echo '<br>';
				// print_r($json_rows);
				// echo '</pre>';
				// die;

				// attempt to add CSV to database
				if (!empty($json_rows) && $_POST['confirm-upload'] == 'confirm') {
					try {
						$json_data = json_encode($json_rows);
						$file_to_wpdb = $this->process_pending_messages($json_data, $trim_rows, $file_data);
						$list_csv_contents .= ($file_to_wpdb) ? '<div class="alert-success">' . $file_to_wpdb . '</div>' : '';
					} catch (Exception $e) {
						echo 'Error: ' . $e;
					}
				}

				foreach ($file_info as $worksheet) {
					$cols = $worksheet['totalColumns'];
					$rows = $worksheet['totalRows'] - 1;
				}

				$list_csv_contents .= '<div class="file-contents"><h4>Contents of File</h4>';
				$list_csv_contents .= '<p>' . $rows . ' rows in the uploaded .xlsx file. ';
				$list_csv_contents .= 'Campaign created with ' . $trim_rows . ' contacts.</p></div>';
				$list_csv_contents .= ($skipped_rows > 0) ? '<div class="alert-warning">' . $skipped_rows . ' entries had matching phone numbers and were not included in the upload.</div>' : '';
			} else {
				$list_csv_contents .= 'Parse error.';
			}
		}

		$upload_form = '<div class="twilio-csv-form-container">
        <form
        name="twilio-csv-upload-form"
        id="twilio-csv-upload-form"
        action=""
        method="post"
        enctype="multipart/form-data"
        >
        <div class="upload-section">
        	<label for="csv-upload">Choose RMS file (.xls, .xlsx)
        	<input
			type="file"
			id="csv-upload"
			name="csv-upload"
			accept=".xlsx"
			class="upload-file"
        	/>
			</label>
			<p id="file-name" class="file-name"></p>
		</div>
        ' . ((!empty($list_csv_contents)) ? '<div class="list-csv-contents">' . $list_csv_contents . '</div>' : '') .
			'<div class="confirm-upload">
			<label for="confirm-upload">
			<input type="checkbox" value="confirm" name="confirm-upload" checked>Add file to database</label>
		</div>
		<div class="submit-contacts-to-twilio">
          <input type="submit" value="Upload Contact List" name="csv-submit" id="csv-submit" />
        </div>

      </form>
    </div>';

		return $upload_form;
	}

	public function select_uploaded_csv_files($atts)
	{
		// require_once(__DIR__ . '/js/twilio-csv-extra.js');
		// sets atts and initial array of options to ten and zero
		$atts = shortcode_atts(array(
			'pagination' => 10
		), $atts, 'select_uploaded_csv_files');
		$option_group = '';

		// go get entries from database
		global $wpdb;
		$csv_table = $wpdb->prefix . 'twilio_csv_entries';
		$table_contents = $wpdb->get_results('SELECT * FROM ' . $csv_table . ' ORDER BY id DESC;');

		// loop table_contents into option group
		$entry_array = array();
		foreach ($table_contents as $entry) {
			array_push($entry_array, json_decode($entry->contact_data));
			$option_group .= '<option value="' . $entry->id . '">' . $entry->date . ' - ' . $entry->num_entries . ' Entries</option>';
		}


		// print('<pre>');
		// print_r($entry_array);
		// print('</pre>');

		$embedded_page = get_page_link();

		// form HTML with looped option group
		$selector_form = '<div class="twilio-csv-viewer">
									<form
									name="twilio-csv-viewer"
									id="twilio-csv-viewer"
									action="' . $embedded_page . '?mode=send"
									method="post"
									enctype="application/x-www-form-urlencoded"
									onsubmit="return confirm(\'Do you really want to submit the form?\');"
									>
										<div class="view-section">
											<label for="csv-select">Select Contact List
											<select type="select" id="csv-select" name="csv-select">
											' . $option_group . '
											</select>
											</label>
											</div>

										<div class="submit-contacts-to-twilio">
											
											<div class="twilio-body">
											<label for="body">Message Body
											<select name="body" id="message-body">
											<option value="message-1">Hi FIRSTNAME, I\'m with Globe Life...</option>
											<option value="message-2">Hey FIRSTNAME, Im reaching out on behalf ...</option> 
											<option value="message-3">ORMOND CAMPAIGN</option>
											<option value="message-4">Good afternoon FIRSTNAME, This is Mr. Johnson with ...</option>
												</select>
											</label>
											</div>
											
											<div class="confirm-twilio">
											<label for="confirm-twilio">
											<input type="checkbox" value="confirm" name="confirm-twilio" required /> Confirm selected message?
											</label>
											</div>
											<div class="submit-twilio">
											<label for="csv-submit">Begin New Text Campaign
											<div class="csv-submit-button">	
											<input type="submit" value="Send Recruiting SMS" name="csv-submit" class="fusion-button button-3d button-medium button-default button-2" />
											</div>
											</label>
											</div>
											
										</div>
											
										</form>
										<div class="api-information"></div>
									</div>';
		// <textarea width="400" height="120" name="body" maxlength="155" placeholder="Maximum character length: 155" required /></textarea>
		return $selector_form;
	}

	public function TwilioCSV_show_results()
	{
		// Exit unless the stars are aligned
		if (!$_POST['csv-submit']) return 'Form was not submitted.';
		if ($_POST['confirm-twilio'] !== 'confirm') return 'Confirmation box wasn\'t checked.';
		if (!$_POST['body']) return 'No message to send!';

		// Start tracking execution time
		$start_time = microtime(true);

		// Go get relevant JSON data and decode for PHP
		global $wpdb;
		$csv_table = $wpdb->prefix . 'twilio_csv_entries';
		$results = $wpdb->get_results('SELECT * FROM ' . $csv_table . ' WHERE id=' . $_POST['csv-select'] . ';');
		foreach ($results as $entry) {
			// Everyone on the uploaded xlsx file
			$contact_array = json_decode($entry->contact_data);
			$file_type = $entry->file_type;
		}

		// echo '<pre>';
		// echo 'File type: ' . $entry->file_type;
		// print_r($results);
		// echo '</pre>';
		// die;

		if ($file_type == 'office') {
			$uploaded_file_type = 'RMS';
		} else if ($file_type == 'record') {
			$uploaded_file_type = 'RMS2';
		} else if ($file_type == 'ormond') {
			$uploaded_file_type = 'ORMOND';
		} else {
			print 'Error';
			die;
		}

		// Go get API Keys and open a new Client
		$api_details = get_option('twilio-csv');
		if (is_array($api_details) and count($api_details) != 0) {
			$TWILIO_SID = $api_details['api_sid'];
			$TWILIO_TOKEN = $api_details['api_auth_token'];
		}
		$client = new Client($TWILIO_SID, $TWILIO_TOKEN);

		$message_result_list = '<ul>';
		$message_count = 0;
		$contact_count = 0;

		// List of programmed messages with replacement variables.
		$messages = array();
		$contacted_numbers = array();
		$introductory_message = '';
		$messages['message-1'] = 'Hey FIRSTNAME, my name is Guillermo with The Johnson Group. We saw your resume online. Are you still looking for a career opportunity?';
		$messages['message-2'] = 'Hi FIRSTNAME, Im reaching out on behalf of Globe Life - Liberty Division. We received your request for employment consideration. Are you still looking for a career?';
		$messages['message-3'] = "FIRSTNAME, Im reaching out because Ive opened a small office in Ormond Beach, and I would love for you to entertain utilizing your 215 license with my agency. Are you open to the possibility?\n\nCarleus Johnson\nAgency Owner\nThe Johnson Group of Globe Life Liberty National Division";
		$messages['message-4'] = 'Good afternoon FIRSTNAME, This is Mr. Johnson with The Johnson Group of Globe Life. I got your number from home office and I am aware that you used to work with our sister company. If youre not using your license, I am loking to pilot a part time program and want to know if you would be interested in teaming up?';
		$opt_out_instructions = 'If you are not interested, please reply STOP to be removed from our list.';
		$selected_message = $messages[$_POST['body']];

		// Process list of contacts with selected message
		foreach ($contact_array as $contact) {

			$recipient = $contact->CellPhone ?? $contact->Telephone ?? $contact->{'Phone Number'};
			if ($uploaded_file_type == 'RMS' || $uploaded_file_type == 'ORMOND') $first_name = $contact->{'First Name'};
			else if ($uploaded_file_type == 'RMS2') $first_name = explode(' ', $contact->Name)[0];
			if (!$recipient) {
				$message_result_list .= '<li>Error: No Cell Phone or Telephone Number</li>';
				continue;
			}

			// echo '<pre>';
			// print_r($contact);
			// echo $recipient;
			// echo $first_name;
			// echo '</pre>';
			// die;

			// Query Twilio API for all messages sent to this number within the last 30 days.
			// If any messages are found, skip this contact.
			$message_query = $client->messages->read(
				[
					'To' => $recipient,
					'dateSentAfter' => date('Y-m-d', strtotime('-30 days'))
				],
				1,
				1
			);
			if (count($message_query) > 0) {
				$message_result_list .= '<li>Error: Message already sent to ' . $recipient . ' within the last 30 days. Contact skipped.</li>';
				continue;
			}

			$TWILIO_MESSAGE_BODY = str_replace('FIRSTNAME', $first_name, $selected_message);
			// Add each message phone number to array of contacted numbers to prevent duplicates on same CSV file.
			if (!in_array($recipient, $contacted_numbers)) {
				try {
					$opt_out_message = $client->messages->create(
						$recipient,
						[
							'body' => $opt_out_instructions,
							'from' => 'MGed693e77e70d6f52882605d37cc30d4c'
						]
					);
					$send_message = $client->messages->create(
						$recipient,
						[
							'body' => $TWILIO_MESSAGE_BODY,
							'from' => 'MGed693e77e70d6f52882605d37cc30d4c'
						]
					);
					if ($send_message) $message_result_list .= '<li>Message sent to <a href="tel:' . $recipient . '" title="Call ' . $recipient . '">' . $recipient . '</a></li>';
					$message_count++; // total messages sent
				} catch (\Exception $throwable) {
					GFCommon::log_error($throwable);
					error_log('Error sending message to ' . $recipient . '. Details: ' . $throwable);
				}
			}
			array_push($contacted_numbers, $recipient);
			$contact_count++; // total contacts processed
		}

		// Get total execution time in milliseconds.
		$total_time = round((microtime(true) - $start_time) * 1000);

		return '<div class="results">Run time: ' . $total_time . ' milliseconds. Messages processed: ' . $message_count . ' to ' . $contact_count . ' contacts. ' . 'Results below: ' . $message_result_list . '</ul></div>';
	}
	/**
	 * Single Message form sender and POST handler. 
	 *
	 * @return HTML
	 */
	function send_single_message()
	{
		// status init
		$message_sent = false;

		// check for form submission
		if ($_POST['single-submit']) {

			// get plugin options and loop through them
			$api_details = get_option('twilio-csv');
			if (is_array($api_details) and count($api_details) != 0) {
				$TWILIO_SID = $api_details['api_sid'];
				$TWILIO_TOKEN = $api_details['api_auth_token'];
			}

			// create message request with authorization
			$client = new Client($TWILIO_SID, $TWILIO_TOKEN);
			$TWILIO_MESSAGE_BODY = $_POST['message-body'];

			try {
				$send_message = $client->messages->create(
					$_POST['single-to'],
					[
						'body' => $TWILIO_MESSAGE_BODY,
						'from' => 'MGed693e77e70d6f52882605d37cc30d4c'
					]
				);
				// set status to success
				if ($send_message) $message_sent = true;
			} catch (\Throwable $throwable) {
				$single_result = $throwable->getMessage();
				return $throwable->getMessage();
			}
			// add Results Box text
			$single_result = 'Message sent to ' . $_POST['single-to'] . '.';
		}

		// HTML
		$results_box = '<div class="results_container">' . $single_result . '</div>';
		$form = '<div class="send_single_form_container">
		<form action="" name="send-single-sms" method="post" id="send-single-sms" enctype="application/x-www-form-urlencoded">
		  <div class="select-recipient">
			<label for="single-to">Enter Phone Number <span class="required">*</span></label>
			<input type="tel" id="single-to" name="single-to" pattern="+1 [0-9]{3} [0-9]{3} [0-9]{4}" maxlength="12" required
			placeholder="+1 386 868 9059" />
		  </div><div class="message-body">
			<label for="message-body">Message Body <span class="required">*</span></label>
			<textarea id="message-body" name="message-body" rows="7" cols="40" placeholder="Message body here ..."
			  required></textarea>
		  </div><div class="submit-area">
			<label for="single-submit">Submit SMS Message</label>
			<input type="submit" value="Submit" name="single-submit" id="single-submit" />
		  </div>
		</form>
	  </div>';

		// Always display the form, optionally include results box if message was sent
		if ($message_sent) {
			return $results_box . $form;
		} else {
			return $form;
		}
	}


	function handle_incoming_message_from_twilio()
	{


		// creating a webhook to handle POST from twilio

		// if (!$_POST['body']) return;

		// $gforms_consumer = "ck_6a4204b5c2e658c7511d1eac3bfc25efb3337922";
		// $gforms_secret = "cs_056ef416b003f7c6c78d922c687e9351da20c1a9";
		// $url = "https://thejohnson.group/wp-json/gf/v2/forms/80/entries";
		// $method = "POST";
		// $args = array();

		// $from = $_POST['from'];
		// $body = $_POST['body'];
		// $date_timestamp = new DateTime();

		// $body_content = '{
		// 	"date_created" : ' . $date_timestamp . ',
		// 	"is_starred"   : 0,
		// 	"is_read"      : 0,
		// 	"ip"           : "::1",
		// 	"source_url"   : "",
		// 	"currency"     : "USD",
		// 	"created_by"   : 1,
		// 	"user_agent"   : "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0",
		// 	"status"       : "active",
		// 	"1"            : ' . $from . ',
		// 	"3"            : ' . $body . '
		// }';

		// require_once('class-oauth-request.php');
		// $oauth = new OAuth_Request($url, $gforms_consumer, $gforms_secret, $method, $args);

		// $response = wp_remote_request(
		// 	$oauth->get_url(),
		// 	array(
		// 		'method' => $method,
		// 		'body' => $body_content,
		// 		'headers' => array('Content-Type' => 'application/json')
		// 		)
		// 	);

		// 	// Check the response code.
		// 	if (wp_remote_retrieve_response_code($response) != 200 || (empty(wp_remote_retrieve_body($response)))) {
		// 		// If not a 200, HTTP request failed.
		// 		die('There was an error attempting to access the API.');
		// 	} else {
		// 		return 'Message sent';
		// 	}
	}


	// begin webhook
	function register_TwilioCSV_route()
	{
		register_rest_route('twilio_csv/v1', '/receive_sms', array(
			'methods' => 'POST',
			'callback' => array($this, 'trigger_receive_sms')
		));
		register_rest_route('twilio_csv/v1', '/action_button', array(
			'methods' => 'POST',
			'callback' => array($this, 'trigger_action_button')
		));
	}

	// // create rest hook for action button handler
	// function register_twilio_action_route()
	// {
	// }

	function trigger_action_button()
	{
		if (!isset($_POST)) return;
		echo 'action button triggered';
		wp_die();
	}

	function trigger_receive_sms()
	{
		// Escape if no POST data to webhook
		if (!isset($_POST)) return;
		$form_entry = array();
		$name = array();
		$response_text = ''; // response text to twilio


		$trimmed_number = (str_contains($_POST['From'], '+1')) ? substr($_POST['From'], 2) : $_POST['From'];



		global $wpdb;
		$table = $wpdb->prefix . 'twilio_csv_contacts';
		$ll_table = $wpdb->prefix . 'twilio_csv_contacts_ll';
		$phone_number = '';

		if ($_POST['Mode'] == 'll') {
			// handler for lapsed list webhook
			$contact = $wpdb->get_results("SELECT * FROM $ll_table WHERE PHONE_NUMBER = " . $trimmed_number . "");
			if (empty($contact)) {
				if (preg_match('/^\+\d(\d{3})(\d{3})(\d{4})$/', $trimmed_number,  $matches)) {
					$result = '(' . $matches[1] . ')' . ' ' . $matches[2] . '-' . $matches[3];
				}
				$contact = $wpdb->get_results("SELECT * FROM $ll_table WHERE PHONE_NUMBER = " . $result . "");
			}

			if (!empty($contact)) {
				foreach ($contact as $sender) {
					$sender_id = $sender->id;
					$sender_first_name = $sender->first_name;
					$sender_last_name = $sender->last_name;
					$sender_phone_number = $sender->phone_number;
					$sender_email = $sender->email;
					$sender_unique_id = $sender->unique_id;
				}
			}

			// Try Twilio Caller ID lookup
			// $caller_id = $this->get_caller_id($_POST['From']);

			// Create new entry in form ID 90
			$form_entry['id'] = '';
			$form_entry['form_id'] = 90;
			$form_entry['date_created'] = '';
			$form_entry['is_starred'] = false;
			$form_entry['is_read'] = false;
			$form_entry['ip'] = '';
			$form_entry['source_url'] = '';
			$form_entry['created_by'] = '';
			$form_entry['post_id'] = '19054';
			$form_entry['1'] = $sender_phone_number ?? '1234567890';
			$form_entry['4'] = $sender_email ?? 'no@email.found';
			$form_entry['5.3'] = $caller_id ?? $sender_first_name ?? '';
			$form_entry['5.6'] = $sender_last_name ?? '';
			$form_entry['6'] = $_POST['Body'] ?? 'message body failed to load';
			$form_entry['7'] = $_POST['Lead Status'] ?? 'New Lead';
			$form_entry['8'] = $result;

			// submit entry through GFAPI
			try {
				$entry_id = GFAPI::add_entry($form_entry);
			} catch (Exception $e) {
				wp_die($e);
			}


			echo header('Content-Type: text/xml');
			die;
		} else {



			// Twilio Key List:
			// ToCountry, ToState, SmsMessageSid, NumMedia, ToCity, FromZip, SmsSid, FromState, SmsStatus, FromCity
			// Body, To, From
			// FromCountry, MessagingServiceSid, ToZip, NumSegments, ReferralNumMedia, MessageSid, AccountSid, ApiVersion

			// $message_array = explode(' ', $_POST['body']);
			// if (!in_array('yes', $message_array)) die;

			/*
		* Add message to front end for further work
		*/


			try {
				$number_lookup = $wpdb->get_results('SELECT * FROM ' . $table);
				if (!empty($number_lookup)) {
					foreach ($number_lookup as $sender) {
						if ($sender->phone_number == $trimmed_number) {
							$first_name = $sender->first_name;
							$last_name = $sender->last_name;
							$phone_number = $sender->phone_number;
							$email = $sender->email;
						}
					}
				} else {
					$response_text .= 'Number Lookup was empty. Attempted to look up: ' . $trimmed_number . ' against ' . $phone_number . ' in the database.';
				}
			} catch (Exception $error) {
				error_log('Number Lookup failed: ' . $error);
			}

			$form_entry['id'] = '';
			$form_entry['form_id'] = '80';
			$form_entry['created_by'] = '';
			$form_entry['date_created'] = '';
			$form_entry['is_starred'] = 'false';
			$form_entry['is_read'] = 'false';
			$form_entry['ip'] = '';
			$form_entry['source_url'] = '';
			$form_entry['post_id'] = '15948';
			$form_entry['status'] = 'active';
			$form_entry['1'] = $_POST['From'] ?? 'POST EMPTY';
			$form_entry['3'] = $_POST['Body'] ?? 'BODY EMPTY';
			$form_entry['4.3'] = $first_name;
			$form_entry['4.6'] = $last_name;
			$form_entry['5'] = (!empty($caller_id)) ? $caller_id : 'Caller ID Unavailable';
			$form_entry['6'] = $_POST['Disposition'] ?? 'DISPOSITION EMPTY';
			$form_entry['7'] = $email ?? 'EMAIL EMPTY';
			$form_entry['8'] = $_POST['Status'] ?? 'STATUS EMPTY';

			try {
				$submission = GFAPI::add_entry($form_entry);
				// if ($submission) {
				// 	$response_text .= 'trigger webhook';
				// }
			} catch (Exception $error) {
				// $response_text .= $error;
				error_log('Create Message Error');
				error_log($error);
			}

			// Message Response Removed

			echo header('content-type: text/xml');
			die;
		}

		// $studioFlowMessage = array_keys($_POST);
		// foreach ($studioFlowMessage as $key => $value) {
		// 	$response_text .= $value . ': ' . $_POST[$value] . '<br>';
		// }


		/*
		* Old bits and pieces
		*/

		// echo <<<RESPOND
		// <?php ml version="1.0" encoding="UTF-8" ? >
		// <Response>
		//   <Message>Ahoy from WordPress</Message>
		// </Response>
		// RESPOND;

		// $api_details = get_option('twilio-csv');
		// if (is_array($api_details) and count($api_details) != 0) {
		// 	$TWILIO_SID = $api_details['api_sid'];
		// 	$TWILIO_TOKEN = $api_details['api_auth_token'];
		// }
		// $twilio = new Client($TWILIO_SID, $TWILIO_TOKEN);
		// $phone_number = $twilio->lookups->v1->phoneNumbers($_POST['From'])->fetch(['type' => ['caller-name']]);
		// $caller_id = $phone_number->callerName;
	}

	// end webhook

	function TwilioCSV_gravity_view_update_handler()
	{
		if (!isset($_GET['lead_id'])) {
			return;
		}
		// Buffer include
		ob_start();
		$content = require_once(plugin_dir_path(__FILE__) . '/partials/class-twilio-csv-update-handler.php');
		ob_end_clean();
		echo $content;
		return '&nbsp;';
	}

	function TwilioCSV_display_upload_form()
	{
		require_once(plugin_dir_path(__FILE__) . '/partials/class-twilio-csv-upload-form.php');
		return;
	}

	function TwilioCSV_register_display_upload_form()
	{
		add_shortcode('twilio_csv_display_upload_form', array($this, 'TwilioCSV_display_upload_form'));
	}

	function TwilioCSV_register_gravity_view_update_handler()
	{
		add_shortcode('update_handler', array($this, 'TwilioCSV_gravity_view_update_handler'));
	}

	function TwilioCSV_register_shortcodes_handle()
	{
		add_shortcode('msg_handler', array($this, 'handle_incoming_message_from_twilio'));
	}

	function TwilioCSV_register_shortcodes_send_single()
	{
		add_shortcode('send_single_message', array($this, 'send_single_message'));
	}

	function TwilioCSV_register_shortcodes_create()
	{
		add_shortcode('create_csv_upload_form', array($this, 'create_csv_upload_form'));
	}


	function TwilioCSV_register_shortcodes_select()
	{
		add_shortcode('select_uploaded_csv_files', array($this, 'select_uploaded_csv_files'));
	}


	function TwilioCSV_register_shortcodes_send()
	{
		add_shortcode('twilio_csv_show_results', array($this, 'TwilioCSV_show_results'));
	}

	// Shortcode to render Reports output
	function TwilioCSV_register_shortcodes_reports()
	{
		add_shortcode('twilio_csv_reports', array($this, 'TwilioCSV_reports'));
	}

	function TwilioCSV_reports()
	{
		$return_content = '';
		$messaging_reports = new twilio_csvReports;
		$outbound_messages = $messaging_reports->get_outbound();
		// echo "hmmph";	
		// $inbound_messages = $messaging_reports->get_inbound();
		// echo '<pre>';
		// var_dump($outbound_messages);
		// display each message from the JSON object as a list item
		// foreach ($outbound_messages as $message) {
		// 	$return_content .= '<li>' . $message->body . '</li>';
		// }
		// echo $return_content;
		// echo '</pre>';
		// var_dump($inbound_messages);
		// return $outbound_messages;
	}

	/**
	 * This hooks into the recruiting page and deposits some javascript, but doesn't seem to function yet.
	 *
	 * @return javascript supposed to update the file-name element
	 */
	function TwilioCSV_add_javascript()
	{
		if (is_page('15948')) {
?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					const file_uploader = document.getElementById("csv-upload");
					const file_text = document.getElementById("file-name");

					/* file.onclick = alert("Yeah the script is working"); */

					function update_file_size(e) {
						const [file] = e.target.files;
						// Get the file name and size
						const {
							name: fileName,
							size
						} = file;
						// Convert size in bytes to kilo bytes
						const fileSize = (size / 1000).toFixed(2);
						// Set the text content
						const fileNameAndSize = `${fileName} - ${fileSize} KB and a bunch of other stuff`;
						file_text.textContent = fileNameAndSize;

					}

					jQuery("#csv-upload").on("change", update_file_size(this));
					jQuery("#twilio-csv-upload-form").on("click", alert("test alert"));

				});
			</script>
<?php
		}
	}
	#endregion Original Twilio CSV Code

	#region Lapsed List Functionality

	// All lapsed list functionality included in this file.

	function ll_csv_upload_form()
	{
		// var_dump($_POST);
		// var_dump($_FILES);
		// form settings and number of CSVs to display
		$attributes = shortcode_atts(array(
			'pagination' => 10
		), $atts, 'll_csv_upload_form');
		$list_upload_contents = '';
		$allowable_headers = array('Name', 'First Name', 'Agent Name', 'Agency');
		$uploaded_file = $_FILES['csv_file'] ?? null;
		$nickname = $_POST['nickname'] ?? 'file';

		if ($uploaded_file) {
			$file_extension = ucfirst(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
			if ($file_extension !== 'Xlsx') {
				return '<p class="alert alert-danger">Please upload a .xlsx file.</p>';
			}

			// save the file to the server and return an array
			$wp_upload_file = wp_handle_upload($uploaded_file, array('test_form' => false));
			try {
				$file_type = IOFactory::identify($wp_upload_file['file']) ?? '';
			} catch (Exception $identify_error) {
				echo 'Identify Error: ' . $identify_error;
				die;
			}

			try {
				$reader = IOFactory::createReader($file_type);
			} catch (Exception $read_error) {
				echo 'Reader Error: ' . $read_error;
				die;
			}

			try {
				$parsed_file = $reader->load($wp_upload_file['file']);
			} catch (Exception $parse_error) {
				echo 'Parse Error: ' . $parse_error;
				die;
			}

			try {
				$file_info = $reader->listWorksheetInfo($wp_upload_file['file']);
			} catch (Exception $parse_error) {
				echo 'File Info failed: ' . $parse_error;
				die;
			}

			if ($parsed_file) {



				$header_values = $json_rows = [];
				$phone_numbers_in_csv = [];
				$num_parsed_rows = 0;
				$num_skipped_rows = 0;

				$uploaded_array = $parsed_file->getActiveSheet()->toArray();
				$first_cell_value = $uploaded_array[0][0];


				$contains = str_contains($first_cell_value, 'Lapsed');
				$contains_Agent = str_contains($first_cell_value, 'Agent');
				$contains_Agency = str_contains($first_cell_value, 'Agency');
				$contains_Premium_Notice = str_contains($first_cell_value, 'PREMIUM NOTICE');

				// $first_row = $uploaded_array[0];
				// var_dump($first_row);
				// die();

				// echo '<pre>';
				// var_dump($contains);
				// var_dump($contains_Agent);
				// var_dump($contains_Agency);
				// var_dump($contains_Premium_Notice);
				// echo '</pre>';

				// if (!in_array($first_cell_value, $allowable_headers)) {
				// 	return '<p class="alert alert-danger">Please make sure the first cell in the first row of your CSV is a valid header.</p>';
				// }

				if ($first_cell_value == 'Name') {
					$csv_type = 'Name';
					foreach ($uploaded_array as $row => $cell) {

						if ($row === 4) {
							$header_values = $cell;
							continue;
						}

						// In this style of sheet, the phone number is col 2
						$phone_number = $cell[1];
						if (!empty($phone_number)) {
							// $cell[1] = strip_phone_extras($phone_number);
						}

						if (!in_array($cell[1], $phone_numbers_in_csv)) {
							array_push($phone_numbers_in_csv, $cell[1]);
							$json_rows[] = array_combine($header_values, $cell);
							$num_parsed_rows++;
						} else {
							// number already pushed and is duplicate
							$num_skipped_rows++;
							continue;
						}
					} // end foreach
				} else if ($contains == true) {
					$csv_type = 'Lapsed';
					// Second type of sheet, headers begin on row 4
					foreach ($uploaded_array as $row => $cell) {
						if ($row < 3) {
							continue;
						}
						if ($row === 3) {
							$header_values = $cell;
							continue;
						}
						// format Payor column for name, column 4
						$payor_name = $cell[3];
						// echo $payor_name;
						// die;

						// Phone is Column 3
						$phone_number = $cell[2];

						// format phone number
						$phone_number = preg_replace('/[^0-9]/', '', $phone_number);

						if (!in_array($phone_number, $phone_numbers_in_csv)) {
							array_push($phone_numbers_in_csv, $cell[2]);
							$json_rows[] = array_combine($header_values, $cell);
							$num_parsed_rows++;
						} else {
							// number already pushed and is duplicate
							if (!is_null($cell[2])) {
								$num_skipped_rows++;
								continue;
							}
							continue;
						}
					}
				} else if ($contains_Agent == true || $first_cell_value === null) {
					$csv_type = 'Chargeback';
					foreach ($uploaded_array as $row => $cell) {
						// print_r($cell);
						// continue;
						// third type of sheet, headers begin on row 1
						if ($row === 0) {
							$header_values = $cell;
							continue;
						}

						$payor_name = $cell[2];
						$phone_number = $cell[4];
						$phone_number = preg_replace('/[^0-9]/', '', $phone_number);

						// Check if number already in array of numbers
						if (!in_array($phone_number, $phone_numbers_in_csv)) {
							array_push($phone_numbers_in_csv, $phone_number);
							$json_rows[] = array_combine($header_values, $cell);
							$num_parsed_rows++;
						} else {
							// number already pushed, duplicate
							if (!is_null($cell[4])) {
								$num_skipped_rows++;
								continue;
							}
							continue;
						}
					}
					// echo '<pre>';
					// print_r( $json_rows );
					// echo '</pre>';
					// die;
				} else if ($contains_Agency == true) {
					$csv_type = 'Agency';
					foreach ($uploaded_array as $row => $cell) {
						// print_r($cell);
						// continue;
						// fourth type of sheet, headers begin on row 1
						if ($row === 0) {
							$header_values = $cell;
							continue;
						}

						$payor_name = $cell[14];
						$phone_number = $cell[13];
						$phone_number = preg_replace('/[^0-9]/', '', $phone_number);
						// Check if number already in array of numbers
						if (!in_array($phone_number, $phone_numbers_in_csv)) {
							array_push($phone_numbers_in_csv, $phone_number);
							$json_rows[] = array_combine($header_values, $cell);
							$num_parsed_rows++;
						} else {
							// number already pushed, duplicate
							if (!is_null($cell[13])) {
								$num_skipped_rows++;
								continue;
							}
							continue;
						}
					}
					// echo '<pre>';
					// var_dump($contains_Premium_Notice);
					// var_dump($uploaded_array);
					// echo '</pre>';
					// die;
					// echo '<pre>';
					// print_r( $json_rows );
					// echo '</pre>';
					// die;

				} else if ($contains_Premium_Notice) {

					// Janky format
					$csv_type = 'Premium Notice';

					$header_values = array(
						'First Name',
						'Last Name',
						'Phone Number'
					);

					foreach ($uploaded_array as $row => $cell) {
						// skip first row and empty phone rows
						if ($row === 0) {
							continue;
						}

						if (empty($cell[27]) || is_null($cell[27])) {
							continue;
						}

						// format name fields
						$full_name = $cell[11];

						$explode_name = explode(',', $full_name);

						$last_name = $explode_name[0];

						if (str_contains($explode_name[1], ' ')) {
							$explode_first_name = explode(' ', $explode_name[1]);
							$first_name = $explode_first_name[0];
						} else {
							$first_name = $explode_name[1];
						}

						// format name fields from all caps to title case
						$first_name = ucwords(strtolower($first_name));
						$last_name = ucwords(strtolower($last_name));

						// if first or last name are empty, skip
						if (empty($first_name) || empty($last_name)) {
							$num_skipped_rows++;
							continue;
						}

						// format phone number for db
						$area_code = $cell[27];
						$digits = $cell[30];
						$phone_number = $area_code . $digits;
						// remove special characters
						$phone_number = preg_replace('/[^0-9]/', '', $phone_number);

						// continue if number is not 10 digits, or null, or empty, or all zeroes
						if (strlen($phone_number) !== 10 || is_null($phone_number) || empty($phone_number) || $phone_number === '0000000000') {
							$num_skipped_rows++;
							continue;
						}

						// continue if first three numbers of $phone_number are 0
						if (substr($phone_number, 0, 3) === '000') {
							$num_skipped_rows++;
							continue;
						}

						$contact_array = array(
							$first_name,
							$last_name,
							$phone_number
						);

						// Check if number already in array of numbers
						if (!in_array($phone_number, $phone_numbers_in_csv)) {
							array_push($phone_numbers_in_csv, $phone_number);
							$json_rows[] = array_combine($header_values, $contact_array);
							$num_parsed_rows++;
						} else {
							// number already pushed, duplicate
							if (!is_null($cell[27])) {
								$num_skipped_rows++;
								continue;
							}
							continue;
						}
					}

					// echo '<pre>';
					// echo 'Number of parsed rows: ' . $num_parsed_rows;
					// echo '<br />';
					// echo 'Number of skipped rows: ' . $num_skipped_rows;
					// print_r($json_rows);
					// echo '</pre>';
					// die();

				} else {
					$list_upload_contents = '<p class="alert alert-danger">Please make sure the first cell in the first row of your CSV is a valid header.';
					$list_upload_contents .= '<p> First header value: ' . $first_cell_value . '</p>';
					return $list_upload_contents;
				}

				// working through here

				$trim_rows = count($json_rows);

				$file_data = array();
				$file_data['file_name'] = $uploaded_file['tmp_name'];
				$file_data['rows'] = $trim_rows;
				$file_data['date'] = date('g:i:s A m/d/Y', strtotime('now -4 hours'));
				$file_data['nickname'] = $nickname;
				$file_data['csv_type'] = $csv_type;

				if (!empty($json_rows) && $_POST['confirm_upload'] == 'confirm') {
					try {
						global $wpdb;
						$mode = 'll';
						$json_data = json_encode($json_rows);
						$file_wpdb = $this->process_pending_lapsed($json_data, $num_parsed_rows, $file_data);
						// var_dump($file_wpdb);
						// echo $wpdb->last_query;
						// echo $wpdb->last_error;

						// die();




						$list_upload_contents .= ($file_wpdb) ? $file_wpdb : '<p class="alert alert-danger">There was an error uploading your CSV. Please try again.</p>';
					} catch (Exception $json_error) {
						echo 'JSON Error: ' . $json_error;
						die;
					}
				}

				foreach ($file_info as $worksheet) {
					$cols = $worksheet['totalColumns'];
					$rows = $worksheet['totalRows'] - 1;
				}

				$list_upload_contents .= '<div class="file-contents"><h4>Contents of File</h4>';
				$list_upload_contents .= '<p>' . $rows . ' rows in uploaded file.</p>';
				$list_upload_contents .= ($num_skipped_rows > 0) ? '<p>' . $num_skipped_rows . ' rows were skipped.</p>' : '';
			} else {
				$list_upload_contents = '<p class="alert alert-danger">There was an error uploading your CSV. Please try again.</p>';
			}
		}
		$primary_upload_form = '<div class="ll-csv-upload-form">';
		$primary_upload_form .= '<form action="" method="post" enctype="multipart/form-data" id="ll-csv-upload-form" name="ll-csv-upload-form">';
		$primary_upload_form .= '<div class="upload-section">';
		$primary_upload_form .= '<label for="csv_file">Upload CSV File</label><input type="file" name="csv_file" id="csv_file" accept=".xlsx" class="upload-file" /></label><p id="file-name" class="file-name"></p></div>';
		$primary_upload_form .= (!empty($list_upload_contents)) ? '<div class="list-upload-contents">' . $list_upload_contents . '</div>' : '';
		$primary_upload_form .= '<div class="form-group"><label for="nickname">Add a Nickname for this file: </label><input type="text" name="nickname" id="nickname" class="form-control" placeholder="Nickname here"/></div>';
		$primary_upload_form .= '<div class="confirm-upload-section">';
		$primary_upload_form .= '<label for="confirm-upload">Confirm Upload</label><input type="checkbox" name="confirm_upload" id="confirm-upload" value="confirm" /></label><p>I have checked the contents of the file and I am ready to upload.</p></div>';
		$primary_upload_form .= '<div class="submit-section"><input type="submit" name="submit" value="Upload" class="submit-button" /></div>';
		$primary_upload_form .= '</form></div>';

		return $primary_upload_form;

		// begin parse if file exists

	}

	function process_pending_lapsed($json_data, $row_count, $file_data)
	{

		$output = '';
		$insert_contact = 0;
		$new_contacts = 0;
		$existing_ids = array();

		// decode json data
		$serialized_data = maybe_serialize($json_data);

		// WPDB
		global $wpdb;
		$ll_csv_table = $wpdb->prefix . 'twilio_csv_entries_ll';
		$ll_csv_contacts_table = $wpdb->prefix . 'twilio_csv_contacts_ll';

		// CSV Entry
		$csv_entry = array(
			'id' => '',
			'date' => $file_data['date'],
			'contact_data' => $serialized_data,
			'num_entries' => $row_count,
			'send_count' => 0,
			'success_count' => 0,
			'fail_count' => 0,
			'file_name' => $file_data['file_name'],
			'nickname' => $file_data['nickname'],
			'csv_type' => $file_data['csv_type']
		);


		// insert CSV Entry
		try {
			$insert_csv_entry = $wpdb->insert($ll_csv_table, $csv_entry);
			// dump csv entry in pre and die
			$output .= ($insert_csv_entry) ? '<p class="alert alert-success">Your CSV has been uploaded and is now pending processing.</p>' : '<p class="alert alert-danger">There was an error uploading your CSV. Please try again.</p>';
		} catch (Exception $insert_csv_error) {
			echo 'Insert CSV Error: ' . $insert_csv_error;
			die;
		}

		// Begin parsing contacts and inserting into Contact table
		try {
			$contact_query = $wpdb->get_results("SELECT * FROM $ll_csv_contacts_table");
			if (!empty($contact_query)) {
				foreach ($contact_query as $contact) {
					$existing_ids[] = $contact->unique_id;
				}
			} else {
				$existing_ids = array();
			}
		} catch (Exception $contact_query_error) {
			echo 'Contact Query Error: ' . $contact_query_error;
			die;
		}

		// create associative array from $json_data called $json_array
		$json_array = json_decode($json_data, true);

		switch ($file_data['csv_type']) {
			case 'Chargeback':
				foreach ($json_array as $entry) {
					$first_name = $entry['Insured First Name'];
					$last_name = $entry['Insured Last Name'];
					$phone = $entry['Phone Number'];
					$phone = preg_replace('/[^0-9]/', '', $phone);
					$id_salt = $first_name . $last_name . ' yeet ' . $phone;
					$unique_id = hash('sha256', $id_salt);
					$email = '';

					$entry_to_insert = array(
						'id' => '',
						'first_name' => $first_name ?? '',
						'last_name' => $last_name ?? '',
						'phone_number' => $phone ?? '',
						'email' => $email ?? '',
						'unique_id' => $unique_id
					);


					// Check if unique ID already exists in array of existing IDs
					if (in_array($unique_id, $existing_ids)) {
						// do nothing
						$output .= '<p class="alert alert-info">' . $first_name . ' already exists in the database.</p>';
						continue;
					}

					// insert contact into DB
					try {
						$insert_contact = $wpdb->insert($ll_csv_contacts_table, $entry_to_insert, array('%s', '%s', '%s', '%s', '%s', '%s'));
						$output .= ($insert_contact) ? '<p class="alert alert-success">The contact ' . $first_name . ' ' . $last_name . ' has been added to the database.</p>' : '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Please try again.</p>';
						if ($insert_contact) {
							$new_contacts++;
						}
					} catch (Exception $insert_contact_error) {
						echo 'Insert Contact Error: ' . $insert_contact_error;
						die;
					}
				}
				break;
			case 'Lapsed':
				// Loop through JSON array and insert into Contact table
				foreach ($json_array as $entry) {

					$name = $entry['Name'] ?? $entry['Payor'] ?? null;
					if (is_null($name)) {
						continue;
					}
					$split_name = explode(',', $name);

					// echo 'Split name: ';
					// var_dump($split_name);


					// if first name has a space, explode on space and take first element
					if (strpos($split_name[1], ' ') !== false) {
						// trim leading white space
						$split_name[1] = trim($split_name[1]);
						$split_first_name = explode(' ', $split_name[1]);
						$first_name = $split_first_name[0];
						$last_name = $split_name[0];
						// var_dump($split_first_name);
					} else {
						// echo 'yote';
						$first_name = $split_name[1];
						$last_name = $split_name[0];
					}


					// Remove all special characters from Phone Number and format to ten digits
					$phone = $entry['Phone Number'] ?? $entry['Telephone'];
					$phone = preg_replace('/[^0-9]/', '', $phone);

					$id_salt = $first_name . $last_name . '_yeet_' . $phone;
					$unique_id = hash('sha256', $id_salt);
					$email = '';

					$entry_to_insert = array(
						'id' => '',
						'first_name' => $first_name ?? '',
						'last_name' => $last_name ?? '',
						'phone_number' => $phone ?? '',
						'email' => $email ?? '',
						'unique_id' => $unique_id
					);

					// Check if unique ID already exists in array of existing IDs
					if (in_array($unique_id, $existing_ids)) {
						// do nothing
						$output .= '<p class="alert alert-info">' . $first_name . ' already exists in the database.</p>';
						continue;
					}

					// insert contact into DB
					try {
						$insert_contact = $wpdb->insert($ll_csv_contacts_table, $entry_to_insert, array('%s', '%s', '%s', '%s', '%s', '%s'));
						$output .= ($insert_contact) ? '<p class="alert alert-success">The contact ' . $first_name . ' ' . $last_name . ' has been added to the database.</p>' : '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Please try again.</p>';
						if ($insert_contact) {
							$new_contacts++;
						}
					} catch (Exception $insert_contact_error) {
						echo 'Insert Contact Error: ' . $insert_contact_error;
						die;
					}
				}
				break;

			case 'Agency':
				// echo 'Agency';
				// echo '<pre>';

				// loop through JSON array and insert into Contact table
				foreach ($json_array as $entry) {
					$name = $entry['Payer Name'] ?? null;
					if (is_null($name)) {
						continue;
					}
					$split_name = explode(',', $name);
					// if first name has a space, explode on space and take first element
					if (strpos($split_name[1], ' ') !== false) {
						// trim leading white space
						$split_name[1] = trim($split_name[1]);
						$split_first_name = explode(' ', $split_name[1]);
						$first_name = $split_first_name[0];
						$last_name = $split_name[0];
						// var_dump($split_first_name);
					} else {
						// echo 'yote';
						$first_name = $split_name[1];
						$last_name = $split_name[0];
					}
					// Remove all special characters from Phone Number and format to ten digits
					$phone = $entry['Phone Number'] ?? $entry['Telephone'];
					$phone = preg_replace('/[^0-9]/', '', $phone);
					$id_salt = $first_name . $last_name . '_yeet_' . $phone;
					$unique_id = hash('sha256', $id_salt);
					$email = '';
					$entry_to_insert = array(
						'id' => '',
						'first_name' => $first_name ?? '',
						'last_name' => $last_name ?? '',
						'phone_number' => $phone ?? '',
						'email' => $email ?? '',
						'unique_id' => $unique_id
					);

					// Check if unique ID already exists in array of existing IDs
					if (in_array($unique_id, $existing_ids)) {
						// do nothing
						$output .= '<p class="alert alert-info">' . $first_name . ' already exists in the database.</p>';
						continue;
					}
					// insert contact into DB
					try {
						$insert_contact = $wpdb->insert($ll_csv_contacts_table, $entry_to_insert, array('%s', '%s', '%s', '%s', '%s', '%s'));
						$output .= ($insert_contact) ? '<p class="alert alert-success">The contact ' . $first_name . ' ' . $last_name . ' has been added to the database.</p>' : '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Please try again.</p>';
						if ($insert_contact) {
							$new_contacts++;
						}
					} catch (Exception $insert_contact_error) {
						$output .= '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Error: ' . $insert_contact_error . '</p>';
						error_log('Insert Contact Error: ' . $insert_contact_error);
						continue;
					}

					// print_r($entry_to_insert);
					// echo '<br>';
				}
				// echo '</pre>';
				// die;
				break;

			case 'Premium Notice':

				// loop through JSON array and insert into Contact table
				foreach ($json_array as $contact) {
					$first_name = $contact['First Name'] ?? null;
					$last_name = $contact['Last Name'] ?? null;
					$phone = $contact['Phone Number'] ?? null;
					$email = '';
					$id_salt = $first_name . $last_name . '_yeet_' . $phone;
					$unique_id = hash('sha256', $id_salt);
					$entry_to_insert = array(
						'id' => '',
						'first_name' => $first_name ?? '',
						'last_name' => $last_name ?? '',
						'phone_number' => $phone ?? '',
						'email' => $email ?? '',
						'unique_id' => $unique_id
					);

					// Check if unique ID already exists in array of existing IDs
					if (in_array($unique_id, $existing_ids)) {
						// do nothing
						$output .= '<p class="alert alert-info">' . $first_name . ' already exists in the database.</p>';
						continue;
					}

					// insert contact into DB
					try {
						$insert_contact = $wpdb->insert($ll_csv_contacts_table, $entry_to_insert, array('%s', '%s', '%s', '%s', '%s', '%s'));
						$output .= ($insert_contact) ? '<p class="alert alert-success">The contact ' . $first_name . ' ' . $last_name . ' has been added to the database.</p>' : '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Please try again.</p>';
						if ($insert_contact) {
							$new_contacts++;
						}
					} catch (Exception $insert_contact_error) {
						$output .= '<p class="alert alert-danger">There was an error adding ' . $first_name . ' ' . $last_name . ' to the database. Error: ' . $insert_contact_error . '</p>';
						error_log('Insert Contact Error: ' . $insert_contact_error);
						continue;
					}
				}

				break;
		}

		if ($insert_contact && $new_contacts > 0) {
			$output .= '<p class="alert alert-success">' . $new_contacts . ' new contacts have been added to the database.</p>';
		} else if ($insert_contact && $new_contacts == 0) {
			$output .= '<p class="alert alert-info">No new contacts were added to the database, but CSV successfully inserted.</p>';
		} else if ($insert_csv_entry && $new_contacts == 0) {
			$output .= '<p class="alert alert-info">No new contacts were added to the database, but CSV successfully inserted.</p>';
		} else {
			$output .= '<strong>Submission failure. Please try again.</strong>';
		}

		return $output;
	}

	function ll_display_uploaded_files()
	{

		// embedded page
		$ll_page = get_page_link();

		// init wpdb
		global $wpdb;
		$ll_csv_table = $wpdb->prefix . 'twilio_csv_entries_ll';
		$ll_csv_contacts_table = $wpdb->prefix . 'twilio_csv_contacts_ll';

		// collect shortcode attributes and set pagination to 10 by default
		$atts = shortcode_atts(array(
			'page' => 1,
			'per_page' => 10
		), $atts, 'll_display_uploaded_files');


		// get most 'per_page' most recent csv entries
		$csv_entries = $wpdb->get_results("SELECT * FROM $ll_csv_table ORDER BY id DESC LIMIT " . $atts['per_page'] . " OFFSET " . ($atts['page'] - 1) * $atts['per_page']);
		$csv_entries_count = $wpdb->get_var("SELECT COUNT(*) FROM $ll_csv_table");
		$csv_entries_count_pages = ceil($csv_entries_count / $atts['per_page']);
		$csv_entries_count_pages_array = array();
		for ($i = 1; $i <= $csv_entries_count_pages; $i++) {
			$csv_entries_count_pages_array[] = $i;
		}

		// loop through results and add csv entries to Option group
		$option_group = '';
		$bulk_message_options = '';

		//format $csv_entries->date for display m/d/Y
		$date_format = 'm/d/Y';

		foreach ($csv_entries as $csv_entry) {
			$entry_date = date($date_format, strtotime($csv_entry->date));
			$option_group .= '<option value="' . $csv_entry->id . '">' . $entry_date . ' - ' . $csv_entry->nickname . ' - ' . $csv_entry->num_entries . ' Names in File</option>';
		}

		// create Message array for selection option group
		$bulk_messages = array(
			array(
				'value' => 'message-0',
				'text' => 'Select a Message',
				'message' => 'Select message to send to contacts...'
			),
			array(
				'value' => 'message-1',
				'text' => 'Lapsed Script',
				'message' => 'Hello CLIENTNAME, my name is Ricardo with your insurance company Liberty National. I have received an urgent notice that your policy is no longer in benefit. When is a good time for me to call you to go over your options?'
			),
			array(
				'value' => 'message-2',
				'text' => 'Lapsed Script 2',
				'message' => 'Hello CLIENTNAME, this is Ricardo with your insurance company Globe Life Liberty National Division. I have been trying to reach you regarding your coverage. Currently, you are not protected. When is the best time for us to talk?'
			)
		);
		$opt_out_ll = 'If you are no longer interested in keeping your coverage, reply STOP.';

		foreach ($bulk_messages as $message) {
			$bulk_message_options .= '<option value="' . $message['message'] . '">' . $message['text'] . '</option>';
		}
		// render Form to select which CSV file to submit
		$selector_form = '<div class="ll-csv-selector-form">';
		$selector_form .= '<form action="' . $ll_page . '?upload=send"
							method="post"
							name="ll-csv-selector-form"
							id="ll-csv-selector-form"
							enctype="multipart/form-data"
							onsubmit="return confirm(\'Are you sure you want to send this CSV file?\');"
							class="ll-csv-selector-form"
							>';
		$selector_form .= '<div class="form-group">';
		$selector_form .= '<label for="ll-csv-selector">Select CSV file to send:&nbsp;</label>';
		$selector_form .= '<select class="form-control" id="ll-csv-selector" name="ll-csv-selector">';
		$selector_form .= '<option value="">Select CSV file to send</option>';
		$selector_form .= $option_group;
		$selector_form .= '</select>';
		$selector_form .= '</div>';
		$selector_form .= '<div class="form-group">';
		$selector_form .= '<label for="ll-csv-message">Select message to send to contacts:&nbsp;</label>';
		$selector_form .= '<select class="form-control" id="ll-csv-message" name="ll-csv-message">';
		$selector_form .= $bulk_message_options;
		$selector_form .= '</select>';
		$selector_form .= '<input type="hidden" name="ll-csv-message-opt-out" value="' . $opt_out_ll . '" />';
		$selector_form .= '</div>';
		$selector_form .= '<div class="form-group csv-options">';
		$selector_form .= '<label for="ll-csv-override">Override 30 Day Limit:&nbsp;';
		$selector_form .= '<input type="checkbox" id="ll-csv-override" name="ll-csv-override" value="1" /></label>';
		// $selector_form .= '</div>';
		// $selector_form .= '<div class="form-group">';
		$selector_form .= '<label for="ll-csv-test">Test message?&nbsp;';
		$selector_form .= '<input type="checkbox" id="ll-csv-test" name="ll-csv-test" value="1" /></label>';
		$selector_form .= '<label for="ll-csv-opt-out">Include opt-out?&nbsp;';
		$selector_form .= '<input type="checkbox" id="ll-csv-opt-out" name="ll-csv-opt-out" value="1" checked/></label>';
		$selector_form .= '</div>';
		$selector_form .= '<div class="form-group">';
		$selector_form .= '<label for="ll-csv-confirm-send">Confirm send:&nbsp;';
		$selector_form .= '<input type="checkbox" id="ll-csv-confirm-send" name="ll-csv-confirm-send" value="1" /></label>';
		$selector_form .= '</div>';
		$selector_form .= '<button type="submit" class="btn btn-primary">Send CSV</button>';
		$selector_form .= '</form>';
		$selector_form .= '</div>';

		return $selector_form;
	}

	function ll_show_results()
	{
		// exit if no $_POST
		if (!isset($_POST)) {
			return 'No form submitted.';
		}

		global $wpdb;
		$ll_csv_table = $wpdb->prefix . 'twilio_csv_entries_ll';
		$ll_csv_contacts_table = $wpdb->prefix . 'twilio_csv_contacts_ll';
		$csv_file_type = '';

		// collect csv equal to $_POST['ll-csv-selector']
		$csv_id = $_POST['ll-csv-selector'];
		$csv_entry = $wpdb->get_results("SELECT * FROM $ll_csv_table WHERE id = $csv_id");
		foreach ($csv_entry as $csv_entry) {
			$contact_list = $csv_entry->contact_data;
			$csv_file_type = $csv_entry->csv_type;
		}

		$contact_list = json_decode($contact_list, true);

		// echo '<pre>';
		// print_r($contact_list);
		// echo '</pre>';
		// die();

		// begin sending messages and creating list of results

		if ($_POST['ll-csv-confirm-send'] == 1) {
			$message_count = 0;
			$list_item = '<ol>';
			$duplicate_strip = array();
			foreach ($contact_list as $contact) {
				if (in_array($contact['Telephone'], $duplicate_strip)) {
					continue;
				}
				// if messaged in the last 30 days, skip
				$messaged = $this->check_if_messaged($contact['Telephone']);
				if ($messaged) {
					$list_item .= '<li>' . $contact['Telephone'] . ' - already messaged within last 30 days, skipped.</li>';
					continue;
				}

				// get first name from LASTNAME,FIRSTNAME INITIAL
				$fullname = explode(' ', $contact['Payor']);
				$first_last = explode(',', $fullname[0]);
				$first_name = $first_last[1];
				$last_name = $first_last[0];
				// convert to title case
				$first_name = ucwords(strtolower($first_name));

				// replace CLIENTNAME with contact name
				$message = $_POST['ll-csv-message'];
				$message = str_replace('CLIENTNAME', $first_name, $message);

				$opt_out = $this->send_twilio($contact['Telephone'], $_POST['ll-csv-message-opt-out'], false);
				$primary = $this->send_twilio($contact['Telephone'], $message, false);

				if ($primary && $opt_out) {
					$message_count++;
					$list_item .= '<li>Message Sent to ' . $first_name . ' - ' . $contact['Telephone'] . '</li>';
				} else if (!$primary && !$opt_out) {
					$list_item .= '<li>Messages to ' . $first_name . ' - ' . $contact['Telephone'] . ' failed, client has opted out.</li>';
				}
				array_push($duplicate_strip, $contact['Telephone']);
			}
			$list_item .= '</ol>';
			$list_item .= '<p>' . $message_count . ' messages sent.</p>';

			// echo '<pre>';
			// var_dump($list_item);
			// echo '</pre>';
			// die();

			return $list_item;
		}
	}

	public static function check_if_messaged($phone_number)
	{
		$api_details = get_option('twilio-csv');
		if (is_array($api_details) and count($api_details) != 0) {
			$TWILIO_SID = $api_details['api_sid'];
			$TWILIO_TOKEN = $api_details['api_auth_token'];
		}
		$client = new Client($TWILIO_SID, $TWILIO_TOKEN);

		try {
			$messages = $client->messages->read(
				array(
					'from' => '+13862006100',
					'to' => $phone_number,
					'dateSentAfter' => date('Y-m-d', strtotime('-30 days'))
				),
				20
			);
		} catch (TwilioException $error) {
			error_log($error);
			return false;
		}

		if (count($messages) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function send_twilio($phone_number, $message, $bypass = false)
	{

		if ($bypass) {
			return true;
		}

		$api_details = get_option('twilio-csv');
		if (is_array($api_details) and count($api_details) != 0) {
			$TWILIO_SID = $api_details['api_sid'];
			$TWILIO_TOKEN = $api_details['api_auth_token'];
		}
		$client = new Client($TWILIO_SID, $TWILIO_TOKEN);
		// var_dump($client);
		// die();
		try {
			$client->messages->create(
				$phone_number,
				array(
					'from' => '+13862006100',
					'body' => $message
				)
			);
		} catch (TwilioException $error) {
			error_log($error);
			return false;
		}

		return true;
	}

	public static function get_caller_id($from)
	{
		$api_details = get_option('twilio-csv');
		if (is_array($api_details) and count($api_details) != 0) {
			$TWILIO_SID = $api_details['api_sid'];
			$TWILIO_TOKEN = $api_details['api_auth_token'];
		}
		$client = new Client($TWILIO_SID, $TWILIO_TOKEN);
		$caller_id = $client->lookups->v1->phoneNumbers($from)->fetch(['type' => ['caller-name']]);

		return $caller_id->callerName;
	}

	// Register all Shortcodes for front end Lapsed List functionality here
	function ll_register_shortcode_upload_form()
	{
		add_shortcode('ll_csv_upload_form', array($this, 'll_csv_upload_form'));
	}
	function ll_register_shortcode_display_uploads()
	{
		add_shortcode('ll_display_uploaded_files', array($this, 'll_display_uploaded_files'));
	}

	function ll_register_shortcode_show_results()
	{
		add_shortcode('ll_show_results', array($this, 'll_show_results'));
	}


	#endregion

} //  class TwilioCSV_Public()
