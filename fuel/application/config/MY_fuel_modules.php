<?php 
/*
|--------------------------------------------------------------------------
| MY Custom Modules
|--------------------------------------------------------------------------
|
| Specifies the module controller (key) and the name (value) for fuel
*/


/*********************** EXAMPLE ***********************************

$config['modules']['quotes'] = array(
	'preview_path' => 'about/what-they-say',
);

$config['modules']['projects'] = array(
	'preview_path' => 'showcase/project/{slug}',
	'sanitize_images' => FALSE // to prevent false positives with xss_clean image sanitation
);

*********************** EXAMPLE ***********************************/

$config['modules']['items'] = array();
$config['modules']['auctions'] = array();
$config['modules']['players'] = array();
$config['modules']['market'] = array();
$config['modules']['mail'] = array();
$config['modules']['fee'] = array();
$config['modules']['static'] = array();
$config['modules']['player_items'] = array();
$config['modules']['enchantments'] = array();