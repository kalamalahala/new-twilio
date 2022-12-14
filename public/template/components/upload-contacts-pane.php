<?php

/**
 * Contacts layout for Twilio CSV plugin.
 */

?>

<div class="wrap">
    <div class="content-wrap">
        <nav>
            <div class="nav nav-tabs" id="uploads-tab" role="tablist">
                <button class="nav-link active" id="nav-upload-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Upload Sheet</button>
                <button class="nav-link" id="nav-single-tab" data-bs-toggle="tab" data-bs-target="#nav-single" type="button" role="tab" aria-controls="nav-create" aria-selected="true">Create Contact</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane active" id="nav-home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="mt-4"><?php echo __('Upload Contacts', 'text-domain'); ?></h1>
                            <p class="lead"><?php echo __('Fill out the template file then upload your contacts here to begin.', 'text-domain'); ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <!-- Upload form for template spreadsheet containing the following columns: -->
                <!-- First Name, Last Name, Email, Phone Number, City, State, Source -->
                <!-- Use Bootstrap 4 form-group row mb-3s and columns -->
                <div class="container-fluid">

                    <form action="" method="post" id="twiliocsv_upload_form">
                        <div class="form-group row mb-3">
                            <label for="twilio-csv-upload-contacts" class="col-sm-2 col-form-label"><?php echo __('Select File', 'text-domain'); ?></label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="twilio-csv-upload-contacts" name="twilio-csv-upload-contacts" accept=".xls,.xlsx">
                            </div>
                        </div>
                        <!-- small text for download link beneath label -->
                        <div class="form-group row mb-3">
                            <label for="twilio-csv-upload-contacts" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <small id="twilio-csv-upload-contacts-help" class="form-text text-muted"><?php echo __('Download a template spreadsheet', 'text-domain'); ?> <a href="<?php echo plugin_dir_url(__FILE__) . '../../includes/assets/template.xlsx'; ?>"><?php echo __('here', 'text-domain'); ?></a></small>
                            </div>
                        </div>
                        <div class="form-group row mb-3 upload-summary-card d-none">
                            <div class="col-sm-10">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Upload Summary</h5>

                                    </div>
                                    <div class="card-body">
                                        <p class="card-text upload-summary-text">Content</p>
                                    </div>
                                    <div class="card-footer">
                                        <?php echo __('Select your columns as needed below.', 'text-domain'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- "Select Columns" -->
                        <!-- Select Option group for each column returned from AJAX -->
                        <!-- Use Bootstrap 4 form-group row mb-3s and columns -->
                        <!-- First Name, Last Name, Phone Number, Email, City, State, Source -->
                        <div id="column-select" hidden>
                            <h3><?php echo __('Select Columns', 'text-domain'); ?></h3>
                            <div class="form-group row mb-3">
                                <div class="col-sm-10">
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('First Name', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-first-name" name="twilio-csv-column-selector-first-name">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for=" twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('Last Name', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-last-name" name="twilio-csv-column-selector-last-name">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('Email', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-email" name="twilio-csv-column-selector-email">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('Phone Number', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-phone-number" name="twilio-csv-column-selector-phone">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('City', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-city" name="twilio-csv-column-selector-city">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('State', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-state" name="twilio-csv-column-selector-state">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="twilio-csv-column-selector" class="col-sm-2 col-form-label"><?php echo __('Source', 'text-domain'); ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="twilio-csv-column-selector-source" name="twilio-csv-column-selector-source">
                                                <option value="0"><?php echo __('Select Column', 'text-domain'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="alert alert-success d-none" role="alert">
                                    <p class="h3">Success!</p>
                                    <p class="success-text">text data</p>
                                </div>
                                <div class="alert alert-warning d-none" role="alert">
                                    <p class="h3">Error</p>
                                    <p class="warning-text">text data</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3 d-inline">
                            <div class="col-sm-10 d-inline">
                                <button type="submit" class="btn btn-primary" id="twiliocsv_upload_form_submit"><?php echo __('Upload', 'text-domain'); ?></button>
                                <button type="reset" class="btn btn-secondary"><?php echo __('Reset', 'text-domain'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="nav-single" role="tabpanel" aria-labelledby="single-tab">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="mt-4"><?php echo __('Create Single Contact', 'text-domain'); ?></h1>
                            <p class="lead"><?php echo __('Add one contact to the database.', 'text-domain'); ?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="single-form-wrap w-50">

                    <?php TwilioCSV::add_contact_modal_form(); ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <!-- Error if upload fails -->
                            <div class="alert alert-warning upload-single-warning d-none" role="alert">
                                <h4 class="alert-heading">Error Adding Contact</h4>
                                <p class="mb-0 error-text">error text</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-success upload-single-success d-none" role="alert">
                                <h4 class="alert-heading">Success!</h4>
                                <p class="mb-0 success-text">success-text</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>