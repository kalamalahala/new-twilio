(function ($) {
    'use strict';

    $(document).ready(function () {
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

        // Constants
        const dataTable = $('#twilio-csv-contacts-table');
        const dataTableBody = dataTable.find('tbody');
        const ajax_url = twilio_csv_ajax.ajax_url;
        const ajax_nonce = twilio_csv_ajax.nonce;
        const ajax_action = twilio_csv_ajax.action;
        const dtMethod = "get_active_contacts";
        const dtUrlString =
            ajax_url +
            "?action=" +
            ajax_action +
            "&method=" +
            dtMethod +
            "&nonce=" +
            ajax_nonce;



        // DataTable Init
        dataTable.DataTable({
            ajax: {
                url: dtUrlString,
                dataSrc: "",
            },
            columns: [
                { data: "id" },             // 0
                {                           // 1
                    defaultContent: "",
                    width: "3%"
                },
                { data: "_created" },       // 2
                { data: "_updated" },       // 3
                { data: "_first_name" },    // 4
                { data: "_last_name" },     // 5
                { data: "_phone" },         // 6
                { data: "_email" },         // 7
                { data: "_city" },          // 8
                { data: "_state" },         // 9
                { data: "_source" },        // 10
                { data: "_status" },        // 11
                { data: "_disposition" }, // 12
                { defaultContent: "" },     // 13
            ],
            createdRow: function (row, data, dataIndex) {
                let id = data.id ? data.id : "";
                let _status = data._status ? data._status : "";
                let _disposition = data._disposition ? data._disposition : "";
                let _source = data._source ? data._source : "";

                if (_disposition == "Do Not Call") {
                    $(row).addClass("alert alert-danger");
                } else if (_status == "New Message" && _disposition !== "Do Not Call") {
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
                    clearMessage: "Clear All Filters",
                    collapse: {
                        0: "Filter Candidates",
                        _: "Filter Candidates (%d)",
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
                    targets: [2, 3], // Column: Date Added, Date Updated
                    searchPanes: {
                        show: false,
                    },
                    render: function (data, type, row) {
                        // return '' if data is null or undefined
                        if (!data || data === null) {
                            return ""; // else, if date is 0000-00-00 00:00:00, return empty string
                        } else if (data === "0000-00-00 00:00:00") {
                            return "";
                        }

                        let date = new Date(data);
                        let dateString = date.toLocaleString();
                        let dateSplit = dateString.split(",");
                        let formattedDate = dateSplit[0] + "<br />" + dateSplit[1];
                        return formattedDate;


                    },
                },
                {
                    targets: [6], // Column: Phone
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
                    targets: [7], // Column: Email
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
                    targets: [8,9],
                    searchPanes: {
                        show: true,
                    }
                },
                {
                    targets: [10], // Column: Source
                    searchPanes: {
                        show: true,
                    }
                },
                {
                    targets: [11], // Column:  Status
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
                    targets: [12], // Column: Disposition
                    searchPanes: {
                        show: true,
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
                        const actionPopover = '<div class="btn-group dropleft twilio-csv-contact-actions" role="group" aria-label="Contact Actions"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu">'
                            + '<a class="dropdown-item twilio-csv-contact-send-sms" href="#" data-id="' + idString + '" data-contact-first-name="'+firstName+'" data-contact-last-name="'+lastName+'" data-contact-phone="'+phone+'" title="Send SMS Message"><i class="fa fa-comment-o"></i> Send Single SMS</a>'
                            + '<a class="dropdown-item twilio-csv-contact-send-email" href="#" data-id="' + idString + '" title="Send Email"><i class="fa fa-envelope-o"></i> Send Single Email</a>'
                            + /* disposition */ '<a class="dropdown-item twilio-csv-contact-disposition" href="#" data-id="' + idString + '" title="Disposition"><i class="fa fa-check-square-o"></i> Disposition</a>'
                            + '<a class="dropdown-item twilio-csv-contact-interview" href="#" data-id="' + idString + '" data-contact-first-name="'+firstName+'" data-contact-last-name="'+lastName+'" data-contact-phone="'+phone+'" data-contact-email="'+email+'" title="Begin Interview"><i class="fa fa-paper-plane-o"></i> Begin Interview</a>'
                            + /* final interview -> create recruit */ '<a class="dropdown-item twilio-csv-contact-final-interview" href="#" data-id="' + idString + '" data-contact-first-name="'+firstName+'" data-contact-last-name="'+lastName+'" data-contact-phone="'+phone+'" data-contact-email="'+email+'" title="Final Interview"><i class="fa fa-paper-plane"></i> Final Interview</a>'
                            + '<a class="dropdown-item twilio-csv-contact-conversation" href="?page=twilio-csv-conversations&contact_id=' + idString + '" data-id="' + idString + '" title="View Conversation"><i class="fa fa-comments-o"></i> Messages</a><div class="dropdown-divider"></div>'
                            + '<a class="dropdown-item twilio-csv-contact-delete text-danger" href="#" data-id="' + idString + '" title="Delete Contact"><i class="fa fa-exclamation-triangle"></i> Delete</a></div></div>';
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
                        text: "Select Visible",
                        className: "btn-secondary",
                        action: function (e, dt, node, config) {
                            // dt.rows({ search: "applied" }).select();
                            dt.rows({ page: "current" }).select();
                        },
                    },
                    {
                        extend: "selectNone",
                        className: "btn-outline-secondary",
                        text: "De-select All",
                    },
                    {
                        extend: "searchPanes",
                        className: "btn-outline-secondary",
                        config: {
                            cascadePanes: true,
                        }
                    },
                    {
                        text: "Send Bulk SMS",
                        className: "btn-sm btn-primary twilio-csv-send-bulk-sms-modal",
                    },
                    {
                        extend: "refresh",
                    }
                ],
            },
            select: {
                style: "multi",
                selector: "td:first-child",
            },
            order: [[3, "desc"],[2, "asc"]],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100, 250, 500],
            responsive: true,
            dom: 'Bflrtip',
        });

        // .twilio-csv-contact-delete AJAX
        $(document).on("click", ".twilio-csv-contact-delete", function (e) {
            e.preventDefault();
            let id = $(this).data("id");
            let row = $(this).closest("tr");
            let table = $("#twilio-csv-contacts-table").DataTable();
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

    });
})(jQuery);

jQuery.fn.dataTable.ext.buttons.refresh = {
    className: "buttons-refresh btn-secondary",
    text: "Refresh",
    action: function (e, dt, node, config) {
        dt.ajax.reload();
    }
};