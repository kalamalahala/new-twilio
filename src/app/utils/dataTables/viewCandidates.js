/**
 * create front end data table for agents
 */
let $ = jQuery;


export const createPublicDtViewCandidates = () => {
    const ajax_url = twilio_csv_ajax.ajax_url;
    const nonce = twilio_csv_ajax.nonce;
    const action = twilio_csv_ajax.action;
    let dtMethod = 'get_contacts';

    // assemble GET ajax request for DataTables
    const dtAjaxUrl = ajax_url + '?action=' + action + '&nonce=' + nonce + '&method=' + dtMethod;
    const table = $('#twilio-csv-public-view-contacts-table').DataTable({
        ajax: {
            url: dtAjaxUrl,
            dataSrc: "",
        },
        columns: [
            { data: "id" },             // 0
            {                           // 1
                defaultContent: "",
                width: "3%"
            },
            { data: "_updated" },       // 2
            { data: "_first_name" },    // 3
            { data: "_last_name" },     // 4
            { data: "_phone" },         // 5
            { data: "_email" },         // 6
            { data: "_city" },          // 7
            { data: "_state" },         // 8
            { data: "_source" },        // 9
            { data: "_status" },        // 10
            { data: "_disposition" }, // 11
            { data: "_user_id" },       // 12
            { defaultContent: "" },     // 13
        ],
        createdRow: function (row, data, dataIndex) {
            let id = data.id ? data.id : "";
            let _status = data._status ? data._status : "";
            let _disposition = data._disposition ? data._disposition : "";
            let _source = data._source ? data._source : "";
            let filterDisposition = {
                'Not Interested': 'bg-warning bg-gradient text-dark not-interested',
                'Not Qualified': 'bg-warning bg-gradient text-dark not-qualified',
                'Not In Service': 'bg-warning bg-gradient text-dark not-in-service',
                'Wrong Number': 'bg-warning bg-gradient text-dark wrong-number',
                'Spam': 'bg-warning bg-gradient text-dark spam',
                'Duplicate': 'bg-warning bg-gradient text-dark duplicate',
                'Do Not Call': 'bg-danger bg-gradient text-white do-not-call',
            }

            $.each(filterDisposition, function (key, value) {
                    if (_disposition === key) {
                        $(row).addClass(value);
                }
            });

            // if (_disposition == "Do Not Call") {
            //     $(row).addClass("bg-danger bg-gradient text-white do-not-call");
            // } else if (_disposition == "Not Interested") {
            //     $(row).addClass("bg-warning bg-gradient text-white not-interested"); 
            // } else if (_disposition == "Not Qualified") {
            //     $(row).addClass("bg-warning bg-gradient text-white not-qualified");
            // } else if (_disposition == "Not Qualified - No Answer") {
            //     $(row).addClass("bg-warning bg-gradient text-white not-qualified-no-answer");

            if (_status == "New Message" && _disposition !== "Do Not Call") {
                $(row).addClass("alert alert-success");
            }

            // add data-id attribute to row
            $(row).attr("data-id", id);
            // add data-status attribute to row
            $(row).attr("data-status", _status);
            // add data-_source attribute to row
            $(row).attr("data-source", _source);
        },
        processing: true,
        language: {
            searchPanes: {
                clearMessage: `<i class="fa-solid fa-filter-circle-xmark"></i> Clear All Filters`,
                collapse: {
                    0: `<i class="fa-solid fa-filter"></i> Filter Table`,
                    _: `<i class="fa-solid fa-filter"></i> Filter Candidates (%d Filters Active)`,
                },
            },
            loadingRecords: "Loading Contacts...",
        },
        columnDefs: [
            {
                targets: [0], // Column: ID
                visible: false,
            },
            {
                targets: [1], // Column: Select
                orderable: false,
                searchable: false,
                className: "select-checkbox",
            },
            {
                targets: [2], // Column: Updated
                searchPanes: {
                    show: false,

                },
                render: function (data, type, row) {
                    // if 0000-00-00 00:00:00 then return empty string
                    if (data == "0000-00-00 00:00:00") {
                        return "";
                    }
                    let date = new Date(data);
                    let formattedDate = date.toLocaleString('en-US', {
                        month: 'numeric',
                        day: 'numeric',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: true,

                    });
                    // replace " at " with "<br />"
                    let delimiter = formattedDate.match(/ at /) ? ' at ' : ",";
                    formattedDate = formattedDate.replace(delimiter, "<br />");
                    return formattedDate;
                }
            },
            {
                targets: [5], // Column: Phone
                searchPanes: {
                    show: false,
                },
                render: function (data, type, row) {
                    /* Render phone as a tel link
                     *  title="Call {first_name} {last_name}"
                     */
                    return (
                        '<a href="tel:' +
                        data +
                        '"' +
                        'target="_blank" title="Call ' +
                        row._first_name +
                        " " +
                        row._last_name +
                        '">' +
                        data +
                        "</a>"
                    );
                },
            },
            {
                targets: [6], // Column: Email
                searchPanes: {
                    show: false,
                },
                render: function (data, type, row) {
                    /* Render email as a mailto link */
                    return (
                        '<a href="mailto:' +
                        data +
                        '"' +
                        'target="_blank" title="Email ' +
                        row.first_name +
                        " " +
                        row.last_name +
                        '">' +
                        data +
                        "</a>"
                    );
                },
            },
            {
                targets: [7], // Column: Source
                searchPanes: {
                    show: true,
                }
            },
            {
                targets: [10], // Column:  Status
                searchPanes: {
                    show: true,
                },
                render: function (data, type, row) {
                    // Convert _status first letter to uppercase if not null
                    if (data !== null) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    } else {
                        return "";
                    }
                }
            },
            {
                targets: [11], // Column: Disposition
                searchPanes: {
                    show: true,
                }
            },
            {
                targets: [12], // Column: User
                searchPanes: {
                    show: true,
                },
                render: function (data, type, row) {
                    // match the user id provided with the full_name in twilio_csv_ajax.users
                    let user = twilio_csv_ajax.users.find((user) => user.id == data);
                    // if user is found return the full_name
                    if (user) {
                        return user.full_name;
                    } else {
                        return "";
                    }
                }
            },
            {
                targets: [-1], // Column: Actions
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    /* Render actions as a button group */
                    // bootstrap 4 small button: View
                    // Get the contact id from the ajax request
                    const idString = row.id.toString();
                    const firstName = row._first_name;
                    const lastName = row._last_name;
                    const phone = row._phone;
                    const email = row._email;
                    const actionPopover = '<div class="btn-group dropstart twilio-csv-contact-actions" role="group" aria-label="Contact Actions"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu">'
                        + '<a class="dropdown-item twilio-csv-contact-send-sms" href="#" data-id="' + idString + '" data-contact-first-name="' + firstName + '" data-contact-last-name="' + lastName + '" data-contact-phone="' + phone + '" title="Send SMS Message"><i class="fa-regular fa-comment"></i> Send Single SMS</a>'
                        + '<a class="dropdown-item twilio-csv-contact-send-email" href="#" data-id="' + idString + '" title="Send Email"><i class="fa-regular fa-envelope"></i> Send Single Email</a>'
                        + /* disposition */ '<a class="dropdown-item twilio-csv-contact-disposition" href="#" data-id="' + idString + '" title="Disposition"><i class="fa-regular fa-check-square"></i> Disposition</a>'
                        + '<a class="dropdown-item twilio-csv-contact-interview" href="#" data-id="' + idString + '" data-contact-first-name="' + firstName + '" data-contact-last-name="' + lastName + '" data-contact-phone="' + phone + '" data-contact-email="' + email + '" title="Begin Interview"><i class="fa-regular fa-paper-plane"></i> Begin Interview</a>'
                        + /* final interview -> create recruit */ '<a class="dropdown-item twilio-csv-contact-final-interview" href="#" data-id="' + idString + '" data-contact-first-name="' + firstName + '" data-contact-last-name="' + lastName + '" data-contact-phone="' + phone + '" data-contact-email="' + email + '" title="Final Interview"><i class="fa-regular fa-paper-plane"></i> Final Interview</a>'
                        + '<a class="dropdown-item twilio-csv-contact-conversation" href="#" data-id="' + idString + '" data-contact-full-name="' + firstName + ' ' + lastName + '" data-contact-phone="' + phone + '" title="View Conversation"><i class="fa-solid fa-comments"></i> Messages</a><div class="dropdown-divider"></div>'
                        + '<a class="dropdown-item twilio-csv-contact-delete text-danger" href="#" data-id="' + idString + '" title="Delete Contact"><i class="fa-solid fa-square-exclamation"></i> Delete</a></div></div>';
                    return actionPopover;
                },
            }

        ],
        buttons: {
            dom: {
                button: {
                    className: "btn btn-sm",
                },
            },
            buttons: [
                {
                    text: `<i class="fa-solid fa-check"></i> Select Visible Rows`,
                    className: "btn-secondary twilio-csv-select-visible-rows",
                    action: function (e, dt, node, config) {
                        // dt.rows({ search: "applied" }).select();
                        dt.rows({ page: "current" }).select();
                    },
                },
                {
                    extend: "selectNone",
                    className: "btn-outline-secondary",
                    text: `<i class="fa-solid fa-xmark"></i> Clear Selection`,
                },
                {
                    extend: "searchPanes",
                    className: "btn-outline-secondary",
                    config: {
                        cascadePanes: true,
                    }
                },
                {
                    text: `<i class="fa-solid fa-refresh"></i> Refresh`,
                    className: "btn-sm btn-outline-secondary twilio-csv-refresh",
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                    },
                },
                {
                    text: `<i class="fa-solid fa-comment-sms"></i> Send Bulk SMS`,
                    className: "btn-sm btn-primary twilio-csv-send-bulk-sms-modal",
                },
            ],
        },
        select: {
            style: "multi",
            selector: "td:first-child",
        },
        order: [[2, "desc"]],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100, 250, 500],
        responsive: false,
        dom: `B<"form-assign-to-user"><"see-dnc">flrtip`,
        initComplete: function () {
            if (twilio_csv_ajax.current_user_role === "twilio_csv_user") {
                this.api()
                .column(12)
                .visible(false);
            }
        }
    });
    return table;
}

