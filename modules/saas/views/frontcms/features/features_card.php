<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>">
                    <a href="#slider" data-toggle="tab"><?= _l('all') ?> <?= _l('slider') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>">
                    <a href="#create" data-toggle="tab"><?= _l('new') ?> <?= _l('slider') ?></a>
                </li>
            </ul>
            <!--Tab content-->
            <div class="tab-content bg-white">
                <!--All Pages-->
                <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="slider">
                    <div class="table-responsive">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?= _l('title') ?></th>
                                <th><?= _l('slider') . ' ' . _l('image') ?></th>
                                <th><?= _l('description') ?></th>
                                <th><?= _l('status') ?></th>
                                <th class="col-options no-sort"><?= _l('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <script type="text/javascript">
                                'use strict';
                                list = base_url + "saas/frontcms/settings/slider_list";
                            </script>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!--add slider-->
                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                    <?php
                    if (!empty($slider_info)) {
                        $id = $slider_info->id;
                    }
                    echo form_open(base_url() . 'saas/frontcms/settings/save_slider/' . $id, array('id' => 'form', 'class' => 'form-horizontal ') . 'enctype="multipart/form-data"');
                    ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->title);
                            } ?>" name="title" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('subtitle') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->subtitle);
                            } ?>" name="subtitle" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
                        <div class="col-md-9">
                                <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($slider_info)) {
                                    echo($slider_info->description);
                                } ?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Slider Background</label>
                        <div class="col-lg-6">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail w-210">
                                    <?php
                                    if (!empty($slider_info)) {
                                        if ($slider_info->slider_bg != '') { ?>
                                            <img src="<?php echo base_url() . $slider_info->slider_bg; ?>">
                                        <?php }
                                    } else { ?>
                                        <span>select image</span>
                                    <?php } ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                                <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                                <input type="file" name="slider_bg" value="upload" class="form-controll"
                                                       data-buttonText="<?= _l('choose_file') ?>" id="myImg"/>
                                                <span class="fileinput-exists"><?= _l('change') ?></span>
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists"
                                               data-dismiss="fileinput"><?= _l('remove') ?></a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Slider Image</label>
                        <div class="col-lg-6">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail w-210">
                                    <?php
                                    if (!empty($slider_info)) {
                                        if ($slider_info->slider_img != '') { ?>
                                            <img src="<?php echo base_url() . $slider_info->slider_img; ?>">
                                        <?php }
                                    } else { ?>
                                        <span>select image</span>
                                    <?php } ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                                <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                                <input type="file" name="slider_img" value="upload"
                                                       class="form-controll" data-buttonText="<?= _l('choose_file') ?>"
                                                       id="myImg"/>
                                                <span class="fileinput-exists"><?= _l('change') ?></span>
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists"
                                               data-dismiss="fileinput"><?= _l('remove') ?></a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_text_1') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_text_1);
                            } ?>" name="button_text_1" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_icon_1') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_icon_1);
                            } ?>" name="button_iocn_1" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_link_1') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_link_1);
                            } ?>" name="button_link_1" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_text_2') ?> </label>
                        <div class="col-lg-9">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_text_2);
                            } ?>" name="button_text_2" class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_icon_2') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_icon_2);
                            } ?>" name="button_iocn_2" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_link_2') ?> </label>
                        <div class="col-lg-9">
                            <input type="text" value="<?php
                            if (!empty($slider_info)) {
                                echo html_escape($slider_info->button_link_2);
                            } ?>" name="button_link_2" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
                        <div class="col-lg-6">
                            <div class="material-switch tw-mt-2">
                                <input name="status" id="ext_url" type="checkbox" value="1" <?php
                                if (!empty($slider_info)) {
                                    if ($slider_info->status == 1) {
                                        echo 'checked';
                                    }
                                } ?> />
                                <label for="ext_url" class="label-success"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>

    </div>