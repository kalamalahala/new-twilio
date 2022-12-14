<?php

/** Form to update recruits */

?>

<form id="update-recruit-form">
  <div class="form-group">
    <label for="time-in-xcel">Time in XCEL</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-clock-o"></i>
        </div>
      </div> 
      <input id="time-in-xcel" name="time_in_xcel" placeholder="0hrs 00min" type="text" class="form-control" aria-describedby="time-in-xcelHelpBlock">
    </div> 
    <small><span id="time-in-xcelHelpBlock" class="form-text text-muted">Enter the amount of time <span class="recruit-first-name">NAME</span> has spent in the XCEL Solutions workbook.</span></small>
  </div>
  <div class="form-group">
    <label for="ple-percent">PLE %</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-percent"></i>
        </div>
      </div> 
      <input id="ple-percent" name="ple_percent" placeholder="0% - 100%" type="number" min="0" max="100" class="form-control" aria-describedby="ple-percentHelpBlock">
    </div> 
    <small><span id="ple-percentHelpBlock" class="form-text text-muted">What percent of the PLE course has <span class="recruit-first-name">NAME</span> completed?</span></small>
  </div> 
  <div class="form-group">
    <label for="prep-completion">Prep Completion %</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-percent"></i>
        </div>
      </div> 
      <input id="prep-completion" name="prep_percent" placeholder="0% - 100%" type="number" min="0" max="100" class="form-control" aria-describedby="prep-completionHelpBlock">
    </div> 
    <small><span id="prep-completionHelpBlock" class="form-text text-muted">What percent of the prep exam has <span class="recruit-first-name">NAME</span> completed?</span></small>
  </div> 
  <div class="form-group">
    <label for="sim-percent">Sim %</label> 
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fa fa-percent"></i>
        </div>
      </div> 
      <input id="sim-percent" name="sim_percent" placeholder="0% - 100%" type="number" min="0" max="100" class="form-control" aria-describedby="sim-percentHelpBlock">
    </div> 
    <small><span id="sim-percentHelpBlock" class="form-text text-muted">What percent of the simulation exam has <span class="recruit-first-name">NAME</span> completed?</span></small>
  </div> 
  <div class="form-group">
    <label for="prepared-to-pass">Prepared to Pass</label> 
    <div>
      <select id="prepared-to-pass" name="prepared_to_pass" class="custom-select" aria-describedby="prepared-to-passHelpBlock">
        <option value="Not Prepared">Not Prepared</option>
        <option value="Getting Closer">Getting Closer</option>
        <option value="Almost There">Almost There</option>
        <option value="Prepared to Pass">Prepared to Pass</option>
      </select> 
      <small><span id="prepared-to-passHelpBlock" class="form-text text-muted">Select <span class="recruit-first-name">NAME</span>'s preparedness level.</span></small>
    </div>
  </div> 
  <div class="form-group">
    <input type="hidden" id="recruit-id-field" name="id" value="" />
    <button name="submit" type="submit" class="btn btn-primary">Submit Update</button>
  </div>
</form>