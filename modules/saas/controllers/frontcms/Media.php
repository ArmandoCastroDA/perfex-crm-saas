<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Media extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('cms_media_model');
        $this->load->model('saas_model');
        $this->load->library('imageResize');
    }

    public function index()
    {

        $data['title'] = _l('media');
//        $data['dropzone'] = true;
//        $data['init'] = true;

        $this->load->view('frontcms/media/index', $data);
//        $this->load->view('_layout_main', $data);
    }

    public function pageList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_pages';
            $this->datatables->join_table = array('tbl_saas_front_pages_contents');
            $this->datatables->join_where = array('tbl_saas_front_pages_contents.page_id=tbl_saas_front_pages.pages_id');
            $this->datatables->column_search = array('title', 'tbl_saas_front_pages_contents.content_type');
            $this->datatables->order = array('tbl_saas_front_pages.pages_id' => 'asc');
            $fetch_data = make_datatables();

            $data = array();
            $edited = super_admin_access();
            $deleted = super_admin_access();
            foreach ($fetch_data as $_key => $pages) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $pages->title;
                $sub_array[] = '<a target="_blank" href="' . base_url() . $pages->url . '">' . base_url() . $pages->url . '<a>';
                if ($pages->content_type == "gallery") {
                    $sub_array[] = '<span class="label label-success">' . $pages->content_type . '</span>';
                } elseif ($pages->content_type == "events") {
                    $sub_array[] = '<span class="label label-info">' . $pages->content_type . '</span>';
                } elseif ($pages->content_type == "notice") {
                    $sub_array[] = '<span class="label label-warning">' . $pages->content_type . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-default">' .  _l("standard") . '</span>';
                }
                if (!empty($edited)) {
                    $action .= btn_edit('saas/frontcms/page/index/' . $pages->pages_id) . ' ';
                }
                if (!empty($deleted) && $pages->page_type != "default") {
                    $action .= ajax_anchor(base_url("saas/frontcms/page/delete_page/$pages->pages_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key));
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function add_media($id = null)
    {
        $data['title'] = _l('new') . ' ' .  _l('media'); //Page title
        $created = super_admin_access();
        if (!empty($created)) {
            $data['dropzone'] = true;
            $data['subview'] = $this->load->view('frontcms/media/add_media', $data, FALSE);
            $this->load->view('saas/_layout_modal_xl', $data); //page load
        } else {
            set_alert('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function save_media()
    {
        $this->cms_media_model->_table_name = "tbl_saas_front_cms_media"; //table name
        $this->cms_media_model->_primary_key = "id";
        $created = super_admin_access();
        if (!empty($created)) {
            $upload_file = array();
            $files = $this->input->post("files", true);
            $target_path = module_dir_path(SaaS_MODULE) . "uploads/";
            if (!empty($files) && is_array($files)) {
                foreach ($files as $key => $file) {
                    if (!empty($file)) {
                        $file_name = $this->input->post('file_name_' . $file, true);
                        $new_file_name = move_temp_file($file_name, $target_path);
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $extention = _mime_content_type($target_path . $new_file_name);
                        if (!empty($new_file_name)) {
                            $file_type = explode('/', $extention);
                        }
                        $size = $this->input->post('file_size_' . $file, true) / 1000;
                        if ($new_file_name) {
                            $up_data = array(
                                "fileName" => $new_file_name,
                                "path" => "modules/frontcms/uploads/",
                                "fullPath" => $target_path . $new_file_name,
                                "ext" => $extention,
                                "size" => round($size, 2),
                                "is_image" => $is_image,
                                "file_type" => (!empty($file_type[0]) ? $file_type[0] : ''),
                            );
                            array_push($upload_file, $up_data);
                        }
                    }
                }
            }
            if (!empty($upload_file)) {
                foreach ($upload_file as $u_value) {
                    $data = array(
                        'dir_path' => $u_value["path"],
                        'img_name' => $u_value["fileName"],
                        'file_type' => $u_value["file_type"],
                        'file_ext' => $u_value["ext"],
                        'file_size' => $u_value["size"],
                        'thumb_name' => $u_value["fileName"],
                        'thumb_path' => $u_value["path"]
                    );
                    $id = $this->cms_media_model->save($data);
                }
            }

            // youtube video
            $video_url = $this->input->post('vid_url', true);
            if (!empty($video_url)) {
                $this->addVideo($video_url);
            }
            if (!empty($id)) {
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'media',
                    'module_field_id' => $id,
                    'activity' => "add_media",
                    'icon' => 'fa-ticket',
                    'value1' => 'Save Media image',
                );
                $this->cms_media_model->_table_name = "tbl_activities"; //table name
                $this->cms_media_model->_primary_key = "activities_id";
                $this->cms_media_model->save($activities);
            }
            // messages for user
            $type = "success";
            $message = _l('add') . ' ' .  _l('media');
            set_alert($type, $message);

            redirect('saas/frontcms/media');
        }
    }

    public function addVideo($video_url = null)
    {
        $youtube = "https://www.youtube.com/oembed?url=" . $video_url . "&format=json";
        $curl = curl_init($youtube);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode == 200) {
            $upload_response = $this->imageresize->resizeVideoImg($return);
            if ($upload_response) {
                $upload_response = json_decode($upload_response);
                $data = array(
                    'vid_url' => $video_url,
                    'vid_title' => $upload_response->vid_title,
                    'img_name' => $upload_response->store_name,
                    'file_type' => $upload_response->file_type,
                    'file_size' => $upload_response->file_size,
                    'thumb_name' => $upload_response->store_name,
                    'thumb_path' => $upload_response->thumb_path,
                    'dir_path' => $upload_response->dir_path,
                );

                $this->cms_media_model->_table_name = "tbl_saas_front_cms_media"; //table name
                $this->cms_media_model->_primary_key = "id";
                $this->cms_media_model->save($data);
            }
        }
    }

    public function getPage()
    {
        $keyword = $this->input->post('keyword', true);
        $file_type = $this->input->post('file_type', true);
        $is_gallery = $this->input->post('is_gallery', true);
        if (!isset($is_gallery)) {
            $is_gallery = 1;
        }
        $this->load->model("cms_media_model");
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = "#";
        $config["total_rows"] = $this->cms_media_model->count_all($keyword, $file_type);
        $config["per_page"] = 10;
        $config["uri_segment"] = 5;
        $config["use_page_numbers"] = TRUE;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';
        $config['next_link'] = '&gt;';
        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_link"] = "&lt;";
        $config["prev_tag_open"] = "<li>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='active'><a href='#'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li>";
        $config["num_tag_close"] = "</li>";
        $config["num_links"] = 1;
        $this->pagination->initialize($config);
        $page = $this->uri->segment(5);
        $start = ($page - 1) * $config["per_page"];
        $result = $this->cms_media_model->fetch_details($config["per_page"], $start, $keyword, $file_type);
        $img_data = array();
        $check_empty = 0;
        if (!empty($result)) {
            $check_empty = 1;
            foreach ($result as $res_value) {
                $div = $this->genrateDiv($res_value, $is_gallery);
                $img_data[] = $div;
            }
        }
        $output = array(
            'pagination_link' => $this->pagination->create_links(),
            'result_status' => $check_empty,
            'result' => $img_data,
        );
        echo json_encode($output);
        exit();
    }

    public function genrateDiv($result, $is_gallery)
    {
        $is_image = "0";
        $is_video = "0";
        if ($result->file_type == 'image') {
            $file = base_url() . $result->dir_path . $result->img_name;
            $file_src = base_url() . $result->dir_path . $result->img_name;
            $is_image = 1;
        } elseif ($result->file_type == 'video') {
            $file = base_url() . $result->thumb_path . $result->img_name;
            $file_src = $result->vid_url;
            $is_video = 1;
        } elseif ($result->file_ext == 'text/plain') {
            $file = base_url('modules/frontcms/assets/img/txticon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_ext == 'application/zip' || $result->file_ext == 'application/x-rar') {
            $file = base_url('modules/frontcms/assets/img/zipicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_ext == 'application/pdf') {
            $file = base_url('modules/frontcms/assets/img/pdficon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_ext == 'application/msword') {
            $file = base_url('modules/frontcms/assets/img/wordicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_ext == 'application/vnd.openxmlformats-officedocument.word') {
            $file = base_url('modules/frontcms/assets/img/wordicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_ext == 'application/vnd.ms-excel') {
            $file = base_url('modules/frontcms/assets/img/excelicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } else {
            $file = base_url('modules/frontcms/assets/img/docicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        }
        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_" . $result->id . "'>";
        $output .= "<div class='fadeoverlay'>";
        $output .= "<div class='fadeheight'>";
        $output .= "<img class='' data-fid='" . $result->id . "' data-content_type='" . $result->file_ext . "' data-content_name='" . $result->img_name . "' data-is_image='" . $is_image . "' data-vid_url='" . $result->vid_url . "' data-img='" . base_url() . $result->dir_path . $result->img_name . "' src='" . $file . "'>";
        $output .= "</div>";
        if ($is_video == 1) {
            $output .= "<i class='fa fa-youtube-play videoicon'></i>";
        }
        if ($is_image == 1) {
            $output .= "<i class='fa fa-picture-o videoicon'></i>";
        }
        if (!$is_gallery) {
            $output .= "<div class='overlay3'>";
            $output .= "<a href='#' class='uploadcheckbtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#detail' data-image='" . $file . "' data-source='" . $file_src . "' data-media_name='" . $result->img_name . "' data-media_size='" . $result->file_size . "' data-media_type='" . $result->file_ext . "'><i class='fa fa-navicon'></i></a>";
            $output .= "<a href='#' class='uploadclosebtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
            $output .= "<p class='processing'>Processing...</p>";
            $output .= "</div>";
        }
        if ($is_video == 1) {
            $output .= "<p class=''>" . $result->vid_title . "</p>";
        } else {
            $output .= "<p class=''>" . $result->img_name . "</p>";
        }
        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }


    public function deleteItem()
    {
        $record_id = $this->input->post('record_id', true);
        $record = get_row('tbl_saas_front_cms_media', array('id' => $record_id));
        $this->cms_media_model->_table_name = "tbl_saas_front_cms_media"; //table name
        $this->cms_media_model->_primary_key = "id";
        if (!empty($record)) {
            remove_files($record->img_name, $record->dir_path);
            $this->cms_media_model->delete($record_id);
            // messages for user
            $type = "success";
            $message = _l('delete') . ' ' .  _l('media');
        } else {
            $type = "error";
            $message = _l('no_record_found');
        }
        $data['status'] = $type;
        $data['msg'] = $message;
        echo json_encode($data);
        exit();
    }
}
