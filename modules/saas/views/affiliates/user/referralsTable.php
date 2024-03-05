<div class="card panel-custom">
    <!-- Default panel contents -->
    <div class="card-heading">
        <div class="ps-4 pt-3 pb-3 title border-bottom">
            <strong><?= _l('referrals') . ' ' . _l('histories') ?></strong>
        </div>
    </div>

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive bg-white shadow rounded">
            <table class="table mb-0 table-center" id="payoutTable">
                <thead>
                <tr>
                    <th class="border-bottom"><?= _l('amount') ?></th>
                    <th class="border-bottom"><?= _l('status') ?></th>
                    <th class="border-bottom"><?= _l('date') ?></th>
                    <th class="border-bottom"><?= _l('action') ?></th>
                </tr>
                </thead>
                <tbody id="pricing">
                <?php
                $base_currency = get_base_currency();
                if (isset($payout_histories) && !empty($payout_histories)) {
                    foreach ($payout_histories as $key => $payout) {
                        ?>
                        <tr>
                            <td><?= app_format_money($payout->amount, $base_currency) ?></td>
                            <td><?= _l($payout->status) ?></td>
                            <td><?= _d($payout->date) ?></td>
                            <td>
                                <?php
                                if ($payout->status === 'pending' || $payout->status === 'rejected') {
                                    ?>
                                    <a
                                            onclick="return confirm('<?= _l('are_you_sure') ?>');"
                                            href="<?= site_url('affiliate/payouts/delete/' . $payout->id) ?>"
                                            class="btn btn-danger btn-sm"><?= _l('delete') ?></a>
                                    <?php
                                } else {
                                    ?>
                                    <button
                                            disabled
                                            class="btn btn-danger btn-sm"><?= _l('delete') ?></button>
                                    <?php
                                }
                                ?>
                            </td>
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