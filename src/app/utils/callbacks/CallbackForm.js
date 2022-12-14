const $ = jQuery;
export class TwilioCSVCallbackForm {
    constructor() {
        this.listeners();
    }
    listeners() {

        $(document).on('submit', '#schedule-callback', function (e) {
            e.preventDefault();
            const submitButton = $('#schedule-callback').find('button[type="submit"]');
            submitButton.prop('disabled', true);
            submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Scheduling...');
            let formData = new FormData(this);

            const ajax_url = twilio_csv_ajax.ajax_url;
            const action = twilio_csv_ajax.action;
            const nonce = twilio_csv_ajax.nonce;
            const method = 'create_scheduled_item';

            formData.append('action', action);
            formData.append('nonce', nonce);
            formData.append('method', method);

            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    $('#callback-warning').addClass('d-none');
                    $('#callback-success').removeClass('d-none');

                },
                error: function (error) {
                    console.log(error);
                    $('#callback-success').addClass('d-none');
                    $('#callback-warning').removeClass('d-none');
                },
                complete: function () {
                    submitButton.prop('disabled', false);
                    submitButton.text('Submit');
                    $('#schedule-callback').trigger('reset');
                }
            });

        });
    }
}
