// on-click listeners for the modals in the plugin
const $ = jQuery;
import { launchModal } from "./modals";
import { populateBulkSMSModal } from "./bulkSMSModal";
import { populateSendSingleModal } from "./singleSMSModal";

export const onClickListeners = () => {
    // On click listener for Bulk SMS
    $(document).on('click', '.twilio-csv-send-bulk-sms-modal', function (e) {
        e.preventDefault();
        let currentText = $(this).text();
        const smsModal = $('#send-bulk-sms');
        const table = $('.dataTables_wrapper').find('table').DataTable();
        const selectedRows = table.rows('.selected').count();
        
        if (selectedRows === 0) {
            $(this).text('No Contacts Selected');
            setTimeout(() => {
                $(this).text(currentText);
            }, 2000);
            return;
        }
        populateBulkSMSModal(table);
        launchModal(smsModal);
    });

    $(document).on('click', '.twilio-csv-contact-send-sms', function (e) {
        e.preventDefault();
        // console.debug($(this));
        populateSendSingleModal($(this).data());
        launchModal('#send-single-sms');
    });

    $(document).on('click', '.twilio-csv-contact-disposition', function (e) {
        e.preventDefault();
        const dispositionModal = $('#disposition-modal');

        // get the data-id attribute of the clicked row
        let contactId = $(this).data('id');
        $('#disposition-form-contact-id').val(contactId);
        launchModal(dispositionModal);
    });

    $(document).on('click', '.twilio-csv-contact-final-interview', function (e) {
        e.preventDefault();
        const finalInterviewModal = $('#final-interview-modal');

        // get the data-id attribute of the clicked row
        let contactId = $(this).data('id');
        let contactFirstName = $(this).data('contact-first-name');
        let contactLastName = $(this).data('contact-last-name');
        let contactFullName = contactFirstName + ' ' + contactLastName;
        let contactPhone = $(this).data('contact-phone');
        let contactEmail = $(this).data('contact-email');

        // replace all instances of .final-interview-name with contactFirstName
        $('.final-interview-name').text(contactFirstName);
        $('.final-interview-name-header').text(contactFirstName + ' ' + contactLastName);
        $('#final-interview-contact-id').val(contactId);
        $('#final-interview-full-name').val(contactFullName);
        $('#candidateFirstName').val(contactFirstName);
        $('#candidateLastName').val(contactLastName);
        $('#candidatePhone').val(contactPhone);
        $('#candidateEmail').val(contactEmail);

        launchModal(finalInterviewModal);
    });

    $(document).on('click', '.twilio-csv-contact-interview', function (e) {
        e.preventDefault();
        console.log('clicked!');
        const firstInterviewModal = $('#begin-interview-form-modal');
        const contactId = $(this).data('id');
        const contactFirstName = $(this).data('contact-first-name');
        const contactLastName = $(this).data('contact-last-name');
        const contactEmail = $(this).data('contact-email');
        const contactPhone = $(this).data('contact-phone');
        // get the text of the currently selected option inside of #select-briefing
        const briefingText = $('#select-briefing').find('option:selected').text();

        const contactIdField = $('#begin-interview-form').find('input[name="contact-id"]');
        const fullNameField = $('#begin-interview-form').find('input[name="full-name"]');
        const emailField = $('#begin-interview-form').find('input[name="email-address"]');


        $('.candidate-name').text(contactFirstName + ' ' + contactLastName);
        $('.candidate-name-small').text(contactFirstName);
        $('.candidate-phone').html('<a href="tel:' + contactPhone + '">' + contactPhone + '</a>');
        $('.candidate-email').html('<a href="mailto:' + contactEmail + '">' + contactEmail + '</a>');

        $('.selected-briefing-date').text(briefingText);
        contactIdField.val(contactId);
        fullNameField.val(contactFirstName + ' ' + contactLastName);
        emailField.val(contactEmail);

        launchModal(firstInterviewModal);
    });

    $(document).on('click', '.twilio-csv-update-recruit', function (e) {
        e.preventDefault();
        const updateRecruitModal = $('#update-recruit-modal');
        const updateRecruitForm = $('#update-recruit-form');
        const recruitIdField = $('#recruit-id-field');
        const preparedToPassField = $('#prepared-to-pass');
        const timeInXCELField = $('#time-in-xcel');
        const plePercentField = $('#ple-percent');
        const prepCompletionField = $('#prep-completion');
        const simCompletionField = $('#sim-completion');

        // get the data-id attribute of the clicked row
        let contactId = $(this).data('id');
        let recruitFirstName = $(this).data('recruit-first-name');
        let recruitLastName = $(this).data('recruit-last-name');
        let recruitPhone = $(this).data('recruit-phone');
        let recruitEmail = $(this).data('recruit-email');
        let timeInXCEL = $(this).data('time-in-xcel');
        let plePercent = $(this).data('ple-percent');
        let prepCompletion = $(this).data('prep-completion');
        let simCompletion = $(this).data('sim-completion');
        let preparedToPass = $(this).data('prepared-to-pass');

        // replace all instances of .recruit-first-name with recruitFirstName
        $('.recruit-first-name').text(recruitFirstName);

        recruitIdField.val(contactId);
        timeInXCELField.val(timeInXCEL);
        plePercentField.val(plePercent);
        prepCompletionField.val(prepCompletion);
        simCompletionField.val(simCompletion);
        preparedToPassField.find('option').each(function () {
            if ($(this).val() === preparedToPass) {
                $(this).prop('selected', true);
            }
        });

        launchModal(updateRecruitModal);
    });
}