<?php defined('BASEPATH') or exit('No direct script access allowed');

class Packages extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }

    public function index()
    {
        $data['title'] = 'Packages - Make Package';
        $data['active'] = 1;
        $data['subview'] = $this->load->view('packages/manage', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function packagesList($status = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_packages';
            $this->datatables->select = 'tbl_saas_packages.*,(SELECT COUNT(id) FROM tbl_saas_companies WHERE package_id=tbl_saas_packages.id) as total_companies';
            $column = array('name', 'trail_period', 'recommended', 'status', 'monthly_price', 'yearly_price', 'lifetime_price');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('tbl_saas_packages.id' => 'desc');
            $where = array();
            if (!empty($status)) {
                $where = array('tbl_saas_packages.status' => $status);
            }
            $fetch_data = make_datatables($where);
            $data = array();

            $access = super_admin_access();
            foreach ($fetch_data as $key => $row) {
                $sub_array = array();
                $name = null;
                $name .= '<a href="' . base_url() . 'package_details/' . $row->id . '" title="' . _l('details') . '" data-toggle="modal" data-target="#myModal">' . $row->name . '</a>  ';
                // count total companies in this package
                $total_companies = $row->total_companies;
                $name .= '<br> <small class="text-muted">' . _l('companies') . ': ' . $total_companies . '</small>';

                $name .= '<div class="row-options">';
                if (!empty($access)) {
                    $name .= '<a href="' . base_url() . 'saas/packages/create/' . $row->id . '" title="' . _l('edit') . '">' . _l('edit') . '</a>  ';
                }
                $name .= '| <a href="' . base_url() . 'package_details/' . $row->id . '" title="' . _l('details') . '" data-toggle="modal" data-target="#myModal">' . _l('details') . '</a>  ';
                if (!empty($access)) {
                    $name .= '| <a href="' . base_url() . 'saas/packages/delete_packages/' . $row->id . '" title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $name .= '</div>';

                $sub_array[] = $name;
                $sub_array[] = $row->trial_period . ' ' . _l('days');
                $sub_array[] = package_price($row, 'row');

                $sub_array[] = ($row->recommended == 'Yes') ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
                if (!empty($access)) {
                    $checked = ($row->status == 'published') ? 'checked' : '';
                    $sub_array[] = '<div class="onoffswitch"><input type="checkbox"
                    data-id="' . $row->id . '"
                    data-switch-url="' . admin_url() . 'saas/packages/change_package_status" 
    id="onoffswitch_' . $row->id . '" class="onoffswitch-checkbox status" ' . $checked . ' /><label for="onoffswitch_' . $row->id . '" class="onoffswitch-label"></label></div>';
                } else {
                    $sub_array[] = $row->status == 'published' ? '<span class="label label-success">' . _l('published') . '</span>' : '<span class="label label-danger">' . _l('unpublished') . '</span>';
                }
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function change_package_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->where('id', $id);
            $this->db->update('tbl_saas_packages', array('status' => $status == 1 ? 'published' : 'unpublished'));
            log_activity('Package Status Changed [ID:' . $id . ', Status' . $status . ']');
            set_alert('success', _l('updated_successfully', _l('package')));
            echo json_encode(array('success' => true));
            die;
        }
    }

    public function create($id = null)
    {

        $data['title'] = _l('create_package');
        $data['active'] = 2;
        if (!empty($id)) {
            $data['package_info'] = get_row('tbl_saas_packages', array('id' => $id));
            $data['title'] = 'Packages - Edit Package';
        }
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', ['expenses_only !=' => 1]);
        $data['modules'] = $this->app_modules->get();
        $data['subview'] = $this->load->view('packages/create', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function save_packages($id = null)
    {

        $data = $this->saas_model->array_from_post(array(
            'name', 'monthly_price', 'yearly_price', 'lifetime_price',
            'trial_period', 'description', 'status', 'allowed_payment_modes', 'modules', 'allowed_themes','disabled_modules'
        ));
        $data['allowed_payment_modes'] = isset($data['allowed_payment_modes']) ? serialize($data['allowed_payment_modes']) : serialize([]);
        $data['modules'] = isset($data['modules']) ? serialize($data['modules']) : serialize([]);
        $data['disabled_modules'] = isset($data['disabled_modules']) ? serialize($data['disabled_modules']) : serialize([]);
        $data['allowed_themes'] = isset($data['allowed_themes']) ? serialize($data['allowed_themes']) : serialize([]);
        $all_field = get_order_by('tbl_saas_package_field', array('status' => 'active'), 'order', 'asc');
        if (!empty($all_field)) {
            foreach ($all_field as $key => $field) {
                $field_name = $field->field_name;
                if ($field->field_type == 'text') {
                    $additional_field = 'additional_' . $field_name;
                    $data[$additional_field] = $this->input->post($additional_field, true) ? $this->input->post($additional_field, true) : NULL;
                }
                $data[$field_name] = $this->input->post($field_name, true);
            }
        }

        $recommended = $this->input->post('recommended', true);
        $update_all_company_packages = $this->input->post('update_all_company_packages', true);

        if (!empty($update_all_company_packages)) {
            $all_company = $this->saas_model->select_data('tbl_saas_companies', 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id', NULL, array('tbl_saas_companies.package_id' => $id, 'tbl_saas_companies.for_seed' => NULL, 'tbl_saas_companies_history.active' => 1), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'result');

            if (!empty($all_company)) {
                foreach ($all_company as $key => $company) {
                    $pdata = $data;
                    $pdata['package_id'] = $id;
                    $this->saas_model->update_company_history($pdata, $company['company_history_id']);
                }
                $this->db = config_db(null, true);
            }
        }
        if (!empty($recommended)) {
            $data['recommended'] = $recommended;
            // remove recommended from other packages
            $this->db->where('recommended', 'Yes');
            $this->db->update('tbl_saas_packages', ['recommended' => 'No']);
        } else {
            $data['recommended'] = 'No';
        }
        $this->saas_model->_table_name = "tbl_saas_packages"; // table name
        $this->saas_model->_primary_key = "id"; // $id
        $this->saas_model->save($data, $id);


        // messages for user
        set_alert('success', _l('added_successfully', _l('package')));
        redirect('saas/packages');
    }


    public function package_details($id)
    {

        $data['title'] = 'Packages - Package Details';
        $data['package'] = get_row('tbl_saas_packages', array('id' => $id));
        $data['subview'] = $this->load->view('packages/package_details', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function delete_packages($id)
    {
        $package = get_row('tbl_saas_packages', array('id' => $id));
        log_activity('Package Deleted [ID:' . $id . ', Name' . $package->name . ']');

        $this->saas_model->_table_name = 'tbl_saas_packages';
        $this->saas_model->_primary_key = 'id';
        $this->saas_model->delete($id);

        // messages for user
        $type = "success";
        $message = _l('package_deleted');
        set_alert($type, $message);
        redirect('saas/packages');
    }

    public function customize($company_id = null)
    {
        $data['title'] = 'Customize Package';
        $companies_id = $this->input->post('companies_id', true);
        if (!empty($companies_id)) {
            $company_id = $companies_id;
        }
        if (!empty($company_id)) {
            $data['company_id'] = $company_id;
            $data['companyInfo'] = $this->saas_model->company_info($company_id, true);
            $data['packageInfo'] = get_usages($data['companyInfo']);
            $data['moduleInfo'] = get_old_result('tbl_saas_package_module');
        }
        $data['subview'] = $this->load->view('packages/customize', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function customize_package()
    {
        $new_limit = $this->input->post('new_limit', true);
        $new_module = $this->input->post('new_module', true);
        $company_id = $this->input->post('companies_id', true);
        $company_history_id = $this->input->post('company_history_id', true);
        $discount_percent = $this->input->post('discount_percent', true);
        $subtotal = $this->input->post('subtotal', true);
        $discount_type = $this->input->post('discount_total_type_selected', true);

        $total = $this->input->post('total', true);
        $companyInfo = $this->saas_model->company_info($company_id, true);

        $post = array();
        foreach ($new_limit as $key => $limit) {
            if (!empty($limit)) {
                if ($key === 'disk_space') {
                    $old_disk_space = $companyInfo->$key; // 1GB
                    // convert GB to byte and add new limit
                    $old_disk_space = convertGBToBytes($old_disk_space) + $limit * 1024 * 1024;
                    $post[$key] = convertSize($old_disk_space, 2);
                } else {
                    $post[$key] = $companyInfo->$key + $limit;
                }

            }
        }
        if (!empty($new_module)) {
            $old_module = $companyInfo->modules ? unserialize($companyInfo->modules) : [];
            // switch $new_module key and value and reset array key
            $new_module = array_flip($new_module);
            $new_module = array_values($new_module);
            // add the new module with old module if not exist
            $post['modules'] = serialize(array_unique(array_merge($old_module, $new_module)));
        }
        $post['package_id'] = $companyInfo->package_id;

        $companies_history_id = $this->saas_model->update_company_history($post, $company_history_id);
        if (!empty($companies_history_id)) {
            $pdata = array(
                'package_id' => $companyInfo->package_id,
                'billing_cycle' => $companyInfo->frequency,
                'is_coupon' => null,
                'coupon_code' => null,
                'reference_no' => 'SAAS-CPP- ' . date('Ymd') . '-' . rand(100000, 999999),
                'companies_history_id' => $company_history_id,
                'companies_id' => $company_id,
                'transaction_id' => 'TRN' . date('Ymd') . date('His') . '_' . substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                'payment_method' => 'manual',
                'subtotal' => $subtotal,
                'discount_percent' => $discount_percent,
                'discount_amount' => $discount_type == '%' ? ($subtotal * $discount_percent) / 100 : $discount_percent,
                'amount' => $total,
            );

            $this->saas_model->packagePayment($pdata, $company_history_id);
        }
        set_alert('success', _l('package_customized'));
        redirect('saas/companies/details/' . $company_id);


    }

    public
    function settings($active = null)
    {
        if (empty($active)) {
            $data['active'] = 'fields';
        } else {
            $data['active'] = $active;
        }
        $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
        $data['all_tabs'] = $this->package_tabs();
        if ($data['active'] == 'fields') {
            $data['menu_items'] = $this->package_fields();
        } else {
            $data['modules'] = $this->app_modules->get();
            $data['update_url'] = 'saas/packages/settings/frequency';
            $data['moduleInfo'] = get_old_result('tbl_saas_package_module');
            // set array key as module system name
            $newModuleInfo = [];
            foreach ($data['moduleInfo'] as $val) {
                $newModuleInfo[$val->module_name] = $val->price;
            }
            $data['moduleInfo'] = $newModuleInfo;
        }
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public
    function package_fields(): array
    {
        $menu_items = get_order_by('tbl_saas_package_field', null, 'order', 'asc');
        $menu = [];
        foreach ($menu_items as $item) {
            $menu[$item->field_label] = [
                'slug' => $item->field_id,
                'name' => $item->field_label,
                'position' => $item->order,
                'disabled' => $item->status == 'inactive' ? 'true' : 'false',
                'children' => [],
            ];
        }
        return $menu;
    }

    public
    function package_tabs(): array
    {
        $url = 'saas/packages/settings/';
        $tab = array(
            'fields' => [
                'position' => 1,
                'name' => 'settings_group_fields',
                'url' => $url . 'fields',
                'count' => '',
                'icon' => 'fa fa-list',
                'view' => $url . 'fields',
            ],
            'frequency' => [
                'position' => 2,
                'name' => 'frequency',
                'url' => $url . 'frequency',
                'count' => '',
                'icon' => 'fa fa-puzzle-piece',
                'view' => $url . 'frequency',
            ],
        );
        return $tab;
    }

    public
    function update_package_field()
    {
        $options = $this->input->post('options');
        foreach ($options as $val) {
            if (isset($val['children'])) {
                $newChild = [];
                foreach ($val['children'] as $keyChild => $child) {
                    $newChild[$child['id']] = $child;
                }
                $val['children'] = $newChild;
            }
            $data['status'] = $val['disabled'] == 'true' ? 'inactive' : 'active';
            $data['order'] = $val['position'];
            $this->db->where('field_id', $val['id']);
            $this->db->update('tbl_saas_package_field', $data);

        }

    }

    public
    function update_modules($id = null)
    {
        $data = $this->saas_model->array_from_post(array(
            'price', 'module_name', 'module_title', 'status', 'module_order'
        ));

        // check if module name is empty or not
        if (empty($data['module_name']) || empty($data['price']) || empty($data['module_title'])) {
            set_alert('danger', _l('module_name_price_required'));
            redirect('saas/packages/set_module_price');
        }
        // check already exist or not
        $where = array('module_name' => $data['module_name']);
        if (!empty($id)) {
            $where['package_module_id !='] = $id;
        }
        $moduleInfo = get_row('tbl_saas_package_module', $where);
        if (!empty($moduleInfo)) {
            set_alert('danger', _l('module_name_already_exist'));
            redirect('saas/packages/set_module_price');
        }


        $preview_video_url = $this->input->post('preview_video_url', true);
        // check if url is valid or not
        if (!empty($preview_video_url)) {
            if (!filter_var($preview_video_url, FILTER_VALIDATE_URL)) {
                set_alert('danger', _l('invalid_url'));
                redirect('saas/packages/set_module_price');
            }
        }
        $data['preview_video_url'] = $preview_video_url ?: null;
        $data['descriptions'] = html_purify($this->input->post('descriptions', false));

        $this->saas_model->_table_name = "tbl_saas_package_module"; // table name
        $this->saas_model->_primary_key = "package_module_id"; // $id
        $module_id = $this->saas_model->save($data, $id);
        $remove_preview_image = $this->input->post('remove_preview_image', true) ?: null;


        $attachments = handle_module_attachments($module_id);
        if (!empty($id) && !empty($attachments)) {
            $this->delete_module_attachment($id, $remove_preview_image);
        }

        if (!empty($attachments)) {
            $adata['preview_image'] = serialize($attachments);
            $this->saas_model->save($adata, $module_id);
        }

        set_alert('success', _l('module_price_updated'));
        redirect('saas/packages/modules');
    }

    /**
     * Remove ticket attachment by id
     * @param mixed $id attachment id
     * @return boolean
     */
    public function delete_module_attachment($id, $remove_preview_image = null): bool
    {
        $deleted = false;
        $moduleInfo = get_row('tbl_saas_package_module', array('package_module_id' => $id));
        if ($moduleInfo) {
            // check attachments is not empty
            $preview_image = $moduleInfo->preview_image;
            $path = get_upload_path() . $id . '/';

            if (!empty($preview_image)) {
                $preview_image = unserialize($preview_image);
                if (!empty($remove_preview_image)) {
                    $updated_preview_image = [];
                    foreach ($preview_image as $key => $value) {
                        if (!in_array($value['file_name'], $remove_preview_image)) {
                            $updated_preview_image[] = $value;
                        }
                    }
                    $adata['preview_image'] = serialize($updated_preview_image);

                    $this->saas_model->_table_name = "tbl_saas_package_module"; // table name
                    $this->saas_model->_primary_key = "package_module_id"; // $id
                    $this->saas_model->save($adata, $id);

                    foreach ($remove_preview_image as $key => $value) {
                        $filename = $path . $value;
                        if (file_exists($filename)) {
                            unlink($filename);
                            $deleted = true;
                        }
                    }
                } else {
                    if (!empty($preview_image['file_name'])) {
                        $filename = $path . $preview_image['file_name'];
                        if (file_exists($filename)) {
                            unlink($filename);
                            $deleted = true;
                        }
                    } else {
                        foreach ($preview_image as $attachment) {
                            $filename = $path . $attachment['file_name'];
                            if (file_exists($filename)) {
                                unlink($filename);
                                $deleted = true;
                            }
                        }

                    }
                }
            }
            // remove last slash
            $path = rtrim($path, '/');
            // check path directory is exists or not
            if (file_exists($path)) {
                $other_attachments = list_files($path);
                if (count($other_attachments) == 0) {
                    delete_dir($path);
                }
            }

        }
        return true;
    }

    public function modules()
    {
        $data['title'] = 'Modules - Make Module';
        $data['subview'] = $this->load->view('packages/modules/manage', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function modulesList($status = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_package_module';
            $column = array('module_name', 'module_title', 'price', 'descriptions', 'status', 'monthly_price', 'yearly_price', 'lifetime_price');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('tbl_saas_package_module.package_module_id' => 'desc');
            $where = array();
            if (!empty($status)) {
                $where = array('tbl_saas_packages.status' => $status);
            }
            $fetch_data = make_datatables($where);
            $data = array();
            $access = super_admin_access();
            foreach ($fetch_data as $key => $row) {
                $sub_array = array();
                $description = $this->app_modules->get($row->module_name);
                if (empty($description)) {
                    continue;
                }
                $name = null;
                $name .= '<a href="' . base_url() . 'saas/module_details/' . $row->module_name . '" title="' . _l('details') . '">' . (!empty($row->module_title) ? $row->module_title : $description['headers']['module_name']) . '</a>  ';

                $name .= '<div class="row-options">';
                if (!empty($access)) {
                    $name .= '<a href="' . base_url() . 'saas/packages/set_module_price/' . $row->package_module_id . '" title="' . _l('edit') . '">' . _l('edit') . '</a>  ';
                }
                $name .= '| <a href="' . base_url() . 'saas/module_details/' . $row->module_name . '" title="' . _l('details') . '">' . _l('details') . '</a>  ';
                if (!empty($access)) {
                    $name .= '| <a href="' . base_url() . 'saas/packages/delete_module/' . $row->package_module_id . '" title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $name .= '</div>';

                $sub_array[] = $name;
                $sub_array[] = display_money($row->price);
                $sub_array[] = ($row->module_order);
                $sub_array[] = '<a target="_blank" href="' . $row->preview_video_url . '" title="' . _l('details') . '">' . $row->preview_video_url . '</a>  ';


                if (!empty($access)) {
                    $checked = ($row->status == 'published') ? 'checked' : '';
                    $sub_array[] = '<div class="onoffswitch"><input type="checkbox"
                    data-id="' . $row->package_module_id . '"
                    data-switch-url="' . admin_url() . 'saas/packages/change_module_status" 
    id="onoffswitch_' . $row->package_module_id . '" class="onoffswitch-checkbox status" ' . $checked . ' /><label for="onoffswitch_' . $row->package_module_id . '" class="onoffswitch-label"></label></div>';
                } else {
                    $sub_array[] = $row->status == 'published' ? '<span class="label label-success">' . _l('published') . '</span>' : '<span class="label label-danger">' . _l('unpublished') . '</span>';
                }
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }


    public function change_module_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->where('package_module_id', $id);
            $this->db->update('tbl_saas_package_module', array('status' => $status == 1 ? 'published' : 'unpublished'));
            log_activity('Module Status Changed [ID:' . $id . ', Status' . $status . ']');
            set_alert('success', _l('updated_successfully', _l('module')));
            echo json_encode(array('success' => true));
            die;
        }
    }

    public function change_frequency_status($frequency, $status)
    {

        if ($this->input->is_ajax_request()) {
            $get_frequency = get_option('saas_disable_frequency');
            $get_frequency = json_decode($get_frequency);
            // if object then convert to array
            if (is_object($get_frequency)) {
                $get_frequency = (array)$get_frequency;
            }

            if (!empty($get_frequency)) {
                if ($status == 1) {
                    $get_frequency[] = $frequency;
                } else {
                    $key = array_search($frequency, $get_frequency);
                    unset($get_frequency[$key]);
                }
            } else {
                $get_frequency[] = $frequency;
            }
            $post_data['saas_disable_frequency'] = json_encode($get_frequency);
            $this->saas_model->update_option($post_data);

            log_activity('Frequency Status Changed [Frequency:' . $frequency . ', Status' . $status . ']');

            set_alert('success', _l('updated_successfully', _l('frequency')));
            echo json_encode(array('success' => true));
            die;
        }
    }

    public function set_module_price($id = null)
    {
        $data['title'] = _l('set_module_price');
        if (!empty($id)) {
            $data['module_info'] = get_row('tbl_saas_package_module', array('package_module_id' => $id));
            $data['title'] = 'Modules - Edit Module';
        }
        $data['modules'] = $this->app_modules->get();
        $data['subview'] = $this->load->view('packages/modules/create', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function delete_module($id)
    {
        $module = get_row('tbl_saas_package_module', array('package_module_id' => $id));
        log_activity('Module Deleted [ID:' . $id . ', Name' . $module->module_name . ']');

        $this->delete_module_attachment($id);

        $this->saas_model->_table_name = 'tbl_saas_package_module';
        $this->saas_model->_primary_key = 'package_module_id';
        $this->saas_model->delete($id);

        // messages for user
        $type = "success";
        $message = _l('module_deleted');
        set_alert($type, $message);
        redirect('saas/packages/modules');
    }


}
