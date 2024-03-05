<div class="tw-mb-2 sm:tw-mb-4">
    <a href="<?php echo saas_url('super_admin/create'); ?>" class="btn btn-primary">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new_super_admin'); ?>
    </a>
</div>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('staff/delete', ['delete_staff_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, ['staffid', ['firstname', 'lastname']], 'staff_member', get_staff_user_id(), [], [], '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="panel_s ">
    <div class="panel-body">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= _l('staff_dt_name') ?></th>
                <th><?= _l('staff_dt_email') ?></th>
                <th><?= _l('staff_dt_last_Login') ?></th>
                <th><?= _l('staff_dt_active') ?></th>

            </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                'use strict'
                $(function () {
                    list = base_url + "saas/super_admin/userList";
                    initDataTable('#DataTables', list);

                });

                function delete_staff_member(id) {
                    $('#delete_staff').modal('show');
                    $('#transfer_data_to').find('option').prop('disabled', false);
                    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
                    $('#delete_staff .delete_id input').val(id);
                    $('#transfer_data_to').selectpicker('refresh');
                }
            </script>
            </tbody>
        </table>
    </div>
</div>
