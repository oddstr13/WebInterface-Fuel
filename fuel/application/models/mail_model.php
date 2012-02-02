<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Mail_model extends Base_module_model {
 
	public $foreign_keys = array('item_id' => 'items_model', 'player' => 'players_model');
	
    function __construct()
    {
        parent::__construct('wa_mail');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->join('wa_items', 'wa_items.id = wa_mail.item_id', 'left');
		$this->db->join('wa_users', 'wa_users.id = wa_mail.player', 'left');
        $this->db->select('wa_mail.id, wa_mail.item_id, wa_users.username AS owner, wa_items.name AS name, wa_items.damage AS damage, wa_mail.quantity', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_player_mail($player)
    {
		$this->db->join('wa_items', 'wa_items.id = wa_mail.item_id', 'left');
		$this->db->join('wa_users', 'wa_users.id = wa_mail.player', 'left');
		$this->db->select('wa_mail.id, wa_mail.item_id, wa_users.username AS player, wa_items.name AS name, wa_items.damage AS damage, wa_mail.quantity', FALSE);
		$query = $this->db->get_where('wa_mail', array('wa_mail.player' => $player));
        return $query->result();
    }
	function delete_mail($id)
	{
		$this->db->delete('wa_mail', array('id' => $id)); 	
	}
	function new_mail($item_id, $player, $quantity)
	{
		$data = array(
   			'item_id' => $item_id ,
   			'quantity' => $quantity ,
			'player' => $player
		);

		$this->db->insert('wa_mail', $data); 
	}
}
