<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= _l('reset_password') ?></h4>
    </div>
    <?php
    echo form_open('saas/companies/reset_password/' . $company_info->id, array('id' => 'reset_password'));
    ?>
    <div class="modal-body form-horizontal">
        <div class="form-group">
            <div class="col-lg-12">
                <input type="password" class="form-control" id="new_password" required
                       placeholder="<?= _l('enter') . ' ' . _l('new') . ' ' . _l('password') . ' ' . _l('for') . ' ' . $company_info->name ?>"
                       name="password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <input type="password" class="form-control" id="newpasswordr" required
                       placeholder="<?= _l('enter') . ' ' . _l('confirm_password') . ' ' . _l('for') . ' ' . $company_info->name ?>"
                       name="confirm_password">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
            <button type="submit" id="new_uses_btn" class="btn btn-primary"><?= _l('update') ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script>

    appValidateForm($('#reset_password'), {
        new_password: 'required',
        newpasswordr: 'required',
        newpasswordr: {
            equalTo: "#new_password"
        }
    });

</script>

