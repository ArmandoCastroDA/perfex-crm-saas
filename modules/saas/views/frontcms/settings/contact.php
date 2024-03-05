
<div class="panel_s">


    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : ''; ?>">
                <a href="#pages" data-toggle="tab"> <?= _l('all_contact') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : ''; ?>">
                <a href="#create" data-toggle="tab"> <?= _l('contact_heading') ?></a>
            </li>
            <li class="pull-right hidden-print">
                <button style="margin: 12px 12px 0px 0px;" href="<?= base_url() ?>saas/frontcms/settings/create_contact"
                        class="btn btn-xs btn-primary" data-toggle="modal" data-placement="top" data-target="#myModal">
                    <i class="fa fa-plus "></i> <?= ' ' . _l('add') . ' ' . _l('new_contact') ?></button>
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
                            <th><?= _l('name') ?></th>
                            <th><?= _l('icons') ?></th>
                            <th><?= _l('title') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <script type="text/javascript">
                            list = base_url + "saas/frontcms/settings/contactList";
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                <?php
                $contact_heading = get_row('tbl_saas_all_heading_section', array('type' => 'contact_heading'));
                if (!empty($contact_heading)) {
                    $conId = $contact_heading->heading_id;
                } else {
                    $conId = '';
                }
                echo form_open(base_url() . 'saas/frontcms/settings/save_contact_heading/' . $conId, array('class' => 'form-horizontal'));
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('title') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" value="<?php
                        if (!empty($contact_heading)) {
                            echo $contact_heading->title;
                        } ?>" name="title" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-lg-3"><?= _l('description') ?></label>
                    <div class="col-md-9">
                            <textarea name="description" class="form-control tinymce" rows="3">
                                <?php
                                if (!empty($contact_heading)) {
                                    echo $contact_heading->description;
                                } ?>
                            </textarea>
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
