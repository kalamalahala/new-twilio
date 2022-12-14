(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    // $(document).ready(function () {

    //     const wrapper = $('#conversations-card-wrap');
    //     if (wrapper.prop('hidden') === true) {
    //         return;
    //     }

    //     const ajaxUrl = twilio_csv_ajax.ajax_url;
    //     const ajaxAction = twilio_csv_ajax.action;
    //     const ajaxNonce = twilio_csv_ajax.nonce;
    //     const ajaxMethod = 'get_conversation';
    //     // get contact ID from url parameter contact_id
    //     const contactId = $('#contact_id').val();

    //     let inboundDiv = $('#inbound-message-0');
    //     let outboundDiv = $('#outbound-message-0');

    //     // fire off an ajax request to get the conversation
    //     $.ajax({
    //         url: ajaxUrl,
    //         type: 'POST',
    //         data: {
    //             action: ajaxAction,
    //             nonce: ajaxNonce,
    //             method: ajaxMethod,
    //             contact_id: contactId
    //         },
    //         success: function (response) {
    //             // loop through array of objects, create divs and append to .messages-wrapper
    //             $.each(response, function (index, value) {
    //                 let messageDiv = '';
    //                 let direction;
    //                 if (value._SmsStatus === 'received') {
    //                     messageDiv = inboundDiv.clone(true);
    //                     direction = 'inbound';
    //                 } else if (value._SmsStatus === 'sent' || value._SmsStatus === 'delivered') {
    //                     messageDiv = outboundDiv.clone(true);
    //                     direction = 'outbound';
    //                 } else {
    //                     // go to next item
    //                     return true;
    //                 }
    //                 messageDiv.attr('id', direction + '-message-' + index);
    //                 messageDiv.find('.message-body').text(value._Body);
    //                 messageDiv.find('.message-date').text(value._created);
    //                 messageDiv.find('.message-status').text(value._SmsStatus);
    //                 $('.messages-wrapper').append(messageDiv);
    //             });
    //         },
    //         error: function (error) {
    //             console.log(error);
    //         },
    //         complete: function () {
    //             // add d-none to #loading-spinner, and remove d-flex
    //             $('#loading-spinner').addClass('d-none').removeClass('d-flex');

    //             // remove d-none from .messages-wrapper
    //             $('.messages-wrapper').removeClass('d-none');

    //             // remove d-none from all outbound and inbound messages except first
    //             $('.outbound-message:not(:first)').removeClass('d-none');
    //             $('.inbound-message:not(:first)').removeClass('d-none');

    //             // scroll to bottom of .messages-wrapper
    //             $('.messages-wrapper').animate({
    //                 scrollTop: $('.messages-wrapper').get(0).scrollHeight
    //             }, 150);

    //             // set interval to check for new messages every 5 seconds
    //             // setInterval(function () {
    //             // getNewMessages(); @todo implement this
    //             // }, 5000);
    //         }

    //     });

    //     $('.message-input').on('keyup', function (e) {
    //         let message = $(this).val();
    //         let messageLength = message.length;
    //         let maxLength = 160;
    //         let remaining = maxLength - messageLength;
    //         // console.log(remaining);
    //         $('#remaining-digits').text(remaining);

    //         if (remaining < 0) {
    //             $('#remaining-text').addClass('text-danger').removeClass('text-muted');
    //             $('.message-submit').prop('disabled', true);
    //         } else {
    //             $('#remaining-text').addClass('text-muted').removeClass('text-danger');
    //             $('.message-submit').prop('disabled', false);
    //         }
    //     });


    //     $('#conversation-send-sms').submit(
    //         function (e) {
    //             e.preventDefault();

    //             // disable .message-submit button
    //             $('.message-submit').prop('disabled', true);
    //             $('.message-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

    //             let formData = new FormData(this);
    //             formData.append('action', ajaxAction);
    //             formData.append('nonce', ajaxNonce);
    //             formData.append('method', 'send_sms');

    //             $.ajax({
    //                 url: ajaxUrl,
    //                 type: 'POST',
    //                 data: formData,
    //                 processData: false,
    //                 contentType: false,
    //                 success: function (response) {
    //                     console.log(response);
    //                 },
    //                 error: function (error) {
    //                     console.log(error);
    //                 },
    //                 complete: function () {
    //                     // enable button and change text to Send
    //                     $('.message-submit').prop('disabled', false);
    //                     $('.message-submit').html('Send');

    //                     // clear message input
    //                     $('.message-input').val('');
    //                     // reset remaining digits
    //                     $('#remaining-digits').text(160);
    //                 }
    //             });
    //         });

    //         $('#_schedule-date').datetimepicker(
    //             {
    //                 format: 'd/m/y g:i A',
    //                 minDate: 0,
    //                 step: 15,
    //             }
    //         );

    //         $(document).on('click', '.programmable-message-selector', function (e) {
    //             e.preventDefault();
    //             let message = $(this).data('message');
    //             $('.message-input').val(message);
    //         });

    // });

})(jQuery);

