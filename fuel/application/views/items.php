<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth');
	if ($CI->tank_auth->is_logged_in()){
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>
<?php 
	$CI->load->model('items_model');
	$CI->load->model('market_model');
	$CI->load->model('player_items_model');
	$CI->load->model('enchantments_model');
	
    $items = $CI->player_items_model->get_player_items($MyData->username);
	//echo "<pre>";
	//print_r($items);
	//echo "</pre>";
?>
	<h2 align="center">My Items</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Item</th>
					<th align="center">Quantity</th>
					<th align="center">Market Price (Each)</th>
					<th align="center">Market Price (Total)</th>
					<th align="center">Mail it</th>
			  </tr>
			</thead>
			<tbody>
<?php
				foreach ($items as $item) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($item->item_id);
					$base = $CI->items_model->isTrueDamage($item->name);
					$market = $CI->market_model->get_market_price($item->item_id);
					if (empty($market)){
						$mark = 0;
					}else{
						$mark = $market[0]->price;	
					}
?>
					<tr>
						<td align="center">
                        <img src="<?php echo $CI->items_model->get_item_image($item->name, $item->damage, $base); ?>"  /><br/>
						<?php 
							echo $CI->items_model->getItemName($item->name, $item->damage);

							foreach ($enchantments as $ench){
								echo "<br/>";
								echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);	
							}
						?>
                        </td>
						<td align="center"><?php echo $item->quantity; ?></td>
                        <td align="center"><?php echo round($mark, 2); ?></td>
						<td align="center"><?php echo round($mark, 2); ?></td>
						<td align="center"><?php echo "Mail it"; ?></td>
			  </tr>
<?php 
				} 
?>
			</tbody>
		</table>
</div>
<?php
	} else {
		redirect('/login');	
	}
?>