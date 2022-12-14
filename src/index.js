const $ = jQuery;
import "./style.css";
import "./_custom.scss";
import * as twilioCsvPublic from "./app/public";
import * as twilioCsvListeners from "./app/utils/listeners";
let loadingOverlay = $("#loading-overlay");
let loadingText = $("#loading-text");
loadingText.text("Loading Twilio CSV Plugin...");

function load() {
  // Initialize the public class
  let publicInit = new twilioCsvPublic.TwilioCSVPublic();

  // Initialize the listeners class
  let listeners = new twilioCsvListeners.TwilioCSVListeners();

  $(document).ready(function () {
    // hide loading overlay
    loadingOverlay.hide();
  });
}

// load the plugin
load();