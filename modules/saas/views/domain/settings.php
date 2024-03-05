<?php
echo form_open(admin_url('saas/domain/settings'), ['id' => 'settings-form']);
?>
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('custom_domain') . ' ' . _l('settings'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="col-md-6">
            <div class="form-group">
                <label>
                    <?php echo _l('title'); ?>
                </label>
                <input name="custom_domain_title" id="custom_domain_title"
                       value="<?php echo get_option('custom_domain_title'); ?>"
                       type="text" class="form-control"
                >
            </div>
        </div>
        <div class="col-md-6">
            <?php
            $ip_address = get_option('custom_domain_ip_address');
            if (empty($ip_address)) {
                // get the IP address from server
                $ip_address = gethostbyname($_SERVER['SERVER_NAME']);
            }
            echo render_input('custom_domain_ip_address', 'custom_domain_ip_address', $ip_address); ?>


        </div>
        <div class="col-md-12">
            <p class="bold"><?php echo _l('details'); ?></p>
            <?php
            $details = get_option('custom_domain_details'); ?>
            <?php echo render_textarea('custom_domain_details', '', $details, [], [], '', 'tinymce'); ?>
        </div>
        <div class="col-md-12">
            <div class="tw-mt-5 ">
                <p class="label label-primary tw-text-lg"><i class="fas fa-info-circle-fill"></i>
                    <?= _l('custom_settings_info') ?>
                </p>
                <h6 class="fs-14">
                    <?= _l('dns_settings') . ' ' . _l('one') ?>
                </h6>
                <div class="card bg-light pt-3 pl-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    <?= _l('type') ?>
                                </label>
                                <p>
                                    <span class="fs-14 font-weight-normal label label-primary badge-pill">
                                        <?= _l('cname') ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    <?= _l('host') ?>
                                </label>
                                <p><span class="fs-14 font-weight-normal label label-primary badge-pill">www</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>
                                    <?= _l('value') ?>
                                </label>
                                <p><span class="fs-14 font-weight-normal label label-primary badge-pill copy"
                                         data-copy="<?= APP_BASE_URL ?>"
                                    >
                                        <?= APP_BASE_URL ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>TTL</label>
                                <p>
                                    <span class="fs-14 font-weight-normal label label-primary badge-pill">Automatic</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="mt-2 fs-14">
                    <?= _l('dns_settings') . ' ' . _l('two') ?>
                </h6>
                <div class="card bg-light pt-3 pl-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= _l('type') ?></label>
                                <p>
                                    <span class="fs-14 font-weight-normal label label-primary badge-pill">
                                        <?= _l('a_record') ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= _l('host') ?></label>
                                <p><span class="fs-14 font-weight-normal label label-primary badge-pill">@</span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= _l('value') ?></label>
                                <p>
                                    <span class="fs-14 font-weight-normal label label-primary badge-pill copy"
                                          data-copy="<?= $ip_address ?>">
                                        <?= $ip_address ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>TTL</label>
                                <p>
                                    <span class="fs-14 font-weight-normal label label-primary badge-pill">Automatic</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-toolbar text-right">
            <button type="submit" class="btn btn-primary">
                <?php echo _l('settings_save'); ?>
            </button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    // click to copy
    $(document).ready(function () {
        $('.copy').click(function () {
            var copyText = $(this).attr('data-copy');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
            alert_float('success', 'Copied');
        });
    });
</script>
