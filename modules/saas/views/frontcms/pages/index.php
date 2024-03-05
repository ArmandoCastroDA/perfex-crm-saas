<?php if (!empty(super_admin_access())) { ?>
    <div class="_buttons tw-mb-2 sm:tw-mb-4">
        <a class="btn btn-primary pull-left display-block tw-mr-3"
           href="<?= base_url('saas/frontcms/page/create') ?>"><?= _l('create') ?> <?= _l('page') ?></a>

        <a class="btn btn-primary pull-right display-block tw-ml-3"
           href="<?= base_url('saas/frontcms/page/sync') ?>"><?= _l('sync') ?> <?= _l('page') ?></a>
        <div class="clearfix"></div>
    </div>
<?php } ?>
<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= _l('title') ?></th>
                    <th><?= _l('url') ?></th>
                    <th><?= _l('page') ?> <?= _l('type') ?></th>

                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    list = base_url + "saas/frontcms/page/pageList";
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        'use strict';
        initDataTable('#DataTables', list, undefined, undefined, 'undefined');
    });
</script>