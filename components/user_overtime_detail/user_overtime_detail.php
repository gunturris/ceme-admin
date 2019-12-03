<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('list_kalender' , false);  
my_component_load('user_overtime' , false);  
my_component_load('user_overtime_detail');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Overtime";
 
load_facebox_script();
$content = user_overtime_detail($id);

generate_my_web($content, $pagename  );