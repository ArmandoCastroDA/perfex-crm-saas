<?php
if (!empty($creatives_card)) {
    $pages_id = $creatives_card->id;
} else {
    $pages_id = null;
}
echo form_open(base_url() . 'saas/frontcms/creatives/save_creatives_card/' . $pages_id, array('class' => 'form-horizontal', 'id' => 'form'));
?>
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new') . ' ' . _l('creatives_card'); ?></h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('name') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->name);
                } ?>" name="name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('icons') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->icons);
                } ?>" name="icons" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('designation') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->designation);
                } ?>" name="designation" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('title 1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->title);
                } ?>" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('title 2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->title_2);
                } ?>" name="title_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('color 1') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->color);
                } ?>" name="color" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= _l('color 2') ?> </label>
            <div class="col-lg-6">
                <input type="text" value="<?php
                if (!empty($creatives_card)) {
                    echo html_escape($creatives_card->color_2);
                } ?>" name="color_2" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-3"><?= _l('status') ?></label>
            <div class="col-lg-6">
                <div class="material-switch tw-mt-2">
                    <input name="status" id="ext_url" type="checkbox" value="1" <?php
                    if (!empty($creatives_card)) {
                        if ($creatives_card->status == 1) {
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

        <?php echo form_close(); ?>
    </div>
</div>

