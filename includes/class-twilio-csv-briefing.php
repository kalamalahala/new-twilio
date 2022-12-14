<?php

/**
 * handles the Twilio CSV Briefing page
 */


class TwilioCSVBriefing {

    public $_title;
    public $_weblink;
    public $_body;
    public $_scheduled;
    public $_created;
    public $_updated;
    public $table;

    public function __construct($briefing_id = null) {
        if ($briefing_id) {
            $this->get_briefing($briefing_id);
        } else {
            $this->_title = '';
            $this->_weblink = '';
            $this->_body = '';
            $this->_scheduled = '';
            $this->_created = '';
            $this->_updated = '';
        }

        global $wpdb;
        $this->table = $wpdb->prefix . TWILIOCSV_SCHEDULED_BRIEFINGS_TABLE;
    }

    public function get_briefing($briefing_id) {
        global $wpdb;
        $table_name = self::table();
        $sql = "SELECT * FROM $table_name WHERE id = $briefing_id";
        $result = $wpdb->get_row($sql);
        if ($result) {
            $this->id = $result->id;
            $this->_title = $result->_title;
            $this->_weblink = $result->_weblink;
            $this->_body = $result->_body;
            $this->_scheduled = $result->_scheduled;
            $this->_created = $result->_created;
            $this->_updated = $result->_updated;

            return $this;
        }

        return false;
    }

    public function save($data = null) {
        global $wpdb;
        $table_name = self::table();

        $id = $data['id'] ?? null;
        $briefing = null;

        // if no $data['id'], creating new entry
        if (is_null($id)) {
            $_title = $data['_title'];
            $_weblink = $data['_weblink'];
            $_body = $data['_body'];
            $_scheduled = $data['_scheduled'];
            $_created = TwilioCSV::localized_date();
            $_updated = TwilioCSV::localized_date();
        } else if (isset($data['id']) && $data['id'] > 0) {
            $id = $data['id'];
            $briefing = new TwilioCSVBriefing();
            $briefing = $briefing->get_briefing($id);
            $briefing->id = $id;
            $_title = $data['_title'] ?? $briefing->_title;
            $_weblink = $data['_weblink'] ?? $briefing->_weblink;
            $_body = $data['_body'] ?? $briefing->_body;
            $_scheduled = $data['_scheduled'] ?? $briefing->_scheduled;
            $_created = $briefing->_created;
            $_updated = TwilioCSV::localized_date();
        }


        $data = array(
            '_title' => $_title,
            '_weblink' => $_weblink,
            '_body' => $_body,
            '_scheduled' => $_scheduled,
            '_created' => $_created,
            '_updated' => $_updated,
        );
        $format = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        );
        if ($briefing !== null) {
            $where = array('id' => $briefing->id);
            $where_format = array('%d');
            $result = $wpdb->update($table_name, $data, $where, $format, $where_format);

            return ($result > 0) ? true : false;

        } else {
            $result = $wpdb->insert($table_name, $data, $format);
            return ($result > 0) ? true : false;
        }
    }

    public function create_briefing($_title, $_weblink, $_body, $_scheduled) {
        $new_briefing = new TwilioCSVBriefing();
        $data['_title'] = $_title;
        $data['_weblink'] = $_weblink;
        $data['_body'] = $_body;
        $scheduled = date('Y-m-d H:i:s', strtotime($_scheduled));
        $data['_scheduled'] = $scheduled;
        $data['_created'] = TwilioCSV::localized_date();
        $data['_updated'] = TwilioCSV::localized_date();

        $result = $new_briefing->save($data);

        global $wpdb;
        $payload['success'] = $result;
        $payload['id'] = $wpdb->insert_id;
        $payload['message'] = ($payload['success']) ? 'Briefing created successfully.' : 'Briefing creation failed.';
        return $payload;
    }

    public function delete() {
        global $wpdb;
        $table_name = self::table();
        $where = array('id' => $this->id);
        $where_format = array('%d');
        $deletion = $wpdb->delete($table_name, $where, $where_format);

        return ($deletion > 0) ? true : false;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_title() {
        return $this->_title;
    }

    public function set_title($title) {
        // handle special characters
        $title = htmlspecialchars($title);
        $this->_title = $title;
    }

    public function get_weblink() {
        return $this->_weblink;
    }

    public function set_weblink($weblink) {
        // handle special characters
        $weblink = htmlspecialchars($weblink);
        $this->_weblink = $weblink;
    }

    public function get_body() {
        return $this->_body;
    }

    public function set_body($body) {
        // handle special characters
        $body = htmlspecialchars($body);
        $this->_body = $body;
    }

    public function get_scheduled() {
        return $this->_scheduled;
    }

    public function set_scheduled($scheduled) {
        $this->_scheduled = $scheduled;
    }

    public function get_created() {
        return $this->_created;
    }

    public function set_created($created = null) {
        if ($created) {
            $this->_created = $created;
        } else {
            $this->_created = TwilioCSV::localized_date();
        }
        $this->_created = $created;
    }

    public function get_updated() {
        return $this->_updated;
    }

    public function set_updated($updated = null) {
        if ($updated) {
            $this->_updated = $updated;
        } else {
            $this->_updated = TwilioCSV::localized_date();
        }
        $this->_updated = $updated;
    }

    public static function table() {
        global $wpdb;
        $table = $wpdb->prefix . TWILIOCSV_SCHEDULED_BRIEFINGS_TABLE;
        return $table;
    }

    public static function get_all_briefings() {
        global $wpdb;
        $table_name = self::table();
        $sql = "SELECT * FROM $table_name ORDER BY _scheduled DESC";
        $results = $wpdb->get_results($sql);
        return $results;
    }

    public static function get_future_briefings() {
        global $wpdb;
        $table_name = self::table();
        $now = TwilioCSV::localized_date();
        $sql = "SELECT * FROM $table_name WHERE _scheduled > '$now' ORDER BY _scheduled ASC";
        $results = $wpdb->get_results($sql);
        return $results;
    }
}