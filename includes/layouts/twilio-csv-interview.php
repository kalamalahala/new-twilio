<?php

/** container for gravity forms interview scripting */

?>


<div class="wrap">
<?php TwilioCSV::navbar(); ?>
<?php TwilioCSV::modals(); ?>

    <div class="content-wrap">
    <?php 
    echo do_shortcode('[gravityform id="100" title="false" description="false" ajax="true"]');
    ?>
    </div>
</div>