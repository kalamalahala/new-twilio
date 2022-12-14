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
    $(document).ready(function () {
        // Populate Send Single SMS Modal for recruiting
        // $(document).on('click', '.twilio-csv-recruit-send-sms', function (e) {
        //     const smsModal = $('#send-single-sms');
        //     const smsMessage = $('#sms');
        //     let recruitFirstName = $(this).data('recruit-first-name');
        //     let recruitLastName = $(this).data('recruit-last-name');
        //     let recruitPhone = $(this).data('recruit-phone');
        //     let recruitId = $(this).data('id');

        //     $('#contact-id').val(recruitId); // in the modal, the field is contact-id
        //     $('#contact-first-name').val(recruitFirstName); // but we provide the recruit id and name
        //     $('#contact-last-name').val(recruitLastName); // because the modal is used for both contacts and recruits
        //     $('#contact-phone').val(recruitPhone);
        //     $('.sms-first-name').text(recruitFirstName);
        //     $('.sms-last-name').text(recruitLastName);
        //     $('.sms-phone').text(recruitPhone);
        //     $('.sms-remaining').text('160');
        //     $('#sms-remaining-text').removeClass('text-danger').addClass('text-muted');
        //     $('.send-single-sms-submit').prop('disabled', false);
        //     smsMessage.val('');
        //     e.preventDefault();
        //     // Show the modal
        //     smsModal.modal('show');
        // });

        // $(document).on('click', '.programmable-message-selector', function (e) {
        //     e.preventDefault();
        //     const messageBody = $(this).data('message');
        //     console.log(messageBody);
        //     // find textarea name "body" located in the same form as the clicked button
        //     $(this).closest('form').find('textarea[name="body"]').val(messageBody);
        //     // console.log(textarea);
        //     // textarea.val(textarea.val() + messageBody);
        // });
    });

    // // Decrement .sms-remaining based on lenth of #sms message on keyup
    // $(document).on('keyup', '#sms', function () {
    //     let smsLength = $(this).val().length;
    //     let smsRemaining = 160 - smsLength;
    //     $('.sms-remaining').text(smsRemaining);

    //     // if (smsRemaining < 0) {
    //     //     $('#sms-remaining-text').removeClass('text-muted').addClass('text-danger');
    //     //     $('.send-single-sms-submit').prop('disabled', true);
    //     // } else {
    //     //     $('#sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    //     //     $('.send-single-sms-submit').prop('disabled', false);
    //     // }

    // });

    // $(document).on('submit', '#send-single-sms-form', function (e) {
    //     e.preventDefault();

    //     if ($('#sms').val().length === 0) {
    //         return;
    //     }

    //     $('.send-single-sms-submit').prop('disabled', true);
    //     $('.send-single-sms-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

    //     let formData = new FormData(this);
    //     let ajaxUrl = twilio_csv_ajax.ajax_url;
    //     let action = twilio_csv_ajax.action;
    //     let nonce = twilio_csv_ajax.nonce;
    //     let method = 'send_sms';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     $.ajax({
    //         url: ajaxUrl,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //             // console.log(response.responseJSON.success);
    //         },
    //         error: function (error) {
    //             console.log(error);
    //             // console.log(error.responseJSON.success);
    //         },
    //         complete: function () {
    //             $('.send-single-sms-submit').prop('disabled', false);
    //             $('.send-single-sms-submit').text('Send');
    //             $('#send-single-sms').modal('hide');


    //             // reload the DataTables table
    //             $('#twilio-csv-contacts-table').DataTable().ajax.reload();
    //         }
    //     });

    // });

    // Repeat most of the above but for the #send-bulk-sms modal and #send-bulk-sms-form
    // $(document).on('click', '.twilio-csv-send-bulk-sms-modal', function (e) {
    //     const smsModal = $('#send-bulk-sms');
    //     const smsMessage = $('#bulk-sms');
    //     $('.bulk-sms-remaining').text('160');
    //     $('#bulk-sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    //     $('.send-bulk-sms-submit').prop('disabled', false);
    //     smsMessage.val('');
    //     e.preventDefault();

    //     // get the number of selected rows from the DataTables table
    //     let selectedRows = $('#twilio-csv-contacts-table').DataTable().rows('.selected').count();
    //     $('.bulk-sms-recipients-count').text(selectedRows);
    //     $('#contact-count').val(selectedRows);

    //     // collect the selected rows data-id attributes into a comma separated string
    //     let selectedRowsIds = [];
    //     $('#twilio-csv-contacts-table').DataTable().rows('.selected').every(function () {
    //         selectedRowsIds.push(this.data().id);
    //     });
    //     let selectedRowsIdsString = selectedRowsIds.join(',');
    //     console.log(selectedRowsIdsString);
    //     $('#contact-id-list').val(selectedRowsIdsString);

    //     // Show the modal
    //     smsModal.modal('show');
    // });

    // // Decrement .bulk-sms-remaining based on lenth of #bulk-sms message on keyup
    // $(document).on('keyup', '#bulk-sms', function () {
    //     let smsLength = $(this).val().length;
    //     let smsRemaining = 160 - smsLength;

    //     $('.bulk-sms-remaining').text(smsRemaining);

    //     // if (smsRemaining < 0) {
    //     //     $('#bulk-sms-remaining-text').removeClass('text-muted').addClass('text-danger');
    //     //     $('.send-bulk-sms-submit').prop('disabled', true);
    //     // } else {
    //     //     $('#bulk-sms-remaining-text').removeClass('text-danger').addClass('text-muted');
    //     //     $('.send-bulk-sms-submit').prop('disabled', false);
    //     // }

    // });

    // $(document).on('submit', '#send-bulk-sms-form', function (e) {
        // e.preventDefault();

        // if ($('#bulk-sms').val().length === 0) {
        //     return;
        // }

        // $('.send-bulk-sms-submit').prop('disabled', true);

        // $('.send-bulk-sms-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

        // let formData = new FormData(this);
        // let ajaxUrl = twilio_csv_ajax.ajax_url;
        // let action = twilio_csv_ajax.action;
        // let nonce = twilio_csv_ajax.nonce;
        // let method = 'send_bulk_sms';

        // formData.append('action', action);
        // formData.append('nonce', nonce);
        // formData.append('method', method);

        // $.ajax({
        //     url: ajaxUrl,
        //     type: 'POST',
        //     data: formData,
        //     processData: false,
        //     contentType: false,
        //     success: function (response) {
        //         console.log(response);
        //     },
        //     error: function (error) {
        //         console.log(error);
        //     },
        //     complete: function () {
        //         $('.send-bulk-sms-submit').prop('disabled', false);
        //         $('.send-bulk-sms-submit').text('Send');
        //         $('#send-bulk-sms').modal('hide');
        //         // reload the DataTables table
        //         $('#twilio-csv-contacts-table').DataTable().ajax.reload();
        //     }
        // });
    // });

    // Repeat most of the above but for the #disposition-modal and #update-disposition-form
    // $(document).on('click', '.twilio-csv-contact-disposition', function (e) {
    //     e.preventDefault();
    //     const dispositionModal = $('#disposition-modal');

    //     // get the data-id attribute of the clicked row
    //     let contactId = $(this).data('id');
    //     $('#disposition-form-contact-id').val(contactId);
    //     dispositionModal.modal('show');
    // });

    // $(document).on('submit', '#update-disposition-form', function (e) {
    //     e.preventDefault();
    //     const submitButton = $('#update-disposition-form').find('button[type="submit"]');
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    //     let formData = new FormData(this);

    //     let ajaxUrl = twilio_csv_ajax.ajax_url;
    //     let action = twilio_csv_ajax.action;
    //     let nonce = twilio_csv_ajax.nonce;
    //     let method = 'update_disposition';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     $.ajax({
    //         url: ajaxUrl,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //         },
    //         error: function (error) {
    //             console.log(error);
    //         },
    //         complete: function () {
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Update Disposition');
    //             $('#disposition-modal').modal('hide');

    //             // reload the DataTables table
    //             $('#twilio-csv-contacts-table').DataTable().ajax.reload();
    //         }
    //     });
    // });



    // $(document).on('click', '.merge-tag-selector', function (e) {
    //     e.preventDefault();
    //     let mergeTag = $(this).data('tag');
    //     // add merge tag to element with name 'body' in the same form
    //     let form = $(this).closest('form');
    //     let body = form.find('textarea[name="body"]');
    //     let bodyVal = body.val();

    //     // insert additional space before and after merge tag, if whitespace is not already present
    //     if (bodyVal.length > 0) {
    //         if (bodyVal.charAt(bodyVal.length - 1) !== ' ') {
    //             mergeTag = ' ' + mergeTag;
    //         }
    //         if (bodyVal.charAt(0) !== ' ') {
    //             mergeTag = mergeTag + ' ';
    //         }
    //     }

    //     body.val(bodyVal + mergeTag);
    // });

    // $(document).on('click', '.twilio-csv-contact-final-interview', function (e) {
    //     e.preventDefault();
    //     const finalInterviewModal = $('#final-interview-modal');

    //     // get the data-id attribute of the clicked row
    //     let contactId = $(this).data('id');
    //     let contactFirstName = $(this).data('contact-first-name');
    //     let contactLastName = $(this).data('contact-last-name');
    //     let contactPhone = $(this).data('contact-phone');
    //     let contactEmail = $(this).data('contact-email');

    //     // replace all instances of .final-interview-name with contactFirstName
    //     $('.final-interview-name').text(contactFirstName);
    //     $('.final-interview-name-header').text(contactFirstName + ' ' + contactLastName);
    //     $('#final-interview-contact-id').val(contactId);
    //     $('#candidateFirstName').val(contactFirstName);
    //     $('#candidateLastName').val(contactLastName);
    //     $('#candidatePhone').val(contactPhone);
    //     $('#candidateEmail').val(contactEmail);

    //     finalInterviewModal.modal('show');
    // });

    // $(document).on('submit', '#final-interview-form', function (e) {
    //     e.preventDefault();
    //     const submitButton = $('#final-interview-form').find('button[type="submit"]');
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
    //     let formData = new FormData(this);


    //     let ajaxUrl = twilio_csv_ajax.ajax_url;
    //     let action = twilio_csv_ajax.action;
    //     let nonce = twilio_csv_ajax.nonce;
    //     let method = 'send_final_interview';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     // formData contains q1, q2, ... qX
    //     // change q1 to be an object containing question: and answer:
    //     // where question is the label element's textarea and answer is the value of the input

    //     // get all the questions
    //     let questions = $('.final-interview-question');
    //     // let answers = $('.final-interview-question textarea');
    //     let questionAnswerArray = [];
    //     for (let i = 0; i < questions.length; i++) {
    //         let question = $(questions[i]).find('label').text();
    //         let answer = $(questions[i]).find('textarea').val();
    //         let questionNumber = i + 1;
    //         questionAnswerArray.push({
    //             question: 'q' + questionNumber + ': ' + question,
    //             answer: answer
    //         });
    //     }

    //     // add radio choices to questionAnswerArray
    //     let commitmentAnswer = $('input[name="commitment-radio"]:checked').val();
    //     let commitmentQuestion = 'q' + (questions.length + 1) + ': ' + $('.commitment').text();
    //     let hoursProblemAnswer = $('input[name="hours-radio"]:checked').val();
    //     let hoursProblemQuestion = 'q' + (questions.length + 2) + ': ' + $('.hours-problem').text();

    //     questionAnswerArray.push({
    //         question: commitmentQuestion,
    //         answer: commitmentAnswer
    //     });

    //     questionAnswerArray.push({
    //         question: hoursProblemQuestion,
    //         answer: hoursProblemAnswer
    //     });

    //     // remove all the q1, q2, ... qX from formData
    //     for (let i = 0; i < questions.length; i++) {
    //         formData.delete('q' + (i + 1));
    //     }

    //     // append questionAnswerArray
    //     formData.append('questionAnswerArray', JSON.stringify(questionAnswerArray));

    //     $.ajax({
    //         url: ajaxUrl,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //         },
    //         error: function (error) {
    //             console.log(error);
    //         },
    //         complete: function () {
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Send Agreement Email');

    //             // hide the modal
    //             $('#final-interview-modal').modal('hide');

    //             // reset the form
    //             $('#final-interview-form').trigger('reset');

    //             // find a datatables in the document and reload it
    //             $(document).find('.dataTables_wrapper').each(function () {
    //                 $(this).find('table').DataTable().ajax.reload();
    //             });
    //         }
    //     });


    // });

    // $(document).on('click', '#final-interview-form .btn-secondary', function (e) {
    //     e.preventDefault();
    //     confirm('This will clear all the answers you have entered. Are you sure you want to do this?') ? $('#final-interview-form').trigger('reset') : null;
    // });

    // $(document).on('click', '.twilio-csv-update-recruit', function (e) {
    //     e.preventDefault();
    //     const updateRecruitModal = $('#update-recruit-modal');
    //     const updateRecruitForm = $('#update-recruit-form');
    //     const recruitIdField = $('#recruit-id-field');
    //     const preparedToPassField = $('#prepared-to-pass');
    //     const timeInXCELField = $('#time-in-xcel');
    //     const plePercentField = $('#ple-percent');
    //     const prepCompletionField = $('#prep-completion');
    //     const simCompletionField = $('#sim-completion');

    //     // get the data-id attribute of the clicked row
    //     let contactId = $(this).data('id');
    //     let recruitFirstName = $(this).data('recruit-first-name');
    //     let recruitLastName = $(this).data('recruit-last-name');
    //     let recruitPhone = $(this).data('recruit-phone');
    //     let recruitEmail = $(this).data('recruit-email');
    //     let timeInXCEL = $(this).data('time-in-xcel');
    //     let plePercent = $(this).data('ple-percent');
    //     let prepCompletion = $(this).data('prep-completion');
    //     let simCompletion = $(this).data('sim-completion');
    //     let preparedToPass = $(this).data('prepared-to-pass');

    //     // replace all instances of .recruit-first-name with recruitFirstName
    //     $('.recruit-first-name').text(recruitFirstName);

    //     recruitIdField.val(contactId);
    //     timeInXCELField.val(timeInXCEL);
    //     plePercentField.val(plePercent);
    //     prepCompletionField.val(prepCompletion);
    //     simCompletionField.val(simCompletion);
    //     preparedToPassField.find('option').each(function () {
    //         if ($(this).val() === preparedToPass) {
    //             $(this).prop('selected', true);
    //         }
    //     });

    //     updateRecruitModal.modal('show');
    // });

    // $(document).on('submit', '#update-recruit-form', function (e) {
    //     e.preventDefault();
    //     const submitButton = $('#update-recruit-form').find('button[type="submit"]');
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    //     let formData = new FormData(this);

    //     const ajax_url = twilio_csv_ajax.ajax_url;
    //     const action = twilio_csv_ajax.action;
    //     const nonce = twilio_csv_ajax.nonce;
    //     const method = 'update_recruit';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     $.ajax({
    //         url: ajax_url,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //         },
    //         error: function (error) {
    //             console.log(error);
    //         },
    //         complete: function () {
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Update Recruit');
    //             $('#update-recruit-modal').modal('hide');
    //             $('#update-recruit-form').trigger('reset');
    //             $(document).find('.dataTables_wrapper').each(function () {
    //                 $(this).find('table').DataTable().ajax.reload();
    //             });
    //         }
    //     });
    // });

    // $(document).on('click', '#add-new-contact-launch-modal', function (e) {
    //     e.preventDefault();
    //     $('#add-contact-modal').modal('show');
    // });


    // $(document).on('submit', '#add-contact-form', function (e) {
    //     e.preventDefault();
    //     const submitButton = $('#add-contact-form').find('button[type="submit"]');
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
    //     let formData = new FormData(this);

    //     const ajax_url = twilio_csv_ajax.ajax_url;
    //     const action = twilio_csv_ajax.action;
    //     const nonce = twilio_csv_ajax.nonce;
    //     const method = 'add_contact';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     $.ajax({
    //         url: ajax_url,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Add Contact');
    //             $('#add-contact-form').find('.alert-warning').addClass('d-none');
    //             $('#add-contact-form').find('.alert-success').removeClass('d-none');
    //             $('#add-contact-form').find('p.success-text').text('Inserted ' + response.insert + ' contact(s): ' + response.contact);
                
    //             $('#add-contact-form').trigger('reset');

    //             $('#add-contact-modal').modal('hide');
    //         },
    //         error: function (error) {
    //             console.log(error.responseJSON);
    //             // hide success alert just in case
    //             $('#add-contact-form').find('.alert-success').addClass('d-none');
    //             $('#add-contact-form').find('.alert-warning').removeClass('d-none');
    //             $('#add-contact-form').find('p.error-text').text(error.responseJSON.error);
    //         },
    //         complete: function () {
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Add Contact');
    //             // $('#add-contact-modal').modal('hide');
    //             // $('#add-contact-form').trigger('reset');
    //             $(document).find('.dataTables_wrapper').each(function () {
    //                 $(this).find('table').DataTable().ajax.reload();
    //             });
    //         }
    //     });
    // });

    // callback scheduler form
    // $(document).on('submit', '#schedule-callback', function (e) {
    //     e.preventDefault();
    //     const submitButton = $('#schedule-callback').find('button[type="submit"]');
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Scheduling...');
    //     let formData = new FormData(this);

    //     const ajax_url = twilio_csv_ajax.ajax_url;
    //     const action = twilio_csv_ajax.action;
    //     const nonce = twilio_csv_ajax.nonce;
    //     const method = 'create_scheduled_item';

    //     formData.append('action', action);
    //     formData.append('nonce', nonce);
    //     formData.append('method', method);

    //     $.ajax({
    //         url: ajax_url,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //             $('#callback-warning').addClass('d-none');
    //             $('#callback-success').removeClass('d-none');

    //         },
    //         error: function (error) {
    //             console.log(error);
    //             $('#callback-success').addClass('d-none');
    //             $('#callback-warning').removeClass('d-none');
    //         },
    //         complete: function () {
    //             submitButton.prop('disabled', false);
    //             submitButton.text('Submit');
    //             $('#schedule-callback').trigger('reset');
    //         }
    //     });

    // });

    // $(document).on('click', '.twilio-csv-contact-interview', function (e) {
    //     e.preventDefault();
    //     const contactId = $(this).data('id');
    //     const contactFirstName = $(this).data('contact-first-name');
    //     const contactLastName = $(this).data('contact-last-name');
    //     const contactEmail = $(this).data('contact-email');
    //     const contactPhone = $(this).data('contact-phone');
    //     // get the text of the currently selected option inside of #select-briefing
    //     const briefingText = $('#select-briefing').find('option:selected').text();

    //     const contactIdField = $('#begin-interview-form').find('input[name="contact-id"]');
    //     const fullNameField = $('#begin-interview-form').find('input[name="full-name"]');
    //     const emailField = $('#begin-interview-form').find('input[name="email-address"]');


    //     $('.candidate-name').text(contactFirstName + ' ' + contactLastName);
    //     $('.candidate-name-small').text(contactFirstName);
    //     $('.candidate-phone').html('<a href="tel:' + contactPhone + '">' + contactPhone + '</a>');
    //     $('.candidate-email').html('<a href="mailto:' + contactEmail + '">' + contactEmail + '</a>');

    //     $('.selected-briefing-date').text(briefingText);
    //     contactIdField.val(contactId);
    //     fullNameField.val(contactFirstName + ' ' + contactLastName);
    //     emailField.val(contactEmail);


    //     $('#begin-interview-form-modal').modal('show');
    // });

    // // when a new briefing date is selected in #select-briefing, update the text in .selected-briefing-date
    // $(document).on('change', '#select-briefing', function (e) {
    //     e.preventDefault();
    //     const briefingText = $('#select-briefing').find('option:selected').text();
    //     $('.selected-briefing-date').text(briefingText);
    // });

    // // begin interview form logic handler
    // $(document).on('click', 'input[name="did-client-answer-yn"]', function (e) {
    //     console.log('clicked input');
    //     const answer = $(this).val();
    //     console.log(answer);
    //     if (answer == 'Yes') {
    //         // add class d-none to .no-answer-section, loop through all inputs and textareas and set them to disabled
    //         $('.no-answer-section').addClass('d-none');
    //         $('.no-answer-section').find('input, textarea').prop('disabled', true);
    //         // remove d-none from .interview-script, loop through class and remove disabled from all inputs and textareas
    //         $('.interview-script').removeClass('d-none');
    //         $('.interview-script').find('input').each(function () {
    //             $(this).prop('disabled', false);
    //         });
    //         $('.interview-script').find('textarea').each(function () {
    //             $(this).prop('disabled', false);
    //         });
    //     } else {
    //         $('.interview-script').addClass('d-none');
    //         $('.interview-script').find('input').each(function () {
    //             $(this).prop('disabled', true);
    //         });
    //         $('.interview-script').find('textarea').each(function () {
    //             $(this).prop('disabled', true);
    //         });
    //         $('.no-answer-section').removeClass('d-none');
    //         $('.no-answer-section').find('input, textarea').prop('disabled', false);
    //     }
    // });

    // // call back field if no answer and input[name="no-answer"] value is Call Back
    // $(document).on('click', 'input[name="no-answer"]', function (e) {
    //     const answer = $(this).val();
    //     if (answer == 'Call Back') {
    //         $('.call-back-section').removeClass('d-none');
    //         $('.call-back-section').find('input, textarea').prop('disabled', false);
    //     } else {
    //         $('.call-back-section').addClass('d-none');
    //         $('.call-back-section').find('input, textarea').prop('disabled', true);
    //     }
    // });

    // // input[name="can-talk-job-seeker-yn"] logic handler
    // $(document).on('click', 'input[name="can-talk-job-seeker-yn"]', function (e) {
    //     const answer = $(this).val();
    //     if (answer == 'Yes') {
    //         // remove d-none from .can-talk-script-section, loop through class and remove disabled from all inputs and textareas
    //         $('.can-talk-script-section').removeClass('d-none');
    //         $('.can-talk-script-section').find('input').each(function () {
    //             $(this).prop('disabled', false);
    //         });
    //         $('.can-talk-script-section').find('textarea').each(function () {
    //             $(this).prop('disabled', false);
    //         });

    //         // add class d-none to .script-text-dnc, loop through all inputs and textareas and set them to disabled
    //         $('.script-text-dnc').addClass('d-none');
    //         $('.script-text-dnc').find('input, textarea').prop('disabled', true);

    //     } else {
    //         $('.can-talk-script-section').addClass('d-none');
    //         $('.can-talk-script-section').find('input').each(function () {
    //             $(this).prop('disabled', true);
    //         });
    //         $('.can-talk-script-section').find('textarea').each(function () {
    //             $(this).prop('disabled', true);
    //         });

    //         $('.script-text-dnc').removeClass('d-none');
    //         $('.script-text-dnc').find('input, textarea').prop('disabled', false);
    //     }
    // });

    // // input[name="confirm-email-address-yn"] logic handler
    // $(document).on('click', 'input[name="confirm-email-address-yn"]', function (e) {
    //     const answer = $(this).val();
    //     if (answer == 'No') {
    //         // remove d-none from .correct-email-option, remove disabled from all inputs and textareas
    //         $('.correct-email-option').removeClass('d-none');
    //         $('.correct-email-option').find('input, textarea').prop('disabled', false);
    //     } else {
    //         $('.correct-email-option').addClass('d-none');
    //         $('.correct-email-option').find('input, textarea').prop('disabled', true);
    //     }
    // });

    // // input[name="available-for-briefing-yn"] logic handler
    // $(document).on('click', 'input[name="available-for-briefing-yn"]', function (e) {
    //     const answer = $(this).val();
    //     if (answer == 'No') {
    //         // remove d-none from .script-text-cannot-zoom
    //         $('.script-text-cannot-zoom').removeClass('d-none');
    //         // hide .script-text-5, disable child inputs
    //         $('.script-text-5').addClass('d-none');
    //         $('.script-text-5').find('input, textarea').prop('disabled', true);
    //     } else {
    //         $('.script-text-cannot-zoom').addClass('d-none');
    //         $('.script-text-5').removeClass('d-none');
    //         $('.script-text-5').find('input, textarea').prop('disabled', false);
    //     }
    // });


    // // submit handler for begin-interview-form
    // $(document).on('submit', '#begin-interview-form', function (e) {
    //     e.preventDefault();
    //     // var form = $('#begin-interview-form')[0];
    //     // console.log(form);
    //     // return false;
    //     const formData = new FormData(this);
    //     const ajaxUrl = twilio_csv_ajax.ajax_url;
    //     const ajaxNonce = twilio_csv_ajax.nonce;
    //     const ajaxAction = 'twilio_csv_ajax';
    //     const method = 'submit_begin_interview_form';
    //     const submitButton = $('#begin-interview-form').find('button[type="submit"]');

    //     // disable submit button
    //     submitButton.prop('disabled', true);
    //     submitButton.html('<i class="fas fa-spinner fa-spin"></i> Submitting Interview...');


    //     // .question-group elements have names and IDs qN, where N is the question number
    //     // the label for each question is the question text
    //     // the value of the matching textarea is the answer

    //     // get the question number from the name attribute of the .question-group element
    //     // get the question text from the label text
    //     // get the answer from the value of the matching textarea
    //     // add the question number, question text, and answer to the formData object
    //     let questionAnswerArray = [];
    //     $('.question-group').find('.form-group').each(function () {
    //         const questionNumber = $(this).find('label').attr('for');
    //         const questionText = $(this).find('label').text();
    //         const answer = $(this).find('textarea').val();
    //         questionAnswerArray.push({
    //             questionNumber: questionNumber,
    //             questionText: questionText,
    //             answer: answer
    //         });

    //         formData.delete(questionNumber);
    //     });

    //     // add the questionAnswerArray to the formData object
    //     formData.append('questionAnswerArray', JSON.stringify(questionAnswerArray));


    //     formData.append('action', ajaxAction);
    //     formData.append('method', method);
    //     formData.append('nonce', ajaxNonce);
    //     $.ajax({
    //         url: ajaxUrl,
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             console.log(response);
    //         },
    //         error: function (error) {
    //             console.log(error);
    //         },
    //         complete: function () {
    //             // redraw ajax table
    //             let table = $(document).find('table.dataTable').DataTable();
    //             table.ajax.reload();

    //             // clear form
    //             $('#begin-interview-form').trigger('reset');

    //             // enable submit button
    //             submitButton.prop('disabled', false);
    //             submitButton.html('Finish Call & Submit');

    //             // hide modal
    //             $('#begin-interview-form-modal').modal('hide');
    //         }
    //     }); // end ajax
    // }); // end submit event



    /**
     * <button class="btn btn-primary" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Loading...
        </button>
     */

})(jQuery);

