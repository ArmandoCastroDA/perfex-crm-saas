<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="header">
    <div class="hide-menu tw-ml-1"><i class="fa fa-align-left"></i></div>

    <nav>
        <div class="tw-flex tw-justify-between">
            <div class="tw-flex tw-flex-1 sm:tw-flex-initial">

                <ul class="nav navbar-nav visible-md visible-lg">
                    <li class="header-home">
                        <a href="<?php echo admin_url(); ?>" class="tw-flex tw-items-center tw-justify-center">
                            <i class="fa fa-arrow-left fa-lg tw-mr-1"></i></i>
                            <span class="tw-text-base"><?php echo _l('nav_back_to_admin'); ?></span>
                        </a>
                </ul>
            </div>

            <div class="mobile-menu tw-shrink-0 ltr:tw-ml-4 rtl:tw-mr-4">
                <button type="button"
                        class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed tw-ml-1.5"
                        data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
                    <i class="fa fa-chevron-down fa-lg"></i>
                </button>
                <ul class="mobile-icon-menu tw-inline-flex tw-mt-5">
                    <?php
                    // To prevent not loading the timers twice
                    if (is_mobile()) { ?>
                        <li
                                class="dropdown notifications-wrapper header-notifications tw-block ltr:tw-mr-1.5 rtl:tw-ml-1.5">
                            <?php $this->load->view('admin/includes/notifications'); ?>
                        </li>
                        <li class="header-timers ltr:tw-mr-1.5 rtl:tw-ml-1.5">
                            <a href="#" id="top-timers" class="dropdown-toggle top-timers tw-block tw-h-5 tw-w-5"
                               data-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="tw-w-5 tw-h-5 tw-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span
                                        class="tw-leading-none tw-px-1 tw-py-0.5 tw-text-xs bg-success tw-z-10 tw-absolute tw-rounded-full -tw-right-3 -tw-top-2 tw-min-w-[18px] tw-min-h-[18px] tw-inline-flex tw-items-center tw-justify-center icon-started-timers<?php echo $totalTimers = count($startedTimers) == 0 ? ' hide' : ''; ?>"><?php echo count($startedTimers); ?></span>
                            </a>
                            <ul class="dropdown-menu animated fadeIn started-timers-top width300"
                                id="started-timers-top">
                                <?php $this->load->view('admin/tasks/started_timers', ['startedTimers' => $startedTimers]); ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
                <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;"
                     role="navigation">
                    <ul class="nav navbar-nav">
                        <li class="header-my-profile"><a href="<?php echo admin_url('profile'); ?>">
                                <?php echo _l('nav_my_profile'); ?>
                            </a>
                        </li>

                        <li class="header-edit-profile"><a href="<?php echo admin_url('staff/edit_profile'); ?>">
                                <?php echo _l('nav_edit_profile'); ?>
                            </a>
                        </li>
                        <?php if (is_staff_member()) { ?>
                            <li class="header-newsfeed">
                                <a href="#" class="open_newsfeed mobile">
                                    <?php echo _l('whats_on_your_mind'); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="header-logout">
                            <a href="#" onclick="logout(); return false;">
                                <?php echo _l('nav_logout'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="icon header-user-profile" data-toggle="tooltip" title="<?php echo get_staff_full_name(); ?>"
                    data-placement="bottom">
                    <a href="#" class="dropdown-toggle profile tw-block rtl:!tw-px-0.5 !tw-py-1" data-toggle="dropdown"
                       aria-expanded="false">
                        <?php echo staff_profile_image($current_user->staffid, ['img', 'img-responsive', 'staff-profile-image-small', 'tw-ring-1 tw-ring-offset-2 tw-ring-primary-500 tw-mx-1 tw-mt-2.5']); ?>
                    </a>
                    <ul class="dropdown-menu animated fadeIn">
                        <li class="header-my-profile"><a
                                    href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a>
                        </li>

                        <li class="header-edit-profile"><a
                                    href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a>
                        </li>
                        <?php if (!is_language_disabled()) { ?>
                            <li class="dropdown-submenu pull-left header-languages">
                                <a href="#" tabindex="-1"><?php echo _l('language'); ?></a>
                                <ul class="dropdown-menu dropdown-menu">
                                    <li class="<?php echo $current_user->default_language == '' ? 'active' : ''; ?>">
                                        <a href="<?php echo admin_url('staff/change_language'); ?>">
                                            <?php echo _l('system_default_string'); ?>
                                        </a>
                                    </li>
                                    <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                    <li
                                            class="<?php echo $current_user->default_language == $user_lang ? 'active' : ''; ?>">
                                        <a href="<?php echo admin_url('staff/change_language/' . $user_lang); ?>">
                                            <?php echo ucfirst($user_lang); ?>
                                        </a>
                                        <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <li class="header-logout">
                            <a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a>
                        </li>
                    </ul>
                </li>

                <li class="icon dropdown tw-relative tw-block notifications-wrapper header-notifications rtl:tw-ml-3"
                    data-toggle="tooltip" title="<?php echo _l('nav_notifications'); ?>" data-placement="bottom">
                    <?php $this->load->view('admin/includes/notifications'); ?>
                </li>

                <?php hooks()->do_action('admin_navbar_end'); ?>
            </ul>
        </div>
    </nav>
</div>