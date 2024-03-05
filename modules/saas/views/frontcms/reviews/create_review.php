<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new') . ' ' . _l('review'); ?></h4>
    </div>
    <div class="panel-body">
        <?php
        if (!empty($review)) {
            $pages_id = $review->id;
        } else {
            $pages_id = null;
        }
        echo form_open_multipart('saas/frontcms/reviews/save_review/' . $pages_id, array('id' => 'review_form', 'class' => 'form-horizontal'));
        ?>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('author_name') ?> </label>
            <div class="col-lg-6">
                <input type="text"
                       required
                       value="<?php
                       if (!empty($review)) {
                           echo html_escape($review->title);
                       } ?>" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('designation') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($review)) {
                    echo html_escape($review->designation);
                } ?>" name="designation" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('image') ?></label>
            <div class="col-lg-6">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail w-210">
                        <?php
                        if (!empty($review)) {
                            if ($review->image != '') { ?>
                                <img src="<?php echo base_url() . $review->image; ?>">
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
            <label class="col-lg-3 control-label"><?= _l('rating') ?> </label>
            <div class="col-lg-6">
                <input type="number"
                       max="5"
                       inputmode="decimal"
                       step="0.5"
                       pattern="^\d+(?:\.\d{1,2})?$"
                       value="<?php
                       if (!empty($review)) {
                           echo html_escape($review->title_2);
                       } ?>" name="title_2" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="" class="control-label col-lg-3"><?= _l('review') ?></label>
            <div class="col-md-9">
                            <textarea name="description" class="form-control tinymce" rows="3"><?php
                                if (!empty($review)) {
                                    echo($review->description);
                                } ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
            <div class="col-lg-6">
                <div class="material-switch tw-mt-2">
                    <input name="status" id="ext_urle" type="checkbox" value="1" <?php
                    if (!empty($review)) {
                        if ($review->status == 1) {
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
