<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new') . ' ' . _l('brand'); ?></h4>
    </div>
    <div class="panel-body">
        <?php
        if (!empty($brand)) {
            $pages_id = $brand->id;
        } else {
            $pages_id = null;
        }
        echo form_open_multipart('saas/frontcms/brands/save_brand/' . $pages_id, array('id' => 'brand_form', 'class' => 'form-horizontal'));
        ?>


        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($brand)) {
                    echo html_escape($brand->title);
                } ?>" name="title" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('image') ?></label>
            <div class="col-lg-6">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail w-210">
                        <?php
                        if (!empty($brand)) {
                            if ($brand->image != '') { ?>
                                <img src="<?php echo base_url() . $brand->image; ?>">
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
            <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
            <div class="col-lg-6">
                <div class="material-switch tw-mt-2">
                    <input name="status" id="ext_urle" type="checkbox" value="1" <?php
                    if (!empty($brand)) {
                        if ($brand->status == 1) {
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
