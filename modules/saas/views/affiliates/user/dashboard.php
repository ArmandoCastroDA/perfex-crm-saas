<section class="bg-profile d-table w-100 bg-light">
    <div class="container">
        <div class="row">
            <?php
            foreach ($states as $state) { ?>

                <div class="col-md-6 col-xl-3">
                    <div class="mini-stats-wid card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1"><p class="text-muted fw-medium"><?= _l($state['name']); ?></p>
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
            <div class="col-sm-6">
                <?php $this->load->view('affiliates/user/commissionsTable') ?>
            </div>
            <div class="col-sm-6">
                <?php $this->load->view('affiliates/user/payoutsTable') ?>
            </div>
        </div>
    </div>

</section>
