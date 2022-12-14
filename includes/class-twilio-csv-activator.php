<?php

/**
 * Fired during plugin activation
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 */
class TwilioCSV_Activator {

	private $contacts_table;
	private $recruits_table;
	private $conversations_table;
	private $messages_table;
	private $email_campaigns_table;
	private $sms_campaigns_table;
	private $logs_table;
	private $schedule_table;	
	private $programmable_messages_table;
	private $scheduled_briefings_table;
	private $interviews_table;
	private $settings_options_table;

	public function __construct() {

		global $wpdb;
		$this->contacts_table = $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
		$this->recruits_table = $wpdb->prefix . TWILIOCSV_RECRUITS_TABLE;
		$this->conversations_table = $wpdb->prefix . TWILIOCSV_CONVERSATIONS_TABLE;
		$this->messages_table = $wpdb->prefix . TWILIOCSV_MESSAGES_TABLE;
		$this->email_campaigns_table = $wpdb->prefix . TWILIOCSV_EMAIL_CAMPAIGNS_TABLE;
		$this->sms_campaigns_table = $wpdb->prefix . TWILIOCSV_SMS_CAMPAIGNS_TABLE;
		$this->logs_table = $wpdb->prefix . TWILIOCSV_LOGS_TABLE;
		$this->schedule_table = $wpdb->prefix . TWILIOCSV_SCHEDULE_TABLE;
		$this->programmable_messages_table = $wpdb->prefix . TWILIOCSV_PROGRAMMABLE_MESSAGES_TABLE;
		$this->scheduled_briefings_table = $wpdb->prefix . TWILIOCSV_SCHEDULED_BRIEFINGS_TABLE;
		$this->interviews_table = $wpdb->prefix . TWILIOCSV_INTERVIEWS_TABLE;
		$this->settings_options_table = $wpdb->prefix . TWILIOCSV_SETTINGS_OPTIONS_TABLE;

	}
	
