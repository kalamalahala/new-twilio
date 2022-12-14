<?php

/**
 * 
 * datatable to display Pending Hires to push to final interview
 */

?>

<div class="wrap">
    <?php TwilioCSV::navbar(); ?>
    <?php TwilioCSV::modals(); ?>

    <div class="content-wrap">
        <div class="table-wrapper">
            <!-- Contacts DataTable Frame - Populate via AJAX -->
            <table class="table" id="twilio-csv-pending-hires-table" style="width:100%">
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