const $ = jQuery;

export const updateRecruitInfoModal = () => {


    $(document).on('submit', '#update-recruit-form', function (e) {
        e.preventDefault();
        const submitButton = $('#update-recruit-form').find('button[type="submit"]');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
        let formData = new FormData(this);

        const ajax_url = twilio_csv_ajax.ajax_url;
        const action = twilio_csv_ajax.action;
        const nonce = twilio_csv_ajax.nonce;
        const method = 'update_recruit';

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
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () {
                submitButton.prop('disabled', false);
                submitButton.text('Update Recruit');
                $('#update-recruit-modal').modal('hide');
                $('#update-recruit-form').trigger('reset');
                $(document).find('.dataTables_wrapper').each(function () {
                    $(this).find('table').DataTable().ajax.reload();
                });
            }
        });
    });
}

export const smsKeyup = () => {
    $(document).on('keyup', '#sms', function () {
        let smsLength = $(this).val().length;
        let smsRemaining = 160 - smsLength;
        $('.sms-remaining').text(smsRemaining);

        if (smsRemaining < 0) {
            $('#sms-remaining-text').removeClass('text-muted').addClass('text-danger');
            $('.over-limit').removeClass('d-none');
        } else {
            $('#sms-remaining-text').removeClass('text-danger').addClass('text-muted');
            $('.over-limit').addClass('d-none');
        }
    });
};

/**
 * Modifies the remaining characters after a merge tag is inserted
 * for a selected field
 * @param {string} field 
 */
export const listenKeyUp = (field) => {
    console.log(field);
    let smsLength = $(field).val().length;
    let smsRemaining = 160 - smsLength;
    $('.sms-remaining').text(smsRemaining);

    if (smsRemaining < 0) {
        $('#sms-remaining-text').removeClass('text-muted').addClass('text-danger');
        $('.over-limit').removeClass('d-none');
    } else {
        $('#sms-remaining-text').removeClass('text-danger').addClass('text-muted');
        $('.over-limit').addClass('d-none');
    }
};


export const launchModal = (modal) => {
    // accept a boostrap 5 modal id
    $(modal).modal('show');
}