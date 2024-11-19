import "bootstrap-icons/font/bootstrap-icons.css";
// Popover
import { Popover, Tooltip, Toast } from "bootstrap";
document.addEventListener("DOMContentLoaded", function () {
    var popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new Popover(popoverTriggerEl);
    });
    // Detect touch events to dismiss the popover on touch devices
    document.addEventListener("touchstart", function (event) {
        popoverList.forEach(function (popover) {
            // If the clicked/touched element is not inside the popover, hide the popover
            // if (!event.target.closest('[data-bs-toggle="popover"]')) {
            popover.hide();
            // }
        });
    });
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl);
    });
    var toastEl = document.getElementById("toast_notification");
    if (toastEl) {
        var toast = new Toast(toastEl, {
            delay: 5000,
        });
        toast.show();
    }
});

// Global function
function toSnakeCase(str) {
    return str
        .replace(/\s+/g, "_") // Replace spaces with underscores
        .replace(/[A-Z]/g, (letter) => `_${letter.toLowerCase()}`) // Add underscore before uppercase letters and convert to lowercase
        .replace(/^-/, ""); // Remove any leading underscore
}

// import Bootstrap5
import "bootstrap";
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;
