<?php

/**
 * Modal forms to be included throughout the plugin.
 */

$current_time = TwilioCSV::localized_date();
$current_time_plus_5 = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($current_time)));

?>

<!-- Modal Single SMS -->
<div class="modal fade" id="send-single-sms" tabindex="-1" role="dialog" aria-labelledby="send-single-sms" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Send Single SMS', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" method="post" id="send-single-sms-form">
                        <input type="number" name="contact-id" id="contact-id" value="0" hidden>
                        <input type="text" name="contact-first-name" id="contact-first-name" hidden>
                        <input type="text" name="contact-last-name" id="contact-last-name" hidden>
                        <input type="tel" name="to" id="contact-phone" hidden>
                        <label for="sms-recipient"><?php echo __('Recipient', 'text-domain'); ?></label>
                        <div class="sms-recipient row mb-2">
                            <div class="col-sm-6">
                                <span class="sms-first-name">FIRSTNAME</span>&nbsp;<span class="sms-last-name">LASTNAME</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="sms-phone">1234567890</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sms"><?php echo __('Message', 'text-domain'); ?></label>
                            <textarea rows="4" cols="50" name="body" id="sms" class="form-control" placeholder="<?php echo __('Type your message here ...', 'text-domain'); ?>" aria-describedby="smsHelp"></textarea>
                            <div class="mt-1"><small id="sms-remaining-text" class="text-muted"><span class="sms-remaining">160</span> <?php echo __('characters remaining.', 'text-domain'); ?><span class="over-limit fst-italic text-danger d-none">&nbsp;(<?php echo __('An additional charge may apply to this message due to its length.'); ?></span></small></div>
                            <small id="smsHelp" class="text-muted"><?php echo __('Enter placeholders such as ', 'text-domain'); ?>{{FIRSTNAME}}<?php echo __(' to add dynamic text to your message. For a full list of placeholders,', 'text-domain'); ?> <a href="#" target="_self" title="<?php echo __('TwilioCSV Documentation', 'text-domain'); ?>"><?php echo __('click here', 'text-domain'); ?></a></small>

                            <?php TwilioCSV::snip_merge_tags(); ?>
                            <?php TwilioCSV::snip_programmable_messages_individual(); ?>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('Cancel', 'text-domain'); ?></button>
                <button type="submit" class="btn btn-primary send-single-sms-submit"><?php echo __('Send', 'text-domain'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk SMS Sender -->
<div class="modal fade" id="send-bulk-sms" tabindex="-1" role="dialog" aria-labelledby="send-bulk-sms" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Send Bulk Text Message', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="send-bulk-sms-form">
                    <!-- comma seperated list of selected client IDs -->
                    <textarea type="text" name="contact-id-list" id="contact-id-list" value="0" hidden></textarea>
                    <input type="number" name="contact-count" id="contact-count" value="0" hidden>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="num-follow-up-messages-selector"><?php echo __('Number of Follow Up Messages'); ?></label>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-check-inline" id="num-follow-up-messages-selector">
                                <input class="form-check-input" type="radio" name="num-follow-up-messages" id="no-followup" value="0" checked>
                                <label class="form-check-label" for="no-followup"><?php echo __('None'); ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="num-follow-up-messages" id="one-followup" value="1">
                                <label class="form-check-label" for="one-followup"><?php echo __('One'); ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="num-follow-up-messages" id="two-followup" value="2">
                                <label class="form-check-label" for="two-followup"><?php echo __('Two'); ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="num-follow-up-messages" id="three-followup" value="3">
                                <label for="three-followup" class="form-check-label"><?php echo __('Three'); ?></label>
                            </div>
                            <hr />
                        </div>
                        <!-- border radius, drop shadow, small padding -->
                        <div class="row">
                            <!-- initial message-container -->
                            <div class="col-12 border rounded-3 shadow-sm p-3 mb-3 mx-2 message-container">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <p class="lead">
                                            <?php echo __('Initial Message'); ?>
                                        </p>
                                        <label for="bulk-sms"><?php echo __('Message Body', 'text-domain'); ?></label>
                                        <textarea rows="4" cols="50" name="body" id="bulk-sms" class="form-control mb-2" placeholder="<?php echo __('Type your message here ...', 'text-domain'); ?>" aria-describedby="bulk-smsHelp"></textarea>
                                        <div><small id="bulk-sms-remaining-text" class="text-muted"><span class="bulk-sms-remaining">160</span> <?php echo __('characters remaining.', 'text-domain'); ?></small></div>
                                        <small id="smsHelp" class="text-muted"><?php echo __('Enter placeholders such as ', 'text-domain'); ?>{{FIRSTNAME}}<?php echo __(' to add dynamic text to your message. For a full list of placeholders,', 'text-domain'); ?> <a href="#" target="_self" title="<?php echo __('TwilioCSV Documentation', 'text-domain'); ?>"><?php echo __('click here', 'text-domain'); ?></a></small>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <?php TwilioCSV::snip_merge_tags(); ?>
                                        <?php TwilioCSV::snip_programmable_messages_bulk(); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 mb-3">
                                        <label for="schedule-bulk-radio">Schedule this message?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input schedule-bulk-radio" type="radio" name="schedule-bulk-radio" id="schedule-yes" value="Yes" checked>
                                            <label class="form-check-label schedule-bulk-radio" for="schedule-yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input schedule-bulk-radio" type="radio" name="schedule-bulk-radio" id="schedule-no" value="No">
                                            <label class="form-check-label schedule-bulk-radio" for="schedule-no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row schedule-bulk-message">
                                    <div class="col-12 mb-3">
                                        <label for="send-time" class="form-label">Send Time</label>
                                        <input type="text" class="form-control" name="send-time" id="send-time" aria-describedby="send-time-helper" value="<?php echo ($current_time_plus_5); ?>" disabled>
                                        <small id="send-time-helper" class="form-text text-muted">Select a time to send the initial message. <span class="text-danger">Scheduled messages require a <strong>minimum</strong> of 15 minutes in advance of the current time.</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- first follow-up message, to be shown if selectedValue is 1 -->
                    <div class="row first-follow-up-container d-none">
                        <!-- first follow-up message-container -->
                        <div class="col-12 border rounded-3 shadow-sm p-3 mb-3 mx-2 message-container">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <p class="lead">
                                        <?php echo __('First Follow Up Message'); ?>
                                    </p>
                                    <label for="bulk-followup-message-body"><?php echo __('Message Body', 'text-domain'); ?></label>
                                    <textarea rows="4" cols="50" name="first-follow-up-body" id="first-follow-up-body" class="form-control mb-2" placeholder="<?php echo __('Type your message here ...', 'text-domain'); ?>" aria-describedby="first-follow-up-bodyHelp" disabled></textarea>
                                    <div><small id="first-follow-up-body-remaining-text" class="text-muted"><span class="first-follow-up-body-remaining">160</span> <?php echo __('characters remaining.', 'text-domain'); ?></small></div>
                                    <small id="first-follow-up-bodyHelp" class="text-muted"><?php echo __('Enter placeholders such as ', 'text-domain'); ?>{{FIRSTNAME}}<?php echo __(' to add dynamic text to your message. For a full list of placeholders,', 'text-domain'); ?> <a href="#" target="_self" title="<?php echo __('TwilioCSV Documentation', 'text-domain'); ?>"><?php echo __('click here', 'text-domain'); ?></a></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <?php TwilioCSV::snip_merge_tags(); ?>
                                    <?php TwilioCSV::snip_programmable_messages_bulk(); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label aria-describedby="first-follow-up-helper"><?php echo __('How long until the follow-up message is sent?'); ?></label>
                                    <small class="text-muted d-block" id="first-follow-up-helper"><?php echo __('Defaults to 24 hours from initial message.', 'text-domain'); ?></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <input type="number" name="first-follow-up-number" id="first-follow-up" class="form-control" value="1" aria-labelledby="first-follow-up-label" disabled>
                                    <small class="text-muted" id="first-follow-up-label"><?php echo __('Enter a number', 'text-domain'); ?></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <select name="first-follow-up-unit" id="first-follow-up-unit" class="form-select" aria-labeledby="first-follow-up-unit-label" disabled>
                                        <option value="days" selected>Day(s)</option>
                                        <option value="hours">Hour(s)</option>
                                        <option value="minutes">Minute(s)</option>
                                    </select>
                                    <small class="text-muted d-block" id="first-follow-up-unit-label"><?php echo __('Select a unit of time', 'text-domain'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- second follow-up message, to be shown if selectedValue is 2 -->
                    <div class="row second-follow-up-container d-none">
                        <!-- second follow-up message-container -->
                        <div class="col-12 border rounded-3 shadow-sm p-3 mb-3 mx-2 message-container">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <p class="lead">
                                        <?php echo __('Second Follow Up Message'); ?>
                                    </p>
                                    <label for="bulk-followup-message-body"><?php echo __('Message', 'text-domain'); ?></label>
                                    <textarea rows="4" cols="50" name="second-follow-up-body" id="second-follow-up-body" class="form-control mb-2" placeholder="<?php echo __('Type your message here ...', 'text-domain'); ?>" aria-describedby="second-follow-up-bodyHelp" disabled></textarea>
                                    <div><small id="second-follow-up-body-remaining-text" class="text-muted"><span class="second-follow-up-body-remaining">160</span> <?php echo __('characters remaining.', 'text-domain'); ?></small></div>
                                    <small id="second-follow-up-bodyHelp" class="text-muted"><?php echo __('Enter placeholders such as ', 'text-domain'); ?>{{FIRSTNAME}}<?php echo __(' to add dynamic text to your message. For a full list of placeholders,', 'text-domain'); ?> <a href="#" target="_self" title="<?php echo __('TwilioCSV Documentation', 'text-domain'); ?>"><?php echo __('click here', 'text-domain'); ?></a></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <?php TwilioCSV::snip_merge_tags(); ?>
                                    <?php TwilioCSV::snip_programmable_messages_bulk(); ?>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-12 mb-3">
                                    <label aria-describedby="second-follow-up-helper"><?php echo __('How long until the follow-up message is sent?'); ?></label>
                                    <small class="text-muted d-block" id="second-follow-up-helper"><?php echo __('Defaults to 24 hours from the first follow up message.', 'text-domain'); ?></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <input type="number" name="second-follow-up-number" id="second-follow-up" class="form-control" value="1" disabled>
                                </div>
                                <div class="col-6 mb-3">
                                    <select name="second-follow-up-unit" id="second-follow-up-unit" class="form-select" disabled>
                                        <option value="days" selected>Day(s)</option>
                                        <option value="hours">Hour(s)</option>
                                        <option value="minutes">Minute(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- third follow-up message, to be shown if selectedValue is 3 -->
                    <div class="row third-follow-up-container d-none">
                        <!-- third follow-up message-container -->
                        <div class="col-12 border rounded-3 shadow-sm p-3 mb-3 mx-2 message-container">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <p class="lead">
                                        <?php echo __('Third Follow Up Message'); ?>
                                    </p>
                                    <label for="bulk-followup-message-body"><?php echo __('Message', 'text-domain'); ?></label>
                                    <textarea rows="4" cols="50" name="third-follow-up-body" id="third-follow-up-body" class="form-control mb-2" placeholder="<?php echo __('Type your message here ...', 'text-domain'); ?>" aria-describedby="third-follow-up-bodyHelp" disabled></textarea>
                                    <div><small id="third-follow-up-body-remaining-text" class="text-muted"><span class="third-follow-up-body-remaining">160</span> <?php echo __('characters remaining.', 'text-domain'); ?></small></div>
                                    <small id="third-follow-up-bodyHelp" class="text-muted"><?php echo __('Enter placeholders such as ', 'text-domain'); ?>{{FIRSTNAME}}<?php echo __(' to add dynamic text to your message. For a full list of placeholders,', 'text-domain'); ?> <a href="#" target="_self" title="<?php echo __('TwilioCSV Documentation', 'text-domain'); ?>"><?php echo __('click here', 'text-domain'); ?></a></small>
                                </div>
                                <div class="col-12 mb-3">
                                    <?php TwilioCSV::snip_merge_tags(); ?>
                                    <?php TwilioCSV::snip_programmable_messages_bulk(); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label aria-describedby="third-follow-up-helper"><?php echo __('How long until the follow-up message is sent?'); ?></label>
                                    <small class="text-muted d-block" id="third-follow-up-helper"><?php echo __('Defaults to 24 hours from the second follow up message.', 'text-domain'); ?></small>
                                </div>
                                <div class="col-6 mb-3">
                                    <input type="number" name="third-follow-up-number" id="third-follow-up" class="form-control" value="1" disabled>
                                </div>
                                <div class="col-6 mb-3">
                                    <select name="third-follow-up-unit" id="third-follow-up-unit" class="form-select" disabled>
                                        <option value="days" selected>Day(s)</option>
                                        <option value="hours">Hour(s)</option>
                                        <option value="minutes">Minute(s)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer row">
                <div class="row">
                    <div class="col-6 mb-1">
                        <button type="button" class="btn btn-secondary btn-block reset-and-close" form="send-bulk-sms-form" data-bs-dismiss="modal" style="width:100%"><i class="fa-solid fa-trash"></i> <?php echo __('Cancel', 'text-domain'); ?></button>
                    </div>
                    <div class="col-6 mb-1">
                        <button type="submit" class="btn btn-primary btn-block send-bulk-sms-submit col-12" form="send-bulk-sms-form" style="width:100%"><i class="fa-regular fa-paper-plane"></i> <?php echo __('Send to', 'text-domain'); ?>&nbsp;<span class="badge bg-light text-dark bulk-sms-recipients-count">0</span>&nbsp;<?php echo __('contacts', 'text-domain'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="disposition-modal" tabindex="-1" role="dialog" aria-labelledby="disposition-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Disposition Contact', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <?php TwilioCSV::disposition_form(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="final-interview-modal" tabindex="-1" role="dialog" aria-labelledby="final-interview-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Final Interview', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <?php TwilioCSV::final_interview_form(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="update-recruit-modal" tabindex="-1" role="dialog" aria-labelledby="update-recruit-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Update Recruit', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <?php TwilioCSV::update_recruit_form(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-contact-modal" tabindex="-1" role="dialog" aria-labelledby="add-contact-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo __('Add contact', 'text-domain'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <?php TwilioCSV::add_contact_modal_form(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="begin-interview-form-modal" tabindex="-1" role="dialog" aria-labelledby="begin-interview-form" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo __('Interview Script', 'text-domain'); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <?php TwilioCSV::begin_interview_form(); ?>
            </div>
        </div>
    </div>
</div>