<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-3 sm:tw-gap-5">
    <?php
    foreach ($states as $state) { ?>
        <a href="<?php echo base_url($state['link']) ?>">
            <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
                <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                    <dt class="tw-font-medium text-<?= $state['color']; ?>">
                        <?= _l($state['name']); ?>
                    </dt>
                    <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                        <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <span class="tw-mr-1">    <span class="counter">
                            <?php echo $state['count']; ?>
                        </span></span>
                        </div>
                    </dd>
                </div>
            </div>
        </a>
        <?php
    } ?>
</dl>
<div class="row tw-mt-5">
    <div class="col-md-6 ">
        <div class="panel panel-custom menu h-437">
            <header class="panel-heading mb-0">
                <h3 class="panel-title"><?= _l('recent_subscriptions') ?></h3>
            </header>

            <div class="panel-body p-0">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Date</th>
                    </tr>
                    </thead>

                    <?php
                    $recent_reginstered = get_company_subscription(null, 'running', true, 4, true);

                    if (!empty($recent_reginstered)) {
                        foreach ($recent_reginstered as $_key => $v_recent_reginstered) {
                            if ($v_recent_reginstered->for_seed == 'yes') {
                                continue;
                            }
                            ?>
                            <tbody>
                            <tr>
                                <td><?= $_key + 1 ?> </td>
                                <td>
                                    <a href="<?php echo base_url("saas/companies/details/$v_recent_reginstered->companies_id"); ?>"
                                    ><?= $v_recent_reginstered->name ?></a>
                                </td>
                                <td><?= $v_recent_reginstered->email ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#myModal"
                                       href="<?php echo base_url("subs_package_details/$v_recent_reginstered->company_history_id/1"); ?>"
                                       class="text-center"><?= ($v_recent_reginstered->package_name) ?></a>
                                </td>
                                <td><?= date('Y-m-d', strtotime($v_recent_reginstered->created_date)) ?></td>
                            </tr>
                            </tbody>
                        <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mt-lg">
        <div class="panel panel-custom menu h-437">
            <header class="panel-heading mb-0">
                <h3 class="panel-title"><?= _l('recent_licence_expired_subscriptions') ?></h3>
            </header>

            <div class="panel-body p-0">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Expired Date</th>
                    </tr>
                    </thead>

                    <?php
                    $recent_reginstered = get_company_subscription(null, 'expired', true, 4, true);
                    if (!empty($recent_reginstered)) {
                        foreach ($recent_reginstered as $_key => $v_recent_reginstered) {
                            ?>
                            <tbody>
                            <tr>
                                <td><?= $_key + 1 ?> </td>
                                <td>
                                    <a href="<?php echo base_url("saas/companies/details/$v_recent_reginstered->companies_id"); ?>"
                                    ><?= $v_recent_reginstered->name ?></a>
                                </td>
                                <td><?= $v_recent_reginstered->email ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#myModal"
                                       href="<?php echo base_url("subs_package_details/$v_recent_reginstered->company_history_id/1"); ?>"
                                       class="text-center"><?= $v_recent_reginstered->package_name ?></a>
                                </td>
                                <td><?= $v_recent_reginstered->expired_date ?></td>
                            </tr>
                            </tbody>
                        <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-12 clearfix">
        <div class="panel panel-custom menu">
            <header class="panel-heading mb-0">
                <?= _l('package') . ' ' . _l('overview') ?>
            </header>

            <div class="panel-body">
                <div id="morris-bar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Morris.js charts -->
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/plugins/raphael/raphael.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/plugins/morris/morris.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/waypoints.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/jquery.counterup.min.js"></script>

<script type="text/javascript">
    "use strict";
    $(document).ready(function () {
        var chartdata = [
            <?php
            if(!empty($plan_overview)){foreach($plan_overview as $package_name => $v_subs_info){
            ?>
            {
                y: "<?= $package_name ?>",
                a: <?= $v_subs_info['pending'] ?>,
                b: <?= $v_subs_info['running'] ?>,
                c: <?= $v_subs_info['expired'] ?>,
                d: <?= $v_subs_info['suspended'] ?>,
                e: <?= $v_subs_info['terminated'] ?>

            },
            <?php }} ?> ]


        new Morris.Bar({
            element: 'morris-bar',
            data: chartdata,
            xkey: 'y',
            ykeys: ["a", "b", "c", "d", "e"],
            labels: ["<?= _l('pending')?>", "<?= _l('running')?>", "<?= _l('expired')?>", "<?= _l('suspended')?>", "<?= _l('terminated')?>"],
            xLabelMargin: 2,
            barColors: ['#23b7e5', '#ff902b', '#f05050', '#ff902e', '#f00060'],
            resize: true,
            parseTime: false,
        });

        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    });
</script>
