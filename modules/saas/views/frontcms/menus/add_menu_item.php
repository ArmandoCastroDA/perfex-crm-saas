<div class="row">
    <div class="col-md-7">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php
                    if (!empty($menu_item)) {
                        echo _l('edit');
                    } else {
                        echo _l('add');
                    } ?>
                    <?= _l('menu') . ' ' . _l('item') ?>
                </div>
            </div>

            <div class="panel-body">
                <?php
                if (!empty($menu_info)) {
                    $slug = $menu_info->slug;
                } else {
                    $slug = '';
                }
                if (!empty($menu_item)) {
                    $menu_slug = $menu_item->slug;
                } else {
                    $menu_slug = '';
                }
                echo form_open_multipart(base_url() . 'saas/frontcms/menus/add_menu_item/' . $slug . '/' . $menu_slug, array('class' => 'form-horizontal form-groups-bordered validate', 'data-parsley-validate' => '', 'enctype' => 'multipart/form-data'));
                ?>

                <input type="hidden" name="menu_id" value="<?php
                if (!empty($menu_info)) {
                    echo($menu_info->id);
                } ?>">

                <input type="hidden" name="item_id" value="<?php
                if (!empty($menu_item)) {
                    echo $menu_item->id;
                } ?>">

                <div class="form-group">
                    <label for="exampleInputEmail1"><?= _l('menu') . ' ' . _l('title'); ?></label>
                    <small class="required"> *</small>
                    <input autofocus="" id="menu" name="menu" placeholder="" type="text" class="form-control"
                           required value="<?php
                    if (!empty($menu_item)) {
                        echo html_escape($menu_item->menu);
                    } ?>"/>
                    <span class="text-danger"><?php echo form_error('menu'); ?></span>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo _l('external_url'); ?></label>
                    <div class="material-switch">
                        <input id="ext_url" name="ext_url" type="checkbox" class="ext_url_chk" value="1" <?php
                        if (!empty($menu_item) && !empty($menu_item->ext_url)) {
                            echo "checked";
                        } ?> />
                        <label for="ext_url" class="label-success"></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo _l('external_url_address'); ?></label>
                    <input id="ext_url_link" name="ext_url_link" type="text" class="form-control" value="<?php
                    if (!empty($menu_item) && !empty($menu_item->ext_url)) {
                        echo html_escape($menu_item->ext_url_link);
                    } ?>" <?php
                    if (!empty($menu_item) && is_null($menu_item->ext_url_link)) {
                        echo "disabled";
                    } elseif (empty($menu_slug)) {
                        echo "disabled";
                    }
                    ?> />
                    <span class="text-danger"><?php echo form_error('ext_url_link'); ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo _l('open_in_new_tab'); ?></label>
                    <div class="material-switch">
                        <input id="open_new_tab" name="open_new_tab" type="checkbox" <?php
                        if (!empty($menu_item) && !empty($menu_item->open_new_tab)) {
                            echo "checked";
                        } ?> class="chk" value="1"/>
                        <label for="open_new_tab" class="label-success"></label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo _l('mpage'); ?></label>
                    <select id="page_id" name="page_id" class="form-control">
                        <option value=""><?php echo _l('select'); ?></option>
                        <?php
                        if (!empty($page_list)) {
                            foreach ($page_list as $page) { ?>
                                <option value="<?php echo $page->pages_id; ?>" <?php if (!empty($menu_item) && $menu_item->page_id == $page->pages_id) echo "selected=selected" ?>><?php echo $page->title; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-info pull-right"
                           value="<?php echo _l('save'); ?>">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <?= _l('menu') . ' ' . _l('item') . ' ' . _l('list') ?>
                </div>
            </div>

            <div class="panel-body">
                <?php
                echo form_open(base_url() . 'saas/frontcms/menus/update/', array('data-parsley-validate' => '', 'enctype' => 'multipart/form-data'));
                ?>
                <div class="menu-box">
                    <ol class="sortable">
                        <?php
                        if (!empty($dropdown_menu_list)) {
                            $m_slug = $slug;
                            $count = 1;
                            foreach ($dropdown_menu_list as $menu) { ?>
                                <li id="list_<?php echo $menu['id']; ?>">
                                    <div>
                                        <?php echo $menu['menu']; ?>
                                        <span class="pull-right">
                                                <a href="<?php echo site_url('saas/frontcms/menus/add_menu_item/' . $m_slug . "/" . $menu['slug']) ?>"
                                                   class="btn btn-xs" title="<?php echo _l('edit'); ?>"><i
                                                            class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-xs" title="<?php echo _l('delete'); ?>"
                                                   data-id="<?php echo $menu['id']; ?>" id="deleteItem"
                                                   data-toggle="modal" data-target="#confirm-delete"><i
                                                            class="fa fa-remove"></i></a>
                                            </span>
                                    </div>
                                    <?php
                                    if (!empty($menu['submenus'])) { ?>
                                        <ol class="submenu-list">
                                            <?php
                                            foreach ($menu['submenus'] as $submenu_key => $submenu_value) {
                                                ?>
                                                <li id="list_<?php echo $submenu_value['id']; ?>">
                                                    <div class="ui-sortable-handle pinter">
                                                        <?php echo html_escape($submenu_value['menu']); ?>
                                                        <span class="pull-right">
                                                                <a href="<?php echo site_url('saas/frontcms/menus/add_menu_item/' . $m_slug . "/" . $submenu_value['slug']) ?>"
                                                                   class="btn btn-xs" title="Edit Item"><i
                                                                            class="fa fa-pencil"></i></a>
                                                                <a href="#" class="btn btn-xs" title="Delete Item"
                                                                   data-id="<?php echo $submenu_value['id']; ?>"
                                                                   id="deleteItem" data-toggle="modal"
                                                                   data-target="#confirm-delete"><i
                                                                            class="fa fa-remove"></i></a>
                                                            </span>

                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ol>
                                    <?php } ?>
                                </li>
                                <?php
                                $count++;
                            }
                        } ?>
                    </ol>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="<?= module_dir_url(SaaS_MODULE, 'assets/js/jquery.mjs.nestedSortable.js') ?>"></script>

    <script type="text/javascript">
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $('.delmodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: false
                })
                $('#confirm-delete').on('show.bs.modal', function (e) {
                    var data = $(e.relatedTarget).data();
                    $('.del_menuid', this).val("");
                    $('.del_menuid', this).val(data.id);
                });


                $('#confirm-delete').on('click', '.btn-ok', function (e) {
                    var $modalDiv = $(e.delegateTarget);
                    var id = $('.del_menuid').val();
                    $.ajax({
                        type: "post",
                        url: '<?php echo site_url("saas/frontcms/menus/delete_menu_item") ?>',
                        dataType: 'JSON',
                        data: {
                            'id': id
                        },
                        beforeSend: function () {
                            $modalDiv.addClass('modalloading');
                        },
                        success: function (data) {
                            if (data.status === 'success') {
                                location.reload(true);
                            }
                            $("#confirm-delete").modal('hide');
                            toastr[data.status](data.msg);
                        },
                        complete: function () {
                            $modalDiv.removeClass('modalloading');
                        }
                    });
                });
            });
            $('.ext_url_chk').on('change', function () {
                var c = this.checked ? 1 : 0;
                if (c) {
                    $('#ext_url_link').prop("disabled", false);
                } else {
                    $('#ext_url_link').prop("disabled", true);

                }
            });
            $('ol.sortable').nestedSortable({
                disableNesting: 'no-nest',
                forcePlaceholderSize: true,
                handle: 'div',
                helper: 'clone',
                items: 'li',
                maxLevels: 2,
                opacity: .6,
                tabSize: 25,
                tolerance: 'pointer',
                toleranceElement: '> div',
                update: function () {
                    var list = $(this).nestedSortable('toHierarchy');
                    var urls = base_url + "saas/frontcms/menus/sort_menu";
                    $.ajax({
                        url: urls,
                        type: 'post',
                        data: {
                            order: list
                        },
                        dataType: "html",
                        success: function (response) {
                            console.log(response);
                        },
                        beforeSend: function () {
                        },
                        complete: function () {
                        }
                    });
                }
            });

        })(jQuery);
    </script>

    <div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo _l('confirmation'); ?></h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure want to delete item, this action is irreversible!</p>
                    <p>Do you want to proceed?</p>
                    <p class="debug-url"></p>
                    <input type="hidden" name="del_menuid" class="del_menuid" value="">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo _l('cancel'); ?></button>
                    <a class="btn btn-danger btn-ok"><?php echo _l('delete'); ?></a>
                </div>
            </div>
        </div>
    </div>