<section class="bg-home bg-circle-gradiant d-flex align-items-center">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card login-page shadow rounded border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center"><?= _l('login') ?></h4>
                        <?php echo form_open('company/login', ['id' => 'register-form'], 'class="login-form mt-4"'); ?>
                        <div class="row">
                            <?php
                            $alertclass = "";
                            if ($this->session->flashdata('message-success')) {
                                $alertclass = "success";
                            } else if ($this->session->flashdata('message-warning')) {
                                $alertclass = "warning";
                            } else if ($this->session->flashdata('message-info')) {
                                $alertclass = "info";
                            } else if ($this->session->flashdata('message-danger')) {
                                $alertclass = "danger";
                            }
                            if ($this->session->flashdata('message-' . $alertclass)) { ?>
                                <div class="col-lg-12" id="alerts">
                                    <div class="text-center alert alert-<?php echo $alertclass; ?>">
                                        <?php
                                        echo $this->session->flashdata('message-' . $alertclass);
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('email') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user fea icon-sm icons">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <input type="email" class="form-control ps-5" placeholder="Email"
                                               name="email" required="">
                                        <div class="text-danger"><?= form_error('email') ?></div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('password') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-key fea icon-sm icons">
                                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                        </svg>
                                        <input type="password" class="form-control ps-5" placeholder="Password"
                                               name="password"
                                        >
                                        <div class="text-danger"><?= form_error('password') ?></div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-12">
                                <div class="d-flex justify-content-between">
                                    <div class="mb-3">

                                    </div>
                                    <p class="forgot-pass mb-3"><a
                                                href="<?php echo base_url('company/forgot-password'); ?>"
                                                class="text-dark fw-bold">
                                            <?= _l('admin_auth_login_fp') ?>

                                        </a>
                                    </p>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-12 mb-0">
                                <div class="d-grid">
                                    <button class="btn btn-primary">
                                        <?= _l('admin_auth_login_button') ?>
                                    </button>
                                </div>
                            </div><!--end col-->


                            <div class="col-12 text-center">
                                <p class="mb-0 mt-3"><small class="text-dark me-2">
                                        <?= _l('dont_have_account') ?>
                                    </small>
                                    <a href="<?php echo base_url('register'); ?>"
                                       class="text-dark fw-bold btn btn-secondary btn-sm">
                                        <?= _l('register') ?>
                                    </a></p>
                            </div><!--end col-->
                        </div><!--end row-->
                        <?php echo form_close(); ?>
                    </div>
                </div><!---->
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section>