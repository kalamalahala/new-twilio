<?php

/**
 * Twilio CSV Settings
 * 
 * Handles the settings page for the plugin and
 * option/option group management.
 * 
 * @package TwilioCSV
 * @subpackage TwilioCSV\TwilioCSVSettings
 * @since 1.2
 * 
 * @see https://codex.wordpress.org/Creating_Options_Pages
 */

class TwilioCSVSettings
{

    private $table;

    public function __construct()
    {
        $this->table = self::_table();
    }

    public function table() {
        return $this->table;
    }

    public static function _table() {
        global $wpdb;
        $prefix = $wpdb->prefix;
        return (defined('TWILIOCSV_SETTINGS_OPTIONS_TABLE')) ? $prefix . TWILIOCSV_SETTINGS_OPTIONS_TABLE : $prefix . 'twiliocsv_settings_options';
    }

    
	public static function _db_get_option(string $option_name): string|bool
	{
		global $wpdb;
        $table = self::_table();
        $query = "SELECT _value FROM $table WHERE _option = %s";
        $result = $wpdb->get_var($wpdb->prepare($query, $option_name));

        return ($result) ? $result : false;
	}

    public function db_get_option(string $option_name): string|bool
    {
        global $wpdb;
        $table = $this->table();
        $query = "SELECT _value FROM $table WHERE _option = %s";
        $result = $wpdb->get_var($wpdb->prepare($query, $option_name));

        return ($result) ? $result : false;
    }

    public function db_get_user_settings(int $user_id): string|bool
    {
        global $wpdb;
        $table = $this->table();
        $query = "SELECT _value FROM $table WHERE _user_id = %d ORDER BY id DESC LIMIT 1";
        $result = $wpdb->get_var($wpdb->prepare($query, $user_id));

        return ($result) ? $result : false;
    }

    public function db_get_user_sending_number(int $user_id): string|bool
    {
        global $wpdb;
        $table = $this->table();
        $query = "SELECT _value FROM $table WHERE _user_id = %d AND _option = %s ORDER BY id DESC LIMIT 1";
        $result = $wpdb->get_var($wpdb->prepare($query, $user_id, 'sending_number'));

        return ($result) ? $result : false;
    }

    public static function _db_get_user_settings(int $user_id, string $option_name): string|bool
    {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT _value FROM $table WHERE _option = %s AND _user_id = %d";
        $result = $wpdb->get_var($wpdb->prepare($query, $option_name, $user_id));

        return ($result) ? $result : false;
    }

    public function db_set_user_settings(int $user_id, string $option_name, string $option_value): bool
    {
        global $wpdb;
        $table = $this->table();
        $query = "INSERT INTO $table (_user_id, _option, _value) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE _value = %s";
        $result = $wpdb->query($wpdb->prepare($query, $user_id, $option_name, $option_value, $option_value));

        return ($result) ? true : false;
    }

    public static function _db_set_user_settings(int $user_id, array $options): bool
    {
        global $wpdb;
        $table = self::_table();

        // $options is a key/value array
        foreach ($options as $option_name => $option_value) {
            $query = "INSERT INTO $table (_user_id, _option, _value) VALUES (%d, %s, %s) ON DUPLICATE KEY UPDATE _value = %s";
            $result = $wpdb->query($wpdb->prepare($query, $user_id, $option_name, $option_value, $option_value));
        }

        return ($result) ? true : false;
    }

    public function list_messaging_services(): array
    {
        $services = TwilioCSV::service_information();

        return $services;
    }

    public function list_sending_numbers(string $msid): array
    {
        $numbers = TwilioCSV::sending_numbers($msid);

        return $numbers;
    }

    public function register_settings(string $option_group, string $setting_name): void
    {
        register_setting($option_group, $setting_name);
    }

    public function option_group(array $args): void
    {
        wp_parse_args($args, array(
            'option_group' => '',
            'option_name' => '',
            'sanitize_callback' => '',
        ));

        register_setting(
            $args['option_group'],
            $args['option_name'],
            $args['sanitize_callback']
        );
    }

