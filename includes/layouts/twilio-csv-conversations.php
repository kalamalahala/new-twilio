<?php

/**
 * Displays all conversations present in conversations table
 * using DataTables.
 * 
 * @package     TwilioCSV
 * @subpackage  TwilioCSV/admin
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
$contact_id = $_GET['contact_id'] ?? false;
if ($contact_id !== false) {
    $contact = new TwilioCSVContact($contact_id);
    $full_name = $contact->get_first_name() . ' ' . $contact->get_last_name();
    $phone = $contact->get_phone();
}
// TwilioCSV::dump($contact);
?>

<!-- Bootstrap 4 Styling -->
<div class="wrap">
    <?php TwilioCSV::navbar(); ?>
    <?php TwilioCSV::modals(); ?>
    <div class="content-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="d-inline-flex align-items-baseline">
                        <h1>Conversations
                            <?php if (!$contact_id) {
                                echo " - Select Contact";
                            } else {
                                echo " - $full_name";
                            } ?>
                        </h1>
                    </div>
                    <div class="card" id="conversations-card-wrap" <?php if (!$contact_id) echo 'hidden' ?>>
                        <div class="card-header">
                            <?php echo (isset($contact_id) && $contact_id !== false) ? $full_name : __('Contact Name', 'text-domain'); ?>
                        </div>
                        <div class="card-body p-0">
                            <!-- float a bootstrap border spinner over card-body until AJAX removes it -->
                            <div id="loading-spinner" class="d-flex justify-content-center d-none">
                                <div class="spinner-border text-primary p-2 m-5" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="messages-wrapper pl-2 pr-2 pt-5 d-none">

                                <div class="inbound-message text-left d-none" id="inbound-message-0" data-id="0">
                                    <div class="message-body pl-0 pr-2 mb-2">STOP</div>
                                    <div class="row d-flex flex-row pl-2 pr-2">
                                        <small class="message-status">Received</small>
                                        <small class="message-date">2020-01-01 12:00:00</small>
                                    </div>
                                </div>

                                <div class="outbound-message text-right d-none" id="outbound-message-0" data-id="0">
                                    <div class="message-body pl-0 pr-2 mb-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint enim rem, quas ipsum provident porro! Facilis et explicabo fugiat. Unde minima optio, ratione tempora libero totam harum asperiores magni in!</div>
                                    <div class="row d-flex flex-row-reverse pl-2 pr-3">
                                        <small class="message-status">Received</small>
                                        <small class="message-date">2020-01-01 12:00:00</small>
                                    </div>
                                </div>


                            </div>


                            <div class="card-footer mb-0 pb-2">
                                <div class="message-box">
                                    <form action="" id="conversation-send-sms">
                                        <div class="form-group row mb-1">
                                            <div class="col-12 d-inline-flex">
                                                <input type="hidden" id="contact_id" name="contact-id" value="<?php echo $contact_id ?? ''; ?>">
                                                <input type="hidden" id="phone" name="to" value="<?php echo $phone ?? ''; ?>">
                                                <input type="text" name="body" class="message-input col-10" placeholder="Type message..."></input>
                                                <button type="submit" class="message-submit btn btn-primary col-2 ml-1">Send</button>
                                            </div>
                                            <div class="col-12 d-inline-flex mb-0">
                                                <small id="remaining-text" class="text-muted col-10 mb-0 pb-0"><span id="remaining-digits">160</span> characters remaining.</small>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <?php TwilioCSV::snip_programmable_messages_individual(); ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <div class="card-footer mt-0 mb-0">
                                <?php echo TwilioCSV::disposition_form_inline(); ?>
                                <div class="mb-1">&nbsp;</div>
                                <?php echo TwilioCSV::callback_form_inline(); ?>
                                <div class="alert alert-success d-none" id="callback-success"role="alert">
                                    Callback scheduled!
                                </div>
                                <div class="alert alert-warning d-none" id="callback-warning"role="alert">
                                    Callback failed, please try again.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">

</div>