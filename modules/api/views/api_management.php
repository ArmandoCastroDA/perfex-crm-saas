<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link href="<?php echo base_url('modules/api/assets/main.css'); ?>" rel="stylesheet" type="text/css" />
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <a href="#" onclick="new_user(); return false" class="btn btn-info pull-left display-block"><?php echo _l('new_user_api'); ?></a>
                  </div>
                  <div class="clearfix"></div>
                  <hr class="hr-panel-heading" />
                  <div class="clearfix"></div>
                  <table class="apitable table dt-table">
                     <thead>
                        <th><?php echo _l('id'); ?></th>
                        <th><?php echo _l('user_api'); ?></th>
                        <th><?php echo _l('name_api'); ?></th>
                        <th><?php echo _l('token_api'); ?></th>
                        <th><?php echo _l('expiration_date'); ?></th>
                        <th><?php echo _l('options'); ?></th>
                     </thead>
                     <tbody>
                        <?php foreach($user_api as $user){ ?>
                        <tr>
                           <td><?php echo addslashes($user['id']); ?></td>
                           <td><?php echo addslashes($user['user']); ?></td>
                           <td><?php echo addslashes($user['name']); ?></td>
                           <td><?php echo addslashes($user['token']); ?></td>
                           <td><?php echo addslashes($user['expiration_date']); ?></td>
                           <td>
                             <a href="#" onclick="edit_user(this,<?php echo addslashes($user['id']); ?>); return false" data-user="<?php echo addslashes($user['user']); ?>" data-name="<?php echo addslashes($user['name']); ?>" data-expiration_date="<?php echo addslashes($user['expiration_date']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                              <a href="<?php echo admin_url('api/delete_user/'.addslashes($user['id'])); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="user_api" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('api/user')); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
               <span class="edit-title"><?php echo _l('edit_user_api'); ?></span>
               <span class="add-title"><?php echo _l('new_user_api'); ?></span>
            </h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="additional"></div>
                  <?php echo render_input('user','user_api'); ?>
                  <?php echo render_input('name','name_api'); ?>
                  <?php echo render_datetime_input('expiration_date','expiration_date'); ?>
               </div>
               
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div><!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script src="<?php echo base_url('modules/api/assets/main.js'); ?>"></script>