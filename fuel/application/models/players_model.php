<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Players_model extends Base_module_model {
	
    function __construct()
    {
        parent::__construct('wa_users');
    }
	function find_active()
	{
		$this->db->where('bought >', 0);
		$this->db->or_where('sold >', 0); 
        $query = $this->db->get('wa_users');
        return $query->result();
	}
	function set_money($name, $amount)
	{
		$this->db->update('wa_users', array('money' => $amount), array('id' => $name));	
	}
	function set_sales($name, $sold, $earnt)
	{
		$this->db->update('wa_users', array('sold' => $sold, 'earnt' => $earnt), array('id' => $name));	
	}
	function set_purchases($name, $bought, $spent)
	{
		$this->db->update('wa_users', array('bought' => $bought, 'spent' => $spent), array('id' => $name));	
	}
}