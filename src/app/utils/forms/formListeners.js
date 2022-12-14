const $ = jQuery;
import _default from '@popperjs/core/lib/modifiers/popperOffsets';
import flatpickr from 'flatpickr';
const ajaxurl = twilio_csv_ajax.ajax_url;
const nonce = twilio_csv_ajax.nonce;
const action = twilio_csv_ajax.action;

export class TwilioCSVFormListeners {
    constructor() {
        this.listeners();
    }
    listeners() {
        $(document).ready(function () {
            
            // Scheduled Briefings Form
            $(document).on('submit', '#scheduled-briefings-form', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                formData.append('action', action);
                formData.append('nonce', nonce);
                // get ajax method from input #_method
                const dtMethod = $('#_method').val();
                formData.append('method', dtMethod);

                // disable button[type=submit]
                const submitBtn = $(this).find('button[type=submit]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating Briefing...');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response.success == true || response.success == 'true') {
                            // create dismissable bootstrap4 success alert inside .alert-container
                            const alert = `<div class="alert alert-success alert-dismissable fade show" role="alert">${response.message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                            $('.alert-container').html(alert);
                        }

                    },
                    error: function (error) {
                        console.log(error);
                        // create dismissable bootstrap4 error alert inside .alert-container
                        const alert = `<div class="alert alert-danger alert-dismissable fade show" role="alert">${error.message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
                        $('.alert-container').html(alert);
                    },
                    complete: function () {
                        // enable button[type=submit]
                        submitBtn.prop('disabled', false);
                        submitBtn.html('Create Briefing');

                    }
                });

                return true;
            });

            // Update Disposition Inline Form
            $(document).on('submit', '#update-disposition-form-inline', function (e) {
                e.preventDefault();
                const method = 'update_disposition';

                // buttons and alerts
                const submitButton = $(this).find('button[type=submit]');
                const currentSubmitHTML = submitButton.html();
                const contactName = $(this).find('input[name=disposition-form-contact-name]').val();
                const successBox = $('#inline-disposition-success');
                const warningBox = $('#inline-disposition-warning');
                const successBoxHeader = successBox.find('strong');
                const warningBoxHeader = warningBox.find('strong');
                const successBoxMessage = successBox.find('span');
                const warningBoxMessage = warningBox.find('span');

                // form data
                const formData = new FormData(this);
                formData.append('action', action);
                formData.append('nonce', nonce);
                formData.append('method', method);

                const selectedDisposition = $(this).find('select[name=update-disposition]').val();

                let proceed = confirm(`Set ${contactName}'s disposition to ${selectedDisposition}?`);

                if (proceed) {
                    // begin AJAX
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            submitButton.prop('disabled', true);
                            submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                        },
                        success: function (response) {
                            console.log(response);
                            $(successBoxHeader).html('Success!');
                            $(successBoxMessage).html(response.message);
                            successBox.removeClass('d-none');
                            warningBox.addClass('d-none');
                        },
                        error: function (error) {
                            console.log(error);
                            $(warningBoxHeader).html('Error!');
                            $(warningBoxMessage).html(error.responseJSON.message);
                            warningBox.removeClass('d-none');
                            successBox.addClass('d-none');
                        },
                        complete: function () {
                            submitButton.prop('disabled', false);
                            submitButton.html(currentSubmitHTML);
                        }
                    });
                }               
                
            });

            const callBackField = $('#call-back-field');
            flatpickr(callBackField, {
                static: false,
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: false,
                altInput: true,
                altFormat: "F j, Y \\a\\t h:i K",
            });

            const briefingDatepicker = $('#_scheduled');
            flatpickr(briefingDatepicker, {
                static: false,
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: false,
                altInput: true,
                altFormat: "F j, Y \\a\\t h:i K",
            });

        });
    }
}