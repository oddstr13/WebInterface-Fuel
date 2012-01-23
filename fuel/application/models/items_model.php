<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Items_model extends Base_module_model {
 
    function __construct()
    {
        parent::__construct('wa_items');
    }
	function list_items($limit = NULL, $offset = NULL, $col = 'id', $order = 'asc')
    {
        $this->db->select('wa_items.id, wa_items.name, wa_items.damage, wa_items.owner, wa_items.quantity', FALSE);
        $data = parent::list_items($limit, $offset, $col, $order);
        return $data;
    }
	function get_player_items($player)
    {
		$query = $this->db->get_where('wa_items', array('owner' => $player));
        return $query->result();
    }
	function isTrueDamage ($itemId)
	{
		$baseDur = 0;
		switch ($itemId)
		{
			case 261:
				$baseDur = 385;
				break;
			case 256:
			case 257:
			case 258:
			case 267:
			case 292:				
				$baseDur = 251;
				break;
			case 268:
			case 269:
			case 270:
			case 271:
			case 290:				
				$baseDur = 60;
				break;
			case 272:
			case 273:
			case 274:
			case 275:
			case 291:				
				$baseDur = 132;
				break;
			case 276:
			case 277:
			case 278:
			case 279:
			case 293:				
				$baseDur = 1562;
				break;
			case 283:
			case 284:
			case 285:
			case 286:
			case 294:				
				$baseDur = 33;
				break;
			case 298:				
				$baseDur = 34;
				break;
			case 299:				
				$baseDur = 49;
				break;
			case 300:				
				$baseDur = 46;
				break;	
			case 301:				
				$baseDur = 40;
				break;
			case 302:				
				$baseDur = 67;
				break;
			case 303:				
				$baseDur = 96;
				break;
			case 304:				
				$baseDur = 92;
				break;
			case 305:				
				$baseDur = 79;
				break;
			case 306:				
				$baseDur = 136;
				break;
			case 307:				
				$baseDur = 192;
				break;
			case 308:				
				$baseDur = 184;
				break;
			case 309:				
				$baseDur = 160;
				break;
			case 310:				
				$baseDur = 272;
				break;
			case 311:				
				$baseDur = 384;
				break;
			case 312:				
				$baseDur = 368;
				break;
			case 313:				
				$baseDur = 320;
				break;
			case 314:				
				$baseDur = 68;
				break;
			case 315:				
				$baseDur = 996;
				break;
			case 316:				
				$baseDur = 92;
				break;
			case 317:				
				$baseDur = 80;
				break;
			default:
				$baseDur = 0;
				break;
		}
		return $baseDur;
	}
	function get_item_image($id, $damage, $base)
	{
		if ($base > 0){
			return "/assets/images/".$id.".png";	
		}else{
			return "/assets/images/".$id."-".$damage.".png";	
		}
	}
}
 
class Item_model extends Base_module_record {
 
}
