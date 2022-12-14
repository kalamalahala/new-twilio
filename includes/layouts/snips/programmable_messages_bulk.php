<?php

/** snip to include programmable messages inside of the Bulk SMS form */

$messages = TwilioCSVProgrammableMessages::get_programmable_messages('Bulk Message');
$classes = 'programmable-message-selector';
?>


    <div class="btn-group my-2">
        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa-solid fa-envelopes-bulk"></i> <?php echo __('Select Programmed Message', 'text-domain'); ?>
        </button>
        <div class="dropdown-menu mb-3">
            <?php
            if (count($messages) > 0) {

                foreach ($messages as $message) {
                    $title = $message['_title'];
                    $body = $message['_body'];
                    echo '<a class="dropdown-item ' . $classes . '" href="#" data-message="' . $body . '">' . $title . '</a>';
                }
            } else {
                echo '<a class="dropdown-item link-danger" href="#"><i class="fa-solid fa-triangle-exclamation"></i> ' . __('No Bulk Messages available!', 'text-domain') . '</a>';
            }
            ?>
        </div>
    </div>