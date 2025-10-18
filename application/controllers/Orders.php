<?php
class Orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->library('pagination');
    }

    public function index() {
        // --- GET filters from query string ---
        $search = $this->input->get('search');
        $store  = $this->input->get('store');
        $status = $this->input->get('status');

        // --- Pagination Config ---
        $config = array();
        $config['base_url'] = site_url('orders/index');
        $config['total_rows'] = $this->Order_model->count_orders($search, $store, $status);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Styling (Bootstrap 5)
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);

        $page = ($this->input->get('page')) ? $this->input->get('page') : 0;

        // --- Get filtered and paginated results ---
        $data['orders'] = $this->Order_model->get_orders($config['per_page'], $page, $search, $store, $status);
        $data['pagination'] = $this->pagination->create_links();

        // --- Pass filters back to view ---
        $data['search'] = $search;
        $data['store'] = $store;
        $data['status'] = $status;

        $this->load->view('orders_view', $data);
    }
}
