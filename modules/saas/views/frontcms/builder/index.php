<div class="row">
    <div class="col-lg-12">
        <div class="col-md-3">
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                <?php
                $can_do = true;
                $url = 'saas/';
                if (!empty(is_client_logged_in())) {
                    $url = 'clients/';
                } elseif (!empty(subdomain())) {
                    $url = 'admin/';
                }

                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'theme_builder') ? 'active' : ''; ?>">
                        <a href="<?= base_url($url) ?>themebuilder">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('settings') ?>
                        </a>
                    </li>
                    <li class="<?php echo ($load_setting == 'builder') ? 'active' : ''; ?>">
                        <a
                                target="_blank"
                                href="<?= base_url($url) ?>themebuilder/builder">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('builder') ?>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>
        <section class="col-sm-9">
            <section class="">
                <?php $this->load->view('frontcms/builder/' . $load_setting) ?>
            </section>
        </section>
    </div>
</div>
