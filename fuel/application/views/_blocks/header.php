<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title><?php echo fuel_var('page_title', '')?></title>
	<meta charset="UTF-8" />
	<meta name="ROBOTS" content="ALL" />
	<meta name="MSSmartTagsPreventParsing" content="true" />

	<meta name="keywords" content="<?php echo fuel_var('meta_keywords')?>" />
	<meta name="description" content="<?php echo fuel_var('meta_description')?>" />

	<?php echo css('main'); ?>
	<?php echo css($css); ?>
	
	<?php echo js('jquery, main'); ?>
	<?php echo js($js); ?>
	
	<?php 
		$CI->load->library('tank_auth');
		$CI->lang->load('tank_auth'); 
		$CI->load->model('mail_model');
		$CI->load->model('fee_model');
		$CI->load->model('player_items_model');
		$CI->load->model('players_model');
		$CI->load->model('iconomy_model');
	?>
	<base href="<?php echo site_url()?>" />
	<style type="text/css" title="currentStyle">
			@import "/fuel/modules/fuel/assets/css/table_jui.css";
			@import "/fuel/modules/fuel/assets/css/start/jquery-ui-1.8.16.custom.css";
	</style>
	<script type="text/javascript" language="javascript" src="/fuel/modules/fuel/assets/js/jquery/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="/fuel/modules/fuel/assets/js/jquery/plugins/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8">
      $(document).ready(function() {
				oTable = $('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers"
				});
			} );
    </script>
	
</head>
<?php 
	$lastFeeCheck = $CI->fee_model->get_last_fee();
	if (empty($lastFeeCheck)){
		$CI->fee_model->new_fee();
	}else{
		$checks = $this->config->item('fee_checks_per_day');
		$amount = $this->config->item('fee_per_item_per_day');
		$useIcon = $this->config->item('iconomy_table_name');
		$iconTable = "";
		if ($useIcon){
			$iconTable = $this->config->item('iconomy_table_name');
		}
		$amount_per_check = $amount/$checks;
		$time_between_checks = 86400/$checks;
		$players_info = $CI->players_model->list_items();
		
		if ($lastFeeCheck[0]->time + $time_between_checks < time())
		{
			foreach ($players_info as $player) {
				$count = 0;
				$items = $CI->player_items_model->get_player_items($player['id']);
				$mail = $CI->mail_model->get_player_mail($player['id']);
				foreach ($mail as $m) {
					$count += $m->quantity;
				}
				foreach ($items as $i) {
					$count += $i->quantity;
				}
				$total_cost = $count * $amount_per_check;
				if ($useIcon){
					$iconInfo = $CI->iconomy_model->get_money($player['username']);
					$CI->iconomy_model->set_money($player['username'], $iconInfo->balance - $total_cost);
				}else{		
					$CI->players_model->set_money($player['id'], $player['money'] - $total_cost);	
				}
			}
			$CI->fee_model->new_fee();
		}
	}
	
	
	
	
	
	
	$MyUsername = $CI->session->userdata('username'); 
	if ($useIcon){
		$MyIcon = $CI->iconomy_model->get_money($MyUsername);	
	}
	$MyData = $CI->users->get_user_by_username($MyUsername);
?>

<body class="<?php echo fuel_var('body_class', 'Body Class');?>">
<div id="container">
	<div id="container_inner">
<div id="userInfoBox">
        	<h2>User Info</h2>
            <?php if(!$CI->tank_auth->is_logged_in()){ ?>
    <p><a href="/login">[Login]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/register">[Register]</a></p>
    <?php } else { ?>
       	<table width="100%" border="0">
  <tr>
    <td rowspan="3"><img width="64px" src="http://minotar.net/avatar/<?php echo $MyData->username; ?>"/></td>
    <td align="left">Minecraft IGN</td>
    <td align="left"><?php echo $MyData->username; ?> </td>
  </tr>
  <tr>
    <td align="left">Money</td>
    <td align="left"><?php echo fuel_var('Currency Prefix'); ?><?php 
		if($useIcon){
			echo $MyIcon->balance;
		}else{
			echo $MyData->money; 
		}
	
	?></td>
  </tr>
  <tr>
    <td align="left">Mail</td>
    <td align="left"><?php 
		$player_mail = $CI->mail_model->get_player_mail($MyData->id);
		echo count($player_mail);	
	?></td>
  </tr>
  <tr>
    <td><a href="/auth/logout">[Logout]</a></td>
    <td align="left"><a href="/change_email">[Change Email]</a></td>
  </tr>
  </table>

            
    <?php } ?>
  </div>
		<div id="header">	
			<h1><?php echo fuel_var('Site name'); ?></h1>
		</div>
		<div id="navigation" class="menu">
			<?php echo fuel_nav(); ?>
		</div>
		<div id="main">
        	
			