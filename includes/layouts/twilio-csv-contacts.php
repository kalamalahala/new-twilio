<?php

/**
 * Contacts layout for Twilio CSV plugin.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


$start = TwilioCSV::localized_date();
// format to Y-d-m H:i:s
$start = date('Y-d-m H:i:s', strtotime($start));

$scheduled_callbacks = TwilioCSVSchedule::_get_schedules_by_date_range_and_action('Call Back', $start);

// size of $scheduled_callbacks
$size = count($scheduled_callbacks);
if ($size > 0) {
    $dnone = '';
} else {
    $dnone = 'd-none';
}

?>

<div class="wrap">
    <?php TwilioCSV::navbar(); ?>
    <?php TwilioCSV::modals(); ?>

    <div class="content-wrap">
        <div class="card border-secondary" style="max-width:18rem;">
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
        <div class="card border-secondary <?php echo $dnone; ?>" style="max-width:18rem;">
            <div class="card-header">
                <h4><?php echo __('Scheduled Callbacks', 'text-domain'); ?></h4>
            </div>
            <div class="card-body">
                <?php var_dump($scheduled_callbacks); ?>
                <?php TwilioCSV::dump($start); ?>
                <p class="card-text"><?php echo __('Need to upload a list of contacts?', 'text-domain'); ?></p>
                <a href="?page=twilio-csv-upload-contacts" class="btn btn-sm btn-primary mb-2"><?php echo __('Click Here', 'text-domain'); ?></a>
                <p class="card-text"><?php echo __('Want to add a single contact?', 'text-domain'); ?></p>
                <a name="add-new-contact-launch-modal" id="add-new-contact-launch-modal" class="btn btn-sm btn-primary" href="#" role="button"><?php echo __('Add New Contact', 'text-domain'); ?></a>
            </div>
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