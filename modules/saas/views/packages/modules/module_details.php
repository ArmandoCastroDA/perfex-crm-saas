<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<?php
$module_name = $this->app_modules->get($module->module_name);
$back_url = base_url('clients/get_modules');
if (!empty(subdomain())) {
    $back_url = base_url('admin/get_modules');
} else if (!empty(is_super_admin())) {
    $back_url = base_url('saas/packages/modules');
}
$module_title = (!empty($module->module_title)) ? $module->module_title : $module_name['headers']['module_name'];
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel_s">
            <div class="panel-body panel-table-full">
                <div class="">
                    <a href="<?= $back_url ?>" class="btn btn-info ">
                        <i class="fa fa-arrow-left"></i>
                        <?= _l('back') ?>
                    </a>
                    <?php
                    if (!empty(is_super_admin())) {
                        ?>
                        <a href="<?= base_url('saas/packages/set_module_price/' . $module->package_module_id) ?>"
                           class="btn btn-success ">
                            <i class="fa fa-pencil"></i>
                            <?= _l('edit') ?>
                        </a>
                        <?php
                    }
                    ?>
                    <a href="javascript:void(0)"
                       data-module="<?= $module->module_name ?>"
                       data-price="<?= $module->price ?>"
                       data-price-format="<?= display_money($module->price) ?>"
                       data-name="<?= $module_title ?>"
                       data-title="<?= $module->module_name ?>"
                       onclick="addToCart(this)"
                       class="pull-right btn btn-primary">
                        <i class="fa fa-cart-plus"></i>
                        <?= _l('add_to_cart') ?>
                    </a>
                </div>
                <h2 class="mbot20"><?= $module_title ?>
                    <span class="pull-right">
                        <?= display_money($module->price) ?>
                    </span>
                </h2>
                <div class="creative-photo tw-mb-6 ">
                    <div>
                        <?php
                        $preview_image = unserialize($module->preview_image);
                        $image_url = base_url('uploads/modules/' . $module->package_module_id) . '/';

                        if (empty($preview_image)) {
                            $preview_image = [];
                            $preview_image[] = ['file_name' => 'Image_not_available.png'];
                            $image_url = module_dir_url('saas/uploads');
                        }

                        foreach ($preview_image as $image) {
                            ?>
                            <a data-fancybox="gallery"
                               style="display: <?= $image['file_name'] == $preview_image[0]['file_name'] ? 'block' : 'none' ?>"
                               href="<?= $image_url . $image['file_name'] ?>">
                                <img class="tw-w-full"
                                     src="<?= $image_url . $image['file_name'] ?>"
                                     width="100%" height="auto"
                                     alt=""/>
                            </a>
                            <?php
                        }

                        ?>
                    </div>
                </div>
                <div class="p-description">
                    <h3><?= _l('description') ?></h3>
                    <p><?= $description = ($module->descriptions);
                        check_for_links($description); ?></p>
                    <?php
                    if (!empty($module->preview_video_url)) {
                        $preview_video_url = $module->preview_video_url;
                        // check is youtube video or not
                        $is_youtube = strpos($module->preview_video_url, 'youtube');
                        if (empty($is_youtube)) {
                            $is_youtube = strpos($module->preview_video_url, 'youtu.be');
                        }
                        if ($is_youtube) {
                            // check is embed url or not
                            $is_embed = strpos($module->preview_video_url, 'embed');
                            if (!$is_embed) {
                                $video_id = explode('=', $module->preview_video_url);
                                $video_id = end($video_id);
                                $module->preview_video_url = 'https://www.youtube.com/embed/' . $video_id;
                            }
                        }

                        ?>
                        <h3><?= _l('video') ?></h3>
                        <div class="creative-photo tw-mb-6 ">
                            <p>
                                <a href="<?= $preview_video_url ?>"
                                   target="_blank">
                                    <?= _l('video_preview_description', $module->preview_video_url) ?>
                                </a>

                            </p>
                            <div>
                                <iframe class="" width="100%" height="315"
                                        src="<?= $module->preview_video_url ?>"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (empty(is_super_admin())) {
    $this->load->view('packages/modules/cart_module');
}

?>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    Fancybox.bind('[data-fancybox="gallery"]', {
        //
    });
</script>
