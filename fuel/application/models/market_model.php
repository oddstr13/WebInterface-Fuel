<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Market_model extends Base_module_model {
 
	public $foreign_keys = array('item_id' => 'items_model');
	
    function __construct()
    {
        parent::__construct('wa_market_prices');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->join('wa_items', 'wa_items.id = wa_market_prices.item_id', 'left');
        $this->db->select('wa_market_prices.id, wa_market_prices.item_id, wa_market_prices.quantity, wa_market_prices.price, wa_items.name AS name, wa_items.damage AS damage', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_market_price($item_id)
	{
		$query = $this->db->get_where('wa_market_prices', array('item_id' => $item_id));
		return $query->result();
	}
	function new_market_price($item_id, $quantity, $price)
	{
		$data = array(
   			'item_id' => $item_id ,
   			'quantity' => $quantity ,
			'price' => $price
		);

		$this->db->insert('wa_market_prices', $data); 
	}
	function set_price($id, $amount, $price)
	{
		$this->db->update('wa_market_prices', array('quantity' => $amount, 'price' => $price), array('id' => $id));	
	}
}
