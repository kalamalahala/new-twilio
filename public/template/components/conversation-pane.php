<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

(int)$contact_id = TwilioCSVMessage::_get_most_recent_message();
if ($contact_id !== false) {
    $contact = new TwilioCSVContact($contact_id);
    $full_name = $contact->get_first_name() . ' ' . $contact->get_last_name();
    $phone = $contact->get_phone();
}

?>

<input type="hidden" id="conversation-name" value="<?php echo $full_name; ?>" />

<div class="row mt-3">
    <div class="col-12">
        <div class="d-flex flex-row justify-content-start align-items-end">


            <h1><?php echo __("Conversation"); ?>&nbsp;&dash;&nbsp;<span id="conversation-full-name"><?php if (!isset($contact_id) || !$contact_id) {
                                                                                                            echo __("Select Contact");
                                                                                                        } else {
                                                                                                            echo $full_name;
                                                                                                        } ?></span></h1>
            <button class="btn btn-primary refresh-conversations ms-3 mb-3" type="button">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                 Loading...
            </button>
        </div>
    </div>
</div>
<div class="chat-wrapper">
    <!-- float a bootstrap border spinner over card-body until AJAX removes it -->
    <div class="messages-wrapper pl-2 pr-2 pt-5">
        <!-- bootstrap 5.2 -->
        <?php
        /*
                                <input type="hidden" name="conversation-id-selected" id="contact-id-selected" value="<?php echo TwilioCSVMessage::_get_most_recent_message(); ?>" />
                                */
        ?>
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
        <div class="no-messages mx-auto d-none">
            <p class="h3 p-5">No messages!</p>
        </div>
    </div>

    <div class="message-box">
        <form action="" id="conversation-send-sms" class="row row-cols-lg-auto g-3 align-items-center mt-1 mb-3">
            <input type="hidden" id="contact_id" name="contact-id" value="<?php echo $contact_id ?? ''; ?>">
            <input type="hidden" id="phone" name="to" value="<?php echo $phone ?? ''; ?>">
            <div class="col-12">
                <?php TwilioCSV::snip_programmable_messages_individual(); ?>
            </div>
            <div class="col-12">
                <input type="text" name="body" class="message-input form-control" placeholder="Type message..."></input>
                <small id="remaining-text" class="text-muted col-10 pb-0"><span id="remaining-digits">160</span> characters remaining.</small>
            </div>
            <div class="col-12">
                <button type="submit" class="message-submit btn btn-primary ml-1">Send <i class="fa-regular fa-comment"></i></button>
            </div>
        </form>
        <div class="disposition-form-inline-container">
            <?php echo TwilioCSV::disposition_form_inline(); ?>
        </div>
        <div class="callback-form-inline-container">
            <?php echo TwilioCSV::callback_form_inline(); ?>
        </div>
    </div>
</div>