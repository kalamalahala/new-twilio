<?php

/**
 * Class to handle TwilioCSV Contact uploads
 */

use PhpOffice\PhpSpreadsheet\IOFactory;
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-twilio-csv-contact.php';

class TwilioCSVUploadHandler {

    private mixed $file;
    private string $contacts_table;

    public function __construct($file = null) {
        if ($file) {
            $this->file = $file;
        }
    }

    /**
     * _parse_headers
     * 
     * Parses the selected file and returns the values of the first row
     *
     * @param array $file
     * @return mixed $headers
     */
    public static function _parse_headers(array $file): array {
        $file = $file['file'];
        $file_type = IOFactory::identify($file);
        $reader = IOFactory::createReader($file_type);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        // Select the first row
        $headers = $worksheet->rangeToArray('A1:Z1', null, true, true, true);
        $sheet['headerNames'] = '';
        foreach (array_filter($headers[1]) as $key => $value) {
            $sheet['headerNames'] .= '<li>Column ' . $key . ': ' . $value . '</li>';
        }
        $sheet['rows'] = $worksheet->getHighestRow() - 1;
        $sheet['headers'] = array_filter($headers[1]);
        return $sheet;
    }


    /**
     * _handle_upload
     * 
     * Uploads the file to the wp uploads forlder for parsing
     * then passes the file information back to the ajax handler
     *
     * @param array $file
     * @return array|string
     */
    public static function _handle_upload(array $file): array|string {
        // wp_handle_upload
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($file, $upload_overrides);
        if ($movefile && !isset($movefile['error'])) {
            return $movefile;
        } else {
            return $movefile['error'];
        }
    }

    /**
     * _parse_indexes
     * 
     * Returns an array containing the selected columns of
     * the selected spreadsheet
     *
     * @param array $form_data
     * @return array
     */
    public static function _parse_indexes(array $form_data): array {
        $indexes = [];
        foreach ($form_data as $key => $value) {
            if (strpos($key, 'col') !== false) {
                // split on -, column name is [0]
                $index = explode('-', $key);
                $indexes[$index[0]] = $value;
            }
        }
        return $indexes;
    }

    /**
     * _handle_contacts
     * 
     * Merges the selected columns with the values of the spreadsheet and
     * passes them to _insert_contact, which inserts them into the database.
     * If inserted, _insert_contact returns the id of the new contact.
     * 
     * Return array of IDs or errors
     *
     * @param array $file - uploaded template spreadsheet
     * @param array $indexes - array [database_column_name => spreadsheet_column_index]
     * @return array
     */
    public static function _handle_contacts(array $file, array $indexes): array {
        $output = [];

        $file = $file['file'];
        $file_type = IOFactory::identify($file);
        $reader = IOFactory::createReader($file_type);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        // skip first row, it's the header
        // create an array of each row given the selected indexes
        $rows = $worksheet->rangeToArray('A2:Z' . $worksheet->getHighestRow(), null, true, true, true);

        // create array of contacts using the selected indexes
        foreach ($rows as $row) {
            $contact = [];
            foreach ($indexes as $key => $value) {
                if ($value == 0) {
                    continue;
                }
                $contact[$key] = $row[$value];
                $contact['wp_user_id'] = get_current_user_id();
            }
            $output[] = self::_insert_contact($contact);
        }
        
        return $output;
    }

    public static function _insert_contact(array $contact): array {
        // array keys: _first_name, _last_name, _phone, _email, _city, _state, _source
        // these keys match the database column names
        // if array key is empty, set to empty string

        $results = [];

        $first_name = $contact['_first_name'] ?? '';
        $last_name = $contact['_last_name'] ?? '';
        $phone = $contact['_phone'] ?? '';
        $email = $contact['_email'] ?? '';
        $city = $contact['_city'] ?? '';
        $state = $contact['_state'] ?? '';
        $source = $contact['_source'] ?? '';
        $user_id = $contact['wp_user_id'] ?? '';
        $status = '';
        $disposition = 'New';
        $notes = '';

        $new_contact = new \TwilioCSVContact();

        // validate and check if phone exists
        if ($new_contact->_phone_valid($phone)) {
            if ($new_contact->_phone_exists($phone)) {
                $results['success'] = false;
                $results['message'] = "Phone number $phone already exists in the database.";
                $results['query'] = TwilioCSV::_wpdb_debug()['query'];
                $results['error'] = TwilioCSV::_wpdb_debug()['error'];
                return $results;
            }
        } else {
            $results['success'] = false;
            $results['message'] = "Phone number $phone is not valid.";
            $results['query'] = TwilioCSV::_wpdb_debug()['query'];
            $results['error'] = TwilioCSV::_wpdb_debug()['error'];
            return $results;
        }

        // validate and check if email exists
        if ($new_contact->_email_exists($email)) {
            $results['success'] = false;
            $results['message'] = "Email $email already exists in the database.";
            $results['query'] = TwilioCSV::_wpdb_debug()['query'];
            $results['error'] = TwilioCSV::_wpdb_debug()['error'];
            return $results;
        }

        $new_contact->set_first_name($first_name);
        $new_contact->set_last_name($last_name);
        $new_contact->set_phone($phone);
        $new_contact->set_email($email);
        $new_contact->set_city($city);
        $new_contact->set_state($state);
        $new_contact->set_source($source);
        $new_contact->set_status($status);
        $new_contact->set_disposition($disposition);
        $new_contact->set_notes($notes);
        $new_contact->set_user_id($user_id);

        $insert = $new_contact->_save();

        $results = [
            'success' => $insert,
            'message' => $insert ? "Contact $first_name $last_name inserted successfully." : "Contact $first_name $last_name failed to insert.",
            'query' => TwilioCSV::_wpdb_debug()['query'],
            'error' => TwilioCSV::_wpdb_debug()['error'],
            'id' => $new_contact->get_id()
        ];

        return $results;
    }
}
