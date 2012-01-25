<?php 
	$CI->load->library('tank_auth');
	$CI->lang->load('tank_auth'); 
	$MyUsername = $CI->session->userdata('username'); 
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>
<?php 
	$CI->load->model('players_model');
	
    $players = $CI->players_model->find_active();
?>

	<h2>Players</h2>
	<div class="demo_jui">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
			<thead>
				<tr>
					<th align="center">Image</th>
					<th align="center">Name</th>
					<th align="center">Items Sold</th>
					<th align="center">Items Bought</th>
					<th align="center">Money Earnt</th>
					<th align="center">Money Spent</th>
					<th align="center">Total Profit</th>
				</tr>
			</thead>
			<tbody>
<?php
				foreach ($players as $player) {
?>
					<tr>
						<td align="center">
                        <img width="32px" src="http://minotar.net/avatar/<?php echo $player->username; ?>"  />
                        </td>
						<td align="center"><?php echo $player->username; ?></td>
						<td align="center"><?php echo $player->sold; ?></td>
						<td align="center"><?php echo $player->bought; ?></td>
						<td align="center"><?php echo $player->earnt; ?></td>
						<td align="center"><?php echo $player->spent; ?></td>
						<td align="center"><?php echo $player->earnt - $player->spent; ?></td>
					</tr>
<?php 
				} 
?>
			</tbody>
		</table>
	</div>