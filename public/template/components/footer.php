<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

?>

<div class="fixed-bottom">
    <div class="row container-fluid footer-box d-flex align-items-center mx-auto">

        <a href="https://console.twilio.com" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo plugin_dir_url(__FILE__) . '../img/logo.png'; ?>" alt="Twilio Logo" class="img-fluid" width="75px" height="25px">
        </a>

        <div class="copyright-info ml-auto">

            <div class="row">
                <div class="col-sm-12">
                    <p class="mb-0"><i class="fa fa-copyright" aria-hidden="true"></i> <?php echo date('Y'); ?> | v<?php echo TwilioCSV::version(); ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Report Issues -->
                    <a href="https://github.com/kalamalahala" target="_blank" title="Tyler Karle">Tyler Karle</a> | <a class="badge badge-info" href="mailto:tyler@thejohnson.group?subject=Twilio CSV Plugin Issue">Report Issue</a>
                </div>
            </div>

        </div>
    </div>
</div>