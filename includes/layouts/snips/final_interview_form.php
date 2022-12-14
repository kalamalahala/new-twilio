<?php

/** Final Interview Form */

?>

<div class="final-wrap">
    <form action="" id="final-interview-form">
        <h2>Interview with <span class="final-interview-name-header">NAME</span></h2>
        <h3>Step 1</h3>
        <small class="text-muted"><em>Address the questionnaire. If the form is already completed, ask questions based on their responses. If the form is not already completed, ask the questions on the form, take notes, and converse accordingly based on the answers that are given.</em></small>
        <hr>
        <p class="font-weight-normal">Hi <span class="final-interview-name">NAME</span> this is <strong><?php echo TwilioCSV::user_name('first'); ?></strong> with <strong>The Johnson Group</strong>. I'm aware that you've completed the overview of what we do, how we do it, and how we're looking to expand. I'd like to ask you a few questions to determine if you're a good fit for our organization. Do you have time to talk?</p>

        <div class="form-group final-interview-question">
            <label for="q1">What aspects of the opportunity appeal to you the most?</label>
            <textarea class="form-control" name="q1" id="q1" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q2">What is your biggest concern about the opportunity?</label>
            <textarea class="form-control" name="q2" id="q2" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q3">Why should we make you a part of our team?</label>
            <textarea class="form-control" name="q3" id="q3" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q4">If selected, what are your career goals?</label>
            <textarea class="form-control" name="q4" id="q4" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q5">If I were to meet your former / current employer, how would they describe you?</label>
            <textarea class="form-control" name="q5" id="q5" rows="3"></textarea>
        </div>

        <h3>Step 2</h3>
        <small class="text-muted"><em>Present the vision and set the expectation.</em></small>
        <hr>
        <p class="font-weight-normal"><span class="final-interview-name">NAME</span> as you've heard in the overview our company is already a winning organization. Our purpose in expanding and meeting with candidates like yourself is to ensure that we continue that legacy. If we bring you aboard, our primary objective for your first 90 days is to get you prepared to join our leadership team.</p>

        <!-- q6 through q9 -->

        <div class="form-group final-interview-question">
            <label for="q6">How do you feel about serving in a leadership capacity?</label>
            <textarea class="form-control" name="q6" id="q6" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q7">How do you perform under pressure?</label>
            <textarea class="form-control" name="q7" id="q7" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q8">What sacrifices are you willing to make in order to earn six figures? (Morally, ethically, legally)</label>
            <textarea class="form-control" name="q8" id="q8" rows="3"></textarea>
        </div>
        <div class="form-group final-interview-question">
            <label for="q9">Are you competitive? If yes, give me an example of a time when you had to be competitive</label>
            <textarea class="form-control" name="q9" id="q9" rows="3"></textarea>
        </div>

        <p class="font-weight-normal">
            <span class="final-interview-name">NAME</span> you have an opportunity to become a leader with one of the top-ranked companies in the country. The financial, personal, and professional gains that you and your family could benefit from are tremendous and my assignment is to make sure that you're the right person for the job. Do you believe that you're the right person for the job? If selected, we will need 90 days of unadulterated commitment.
        </p>

        <p class="font-weight-normal">Do you have any commitments planned in the next 90 days that would interfere with your training?</p>
        <p class="font-weight-normal">Your training is not based on a 9-5 model. There are days that your schedule can begin earlier than 9 AM, and there are days that your schedule will end at 9PM, does this present a problem for you? (If so, Identify, Isolate, and Overcome)</p>
        <p class="font-weight-normal">Before you can begin training you must become licensed. This process should only take 7-10 days. Your inability to complete licensing in a timely manner could postpone or disqualify you from earning a leadership position with the company. <span class="final-interview-name">NAME</span> please understand that you are competing for a spot on the leadership team and everything you do as well as don't do, as it concerns this opportunity, is under review.</p>
        <p class="font-weight-normal">Do you have any commitments planned in the next 90 days that would interfere with your training?</p>

        <div class="form-group">
            <label class="font-weight-bold commitment">Commitments within 90 days?</label>
            <div>
                <div class="custom-controls-stacked">
                    <div class="custom-control custom-radio">
                        <input name="commitment-radio" id="commitment-radio_0" type="radio" class="custom-control-input" value="Yes">
                        <label for="commitment-radio_0" class="custom-control-label">Yes</label>
                    </div>
                </div>
                <div class="custom-controls-stacked">
                    <div class="custom-control custom-radio">
                        <input name="commitment-radio" id="commitment-radio_1" type="radio" class="custom-control-input" value="No" checked="checked">
                        <label for="commitment-radio_1" class="custom-control-label">No</label>
                    </div>
                </div>
            </div>
        </div>

        <p class="font-weight-normal">Your training is not based on a 9-5 model. There are days that your schedule can begin earlier than 9 AM, and there are days that your schedule will end at 9PM, does this present a problem for you? (If so, Identify, Isolate, and Overcome)</p>

        <div class="form-group">
            <label class="font-weight-bold hours-problem">Problem With Hours</label>
            <div>
                <div class="custom-controls-stacked">
                    <div class="custom-control custom-radio">
                        <input name="hours-radio" id="hours-radio_0" type="radio" class="custom-control-input" value="Yes">
                        <label for="hours-radio_0" class="custom-control-label">Yes</label>
                    </div>
                </div>
                <div class="custom-controls-stacked">
                    <div class="custom-control custom-radio">
                        <input name="hours-radio" id="hours-radio_1" type="radio" class="custom-control-input" value="No" checked="checked">
                        <label for="hours-radio_1" class="custom-control-label">No</label>
                    </div>
                </div>
            </div>
        </div>

        <p class="font-weight-normal">Before you can begin training you must become licensed. This process should only take 7-10 days. Your inability to complete licensing in a timely manner could postpone or disqualify you from earning a leadership position with the company. <span class="final-interview-name">NAME</span> please understand that you are competing for a spot on the leadership team and everything you do as well as don't do, as it concerns this opportunity, is under review.</p>

        <h3>Step 3</h3>
        <small class="text-muted"><em>Close the interview.</em></small>
        <hr>

        <p class="font-weight-normal">To begin the licensing process you will need $49. The agency is providing a scholarship that will cover the remaining balance. Do you have $49 to begin the course? If yes, I will forward you a message when we end the call that will walk you through the registration process step by step. Once you've completed registration, text or email me a screenshot of the completed registration page.</p>

        <h4>Step 4</h4>
        <small class="text-muted"><em>Collect candidate information for agreement email.</em></small>
        <hr>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label class="font-weight-bold" for="candidateFirstName">Candidate Name
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="candidateFirstName" id="candidateFirstName" placeholder="First Name">
            </div>
            <div class="form-group col-md-6">
                <label for="candidateLastName">&nbsp;</label>
                <input type="text" class="form-control" name="candidateLastName" id="candidateLastName" placeholder="Last Name">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label class="font-weight-bold" for="candidatePhone">Candidate Phone
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="candidatePhone" id="candidatePhone" placeholder="Phone">
            </div>
            <div class="form-group col-md-6">
                <label class="font-weight-bold" for="candidateEmail">Candidate Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control" name="candidateEmail" id="candidateEmail" placeholder="Email">
            </div>
        </div>

        <!-- Submit -->
        <div class="form-group">
            <input type="hidden" name="id" id="final-interview-contact-id" value="">
            <input type="hidden" name="full-name" id="final-interview-full-name" value="">
            <button type="submit" class="btn btn-primary">Send Agreement Email</button>
            <button type="reset" class="btn btn-secondary">Clear Answers</button>
        </div>

    </form>
</div>