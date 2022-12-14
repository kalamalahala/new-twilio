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
        const dataTable = $('#twilio-csv-recruits-table');
        const dataTableBody = dataTable.find('tbody');
        const ajax_url = twilio_csv_ajax.ajax_url;
        const ajax_nonce = twilio_csv_ajax.nonce;
        const ajax_action = twilio_csv_ajax.action;
        const dtMethod = "get_recruits";
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
                { data: "_recruit_id" },             // 0
                {                           // 1
                    defaultContent: "",
                    width: "3%"
                },
                { data: "_interview_date" },       // 2
                { data: "_updated" },       // 3
                { data: "_time_in_xcel" },  // 4
                { data: "_ple_percent" },   // 5
                { data: "_ple_completion_date" },     // 6
                { data: "_first_name" },     // 7
                { data: "_last_name" },     // 8
                { data: "_phone" },     // 9
                { data: "_email" },     // 10
                { data: "_source" },     // 11
                { data: "_prep_percent" },     // 12
                { data: "_sim_percent" },     // 13
                { data: "_prepared_to_pass" },     // 14
                { defaultContent: "" },     // 15
            ],
            createdRow: function (row, data, dataIndex) {
                let id = data._recruit_id ? data._recruit_id : "";
                let _status = data._status ? data._status : "";
                let _disposition = data._disposition ? data._disposition : "";
                let _source = data._source ? data._source : "";

                // if (_disposition == "Do Not Call") {
                //     $(row).addClass("alert alert-danger");
                // } else if (_status == "New Message" && _disposition !== "Do Not Call") {
                //     $(row).addClass("alert alert-success");
                // }

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
                loadingRecords: "Loading Recruits...",
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
                    targets: [2, 3, 6], // Column: Interview, Last Updated
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
                    targets: [9], // Column: Phone
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
                    targets: [10], // Column: Email
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
                    targets: [5, 12, 13], // Column: PLE %, Prep %, Sim %
                    searchPanes: {
                        show: false,
                    },
                    render: function (data, type, row) {
                        if (data === null) {
                            return "";
                        } else {
                            return data + "%";
                        }
                    }
                },
                {
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13],
                    searchPanes: {
                        show: false,
                    }
                },
                {
                    targets: [11], // Column: Source
                    searchPanes: {
                        show: true,
                    }
                },
                {
                    targets: [14], // Column:  Prepared to Pass?
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
                    targets: [-1], // Column: Actions
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        /* Render actions as a button group */
                        // bootstrap 4 small button: View
                        // Get the contact id from the ajax request
                        const idString = row._recruit_id.toString();
                        console.log(idString);
                        const firstName = row._first_name;
                        const lastName = row._last_name;
                        const phone = row._phone;
                        const timeInXCEL = row._time_in_xcel;
                        const plePercent = row._ple_percent;
                        const prepPercent = row._prep_percent;
                        const simPercent = row._sim_percent;
                        const preparedToPass = row._prepared_to_pass;
                        const actionPopover = '<div class="btn-group dropleft twilio-csv-recruit-actions" role="group" aria-label="recruit Actions"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu">'
                            + '<a class="dropdown-item twilio-csv-recruit-send-sms" href="#" data-id="' + idString + '" data-recruit-first-name="' + firstName + '" data-recruit-last-name="' + lastName + '" data-recruit-phone="' + phone + '" title="Send SMS Message"><i class="fa fa-comment-o"></i> Send SMS</a>'
                            + '<a class="dropdown-item twilio-csv-update-recruit" href="#" data-id="' + idString + '"'
                            + 'data-recruit-first-name="' + firstName + '"' + 'data-recruit-last-name="' + lastName + '"' + 'data-recruit-phone="' + phone + '"' + 'data-recruit-time-in-xcel="' + timeInXCEL + '"' + 'data-recruit-ple-percent="' + plePercent + '"' + 'data-recruit-prep-percent="' + prepPercent + '"' + 'data-recruit-sim-percent="' + simPercent + '" data-recruit-prepared-to-pass="' + preparedToPass + '"'
                            + 'title="Update Recruit"><i class="fa fa-pencil"></i> Update Recruit</a>'
                            + '<a class="dropdown-item twilio-csv-recruit-delete text-danger" href="#" data-id="' + idString + '" title="Delete recruit"><i class="fa fa-exclamation-triangle"></i> Delete</a></div></div>';
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
            order: [[3, "desc"], [2, "asc"]],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100, 250, 500],
            responsive: true,
            dom: 'Bflrtip',
        });

        // .twilio-csv-contact-delete AJAX
        $(document).on("click", ".twilio-csv-recruit-delete", function (e) {
            e.preventDefault();
            let id = $(this).data("id");
            console.log('id: ', id);
            let row = $(this).closest("tr");
            console.log('row: ', row);
            let table = $("#twilio-csv-recruits-table").DataTable();
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
                console.log(id);
                $.ajax({
                    url: twilio_csv_ajax.ajax_url,
                    type: "POST",
                    data: {
                        action: twilio_csv_ajax.action,
                        nonce: twilio_csv_ajax.nonce,
                        method: 'delete_recruit',
                        id: id,
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (error) {
                        console.log(error);
                    },
                    complete: function () {
                        table.ajax.reload();
                    }
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