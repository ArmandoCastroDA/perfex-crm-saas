<div class="panel panel-custom" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            <?php
            echo lang('faq');
            $unread_email = total_rows('tbl_saas_front_contact_us', array('view_status' => 0));
            echo ' (' . $unread_email . ')';
            ?>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= _l('subject') ?></th>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('email') ?></th>
                    <th><?= _l('phone') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    $(function () {
                        'use strict';
                        list = base_url + "saas/faq/faqList";
                        initDataTable('#DataTables', list);
                    });
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>