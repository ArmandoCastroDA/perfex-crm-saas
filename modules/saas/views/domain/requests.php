
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('custom_domain') . ' ' . _l('requests'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('company') ?></th>
                    <th><?= _l('custom_domain') ?></th>
                    <th><?= _l('request_date') ?></th>
                    <th><?= _l('status') ?></th>

                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script type="text/javascript">
                list = base_url + "saas/domain/requestsList";
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