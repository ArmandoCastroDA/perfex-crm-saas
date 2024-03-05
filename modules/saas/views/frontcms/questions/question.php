<?php
$questions_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'questions'),false);
?>
<section class="section">
    <div class="row  justify-content-center">
        <div class="col-12 text-center">
            <div class="section-title">
                <h4 class="title mb-4"><?= $questions_info->title ?: '' ?></h4>

                <p class="text-muted para-desc mx-auto"><?= !empty($questions_info->description) ? $questions_info->description : 'Have Question?' ?></p>
                <a class="btn btn-primary mt-4"
                   href="<?= !empty($questions_info->lings) ? $questions_info->lings : base_url('/front/contact-us') ?>"><?= $questions_info->name ?: 'Start working with SaaS that can provide everything you need to generate awareness, drive triffic, connect.' ?></a>
            </div>
        </div>
    </div>
</section>