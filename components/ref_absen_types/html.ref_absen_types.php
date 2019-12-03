<?php

function list_absen_types(){
    
    global $box;
	$header = array(    
		'Code'=>array('style'=>'text-align:center;border-bottom:2px solid;width:35%'),  
		'Absen type'=>array('style'=>'text-align:center;border-bottom:2px solid;width:54%'),  
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
	);
    
    
    $query = "SELECT * FROM absen_types  LIMIT 100";
    $result = my_query($query);
    $row = array();
    while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com='.$_GET['com'].'&task=edit&id='.$ey['type_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
  
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:confirmDelete('.$ey['type_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
		$row[] = array( 
			'code'=>  position_text_align (  $ey['type_code'] ,   'center'), 
			'name'=> $ey['name'],
			'action'=> position_text_align ( $edit_button ." ". $delete_button ,   'center')
		);
		 
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Add data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi ); 
	return table_builder($header , $datas , 4 , false    ); 
    
    
}

function get_type_by_code($code ){
    $query = "SELECT * FROM absen_types WHERE type_code = '{$code}' ";
    $res = my_query($query);
    if( my_num_rows($res) > 0 ){
        return true;
    }
    return false;
}

function absen_type_validate($id = 0){
   
	$errsubmit = false;
	$err = array();
    if($id == 0){	 
        $code_exists = get_type_by_code($_POST['type_code'] );
     
        if( $_POST['type_code'] == ""){
            $errsubmit = true;
            $err[] = "Code empty";
        }elseif( $code_exists ){
            $errsubmit = true;
            $err[] = "Code already used";
        }
    }
	
	if(trim($_POST['name']) == ''){
		$errsubmit = true;
		$err[] = "Absen type name is empty";
	} 
     
    
	if( $errsubmit){
		return $err;
	}
	return false;

}
    
function absen_type_submit($id = 0){
    $datas = array();
    $datas['type_code'] = my_type_data_str($_POST['type_code']);
    $datas['name'] = my_type_data_str($_POST['name']);
    
    if( $id > 0 ){
        return my_update_record('absen_types' , 'type_id' , $id , $datas);
    }
    return my_insert_record('absen_types' , $datas);
    
}

function absen_type_form($id = 0){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_absen_add" , "form_absen_add"  );
	$fields = my_get_data_by_id('absen_types','type_id', $id);
 
	 
	$type_code = array(
			'name'=>'type_code',
			'value'=>(isset($_POST['type_code'])? $_POST['type_code'] : $fields['type_code']),
			'id'=>'type_code',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_type_code = form_dynamic($type_code);
	$view .= form_field_display( $form_type_code  , "Type code"  );
    
	$name	 = array(
			'name'=>'name',
			'value'=>(isset($_POST['name'])? $_POST['name'] : $fields['name']),
			'id'=>'name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_name = form_dynamic($name);
	$view .= form_field_display( $form_name  , "Absen type label"  );
    
     
    
    $submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'submit-green'
	);
	$form_submit= form_dynamic($submit); 
	 
	$cancel = array(
		'value' => ( '  Batal  '),
		'name' => 'cancel', 
		'type'=>'button',
		'onclick'=>'location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'submit-gray'
	);
	$form_cancel = form_dynamic($cancel );
	 
	$view .= form_field_display( $form_submit .' '.$form_cancel   , "<hr />"  );
	$view .= form_footer( );	
	
	return  $view;
}