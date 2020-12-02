<?php defined('BASEPATH') or exit('No direct script access allowed');

class Utilities extends MY_Controller
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
        $this->load->admin_model('utilities_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }

    public function product()
    {
        $this->sma->checkPermissions();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Product Utilities')));
        $meta = array('page_title' => lang('Product Utilities'), 'bc' => $bc);

        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['categories'] = $this->db->get('categories')->result();

            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $category_id =$this->input->get('category_id');
            if($from !== null && $to !== null &&  $category_id !== null){
                $products = $this->db->select('code,products.id,cost,price,name');
                // $products = $this->db->select('*');

                $from = $this->sma->dateTime($from);
                $to = $this->sma->dateTime($to);

                if($category_id == "all"){
                    $products = $products->get('products');
                }else{
                    $products = $products->where('category_id', $category_id)->get('products');
                }
                $products = $products->result();

                foreach($products as $product){
                    // purchases
                    $purchases = $this->db->select('max(sma_purchase_items.unit_cost) as last_highest_cost, min(sma_purchase_items.unit_cost) as last_lowest_cost');
                    $purchases = $purchases->where('purchases.date >=', $from)->where('purchases.date <=', $to);
                    $purchases = $purchases->join('purchase_items', 'purchase_items.purchase_id = purchases.id');
                    $purchases = $purchases->where('purchase_items.product_id', $product->id);
                    $purchases = $purchases->get('purchases')->row();
                    $product->last_highest_cost = $purchases->last_highest_cost;
                    $product->last_lowest_cost = $purchases->last_lowest_cost;

                    //sales
                    $sales = $this->db->select('max(sma_sale_items.unit_price) as last_highest_price, min(sma_sale_items.unit_price) as last_lowest_price');
                    $sales = $sales->where('sales.date >=', $from)->where('sales.date <=', $to);
                    $sales = $sales->join('sale_items', 'sale_items.sale_id = sales.id');
                    $sales = $sales->where('sale_items.product_id', $product->id);
                    $sales = $sales->get('sales')->row();
                    $product->last_highest_price = $sales->last_highest_price;
                    $product->last_lowest_price = $sales->last_lowest_price;
                }
                $this->data['products'] = $products;
            }
            $this->page_construct('utilities/index', $meta, $this->data);
        }
    }

    public function updateProducts(){
        $this->sma->checkPermissions();
        
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        foreach($this->input->post('product') as $product_id => $data){
            if(isset($data['checkbox'])){
               unset($data['checkbox']);
               $data['last_from'] = $from;
               $data['last_to'] = $to;

               $product = $this->db->where('id', $product_id)->get('products')->row();


               $purchases = $this->db->select('max(sma_purchase_items.unit_cost) as last_highest_cost, min(sma_purchase_items.unit_cost) as last_lowest_cost');
                $purchases = $purchases->where('purchases.date >=', $from)->where('purchases.date <=', $to);
                $purchases = $purchases->join('purchase_items', 'purchase_items.purchase_id = purchases.id');
                $purchases = $purchases->where('purchase_items.product_id', $product_id);
                $purchases = $purchases->get('purchases')->row();
                $data['last_highest_cost'] = $purchases->last_highest_cost ? $purchases->last_highest_cost : $product->cost;
                $data['last_lowest_cost'] = $purchases->last_lowest_cost ? $purchases->last_lowest_cost : $product->cost;

                //sales
                $sales = $this->db->select('max(sma_sale_items.unit_price) as last_highest_price, min(sma_sale_items.unit_price) as last_lowest_price');
                $sales = $sales->where('sales.date >=', $from)->where('sales.date <=', $to);
                $sales = $sales->join('sale_items', 'sale_items.sale_id = sales.id');
                $sales = $sales->where('sale_items.product_id', $product_id);
                $sales = $sales->get('sales')->row();
                $data['last_highest_price'] = $sales->last_highest_price ? $sales->last_highest_price : $product->price;
                $data['last_lowest_price'] = $sales->last_lowest_price ? $sales->last_lowest_price : $product->price;

               $this->db->where('id', $product_id)->update('products', $data);
             }
        }
        $this->session->set_flashdata('message', lang("Successfully updated products"));
        admin_redirect("products");

    }
}
