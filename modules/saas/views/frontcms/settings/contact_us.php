<div class="panel panel-custom">
    <!-- Start Form -->
    <header class="panel-heading"><?= _l('contacts') ?>
    </header>
    <div class="panel-body pb-sm">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('email') ?></th>
                    <th><?= _l('phone') ?></th>
                    <th><?= _l('subject') ?></th>
                    <th class="col-options no-sort"><?= _l('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <script type="text/javascript">
                    list = base_url + "saas/frontcms/contacts/contactsList";
                </script>
                </tbody>
            </table>
        </div>
    </div>
</div>

