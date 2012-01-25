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
		$query = $this->db->get_where('wa_items', array('owner' => $player, 'quantity >' => 0));
        return $query->result();
    }
	function get_item($item_id)
	{
		$query = $this->db->get_where('wa_items', array('id' => $item_id));
		return $query->result();
	}
	function set_quant($item_id, $new_quant)
	{
		$this->db->update('wa_items', array('quantity' => $new_quant), array('id' => $item_id));
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
	function numberToRoman($num) 
 	{
     // Make sure that we only use the integer portion of the value
     $n = intval($num);
     $result = '';
 
     // Declare a lookup array that we will use to traverse the number:
     $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
     'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
     'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
 
     foreach ($lookup as $roman => $value) 
     {
         // Determine the number of matches
         $matches = intval($n / $value);
 
         // Store that many characters
         $result .= str_repeat($roman, $matches);
 
         // Substract that from the number
         $n = $n % $value;
     }
 
     // The Roman numeral should be built, return it
     return $result;
 	}
	function getEnchName($enchId)
	{
		switch($enchId)
		{
			case 0:
				return "Protection";
				break;
			case 1:
				return "Fire Protecion";
				break;
			case 2:
				return "Feather Falling";
				break;
			case 3:
				return "Blast Protection";
				break;
			case 4:
				return "Projectile Protection";
				break;
			case 5:
				return "Respiration";
				break;
			case 6:
				return "Aqua Affinity";
				break;
			case 16:
				return "Sharpness";
				break;
			case 17:
				return "Smite";
				break;
			case 18:
				return "Bane of Arthropods";
				break;
			case 19:
				return "Knockback";
				break;
			case 20:
				return "Fire Aspect";
				break;
			case 21:
				return "Looting";
				break;
			case 32:
				return "Efficiency";
				break;
			case 33:
				return "Silk Touch";
				break;
			case 34:
				return "Unbreaking";
				break;
			case 35:
				return "Fortune";
				break;
			default:
				return "Unknown";
				break;
		}
	}
	function getItemMaxStack($itemId) {
	switch($itemId) {
		case 63:
			return 1;
		case 68:
			return 1;
		case 92:
			return 1;
		case 256:
			return 1;
		case 257:
			return 1;
		case 258:
			return 1;
		case 259:
			return 1;
		case 261:
			return 1;
		case 267:
			return 1;
		case 268:
			return 1;
		case 269:
			return 1;
		case 270:
			return 1;
		case 271:
			return 1;
		case 272:
			return 1;
		case 273:
			return 1;
		case 274:
			return 1;
		case 275:
			return 1;
		case 276:
			return 1;
		case 277:
			return 1;
		case 278:
			return 1;
		case 279:
			return 1;
		case 282:
			return 1;
		case 283:
			return 1;
		case 284:
			return 1;
		case 285:
			return 1;
		case 286:
			return 1;
		case 290:
			return 1;
		case 291:
			return 1;
		case 292:
			return 1;
		case 293:
			return 1;
		case 294:
			return 1;
		case 298:
			return 1;
		case 299:
			return 1;
		case 300:
			return 1;
		case 301:
			return 1;
		case 302:
			return 1;
		case 303:
			return 1;
		case 304:
			return 1;
		case 305:
			return 1;
		case 306:
			return 1;
		case 307:
			return 1;
		case 308:
			return 1;
		case 309:
			return 1;
		case 310:
			return 1;
		case 311:
			return 1;
		case 312:
			return 1;
		case 313:
			return 1;
		case 314:
			return 1;
		case 315:
			return 1;
		case 316:
			return 1;
		case 317:
			return 1;
		case 323:
			return 1;
		case 324:
			return 1;
		case 325:
			return 1;
		case 326:
			return 1;
		case 327:
			return 1;
		case 328:
			return 1;
		case 329:
			return 1;
		case 330:
			return 1;
		case 332:
			return 16;
		case 333:
			return 1;
		case 335:
			return 1;
		case 342:
			return 1;
		case 343:
			return 1;
		case 344:
			return 16;
		case 346:
			return 1;
		case 354:
			return 1;
		case 355:
			return 1;
		case 358:
			return 1;
		case 359:
			return 1;
		case 2256:
			return 1;
		case 2257:
			return 1;
		case 2258:
			return 1;
		case 2259:
			return 1;
		case 2260:
			return 1;
		case 2261:
			return 1;
		case 2262:
			return 1;
		case 2263:
			return 1;
		case 2264:
			return 1;
		case 2265:
			return 1;
		case 2266:
			return 1;
		default:
			return 64;
	}
	}
	function getItemName($itemId, $itemDamage)
	{
		switch ($itemId)
		{
			case 1:
				return "Stone";
				break;
			case 2:
				return "Grass";
				break;
			case 3:
				return "Dirt";
				break;	
			case 4:
				return "Cobblestone";
				break;
			case 5:
				return "Wooden Plank";
				break;
			case 6:
				switch ($itemDamage)
				{
					case 1:
						return "Redwood Sapling";
						break;
					case 2:
						return "Birch Sapling";
						break;
					default:
						return "Sapling";
						break;	
				}
				break;
			case 7:
				return "Bedrock";
				break;
			case 8:
				return "Water";
				break;
			case 9:
				return "Stationary Water";
				break;
			case 10:
				return "Lava";
				break;
			case 11:
				return "Stationary Lava";
				break;
			case 12:
				return "Sand";
				break;
			case 13:
				return "Gravel";
				break;
			case 14:
				return "Gold Ore";
				break;
			case 15:
				return "Iron Ore";
				break;
			case 16:
				return "Coal Ore";
				break;
			case 17:
				switch ($itemDamage)
				{
					case 1:
						return "Redwood Log";
						break;
					case 2:
						return "Birchwood Log";
						break;
					default:
						return "Log";
						break;
				}
				break;
			case 18:
				switch ($itemDamage)
				{
					case 1:
						return "Redwood Leaves";
						break;
					case 2:
						return "Birchwood Leaves";
						break;
					default:
						return "Leaves";
						break;
				}
				break;
			case 19:
				return "Sponge";
				break;
			case 20:
				return "Glass";
				break;
			case 21:
				return "Lapis Lazuli Ore";
				break;
			case 22:
				return "Lapis Lazuli Block";
				break;
			case 23:
				return "Dispenser";
				break;
			case 24:
				return "Sandstone";
				break;
			case 25:
				return "Note Block";
				break;
			case 26:
				return "Bed Block";
				break;
			case 27:
				return "Powered Rail";
				break;
			case 28:
				return "Detector Rail";
				break;
			case 29:
				return "Sticky Piston";
				break;
			case 30:
				return "Web";
				break;
			case 31:
				switch ($itemDamage)
				{
					case 1:
						return "Tall Grass";
						break;
					case 2:
						return "Live Shrub";
						break;
					default:
						return "Dead Shrub";
						break;
				}
				break;
			case 32:
				return "Dead Shrub";
				break;
			case 33:
				return "Piston";
				break;
			case 34:
				return "Piston Head";
				break;
			case 35:
				switch ($itemDamage)
				{
					case 1:
						return "Orange Wool";
						break;
					case 2:
						return "Magenta Wool";
						break;
					case 3:
						return "Light Blue Wool";
						break;
					case 4:
						return "Yellow Wool";
						break;
					case 5:
						return "Light Green Wool";
						break;
					case 6:
						return "Pink Wool";
						break;
					case 7:
						return "Grey Wool";
						break;
					case 8:
						return "Light Grey Wool";
						break;
					case 9:
						return "Cyan Wool";
						break;
					case 10:
						return "Purple Wool";
						break;
					case 11:
						return "Blue Wool";
						break;
					case 12:
						return "Brown Wool";
						break;
					case 13:
						return "Dark Green Wool";
						break;
					case 14:
						return "Red Wool";
						break;
					case 15:
						return "Black Wool";
						break;
					default:
						return "White Wool";
						break;
				}
				break;
			case 37:
				return "Dandelion";
				break;
			case 38:
				return "Rose";
				break;
			case 39:
				return "Brown Mushroom";
				break;
			case 40:
				return "Red Mushroom";
				break;
			case 41:
				return "Gold Block";
				break;
			case 42:
				return "Iron Block";
				break;
			case 43:
				switch ($itemDamage)
				{
					case 1:
						return "Double Sandstone Slab";
						break;
					case 2:
						return "Double Wooden Slab";
						break;
					case 3:
						return "Double Cobblestone Slab";
						break;
					default:
						return "Double Stone Slab";

						break;
				}
				break;
			case 44:
				switch ($itemDamage)
				{
					case 1:
						return "Sandstone Slab";
						break;
					case 2:
						return "Wooden Slab";
						break;
					case 3:
						return "Cobblestone Slab";
						break;
					default:
						return "Stone Slab";
						break;
				}
				break;
			case 45:
				return "Brick Block";
				break;
			case 46:
				return "TNT";
				break;
			case 47:
				return "Bookshelf";
				break;
			case 48:
				return "Mossy Cobblestone";
				break;
			case 49:
				return "Obsidian";
				break;
			case 50:
				return "Torch";
				break;
			case 51:
				return "Fire";
				break;
			case 52:
				return "Monster Spawner";
				break;
			case 53:
				return "Wooden Stairs";
				break;
			case 54:
				return "Chest";
				break;
			case 55:
				return "Redstone Wire";
				break;
			case 56:
				return "Diamond Ore";
				break;
			case 57:
				return "Diamond Block";
				break;
			case 58:
				return "Workbench";
				break;
			case 59:
				return "Crops";
				break;
			case 60:
				return "Soil";
				break;
			case 61:
				return "Furnace";
				break;
			case 62:
				return "Burning Furnace";
				break;
			case 63:
				return "Sign Post";
				break;
			case 64:
				return "Wooden Door Block";
				break;
			case 65:
				return "Ladder";
				break;
			case 66:
				return "Rails";
				break;
			case 67:
				return "Cobblestone Stairs";
				break;
			case 68:
				return "Wall Sign";
				break;
			case 69:
				return "Lever";
				break;
			case 70:
				return "Stone Pressure Plate";
				break;
			case 71:
				return "Iron Door Block";
				break;
			case 72:
				return "Wooden Pressure Plate";
				break;
			case 73:
				return "Redstone Ore";
				break;
			case 74:
				return "Glowing Redstone Ore";
				break;
			case 75:
				return "Redstone Torch";
				break;
			case 76:
				return "Redstone Torch";
				break;
			case 77:
				return "Stone Button";
				break;
			case 78:
				return "Snow";
				break;
			case 79:
				return "Ice";
				break;
			case 80:
				return "Snow Block";
				break;
			case 81:
				return "Cactus";
				break;
			case 82:
				return "Clay";
				break;
			case 83:
				return "Sugar Cane (Block)";
				break;
			case 84:
				return "Jukebox";
				break;
			case 85:
				return "Fence";
				break;
			case 86:
				return "Pumpkin";
				break;	
			case 87:
				return "Netherrack";
				break;
			case 88:
				return "Soul Sand";
				break;
			case 89:
				return "Glowstone";
				break;
			case 90:
				return "Portal";
				break;
			case 91:
				return "Jack-O-Lantern";
				break;
			case 92:
				return "Cake";
				break;
			case 93:
				return "Redstone Repeater";
				break;
			case 94:
				return "Redstone Repeater";
				break;
			case 95:
				return "Locked Chest";
				break;
			case 96:
				return "Trapdoor";
				break;
			case 97:
				return "Silverfish Stone";
				break;
			case 98:
				switch ($itemDamage)
				{
					case 1:
						return "Mossy Stone Brick";
						break;
					case 2:
						return "Cracked Stone Brick";
						break;
					default:
						return "Stone Brick";
						break;
				}
				break;
			case 99:
				return "Brown Mushroom Cap";
				break;
			case 100:
				return "Red Mushroom Cap";
				break;
			case 101:
				return "Iron Bars";
				break;
			case 102:
				return "Glass Pane";
				break;
			case 103:
				return "Melon";
				break;
			case 104:
				return "Pumpkin Stem";
				break;
			case 105:
				return "Melon Stem";
				break;
			case 106:
				return "Vines";
				break;
			case 107:
				return "Fence Gate";
				break;
			case 108:
				return "Brick Stairs";
				break;
			case 109:
				return "Stone Brick Stairs";
				break;
			case 110:
				return "Mycelium";
				break;
			case 111:
				return "Lily Pad";
				break;
			case 112:
				return "Nether Brick";
				break;
			case 113:
				return "Nether Brick Fence";
				break;
			case 114:
				return "Nether Brick Stairs";
				break;
			case 115:
				return "Nether Wart";
				break;
			case 116:
				return "Enchantment Table";
				break;
			case 117:
				return "Brewing Stand (Block)";
				break;
			case 118:
				return "Cauldron (Block)";
				break;
			case 119:
				return "End Portal";
				break;
			case 120:
				return "End Portal Frame";
				break;
			case 121:
				return "End Stone";
				break;
			case 122:
				return "Dragon Egg";
				break;
			case 256:
				return "Iron Shovel ".round(($itemDamage/251)*100, 1)."% damaged";
				break;
			case 257:
				return "Iron Pickaxe ".round(($itemDamage/251)*100, 1)."% damaged";
				break;
			case 258:
				return "Iron Axe ".round(($itemDamage/251)*100, 1)."% damaged";
				break;
			case 259:
				return "Flint and Steel";
				break;
			case 260:
				return "Apple";
				break;
			case 261:
				return "Bow ".round(($itemDamage/385)*100, 1)."% damaged";
				break;
			case 262:
				return "Arrow";
				break;
			case 263:
				switch ($itemDamage)
				{
					case 1:
						return "Charcoal";
						break;
					default:
						return "Coal";
						break;
				}
				break;
			case 264:
				return "Diamond";
				break;
			case 265:
				return "Iron Ingot";
				break;
			case 266:
				return "Gold Ingot";
				break;
			case 267:
				return "Iron Sword ".round(($itemDamage/251)*100, 1)."% damaged";
				break;
			case 268:
				return "Wooden Sword ".round(($itemDamage/60)*100, 1)."% damaged";
				break;
			case 269:
				return "Wooden Shovel ".round(($itemDamage/60)*100, 1)."% damaged";
				break;
			case 270:
				return "Wooden Pickaxe ".round(($itemDamage/60)*100, 1)."% damaged";
				break;
			case 271:
				return "Wooden Axe ".round(($itemDamage/60)*100, 1)."% damaged";
				break;
			case 272:
				return "Stone Sword ".round(($itemDamage/132)*100, 1)."% damaged";
				break;
			case 273:
				return "Stone Shovel ".round(($itemDamage/132)*100, 1)."% damaged";
				break;
			case 274:
				return "Stone Pickaxe ".round(($itemDamage/132)*100, 1)."% damaged";
				break;
			case 275:
				return "Stone Axe ".round(($itemDamage/132)*100, 1)."% damaged";
				break;
			case 276:
				return "Diamond Sword ".round(($itemDamage/1562)*100, 1)."% damaged";
				break;
			case 277:
				return "Diamond Shovel ".round(($itemDamage/1562)*100, 1)."% damaged";
				break;
			case 278:
				return "Diamond Pickaxe ".round(($itemDamage/1562)*100, 1)."% damaged";
				break;
			case 279:
				return "Diamond Axe ".round(($itemDamage/1562)*100, 1)."% damaged";
				break;
			case 280:
				return "Stick";
				break;
			case 281:
				return "Bowl";
				break;
			case 282:
				return "Mushroom Soup";
				break;
			case 283:
				return "Gold Sword ".round(($itemDamage/33)*100, 1)."% damaged";
				break;
			case 284:
				return "Gold Shovel ".round(($itemDamage/33)*100, 1)."% damaged";
				break;
			case 285:
				return "Gold Pickaxe ".round(($itemDamage/33)*100, 1)."% damaged";
				break;
			case 286:
				return "Gold Axe ".round(($itemDamage/33)*100, 1)."% damaged";
				break;
			case 287:
				return "String";
				break;
			case 288:
				return "Feather";
				break;
			case 289:
				return "Sulphur";
				break;
			case 290:
				return "Wooden Hoe ".round(($itemDamage/60)*100, 1)."% damaged";
				break;
			case 291:
				return "Stone Hoe ".round(($itemDamage/132)*100, 1)."% damaged";
				break;
			case 292:
				return "Iron Hoe ".round(($itemDamage/251)*100, 1)."% damaged";
				break;
			case 293:
				return "Diamond Hoe ".round(($itemDamage/1562)*100, 1)."% damaged";
				break;
			case 294:
				return "Gold Hoe ".round(($itemDamage/33)*100, 1)."% damaged";
				break;
			case 295:
				return "Seeds";
				break;
			case 296:
				return "Wheat";
				break;
			case 297:
				return "Bread";
				break;
			case 298:
				return "Leather Helmet ".round(($itemDamage/34)*100, 1)."% damaged";
				break;
			case 299:
				return "Leather Chestplate ".round(($itemDamage/49)*100, 1)."% damaged";
				break;
			case 300:
				return "Leather Leggings ".round(($itemDamage/46)*100, 1)."% damaged";
				break;
			case 301:
				return "Leather Boots ".round(($itemDamage/40)*100, 1)."% damaged";
				break;
			case 302:
				return "Chain Mail Helmet ".round(($itemDamage/67)*100, 1)."% damaged";
				break;
			case 303:
				return "Chain Mail Chestplate ".round(($itemDamage/96)*100, 1)."% damaged";
				break;
			case 304:
				return "Chain Mail Leggings ".round(($itemDamage/92)*100, 1)."% damaged";
				break;
			case 305:
				return "Chain Mail Boots ".round(($itemDamage/79)*100, 1)."% damaged";
				break;
			case 306:
				return "Iron Helmet ".round(($itemDamage/136)*100, 1)."% damaged";
				break;
			case 307:
				return "Iron Chestplate ".round(($itemDamage/192)*100, 1)."% damaged";
				break;
			case 308:
				return "Iron Leggings ".round(($itemDamage/184)*100, 1)."% damaged";
				break;
			case 309:
				return "Iron Boots ".round(($itemDamage/160)*100, 1)."% damaged";
				break;
			case 310:
				return "Diamond Helmet ".round(($itemDamage/272)*100, 1)."% damaged";
				break;
			case 311:
				return "Diamond Chestplate ".round(($itemDamage/384)*100, 1)."% damaged";
				break;
			case 312:
				return "Diamond Leggings ".round(($itemDamage/368)*100, 1)."% damaged";
				break;
			case 313:
				return "Diamond Boots ".round(($itemDamage/320)*100, 1)."% damaged";
				break;
			case 314:
				return "Gold Helmet ".round(($itemDamage/68)*100, 1)."% damaged";
				break;
			case 315:
				return "Gold Chestplate ".round(($itemDamage/96)*100, 1)."% damaged";
				break;
			case 316:
				return "Gold Leggings ".round(($itemDamage/92)*100, 1)."% damaged";
				break;
			case 317:
				return "Gold Boots ".round(($itemDamage/80)*100, 1)."% damaged";
				break;
			case 318:
				return "Flint";
				break;
			case 319:
				return "Raw Porkchop";
				break;
			case 320:
				return "Cooked Porkchop";
				break;
			case 321:
				return "Painting";
				break;
			case 322:
				return "Golden Apple";
				break;
			case 323:
				return "Sign";
				break;
			case 324:
				return "Wooden Door";
				break;
			case 325:
				return "Bucket";
				break;
			case 326:
				return "Water Bucket";
				break;
			case 327:
				return "Lava Bucket";
				break;
			case 328:
				return "Minecart";
				break;
			case 329:
				return "Saddle";
				break;
			case 330:
				return "Iron Door";
				break;
			case 331:
				return "Redstone";
				break;
			case 332:
				return "Snowball";
				break;
			case 333:
				return "Boat";
				break;
			case 334:
				return "Leather";
				break;
			case 335:
				return "Milk Bucket";
				break;
			case 336:
				return "Clay Brick";
				break;
			case 337:
				return "Clay Balls";
				break;
			case 338:
				return "Sugar Cane";
				break;
			case 339:
				return "Paper";
				break;
			case 340:
				return "Book";
				break;
			case 341:
				return "Slimeball";
				break;
			case 342:
				return "Storage Minecart";
				break;
			case 343:
				return "Powered Minecart";
				break;
			case 344:
				return "Egg";
				break;
			case 345:
				return "Compass";
				break;
			case 346:
				return "Fishing Rod";
				break;
			case 347:
				return "Clock";
				break;
			case 348:
				return "Glowstone Dust";
				break;
			case 349:
				return "Raw Fish";
				break;
			case 350:
				return "Cooked Fish";
				break;
			case 351:
				switch ($itemDamage)
				{
					case 1:
						return "Rose Red";
						break;
					case 2:
						return "Cactus Green";
						break;
					case 3:
						return "Cocoa Beans";
						break;
					case 4:
						return "Lapis Lazuli";
						break;
					case 5:
						return "Purple Dye";
						break;
					case 6:
						return "Cyan Dye";
						break;
					case 7:
						return "Light Grey Dye";
						break;
					case 8:
						return "Grey Dye";
						break;
					case 9:
						return "Pink Dye";
						break;
					case 10:
						return "Lime Dye";
						break;
					case 11:
						return "Dandelion Yellow";
						break;
					case 12:
						return "Light Blue Dye";
						break;
					case 13:
						return "Magenta Dye";
						break;
					case 14:
						return "Orange Dye";
						break;
					case 15:
						return "Bone Meal";
						break;
					default:
						return "Ink Sack";
						break;
				}
				break;
			case 352:
				return "Bone";
				break;
			case 353:
				return "Sugar";
				break;
			case 354:
				return "Cake";
				break;
			case 355:
				return "Bed";
				break;
			case 356:
				return "Redstone Repeater";
				break;
			case 357:
				return "Cookie";
				break;
			case 358:
				return "Map ".$itemDamage;
				break;
			case 359:
				return "Shears";
				break;
			case 360:
				return "Melon Slice";
				break;
			case 361:
				return "Pumpkin Seeds";
				break;
			case 362:
				return "Melon Seeds";
				break;
			case 363:
				return "Raw Beef";
				break;
			case 364:
				return "Steak";
				break;
			case 365:
				return "Raw Chicken";
				break;
			case 366:
				return "Cooked Chicken";
				break;
			case 367:
				return "Rotten Flesh";
				break;
			case 368:
				return "Ender Pearl";
				break;
			case 369:
				return "Blaze Rod";
				break;
			case 370:
				return "Ghast Tear";
				break;
			case 371:
				return "Gold Nugget";
				break;
			case 372:
				return "Nether Wart";
				break;
			case 373:
				switch ($itemDamage)
				{
					case 0:
						return "Water Bottle";
						break;
					case 16:
						return "Awkward Potion";
						break;
					case 32:
						return "Thick Potion";
						break;
					case 64:
						return "Mundane Potion";
						break;
					case 8193:
						return "Regeneration Potion (0:45)";
						break;
					case 8194:
						return "Swiftness Potion (3:00)";
						break;
					case 8195:
						return "Fire Resistance Potion (3:00)";
						break;
					case 8196:
						return "Poison Potion (0:45)";
						break;
					case 8197:
						return "Healing Potion";
						break;
					case 8200:
						return "Weakness Potion (1:30)";
						break;
					case 8201:
						return "Strength Potion (3:00)";
						break;
					case 8202:
						return "Slowness Potion (1:30)";
						break;
					case 8204:
						return "Harming Potion";
						break;
					case 8225:
						return "Regeneration Potion II (0:22)";
						break;
					case 8226:
						return "Swiftness Potion II (1:30)";
						break;
					case 8228:
						return "Poison Potion II (0:22)";
						break;
					case 8229:
						return "Healing Potion II";
						break;
					case 8233:
						return "Strength Potion II (1:30)";
						break;
					case 8236:
						return "Harming Potion II";
						break;
					case 8257:
						return "Regeneration Potion (2:00)";
						break;
					case 8258:
						return "Swiftness Potion (8:00)";
						break;
					case 8259:
						return "Fire Resistance Potion (8:00)";
						break;
					case 8260:
						return "Poison Potion (2:00)";
						break;
					case 8264:
						return "Weakness Potion (4:00)";
						break;
					case 8265:
						return "Strength Potion (8:00)";
						break;
					case 8266:
						return "Slowness Potion (4:00)";
						break;
					case 16378:
						return "Fire Resistance Splash (2:15)";
						break;
					case 16385:
						return "Regeneration Splash (0:33)";
						break;
					case 16386:
						return "Swiftness Splash (2:15)";
						break;
					case 16388:
						return "Poison Splash (0:33)";
						break;
					case 16389:
						return "Healing Splash";
						break;
					case 16392:
						return "Weakness Splash (1:07)";
						break;
					case 16393:
						return "Strength Splash (2:15)";
						break;
					case 16394:
						return "Slowness Splash (2:15)";
						break;
					case 16396:
						return "Harming Splash";
						break;
					case 16418:
						return "Swiftness Splash II (1:07)";
						break;
					case 16420:
						return "Poison Splash II (0:16)";
						break;
					case 16421:
						return "Healing Splash II";
						break;
					case 16425:
						return "Strength Splash II (1:07)";
						break;
					case 16428:
						return "Harming Splash II";
						break;
					case 16449:
						return "Regeneration Splash (1:30)";
						break;
					case 16450:
						return "Swiftness Splash (6:00)";
						break;
					case 16451:
						return "Fire Resistance Splash (6:00)";
						break;
					case 16452:
						return "Poison Splash (1:30)";
						break;
					case 16456:
						return "Weakness Splash (3:00)";
						break;
					case 16457:
						return "Strength Splash (6:00)";
						break;
					case 16458:
						return "Slowness Splash (3:00)";
						break;
					case 16471:
						return "Regeneration Splash II (0:16)";
						break;
					default:
						return "Clear Potion";
						break;
				}
				break;
			case 374:
				return "Glass Bottle";
				break;
			case 375:
				return "Spider Eye";
				break;
			case 376:
				return "Fermented Spider Eye";
				break;
			case 377:
				return "Blaze Powder";
				break;
			case 378:
				return "Magma Cream";
				break;
			case 379:
				return "Brewing Stand";
				break;
			case 380:
				return "Cauldron";
				break;
			case 381:
				return "Eye of Ender";
				break;
			case 382:
				return "Glistering Melon (Slice)";
				break;
			case 2256:
				return "Music Disc (13)";
				break;
			case 2257:
				return "Music Disc (Cat)";
				break;
			case 2258:
				return "Music Disc (Blocks)";
				break;
			case 2259:
				return "Music Disc (Chirp)";
				break;
			case 2260:
				return "Music Disc (Far)";
				break;
			case 2261:
				return "Music Disc (Mall)";
				break;
			case 2262:
				return "Music Disc (Mellohi)";
				break;
			case 2263:
				return "Music Disc (Stal)";
				break;
			case 2264:
				return "Music Disc (Strad)";
				break;
			case 2265:
				return "Music Disc (Ward)";
				break;
			case 2266:
				return "Music Disc (11)";
				break;
			default:
				return "air";
				break;		
		}
	}
}
 
class Item_model extends Base_module_record {
 
}
