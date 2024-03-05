<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Companies viewer modal -->
<div class="modal view-company-modal animated fadeIn" id="view-company-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog tw-w-full tw-h-screen tw-mt-0" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-close"></i></span></button>
                <div class="tw-flex tw-justify-end">
                    <div class="tw-flex col-md-2 col-xs-6">
                        <?php

                        if (!empty($company_info->companies_id)) {
                            $where = ['status' => 'running', 'id' => $company_info->companies_id];
                        } elseif (!empty(super_admin_access())) {
                            $where = ['status' => 'running'];
                        }
                        if (!empty($where)) {
                            $company_options = get_old_any_field('tbl_saas_companies', $where, 'id,name', true);
                            echo render_select('view-company', $company_options, ['id', ['name']], '', '0', [], [], 'tw-w-full', '', true);
                        }
                        ?>
                    </div>
                    <h4 class="modal-title"></h4>
                </div>
            </div>
            <div class="modal-body tw-m-0">
                <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center first-loader">
                    <i class="fa fa-spin fa-spinner fa-4x"></i>
                </div>
                <iframe class="tw-w-full tw-h-full" id="company-viewer">
                </iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const login_as_company_url = base_url + "saas/gb/login_as_company/";
    // Company modal view
    $(document).on("click", ".view-company", handleCompanyModalView);
    // Detect change in modal company list selector and react
    $(document).on("change", '[name="view-company"]', handleModalCompanyChange);

    function getCompanyViewerFrame() {
        return document.querySelector("#company-viewer");
    }

    function handleModalCompanyChange() {
        let company_id = $(this).val();
        if (!company_id.length) $("#view-company-modal").modal("hide");
        magicAuth(company_id);
    }

    /**
     * Handles the company modal view.
     */
    function handleCompanyModalView() {
        let company_id = $(this).data("company-id");
        let viewPane = $("#view-company-modal");
        if (viewPane.hasClass("modal")) viewPane.modal("show"); else {
            viewPane.show();
            viewPane.find(".close,.close-btn").click(function () {
                viewPane.hide();
            });
        }

        $('select[name="view-company"]')
            .selectpicker("val", company_id)
            .trigger("change");

        try {
            let iframe = getCompanyViewerFrame();
            iframe.contentWindow.set_body_small();
        } catch (error) {
            console.log(error);
        }
    }

    function magicAuth(company_id) {
        let iframe = getCompanyViewerFrame();
        iframe.src = login_as_company_url + company_id;
        iframe.onload = function () {
            $(".first-loader").hide();
        };
        iframe.contentWindow?.NProgress?.start() || $(".first-loader").show();
    }
</script>