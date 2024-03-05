<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo payment_gateway_head() ?>

<script>
    let list = null;
    let base_url = "<?php echo base_url(); ?>"
</script>
<?php

echo csrf_jquery_token()
?>
<?php echo payment_gateway_scripts(); ?>

<div class="row container tw-mx-auto tw-pt-24 ">
    <div class="row">
        <div class="">
            <?php echo $subview ?>
        </div>
    </div>
</div>

<?php $this->load->view('saas/_layout_modal'); ?>
