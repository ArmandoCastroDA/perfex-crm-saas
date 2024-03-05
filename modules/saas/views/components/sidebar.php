<aside class="aside">
    <!-- START Sidebar (left)-->
    <?php
    $user_id = get_staff_user_id();
    $profile_info = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
    $user_info = $this->db->where('user_id', $user_id)->get('tbl_users')->row();
    ?>
    <div class="aside-inner">
        <nav data-sidebar-anyclick-close="" class="sidebar">
            <!-- START sidebar nav-->
            <ul class="nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <a href="<?php echo base_url('admin/user/user_details/' . $this->session->userdata('user_id') . ''); ?>">
                        <div id="user-block" class="block">
                            <div class="item user-block">
                                <!-- User picture-->
                                <div class="user-block-picture">
                                    <div class="user-block-status">
                                        <img src="<?= base_url() . $profile_info->avatar ?>" alt="Avatar" width="60"
                                             height="60"
                                             class="img-thumbnail img-circle">
                                        <div class="circle circle-success circle-lg"></div>
                                    </div>
                                </div>
                                <!-- Name and Job-->
                                <div class="user-block-info">
                                    <span class="user-block-name"><?= $profile_info->fullname ?></span>
                                    <span class="user-block-role"></i> <?= _l('online') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <?php
            echo $this->saas_model->saas_menu();
            ?>
        </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>