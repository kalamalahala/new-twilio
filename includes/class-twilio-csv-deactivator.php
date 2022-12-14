<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 */
class TwilioCSV_Deactivator {

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
	 * Drop the plugin's database tables.
	 *
	 * Mirror the schema in Twilio_Csv_Activator::activate() and drop the tables
	 * when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		global $wpdb;
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


		// $wpdb->query( "DROP TABLE IF EXISTS $contacts_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $recruits_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $conversations_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $messages_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $email_campaigns_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $sms_campaigns_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $logs_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $schedule_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $programmable_messages_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $scheduled_briefings_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $interviews_table" );
		// $wpdb->query( "DROP TABLE IF EXISTS $settings_options_table" );

		// Delete the page that was created for the plugin slug twilio-csv
		$page = get_page_by_path( 'recruiting' );
		if ( $page ) {
			wp_delete_post( $page->ID, true );
		}

		$page = TwilioCSVSettings::_db_get_option('page');
		if ( $page ) {
			wp_delete_post( $page, true );
		}
		

	}

}
