const $ = jQuery;
import flatpickr from 'flatpickr';

export class TwilioCSVConversationHandler {

    constructor() {
        this.conversationListeners();
    }

    conversationListeners() {

        $(document).ready(function () {
            const ajax_url = twilio_csv_ajax.ajax_url;
            const nonce = twilio_csv_ajax.nonce;
            const action = twilio_csv_ajax.action;
            // set the conversation length to 0 in storage
            sessionStorage.setItem('conversationLength', 0);

            fillConversation();
            setInterval(fillConversation, 30000);

            $('.message-input').on('keyup', function (e) {
                let message = $(this).val();
                let messageLength = message.length;
                let maxLength = 160;
                let remaining = maxLength - messageLength;
                // console.log(remaining);
                $('#remaining-digits').text(remaining);

                if (remaining < 0) {
                    $('#remaining-text').addClass('text-danger').removeClass('text-muted');
                    $('.message-submit').prop('disabled', true);
                } else {
                    $('#remaining-text').addClass('text-muted').removeClass('text-danger');
                    $('.message-submit').prop('disabled', false);
                }
            });


            $('#conversation-send-sms').submit(
                function (e) {
                    e.preventDefault();


                    // disable .message-submit button
                    $('.message-submit').prop('disabled', true);
                    $('.message-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

                    let formData = new FormData(this);
                    formData.append('action', action);
                    formData.append('nonce', nonce);
                    formData.append('method', 'send_sms');

                    $.ajax({
                        url: ajax_url,
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
                            // enable button and change text to Send
                            $('.message-submit').prop('disabled', false);
                            $('.message-submit').html('Send');

                            // clear message input
                            $('.message-input').val('');
                            // reset remaining digits
                            $('#remaining-digits').text(160);

                            // fill conversation
                            fillConversation();
                        }
                    });
                });

            flatpickr('#_schedule-date', {
                static: false,
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: false,
                altInput: true,
                altFormat: "F j, Y \\a\\t h:i K",
            });


            $(document).on('keyup', '.message-input', function (e) {
                let digits = $('#remaining-digits');
                let message = $(this).val();
                let messageLength = message.length;
                let maxLength = 160;
                let remaining = maxLength - messageLength;
                digits.text(remaining);
                if (remaining < 0) {
                    digits.addClass('text-danger').removeClass('text-muted');
                }
                else {
                    digits.addClass('text-muted').removeClass('text-danger');
                }
            });

        });

        $(document).on('click', '.refresh-conversations', function () {
            // console.log('refresh');
            fillConversation();
        });
    }

    viewConversation() {
        $(document).on('click', '.twilio-csv-contact-conversation', function (e) {
            e.preventDefault();
            const contactId = $(this).data('id');
            const fullName = $(this).data('contact-full-name');
            $('#conversation-name').val(fullName);
            setConvoName();
            changeConvoName();
            const nameSpan = $('#conversation-full-name');
            const nameField = $('#disposition-form-contact-name');
            const phone = $(this).data('contact-phone');
            const callbackForm = $('#schedule-callback');
            const callbackFormIdField = callbackForm.find('input[name="id"]');
            $('#contact_id').val(contactId);
            callbackFormIdField.val(contactId);
            $('#conversation-send-sms').find('input[name="to"]').val(phone);
            console.log('attempting to set contact id to ' + contactId);
            nameSpan.text(fullName);
            nameField.val(fullName);

            fillConversation();
            var pill = $('#v-pills-contact-tab');
            pill.tab('show');
        });
    }
}

// export function viewConversation(contactId) {
//     console.log('view conversation called for ' + contactId);
//     return;
// }

