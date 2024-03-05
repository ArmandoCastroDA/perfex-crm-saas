<?php
echo form_open_multipart('saas/frontcms/menus/save_menu', array('id' => 'menu-form'));
?>
<div class="panel panel-custom" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title"><?= _l('add_menu') ?></div>
    </div>

    <div class="modal-body">
        <div class="form-group clearfix">
            <label for="" class="control-label"><?= _l('menu'); ?> <span class="required">*</span></label>
            <input type="text" name="menu" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="" class="control-label"><?= _l('description'); ?></label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
        <button type="submit" class="btn btn-primary"><?= _l('save') ?></button>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    // check package_id is empty or not by name
    $(document).ready(function () {
        $("#menu-form").appFormValidator();
    });
</script>
