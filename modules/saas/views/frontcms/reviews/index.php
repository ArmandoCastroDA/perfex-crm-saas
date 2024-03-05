<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a href="<?php echo saas_url('frontcms/reviews/create_review'); ?>"
       class="btn btn-primary pull-left display-block tw-mr-3">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') ?> <?= _l('reviews') ?>
    </a>
    <a href="<?php echo saas_url('frontcms/reviews/create'); ?>"
       class="btn btn-primary pull-left display-block"
       class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal"
    >
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('new') ?> <?= _l('reviews_heading') ?>
    </a>
    <div class="clearfix"></div>
</div>
<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " id="DataTables">
                <thead>
                <tr>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('image') ?></th>
                    <th><?= _l('rating') ?></th>
                    <th><?= _l('status') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    list = base_url + "saas/frontcms/reviews/reviewsList";
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>