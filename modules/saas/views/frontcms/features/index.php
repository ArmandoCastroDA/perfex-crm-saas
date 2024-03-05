<?php
$active = 1;
?>

<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a href="<?php echo saas_url('frontcms/features/new'); ?>"
       class="btn btn-primary pull-left display-block tw-mr-3">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') ?> <?= _l('features') ?>
    </a>
    <a href="<?php echo saas_url('frontcms/features/new2'); ?>"
       class="btn btn-primary pull-left display-block tw-mr-3">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') ?> <?= _l('features') . ' 2' ?>
    </a>
    <a href="<?php echo saas_url('frontcms/features/create'); ?>"
       class="btn btn-primary pull-left display-block"
       class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal"
    ><i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('add') ?> <?= _l('features_heading') ?>
    </a>
    <div class="clearfix"></div>
</div>
<div class="nav-tabs-custom panel_s">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="<?= base_url('saas/frontcms/features') ?>"><?= _l('all') ?> <?= _l('features') ?></a>
        </li>
        <li class="">
            <a href="<?= base_url('saas/frontcms/features/features_list') ?>"><?= _l('all') . ' ' . _l('features') . ' ' . ('2') ?></a>
        </li>
    </ul>

    <div class="">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= _l('title') ?></th>
                        <th><?= _l('description') ?></th>
                        <th><?= _l('status') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <script type="text/javascript">
                        list = base_url + "saas/frontcms/features/featuresList";
                    </script>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
