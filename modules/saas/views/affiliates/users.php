<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('affiliates') . ' ' . _l('users'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('email') ?></th>
                    <th><?= _l('total_referred') ?></th>
                    <th><?= _l('total_earning') ?></th>
                    <th><?= _l('total_withdrawn') ?></th>
                    <th><?= _l('remaining_balance') ?></th>
                    <th><?= _l('status') ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script type="text/javascript">
                list = base_url + "saas/affiliates/usersList";
            </script>
        </div>
    </div>
</div>

<script>
    $(function () {
        'use strict';
        initDataTable('#DataTables', list, undefined, undefined, 'undefined');
    });
</script>