// filter out dnc contacts unless #show-dnc is checked
export const filterDNCToggle = (table) => {
    let filterDisposition = {
        'Not Interested': 'not-interested',
        'Not Qualified': 'not-qualified',
        'Not In Service': 'not-in-service',
        'Wrong Number': 'wrong-number',
        'Spam': 'spam',
        'Duplicate': 'duplicate',
        'Do Not Call': 'do-not-call',
    }

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        // console.log($(table.row(dataIndex).node()));
        let row = $(table.row(dataIndex).node());
        let showDNC = $('#show-dnc').is(':checked');

        // if showDNC is checked, show all rows
        if (showDNC) {
            return true;
        } else {
            // if showDNC is not checked, filter out rows with dispositions in filterDisposition
            for (let disposition in filterDisposition) {
                if (row.hasClass(filterDisposition[disposition])) {
                    return false;
                }
            }
            return true;
        }
        

        // Display all rows except 'has class DNC', unless #show-dnc is checked
        // if (!$(table.row(dataIndex).node()).hasClass("do-not-call") || $("#show-dnc").is(":checked")) {
        //     return true;
        // }
        // return false;
        // !$(dataTable.row(dataIndex).node()).hasClass("do-not-call") || $("#show-dnc").is(":checked");
    });
}

$.fn.dataTable.ext.buttons.clearSelection = {
    text: "Clear Selection",
    action: function (e, dt, node, config) {
        dt.rows().deselect();
    }
}

$.fn.dataTable.ext.buttons.refresh = {
    text: "Refresh",
    action: function (e, dt, node, config) {
        dt.ajax.reload();
    }
}

// .twilio-csv-contact-delete AJAX
$(document).on("click", ".twilio-csv-contact-delete", function (e) {
    console.log("twilio-csv-contact-delete");
    e.preventDefault();
    let id = $(this).data("id");
    let row = $(this).closest("tr");
    let table = $(document).find(".dataTables_wrapper").find("table").DataTable();
    // get data from DataTables row
    let data = table.row(row).data();


    console.log(data);
    let firstName = data._first_name;
    let lastName = data._last_name;
    let fullName = firstName + " " + lastName;
    let confirmDelete = confirm(
        "Are you sure you want to delete " + fullName + "?"
    );
    if (confirmDelete) {
        $.ajax({
            url: twilio_csv_ajax.ajax_url,
            type: "POST",
            data: {
                action: twilio_csv_ajax.action,
                nonce: twilio_csv_ajax.nonce,
                method: 'delete_contact',
                id: id,
            },
            success: function (response) {
                if (response.success) {
                    table.row(row).remove().draw();
                } else {
                    alert(response.data);
                }
            },
        });
    }
});