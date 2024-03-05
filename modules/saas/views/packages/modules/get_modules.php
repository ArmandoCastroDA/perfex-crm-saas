<div class="panel_s">
    <div class="panel-body panel-table-full">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $mUrl = 'clients/';
                if (!empty(subdomain())) {
                    $mUrl = 'admin/';
                }
                if (!empty($all_modules)) {
                    foreach ($all_modules as $module) {
                        $module_name = $this->app_modules->get($module->module_name);
                        $module_title = (!empty($module->module_title)) ? $module->module_title : $module_name['headers']['module_name'];
                        // after 100 characters add ... to the description
                        $length = 350;
                        $description = strlen($module->descriptions) > $length ? substr($module->descriptions, 0, $length) . '...' : $module->descriptions;
                        $description = strip_tags($description);

                        $preview_image = '';
                        if (!empty($module->preview_image)) {
                            $preview_image = unserialize($module->preview_image);
                            $preview_image = base_url('uploads/modules/' . $module->package_module_id . '/' . $preview_image[0]['file_name']);
                        } else {
                            $preview_image = module_dir_url('saas/uploads/Image_not_available.png');
                            // remove last slash from url if exist
                            $preview_image = rtrim($preview_image, '/');
                        }


                        $url = base_url($mUrl . 'module_details/' . $module->module_name);


                        ?>
                        <div class="col-lg-4 col-lg-4">
                            <div class="product-item">
                                <div class="thumbnil-price">
                                    <img class="tw-w-full"
                                         src="<?= $preview_image ?>"
                                         alt="<?= $module_title ?>"/>
                                    <div class="label label-primary product-price">
                                        <?= display_money($module->price) ?>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <a href="<?= $url ?>" class=" product-title fz-18-b-black">
                                        <?= $module_title ?>
                                    </a>
                                    <div class="info fz-15-m-black-2 tw-py-5">
                                        <?= $description ?>
                                    </div>

                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <a href="javascript:void(0)"
                                           data-module="<?= $module->module_name ?>"
                                           data-price="<?= $module->price ?>"
                                           data-price-format="<?= display_money($module->price) ?>"
                                           data-name="<?= $module_title ?>"
                                           onclick="addToCart(this)"
                                           class="btn btn-sm btn-primary">
                                            <i class="fa fa-cart-plus"></i>
                                            <?= _l('add_to_cart') ?>
                                        </a>
                                        <a href="<?= $url ?>"
                                           class="btn btn-sm tw-text-base tw-p-5 font-weight-normal label label-primary
                                    badge-pill"><?= _l('preview') ?>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }

                ?>
            </div>
        </div>
    </div>
</div>

<?php
if (empty(is_super_admin())) {
    $this->load->view('packages/modules/cart_module');
}

?>

