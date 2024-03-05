<?php
$created = super_admin_access();
$edited = super_admin_access();
$deleted = super_admin_access();
?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>">
            <a href="#pages" data-toggle="tab"><?= _l('all') ?> <?= _l('abouts') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>">
            <a href="#create" data-toggle="tab"><?= _l('abouts_works') ?></a>
        </li>
        <li class="<?= $active == 3 ? 'active' : ''; ?>">
            <a href="#creates" data-toggle="tab"><?= _l('new') ?> <?= _l('abouts') ?></a>
        </li>
        <li class="<?= $active == 4 ? 'active' : ''; ?>">
            <a href="#createss" data-toggle="tab"><?= _l('abouts_footer') ?></a>
        </li>
        <li class="pull-right hidden-print">
            <button href="<?= base_url() ?>saas/frontcms/abouts/create"
                    class="btn btn-xs btn-info add_heading" data-toggle="modal" data-placement="top"
                    data-target="#myModal">
                <i class="fa fa-plus "></i> <?= ' ' . _l('add') . ' ' . _l('abouts_heading') ?></button>
        </li>
    </ul>


    <!--Tab content-->
    <div class="tab-content bg-white">
        <!--All Pages-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="pages">
            <div class="table-responsive">
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><?= _l('title') ?></th>
                        <th><?= _l('description') ?></th>
                        <th><?= _l('status') ?></th>
                        <th class="col-options no-sort"><?= _l('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <script type="text/javascript">
                        list = base_url + "saas/frontcms/abouts/aboutsList";
                    </script>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($created) || !empty($edited)) {
            if (!empty($page_info)) {
                $pages_id = $page_info->pages_id;
            } else {
                $pages_id = null;
            }
            ?>
            <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">

                <form role="form" id="form"
                      action="<?php echo base_url(); ?>saas/frontcms/abouts/save_about_works/<?php if (!empty($abouts_card->id)) {
                          echo $abouts_card->id;
                      } ?>" method="post" enctype="multipart/form-data" class="form-horizontal  ">


                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= _l('works_type') ?></strong></label>
                        <div class="col-sm-6">
                            <select name="type" class="form-control selectpicker" data-width="100%" required>
                                <option value="">Select One</option>
                                <option value="about_works" <?php if (!empty($abouts_card)) {
                                    if ($abouts_card->type == 'about_works') {
                                        echo 'selected';
                                    }
                                } ?>>About Works
                                </option>
                                <option value="discussion" <?php if (!empty($abouts_card)) {
                                    if ($abouts_card->type == 'discussion') {
                                        echo 'selected';
                                    }
                                } ?>>Discussion
                                </option>
                                <option value="strategy" <?php if (!empty($abouts_card)) {
                                    if ($abouts_card->type == 'strategy') {
                                        echo 'selected';
                                    }
                                } ?>>Strategy & Testing
                                </option>
                                <option value="reporting" <?php if (!empty($abouts_card)) {
                                    if ($abouts_card->type == 'reporting') {
                                        echo 'selected';
                                    }
                                } ?>>Reporting
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('name') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($abouts_card)) {
                                echo $abouts_card->name;
                            } ?>" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('icons') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($abouts_card)) {
                                echo $abouts_card->icons;
                            } ?>" name="icons" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($abouts_card)) {
                                echo $abouts_card->title;
                            } ?>" name="title" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
                        <div class="col-md-9">
                            <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($abouts_card)) {
                                    echo($abouts_card->description);
                                } ?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
                        <div class="col-lg-6">
                            <div class="material-switch tw-mt-2">
                                <input name="status" id="ext_urls" type="checkbox" value="1" <?php
                                if (!empty($abouts_card)) {
                                    if ($abouts_card->status == 1) {
                                        echo 'checked';
                                    }
                                } ?> />
                                <label for="ext_urls" class="label-success"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>

        <div class="tab-pane <?= $active == 3 ? 'active' : ''; ?>" id="creates">
            <?php
            $abouts_infoss = get_row('tbl_saas_all_section_area', array('type' => 'abouts'));
            ?>
            <form role="form" id="form"
                  action="<?php echo base_url(); ?>saas/frontcms/abouts/save_abouts_card/<?php if (!empty($abouts_infoss->id)) {
                      echo $abouts_infoss->id;
                  } ?>" method="post" enctype="multipart/form-data" class="form-horizontal  ">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo html_escape($abouts_infoss->title);
                        } ?>" name="title" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('image') ?></label>
                    <div class="col-lg-6">
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
                    <label class="col-lg-3 control-label"><?= _l('button_name') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->name;
                        } ?>" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('button_link') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->link;
                        } ?>" name="link" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('number_of_years') ?> </label>
                    <div class="col-lg-6">
                        <input type="number" value="<?php
                        if (!empty($abouts_infoss)) {
                            echo $abouts_infoss->color;
                        } ?>" name="color" class="form-control">
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($abouts_infoss)) {
                                    echo($abouts_infoss->description);
                                } ?>
                            </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
                    <div class="col-lg-6">
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
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <div class="tab-pane <?= $active == 4 ? 'active' : ''; ?>" id="createss">
            <?php
            $about_footer = get_row('tbl_saas_all_section_area', array('type' => 'about_footer'));
            ?>
            <form role="form" id="form"
                  action="<?php echo base_url(); ?>saas/frontcms/abouts/save_about_footer/<?php if (!empty($about_footer->id)) {
                      echo $about_footer->id;
                  } ?>" method="post" enctype="multipart/form-data" class="form-horizontal  ">


                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('icons') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->icons);
                        } ?>" name="icons" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('links') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->links);
                        } ?>" name="links" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('image') ?></label>
                    <div class="col-lg-6">
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
                    <label class="col-lg-3 control-label"><?= _l('youtube_id') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo ($about_footer->youtube_id);
                        } ?>" name="youtube_id" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('name') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->name);
                        } ?>" name="name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('title_1') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->title);
                        } ?>" name="title" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('title_2') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->title_2);
                        } ?>" name="title_2" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('button_name') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->button_name_2);
                        } ?>" name="button_name_2" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('button_icons') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->icons_2);
                        } ?>" name="icons_2" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('button_links') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($about_footer)) {
                            echo html_escape($about_footer->button_link_2);
                        } ?>" name="button_link_2" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($about_footer)) {
                                    echo($about_footer->description);
                                } ?>
                            </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
                    <div class="col-lg-6">
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
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary ml-lg"><?= _l('save') ?>
                        </button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>