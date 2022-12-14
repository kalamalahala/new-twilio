<?php

/** Includeable form to update disposition of a given contact */

(int)$contact_id = TwilioCSVMessage::_get_most_recent_message();
if ($contact_id !== false) {
    $contact = new TwilioCSVContact($contact_id);
    $full_name = $contact->get_first_name() . ' ' . $contact->get_last_name();
    $phone = $contact->get_phone();
}
?>
<form id="update-disposition-form-inline" class="row row-cols-lg-auto g-3 align-items-center mb-3">
    <input type="hidden" id="disposition-form-contact-id" name="contact-id" value="<?php if (isset($contact_id)) {
                                                                                        echo $contact_id;
                                                                                    } else {
                                                                                        echo '';
                                                                                    } ?>">
    <input type="hidden" id="disposition-form-contact-name" name="disposition-form-contact-name" value="<?php echo $full_name; ?>">
    <div class="col-12">
        <label for="update-disposition"><small><?php echo __('Update Disposition', 'text-domain'); ?></small></label>
    </div>
    <div class="col-12">
        <select id="update-disposition" name="update-disposition" class="form-select">
            <?php
            $dispositions = TwilioCSVContact::_list_dispositions();
            if ($contact_id) {
                $contact = new TwilioCSVContact($contact_id);
                $current_disposition = $contact->get_disposition();
            } else {
                $current_disposition = '';
            }
            foreach ($dispositions as $disposition) {
                if ($disposition == $current_disposition) {
                    echo '<option value="' . $disposition . '" selected>' . $disposition . '</option>';
                } else {
                    echo '<option value="' . $disposition . '">' . $disposition . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="col-12">
        <button name="submit" type="submit" class="btn btn-sm btn-primary"><i class="fa-regular fa-pen-to-square"></i> <?php echo __('Disposition', 'text-domain'); ?></button>
    </div>
    <div class="col-12">
        <div id="inline-disposition-success" class="alert alert-success alert-dismissible fade show d-none" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Success!</strong> <span>Alert Text</span>
        </div>
    </div>
    <div class="col-12">
        <div id="inline-disposition-warning" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Error!</strong> <span> Text</span>
        </div>
    </div>
</form>