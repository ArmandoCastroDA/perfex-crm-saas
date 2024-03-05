<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        echo '<link href="' . module_dir_url(SaaS_MODULE, 'assets/css/style_media.css') . '"  rel="stylesheet" type="text/css" />';
        $themes = array();
        $form_url = 'saas/themebuilder/updateBuilder';
        $name = 'saas_default_theme';
        $dir = 'themes/';
        if (!empty(is_client_logged_in()) || !empty(subdomain())) {
            if (!empty(subdomain())) {
                $form_url = 'admin/themebuilder/updateBuilder';
                $subs = get_company_subscription(null, 'running');
            } else {
                $form_url = 'clients/themebuilder/updateBuilder';
                $subs = get_company_subscription_by_id(null, 'running');
            }

            $allowed_themes = (!empty($subs->allowed_themes) ? unserialize($subs->allowed_themes) : array());
            if (count($allowed_themes) > 0) {
                $themes = $allowed_themes;
            }
            $dir = $subs->domain . '/';
            $name = 'default_theme';
        } else {
            $themes = get_theme_list();
            // add default theme to the list
            array_unshift($themes, 'default');
        }
        echo form_open_multipart(base_url($form_url), array('role' => 'form', 'data-parsley-validate' => '', 'novalidate' => '', 'class' => 'form-horizontal'));

        $default_theme = get_option($name);
        $url = base_url('preview/' . $dir . $default_theme . '/index.html');
        if ($default_theme == 'default') {
            $url = base_url('');
        }
        ?>
        <section class="panel panel-custom">
            <header class="panel-heading">
                <h4 class="">
                    <?= _l('upload_theme') ?>
                    <button type="submit" class="btn btn-sm btn-primary mt-sm pull-right row tw-ml-3">
                        <?= _l('save_changes') ?>
                    </button>
                </h4>
            </header>
            <div class="panel-body pb-sm">
                <?php
                if (empty(is_client_logged_in()) && empty(subdomain())) {
                    ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('upload_theme') ?></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="file" name="theme_zip" class="form-control">
                                <span class="input-group-addon">
                                <a href="https://docs.coderitems.com/perfectsaas/#how_upload_theme" target="_blank">
                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                       title="<?= _l('upload_theme_help') ?>"></i>
                                </a>
                            </span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center"><?= _l('available_themes') ?></h4>
                        <hr>
                        <?php
                        $preview_url = base_url('uploads/themebuilder/themes/');
                        foreach ($themes as $key => $theme) {
                            $themeName = basename($theme);
                            $active = '';
                            if ($themeName == $default_theme) {
                                $active = 'active';
                            } elseif ($themeName == 'default') {
                                $active = 'active';
                            }
                            // check the image exist or not into uploads/themebuilder/themes $themeName/preview.png
                            $theme_image = FCPATH . 'uploads/themebuilder/themes/' . $themeName . '/preview.png';
                            if (!file_exists($theme_image)) {
                                if ($themeName == 'default') {
                                    $theme_image = module_dir_url('saas/uploads/default_theme.png');
                                } else {
                                    $theme_image = module_dir_url('saas/uploads/Image_not_available.png');
                                }
                                // remove last slash from url if exist
                                $theme_image = rtrim($theme_image, '/');
                            } else {
                                $theme_image = base_url() . 'uploads/themebuilder/themes/' . $themeName . '/preview.png';
                            }
                            $url = $preview_url . $themeName . '/index.html';
                            if ($themeName == 'default') {
                                $url = base_url();
                            }

                            ?>
                            <div class="col-md-4 ">
                                <div class="theme tw-rounded-lg tw-cursor-pointer tw-group tw-relative tw-mb-5 tw-shadow <?= $active ?>">
                                    <img class="tw-w-full tw-rounded-lg" src="<?= $theme_image ?>"
                                         alt="">
                                    <div class="tw-group-hover tw-rounded-lg theme-overlay tw-absolute tw-top-0 tw-left-0 tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-bg-neutral-900 tw-bg-opacity-50">
                                        <a href="<?= $url ?>" target="_blank"
                                           class="btn btn-sm btn-primary">
                                            <?= _l('preview') ?>
                                        </a>
                                        <input class="tw-hidden" type="radio" name="<?= $name ?>"
                                               value="<?= $themeName ?>"
                                            <?php if ($themeName == $default_theme) {
                                                echo 'checked';
                                            } ?>>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </section>
        <?php echo form_close(); ?>
        <!-- End Form -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $('body').on('change', '#theme_view', function () {
            const layout = $(this).val();
            let url = '<?= base_url('preview/' . $dir) ?>' + layout + '/index.html';
            if (layout == 'default') {
                url = '<?= base_url('') ?>'
            }
            // add value to invoice_layout_preview preview
            $('#theme_view_preview').attr('href', url);
        });
        const themes = document.querySelectorAll('.theme');
        themes.forEach(theme => {
            theme.addEventListener('click', () => {
                themes.forEach((item) => item.classList.remove("active"));

                theme.classList.add("active");
                // check the input checked or not inside the theme div
                const input = theme.querySelector('input');
                input.checked = true;

            });
        });
    });
</script>