<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Enchantments_model extends Base_module_model {
 
	public $foreign_keys = array('item_id' => 'items_model');
	
    function __construct()
    {
        parent::__construct('wa_enchantments');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->join('wa_items', 'wa_items.id = wa_enchantments.item_id', 'left');
        $this->db->select('wa_enchantments.id, wa_enchantments.name, wa_enchantments.level, wa_enchantments.item_id', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_item_enchantments($item_id)
    {
		$query = $this->db->get_where('wa_enchantments', array('item_id' => $item_id));
        return $query->result();
    }

}
 
class Enchantment_model extends Base_module_record {
 
}
