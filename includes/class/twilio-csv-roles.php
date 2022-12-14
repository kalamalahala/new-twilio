<?php

namespace TwilioCSV;

/**
 * TwilioCSVRole
 * 
 * Handles the role management for the plugin.
 * 
 * @package TwilioCSV
 * @since 1.2.5
 */ 
class TwilioCSVRole {

    public function __construct() {
        $this->_do_roles();
        }
    
    public function _do_roles() {

        // Twilio CSV Admin Role
        add_action( 'admin_init', array( $this, 'twilio_csv_admin_role' ) );
        // Twilio CSV Manager Role
        add_action( 'admin_init', array( $this, 'twilio_csv_manager_role' ) );
        // Twilio CSV User Role
        add_action( 'admin_init', array( $this, 'twilio_csv_user_role' ) );
        
    }

    public function twilio_csv_admin_role() {
        // Add the Twilio CSV Admin Role
        add_role( 'twilio_csv_admin', __('Twilio CSV Admin'), array(
            'read' => true,
            'upload_files' => true,
            'twilio_csv_admin' => true,
            'twilio_csv_manager' => true,
            'twilio_csv_user' => true,
        ) );
    }

    public function twilio_csv_manager_role() {
        // Add the Twilio CSV Manager Role
        add_role( 'twilio_csv_manager', __('Twilio CSV Manager'), array(
            'read' => true,
            'twilio_csv_manager' => true,
            'twilio_csv_user' => true,
        ) );
    }

    public function twilio_csv_user_role() {
        // Add the Twilio CSV User Role
        add_role( 'twilio_csv_user', __('Twilio CSV User'), array(
            'read' => true,
            'twilio_csv_user' => true,
        ) );
    }
    
}