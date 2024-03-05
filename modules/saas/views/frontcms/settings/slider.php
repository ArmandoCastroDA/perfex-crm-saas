<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a href="<?php echo saas_url('frontcms/settings/slider/create'); ?>"
       class="btn btn-primary pull-left display-block">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') . ' ' . _l('slider'); ?>
    </a>
    <div class="clearfix"></div>
</div>
<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= _l('title') ?></th>
                    <th><?= _l('slider') . ' ' . _l('image') ?></th>
                    <th><?= _l('description') ?></th>
                    <th><?= _l('status') ?></th>
                    <th class="col-options no-sort"><?= _l('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    'use strict';
                    $(document).ready(function () {
                        list = base_url + "saas/frontcms/settings/slider_list";
                        initDataTable('#DataTables', list);
                    });
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
