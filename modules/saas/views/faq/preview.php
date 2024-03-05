<div class="panel panel-custom" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            <?= _l('view') . ' ' . _l('email') ?>
        </div>
    </div>

    <div class="panel-body">
        <?php
        if (!empty($email_info)) { ?>

            <p><strong>Subject: </strong><strong><?php echo $email_info->subject; ?></strong></p>
            <p><strong>Name</strong>: <?php echo $email_info->name; ?></p>
            <p><strong>Email: </strong><a
                        href="mailto:<?php echo $email_info->email; ?>"><?php echo $email_info->email; ?></a></p>
            <p><strong>Phone: </strong><a
                        href="tel::<?php echo $email_info->phone; ?>"><?php echo $email_info->phone; ?></a></p>
            <p><strong>Message: </strong></p>
            <?php echo $email_info->description; ?>


        <?php } ?>
    </div>
    <div class="modal-footer">
        <a class="btn btn-primary" href="<?php echo saas_url('faq/'); ?>">
            <i class="fa fa-arrow-left"></i>
            <?= _l('back') ?>
        </a>
    </div>
</div>