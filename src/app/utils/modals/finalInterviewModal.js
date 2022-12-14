const $ = jQuery;

// Handle Final Interview Modal
export const finalInterviewModalHandlers = () => {


    $(document).on('submit', '#final-interview-form', function (e) {
        e.preventDefault();
        const submitButton = $('#final-interview-form').find('button[type="submit"]');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
        let formData = new FormData(this);


        let ajaxUrl = twilio_csv_ajax.ajax_url;
        let action = twilio_csv_ajax.action;
        let nonce = twilio_csv_ajax.nonce;
        let method = 'send_final_interview';

        formData.append('action', action);
        formData.append('nonce', nonce);
        formData.append('method', method);

        // formData contains q1, q2, ... qX
        // change q1 to be an object containing question: and answer:
        // where question is the label element's textarea and answer is the value of the input

        // get all the questions
        let questions = $('.final-interview-question');
        // let answers = $('.final-interview-question textarea');
        let questionAnswerArray = [];
        for (let i = 0; i < questions.length; i++) {
            let question = $(questions[i]).find('label').text();
            let answer = $(questions[i]).find('textarea').val();
            let questionNumber = i + 1;
            questionAnswerArray.push({
                question: 'q' + questionNumber + ': ' + question,
                answer: answer
            });
        }

        // add radio choices to questionAnswerArray
        let commitmentAnswer = $('input[name="commitment-radio"]:checked').val();
        let commitmentQuestion = 'q' + (questions.length + 1) + ': ' + $('.commitment').text();
        let hoursProblemAnswer = $('input[name="hours-radio"]:checked').val();
        let hoursProblemQuestion = 'q' + (questions.length + 2) + ': ' + $('.hours-problem').text();

        questionAnswerArray.push({
            question: commitmentQuestion,
            answer: commitmentAnswer
        });

        questionAnswerArray.push({
            question: hoursProblemQuestion,
            answer: hoursProblemAnswer
        });

        // remove all the q1, q2, ... qX from formData
        for (let i = 0; i < questions.length; i++) {
            formData.delete('q' + (i + 1));
        }

        // append questionAnswerArray
        formData.append('questionAnswerArray', JSON.stringify(questionAnswerArray));

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
                submitButton.prop('disabled', false);
                submitButton.text('Send Agreement Email');

                // hide the modal
                $('#final-interview-modal').modal('hide');

                // reset the form
                $('#final-interview-form').trigger('reset');

                // find a datatables in the document and reload it
                $(document).find('.dataTables_wrapper').each(function () {
                    $(this).find('table').DataTable().ajax.reload();
                });
            }
        });
    });

    $(document).on('click', '#final-interview-form .btn-secondary', function (e) {
        e.preventDefault();
        confirm('This will clear all the answers you have entered. Are you sure you want to do this?') ? $('#final-interview-form').trigger('reset') : null;
    });
}