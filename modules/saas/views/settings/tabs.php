<div class="row mt-lg">
    <?php echo form_open_multipart(
        (!isset($update_url)
            ? 'saas/settings/updateOption'
            : $update_url),
        ['id' => 'settings-form', 'class' => isset($tab['update_url']) ? 'custom-update-url' : '']
    );
    ?>
    <div class="col-sm-3">
        <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
            <?= _l('settings') ?>
        </h4>
        <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
            <?php

            if (!empty($all_tabs)) {
                foreach ($all_tabs as $key => $v_tab) {
                    ?>
                    <li class="<?php
                    if ($active == $key) {
                        echo 'active';
                    }
                    ?>">
                        <a href="<?= base_url($v_tab['url']) ?>">
                            <?php if (!empty($v_tab['icon'])) { ?>
                                <i class="<?= $v_tab['icon'] ?> menu-icon"></i>
                            <?php } ?>
                            <?= _l($v_tab['name']) ?>
                            <strong class="pull-right">
                                <?php
                                if (!empty($v_tab['count'])) {
                                    echo '<span class="label label-inverse">' . $v_tab['count'] . '</span>';
                                }
                                ?>
                            </strong>
                        </a>
                    </li>
                    <?php
                }
            }
            ?>

        </ul>
        <div class="btn-bottom-toolbar text-right">
            <button type="submit" class="btn btn-primary">
                <?php echo _l('settings_save'); ?>
            </button>
        </div>
    </div>

    <div class="col-sm-9">
        <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
            <?php
            if (!empty($active)) {
                echo _l($all_tabs[$active]['name']);
            }
            ?>
        </h4>
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <div class="panel_s">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('debug')) {
                        ?>
                        <div class="col-lg-12 tw-mb-2.5">
                            <div class="alert alert-warning">
                                <?php echo $this->session->flashdata('debug'); ?>
                            </div>
                        </div>
                        <?php
                    } ?>
                    <?php
                    $view = tab_load_view($all_tabs, $active);
                    $this->load->view($view);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>