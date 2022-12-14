const $ = jQuery;
const ajax_url = twilio_csv_ajax.ajax_url;
const nonce = twilio_csv_ajax.nonce;
const action = twilio_csv_ajax.action;

export const userAdmin = () => {
    $(document).ready(function () {
        // define elements
        const form = $('#user-admin-form');
        const userSelect = $('#select-user');
        const roleSelect = $('#select-role');
        const selectStatus = $('#select-status');
        const selectSendingNumber = $('#select-sending-number');
        const userDetails = $('#user-details');
        const userDisplayName = $('#user-display-name');
        const userLastUpdated = $('#user-last-updated');
        const userRole = $('#user-role');
        const userStatus = $('#user-status');
        const userSendingNumber = $('#user-sending-number');
        const userAssignedCount = $('#user-assigned-count');
        const updateSuccessHolder = $('#update-success-holder');
        const updateWarningHolder = $('#update-warning-holder');
        const updateSuccessMessage = $('#update-success-message');
        const updateWarningMessage = $('#update-warning-message');
        let formSubmit = form.find('button[type=submit]');
        let submitCurrentHTML = formSubmit.html();

        // userSelect change
        userSelect.change(function () {
            let selectedUserID = $(this).val();
            if (selectedUserID !== '') {
                getUserDetails(selectedUserID);
            }
        });

        $(document).on('submit', '#user-admin-form', function (e) {
            e.preventDefault();
            const method = 'update_user_details';
            formSubmit.prop('disabled', true);
            formSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

            let formData = new FormData(this);
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
                    displayAlert(updateSuccessHolder, updateSuccessMessage, response.message);
                    console.log(response);
                },
                error: function (error) {
                    displayAlert(updateWarningHolder, updateWarningMessage, error.error);
                    console.log(error);
                },
                complete: function () {
                    formSubmit.prop('disabled', false);
                    formSubmit.html(submitCurrentHTML);
                }
            });

        });

        const getUserDetails = (userID) => {
            const method = 'get_user_details';

            formSubmit.prop('disabled', true);
            formSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            // Ajax call with user ID
            $.ajax({
                url: ajax_url,
                type: 'GET',
                data: {
                    action,
                    method,
                    userID,
                    nonce,
                },
                success: function (response) {
                    console.log(response);
                    updateUserDetails(response.user);
                },
                error: function (error) {
                    console.log(error);
                },
                complete: function () {
                    formSubmit.prop('disabled', false);
                    formSubmit.html(submitCurrentHTML);
                }
            });
        };

        const updateUserDetails = (user) => {
            userDisplayName.html(user.name);
            roleSelect.val(user.role);
            selectStatus.val(user.status);
            selectSendingNumber.val(user.sending_number);
            let updated = user.last_updated;
            let assignedCount = user.assigned_count;
            
            switch (user.role) {
                case 'administrator':
                    var role = 'Administrator';
                    break;
                case 'twilio_csv_admin':
                    var role = 'Twilio CSV Admin';
                    break;
                case 'twilio_csv_user':
                    var role = 'Twilio CSV User';
                    break;
                case 'twilio_csv_manager':
                    var role = 'Twilio CSV Manager';
                    break;
                default:
                    var role = 'Not set';
            }

            switch (user.status) {
                case 'active':
                    var status = 'Active';
                    break;
                case 'inactive':
                    var status = 'Inactive';
                    break;
                default:
                    var status = 'Not set';
            }

            switch (user.sending_number) {
                case null:
                    var sendingNumber = 'Not set';
                    break;
                case false:
                    var sendingNumber = 'Not set';
                    break;
                case '':
                    var sendingNumber = 'Not set';
                    break;
                default:
                    // format user.sending_number to (xxx) xxx-xxxx
                    var sendingNumber = user.sending_number.toString();
                    sendingNumber = sendingNumber.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            }

            userLastUpdated.html(updated);
            userRole.html(role);
            userStatus.html(status);
            userAssignedCount.html(assignedCount);
            userSendingNumber.html(sendingNumber);

            userDetails.removeClass('d-none');
        }

        const displayAlert = (box, text, message) => {
            box.removeClass('d-none');
            text.html(message);
        }
    });
}
