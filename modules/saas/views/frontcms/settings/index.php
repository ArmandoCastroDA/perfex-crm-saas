<div class="row">
    <div class="col-lg-12">
        <div class="col-md-3">
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                <?php
                $can_do = true;
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'general') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('general_settings') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'pricing') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/pricing">
                            <i class="fa fa-credit-card menu-icon"></i>
                            <?= _l('pricing') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'footer_left') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/footer_left">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('footer_left') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'footer_middle') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/footer_middle">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('footer_middle') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'footer_left_icon') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/footer_left_icon">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('footer_left_icon') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'footer_right') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/footer_right">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('footer_right') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'footer_bottom') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/footer_bottom">
                            <i class="fa fa-fw fa-info-circle menu-icon"></i>
                            <?= _l('footer_bottom') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'contact') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/contact">
                            <i class="fa fa-phone  menu-icon"></i>
                            <?= _l('contact_info') ?>
                        </a>
                    </li>
                <?php }
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'questions') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>saas/frontcms/settings/questions">
                            <i class="fa fa-phone  menu-icon"></i>
                            <?= _l('questions') ?>
                        </a>
                    </li>
                <?php }
               
                ?>
            </ul>
        </div>
        <section class="col-sm-9">
            <section class="">
                <?php $this->load->view('frontcms/settings/' . $load_setting) ?>
            </section>
        </section>
    </div>
</div>

