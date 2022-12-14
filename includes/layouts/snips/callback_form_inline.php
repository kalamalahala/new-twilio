<?php

// callback form inline

$contact_id = $_GET['contact_id'] ?? false;
if ($contact_id !== false) {
    $contact = new TwilioCSVContact($contact_id);
    $full_name = $contact->get_first_name() . ' ' . $contact->get_last_name();
    $phone = $contact->get_phone();
}

?>

<form class="row row-cols-lg-auto g-3 align-items-center mb-3" id="schedule-callback">
    <input type="hidden" name="id" value="<?php echo $contact_id ?>" />
    <input type="hidden" name="type" value="Call Back">
    <div class="col-12">
        <label for="_schedule-date" class="visually-hidden"><small>Callback Time</small></label>
    </div>
    <div class="col-12">
        <div class="input-group">
            <input type="text" name="_schedule_date" id="_schedule-date" class="form-control-sm" placeholder="Schedule Callback Time" aria-describedby="schedule-help-text">
            <span class="fa-solid fa-calendar input-group-text" id="schedule-help-text"></span>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-regular fa-clock"></i> Submit
        </button>
    </div>
</form>

<div class="alert alert-success d-none" id="callback-success" role="alert">
    Callback scheduled!
</div>
<div class="alert alert-warning d-none" id="callback-warning" role="alert">
    Callback failed, please try again.
</div>