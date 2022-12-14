<?php

// Add one contact to the contact database

?>


<!-- New Contact Form -->
<div class="container-fluid">
    <div class="row g-3">
        <div class="col-md-12">
            <form id="add-contact-form">
                <div class="form-group row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="first_name" name="_first_name" placeholder="<?php echo __('First Name', 'text-domain'); ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="last_name" name="_last_name" placeholder="<?php echo __('Last Name', 'text-domain'); ?>">
                    </div>
                </div>
                <div class="form-group row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="tel" class="form-control" id="phone" name="_phone" placeholder="<?php echo __('Phone Number', 'text-domain'); ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="email" class="form-control" id="email" name="_email" placeholder="<?php echo __('Email', 'text-domain'); ?>">
                    </div>
                </div>
                <div class="form-group row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="city" name="_city" placeholder="<?php echo __('City', 'text-domain'); ?>">
                    </div>
                    <div class="col-md-6">
                        <select name="_state" id="state" class="form-select">
                            <option value="">Select State</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row g-3 mb-3">
                    <div class="col-md-12">
                        <select name="_source" id="source" class="form-select">
                            <option value="">Select Lead Source</option>
                            <option value="CareerBuilder">CareerBuilder</option>
                            <option value="Indeed">Indeed</option>
                            <option value="Monster">Monster</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="Referral">Referral</option>
                            <option value="Personal Recruit">Personal Recruit</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary mb-2"><i class="fa-solid fa-check"></i> <?php echo __('Create New Contact', 'text-domain'); ?></button>
                        <button type="reset" class="btn btn-secondary mb-2"><i class="fa-solid fa-trash"></i> <?php echo __('Clear Form', 'text-domain'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>