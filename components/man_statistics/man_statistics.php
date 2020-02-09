<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('leagues' );

//require_once(__DIR__ .'/custom.man_statistics.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Statistics';

load_facebox_script();
$content =  page_statistics() ; 

generate_my_web($content, $modulname );
?>
