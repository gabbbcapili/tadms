<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cheque extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Supplier || $this->Customer) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->library('form_validation');
        $this->load->admin_model('Cheque_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }

    public function index($warehouse_id = null)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('cheque')));
        $meta = array('page_title' => lang('cheque'), 'bc' => $bc);
        $this->page_construct('cheque/index', $meta, $this->data);
    }

    public function getCheque($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        $this->load->library('datatables');

        $this->datatables
            ->select("{$this->db->dbprefix('cheque')}.id as id, if(sma_cheque.type=1, 'Customer', 'Supplier'), DATE_FORMAT({$this->db->dbprefix('cheque')}.transaction_date, '%Y-%m-%d %T') as transaction_date, DATE_FORMAT({$this->db->dbprefix('cheque')}.deposit_date, '%Y-%m-%d %T') as deposite_date, cheque_code, cheque_number, amount, bank_name, if(sma_cheque.is_deposited=1, 'Yes', 'No'), account_name")
            ->from('cheque');

        $this->datatables->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('cheque/edit/$1') . "' class='tip' title='" . lang("Edit Cheque") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("Delete Cheque") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('cheque/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        echo $this->datatables->generate();
    }

    public function view($id = null)
    {
        $this->sma->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $cheque = $this->Cheque_model->getChequeById($id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($cheque->created_by, true);
        }
        $this->data['created_by'] = $this->site->getUser($cheque->created_by);
        $this->data['cheque'] = $cheque;
        $this->load->view($this->theme . 'cheque/view', $this->data);
    }

    public function add()
    {
        $this->sma->checkPermissions();
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('amount', lang("amount"), 'required');

        if ($this->form_validation->run() == true) {
            $data = [
            	'type' =>  $this->input->post('type'),
            	'deposit_date' =>  $this->input->post('deposit_date'),
            	'transaction_date' =>  $this->input->post('transaction_date'),
            	'amount' =>  $this->input->post('amount'),
            	'cheque_code' =>  $this->input->post('cheque_code'),
            	'cheque_number' => $this->input->post('cheque_number'),
            	'bank_name' =>  $this->input->post('bank_name'),
            	'account_name' =>  $this->input->post('account_name'),
            	'account_name' => $this->input->post('account_name'),
            	'account_number' =>  $this->input->post('account_number'),
            	'is_deposited' =>  $this->input->post('is_deposited'),
            	'created_by' =>  $this->session->userdata('user_id'),
            	'created_at' =>  date('Y-m-d H:i:s'),
            ];
        }
        if ($this->form_validation->run() == true && $this->Cheque_model->addCheque($data)) {
            $this->session->set_flashdata('message', lang("Cheque Added"));
            admin_redirect("cheque");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('cheque'), 'page' => lang('cheque')), array('link' => '#', 'page' => lang('Add Cheque')));
            $meta = array('page_title' => lang('Add Cheque'), 'bc' => $bc);
            $this->page_construct('cheque/add', $meta, $this->data);
        }
    }

    public function edit($id = null)
    {
        $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->Cheque_model->getChequeById($id);

        // die(var_dump($inv));
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
		$this->form_validation->set_rules('amount', lang("amount"), 'required');


        if ($this->form_validation->run() == true) {
       
            // $this->sma->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true) {
            $data = [
            	'type' =>  $this->input->post('type'),
            	'deposit_date' =>  $this->input->post('deposit_date'),
            	'transaction_date' =>  $this->input->post('transaction_date'),
            	'amount' =>  $this->input->post('amount'),
            	'cheque_code' =>  $this->input->post('cheque_code'),
            	'cheque_number' => $this->input->post('cheque_number'),
            	'bank_name' =>  $this->input->post('bank_name'),
            	'account_name' =>  $this->input->post('account_name'),
            	'account_name' => $this->input->post('account_name'),
            	'account_number' =>  $this->input->post('account_number'),
            	'is_deposited' =>  $this->input->post('is_deposited'),
            	'created_by' =>  $this->session->userdata('user_id'),
            ];
        }
        if ($this->form_validation->run() == true && $this->Cheque_model->updateCheque($id, $data)) {
            $this->session->set_flashdata('message', lang("Cheque Edited"));
            admin_redirect("cheque");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('cheque'), 'page' => lang('cheque')), array('link' => '#', 'page' => lang('Add Cheque')));
            $this->data['inv'] = $inv;
            $this->data['id'] = $id;
            $meta = array('page_title' => lang('Add Cheque'), 'bc' => $bc);
            $this->page_construct('cheque/edit', $meta, $this->data);
        }
    }

    public function delete($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->Cheque_model->deleteCheque($id)) {
            if ($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => lang("Cheque Deleted")));
            }
            $this->session->set_flashdata('message', lang('Cheque Deleted'));
            admin_redirect('welcome');
        }
    }

    function cheque_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->Cheque_model->deleteCheque($id);
                    }
                    $this->session->set_flashdata('message', lang("Cheque Deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('Cheque'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('Type'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('Transaction Date'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('Deposit Date'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('Amount'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('Cheque Code'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('Cheque Number'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('Bank Name'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('Account Namer'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('Deposited'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('Created At'));
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $cheque = $this->Cheque_model->getChequeById($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $cheque->type == 1 ? 'Customer' : 'Supplier');
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->sma->hrld($cheque->transaction_date));
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->sma->hrld($cheque->deposit_date));
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $cheque->amount);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $cheque->cheque_code);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $cheque->cheque_number);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $cheque->bank_name);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $cheque->account_name);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $cheque->is_deposited == 1 ? 'Yes' : 'No');
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $this->sma->hrld($cheque->created_at));
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'cheque_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
                elseif ($this->input->post('form_action') == 'deposited') {

           
                    foreach ($_POST['val'] as $id) {
                        $cheque = $this->Cheque_model->updateToDeposited($id);
                       
                    }

                    $this->session->set_flashdata('message', lang("Cheque Updated [Deposited]"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("No Cheque Selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
}
