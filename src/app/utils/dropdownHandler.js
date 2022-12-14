import { listenKeyUp } from "./modals/modals";
import { listenBulkSMSKeyUp } from "./modals/modals";

const $ = jQuery;

export function mergeTag() {
    // Insert merge tag
    $(document).on('click', '.merge-tag-selector', function (e) {
        e.preventDefault();
        let mergeTag = $(this).data('tag');

        // add merge tag to element with name 'body' in the same form
        let form = $(this).closest('form');
        let body = form.find('textarea[name$="body"]');
        let bodyVal = body.val();

        // insert additional space before and after merge tag, if whitespace is not already present
        if (bodyVal.length > 0) {
            if (bodyVal.charAt(bodyVal.length - 1) !== ' ') {
                mergeTag = ' ' + mergeTag;
            }
            if (bodyVal.charAt(0) !== ' ') {
                mergeTag = mergeTag + ' ';
            }
        }

        body.val(bodyVal + mergeTag);
        // listenKeyUp(body);
    });
}

export function programmableSMS(field) {
        $(document).on('click', '.programmable-message-selector', function (e) {
            e.preventDefault();
            console.log('programmable message clicked');
            console.log(field);
        
            const bodyField = $(this).closest('form').find(field);
            console.log(bodyField);
            const selectedProgrammableMessageBody = $(this).data('message');

            // append programmable message body to body field existing value
            bodyField.val(bodyField.val() + selectedProgrammableMessageBody);
            
            // if (field === 'input[name="body"]') {
            //     listenBulkSMSKeyUp(bodyField);
            // } else {
            // listenKeyUp(bodyField);
            // }
            // trigger keyup event to update character count
        });
}