	/**
	 * Inits all database tables for TwilioCSV
	 *
	 * Create the following tables defined in twilio-csv.php:
	 * 1. twilio_csv_contacts
	 * 2. twilio_csv_recruits
	 * 3. twilio_csv_conversations
	 * 4. twilio_csv_messages
	 * 5. twilio_csv_email_campaigns
	 * 6. twilio_csv_sms_campaigns
	 * 7. twilio_csv_logs
	 * 8. twilio_csv_schedule
	 * 9. twilio_csv_programmable_messages
	 * 10. twilio_csv_scheduled_briefings
	 * 11. twilio_csv_interviews
	 * 12. twilio_csv_settings_options
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		global $wpdb;

		// collect table names
		$contacts_table = $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
		$recruits_table = $wpdb->prefix . TWILIOCSV_RECRUITS_TABLE;
		$conversations_table = $wpdb->prefix . TWILIOCSV_CONVERSATIONS_TABLE;
		$messages_table = $wpdb->prefix . TWILIOCSV_MESSAGES_TABLE;
		$email_campaigns_table = $wpdb->prefix . TWILIOCSV_EMAIL_CAMPAIGNS_TABLE;
		$sms_campaigns_table = $wpdb->prefix . TWILIOCSV_SMS_CAMPAIGNS_TABLE;
		$logs_table = $wpdb->prefix . TWILIOCSV_LOGS_TABLE;
		$schedule_table = $wpdb->prefix . TWILIOCSV_SCHEDULE_TABLE;
		$programmable_messages_table = $wpdb->prefix . TWILIOCSV_PROGRAMMABLE_MESSAGES_TABLE;
		$scheduled_briefings_table = $wpdb->prefix . TWILIOCSV_SCHEDULED_BRIEFINGS_TABLE;
		$interviews_table = $wpdb->prefix . TWILIOCSV_INTERVIEWS_TABLE;
		$settings_options_table = $wpdb->prefix . TWILIOCSV_SETTINGS_OPTIONS_TABLE;


		// include dbdelta
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// get charset_collate
		$charset_collate = $wpdb->get_charset_collate();

		// contacts table
		if (!TwilioCSV::table_exists($contacts_table)) {
			$sql = "CREATE TABLE $contacts_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_first_name varchar(255) DEFAULT NULL,
				_last_name varchar(255) DEFAULT NULL,
				_phone varchar(255) DEFAULT NULL,
				_email varchar(255) DEFAULT NULL,
				_city varchar(255) DEFAULT NULL,
				_state varchar(255) DEFAULT NULL,
				_source varchar(255) DEFAULT NULL,
				_status varchar(255) DEFAULT NULL,
				_disposition varchar(255) DEFAULT NULL,
				_notes varchar(255) DEFAULT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_user_id mediumint(9) DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// recruits table
		if (!TwilioCSV::table_exists($recruits_table)) {
			$sql = "CREATE TABLE $recruits_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_contact_id mediumint(9) NOT NULL,
				_interview_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_time_in_xcel varchar(255) DEFAULT NULL,
				_ple_percent varchar(255) DEFAULT NULL,
				_ple_completion_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_prep_percent varchar(255) DEFAULT NULL,
				_sim_percent varchar(255) DEFAULT NULL,
				_prepared_to_pass varchar(255) DEFAULT NULL,
				_interview_questions TEXT DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// conversations table
		if (!TwilioCSV::table_exists($conversations_table)) {
			$sql = "CREATE TABLE $conversations_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_id varchar(255) NOT NULL,
				_contact_id varchar(255) NOT NULL,
				_recruit_id varchar(255) NOT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// messages table
		if (!TwilioCSV::table_exists($messages_table)) {
			$sql = "CREATE TABLE $messages_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_SmsMessageSid varchar(255) NOT NULL,
				_MessagingServiceSid varchar(255) NOT NULL,
				_From varchar(255) DEFAULT NULL,
				_To varchar(255) NOT NULL,
				_Body varchar(255) NOT NULL,
				_SmsStatus varchar(255) NOT NULL,
				_NumMedia varchar(255) DEFAULT NULL,
				_Media JSON DEFAULT NULL,
				_contact_id varchar(255) NOT NULL,
				_conversation_id varchar(255) DEFAULT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_is_scheduled varchar(255) DEFAULT NULL,
				_scheduled_status varchar(255) DEFAULT NULL,
				_scheduled_datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// email campaigns table
		if (!TwilioCSV::table_exists($email_campaigns_table)) {
			$sql = "CREATE TABLE $email_campaigns_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_contact_id varchar(255) NOT NULL,
				_campaign_name varchar(255) DEFAULT NULL,
				_subject varchar(255) DEFAULT NULL,
				_body TEXT DEFAULT NULL,
				_first_follow_up_body TEXT DEFAULT NULL,
				_second_follow_up_body TEXT DEFAULT NULL,
				_third_follow_up_body TEXT DEFAULT NULL,
				_status varchar(255) NOT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_first_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_second_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_final_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// sms campaigns table
		if (!TwilioCSV::table_exists($sms_campaigns_table)) {
			$sql = "CREATE TABLE $sms_campaigns_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_contact_id varchar(255) NOT NULL,
				_campaign_name varchar(255) DEFAULT NULL,
				_body TEXT DEFAULT NULL,
				_first_follow_up_body TEXT DEFAULT NULL,
				_second_follow_up_body TEXT DEFAULT NULL,
				_third_follow_up_body TEXT DEFAULT NULL,
				_status varchar(255) NOT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_first_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_second_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_final_follow_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// logs table
		if (!TwilioCSV::table_exists($logs_table)) {
			$sql = "CREATE TABLE $logs_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_action varchar(255) NOT NULL,
				_message TEXT DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// schedule table
		if (!TwilioCSV::table_exists($schedule_table)) {
			$sql = "CREATE TABLE $schedule_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_contact_id varchar(255) NOT NULL,
				_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_action varchar(255) NOT NULL,
				_status varchar(255) DEFAULT 'Pending' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// programmable messages
		if (!TwilioCSV::table_exists($programmable_messages_table)) {
			$sql = "CREATE TABLE $programmable_messages_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_title varchar(255) NOT NULL,
				_body TEXT DEFAULT NULL,
				_type varchar(255) NOT NULL DEFAULT 'individual',
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// scheduled briefings
		if (!TwilioCSV::table_exists($scheduled_briefings_table)) {
			$sql = "CREATE TABLE $scheduled_briefings_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_title varchar(255) NOT NULL,
				_weblink varchar(255) DEFAULT NULL,
				_body TEXT DEFAULT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_scheduled datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// interviews
		if (!TwilioCSV::table_exists($interviews_table)) { // _contact_id, _disposition, _notes, _created, _updated, _question_answer_array
			$sql = "CREATE TABLE $interviews_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_contact_id mediumint(9) NOT NULL,
				_disposition varchar(255) NOT NULL,
				_notes TEXT DEFAULT NULL,
				_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				_question_answer_array TEXT DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// settings and options
		if (!TwilioCSV::table_exists($settings_options_table)) {
			$sql = "CREATE TABLE $settings_options_table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				_option varchar(255) NOT NULL,
				_value TEXT DEFAULT NULL,
				_user_id mediumint(9) DEFAULT NULL,
				_contact_id mediumint(9) DEFAULT NULL,
				_programmable_message_id mediumint(9) DEFAULT NULL,
				_scheduled_briefing_id mediumint(9) DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";
			dbDelta($sql);
		}

		// Create new page with slug 'recruiting'
		$page = get_page_by_path('recruiting');
		if (!$page) {
			$page_id = wp_insert_post(array(
				'post_title' => 'Recruiting Portal',
				'post_name' => 'recruiting',
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_author' => 1,
				'comment_status' => 'closed',
				'ping_status' => 'closed',
			));

			if ($page_id) {
				$setting_name = 'page';
				$setting_value = $page_id;
				$wpdb->insert($settings_options_table, array(
					'_option' => $setting_name,
					'_value' => $setting_value,
				));
			}
		}


	}

}
