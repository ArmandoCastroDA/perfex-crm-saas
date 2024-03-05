<?php
echo form_open_multipart(base_url('saas/frontcms/media/save_media'), array('id' => 'media_form', 'class' => 'form-horizontal', 'data-parsley-validate' => 'true'));
?>
    <div class="panel panel-custom" data-collapsed="0">
        <div class="panel-heading">
            <div class="panel-title"><?= _l('add') . ' ' . _l('media') ?></div>
        </div>

        <div class="modal-body">
            <div class="form-group clearfix">
                <label for="" class="control-label"><?= _l('upload_your_file'); ?></label>
                <div id="comments_file-dropzone" class="dropzone mb15">

                </div>
                <div id="comments_file-dropzone-scrollbar">
                    <div id="comments_file-previews">
                        <div id="file-upload-row" class="mt pull-left">

                            <div class="preview box-content pr-lg w-100">
                                <span data-dz-remove class="pull-right pointer">
                                    <i class="fa fa-times"></i>
                                </span>
                                <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                                <input class="file-count-field" type="hidden" name="files[]" value=""/>
                                <div class="mb progress progress-striped upload-progress-sm active mt-sm"
                                     role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div class="progress-bar progress-bar-success w-0"
                                         data-dz-uploadprogress></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!empty($tickets_info->upload_file)) {
                    $uploaded_file = json_decode($tickets_info->upload_file);
                }
                if (!empty($uploaded_file)) {
                    foreach ($uploaded_file as $v_files_image) { ?>
                        <div class="pull-left mt pr-lg mb w-100">
                            <span data-dz-remove class="pull-right existing_image pointer"><i
                                        class="fa fa-times"></i></span>
                            <?php if ($v_files_image->is_image == 1) { ?>
                                <img data-dz-thumbnail
                                     src="<?php echo base_url() . html_escape($v_files_image->path) ?>"
                                     class="upload-thumbnail-sm"/>
                            <?php } else { ?>
                                <span data-toggle="tooltip" data-placement="top"
                                      title="<?= html_escape($v_files_image->fileName) ?>"
                                      class="mailbox-attachment-icon"><i class="fa fa-file-text-o"></i></span>
                            <?php } ?>

                            <input type="hidden" name="path[]" value="<?php echo html_escape($v_files_image->path) ?>">
                            <input type="hidden" name="fileName[]"
                                   value="<?php echo html_escape($v_files_image->fileName) ?>">
                            <input type="hidden" name="fullPath[]"
                                   value="<?php echo html_escape($v_files_image->fullPath) ?>">
                            <input type="hidden" name="size[]" value="<?php echo html_escape($v_files_image->size) ?>">
                            <input type="hidden" name="is_image[]"
                                   value="<?php echo html_escape($v_files_image->is_image) ?>">
                        </div>
                    <?php }; ?>
                <?php }; ?>
                <script type="text/javascript">
                    (function ($) {
                        "use strict";
                        $(document).ready(function () {
                            $(".existing_image").on("click", function () {
                                $(this).parent().remove();
                            });

                            var fileSerial = 0;
                            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                            var previewNode = document.querySelector("#file-upload-row");
                            previewNode.id = "";
                            var previewTemplate = previewNode.parentNode.innerHTML;
                            previewNode.parentNode.removeChild(previewNode);
                            Dropzone.autoDiscover = false;
                            var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                                url: "<?= base_url() ?>admin/common/upload_file",
                                thumbnailWidth: 80,
                                thumbnailHeight: 80,
                                parallelUploads: 20,
                                previewTemplate: previewTemplate,
                                dictDefaultMessage: '<?php echo _l("file_upload_instruction"); ?>',
                                autoQueue: true,
                                previewsContainer: "#comments_file-previews",
                                clickable: true,
                                accept: function (file, done) {
                                    if (file.name.length > 200) {
                                        done("Filename is too long.");
                                        $(file.previewTemplate).find(".description-field").remove();
                                    }
                                    //validate the file
                                    $.ajax({
                                        url: "<?= base_url() ?>admin/common/validate_project_file",
                                        data: {
                                            file_name: file.name,
                                            file_size: file.size
                                        },
                                        cache: false,
                                        type: 'POST',
                                        dataType: "json",
                                        success: function (response) {
                                            if (response.success) {
                                                fileSerial++;
                                                $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                                $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                    <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                                $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                                done();
                                            } else {
                                                $(file.previewTemplate).find("input").remove();
                                                done(response.message);
                                            }
                                        }
                                    });
                                },
                                processing: function () {
                                    $("#file-save-button").prop("disabled", true);
                                },
                                queuecomplete: function () {
                                    $("#file-save-button").prop("disabled", false);
                                },
                                fallback: function () {
                                    //add custom fallback;
                                    $("body").addClass("dropzone-disabled");
                                    $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                                    $("#comments_file-dropzone").hide();

                                    $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo _l("add_more"); ?>" + "</button>");

                                    $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                        var newFileRow = "<div class='file-row pb pt10 b-b mb10'>" +
                                            "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>" +
                                            "<div class='mb5 pb5'><input class='form-control description-field'  name='comment[]'  type='text' style='cursor: auto;' placeholder='<?php echo _l("comment") ?>' /></div>" +
                                            "</div>";
                                        $("#comments_file-previews").prepend(newFileRow);
                                    });
                                    $("#add-more-file-button").trigger("click");
                                    $("#comments_file-previews").on("click", ".remove-file", function () {
                                        $(this).closest(".file-row").remove();
                                    });
                                },
                                success: function (file) {
                                    setTimeout(function () {
                                        $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                                    }, 1000);
                                }
                            });

                        })
                    })(jQuery);
                </script>
            </div>
            <hr>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('upload_youtube_video'); ?></label>
                <input type="text" name="vid_url" class="form-control">
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
<?php echo form_close(); ?>