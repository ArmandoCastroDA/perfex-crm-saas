<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_saas_head(); ?>
<?php
echo '<link href="' . module_dir_url(SaaS_MODULE, 'assets/css/style_media.css') . '"  rel="stylesheet" type="text/css" />';
if (empty($table)) {
    ?>
    <?php init_tail(); ?>
    <?php
}
?>
<div id="wrapper">
    <div class="content">
        <?php echo $subview ?>
    </div>
</div>
<link rel="stylesheet"
      href="<?php echo module_dir_url(SaaS_MODULE, 'assets/plugins/jasny-bootstrap/jasny-bootstrap.min.css'); ?>">
<script src="<?php echo module_dir_url(SaaS_MODULE, 'assets/plugins/jasny-bootstrap/jasny-bootstrap.min.js'); ?>"></script>
<?php
if (!empty($table)) {
    ?>
    <?php init_tail(); ?>

    <script type="text/javascript">
        (function () {
            "use strict";
            initDataTable('#DataTables', list);
        })(jQuery);
    </script>
    <?php
}
?>
<?php $this->load->view('saas/_layout_modal'); ?>
<?php $this->load->view('saas/_layout_modal_xl'); ?>
