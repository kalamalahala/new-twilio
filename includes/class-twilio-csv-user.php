<?php

// user class

use Twilio\Rest\Client;

class TwilioCSVUser {
    private $user_id;
    private $user_first_name;
    private $user_last_name;
    private $user_display_name;
    private $user_role;
    private $user_status;
    private $user_settings;
    private $sending_number;

    public function __construct($id = null) {
        if ($id) {
            $this->user_id = $id;
            $this->user_role = $this->get_user_role();
            $this->user_status = $this->get_user_status();
            $this->user_settings = $this->get_user_settings();
            $this->sending_number = $this->get_sending_number();
            $this->last_updated = $this->get_last_updated();
            $this->assigned_count = $this->get_assigned_count();

            $names = $this->name_array();
            $this->user_first_name = $names['first_name'];
            $this->user_last_name = $names['last_name'];
            $this->user_display_name = $names['display_name'];
            
        }
    }

    public function get_last_updated() {
        $id = $this->user_id;
        // get _updated from the contacts table where _user_id = $id, order by _updated DESC, limit 1
        global $wpdb;
        $table_name = TwilioCSVContact::table();
        $sql = "SELECT _updated FROM $table_name WHERE _user_id = $id ORDER BY _updated DESC LIMIT 1";
        $last_updated = $wpdb->get_var($sql);

        if (!$last_updated) {
            $last_updated = 'Never';
        } else {
            $last_updated = date('m/d/Y', strtotime($last_updated));
        }
        return $last_updated;
    }

    public function get_assigned_count() {
        $id = $this->user_id;
        // get count of id from the contacts table where _user_id = $id
        global $wpdb;
        $table_name = TwilioCSVContact::table();
        $sql = "SELECT COUNT(id) FROM $table_name WHERE _user_id = $id";
        $assigned_count = $wpdb->get_var($sql);
        
        return $assigned_count;
    }

    public function get_user_role() {
        $user = get_userdata($this->user_id);
        $user_roles = $user->roles;
        return $user_roles[0];
    }

    public function get_user_status() {
        // check user for meta_key = 'twilio_csv_user_status'
        $user_status = get_user_meta($this->user_id, 'twilio_csv_user_status', true);
        if (!$user_status) {
            $user_status = 'active';
            add_user_meta($this->user_id, 'twilio_csv_user_status', $user_status);
        }
        return $user_status;
    }

    public function get_user_settings() {
        $settings = new TwilioCSVSettings();
        $user_settings = $settings->db_get_user_settings($this->user_id);
        return $user_settings;
    }

    public function get_sending_number() {
        $settings = new TwilioCSVSettings();
        $sending_number = $settings->db_get_user_sending_number($this->user_id);
        return $sending_number;
    }

    public function get_user_details() {
        $user = get_userdata($this->user_id);
        $user_details = [
            'id' => $this->user_id,
            'name' => $user->display_name,
            'role' => $this->user_role,
            'status' => $this->user_status,
            'settings' => $this->user_settings,
            'sending_number' => $this->sending_number,
            'last_updated' => $this->last_updated,
            'assigned_count' => $this->assigned_count,
            'bulk-details' => get_userdata($this->user_id),
            'bulk-meta' => get_user_meta($this->user_id),
        ];
        return $user_details;
    }

    // Setters
    public function set_user_role($role) {
        $user = new WP_User($this->user_id);
        $user->set_role($role);
        $this->user_role = $this->get_user_role();
    }

    public function set_user_display_name($name) {
        $user = new WP_User($this->user_id);
        $user->display_name = $name;
        $user->save();
    }

    public function set_user_status($status) {
        update_user_meta($this->user_id, 'twilio_csv_user_status', $status);
        $this->user_status = $this->get_user_status();
    }

    public function set_user_settings(array $options) {
        $settings = new TwilioCSVSettings();
        foreach ($options as $key => $value) {
            $settings->db_set_user_settings($this->user_id, $key, $value);
        }
        $this->user_settings = $this->get_user_settings();
    }

    /**
     * name_array
     * 
     * Return a simple array containing the user's first name, last name, and display name.
     * If the user does not have a first name or last name, the display name will be used for both
     * the first name, and display name. Last name will be an empty string.
     * 
     * Array keys:
     * - first_name
     * - last_name
     * - display_name
     * 
     * @return array $name_array
     */
    public function name_array(): array {
        $wp_data = get_userdata($this->user_id);
        $wp_meta = get_user_meta($this->user_id);
        if (array_key_exists('first_name', $wp_meta) && array_key_exists('last_name', $wp_meta)) {
            $name_array = [
                'first_name' => $wp_meta['first_name'][0],
                'last_name' => $wp_meta['last_name'][0],
                'display_name' => $wp_data->display_name,
            ];
        } else {
            $name_array = [
                'first_name' => $wp_data->display_name,
                'last_name' => '',
                'display_name' => $wp_data->display_name,
            ];
        }

        return $name_array;
    }

    public function handle_admin_form_submit($post_data) {
        /**
         * {
         *         "select-user": "1",
         *         "select-role": "administrator",
         *         "select-status": "active",
         *         "select-sending-number": "+19999999999,
         *         "action": "twilio_csv_ajax_public",
         *         "nonce": "00ce577f93",
         *         "method": "update_user_details"
         *         }
         */

        $user_id = $post_data['select-user'];
        $user_role = $post_data['select-role'];
        $user_status = $post_data['select-status'];
        $sending_number = $post_data['select-sending-number'] ?? null;
        
        $this->user_id = $user_id;
        $this->set_user_role($user_role);
        $this->set_user_status($user_status);
        if ($sending_number) {
            $user_settings = [
                'sending_number' => $post_data['select-sending-number'],
            ];
            $this->set_user_settings($user_settings);
        }

        global $wpdb;
        $error = $wpdb->last_error;
        $query = $wpdb->last_query;
        $success = ($error == '') ? true : false;
        
        $response = [
            'role' => $this->user_role,
            'status' => $this->user_status,
            'sending_number' => $this->user_settings,
            'success' => $success,
            'error' => $error,
            'query' => $query,
        ];

        $response['message'] = ($success) ? "$this->user_display_name successfully updated." : "Error: $error";

        return $response;
    }
}