const $ = jQuery;
import moment from 'moment';
import * as overlay from '../loadingOverlay/loadingOverlay';

export const createScheduledCallbackDataTable = () => {
    const ajax_url = twilio_csv_ajax.ajax_url;
    const action = twilio_csv_ajax.action;
    const nonce = twilio_csv_ajax.nonce;
    const method = 'get_scheduled_callbacks';
    const dtUrlString = ajax_url + '?action=' + action + '&nonce=' + nonce + '&method=' + method;

    $('#scheduled-callback-table').DataTable({
        processing: true,
        ajax: {
            url: dtUrlString,
            dataSrc: ''
        },
        columns: [
            { data: '_date',
                render: function (data, type, row) { 
                    return moment(data).format('MMMM Do YYYY, h:mm:ss a');
                }},
            // _first
            { data: '_first_name' },
            // _last
            { data: '_last_name' },
            // _phone
            { data: '_phone',
                render: function (data, type, row) {
                    if (!data) {
                        return '';
                    }
                    // remove leading + and 1, if present
                    let phone = data.replace(/^\+?1?/, '');
                    // remove all non-numeric characters
                    phone = phone.replace(/\D/g, '');
                    // add leading 1 if needed
                    if (phone.length === 10) {
                        phone = '1' + phone;
                    }
                    // (123) 456-7890
                    phone = phone.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '$1 ($2) $3-$4');
                    return '<a href="tel:' + data + '">' + phone + '</a>';
                }
            },
            {
                data: '_schedule_status',
            },
            { data: '_schedule_id',
                render: function (data, type, row) {
                    let dropdown = `<div class="btn-group dropstart">`;
                    dropdown += `<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>`;
                    dropdown += `<ul class="dropdown-menu">`;
                    dropdown += `<li><h6 class="dropdown-header">Callback Actions</h6></li>`;
                    dropdown += `<li><a class="dropdown-item pending-scheduled-item" href="#" data-id="${data}"><i class="fa-solid fa-clock"></i>&nbsp; Mark Pending</a></li>`;
                    dropdown += `<li><a class="dropdown-item complete-scheduled-item" href="#" data-id="${data}"><i class="fa-solid fa-check"></i>&nbsp;Mark Complete</a></li>`;
                    dropdown += `<li><hr class="dropdown-divider"></li>`
                    dropdown += `<li><a class="dropdown-item delete-scheduled-item" href="#" data-id="${data}"><i class="fa-solid fa-trash"></i>&nbsp;Delete</a></li>`;
                    dropdown += `</ul></div>`;

                    return dropdown;
                }
            },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).attr('data-id', data._schedule_id);
            $(row).attr('data-schedule-status', data._schedule_status);
            $(row).attr('data-contact-id', data._contact_id);
            $(row).attr('data-user-id', data._user_id);

            if (data._schedule_status === 'Completed') {
                $(row).addClass('table-success text-success fw-bold text-decoration-line-through');
            }
        },
        order: [[0, "desc"]],
        dom: 'Bfrtip',
        buttons: [
            {
                text: `<span style="color: white;"><i class="fa-solid fa-refresh"></i> Refresh</span>`,
                className: "btn-sm btn-outline-secondary twilio-csv-refresh",
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                },
            },
        ],
        responsive: true,
        initComplete: function () {
            this.api().
                rows().
                every(function () {
                    console.log(twilio_csv_ajax.current_user_role);
                    console.log(this.data()._user_id);
                    // If the current user is 'twilio_csv_user', hide the row if data-user-id is not the current user
                    if (twilio_csv_ajax.current_user_role === 'twilio_csv_user') {
                        if (this.data()._user_id !== twilio_csv_ajax.current_user_id) {
                            this.node().style.display = 'none';
                        }
                    }
                });
        }
    });

    $(document).on('click', '.delete-scheduled-item', function (e) {
        e.preventDefault();
        deleteItem($(this).data('id'), this);
    });

    $(document).on('click', '.complete-scheduled-item', function (e) {
        e.preventDefault();
        setScheduleStatus($(this).data('id'), 'Completed', this);
    });

    $(document).on('click', '.pending-scheduled-item', function (e) {
        e.preventDefault();
        setScheduleStatus($(this).data('id'), 'Pending', this);
    });

            
}


const deleteItem = (id, source) => {
    const ajaxUrl = twilio_csv_ajax.ajax_url;
    const action = twilio_csv_ajax.action;
    const nonce = twilio_csv_ajax.nonce;
    const method = 'delete_scheduled_item';
    const table = $(source).closest('table').DataTable();
    const data = {
        action: action,
        nonce: nonce,
        method: method,
        id: id,
    };

    overlay.displayLoadingOverlay('Deleting Scheduled Callback...', 'Please wait while we delete the scheduled callback.');

    let request = $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: data,
        success: (response) => {
            // display success message
        },
        error: (error) => {
            // error handling
        },
        complete: (request) => {
            table.ajax.reload();
            overlay.hideLoadingOverlay();       
        }
    });

    return request;
}

const setScheduleStatus = (id, status, source) => {
    const ajaxUrl = twilio_csv_ajax.ajax_url;
    const action = twilio_csv_ajax.action;
    const nonce = twilio_csv_ajax.nonce;
    const method = 'set_schedule_status';
    const table = $(source).closest('table').DataTable();

    const data = {
        action: action,
        nonce: nonce,
        method: method,
        id: id,
        status: status,
    };

    overlay.displayLoadingOverlay('Updating Scheduled Callback...', 'Please wait while we update the scheduled callback.');

    let request = $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: data,
        success: (response) => {
            // display success message
        },
        error: (error) => {
            // error handling
        },
        complete: (request) => {
            table.ajax.reload();
            overlay.hideLoadingOverlay();
        }
    });

    return request;
}