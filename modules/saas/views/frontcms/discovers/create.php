<?php
echo form_open(base_url() . 'saas/frontcms/discovers/save_discovers_heading/' . (!empty($discovers_info->heading_id) ? $discovers_info->heading_id : ''), array('id' => 'discovers_heading_form'));
?>

<div class="panel panel-custom" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title"><?= _l('create_feature') ?></div>
    </div>

    <div class="modal-body">
        <div class="form-group clearfix">
            <label for="" class="control-label"><?= _l('title'); ?> <span class="required">*</span></label>
            <input type="text" value="<?= !empty($discovers_info->title) ? $discovers_info->title : '' ?>" name="title"
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label for="" class="control-label"><?= _l('description'); ?></label>
            <textarea name="description" class="form-control"
                      rows="4"><?= !empty($discovers_info->description) ? $discovers_info->description : '' ?></textarea>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
        <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
    </div>
</div>
<?php echo form_close(); ?>
