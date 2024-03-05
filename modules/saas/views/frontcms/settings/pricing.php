<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        echo form_open(base_url() . 'saas/frontcms/settings/updateOption', array('class' => 'form-horizontal  ', 'id' => 'form'));
        ?>
        <section class="panel panel-custom">
            <header class="panel-heading"><?= _l('pricing') ?></header>
            <div class="panel-body pb-sm">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?php echo _l('buy_now_page') ?> </label>
                    <div class="col-lg-6">
                        <div
                                class="radio radio-inline radio-primary">
                            <input type="radio" name="saas_buy_now_page" id="modal"
                                   value="modal" <?php if (get_option('saas_buy_now_page') == 'modal' || get_option('saas_buy_now_page') == '') {
                                echo 'checked';
                            } ?>>
                            <label for="modal"><?= _l('modal') ?></label>
                        </div>
                        <div
                                class="radio radio-inline radio-primary ">
                            <input type="radio" name="saas_buy_now_page" id="new_page"
                                   value="new_page" <?php if (get_option('saas_buy_now_page') == 'new_page') {
                                echo 'checked';
                            } ?>>
                            <label for="new_page"><?= _l('new_page') ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?php echo _l('title') ?> </label>

                    <div class="col-lg-6">
                        <input type="text" value="<?php if (get_option('saas_front_pricing_title') != '') {
                            echo get_option('saas_front_pricing_title');
                        } ?>" name="saas_front_pricing_title" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?php echo _l('description') ?> </label>

                    <div class="col-lg-6">
                            <textarea name="saas_front_pricing_description" class="form-control tinymce"
                                      rows="3"><?php if (get_option('saas_front_pricing_description') != '') {
                                    echo get_option('saas_front_pricing_description');
                                } ?></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"><?= _l('save') ?></button>
                    </div>
                </div>
            </div>
        </section>
        <?php echo form_close(); ?>
    </div>
</div>
