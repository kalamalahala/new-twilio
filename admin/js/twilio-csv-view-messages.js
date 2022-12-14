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
        const dataTable = $('#twilio-csv-messages-table');
        const dataTableBody = dataTable.find('tbody');
        const ajax_url = twilio_csv_ajax.ajax_url;
        const ajax_nonce = twilio_csv_ajax.nonce;
        const ajax_action = twilio_csv_ajax.action;
        const dtMethod = "get_all_messages";
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
                { data: "_Body" },        // 8
                { defaultContent: "" },     // 9
            ],
            createdRow: function (row, data, dataIndex) {
                let id = data.id ? data.id : "";
                let _status = data._status ? data._status : "";
                let _source = data._source ? data._source : "";

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
                loadingRecords: "Loading Messages...",
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
                        const actionPopover = '<div class="btn-group dropleft twilio-csv-contact-actions" role="group" aria-label="Contact Actions"><button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button><div class="dropdown-menu">'
                            + '<a class="dropdown-item twilio-csv-contact-send-sms" href="#" data-id="' + idString + '" data-contact-first-name="'+firstName+'" data-contact-last-name="'+lastName+'" data-contact-phone="'+phone+'" title="Send SMS Message"><i class="fa fa-comment-o"></i> Send Single SMS</a>'
                            + '<a class="dropdown-item twilio-csv-contact-send-email" href="#" data-id="' + idString + '" title="Send Email"><i class="fa fa-envelope-o"></i> Send Single Email</a>'
                            + '<a class="dropdown-item twilio-csv-contact-interview" href="https://thejohnson.group/agent-portal/recruiting/twiliocsv-interview/?contact_id='+ idString +'" data-id="' + idString + '" title="Begin Interview"><i class="fa fa-paper-plane-o"></i> Begin Interview</a>'
                            + '<a class="dropdown-item twilio-csv-contact-conversation" href="?page=twilio-csv-conversations&contact_id=' + idString + '" data-id="' + idString + '" title="View Conversation"><i class="fa fa-comments-o"></i> Messages</a><div class="dropdown-divider"></div>'
                            + '<a class="dropdown-item twilio-csv-contact-delete text-danger" href="#" data-id="' + idString + '" title="Delete Contact"><i class="fa fa-exclamation-triangle"></i> Delete</a></div></div>';
                        return actionPopover;
                    },
                },
            ],
            buttons: {
                dom: {
                    button: {
                        className: "btn btn-sm btn-secondary",
                    },
                },
                buttons: [
                    {
                        text: "Select Visible",
                        action: function (e, dt, node, config) {
                            dt.rows({ page: "current" }).select();
                            // dt.rows({ search: "applied" }).select();
                        },
                    },
                    {
                        extend: "selectNone",
                        className: "btn-secondary",
                        text: "De-select All",
                    },
                    {
                        extend: "searchPanes",
                        config: {
                            cascadePanes: true,
                        },
                    },

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

    });
})(jQuery);