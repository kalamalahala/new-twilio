<?php

/** Includeable form to update disposition of a given contact */

$contact_id = $_GET['contact_id'] ?? false;
?>
<form id="update-disposition-form">
  <div class="form-group">
    <input type="hidden" id="disposition-form-contact-id" name="contact-id" value="<?
                                                                                    if (isset($contact_id)) {
                                                                                      echo $contact_id;
                                                                                    } else {
                                                                                      echo '';
                                                                                    }
                                                                                    ?>">
    <label for="update-disposition"><?php echo __('Update Disposition', 'text-domain'); ?></label>
    <select id="update-disposition" name="update-disposition" class="form-select mt-2 mb-1" aria-describedby="update-dispositionHelpBlock">
      <?php
      $dispositions = TwilioCSVContact::_list_dispositions();
      foreach ($dispositions as $disposition) {
        echo '<option value="' . $disposition . '">' . $disposition . '</option>';
      }
      ?>
    </select>
    <span id="update-dispositionHelpBlock" class="form-text text-muted"><?php echo __('Update disposition for this contact.', 'text-domain'); ?></span>
  </div>
  <div class="form-group mt-3">
    <button name="submit" type="submit" class="btn btn-primary"><?php echo __('Update Disposition', 'text-domain'); ?></button>
  </div>
</form>