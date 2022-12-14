import { Toast } from "bootstrap";

export function toastListener() {
    const toastTrigger = document.getElementById('liveToastBtn');
    const toastLiveExample = document.getElementById('toast-test');
    const targetElement = document.querySelector('[data-bs-toast="stack"]');
    const container = document.querySelector('.toast-stack-container');

    targetElement.parentNode.removeChild(targetElement);


    if (toastTrigger) {
        toastTrigger.addEventListener('click', e => {
            e.preventDefault();

            var newToast = targetElement.cloneNode(true);
            container.append(newToast);

            var toast = Toast.getOrCreateInstance(newToast);

            toast.show();
        })
    }
}