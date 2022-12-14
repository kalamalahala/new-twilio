import { createPublicDtViewCandidates } from "./utils/dataTables/viewCandidates";
import { filterDNCToggle } from "./utils/dataTables/viewCandidates";
import { assignToUsersFormSubmit } from "./utils/forms/assignToUsersForm";
const $ = jQuery;
const users = twilio_csv_ajax.users;
const currentUser = twilio_csv_ajax.current_user_role;



export class TwilioCSVPublic {
    constructor() {
        this.init();
    }
    init() {
        // create DataTables
        const table = createPublicDtViewCandidates();
        const filter = filterDNCToggle(table);
        // add checkbox to view DNC candidates
        $('div.see-dnc').html(`
        <div class="row mt-3">
        <div class="col-md-6">
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="show-dnc" id="show-dnc" value="true">
            Show DNC Candidates
          </label>
        </div>
        </div>
        </div>
        `);

        if (currentUser === 'administrator' || currentUser === 'twilio_csv_manager' || currentUser === 'twilio_csv_admin') {
            // create assign to user form
            const assignToUserForm = this.createAssignToUserForm(users);
            assignToUsersFormSubmit();
        }

        // add event listener to checkbox
        $('#show-dnc').on('change', function () {
            table.draw();
        });

        // table.on('select', function (e, dt, type, indexes) {
        //     let selectedCount = table.rows({ selected: true }).count();
        //     if (selectedCount > 0) {
        //         $('#twilio-csv-send-bulk-sms-modal').prop('disabled', false);
        //     } else {
        //         $('#twilio-csv-send-bulk-sms-modal').prop('disabled', true);
        //     }
        // });
        // table.on('deselect', function () {
        //     let selectedCount = table.rows({ selected: true }).count();
        //     if (selectedCount > 0) {
        //         $('#twilio-csv-send-bulk-sms-modal').prop('disabled', false);
        //     } else {
        //         $('#twilio-csv-send-bulk-sms-modal').prop('disabled', true);
        //     }
        // });
        
    }
    
    createAssignToUserForm(listOfUsers) {
        let optionGroup = '';
        $.each(listOfUsers, (key, value) => {
            optionGroup += `<option value="${value.id}">${value.full_name}</option>`;
        });
        $('div.form-assign-to-user').html(`<form class="row row-cols-lg-auto g-3 align-items-top mt-1 mb-1" id="assign-to-user-form">
                                            <div class="col-12">
                                                <select name="user-id-list" id="user-id-list" class="form-select" placeholder="" aria-describedby="helpUserIdList">
                                                    <option value="">Select User</option>
                                                    ${optionGroup}
                                                </select>
                                                <div id="helpUserIdList" class="form-text">Select a user to assign the selected candidates to.</div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-paper-plane"></i> Assign</button>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-dismissable alert-success d-none" role="alert" id="assign-to-user-success">
                                                    <i class="fa-solid fa-check-circle"></i> <span class="response-message"></span>.
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                <div class="alert alert-dismissable alert-danger d-none" role="alert" id="assign-to-user-error">
                                                    <i class="fa-solid fa-exclamation-circle"></i> <span class="response-message"></span>.
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            </div>
                                            
                                            </form>`);
    }
}