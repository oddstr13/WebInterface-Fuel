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

	<h2 align="center">Current Auctions</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Item</th>
					<th align="center">Seller</th>
					<th align="center">Expires</th>
					<th align="center">Quantity</th>
					<th align="center">Price (Each)</th>
					<th align="center">Price (Total)</th>
					<th align="center">% of Market Price</th>
                    <th align="center">Buy</th>
					<th align="center">Cancel</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($auctions as $auction) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($auction->item->id);
					$base = $CI->items_model->isTrueDamage($auction->name);
?>
					<tr>
						<td align="center">
                        <img src="<?php echo $CI->items_model->get_item_image($auction->item->name, $auction->item->damage, $base); ?>"  /><br/>
						<?php 
							echo $CI->items_model->getItemName($auction->item->name, $auction->item->damage); 
							foreach ($enchantments as $ench){
								echo "<br/>";
								echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);		
							}
						?>
                        </td>
						<td align="center"><img width="32px" src="http://minotar.net/avatar/<?php echo $auction->seller; ?>" /><br/><?php echo $auction->seller; ?></td>
						<td align="center"><?php echo $auction->started; ?></td>
						<td align="center"><?php echo $auction->quantity; ?></td>
						<td align="center"><?php echo $auction->price; ?></td>
						<td align="center"><?php echo ($auction->price * $auction->quantity); ?></td>
						<td align="center"><?php echo "market price"; ?></td>
                        <td align="center"><form action='trade/buy_item' method='post'><input type='text' size="6" name='Quantity' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $auction->id; ?>' /><input type='submit' value='Buy' class='button' /></form></td>
						<td align="center"><?php echo "cancel"; ?></td>
					</tr>
<?php 
				} 
?>
			</tbody>
		</table>
	</div>