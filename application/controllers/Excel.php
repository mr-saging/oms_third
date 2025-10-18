<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;


class Excel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session'); // ✅ Make sure this line exists
        $this->load->helper(array('url', 'form'));
        $this->load->model('Excel_model');
    }

    public function index() {
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('excel_import', $data);
    }



    public function import() {
        $file_mimes = ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv'];

        if (isset($_FILES['uploadFile']['name']) && in_array($_FILES['uploadFile']['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES['uploadFile']['name']);
            $extension = strtolower(end($arr_file));

            $reader = ($extension == 'csv') ? IOFactory::createReader('Csv') : IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($_FILES['uploadFile']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $data = [];
            $rowIndex = 0;
            foreach ($sheetData as $row) {
                $rowIndex++;
                if ($rowIndex == 1) continue; // skip header
                $data[] = [
                    'name'    => $row['A'] ?? '',
                    'email'   => $row['B'] ?? '',
                    'phone'   => $row['C'] ?? '',
                    'course'  => $row['D'] ?? '',
                    'address' => $row['E'] ?? '',
                ];
            }

            if (!empty($data)) {
                $this->Excel_model->insert_batch($data);
                $this->session->set_flashdata('success', 'Data imported successfully!');
            } else {
                $this->session->set_flashdata('error', 'No data found to import.');
            }
        } else {
            $this->session->set_flashdata('error', 'Please upload a valid Excel/CSV file.');
        }
        redirect('excel');
    }
}
