<?php if (!empty(super_admin_access())) { ?>
    <div class="_buttons tw-mb-2 sm:tw-mb-4">

        <a href="<?php echo saas_url('coupons/create'); ?>"
           class="btn btn-primary pull-left display-block">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('new_coupon'); ?>
        </a>
        <div class="clearfix"></div>
    </div>
<?php } ?>
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('coupons'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('coupon') . ' ' . _l('for') ?></th>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('code') ?></th>
                    <th><?= _l('amount') ?></th>
                    <th><?= _l('end_date') ?></th>
                    <th><?= _l('published') ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script type="text/javascript">
                list = base_url + "saas/coupons/couponList";
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