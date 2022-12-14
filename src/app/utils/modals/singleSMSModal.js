const $ = jQuery;

// #region Send Single SMS Modal
/**
 * Populates the modal with the correct data then opens it
 */
 export const singleSmsModalHandlers = () => { // handle Single SMS Modal
    // Handle modal form submission
    $(document).on('submit', '#send-single-sms-form', function (e) {
        e.preventDefault();

        if ($('#sms').val().length === 0) {
            return;
        }

        $('.send-single-sms-submit').prop('disabled', true);
        $('.send-single-sms-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

        let formData = new FormData(this);
        let ajaxUrl = twilio_csv_ajax.ajax_url;
        let action = twilio_csv_ajax.action;
        let nonce = twilio_csv_ajax.nonce;
        let method = 'send_sms';

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
                // console.log(response.responseJSON.success);
                $('#send-single-sms').modal('hide');
            },
            error: function (error) {
                console.log(error);
                // console.log(error.responseJSON.success);
            },
            complete: function () {
                $('.send-single-sms-submit').prop('disabled', false);
                $('.send-single-sms-submit').text('Send');


                // reload the DataTables table
                $('#twilio-csv-contacts-table').DataTable().ajax.reload();
            }
        });

    }); // end handle modal form submission


}; // end handle Single SMS Modal
// #endregion

export const populateSendSingleModal = (button) => {
    // Populate the contents of the Send Single SMS Modal
    const data = $(this).data();
    const smsModal = $('#send-single-sms');
    const smsMessage = $('#sms');
    let contactFirstName = button.contactFirstName;
    let contactLastName = button.contactLastName;
    let contactPhone = button.contactPhone;
    let contactId = button.id;
    // console.log(button);

    $('#contact-id').val(contactId);
    $('#contact-first-name').val(contactFirstName);
    $('#contact-last-name').val(contactLastName);
    $('#contact-phone').val(contactPhone);
    $('.sms-first-name').text(contactFirstName);
    $('.sms-last-name').text(contactLastName);
    $('.sms-phone').text(contactPhone);
    $('.sms-remaining').text('160');
    $('#sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    $('.send-single-sms-submit').prop('disabled', false);
    smsMessage.val('');
}