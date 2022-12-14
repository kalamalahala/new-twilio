<?php

/** Adds clickable buttons to insert {{VARIABLES}} in the closest form */

$tags = TwilioCSVMessage::merge_tags();

// Add custom class of the current admin page, if any
$classes = 'merge-tag-selector';
// $page = (!is_null($_GET['page'])) ? $_GET['page'] : false;
// if ($page) {
//     $classes .= ' ' . $page;
// }

?>

<div class="btn-group">
    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa-solid fa-code"></i> <?php echo __('Insert Variable', 'text-domain'); ?>
    </button>
    <div class="dropdown-menu">
        <?php
            foreach ($tags as $tag => $friendly_name) {
                echo '<a class="dropdown-item ' . $classes . '" href="#" data-tag="' . $tag . '">' . $friendly_name . '</a>';
            }
        ?>
    </div>
</div>