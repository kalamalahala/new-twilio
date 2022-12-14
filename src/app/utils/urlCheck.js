export const urlCheck = () => {

    let url = window.location.href;

    if (url.includes('/recruiting/')) {
        console.debug('Twilio CSV plugin is active');
        console.error(url);
    } else {
        console.debug('Twilio CSV plugin is not active');
       console.error(url);
    }
}