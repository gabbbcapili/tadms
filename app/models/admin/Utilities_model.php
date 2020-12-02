<?php defined('BASEPATH') or exit('No direct script access allowed');

class Utilities_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5)
    {
        $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getChequeById($id)
    {
        $q = $this->db->get_where('cheque', ['id' => $id], 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


    public function addCheque($data = array())
    {
        if ($this->db->insert('cheque', $data)) {
            return true;
        }

        return false;
    }

    public function updateCheque($id, $data = array())
    {
        if ($this->db->update('cheque', $data, array('id' => $id))) {
            return true;
        }

        return false;
    }

    public function resetSaleActions($id)
    {
        if ($items = $this->getReturnItems($id)) {
            foreach ($items as $item) {
                if ($item->product_type == 'standard') {
                    $clause = ['product_id' => $item->product_id, 'purchase_id' => null, 'transfer_id' => null, 'option_id' => $item->option_id];
                    $this->site->setPurchaseItem($clause, (0-$item->quantity));
                    $this->site->syncQuantity(null, null, null, $item->product_id);
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($item->product_id);
                    foreach ($combo_items as $combo_item) {
                        $clause = ['product_id' => $combo_item->id, 'purchase_id' => null, 'transfer_id' => null, 'option_id' => null];
                        $this->site->setPurchaseItem($clause, (0-($combo_item->qty*$item->quantity)));
                        $this->site->syncQuantity(null, null, null, $combo_item->id);
                    }
                }
            }
        }
    }

    public function deleteCheque($id)
    {
        if ( $this->db->delete('cheque', array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function updateToDeposited($id){
        if ($this->db->update('cheque', array('is_deposited' => 1))) {
            return true;
        }
        return false;
    }

    public function getCheques($type = 0, $is_deposited = 0, $used = 0){
        return $this->db->get_where('cheque', ['type' => $type, 'is_deposited' => $is_deposited, 'used' => $used])->result();
    }
}
