<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <h2 class="text-white title-dark">
                        <?= _l('find_my_company') ?>
                    </h2>

                    <p class="text-white-50 para-desc mb-0 mx-auto">
                        <?= _l('find_my_company_desc') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-invoice d-table w-100 bg-light pt-0">
    <div class="row my-md-5 pt-md-3 my-4 pt-2 justify-content-center ">
        <div class="col-12 text-center">
            <div class="section-title">
                <h4 class="title mb-4">
                    <?= _l('find_my_company') ?> ?
                </h4>
            </div>
        </div><!--end col-->
    </div>
    <div class="col-sm-4 m-auto">
        <div class="card rounded border-0 shadow ms-lg-5 justify-content-center align-items-center">
            <div class="card-body">
                <div class="content pt-2">
                    <div class="card-body">

                        <?= form_open() ?>
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="form-icon position-relative ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-link-2 fea icon-sm icons z-index">
                                            <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"/>
                                            <line x1="8" y1="12" x2="16" y2="12"/>
                                        </svg>
                                        <div class="input-group">
                                            <input type="text" class="form-control ps-5"
                                                   placeholder="Domain" name="domain" required="">
                                            <div class="text-danger"></div>
                                            <span class="input-group-text"
                                                  id="basic-addon1">.<?= saas_base_url() ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-3">
                                <span class="text-muted
                                d-block
                                text-center
                                "><?= _l('or') ?></span>

                                </div>


                                <div class="mb-3">
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="feather feather-user fea icon-sm icons">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <input type="email" class="form-control ps-5"
                                               placeholder="Email" name="email" required="">
                                        <div class="text-danger"></div>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-12 mb-0">
                                <div class="d-grid">
                                    <button class="btn btn-primary"><?= _l('find_my_company') ?></button>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>