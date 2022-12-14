const $ = jQuery;

export const firstInterviewModalHandlers = () => {


    // when a new briefing date is selected in #select-briefing, update the text in .selected-briefing-date
    $(document).on('change', '#select-briefing', function (e) {
        e.preventDefault();
        const briefingText = $('#select-briefing').find('option:selected').text();
        $('.selected-briefing-date').text(briefingText);
    });

    // begin interview form logic handler
    $(document).on('click', 'input[name="did-client-answer-yn"]', function (e) {
        const answer = $(this).val();
        if (answer == 'Yes') {
            // add class d-none to .no-answer-section, loop through all inputs and textareas and set them to disabled
            $('.no-answer-section').addClass('d-none');
            $('.no-answer-section').find('input, textarea').prop('disabled', true);
            // remove d-none from .interview-script, loop through class and remove disabled from all inputs and textareas
            $('.interview-script').removeClass('d-none');
            $('.interview-script').find('input').each(function () {
                $(this).prop('disabled', false);
            });
            $('.interview-script').find('textarea').each(function () {
                $(this).prop('disabled', false);
            });
        } else {
            $('.interview-script').addClass('d-none');
            $('.interview-script').find('input').each(function () {
                $(this).prop('disabled', true);
            });
            $('.interview-script').find('textarea').each(function () {
                $(this).prop('disabled', true);
            });
            $('.no-answer-section').removeClass('d-none');
            $('.no-answer-section').find('input, textarea').prop('disabled', false);
        }
    });

    // call back field if no answer and input[name="no-answer"] value is Call Back
    $(document).on('click', 'input[name="no-answer"]', function (e) {
        const answer = $(this).val();
        if (answer == 'Call Back') {
            $('.call-back-section').removeClass('d-none');
            $('.call-back-section').find('input, textarea').prop('disabled', false);
        } else {
            $('.call-back-section').addClass('d-none');
            $('.call-back-section').find('input, textarea').prop('disabled', true);
        }
    });

    // input[name="can-talk-job-seeker-yn"] logic handler
    $(document).on('click', 'input[name="can-talk-job-seeker-yn"]', function (e) {
        const answer = $(this).val();
        if (answer == 'Yes') {
            // remove d-none from .can-talk-script-section, loop through class and remove disabled from all inputs and textareas
            $('.can-talk-script-section').removeClass('d-none');
            $('.can-talk-script-section').find('input').each(function () {
                $(this).prop('disabled', false);
            });
            $('.can-talk-script-section').find('textarea').each(function () {
                $(this).prop('disabled', false);
            });

            // add class d-none to .script-text-dnc, loop through all inputs and textareas and set them to disabled
            $('.script-text-dnc').addClass('d-none');
            $('.script-text-dnc').find('input, textarea').prop('disabled', true);

        } else {
            $('.can-talk-script-section').addClass('d-none');
            $('.can-talk-script-section').find('input').each(function () {
                $(this).prop('disabled', true);
            });
            $('.can-talk-script-section').find('textarea').each(function () {
                $(this).prop('disabled', true);
            });

            $('.script-text-dnc').removeClass('d-none');
            $('.script-text-dnc').find('input, textarea').prop('disabled', false);
        }
    });

    // input[name="confirm-email-address-yn"] logic handler
    $(document).on('click', 'input[name="confirm-email-address-yn"]', function (e) {
        const answer = $(this).val();
        if (answer == 'No') {
            // remove d-none from .correct-email-option, remove disabled from all inputs and textareas
            $('.correct-email-option').removeClass('d-none');
            $('.correct-email-option').find('input, textarea').prop('disabled', false);
        } else {
            $('.correct-email-option').addClass('d-none');
            $('.correct-email-option').find('input, textarea').prop('disabled', true);
        }
    });

    // input[name="available-for-briefing-yn"] logic handler
    $(document).on('click', 'input[name="available-for-briefing-yn"]', function (e) {
        const answer = $(this).val();
        if (answer == 'No') {
            // remove d-none from .script-text-cannot-zoom
            $('.script-text-cannot-zoom').removeClass('d-none');
            // hide .script-text-5, disable child inputs
            $('.script-text-5').addClass('d-none');
            $('.script-text-5').find('input, textarea').prop('disabled', true);
        } else {
            $('.script-text-cannot-zoom').addClass('d-none');
            $('.script-text-5').removeClass('d-none');
            $('.script-text-5').find('input, textarea').prop('disabled', false);
        }
    });


    // submit handler for begin-interview-form
    $(document).on('submit', '#begin-interview-form', function (e) {
        e.preventDefault();
        // var form = $('#begin-interview-form')[0];
        // console.log(form);
        // return false;
        const formData = new FormData(this);
        const ajaxUrl = twilio_csv_ajax.ajax_url;
        const ajaxNonce = twilio_csv_ajax.nonce;
        const ajaxAction = 'twilio_csv_ajax';
        const method = 'submit_begin_interview_form';
        const submitButton = $('#begin-interview-form').find('button[type="submit"]');

        // disable submit button
        submitButton.prop('disabled', true);
        submitButton.html('<i class="fas fa-spinner fa-spin"></i> Submitting Interview...');


        // .question-group elements have names and IDs qN, where N is the question number
        // the label for each question is the question text
        // the value of the matching textarea is the answer

        // get the question number from the name attribute of the .question-group element
        // get the question text from the label text
        // get the answer from the value of the matching textarea
        // add the question number, question text, and answer to the formData object
        let questionAnswerArray = [];
        $('.question-group').find('.form-group').each(function () {
            const questionNumber = $(this).find('label').attr('for');
            const questionText = $(this).find('label').text();
            const answer = $(this).find('textarea').val();
            questionAnswerArray.push({
                questionNumber: questionNumber,
                questionText: questionText,
                answer: answer
            });

            formData.delete(questionNumber);
        });

        // add the questionAnswerArray to the formData object
        formData.append('questionAnswerArray', JSON.stringify(questionAnswerArray));


        formData.append('action', ajaxAction);
        formData.append('method', method);
        formData.append('nonce', ajaxNonce);
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
                // redraw ajax table
                let table = $(document).find('table.dataTable').DataTable();
                table.ajax.reload();

                // clear form
                $('#begin-interview-form').trigger('reset');

                // enable submit button
                submitButton.prop('disabled', false);
                submitButton.html('Finish Call & Submit');

                // hide modal
                $('#begin-interview-form-modal').modal('hide');
            }
        }); // end ajax
    }); // end submit event
}