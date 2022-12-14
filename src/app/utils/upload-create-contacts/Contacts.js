const $ = jQuery;
export class TwilioCSVContacts {
    constructor() {
        this.handlers();
    }


    handlers() {

        // Constants
        const submitButton = $('#twiliocsv_upload_form_submit');
        const fileInput = $('#twilio-csv-upload-contacts');
        const successAlert = $('#twilio_csv_upload_form').find('.alert-success');
        const warningAlert = $('#twilio_csv_upload_form').find('.alert-warning');
        const successText = successAlert.find('p.success-text');
        const warningText = warningAlert.find('p.warning-text');
        const summaryText = $('p.upload-summary-text');

        $(fileInput).on('change', function () {
            // console.log('yeet');
            if (fileInput[0].files.length > 0) {
                debug_print('Parsing file...');
                let file = fileInput[0].files[0];
                let formData = new FormData();
                formData.append('file', file);
                formData.append('action', twilio_csv_ajax.action);
                formData.append('nonce', twilio_csv_ajax.nonce);
                formData.append('method', 'parse_sheet');
                parseFile(formData);
                // since javascript is asynchronous
                // handle the response in the done function
            }
        });





        $('#twiliocsv_upload_form').submit(function (e) {
            e.preventDefault();
            debug_print('Uploading file...');
            // Disable the submit button
            submitButton.prop('disabled', true);
            submitButton.html('Uploading...');

            // Get the file
            const file = $('#twilio-csv-upload-contacts')[0].files[0];
            // Get the column indexes
            const firstNameIndex = $('#twilio-csv-column-selector-first-name').val();
            const lastNameIndex = $('#twilio-csv-column-selector-last-name').val();
            const phoneIndex = $('#twilio-csv-column-selector-phone-number').val();
            const emailIndex = $('#twilio-csv-column-selector-email').val();
            const cityIndex = $('#twilio-csv-column-selector-city').val();
            const stateIndex = $('#twilio-csv-column-selector-state').val();
            const sourceIndex = $('#twilio-csv-column-selector-source').val();

            // Create the form data
            let formData = new FormData();
            formData.append('file', file);
            formData.append('action', twilio_csv_ajax.action);
            formData.append('nonce', twilio_csv_ajax.nonce);
            formData.append('method', 'upload_contacts');
            formData.append('_first_name-col', firstNameIndex);
            formData.append('_last_name-col', lastNameIndex);
            formData.append('_phone-col', phoneIndex);
            formData.append('_email-col', emailIndex);
            formData.append('_city-col', cityIndex);
            formData.append('_state-col', stateIndex);
            formData.append('_source-col', sourceIndex);


            // AJAX Router
            $.ajax({
                url: twilio_csv_ajax.ajax_url,
                processData: false,
                contentType: false,
                type: 'POST',
                data: formData
            }).done(function (response) {
                let responseMessage = '';
                for (let i = 0; i < response.length; i++) {
                    // if any responses have 'error' index
                    if (response[i].error) {
                        responseMessage += '<li>' + response[i].error + '</li>';
                    } else if (response[i].contact) {
                        responseMessage += '<li>Created contact: ' + response[i].contact + '</li>';
                    }
                };
                debug_print('<strong>Done!</strong><p>Results:</p><ul>' + responseMessage + '</ul>');
                console.log(response);
                submitButton.prop('disabled', false);
                submitButton.html('Upload');
            });

        });
    }
}

function debug_print($message) {
    let summaryText = jQuery('p.upload-summary-text');
    let summaryCard = jQuery('div.upload-summary-card');
	// remove d-none if it exists
    if (summaryCard.hasClass('d-none')) {
        summaryCard.removeClass('d-none');
    }
    summaryText.html($message);
}

async function parseFile(formData) {
    const submitButton = jQuery('#twiliocsv_upload_form_submit');
    // disable submit button
    submitButton.prop('disabled', true);
    submitButton.html('Parsing...');
    // pass FormData object to ajax
    jQuery.ajax({
        url: twilio_csv_ajax.ajax_url,
        processData: false,
        contentType: false,
        type: 'POST',
        data: formData
    }).done(function (response) {
        // enable submit button
        submitButton.prop('disabled', false);
        show_column_selector(response);
        submitButton.html('Upload ' + response.rows + ' Contacts');
        debug_print('<strong>Done!</strong><p>Found ' + response.rows + ' contacts.</p><p>Found the following columns:</p><ol>' + response.headerNames + '</ol>');
        console.log(response);
    });
}

function show_column_selector(options) {
    const selectGroupDiv = jQuery('#column-select');
    const firstNameRow = jQuery('#twilio-csv-column-selector-first-name');
    const lastNameRow = jQuery('#twilio-csv-column-selector-last-name');
    const phoneRow = jQuery('#twilio-csv-column-selector-phone-number');
    const emailRow = jQuery('#twilio-csv-column-selector-email');
    const cityRow = jQuery('#twilio-csv-column-selector-city');
    const stateRow = jQuery('#twilio-csv-column-selector-state');
    const sourceRow = jQuery('#twilio-csv-column-selector-source');
    // remove hidden from select group
    selectGroupDiv.prop('hidden', false);
    jQuery.each(options.headers, function (index, value) {
        // add options to select
        firstNameRow.append(jQuery('<option>', {value: index, text: value}));
        lastNameRow.append(jQuery('<option>', {value: index, text: value}));
        phoneRow.append(jQuery('<option>', {value: index, text: value}));
        emailRow.append(jQuery('<option>', {value: index, text: value}));
        cityRow.append(jQuery('<option>', {value: index, text: value}));
        stateRow.append(jQuery('<option>', {value: index, text: value}));
        sourceRow.append(jQuery('<option>', {value: index, text: value}));        
    });
}
