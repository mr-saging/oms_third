<?php
class Order_model extends CI_Model {

    public function get_orders($limit, $start, $search = '', $store = '', $status = '') {
        $this->db->limit($limit, $start);

        if ($search) {
            $this->db->like('customer_name', $search);
        }
        if ($store) {
            $this->db->where('store', $store);
        }
        if ($status) {
            $this->db->where('status', $status);
        }

        $query = $this->db->get('orders');
        return $query->result();
    }

    public function count_orders($search = '', $store = '', $status = '') {
        if ($search) {
            $this->db->like('customer_name', $search);
        }
        if ($store) {
            $this->db->where('store', $store);
        }
        if ($status) {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results('orders');
    }
}
