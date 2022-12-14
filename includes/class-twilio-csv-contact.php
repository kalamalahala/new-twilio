<?php

/**
 * Class Twilio_CSV_Contact
 * 
 * Class to handle TwilioCSV Contact uploads
 * 
 * Contains getters and setters for the TwilioCSV Contact class
 */

class TwilioCSVContact
{

    public $id;
    public $_first_name;
    public $_last_name;
    public $_phone;
    public $_email;
    public $_city;
    public $_state;
    public $_source;
    public $_status;
    public $_disposition;
    public $_notes;
    public $_created;
    public $_updated;
    public $_user_id;
    public $table;

    public function __construct($id = null)
    {
        global $wpdb;
        $this->table = $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
        if ($id) {
            $this->id = $id;
            $this->_get($id);
        }
    }

    public function _get(int $id): bool|object
    {
        global $wpdb;
        $table = $this->table;
        $sql = "SELECT * FROM $table WHERE id = %d";
        $sql = $wpdb->prepare($sql, $id);
        $result = $wpdb->get_row($sql, ARRAY_A);
        if ($result) {
            $this->id = $result['id'];
            $this->_first_name = $result['_first_name'];
            $this->_last_name = $result['_last_name'];
            $this->_phone = $result['_phone'];
            $this->_email = $result['_email'];
            $this->_city = $result['_city'];
            $this->_state = $result['_state'];
            $this->_source = $result['_source'];
            $this->_status = $result['_status'];
            $this->_disposition = $result['_disposition'];
            $this->_notes = $result['_notes'];
            $this->_created = $result['_created'];
            $this->_updated = $result['_updated'];
            $this->_user_id = $result['_user_id'];
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Create a new contact from a CSV row
     * with specified columns by index
     * 
     * @return int $id
     */
    public function _new(array $data): int
    {
        $this->_first_name = (isset($data['_first_name'])) ? self::sanitize($data['_first_name']) : '';
        $this->_last_name = (isset($data['_last_name'])) ? self::sanitize($data['_last_name']) : '';
        $this->_phone = (isset($data['_phone'])) ? self::sanitize($data['_phone']) : '';
        $this->_email = (isset($data['_email'])) ? self::sanitize($data['_email']) : '';
        $this->_city = (isset($data['_city'])) ? self::sanitize($data['_city']) : '';
        $this->_state = (isset($data['_state'])) ? self::sanitize($data['_state']) : '';
        $this->_source = (isset($data['_source'])) ? self::sanitize($data['_source']) : '';
        $this->_status = (isset($data['_status'])) ? self::sanitize($data['_status']) : '';
        $this->_disposition = (isset($data['_disposition'])) ? self::sanitize($data['_disposition']) : '';
        $this->_notes = (isset($data['_notes'])) ? self::sanitize($data['_notes']) : '';
        $this->_created = TwilioCSV::localized_date();
        $this->_updated = TwilioCSV::localized_date();
        $this->_user_id = (isset($data['_user_id'])) ? self::sanitize($data['_user_id']) : 'Unassigned';
        $this->_save();
        return $this->id;

        // this is returning empty objects, pick backup here
        // @todo Something is wrong with the object creation
    }

    public function _save(): int|string
    {
        global $wpdb;
        $data = array(
            '_first_name' => $this->_first_name,
            '_last_name' => $this->_last_name,
            '_phone' => $this->_phone,
            '_email' => $this->_email,
            '_city' => $this->_city,
            '_state' => $this->_state,
            '_source' => $this->_source,
            '_status' => $this->_status,
            '_disposition' => $this->_disposition,
            '_notes' => $this->_notes,
            '_user_id' => $this->_user_id,
        );
        $format = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
        );
        if (isset($this->id)) { // update with new timestamp
            $updated_date = TwilioCSV::localized_date();
            $data['_updated'] = $updated_date;
            array_push($format, '%s');
            $insert = $wpdb->update($this->table, $data, array('id' => $this->id), $format);
            return $insert;
        } else { // new contact, check for conflicting phone or email
            $phone_check = $this->_phone_exists($data['_phone']);
            $email_check = $this->_email_exists($data['_email']);
            if ($phone_check || $email_check) {
                return "Error inserting $this->_first_name $this->_last_name. Phone or email already exists.";
            }

            // No conflicts, add _created timestamp
            $created_date = TwilioCSV::localized_date();
            $data['_created'] = $created_date;
            array_push($format, '%s');
            $insert = $wpdb->insert($this->table, $data, $format);
            $this->id = $wpdb->insert_id;

            // wp_send_json($wpdb->last_query . ' ' . $wpdb->last_error, 500);
            return $insert;
        }
    }

    public function _delete()
    {
        global $wpdb;
        $delete[] = $wpdb->delete($this->table, array('id' => $this->id));
        if ($delete) {
            $delete['success'] = true;
            $delete['id'] = $this->id;
            $delete['message'] = "Contact $this->id deleted.";
            return $delete;
        }
        return $delete;
    }

    #region Getters and Setters
    public function get_id()
    {
        return $this->id;
    }

    public function get_first_name()
    {
        return $this->_first_name ?? '';
    }

    public function set_first_name(string $first_name)
    {
        $this->_first_name = $first_name;
    }

    public function get_last_name()
    {
        return $this->_last_name ?? '';
    }

    public function set_last_name(string $last_name)
    {
        $this->_last_name = $last_name;
    }

    public function get_full_name()
    {
        return $this->_first_name . ' ' . $this->_last_name;
    }

    public function get_phone()
    {
        return $this->_phone ?? '';
    }

    public function set_phone(string $phone)
    {
        // format phone number to 10 digits no spaces special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $this->_phone = $phone;
    }

    public function get_email()
    {
        return $this->_email ?? '';
    }

    public function set_email(string $email)
    {
        $this->_email = $email;
    }

    public function get_city()
    {
        return $this->_city ?? '';
    }

    public function set_city(string $city)
    {
        $this->_city = $city;
    }

    public function get_state()
    {
        return $this->_state ?? '';
    }

    public function set_state(string $state)
    {
        $this->_state = $state;
    }

    public function get_source()
    {
        return $this->_source ?? '';
    }

    public function set_source(string $source)
    {
        $this->_source = $source;
    }

    public function get_status()
    {
        return $this->_status ?? '';
    }

    public function set_status(string $status)
    {
        $this->_status = $status;
    }

    public function get_disposition()
    {
        return $this->_disposition ?? '';
    }

    public function set_disposition(string $disposition)
    {
        $this->_disposition = $disposition;
    }

    public function get_notes()
    {
        return $this->_notes ?? '';
    }

    public function set_notes(string $notes)
    {
        $this->_notes = $notes;
    }

    public function set_user(int $user_id)
    {
        $this->_user_id = $user_id;
    }

    public function get_created()
    {
        return $this->_created;
    }

    public function set_created(string $created)
    {
        $this->_created = $created;
    }

    public function get_updated()
    {
        return $this->_updated ?? '';
    }

    public function set_updated(string $updated)
    {
        $this->_updated = $updated;
    }

    public function get_user_id()
    {
        return $this->_user_id;
    }

    public function set_user_id(int $user_id)
    {
        $this->_user_id = $user_id;
    }
    #endregion


    /**
     * _email_exists
     *
     *  Checks if email exists in database
     * 
     * @param string $email
     * @return bool
     */
    public function _email_exists($email = ''): bool
    {

        // if no email passed, use current object email
        if ($email !== '') {
            $this->_email = $email;
            $check_email = $this->_email;
        } else {
            $check_email = $this->_email;
        }

        global $wpdb;
        $table = self::table();

        $query = "SELECT _email FROM $table WHERE _email = '%s'";
        $query = $wpdb->prepare($query, $check_email);
        $email_in_db = $wpdb->get_var($query);

        if ($email_in_db) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * _phone_exists
     * 
     * Checks if phone number exists in database
     *
     * @param string $phone
     * @return bool
     */
    public function _phone_exists($phone = ''): bool
    {

        // if no phone passed, use current object phone
        if ($phone !== '') {
            $this->_phone = $phone;
            $check_phone = $this->_phone;
        } else {
            $check_phone = $this->_phone;
        }



        // Sanitize query using prepare, return value with get_var
        global $wpdb;
        $table = self::table();
        $query = $wpdb->prepare("SELECT _phone FROM $table WHERE _phone LIKE '%$check_phone%'");
        $phone_in_db = $wpdb->get_var($query);

        if ($phone_in_db) {
            return true;
        } else {
            return false;
        }
    }

    public function _phone_valid($phone = ''): bool
    {
        // if no phone passed, use current object phone
        if ($phone !== '') {
            $this->_phone = $phone;
            $check_phone = $this->_phone;
        } else {
            $check_phone = $this->_phone;
        }

        // Sanitize phone number, then check length
        $check_phone = preg_replace('/[^0-9]/', '', $check_phone);

        // Check if phone number is 10 digits and not all 0's, and area code is not 000
        if (strlen($check_phone) >= 10 && $check_phone !== '0000000000' && substr($check_phone, 0, 3) !== '000') {
            return true;
        } else {
            return false;
        }
    }

    private static function sanitize(string $string): string
    {
        return sanitize_text_field($string);
    }

    /**
     * Returns the table name for the contacts table
     */
    public static function table(): string
    {
        global $wpdb;
        return $wpdb->prefix . TWILIOCSV_CONTACTS_TABLE;
    }

    /**
     * retrieve_id_by_phone
     * 
     * @param string $phone - phone number to search for
     */
    public static function retrieve_id_by_phone(string $phone): int
    {
        global $wpdb;
        $table = TwilioCSVContact::table();

        // Remove all non-numeric characters from phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // If phone is 10 digits, ltrim to remove leading digit
        if (strlen($phone) === 10) {
            $phone = ltrim($phone, '1');
        }

        // Sanitize $phone input
        $query = $wpdb->prepare("SELECT id FROM $table WHERE _phone LIKE %s", '%' . $phone . '%');
        $id = $wpdb->get_var($query);

        if ($id) {
            return (int)$id;
        } else {
            return false;
        }
    }

    /**
     * _set_status
     * 
     * Sets the status of the contact and updates the database with the new status
     *
     * @param mixed|string $status
     * @param mixed|int $id
     * @return int|bool
     */
    public static function _set_status($status, $id)
    {
        global $wpdb;
        $table = self::table();
        $data = array(
            '_status' => $status,
        );
        $format = array(
            '%s',
        );
        $updated_date = TwilioCSV::localized_date();
        $data['_updated'] = $updated_date;
        array_push($format, '%s');
        $update = $wpdb->update($table, $data, array('id' => $id), $format);
        return $update;
    }

    public static function _list_dispositions()
    {
        $dispositions = array(
            'New',
            'Active',
            'Scheduled Callback',
            'Zoom Meeting Scheduled',
            'No Answer',
            'Left Voicemail',
            'Not Interested',
            'Not Qualified',
            'Not Available',
            'Not In Service',
            'Wrong Number',
            'Do Not Call',
            'Spam',
            'Duplicate',
            'Other - See Notes',
        );
        return $dispositions;
    }

    public static function _set_disposition(string $disposition, int $id): array
    {
        global $wpdb;
        $table = self::table();
        $results = [];

        $data = array(
            '_disposition' => $disposition,
        );
        $format = array(
            '%s',
        );
        $update = $wpdb->update($table, $data, array('id' => $id), $format);

        if ($update) {
            $results['success'] = true;
            $results['message'] = 'Disposition updated successfully';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            self::_update_updated($id);
        } else {
            $results['success'] = false;
            $results['message'] = 'Error updating disposition';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
        }
        return $results;
    }

    public static function _create_new_recruit_from_contact($contact_id): bool
    {
        global $wpdb;
        $table = TwilioCSVRecruit::table();
        // if contact already is a recruit, return false
        $query = $wpdb->prepare("SELECT _contact_id FROM $table WHERE _contact_id = %d", $contact_id);
        $contact_id_in_db = $wpdb->get_var($query);
        if ($contact_id_in_db) {
            TwilioCSVContact::_set_disposition('New Recruit', $contact_id);
            return false;
        }

        // create new recruit entry
        $data = array(
            '_contact_id' => $contact_id,
            '_interview_date' => TwilioCSV::localized_date(),
            '_updated' => TwilioCSV::localized_date(),
        );
        $format = array(
            '%d',
            '%s',
            '%s',
        );
        $insert = $wpdb->insert($table, $data, $format);
        if ($insert > 0) {
            TwilioCSVContact::_set_disposition('New Recruit', $contact_id);
        }
        error_log(print_r($wpdb->last_query, true));
        error_log(print_r($wpdb->last_error, true));
        return $insert;
    }

    public static function handle_interview($form_submission)
    {
        $response = [];
        $interview_table_data = [];
        // Get contact ID from form submission
        $contact_id = $form_submission['contact-id'];
        $contact = new TwilioCSVContact($contact_id);
        $contact = $contact->_get($contact->id);
        $phone = $contact->_phone;
        $email = $contact->_email;
        $first_name = $contact->_first_name;
        $last_name = $contact->_last_name;
        $full_name = $first_name . ' ' . $last_name;
        $status = $contact->_status;
        $disposition = $contact->_disposition;

        /**
         * Form fields:
         * 
         * did-client-answer-yn: Yes/No
         *  - Did the contact answer the phone?
         * no-answer: Left Voicemail, Voicemail Full, No Voicemail, Not In Service / Disconnected, Call Back
         * no-answer-notes: Notes about why contact did not answer
         * call-back-date: Date string to schedule call back
         * can-talk-job-seeker-yn: Yes/No
         *  - Can the contact talk about the opportunity?
         * q1 - qN: Question and answer fields
         *  - Questions and answers from the job description
         *  - loop through questions and answers and save to database
         * select-briefing: Select / Option field
         *  - Select the briefing the contact is interested in
         *  - date and time, schedule zoom meeting
         * available-for-briefing-yn: Yes/No
         *  - Is the contact available for a briefing?
         * confirm-email-address-yn: Yes/No
         *  - If no, update the contact's email address using 'email-address' field
         * email-address: Email address
         * remove-dnc: Keep In Database / Remove - Do Not Call
         *  - if 'Remove - Do Not Call' is selected, update the contact's disposition to 'Do Not Call'
         */

        $client_anserwed = $form_submission['did-client-answer-yn'];
        $dnc = $form_submission['remove-dnc'] ?? null;


        if ($client_anserwed == 'No') {

            $no_answer_disposition = $form_submission['no-answer'];
            $no_answer_notes = $form_submission['no-answer-notes'] ?? '';

            if ($no_answer_disposition == 'Call Back') {

                $call_back_date = $form_submission['call-back-date'];
                $call_back_item = TwilioCSVSchedule::_create_scheduled_item($contact_id, 'Call Back', $call_back_date);
                $response['success'] = TwilioCSVContact::_set_disposition('Scheduled Callback', $contact_id);
                $response['message'] = 'Contact will be called back on ' . $call_back_date;
                return $response;

            } else { // No Answer, create entry using TwilioCSVInterview

                $response['success'] = TwilioCSVContact::_set_disposition($no_answer_disposition, $contact_id);
                $response['message'] = 'Contact disposition set to ' . $no_answer_disposition;

                $interview_table_data['_contact_id'] = $contact_id;
                $interview_table_data['_disposition'] = $no_answer_disposition;
                $interview_table_data['_notes'] = $no_answer_notes;
                $interview_table_data['_created'] = TwilioCSV::localized_date();
                $interview_table_data['_updated'] = TwilioCSV::localized_date();
                
                $response['interview_update'] = TwilioCSVInterview::create($interview_table_data);

                if ($no_answer_disposition === 'No Answer') {
                    $message['contact-id'] = $contact_id;
                    $message['body'] = "Hey, I gave you a call. When is a better time to give you a call back?";
                    $message['to'] = $phone;
                    $response['followup'] = TwilioCSV::send_sms($message);
                }

                return $response;

            }
        } else {
            // Begin logic handling bottom up
            // If Do Not Call is selected, exit logic and update contact's disposition
            if ($dnc === 'Do Not Call') {

                $disposition = 'Do Not Call';
                $response['success']['dnc'] = TwilioCSVContact::_set_disposition($disposition, $contact_id);
                $response['message']['dnc'] = 'Contact Added to Do Not Call list';
                return $response;

            }

            // Was contact able to talk about the opportunity?
            $can_talk = $form_submission['can-talk-job-seeker-yn'];
            if ($can_talk === 'No') {

                $disposition = 'Cannot Talk Now';
                $response['success']['not-interested'] = TwilioCSVContact::_set_disposition($disposition, $contact_id);
                $response['message']['not-interested'] = 'Contact disposition set to ' . $disposition;
                return $response;

            } else {  // Contact is interested in the opportunity, begin interview
                $question_answer_array = $form_submission['questionAnswerArray']; // a JSON string
                $interview_table_data['_question_answer_array'] = $question_answer_array;
                $interview_table_data['_contact_id'] = $contact_id;
                $interview_table_data['_created'] = TwilioCSV::localized_date();
                
                if ($form_submission['available-for-briefing-yn'] === 'Yes') {

                    $disposition = 'Zoom Meeting Scheduled';
                    $response['success'] = TwilioCSVContact::_set_disposition($disposition, $contact_id);
                    $response['message'] = 'Zoom Meeting Scheduled';
        
                    // Get the scheduled date and time of the selected briefing
                    $briefing_id = $form_submission['select-briefing'];
                    $briefing = new TwilioCSVBriefing($briefing_id);
                    $schedule_date = $briefing->_scheduled;
        
                    // Create new Scheduled Item
                    $scheduled_item = TwilioCSVSchedule::_create_scheduled_item($contact_id, 'Zoom Meeting Scheduled', $schedule_date);
                    $response['scheduled_item'] = $scheduled_item;

                    // Log interview
                    $interview_table_data['_disposition'] = $disposition;
                    $interview = TwilioCSVInterview::create($interview_table_data);
                    $response['interview'] = $interview;
                    
                    $email_array = array(
                        'email-address' => $form_submission['email-address'],
                        'full-name' => $form_submission['full-name'],
                        'select-briefing' => $form_submission['select-briefing'],
                    );

                    $response['email'] = TwilioCSVEmailHandler::send_webinar_link($email_array);

                    if ($form_submission['confirm-email-address-yn'] === 'No') {
                        $email = $form_submission['email-address'];
                        $contact->set_email($email);
                        $contact->_save();
                        $response['message'] = 'Contact email address updated';
                    }

                    return $response;
        

                    } else { // Contact is not available for a briefing

                        $disposition = 'Not Available for Briefing';
                        $response['success'] = TwilioCSVContact::_set_disposition($disposition, $contact_id);
                        $response['message'] = 'Contact disposition set to ' . $disposition;

                        $interview_table_data['_disposition'] = $disposition;
                        $interview_table_data['_notes'] = 'Contact was not available to join the briefing. Interview skipped, call back later.' ?? '';
                        $interview_table_data['_created'] = TwilioCSV::localized_date();
                        $interview_table_data['_updated'] = TwilioCSV::localized_date();
                        
                        $response['interview_update'] = TwilioCSVInterview::create($interview_table_data);

                        return $response;

                    }

                    // end of function output
                    $response['success'] = false;
                    $response['message'] = 'Something went wrong. Please try again. Called from within Interview Logic.';
                    $response['data'] = $form_submission;
                    return $response;
                } // end interview logic

        } // end if client answered

    // if we got this far without returning, something went wrong
    $response['success'] = false;
    $response['message'] = 'Something went wrong. Please try again. Called from outside Interview Logic.';
    $response['data'] = $form_submission;
    return $response;
    } // end function

    public static function assign_contacts_to_user(int $user_id, string $contact_ids) {
        global $wpdb;
        $table = self::table();
        $response = [];

        $user_full_name = TwilioCSV::_user_name($user_id);

        $contact_ids = explode(',', $contact_ids);
        $contact_ids = array_map('intval', $contact_ids);
        $contact_ids = array_filter($contact_ids);
        $contact_ids = array_unique($contact_ids);
        $contact_ids = implode(',', $contact_ids);

        $timestamp = TwiliOCSV::localized_date();
        
        $sql = "UPDATE $table SET _user_id = $user_id, _updated = '$timestamp' WHERE id IN ($contact_ids)";
        $result = $wpdb->query($sql);
        $response['result'] = $result;

        if ($wpdb->last_error === '') {
            $response['success'] = true;
            if ($result > 0) {
                $response['message'] = "$result contacts were updated and assigned to $user_full_name";
            } else {
                $response['message'] = "No contacts were updated, but no errors occured. The contacts selected may already be assigned to $user_full_name";
            }
            $response['query'] = $wpdb->last_query;
            $response['error'] = $wpdb->last_error;
        } else {
            $response['success'] = false;
            $response['message'] = 'No contacts assigned to user ' . $user_id;
            $response['query'] = $wpdb->last_query;
            $response['error'] = $wpdb->last_error;
        }
        return $response;
    }

    public static function _update_updated(int $id): bool {
        global $wpdb;
        $table_name = self::table();
        $now = TwilioCSV::localized_date();
        $result = $wpdb->update($table_name, array('_updated' => $now), array('id' => $id));
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_contact_phone_by_id_string(string $comma_seperated_ids): array {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT _phone FROM $table WHERE id IN ($comma_seperated_ids)";
        $result = $wpdb->get_results($sql, ARRAY_A);

        if ($result > 0) {
            $results['success'] = true;
            $results['message'] = 'Phone numbers retrieved';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            $results['data'] = $result;
        } else {
            $results['success'] = false;
            $results['message'] = 'No phone numbers retrieved';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            $results['data'] = $result;
        }

        return $results;
    }

    public static function get_all_contact_info_by_id_string(string $comma_seperated_ids): array
    {
        global $wpdb;
        $table = self::table();
        $sql = "SELECT * FROM $table WHERE id IN ($comma_seperated_ids)";
        $result = $wpdb->get_results($sql, ARRAY_A);

        if ($result > 0) {
            $results['success'] = true;
            $results['message'] = 'Contact info retrieved';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            $results['data'] = $result;
        } else {
            $results['success'] = false;
            $results['message'] = 'No contact info retrieved';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            $results['data'] = $result;
        }

        return $results;
    }

} // end class
