<div class="row">
    <?php
    foreach ($states as $state) { ?>
        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-2 sm:tw-mb-0">

            <div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
                <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                    <dt class="tw-font-medium text-<?= $state['color']; ?>">
                        <?= _l($state['name']); ?>
                    </dt>
                    <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                        <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <span class="tw-mr-1">    <span class="counter">
                            <?php echo $state['count']; ?>
                        </span>
                        </span>
                        </div>
                    </dd>
                </div>
            </div>

        </div>
        <?php
    } ?>
</div>
<div class="row tw-mt-5">
    <div class="col-sm-6">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= _l('commission') . ' ' . _l('histories') ?></strong>
                </div>
            </div>

            <!-- Table -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped da" id="commissionTable">
                        <thead>
                        <tr>
                            <th><?= _l('total') . ' ' . _l('amount') ?></th>
                            <th><?= _l('commission') . ' ' . _l('amount') ?></th>
                            <th><?= _l('commission') . ' ' . _l('type') ?></th>
                            <th><?= _l('date') ?></th>
                        </tr>
                        </thead>
                        <tbody id="pricing">
                        <script>
                            $(function () {
                                'use strict';
                                initDataTable('#commissionTable', base_url + "saas/affiliates/commissionHistoryList/" + '<?= $user->user_id ?>', undefined, undefined, undefined);
                            });
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--************ Payment History End***********-->
    </div>
    <div class="col-sm-6">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= _l('referral') . ' ' . _l('companies') ?></strong>
                </div>
            </div>

            <!-- Table -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped da" id="referralTable">
                        <thead>
                        <tr>
                            <th><?= _l('name') ?></th>
                            <th><?= _l('email') ?></th>
                            <th><?= _l('date') ?></th>
                        </tr>
                        </thead>
                        <tbody id="pricing">
                        <script>
                            $(function () {
                                'use strict';
                                initDataTable('#referralTable', base_url + "saas/affiliates/referralCompanyList/" + '<?= $user->user_id ?>', undefined, undefined, undefined);
                            });
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--************ Payment History End***********-->
    </div>

    <div class="col-sm-6">
        <div class="panel panel-custom">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= _l('payout') . ' ' . _l('histories') ?></strong>
                </div>
            </div>

            <!-- Table -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped da" id="payoutTable">
                        <thead>
                        <tr>
                            <th><?= _l('amount') ?></th>
                            <th><?= _l('status') ?></th>
                            <th><?= _l('date') ?></th>

                        </tr>
                        </thead>
                        <tbody id="pricing">
                        <script>
                            $(function () {
                                'use strict';
                                initDataTable('#payoutTable', base_url + "saas/affiliates/payoutHistoryList/" + '<?= $user->user_id ?>', undefined, undefined, undefined);
                            });
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--************ Payment History End***********-->
    </div>
</div>