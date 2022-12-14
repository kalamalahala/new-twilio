const $ = jQuery;
// import bootstrap from 'bootstrap';

export class TwilioCSVProgrammableMessages {
    constructor() {
        this.programmableMessages();
    }

    programmableMessages() {

        $(document).ready(function () {
            // console.log('listener constructed');
            const ajaxurl = twilio_csv_ajax.ajax_url;
            const nonce = twilio_csv_ajax.nonce;
            const action = twilio_csv_ajax.action;

            let dtMethod = 'get_programmable_messages';
            const dtAjaxUrl = ajaxurl + '?action=' + action + '&nonce=' + nonce + '&method=' + dtMethod;

            // implement datatables for programmable messages table
            const table = $('#programmable-messages-table').DataTable({

                "processing": true,

                ajax: {
                    url: dtAjaxUrl,
                    dataSrc: '',
                }, // columns: Name, Body, Type, Edit Button, Delete Button
                columns: [
                    { data: '_title' },
                    { data: '_body' },
                    { data: '_type' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return '<button class="btn btn-primary btn-sm message-action edit-programmable-message" data-action="edit_message" data-id="' + data + '">Edit</button>';
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return '<button class="btn btn-danger btn-sm message-action delete-programmable-message" data-action="delete_message" data-id="' + data + '">Delete</button>';
                        }
                    }
                ],
                buttons: [
                    {
                        text: `<span style="color: white;"><i class="fa-solid fa-refresh"></i> Refresh</span>`,
                        className: "btn-sm btn-outline-secondary twilio-csv-refresh",
                        action: function (e, dt, node, config) {
                            dt.ajax.reload();
                        },
                    },
                ],
                "dom": 'Bfrtip',
            });
    
            $('#create-programmable-message-form').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const successAlert = $('#create-programmable-message-form').find('.alert-success');
                const errorAlert = $('#create-programmable-message-form').find('.alert-warning');
                formData.append('action', action);
                formData.append('nonce', nonce);
                formData.append('method', 'create_programmable_message');
    
                $('#create-programmable-message-form').find('button[type="submit"]').attr('disabled', true);
                $('#create-programmable-message-form').find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...');
    
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        successAlert.removeClass('d-none');
                        errorAlert.addClass('d-none');
                    },
                    error: function (error) {
                        console.log(error);
                        errorAlert.removeClass('d-none');
                        successAlert.addClass('d-none');
                    },
                    complete: function () {
                        $('#create-programmable-message-form').find('button[type="submit"]').attr('disabled', false);
                        $('#create-programmable-message-form').find('button[type="submit"]').html('Create');
                        $('#create-programmable-message-form').trigger('reset');
                    }
    
                });
            });
    
            $('.merge-tag-selector').click(function (e) {
                e.preventDefault();
                const mergeTag = $(this).data('tag');
                // find nearest textarea
                const textarea = $(this).closest('.form-group').find('textarea');
                textarea.val(textarea.val() + mergeTag);
            });
    
            $('#edit-programmable-message-form').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', action);
                formData.append('nonce', nonce);
                formData.append('method', 'update_programmable_message');
    
                console.log(formData);

                const successAlert = document.querySelector('.programmable-update-success');
                const errorAlert = document.querySelector('.programmable-update-warning');
    
                $('#edit-programmable-message-form').find('button[type="submit"]').attr('disabled', true);
                $('#edit-programmable-message-form').find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        successAlert.classList.remove('d-none');
                        errorAlert.classList.add('d-none');
                    },
                    error: function (error) {
                        console.log(error);
                        errorAlert.classList.remove('d-none');
                        successAlert.classList.add('d-none');
                    },
                    complete: function () {
                        $('#edit-programmable-message-form').find('button[type="submit"]').attr('disabled', false);
                        $('#edit-programmable-message-form').find('button[type="submit"]').html('Update');
                        $('#edit-programmable-message-form').trigger('reset');
                    }
    
                });
            });
        });
    }

    handlers() {
        const ajaxurl = twilio_csv_ajax.ajax_url;
        const nonce = twilio_csv_ajax.nonce;
        const action = twilio_csv_ajax.action;
        
        $(document).on('click', '.message-action', function (e) {
            e.preventDefault();
            console.log('clicked');
            const messageId = $(this).data('id');
            const action = $(this).data('action');
            const ajaxUrl = twilio_csv_ajax.ajax_url;
            const nonce = twilio_csv_ajax.nonce;
            const ajaxAction = twilio_csv_ajax.action;
            
            if (action === 'edit_message') {
                $.ajax({
                    url: ajaxurl,
                    type: 'GET',
                    data: {
                        action: ajaxAction,
                        nonce: nonce,
                        method: 'get_programmable_message',
                        id: messageId
                    },
                    success: function (response) {

                        // switch tab panes Bootstrap 5.2
                        const programmableMessagesTab = document.getElementById('twilio-csv-programmable-messages-view-tab');
                        const programmableMessagesTabPane = document.getElementById('twilio-csv-programmable-messages-view');
                        const editProgrammableMessageTab = document.getElementById('twilio-csv-programmable-messages-edit-tab');
                        const editProgrammableMessageTabPane = document.getElementById('twilio-csv-programmable-messages-edit');


                        programmableMessagesTab.classList.remove('active');
                        programmableMessagesTabPane.classList.remove('active');
                        programmableMessagesTabPane.classList.remove('show');
                        editProgrammableMessageTab.classList.add('active');
                        editProgrammableMessageTabPane.classList.add('active');
                        editProgrammableMessageTabPane.classList.add('show');
                        

                        // select the message with the matching id
                        $('#edit-programmable-message-form').find('select[name="id"]').val(messageId);
                        // populate the form with the message data
                        $('#edit-programmable-message-form').find('input[name="_title"]').val(response._title);
                        $('#edit-programmable-message-form').find('textarea[name="_body"]').val(response._body);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }

            if (action === 'delete_message') {
                if (confirm('Are you sure you want to delete this message?')) {
                    $(this).prop('disabled', true);
                    $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
                    const parentRow = $(this).closest('tr');
                    // console.log(parentRow);
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: ajaxAction,
                            nonce: nonce,
                            method: 'delete_programmable_message',
                            id: messageId
                        },
                        success: function (response) {
                            console.log(response);
                            // hide row containing the button
                        },
                        error: function (error) {
                            console.log(error);
                        },
                        complete: function () {
                            parentRow.addClass('d-none');
                            $(this).prop('disabled', false);
                            $(this).html('<i class="fa-solid fa-trash-can"></i> Delete');
                        }
                    });
                }
            }
        });

    }
}