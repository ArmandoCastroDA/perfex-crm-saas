<?php
if (!empty($footer_icons_info)) {
    $fid = $footer_icons_info->heading_id;
} else {
    $fid = '';
}
echo form_open(base_url('saas/frontcms/settings/save_footer_icons/' . $fid), array('id' => 'form'));
?>


    <div class="panel panel-custom" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?= _l('create_footer_icons') ?></div>
        </div>

        <div class="modal-body">
            <div class="form-group clearfix">
                <label for="" class="control-label"><?= _l('icons'); ?> <span class="required">*</span></label>
                <input type="text" value="<?= !empty($footer_icons_info->icons) ? $footer_icons_info->icons : '' ?>"
                       name="icons" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('links'); ?></label>
                <input name="links" class="form-control"
                       value="<?= !empty($footer_icons_info->links) ? $footer_icons_info->links : '' ?>"></input>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
            <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
        </div>
    </div>
<?php echo form_close(); ?>