function fillConversation() {
    const ajaxUrl = twilio_csv_ajax.ajax_url;
    const ajaxAction = twilio_csv_ajax.action;
    const ajaxNonce = twilio_csv_ajax.nonce;
    const ajaxMethod = 'get_conversation';
    // get contact ID from url parameter contact_id
    const contactId = $('#contact_id').val();
    let conversationLength = sessionStorage.getItem('conversationLength');
    let conversationContactId = sessionStorage.getItem('conversationContactId');

    let inboundDiv = $('#inbound-message-0');
    let outboundDiv = $('#outbound-message-0');

    let refreshButton = $('.refresh-conversations');
    let refreshSpinner = $('.refresh-conversations').find('span');

    // disable refresh button
    refreshButton.prop('disabled', true);
    refreshSpinner.addClass('spinner-border spinner-border-sm');
    refreshButton.text(' Loading...');



    // create Spinner the size of messages-wrapper, place it over messages-wrapper
    // if spinner is already present, don't create it again
    if (!$('.messages-wrapper').find('.spinner-border').length) {
        let spinner = $('<div id="loading-spinner-container" class="d-flex"><div id="loading-spinner" class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        $('.messages-wrapper').append(spinner);
        let wrapperWidth = $('.messages-wrapper').width();
        let wrapperHeight = $('.messages-wrapper').height();
        spinner.css({
            'position': 'absolute',
            'top': '50%',
            'left': '50%',
            'transform': 'translate(-50%, -50%)',
            'background-color': '#fff',
            'min-width': wrapperWidth,
            'min-height': wrapperHeight,
        });
        // cover messages-wrapper with spinner
        $('.messages-wrapper').css('position', 'relative');
    }


    // fire off an ajax request to get the conversation
    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: {
            action: ajaxAction,
            nonce: ajaxNonce,
            method: ajaxMethod,
            contact_id: contactId
        },
        success: function (response) {
            updateConversationBox(response);
            // remove d-none from .loading-spinner-container
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {
            // add d-none to #loading-spinner-container if it doesn't already have it
            $('#loading-spinner-container').addClass('d-none');

            // enable refresh button
            refreshButton.prop('disabled', false);
            refreshSpinner.removeClass('spinner-border spinner-border-sm');
            refreshButton.html('<i class="fa-solid fa-arrows-rotate"></i> Refresh');

            // remove d-none from .messages-wrapper
            $('.messages-wrapper').removeClass('d-none');

            // remove d-none from all outbound and inbound messages except first
            $('.outbound-message:not(:first)').removeClass('d-none');
            $('.inbound-message:not(:first)').removeClass('d-none');

            // scroll to bottom of .messages-wrapper
            $('.messages-wrapper').animate({
                scrollTop: $('.messages-wrapper').get(0).scrollHeight
            }, 150);
        }
    });

    function updateConversationBox(response) {
        let conversationLength = sessionStorage.getItem('conversationLength');
        let conversationContactId = sessionStorage.getItem('conversationContactId');
        const contactId = $('#contact_id').val();

        if (response.length > conversationLength || contactId !== conversationContactId) {
            $('#loading-spinner-container').removeClass('d-none');
            $('.inbound-message:not(.d-none)').remove();
            $('.outbound-message:not(.d-none)').remove();

            // if no messages, show no-messages div
            if (response.length === 0) {
                $('.no-messages').removeClass('d-none');
            } else {
                $('.no-messages').addClass('d-none');
            }
            // loop through array of objects, create divs and append to .messages-wrapper
            $.each(response, function (index, value) {
                let messageDiv = '';
                let direction;
                if (value._SmsStatus === 'received') {
                    messageDiv = inboundDiv.clone(true);
                    direction = 'inbound';
                } else if (value._SmsStatus === 'sent' || value._SmsStatus === 'delivered') {
                    messageDiv = outboundDiv.clone(true);
                    direction = 'outbound';
                } else {
                    // go to next item
                    return true;
                }
                messageDiv.attr('id', direction + '-message-' + index);
                messageDiv.find('.message-body').text(value._Body);
                messageDiv.find('.message-date').text(value._created);
                messageDiv.find('.message-status').text(value._SmsStatus);
                $('.messages-wrapper').append(messageDiv);
            });

        } else {
            console.log('no new messages for contact id ' + contactId);
        }
        // scroll to bottom of .messages-wrapper
        $('.messages-wrapper').animate({
            scrollTop: $('.messages-wrapper').get(0).scrollHeight
        }, 150);


        // set conversationLength to response.length
        sessionStorage.setItem('conversationLength', response.length);
    }
}

export const changeConvoName = () => {
    const currentConvoName = sessionStorage.getItem('currentConvoName');
    $('.chat-pill-name').text(currentConvoName);
}

export const setConvoName = () => {
    const currentConvoName = $('#conversation-name').val();
    sessionStorage.setItem('currentConvoName', currentConvoName);
}