<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        echo form_open_multipart(base_url('saas/frontcms/settings/updateOption'), array('role' => 'form', 'data-parsley-validate' => '', 'novalidate' => '', 'class' => 'form-horizontal'));
        ?>
        <section class="panel panel-custom">
            <header class="panel-heading"><?= _l('general_settings') ?>
            </header>
            <div class="panel-body pb-sm">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('google_analytics_tracking_id') ?></label>
                    <div class="col-lg-6">

                        <input name="google_analytics_tracking_id" type="text" value="<?php
                        if (get_option('google_analytics_tracking_id') != '') {
                            echo html_escape(get_option('google_analytics_tracking_id'));
                        } ?>"
                               class="form-control" placeholder="<?= _l('google_analytics_tracking_id_placeholder') ?>"
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_meta_title') ?></label>
                    <div class="col-lg-6">

                        <input name="home_meta_title" type="text" value="<?php
                        if (get_option('home_meta_title') != '') {
                            echo html_escape(get_option('home_meta_title'));
                        } ?>"
                               class="form-control" placeholder="<?= _l('home_meta_title') ?>"
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_meta_author') ?></label>
                    <div class="col-lg-6">

                        <input name="home_meta_author" type="text" value="<?php
                        if (get_option('home_meta_author') != '') {
                            echo html_escape(get_option('home_meta_author'));
                        } ?>"
                               class="form-control" placeholder="<?= _l('home_meta_author') ?>"
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_meta_description') ?></label>
                    <div class="col-lg-6">

                        <textarea name="home_meta_description" type="text" class="form-control"
                                  placeholder="<?= _l('home_meta_description') ?>"><?php if (get_option('home_meta_description') != '') {
                                echo html_escape(get_option('home_meta_description'));
                            } ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_meta_keywords') ?></label>
                    <div class="col-lg-6">

                        <textarea name="home_meta_keywords" type="text" class="form-control"
                                  placeholder="<?= _l('home_meta_keywords_placeholder') ?>"><?php if (get_option('home_meta_keywords') != '') {
                                echo html_escape(get_option('home_meta_description'));
                            } ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= html_escape(_l('header_custom_css')) ?>
                        <i class="fa fa-question-circle" data-toggle="tooltip"
                           title="<?= _l('header_custom_css_help') ?>"></i>
                    </label>
                    <div class="col-lg-6">
                        <textarea name="header_custom_css" type="text" class="form-control" rows="6"
                                  placeholder="<?= _l('header_custom_css') ?>"><?php if (get_option('header_custom_css') != '') {
                                echo html_escape(get_option('header_custom_css'));
                            } ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= html_escape(_l('header_custom_script')) ?>
                        <i class="fa fa-question-circle" data-toggle="tooltip"
                           title="<?= _l('header_custom_script_help') ?>"></i>
                    </label>
                    <div class="col-lg-6">
                        <textarea name="header_custom_script" type="text" class="form-control" rows="6"
                                  placeholder="<?= _l('header_custom_script') ?>"><?php if (get_option('header_custom_script') != '') {
                                echo html_escape(get_option('header_custom_script'));
                            } ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= html_escape(_l('footer_custom_script')) ?>
                        <i class="fa fa-question-circle" data-toggle="tooltip"
                           title="<?= _l('header_custom_script_help') ?>"></i>
                    </label>
                    <div class="col-lg-6">
                        <textarea name="footer_custom_script" type="text" class="form-control" rows="6"
                                  placeholder="<?= _l('footer_custom_script') ?>"><?php if (get_option('footer_custom_script') != '') {
                                echo html_escape(get_option('footer_custom_script'));
                            } ?></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_page_slider') ?></label>
                    <div class="col-lg-6">
                        <div class="material-switch tw-mt-2">
                            <input name="saas_front_slider" id="ext_url" type="checkbox" value="1" <?php
                            if (get_option('saas_front_slider') != '') {
                                echo "checked";
                            } ?> />
                            <label for="ext_url" class="label-success"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('home_slider_speed') ?></label>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" data-parsley-type="number"
                                   value="<?php if (get_option('home_slider_speed') != '') {
                                       echo html_escape(get_option('home_slider_speed'));
                                   } ?>" name="home_slider_speed" class="form-control">
                            <div class="input-group-addon"><?= _l('second') ?></div>
                        </div>
                    </div>
                </div>
                <div class="btn-bottom-toolbar text-right">
                    <button type="submit" class="btn btn-sm btn-primary"><?= _l('save_changes') ?></button>
                </div>
            </div>
        </section>
        <?php echo form_close(); ?>
        <!-- End Form -->
    </div>
</div>