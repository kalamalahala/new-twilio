const $ = jQuery;
import 'flatpickr';

export const bulkSMSModalHandlers = () => { // handle Bulk SMS Modal features
    // Handle modal form submission
    $(document).on('submit', '#send-bulk-sms-form', function (e) {
        e.preventDefault();
        if ($('#bulk-sms').val().length === 0) {
            return;
        }
        const submitButton = $('.send-bulk-sms-submit');
        const originalSubmitHTML = submitButton.html();
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

        let formData = new FormData(this);
        let ajaxUrl = twilio_csv_ajax.ajax_url;
        let action = twilio_csv_ajax.action;
        let nonce = twilio_csv_ajax.nonce;
        let method = 'send_bulk_sms';

        formData.append('action', action);
        formData.append('nonce', nonce);
        formData.append('method', method);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () {
                $('.send-bulk-sms-submit').prop('disabled', false);
                $('.send-bulk-sms-submit').html(originalSubmitHTML);
                $('#send-bulk-sms').modal('hide');
                // reload the DataTables table
                $('#twilio-csv-contacts-table').DataTable().ajax.reload();
            }
        });
    });

    // Don't repeat yourself? Yeah, right!

    $(document).on('change', '.schedule-bulk-radio', () => {
        const selectedValue = $('.schedule-bulk-radio:checked').val();
        if (selectedValue === 'Yes') {
            showAndEnableFields('.schedule-bulk-message');
        } else {
            hideAndDisableFields('.schedule-bulk-message');
        }
    });

    $(document).on('change', 'input[name=num-follow-up-messages]', () => {
        const selectedValue = $('input[name=num-follow-up-messages]:checked').val();
        console.log('selectedValue', selectedValue);
        if (selectedValue === '1') {
            showAndEnableFields('.first-follow-up-container');
            hideAndDisableFields('.second-follow-up-container');
            hideAndDisableFields('.third-follow-up-container');
        } else if (selectedValue === '2') {
            showAndEnableFields('.first-follow-up-container');
            showAndEnableFields('.second-follow-up-container');
            hideAndDisableFields('.third-follow-up-container');
        } else if (selectedValue === '3') {
            showAndEnableFields('.first-follow-up-container');
            showAndEnableFields('.second-follow-up-container');
            showAndEnableFields('.third-follow-up-container');
        } else {
            hideAndDisableFields('.first-follow-up-container');
            hideAndDisableFields('.second-follow-up-container');
            hideAndDisableFields('.third-follow-up-container');
        }
    });

    const showAndEnableFields = (selector) => {
        $(selector).removeClass('d-none');
        $(selector).find('input, textarea, select').removeAttr('disabled');
    }

    const hideAndDisableFields = (selector) => {
        $(selector).addClass('d-none');
        $(selector).find('input, textarea, select').attr('disabled', 'disabled');
    }


    // const resetAndCloseButton  = $('.reset-and-close');
    $(document).on('click', '.reset-and-close', function (e) {
        e.preventDefault();
        hideAndDisableFields('.schedule-bulk-message');
        hideAndDisableFields('.first-follow-up-container');
        hideAndDisableFields('.second-follow-up-container');
        hideAndDisableFields('.third-follow-up-container');

        $('#send-bulk-sms').modal('hide');
        $('#send-bulk-sms-form').trigger('reset');
    });


    // flatpickr for #send-time
    flatpickr('#send-time', {
        static: false,
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        time_24hr: false,
        altInput: true,
        altFormat: "F j, Y \\a\\t h:i K",
    });

}

// Populate fields based on selected rows
export function populateBulkSMSModal(dataTableElement) {
    const smsMessageField = $('#bulk-sms');
    smsMessageField.val('');
    $('.bulk-sms-remaining').text('160');
    $('#bulk-sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    $('.send-bulk-sms-submit').prop('disabled', false);

    let selectedRows = dataTableElement.rows('.selected').count();
    $('.bulk-sms-recipients-count').text(selectedRows);
    $('#contact-count').val(selectedRows);

    // collect the selected rows data-id attributes into a comma separated string
    let selectedRowsIds = [];
    dataTableElement.rows('.selected').every(function () {
        selectedRowsIds.push(this.data().id);
    });
    let selectedRowsIdsString = selectedRowsIds.join(',');
    console.log(selectedRowsIdsString);
    $('#contact-id-list').val(selectedRowsIdsString);

}

export const bulkSMSKeyup = () => {
    // Decrement .bulk-sms-remaining based on lenth of #bulk-sms message on keyup
    $(document).on('keyup', '#bulk-sms', function () {
        let smsLength = $(this).val().length;
        let smsRemaining = 160 - smsLength;

        $('.bulk-sms-remaining').text(smsRemaining);

        if (smsRemaining < 0) {
            $('#bulk-sms-remaining-text').removeClass('text-muted').addClass('text-danger');
        } else {
            $('#bulk-sms-remaining-text').removeClass('text-danger').addClass('text-muted');
        }

    });
}

export const listenBulkSMSKeyUp = (field) => {
    let smsLength = field.val().length;
    let smsRemaining = 160 - smsLength;
    $('.bulk-sms-remaining').text(smsRemaining);

    if (smsRemaining < 0) {
        $('#bulk-sms-remaining-text').removeClass('text-muted').addClass('text-danger');
    } else {
        $('#bulk-sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    }
}