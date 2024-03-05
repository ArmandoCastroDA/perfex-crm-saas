<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="_buttons tw-mb-2 sm:tw-mb-4">
    <a href="<?= site_url($c_url . 'custom_domain/new') ?>" class="btn btn-primary pull-left display-block">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?= _l('new_domain') ?>
    </a>

    <button class="btn btn-primary pull-right display-block"
            data-toggle="modal"
            data-target="#dns_settings_modal"
    >
        <i class="fa fa-cog tw-mr-1"></i>
        <?= _l('dns_settings') ?>
        <i class="fa fa-angle-right tw-ml-1"></i>
    </button>

    <div class="clearfix"></div>
</div>

<div class="panel_s <?= (!empty($action) ? '' : 'hide') ?>">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l($action) . ' ' . _l('custom_domain') . ' ' . _l('requests'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <?php
        $links = all_company_url($company_info->domain);
        $current_domain = $links['custom_domain'] ?? '';
        if (empty($current_domain)) {
            $current_domain = $links['subdomain'];
        }
        if (empty($current_domain)) {
            $current_domain = $links['url'];
        }
        // get http or https from current domain
        $http_or_https = 'http';
        $url = prep_url($current_domain);
        if (str_starts_with($url, 'http://') && is_https())
            $http_or_https = 'https';
        if (str_starts_with($url, 'http://') && !is_https())
            $http_or_https = 'http';
        if (str_starts_with($url, 'https://') && is_https())
            $http_or_https = 'https';
        if (str_starts_with($url, 'https://') && !is_https())
            $http_or_https = 'http';

        $current_domain = str_replace(['http://', 'https://'], '', $current_domain);
        // remove last slash
        if (str_ends_with($current_domain, '/')) {
            $current_domain = substr($current_domain, 0, -1);
        }
        $custom_domain = '';
        if (!empty($domain_info))
            $custom_domain = $domain_info->custom_domain;


        ?>
        <div class="col-md-6">
            <div class="form-group">
                <label for="custom_domain">
                    <?php echo _l('current_domain'); ?>
                </label>
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $http_or_https; ?>://</span>
                    <input type="text" class="form-control"
                           readonly
                           value="<?php echo $current_domain; ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php echo form_open(site_url($c_url . 'custom_domain/update/' . $id), ['id' => 'custom_domain_form']); ?>
            <div class="form-group">
                <label for="custom_domain">
                    <?php echo _l('custom_domain'); ?>
                </label>
                <div class="input-group">
                    <span class="input-group-addon">https://</span>
                    <input type="text" class="form-control" id="custom_domain" name="custom_domain"
                           placeholder="Enter custom domain" value="<?php echo $custom_domain; ?>">
                    <span class="input-group-btn">
                <button class="btn btn-primary" type="submit" id="custom_domain_btn"><?= _l('update') ?></button>
            </span>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</div>

<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('custom_domain') . ' ' . _l('requests'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('custom_domain') ?></th>
                    <th><?= _l('request_date') ?></th>
                    <th><?= _l('status') ?></th>
                    <th><?= _l('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($all_domain)) {
                    foreach ($all_domain as $domain) {
                        $status = '<span class="label label-warning">' . _l('pending') . '</span>';
                        if ($domain->status == 'approved') {
                            $status = '<span class="label label-success">' . _l('approved') . '</span>';
                        } elseif ($domain->status == 'rejected') {
                            $status = '<span class="label label-danger">' . _l('rejected') . '</span>';
                        }
                        ?>
                        <tr>
                            <td><?php echo $domain->custom_domain; ?></td>
                            <td><?php echo _dt($domain->request_date); ?></td>
                            <td>
                                <?php
                                echo $status;
                                ?>
                            </td>
                            <td>

                                <?php if ($domain->status == 'pending') { ?>
                                    <a href="<?php echo site_url($c_url . 'custom_domain/edit/' . $domain->request_id); ?>"
                                       class="btn btn-success btn-xs">
                                        <i class="fa fa-edit"></i>
                                        <?php echo _l('edit') ?>
                                    </a>
                                <?php } ?>

                                <a href="<?php echo site_url($c_url . 'custom_domain/delete/' . $domain->request_id); ?>"
                                   class="btn btn-danger btn-xs _delete">
                                    <i class="fa fa-remove"></i>
                                    <?php echo _l('delete'); ?>
                                </a>


                            </td>
                        </tr>

                    <?php }
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="dns_settings_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo ConfigItems('custom_domain_title'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="sm:tw-col-span-2 project-overview-description tc-content">
                    <dd class="tw-mt-1 tw-space-y-5 tw-text-sm tw-text-neutral-900">
                        <?php
                        $details = ConfigItems('custom_domain_details');
                        echo check_for_links($details);
                        ?>
                    </dd>
                </div>
            </div>

            <div class="col-md-12">
                <div class="tw-mt-5 ">
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
                                        <?php
                                        $ip_address = ConfigItems('custom_domain_ip_address');
                                        ?>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->