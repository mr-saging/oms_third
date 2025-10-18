<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;

class PagesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PagesModel');
        $this->load->library(['form_validation','session']);
        $this->load->helper(['url','form']);
    }

    /* ----------------------- VIEWS ----------------------- */

    public function index() {
        $this->load->view('homepage');
        $this->load->view('footer');
    }

    public function registration_user() {
        $this->load->view('registration_user');
    }

    public function login_admin() {
        $this->load->view('login_admin');
    }

    public function login_user() {
        $this->load->view('login_user');
    }

    public function orderdetails_admin() {
        $this->load->view('orderdetails_admin');
    }

    public function carousel() {
        $this->load->view('carousel');
    }

    public function my_orders() {
        $this->load->view('my_orders');
    }

    // Food menu view — load products from DB and pass to view
    public function food_menu()
    {
        // fetch products from model (returns array of objects with fields: foodID, food_name, description, type, price, stocks, image)
        $data['products'] = $this->PagesModel->getRecordsFood();
        $this->load->view('food_menu', $data);
    }

    // Food qart view
    public function food_cart()
    {
        $this->load->view('food_cart');
    }

    // Food checkout view
    public function food_checkout()
    {
        // pass stores (for store selection fallback) and current user (if logged in)
        $data['stores'] = $this->PagesModel->getStores();
        $data['user'] = null;
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {
            $data['user'] = $this->PagesModel->get_customer($user_id);
        }
        $this->load->view('food_checkout', $data);
    }

    /* ----------------------- USER REGISTRATION ----------------------- */

    public function register_validation() {
        // Form validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|regex_match[/^[A-Za-z]+$/]',
            array(
                'regex_match' => 'The %s may only contain letters with no spaces and numbers.'
            )
        );
        
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|regex_match[/^[A-Za-z]+$/]',
            array(
                'regex_match' => 'The %s may only contain letters with no spaces and numbers.'
            )
        );

        $this->form_validation->set_rules('username', 'Username', 'required|trim');

        $this->form_validation->set_rules('birth_date', 'Birthdate', 'required');

        $this->form_validation->set_rules('email_address', 'Email', 'required|valid_email');

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|regex_match[/^[0-9]{11}$/]',
        array(
            'required' => 'The %s field is required.',
            'regex_match' => 'The %s must be exactly 11 digits.'
        )
        );


        if ($this->form_validation->run() == FALSE) {
            // Reload registration view with errors
            $this->registration_user();
        } else {
            $data = array(
                'first_name'     => $this->input->post('first_name', TRUE),
                'last_name'      => $this->input->post('last_name', TRUE),
                'username'       => $this->input->post('username', TRUE),
                'email_address'  => $this->input->post('email_address', TRUE),
                'birth_date'     => $this->input->post('birth_date', TRUE),
                'phone_number'   => $this->input->post('phone_number', TRUE),
                'password'       => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            );

            $insert = $this->PagesModel->insertData($data);

            if ($insert) {
                $this->session->set_flashdata('success', 'Account created successfully! You can now log in.');
                redirect('PagesController/login_user');
            } else {
                $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                $this->registration_user(); // reload without redirect
            }
        }
    }

    /* ----------------------- USER LOGIN VALIDATION ----------------------- */

    public function login_validation()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->PagesModel->validate_login($username);
        if ($user && password_verify($password, $user->password)) {
            // Ensure these keys exist in session
            $this->session->set_userdata([
                'user_id'   => isset($user->userID) ? $user->userID : (isset($user->id) ? $user->id : 0),
                'username'  => isset($user->username) ? $user->username : '',
                'first_name'=> isset($user->first_name) ? $user->first_name : '',
                'last_name' => isset($user->last_name) ? $user->last_name : '',
                'email'     => isset($user->email) ? $user->email : '',
                'phone'     => isset($user->phone) ? $user->phone : '',
                'logged_in' => TRUE
            ]);
            redirect('PagesController/index');
        } else {
            $this->session->set_flashdata('error', 'Invalid Username or Password');
            redirect('PagesController/login_user');
        }
    }



    // LOGOUT USER
    public function logout() {
        $this->session->unset_userdata(['user_id', 'username']);
        $this->session->sess_destroy();
        redirect('PagesController/index');
    }

    // ADMIN LOGIN VALIDATION
    public function admin_login_validation() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->PagesModel->validate_admin_login($username);

        if ($admin && $password === $admin->password) {
            $this->session->set_userdata([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'is_admin_logged_in' => TRUE
            ]);
            redirect('PagesController/dashboard_admin');
        } else {
            $this->session->set_flashdata('error', 'Invalid Admin Credentials');
            $this->login_admin();
        }
    }

    public function dashboard_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }

        // provide the view with orders/stores/customers so counts on the dashboard are dynamic
        $data['orders']     = $this->PagesModel->getOrders();
        $data['stores']     = $this->PagesModel->getStores();
        $data['customers']  = $this->PagesModel->getCustomers();
         $data['products']   = $this->PagesModel->getRecordsFood();

        $this->load->view('admin_dashboard', $data);
    }

    /* ----------------------- ADMIN PAGES ----------------------- */

    // Admin: orders list view
    public function orders_admin() {
        $data['orders'] = $this->PagesModel->getOrders();
        $data['errors'] = $this->session->flashdata('errors');
        $data['open_add_modal'] = $this->session->flashdata('open_add_modal');
        $data['open_edit_modal'] = $this->session->flashdata('open_edit_modal');
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('admin_orders', $data);
    }

    // Import orders from Excel/CSV
    public function import_orders() {
        $file_mimes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'text/plain'
        ];

        if (!isset($_FILES['uploadFile']['name']) || !in_array($_FILES['uploadFile']['type'], $file_mimes)) {
            $this->session->set_flashdata('error', 'Please upload a valid Excel/CSV file.');
            redirect('PagesController/orders_admin');
        }

        $arr_file = explode('.', $_FILES['uploadFile']['name']);
        $extension = strtolower(end($arr_file));

        try {
            $reader = ($extension === 'csv') ? IOFactory::createReader('Csv') : IOFactory::createReader('Xlsx');
            // allow formatted values so strings like "Nov 30, 2025" are preserved
            $reader->setReadDataOnly(false);
            $spreadsheet = $reader->load($_FILES['uploadFile']['tmp_name']);
            // return cells indexed by column letter
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            if (empty($sheetData) || count($sheetData) < 2) {
                $this->session->set_flashdata('error', 'No rows found in the uploaded file.');
                redirect('PagesController/orders_admin');
            }

            $data = [];
            $skipped = [];
            $rowIndex = 0;
            foreach ($sheetData as $row) {
                $rowIndex++;
                if ($rowIndex === 1) continue; // header

                // columns expected A..G: userID, storeID, order_date, status, total_amount, payment_method, payment_status
                $userID_raw = trim((string)($row['A'] ?? ''));
                $storeID_raw = trim((string)($row['B'] ?? ''));
                $order_date_raw = $row['C'] ?? '';
                $status_raw = trim((string)($row['D'] ?? ''));
                $total_raw = trim((string)($row['E'] ?? ''));
                $payment_method_raw = trim((string)($row['F'] ?? ''));
                $payment_status_raw = trim((string)($row['G'] ?? ''));

                // validate ids
                $userID = $userID_raw === '' ? null : (int)$userID_raw;
                $storeID = $storeID_raw === '' ? null : (int)$storeID_raw;

                if ($userID === null || !$this->PagesModel->customerExists($userID)) {
                    $skipped[] = "Row {$rowIndex}: invalid userID '{$userID_raw}'";
                    continue;
                }
                if ($storeID === null || !$this->PagesModel->storeExists($storeID)) {
                    $skipped[] = "Row {$rowIndex}: invalid storeID '{$storeID_raw}'";
                    continue;
                }

                // parse date:
                $order_date = null;
                // if cell is numeric it's probably an Excel serial date
                if (is_numeric($order_date_raw) && $order_date_raw != 0) {
                    try {
                        $dt = SpreadsheetDate::excelToDateTimeObject((float)$order_date_raw);
                        $order_date = $dt->format('Y-m-d H:i:s');
                    } catch (\Throwable $e) {
                        $order_date = null;
                    }
                } elseif (is_string($order_date_raw) && trim($order_date_raw) !== '') {
                    // try strtotime for common string formats
                    $ts = strtotime(trim($order_date_raw));
                    if ($ts !== false && $ts > 0) {
                        $order_date = date('Y-m-d H:i:s', $ts);
                    } else {
                        // try PhpSpreadsheet conversion if the cell object was returned as a DateTime string: attempt Date::excelToDateTimeObject by casting to float if possible
                        $floatVal = @floatval($order_date_raw);
                        if ($floatVal > 0) {
                            try {
                                $dt = SpreadsheetDate::excelToDateTimeObject($floatVal);
                                $order_date = $dt->format('Y-m-d H:i:s');
                            } catch (\Throwable $e) {
                                $order_date = null;
                            }
                        }
                    }
                }

                // parse total amount: remove non-numeric except dot and minus, handle commas and currency symbol
                $total_sanitized = str_replace(['₱', ',', ' '], ['', '', ''], $total_raw);
                // also strip any other non-numeric chars except dot and minus
                $total_sanitized = preg_replace('/[^\d\.\-]/', '', $total_sanitized);
                $total_amount = is_numeric($total_sanitized) ? (float)$total_sanitized : 0.00;

                $data[] = [
                    'userID' => $userID,
                    'storeID' => $storeID,
                    'order_date' => $order_date, // can be null
                    'status' => $status_raw ?: 'Pending',
                    'total_amount' => $total_amount,
                    'payment_method' => $payment_method_raw ?: 'cash',
                    'payment_status' => $payment_status_raw ?: 'pending',
                ];
            }

            if (!empty($data)) {
                $inserted = $this->PagesModel->insertOrdersBatch($data);
                $msg = ($inserted ? "{$inserted} rows imported." : "Import complete; no rows inserted.");
                if (!empty($skipped)) {
                    $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                }
                $this->session->set_flashdata('success', $msg);
            } else {
                $msg = 'No valid rows to import.';
                if (!empty($skipped)) $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                $this->session->set_flashdata('error', $msg);
            }
        } catch (\Throwable $e) {
            $this->session->set_flashdata('error', 'Error reading file: ' . $e->getMessage());
        }

        redirect('PagesController/orders_admin');
    }

    // Insert order (modified to send email on paid)
    public function insert_order()
    {
        $this->load->library('form_validation');

        // show errors as small red text under inputs
        $this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');

        // validation rules
        $this->form_validation->set_rules('first_name', 'First name', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('last_name', 'Last name', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[150]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim|min_length[7]|max_length[20]');
        $this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('postal', 'Postal / ZIP', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('payment_method', 'Payment method', 'required|in_list[Cash,Credit Card]');
        $this->form_validation->set_rules('total_amount', 'Total amount', 'required|numeric|greater_than[0]');

        $isAjax = $this->input->is_ajax_request();

        // if validation fails, respond appropriately
        if ($this->form_validation->run() === FALSE) {
            if ($isAjax) {
                $errors = validation_errors();
                return $this->output
                    ->set_status_header(422)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode(['success' => false, 'errors' => $errors]));
            } else {
                $this->session->set_flashdata('errors', validation_errors());
                redirect('PagesController/food_checkout');
            }
            return;
        }

        // require login server-side
        $userID = (int) $this->session->userdata('user_id');
        if ($userID <= 0) {
            if ($isAjax) {
                return $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode(['success' => false, 'message' => 'User must be logged in.']));
            } else {
                $this->session->set_flashdata('error', 'You must be logged in to place an order.');
                redirect('PagesController/login_user');
            }
            return;
        }

        // gather sanitized inputs
        $storeID = (int) $this->input->post('storeID', TRUE) ?: NULL;
        $cart_json = $this->input->post('cart_json', TRUE) ?: json_encode([]);
        $total_amount = (float) $this->input->post('total_amount', TRUE);
        // accept exactly "Cash" or "Credit Card" (match form labels)
        $payment_method = trim($this->input->post('payment_method', TRUE));
        if ($payment_method !== 'Cash' && $payment_method !== 'Credit Card') {
            $payment_method = 'Cash';
        }
        $status = $this->input->post('status', TRUE) ?: 'Pending';
        $payment_status = $this->input->post('payment_status', TRUE) ?: 'pending';

        // build order payload matching PagesModel::insert_order allowed keys
        $orderData = [
            'userID'         => $userID,
            'storeID'        => $storeID,
            'order_date'     => date('Y-m-d H:i:s'),
            'status'         => $status,
            'total_amount'   => $total_amount,
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'cart_json'      => $cart_json,
    ];

        $this->load->model('PagesModel');
        $insertId = $this->PagesModel->insert_order($orderData);

        if ($insertId) {
            // fetch saved order optionally
            $savedOrder = $this->PagesModel->get_order($insertId);

            // send a receipt email immediately when user clicks "Pay now"
            // pass current POST data so we can include billing address (not stored in orders table)
            try {
                $this->send_receipt_email($savedOrder, $this->input->post());
            } catch (\Throwable $e) {
                log_message('error', 'Receipt email error: '.$e->getMessage());
            }

            if ($isAjax) {
                return $this->output
                    ->set_status_header(201)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode([
                        'success' => true,
                        'orderID' => $insertId,
                        'order'   => $savedOrder ? $savedOrder : null,
                        'admin_orders_url' => site_url('PagesController/orders_admin')
                    ]));
            }

            $this->session->set_flashdata('success', 'Order placed successfully.');
            redirect('PagesController/food_menu');
        } else {
            if ($isAjax) {
                return $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode(['success' => false, 'message' => 'Unable to place order.']));
            }
            $this->session->set_flashdata('error', 'Unable to place order. Try again.');
            redirect('PagesController/food_checkout');
        }
    }

    // Update order (send email when payment_status changes to paid)
    public function update_order() {
        $orderID = $this->input->post('orderID', TRUE);
        $this->form_validation->set_rules('status','Status','required');
        $this->form_validation->set_rules('payment_status','Payment Status','required');
        $this->form_validation->set_rules('total_amount','Total Amount','required|numeric|greater_than_equal_to[1]');

        $oldOrder = $this->PagesModel->get_order($orderID);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('open_edit_modal', $orderID);
            $this->session->set_flashdata('errors', [
                'status' => form_error('status'),
                'payment_status' => form_error('payment_status'),
                'total_amount' => form_error('total_amount')
            ]);
            redirect('PagesController/orders_admin');
            return;
        }

        $data = [
            'status'         => $this->input->post('status', TRUE),
            'payment_status' => $this->input->post('payment_status', TRUE),
            'total_amount'   => $this->input->post('total_amount', TRUE)
        ];

        // optionally update order_date if provided
        $order_date_raw = $this->input->post('order_date', TRUE);
        if (!empty($order_date_raw)) {
            $data['order_date'] = date('Y-m-d H:i:s', strtotime($order_date_raw));
        }

        $ok = $this->PagesModel->updateOrder($orderID, $data);

        // reload order and customer after update
        $order = $this->PagesModel->get_order($orderID);
        $customer = $this->PagesModel->get_customer($order->userID ?? 0);
        $store = $this->PagesModel->get_store($order->storeID ?? null);

        // detect changes between old and new
        $changes = [];
        if ($oldOrder) {
            if ((string)($oldOrder->status ?? '') !== (string)($data['status'] ?? '')) {
                $changes['status'] = [
                    'old' => $oldOrder->status ?? '',
                    'new' => $data['status'] ?? ''
                ];
            }
            if ((string)($oldOrder->payment_status ?? '') !== (string)($data['payment_status'] ?? '')) {
                $changes['payment_status'] = [
                    'old' => $oldOrder->payment_status ?? '',
                    'new' => $data['payment_status'] ?? ''
                ];
            }
        } else {
            // if oldOrder missing, but update succeeded, treat as full set
            if (!empty($data['status'])) $changes['status'] = ['old' => '', 'new' => $data['status']];
            if (!empty($data['payment_status'])) $changes['payment_status'] = ['old' => '', 'new' => $data['payment_status']];
        }

        // If either status or payment_status changed -> send update email
        if (!empty($changes) && $customer) {
            try {
                $this->send_order_update_email($order, $customer, $changes);
            } catch (\Throwable $e) {
                log_message('error', 'Order update email error: ' . $e->getMessage());
            }
        }


        $this->session->set_flashdata('message', $ok ? 'Order updated.' : 'Failed to update order.');
        redirect('PagesController/orders_admin');
    }

    /**
     * Send a simple notification when admin updates order status or payment status.
     * Now sends specific emails for Completed/Paid, Cancelled/Failed and Refunded states,
     * otherwise falls back to a generic "Order Updated" notification.
     */
    private function send_order_update_email($order, $customer, array $changes = []) {
        if (empty($order) || empty($customer)) return;

        $this->load->library('Phpmailer_lib');
        $mail = $this->phpmailer_lib->load();

        try {
            // SMTP config (keep consistent with other mail functions)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ongjaredb@gmail.com';
            $mail->Password   = 'xteo jhia oqkk pxuo';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';
            $mail->Encoding   = 'base64';

            $mail->setFrom('ongjaredb@gmail.com', 'FoodsOUT');

            $to = isset($customer->email_address) ? $customer->email_address : (isset($customer->email) ? $customer->email : null);
            if (empty($to)) return;
            $mail->addAddress($to);

            $orderId = isset($order->orderID) ? $order->orderID : '-';
            $status = strtolower(trim($order->status ?? ''));
            $pstatus = strtolower(trim($order->payment_status ?? ''));

            // Determine message type
            if ($status === 'Completed' && $pstatus === 'Paid') {
                $subject = "Order Completed — #{$orderId}";
                $headline = "Your order has been completed";
                $summary = "We're happy to let you know your order <strong>#{$orderId}</strong> has been completed and delivered.";
            } elseif ($status === 'Cancelled' || $pstatus === 'Failed') {
                $subject = "Order Cancelled — #{$orderId}";
                $headline = "Your order has been cancelled";
                $summary = "We're sorry — your order <strong>#{$orderId}</strong> has been cancelled.";
            } elseif ($pstatus === 'Refunded') {
                $subject = "Order Refunded — #{$orderId}";
                $headline = "Your payment has been refunded";
                $summary = "A refund for order <strong>#{$orderId}</strong> has been processed. Please allow a few days for the funds to appear in your account.";
            } else {
                $subject = "Order Updated — #{$orderId}";
                $headline = "Your order was updated";
                $summary = "Changes were made to your order <strong>#{$orderId}</strong>.";
            }

            $mail->Subject = $subject;
            $mail->isHTML(true);

            // decode items (if any)
            $items = [];
            if (!empty($order->cart_json)) {
                $decoded = @json_decode($order->cart_json, true);
                if (is_array($decoded)) $items = $decoded;
            }

            // Attach files referenced by items (avoid duplicates)
            $attachedNames = [];
            $attachedFiles = [];
            if (!empty($items)) {
                foreach ($items as $it) {
                    $img = $it['img'] ?? ($it['image'] ?? null);
                    $foodId = $it['id'] ?? ($it['foodID'] ?? null);
                    $candidates = [];
                    if (!empty($img)) {
                        $candidates[] = './' . ltrim($img, '/');
                        $candidates[] = './uploads/' . ltrim($img, '/');
                        if (strpos($img, DIRECTORY_SEPARATOR) !== false) $candidates[] = $img;
                    }
                    if (!empty($foodId)) {
                        $food = $this->PagesModel->get_food((int)$foodId);
                        if (!empty($food->image)) {
                            $candidates[] = './uploads/' . ltrim($food->image, '/');
                            $candidates[] = $food->image;
                        }
                    }
                    foreach ($candidates as $cpath) {
                        if (empty($cpath)) continue;
                        $cpath = str_replace(['//','\\\\'], DIRECTORY_SEPARATOR, $cpath);
                        if (file_exists($cpath) && is_file($cpath)) {
                            if (!in_array($cpath, $attachedFiles, true)) {
                                try {
                                    $mail->addAttachment($cpath, basename($cpath));
                                    $attachedFiles[] = $cpath;
                                    $attachedNames[] = basename($cpath);
                                } catch (\Throwable $e) {
                                    // ignore
                                }
                            }
                            break;
                        }
                    }
                }
            }

            // Build HTML body
            $custName = htmlspecialchars(trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) ?: $to);
            $body  = '<div style="font-family:Arial,Helvetica,sans-serif;max-width:700px;margin:0 auto;color:#222;">';
            $body .= '<div style="background:#ff4d00;padding:12px 16px;border-radius:8px;color:#fff;">';
            $body .= '<h2 style="margin:0;font-size:18px;">' . htmlspecialchars($headline) . '</h2>';
            $body .= '</div>';

            $body .= '<div style="padding:14px;">';
            $body .= '<p>Hi ' . $custName . ',</p>';
            $body .= '<p>' . $summary . '</p>';

            // show changed fields when available (for generic update & helpful context)
            if (!empty($changes)) {
                $body .= '<div style="margin-top:10px;"><strong>Changes:</strong><ul>';
                foreach ($changes as $field => $vals) {
                    $label = $field === 'payment_status' ? 'Payment Status' : (ucfirst(str_replace('_',' ',$field)));
                    $body .= '<li><strong>' . htmlspecialchars($label) . ':</strong> ' . htmlspecialchars($vals['old'] ?? '—') . ' → ' . htmlspecialchars($vals['new'] ?? '—') . '</li>';
                }
                $body .= '</ul></div>';
            }

            // order summary
            $body .= '<table style="width:100%;border-collapse:collapse;margin-top:12px;">';
            $body .= '<tr><td style="padding:6px 0;"><strong>Order ID</strong></td><td style="text-align:right;padding:6px 0;">' . htmlspecialchars($orderId) . '</td></tr>';
            $body .= '<tr><td style="padding:6px 0;"><strong>Date</strong></td><td style="text-align:right;padding:6px 0;">' . (isset($order->order_date) ? htmlspecialchars(date('M d, Y H:i', strtotime($order->order_date))) : '—') . '</td></tr>';
            $body .= '<tr><td style="padding:6px 0;"><strong>Total</strong></td><td style="text-align:right;padding:6px 0;color:#ff4d00;">₱' . number_format((float)($order->total_amount ?? 0), 2) . '</td></tr>';
            $body .= '<tr><td style="padding:6px 0;"><strong>Status</strong></td><td style="text-align:right;padding:6px 0;">' . htmlspecialchars($order->status ?? '') . ' • ' . htmlspecialchars($order->payment_status ?? '') . '</td></tr>';
            $body .= '</table>';

            // items list
            if (!empty($items)) {
                $body .= '<div style="margin-top:12px;"><strong>Items</strong>';
                $body .= '<table style="width:100%;border-collapse:collapse;margin-top:8px;">';
                $body .= '<thead><tr style="background:#f7f7f7;"><th style="padding:8px;border-bottom:1px solid #eee;text-align:left;">Item</th><th style="padding:8px;border-bottom:1px solid #eee;">Qty</th><th style="padding:8px;border-bottom:1px solid #eee;text-align:right;">Price</th></tr></thead>';
                $body .= '<tbody>';
                foreach ($items as $it) {
                    $name = htmlspecialchars($it['name'] ?? ($it['title'] ?? 'Item'));
                    $qty = (int)($it['qty'] ?? $it['quantity'] ?? 1);
                    $price = number_format((float)($it['price'] ?? $it['amount'] ?? 0), 2);
                    $body .= '<tr>';
                    $body .= '<td style="padding:8px;border-top:1px solid #f1f1f1;">' . $name . '</td>';
                    $body .= '<td style="padding:8px;border-top:1px solid #f1f1f1;">' . $qty . '</td>';
                    $body .= '<td style="padding:8px;border-top:1px solid #f1f1f1;text-align:right;">₱' . $price . '</td>';
                    $body .= '</tr>';
                }
                $body .= '</tbody></table></div>';
            }

            if (!empty($attachedNames)) {
                $body .= '<div style="margin-top:12px;background:#f9f9fb;padding:10px;border-radius:8px;">';
                $body .= '<strong>Files attached:</strong><ul style="margin:8px 0 0 18px;padding:0;">';
                foreach ($attachedNames as $fn) {
                    $body .= '<li>' . htmlspecialchars($fn) . '</li>';
                }
                $body .= '</ul></div>';
            }

            $body .= '<p style="margin-top:12px;color:#666;">If you have any questions, reply to this email or contact our support.</p>';
            $body .= '<p style="color:#ff4d00;font-weight:700;">Thanks for choosing FoodsOUT!</p>';
            $body .= '</div></div>';

            $mail->Body = $body;
            $mail->send();
        } catch (\Throwable $e) {
            log_message('error', 'Order Update Email Error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
        }
    }

    // Delete order
    public function delete_order($orderID = null) {
        if ($orderID === null) redirect('PagesController/orders_admin');
        $ok = $this->PagesModel->delete_order($orderID);
        $this->session->set_flashdata('message', $ok ? 'Order deleted.' : 'Failed to delete order.');
        redirect('PagesController/orders_admin');
    }

    public function stores_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }

        // fetch stores
        $data['stores'] = $this->PagesModel->getStores();

        // modal flags for validation errors
        $data['open_add_modal'] = $this->session->flashdata('open_add_modal');
        $data['open_edit_modal'] = $this->session->flashdata('open_edit_modal');
        $data['errors'] = $this->session->flashdata('errors');

        $this->load->view('admin_stores', $data);
    }

    // Insert Store
    public function insert_store() {
        $this->form_validation->set_rules('store_name', 'Store Name', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('open_add_modal', true);
            $this->session->set_flashdata('errors', [
                'store_name' => form_error('store_name'),
                'address' => form_error('address')
            ]);
            redirect('PagesController/stores_admin');
            return;
        }

        $data = [
            'store_name' => $this->input->post('store_name', TRUE),
            'address'    => $this->input->post('address', TRUE),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->PagesModel->insertStore($data);
        $this->session->set_flashdata('message', $insert ? 'Store added successfully!' : 'Failed to add store.');
        redirect('PagesController/stores_admin');
    }

    // Update Store
    public function update_store() {
        $storeID = $this->input->post('id', TRUE);
        $this->form_validation->set_rules('store_name', 'Store Name', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('open_edit_modal', $storeID);
            $this->session->set_flashdata('errors', [
                'store_name' => form_error('store_name'),
                'address' => form_error('address')
            ]);
            redirect('PagesController/stores_admin');
            return;
        }

        $data = [
            'store_name' => $this->input->post('store_name', TRUE),
            'address'    => $this->input->post('address', TRUE)
        ];

        $updated = $this->PagesModel->updateStore($storeID, $data);
        $this->session->set_flashdata('message', $updated ? 'Store updated successfully!' : 'Failed to update store.');
        redirect('PagesController/stores_admin');
    }

    // Delete Store
    public function delete_store($storeID = null) {
        if ($storeID === null) redirect('PagesController/stores_admin');

        $deleted = $this->PagesModel->delete_store($storeID);
        $this->session->set_flashdata('message', $deleted ? 'Store deleted successfully!' : 'Failed to delete store.');
        redirect('PagesController/stores_admin');
    }

    public function calendar_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }
        $this->load->view('admin_calendar');
    }

    public function chat_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }
        $this->load->view('admin_chat');
    }

    public function products_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }

        $data['food_details'] = $this->PagesModel->getRecordsFood();

        // NEW: pass stores list so admin products view can show/select store
        $data['stores'] = $this->PagesModel->getStores();

        // Show modal if flagged
        $data['open_add_modal'] = $this->session->flashdata('open_add_modal');
        $data['open_edit_modal'] = $this->session->flashdata('open_edit_modal');
        $data['errors'] = $this->session->flashdata('errors');
        $data['upload_error'] = $this->session->flashdata('upload_error');

        $this->load->view('admin_products', $data);
    }

    // Import products from Excel/CSV (header-aware, accepts image column)
    public function import_products() {
        $allowedExt = ['csv','xls','xlsx'];
        if (!isset($_FILES['uploadFile']['name']) || empty($_FILES['uploadFile']['name'])) {
            $this->session->set_flashdata('error', 'No file uploaded.');
            redirect('PagesController/products_admin');
        }

        $arr_file = explode('.', $_FILES['uploadFile']['name']);
        $extension = strtolower(end($arr_file));
        if (!in_array($extension, $allowedExt)) {
            $this->session->set_flashdata('error', 'Unsupported file type. Use CSV/XLS/XLSX.');
            redirect('PagesController/products_admin');
        }

        try {
            $reader = ($extension === 'csv') ? IOFactory::createReader('Csv') : IOFactory::createReader('Xlsx');
            if ($extension === 'csv') {
                // ensure comma delimiter
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
                $reader->setEscapeCharacter('\\');
            }
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['uploadFile']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            if (empty($sheetData) || count($sheetData) < 2) {
                $this->session->set_flashdata('error', 'Empty file or no data rows found.');
                redirect('PagesController/products_admin');
            }

            // build header map from first row
            $firstRow = reset($sheetData);
            $headerMap = [];
            foreach ($firstRow as $col => $val) {
                $key = preg_replace('/[^a0-9_]/', '', strtolower(trim((string)$val)));
                if ($key !== '') $headerMap[$key] = $col; // e.g. 'food_name' => 'A'
            }

            // helper to get field by header name or fallback column order
            $get = function($row, $name, $fallbackCol) use ($headerMap) {
                $k = preg_replace('/[^a0-9_]/', '', strtolower($name));
                if (!empty($headerMap[$k]) && isset($row[$headerMap[$k]])) return trim((string)$row[$headerMap[$k]]);
                return isset($row[$fallbackCol]) ? trim((string)$row[$fallbackCol]) : '';
            };

            $data = [];
            $skipped = [];
            $rowIndex = 0;
            foreach ($sheetData as $row) {
                $rowIndex++;
                if ($rowIndex === 1) continue; // header

                // Try to read by header names with sensible fallbacks (A..F)
                $food_name = $get($row, 'food_name', 'A');
                // description replaces food_id; accept long text
                $description = $get($row, 'description', 'B');
                // In your CSV price is column C
                $price_raw = $get($row, 'price', 'C');
                $stocks_raw= $get($row, 'stocks', 'D');
                $type      = $get($row, 'type', 'E');
                $image     = $get($row, 'image', 'F');

                if ($food_name === '' ) {
                    $skipped[] = "Row {$rowIndex}: missing name";
                    continue;
                }

                // sanitize price (remove currency, commas)
                $price_s = str_replace(['₱',',',' '], ['', '', ''], $price_raw);
                $price_s = preg_replace('/[^\d\.\-]/', '', $price_s);
                $price = is_numeric($price_s) ? (float)$price_s : null;

                $stocks_s = preg_replace('/[^\d\-]/', '', $stocks_raw);
                $stocks = $stocks_s !== '' && is_numeric($stocks_s) ? (int)$stocks_s : null;

                if ($price === null) {
                    $skipped[] = "Row {$rowIndex}: invalid price '{$price_raw}'";
                    continue;
                }
                if ($stocks === null) {
                    $skipped[] = "Row {$rowIndex}: invalid stocks '{$stocks_raw}'";
                    continue;
                }

                $data[] = [
                    'food_name'   => $food_name,
                    'description' => $description ?: null,
                    'type'        => $type ?: 'other',
                    'price'       => $price,
                    'stocks'      => $stocks,
                    'image'       => $image ?: null,
                ];
            }

            if (!empty($data)) {
                $inserted = $this->PagesModel->insertProductsBatch($data);
                $msg = ($inserted ? "{$inserted} rows imported." : "Import completed; 0 rows inserted.");
                if (!empty($skipped)) $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                $this->session->set_flashdata('success', $msg);
            } else {
                $msg = 'No valid rows to import.';
                if (!empty($skipped)) $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                $this->session->set_flashdata('error', $msg);
            }
        } catch (\Throwable $e) {
            $this->session->set_flashdata('error', 'Import error: '.$e->getMessage());
        }

        redirect('PagesController/products_admin');
    }

    // STORES -- Import stores from Excel/CSV (header-aware)
    public function import_stores() {
        $allowedExt = ['csv','xls','xlsx'];
        
        if (!isset($_FILES['uploadFile']['name']) || empty($_FILES['uploadFile']['name'])) {
            $this->session->set_flashdata('error', 'No file uploaded.');
            redirect('PagesController/stores_admin');
        }

        $arr_file = explode('.', $_FILES['uploadFile']['name']);
        $extension = strtolower(end($arr_file));
        if (!in_array($extension, $allowedExt)) {
            $this->session->set_flashdata('error', 'Unsupported file type. Use CSV/XLS/XLSX.');
            redirect('PagesController/stores_admin');
        }

        try {
            $reader = ($extension === 'csv') ? IOFactory::createReader('Csv') : IOFactory::createReader('Xlsx');
            if ($extension === 'csv') {
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
                $reader->setEscapeCharacter('\\');
            }
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['uploadFile']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            if (empty($sheetData) || count($sheetData) < 2) {
                $this->session->set_flashdata('error', 'Empty file or no data rows found.');
                redirect('PagesController/stores_admin');
            }

            // build header map from first row
            $firstRow = reset($sheetData);
            $headerMap = [];
            foreach ($firstRow as $col => $val) {
                $key = preg_replace('/[^a0-9_]/', '', strtolower(trim((string)$val)));
                if ($key !== '') $headerMap[$key] = $col; // e.g. 'store_name' => 'A'
            }

            $get = function($row, $name, $fallbackCol) use ($headerMap) {
                $k = preg_replace('/[^a0-9_]/', '', strtolower($name));
                if (!empty($headerMap[$k]) && isset($row[$headerMap[$k]])) return trim((string)$row[$headerMap[$k]]);
                return isset($row[$fallbackCol]) ? trim((string)$row[$fallbackCol]) : '';
            };

            $data = [];
            $skipped = [];
            $rowIndex = 0;
            foreach ($sheetData as $row) {
                $rowIndex++;
                if ($rowIndex === 1) continue; // header

                $store_name = $get($row, 'store_name', 'A');
                $address = $get($row, 'address', 'B');
                $created_raw = $get($row, 'created_at', 'C');

                if ($store_name === '' || $address === '') {
                    $skipped[] = "Row {$rowIndex}: missing name or address";
                    continue;
                }

                // parse created_at if provided
                $created_at = null;
                if ($created_raw !== '') {
                    if (is_numeric($created_raw)) {
                        try {
                            $dt = SpreadsheetDate::excelToDateTimeObject((float)$created_raw);
                            $created_at = $dt->format('Y-m-d H:i:s');
                        } catch (\Throwable $e) {
                            $created_at = date('Y-m-d H:i:s', strtotime($created_raw));
                        }
                    } else {
                        $ts = strtotime($created_raw);
                        $created_at = ($ts !== false && $ts > 0) ? date('Y-m-d H:i:s', $ts) : date('Y-m-d H:i:s');
                    }
                } else {
                    $created_at = date('Y-m-d H:i:s');
                }

                $data[] = [
                    'store_name' => $store_name,
                    'address'    => $address,
                    'created_at' => $created_at
                ];
            }

            if (!empty($data)) {
                $inserted = $this->PagesModel->insertStoresBatch($data);
                $msg = ($inserted ? "{$inserted} rows imported." : "Import completed; 0 rows inserted.");
                if (!empty($skipped)) $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                $this->session->set_flashdata('success', $msg);
            } else {
                $msg = 'No valid rows to import.';
                if (!empty($skipped)) $msg .= ' Skipped: ' . implode(' ; ', array_slice($skipped, 0, 10));
                $this->session->set_flashdata('error', $msg);
            }
        } catch (\Throwable $e) {
            $this->session->set_flashdata('error', 'Import error: '.$e->getMessage());
        }

        redirect('PagesController/stores_admin');
    }

    public function customers_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }

        // fetch customers from model
        $data['customers'] = $this->PagesModel->getCustomers();

        // modal flags for validation errors (kept for consistency)
        $data['open_add_modal'] = $this->session->flashdata('open_add_modal');
        $data['open_edit_modal'] = $this->session->flashdata('open_edit_modal');
        $data['errors'] = $this->session->flashdata('errors');

        $this->load->view('admin_customers', $data);
    }

    public function reports_admin() {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('PagesController/login_admin');
        }
        $this->load->view('admin_reports');
    }

    public function admin_logout() {
        $this->session->unset_userdata(['admin_id', 'admin_username', 'is_admin_logged_in']);
        $this->session->sess_destroy();
        redirect('PagesController/login_admin');
    }

    /* ----------------------- USER DASHBOARD ----------------------- */

    public function dashboard() {
        $data['food_details'] = $this->PagesModel->getRecordsFood();
        $this->load->view('dashboard', $data);
    }

    /* ----------------------- FOOD CRUD ----------------------- */

    // Insert Food
    public function insert_food() {
        $this->form_validation->set_rules('food_name', 'Food Name', 'required|trim|regex_match[/^[a-zA-Z ]+$/]');
        $this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[1000]');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[1]');
        $this->form_validation->set_rules('stocks', 'Stocks', 'required|integer|greater_than_equal_to[0]');
        // NEW: store selection required
        $this->form_validation->set_rules('storeID', 'Store', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $data['food_details'] = $this->PagesModel->getRecordsFood();
            $data['stores'] = $this->PagesModel->getStores(); // ensure stores available for modal
            $data['open_add_modal'] = true;
            $data['errors'] = [
                'food_name'   => form_error('food_name'),
                'description' => form_error('description'),
                'type'        => form_error('type'),
                'price'       => form_error('price'),
                'stocks'      => form_error('stocks'),
                'storeID'     => form_error('storeID')
            ];
            $this->load->view('admin_products', $data);
            return;
        }

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        $image_name = null;
        if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            } else {
                $data['food_details'] = $this->PagesModel->getRecordsFood();
                $data['stores'] = $this->PagesModel->getStores();
                $data['open_add_modal'] = true;
                $data['upload_error'] = $this->upload->display_errors();
                $this->load->view('admin_products', $data);
                return;
            }
        }

        // include storeID in payload
        $data = array(
            'food_name'   => $this->input->post('food_name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price'       => $this->input->post('price', TRUE),
            'stocks'      => $this->input->post('stocks', TRUE),
            'type'        => $this->input->post('type', TRUE),
            'image'       => $image_name,
            'storeID'     => $this->input->post('storeID', TRUE)
        );

        $this->PagesModel->insertDataFood($data);
        $this->session->set_flashdata('message', 'Product added successfully!');
        $this->session->set_flashdata('type', 'success');
        redirect('PagesController/products_admin');
    }

    // Update Food
    public function update_food()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('food_name', 'Food Name', 'required|trim|regex_match[/^[a-zA-Z ]+$/]');
        $this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[1000]');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[1]');
        $this->form_validation->set_rules('stocks', 'Stocks', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('storeID', 'Store', 'required|integer');

        $foodID = $this->input->post('foodID', TRUE);

        if ($this->form_validation->run() == FALSE) {
            $old = $this->input->post();
            $this->session->set_flashdata('edit_old_input', $old);
            if (!empty($old['foodID'])) {
                $this->session->set_flashdata('open_edit_modal', $old['foodID']);
            }
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('PagesController/products_admin');
        }

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        $image_name = $this->input->post('old_image');
        if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            } else {
                $data['food_details'] = $this->PagesModel->getRecordsFood();
                $data['stores'] = $this->PagesModel->getStores();
                $data['open_edit_modal'] = $foodID;
                $data['upload_error'] = $this->upload->display_errors();
                $this->load->view('admin_products', $data);
                return;
            }
        }

        $data = array(
            'food_name'   => $this->input->post('food_name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price'       => $this->input->post('price', TRUE),
            'stocks'      => $this->input->post('stocks', TRUE),
            'type'        => $this->input->post('type', TRUE),
            'image'       => $image_name,
            'storeID'     => $this->input->post('storeID', TRUE)
        );

        $this->PagesModel->updateFood($foodID, $data);
        $this->session->set_flashdata('message', 'Product updated successfully!');
        $this->session->set_flashdata('type', 'success');
        redirect('PagesController/products_admin');
    }

    // Delete Food
    public function delete_food($foodID) {
        $food_item = $this->PagesModel->get_food($foodID);

        if($food_item) {
            $deleted = $this->PagesModel->delete_food($foodID);

            if($deleted) {
                $this->load->library('Phpmailer_lib');
                $this->send_delete_email($food_item);

                $this->session->set_flashdata('message', 'Food deleted successfully!');
                $this->session->set_flashdata('type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Failed to delete food.');
                $this->session->set_flashdata('type', 'error');
            }
        } else {
            $this->session->set_flashdata('message', 'Food item not found.');
            $this->session->set_flashdata('type', 'error');
        }

        redirect('PagesController/products_admin');
    }

    private function send_delete_email($food_item) {
        $mail = $this->phpmailer_lib->load();

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ongjaredb@gmail.com';
            $mail->Password   = 'xteo jhia oqkk pxuo';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('ongjaredb@gmail.com', 'FoodsOUT Admin');
            $mail->addAddress('2088581@g.cu.edu.ph');

            $mail->Subject = "PRODUCT DELETED: " . $food_item->food_name;
            $mail->isHTML(true);

            $message = "<h3>Product Deleted</h3>";
            $message .= "<p>The following product has been deleted:</p>";
            $message .= "<ul>";
            $message .= "<li><b>Name:</b> " . htmlspecialchars($food_item->food_name) . "</li>";
            $message .= "<li><b>Code:</b> " . htmlspecialchars($food_item->food_id) . "</li>";
            $message .= "<li><b>Type:</b> " . ucfirst($food_item->type) . "</li>";
            $message .= "<li><b>Price:</b> ₱" . number_format($food_item->price,2) . "</li>";
            $message .= "<li><b>Stocks:</b> " . $food_item->stocks . "</li>";
            $message .= "</ul>";

            if(!empty($food_item->image) && file_exists('./uploads/'.$food_item->image)) {
                $mail->addAttachment('./uploads/'.$food_item->image, $food_item->image);
            }

            $mail->Body = $message;
            $mail->send();
        } catch (Exception $e) {
            log_message('error', 'Mailer Error: ' . $mail->ErrorInfo);
        }
    }

    /**
     * Send simple receipt email after order placed.
     * Receives saved $order object and optional $post array (billing fields from checkout form).
     * Attaches food images/files as plain attachments (no inline embedding).
     */
    private function send_receipt_email($order, $post = null) {
        if (empty($order)) return;

        // load mailer
        $this->load->library('Phpmailer_lib');
        $mail = $this->phpmailer_lib->load();

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ongjaredb@gmail.com';
            $mail->Password   = 'xteo jhia oqkk pxuo';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // ensure UTF-8 to fix currency/character corruption
            $mail->CharSet   = 'UTF-8';
            $mail->Encoding  = 'base64';

            $fromEmail = 'ongjaredb@gmail.com';
            $mail->setFrom($fromEmail, 'FoodsOUT');

            // recipient from customer record or posted email
            $customer = $this->PagesModel->get_customer($order->userID);
            $to = ($post && !empty($post['email'])) ? $post['email'] : (isset($customer->email_address) ? $customer->email_address : null);
            if (empty($to)) return;
            $mail->addAddress($to);

            $mail->Subject = "Receipt - Order #".(isset($order->orderID)?$order->orderID:'-');
            $mail->isHTML(true);

            // billing address (from posted fields when available)
            $billingName = '';
            $billingAddr = '';
            $billingPhone = '';
            if ($post) {
                $billingName = trim(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? ''));
                $billingAddr = trim(($post['address'] ?? '') . ', ' . ($post['city'] ?? '') . ' ' . ($post['postal'] ?? ''));
                $billingPhone = $post['phone'] ?? '';
            } elseif ($customer) {
                $billingName = trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));
                $billingAddr = trim($customer->address ?? '');
                $billingPhone = $customer->phone_number ?? '';
            }

            // cart items - try decode cart_json saved in order, fallback to posted cart_json (checkout form)
            $items = [];
            $rawCart = null;
            if (!empty($order->cart_json)) {
                $rawCart = $order->cart_json;
            } elseif (!empty($post) && !empty($post['cart_json'])) {
                $rawCart = $post['cart_json'];
            }
            if (!empty($rawCart)) {
                $items = @json_decode($rawCart, true);
                if (!is_array($items)) $items = [];
            }

            // Attachments: attach local files referenced by items (uploads/) once each
            $attachedFiles = [];
            $attachedNames = [];

            if (!empty($items)) {
                foreach ($items as $it) {
                    $img = $it['img'] ?? ($it['image'] ?? null);
                    $foodId = $it['id'] ?? ($it['foodID'] ?? null);

                    // candidate local paths
                    $candidates = [];
                    if (!empty($img)) {
                        // possible relative path or filename
                        $candidates[] = './' . ltrim($img, '/');
                        $candidates[] = './uploads/' . ltrim($img, '/');
                        // if already a full system path, include it
                        if (strpos($img, DIRECTORY_SEPARATOR) !== false) $candidates[] = $img;
                    }
                    if (!empty($foodId)) {
                        $food = $this->PagesModel->get_food((int)$foodId);
                        if (!empty($food->image)) {
                            $candidates[] = './uploads/' . ltrim($food->image, '/');
                            $candidates[] = $food->image;
                        }
                    }

                    foreach ($candidates as $cpath) {
                        if (empty($cpath)) continue;
                        // normalize path
                        $cpath = str_replace(['//','\\\\'], DIRECTORY_SEPARATOR, $cpath);
                        if (file_exists($cpath) && is_file($cpath)) {
                            // attach only once
                            if (!in_array($cpath, $attachedFiles, true)) {
                                try {
                                    $mail->addAttachment($cpath, basename($cpath));
                                    $attachedFiles[] = $cpath;
                                    $attachedNames[] = basename($cpath);
                                } catch (\Throwable $e) {
                                    // ignore attachment failures (continue)
                                }
                            }
                            break; // stop checking further candidates for this item
                        }
                    }
                }
            }

            // Build attractive HTML receipt
            $orderId = htmlspecialchars($order->orderID ?? '-');
            $orderDate = isset($order->order_date) ? date('M d, Y H:i', strtotime($order->order_date)) : date('M d, Y H:i');
            $total = '₱' . number_format((float)($order->total_amount ?? 0), 2);
            $method = htmlspecialchars($order->payment_method ?? ($post['payment_method'] ?? ''));
            $status = htmlspecialchars($order->status ?? '');
            $pstatus = htmlspecialchars($order->payment_status ?? '');

            $body  = '<div style="font-family:Arial,Helvetica,sans-serif;color:#222;max-width:700px;margin:0 auto;padding:18px;">';
            $body .= '<div style="background:#ff4d00;color:#fff;padding:12px 16px;border-radius:8px 8px 6px 6px;">';
            $body .= '<h1 style="margin:0;font-size:20px;">FoodsOUT</h1>';
            $body .= '<div style="font-size:13px;opacity:0.95;">Order Receipt</div>';
            $body .= '</div>';

            $body .= '<div style="padding:14px 8px 0 8px;">';
            $body .= '<table style="width:100%;border-collapse:collapse;margin-bottom:12px;">';
            $body .= '<tr><td style="vertical-align:top;padding:8px;"><strong>Order #</strong><br>' . $orderId . '</td>';
            $body .= '<td style="vertical-align:top;padding:8px;text-align:right;"><strong>Date</strong><br>' . htmlspecialchars($orderDate) . '</td></tr>';
            $body .= '<tr><td style="vertical-align:top;padding:8px;"><strong>Payment</strong><br>' . $method . '</td>';
            $body .= '<td style="vertical-align:top;padding:8px;text-align:right;"><strong>Status</strong><br>' . $status . ' • ' . $pstatus . '</td></tr>';
            $body .= '</table>';

            $body .= '<div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:10px;">';
            $body .= '<div style="flex:1;min-width:220px;">';
            $body .= '<div style="font-weight:700;margin-bottom:6px;">Billing</div>';
            $body .= '<div style="color:#555;font-size:14px;">' . htmlspecialchars($billingName ?: $to) . '<br>' . nl2br(htmlspecialchars($billingAddr)) . '<br>' . htmlspecialchars($billingPhone) . '</div>';
            $body .= '</div>';
            $body .= '<div style="width:180px;min-width:160px;">';
            $body .= '<div style="font-weight:700;margin-bottom:6px;">Total Paid</div>';
            $body .= '<div style="font-size:18px;color:#ff4d00;font-weight:800;">' . $total . '</div>';
            $body .= '</div>';
            $body .= '</div>';

            // Items table
            $body .= '<div style="margin-top:8px;">';
            $body .= '<table style="width:100%;border-collapse:collapse;">';
            $body .= '<thead><tr style="background:#f7f7f7;color:#333;text-align:left;"><th style="padding:10px 8px;border-bottom:1px solid #eee;">Item</th><th style="padding:10px 8px;border-bottom:1px solid #eee;">Qty</th><th style="padding:10px 8px;border-bottom:1px solid #eee;text-align:right;">Price</th></tr></thead>';
            $body .= '<tbody>';

            if (!empty($items)) {
                foreach ($items as $it) {
                    $name = htmlspecialchars($it['name'] ?? ($it['title'] ?? 'Item'));
                    $qty  = (int)($it['qty'] ?? $it['quantity'] ?? 1);
                    $price = number_format((float)($it['price'] ?? $it['amount'] ?? 0), 2);
                    $body .= '<tr>';
                    $body .= '<td style="padding:10px 8px;border-top:1px solid #f1f1f1;">' . $name . '</td>';
                    $body .= '<td style="padding:10px 8px;border-top:1px solid #f1f1f1;">' . $qty . '</td>';
                    $body .= '<td style="padding:10px 8px;border-top:1px solid #f1f1f1;text-align:right;">₱' . $price . '</td>';
                    $body .= '</tr>';
                }
            } else {
                $body .= '<tr><td colspan="3" style="padding:12px 8px;color:#666;">No items available</td></tr>';
            }

            $body .= '</tbody>';
            $body .= '<tfoot>';
            $body .= '<tr><td colspan="2" style="padding:10px 8px;border-top:1px solid #eee;"><strong>Subtotal</strong></td><td style="padding:10px 8px;border-top:1px solid #eee;text-align:right;">' . $total . '</td></tr>';
            $body .= '</tfoot>';
            $body .= '</table>';
            $body .= '</div>';

            // Attachments list (if any)
            if (!empty($attachedNames)) {
                $body .= '<div style="margin-top:12px;padding:10px;background:#f9f9fb;border-radius:8px;font-size:13px;">';
                $body .= '<strong>Attachments included:</strong><br>';
                $body .= '<ul style="margin:8px 0 0 18px;padding:0;color:#444;">';
                foreach ($attachedNames as $fn) {
                    $body .= '<li>' . htmlspecialchars($fn) . '</li>';
                }
                $body .= '</ul>';
                $body .= '</div>';
            }

            $body .= '<div style="margin-top:18px;color:#666;font-size:13px;">If you have questions about your order, reply to this email. Thank you for ordering from FoodsOUT!</div>';
            $body .= '</div></div>';

            $mail->Body = $body;
            $mail->send();
        } catch (\Throwable $e) {
            log_message('error', 'Receipt Email Error: ' . ($mail->ErrorInfo ?? $e->getMessage()));
        }
    }
}
?>
