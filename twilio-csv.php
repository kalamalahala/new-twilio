<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://thejohnson.group/
 * @since             1.1.75
 * @package           TwilioCSV
 *
 * @wordpress-plugin
 * Plugin Name:       Twilio CSV Upload
 * Plugin URI:        https://thejohnson.group/
 * Description:       Recruiting management plugin for uploading contacts and contacting them via Twilio.
 * Version:           1.2.5
 * Author:            Tyler Karle
 * Author URI:        https://thejohnson.group/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twilio-csv
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TWILIOCSV_VERSION', '1.2.5' );
define( 'TWILIOCSV_CONTACTS_TABLE', 'twiliocsv_contacts' );
define( 'TWILIOCSV_RECRUITS_TABLE', 'twiliocsv_recruits' );
define( 'TWILIOCSV_CONVERSATIONS_TABLE', 'twiliocsv_conversations' );
define( 'TWILIOCSV_MESSAGES_TABLE', 'twiliocsv_messages' );
define( 'TWILIOCSV_EMAIL_CAMPAIGNS_TABLE', 'twiliocsv_email_campaigns' );
define( 'TWILIOCSV_SMS_CAMPAIGNS_TABLE', 'twiliocsv_sms_campaigns' );
define( 'TWILIOCSV_LOGS_TABLE', 'twiliocsv_logs' );
define( 'TWILIOCSV_SCHEDULE_TABLE', 'twiliocsv_schedule' );
define( 'TWILIOCSV_PROGRAMMABLE_MESSAGES_TABLE', 'twiliocsv_programmable_messages' );
define( 'TWILIOCSV_SCHEDULED_BRIEFINGS_TABLE', 'twiliocsv_scheduled_briefings' );
define( 'TWILIOCSV_INTERVIEWS_TABLE', 'twiliocsv_interviews' );
define( 'TWILIOCSV_SETTINGS_OPTIONS_TABLE', 'twiliocsv_settings_options' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twilio-csv-activator.php
 */
function activate_TwilioCSV() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twilio-csv-activator.php';
	TwilioCSV_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twilio-csv-deactivator.php
 */
function deactivate_TwilioCSV() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twilio-csv-deactivator.php';
	TwilioCSV_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_TwilioCSV' );
register_deactivation_hook( __FILE__, 'deactivate_TwilioCSV' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-twilio-csv.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.75
 */
function run_TwilioCSV() {

	$plugin = new TwilioCSV();
	$plugin->run();

}
run_TwilioCSV();