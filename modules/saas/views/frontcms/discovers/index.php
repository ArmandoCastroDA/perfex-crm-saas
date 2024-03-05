<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a class="btn btn-primary pull-left display-block tw-mr-3"
       href="<?= base_url('saas/frontcms/discovers/new') ?>"><?= _l('create') ?> <?= _l('discovers') ?></a>

    <a class="btn btn-primary pull-right display-block tw-ml-3"
       class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal"
       href="<?= base_url('saas/frontcms/discovers/create') ?>"><?= _l('add') ?> <?= _l('discovers_heading') ?></a>
    <div class="clearfix"></div>
</div>
<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " id="DataTables">
                <thead>
                <tr>
                    <th><?= _l('title') ?></th>
                    <th><?= _l('description') ?></th>
                    <th><?= _l('status') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    list = base_url + "saas/frontcms/discovers/discoversList";
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>