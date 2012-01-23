<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>
<?php 
	$CI->load->model('auctions_model');
	$CI->load->model('items_model');
	$CI->load->model('enchantments_model');
	
    $auctions = $CI->auctions_model->find_all();
?>

	<h2>Current Auctions</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th>Item</th>
					<th>Seller</th>
					<th>Expires</th>
					<th>Quantity</th>
					<th>Price (Each)</th>
					<th>Price (Total)</th>
					<th>% of Market Price</th>
					<th>Cancel</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($auctions as $auction) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($auction->item->id);
					$base = $CI->items_model->isTrueDamage($auction->name);
?>
					<tr>
						<td>
                        <img src="<?php echo $CI->items_model->get_item_image($auction->item->name, $auction->item->damage, $base); ?>"  />
						<?php 
							echo $auction->item->name; 
							foreach ($enchantments as $ench){
								echo "<br/>";
								echo $ench->name." ".$ench->level;	
							}
						?>
                        </td>
						<td><?php echo $auction->item->owner; ?></td>
						<td><?php echo $auction->started; ?></td>
						<td><?php echo $auction->quantity; ?></td>
						<td><?php echo fuel_var('Currency Prefix'); ?><?php echo $auction->price; ?></td>
						<td><?php echo fuel_var('Currency Prefix'); ?><?php echo ($auction->price * $auction->quantity); ?></td>
						<td><?php echo "market price"; ?></td>
						<td><?php echo "cancel"; ?></td>
					</tr>
<?php 
				} 
?>
			</tbody>
		</table>
	</div>