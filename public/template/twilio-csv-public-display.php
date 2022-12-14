<!DOCTYPE html>
<html lang="en">

<head>
    <?php

    /**
     * Provide a public-facing view for the plugin
     *
     * This file is used to markup the public-facing aspects of the plugin.
     *
     * @link       https://thejohnson.group/
     * @since      1.0.0
     *
     * @package    Twilio_Csv
     * @subpackage Twilio_Csv/public/partials
     */

    wp_head();

    $user = wp_get_current_user();
    $default_pic_url = plugins_url('twilio-csv/public/img/default-user.png');
    $has_profile_pic = TwilioCSV::has_profile_picture($user->ID);
    $profile_pic_url = $has_profile_pic ? TwilioCSV::get_profile_picture($user->ID) : $default_pic_url;
    $user_name = [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'display_name' => $user->display_name,
        'full_name' => $user->first_name . ' ' . $user->last_name,
    ];

    $roles_pretty = [
        'administrator' => 'Administrator',
        'twilio_csv_admin' => 'Twilio CSV Admin',
        'twilio_csv_user' => 'Twilio CSV User',
        'twiiio_csv_manager' => 'Twilio CSV Manager'
    ];

    $user_role = $user->roles[0];
    $user_role_pretty = $roles_pretty[$user_role];

    $twilio_user = new TwilioCSVUser($user->ID);
    $sending_number = $twilio_user->get_sending_number();
    if (!$sending_number) {
        $fancy_number = 'No sending number set';
    } else {
        $fancy_number = ltrim($sending_number, '+1');
        $fancy_number = '(' . substr($fancy_number, 0, 3) . ') ' . substr($fancy_number, 3, 3) . '-' . substr($fancy_number, 6);
    }

    $is_admin = TwilioCSV::is_admin();
    $disabled_tab = $is_admin ? '' : 'disabled';

    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twilio CSV</title>
</head>

