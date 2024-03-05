<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a href="<?php echo saas_url('frontcms/settings/create_footer_icons'); ?>"
       class="btn btn-primary pull-left display-block"
       class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal"
    >
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') ?> <?= _l('footer_icons') ?>
    </a>
    <div class="clearfix"></div>
</div>
<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= _l('icons') ?></th>
                    <th><?= _l('links') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    list = base_url + "saas/frontcms/settings/footer_iconsList";
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>