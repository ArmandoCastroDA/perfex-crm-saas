<?php
$referralUrl = base_url('register?via=' . $user->referral_link);
$referral = base_url('register?via=');
?>
<section class="bg-profile d-table w-100 bg-light">
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <?php if (isset($error_messages)) {
                    foreach ($error_messages as $error_message) {
                        echo '<div class="alert alert-danger">' . $error_message . '</div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 rounded shadow">
                    <div class="card-body">
                        <div class="border-bottom pb-4">
                            <h5>
                                <?= _l('hello') ?> <?= $user->first_name ?> <?= $user->last_name ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <?= _l('your_affiliate_link_is') ?>
                            </p>
                            <?= form_open(base_url('affiliate/settings'), ['id' => 'affiliate-link-form']) ?>
                            <div class="input-group hide"
                                 id="affiliate_link_edit"
                            >
                                <div class="input-group-prepend"
                                ><span
                                            class="input-group-text p-2">
                                        <?= $referral ?>
                                    </span>
                                </div>
                                <input type="text" class="form-control" name="referral_link"
                                       value="<?= $user->referral_link ?>">

                                <button class="btn btn-warning" type="submit">
                                    <?= _l('update') ?>
                                </button>
                                <button class="btn btn-primary" type="button"
                                        onclick="cancelEditAffiliateLink()">
                                    <?= _l('cancel') ?>
                                </button>
                            </div>
                            <?= form_close() ?>

                            <div class="input-group" id="affiliate_link">
                                <input type="text" class="form-control"
                                       id="affiliate-link"
                                       onfocus="this.select();" onmouseup="return false;"
                                       value="<?= $referralUrl ?>" readonly>

                                <button class="btn btn-warning" type="button"
                                        onclick="copyToClipboard('<?= $referralUrl ?>');">
                                    <?= _l('copy') ?>
                                </button>
                                <button class="btn btn-primary" type="button"
                                        onclick="editAffiliateLink()">
                                    <?= _l('edit') ?>
                                </button>
                            </div>
                        </div>
                        <h5 class="text-md-start text-center">Personal Detail :</h5>
                        <?php
                        echo form_open(base_url('affiliate/settings'), ['id' => 'affiliate-settings-form']);
                        ?>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('first_name') ?> :</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user fea icon-sm icons">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <input name="first_name" id="first" type="text" class="form-control ps-5"
                                               value="<?= $user->first_name ?>"
                                        >
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('last_name') ?> :</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-user-check fea icon-sm icons">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <polyline points="17 11 19 13 23 9"></polyline>
                                        </svg>
                                        <input name="last_name" id="last" type="text" class="form-control ps-5"
                                               value="<?= $user->last_name ?>"
                                        >
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('mobile') ?> :</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-phone fea icon-sm icons">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                        <input name="mobile" id="number" type="number"
                                               value="<?= $user->mobile ?>"
                                               class="form-control ps-5">
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('country') ?> :</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-bookmark fea icon-sm icons">
                                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        <select name="country" id="country" class="form-select ps-5">
                                            <option value=""><?= _l('select_country') ?></option>
                                            <?php
                                            $countries = get_all_countries();
                                            foreach ($countries as $country) { ?>
                                                <option value="<?= $country['short_name'] ?>"
                                                    <?= $user->country == $country['short_name'] ? 'selected' : '' ?>
                                                ><?= $country['short_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label"><?= _l('address') ?> :</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-message-circle fea icon-sm icons">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                        </svg>
                                        <textarea name="address" id="address" rows="4"
                                                  class="form-control ps-5"><?= $user->address ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div><!--end row-->
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="submit" id="submit" name="send" class="btn btn-primary"
                                       value="<?= _l('save_changes') ?>">
                            </div><!--end col-->
                        </div><!--end row-->
                        <?= form_close() ?>


                        <div class="row">
                            <div class="col-md-6 mt-4 pt-2">
                                <h5><?= _l('change_email') ?></h5>

                                <?php
                                echo form_open(base_url('affiliate/settings'), ['id' => 'change_email']);
                                ?>
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"><?= _l('current_password') ?> :</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-key fea icon-sm icons">
                                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                                </svg>
                                                <input name="current_password" type="password"
                                                       autocomplete="off"
                                                       class="form-control ps-5"/>
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"><?= _l('new_email') ?> :</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-mail fea icon-sm icons">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg>
                                                <input name="new_email" id="email" type="email"
                                                       class="form-control ps-5" value="<?= $user->email ?>"

                                                />
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-lg-12 mt-2 mb-0">
                                        <button type="submit" class="btn btn-primary"><?= _l('change_email') ?></button>
                                    </div><!--end col-->
                                </div><!--end row-->
                                <?= form_close() ?>
                            </div><!--end col-->

                            <div class="col-md-6 mt-4 pt-2">
                                <h5><?= _l('change_password') ?></h5>
                                <?php
                                echo form_open(base_url('affiliate/settings'), ['id' => 'change_password']);
                                ?>
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"><?= _l('current_password') ?> :</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-key fea icon-sm icons">
                                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                                </svg>
                                                <input type="password" class="form-control ps-5"
                                                       name="old_password"
                                                       placeholder="<?= _l('current_password') ?>" required="">
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"><?= _l('new_password') ?> :</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-key fea icon-sm icons">
                                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                                </svg>
                                                <input type="password" class="form-control ps-5"
                                                       name="new_password"
                                                       placeholder="<?= _l('new_password') ?>" required="">
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label"><?= _l('confirm_password') ?> :</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-key fea icon-sm icons">
                                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                                                </svg>
                                                <input type="password" class="form-control ps-5"
                                                       name="confirm_password"
                                                       placeholder="<?= _l('confirm_password') ?>" required="">
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-lg-12 mt-2 mb-0">
                                        <button type="submit"
                                                class="btn btn-primary"><?= _l('change_password') ?></button>
                                    </div><!--end col-->
                                </div><!--end row-->
                                <?= form_close() ?>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<script type="text/javascript">
    'use strict';

    function copyToClipboard(text) {
        var dummy = document.createElement('input');
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert("Copied to clipboard");
    }

    function editAffiliateLink() {
        // remove hide class from affiliate_link_edit id and add hide class to affiliate_link id
        document.getElementById("affiliate_link_edit").classList.remove("hide");
        document.getElementById("affiliate_link").classList.add("hide");
    }

    function cancelEditAffiliateLink() {
        // remove hide class from affiliate_link id and add hide class to affiliate_link_edit id
        document.getElementById("affiliate_link").classList.remove("hide");
        document.getElementById("affiliate_link_edit").classList.add("hide");
    }
</script>