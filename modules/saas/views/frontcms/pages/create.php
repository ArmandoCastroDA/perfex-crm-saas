<?php

if (isset($page_info)) {
    $pages_id = $page_info->pages_id;
} else {
    $pages_id = '';
}
echo form_open(base_url('saas/frontcms/page/save_pages/' . $pages_id), array('id' => 'new_pages_form', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><?php echo _l('new') . ' ' . _l('pages'); ?>

        </div>
        <span class="text-muted ">
            we are using <a target="_blank" href="https://shreethemes.in/landrick/landing/index.html">
                Landrick</a> theme for this frontcms.
        </span>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label "><?= _l('title') ?> <span
                        class="text-danger">*</span></label>
            <div class="">
                <input type="text" class="form-control" value="<?php
                if (!empty($page_info)) {
                    echo html_escape($page_info->title);
                }
                ?>" name="title" required="">
                <?php
                if (!empty($page_info)) {
                    ?>
                    <input type="hidden" class="form-control" value="<?php
                    if (!empty($page_info)) {
                        echo html_escape($page_info->slug);
                    }
                    ?>" name="slug" required="">
                <?php } ?>
            </div>
        </div>
        <input type="hidden" name="content_category" value="standard">
        <div class="form-group">
            <label class="control-label"> <?= _l('description') ?> <span class="text-danger">*</span></label>
            <div class="pull-right hidden-print">
                <a href="<?= base_url() ?>saas/frontcms/page/add_image" class="btn btn-xs btn-primary"
                   data-toggle="modal" data-placement="top" data-target="#myModal_xl">
                    <i class="fa fa-plus "></i> <?= ' ' . _l('add') . ' ' . _l('media') ?></a>
            </div>
            <textarea name="description"
                      required="required"
                      class="tinymce"
            ><?= !empty($page_info->description) ? $page_info->description : ' ' ?></textarea>
        </div>

        <div class="btn-bottom-toolbar text-right">
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save mr-sm"></i>
                <?= _l('save') ?>
            </button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    $(document).ready(function () {
        $("#new_pages_form").appFormValidator();
    });
</script>