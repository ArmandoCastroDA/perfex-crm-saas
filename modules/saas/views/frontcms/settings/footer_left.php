<div class="row">
    <div class="col-lg-12">
        <?php
        if (!empty($footer_left_info)) {
            $id = $footer_left_info->heading_id;
        } else {
            $id = '';
        }
        echo form_open(base_url() . 'saas/frontcms/settings/save_footer_left/' . $id, array('enctype' => 'multipart/form-data'));
        ?>


        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title"><?= _l('create_footer_left') ?></div>
            </div>

            <div class="modal-body">

                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('footer_link'); ?> <span
                                class="required">*</span></label>
                    <input type="text"
                           value="<?= !empty($footer_left_info->links) ? $footer_left_info->links : '' ?>"
                           name="links" class="form-control">
                </div>

                <div class="form-group">
                    <label for="" class="control-label"><?= _l('description'); ?></label>
                    <textarea name="description" class="form-control"
                              rows="4"><?= !empty($footer_left_info->description) ? $footer_left_info->description : '' ?></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
                <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>
</div>