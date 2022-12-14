const $ = jQuery;
let loadingOverlay = $('#loading-overlay');
let loadingHeader = $('#loading-header');
let loadingText = $('#loading-text');

export function displayLoadingOverlay(headerText, bodyText) {
    loadingHeader.text(headerText);
    loadingText.text(bodyText);
    loadingOverlay.show();
}

export function hideLoadingOverlay() {
    loadingOverlay.hide();
}