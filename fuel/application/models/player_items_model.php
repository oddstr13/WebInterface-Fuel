<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Player_items_model extends Base_module_model {
 
	public $foreign_keys = array('item_id' => 'items_model');
	
    function __construct()
    {
        parent::__construct('wa_player_items');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->join('wa_items', 'wa_items.id = wa_player_items.item_id', 'left');
        $this->db->select('wa_player_items.id, wa_player_items.item_id, wa_player_items.player, wa_items.name AS name, wa_items.damage AS damage, wa_player_items.quantity', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_player_items($player)
    {
		$this->db->join('wa_items', 'wa_items.id = wa_player_items.item_id', 'left');
		$this->db->select('wa_player_items.id, wa_player_items.item_id, wa_player_items.player, wa_items.name AS name, wa_items.damage AS damage, wa_player_items.quantity', FALSE);
		$query = $this->db->get_where('wa_player_items', array('wa_player_items.player' => $player));
        return $query->result();
    }
	function get_item($id)
	{
		$this->db->join('wa_items', 'wa_items.id = wa_player_items.item_id', 'left');
		$this->db->select('wa_player_items.id, wa_player_items.item_id, wa_player_items.player, wa_items.name AS name, wa_items.damage AS damage, wa_player_items.quantity', FALSE);
		$query = $this->db->get_where('wa_player_items', array('wa_player_items.id' => $id));
		return $query->result();
	}
	function get_item_match($item_id, $player)
	{
		$this->db->join('wa_items', 'wa_items.id = wa_player_items.item_id', 'left');
		$this->db->select('wa_player_items.id, wa_player_items.item_id, wa_player_items.player, wa_items.name AS name, wa_items.damage AS damage, wa_player_items.quantity', FALSE);
		$query = $this->db->get_where('wa_player_items', array('wa_player_items.item_id' => $item_id, 'wa_player_items.player' => $player));
		return $query->result();
	}
	function set_quant($item_id, $new_quant)
	{
		$this->db->update('wa_player_items', array('quantity' => $new_quant), array('id' => $item_id));
	}
	function set_quantity($id, $amount)
	{
		$this->db->update('wa_player_items', array('quantity' => $amount), array('id' => $id));	
	}
	function new_player_item($item_id, $player, $quantity)
	{
		$data = array(
   			'item_id' => $item_id ,
   			'quantity' => $quantity ,
			'player' => $player
		);

		$this->db->insert('wa_player_items', $data); 
	}
}
