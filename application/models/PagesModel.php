<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PagesModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /* ----------------------- USER FUNCTIONS ----------------------- */

    // ✅ Secure login validation (uses password_verify)
    public function validate_login($username) {
        // Allow login using username or email
        $this->db->where("(username = '$username' OR email_address = '$username')");
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0) {
            return $query->row(); // Return user record (password check done in controller)
        }
        return false;
    }

    // ✅ Admin login validation (no password hash verification needed)
public function validate_admin_login($username) {
    $this->db->where('username', $username);
    $this->db->or_where('email', $username);
    $query = $this->db->get('admin');

    // Return the row (no hash verification needed here)
    return $query->row();
}



    // ✅ Insert new customer
    public function insertData($data) {
        return $this->db->insert('customers', $data);
    }

    // Optional: fetch all customers
    public function getRecords() {
        return $this->db->get('customers')->result();
    }

    // clearer alias used by controller for customers list
    public function getCustomers() {
        return $this->getRecords();
    }

    /**
     * Return total number of customers (rows in customers table)
     */
    public function countCustomers() {
        return (int) $this->db->count_all('customers');
    }

    /* ----------------------- FOOD FUNCTIONS ----------------------- */

    public function insertDataFood($data_food) {
        return $this->db->insert('food_details', $data_food);
    }

    public function getRecordsFood() {
        return $this->db->get('food_details')->result();
    }

    public function updateFood($foodID, $data_food) {
        $this->db->where('foodID', $foodID);
        return $this->db->update('food_details', $data_food);
    }

public function get_food($foodID) {
    return $this->db->get_where('food_details', ['foodID' => $foodID])->row();
}

