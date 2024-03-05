<div class="panel_s">
    <div class="">
        <div class="nav-tabs-custom ">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>">
                    <a href="#pages" data-toggle="tab"><?= _l('all') ?> <?= _l('footer') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>">
                    <a href="#create" data-toggle="tab"><?= _l('new') ?> <?= _l('footer') ?></a>
                </li>
            </ul>


            <!--Tab content-->
            <div class="tab-content bg-white">
                <!--All Pages-->
                <div class="tab-pane panel-body <?= $active == 1 ? 'active' : ''; ?>" id="pages">
                    <div class="table-responsive">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><?= _l('button_name') ?></th>
                                <th><?= _l('button_link') ?></th>
                                <th><?= _l('status') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <script type="text/javascript">
                                list = base_url + "saas/frontcms/settings/footerList";
                            </script>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                    <?php
                    if (!empty($footer_card->id)) {
                        $fid = $footer_card->id;
                    } else {
                        $fid = '';
                    }
                    echo form_open(base_url('saas/frontcms/settings/save_footer_card/' . $fid), array('id' => 'form', 'class' => 'form-horizontal '));
                    ?>


                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong><?= _l('heading_name') ?></strong></label>
                        <div class="col-sm-6">
                            <select name="type" class="form-control selectpicker" style="width: 100%" required>
                                <option value="">Select One</option>
                                <option value="company" <?php if (!empty($footer_card)) {
                                    if ($footer_card->type == 'company') {
                                        echo 'selected';
                                    }
                                } ?>><?= _l('company') ?></option>
                                <option value="usefull_links" <?php if (!empty($footer_card)) {
                                    if ($footer_card->type == 'usefull_links') {
                                        echo 'selected';
                                    }
                                } ?>><?= _l('usefull_links') ?></option>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_name') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($footer_card)) {
                                echo html_escape($footer_card->button_name_2);
                            } ?>" name="button_name_2" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= _l('button_link') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" value="<?php
                            if (!empty($footer_card)) {
                                echo html_escape($footer_card->button_link_2);
                            } ?>" name="button_link_2" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
                        <div class="col-lg-6">
                            <div class="material-switch tw-mt-2">
                                <input name="status" id="ext_urle" type="checkbox" value="1" <?php
                                if (!empty($footer_card)) {
                                    if ($footer_card->status == 1) {
                                        echo 'checked';
                                    }
                                } ?> />
                                <label for="ext_urle" class="label-success"></label>
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
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
