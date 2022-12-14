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
	// 	const ajaxurl = twilio_csv_ajax.ajax_url;
	// 	const nonce = twilio_csv_ajax.nonce;
	// 	const action = twilio_csv_ajax.action;

	// 	$('#create-programmable-message-form').submit(function (e) {
	// 		e.preventDefault();
	// 		const formData = new FormData(this);
	// 		formData.append('action', action);
	// 		formData.append('nonce', nonce);
	// 		formData.append('method', 'create_programmable_message');

	// 		console.log(formData);

	// 		$('#create-programmable-message-form').find('button[type="submit"]').attr('disabled', true);
	// 		$('#create-programmable-message-form').find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...');

	// 		$.ajax({
	// 			url: ajaxurl,
	// 			type: 'POST',
	// 			processData: false,
	// 			contentType: false,
	// 			data: formData,
	// 			success: function (response) {
	// 				console.log(response);
	// 			},
	// 			error: function (error) {
	// 				console.log(error);
	// 			},
	// 			complete: function () {
	// 				$('#create-programmable-message-form').find('button[type="submit"]').attr('disabled', false);
	// 				$('#create-programmable-message-form').find('button[type="submit"]').html('Create');
	// 			}

	// 		});
	// 	});

	// 	$('.merge-tag-selector').click(function (e) {
	// 		e.preventDefault();
	// 		const mergeTag = $(this).data('tag');
	// 		// find nearest textarea
	// 		const textarea = $(this).closest('.form-group').find('textarea');
	// 		textarea.val(textarea.val() + mergeTag);
	// 	});

	// 	$('.message-action').click(function (e) {
	// 		e.preventDefault();
	// 		const messageId = $(this).data('id');
	// 		const action = $(this).data('action');
	// 		const ajaxUrl = twilio_csv_ajax.ajax_url;
	// 		const nonce = twilio_csv_ajax.nonce;
	// 		const ajaxAction = twilio_csv_ajax.action;
			
	// 		if (action === 'edit_message') {
	// 			$.ajax({
	// 				url: ajaxurl,
	// 				type: 'GET',
	// 				data: {
	// 					action: ajaxAction,
	// 					nonce: nonce,
	// 					method: 'get_programmable_message',
	// 					id: messageId
	// 				},
	// 				success: function (response) {
	// 					console.log(response);

	// 					// navigate to the #twilio-csv-programmable-messages-edit nav tab tab-pane
	// 					$('#twilio-csv-programmable-messages-edit').tab('show');

	// 					// select the message with the matching id
	// 					$('#edit-programmable-message-form').find('select[name="id"]').val(messageId);
	// 					// populate the form with the message data
	// 					$('#edit-programmable-message-form').find('input[name="_title"]').val(response._title);
	// 					$('#edit-programmable-message-form').find('textarea[name="_body"]').val(response._body);
	// 				},
	// 				error: function (error) {
	// 					console.log(error);
	// 				}
	// 			});
	// 		}
	// 	});

	// 	$('#edit-programmable-message-form').submit(function (e) {
	// 		e.preventDefault();
	// 		const formData = new FormData(this);
	// 		formData.append('action', action);
	// 		formData.append('nonce', nonce);
	// 		formData.append('method', 'update_programmable_message');

	// 		console.log(formData);

	// 		$('#edit-programmable-message-form').find('button[type="submit"]').attr('disabled', true);
	// 		$('#edit-programmable-message-form').find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

	// 		$.ajax({
	// 			url: ajaxurl,
	// 			type: 'POST',
	// 			processData: false,
	// 			contentType: false,
	// 			data: formData,
	// 			success: function (response) {
	// 				console.log(response);
	// 			},
	// 			error: function (error) {
	// 				console.log(error);
	// 			},
	// 			complete: function () {
	// 				$('#edit-programmable-message-form').find('button[type="submit"]').attr('disabled', false);
	// 				$('#edit-programmable-message-form').find('button[type="submit"]').html('Update');
	// 			}

	// 		});
	// 	});
	// });

})(jQuery);