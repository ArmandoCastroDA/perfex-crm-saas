<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('affiliates') . ' ' . _l('payouts'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('available_amount') ?></th>
                    <th><?= _l('requested_amount') ?></th>
                    <th><?= _l('submitted_date') ?></th>
                    <th><?= _l('status') ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script type="text/javascript">
                list = base_url + "saas/affiliates/payoutsList";
            </script>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        'use strict';
        initDataTable('#DataTables', list, undefined, undefined, 'undefined');
    });
</script>
