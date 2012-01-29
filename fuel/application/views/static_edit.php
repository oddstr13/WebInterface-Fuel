<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
	if ($CI->session->userdata('is_admin') != 1)
	{
		redirect('/static');
	}
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

	$items = $CI->items_model->list_items();
?>

	<h2 align="center">Static Prices Editor</h2>
    
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
        <p align="center">Enter the values you want the server to buy / sell various items for. If you leave a field blank, that item will not be able to be bought / sold.</p>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Item</th>
                    <th align="center">Market Price</th>
					<th align="center">Buy Price (Each)</th>
                    <th align="center">Sell Price (Each)</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($items as $item) {
						$enchantments = $CI->enchantments_model->get_item_enchantments($item['id']);
						$base = $CI->items_model->isTrueDamage($item['name']);
						$market = $CI->market_model->get_market_price($item['id']);
						$static = $CI->static_model->get_static_by_item($item['id']);
						if (isset($static)){
							if (isset($static[0]->buy)){
								$buy = $static[0]->buy;
							}else{
								$buy = "";	
							}
							if (isset($static[0]->sell)){
								$sell = $static[0]->sell;
							}else{
								$sell = "";	
							}
						}else{
							$buy = "";
							$sell = "";	
						}
						if (empty($market)){
							$mark = 0;
						}else{
							$mark = $market[0]->price;	
						}
						
?>
						<tr>
							<td align="center">
               	         <img src="<?php echo $CI->items_model->get_item_image($item['name'], $item['damage'], $base); ?>"  /><br/>
							<?php 
								echo $CI->items_model->getItemName($item['name'], $item['damage']); 
								foreach ($enchantments as $ench)
								{
									echo "<br/>";
									echo $CI->items_model->getEnchName($ench->name)." - ".$CI->items_model->numberToRoman($ench->level);		
								}
						?>
                        	</td>
							<td align="center"><?php echo $mark; ?></td>
                 	       <td align="center"><form action='trade/set_buy_static' method='post'><input type='text' size="6" name='Value' value='<?php echo $buy; ?>' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $item['id']; ?>' /><input type='submit' value='Set Buy Price' class='button' /></form></td>
                           <td align="center"><form action='trade/set_sell_static' method='post'><input type='text' size="6" name='Value' value='<?php echo $sell; ?>' onKeyPress='return numbersonly(this, event)' class='input'><input type='hidden' name='ID' value='<?php echo $item['id']; ?>' /><input type='submit' value='Set Sell Price' class='button' /></form></td>
						</tr>
<?php 
						
				}
?>
			</tbody>
		</table>
	</div>