<?php

/** class handler for initial interviews */

class TwilioCSVInterview
{

    /**
     * id
     * _contact_id
     * _disposition
     * _notes
     * _created
     * _updated
     * _question_answer_array
     */

    public $id;
    public $_contact_id;
    public $_disposition;
    public $_notes;
    public $_created;
    public $_updated;
    public $_question_answer_array;

    public function __construct($id = null)
    {
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    public function load()
    {
        global $wpdb;
        $table = $wpdb->prefix . TWILIOCSV_INTERVIEWS_TABLE;
        $query = "SELECT * FROM $table WHERE id = $this->id";
        $result = $wpdb->get_row($query);
        $this->_contact_id = $result->_contact_id;
        $this->_disposition = $result->_disposition;
        $this->_notes = $result->_notes;
        $this->_created = $result->_created;
        $this->_updated = $result->_updated;
        $this->_question_answer_array = $result->_question_answer_array;

        return $this;
    }

    public static function table() {
        global $wpdb;
        return $wpdb->prefix . TWILIOCSV_INTERVIEWS_TABLE;
    }

    public static function create( array $data ) {
        // $data keys are the column names of the table
        global $wpdb;
        $table = self::table();

        $contact_id = $data['_contact_id'];
        $disposition = $data['_disposition'];
        $created = $data['_created'];
        $question_answer_array = $data['_question_answer_array'] ?? null;

        // prepare
        $query = "INSERT INTO $table (_contact_id, _disposition, _created, _question_answer_array) VALUES (%d, %s, %s, %s)";
        $query = $wpdb->prepare($query, $contact_id, $disposition, $created, $question_answer_array);

        // execute
        $result = $wpdb->query($query);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Interview created';
            $response['id'] = $wpdb->insert_id;

            return $response;

        } else {
            $response['success'] = false;
            $response['message'] = 'Interview not created';

            return $response;
        }

    }

}
