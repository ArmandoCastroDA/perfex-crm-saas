<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cms_media_model extends App_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = config_db(NULL, true);
    }

    function fetch_details($limit, $start, $st = 'img', $media_type = NULL)
    {
        $this->db->select("*");
        $this->db->like('img_name', $st);
        $this->db->like('file_type', $media_type);
        $this->db->from("tbl_saas_front_cms_media");
        $this->db->order_by("id", "DESC");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    function count_all($st = NULL, $media_type = NULL)
    {
        $this->db->like('file_type', $media_type);
        $this->db->like('img_name', $st);
        $query = $this->db->get("tbl_saas_front_cms_media");
        return $query->num_rows();
    }

    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_saas_front_cms_media');
        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

}
