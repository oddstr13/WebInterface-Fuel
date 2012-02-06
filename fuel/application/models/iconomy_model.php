<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Iconomy_model extends Base_module_model {
	
    function __construct()
    {
        parent::__construct($this->config->item('iconomy_table_name'));
    }
	function set_money($name, $amount)
	{
		$this->db->update($this->config->item('iconomy_table_name'), array('balance' => $amount), array('username' => $name));	
	}
	function get_money($username)
	{
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->config->item('iconomy_table_name'));
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
}