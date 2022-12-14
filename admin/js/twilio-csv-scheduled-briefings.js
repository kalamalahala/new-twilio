(function ($) {
    'use strict';

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

    // $(document).ready(function () {
    //     const ajaxurl = twilio_csv_ajax.ajax_url;
    //     const nonce = twilio_csv_ajax.nonce;
    //     const action = twilio_csv_ajax.action;
    //     const dtMethod = "get_all_briefings";
    //     const dtUrlString =
    //         ajaxurl +
    //         "?action=" +
    //         action +
    //         "&nonce=" +
    //         nonce +
    //         "&method=" +
    //         dtMethod;

    //     $('#scheduled-briefings-view-table').DataTable({
    //         "processing": true,
    //         "ajax": {
    //             "url": dtUrlString,
    //             "dataSrc": ""
    //         },
    //         columns: [
    //             { data: 'id' },
    //             { data: '_created' },
    //             { data: '_updated' },
    //             { data: '_scheduled' },
    //             { data: '_title' },
    //             { data: '_weblink' },
    //             { data: '_body' },
    //             { defaultContent: "" }
    //         ],
    //         columnDefs: [
    //             {
    //                 targets: [0,6],
    //                 visible: false,
    //                 searchable: false
    //             },
    //             {
    //                 targets: [1,2,3],
    //                 searchPanes: {
    //                     show: false
    //                 },
    //                 render: function (data, type, row) { // render date strings
    //                     if (!data || data === null) {
    //                         return "";
    //                     } else if (data === "0000-00-00 00:00:00") {
    //                         return "";
    //                     }

    //                     let date = new Date(data);
    //                     let dateString = date.toLocaleString();
    //                     let dateSplit = dateString.split(",");
    //                     let dateFormatted = dateSplit[0] + "<br />" + dateSplit[1];
    //                     return dateFormatted;
    //                 },
    //             },
    //             {
    //                 targets: [-1],
    //                 orderable: false,
    //                 searchable: false,
    //                 render: function (data, type, row) {
    //                     const briefingId = row.id;
                        
    //                     const editButton = '<a href="#" class="btn btn-primary btn-sm edit-briefing" data-briefing-id="' + briefingId + '">Edit</a>';
    //                     const deleteButton = '<a href="#" class="btn btn-secondary btn-sm delete-briefing" data-briefing-id="' + briefingId + '">Delete</a>';
    //                     return editButton + '&nbsp;' + deleteButton;
    //             },
    //         }],
    //         dom: 'Bfrtip',
    //         buttons: {
    //             dom: {
    //                 button: {
    //                     className: "btn btn-sm",
    //                 },
    //             },
    //         },
    //         order: [[3, "asc"]],
    //         searchPanes: {
    //             cascadePanes: true,
    //             layout: "columns-3",
    //             viewTotal: true,
    //             columns: [1,2,3]
    //         },
    //         pageLength: 25,
    //         lengthMenu: [5, 10, 25, 50, 100],
    //         responsive: true,
    //     });


    //     $('#_scheduled').datetimepicker({
    //         format: 'Y-m-d h:i A',
    //         timeFormat: 'h:mm tt',
    //         minDate: 0,
    //         step: 15,
    //         validateOnBlur: false,
    //     });

    //     $(document).on('submit', '#scheduled-briefings-form', function (e) {
    //         e.preventDefault();
    //         const formData = new FormData(this);
    //         console.log(formData);
    //         formData.append('action', action);
    //         formData.append('nonce', nonce);
    //         // get ajax method from input #_method
    //         const dtMethod = $('#_method').val();
    //         formData.append('method', dtMethod);

    //         // disable button[type=submit]
    //         const submitBtn = $(this).find('button[type=submit]');
    //         submitBtn.prop('disabled', true);
    //         submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating Briefing...');


    //         // show spinner and 'Creating...' text



    //         $.ajax({
    //             url: ajaxurl,
    //             type: 'POST',
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //             success: function (response) {
    //                 console.log(response);
    //                 if (response.success == true || response.success == 'true') {
    //                     // create dismissable bootstrap4 success alert inside .alert-container
    //                     const alert = `<div class="alert alert-success alert-dismissible fade show w-50" role="alert">${response.message}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`;
    //                     $('.alert-container').html(alert);
    //                 }

    //                 },
    //                 error: function(error) {
    //                     console.log(error);
    //                     // create dismissable bootstrap4 error alert inside .alert-container
    //                     const alert = `<div class="alert alert-danger alert-dismissible fade show w-50" role="alert">${error.message}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`;
    //                     $('.alert-container').html(alert);
    //                 },
    //                 complete: function() {
    //                     // enable button[type=submit]
    //                     submitBtn.prop('disabled', false);
    //                     submitBtn.html('Create Briefing');

    //                 }
    //             });

    //         return true;
    //     });

    //     // edit briefing
    //     $(document).on('click', '.edit-briefing', function (e) {
    //         e.preventDefault();
    //         // get data from row that the button was clicked in table #scheduled-briefings-view-table
    //         const briefingId = $(this).data('briefing-id');
    //         const briefingRow = $(this).closest('tr');
    //         const briefingData = $('#scheduled-briefings-view-table').DataTable().row(briefingRow).data();
    //         console.log(briefingData);

    //         const briefingTitle = briefingData._title;
    //         const briefingBody = briefingData._body;
    //         const briefingScheduled = briefingData._scheduled;
    //         const briefingWeblink = briefingData._weblink;


    //         $('#scheduled-briefings-form').find('input[name=_id]').val(briefingId);
    //         $('#scheduled-briefings-form').find('input[name=_title]').val(briefingTitle);
    //         $('#scheduled-briefings-form').find('input[name=_weblink]').val(briefingWeblink);
    //         $('#scheduled-briefings-form').find('input[name=_scheduled]').val(briefingScheduled);
    //         // update tinyMCE instance
    //         tinyMCE.get('scheduled-briefings-body').setContent(briefingBody);
    //         $('#scheduled-briefings-form').find('button[type=submit]').html('Update Briefing');
    //         $('#scheduled-briefings-form').find('input[name=_method]').val('update_briefing');

    //         // navigate to #twilio-csv-scheduled-briefings-edit tab pane
    //         $('#twilio-csv-scheduled-briefings-create-tab').tab('show');
            
    //     });

    //     // redraw dataTable when #twilio-csv-scheduled-briefings-view-tab is clicked
    //     $(document).on('click', '#twilio-csv-scheduled-briefings-view-tab', function (e) {
    //         // redraw ajax dataTable
    //         $('#scheduled-briefings-view-table').DataTable().ajax.reload();
    //     });

    //     // set _method to schedule_briefing when #twilio-csv-scheduled-briefings-create-tab is clicked
    //     $(document).on('click', '#twilio-csv-scheduled-briefings-create-tab', function (e) {
    //         $('#scheduled-briefings-form').find('input[name=_method]').val('schedule_briefing');
    //     });

    //     // delete briefing
    //     $(document).on('click', '.delete-briefing', function (e) {
    //         e.preventDefault();
    //         const ajaxUrl = ajaxurl;
    //         const ajaxAction = 'twilio_csv_ajax';
    //         const ajaxNonce = nonce;
    //         const ajaxMethod = 'delete_briefing';
    //         const briefingId = $(this).data('briefing-id');
            
    //         // confirm then send ajax with method delete_briefing
    //         if (confirm('Are you sure you want to delete this briefing?')) {
    //             $.ajax({
    //                 url: ajaxUrl,
    //                 type: 'POST',
    //                 data: {
    //                     action: ajaxAction,
    //                     nonce: ajaxNonce,
    //                     method: ajaxMethod,
    //                     briefing_id: briefingId
    //                 },
    //                 success: function (response) {
    //                     console.log(response);
    //                 },
    //                 error: function(error) {
    //                     console.log(error);
    //                 },
    //                 complete: function() {
    //                     // redraw ajax dataTable
    //                     $('#scheduled-briefings-view-table').DataTable().ajax.reload();
    //                 }
    //             });
    //         }
    //     });

    // });

})(jQuery);