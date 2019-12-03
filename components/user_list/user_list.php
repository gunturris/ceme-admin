<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('user_list');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Settings _BN_ Employees";

if( $task =="excel_export"){
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment;filename=data_employees.xls");
     
    echo emp_excel_report();
    exit;
}else{ 
    load_facebox_script();
    $content = user_list();	
}
generate_my_web($content, $pagename  );