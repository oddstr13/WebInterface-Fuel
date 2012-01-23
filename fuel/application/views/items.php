<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>
<?php 
	$CI->load->model('items_model');
	$CI->load->model('enchantments_model');
	
    $items = $CI->items_model->get_player_items($MyData->username);
?>
	<h2>My Items</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th>Item</th>
					<th>Quantity</th>
					<th>Market Price (Each)</th>
					<th>Market Price (Total)</th>
					<th>Mail it</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($items as $item) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($item->id);
					$base = $CI->items_model->isTrueDamage($item->name);
?>
					<tr>
						<td>
                        <img src="<?php echo $CI->items_model->get_item_image($item->name, $item->damage, $base); ?>"  />
						<?php 
							echo $item->name;

							foreach ($enchantments as $ench){
								echo "<br/>";
								echo $ench->name." ".$ench->level;	
							}
						?>
                        </td>
						<td><?php echo $item->quantity; ?></td>
                        <td><?php echo fuel_var('Currency Prefix'); ?><?php echo "market price (each)"; ?></td>
						<td><?php echo fuel_var('Currency Prefix'); ?><?php echo "market price (total)"; ?></td>
						<td><?php echo "Mail it"; ?></td>
					</tr>
<?php 
				} 
?>
			</tbody>
		</table>
	</div>