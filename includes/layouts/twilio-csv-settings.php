<?php

/**
 * General settings layout for Twilio CSV plugin.
 * 
 * @package     TwilioCSV
 * @subpackage  TwilioCSV/settings
 * @since       1.1.75
 * @version     1.2.0
 * @author      Tyler Karle <tyler.karle@icloud.com>
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

?>

<!-- Bootstrap 4 Styling -->
<div class="wrap">
<?php TwilioCSV::navbar(); ?>
    <div class="content-wrap">
        <form method="post" action="options.php">
            <?php settings_fields('twilio_csv_settings'); ?>

        <!-- begin Bootstrap 4 Nav Tabs -->
        <ul class="nav nav-tabs" id="twilio-csv-settings-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="twilio-csv-settings-tab" data-bs-toggle="tab" href="#twilio-csv-settings" role="tab" aria-controls="twilio-csv-settings" aria-selected="true">Twilio Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="twilio-csv-sendgrid-tab" data-bs-toggle="tab" href="#twilio-csv-sendgrid" role="tab" aria-controls="twilio-csv-sendgrid" aria-selected="false">SendGrid Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="twilio-csv-misc-tab" data-bs-toggle="tab" href="#twilio-csv-misc" role="tab" aria-controls="twilio-csv-misc" aria-selected="false">Miscellaneous</a>
            </li>
        </ul>
        <div class="tab-content" id="twilio-csv-settings-tabs-content">
            <div class="tab-pane fade show active" id="twilio-csv-settings" role="tabpanel" aria-labelledby="twilio-csv-settings-tab">

            <div class="settings-wrapper my-2">
                <?php do_settings_sections('twilio-csv-twilio-settings'); ?>
            </div>
                
            </div>
            <div class="tab-pane fade" id="twilio-csv-sendgrid" role="tabpanel" aria-labelledby="twilio-csv-sendgrid-tab">
                <div class="settings-wrapper my-2">
                    <?php do_settings_sections('twilio-csv-sendgrid-settings'); ?>

                </div>
            </div>

            <div class="tab-pane fade" id="twilio-csv-misc" role="tabpanel" aria-labelledby="twilio-csv-misc-tab">
                <div class="settings-wrapper my-2">
                    <?php do_settings_sections('twilio-csv-misc-settings'); ?>
                </div>
            </div>
        </div>

                <?php submit_button(); ?>
        </form>

    </div>
</div>