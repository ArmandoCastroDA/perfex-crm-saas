<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <h2 class="text-white title-dark">Contact Us</h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto">Please fill out the form below to send us an email
                        and we will get back to you as soon as possible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section pt-5 mt-4">
    <div class="container mt-100 mt-60">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0 order-2 order-md-1">
                <div class="card custom-form rounded border-0 shadow p-4">

                    <?php
                    $attributes = array('id' => 'contact-form', 'name' => 'myForm',
                        'onsubmit' => 'return validateForm()',
                        'class' => 'php-email-form');
                    echo form_open_multipart(base_url('save_faq'), $attributes);
                    ?>


                    <p id="error-msg" class="mb-0"></p>

                    <input type="hidden" name="token_name"
                           id="token_name"
                           value="<?= $this->security->get_csrf_token_name() ?>"
                    >
                    <input type="hidden" name="token_value"
                           id="token_value"
                           value="<?= $this->security->get_csrf_hash() ?>"
                    >

                    <div id="simple-msg"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                <div class="form-icon position-relative">
                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                    <input name="name" id="name" type="text" class="form-control ps-5"
                                           placeholder="Name :">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Your Email <span class="text-danger">*</span></label>
                                <div class="form-icon position-relative">
                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                    <input name="email" id="email" type="email" class="form-control ps-5"
                                           placeholder="Email :">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="book" class="fea icon-sm icons"></i>
                                    <input name="subject" id="subject" class="form-control ps-5"
                                           placeholder="subject :">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Comments <span class="text-danger">*</span></label>
                                <div class="form-icon position-relative">
                                    <i data-feather="message-circle" class="fea icon-sm icons clearfix"></i>
                                    <textarea name="comments" id="comments" rows="4" class="form-control ps-5"
                                              placeholder="Message :"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" id="submit" name="send" class="btn btn-primary">Send Message
                                </button>
                            </div>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>

            <div class="col-lg-7 col-md-6 order-1 order-md-2">
                <div class="title-heading ms-lg-4">
                    <?php $contact_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'contact_heading'), false) ?>

                    <h4 class="mb-4"><?= $contact_heading_info->title ?: '' ?></h4>
                    <p class="text-muted"><?= $contact_heading_info->description ?: '' ?></p>
                    <?php $contact_info = get_old_order_by('tbl_saas_all_section_area', array('type' => 'new_contact'), null, null, 6);
                    foreach ($contact_info as $key => $v_info) {
                        ?>
                        <div class="d-flex contact-detail align-items-center mt-3">
                            <div class="icon">
                                <i data-feather="<?= $v_info->icons ?: '' ?>" class="fea icon-m-md text-dark me-3"></i>
                            </div>
                            <div class="flex-1 content">
                                <h6 class="title fw-bold mb-0"><?= $v_info->title ?: '' ?></h6>
                                <a href="<?= $v_info->link ?: '' ?>" class="text-primary"><?= $v_info->name ?: '' ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>