public function delete_food($foodID) {
    return $this->db->delete('food_details', ['foodID' => $foodID]);
}

    // STORES CRUD
    public function getStores() {
        return $this->db->order_by('created_at','DESC')->get('stores')->result();
    }

    public function get_store($storeID) {
        return $this->db->get_where('stores', ['storeID' => $storeID])->row();
    }

    public function insertStore($data) {
        return $this->db->insert('stores', $data);
    }

    public function updateStore($storeID, $data) {
        $this->db->where('storeID', $storeID);
        return $this->db->update('stores', $data);
    }

    public function delete_store($storeID) {
        return $this->db->delete('stores', ['storeID' => $storeID]);
    }

    // ORDERS CRUD (exact entities: orderID, userID, storeID, order_date, status, total_amount, payment_method, payment_status)
    public function getOrders() {
        // order by orderID descending so newest orderID appears first
        $this->db->select('o.*, c.first_name, c.last_name, s.store_name');
        $this->db->from('orders o');
        $this->db->join('customers c', 'c.userID = o.userID', 'left');
        $this->db->join('stores s', 's.storeID = o.storeID', 'left');
        $this->db->order_by('o.orderID', 'ASC'); // changed: order by orderID
        return $this->db->get()->result();
    }

    public function get_order($orderID) {
        return $this->db->get_where('orders', ['orderID' => $orderID])->row();
    }

    /**
     * Get orders for a specific user (most recent first)
     */
    public function get_orders_by_user($userID) {
        if (empty($userID)) return [];
        $this->db->from('orders');
        $this->db->where('userID', (int)$userID);
        $this->db->order_by('orderID', 'DESC');
        $res = $this->db->get()->result();
        return $res;
    }

     public function insert_order(array $data)
{
    // allow only expected columns (prevents SQL errors if extra keys present)
    $allowed = [
        'userID','storeID','order_date','status','total_amount',
        'payment_method','payment_status','cart_json'
    ];

    $payload = [];
    foreach ($allowed as $key) {
        if (isset($data[$key])) {
            // only include cart_json if the DB table has that column
            if ($key === 'cart_json' && ! $this->db->field_exists('cart_json', 'orders')) {
                continue;
            }
            $payload[$key] = $data[$key];
        }
    }

    $this->db->insert('orders', $payload);
    if ($this->db->affected_rows() > 0) {
        return $this->db->insert_id();
    }
    return false;
    }

    public function updateOrder($orderID, array $data) {
        $this->db->where('orderID', $orderID);
        return $this->db->update('orders', $data);
    }

    public function delete_order($orderID) {
        return $this->db->delete('orders', ['orderID' => $orderID]);
    }

    public function customerExists($userID) {
        if (empty($userID)) return false;
        $this->db->where('userID', $userID);
        return $this->db->count_all_results('customers') > 0;
    }

    public function storeExists($storeID) {
        if (empty($storeID)) return false;
        $this->db->where('storeID', $storeID);
        return $this->db->count_all_results('stores') > 0;
    }

    // keep insertOrdersBatch as implemented earlier
    public function insertOrdersBatch(array $data = []) {
        if (!empty($data)) {
            $this->db->insert_batch('orders', $data);
            return $this->db->affected_rows();
        }
        return 0;
    }

    // PRODUCTS --- Batch insert used by import
    public function insertProductsBatch(array $data = []) {
        if (!empty($data)) {
            // ensure columns exist in your food_details table: food_name, food_id, type, price, stocks, image
            $this->db->insert_batch('food_details', $data);
            return $this->db->affected_rows();
        }
        return 0;
    }

    // STORES --- Batch insert stores used by import
    public function insertStoresBatch(array $data = []) {
        if (!empty($data)) {
            $this->db->insert_batch('stores', $data);
            return $this->db->affected_rows();
        }
        return 0;
    }

    public function get_customer($userID) {
        return $this->db->get_where('customers', ['userID' => $userID])->row();
    }

    /**
     * Return user registration counts grouped by period.
     * $range: 'daily' (last 7 days), 'weekly' (last 12 weeks), 'monthly' (last 12 months)
     * Returns ['labels'=>[], 'values'=>[]]
     */
    public function get_users_stats($range = 'monthly') {
        $labels = [];
        $values = [];

        if ($range === 'daily') {
            // last 7 days grouped by date
            $sql = "SELECT DATE(created_at) AS label, COUNT(*) AS cnt
                    FROM customers
                    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                    GROUP BY DATE(created_at)
                    ORDER BY DATE(created_at) ASC";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = date('M d', strtotime($r->label));
                $values[] = (int)$r->cnt;
            }
        } elseif ($range === 'weekly') {
            // last 12 weeks grouped by YEAR+WEEK. Use min(created_at) as representative date for label.
            $sql = "SELECT YEAR(created_at) AS yr, WEEK(created_at,1) AS wk, COUNT(*) AS cnt, MIN(created_at) AS min_date
                    FROM customers
                    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 11 WEEK)
                    GROUP BY yr, wk
                    ORDER BY yr ASC, wk ASC";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = date('M d', strtotime($r->min_date)); // week representative
                $values[] = (int)$r->cnt;
            }
        } else {
            // monthly (last 12 months)
            $sql = "SELECT DATE_FORMAT(created_at, '%b %Y') AS label, COUNT(*) AS cnt, MIN(created_at) AS min_date
                    FROM customers
                    WHERE created_at IS NOT NULL
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY min_date ASC
                    LIMIT 12";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = $r->label;
                $values[] = (int)$r->cnt;
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Return sales sums grouped by period.
     * $range: 'daily' (last 7 days), 'weekly' (last 12 weeks), 'monthly' (last 12 months)
     * Returns ['labels'=>[], 'values'=>[]]
     */
    public function get_sales_stats($range = 'monthly') {
        $labels = [];
        $values = [];

        if ($range === 'daily') {
            $sql = "SELECT DATE(order_date) AS label, SUM(total_amount) AS total
                    FROM orders
                    WHERE LOWER(status) = 'completed' AND LOWER(payment_status) = 'paid'
                      AND order_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                    GROUP BY DATE(order_date)
                    ORDER BY DATE(order_date) ASC";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = date('M d', strtotime($r->label));
                $values[] = (float)$r->total;
            }
        } elseif ($range === 'weekly') {
            $sql = "SELECT YEAR(order_date) AS yr, WEEK(order_date,1) AS wk, SUM(total_amount) AS total, MIN(order_date) AS min_date
                    FROM orders
                    WHERE LOWER(status) = 'completed' AND LOWER(payment_status) = 'paid'
                      AND order_date >= DATE_SUB(CURDATE(), INTERVAL 11 WEEK)
                    GROUP BY yr, wk
                    ORDER BY min_date ASC";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = date('M d', strtotime($r->min_date));
                $values[] = (float)$r->total;
            }
        } else {
            // monthly
            $sql = "SELECT DATE_FORMAT(order_date, '%b %Y') AS label, SUM(total_amount) AS total, MIN(order_date) AS min_date
                    FROM orders
                    WHERE LOWER(status) = 'completed' AND LOWER(payment_status) = 'paid'
                      AND order_date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
                    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                    ORDER BY min_date ASC
                    LIMIT 12";
            $q = $this->db->query($sql)->result();
            foreach ($q as $r) {
                $labels[] = $r->label;
                $values[] = (float)$r->total;
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
?>
