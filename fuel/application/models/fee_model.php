<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(FUEL_PATH.'models/base_module_model.php');
  
class Fee_model extends Base_module_model {
	
    function __construct()
    {
        parent::__construct('wa_storage_fee');
    }
	function get_last_fee()
    {
		$this->db->select('id, time', FALSE);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('wa_storage_fee');
        return $query->result();
    }
	function new_fee()
	{
		$data = array('time' => time());

		$this->db->insert('wa_storage_fee', $data); 
	}
}