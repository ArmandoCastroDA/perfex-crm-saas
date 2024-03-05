<?php init_saas_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <div class="panel-title">
                    <?= _l('media_manager') ?>
                    <div class="pull-right hidden-print">
                        <a href="<?= base_url() ?>saas/frontcms/media/add_media" class="btn btn-xs btn-info"
                           data-toggle="modal" data-placement="top" data-target="#myModal">
                            <i class="fa fa-plus "></i> <?= ' ' . _l('add') . ' ' . _l('media') ?></a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="name" class="control-label"><?php echo lang('search_by_file_name'); ?></label>
                        <input type="text" value='' class="form-control search_text" id=""
                               placeholder="<?php echo lang('enter_keyword'); ?>">
                    </div>
                </form>
                <hr>

                <div class="mediarow">
                    <div class="row" id="media_div"></div>
                </div>
                <div align="right" id="pagination_link"></div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('saas/_layout_modal'); ?>
<script type="text/javascript">
    (function ($) {
        "use strict";
        $(document).ready(function () {
            load(1);
            $(document).on("click", ".pagination li a", function (event) {
                event.preventDefault();
                var page = $(this).data("ci-pagination-page");
                load(page);
            });
            $(".search_text").on("keyup", function () {
                load(1);
            });
            $(".file_type").on("change", function () {
                load(1);
            });

            $('#postdate').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            $("#confirm-delete").modal({
                backdrop: false,
                show: false

            });
            $('#confirm-delete').on('show.bs.modal', function (e) {
                var record_id = $(e.relatedTarget).data('record_id');
                $('#record_id').val(0).val(record_id);
                $('.del_modal_title').html('<?= _l('delete_confirmation') ?>');
                $('.del_modal_body').html('<?= _l('delete_conform') ?>');
            });

            $('#detail').on('show.bs.modal', function (e) {
                var data = $(e.relatedTarget).data();
                var media_content_path = "<a class='break' href='" + data.source + "' target='_blank'>" + data.source + "</a>";
                $('#modal_media_name').text("").text(data.media_name);
                $('#modal_media_path').html("").html(media_content_path);
                $('#modal_media_type').text("").text(data.media_type);
                $('#modal_media_size').text("").text(convertSize(data.media_size));
                updateMediaDetailPopup(data.media_type, data.source, data.image);

            });

            function updateMediaDetailPopup(media_type, url, thumb_path) {
                var content_popup = "";
                if (media_type == "video") {
                    var youtubeID = YouTubeGetID(url);
                    content_popup = '<object data="https://www.youtube.com/embed/' + youtubeID + '" width="100%" height="400"></object>';
                } else {
                    content_popup = '<img src="' + thumb_path + '" class="img-responsive">';
                }
                $('.popup_image').html("").html(content_popup);
            }

            function YouTubeGetID(url) {
                var ID = '';
                url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
                if (url[2] !== undefined) {
                    ID = url[2].split(/[^0-9a-z_\-]/i);
                    ID = ID[0];
                } else {
                    ID = url;
                }
                return ID;
            }
        });

        function load(page) {
            var keyword = $('.search_text').val();
            var file_type = $('.file_type').val();
            var is_gallery = 0;
            $.ajax({
                url: "<?php echo base_url(); ?>saas/frontcms/media/getPage/" + page,
                method: "POST",
                data: {
                    'keyword': keyword,
                    'file_type': file_type,
                    'is_gallery': is_gallery
                },
                dataType: "json",
                beforeSend: function () {
                    $('#media_div').empty();
                },
                success: function (data) {
                    $('#media_div').empty();
                    if (data.result_status === 1) {
                        $.each(data.result, function (index, value) {
                            $("#media_div").append(data.result[index]);
                        });
                        $('#pagination_link').html(data.pagination_link);
                    } else {
                    }
                },
                complete: function () {

                }
            });
        }

        $(document).on('click', '.btn_delete', function () {
            var $this = $('.btn_delete');
            var record_id = $('#record_id').val();
            $.ajax({
                url: "<?php echo base_url(); ?>saas/frontcms/media/deleteItem",
                type: "POST",
                data: {
                    'record_id': record_id
                },
                dataType: 'json',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data, textStatus, jqXHR) {
                    console.log(data.status);
                    if (data.status === 'success') {
                        load(1);
                    }
                    $("#confirm-delete").modal('hide');
                    toastr[data.status](data.msg);
                },

                complete: function () {

                    $this.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });

        function convertSize(bytes, decimalPoint) {
            if (bytes == 0)
                return '0 Bytes';
            var k = 1024,
                dm = decimalPoint || 2,
                sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
    })(jQuery);
</script>

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="panel panel-custom fullshadow">
            <div class="panel-heading">
                <span id="modal_media_name"></span> - <?= _l('details') ?>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only">Close</span></button>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 col-sm-8 popup_image"></div>
                    <div class="col-md-4 col-sm-4 smcomment-title pt-2">
                        <p id="modal_media_name"></p>
                        <h4 class="mb-0"><?= _l('media_type') ?></h4>
                        <p id="modal_media_type"></p>
                        <h4 class="mb-0"><?= _l('media_path') ?></h4>
                        <p id="modal_media_path"></p>
                        <h4 class="mb-0"><?= _l('media_size') ?></h4>
                        <p id="modal_media_size"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="record_id" name="record_id" value="0">
            <div class="modal-header">
                <h4 class="modal-title del_modal_title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body del_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('cancel') ?></button>
                <a class="btn btn_delete btn-danger"
                   data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?= _l('delete') ?></a>
            </div>
        </div>
    </div>
</div>