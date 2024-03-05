<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<aside id="menu" class="sidebar sidebar">
    <ul class="nav metis-menu" id="side-menu">
        <li class="tw-mt-[63px] sm:tw-mt-0 -tw-mx-2 tw-overflow-hidden sm:tw-bg-neutral-900/50">
            <div id="logo" class="tw-py-2 tw-px-2 tw-h-[63px] tw-flex tw-items-center">
                <?php echo get_saas_company_logo('saas' . '/dashboard', '!tw-mt-0') ?>
            </div>
        </li>
        <?php
        $sidebar_menu = $this->saas_model->build_sidebar_menu();
        ?>
        <?php
        foreach ($sidebar_menu as $key => $item) {
            if ((isset($item['collapse']) && $item['collapse']) && count($item['children']) === 0) {
                continue;
            } ?>
            <li class="menu-item-<?php echo $item['slug']; ?>"
                <?php echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
                <a href="<?php echo count($item['children']) > 0 ? '#' : $item['href']; ?>" aria-expanded="false"
                    <?php echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?>>
                    <i class="<?php echo $item['icon']; ?> menu-icon"></i>
                    <span class="menu-text">
                    <?php echo _l($item['name'], '', false); ?>
                </span>
                    <?php if (count($item['children']) > 0) { ?>
                        <span class="fa arrow pleft5"></span>
                    <?php } ?>
                    <?php if (isset($item['badge'], $item['badge']['value']) && !empty($item['badge'])) { ?>
                        <span
                                class="badge pull-right
               <?= isset($item['badge']['type']) && $item['badge']['type'] != '' ? "bg-{$item['badge']['type']}" : 'bg-info' ?>" <?= (isset($item['badge']['type']) && $item['badge']['type'] == '') ||
                        isset($item['badge']['color']) ? "style='background-color: {$item['badge']['color']}'" : '' ?>>
                    <?= $item['badge']['value'] ?>
                </span>
                    <?php } ?>
                </a>
                <?php if (count($item['children']) > 0) { ?>
                    <ul class="nav nav-second-level collapse" aria-expanded="false">
                        <?php foreach ($item['children'] as $submenu) {
                            ?>
                            <li class="sub-menu-item-<?php echo $submenu['slug']; ?>"
                                <?php echo _attributes_to_string(isset($submenu['li_attributes']) ? $submenu['li_attributes'] : []); ?>>
                                <a href="<?php echo $submenu['href']; ?>"
                                    <?php echo _attributes_to_string(isset($submenu['href_attributes']) ? $submenu['href_attributes'] : []); ?>>
                                    <?php if (!empty($submenu['icon'])) { ?>
                                        <i class="<?php echo $submenu['icon']; ?> menu-icon"></i>
                                    <?php } ?>
                                    <span class="sub-menu-text">
                            <?php echo _l($submenu['name'], '', false); ?>
                        </span>
                                </a>
                                <?php if (isset($submenu['badge'], $submenu['badge']['value']) && !empty($submenu['badge'])) { ?>
                                    <span
                                            class="badge pull-right
               <?= isset($submenu['badge']['type']) && $submenu['badge']['type'] != '' ? "bg-{$submenu['badge']['type']}" : 'bg-info' ?>" <?= (isset($submenu['badge']['type']) && $submenu['badge']['type'] == '') ||
                                    isset($submenu['badge']['color']) ? "style='background-color: {$submenu['badge']['color']}'" : '' ?>>
                        <?= $submenu['badge']['value'] ?>
                    </span>
                                <?php } ?>
                            </li>
                            <?php
                        } ?>
                    </ul>
                <?php } ?>
            </li>

            <?php
        } ?>

    </ul>
</aside>