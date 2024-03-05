<?php
if (!empty($invoices_to_merge)) {
    ?>
    <div class="alert alert-info">
        <div class="row">
            <div id="merge" class="col-md-6">
                <?php
                $this->load->view('packages/merge_invoice', ['invoices_to_merge' => $invoices_to_merge]);
                ?>
            </div>
        </div>
    </div>

    <?php
}
if (!empty($companyInfo)) {
    echo form_open($url . 'proceedPayment');
    echo form_hidden('companies_id', $companyInfo->companies_id);
    echo form_hidden('company_history_id', $companyInfo->company_history_id);
    $allowed_modules = $companyInfo->modules ? unserialize($companyInfo->modules) : [];
    ?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title">
                <strong><?= _l('customize_package', $companyInfo->name) ?></strong>
            </div>

        </div>
        <!-- Table -->
        <div class="panel-body">
            <div class="col-md-12 row">
                <div class="col-md-5">
                    <select name="module_select"
                            class="selectpicker  ajax-search"
                            data-width="100%"
                            data-none-selected-text="<?php echo _l('select_module'); ?>"
                            data-live-search="true">
                        <option value=""></option>
                        <?php foreach ($moduleInfo as $module) {
                            $description = $this->app_modules->get($module->module_name);
                            if (in_array($module->module_name, $allowed_modules)) {
                                continue;
                            }
                            $module_title = moduleTitle($description);
                            ?>
                            <option value="<?php echo $module->module_name; ?>"
                                    data-price="<?php echo app_format_number($module->price); ?>"
                                    data-price-input="<?php echo($module->price); ?>"
                                    data-format="<?php echo $module->module_name; ?>"
                                    data-fulltext="<?php echo($module_title); ?>"
                                    data-subtext="<?php echo strip_tags(mb_substr($module_title, 0, 400)) . '...'; ?>">
                                (<?php echo app_format_number($module->price);; ?>
                                ) <?php echo _l($description['system_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table items items-preview invoice-items-preview" data-type="invoice">
                        <thead>
                        <tr>
                            <th><?= _l('package') ?></th>
                            <th><?= _l('uses') ?></th>
                            <th><?= _l('current_limit') ?></th>
                            <th><?= _l('new_limit') ?></th>
                            <th><?= _l('total_limit') ?></th>
                            <th><?= _l('total_price') ?></th>
                        </tr>
                        </thead>
                        <tbody id="add_module">
                        <?php
                        foreach ($packageInfo as $package) {
                            if (!is_numeric($package['limit']) || $package['active'] === 'inactive' || $package['additional_price'] == null) {
                                continue;
                            }
                            ?>
                            <tr class="apply_new_limit">
                                <td><?= $package['name'] ?></td>
                                <td><?= $package['total_in_text'] ?? $package['total'] ?></td>
                                <td
                                        class="current_limit"
                                ><?= $package['limit_in_text'] ?? $package['limit'] ?></td>
                                <td>
                                    <input type="number"
                                           data-price="<?= $package['additional_price'] ?>"
                                           data-limit="<?= $package['limit'] ?>"
                                           data-format="<?= $package['for'] ?>"
                                           class="form-control new_limit" name="new_limit[<?= $package['for'] ?>]"
                                           value="">


                                    <input type="hidden"
                                           name="new_limit_price[<?= $package['for'] ?>]"
                                           value="<?= $package['additional_price'] ?>">

                                    <input type="hidden" class="total_price_input" value="0">


                                    <small class="text-muted"><?=
                                        ($package['for'] === 'disk_space' ? _l('additional_price_new_disk_space', $package['name']) : _l('additional_price_new_limit', $package['name'])) . ' <strong>' . $package['additional_price'] ?></strong></small>
                                </td>
                                <td class="total_limit"><?= $package['limit_in_text'] ?? $package['limit'] ?></td>
                                <td class="total_price">
                                    <?= 0 ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mtop40">
                <p class="tw-mb-2.5 tw-font-medium"><?= _l('select') . ' ' . _l('payment_method') ?></p>
                <?php
                foreach ($payment_modes as $mode) {
                    if (!is_numeric($mode['id']) && !empty($mode['id'])) {
                        if (!is_payment_mode_allowed_for_saas($mode['id'])) {
                            continue;
                        }
                        ?>
                        <div class="radio radio-success online-payment-radio">
                            <input type="radio" value="<?php echo $mode['id']; ?>"
                                   required
                                   id="pm_<?php echo $mode['id']; ?>" name="paymentmode">
                            <label for="pm_<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></label>
                        </div>
                        <?php if (!empty($mode['description'])) { ?>
                            <div class="mbot15">
                                <?php echo $mode['description']; ?>
                            </div>
                        <?php }
                    }
                } ?>

            </div>

            <div class="col-md-6 pull-right">
                <table class="table text-right tw-text-normal">
                    <tbody>
                    <tr id="subtotal">
                        <td><span
                                    class="bold tw-text-neutral-700"><?php echo _l('credit_note_subtotal'); ?>
                                                :</span>
                        </td>
                        <td class="customize_subtotal">0.00
                        </td>
                    </tr>

                    <tr>
                        <td><span class="text-danger  bold">
                            <?= _l('total') ?>
                        </span>
                        </td>
                        <td>
                            <span class="text-danger" id="total">
                            <?= 0 ?>
                        </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="btn-bottom-toolbar text-right">
        <button
                type="submit"
                class="btn-tr btn btn-primary mright5 text-right invoice-form-submit save-as-draft transaction-submit">
            <?= _l('customized_package') ?>
        </button>
    </div>
    <?php
    echo form_close();
    ?>
    <script type="text/javascript">
        // change module_select then add tr
        $(document).on('change', 'select[name="module_select"]', function () {
            let module_select = $(this);
            let module_select_val = module_select.val();
            if (module_select_val === '') {
                return;
            }
            let module_select_text = module_select.find('option:selected').data('fulltext');
            let module_select_price = module_select.find('option:selected').data('price');
            let module_select_price_input = module_select.find('option:selected').data('price-input');
            let module_select_format = module_select.find('option:selected').data('format');

            let tr = '<tr class="apply_new_limit" data-name="' + module_select_val + '">' +
                '<td>' + module_select_text + '</td>' +
                '<td>0</td>' +
                '<td class="current_limit">' + 1 + '</td>' +
                '<td>' +
                '<input type="number"' +
                'data-price="' + module_select_price + '"' +
                'data-price-input="' + module_select_price_input + '"' +
                'data-limit="' + 1 + '"' +
                'data-format="' + 1 + '"' +
                'readonly ' +
                'class="form-control" name="new_module[' + module_select_val + ']"' +
                'value="' + module_select_price_input + '">' +
                '<small class="text-muted">' +
                (module_select_format === 'disk_space' ? '<?= _l('additional_price_new_disk_space') ?>' : '<?= _l('additional_price_new_limit_js') ?>') + ' <strong>' + module_select_text + '</strong> is <strong>' + module_select_price + '</strong></small>' +
                '<input type="hidden" class="total_price_input"  value="' + module_select_price_input + '">' +
                '<input type="hidden" ' +
                'name="new_module_name[' + module_select_val + ']"' +
                ' value="' + module_select_text + '">' +
                '</td>' +
                '<td class="total_limit">' + 1 + '</td>' +
                // total price with remove button
                '<td class="total_price">' + module_select_price + '<span class="pull-right"><a href="#" class="btn btn-danger btn-xs remove_module"><i class="fa fa-times"></i></a></span></td>' +
                '</tr>';

            let tbody = $('#add_module');
            // set module_select_val to empty
            module_select.val('');
            module_select.selectpicker('refresh');
            // check if tr.apply_new_limit data-name is already exist then return
            if (tbody.find('tr.apply_new_limit[data-name="' + module_select_val + '"]').length > 0) {
                // mark the module_select_val as danger
                tbody.find('tr.apply_new_limit[data-name="' + module_select_val + '"]').addClass('bg-danger');
                setTimeout(function () {
                    tbody.find('tr.apply_new_limit[data-name="' + module_select_val + '"]').removeClass('bg-danger');
                }, 1000);

                return;
            }
            tbody.append(tr);

            calculateTotal();


        });

        // remove_module click then remove tr.apply_new_limit and add module_select_val to module_select
        $(document).on('click', 'a.remove_module', function () {
            // remove tr.apply_new_limit
            $(this).closest('tr').remove();
            calculateTotal();
        });


        // change tr.apply_new_limit .new_limit then update price total_limit and total_price in tr.apply_new_limit
        $(document).on('change', 'tr.apply_new_limit .new_limit', function () {
            let new_limit = $(this).val();
            let price = $(this).data('price');
            let limit = $(this).data('limit');
            let format = $(this).data('format');
            let total_limit = $(this).closest('tr').find('.total_limit');
            let total_price = $(this).closest('tr').find('.total_price');
            let total_price_input = $(this).closest('tr').find('.total_price_input');
            total_price_input.val(new_limit * price);

            let total = new_limit * price;
            if (format === 'disk_space') { // new_limit =1 MB
                new_limit = convertSize(new_limit * 1024 * 1024 + parseInt(limit));
            } else {
                new_limit = parseInt(new_limit) + parseInt(limit);
            }
            // sum new_limit and limit
            total_limit.html(new_limit);
            total_price.html(total);
            calculateTotal();
        });

        // tr.discount_area .input-discount-percent then according to discount-total-type calculate discount-total and update total
        $(document).on('change', '.customize_input-discount-percent', function () {
            calculateTotal();
        });
        // discount-total-type-dropdown click then update discount-total-type-selected
        $(document).on('click', '#discount-total-type-dropdown li a', function () {
            let discount_total_type_selected = $(this).html();
            $('.customize_discount-total-type-selected').html(discount_total_type_selected);
            calculateTotal();
        });


        function convertSize(bytes, decimalPoint) {
            if (bytes === 0)
                return '0 Bytes';
            var k = 1024,
                dm = decimalPoint || 2,
                sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function calculateTotal() {
            let discount_percent = $('.customize_input-discount-percent').val() ?? 0;

            // discount-total-type-selected
            let discount_total_type_selected = $('.customize_discount-total-type-selected').html() || '%';
            // remove whitespace from discount_total_type_selected
            discount_total_type_selected = discount_total_type_selected.replace(/\s/g, '').trim();
            let discountTotal;
            let total = 0;
            $('.total_price_input').each(function () {
                total += parseFloat($(this).val());
            });

            let customize_subtotal = $('.customize_subtotal');
            customize_subtotal.html(total);

            if (discount_total_type_selected === '%') {
                discount_percent = $('.customize_input-discount-percent').val() || 0;
                discountTotal = (total * discount_percent) / 100;
            } else {
                discountTotal = $('.customize_input-discount-percent').val() || 0;
            }
            total = total - discountTotal;
            $('.customize_discount-total').html(discountTotal);

            $('#total').html(total);
            // add hidden input for total
            $('#total').append('<input type="hidden" name="total" value="' + total + '">');
            // add hidden input for discount_total_type_selected
            $('#total').append('<input type="hidden" name="discount_total_type_selected" value="' + discount_total_type_selected.trim() + '">');
            // add hidden input for discount_percent
            $('#total').append('<input type="hidden" name="discount_percent" value="' + discount_percent + '">');
            // add hidden input for subtotal
            $('#total').append('<input type="hidden" name="subtotal" value="' + customize_subtotal.html().trim() + '">');
        }
    </script>
<?php } ?>
