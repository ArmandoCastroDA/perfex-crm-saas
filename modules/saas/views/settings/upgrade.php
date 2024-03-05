<?php
$error = $this->session->userdata('sserror');
if (!empty($error)) { ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php
    $this->session->unset_userdata('sserror');
}

$c_text = null;
if (!empty($sub_info) && $sub_info->is_trial == 'Yes') {
    $c_text = _l('trial_version_end', $sub_info->package_name);
} else if (!empty($sub_info) && $sub_info->is_trial == 'No') {
    $c_text = _l('pricing_plan_version_end', $sub_info->package_name);
}
$company_id = (!empty($sub_info) ? $sub_info->companies_id : '');
echo form_open(BaseUrl('proceedPayment'), array('id' => 'checkoutPayment', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
    <div class="row">
        <div class="modal-body pt0 wrap-modal plain_package_details">
            <!-- PayPal Logo -->
            <div class="col-sm-8 row">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <strong><?= _l('current_package') . ' ' . $sub_info->package_name . ' ' . _l('details') ?></strong>
                    </div>
                    <div class="panel-body">
                        <span class="block mb"><strong class="text-danger bold"> <?= $c_text ?> </strong> But your data is still safe, and you can continue where you left off after paying the account fees..</span>
                        <span>No taxes, or hidden fees included.</span>

                        <section class="package-section">
                            <div class='packaging packaging-palden'>
                                <div class='packaging-item'>
                                    <div class='packaging-deco custom-bg'>
                                        <svg class='packaging-deco-img' enable-background='new 0 0 300 100'
                                             height='100px'
                                             id='Layer_1'
                                             preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100'
                                             width='300px'
                                             x='0px'
                                             xml:space='preserve'
                                             xmlns='http://www.w3.org/2000/svg'
                                             y='0px'>
          <path class='deco-layer deco-layer--1'
                d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z'
                fill='#FFFFFF' opacity='0.6'></path>
                                            <path class='deco-layer deco-layer--2'
                                                  d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z'
                                                  fill='#FFFFFF' opacity='0.6'></path>
                                            <path class='deco-layer deco-layer--3'
                                                  d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;H42.401L43.415,98.342z'
                                                  fill='#FFFFFF' opacity='0.7'></path>
                                            <path class='deco-layer deco-layer--4'
                                                  d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z'
                                                  fill='#FFFFFF'></path>
</svg>
                                        <div class='packaging-package'><span
                                                    class='packaging-currency'> </span><?= $sub_info->package_name ?>
                                            <span class='packaging-period'></span>
                                        </div>

                                        <div class="package_position <?= !empty($sub_info->frequency) ? 'jcenter' : '' ?>">
                                            <?php
                                            if (!empty($sub_info->frequency)) { ?>
                                                <h3 class="packaging-title text-center"> <?= display_money($sub_info->amount, default_currency()) . ' / ' . _l($sub_info->frequency) ?></h3>
                                            <?php } else {
                                                echo package_price($sub_info);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <ul class='packaging-feature-list'>
                                        <?= saas_packege_list($sub_info) ?>
                                    </ul>

                                </div>

                            </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="panel panel-custom" style="box-shadow: 0 0px 0px 0 rgba(0,0,0,.15);overflow: hidden">
                    <!-- Default panel contents -->
                    <div class="panel-body bb mb text-center">

                        <a href="<?= BaseUrl('clients/updatePackage/' . $company_id) ?>"
                           class="btn btn-danger btn-lg btn-block"><?= _l('update') . ' ' . _l('package') ?></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php echo form_close(); ?>