<?php

/** class file to handle Programmable Messages
 * create, read, update, and delete messages
 */

 /**
  * TwilioCSVProgrammableMessages
  *
  * @var string $table
  * table columns:
  * id, _title, _body, _created, _updated
  * auto increment id, string title, string body, datetime created, datetime updated
  */
 class TwilioCSVProgrammableMessages {

    public $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . TWILIOCSV_PROGRAMMABLE_MESSAGES_TABLE;
    }

    public static function table() {
        global $wpdb;
        return $wpdb->prefix . TWILIOCSV_PROGRAMMABLE_MESSAGES_TABLE;
    }

    public function create_message($title, $body, $type) {

        global $wpdb;

        // prepare title, body, and TwilioCSV::localized_date();
        $title = sanitize_text_field($title);
        $body = sanitize_text_field($body);
        $type = sanitize_text_field($type);
        $date = TwilioCSV::localized_date();

        $query = $wpdb->prepare(
            "INSERT INTO $this->table (_title, _body, _type, _created, _updated) VALUES (%s, %s, %s, %s, %s)",
            $title,
            $body,
            $type,
            $date,
            $date
        );

        $wpdb->query($query);

        if ($wpdb->insert_id) {
            return $wpdb->insert_id;
        } else {
            return $wpdb->last_error;
        }

        return $wpdb->insert_id;
    }

    public function get_message($id) {

        global $wpdb;

        $message = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $this->table WHERE id = %d",
                $id
            )
        );

        return $message;
    }

    public function get_messages() {

        global $wpdb;

        $messages = $wpdb->get_results(
            "SELECT * FROM $this->table"
        );

        return $messages;
    }

    public function update_message($id, $title, $body, $type) {

        global $wpdb;

        $update = $wpdb->update(
            $this->table,
            array(
                '_title' => $title,
                '_body' => $body,
                '_type' => $type,
            ),
            array(
                'id' => $id
            )
        );

        $payload = [];

        if ($update) {
            $payload['success'] = true;
            $payload['message'] = 'Message updated successfully';
        } else {
            $payload['success'] = false;
            $payload['message'] = 'Message failed to update';
        }

        // error_log("updated message $id");
        // error_log("title: $title");
        // error_log("body: $body");
        // error_log($wpdb->last_query);
        // error_log($wpdb->last_error);
        return $payload;
    }

    public function delete_message($id) {

        global $wpdb;

        $wpdb->delete(
            $this->table,
            array(
                'id' => $id
            )
        );

        $payload = [];

        if ($wpdb->last_error) {
            $payload['success'] = false;
            $payload['message'] = 'Message failed to delete';
        } else {
            $payload['success'] = true;
            $payload['message'] = 'Message deleted successfully';
        }
        return $payload;
    }

    public static function get_programmable_messages(string $type = 'all') {

        global $wpdb;

        $selector = ($type === 'all') ? '' : "WHERE _type = '$type'";

        $messages = $wpdb->get_results(
            "SELECT * FROM " . TwilioCSVProgrammableMessages::table() . " $selector",
        );

        $programmable_messages = array();

        foreach ($messages as $message) {
            $programmable_messages[] = array(
                'id' => $message->id,
                '_title' => $message->_title,
                '_body' => $message->_body,
            );
        }

        return $programmable_messages;
    }
    
 }