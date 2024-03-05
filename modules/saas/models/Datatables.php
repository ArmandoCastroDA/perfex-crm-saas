<?php

/**
 * Description of Project_Model
 *
 * @author NaYeM
 */
class Datatables extends App_Model
{

    public $table;
    public $select;
    public $join_table;
    public $join_where;
    public $equal_where;
    public $column_order; //set column field database for datatable orderable
    public $column_search; //set column field database for datatable searchable just firstname , lastname , address are searchable
    public $order; // default order
    public $col;
    public $selectSum;
    public $group_by;
    public $groupBy;
    public $colId;

    function make_query()
    {

        if (!empty($this->select)) {
            if (is_array($this->select)) {
                foreach ($this->select as $select) {
                    $this->db->select($select, FALSE);
                }
            } else {
                $this->db->select($this->select);
            }
        }
        $this->db->from($this->table);

        if (!is_null($this->join_table) && !is_null($this->join_where)) {
            for ($i = 0; $i < count($this->join_table); $i++) {
                $this->db->join($this->join_table[$i], $this->join_where[$i], 'left');
            }
        }
        $i = 0;

        if (!empty($this->column_search)) {
            foreach ($this->column_search as $item) // loop column
            {
                if ($_POST['search']['value']) // if datatable send POST for search
                {
                    if ($i === 0) // first loop
                    {
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }
                    if (count($this->column_search) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }
        }

        if (!empty($this->selectSum)) {
            $this->db->select_sum($this->selectSum);
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            foreach ($this->order as $key => $order) {
                $this->db->order_by($key, $order);
            }
        }
        if (isset($this->groupBy)) {
            $this->db->group_by($this->groupBy);
        }

    }

    function get_filtered_data($where = null, $where_in = null)
    {
        $this->make_query();

        if (!empty($where) && is_numeric($where)) {
            $userid = $where;
        } else {
            $_where = $where;
        }
        if (!empty($_where)) {
            $this->db->where($_where);
        }
        if (!empty($where_in)) {
            $this->db->where_in($where_in[0], $where_in[1]);
        }

        //$query = $this->db->get();
        //return $query->num_rows();
        return $this->db->count_all_results();
    }

    function get_all_data($where = null)
    {

        // $this->db->select(count(1)); //added by Tawfique
        $this->db->from($this->table);
        if (!empty($where) && is_numeric($where)) {
            $userid = $where;
        } else {
            $_where = $where;
        }
        if (!empty($_where)) {
            $this->db->where($_where);
        }
        return $this->db->count_all_results();
    }

    public function get_all_project($filterBy = null, $search_by = null)
    {
        $where = array();
        if (empty($filterBy)) {
            $where = array('project_status !=' => 'completed');
        }
        //        foreach ($all_projects as $v_projects) {
        if (!empty($search_by)) {
            if ($search_by == 'by_client') {
                $where = array('tbl_project.client_id' => $filterBy, 'project_status !=' => 'completed');
            }
            if ($search_by == 'by_staff') {
                if ($filterBy == 'everyone') {
                    $where = array('permission' => 'all');
                } else {
                    $where = $filterBy;
                }
            }
        } else {
            if ($filterBy == 'overdue') {
                $where = array('UNIX_TIMESTAMP(end_date) <' => strtotime(date('Y-m-d')), 'project_status !=' => 'completed');
            } elseif (!empty($filterBy)) {
                $where = array('project_status' => $filterBy);
            }
        }
        $all_projects = $this->get_datatable_permission($where);
        return $all_projects;
    }

    public function get_tasks($filterBy = null, $search_by = null)
    {
        $where = array();
        if (empty($filterBy) && !empty(admin())) {
            $where = array('task_status !=' => 'completed');
        }
        if (!empty($search_by)) {
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_opportunity') {
                $where = array('opportunities_id' => $filterBy);
            }
            if ($search_by == 'by_goal') {
                $where = array('goal_tracking_id' => $filterBy);
            }
            if ($search_by == 'by_leads') {
                $where = array('leads_id' => $filterBy);
            }
            if ($search_by == 'by_bug') {
                $where = array('bug_id' => $filterBy);
            }
            if ($search_by == 'by_staff') {
                if ($filterBy == 'everyone') {
                    $where = array('permission' => 'all');
                } else {
                    $where = $filterBy;
                }
            }
        } else {
            if ($filterBy == 'billable') {
                $where = array('billable' => 'Yes');
            } elseif ($filterBy == 'not_billable') {
                $where = array('billable' => 'No');
            } elseif ($filterBy == 'assigned_to_me') {
                $user_id = get_staff_user_id();
                $where = $user_id;
            } elseif (!empty($filterBy)) {
                $where = array('task_status' => $filterBy);
            }
        }
        return array_reverse($this->get_datatable_permission($where));
    }

    public function get_bugs($filterBy = null, $search_by = null)
    {
        $where = array();
        if (!empty($search_by)) {
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_opportunity') {
                $where = array('opportunities_id' => $filterBy);
            }
            if ($search_by == 'from_reporter') {
                $where = array('reporter' => $filterBy);
            }
            if ($search_by == 'by_staff') {
                if ($filterBy == 'everyone') {
                    $where = array('permission' => 'all');
                } else {
                    $where = $filterBy;
                }
            }
        } else {
            if ($filterBy == 'assigned_to_me') {
                $user_id = get_staff_user_id();
                $where = $user_id;
            } else if (!empty($filterBy)) {
                $where = array('bug_status' => $filterBy);
            }
        }
        return array_reverse($this->get_datatable_permission($where));
    }

