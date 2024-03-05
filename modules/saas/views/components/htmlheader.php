<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description"
          content="attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <meta name="keywords"
          content="	attendance, client management, finance, freelance, freelancer, goal tracking, Income Managment, lead management, payroll, project management, project manager, support ticket, task management, timecard">
    <title><?php echo $title; ?></title>
    <?php if (config_item('favicon') != '') : ?>
        <link rel="icon" href="<?php echo base_url() . config_item('favicon'); ?>" type="image/png">
    <?php else : ?>
        <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/png">
    <?php endif; ?>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/simple-line-icons/css/simple-line-icons.css">
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/animate.css/animate.min.css">
    <!-- =============== PAGE VENDOR STYLES ===============-->

    <!-- =============== APP STYLES ===============-->
    <?php $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }

    ?>
    <?php
    if (!empty($RTL)) {
        ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css" id="bscss">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app-rtl.min.css" id="maincss">
    <?php } else {
        ?>
        <!-- =============== BOOTSTRAP STYLES ===============-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" id="bscss">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css" id="maincss">
    <?php }
    $custom_color = config_item('saas_active_custom_color');
    if (!empty($custom_color) && $custom_color == 1) {
        include_once 'assets/css/bg-custom.php';
    } else {
        ?>
        <link id="autoloaded-stylesheet" rel="stylesheet"
              href="<?php echo base_url(); ?>assets/css/<?= config_item('saas_sidebar_theme') ?>.css">
    <?php }
    ?>


    <!-- SELECT2-->

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.min.css">
    <?php
    if (empty(config_item('enable_chat_pro'))) {
        ?>
        <link href="<?php echo base_url() ?>assets/plugins/chat/chat.min.css" rel="stylesheet">
    <?php }
    ?>
    <!-- Datepicker-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/timepicker.min.css">

    <!-- Toastr-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
    <!-- Data Table  CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.colVis.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dataTables/css/responsive.dataTables.min.css">
    <!-- summernote Editor -->

    <link href="<?php echo base_url(); ?>assets/plugins/summernote/summernote.min.css" rel="stylesheet" type="text/css">

    <!-- bootstrap-slider -->
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-slider/bootstrap-slider.min.css" rel="stylesheet">
    <!-- chartist -->
    <link href="<?php echo base_url() ?>assets/plugins/morris/morris.min.css" rel="stylesheet">

    <?= $this->module->load_css(); ?>

    <!--- bootstrap-select ---->
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/chat/chat.min.css" rel="stylesheet">

    <!-- JQUERY-->
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>

    <link href="<?php echo base_url() ?>asset/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="<?php echo base_url() ?>asset/js/bootstrap-toggle.min.js"></script>


    <?php
    if (empty($unread_notifications)) {
        $unread_notifications = 0;
    }
    ?>
    <script>
        var total_unread_notifications = <?php echo $unread_notifications; ?>,
            autocheck_notifications_timer_id = 0,
            list = null,
            bulk_url = null,
            time_format = <?= (config_item('time_format') == 'H:i' ? 'false' : true) ?>,
            ttable = null,
            base_url = "<?php echo base_url(); ?>",
            new_notification = "<?php lang('new_notification'); ?>",
            credit_amount_bigger_then_remaining_credit = "<?= _l('credit_amount_bigger_then_remaining_credit'); ?>",
            credit_amount_bigger_then_invoice_due = "<?= _l('credit_amount_bigger_then_due_amount'); ?>",
            auto_check_for_new_notifications = <?php echo config_item('auto_check_for_new_notifications'); ?>,
            file_upload_instruction = "<?php echo lang('file_upload_instruction_js'); ?>",
            filename_too_long = "<?php echo lang('filename_too_long'); ?>";
        desktop_notifications = "<?php echo config_item('desktop_notifications'); ?>";
        lsetting = "<?php echo lang('settings'); ?>";
        lfull_conversation = "<?php echo lang('full_conversation'); ?>";
        ledit_name = "<?php echo lang('edit') . ' ' . _l('name') ?>";
        ldelete_conversation = "<?php echo lang('delete_conversation') ?>";
        lminimize = "<?php echo lang('minimize') ?>";
        lclose = "<?php echo lang('close') ?>";
        lnew = "<?php echo lang('new') ?>";
        ldelete_confirm = "<?php echo lang('delete_alert') ?>";
        no_result_found = "<?php echo lang('no_result_found') ?>";
        searchType = "<?php echo $this->uri->segment(2); ?>";
        available_tags = <?php echo json_encode(available_tags()); ?>;
    </script>

</head>