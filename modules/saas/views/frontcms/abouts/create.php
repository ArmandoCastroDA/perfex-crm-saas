<?php echo form_open(base_url() . 'saas/frontcms/abouts/save_abouts_heading/' . (!empty($abouts_info) ? $abouts_info->heading_id : ''), array('id' => 'form', 'class' => 'form-horizontal')) ?>

<div class="panel panel-custom" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title"><?= _l('create_about') ?></div>
    </div>

    <div class="modal-body">
        <div class="form-group clearfix">
            <label for="" class="control-label"><?= _l('title'); ?> <span class="required">*</span></label>
            <input type="text" value="<?= !empty($abouts_info->title) ? $abouts_info->title : '' ?>" name="title"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label for="" class="control-label"><?= _l('description'); ?></label>
            <textarea name="description" class="form-control"
                      rows="4"><?= !empty($abouts_info->description) ? $abouts_info->description : '' ?></textarea>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
        <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
    </div>
</div>
<?php echo form_close(); ?>
