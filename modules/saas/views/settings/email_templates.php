<div class="col-md-12">
    <h4 class="tw-font-semibold email-template-heading">
        <?php echo _l('saas'); ?>
        <?php if ($hasPermissionEdit) { ?>
            <a href="<?php echo admin_url('emails/disable_by_type/saas'); ?>"
               class="pull-right mleft5 mright25"><small><?php echo _l('disable_all'); ?></small></a>
            <a href="<?php echo admin_url('emails/enable_by_type/saas'); ?>"
               class="pull-right"><small><?php echo _l('enable_all'); ?></small></a>
        <?php } ?>
    </h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <span class="tw-font-semibold"><?php echo _l('email_templates_table_heading_name'); ?></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($saas as $saas_email) { ?>
                <tr>
                    <td class="<?php if ($saas_email['active'] == 0) {
                        echo 'text-throught';
                    } ?>">
                        <a
                                href="<?php echo admin_url('emails/email_template/' . $saas_email['emailtemplateid']); ?>"><?php echo $saas_email['name']; ?></a>
                        <?php if (ENVIRONMENT !== 'production') { ?>
                            <br/><small><?php echo $saas_email['slug']; ?></small>
                        <?php } ?>
                        <?php if ($hasPermissionEdit) { ?>
                            <a href="<?php echo admin_url('emails/' . ($saas_email['active'] == '1' ? 'disable/' : 'enable/') . $saas_email['emailtemplateid']); ?>"
                               class="pull-right"><small><?php echo _l($saas_email['active'] == 1 ? 'disable' : 'enable'); ?></small></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <h4 class="tw-font-semibold email-template-heading">
        <?php echo _l('affiliate'); ?>
        <?php if ($hasPermissionEdit) { ?>
            <a href="<?php echo admin_url('emails/disable_by_type/affiliate'); ?>"
               class="pull-right mleft5 mright25"><small><?php echo _l('disable_all'); ?></small></a>
            <a href="<?php echo admin_url('emails/enable_by_type/affiliate'); ?>"
               class="pull-right"><small><?php echo _l('enable_all'); ?></small></a>
        <?php } ?>
    </h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <span class="tw-font-semibold"><?php echo _l('email_templates_table_heading_name'); ?></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($affiliate as $affiliate_email) { ?>
                <tr>
                    <td class="<?php if ($affiliate_email['active'] == 0) {
                        echo 'text-throught';
                    } ?>">
                        <a
                                href="<?php echo admin_url('emails/email_template/' . $affiliate_email['emailtemplateid']); ?>"><?php echo $affiliate_email['name']; ?></a>
                        <?php if (ENVIRONMENT !== 'production') { ?>
                            <br/><small><?php echo $affiliate_email['slug']; ?></small>
                        <?php } ?>
                        <?php if ($hasPermissionEdit) { ?>
                            <a href="<?php echo admin_url('emails/' . ($affiliate_email['active'] == '1' ? 'disable/' : 'enable/') . $affiliate_email['emailtemplateid']); ?>"
                               class="pull-right"><small><?php echo _l($affiliate_email['active'] == 1 ? 'disable' : 'enable'); ?></small></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>