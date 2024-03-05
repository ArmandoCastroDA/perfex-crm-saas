<!-- Default panel contents -->
<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= _l('payment') . ' ' . _l('histories') ?></strong>
        </div>
    </div>
    <!-- Table -->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped da" id="PaymentDataTables">
                <thead>
                <tr>
                    <th><?= _l('company_name') ?></th>
                    <th><?= _l('package_name') ?></th>
                    <th><?= _l('transaction_id') ?></th>
                    <th><?= _l('amount') ?></th>
                    <th><?= _l('payment_date') ?></th>
                    <th><?= _l('method') ?></th>
                </tr>
                </thead>
                <tbody>

                <script type="text/javascript">
                    $(function () {
                        'use strict';
                        initDataTable('#PaymentDataTables', base_url + "saas/gb/companyPaymentList/", undefined, undefined, undefined);
                    });
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>