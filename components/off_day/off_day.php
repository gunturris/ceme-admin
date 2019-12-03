<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('off_day');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Off day request";
if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    
    if($task == "edit" ){
        if( $validate = off_day_validation()){
            $content = message_multi_error($validate);
            $content .= off_day_form($id  ); 
        }else{

            off_day_submit($id);
            my_direct("index.php?com=off_day");
        }
    }
    
}else{
  
    if( $task == "ajax_check_name" ){
        autocomplete_info();
    }elseif( $task == "edit" ){
        $content = off_day_form($id);
    } else{
        load_facebox_script();
        $content = list_off_day_request();	
    }
    
}

generate_my_web($content, $pagename  );