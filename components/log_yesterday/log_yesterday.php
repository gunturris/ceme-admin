<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('log_yesterday');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Log _BN_ Yesterday";
 
	load_facebox_script();
	$content = list_log();	
 
generate_my_web($content, $pagename  );