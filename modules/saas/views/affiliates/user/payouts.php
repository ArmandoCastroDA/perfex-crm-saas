<?php
$remaining_balance = $affiliate_info->total_balance - $affiliate_info->withdrawal_amount;
$minimum_payout_amount = get_option('minimum_payout_amount');
?>
    <section class="bg-profile d-table w-100 bg-light">
        <div class="container">
            <div class="row">
                <?php
                foreach ($states as $state) { ?>
                    <div class="col-md-6 col-xl-3">
                        <div class="mini-stats-wid card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1"><p
                                                class="text-muted fw-medium"><?= _l($state['name']); ?></p>
                                        <h4 class="mb-0">
                                            <?php echo $state['count']; ?></h4></div>
                                    <div class="avatar-sm  align-self-center ms-3"><span
                                                class="avatar-title bg-<?= $state['color']; ?> rounded-circle"><i
                                                    class="<?= $state['icon']; ?>"></i>
                                            </span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <?php if (isset($error_messages)) {
                        foreach ($error_messages as $error_message) {
                            echo '<div class="alert alert-danger">' . $error_message . '</div>';
                        }
                    }
                    ?>
                    <?php $this->load->view('affiliates/user/payoutsTable') ?>
                </div>
            </div>
        </div>
    </section>
<?php
if ($minimum_payout_amount < $remaining_balance) {
    ?>
    <div class="modal fade" id="LoginForm" tabindex="-1" aria-labelledby="LoginForm-title"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="LoginForm-title">
                        <?= _l('payout_request') ?>
                    </h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i
                                class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <?= form_open(base_url('affiliate/payouts'), ['id' => 'payout-form', 'class' => 'login-form p-4']) ?>
                <div class="modal-body">
                    <form class="login-form p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <?= _l('amount') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <input type="number" class="form-control" placeholder="<?= _l('amount') ?>"
                                               name="amount"
                                               required=""
                                               max="<?= $remaining_balance ?>"
                                               min="<?= $minimum_payout_amount ?>"
                                        >
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <?= _l('payment_method') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <select class="form-select form-control" name="payment_method" required="">
                                            <?php
                                            $this->load->model('payment_modes_model');
                                            $payment_modes = $this->saas_model->get_payment_modes();
                                            $inv_modes = get_option('withdrawal_payment_method');
                                            if (isset($inv_modes)) {
                                                $inv_modes = unserialize($inv_modes);
                                            }
                                            foreach ($payment_modes as $mode) {
                                                if (isset($inv_modes) && in_array($mode['id'], $inv_modes)) { ?>
                                                    <option value="<?= $mode['id'] ?>"><?= $mode['name'] ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <?= _l('notes') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                    <textarea class="form-control" placeholder="<?= _l('notes') ?>" name="notes"
                                              required=""></textarea>
                                    </div>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><?= _l('submit') ?></button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
<?php } ?>