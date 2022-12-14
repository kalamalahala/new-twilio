<?php

/**
 * Dynamic nav bar for the TwilioCSV Plugin
 */
//<a class="nav-link" href="?page=twilio-csv-contacts">
// <?php echo ($_GET['page'] === 'twilio-csv-contacts') ? 'active' : '';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand h1" href="?page=twilio-csv">TwilioCSV <?php echo __('Dashboard', 'text-domain'); ?></a>
    <button class="navbar-toggler" data-target="#twilio-csv-nav" data-bs-toggle="collapse" aria-controls="twilio-csv-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>


    <div id="twilio-csv-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">


            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv') ? 'active' : ''; ?>">
                <a class="nav-link" href="?page=twilio-csv"><?php echo __('Dashboard', 'text-domain'); ?> <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown ">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown"><?php echo __('Contacts', 'text-domain'); ?></button>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item <?php echo ($_GET['page'] === 'twilio-csv-active-contacts') ? 'active' : ''; ?>" href="?page=twilio-csv-active-contacts"><?php echo __('Active Contacts', 'text-domain'); ?></a>
                    <a class="dropdown-item <?php echo ($_GET['page'] === 'twilio-csv-contacts') ? 'active' : ''; ?>" href="?page=twilio-csv-contacts"><?php echo __('All Contacts', 'text-domain'); ?></a>
                    <a class="dropdown-item <?php echo ($_GET['page'] === 'twilio-csv-upload-contacts') ? 'active' : ''; ?>" href="?page=twilio-csv-upload-contacts"><?php echo __('Upload Contacts', 'text-domain'); ?></a>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add-contact-modal"><?php echo __('Add New Contact', 'text-domain'); ?></a>
                </div>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-pending-hires') ? 'active' : ''; ?>">
                <a href="?page=twilio-csv-pending-hires" class="nav-link"><?php echo __('Pending Hires', 'text-domain') ?></a>
            </li>
            
            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-recruits') ? 'active' : ''; ?>">
                <a href="?page=twilio-csv-recruits" class="nav-link"><?php echo __('Recruits', 'text-domain') ?></a>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-conversations') ? 'active' : ''; ?>">
                <a href="?page=twilio-csv-conversations" class="nav-link"><?php echo __('Conversations', 'text-domain'); ?></a>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-programmable-messages') ? 'active' : ''; ?>">
                <a href="?page=twilio-csv-programmable-messages" class="nav-link"><?php echo __('Programmable Messages', 'text-domain'); ?></a>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-scheduled-briefings') ? 'active' : ''; ?>">
                <a href="?page=twilio-csv-scheduled-briefings" class="nav-link"><?php echo __('Scheduled Briefings', 'text-domain'); ?></a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo __('Campaigns', 'text-domain'); ?></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item <?php echo ($_GET['page'] === 'twilio-csv-email-campaigns') ? 'active' : ''; ?>" href="?page=twilio-csv-email-campaigns"><?php echo __('Email Campaigns', 'text-domain'); ?></a>
                    <a class="dropdown-item <?php echo ($_GET['page'] === 'twilio-csv-sms-campaigns') ? 'active' : ''; ?>" href="?page=twilio-csv-sms-campaigns"><?php echo __('SMS Campaigns', 'text-domain'); ?></a>
                </div>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-logs') ? 'active' : ''; ?>">
                <a class="nav-link" href="?page=twilio-csv-logs"><?php echo __('Logs', 'text-domain'); ?></a>
            </li>

            <li class="nav-item <?php echo ($_GET['page'] === 'twilio-csv-settings') ? 'active' : ''; ?>">
                <a class="nav-link" href="?page=twilio-csv-settings"><?php echo __('Settings', 'text-domain'); ?></a>
            </li>

        </ul>
    </div>
</nav>

<div class="toast-container position-fixed bottom-0 end-0 p3 toast-stack-container">

<div id="default-notification-toast" role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-bs-autohide="false" data-bs-toast="stack">
    <div class="toast-header">
        <i class="fa-solid fa-bell"></i>
        <strong class="me-auto noftification-origin">&nbsp;Notification Origin</strong>
        <small class="notification-time-ago">11 mins ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body notification-body">"Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex non vel ..."<a href="#" target="_blank" title="View Message" class="link-primary"><small class="fts-italic text-primary"> (read more)</small></a>
    </div>
</div>
</div>