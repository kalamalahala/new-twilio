<?php
/** View All Replies */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}



?>

<div class="wrap">
<?php TwilioCSV::navbar(); ?>
<?php TwilioCSV::modals(); ?>

<div class="content-wrap">
        <div class="card border-secondary" style="max-width:18rem;">
            <div class="card-header">
                <h4><?php echo __('All Replies', 'text-domain'); ?></h4>
            </div>
            <div class="card-body">
                <p class="card-text"><?php echo __('Need to upload a list of contacts?', 'text-domain'); ?></p>
                <a href="?page=twilio-csv-upload-contacts" class="btn btn-primary"><?php echo __('Click Here', 'text-domain'); ?></a>
            </div>
        </div>
        <div class="table-wrapper">
            <!-- Contacts DataTable Frame - Populate via AJAX -->
            <table class="table" id="twilio-csv-messages-table" style="width:100%">
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
                    <th>Body</th> <!-- 8 Message -->
                    <th>Actions</th> <!-- 9 Actions -->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    </div>
</div>