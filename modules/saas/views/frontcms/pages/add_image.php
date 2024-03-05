<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title"><?= _l('add') . ' ' . _l('media') ?></div>
    </div>

    <div class="modal-body">
        <div class="mediarow">
            <div class="row" id="media_div"></div>
        </div>
        <div align="right" id="pagination_link"></div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
        <button type="submit" class="btn btn-primary add_media"><?= _l('add') ?></button>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        "use strict";
        $('#myModal_xl').on('loaded.bs.modal', function () {
            load(1);
        });
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

        $(document).on('click', '.img_div_modal', function (event) {
            $('.img_div_modal div.fadeoverlay').removeClass('active');
            $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');
        });
        $(document).on('click', '.add_media', function (event) {
            var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
            var content = "";
            if (typeof content_html !== "undefined") {
                if (is_image === 1) {
                    content = '<img src="' + content_html + '">';
                } else if (content_type == "video") {
                    var youtubeID = YouTubeGetID(vid_url);
                    content = '<iframe id="video" width="420" height="315" src="//www.youtube.com/embed/' + youtubeID + '?rel=0" frameborder="0" allowfullscreen></iframe>';
                } else {
                    content = '<a href="' + content_html + '">' + content_name + '</a>';

                }
                InsertHTML(content);
                $('#myModal_xl').modal('hide');
            }
        });

        function InsertHTML(content_html) {
            var editor = CKEDITOR.instances.editor1;
            if (editor.mode == 'wysiwyg') {
                editor.insertHtml(content_html);
            } else
                alert('You must be in WYSIWYG mode!');
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
                dataType: 'Json',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data, textStatus, jqXHR) {
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