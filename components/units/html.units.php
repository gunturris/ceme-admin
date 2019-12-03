<?php

function list_units(){
    
    my_set_code_js("
        function deleteConfirm(id){
            tc = confirm('Are you sure?');
            if(tc){
                location.href='index.php?com=units&task=delete&id=' + id
            }
            return;
        }
    ");
    global $box;
	$header = array(    
		'Code'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),  
		'Unit name'=>array('style'=>'text-align:center;border-bottom:2px solid;width:64%'),  
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:5%'),  
	);
    
    
    $query = "SELECT * FROM departments LIMIT 100";
    $result = my_query($query);
    $row = array();
    while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com='.$_GET['com'].'&task=edit&id='.$ey['departemen_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
  
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:deleteConfirm('.$ey['departemen_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
		$row[] = array( 
			'code'=>  position_text_align ( $ey['departemen_code'] ,   'center'), 
			'name'=> $ey['departemen_name'],
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


function unit_add_form($id = 0){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_unit_add" , "form_unit_add"  );
	$fields = my_get_data_by_id('departments','departemen_id', $id);
 
	$departemen_code = array(
			'name'=>'departemen_code',
			'value'=>(isset($_POST['departemen_code'])? $_POST['departemen_code'] : $fields['departemen_code']),
			'id'=>'departemen_code',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_departemen_code = form_dynamic($departemen_code);
	$view .= form_field_display( $form_departemen_code  , "Unit Code"  );
	
	$departemen_name = array(
			'name'=>'departemen_name',
			'value'=>(isset($_POST['departemen_name'])? $_POST['departemen_name'] : $fields['departemen_name']),
			'id'=>'departemen_name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_departemen_name = form_dynamic($departemen_name);
	$view .= form_field_display( $form_departemen_name  , "Unit name"  );
    
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

function unit_add_submit($id){
    var_dump($id);
    $datas = array();
    $datas['departemen_code'] = my_type_data_str($_POST['departemen_code']);
    $datas['departemen_name'] = my_type_data_str($_POST['departemen_name']);
    if($id > 0){
        return my_update_record('departments' , 'departemen_id' , $id, $datas);
    }
    $datas['departemen_id'] = mt_rand( 10000,99999 );
    $datas['created_on'] = my_type_data_function( 'NOW()' );
    return my_insert_record('departments' , $datas);
}

function unit_add_validate($id){
    $errsubmit = false;
	$err = array();
	
    if($id == 0){ 
        $code_exists = unit_code_exists($_POST['departemen_code'] );
        if( trim($_POST['departemen_code']) == ""){
            $errsubmit = true;
            $err[] = "Code empty";
        }elseif( $code_exists ){
            $errsubmit = true;
            $err[] = "Code already used";
        }
    }
    
	if(trim($_POST['departemen_name']) == ''){
		$errsubmit = true;
		$err[] = "Invalid unit name";
	} 
    
	if( $errsubmit){
		return $err;
	}
	return false;
}

function unit_code_exists($code){
    $query = "SELECT * FROM departments WHERE departemen_code = '{$code}' ";
    $result = my_query($query);
    if( my_num_rows($result) > 0 ){
        return true;
    }
    return false;
}