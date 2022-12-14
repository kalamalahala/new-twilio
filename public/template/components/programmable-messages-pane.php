<?php

/**
 * 
 *  form to create, edit, and delete preprogrammed messages
 */

$programmed_messages = TwilioCSVProgrammableMessages::get_programmable_messages();

?>
<div class="wrap">
    <div class="content-wrap">

        <!-- tab list View Messages, Create New Message -->
        <ul class="nav nav-tabs" id="twilio-csv-programmable-messages-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="twilio-csv-programmable-messages-view-tab" data-bs-toggle="tab" href="#twilio-csv-programmable-messages-view" role="tab" aria-controls="twilio-csv-programmable-messages-view" aria-selected="true">View Messages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="twilio-csv-programmable-messages-create-tab" data-bs-toggle="tab" href="#twilio-csv-programmable-messages-create" role="tab" aria-controls="twilio-csv-programmable-messages-create" aria-selected="false">Create New Message</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="twilio-csv-programmable-messages-edit-tab" data-bs-toggle="tab" href="#twilio-csv-programmable-messages-edit" role="tab" aria-controls="twilio-csv-programmable-messages-edit" aria-selected="false">Edit Messages</a>
            </li>


        </ul>

        <!-- tab content -->
        <div class="tab-content" id="twilio-csv-programmable-messages-tabs-content">
            <div class="tab-pane fade show active" id="twilio-csv-programmable-messages-view" role="tabpanel" aria-labelledby="twilio-csv-programmable-messages-view-tab">
                <div class="settings-wrapper my-2">

                    <!-- Display programmed messages in table, Name, body, and Edit / Delete -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="programmable-messages-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Body</th>
                                    <th scope="col">Message Type</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // TwilioCSV::dump($programmed_messages);
                                // if ($programmed_messages) {
                                //     foreach ($programmed_messages as $message) {
                                //         echo '<tr>';
                                //         echo '<td>' . $message['_title'] . '</td>';
                                //         echo '<td>' . $message['_body'] . '</td>';
                                //         echo '<td><a href="#" class="message-action btn btn-primary" data-action="edit_message" data-id="' . $message['id'] . '"><i class="fa fa-pencil"></i> Edit</a></td>';
                                //         echo '<td><a href="#" class="message-action btn btn-danger" data-action="delete_message" data-id="' . $message['id'] . '"><i class="fa-solid fa-trash-can"></i> Delete</a></td>';
                                //         echo '</tr>';
                                //     }
                                // } else {
                                //     echo '<tr><td colspan="4"><h3>No Messages Found!</h3><p>Create a message using the link above.</p></td></tr>';
                                // }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="twilio-csv-programmable-messages-create" role="tabpanel" aria-labelledby="twilio-csv-programmable-messages-create-tab">
                <div class="settings-wrapper my-2 w-75">

                    <form id="create-programmable-message-form">
                        <div class="row mt-3 mb-3">
                            <label for="_title" class="col-sm-4 col-form-label"><?php echo __('Message Title'); ?></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-heading"></i></span>

                                    <input id="_title" name="_title" placeholder="Friendly Name" type="text" class="form-control" aria-describedby="_titleHelpBlock">
                                </div>
                                <span id="_titleHelpBlock" class="form-text text-muted"><?php echo __('Enter a name for your message, for example "Quick Response: Call Back"'); ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <label for="_body" class="col-sm-4 col-form-label"><?php echo __('Message Body'); ?></label>
                            <div class="col-sm-8">
                                <textarea id="_body" name="_body" cols="40" rows="5" class="form-control" aria-describedby="_bodyHelpBlock"></textarea>
                                <span id="_bodyHelpBlock" class="form-text text-muted mb-3"><span class="_body-characters-remaining">160</span> <?php echo __('characters remaining') . '.'; ?></span>
                                <div class="merge-tag-selector-container my-3"><?php echo __('Dynamic Tags') . ':'; ?><?php echo TwilioCSV::snip_merge_tags(); ?></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-form-label">
                                <label for="programmble_message_type">Message Type</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Individual Message" id="type_selector1" name="_type">
                                    <label class="form-check-label" for="type_selector1">
                                        Individual Message
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Bulk Message" id="type_selector2" name="_type" checked>
                                    <label class="form-check-label" for="type_selector2">
                                        Bulk Message
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-sm-4 col-sm-8">
                                <button name="submit" type="submit" class="btn btn-primary"><?php echo __('Create Message'); ?></button>
                                <button type="reset" class="btn btn-secondary"><?php echo __('Clear'); ?></button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <div class="tab-pane fade" id="twilio-csv-programmable-messages-edit" role="tabpanel" aria-labelledby="twilio-csv-programmable-messages-edit-tab">
                <div class="settings-wrapper my-2 w-75">

                    <form id="edit-programmable-message-form">
                        <div class="row mt-3">
                            <label for="id" class="col-sm-4 col-form-label"><?php echo __('Select Message'); ?></label>
                            <div class="col-sm-8">
                                <select id="id" name="id" class="form-select">
                                    <option value=""><?php echo __('Select a message'); ?></option>
                                    <?php
                                    if ($programmed_messages) {
                                        foreach ($programmed_messages as $message) {
                                            echo '<option value="' . $message['id'] . '">' . $message['_title'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="row mt-3 mb-3">
                            <label for="_title" class="col-sm-4 col-form-label"><?php echo __('Message Title'); ?></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-heading"></i></span>
                                    <input id="_title" name="_title" placeholder="Friendly Name" type="text" class="form-control" aria-describedby="_titleHelpBlock">
                                </div>
                                <div id="_titleHelpBlock" class="form-text text-muted"><?php echo __('Enter a name for your message such as "Quick Response: Call Back"'); ?></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="_body" class="col-sm-4 col-form-label"><?php echo __('Message Body'); ?></label>
                            <div class="col-sm-8">
                                <textarea id="_body" name="_body" cols="40" rows="5" class="form-control" aria-describedby="_bodyHelpBlock"></textarea>
                                <span id="_bodyHelpBlock" class="form-text text-muted"><span class="_body-characters-remaining">160</span><?php echo __(' characters remaining');
                                                                                                                                            echo '.'; ?></span>
                                <div class="merge-tag-selector-container my-3"><?php echo __('Dynamic Tags') . ':' ?> <?php echo TwilioCSV::snip_merge_tags(); ?></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-form-label">
                                <label for="programmble_message_type">Message Type</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Individual Message" id="type_selector3" name="_type">
                                    <label class="form-check-label" for="type_selector3">
                                        Individual Message
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Bulk Message" id="type_selector4" name="_type" checked>
                                    <label class="form-check-label" for="type_selector4">
                                        Bulk Message
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-sm-4 col-sm-8">
                                <button name="submit" type="submit" class="btn btn-primary"><?php echo __('Update Message'); ?></button>
                                <button type="reset" class="btn btn-secondary"><?php echo __('Clear'); ?></button>
                            </div>
                        </div>
                        <div class="alert alert-success programmable-update-success d-none">
                            <i class="fa-solid fa-checkmark"></i><strong> <?php echo __('Success');
                                                                            echo '!'; ?></strong> <?php echo __('Message updated');
                                                                                                    echo '.'; ?>
                        </div>
                        <div class="alert alert-warning programmable-update-warning d-none">
                            <i class="fa-solid fa-xmark"></i><strong> <?php echo __('Warning');
                                                                        echo '!' ?></strong> <?php echo __('Error in message response. Please try again or refresh the page.'); ?>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>