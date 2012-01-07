<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Items_model extends Base_module_model {
 
    function __construct()
    {
        parent::__construct('wa_items');
    }
}
 
class Item_model extends Base_module_record {
 
}
