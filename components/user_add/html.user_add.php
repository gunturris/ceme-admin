<?php 

function user_add_form($id = 0){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_user_add" , "form_user_add"  );
	$fields = my_get_data_by_id('emp','finger_id', $id);

	$finger_id = array(
			'name'=>'finger_id',
			'value'=>(isset($_POST['finger_id'])? $_POST['finger_id'] : $fields['finger_id']),
			'id'=>'finger_id',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_finger_id = form_dynamic($finger_id);
	$view .= form_field_display( $form_finger_id  , "FingerID"  );
	
	$nik = array(
			'name'=>'nik',
			'value'=>(isset($_POST['nik'])? $_POST['nik'] : $fields['nik']),
			'id'=>'nik',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_finger_id = form_dynamic($nik);
	$view .= form_field_display( $form_finger_id  , "Account Code"  );
	
	$realname = array(
			'name'=>'realname',
			'value'=>(isset($_POST['realname'])? $_POST['realname'] : $fields['realname']),
			'id'=>'realname',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_realname = form_dynamic($realname);
	$view .= form_field_display( $form_realname  , "Realname"  );
	 
    
	$gender = array(
			'name'=>'gender',
			'value'=>(isset($_POST['gender'])? $_POST['gender'] : $fields['gender']),
			'id'=>'gender' 
		);
    $genders = array(
        0 => 'Female',
        1 => 'Male',
    );
	$form_gender = form_radiobutton($gender , $genders);
	$view .= form_field_display( $form_gender  , "Gender"  );
	
    $shifts = array();
    $query_shifts = "SELECT * FROM shift_group";
    $result_shift = my_query($query_shifts);
    while( $row_shift = my_fetch_array($result_shift) ){
        $shifts[$row_shift['shift_id']] = $row_shift['shift_code'] .' / ' . $row_shift['shift_group_name'];
    }
    $shift_group = array(
			'name'=>'shift_id',
			'value'=>(isset($_POST['shift_id'])? $_POST['shift_id'] : $fields['shift_id'] ),
			'id'=>'shift_id' 
		);
    $form_shifts = form_dropdown($shift_group , $shifts);
	$view .= form_field_display( $form_shifts  , "Shift group"  );
    
    $departemens = array();
    $query_deps = "SELECT * FROM departments";
    $result_deps = my_query($query_deps);
    while( $row_dep = my_fetch_array($result_deps) ){
        $departemens[$row_dep['departemen_id']] = $row_dep['departemen_code'] .' / ' . $row_dep['departemen_name'];
    }
    $departemen_id = array(
			'name'=>'departemen_id',
			'value'=>(isset($_POST['departemen_id'])? $_POST['departemen_id'] : $fields['departemen_id'] ),
			'id'=>'departemen_id' 
		);
    $form_dep = form_dropdown($departemen_id , $departemens);
	$view .= form_field_display( $form_dep  , "Unit"  );
    
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

function user_add_validation($id){
	$errsubmit = false;
	$err = array();
	
    if($id == 0){ 
        $finger_exists = finger_id_exists($_POST['finger_id'] );
        if((int) $_POST['finger_id'] == 0){
            $errsubmit = true;
            $err[] = "FingerID empty";
        }elseif( $finger_exists ){
            $errsubmit = true;
            $err[] = "Finger ID already used";
        }
    }
    
	if(trim($_POST['realname']) == ''){
		$errsubmit = true;
		$err[] = "Invalid realname";
	} 
    
	if( $errsubmit){
		return $err;
	}
	return false;

}

function finger_id_exists($finger_id){
    if( ! isset($_GET['create_new']) ){
        return false;
    }
    $query = "SELECT * FROM emp WHERE finger_id = {$finger_id}";
    $result = my_query($query);
    if( my_num_rows($result) > 0){
        return true;
    }
    return false;
}

function user_add_submit(){
  
	$fingerID = (int) trim($_POST['finger_id']);
	$datas = array(); 
	
	$datas['nik']	=  my_type_data_str($_POST['nik']);
	$datas['realname']	=  my_type_data_str($_POST['realname']); 
	// $datas['nickname']	=  my_type_data_str($nickname); 
	$datas['gender']	=  my_type_data_str( $_POST['gender'] ); 
	$datas['shift_id']	=  my_type_data_str( $_POST['shift_id'] ); 
	$datas['departemen_id']	=  my_type_data_str( $_POST['departemen_id'] ); 
	  
	if($fingerID > 0 && !isset($_GET['create_new'])){
		$datas['updated_on']	=  my_type_data_function('NOW()');
		return my_update_record( 'emp' , 'finger_id' ,$fingerID, $datas );
	}
    $datas['finger_id']	=  my_type_data_int( $fingerID );
	$datas['created_on']	=  my_type_data_function('NOW()');
	//upload_data($nickname , $fingerID);
	return my_insert_record( 'emp' , $datas );
}


function upload_data($name , $fingerID){
	return stor_to_machine( $finger_id , $name , $IP , $port = "80" , $Key = "0" );
}

function stor_to_machine( $finger_id , $name , $IP , $port = "80" , $Key = "0" ){ 
	$Connect = fsockopen($IP, $port, $errno, $errstr, 1);
	if($Connect){ 
		$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN>".$finger_id."</PIN><Name>".$name."</Name></Arg></SetUserInfo>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
		fputs($Connect, "Content-Type: text/xml".$newLine);
		fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
		fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
		$buffer=parse_data($buffer,"<Information>","</Information>");
		return $buffer;
	} 
	return false;
}