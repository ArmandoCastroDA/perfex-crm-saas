<?php
$uri = $this->uri->segment(4);
if (empty($uri)) {
    $uri = null;
}
?>
<?php if (!empty(super_admin_access())) { ?>
    <div class="_buttons tw-mb-2 sm:tw-mb-4">

        <a href="<?php echo saas_url('packages/set_module_price'); ?>"
           class="btn btn-primary pull-left display-block">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('module_price'); ?>
        </a>
        <div class="clearfix"></div>
    </div>
<?php } ?>

<div class="panel_s">
    <div class="panel-body panel-table-full">
        <table class="table table-striped DataTables " id="DataTables" width="100%">
            <thead>
            <tr>
                <th><?= _l('module_name') ?></th>
                <th><?= _l('price') ?></th>
                <th><?= _l('leads_status_add_edit_order') ?></th>
                <th><?= _l('preview_video_url') ?></th>
                <th><?= _l('status') ?></th>

            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

<script type="text/javascript">
    'use strict';
    $(function () {
        initDataTable('#DataTables', '<?= base_url('saas/packages/modulesList/' . $uri) ?>', undefined, undefined, 'undefined');
    });
</script>
