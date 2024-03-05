<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/1"
            ><?= _l('abouts') . ' ' . _l('work_process') ?></a>
        </li>
        <li class="<?= $type == 'discussion' ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/2/discussion"
            ><?= _l('abouts') . ' ' . _l('discussion') ?></a>
        </li>
        <li class="<?= $type == 'strategy' ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/2/strategy"
            ><?= _l('abouts') . ' ' . _l('strategy') ?></a>
        </li>
        <li class="<?= $type == 'reporting' ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/2/reporting"
            ><?= _l('abouts') . ' ' . _l('reporting') ?></a>
        </li>
        <li class="<?= $active == 5 ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/5"
            ><?= _l('new') ?> <?= _l('abouts') ?></a>
        </li>
        <li class="<?= $active == 6 ? 'active' : ''; ?>">
            <a href="<?= base_url() ?>saas/frontcms/abouts/index/6"
            ><?= _l('abouts_footer') ?></a>
        </li>
        <li class="pull-right hidden-print">
            <button href="<?= base_url() ?>saas/frontcms/abouts/create"
                    class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal">
                <i class="fa fa-plus "></i> <?= ' ' . _l('add') . ' ' . _l('abouts_heading') ?></button>
        </li>
    </ul>

    <div class="panel_s panel-body">
        <?php
        if ($active == 1) { ?>
            <?php
            $abouts_work_info = get_row('tbl_saas_all_heading_section', array('type' => 'about_works'));
            echo form_open_multipart(base_url() . 'saas/frontcms/abouts/save_about_works/' . $abouts_work_info->heading_id, array('id' => 'about_works'));
            ?>
            <input type="hidden" value="<?php
            if (!empty($abouts_work_info)) {
                echo $abouts_work_info->type;
            } ?>" name="type" class="form-control">

            <div class="form-group">
                <label class=control-label"><?= _l('name') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($abouts_work_info)) {
                        echo $abouts_work_info->name;
                    } ?>" name="name" class="form-control">


                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('title') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($abouts_work_info)) {
                        echo $abouts_work_info->title;
                    } ?>" name="title" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('description') ?></label>
                <div class="">
                            <textarea name="description" class="form-control tinymce" rows="3"><?php
                                if (!empty($abouts_work_info)) {
                                    echo($abouts_work_info->description);
                                } ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('status') ?></label>
                <div class="">
                    <div class="material-switch tw-mt-2">
                        <input name="status" id="ext_urls" type="checkbox" value="1" <?php
                        if (!empty($abouts_work_info)) {
                            if ($abouts_work_info->status == 1) {
                                echo 'checked';
                            }
                        } ?> />
                        <label for="ext_urls" class="label-success"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"></label>
                <div class="">
                    <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                    </button>
                </div>
            </div>
            <?php
            echo form_close();
        }
        ?>

        <?php
        if ($active == 2 || $active == 3 || $active == 4) {
            $abouts_discussion_info = get_row('tbl_saas_all_heading_section', array('type' => $type));
            if (!empty($abouts_discussion_info)) {
                $pages_id = $abouts_discussion_info->heading_id;
            } else {
                $pages_id = null;
            }

            ?>
            <?php
            echo form_open_multipart(base_url() . 'saas/frontcms/abouts/save_about_works/' . $pages_id);
            ?>

            <input type="hidden" value="<?php
            if (!empty($abouts_discussion_info)) {
                echo $abouts_discussion_info->type;
            } ?>" name="type" class="form-control">

            <div class="form-group">
                <label class=control-label"><?= _l('icons') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($abouts_discussion_info)) {
                        echo $abouts_discussion_info->icons;
                    } ?>" name="icons" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('title') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($abouts_discussion_info)) {
                        echo $abouts_discussion_info->title;
                    } ?>" name="title" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('description') ?></label>
                <div class=""><textarea name="description" class="form-control tinymce" rows="3"><?php
                        if (!empty($abouts_discussion_info)) {
                            echo($abouts_discussion_info->description);
                        } ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('status') ?></label>
                <div class="">
                    <div class="material-switch tw-mt-2">
                        <input name="status" id="ext_urls" type="checkbox" value="1" <?php
                        if (!empty($abouts_discussion_info)) {
                            if ($abouts_discussion_info->status == 1) {
                                echo 'checked';
                            }
                        } ?> />
                        <label for="ext_urls" class="label-success"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"></label>
                <div class="">
                    <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                    </button>
                </div>
            </div>

            <?php
            echo form_close();
        }
        ?>

        <?php
        if ($active == 5) {
            $abouts_infoss = get_row('tbl_saas_all_section_area', array('type' => 'abouts'));
            if (!empty($abouts_infoss)) {
                $abouts_infoss = $abouts_infoss;
            } else {
                $abouts_infoss = null;
            }
            echo form_open_multipart(base_url() . 'saas/frontcms/abouts/save_abouts_card/' . $abouts_infoss->id);
            ?>
            <form role="form" id="form"
                  action="<?php echo base_url(); ?>saas/frontcms/abouts/save_abouts_card/<?php if (!empty($abouts_infoss->id)) {
                      echo $abouts_infoss->id;
                  } ?>" method="post" enctype="multipart/form-data" class="form-horizontal  ">

                <div class="form-group">
                    <label class=control-label"><?= _l('title') ?> </label>
                    <div class="">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo html_escape($abouts_infoss->title);
                        } ?>" name="title" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class=control-label"><?= _l('image') ?></label>
                    <div class="">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail w-210">
                                <?php
                                if (!empty($abouts_infoss)) {
                                    if ($abouts_infoss->image != '') { ?>
                                        <img src="<?php echo base_url() . $abouts_infoss->image; ?>">
                                    <?php }
                                } else { ?>
                                    <span>select image</span>
                                <?php } ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                            <div>
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">
                                        <input type="file" name="image" value="upload" class="form-controll"
                                               data-buttonText="<?= _l('choose_file') ?>" id="myImg"/>
                                        <span class="fileinput-exists"><?= _l('change') ?></span>
                                    </span>
                                    <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= _l('remove') ?></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class=control-label"><?= _l('button_name') ?> </label>
                    <div class="">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->name;
                        } ?>" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class=control-label"><?= _l('button_link') ?> </label>
                    <div class="">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->link;
                        } ?>" name="link" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class=control-label"><?= _l('number_of_years') ?> </label>
                    <div class="">
                        <input type="number" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->color;
                        } ?>" name="color" class="form-control">
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label"><?= _l('description') ?></label>
                    <div class="">
                        <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($abouts_infoss)) {
                                    echo($abouts_infoss->description);
                                } ?>
                            </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label"><?= _l('status') ?></label>
                    <div class="">
                        <div class="material-switch tw-mt-2">
                            <input name="status" id="ext_url" type="checkbox" value="1" <?php
                            if (!empty($abouts_infoss)) {
                                if ($abouts_infoss->status == 1) {
                                    echo 'checked';
                                }
                            } ?> />
                            <label for="ext_url" class="label-success"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class=control-label"></label>
                    <div class="">
                        <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                        </button>
                    </div>
                </div>
            </form>
        <?php }
        ?>
        <?php
        if ($active == 6) {
            $about_footer = get_row('tbl_saas_all_section_area', array('type' => 'about_footer'));
            echo form_open_multipart(base_url() . 'saas/frontcms/abouts/save_about_footer/' . $about_footer->id);
            ?>


            <div class="form-group">
                <label class=control-label"><?= _l('icons') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->icons);
                    } ?>" name="icons" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('links') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->link);
                    } ?>" name="link" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('image') ?></label>
                <div class="">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail w-210">
                            <?php
                            if (!empty($about_footer)) {
                                if ($about_footer->image != '') { ?>
                                    <img src="<?php echo base_url() . $about_footer->image; ?>">
                                <?php }
                            } else { ?>
                                <span>select image</span>
                            <?php } ?>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                        <div>
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">
                                        <input type="file" name="image" value="upload" class="form-controll"
                                               data-buttonText="<?= _l('choose_file') ?>" id="myImg"/>
                                        <span class="fileinput-exists"><?= _l('change') ?></span>
                                    </span>
                                    <a href="#" class="btn btn-default fileinput-exists"
                                       data-dismiss="fileinput"><?= _l('remove') ?></a>
                                </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= _l('youtube_id') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer->icons_3)) {
                        echo($about_footer->icons_3);
                    } ?>" name="icons_3" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('name') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->name);
                    } ?>" name="name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('title_1') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->title);
                    } ?>" name="title" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('title_2') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->title_2);
                    } ?>" name="title_2" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('button_name') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->button_name_2);
                    } ?>" name="button_name_2" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('button_icons') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->icons_2);
                    } ?>" name="icons_2" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"><?= _l('button_links') ?> </label>
                <div class="">
                    <input type="text" value="<?php
                    if (!empty($about_footer)) {
                        echo html_escape($about_footer->button_link_2);
                    } ?>" name="button_link_2" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label"><?= _l('description') ?></label>
                <div class="">
                        <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($about_footer)) {
                                    echo($about_footer->description);
                                } ?>
                            </textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="control-label"><?= _l('status') ?></label>
                <div class="">
                    <div class="material-switch tw-mt-2">
                        <input name="status" id="ext_urlss" type="checkbox" value="1" <?php
                        if (!empty($about_footer)) {
                            if ($about_footer->status == 1) {
                                echo 'checked';
                            }
                        } ?> />
                        <label for="ext_urlss" class="label-success"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class=control-label"></label>
                <div class="">
                    <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                    </button>
                </div>
            </div>
            <?php
            echo form_close();
        }
        ?>

    </div>
</div>