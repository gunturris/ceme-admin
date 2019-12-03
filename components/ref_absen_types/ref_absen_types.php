<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('ref_absen_types');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Settings _BN_ Attendance status";

if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    
    if($task == "edit" ){
        if( $validate = absen_type_validate($id)){
            $content = message_multi_error($validate);
            $content .= absen_type_form($id  ); 
        }else{

            absen_type_submit($id);
            my_direct("index.php?com=ref_absen_types");
        }
    }
    
}else{
  
    if( $task == "edit" ){
        $content = absen_type_form($id);
    } else{
        load_facebox_script();
        $content = list_absen_types();	
    }
    
}


 
generate_my_web($content, $pagename  );