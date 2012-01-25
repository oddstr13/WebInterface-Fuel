<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<?php if (!empty($is_blog)) : ?>
	<title><?php echo $CI->fuel_blog->page_title($page_title, ' : ', 'right')?></title>
	<?php else : ?>
	<title><?php echo fuel_var('page_title', '')?></title>
	<?php endif ?>
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
	$MyUsername = $CI->session->userdata('username'); 
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
    <td align="left"><?php echo fuel_var('Currency Prefix'); ?><?php echo $MyData->money; ?></td>
  </tr>
  <tr>
    <td align="left">Mail</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td><a href="/auth/logout">[Logout]</a></td>
    <td align="left"><a href="/change_email">[Change Email]</a></td>
    <td align="left"><a href="/unregister">[Unregister]</a></td>
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
        	
			