<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new') . ' ' . _l('blogs_card'); ?></h4>
    </div>
    <div class="panel-body">
        <?php
        if (!empty($blogs_card)) {
            $pages_id = $blogs_card->id;
        } else {
            $pages_id = null;
        }
        echo form_open_multipart('saas/frontcms/blogs/save_blogs_card/' . $pages_id, array('id' => 'blogs_card_form', 'class' => 'form-horizontal'));
        ?>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('author_name') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->name);
                } ?>" name="name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('date') ?></label>
            <div class="col-lg-6">

                <div class="input-group">
                    <input class="form-control datepicker" type="text" value="" name="date"
                           data-date-format="<?= config_item('date_picker_format'); ?>">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('image') ?></label>
            <div class="col-lg-6">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail w-210">
                        <?php
                        if (!empty($blogs_card)) {
                            if ($blogs_card->image != '') { ?>
                                <img src="<?php echo base_url() . $blogs_card->image; ?>">
                            <?php }
                        } else { ?>
                            <span>select image</span>
                        <?php } ?>
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                    <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="image" value="upload" class="form-controll"
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
            <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->title);
                } ?>" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('link') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->link);
                } ?>" name="link" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('number_1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->color);
                } ?>" name="color" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('number_2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->color_2);
                } ?>" name="color_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_name') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->button_name_2);
                } ?>" name="button_name_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_link') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($blogs_card)) {
                    echo html_escape($blogs_card->button_link_2);
                } ?>" name="button_link_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
            <div class="col-lg-6">
                <div class="material-switch tw-mt-2">
                    <input name="status" id="ext_urle" type="checkbox" value="1" <?php
                    if (!empty($blogs_card)) {
                        if ($blogs_card->status == 1) {
                            echo 'checked';
                        }
                    } ?> />
                    <label for="ext_urle" class="label-success"></label>
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
