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
	
	<?php if (!empty($is_blog)) : ?>
	<?php echo $CI->fuel_blog->header()?>
	<?php endif; ?>
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


<body class="<?php echo fuel_var('body_class', 'Body Class');?>">
<div id="container">
	<div id="container_inner">
		<div id="header">	
			<h1>Web Auction</h1>
		
		</div>
	
		<div id="main">