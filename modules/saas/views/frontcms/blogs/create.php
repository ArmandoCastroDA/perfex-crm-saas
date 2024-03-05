<?php
if (!empty($blogs_info->heading_id)) {
    $id = $blogs_info->heading_id;
} else {
    $id = null;
}
echo form_open(base_url() . 'saas/frontcms/blogs/save_blogs_heading/' . $id, array('id' => 'blogs_heading_form', 'enctype' => 'multipart/form-data'))

?>

    <div class="panel panel-custom" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?= _l('create_feature') ?></div>
        </div>

        <div class="modal-body">
            <div class="form-group clearfix">
                <label for="" class="control-label"><?= _l('title'); ?> <span class="required">*</span></label>
                <input type="text" value="<?= !empty($blogs_info->title) ? $blogs_info->title : '' ?>" name="title"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('description'); ?></label>
                <textarea name="description" class="form-control"
                          rows="4"><?= !empty($blogs_info->description) ? $blogs_info->description : '' ?></textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
            <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
        </div>
    </div>
<?php echo form_close(); ?>