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
	$CI->load->model('auctions_model');
	$CI->load->model('items_model');
	$CI->load->model('player_items_model');
	$CI->load->model('market_model');
	$CI->load->model('enchantments_model');

    $auctions = $CI->auctions_model->find_all();
?>

	<h2 align="center">Current Auctions</h2>
    
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
    	?>
    
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
                    <?php if ($this->session->userdata('can_buy_auction') == 1){ ?>
                    <th align="center">Buy</th>
                    <?php } if ($this->session->userdata('is_admin') == 1){?>
					<th align="center">Cancel</th>
                    <?php } ?>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($auctions as $auction) {
					if (time() < ($auction->started + (86400 * 10))){
						$enchantments = $CI->enchantments_model->get_item_enchantments($auction->item->id);
						$base = $CI->items_model->isTrueDamage($auction->name);
						$market = $CI->market_model->get_market_price($auction->item_id);
						if (empty($market)){
							$mark = 0;
						}else{
							$mark = $market[0]->price;	
						}
?>
						<tr>
							<td align="center">
               	         <img src="<?php echo $CI->items_model->get_item_image($auction->item->name, $auction->item->damage, $base); ?>"  /><br/>
							<?php 
								echo $CI->items_model->getItemName($auction->item->name, $auction->item->damage); 
								foreach ($enchantments as $ench)
								{
									echo "<br/>";
									echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);		
								}
						?>
                        	</td>
							<td align="center"><img width="32px" src="http://minotar.net/avatar/<?php echo $auction->seller; ?>" /><br/><?php echo $auction->seller; ?></td>
							<td align="center"><?php echo date('j/m/Y H:i:s', $auction->started + (10 * 86400)); ?></td>
							<td align="center"><?php echo $auction->quantity; ?></td>
							<td align="center"><?php echo $auction->price; ?></td>
							<td align="center"><?php echo ($auction->price * $auction->quantity); ?></td>
							<td align="center"><?php if ($mark > 0){echo round(($auction->price/$mark)*100, 2);}else{echo 0;} ?></td>
                            <?php if ($this->session->userdata('can_buy_auction') == 1){ ?>
                 	       <td align="center"><form action='trade/buy_item' method='post'><input type='text' size="6" name='Quantity' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $auction->id; ?>' /><input type='submit' value='Buy' class='button' /></form></td>
                           <?php } if ($this->session->userdata('is_admin') == 1){ ?>
							<td align="center"><?php echo "cancel"; ?></td>
                            <?php } ?>
						</tr>
<?php 
					}else{
						$item_info= $CI->player_items_model->get_item_match($auction->item_id, $auction->seller);
						if (empty($item_info)){
							$CI->player_items_model->new_player_item($auction->item_id, $auction->seller, $auction->quantity);	
						}else{
							$CI->player_items_model->set_quantity($item_info[0]->id, $auction->quantity+$item_info[0]->quantity);
						}
						$CI->auctions_model->delete_auction($auction->id);
					}
				}
?>
			</tbody>
		</table>
	</div>