let json = [
    {
        "id": "1",
        "_SmsMessageSid": "SMdff3fb1082e5e3d6e4fc254754441b2d",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "+19045321080",
        "_To": "+13868886995",
        "_Body": "Testing inbound logging",
        "_SmsStatus": "received",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 20:35:49",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "2",
        "_SmsMessageSid": "",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "",
        "_To": "19045321080",
        "_Body": "outbound to {{FULLNAME}}",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:07:19",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "3",
        "_SmsMessageSid": "",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "",
        "_To": "19045321080",
        "_Body": "abloop",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:12:26",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "4",
        "_SmsMessageSid": "SMde9616521de05e714af442c26e905ec8",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "",
        "_To": "19045321080",
        "_Body": "we really are almost there",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:30:23",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "5",
        "_SmsMessageSid": "SM2812610ac1e4e143ace6135dcaacc126",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "+19045321080",
        "_To": "+13868886995",
        "_Body": "Can check inbound webhook as well",
        "_SmsStatus": "received",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:32:09",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "6",
        "_SmsMessageSid": "SM218f3d5b88859909e3d26368ecbf03e0",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "13868886995",
        "_To": "19045321080",
        "_Body": "living on a prayer",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:32:26",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "7",
        "_SmsMessageSid": "MM241858c334b5cb09612b24f38620dd60",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "19045321080",
        "_To": "13868886995",
        "_Body": "Check media",
        "_SmsStatus": "received",
        "_NumMedia": "1",
        "_Media": "{\"https:\\/\\/api.twilio.com\\/2010-04-01\\/Accounts\\/ACd4b4efa2054f2aaf8c06ab0693f3f65b\\/Messages\\/MM241858c334b5cb09612b24f38620dd60\\/Media\\/ME10fd90fcc927b25a52503b496c2f8610\":\"image\\/jpeg\"}",
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:35:31",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "8",
        "_SmsMessageSid": "SM218f3d5b88859909e3d26368ecbf03e0",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "13868886995",
        "_To": "19045321080",
        "_Body": "check outbound update, should show delivered",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:37:35",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "9",
        "_SmsMessageSid": "SMe4f4f485fdb11b68cd3ba43dbcb2e84a",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "",
        "_To": "19045321080",
        "_Body": "send it",
        "_SmsStatus": "delivered",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:43:04",
        "_updated": "2022-10-30 21:43:04"
    },
    {
        "id": "10",
        "_SmsMessageSid": "SM77a2c782495433cf9d17f8651f2397c3",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "19045321080",
        "_To": "13868886995",
        "_Body": "Pew pew\n(sent with Lasers)",
        "_SmsStatus": "received",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:45:35",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "11",
        "_SmsMessageSid": "SMe4f4f485fdb11b68cd3ba43dbcb2e84a",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "13868886995",
        "_To": "19045321080",
        "_Body": "numbers should show now",
        "_SmsStatus": "sent",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:46:10",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "12",
        "_SmsMessageSid": "SMb0ce68aec6cabfd11cdd372a3aeaa29e",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "13868886995",
        "_To": "19045321080",
        "_Body": "i really want it to only show delivered, not sent",
        "_SmsStatus": "delivered",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 21:49:37",
        "_updated": "2022-10-30 21:49:38"
    },
    {
        "id": "13",
        "_SmsMessageSid": "SM12e4f788a85af0097c37f19b12c1bbfa",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "13868886995",
        "_To": "19045321080",
        "_Body": "checking for delivered and string replacement Tyler Karle",
        "_SmsStatus": "delivered",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-30 22:28:10",
        "_updated": "2022-10-30 22:28:10"
    },
    {
        "id": "14",
        "_SmsMessageSid": "SM2c90c112262a6e4c923734bc6f01698a",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "19045321080",
        "_To": "13868886995",
        "_Body": "Stop",
        "_SmsStatus": "received",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-31 00:59:28",
        "_updated": "0000-00-00 00:00:00"
    },
    {
        "id": "15",
        "_SmsMessageSid": "SMe86b75082b25cdcf081dc9b13f83061e",
        "_MessagingServiceSid": "MGed693e77e70d6f52882605d37cc30d4c",
        "_From": "19045321080",
        "_To": "13868886995",
        "_Body": "Start",
        "_SmsStatus": "received",
        "_NumMedia": "0",
        "_Media": null,
        "_contact_id": "1",
        "_conversation_id": "",
        "_created": "2022-10-31 00:59:37",
        "_updated": "0000-00-00 00:00:00"
    }
]