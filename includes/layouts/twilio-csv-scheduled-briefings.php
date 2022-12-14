<div class="wrap">
    <?php TwilioCSV::navbar(); ?>
    <div class="content-wrap">

        <!-- tab list View Briefings, Edit, and Create New Briefing -->
        <ul class="nav nav-tabs" id="twilio-csv-scheduled-briefings-tablist" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="twilio-csv-scheduled-briefings-view-tab" data-bs-toggle="tab" href="#twilio-csv-scheduled-briefings-view-pane" role="tab" aria-controls="twilio-csv-scheduled-briefings-view-pane" aria-selected="true">View Briefings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="twilio-csv-scheduled-briefings-create-tab" data-bs-toggle="tab" href="#twilio-csv-scheduled-briefings-create-pane" role="tab" aria-controls="twilio-csv-scheduled-briefings-create-pane" aria-selected="false">Create / Edit Briefing</a>
            </li>
        </ul>

        <!-- tab content -->
        <div class="tab-content">
            <!-- View Briefings -->
            <div class="tab-pane fade show active" id="twilio-csv-scheduled-briefings-view-pane" role="tabpanel" aria-labelledby="twilio-csv-scheduled-briefings-view-tab">
                <div class="row">
                    <div class="col-12">
                        <!-- dataTable -->
                        <table id="scheduled-briefings-view-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date Created</th>
                                    <th>Date Updated</th>
                                    <th>Date Scheduled</th>
                                    <th>Briefing Title</th>
                                    <th>Weblink</th>
                                    <th>Body</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" role="tabpanel" aria-labelledby="twilio-csv-scheduled-briefings-create-tab" id="twilio-csv-scheduled-briefings-create-pane">

                <form action="" id="scheduled-briefings-form" class="w-75">
                    <input type="hidden" name="_id" id="briefing_id">
                    <input type="hidden" name="_method" id="_method" value="schedule_briefing">
                    <div class="form-group w-50">
                        <label for="_title">Briefing Title <span class="text-danger"><small><em>(required)</em></small></span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-window-maximize"></i>
                                </div>
                            </div>
                            <input id="_title" name="_title" placeholder="Enter a title for your briefing here." type="text" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group w-50 row">
                        <div class="col-md-6">

                            <label for="_weblink">Webinar / Briefing Link <span class="text-danger"><small><em>(required)</em></small></span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-external-link"></i>
                                    </div>
                                </div>
                                <input id="_weblink" name="_weblink" placeholder="https://your.link/here" type="text" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="_date">Date <span class="text-danger"><small><em>(required)</em></small></span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <input id="_scheduled" name="_scheduled" placeholder="YYYY-MM-DD" type="text" class="form-control" required="required">
                            </div>
                        </div>
                    </div>

                    <!-- add WYSIWYG editor -->
                    <div class="form-group w-50">
                        <label for="_body">Briefing Body</label>
                        <?php
                        wp_editor(
                            $content = '',
                            $editor_id = 'scheduled-briefings-body',
                            $settings = array(
                                'textarea_name' => '_body',
                                'textarea_rows' => 10,
                                'media_buttons' => true,
                                'teeny' => true,
                                'quicktags' => true,
                                'tinymce' => array(
                                    'toolbar1' => 'bold,italic,underline,|,bullist,numlist,|,link,unlink,|,undo,redo',
                                    'toolbar2' => '',
                                    'toolbar3' => '',
                                    'toolbar4' => '',
                                ),
                            )
                        );
                        ?>
                    </div>
                    <div class="alert-container">

                    </div>
                    <div class="form-group">
                        <button name="submit" type="submit" class="btn btn-primary">Create Briefing</button>
                        <button type="reset" name="reset" class="btn btn-secondary">Clear Fields</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>