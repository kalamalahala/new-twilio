<?php

/**
 * Handles the Inbound and Outbound message insertion into the Messages database
 * as well as message retrieval by different methods.
 * 
 * @package TwilioCSV
 * @subpackage TwilioCSV\TwilioCSVMessage
 * @since 1.2
 * 
 * 
 */

use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client as Client;

class TwilioCSVMessage
{

    public $table;
    protected $api_key;
    protected $auth_token;
    protected $messaging_service_sid;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . TWILIOCSV_MESSAGES_TABLE;
        $this->api_key = TwilioCSV::option('twilio_account_sid');
        $this->auth_token = TwilioCSV::option('twilio_auth_token');
        // get current user's messaging service sid via TwilioCSVUser get_user_settings()
        // $user_id = get_current_user_id();
        // $twilio_user = new TwilioCSVUser($user_id);
        // $this->messaging_service_sid = $twilio_user->get_user_settings();
    }

    #region handlers
    /**
     * Inserts a message into the database.
     * 
     * Example inbound array from $_POST:
     * Array
     * (
     *     [Body] => Oh how about both
     *     [SmsStatus] => received
     *     [From] => +19045321080
     *     [FromCity] => 
     *     [FromState] => FL
     *     [FromZip] => 
     *     [FromCountry] => US
     *     [To] => +13868886995
     *     [ToCountry] => US
     *     [ToState] => FL
     *     [ToCity] => 
     *     [ToZip] => 
     *     [NumMedia] => 1
     *     [MediaContentType0] => image/jpeg
     *     [MediaUrl0] => https://api.twilio.com/2010-04-01/Accounts/ACd4b4efa2054f2aaf8c06ab0693f3f65b/Messages/MMe5530ced8a8f3093ee031e870d8b4bb5/Media/ME3adf522c6c9cb2edb78d3111d1292608
     *     [ReferralNumMedia] => 0
     *     [SmsSid]         => MMe5530ced8a8f3093ee031e870d8b4bb5
     *     [SmsMessageSid]  => MMe5530ced8a8f3093ee031e870d8b4bb5
     *     [MessageSid]     => MMe5530ced8a8f3093ee031e870d8b4bb5
     *     [MessagingServiceSid] => MGed693e77e70d6f52882605d37cc30d4c
     *     [NumSegments] => 1
     *     [AccountSid] => ACd4b4efa2054f2aaf8c06ab0693f3f65b
     *     [ApiVersion] => 2010-04-01
     * )
     * 
     * @param array $args
     * @return int|false
     */
    public function _add_inbound_message(array $args): int|false
    {
        global $wpdb;
        $table = $this->table;

        /**
         * Collect the args into array with these keys:
         * _SmsMessageSid
         * _MessagingServiceSid
         * _From
         * _To
         * _Body
         * _SmsStatus
         * _NumMedia
         * _Media (convert MediaUrl# to MediaContentType# to JSON if NumMedia > 0)
         * _contact_id
         * _conversation_id
         * _created
         * _updated
         */

        // remove leading + from phone numbers
        $from = ltrim($args['From'], '+1');
        $to = ltrim($args['To'], '+1');
        $contact_id = $this->_get_contact_id_by_phone($from);

        $data = array(
            '_SmsMessageSid' => $args['SmsMessageSid'],
            '_MessagingServiceSid' => $args['MessagingServiceSid'],
            '_From' => $from,
            '_To' => $to,
            '_Body' => $args['Body'],
            '_SmsStatus' => $args['SmsStatus'],
            '_NumMedia' => $args['NumMedia'],
            '_Media' => $this->_get_media($args), // null if NumMedia == 0
            '_contact_id' => $contact_id,
            '_conversation_id' => '', // @todo after conversations are implemented
            '_created' => TwilioCSV::localized_date(),
            '_updated' => '0000-00-00 00:00:00',
        );

        $format = array(
            '%s', // _SmsMessageSid
            '%s', // _MessagingServiceSid
            '%s', // _From
            '%s', // _To
            '%s', // _Body
            '%s', // _SmsStatus
            '%d', // _NumMedia
            '%s', // _Media
            '%d', // _contact_id
            '%s', // _conversation_id
            '%s', // _created
            '%s', // _updated
        );

        // Logic to set Status and Disposition based on message contents
        $dnc_words = self::disallowed_words();
        $body_content = strtolower($args['Body']);
        $dnc = false;
        foreach ($dnc_words as $word) {
            if (strpos($body_content, $word) !== false) {
                $data['_SmsStatus'] = 'Auto-Blocked';
                TwilioCSVContact::_set_status('Auto-Blocked', $contact_id);
                TwilioCSVContact::_set_disposition('Do Not Call', $contact_id);
                $dnc = true;
                break;
            }
        }

        if (!$dnc) {
            TwilioCSVContact::_set_status('New Message', $contact_id);
        }

        $result = $wpdb->insert($table, $data, $format);

        // get all messages to this contact with status 'scheduled'
        $scheduled = self::_get_scheduled_messages($contact_id);
        if ($scheduled) {
            $message_db_ids = implode(',', array_column($scheduled, 'id'));
            $message_sms_ids = array_column($scheduled, '_SmsMessageSid');
            $results[] = self::_update_scheduled_messages($message_db_ids);
            foreach ($message_sms_ids as $sms_id) {
                // cancel each scheduled message in Twilio API
                $results[] = $this->cancel_scheduled_message($sms_id);
            }

        }
        // place all returned message ids in a comma separated string
        // update all messages with status 'scheduled' to 'sent'
        // create array of message sms ids

        error_log('Update results: ' . print_r($results, true));
        

        if ($result === false) {
            return false;
        } else {
            $contact = new TwilioCSVContact($contact_id);
            $contact->set_updated(TwilioCSV::localized_date());
            $contact->_save();
            return $wpdb->insert_id;
        }
    }

    public function _add_outbound_message(array $sms)
    {
        global $wpdb;
        $table = $this->table;
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $twilio_user = new TwilioCSVUser($user_id);
        $msid = $twilio_user->get_user_settings();
        $filter = array(
            'to' => $sms['to'],
            'messaging_service_sid' => $msid,
        );
        $outgoing = self::_get_message_by_to($filter);
        $results = [];

        // error_log('outbound message logging');

        $data = array(
            '_SmsMessageSid' => $outgoing['sid'],
            '_MessagingServiceSid' => $msid,
            '_From' => $outgoing['from'], // may be null if message is scheduled
            '_To' => $sms['to'],
            '_Body' => self::format_sms_body($sms),
            '_SmsStatus' => 'sent',
            '_NumMedia' => 0,
            '_Media' => null,
            '_contact_id' => $sms['contact-id'],
            '_conversation_id' => '', // @todo after conversations are implemented
            '_created' => TwilioCSV::localized_date(),
            '_updated' => '0000-00-00 00:00:00',
            '_is_scheduled' => $sms['is-scheduled'] ?? false,
            '_scheduled_status' => $sms['scheduled-status'] ?? 'pending',
            '_scheduled_datetime' => $sms['scheduled-datetime'] ?? TwilioCSV::localized_date(),
        );

        $format = array(
            '%s', // _SmsMessageSid
            '%s', // _MessagingServiceSid
            '%s', // _From
            '%s', // _To
            '%s', // _Body
            '%s', // _SmsStatus
            '%d', // _NumMedia
            '%s', // _Media
            '%d', // _contact_id
            '%s', // _conversation_id
            '%s', // _created
            '%s', // _updated
            '%s', // _is_scheduled
            '%s', // _scheduled_status
            '%s', // _scheduled_datetime
        );

        $result = $wpdb->insert($table, $data, $format);
        // error_log('Created rows: ' . print_r($result, true));
        // error_log('Last error: ' . print_r($wpdb->last_error, true));
        // error_log('Last query: ' . print_r($wpdb->last_query, true));
        TwilioCSVContact::_set_status('Messaged', $sms['contact-id']);
        if ($result === false) {
            $results['error'] = $wpdb->last_error;
            $results['query'] = $wpdb->last_query;
            $results['success'] = false;
            $results['message'] = 'Error adding outbound message to database.';
            return $results;
        } else {
            $results['success'] = true;
            $results['message'] = 'Outbound message added to database.';
            $results['query'] = $wpdb->last_query;
            $results['error'] = '';
            $results['result'] = $result;
            return $results;
        }
    }

    public function _update_outbound_message($args)
    {
        // error_log('update_outbound_message called');
        global $wpdb;
        $table = $this->table;

        $from = ltrim($args['From'], '+1');

        $data = array(
            '_From' => $from,
            '_SmsStatus' => $args['SmsStatus'],
            '_updated' => TwilioCSV::localized_date(),
        );

        $format = array(
            '%s', // __From (just in case)
            '%s', // _SmsStatus
            '%s', // _updated
        );

        $where = array(
            '_SmsMessageSid' => $args['SmsSid'],
        );

        $where_format = array(
            '%s', // _SmsMessageSid
        );

        $result = $wpdb->update($table, $data, $where, $format, $where_format);
        $id = self::_get_contact_id_by_phone($from);
        TwilioCSVContact::_set_status($args['SmsStatus'], $id);

        if ($result === false || $result === 0) {
            error_log('Error updating outbound message: ' . print_r($wpdb->last_error, true));
            return false;
        } else {
            error_log('Updated outbound message: ' . print_r($result, true));
            return $wpdb->insert_id;
        }
    }

    public static function _update_scheduled_messages(string $message_db_ids): array
    {
        global $wpdb;
        $table = self::table();
        $sql = "UPDATE $table SET _scheduled_status = 'sent', _status = 'sent', _updated = '" . TwilioCSV::localized_date() . "'WHERE id IN ($message_db_ids)";
        $result = $wpdb->query($sql);
        if ($result === false) {
            $results['success'] = false;
            $results['message'] = 'Error updating scheduled messages.';
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
            return $results;
        } else {
            $results['success'] = true;
            $results['message'] = 'Scheduled messages updated.';
            $results['query'] = $wpdb->last_query;
            $results['error'] = '';
            return $results;
        }
    }

    public function cancel_scheduled_message(string $sms_id)
    {
        $sid = $this->api_key;
        $token = $this->auth_token;
        $twilio = new Client($sid, $token);
        $message = $twilio->messages($sms_id)->fetch();
        $result = $message->update(['status' => 'canceled']);

        if ($result === false) {
            $results['success'] = false;
            $results['message'] = 'Error canceling scheduled message.';
            return $results;
        } else {
            $results['success'] = true;
            $results['message'] = 'Scheduled message canceled.';
            return $results;
        }
    }

    public function retrieve_scheduled_messages()
    {
        $sid = $this->api_key;
        $token = $this->auth_token;
        $twilio = new Client($sid, $token);
        $filter = [
            'status' => 'queued',
        ];
        $messages = $twilio->messages->read($filter, 20, 20);
        $results = [];
        foreach ($messages as $key => $value) {
            $results[$key] = $value->toArray();
        }
        return $results;
    }

    /**
     * Get scheduled messages from database.
     * 
     * If $contact_id is provided, only messages for that contact will be returned.
     * 
     * Default column is _scheduled_status, but can be changed to _status.
     *
     * @param integer|null $contact_id
     * @param string $col
     * @return array
     */
    public static function _get_scheduled_messages(int $contact_id = null, string $col = '_scheduled_status'): array {
        global $wpdb;
        $table = self::table();
        $and_id = ($contact_id) ? "AND _contact_id = $contact_id" : '';
        $sql = "SELECT * FROM $table WHERE $col = 'scheduled' $and_id";
        $results = $wpdb->get_results($sql, ARRAY_A);

        return $results;
    }

    public static function _get_message_by_to(array $filter): array
    {
        $account_sid = TwilioCSV::option('twilio_account_sid');
        $auth_token = TwilioCSV::option('twilio_auth_token');
        $twilio = new Client($account_sid, $auth_token);

        $messages = $twilio->messages->read([
            'to' => $filter['to'],
            'messagingServiceSid' => $filter['messaging_service_sid'],
        ]);

        $outbound['sid'] = $messages[0]->sid;
        $from = $messages[0]->from;
        // remove + from phone number
        $outbound['from'] = substr($from, 1);

        return $outbound;
    }

    public function _get_media(array $args): string|null
    {
        if ($args['NumMedia'] == 0) {
            return null;
        }
        $media = array();
        $num_media = $args['NumMedia'];
        for ($i = 0; $i < $num_media; $i++) {
            $media[$args['MediaUrl' . $i]] = $args['MediaContentType' . $i];
        }
        return json_encode($media);
    }

    public function _get_contact_id_by_phone(string $phone)
    {
        $clean_phone = preg_replace('/[^0-9]/', '', $phone);
        $id = TwilioCSVContact::retrieve_id_by_phone($clean_phone);
        return $id;
    }

    public function _get_messages_by_id(int $id): array
    {
        global $wpdb;
        $table = $this->table;
        $sql = "SELECT * FROM $table WHERE _contact_id = $id";
        $results = $wpdb->get_results($sql, ARRAY_A);
        return $results;
    }

    /**
     * format_sms_body
     *
     * SMS Array keys: body, contact-id
     * 
     * @param array $sms_array
     * @return string $body
     */
    public static function format_sms_body(array $sms_array)
    {
        $body = $sms_array['body'];
        $contact_id = $sms_array['contact-id'];
        $contact = new TwilioCSVContact($contact_id);
        $replace_array = array(
            '{{FIRSTNAME}}' => $contact->get_first_name(),
            '{{LASTNAME}}' => $contact->get_last_name(),
            '{{FULLNAME}}' => $contact->get_full_name(),
            '{{PHONE}}' => $contact->get_phone(),
            '{{EMAIL}}' => $contact->get_email(),
            '{{CITY}}' => $contact->get_city(),
            '{{STATE}}' => $contact->get_state(),
            '{{SOURCE}}' => $contact->get_source(),
            '{{STATUS}}' => $contact->get_status(),
            '{{USER_FIRSTNAME}}' => TwilioCSV::user_name('first'),
            '{{USER_LASTNAME}}' => TwilioCSV::user_name('last'),
            '{{USER_FULLNAME}}' => TwilioCSV::user_name('full'),
            // unicode apostrophe
            '\'' => "\2019",
        );
        $body = str_replace(array_keys($replace_array), array_values($replace_array), $body);
        return $body;
    }

    public static function merge_tags()
    {
        $tags = array(
            '{{FIRSTNAME}}' => 'First Name',
            '{{LASTNAME}}' => 'Last Name',
            '{{FULLNAME}}' => 'Full Name',
            '{{PHONE}}' => 'Phone',
            '{{EMAIL}}' => 'Email',
            '{{CITY}}' => 'City',
            '{{STATE}}' => 'State',
            '{{SOURCE}}' => 'Source',
            '{{STATUS}}' => 'Status',
            '{{USER_FIRSTNAME}}' => 'User First Name',
            '{{USER_LASTNAME}}' => 'User Last Name',
            '{{USER_FULLNAME}}' => 'User Full Name',
        );
        return $tags;
    }

    public static function disallowed_words(): array
    {
        $words = array(
            'bitch',
            'fuck',
            'dick',
            'stop',
            'list',
            'remove',
            'cunt',
            'piss',
            'unsubscribe',
            'fcc',
            'retard',
            'retarded',
            'asshole',
            'shit',
            'bastard',
            'bastards',
            'nigger'
        );

        return $words;
    }

    public static function _get_most_recent_message(): int
    {
        global $wpdb;
        $table = self::table();

        $query = "SELECT * from $table ORDER BY `_updated` ASC LIMIT 100";
        $messages = $wpdb->get_results($query);

        $disposition_filter = 'Do Not Call';

        foreach ($messages as $message) {
            $contact = new TwilioCSVContact($message->_contact_id);
            if ($contact->_disposition !== $disposition_filter) {
                return $contact->id;
            } else {
                continue;
            }
        }
        return 0;
    }

    public static function table(): string
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $table_string = TWILIOCSV_MESSAGES_TABLE;

        $table = $prefix . $table_string;

        return $table;
    }
    #endregion handlers

    /**
     * bulk_message_scheduling
     * 
     * Handles form input from the Bulk SMS Modal
     *
     * @param array $form_data
     * @return array
     */
    public function bulk_message_scheduling(array $form_data): array
    {
        $send_time = $form_data['send-time'] ?? TwilioCSV::localized_date(); // 2022-12-06 18:35
        // format to [YYYY]-[MM]-[DD]T[HH]:[MM]:[SS]Z
        $send_time = date('Y-m-d\TH:i:s\Z', strtotime($send_time)); // 2022-12-06T18:35:00Z
        // $send_time = date('Y-m-d\TH:i:s\Z', strtotime($send_time)); // 2022-12-06T18:35:00+00:00
        $message_group = [];
        $specified_contact_info = TwilioCSVContact::get_all_contact_info_by_id_string($form_data['contact-id-list']);

        if ($form_data['num-follow-up-messages'] > 0) {
            $num_selector = [
                0 => 'first',
                1 => 'second',
                2 => 'third',
            ];
        }

        foreach ($specified_contact_info['data'] as $contact) {
            // Send time MUST be a minimum of 900 seconds (15 minutes) in the future of the current time
            $min_send_time = date('Y-m-d\TH:i:s\Z', strtotime('+16 minutes')); // adding 16 minutes to account for the time it takes to process the request
            $message_group[$contact['_phone']][] = [
                'body' => $this->replace_merge_tags($form_data['body'], $contact),
                'send_time' => ($send_time < $min_send_time) ? null : $send_time,
                'to' => $contact['_phone'],
                'contact-id' => $contact['id'],
            ];
            // if (!$this->messaged_last_30_days($contact['id'])) // If the contact has not been messaged in the last 30 days
            // {
            // } else { // If the contact has been messaged in the last 30 days, skip them
            //     $skipped_contacts[] = $contact;
            //     continue;
            // }
        }

        if (isset($num_selector)) { // If there are follow up messages, array_push to each contact the created messages
            $num_follow_ups = $form_data['num-follow-up-messages'];
            $original_send_time = $send_time;
            for ($i = 0; $i < $num_follow_ups; $i++) {
                $send_time_int = $form_data[$num_selector[$i] . '-follow-up-number'];
                $send_time_unit = $form_data[$num_selector[$i] . '-follow-up-unit'];
                $scheduled_send_time = date('Y-m-d\TH:i:s\Z', strtotime($original_send_time . ' +' . $send_time_int . ' ' . $send_time_unit));
                foreach ($specified_contact_info['data'] as $contact) {
                    $message_group[$contact['_phone']][] = [
                        'body' => $this->replace_merge_tags($form_data[$num_selector[$i] . '-follow-up-body'], $contact),
                        'send_time' => $scheduled_send_time,
                        'to' => $contact['_phone'],
                        'contact-id' => $contact['id'],
                    ];
                    // if (!$this->messaged_last_30_days($contact['id'])) {
                    // } else {
                    //     $skipped_contacts[] = $contact;
                    //     continue;
                    // }
                }
                $original_send_time = $scheduled_send_time;
            }
        }

        $output = [
            'success' => true,
            'message' => 'Bulk message scheduling complete',
            'messages' => $message_group,
            'specified_contacts' => $specified_contact_info['data'],
            'skipped_contacts' => $skipped_contacts ?? [],
        ];
        return $output;
    }

    public function process_scheduled_bulk_sms(array $messages): array
    {
        $output = [];
        foreach ($messages as $phone => $message_group) {
            foreach ($message_group as $message) {
                $outgoing = $this->send_sms($message['contact-id'], $message['to'], $message['body'], $message['send_time']);
                $output[] = $outgoing;
            }
        }
        if ($output) {
            $output = [
                'success' => true,
                'message' => 'Bulk message scheduling complete',
                'messages' => $output,
            ];
        } else {
            $output = [
                'success' => false,
                'message' => 'Bulk message scheduling failed',
            ];
        }
        return $output;
    }

    public function send_sms(int $contact_id, string $to, string $body, string $send_time = null): array
    {
        $api_key = $this->api_key;
        $auth_token = $this->auth_token;
        $messaging_service_sid = TwilioCSV::option('twilio_messaging_service_sid');
        
        $current_user_id = get_current_user_id();
        $twilio_csv_user = new TwilioCSVUser($current_user_id);
        $sending_number = $twilio_csv_user->get_sending_number();

        
        $client = new Client($api_key, $auth_token);
        // $client->setLogLevel('debug');
        
        $message_options = [
            'messagingServiceSid' => $messaging_service_sid,
            'body' => $body,
            'statusCallback' => get_site_url() . '/wp-json/twiliocsv/v1/sms',
        ];
        if ($sending_number) {
            $message_options['from'] = $sending_number;
        }

        if ($send_time) {
            $message_options['sendAt'] = $send_time;
            $message_options['scheduleType'] = 'fixed';
        }

        try {
            $message = $client->messages->create(
                $to,
                $message_options
            );
            $last_message = $client->getHttpClient()->lastResponse;
            $this->create_outbound_entry($message, $contact_id);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $debug['error'] = $error;
            $debug['last_response'] = $client->getHttpClient()->lastResponse;
        }

        $output = [
            'to' => $to,
            'body' => $body,
            'send_time' => $send_time,
            'message' => $message,
            'debug' => $debug ?? $last_message ?? null,
        ];
        return $output;
    }

    public function create_outbound_entry(MessageInstance $data, int $contact_id): array {
        global $wpdb;
        $table = $this->table;
        $results = [];
        // error_log('create_outbound_entry');
        // // print all properties of $data to error_log
        // foreach ($data as $key => $value) {
        //     error_log($key . ' => ' . $value);
        // }

        // $created_date = $data->date_created->format('Y-m-d H:i:s');
        $created_local = TwilioCSV::localized_date();


        $cols = array(
            '_SmsMessageSid' => $data->sid,
            '_MessagingServiceSid' => $data->messagingServiceSid,
            '_From' => $data->from,
            '_To' => $data->to,
            '_Body' => $data->body,
            '_SmsStatus' => $data->status,
            '_NumMedia' => $data->numMedia,
            '_Media' => null,
            '_contact_id' => $contact_id,
            '_conversation_id' => '',
            '_created' => $created_local,
            '_updated' => $created_local,
            '_is_scheduled' => ($data->status === 'scheduled') ? 1 : 0,
            '_scheduled_status' => ($data->status === 'scheduled') ? 'scheduled' : $data->status,
            '_scheduled_datetime' => $data->dateSent ?? $created_local,
        );

        $format = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%s',
            '%d',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
            '%s',
        );

        $insert = $wpdb->insert($table, $cols, $format);
        $new_contact_status = 'Message ' . ucwords($data->status);
        TwilioCSVContact::_set_status($new_contact_status, $contact_id);

        if ($insert) {
            $results['success'] = true;
            $results['message'] = 'Message created';
            $results['query'] = $wpdb->last_query;
            $results['data'] = $data;
        } else {
            $results['success'] = false;
            $results['message'] = 'Message creation failed';
            $results['data'] = $data;
            $results['query'] = $wpdb->last_query;
            $results['error'] = $wpdb->last_error;
        }

        return $results;
    }

    public function messaged_last_30_days(int $contact_id): bool
    {
        global $wpdb;
        $output = false;
        $query = "SELECT * FROM {$this->table} WHERE _contact_id = {$contact_id} AND _created > DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $wpdb->get_results($query, ARRAY_A);
        if ($result) {
            $output = true;
        }
        return $output;
    }

    public function replace_merge_tags(string $body, array $contact_info): string
    {
        if (!$body) return '';
        $merge_tags = [
            '{{FIRSTNAME}}' => $contact_info['_first_name'],
            '{{LASTNAME}}' => $contact_info['_last_name'],
            '{{FULLNAME}}' => $contact_info['_first_name'] . ' ' . $contact_info['_last_name'],
            '{{PHONE}}' => $contact_info['_phone'],
            '{{EMAIL}}' => $contact_info['_email'],
            '{{CITY}}' => $contact_info['_city'],
            '{{STATE}}' => $contact_info['_state'],
            '{{SOURCE}}' => $contact_info['_source'],
            '{{STATUS}}' => $contact_info['_status'],
            '{{USER_FIRSTNAME}}' => TwilioCSV::_user_name(get_current_user_id(), 'first'),
            '{{USER_LASTNAME}}' => TwilioCSV::_user_name(get_current_user_id(), 'last'),
            '{{USER_FULLNAME}}' => TwilioCSV::_user_name(get_current_user_id(), 'full')
        ];

        foreach ($merge_tags as $tag => $value) {
            $body = str_replace($tag, $value, $body);
        }

        return $body;
    }

    public function bulk_message(array $form_data)
    {
        // non scheduled message, no follow ups
        // contact-id-list is a comma separated list of contact ids
        // body

        // Get the phone number for each item in the contact-id-list, and TwilioCSV::send_sms() to send the message
        $contact_id_list = explode(',', $form_data['contact-id-list']);
        $body = $form_data['body'];
        $phone_numbers = [];
        foreach ($contact_id_list as $contact_id) {
            $contact = new TwilioCSVContact($contact_id);
            $phone_numbers[] = $contact->_phone;
        }

        return false;
    }
}
