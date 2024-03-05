<?php defined('BASEPATH') or exit('No direct script access allowed');
if (count($invoices_to_merge) > 0) { ?>
    <div class="mergeable-invoices">
        <h4 class="tw-font-semibold tw-mb-4"><?php echo _l('invoices_available'); ?></h4>
        <?php foreach ($invoices_to_merge as $_inv) { ?>
            <div>
                <label for="">
                    <a href="<?php echo base_url('invoice/' . $_inv->id . '/' . $_inv->hash); ?>"
                       class="invoice-preview"
                       data-title="<?php echo format_invoice_status($_inv->status, '', false); ?>" target="_blank">
                        <?php echo format_invoice_number($_inv->id); ?>
                    </a> - <?php echo app_format_money($_inv->total, $_inv->currency_name); ?>
                </label>
            </div>
            <?php
            if ($_inv->discount_total > 0) {
                echo '<b>' . _l('invoices_merge_discount', app_format_money($_inv->discount_total, $_inv->currency_name)) . '</b><br />';
            }
            ?>
        <?php } ?>
    </div>
<?php } ?>
