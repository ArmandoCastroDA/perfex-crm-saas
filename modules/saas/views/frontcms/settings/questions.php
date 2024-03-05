<div class="row">
    <div class="col-lg-12">

        <?php
        if (!empty($questions_info)) {
            $id = $questions_info->heading_id;
        } else {
            $id = '';
        }
        echo form_open_multipart(base_url() . 'saas/frontcms/settings/save_questions/' . $id, array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal'));
        ?>
        <section class="panel panel-custom">
            <header class="panel-heading"><?= _l('questions') ?></header>
            <div class="panel-body pb0">
                <div class="form-group clearfix">
                    <label for="" class="col-lg-3 control-label"><?= _l('title'); ?> <span
                                class="required">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" value="<?= !empty($questions_info->title) ? $questions_info->title : '' ?>"
                               name="title" class="form-control" required>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="" class="col-lg-3 control-label"><?= _l('name'); ?> <span
                                class="required">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" value="<?= !empty($questions_info->name) ? $questions_info->name : '' ?>"
                               name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="" class="col-lg-3 control-label"><?= _l('links'); ?> <span
                                class="required">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" value="<?= !empty($questions_info->links) ? $questions_info->links : '' ?>"
                               name="links" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-lg-3 control-label"><?= _l('description'); ?></label>
                    <div class="col-lg-6">

                        <textarea name="description" class="form-control tinymce"
                                  rows="3"><?= $questions_info->description ? $questions_info->description : '' ?></textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"><?= _l('save') ?></button>
                    </div>
                </div>
            </div>
        </section>
        <?php echo form_close(); ?>
    </div>
</div>