
// init datetimepicker for input[name="call-back-date"]
export function dateTimePickerField(field) {
   return jQuery(document).ready(function () {
      jQuery(field).datetimepicker({
         format: 'Y-m-d H:i A',
         minDate: 0,
         step: 15,
      });
   });
}