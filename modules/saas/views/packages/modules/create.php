<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel_s">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo _l('set_module_price'); ?></h4>
            </div>

            <div class="panel-body">
                <?php
                if (!empty($module_info)) {
                    $id = $module_info->package_module_id;
                } else {
                    $id = null;
                }
                echo form_open(base_url('saas/packages/update_modules/' . $id), array('enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'id' => 'module_form', 'role' => 'form')); ?>


                <div class="form-group mbot15<?= count($modules) > 0 ? ' select-placeholder' : ''; ?>">
                    <label for="allowed_modules"
                           class="control-label"><?php echo _l('select') . ' ' . _l('module'); ?>
                        <span class="text-danger">*</span>
                    </label>
                    <br/>
                    <?php if (count($modules) > 0) { ?>
                        <select class="selectpicker"
                                data-toggle="<?php echo $this->input->get('module_name'); ?>"
                                name="module_name" data-actions-box="true"
                                data-width="100%"
                                required
                                data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <?php foreach ($modules as $module) {
                                if ($module['system_name'] == 'saas') {
                                    continue;
                                }
                                $selected = '';
                                if (isset($module_info)) {
                                    if ($module_info->module_name == $module['system_name']) {
                                        $selected = ' selected';
                                    }
                                } ?>
                                <option value="<?php echo $module['system_name']; ?>" <?php echo $selected; ?>>
                                    <?php echo $module['headers']['module_name']; ?></option>
                                <?php
                            } ?>
                        </select>
                    <?php } else { ?>
                        <p class="tw-text-neutral-500">
                            <?php echo _l('modules'); ?>
                        </p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="field-1" class="control-label"><?= _l('title') ?>
                        <span class="text-danger">*</span></label>
                    <div class="">
                        <input required type="text" name="module_title"
                               placeholder="<?= _l('enter') . ' ' . _l('module') . ' ' . _l('title') ?>"
                               class="form-control" value="<?php
                        if (!empty($module_info->module_title)) {
                            echo $module_info->module_title;
                        }
                        ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="control-label"><?= _l('price') ?>
                        <span class="text-danger">*</span></label>
                    <div class="">
                        <input required type="number" name="price"
                               placeholder="<?= _l('enter') . ' ' . _l('module') . ' ' . _l('price') ?>"
                               class="form-control" value="<?php
                        if (!empty($module_info->price)) {
                            echo $module_info->price;
                        }
                        ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="control-label"><?= _l('preview_video_url') ?></label>
                    <div class="">
                        <input type="text" name="preview_video_url"
                               placeholder="<?= _l('enter') . ' ' . _l('preview_video_url') ?>"
                               class="form-control" value="<?php
                        if (!empty($module_info->preview_video_url)) {
                            echo $module_info->preview_video_url;
                        }
                        ?>"/>
                    </div>
                </div>

                <div class="row mtop20">
                    <div class="col-md-6">
                        <?php echo render_input('module_order', 'leads_status_add_edit_order', total_rows('tbl_saas_package_module') + 1, 'number'); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-1" class="control-label"><?= _l('status') ?></label>
                            <div class="checkbox checkbox-success">
                                <input
                                    <?= (!empty($module_info->status) && $module_info->status == 'published' || empty($module_info) ? 'checked' : ''); ?>
                                        class="select_one" type="checkbox" name="status" value="published">
                                <label>
                                    <?= _l('published') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="attachments_area">
                    <div class="attachments">
                        <div class="attachment">
                            <div class="form-group ">
                                <label for="attachment"
                                       class="control-label"><?php echo _l('screenshots'); ?></label>
                                <div class="input-group">
                                    <input type="file"
                                           extension="<?php echo 'jpg,png,jpeg,gif'; ?>"
                                           filesize="<?php echo file_upload_max_size(); ?>"
                                           class="form-control" name="attachments[0]"
                                           accept=".jpg,.png,.jpeg,.gif">
                                    <span class="input-group-btn">
                                                            <button class="btn btn-default add_more_attachments"
                                                                    data-max="<?php echo 7; ?>"
                                                                    type="button"><i class="fa fa-plus"></i></button>
                                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($module_info->preview_image)) {
                    $all_preview_image = unserialize($module_info->preview_image);
                    foreach ($all_preview_image as $preview_image) {
                        $img = base_url('uploads/modules/' . $module_info->package_module_id . '/' . $preview_image['file_name']);
                        ?>
                        <div class="form-group mtop20 mbot20">
                            <div class="preview_image">
                                <a href="<?php echo $img; ?>" target="_blank">
                                    <img src="<?php echo $img; ?>"
                                         class="img-thumbnail" width="100px">
                                </a>
                                <a href="javascript:void(0);"
                                   data-file="<?php echo $preview_image['file_name']; ?>"
                                   class="remove_preview_image"><?php echo _l('remove'); ?></a>
                            </div>
                        </div>

                    <?php }
                }
                ?>
                <div class="remove_input">

                </div>


                <div class="form-group mtop20">
                    <?php
                    $descriptions = '';
                    if (!empty($module_info->descriptions)) {
                        $descriptions = $module_info->descriptions;
                    }
                    ?>
                    <?php echo render_textarea('descriptions', _l('descriptions'), $descriptions, [], [], '', 'tinymce'); ?>
                </div>

                <div class="btn-bottom-toolbar text-right">
                    <button type="submit"
                            class="btn-tr btn btn-primary mright5 text-right invoice-form-submit save-as-draft transaction-submit">
                        <?php echo _l('update'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script>
    $(function () {
        $('#module_form').appFormValidator();

        $('.remove_preview_image').on('click', function () {
            var file = $(this).data('file');
            var input = '<input type="hidden" name="remove_preview_image[]" value="' + file + '">';
            $('.remove_input').append(input);
            $(this).parent().parent().remove();
        });
    });
</script>