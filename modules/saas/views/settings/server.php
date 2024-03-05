<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        <div class="form-horizontal">

            <div class="form-group">

                <label class="col-lg-3 control-label">
                    <?= _l('create_wildcard_subdomain') ?>
                    <a href="https://docs.coderitems.com/perfectsaas/#wildcard_subdomain_cPanel"
                       target="_blank"
                       data-toggle="tooltip"
                       data-title="<?= _l('create_wildcard_subdomain_help') ?>">
                        <i class="fa fa-question-circle"></i>
                    </a>
                </label>
                <div class="col-lg-5">
                    <div class="radio radio-inline radio-primary">
                        <input type="radio" name="settings[saas_server_wildcard]" id="on"
                               value="on" <?php if (get_option('saas_server_wildcard') == 'on') {
                            echo 'checked';
                        } ?>>
                        <label for="yes">
                            <?= _l('settings_yes') ?>
                        </label>
                    </div>

                    <div class="radio radio-inline radio-primary">
                        <input type="radio" name="settings[saas_server_wildcard]" id="off"
                               value="off" <?php if (get_option('saas_server_wildcard') == 'off') {
                            echo 'checked';
                        } ?>>
                        <label for="no">
                            <?= _l('settings_no') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= _l('select') . ' ' . _l('server') ?> <span
                            class="text-danger">*</span>

                    <a href="https://docs.coderitems.com/perfectsaas/#configure_server"
                       target="_blank"
                       data-toggle="tooltip"
                       data-title="<?= _l('select_server_help') ?>">
                        <i class="fa fa-question-circle"></i>
                    </a>
                </label>
                <div class="col-lg-6">
                    <select name="settings[saas_server]" id="type" class="form-control">
                        <?php $saas_server = get_option('saas_server'); ?>
                        <option
                                value="local" <?= ($saas_server == "local" ? ' selected="selected"' : '') ?>><?= _l('local') ?></option>

                        <option
                                value="mysql" <?= ($saas_server == "mysql" ? ' selected="selected"' : '') ?>><?= _l('mysql_root_access') ?></option>

                        <option
                                value="cpanel" <?= ($saas_server == "cpanel" ? ' selected="selected"' : '') ?>><?= _l('cpanel') ?></option>
                        <option
                                value="plesk" <?= ($saas_server == "plesk" ? ' selected="selected"' : '') ?>><?= _l('plesk') ?></option>
                    </select>
                </div>
            </div>
            <div class="saas_mysql" style="display: <?= ($saas_server == 'mysql_root_access' ? 'block' : 'none'); ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('mysql') . ' ' . _l('host') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_mysql_host') ?>" name="settings[saas_mysql_host]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('mysql') . ' ' . _l('port') ?></label>
                    <div class="col-lg-6">
                        <input type="number" required="" class="form-control"
                               value="<?= get_option('saas_mysql_port') ?>" name="settings[saas_mysql_port]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('mysql_root') . ' ' . _l('username') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_mysql_username') ?>"
                               name="settings[saas_mysql_username]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('mysql') . ' ' . _l('password') ?></label>
                    <div class="col-lg-6">
                        <?php
                        $password = strlen(decrypt(get_option('saas_mysql_password')));
                        ?>
                        <input type="password" name="settings[saas_mysql_password]" placeholder="<?php
                        if (!empty($password)) {
                            for ($p = 1; $p <= $password; $p++) {
                                echo '*';
                            }
                        } ?>" value="" class="form-control">
                        <strong id="show_password" class="required"></strong>
                    </div>
                </div>

            </div>

            <div class="saas_cpanel" style="display: <?= ($saas_server == 'cpanel' ? 'block' : 'none'); ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('cpanel') . ' ' . _l('host') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_cpanel_host') ?>" name="settings[saas_cpanel_host]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('cpanel') . ' ' . _l('port') ?></label>
                    <div class="col-lg-6">
                        <input type="number" required="" class="form-control"
                               value="<?= get_option('saas_cpanel_port') ?>" name="settings[saas_cpanel_port]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('cpanel') . ' ' . _l('username') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_cpanel_username') ?>"
                               name="settings[saas_cpanel_username]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('cpanel') . ' ' . _l('password') ?></label>
                    <div class="col-lg-6">
                        <?php
                        $password = strlen(decrypt(get_option('saas_cpanel_password')));
                        ?>
                        <input type="password" name="settings[saas_cpanel_password]" placeholder="<?php
                        if (!empty($password)) {
                            for ($p = 1; $p <= $password; $p++) {
                                echo '*';
                            }
                        } ?>" value="" class="form-control">
                        <strong id="show_password" class="required"></strong>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Set Output <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <select name="settings[saas_cpanel_output]" class="form-control">
                            <?php $output = get_option('saas_cpanel_output'); ?>
                            <option
                                    value="json" <?= ($output == "json" ? ' selected="selected"' : '') ?>>JSON
                                (Recommended)
                            </option>
                            <option
                                    value="xml" <?= ($output == "xml" ? ' selected="selected"' : '') ?>>XML
                            </option>
                            <option
                                    value="array" <?= ($output == "simplexml" ? ' selected="selected"' : '') ?>>
                                Simple XML
                            </option>
                            <option
                                    value="array" <?= ($output == "array" ? ' selected="selected"' : '') ?>>
                                Array
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="saas_plesk" style="display: <?= ($saas_server == 'plesk' ? 'block' : 'none'); ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('plesk') . ' ' . _l('host') ?> </label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_plesk_host') ?>" name="settings[saas_plesk_host]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('plesk') . ' ' . _l('username') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_plesk_username') ?>"
                               name="settings[saas_plesk_username]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= _l('plesk') . ' ' . _l('password') ?></label>
                    <div class="col-lg-6">
                        <?php
                        $password = strlen(decrypt(get_option('saas_plesk_password')));
                        ?>
                        <input type="password" name="settings[saas_plesk_password]" placeholder="<?php
                        if (!empty($password)) {
                            for ($p = 1; $p <= $password; $p++) {
                                echo '*';
                            }
                        } ?>" value="" class="form-control">
                        <strong id="show_password" class="required"></strong>
                    </div>
                </div>

                <div class="form-group">
                    <label
                            class="col-lg-3 control-label"><?= _l('plesk') . ' ' . _l('webspace_id') ?></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control"
                               value="<?= get_option('saas_plesk_webspace_id') ?>"
                               name="settings[saas_plesk_webspace_id]">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-sm btn-primary"><?= _l('save_changes') ?></button>
                    <button type="submit" name="test_connection" value="test_connection"
                            class="btn btn-sm btn-warning pull-right"><?= _l('test_connection') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    'use strict';
    $(document).ready(function () {
        $("#type").on('change', function () {
            $(this).find("option:selected").each(function () {
                if ($(this).attr("value") == "cpanel") {
                    $('.saas_cpanel').show();
                    $(".saas_cpanel :input").attr("disabled", false);
                    $('.saas_plesk').hide();
                    $(".saas_plesk :input").attr("disabled", true);

                    $('.saas_mysql').hide();
                    $(".saas_mysql :input").attr("disabled", true);

                } else if ($(this).attr("value") == "plesk") {
                    $('.saas_plesk').show();
                    $(".saas_plesk :input").attr("disabled", false);
                    $('.saas_cpanel').hide();
                    $(".saas_cpanel :input").attr("disabled", true);
                    $('.saas_mysql').hide();
                    $(".saas_mysql :input").attr("disabled", true);
                } else if ($(this).attr("value") == "mysql") {
                    $('.saas_plesk').hide();
                    $(".saas_plesk :input").attr("disabled", true);
                    $('.saas_cpanel').hide();
                    $(".saas_cpanel :input").attr("disabled", true);

                    $('.saas_mysql').show();
                    $(".saas_mysql :input").attr("disabled", false);
                } else {
                    $('.saas_cpanel').hide();
                    $(".saas_cpanel :input").attr("disabled", true);
                    $('.saas_plesk').hide();
                    $(".saas_plesk :input").attr("disabled", true);
                    $('.saas_mysql').hide();
                    $(".saas_mysql :input").attr("disabled", true);
                }
            });
        }).change();


    });
</script>