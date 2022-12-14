const $ = jQuery;

// #region Add New Contact Modal
// Handle Add New Contact Modal
export const addNewSingleContactHandler = () => {

    $(document).on('click', '#add-new-contact-launch-modal', function (e) {
        e.preventDefault();
        $('#add-contact-modal').modal('show');
    });

    $(document).on('submit', '#add-contact-form', function (e) {
        e.preventDefault();
        const form = $('#add-contact-form');
        const submitButton = $(this).find('button[type=submit]');
        const currentSubmitHTML = submitButton.html();

        // Ajax details
        const ajax_url = twilio_csv_ajax.ajax_url;
        const action = twilio_csv_ajax.action;
        const nonce = twilio_csv_ajax.nonce;
        const wp_user_id = twilio_csv_ajax.current_user_id;
        const method = 'add_contact';

        // error box elements
        const warningBox = $('.upload-single-warning');
        let warningBoxHeader = warningBox.find('.alert-heading');
        let warningBoxText = warningBox.find('.error-text');
        const successBox = $('.upload-single-success');
        let successBoxHeader = successBox.find('.alert-heading');
        let successBoxText = successBox.find('.success-text');

        // Disable button and add spinner
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

        let formData = new FormData(this);
        formData.append('action', action);
        formData.append('nonce', nonce);
        formData.append('method', method);
        formData.append('wp_user_id', wp_user_id);

        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                $(successBoxHeader).text('Contact Added!');
                $(successBoxText).text(response.message);
                warningBox.addClass('d-none');
                successBox.removeClass('d-none');
                // Reset form
                $(this).trigger('reset');
            },
            error: function (error) {
                // console.log(error.responseJSON);
                $(warningBoxHeader).text('Error adding contact!');
                $(warningBoxText).text(error.responseJSON.message);
                successBox.addClass('d-none');
                warningBox.removeClass('d-none');
            },
            complete: function () {
                submitButton.prop('disabled', false);
                submitButton.html(currentSubmitHTML);
            }
        });
    });
}
// #endregion