<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	if ($CI->tank_auth->is_logged_in()){
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>
<?php 
	$CI->load->model('auctions_model');
	$CI->load->model('items_model');
	$CI->load->model('player_items_model');
	$CI->load->model('enchantments_model');
	
	$items = $CI->player_items_model->get_player_items($MyData->username);
    $auctions = $CI->auctions_model->get_player_auctions($MyData->username);
?>
<div id="newAuction">
    	<h2 align="center">New Auction</h2>
    	<form action="trade/new_auction" method="post" name="auction">
			<table width="100%" cellpadding="5" style="text-align:left;">
			<tr>
				<td width="50%" height="53"><label>Item</label></td><td width="50%"><select name="Item" class="select">
<?php
				foreach ($items as $item) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($item->item_id);
					$base = $CI->items_model->isTrueDamage($item->name);
?>
					<option value="<?php echo $item->id ?>">
<?php 
					echo $CI->items_model->getItemName($item->name, $item->damage); 
					echo "(x".$item->quantity.")";
                    foreach ($enchantments as $ench)
					{
						echo " (".$CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level).")";	
					}
?>
					</option>
<?php 
				}
?>				
				</select></td>
				<tr><td colspan="2" style="text-align:center;">
				</td></tr>
				<tr><td height="50"><label>Quantity</label></td><td><input name="Quantity" type="text" class="input" size="10" /></td></tr>
				<tr><td height="49"><label>Price (Per Item)</label></td><td><input name="Price" type="text" class="input" size="10" /></td></tr>
				<tr><td colspan="2" style="text-align:center;"><input name="Submit" type="submit" class="button" /></td></tr>
				</table>
  </form>
    </div>
	<h2 align="center">&nbsp;</h2>
	<h2 align="center">My Auctions</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Item</th>
					<th align="center">Expires</th>
					<th align="center">Quantity</th>
					<th align="center">Price (Each)</th>
					<th align="center">Price (Total)</th>
					<th align="center">% of Market Price</th>
					<th align="center">Cancel</th>
			  </tr>
			</thead>
			<tbody>
<?php
				foreach ($auctions as $auction) {
					$enchantments = $CI->enchantments_model->get_item_enchantments($auction->item_id);
					$base = $CI->items_model->isTrueDamage($auction->name);
?>
					<tr>
						<td align="center">
                        <img src="<?php echo $CI->items_model->get_item_image($auction->name, $auction->damage, $base); ?>"  /><br/>
						<?php 
							echo $CI->items_model->getItemName($auction->name, $auction->damage); 
							foreach ($enchantments as $ench){
								echo "<br/>";
								echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);	
							}
						?>
                        </td>
						<td align="center"><?php echo $auction->started; ?></td>
						<td align="center"><?php echo $auction->quantity; ?></td>
						<td align="center"><?php echo $auction->price; ?></td>
						<td align="center"><?php echo ($auction->price * $auction->quantity); ?></td>
						<td align="center"><?php echo "market price"; ?></td>
						<td align="center"><?php echo "cancel"; ?></td>
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