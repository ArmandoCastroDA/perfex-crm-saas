<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="">
    <div class="">

        <div class="">
            <?php if (isset($member)) { ?>
                <div class="member">
                    <?php echo form_hidden('isedit'); ?>
                    <?php echo form_hidden('memberid', $member->staffid); ?>
                </div>
            <?php } ?>
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off']); ?>
            <div class="panel_s">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo(isset($member) ? _l('staff_edit_profile') : _l('staff_add_profile')); ?></h4>
                </div>
                <div class="panel-body ">
                    <div class="col-md-6">
                        <?php if ((isset($member) && $member->profile_image == null) || !isset($member)) { ?>
                            <div class="form-group">
                                <label for="profile_image"
                                       class="profile-image"><?php echo _l('staff_edit_profile_image'); ?></label>
                                <input type="file" name="profile_image" class="form-control" id="profile_image">
                            </div>
                        <?php } ?>
                        <?php if (isset($member) && $member->profile_image != null) { ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo staff_profile_image($member->staffid, ['img', 'img-responsive', 'staff-profile-image-thumb'], 'thumb'); ?>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <a
                                                href="<?php echo admin_url('staff/remove_staff_profile_image/' . $member->staffid); ?>"><i
                                                    class="fa fa-remove"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php $value = (isset($member) ? $member->firstname : ''); ?>
                        <?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
                        <?php echo render_input('firstname', 'staff_add_edit_firstname', $value, 'text', $attrs); ?>
                        <?php $value = (isset($member) ? $member->lastname : ''); ?>
                        <?php echo render_input('lastname', 'staff_add_edit_lastname', $value); ?>
                        <?php $value = (isset($member) ? $member->email : ''); ?>
                        <?php echo render_input('email', 'staff_add_edit_email', $value, 'email', ['autocomplete' => 'off']); ?>
                        <?php if (!isset($member) || super_admin_access() || !is_admin() && $member->role == 4) { ?>
                            <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                            <input type="text" class="fake-autofill-field" name="fakeusernameremembered"
                                   value=''
                                   tabindex="-1"/>
                            <input type="password" class="fake-autofill-field" name="fakepasswordremembered"
                                   value='' tabindex="-1"/>

                            <input type="hidden" name="administrator" id="administrator" value="1">

                            <label for="password"
                                   class="control-label"><?php echo _l('staff_add_edit_password'); ?></label>
                            <div class="input-group">
                                <input type="password" class="form-control password" name="password"
                                       autocomplete="off">
                                <span class="input-group-addon tw-border-l-0">
                                        <a href="#password" class="show_password"
                                           onclick="showPassword('password'); return false;"><i
                                                    class="fa fa-eye"></i></a>
                                    </span>
                                <span class="input-group-addon">
                                        <a href="#" class="generate_password"
                                           onclick="generatePassword(this);return false;"><i
                                                    class="fa fa-refresh"></i></a>
                                    </span>
                            </div>
                            <?php if (isset($member)) { ?>
                                <p class="text-muted tw-mt-2"><?php echo _l('staff_add_edit_password_note'); ?></p>
                                <?php if ($member->last_password_change != null) { ?>
                                    <?php echo _l('staff_add_edit_password_last_changed'); ?>:
                                    <span class="text-has-action" data-toggle="tooltip"
                                          data-title="<?php echo _dt($member->last_password_change); ?>">
                                    <?php echo time_ago($member->last_password_change); ?>
                                </span>
                                <?php }
                            } ?>
                        <?php } ?>
                        <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                        <?php echo render_input('phonenumber', 'staff_add_edit_phonenumber', $value); ?>
                        <div class="form-group">
                            <label for="facebook" class="control-label"><i class="fa-brands fa-facebook-f"></i>
                                <?php echo _l('staff_add_edit_facebook'); ?></label>
                            <input type="text" class="form-control" name="facebook"
                                   value="<?php if (isset($member)) {
                                       echo $member->facebook;
                                   } ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="linkedin" class="control-label"><i class="fa-brands fa-linkedin-in"></i>
                                <?php echo _l('staff_add_edit_linkedin'); ?></label>
                            <input type="text" class="form-control" name="linkedin"
                                   value="<?php if (isset($member)) {
                                       echo $member->linkedin;
                                   } ?>">
                        </div>
                        <div class="form-group">
                            <label for="skype" class="control-label"><i class="fa-brands fa-skype"></i>
                                <?php echo _l('staff_add_edit_skype'); ?></label>
                            <input type="text" class="form-control" name="skype"
                                   value="<?php if (isset($member)) {
                                       echo $member->skype;
                                   } ?>">
                        </div>
                        <?php if (!is_language_disabled()) { ?>
                            <div class="form-group select-placeholder">
                                <label for="default_language"
                                       class="control-label"><?php echo _l('localization_default_language'); ?></label>
                                <select name="default_language" data-live-search="true" id="default_language"
                                        class="form-control selectpicker"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""><?php echo _l('system_default_string'); ?></option>
                                    <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                                        $selected = '';
                                        if (isset($member)) {
                                            if ($member->default_language == $availableLanguage) {
                                                $selected = 'selected';
                                            }
                                        } ?>
                                        <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>>
                                            <?php echo ucfirst($availableLanguage); ?></option>
                                        <?php
                                    } ?>
                                </select>
                            </div>
                        <?php } ?>
                        <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1"
                           data-toggle="tooltip"
                           data-title="<?php echo _l('staff_email_signature_help'); ?>"></i>
                        <?php $value = (isset($member) ? $member->email_signature : ''); ?>
                        <?php echo render_textarea('email_signature', 'settings_email_signature', $value, ['data-entities-encode' => 'true']); ?>
                        <div class="form-group select-placeholder">
                            <label for="direction"><?php echo _l('document_direction'); ?></label>
                            <select class="selectpicker"
                                    data-none-selected-text="<?php echo _l('system_default_string'); ?>"
                                    data-width="100%" name="direction" id="direction">
                                <option value="" <?php if (isset($member) && empty($member->direction)) {
                                    echo 'selected';
                                } ?>></option>
                                <option value="ltr" <?php if (isset($member) && $member->direction == 'ltr') {
                                    echo 'selected';
                                } ?>>LTR
                                </option>
                                <option value="rtl" <?php if (isset($member) && $member->direction == 'rtl') {
                                    echo 'selected';
                                } ?>>RTL
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="btn-bottom-toolbar text-right">
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>

    <div class="btn-bottom-pusher"></div>
</div>

<script type="text/javascript">
    'use strict';
    $(function () {
        appValidateForm($('.staff-form'), {
            firstname: 'required',
            lastname: 'required',
            username: 'required',
            password: {
                required: {
                    depends: function (element) {
                        return ($('input[name="isedit"]').length == 0) ? true : false
                    }
                }
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: admin_url + "misc/staff_email_exists",
                    type: 'post',
                    data: {
                        email: function () {
                            return $('input[name="email"]').val();
                        },
                        memberid: function () {
                            return $('input[name="memberid"]').val();
                        }
                    }
                }
            }
        });
    });
</script>
