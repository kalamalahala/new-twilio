<?php

/**
 * Recruits layout for Twilio CSV plugin.
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
        <h4><?php echo __('Recruits / Hires', 'text-domain'); ?></h4>
        <div class="table-wrapper">
            <!-- Contacts DataTable Frame - Populate via AJAX -->
            <table class="table" id="twilio-csv-recruits-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th> <!-- 0 Contact ID -->
                        <th>Select</th> <!-- 1 Select Checkbox -->
                        <th>Interview</th> <!-- 2 Interview -->
                        <th>Last Updated</th> <!-- 3 Last Updated -->
                        <th>Time in XCEL</th> <!-- 4 Time in XCEL -->
                        <th>PLE %</th> <!-- 5 PLE % -->
                        <th>PLE Completed Date</th> <!-- 6 PLE Completed Date -->
                        <th>First Name</th> <!-- 7 First Name -->
                        <th>Last Name</th> <!-- 8 Last Name -->
                        <th>Phone Number</th> <!-- 9 Phone Number -->
                        <th>Email</th> <!-- 10 Email -->
                        <th>Source</th> <!-- 11 Source -->
                        <th>Prep Completion %</th> <!-- 12 Prep Completion % -->
                        <th>Sim Completion %</th> <!-- 13 Sim Completion % -->
                        <th>Prepared to Pass?</th> <!-- 14 Prepared to Pass? -->
                        <th>Actions</th> <!-- 15 Actions -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>