<body>
    <!-- included code -->
    <?php TwilioCSV::modals(); ?>
    <div class="d-none">
        <!-- hidden -->
        <?php TwilioCSV::navbar_public(); ?>
    </div>
    <!-- end included code -->
    <div class="full-overlay-loading" id="loading-overlay">
        <div class="loading-container">
            <div class="loading-header">
                <h4 id="loading-header">Loading ...</h4>
            </div>
            <div class="loading-body">
                <div class="loading-body-content">
                    <div class="loading-body-content-text">
                        <p id="loading-text">Text</p>
                    </div>
                    <div class="loading-body-content-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <!-- <span class="visually-hidden">Loading...</span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- This file should primarily consist of HTML with a little bit of PHP. -->
    <div class="twilio-wrap w-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 header-box d-flex">
                    <div class="d-inline-flex align-items-end">
                        <!-- Default User Pic -->
                        <img src="<?php echo $profile_pic_url; ?>" title="<? echo $user_name['full_name']; ?>" alt="User Avatar" class="user-pic mx-2 my-3" style="border: 3px solid white;">
                        <div class="d-block mb-3">
                            <p class="mb-0 ml-3 welcome-name"><strong>Welcome</strong>,
                                <?php echo $user->first_name; ?>!</p>
                            <p class="mb-0 ml-3 user-role"><strong>Role</strong>:
                                <?php echo $user_role_pretty; ?></p>
                            <p class="mb-0 ml-3 user-sending-number"><strong>Sending Number</strong>:&nbsp;
                                <?php echo $fancy_number; ?></p>
                        </div>
                        <div class="d-block mx-3">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="f1-tab" data-bs-toggle="tab" data-bs-target="#f1" type="button" role="tab" aria-controls="f1" aria-selected="false">Funnel
                                        One</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="f2-tab" data-bs-toggle="tab" data-bs-target="#f2" type="button" role="tab" aria-controls="f2" aria-selected="false" disabled>Funnel Two</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="f3-tab" data-bs-toggle="tab" data-bs-target="#f3" type="button" role="tab" aria-controls="f3" aria-selected="false" disabled>Funnel Three</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="f4-tab" data-bs-toggle="tab" data-bs-target="#f4" type="button" role="tab" aria-controls="f4" aria-selected="false" disabled>Funnel Four</button>
                                </li>
                                <!-- debug -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="debug-tab" data-bs-toggle="tab" data-bs-target="#debug" type="button" role="tab" aria-controls="debug" aria-selected="false" <?php echo $disabled_tab; ?>>Debug</button>
                                </li>
                                <!-- settings -->
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" type="button" role="tab" aria-controls="settings" aria-selected="false" <?php echo $disabled_tab; ?>>Settings</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container-fluid">
                        <div class="row my-5">
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h4>Step One</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="h6">Upload Your Contacts</p>
                                        <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Animi saepe, quidem laudantium asperiores porro fugit maiores amet
                                            voluptatum vel iusto natus non sit culpa possimus! Pariatur beatae facilis
                                            iusto quam!
                                            Animi possimus ratione doloremque optio facere pariatur velit, at hic
                                            commodi assumenda aut culpa odio totam ex, cum libero explicabo repellendus
                                            magni qui accusamus sapiente harum sunt dolorem. Exercitationem, placeat?
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-primary" id="home-go-to-f1" data-bs-toggle="tab" data-bs-target="#f1" type="button" role="tab" aria-controls="f1" aria-selected="false"><i class="fa-solid fa-upload"></i>&nbsp;<?php echo __('Go to Phase One'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h4>Step Two</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="h6">Reach Out and Schedule Interviews</p>
                                        <p class="card-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                                            Rem enim modi vitae sit blanditiis ipsum ad aperiam consequatur, harum non
                                            magni excepturi itaque quo nostrum inventore ducimus iusto molestiae quos.
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-primary" id="home-go-to-f2" data-bs-toggle="tab" data-bs-target="#f2" type="button" role="tab" aria-controls="f2" aria-selected="false" disabled><i class="fa-regular fa-id-card"></i>&nbsp;<?php echo __('Go to Phase Two'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h4>Step Three</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="h6">Conduct Final Interviews with Interested Candidates</p>
                                        <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                            Vero incidunt nihil tempora est explicabo repudiandae ipsum odio enim alias
                                            fuga, reprehenderit ut cum at quod earum maxime exercitationem deserunt
                                            facere.</p>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-primary" id="home-go-to-f3" data-bs-toggle="tab" data-bs-target="#f3" type="button" role="tab" aria-controls="f3" aria-selected="false" disabled><i class="fa-solid fa-file-signature"></i>&nbsp;<?php echo __('Go to Phase Three'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h4>Step Four</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="h6">Track Agent Progress through Pre-Licensing</p>
                                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                            Ipsum magni atque aliquid possimus veniam saepe aliquam voluptate! Modi aut
                                            nemo reprehenderit numquam eaque ipsam, fugit delectus facilis tenetur
                                            quidem amet.
                                            Asperiores nobis, culpa aspernatur eius, et amet possimus, sint magnam quae
                                            cupiditate minima nemo quidem laboriosam quas non ad. Dolor ducimus
                                            temporibus, reiciendis veritatis obcaecati architecto velit neque hic id?
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-primary" id="home-go-to-f4" data-bs-toggle="tab" data-bs-target="#f4" type="button" role="tab" aria-controls="f4" aria-selected="false" disabled><i class="fa-solid fa-list-check"></i>&nbsp;<?php echo __('Go to Phase Four'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="f1" role="tabpanel" aria-labelledby="f1-tab">
                    <div class="row">

                        <div class="col-sm-4 left-box nav-secondary">
                            <div class="nav flex-column nav-pills mx-auto align-items-stretch mt-3 mb-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <p class="h3 mb-3 mt-0">Menu</p>
                                <!-- Upload Contacts -->
                                <a class="nav-link" id="v-pills-upload-tab" data-bs-toggle="pill" href="#v-pills-upload" role="tab" aria-controls="v-pills-upload" aria-selected="false">Upload Contacts</a>
                                <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Conversations</a>
                                <a class="nav-link" id="v-pills-contact-tab" data-bs-toggle="pill" href="#v-pills-contact" role="tab" aria-controls="v-pills-contact" aria-selected="false" id="chat-pill">Chat: <span class="chat-pill-name"></span></a>
                                <!-- Scheduled Callbacks -->
                                <a class="nav-link" id="v-pills-scheduled-callbacks-tab" data-bs-toggle="pill" href="#v-pills-scheduled-callbacks" role="tab" aria-controls="v-pills-scheduled-callbacks" aria-selected="false">Scheduled
                                    Callbacks</a>
                                <!-- Pending Hires -->

                                <!-- <a class="nav-link" id="v-pills-pending-hires-tab" data-bs-toggle="pill" href="#v-pills-pending-hires" role="tab" aria-controls="v-pills-pending-hires" aria-selected="false">Pending Hires</a> -->

                                <!-- Recruits  -->

                                <!-- <a class="nav-link" id="v-pills-recruits-tab" data-bs-toggle="pill" href="#v-pills-recruits" role="tab" aria-controls="v-pills-recruits" aria-selected="false">Recruits</a> -->

                                <!-- Programmable Messages -->
                                <?php if (TwilioCSV::is_admin()) : ?>
                                    <a class="nav-link" id="v-pills-programmable-messages-tab" data-bs-toggle="pill" href="#v-pills-programmable-messages" role="tab" aria-controls="v-pills-programmable-messages" aria-selected="false">Programmable
                                        Messages</a>

                                    <!-- Scheduled Briefings -->

                                    <a class="nav-link" id="v-pills-scheduled-briefings-tab" data-bs-toggle="pill" href="#v-pills-scheduled-briefings" role="tab" aria-controls="v-pills-scheduled-briefings" aria-selected="false">Schedule
                                        Briefing</a>
                                <?php endif; ?>
                                <!-- <a class="nav-link" id="v-pills-activity-tab" data-bs-toggle="pill" href="#v-pills-activity" role="tab" aria-controls="v-pills-activity" aria-selected="false">Activity</a> -->
                                <!-- <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> -->
                            </div>
                        </div>

                        <div class="col-sm-8 right-box">
                            <!-- Navigation Tab panes contained in right-box -->
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active px-2 mx-2" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    <?php echo TwilioCSV_Public::datatables_pane(); ?>
                                </div>

                                <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab">
                                    <?php echo TwilioCSV_Public::conversation_pane(); ?>
                                </div>
                                <!-- Upload Contacts -->
                                <div class="tab-pane fade" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab">
                                    <?php echo TwilioCSV_Public::upload_contacts_pane(); ?>
                                </div>
                                <!-- Programmable Messages -->
                                <?php if (TwilioCSV::is_admin()) : ?>
                                    <div class="tab-pane fade" id="v-pills-programmable-messages" role="tabpanel" aria-labelledby="v-pills-programmable-messages-tab">
                                        <?php echo TwilioCSV_Public::programmable_messages_pane(); ?>
                                    </div>
                                    <!-- Scheduled Briefings -->
                                    <div class="tab-pane fade" id="v-pills-scheduled-briefings" role="tabpanel" aria-labelledby="v-pills-scheduled-briefings-tab">
                                        <?php echo TwilioCSV_Public::scheduled_briefings_pane(); ?>
                                    </div>
                                <?php endif; ?>
                                <!-- Scheduled Callbacks -->
                                <div class="tab-pane fade" id="v-pills-scheduled-callbacks" role="tabpanel" aria-labelledby="v-pills-scheduled-callbacks-tab">
                                    <?php echo TwilioCSV_Public::scheduled_callbacks_pane(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="f2" role="tabpanel" aria-labelledby="f2-tab"> Funnel Two Contents </div>
                <div class="tab-pane" id="f3" role="tabpanel" aria-labelledby="f3-tab"> Funnel Three Contents </div>
                <div class="tab-pane" id="f4" role="tabpanel" aria-labelledby="f4-tab"> Funnel Four Contents </div>
                <div class="tab-pane" id="debug" role="tabpanel" aria-labelledby="debug-tab">
                    <?php echo TwilioCSV_Public::debug_pane(); ?>
                </div>
                <div class="tab-pane" id="settings-pane" role="tabpanel" aria-labelledby="settings-tab">
                    <?php echo TwilioCSV_Public::settings_pane(); ?>
                </div>
            </div>
        </div>

        <?php echo TwilioCSV_Public::get_footer(); ?>

    </div>
</body>

<?php wp_footer(); ?>

</html>