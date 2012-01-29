<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
	if (isset($CI->session->userdata['error_msg']))
	{
		$error = $CI->session->userdata['error_msg'];
	}
	if (isset($CI->session->userdata['other_msg']))
	{
		$msg = $CI->session->userdata['other_msg'];
	}
?>
<?php 
	$CI->load->model('static_model');
	$CI->load->model('items_model');
	$CI->load->model('player_items_model');
	$CI->load->model('market_model');
	$CI->load->model('enchantments_model');

    $auctions = $CI->static_model->list_items();
	$items = $CI->items_model->list_items();
?>

	<h2 align="center">Static Prices</h2>
    
    	<?php 
			if (isset($error))
			{
				?><p style="color:red" align="center"><?php
				echo $error;
				$CI->session->unset_error();
				?></p><?php
			}
			if (isset($msg))
			{
				?><p style="color:green" align="center"><?php
				echo $msg;
				$CI->session->unset_msg();
				?></p><?php
			}
			if ($CI->session->userdata('is_admin') == 1){
				?>
                	<h3 align="center"><a href="/static_edit">Edit Static Prices</a></h3><br/>
                <?php	
			}
			
    	?>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Item</th>
                    <th align="center">Market Price</th>
                    <th align="center">My Quantity</th>
					<th align="center">Buy Price (Each)</th>
					<th align="center">Buy</th>
                    <th align="center">Sell Price (Each)</th>
                    <th align="center">Sell</th>
					<th align="center">Cancel</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($auctions as $auction) {
						$enchantments = $CI->enchantments_model->get_item_enchantments($auction['item_id']);
						$base = $CI->items_model->isTrueDamage($auction['name']);
						$market = $CI->market_model->get_market_price($auction['item_id']);
						$player_item = $CI->player_items_model->get_item_match($auction['item_id'], $MyUsername);
						if (empty($player_item)){
							$my_quant = 0;
						}else{
							$my_quant = $player_item[0]->quantity;	
						}
						if (empty($market)){
							$mark = 0;
						}else{
							$mark = $market[0]->price;	
						}
						if (($auction['buy'] != "")||($auction['sell'] != "")){; 
?>
						<tr>
							<td align="center">
               	         <img src="<?php echo $CI->items_model->get_item_image($auction['name'], $auction['damage'], $base); ?>"  /><br/>
							<?php 
								echo $CI->items_model->getItemName($auction['name'], $auction['damage']); 
								foreach ($enchantments as $ench)
								{
									echo "<br/>";
									echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);		
								}
						?>
                        	</td>
							<td align="center"><?php echo $mark; ?></td>
                            <td align="center"><?php echo $my_quant; ?></td>
                            <td align="center"><?php if (isset($auction['buy'])) {echo $auction['buy'];}else{echo "N/A";} ?></td>
                 	       <td align="center"><?php if (!isset($auction['buy'])) {echo "N/A";}else{ ?><form action='trade/buy_static' method='post'><input type='text' size="6" name='Quantity' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $auction['id']; ?>' /><input type='submit' value='Buy' class='button' /></form><?php } ?></td>
                           <td align="center"><?php if (isset($auction['sell'])) {echo $auction['sell'];}else{echo "N/A";} ?></td>
                           <td align="center"><?php if (!isset($auction['sell'])) {echo "N/A";}else{?><form action='trade/sell_static' method='post'><input type='text' size="6" name='Quantity' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $auction['id']; ?>' /><input type='submit' value='Sell' class='button' /></form><?php } ?></td>
							<td align="center"><?php echo "cancel"; ?></td>
						</tr>
<?php 
						
				}}
?>
			</tbody>
		</table>
	</div>