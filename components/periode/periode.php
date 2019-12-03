<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('periode');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 


$pagename = "Application _BN_ Settings _BN_ Periode";

load_facebox_script();
if($task == 'monthend'){
    month_end();
    my_direct("index.php?com=periode");
    exit;
}else{
    
    $content = list_periodes();
}
generate_my_web($content, $pagename  );