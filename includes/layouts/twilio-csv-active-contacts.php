<?php

/**
 * Contacts layout for Twilio CSV plugin.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}



?>

<div class="wrap">
    <?php TwilioCSV::navbar(); ?>
    <?php TwilioCSV::modals(); ?>

    <div class="content-wrap">
        <div class="row align-items-end">
            <div class="col-2 mt-3">

                <div class="card border-secondary">
                    <!-- style="max-width:18rem;" -->
                    <div class="card-header">
                        <h4><?php echo __('Upload Contacts', 'text-domain'); ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo __('Need to upload a list of contacts?', 'text-domain'); ?></p>
                        <a href="?page=twilio-csv-upload-contacts" class="btn btn-sm btn-primary mb-2"><?php echo __('Click Here', 'text-domain'); ?></a>
                        <p class="card-text"><?php echo __('Want to add a single contact?', 'text-domain'); ?></p>
                        <a name="add-new-contact-launch-modal" id="add-new-contact-launch-modal" class="btn btn-sm btn-primary" href="#" role="button"><?php echo __('Add New Contact', 'text-domain'); ?></a>
                    </div>
                </div>
            </div>

            <!-- Scheduled Follow Ups

            <div class="col-10 mt-3">
                <div class="scheduled-follow-ups">
                    <h4 class="mt-2 ml-2">Follow Ups</h4>
                    <div class="follow-ups">
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>

                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                        <div class="follow-up">

                            <div class="follow-up-list-items-header">
                                <div class="follow-up-name">Tyler Karle</div>
                                <div class="follow-up-date">Date</div>
                            </div>
                            <div class="follow-up-list-notes">
                                notes
                            </div>
                        </div>
                    </div>

                </div>
            </div> -->


        </div>

        <div class="table-wrapper">
            <!-- Contacts DataTable Frame - Populate via AJAX -->
            <table class="table" id="twilio-csv-contacts-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th> <!-- 0 Contact ID -->
                        <th>Select</th> <!-- 1 Select Checkbox -->
                        <th>Date Created</th> <!-- 2 Date Created -->
                        <th>Date Updated</th> <!-- 3 Date Updated -->
                        <th>First Name</th> <!-- 4 First Name -->
                        <th>Last Name</th> <!-- 5 Last Name -->
                        <th>Phone Number</th> <!-- 6 Phone Number -->
                        <th>Email</th> <!-- 7 Email -->
                        <th>City</th> <!-- 8 City -->
                        <th>State</th> <!-- 9 State -->
                        <th>Source</th> <!-- 10 Source -->
                        <th>Status</th> <!-- 11 Status -->
                        <th>Disposition</th> <!-- 12 Disposition -->
                        <th>Actions</th> <!-- 13 Actions -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>