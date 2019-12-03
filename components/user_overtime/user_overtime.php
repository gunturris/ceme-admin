<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('list_kalender' , false);  
my_component_load('user_overtime');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Overtimes";
if( $task == "edit" ){
    
    if( $_SERVER['REQUEST_METHOD'] == "POST"){
        if( $validate = overtime_activity_log_validation($id)){ 
            $content = message_multi_error($validate);
            $content .= form_overtime_activity_log($id);
            
        }else{
            $finger_id = form_overtime_activity_submit($id);
            my_direct("index.php?com=user_overtime_detail&id={$finger_id}");
        }
    }else{
        $content = form_overtime_activity_log($id);
        
    }

}elseif( $task == "calculate_overtime"){ 
    $title = "Calculation on progress ...";
    facebox_page('index.php?com='. $_GET['com'] .'&task=calculate_overtime_page' , $title , 145	); 
    
}elseif( $task == "calculation_overtime_process"){
    //sleep(10);
    calculation_overtime_all();
    echo '<img src="icon/okdone.png" height="60px" /><br/>Done!';
    exit;
}elseif( $task == "calculate_overtime_page"){
    $content = page_kalkulasi_overtime(); 
    generate_my_web($content,"","plain_loading.php");
    exit;    
    
}else{ 
    load_facebox_script();
    $content = user_overtime_log();	
    
}

generate_my_web($content, $pagename  );