    public function get_leads($filterBy = null, $search_by = null)
    {
        $where = array();
        if (!empty($search_by)) {
            if ($search_by == 'by_status') {
                $where = array('lead_status_id' => $filterBy);
            }
            if ($search_by == 'by_source') {
                $where = array('lead_source_id' => $filterBy);
            }
        } else {
            if ($filterBy == 'assigned_to_me') {
                $user_id = get_staff_user_id();
                $where = $user_id;
            }
            if ($filterBy == 'everyone') {
                $where = array('permission' => 'all');
            }
        }
        $all_leads_info = array_reverse($this->get_datatable_permission($where));
        return $all_leads_info;
    }

    public function get_invoices($filterBy = null, $search_by = null)
    {

        $where = array();
        if (!empty($search_by)) {
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_agent') {
                $where = array('user_id' => $filterBy);
            }
            if ($search_by == 'by_client') {
                $where = array('tbl_invoices.client_id' => $filterBy);
            }
            if ($search_by == 'by_client_draft') {
                $where = array('tbl_invoices.client_id' => $filterBy, 'status !=' => 'draft');
            }
            if ($filterBy == 'by_client_recurring') {
                $where = array('tbl_invoices.client_id' => $filterBy, 'recurring' => 'Yes');
            }
        } else {
            if ($filterBy == 'recurring') {
                $where = array('recurring' => 'Yes');
            } else if ($filterBy == 'paid') {
                $where = array('status' => 'Paid');
            } else if ($filterBy == 'not_paid') {
                $where = array('status' => 'Unpaid');
            } else if ($filterBy == 'draft') {
                $where = array('status' => 'draft');
            } else if ($filterBy == 'partially_paid') {
                $where = array('status' => 'partially_paid');
            } else if ($filterBy == 'cancelled') {
                $where = array('status' => 'Cancelled');
            } else if ($filterBy == 'overdue') {
                $where = array('UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')), 'status !=' => 'Paid');
            } else if ($filterBy == 'last_month' || $filterBy == 'this_months') {
                if ($filterBy == 'last_month') {
                    $month = date('Y-m', strtotime('-1 months'));
                } else {
                    $month = date('Y-m');
                }
                $where = array('invoice_month' => $month);
            } else if (strstr($filterBy, '_')) {
                $year = str_replace('_', '', $filterBy);
                $where = array('invoice_year' => $year);
            }
        }
        $all_invoice = array_reverse($this->get_datatable_permission($where));
        return $all_invoice;
    }

    public function get_client_invoices($filterBy = null)
    {
        $where_in = null;
        $where = null;
        $client_id = $this->session->userdata('client_id');
        if (empty($filterBy)) {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status !=' => 'draft');
        }
        if ($filterBy == 'recurring') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status !=' => 'draft', 'recurring' => 'Yes');
        } else if ($filterBy == 'paid') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status' => 'Paid');
        } else if ($filterBy == 'not_paid') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status' => 'Unpaid');
        } else if ($filterBy == 'partially_paid') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status' => 'partially_paid');
        } else if ($filterBy == 'cancelled') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status' => 'Cancelled');
        } else if ($filterBy == 'overdue') {
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
            $status = array('partially_paid', 'Unpaid', 'Cancelled');
            $where_in = array('status', $status);
        } else if ($filterBy == 'last_month' || $filterBy == 'this_months') {
            if ($filterBy == 'last_month') {
                $month = date('Y-m', strtotime('-1 months'));
            } else {
                $month = date('Y-m');
            }
            $where = array('show_client' => 'Yes', 'client_id' => $client_id, 'status !=' => 'draft', 'invoice_month' => $month);
        }
        $all_invoice = make_datatables($where, $where_in);
        return $all_invoice;
    }

    public function get_client_estimates($filterBy = null, $client_id = null)
    {
        $where_in = null;
        $where = null;
        $client_id = $this->session->userdata('client_id');
        if (empty($filterBy)) {
            $where = array('client_id' => $client_id, 'status !=' => 'draft');
        }
        if ($filterBy == 'last_month' || $filterBy == 'this_months') {
            if ($filterBy == 'last_month') {
                $month = date('Y-m', strtotime('-1 months'));
            } else {
                $month = date('Y-m');
            }
            $where = array('client_id' => $client_id, 'status !=' => 'draft', 'estimate_month' => $month);
        } else if ($filterBy == 'expired') {
            $where = array('client_id' => $client_id, 'status' => 'pending', 'UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
        } else if (strstr($filterBy, '_')) {
            $year = str_replace('_', '', $filterBy);
            $where = array('client_id' => $client_id, 'status !=' => 'draft', 'estimate_year' => $year);
        } else if (!empty($filterBy)) {
            $where = array('client_id' => $client_id, 'status !=' => 'draft', 'status' => $filterBy);
        }
        $all_estimate = make_datatables($where);;
        return $all_estimate;
    }

    public function get_estimates($filterBy = null, $search_by = null)
    {
        $where = array();
        $where_in = null;
        if (!empty($search_by)) {
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_agent') {
                $where = array('user_id' => $filterBy);
            }
            if ($search_by == 'by_client') {

                $where = array('tbl_estimates.client_id' => $filterBy);
            }
        } else {
            if ($filterBy == 'last_month' || $filterBy == 'this_months') {

                if ($filterBy == 'last_month') {
                    $month = date('Y-m', strtotime('-1 months'));
                } else {
                    $month = date('Y-m');
                }
                $where = array('estimate_month' => $month);
            } else if ($filterBy == 'expired') {
                $where = array('UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
                $status = array('draft', 'pending');
                $where_in = array('status', $status);
            } else if (strstr($filterBy, '_')) {
                $year = str_replace('_', '', $filterBy);
                $where = array('estimate_year' => $year);
            } else if (!empty($filterBy) && $filterBy != 'all') {
                $where = array('status' => $filterBy);
            }
        }
        $all_estimate = $this->get_datatable_permission($where, $where_in);
        return $all_estimate;
    }

    public function get_credit_note($filterBy = null, $search_by = null)
    {
        $where = array();
        $where_in = null;
        if (!empty($search_by)) {
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_agent') {
                $where = array('user_id' => $filterBy);
            }
            if ($search_by == 'by_client') {

                $where = array('tbl_credit_note.client_id' => $filterBy);
            }
        } else {
            if ($filterBy == 'last_month' || $filterBy == 'this_months') {

                if ($filterBy == 'last_month') {
                    $month = date('Y-m', strtotime('-1 months'));
                } else {
                    $month = date('Y-m');
                }
                $where = array('credit_note_month' => $month);
            } else if ($filterBy == 'expired') {
                $where = array('UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
                $status = array('draft', 'pending');
                $where_in = array('status', $status);
            } else if (strstr($filterBy, '_')) {
                $year = str_replace('_', '', $filterBy);
                $where = array('credit_note_year' => $year);
            } else if (!empty($filterBy)) {
                $where = array('status' => $filterBy);
            }
        }
        $all_estimate = $this->get_datatable_permission($where, $where_in);
        return $all_estimate;
    }

    public function get_payment($filterBy = null, $search_by = null)
    {
        $where = array();
        if (!empty($search_by)) {
            if ($search_by == 'by_invoice') {
                $where = array('tbl_payments.invoices_id' => $filterBy);
            }
            if ($search_by == 'by_account') {
                $where = array('account_id' => $filterBy);
            }
            if ($search_by == 'by_client') {
                $where = array('tbl_payments.paid_by' => $filterBy);
            }
        } else {
            if ($filterBy == 'last_month' || $filterBy == 'this_months') {

                if ($filterBy == 'last_month') {
                    $month = date('m', strtotime('-1 months'));
                    $year = date('Y', strtotime('-1 months'));
                } else {
                    $month = date('m');
                    $year = date('Y');
                }
                $where = array('year_paid' => $year, 'month_paid' => $month);
            } else if ($filterBy == 'today') {

                $where = array('UNIX_TIMESTAMP(payment_date)' => strtotime(date('Y-m-d')));
            } else if (strstr($filterBy, '_')) {
                $year = str_replace('_', '', $filterBy);
                $where = array('year_paid' => $year);
            }
        }
        $all_payments = make_datatables($where);
        return array_reverse($all_payments);
    }

    public function get_proposals($filterBy = null, $search_by = null)
    {
        $where = null;
        $where_in = null;
        if (!empty($search_by)) {
            if ($search_by == 'by_invoice') {
                $where = array('convert' => 'Yes', 'convert_module' => 'invoice', 'convert_module_id' => $filterBy);
            }
            if ($search_by == 'by_agent') {
                $where = array('user_id' => $filterBy);
            }
            if ($search_by == 'by_estimates') {
                $where = array('convert' => 'Yes', 'convert_module' => 'estimate', 'convert_module_id' => $filterBy);
            }
        } else {
            if ($filterBy == 'last_month' || $filterBy == 'this_months') {
                if ($filterBy == 'last_month') {
                    $month = date('Y-m', strtotime('-1 months'));
                } else {
                    $month = date('Y-m');
                }
                $where = array('proposal_month' => $month);
            } else if ($filterBy == 'expired') {
                $where = array('UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
                $status = array('draft', 'pending');
                $where_in = array('status', $status);
            } else if (strstr($filterBy, '_')) {
                $year = str_replace('_', '', $filterBy);

                $where = array('proposal_year' => $year);
            } else if (!empty($filterBy)) {
                $where = array('status' => $filterBy);
            }
        }
        $all_proposal = array_reverse($this->get_datatable_permission($where, $where_in));
        return $all_proposal;
    }

    public function get_client_proposals($filterBy = null)
    {
        $where_in = null;
        $where = array('status !=' => 'draft', 'module' => 'client', 'module_id' => $this->session->userdata('client_id'));
        // get all invoice
        if ($filterBy == 'last_month' || $filterBy == 'this_months') {
            if ($filterBy == 'last_month') {
                $month = date('Y-m', strtotime('-1 months'));
            } else {
                $month = date('Y-m');
            }
            $where = array('status !=' => 'draft', 'module' => 'client', 'module_id' => $this->session->userdata('client_id'), 'proposal_month' => $month);
        } else if ($filterBy == 'expired') {
            $where = array('status' => 'pending', 'module' => 'client', 'module_id' => $this->session->userdata('client_id'), 'UNIX_TIMESTAMP(due_date) <' => strtotime(date('Y-m-d')));
        } else if (!empty($filterBy)) {
            $where = array('module' => 'client', 'module_id' => $this->session->userdata('client_id'), 'status' => $filterBy);
        }
        $all_proposal = make_datatables($where);
        return $all_proposal;
    }

    public function get_tickets($filterBy = null, $search_by = null)
    {
        $where = null;
        if (empty($filterBy) && !empty(admin())) {
            $where = array('status !=' => 'closed');
        }
        if (!empty($search_by)) {
            if ($search_by == 'by_reported') {
                $where = array('reporter' => $filterBy);
            }
            if ($search_by == 'by_project') {
                $where = array('project_id' => $filterBy);
            }
            if ($search_by == 'by_department') {
                $where = array('tbl_tickets.departments_id' => $filterBy);
            }
        } else {
            if ($filterBy == 'assigned_to_me') {
                $user_id = get_staff_user_id();
                $where = $user_id;
            } else if ($filterBy == 'everyone') {
                $where = array('permission' => 'all');
            } elseif (!empty($filterBy)) {
                $where = array('status' => $filterBy);
            }
        }
        $all_tickets = array_reverse($this->get_datatable_permission($where));
        return $all_tickets;
    }


    public function get_deposit($filterBy = null, $type = null)
    {
        $where = null;
        if (empty($type)) {
            $where = array('type' => 'Income');
        }
        if ($type == 'by_account') {
            $where = array('type' => 'Income', 'tbl_transactions.account_id' => $filterBy);
        } else if ($type == 'by_category') {
            $where = array('type' => 'Income', 'category_id' => $filterBy);
        }
        $all_deposits = array_reverse($this->get_datatable_permission($where));
        return $all_deposits;
    }

    public function get_expense($filterBy = null, $type = null)
    {
        $where = null;
        if (empty($type)) {
            $where = array('type' => 'Expense');
        }
        if ($type == 'by_account') {
            $where = array('type' => 'Expense', 'tbl_transactions.account_id' => $filterBy);
        } else if ($type == 'by_category') {
            $where = array('type' => 'Expense', 'category_id' => $filterBy);
        }
        $all_expense = array_reverse($this->get_datatable_permission($where));
        return $all_expense;
    }


    public function get_transfer($filterBy = null, $type = null)
    {
        $where = null;
        if ($type == 'to_account') {
            $where = array('to_account_id' => $filterBy);
        } else if ($type == 'from_account') {
            $where = array('from_account_id' => $filterBy);
        }
        $all_transfer = array_reverse($this->get_datatable_permission($where));
        return $all_transfer;
    }

    public function get_transactions_report($filterBy = null)
    {
        $where = null;
        if (!empty($filterBy)) {
            $where = array('tbl_transactions.account_id' => $filterBy);
        }
        $all_transactions_report = array_reverse($this->get_datatable_permission($where));
        return $all_transactions_report;
    }

    public function get_assign_stocklist($id = NULL, $type = NULL)
    {
        $where = null;

        if (!empty($type)) {
            if ($type == 'by_item_name') {
                $where = array('stock_id' => $id);
            }
            if ($type == 'by_employee') {
                $where = array('user_id' => $id);
            }
            if ($type == 'by_sub_category') {
                $where = array('stock_sub_category_id' => $id);
            }
        }

        $assign_stocklist = make_datatables($where);

        if (!empty($assign_stocklist)) {
            foreach ($assign_stocklist as $stocklist) {
                $stock_info = get_row('tbl_stock', array('stock_id' => $stocklist->stock_id));
                if (!empty($stock_info)) {
                    $stock_sub_info = get_row('tbl_stock_sub_category', array('stock_sub_category_id' => $stock_info->stock_sub_category_id));
                    if (!empty($stock_sub_info)) {
                        $stock_category = get_row('tbl_stock_category', array('stock_category_id' => $stock_sub_info->stock_category_id));
                    }
                }
                $stocklist->stock_sub_category_id = (!empty($stock_info->stock_sub_category_id) ? $stock_info->stock_sub_category_id : '-');
                $stocklist->item_name = (!empty($stock_info->item_name) ? $stock_info->item_name : '-');
                $stocklist->total_stock = (!empty($stock_info->total_stock) ? $stock_info->total_stock : '-');
                $stocklist->total_stock = (!empty($stock_info->total_stock) ? $stock_info->total_stock : '-');
                $stocklist->stock_category_id = (!empty($stock_sub_info->stock_category_id) ? $stock_sub_info->stock_category_id : '-');
                $stocklist->stock_sub_category = (!empty($stock_sub_info->stock_sub_category) ? $stock_sub_info->stock_sub_category : '-');
                $stocklist->stock_category = (!empty($stock_category->stock_category) ? $stock_category->stock_category : '-');
                $stocklist->fullname = fullname($stocklist->user_id);
            }
        }
        return $assign_stocklist;
    }

    public function get_all_clock_history()
    {
        $all_clock_history = make_datatables();
        if (!empty($all_clock_history)) {
            foreach ($all_clock_history as $clock_history) {
                $clock_info = get_row('tbl_clock', array('clock_id' => $clock_history->clock_id));
                $staff_info = get_staff_details($clock_history->user_id);
                $clock_history->attendance_id = (!empty($clock_info->attendance_id) ? $clock_info->attendance_id : '-');
                $clock_history->clockin_time = (!empty($clock_info->clockin_time) ? $clock_info->clockin_time : '-');
                $clock_history->clockout_time = (!empty($clock_info->clockout_time) ? $clock_info->clockout_time : '-');
                $clock_history->comments = (!empty($clock_info->comments) ? $clock_info->comments : '-');
                $clock_history->clocking_status = (!empty($clock_info->clocking_status) ? $clock_info->clocking_status : '-');
                $clock_history->ip_address = (!empty($clock_info->ip_address) ? $clock_info->ip_address : '-');

                $clock_history->employment_id = (!empty($staff_info->employment_id) ? $staff_info->employment_id : '-');
                $clock_history->fullname = (!empty($staff_info->fullname) ? $staff_info->fullname : '-');
            }
        }
        return $all_clock_history;
    }

    public function get_job_application_info($id = NULL, $flag = null)
    {
        $this->db->select('tbl_job_appliactions.*', FALSE);
        $this->db->select('tbl_job_circular.*', FALSE);
        $this->db->from('tbl_job_appliactions');
        $this->db->join('tbl_job_circular', 'tbl_job_circular.job_circular_id = tbl_job_appliactions.job_circular_id', 'left');
        $this->db->group_start();
        if (isset($_POST['search']['value'])) {
            $this->db->like('tbl_job_circular.job_title', $_POST['search']['value']);
            $this->db->or_like('tbl_job_appliactions.name', $_POST['search']['value']);
            $this->db->or_like('tbl_job_appliactions.email', $_POST['search']['value']);
            $this->db->or_like('tbl_job_appliactions.mobile', $_POST['search']['value']);
            $this->db->or_like('tbl_job_appliactions.apply_date', $_POST['search']['value']);
            $this->db->or_like('tbl_job_appliactions.application_status', $_POST['search']['value']);
        }
        $this->db->group_end();
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_emp_salary_list($id = NULL, $designation_id = NULL)
    {
        $this->db->select('tbl_employee_payroll.*', FALSE);
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->select('tbl_salary_template.*', FALSE);
        $this->db->select('tbl_hourly_rate.*', FALSE);
        $this->db->select('tbl_designations.*', FALSE);
        $this->db->select('tbl_departments.deptname', FALSE);
        $this->db->from('tbl_employee_payroll');
        $this->db->join('tbl_account_details', 'tbl_employee_payroll.user_id = tbl_account_details.user_id', 'left');
        $this->db->join('tbl_salary_template', 'tbl_employee_payroll.salary_template_id = tbl_salary_template.salary_template_id', 'left');
        $this->db->join('tbl_hourly_rate', 'tbl_employee_payroll.hourly_rate_id = tbl_hourly_rate.hourly_rate_id', 'left');
        $this->db->join('tbl_designations', 'tbl_designations.designations_id  = tbl_account_details.designations_id', 'left');
        $this->db->join('tbl_departments', 'tbl_departments.departments_id  = tbl_designations.departments_id', 'left');
        $this->db->group_start();
        if (isset($_POST['search']['value'])) {
            $this->db->like('tbl_account_details.employment_id', $_POST['search']['value']);
            $this->db->or_like('tbl_account_details.fullname', $_POST['search']['value']);
            $this->db->or_like('tbl_hourly_rate.hourly_grade', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_template.salary_grade', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_template.basic_salary', $_POST['search']['value']);
            $this->db->or_like('tbl_hourly_rate.hourly_rate', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_template.overtime_salary', $_POST['search']['value']);
        }
        $this->db->group_end();
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_salary_payment_info($salary_payment_id, $result = NULL, $search_type = null)
    {

        $this->db->select('tbl_salary_payment.*', FALSE);
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->select('tbl_designations.*', FALSE);
        $this->db->select('tbl_departments.deptname', FALSE);
        $this->db->from('tbl_salary_payment');
        $this->db->join('tbl_account_details', 'tbl_salary_payment.user_id = tbl_account_details.user_id', 'left');
        $this->db->join('tbl_designations', 'tbl_designations.designations_id  = tbl_account_details.designations_id', 'left');
        $this->db->join('tbl_departments', 'tbl_departments.departments_id  = tbl_designations.departments_id', 'left');
        $this->db->group_start();
        if (isset($_POST['search']['value'])) {
            $this->db->like('tbl_account_details.employment_id', $_POST['search']['value']);
            $this->db->or_like('tbl_account_details.fullname', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_payment.paid_date', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_payment.fine_deduction', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_payment.payment_month', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_payment.payment_type', $_POST['search']['value']);
            $this->db->or_like('tbl_salary_payment.comments', $_POST['search']['value']);
        }
        $this->db->group_end();

        if (!empty($search_type)) {
            if ($search_type == 'employee') {
                $this->db->where("tbl_salary_payment.user_id", $salary_payment_id);
            } elseif ($search_type == 'month') {
                $this->db->where("tbl_salary_payment.payment_month", $salary_payment_id);
            } elseif ($search_type == 'period') {
                $this->db->where("tbl_salary_payment.payment_month >=", $salary_payment_id['start_month']);
                $this->db->where("tbl_salary_payment.payment_month <=", $salary_payment_id['end_month']);
            }
        } else {
            $this->db->where("tbl_salary_payment.salary_payment_id", $salary_payment_id);
        }
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query_result = $this->db->get();
        if (!empty($result)) {
            $result = $query_result->result();
        } else {
            $result = $query_result->row();
        }
        return $result;
    }

    public function my_advance_salary_info($all = null)
    {
        $this->db->select('tbl_advance_salary.*', FALSE);
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->from('tbl_advance_salary');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_advance_salary.user_id', 'left');
        if (!empty($all)) {
            $this->db->order_by('tbl_advance_salary.request_date', "DESC");
        } else {
            $this->db->where('tbl_advance_salary.user_id', $this->session->userdata('user_id'));
        }
        $this->db->group_start();
        if (!empty($_POST['search']['value'])) {
            $this->db->like('tbl_account_details.employment_id', $_POST['search']['value']);
            $this->db->or_like('tbl_account_details.fullname', $_POST['search']['value']);
            $this->db->or_like('tbl_advance_salary.advance_amount', $_POST['search']['value']);
            $this->db->or_like('tbl_advance_salary.deduct_month', $_POST['search']['value']);
            $this->db->or_like('tbl_advance_salary.request_date', $_POST['search']['value']);
            $this->db->or_like('tbl_advance_salary.reason', $_POST['search']['value']);
            $this->db->or_like('tbl_advance_salary.status', $_POST['search']['value']);
        }
        $this->db->group_end();
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_employee_award_by_id($id = NULL, $user = null)
    {

        $this->db->select('tbl_employee_award.*', FALSE);
        $this->db->select('tbl_account_details.*', FALSE);
        $this->db->from('tbl_employee_award');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_employee_award.user_id', 'left');
        $this->db->group_start();
        if (!empty($_POST['search']['value'])) {
            $this->db->like('tbl_account_details.employment_id', $_POST['search']['value']);
            $this->db->or_like('tbl_account_details.fullname', $_POST['search']['value']);
            $this->db->or_like('tbl_employee_award.award_name', $_POST['search']['value']);
            $this->db->or_like('tbl_employee_award.gift_item', $_POST['search']['value']);
            $this->db->or_like('tbl_employee_award.award_amount', $_POST['search']['value']);
            $this->db->or_like('tbl_employee_award.award_date', $_POST['search']['value']);
            $this->db->or_like('tbl_employee_award.given_date', $_POST['search']['value']);
        }
        $this->db->group_end();
        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        if (!empty($id) && empty($user)) {
            $this->db->where('tbl_employee_award.employee_award_id', $id);
            $query_result = $this->db->get();
            $result = $query_result->row();
        } elseif (!empty($id) && !empty($user)) {
            $this->db->where('tbl_employee_award.user_id', $id);
            $this->db->order_by('tbl_employee_award.user_id', 'DESC');
            $query_result = $this->db->get();
            $result = $query_result->result();
        } else {
            $this->db->order_by('tbl_employee_award.employee_award_id', 'DESC');
            $query_result = $this->db->get();
            $result = $query_result->result();
        }
        return $result;
    }

    public function get_goal_tracking($where)
    {
        $all_goal_tracking = array_reverse($this->get_datatable_permission($where));
        return $all_goal_tracking;
    }

    public function get_user($where = null)
    {
        $all_users = array_reverse($this->get_datatable_permission($where));
        return $all_users;
    }

    public function get_datatable_permission($user_id = null, $where_in = null)
    {
        if (empty($user_id)) {
            $user_id = get_staff_user_id();
        } else {
            if (is_numeric($user_id)) {
                $userid = $user_id;
            } else {
                $where = $user_id;
            }
        }
        $this->make_query();
        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($where_in)) {
            $this->db->where_in($where_in[0], $where_in[1]);
        }

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
}