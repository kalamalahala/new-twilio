<?php

/**
 * TwilioCSVRecruit
 * 
 * This class is used to create a new recruit object.
 */
class TwilioCSVRecruit
{

    public $id;
    public $_contact_id;
    public $_interview_date;
    public $_updated;
    public $_time_in_xcel;
    public $_ple_percent;
    public $_ple_completion_date;
    public $_prep_percent;
    public $_sim_percent;
    public $_prepared_to_pass;
    public $_interview_questions;
    public $_first_name;
    public $_last_name;
    public $_phone;
    public $_email;
    public $_source;

    public function __construct($id = null)
    {
        if ($id) {
            $this->id = $id;
            $this->_get();

            // return self::_get_contact_details($this->_contact_id);
        }
    }

    public static function table()
    {
        global $wpdb;
        return $wpdb->prefix . TWILIOCSV_RECRUITS_TABLE;
    }

    public function _get()
    {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT * FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $this->id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            $this->_contact_id = $result->_contact_id;
            $this->_interview_date = $result->_interview_date;
            $this->_updated = $result->_updated;
            $this->_time_in_xcel = $result->_time_in_xcel;
            $this->_ple_percent = $result->_ple_percent;
            $this->_ple_completion_date = $result->_ple_completion_date;
            $this->_prep_percent = $result->_prep_percent;
            $this->_sim_percent = $result->_sim_percent;
            $this->_prepared_to_pass = $result->_prepared_to_pass;
            $this->_interview_questions = $result->_interview_questions;

            $contact_details = self::_get_contact_details($this->_contact_id);
            $this->_first_name = $contact_details->_first_name;
            $this->_last_name = $contact_details->_last_name;
            $this->_phone = $contact_details->_phone;
            $this->_email = $contact_details->_email;
            $this->_source = $contact_details->_source;
            return $this;
        } else {
            error_log('No recruit found with ID: ' . $this->id);
            return false;
        }
    }

    public static function _get_contact_details($contact_id)
    {
        global $wpdb;
        $table = TwilioCSVContact::table();
        $sql = "SELECT * FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $contact_id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            return $result;
        }
    }

    public static function _get_all_recruits() {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT * FROM $table";
        $result = $wpdb->get_results($sql);
        
        // run _get on each recruit to get contact details
        $recruits = [];
        foreach ($result as $recruit) {
            $recruit = new TwilioCSVRecruit($recruit->id);
            $recruits[] = $recruit;
        }

        // return array of recruit objects
        return $recruits;
    }

    public static function _update_recruit($form_data) {
        // update _updated
        self::_set_updated(self::_get_recruit_id($form_data['id']));
        $recruit_id = $form_data['id'];
        // $recruit_id = self::_get_recruit_id($form_data['id']);
        
        // update _time_in_xcel and other columns if they are set in $form_data
        if (isset($form_data['time_in_xcel'])) {
            self::_set_time_in_xcel($recruit_id, $form_data['time_in_xcel']);
        }
        if (isset($form_data['ple_percent'])) {
            self::_set_ple_percent($recruit_id, $form_data['ple_percent']);
        }
        if (isset($form_data['ple_completion_date'])) {
            self::_set_ple_completion_date($recruit_id, $form_data['ple_completion_date']);
        }
        if (isset($form_data['prep_percent'])) {
            self::_set_prep_percent($recruit_id, $form_data['prep_percent']);
        }
        if (isset($form_data['sim_percent'])) {
            self::_set_sim_percent($recruit_id, $form_data['sim_percent']);
        }
        if (isset($form_data['prepared_to_pass'])) {
            self::_set_prepared_to_pass($recruit_id, $form_data['prepared_to_pass']);
        }
        if (isset($form_data['questionAnswerArray'])) {
            self::_set_interview_questions($recruit_id, $form_data['questionAnswerArray']);
        }
    }

    public static function _delete_recruit($id) {
        global $wpdb;
        $table = self::table();
        $contact_id = self::_get_contact_id($id);
        $sql = "DELETE FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            TwilioCSVContact::_set_disposition('Deleted Recruit', $contact_id);
            return array("Deleted recruit with ID: $id, attempted to update disposition of contact with ID: $contact_id");
        } else {
            return array('error' => "Error deleting recruit with ID: $id. $wpdb->last_error | $wpdb->last_query");
        }
    }

    public static function _get_recruit_id($contact_id) {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT id FROM $table WHERE _contact_id = %d";
        $sql = $wpdb->prepare($sql, $contact_id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            return $result->id;
        }
    }

    public static function _get_contact_id($recruit_id) {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT _contact_id FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $recruit_id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            return $result->_contact_id;
        }
    }

    public static function _set_updated($id) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _updated = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, TwilioCSV::localized_date(), $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_time_in_xcel($id, $time_in_xcel) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _time_in_xcel = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $time_in_xcel, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_ple_percent($id, $ple_percent) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _ple_percent = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $ple_percent, $id);
        $result = $wpdb->query($sql);

        // triggers at 25, 50, 75, 100
        if ($result) {
            if ($ple_percent == 25 || $ple_percent == 50 || $ple_percent == 75 || $ple_percent == 100) {
                // self::_send_ple_percent_email($id, $ple_percent);
            }
            if ($ple_percent == 100) {
                self::_set_ple_completion_date($id, TwilioCSV::localized_date());
            }
            return true;
        } else {
            return false;
        }


        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_ple_completion_date($id, $ple_completion_date) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _ple_completion_date = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $ple_completion_date, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_prep_percent($id, $prep_percent) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _prep_percent = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $prep_percent, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_sim_percent($id, $sim_percent) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _sim_percent = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $sim_percent, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_prepared_to_pass($id, $prepared_to_pass) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _prepared_to_pass = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $prepared_to_pass, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _set_interview_questions($id, $questionAnswerArray) {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _interview_questions = %s WHERE id = %d";
        $sql = $wpdb->prepare($sql, $questionAnswerArray, $id);
        $result = $wpdb->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function _get_recruit($id) {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT * FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            return $result;
        }
    }

    public static function _get_recruits() {
        global $wpdb;
        $recruits_table = self::table();
        $contacts_table = TwilioCSVContact::table();
        // get all recruits and their contact info
        $sql = "SELECT *, r.id AS _recruit_id FROM $recruits_table AS r LEFT JOIN $contacts_table AS c ON r._contact_id = c.id
                ORDER BY r._updated DESC";

        $result = $wpdb->get_results($sql);
        if ($result) {
            return $result;
        }
    }

    public static function _get_recruits_by_contact_id($contact_id) {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT * FROM $table WHERE _contact_id = %d";
        $sql = $wpdb->prepare($sql, $contact_id);
        $result = $wpdb->get_results($sql);
        if ($result) {
            return $result;
        }
    }

    public static function _get_interview_json_as_array($id) {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT _interview_questions FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $id);
        $result = $wpdb->get_row($sql);
        if ($result) {
            return json_decode($result->_interview_questions, true);
        }
    }
}
