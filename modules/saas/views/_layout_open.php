<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
echo '<link href="' . module_dir_url(SaaS_MODULE, 'assets/css/style_media.css') . '"  rel="stylesheet" type="text/css" />';
init_head(); ?>
<?php init_tail(); ?>

<script>
    let list = null;
    let base_url = "<?php echo base_url(); ?>"
</script>
<div id="wrapper">
    <div class="content">
        <?php echo $subview ?>
    </div>
</div>

<?php $this->load->view('saas/_layout_modal'); ?>
