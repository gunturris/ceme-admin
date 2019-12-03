<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('ref_shift');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 
$day_id = isset( $_GET['day'] ) ? $_GET['day']:  0; 

$pagename = "Application _BN_ Settings _BN_ Shift";

if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    
    if($task == "edit" ){ 
        shift_submit($id);
        my_direct("index.php?com=ref_shift");

    }elseif( $task == "edit_detail"){
        
        if( $validate = shift_detail_validate($id)){
            $content = message_multi_error($validate);
            $content .= shift_detail_form( $day_id , $id ); 
        }else{ 
            shift_detail_submit($day_id , $id); 
            my_direct("index.php?com=ref_shift&type=detail&id={$id}");
        }
        
    }
    
}else{

    if( $task == "detail"){    
        $content = list_shift_days($id);

    }elseif($task == "edit_detail"){ 
        $content = shift_detail_form( $day_id , $id );
        
    }elseif($task == "edit"){ 
        $content = shift_form($id );

    }else{

        load_facebox_script();
        $content = list_shifts();	

    }

}
generate_my_web($content, $pagename  );