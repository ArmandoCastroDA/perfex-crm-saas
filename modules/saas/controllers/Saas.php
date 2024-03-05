<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['breadcrumbs'] = 'Dashboard';
        $data['plan_overview'] = $this->get_package_overview();
        $data['states'] = $this->get_states();
        $data['subview'] = $this->load->view('saas', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function get_states(): array
    {
        $states = [
            [
                'name' => 'total_companies',
                'icon' => 'fa fa-building',
                'color' => 'info',
                'link' => 'saas/companies',
                'count' => total_rows('tbl_saas_companies', array('for_seed' => NULL))
            ],
            [
                'name' => 'active_companies',
                'icon' => 'fa fa-building',
                'color' => 'success',
                'link' => 'saas/companies/index/running',
                'count' => total_rows('tbl_saas_companies', array('for_seed' => NULL, 'status' => 'running'))
            ],
            [
                'name' => 'licence_expired',
                'icon' => 'fa fa-building',
                'color' => 'danger',
                'link' => 'saas/companies/index/expired',
                'count' => total_rows('tbl_saas_companies', array('status' => 'expired'))
            ],
            [
                'name' => 'inactive_companies',
                'icon' => 'fa fa-building',
                'color' => 'warning',
                'link' => 'saas/companies/index/pending',
                'count' => total_rows('tbl_saas_companies', array('status' => 'pending'))
            ],
            [
                'name' => 'suspended_companies',
                'icon' => 'fa fa-building',
                'color' => 'dark',
                'link' => 'saas/companies/index/suspended',
                'count' => total_rows('tbl_saas_companies', array('status' => 'suspended'))
            ],
            [
                'name' => 'terminated_companies',
                'icon' => 'fa fa-building',
                'color' => 'dark',
                'link' => 'saas/companies/index/terminated',
                'count' => total_rows('tbl_saas_companies', array('status' => 'terminated'))
            ],

            [
                'name' => 'total_packages',
                'icon' => 'fa fa-building',
                'color' => 'primary',
                'link' => 'saas/packages',
                'count' => total_rows('tbl_saas_packages')
            ],
            [
                'name' => 'active_packages',
                'icon' => 'fa fa-building',
                'color' => 'success',
                'link' => 'saas/packages/index/published',
                'count' => total_rows('tbl_saas_packages', array('status' => 'published'))
            ],
            [
                'name' => 'inactive_packages',
                'icon' => 'fa fa-building',
                'color' => 'danger',
                'link' => 'saas/packages/index/unpublished',
                'count' => total_rows('tbl_saas_packages', array('status' => 'unpublished'))
            ],
            [
                'name' => 'faq',
                'icon' => 'fa fa-building',
                'color' => 'warning',
                'link' => 'saas/faq',
                'count' => total_rows('tbl_saas_front_contact_us', array('view_status' => '0'))
            ],
        ];
        return $states;

    }

    public function get_package_overview()
    {
        $frontend_pricing = $this->saas_model->get_packages();
        if (!empty($frontend_pricing)) {
            foreach ($frontend_pricing as $v_pricing) {
                $result[$v_pricing->name]['pending'] = total_rows('tbl_saas_companies', array('status' => 'pending', 'package_id' => $v_pricing->id));
                $result[$v_pricing->name]['running'] = total_rows('tbl_saas_companies', array('for_seed' => NULL, 'status' => 'running', 'package_id' => $v_pricing->id));
                $result[$v_pricing->name]['expired'] = total_rows('tbl_saas_companies', array('status' => 'expired', 'package_id' => $v_pricing->id));
                $result[$v_pricing->name]['suspended'] = total_rows('tbl_saas_companies', array('status' => 'suspended', 'package_id' => $v_pricing->id));
                $result[$v_pricing->name]['terminated'] = total_rows('tbl_saas_companies', array('status' => 'terminated', 'package_id' => $v_pricing->id));
            }
            return $result;
        }
    }


}
