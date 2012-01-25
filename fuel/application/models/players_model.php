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
}