<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://thejohnson.group/
 * @since      1.0.0
 *
 * @package    Twilio_Csv
 * @subpackage Twilio_Csv/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap ml-0 mt-0">
    <?php TwilioCSV::navbar(); ?>
    <div class="content-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="mt-4"><?php echo __('Dashboard', 'text-domain'); ?></h1>
                    <p class="lead"><?php echo __('Welcome to the TwilioCSV dashboard.', 'text-domain'); ?></p>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem">
                        <img class="card-img-top" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/assets/img/twilio2.png' ?>" alt="Fill out the template and upload it.">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo __('Step 1: Upload your job seekers', 'text-domain'); ?></h5>
                            <p class="card-text"><?php echo __('Fill out the provided template with contact information and upload them to form your lead list.', 'text-domain'); ?></p>
                            <a href="?page=twilio-csv-upload-contacts" class="btn btn-primary"><?php echo __('Upload', 'text-domain'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem">
                        <img class="card-img-top" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/assets/img/twilio3.png' ?>" alt="Reach out to your contacts">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo __('Step 2: Reach Out', 'text-domain'); ?></h5>
                            <p class="card-text"><?php echo __('Send programmable messages to your lead list with automatic follow-ups and dispositions. Advanced filtering helps you separate the wheat from the chaff.', 'text-domain'); ?></p>
                            <a href="?page=twilio-csv-contacts" class="btn btn-primary"><?php echo __('View Your Contacts', 'text-domain'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem">
                        <img class="card-img-top" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/assets/img/twilio4.png' ?>" alt="Interview inside the software">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo __('Step 3: Interview', 'text-domain'); ?></h5>
                            <p class="card-text"><?php echo __('TwilioCSV includes a customizable interview process and email templating. Check the settings for more options!', 'text-domain'); ?></p>
                            <a href="?page=twilio-csv-settings" class="btn btn-primary"><?php echo __('Customize', 'text-domain'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="width: 18rem">
                        <img class="card-img-top" src="<?php echo plugin_dir_url(__FILE__) . '../../includes/assets/img/twilio1.png' ?>" alt="Powered By Twilio">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo __('Powered by Twilio', 'text-domain'); ?></h5>
                            <p class="card-text"><?php echo __('TwilioCSV is a WordPress CRM platform designed to empower your recruiting process. Stay in contact from the first message to the hire!', 'text-domain'); ?></p>
                            <a href="#" class="btn btn-primary"><?php echo __('Learn More', 'text-domain'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>