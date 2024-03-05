<?php
$company_name = get_option('saas_companyname') ? get_option('saas_companyname') : 'Perfect SaaS';
$affiliate_commission_amount = get_option('affiliate_commission_amount') ? get_option('affiliate_commission_amount') : 0;
$affiliate_commission_type = get_option('affiliate_commission_type') == 'percentage' ? '%' : 'credits';
$affiliate_rule = get_option('affiliate_rule') == 'only_first_subscription' ? 'first subscription' : 'each subscriptions';
?>
<section class="bg-auth-home bg-circle-gradiant d-table w-100 vh-100">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card shadow rounded border-0 mt-4">
                    <div class="card-body">
                        <h4 class="card-title text-center">
                            <?= $company_name ?>
                            <?= _l('affiliate_program') ?>
                        </h4>
                        <?php echo form_open('affiliate/become', ['id' => 'register-form'], 'class="login-form mt-4"'); ?>
                        <p class="text-muted text-center ">
                            Join Friends of <?= $company_name ?>
                            and receive <?= $affiliate_commission_amount ?> <?= $affiliate_commission_type ?>
                            of <?= $affiliate_rule ?>
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo _l('first_name'); ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user fea icon-sm icons">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <input type="text" class="form-control ps-5" placeholder="First Name"
                                               name="first_name"
                                        >
                                        <div class="text-danger">
                                            <?= form_error('first_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo _l('last_name'); ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user-check fea icon-sm icons">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <polyline points="17 11 19 13 23 9"></polyline>
                                        </svg>
                                        <input type="text" class="form-control ps-5" placeholder="Last Name"
                                               name="last_name"
                                        >
                                        <div class="text-danger">
                                            <?= form_error('last_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo _l('email'); ?><span
                                                class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-mail fea icon-sm icons">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                        <input type="email" class="form-control ps-5" placeholder="Email" name="email"
                                        >
                                        <div class="text-danger">
                                            <?= form_error('email'); ?>
                                        </div>

                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo _l('password'); ?> <span
                                                class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-key fea icon-sm icons">
                                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                        </svg>
                                        <input type="password" class="form-control ps-5" placeholder="Password"
                                               id="password" name="password"
                                        >
                                        <div class="text-danger">
                                            <?php echo form_error('password'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo _l('confirm_password'); ?> <span
                                        <span class="text-danger">*</span></label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-key fea icon-sm icons">
                                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                        </svg>
                                        <input type="password" class="form-control ps-5" placeholder="Password"
                                               name="passwordr" id="passwordr"
                                        >
                                        <div class="text-danger">
                                            <?php echo form_error('passwordr'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->


                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="terms">
                                        <label class="form-check-label" for="flexCheckDefault">I Accept <a
                                                    target="_blank"
                                                    href="<?= base_url('front/terms-conditions') ?>"
                                                    class="text-primary">Terms And Condition</a></label>
                                        <div class="text-danger">
                                            <?php echo form_error('terms'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-md-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary">Register</button>
                                </div>
                            </div><!--end col-->

                            <div class="mx-auto">
                                <p class="mb-0 mt-3"><small class="text-dark me-2">Already have an account ?</small> <a
                                            href="<?= base_url('affiliate/login') ?>"
                                            class="text-dark btn fw-bold btn-secondary btn-sm">Sign in</a></p>
                            </div>
                            <?php echo form_close(); ?>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section>
<!-- Hero End -->
