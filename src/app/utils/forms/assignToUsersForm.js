const $ = jQuery;

const ajax_url = twilio_csv_ajax.ajax_url;
const nonce = twilio_csv_ajax.nonce;
const action = twilio_csv_ajax.action;
const method = 'assign_contact_to_user';

export const assignToUsersFormSubmit = () => {
    $(document).on('submit', '#assign-to-user-form', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        const currentSubmitHTML = submitButton.html();
        // get data-id attribute from each selected row and place in a comma separated string
        const selectedRows = $('#twilio-csv-public-view-contacts-table').DataTable().rows({ selected: true }).data();
        const selectedRowsIds = [];
        selectedRows.each(function (value, index) {
            selectedRowsIds.push(value.id);
        });
        if (selectedRowsIds.length === 0) {
            alert('Please select at least one candidate');
            return;
        }
        const selectedRowsIdsString = selectedRowsIds.join(',');

        // get selected user
        const selectedUser = form.find('select[name="user-id-list"]').val();
        if (selectedUser === '') {
            alert('Please select a user');
            return;
        }
        // get selected user name
        const selectedUserName = form.find('select[name="user-id-list"] option:selected').text();

        // disable submit button
        submitButton.prop('disabled', true);
        submitButton.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Assigning contacts to ${selectedUserName}...`);
        
        let formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', nonce);
        formData.append('method', method);
        formData.append('selectedRows', selectedRowsIdsString);
        formData.append('selectedUser', selectedUser);
        formData.append('selectedUserName', selectedUserName);
        
        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#assign-to-user-error').addClass('d-none');
                // $('.assigned-count').text(response.result);
                // $('.assigned-to-name').text(selectedUserName);
                $('.response-message').text(response.message);
                $('#assign-to-user-success').removeClass('d-none');
            },
            error: function (error) {
                $('#assign-to-user-success').addClass('d-none');
                // $('.assigned-to-name').text(selectedUserName);
                $('.response-message').text(error.responseJSON.message);
                $('#assign-to-user-error').removeClass('d-none');
            },
            complete: function () {
                submitButton.prop('disabled', false);
                submitButton.html(currentSubmitHTML);
            }
        });

        return;
    });
}