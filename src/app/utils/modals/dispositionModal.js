const $ = jQuery;
import { refreshDt } from "../listeners";

export const dispositionModalHandlers = () => {


    $(document).on('submit', '#update-disposition-form', function (e) {
        e.preventDefault();
        const submitButton = $('#update-disposition-form').find('button[type="submit"]');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
        let formData = new FormData(this);

        let ajaxUrl = twilio_csv_ajax.ajax_url;
        let action = twilio_csv_ajax.action;
        let nonce = twilio_csv_ajax.nonce;
        let method = 'update_disposition';

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
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () {
                submitButton.prop('disabled', false);
                submitButton.text('Update Disposition');
                // find datatable on page
                let dataTableElement = $('.dtWrapper').find('table');
                let dataTable = dataTableElement.DataTable();
                refreshDt(dataTable);
                $('#disposition-modal').modal('hide');

            }
        });

    });
}