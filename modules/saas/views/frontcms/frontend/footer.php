</main>
<?php
if (empty($affiliate)) {
    ?>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-py-60">
                        <div class="row">
                            <?php
                            $footer_left_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'footer_left'), false) ?>

                            <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                                <a href="#" class="logo-footer">

                                    <img style="width: 200px; height:50px"
                                         src="<?php echo saas_logo() ?>"
                                         alt="">
                                </a>
                                <p class="mt-4">
                                    <?= (!empty($footer_left_info->description)) ? $footer_left_info->description : '' ?>
                                </p>
                                <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                                    <?php $footer_left_icons = get_old_result('tbl_saas_all_heading_section', array('type' => 'footer_icons'));
                                    foreach ($footer_left_icons as $key => $v_icon) {
                                        ?>

                                        <li class="list-inline-item">
                                            <a href="<?= (!empty($v_icon->links)) ? $v_icon->links : '' ?>"
                                               class="rounded"><i
                                                        data-feather="<?= (!empty($v_icon->icons)) ? $v_icon->icons : '' ?>"
                                                        class="fea icon-sm fea-social"></i></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>


                            <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head">Company</h5>
                                <ul class="list-unstyled footer-list mt-4">
                                    <?php $footer_info = get_old_result('tbl_saas_all_section_area', array('type' => 'company'));
                                    foreach ($footer_info as $key => $v_card_info) {
                                        ?>
                                        <li>
                                            <a href="<?= (!empty($v_card_info->button_link_2)) ? $v_card_info->button_link_2 : '' ?>"
                                               class="text-foot">
                                                <i class="uil uil-angle-right-b me-1"></i>
                                                <?= $v_card_info->button_name_2 ?: '' ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head">Usefull Links</h5>
                                <ul class="list-unstyled footer-list mt-4">
                                    <?php $footer_info = get_old_result('tbl_saas_all_section_area', array('type' => 'usefull_links'));
                                    foreach ($footer_info as $key => $v_card_info) {
                                        ?>
                                        <li>
                                            <a href="<?= (!empty($v_card_info->button_link_2)) ? $v_card_info->button_link_2 : '' ?>"
                                               class="text-foot"><i
                                                        class="<?= (!empty($v_card_info->icons)) ? $v_card_info->icons : '' ?> me-1"></i> <?= $v_card_info->button_name_2 ? $v_card_info->button_name_2 : '' ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php $footer_right_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'footer_right'), false) ?>

                            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head"><?= (!empty($footer_right_info->name)) ? $footer_right_info->name : '' ?></h5>
                                <p class="mt-4">
                                    <?= (!empty($footer_right_info->title)) ? $footer_right_info->title : '' ?>
                                </p>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="foot-subscribe mb-3">
                                                <label class="form-label">Write your email
                                                    <span class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" name="email" id="emailsubscribe"
                                                           class="form-control ps-5 rounded" placeholder="Your email : "
                                                           required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-grid">
                                                <a type="submit" href="" id="submitsubscribe" name="send"
                                                   class="btn btn-soft-primary">
                                                    <?= (!empty($footer_right_info->icons)) ? $footer_right_info->icons : '' ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-py-30 footer-bar">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="text-sm-start">
                            <p class="mb-0">
                                © <?= date('Y') ?>
                                <?php $footer_bottom_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'footer_bottom'), false) ?>

                                <?= (!empty($footer_bottom_info->name)) ? $footer_bottom_info->name : '' ?>
                                <i class="<?= (!empty($footer_bottom_info->icons)) ? $footer_bottom_info->icons : '' ?>"></i> <?= (!empty($footer_bottom_info->title)) ? $footer_bottom_info->title : '' ?>
                                <a href="<?= (!empty($footer_bottom_info->links)) ? $footer_bottom_info->links : '' ?>"
                                   target="_blank"
                                   class="text-reset"><?= (!empty($footer_bottom_info->description)) ? $footer_bottom_info->description : '' ?></a>.
                            </p>
                        </div>
                    </div>
                    <!--end col-->

                    <div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">

                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php } ?>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/tiny-slider.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/tobii.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/feather.min.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/switcher.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/plugins.init.js"></script>
<script src="<?= module_dir_url(SaaS_MODULE) ?>assets/js/app.js"></script>

<?php
$footer_custom_script = get_option('footer_custom_script');
if (!empty($footer_custom_script)) {
    $script = '<script type="text/javascript"> ';
    $script .= html_entity_decode($footer_custom_script);
    $script .= ' </script>';
    echo $script;
}
?>

</body>

</html>