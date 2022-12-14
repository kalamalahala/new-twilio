<?php

// Collect all WP users that are Admins, or have twilio_csv_* roles
$users = get_users(array(
    'role__in' => array('administrator', 'twilio_csv_admin', 'twilio_csv_manager', 'twilio_csv_user'),
));

$settings = new TwilioCSVSettings();
$messaging_service = TwilioCSV::option('twilio_messaging_service_sid');

// get _value from Settings Options table where _option = 'twilio_csv_settings' and _user_id = $user->ID

// $twilio_csv_user_settings = [];
// foreach ($users as $user) {
//     $twilio_csv_user_settings[$user->ID]['settings'] = $settings->_db_get_user_settings($user->ID, 'twilio_csv_settings');
// }

?>

<div class="wrap">
    <div class="content-wrap">
        <div class="row">
            <div class="col-12 mb-3">
                <h3>User Settings</h3>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <form action="" method="post" id="user-admin-form">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h3>Select User to Update</h3>
                            <hr>
                            <div class="mb-3">
                                <label for="select-user" class="form-label">Select User</label>
                                <select class="form-select form-select-lg" name="select-user" id="select-user">
                                    <option selected>-- no user selected --</option>
                                    <?php foreach ($users as $user) : ?>
                                        <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="mb-3">
                                <label for="select-role" class="form-label">Select Role</label>
                                <select class="form-select form-select-lg" name="select-role" id="select-role">
                                    <option selected>-- no role selected --</option>
                                    <option value="administrator">Site Admin</option>
                                    <option value="twilio_csv_admin">Twilio Admin</option>
                                    <option value="twilio_csv_manager">Twilio Manager</option>
                                    <option value="twilio_csv_user">Twilio User</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="mb-3">
                                <label for="select-status" class="form-label">Select Status</label>
                                <select class="form-select form-select-lg" name="select-status" id="select-status">
                                    <option selected>-- no status selected --</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- Select Sending Number -->
                        <div class="col-12 mb-3">
                            <div class="mb-3">
                                <label for="select-sending-number" class="form-label">Select Sending Number</label>
                                <?php
                                $messaging_service = TwilioCSV::option('twilio_messaging_service_sid');
                                if (!$messaging_service) {
                                    echo '<div class="alert alert-danger" role="alert">No Messaging Service has been selected. Please select a Messaging Service in the Twilio Settings.</div>';
                                } else {
                                    $senders = TwilioCSV::sending_numbers($messaging_service);
                                    if (is_array($senders)) {
                                        echo '<select class="form-select form-select-lg" name="select-sending-number" id="select-sending-number">';
                                        echo '<option selected>-- no sending number selected --</option>';
                                        foreach ($senders as $sender) {
                                            echo '<option value="' . $sender['phoneNumber'] . '">' . $sender['phoneNumber'] . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo '<div class="alert alert-danger" role="alert">No Sending Numbers have been found for the selected Messaging Service. Please select a different Messaging Service in the Twilio Settings.</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="mb-3" id="error-success-holder">
                                <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="update-success-holder">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Success!</strong> <span class="text-success" id="update-success-message">User was successfully updated.</span>
                                </div>
                                <div class="alert alert-warning alert-dismissible fade show d-none" role="alert" id="update-warning-holder">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Error!</strong> <span class="text-dark" id="update-warning-message">There was an error updating this user.</span>
                                </div>
                                
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i>&nbsp;<?php echo __('Update User'); ?></button>
                                <button type="reset" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i>&nbsp;<?php echo __('Reset'); ?></button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="col-sm-6 mb-3 d-none" id="user-details">
                <div class="col-12 mb-3">
                    <h3>User Details</h3>
                    <hr>
                    <div class="list-group">
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-2"><i class="fa-regular fa-user"></i>&nbsp;<span id="user-display-name">User Name</span></h5>
                                <small title="Last Seen"><i class="fa-regular fa-eye"></i>&nbsp;Last&nbsp;Seen&nbsp;<span id="user-last-updated">timestamp</span></small>
                            </div>
                            <p class="mb-1">
                                <span class="fw-bold">Role</span>: <span id="user-role">role</span>
                            </p>
                            <p class="mb-1">
                                <span class="fw-bold">Status</span>: <span id="user-status">status</span>
                            </p>
                            <p class="mb-1">
                                <span class="fw-bold">Sending Number</span>: <span id="user-sending-number">sending number</span>
                            </p>
                            <p class="mb-1">
                                <span class="fw-bold">Assigned Contacts: <span class="badge bg-secondary"><i class="fa-solid fa-user"></i>&nbsp;<span id="user-assigned-count">0</span>
                            </p>
                            <small class="text-muted"></small>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>