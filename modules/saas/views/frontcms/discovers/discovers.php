<section class="section" style="padding-bottom: 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php $features_creatives_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'discovers'),false) ?>

                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4"><?= $features_creatives_info->title ?></h4>

                    <p class="text-muted para-desc mb-0 mx-auto"><?= $features_creatives_info->description ?></p>
                </div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-md-5 pt-2">
                <ul class="nav nav-pills bg-white nav-justified flex-column mb-0" id="pills-tab" role="tablist">
                    <?php $discovers_tabs_info = get_old_result('tbl_saas_all_section_area', array('type' => 'discovers', 'status' => 1));
                    foreach ($discovers_tabs_info as $key => $v_tab_info) {
                        ?>
                        <li class="nav-item bg-light rounded-md mb-4">
                            <button class="nav-link rounded-md <?= $key == 0 ? 'active' : '' ?>" id="dashboard"
                                    data-bs-toggle="pill" onclick="myFunction(<?= $key ?>)" role="tab"
                                    aria-controls="dash-board" aria-selected="false">
                                <div class="p-3 text-start">
                                    <h5 class="title"><?= $v_tab_info->title ?></h5>
                                    <p class="text-muted tab-para mb-0"><?= $v_tab_info->description ?></p>
                                </div>
                            </button>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="col-md-7 col-12 mt-4 pt-2">
                <div class="tab-content ms-lg-4" id="pills-tabContent">
                    <?php foreach ($discovers_tabs_info as $key => $v_tab_info) {
                        ?>
                        <div class="tab-pane fade <?= $key == 0 ? 'show active' : '' ?>  allImage" id="<?= $key ?>"
                             role="tabpanel" aria-labelledby="dashboard">
                            <img src="<?= base_url() ?><?= $v_tab_info->image ? $v_tab_info->image : '' ?>"
                                 class="img-fluid mx-auto rounded-md shadow-lg d-block" alt="">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    'use strict';

    function myFunction(e) {
        var element = document.getElementById(e);
        document.querySelectorAll(".allImage").forEach((el) => {
            el.classList.remove("show");
            el.classList.remove("active");
            el.classList.add("hidden");
        });
        element.classList.add("show");
        element.classList.add("active");
    }
</script>