<div class="card shadow rounded border-0 overflow-hidden">
    <!-- Default panel contents -->
    <div class="ps-4 pt-3 pb-3 title border-bottom">
        <strong><?= _l('commission') . ' ' . _l('histories') ?></strong>
    </div>
    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive bg-white shadow rounded">
            <table class="table mb-0 table-center" id="commissionTable">
                <thead>
                <tr>
                    <th class="border-bottom"><?= _l('total') . ' ' . _l('amount') ?></th>
                    <th class="border-bottom"><?= _l('commission') . ' ' . _l('amount') ?></th>
                    <th class="border-bottom"><?= _l('commission') . ' ' . _l('type') ?></th>
                    <th class="border-bottom"><?= _l('date') ?></th>
                </tr>
                </thead>
                <tbody id="pricing">
                <?php
                $base_currency = get_base_currency();
                if (isset($commission_histories) && !empty($commission_histories)) {
                    foreach ($commission_histories as $key => $commission) {
                        ?>
                        <tr>
                            <td><?= display_money($commission->amount_was) ?></td>
                            <td><?= display_money($commission->get_amount) ?></td>
                            <td><?= $commission->commission_type === 'percentage' ? round($commission->commission_value, 2) . '%' : app_format_money($commission->commission_value, $base_currency) ?></td>
                            <td><?= _d($commission->date) ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="text-center"><?= _l('no_data_found') ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div><!--************ Payment History End***********-->