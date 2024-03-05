<?php
echo '<link href="' . module_dir_url(SaaS_MODULE, 'assets/css/style_media.css') . '"  rel="stylesheet" type="text/css" />';
$payment_modes = $this->saas_model->get_payment_modes();
?>
<a href="javascript:void(0)" class="switcher-btn  z-index-1 tw-flex tw-items-center tw-justify-center"
   onclick="switcherToggle()"
>
    <i class="fa fa-shopping-cart"></i>
    <span class="cart-count" id="cart-count">0</span>
</a>
<?php
if (!empty(subdomain())) {
    $companyInfo = get_company_subscription();
    $m_url = 'admin/';
} else {
    $companyInfo = get_company_subscription_by_id();
    $m_url = 'clients/';
}


if (!empty($companyInfo)) {
    echo form_open('proceedPayment');
    echo form_hidden('companies_id', $companyInfo->companies_id);
    echo form_hidden('company_history_id', $companyInfo->company_history_id);
    ?>
    <div class="offcanvas offcanvas-end shadow border-0 mt-3" tabindex="-1" id="switcher-sidebar"
         aria-labelledby="offcanvasLeftLabel" aria-modal="true" role="dialog">
        <div class="panel">
            <div class="panel-heading border-bottom bg-white ">
                <h5><i class="fa fa-shopping-cart"></i>
                    <?= _l('cart') ?>
                    <a href="javascript:void(0)" class="pull-right text-dark" onclick="switcherToggle()">
                        <i class="fa fa-times"></i>
                    </a>
                </h5>
            </div>
            <div class="panel-body ">
                <div class=" bb home-activity ">
                    <ul id="card-body" class="tab-content">

                    </ul>
                </div>
                <div class="tw-border-t tw-border-gray-200 tw-py-6 ">
                    <div class="tw-flex tw-justify-between items-center">
                        <h3 class="mt-0"><?= _l('total') ?></h3>
                        <h3 class="mt-0">
                            <span><?= get_base_currency()->symbol ?></span>
                            <span id="total">0</span>
                        </h3>
                    </div>
                    <div class="tw-flex tw-justify-between items-center">
                        <?php
                        foreach ($payment_modes as $mode) {
                            if (!is_numeric($mode['id']) && !empty($mode['id'])) {
                                if (!is_payment_mode_allowed_for_saas($mode['id'])) {
                                    continue;
                                }
                                ?>
                                <select name="paymentmode" class="form-control" required>
                                    <option value=""><?php echo _l('select') . ' ' . _l('payment_method'); ?></option>
                                    <?php foreach ($payment_modes as $mode) {
                                        if (!is_numeric($mode['id']) && !empty($mode['id'])) {
                                            if (!is_payment_mode_allowed_for_saas($mode['id'])) {
                                                continue;
                                            }
                                            ?>
                                            <option value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <?php
                            }
                        } ?>
                    </div>
                    <div class="tw-flex tw-justify-between tw-mt-4">
                        <a class="btn btn-danger" href="javascript:void(0)"
                           onclick="localStorage.removeItem('modules');show_cart();">
                            <span class="tw-ml-2"><?= _l('empty_cart') ?></span>
                        </a>
                        <button
                                type="submit"
                                class="btn-tr btn btn-primary mright5 text-right invoice-form-submit save-as-draft transaction-submit">
                            <?= _l('checkout') ?>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="hide_offcanvas"
             onclick="switcherToggle()">
            <i class="fa fa-chevron-right"></i>
        </div>
    </div>
    <?php
    echo form_close();
}
?>
<script type="text/javascript">

    // onload show cart
    $(document).ready(function () {
        show_cart();
    });


    function switcherToggle() {
        let switcher = $('#switcher-sidebar');
        // if have class show then add remove class show with animation
        if (switcher.hasClass('show')) {
            switcher.addClass('hiding').removeClass('show');
            setTimeout(function () {
                switcher.removeClass('hiding');
            }, 300);
        } else {
            switcher.addClass('show');
        }
    }


    function addToCart(event) {
        const module = $(event).data('module');
        const price = $(event).data('price');
        const price_format = $(event).data('price-format');
        const name = $(event).data('name');
        const data = {
            module: module,
            price: price,
            name: name,
            price_format: price_format
        };

        let arr = [];
        // check if module already exist in local storage or not
        if (localStorage.getItem('modules')) {
            arr = JSON.parse(localStorage.getItem('modules'));
            // check if module already exist in local storage or not
            if (arr.some(e => e.module === module)) {
                // open
                switcherToggle();
                // add class to #module-<module_name>
                $('#module-' + module).addClass('tw-border tw-border-primary-600');
                alert_float('warning', '<?= _l('module_already_exist_in_cart') ?>');
                return;
            }
        }
        arr.push(data);

        localStorage.setItem('modules', JSON.stringify(arr));

        show_cart();
    }

    function show_cart() {
        let arr = [];
        if (localStorage.getItem('modules')) {
            arr = JSON.parse(localStorage.getItem('modules'));
        }
        $('#cart-count').html(arr.length);
        let html = '';
        let total = 0;
        if (arr.length > 0) {
            arr.forEach(function (item) {
                total += parseFloat(item.price);
                html += `<li class="tw-flex tw-py-3 bb " id="module-${item.module}">
                        <div class="tw-flex tw-flex-1 tw-flex-col">
                            <div class="tw-flex tw-justify-between tw-text-base tw-font-medium tw-text-gray-900">
                                <h3 class="tw-m-0 tw-text-base">
                                    <a href="<?= base_url('clients/module_details/') ?>${item.module}">
                                        ${item.name}
                                    </a>
                                </h3>
                                <p class="tw-m-0 tw-ml-4">${item.price_format}</p>
                            </div>
                            <input type="hidden" name="new_module[${item.module}]" value="${item.price}">
                            <input type="hidden" name="new_module_name[${item.module}]" value="${item.name}">


                            <div class="tw-flex tw-flex-1 tw-items-end tw-justify-between tw-text-sm">
                                <p class="text-gray-500 ">Qty 1</p>
                                <button type="button" onclick="removeFromCart(this)"
                                        data-module="${item.module}"
                                        class="tw-text-base font-weight-normal label label-danger badge-pill">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </div>
                        </div>
                    </li>`;
            });
        } else {
            html = `<li class="tw-flex tw-py-3 bb ">
                        <div class="tw-flex tw-flex-1 tw-flex-col">
                            <div class="tw-flex tw-justify-between tw-text-base tw-font-medium tw-text-gray-900">
                                <h3 class="tw-m-0 tw-text-base">
                                    <?= _l('cart_is_empty') ?>
                                </h3>
                            </div>
                        </div>
                    </li>`;
        }
        // add total in input field
        html += `<input type="hidden" name="total" value="${total}">`;
        html += `<input type="hidden" name="subtotal" value="${total}">`;
        html += `<input type="hidden" name="discount_total_type_selected" value="%">`;
        html += `<input type="hidden" name="discount_percent" value="0">`;

        $('#card-body').html(html);
        $('#total').html(total);

    }

    function removeFromCart(event) {
        const module = $(event).data('module');
        let arr = [];
        if (localStorage.getItem('modules')) {
            arr = JSON.parse(localStorage.getItem('modules'));
        }
        arr = arr.filter(function (item) {
            return item.module !== module;
        });
        localStorage.setItem('modules', JSON.stringify(arr));
        show_cart();
    }

</script>