<?php
if (!empty($features_collaborates)) {
    $id = $features_collaborates->id;
} else {
    $id = '';
}
?>
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new') . ' ' . _l('features_collaborate'); ?></h4>
    </div>
    <div class="panel-body">
        <?php
        echo form_open_multipart('saas/frontcms/features/save_features_collaborate/' . $id, array('id' => 'features_collaborate_form', 'class' => 'form-horizontal'));
        ?>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->title);
                } ?>" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('image') ?></label>
            <div class="col-lg-6">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail w-210">
                        <?php
                        if (!empty($features_collaborates)) {
                            if ($features_collaborates->image != '') { ?>
                                <img src="<?php echo base_url() . $features_collaborates->image; ?>">
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
            <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
            <div class="col-md-9">
                        <textarea name="description" class="form-control tinymce"
                                  rows="3"><?php if (!empty($features_collaborates)) {
                                echo($features_collaborates->description);
                            } ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_name_1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->name);
                } ?>" name="name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_link_1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->link);
                } ?>" name="link" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('icons_1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->icons);
                } ?>" name="icons" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_name_2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->button_name_2);
                } ?>" name="button_name_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_link_2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->button_link_2);
                } ?>" name="button_link_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('icons_2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->icons_2);
                } ?>" name="icons_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_name_3') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->button_name_3);
                } ?>" name="button_name_3" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('button_link_3') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->button_link_3);
                } ?>" name="button_link_3" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('icons_3') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($features_collaborates)) {
                    echo html_escape($features_collaborates->icons_3);
                } ?>" name="icons_3" class="form-control">
            </div>
        </div>


        <div class="form-group">
            <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
            <div class="col-lg-6">
                <div class="material-switch tw-mt-2">
                    <input name="status" id="ext_urls" type="checkbox" value="1" <?php
                    if (!empty($features_collaborates)) {
                        if ($features_collaborates->status == 1) {
                            echo 'checked';
                        }
                    } ?> />
                    <label for="ext_urls" class="label-success"></label>
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