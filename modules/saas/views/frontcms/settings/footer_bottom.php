<div class="row">
    <div class="col-lg-12">
        <?php
        if (!empty($footer_bottom_info)) {
            $id = $footer_bottom_info->heading_id;
        } else {
            $id = '';
        }
        echo form_open_multipart(base_url() . 'saas/frontcms/settings/save_footer_bottom/' . $id, array('enctype' => 'multipart/form-data'));
        ?>


        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title"><?= _l('footer_bottom') ?></div>
            </div>

            <div class="modal-body">

                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('description'); ?> <span
                                class="required">*</span></label>
                    <input type="text" value="<?= !empty($footer_bottom_info->name) ? $footer_bottom_info->name : '' ?>"
                           name="name" class="form-control">
                </div>
                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('by'); ?> <span class="required">*</span></label>
                    <input type="text"
                           value="<?= !empty($footer_bottom_info->title) ? $footer_bottom_info->title : '' ?>"
                           name="title" class="form-control">
                </div>
                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('icons'); ?> <span class="required">*</span></label>
                    <input type="text"
                           value="<?= !empty($footer_bottom_info->icons) ? $footer_bottom_info->icons : '' ?>"
                           name="icons" class="form-control">
                </div>
                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('link_name'); ?> <span
                                class="required">*</span></label>
                    <input type="text"
                           value="<?= !empty($footer_bottom_info->description) ? $footer_bottom_info->description : '' ?>"
                           name="description" class="form-control">
                </div>
                <div class="form-group clearfix">
                    <label for="" class="control-label"><?= _l('button_link'); ?> <span
                                class="required">*</span></label>
                    <input type="text"
                           value="<?= !empty($footer_bottom_info->links) ? $footer_bottom_info->links : '' ?>"
                           name="links" class="form-control">
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