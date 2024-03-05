<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table class="table items items-preview invoice-items-preview" >
        <thead>
        <tr>
            <th><?= _l('name') ?></th>
            <th><?= _l('disabled') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $frequencies = get_frequency();
        $disabled = !empty(get_option('saas_disable_frequency')) ? json_decode(get_option('saas_disable_frequency')) : [];
        // if object convert to array
        if (is_object($disabled)) {
            $disabled = (array)$disabled;
        }
        foreach ($frequencies as $frequency) {
            $checked = '';
            $key = $frequency['value'];

            if (!empty($disabled) && in_array($key, $disabled)) {
                $checked = 'checked';
            }

            $sub_array = '<div class="onoffswitch"><input type="checkbox"
                    data-id="' . $key . '"
                    data-switch-url="' . admin_url() . 'saas/packages/change_frequency_status" 
    id="onoffswitch_' . $key . '" class="onoffswitch-checkbox status" ' . $checked . ' /><label for="onoffswitch_' . $key . '" class="onoffswitch-label"></label></div>';
            ?>
            <tr>
                <td><?= ($frequency['label']) ?></td>
                <td><?=
                    $frequency['name'] == 'monthly' ?
                        '<span class="label label-success">' . _l('cant_disabled') . '</span>'
                        : $sub_array ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="btn-bottom-toolbar text-right" style="visibility: visible;">
    <button type="submit" class="btn btn-primary"><?= _l('update') . ' ' . _l('frequency') ?></button>
</div>
