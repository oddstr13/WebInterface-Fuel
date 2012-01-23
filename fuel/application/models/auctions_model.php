<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Auctions_model extends Base_module_model {
 
	public $foreign_keys = array('item_id' => 'items_model');
	
    function __construct()
    {
        parent::__construct('wa_auctions');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->join('wa_items', 'wa_items.id = wa_auctions.item_id', 'left');
        $this->db->select('wa_auctions.id, wa_auctions.item_id, wa_items.owner AS seller, wa_auctions.price, wa_items.name AS name, wa_items.damage AS damage, wa_auctions.quantity, wa_auctions.started', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_player_auctions($player)
    {
		$this->db->join('wa_items', 'wa_items.id = wa_auctions.item_id', 'left');
		$this->db->select('wa_auctions.id, wa_auctions.item_id, wa_items.owner AS seller, wa_auctions.price, wa_items.name AS name, wa_items.damage AS damage, wa_auctions.quantity, wa_auctions.started', FALSE);
		$query = $this->db->get_where('wa_auctions', array('wa_items.owner' => $player));
        return $query->result();
    }
}
 
class Auction_model extends Base_module_record {
 
}
