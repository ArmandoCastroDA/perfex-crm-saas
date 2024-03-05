<?php
echo form_open_multipart('saas/frontcms/settings/save_contact/' . (!empty($contact_info->id) ? $contact_info->id : ''), array('id' => 'contact_info'));
?>
    <div class="panel panel-custom" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?= _l('create_contact') ?></div>
        </div>

        <div class="modal-body">
            <div class="form-group clearfix">
                <label for="" class="control-label"><?= _l('title'); ?> <span class="required">*</span></label>
                <input type="text" value="<?= !empty($contact_info->title) ? $contact_info->title : '' ?>" name="title"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('icons'); ?></label>
                <input name="icons" class="form-control"
                       value="<?= !empty($contact_info->icons) ? $contact_info->icons : '' ?>"></input>
            </div>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('name'); ?></label>
                <input name="name" class="form-control"
                       value="<?= !empty($contact_info->name) ? $contact_info->name : '' ?>"></input>
            </div>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('links'); ?></label>
                <input name="link" class="form-control"
                       value="<?= !empty($contact_info->link) ? $contact_info->link : '' ?>"></input>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
            <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
        </div>
    </div>
<?php echo form_close(); ?>