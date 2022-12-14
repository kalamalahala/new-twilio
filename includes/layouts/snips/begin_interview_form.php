<?php


/** form to display at the beginning of the interview process 
 * 
 */
$scheduled_briefings = TwilioCSVBriefing::get_future_briefings();

?>

<form id="begin-interview-form">
  <!-- hidden fields -->
  <input type="hidden" name="contact-id" value="" />
  <input type="hidden" name="full-name" value="">
  <!-- i am passing this form element -->
  <div class="d-flex justify-content-between">
    <div class="candidate-wrapper d-inline-flex">
      Candidate: <span class="candidate-name">NAME</span>
      <span class="candidate-phone">PHONE</span>
    </div>
    <div class="form-group">
      <label>Did client answer?</label>
      <div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="did-client-answer-yn" id="did-client-answer-yn_0" type="radio" class="custom-control-input" value="Yes" checked>
            <label for="did-client-answer-yn_0" class="custom-control-label">Yes</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="did-client-answer-yn" id="did-client-answer-yn_1" type="radio" class="custom-control-input" value="No">
            <label for="did-client-answer-yn_1" class="custom-control-label">No</label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="no-answer-section d-none">
    <div class="form-group">
      <label>No Answer from Contact</label>
      <div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_5" type="radio" class="custom-control-input" value="No Answer" disabled>
            <label for="no-answer_5" class="custom-control-label">No Answer</label>
          </div>
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_0" type="radio" class="custom-control-input" value="Left Voicemail" disabled>
            <label for="no-answer_0" class="custom-control-label">Left Voicemail</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_1" type="radio" class="custom-control-input" value="Voicemail Full" disabled>
            <label for="no-answer_1" class="custom-control-label">Voicemail Full</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_2" type="radio" class="custom-control-input" value="No Voicemail" disabled>
            <label for="no-answer_2" class="custom-control-label">No Voicemail</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_3" type="radio" class="custom-control-input" value="Not In Service / Disconnected" disabled>
            <label for="no-answer_3" class="custom-control-label">Not In Service / Disconnected</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="no-answer" id="no-answer_4" type="radio" class="custom-control-input" value="Call Back" disabled>
            <label for="no-answer_4" class="custom-control-label">Call Back</label>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group call-back-section d-none">
      <label>Call Back Date</label>
      <input type="text" id="call-back-field" class="form-control" name="call-back-date" disabled>
    </div>
    <div class="form-group">
      <label for="no-answer-notes">No Answer Notes</label>
      <textarea id="no-answer-notes" name="no-answer-notes" cols="40" rows="5" class="form-control" disabled></textarea>
    </div>
  </div>

  <!-- interview script, hide if above is No -->
  <div class="interview-script">

    <div class="script-text-1">
      <p>Hi <span class="candidate-name-small">NAME</span>, this is <span class="interviewer-name"><?php TwilioCSV::user_name('first'); ?></span> from <span class="company-name">The Johnson Group</span>. I'm calling because you inquired about an opportunity with our company. Is this a good time to talk?</p>
    </div>
    <div class="form-group">
      <div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="can-talk-job-seeker-yn" id="can-talk-job-seeker-yn_0" type="radio" class="custom-control-input" value="Yes" checked>
            <label for="can-talk-job-seeker-yn_0" class="custom-control-label">Yes</label>
          </div>
        </div>
        <div class="custom-controls-stacked">
          <div class="custom-control custom-radio">
            <input name="can-talk-job-seeker-yn" id="can-talk-job-seeker-yn_1" type="radio" class="custom-control-input" value="No">
            <label for="can-talk-job-seeker-yn_1" class="custom-control-label">No</label>
          </div>
        </div>
      </div>
    </div>
    <div class="can-talk-script-section">

      <div class="script-text-2">
        <p>Great! Well <span class="candidate-name-small">NAME</span> we're currently looking for individuals that want to serve in a leadership capacity, and the position can be performed virtually at home.</p>
        <p>To be considered for the opportunity, I have a few questions for you:</p>
      </div>

      <!-- begin question group -->
      <div class="question-group">
        <div class="form-group">
          <label for="q1">Because you'll likely be working from home, on a scale from 1-10, 10 being the highest, how disciplined are you?</label>
          <textarea id="q1" name="q1" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q2">Do you have experience working from home?</label>
          <textarea id="q2" name="q2" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q3">What are you NOT looking for in your next career opportunity?</label>
          <textarea id="q3" name="q3" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q4">How do you feel about serving in a leadership capacity?</label>
          <textarea id="q4" name="q4" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q5">Our agency takes personal development very seriously, are you open to mentorship and career guidance?</label>
          <textarea id="q5" name="q5" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q6">Do you have high speed internet at home?</label>
          <textarea id="q6" name="q6" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="q7">Do you have a laptop or desktop computer?</label>
          <textarea id="q7" name="q7" cols="40" rows="5" class="form-control"></textarea>
        </div>
      </div>
      <!-- end question group -->

      <div class="script-text-4">
        <p><span class="candidate-name-small">NAME</span> based on your responses I would like to invite you to a company overview this <span class="selected-briefing-date">DATE</span>. Can you be available?</p>
      </div>
      <div class="form-group">
        <label for="select-briefing">Select Upcoming Briefing</label>
        <div>
          <select id="select-briefing" name="select-briefing" class="custom-select">
            <option value="">-- Select Briefing --</option>
            <?php
            $briefing_count = 0;
            foreach ($scheduled_briefings as $briefing) {
              if ($briefing_count == 0) {
                $selected = 'selected';
              } else {
                $selected = '';
              }
              $date_string = date('l, F jS, Y', strtotime($briefing->_scheduled));
              $time_string = date('g:i a', strtotime($briefing->_scheduled));
              echo '<option value="' . $briefing->id . '"' . $selected . '>' . $date_string . ' at ' . $time_string . '</option>';
              $briefing_count++;
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div>
          <div class="custom-controls-stacked">
            <div class="custom-control custom-radio">
              <input name="available-for-briefing-yn" id="available-for-briefing-yn_0" type="radio" class="custom-control-input" value="Yes" checked>
              <label for="available-for-briefing-yn_0" class="custom-control-label">Yes</label>
            </div>
          </div>
          <div class="custom-controls-stacked">
            <div class="custom-control custom-radio">
              <input name="available-for-briefing-yn" id="available-for-briefing-yn_1" type="radio" class="custom-control-input" value="No">
              <label for="available-for-briefing-yn_1" class="custom-control-label">No</label>
            </div>
          </div>
        </div>
      </div>
      <div class="script-text-cannot-zoom d-none">
        <p>Ok, I will reach out to my supervisor and see what other times may be available and I will be back in touch with you.</p>
      </div>
      <div class="script-text-5">
        <p>Awesome, let me confirm your email address. I have <strong><span class="candidate-email">EMAIL@EMAIL.COM</span></strong>. Is that correct?</p>
        <div class="form-group">
          <div>
            <div class="custom-controls-stacked">
              <div class="custom-control custom-radio">
                <input name="confirm-email-address-yn" id="confirm-email-address-yn_0" type="radio" class="custom-control-input" value="Yes" checked>
                <label for="confirm-email-address-yn_0" class="custom-control-label">Yes</label>
              </div>
            </div>
            <div class="custom-controls-stacked">
              <div class="custom-control custom-radio">
                <input name="confirm-email-address-yn" id="confirm-email-address-yn_1" type="radio" class="custom-control-input" value="No">
                <label for="confirm-email-address-yn_1" class="custom-control-label">No</label>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group correct-email-option d-none">
          <!-- add d-none after checking layout -->
          <label for="email-address">Email Address
            <span class="text-danger"><small><em>(required)</em></span>
          </label>
          <input id="contact-email-address" name="email-address" type="email" class="form-control" placeholder="Enter Correct Email Address">
        </div>
      </div>
    </div>

    <div class="script-text-dnc d-none">
      <p>Would you like to be removed from our database, or kept in for future opportunities?</p>
      <div class="form-groupn">
        <!-- add d-none after checking layout -->
        <div>
          <div class="custom-controls-stacked">
            <div class="custom-control custom-radio">
              <input name="remove-dnc" id="remove-dnc_0" type="radio" class="custom-control-input" value="Keep In Database" disabled>
              <label for="remove-dnc_0" class="custom-control-label">Keep In Database</label>
            </div>
          </div>
          <div class="custom-controls-stacked">
            <div class="custom-control custom-radio">
              <input name="remove-dnc" id="remove-dnc_1" type="radio" class="custom-control-input" value="Do Not Call" disabled>
              <label for="remove-dnc_1" class="custom-control-label">Remove - Do Not Call</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <button name="submit" type="submit" class="btn btn-primary interview-submit">Finish Call & Submit</button>
  </div>
</form>