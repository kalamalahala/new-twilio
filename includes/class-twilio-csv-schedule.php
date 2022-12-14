<?php

/**
 * TwilioCSVSchedule
 * 
 * This class handles all the scheduling of messages and emails and callbacks.
 * 
 * @since      1.2.5
 * @package    TwilioCSV
 * @subpackage TwilioCSV/includes
 * @author     Tyler Karle <solo.driver.bob@gmail.com>
 * 
 * @var $schedule_table
 * @var $contacts_table
 * @var $recruits_table
 * 
 */
class TwilioCSVSchedule {

    public $schedule_table;
    public $contacts_table;

    public function __construct() {

        global $wpdb;
        $this->schedule_table = $wpdb->prefix . TWILIOCSV_SCHEDULE_TABLE;
        $this->contacts_table = $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
    }
    
    // Static getters
    public static function _table() {
        global $wpdb;
        return $wpdb->prefix . TWILIOCSV_SCHEDULE_TABLE;
    }

    public static function _get_schedule($id) {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE id = $id";
        $result = $wpdb->get_row($query);
        return $result;
    }

    public static function _get_schedules() {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table";
        $result = $wpdb->get_results($query);
        return $result;
    }

    public static function _get_schedules_by_action($type) {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _action = '$type'";
        $result = $wpdb->get_results($query);
        return $result;
    }

    public static function _get_scheduled_call_backs() {
        global $wpdb;
        $table = self::_table();
        $contacts_table = TwilioCSVContact::table();
        // Join the contacts table to get the contact information
        $query = "SELECT *,
                    $table.id _schedule_id,
                    $table._status _schedule_status,
                    $contacts_table.id _contact_id,
                    $contacts_table._user_id _user_id
                    FROM $table
                    LEFT JOIN $contacts_table
                    ON $table._contact_id = $contacts_table.id
                    WHERE _action = 'Call Back'";
        $result = $wpdb->get_results($query);
        return $result;
    }

    /**
     * get_schedules_by_date_range
     * 
     * Return all schedules between two dates, inclusive.
     * If $start is not provided, it will default to the beginning of time.
     * If $end is not provided, it will default to the end of time.
     * 
     * @param string $start
     * @param string $end
     *
     * @return object $result
     */
    public static function _get_schedules_by_date_range($start = false, $end = false): object {
        if (!$start) {
            $start = '1970-01-01 00:00:00';
        }
        if (!$end) {
            $end = '2038-01-19 03:14:07';
        }

        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _date BETWEEN '$start' AND '$end'";
        $result = $wpdb->get_results($query, OBJECT);
        return $result;

    }

    /**
     * get_schedules_by_date_range_and_action
     * 
     * Return all schedules between two dates, inclusive, and of a specific action type.
     * If $start is not provided, it will default to the beginning of time.
     * If $end is not provided, it will default to the end of time.
     * 
     * @param string $start
     * @param string $end
     * @param string $action
     *
     * @return object $result
     */
    public static function _get_schedules_by_date_range_and_action($action, $start = false, $end = false) {
        if (!$start) {
            $start = '1970-01-01 00:00:00';
        }
        if (!$end) {
            $end = '2038-01-19 03:14:07';
        }

        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _date >= '$start' AND _date <= '$end' AND _action = '$action'";
        $result = $wpdb->get_results($query);

        error_log(print_r($result, true));
        error_log($wpdb->last_query);
        error_log($wpdb->last_error);
        return $result;
    }

    public static function _get_schedules_by_contact_id($id) {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _contact_id = $id";
        $result = $wpdb->get_results($query);
        return $result;
    }


    public static function _get_schedules_by_contact_id_and_action($id, $action) {
        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _contact_id = $id AND _action = '$action'";
        $result = $wpdb->get_results($query);
        return $result;
    }


    public static function _get_schedules_by_contact_id_and_date_range($id, $start = false, $end = false) {
        if (!$start) {
            $start = '1970-01-01 00:00:00';
        }
        if (!$end) {
            $end = '2038-01-19 03:14:07';
        }

        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _contact_id = $id AND _date BETWEEN '$start' AND '$end'";
        $result = $wpdb->get_results($query);
        return $result;
    }

    public static function _get_schedules_by_contact_id_and_date_range_and_action($id, $action, $start = false, $end = false) {
        if (!$start) {
            $start = '1970-01-01 00:00:00';
        }
        if (!$end) {
            $end = '2038-01-19 03:14:07';
        }

        global $wpdb;
        $table = self::_table();
        $query = "SELECT * FROM $table WHERE _contact_id = $id AND _date BETWEEN '$start' AND '$end' AND _action = '$action'";
        $result = $wpdb->get_results($query);
        return $result;
    }

