<?php

/**
 * Logs layout for Twilio CSV plugin.
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
$key = new TwilioCSVEmailHandler();
?>

<div class="wrap">
<?php TwilioCSV::navbar(); ?>

<a name="send-email" id="send-email" class="btn btn-primary" href="#" role="button">Send Test Email</a>




<p>

</p>
</div>