    public function settings_section(array $args): void
    {
        wp_parse_args($args, array(
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

        // do_action('qm/debug', $args);
    }

    public function settings_field(array $args): void
    {
        wp_parse_args($args, array(
            'id' => '',
            'title' => '',
            'callback' => '',
            'page' => '',
            'section' => '',
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
    }

    public function sanitize_callback($input): array
    {
        $new_input = array();

        if (isset($input['twilio_account_sid'])) {
            $new_input['twilio_account_sid'] = sanitize_text_field($input['twilio_account_sid']);
        }

        if (isset($input['twilio_auth_token'])) {
            $new_input['twilio_auth_token'] = sanitize_text_field($input['twilio_auth_token']);
        }

        if (isset($input['twilio_phone_number'])) {
            $new_input['twilio_phone_number'] = sanitize_text_field($input['twilio_phone_number']);
        }

        return $new_input;
    }

    public function settings_section_callback($args): void
    {
        switch ($args['id']) {
            case 'twilio_settings':
                echo __('Please enter your Twilio account information below.', 'twilio-csv');
                break;
            case 'sendgrid_settings':
                echo __('Please enter your SendGrid account information below.', 'twilio-csv');
                break;
            case 'timezone_settings':
                echo __('Please select your timezone below.', 'twilio-csv');
                break;
        }
        echo '<hr />';
    }

    public function settings_field_callback($args)
    {

        $option_value = (get_option($args['option_name'], null)) ? get_option($args['option_name'], null) : [''];
        if (isset($option_value[$args['id']])) {
            $value = $option_value[$args['id']];
        } else {
            $value = '';
        }

        if ($args['option_name'] == 'twilio_messaging_service_sid') {
            $services = TwilioCSV::service_information(); // Array of: sid, friendlyName, inboundRequestUrl
            if (!is_array($services)) {
                printf(
                    '<div class="alert alert-danger" role="alert">%s</div>',
                    __('There was an error retrieving your Messaging Services. Please check your Twilio account SID and auth token.', 'twilio-csv')
                );
                return;
            }

            if (in_array('error', $services)) { printf(
                    '<div class="alert alert-danger" role="alert">%s</div>',
                    __($services['message'], 'twilio-csv')
                );
                return;
            }

            // var_dump($services);
            
        
            // create a select box with the services as options
            // value = sid
            // text = friendlyName
            // selected = $value
            printf(
                '<select name="%s[%s]" id="%s">',
                $args['option_name'],
                $args['id'],
                $args['id']
            );
            printf(
                '<option value="">%s</option>',
                __('Select a Messaging Service', 'twilio-csv')
            );
            foreach ($services as $service) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    $service['sid'],
                    selected($service['sid'], $value, false),
                    $service['friendlyName']
                );
            }

            printf(
                '</select>'
            );

            printf(
                '<small class="form-text text-muted description">%s</small>',
                __('Select the Twilio Messaging Service you would like to use.', 'twilio-csv')
            );

            printf(
                '<div><a href="%s" target="_blank">%s</a></div>',
                'https://www.twilio.com/console/sms/services',
                __('Create a new Messaging Service', 'twilio-csv')
            );

            foreach ($services as $service) {
                if ($service['sid'] == $value) {
                    printf(
                        '<div><small class="form-text %s description">%s</small></div>',
                        (($service['inboundRequestUrl'] == '') || (!str_contains($service['inboundRequestUrl'], 'wp-json'))) ? 'text-danger' : 'text-success',
                        ((str_contains($service['inboundRequestUrl'], 'wp-json')) ? __('Inbound Request URL is set to: ', 'twilio-csv') . $service['inboundRequestUrl'] : __('Inbound Request URL is not set.', 'twilio-csv'))
                    );
                    // echo $service['inboundRequestUrl'];
                }
            }
            return;
        }

        printf(
            '<input type="%4$s" id="%1$s" name="%2$s[%1$s]" value="%3$s" />',
            $args['id'],
            $args['option_name'],
            $value,
            $args['type']
        );
        printf(
            '<small class="form-text text-muted description">%s</small>',
            $args['description']
        );
    }

    public function specify_timezone_setting_field($args): void
    {

        // Render a select box with all the timezones
        // Default to selected timezone if set, otherwise default to UTC
        $option_value = (get_option('twilio_csv_timezone', null)) ? get_option('twilio_csv_timezone', null) : [''];
        if (isset($option_value['twilio_csv_timezone'])) {
            $value = $option_value['twilio_csv_timezone'];
        } else {
            $value = 'UTC';
        }
        printf(
            '<select id="%1$s" name="%2$s[%1$s]">',
            $args['id'],
            $args['option_name']
        );
        foreach (timezone_identifiers_list() as $timezone) {
            printf(
                '<option value="%s" %s>%s</option>',
                $timezone,
                selected($value, $timezone, false),
                $timezone
            );
        }
        printf(
            '</select>'
        );
        printf(
            '<small class="form-text text-muted description">%s</small>',
            __('Select your local time zone.', 'twilio-csv')
        );
    }


    // This doesn't do anything yet/ever TODO - remove?
    public function twilio_csv_show_admin_bar_setting_field($args): void
    {
        // Render a checkbox to show or hide the admin bar based on the TwilioCSV Roles
        $option_value = (get_option('twilio_csv_show_admin_bar', null)) ? get_option('twilio_csv_show_admin_bar', null) : [''];
        if (isset($option_value['twilio_csv_show_admin_bar'])) {
            $value = $option_value['twilio_csv_show_admin_bar'];
        } else {
            $value = '0';
        }

        printf(
            '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1" %3$s />',
            $args['id'],
            __($args['option_name']),
            checked($value, '1', false)
        );
    }
}
