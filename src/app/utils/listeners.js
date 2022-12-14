import * as modals from "./modals/modals.js";
import * as modalLaunchers from "./modals/modalLaunchers.js";
import * as dropdown from "./dropdownHandler";
import * as toast from "./toast/toast.js";
import * as bulkSMSModal from "./modals/bulkSMSModal.js";
import * as singleSMSModal from "./modals/singleSMSModal.js";
import * as dispositionModal from "./modals/dispositionModal.js";
import * as firstInterviewModal from "./modals/firstInterviewModal.js";
import * as finalInterviewModal from "./modals/finalInterviewModal.js";
import { TwilioCSVFormListeners } from "./forms/formListeners.js";
import { addNewSingleContactHandler } from "./upload-create-contacts/createSingleContact.js";
import { changeConvoName, setConvoName, TwilioCSVConversationHandler } from "./conversations/Conversations.js";
import { TwilioCSVProgrammableMessages } from "./programmableMessages.js";
import { TwilioCSVScheduledBriefings } from "./briefings/Briefings.js";
import { TwilioCSVContacts } from "./upload-create-contacts/Contacts.js";
import { TwilioCSVCallbackForm } from "./callbacks/CallbackForm.js";
import { createScheduledCallbackDataTable } from "./dataTables/scheduledCallbacks.js";
import { userAdmin } from "./forms/userAdmin.js";
const $ = jQuery;

export class TwilioCSVListeners {
    constructor() {
        this.listeners();
    }

    listeners() {
        $(document).ready(function () {
            setConvoName();

            dropdown.mergeTag();
            dropdown.programmableSMS('input[name="body"]');
            dropdown.programmableSMS('textarea[name="body"]');
            toast.toastListener();

            // Begin Modal Listeners
            modalLaunchers.onClickListeners();
            bulkSMSModal.bulkSMSModalHandlers();
            singleSMSModal.singleSmsModalHandlers();
            dispositionModal.dispositionModalHandlers();
            firstInterviewModal.firstInterviewModalHandlers();
            finalInterviewModal.finalInterviewModalHandlers();

            modals.smsKeyup();
            addNewSingleContactHandler();

            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = urlParams.get("page");
            // if current page contains /twilio-csv/
            const frontEnd = window.location.href.indexOf('/recruiting/') > -1;

            
            if (currentPage === "twilio-csv-conversations" || frontEnd) {
                let conversations = new TwilioCSVConversationHandler();
                let callbackForm = new TwilioCSVCallbackForm();
                conversations.viewConversation();
            }
            if (currentPage === "twilio-csv-programmable-messages" || frontEnd) {
                let programmableMessages = new TwilioCSVProgrammableMessages();
                programmableMessages.handlers();
            }

            if (currentPage === "twilio-csv-scheduled-briefings" || frontEnd) {
                let scheduledBriefings = new TwilioCSVScheduledBriefings();
                // scheduledBriefings.handlers();
            }

            if (currentPage === "twilio-csv-upload-contacts" || frontEnd) {
                let contacts = new TwilioCSVContacts();
                // contacts.handlers();
            }

            if (currentPage === "twilio-csv-scheduled-callbacks" || frontEnd) {
                createScheduledCallbackDataTable();
            }

            if (currentPage === "twilio-csv-user-admin" || frontEnd) {
                userAdmin();
            }

            new TwilioCSVFormListeners();

            // hook front page button #home-go-to-f1 to navigate to #f1 tab panel
            $('#home-go-to-f1').on('click', function (e) {
                e.preventDefault();
                $('#f1-tab').tab('show');
            });

        });
    }
}

export function refreshDt(table) {
    table.ajax.reload(null, false);
}