    public static function _create_scheduled_item($contact_id, $action, $date) {
        global $wpdb;
        $table = self::_table();
        // format date by timezone set in options
        // $timezone = TwilioCSV::timezone();
        // $date = new DateTime($date, new DateTimeZone($timezone));
        // $date = $date->format('Y-m-d H:i:s');

        $date = date('Y-m-d H:i:s', strtotime($date));

        // prepare query
        $query = $wpdb->prepare(
            "INSERT INTO $table (_contact_id, _action, _date) VALUES (%d, %s, %s)",
            $contact_id,
            $action,
            $date
        );
        // execute query
        $result = $wpdb->query($query);

        // update disposition based on action
        switch ($action) {
            case 'Call Back':
                $disposition = 'Scheduled Callback';
                TwilioCSVContact::_set_disposition($disposition, $contact_id);
                break;
        }
        
        if ($result) {
            return true;
        } else {
            return false;
        }
        
    }

    public static function _delete_scheduled_item($id): array {
        global $wpdb;
        $table = self::_table();
        $result = [];
        $delete = $wpdb->delete($table, array('id' => $id), array('%d'));
        if ($delete) {
            $result['success'] = true;
            $result['message'] = 'Scheduled item deleted.';
            $result['query'] = $wpdb->last_query;
        } else {
            $result['success'] = false;
            $result['message'] = 'Scheduled item could not be deleted.';
            $result['query'] = $wpdb->last_query;
            $result['error'] = $wpdb->last_error;
        }

        return $result;
    }


    /**
     * delete_scheduled_items
     * 
     * Delete all scheduled items by their id, or truncate the table.
     * 
     * Must set $all_items AND an empty $schedule_item_ids array to truncate the table.
     *
     * @param array $schedule_item_ids
     * @param boolean $all_items
     * @return array|boolean
     */ 
    public function delete_scheduled_items(array $schedule_item_ids, bool $all_items = false): array {
        global $wpdb;
        $table = self::_table();
        $results = [];
        
        if (empty($schedule_item_ids) && $all_items) {
            $results['query_result'] = $wpdb->query("TRUNCATE TABLE $table");
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            return $results;
        } else {
            $results['query_result'] = $wpdb->delete($table, array('id' => $schedule_item_ids));
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            return $results;
        }

        $results['error'] = "delete_scheduled_items() called improperly. Check the documentation.";
        return $results;
    }

    /**
     * _delete_scheduled_items
     * 
     * Static version of delete_scheduled_items()
     *
     * @param array $schedule_item_ids
     * @param boolean $all_items
     * @return array
     */
    public static function _delete_scheduled_items(array $schedule_item_ids, bool $all_items = false): array {
        global $wpdb;
        $table = self::_table();
        $results = [];
        
        if (empty($schedule_item_ids) && $all_items) {
            $results['query_result'] = $wpdb->query("TRUNCATE TABLE $table");
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            return $results;
        } else {
            $results['query_result'] = $wpdb->delete($table, array('id' => $schedule_item_ids));
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            return $results;
        }

        $results['error'] = "delete_scheduled_items() called improperly. Check the documentation.";
        return $results;
    }

    public function delete_all_schedule_items_by_contact_id(int $contact_id) {
        global $wpdb;
        $table = self::_table();
        $results = [];
        $results['query_result'] = $wpdb->delete($table, array('_contact_id' => $contact_id));
        $results['query'] = $wpdb->last_query;
        $results['error'] = $wpdb->last_error;

        return $results;
    }

    public static function _delete_all_schedule_items_by_contact_id(int $contact_id) {
        global $wpdb;
        $table = self::_table();
        $results = [];
        $results['query_result'] = $wpdb->delete($table, array('_contact_id' => $contact_id));
        $results['query'] = $wpdb->last_query;
        $results['error'] = $wpdb->last_error;

        return $results;
    }

    public function set_schedule_status(int $id, string $status) {
        global $wpdb;
        $table = $this->schedule_table;
        $results = [];
        $results['query_result'] = $wpdb->update($table, array('_status' => $status), array('id' => $id));
        $results['query'] = $wpdb->last_query;
        $results['error'] = $wpdb->last_error;

        return $results;
    }

    public static function _set_schedule_status(int $id, string $status) {
        global $wpdb;
        $table = self::_table();
        $results = [];
        $results['query_result'] = $wpdb->update($table, array('_status' => $status), array('id' => $id));
        $results['query'] = $wpdb->last_query;
        $results['error'] = $wpdb->last_error;

        return $results;
    }






}