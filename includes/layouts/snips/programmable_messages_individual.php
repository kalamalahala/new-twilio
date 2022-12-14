<?php

/** snip to include programmable messages inside of the Bulk SMS form */

$messages = TwilioCSVProgrammableMessages::get_programmable_messages('Individual Message');
$classes = 'programmable-message-selector';
?>


    <div class="btn-group my-2">
        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo __('Select Programmed Message', 'text-domain'); ?>
        </button>
        <div class="dropdown-menu mb-3">
            <?php
            foreach ($messages as $message) {
                $title = $message['_title'];
                $body = $message['_body'];
                echo '<a class="dropdown-item ' . $classes . '" href="#" data-message="' . $body . '">' . $title . '</a>';
            }
            ?>
        </div>
    </div>