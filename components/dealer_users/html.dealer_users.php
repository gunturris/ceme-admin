<?php

function list_dealer_users(){
global $box;
	my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
	
	$headers= array( 
		'Username' => array( 'width'=>'30%','style'=>'text-align:center;' ), 
		'Firstname' => array( 'width'=>'25%','style'=>'text-align:center;' ), 
		'Lastname' => array( 'width'=>'25%','style'=>'text-align:center;' ), 
		'Rights' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Action' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM dealer_users ";
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page = SCROLL_PERHALAMAN;  
	$per_page = PAGING_PERHALAMAN;  
	$current_page = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1 ; 
	if($current_page < 1){
		$current_page = 1;
	}		 
	$task = isset($_GET['task']) ?$_GET['task'] :'' ;
	$field = isset($_GET['field']) ?$_GET['field'] :'' ;
	$key = isset($_GET['key']) ?$_GET['key'] :'' ;
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&halaman=";	 
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = ' Mundur '; 
	$next_page_text = ' Maju ';  
	$first_page_text = ' Awal '; 
	$last_page_text = ' Akhir ';
	
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ->pager_set(
		$pager_url, 
		$total_records, 
		$scroll_page, 
		$per_page, 
		$current_page, 
		$inactive_page_tag, 
		$previous_page_text, 
		$next_page_text, 
		$first_page_text, 
		$last_page_text ,
		$pager_url_last
		); 
	 		
	$result = my_query($query ." LIMIT ".$kgPagerOBJ->start.", ".$kgPagerOBJ->per_page);  
	$i = ($current_page  - 1 ) * $per_page ;
	//PAGING CONTROL END
	
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['userId'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['userId'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'Username' => $ey['username'],  
		'Firstname' => $ey['firstName'],  
		'Lastname' => $ey['lastName'],  
		'Rights' => $ey['rights'],  
		'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data dealer_users' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}


function submit_dealer_users($id){
	 
	$datas = array(); 
     $datas['userId']	=  my_type_data_str($_POST['userId']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['username']	=  my_type_data_str($_POST['username']);
	 $datas['password']	=  my_type_data_str($_POST['password']);
	 $datas['rights']	=  my_type_data_str($_POST['rights']);
	 $datas['firstName']	=  my_type_data_str($_POST['firstName']);
	 $datas['lastName']	=  my_type_data_str($_POST['lastName']);
	 $datas['email']	=  my_type_data_str($_POST['email']);
	 $datas['mobile']	=  my_type_data_str($_POST['mobile']);
	 $datas['marketAnalysis']	=  my_type_data_str($_POST['marketAnalysis']);
	 $datas['leadGenerator']	=  my_type_data_str($_POST['leadGenerator']);
	 $datas['lastpass']	=  my_type_data_str($_POST['lastpass']);
	 $datas['deviceId']	=  my_type_data_str($_POST['deviceId']);
	 $datas['description']	=  my_type_data_str($_POST['description']);
	 $datas['activationCode']	=  my_type_data_str($_POST['activationCode']);
	 $datas['myTime']	=  my_type_data_str($_POST['myTime']);
	 
	if($id > 0){
		return my_update_record( 'dealer_users' , 'dealer_users_id' , $id , $datas );
	}
	return my_insert_record( 'dealer_users' , $datas );
}

function form_dealer_users_validate(){
	return false;
}
	
	
function edit_dealer_users($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_dealer_users" , "form_dealer_users"  );
	$fields = my_get_data_by_id('dealer_users','userId', $id);

  
	
	$username = array(
			'name'=>'username',
			'value'=>(isset($_POST['username'])? $_POST['username'] : $fields['username']),
			'id'=>'username',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_username = form_dynamic($username);
	$view .= form_field_display( $form_username  , "Username"  );
	
	

	
	$password = array(
			'name'=>'password',
			'value'=>(isset($_POST['password'])? $_POST['password'] : $fields['password']),
			'id'=>'password',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_password = form_dynamic($password);
	$view .= form_field_display( $form_password  , "Password"  );
	
	

	$rightss =  array( );
	$query = "SELECT rights_id , label FROM rights";	
	$result = my_query($query);
	while($row_rights = my_fetch_array($result)){
		$rightss[$row_rights['rights_id']] = $row_rights['label'];
	}
	$level = array(
		'name'=>'rights',
		'value'=>( isset($_POST['rights_id']) ? $_POST['rights_id'] : $fields['rights_id']) ,
	);
	$form_rights = form_radiobutton($rights , $rightss);
	$view .= form_field_display(  $form_rights   , "Rights"    ); 
	

	
	$firstName = array(
			'name'=>'firstName',
			'value'=>(isset($_POST['firstName'])? $_POST['firstName'] : $fields['firstName']),
			'id'=>'firstName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_firstName = form_dynamic($firstName);
	$view .= form_field_display( $form_firstName  , "FirstName"  );
	
	

	
	$lastName = array(
			'name'=>'lastName',
			'value'=>(isset($_POST['lastName'])? $_POST['lastName'] : $fields['lastName']),
			'id'=>'lastName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_lastName = form_dynamic($lastName);
	$view .= form_field_display( $form_lastName  , "LastName"  );
	
	

	
	$email = array(
			'name'=>'email',
			'value'=>(isset($_POST['email'])? $_POST['email'] : $fields['email']),
			'id'=>'email',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_email = form_dynamic($email);
	$view .= form_field_display( $form_email  , "Email"  );
	
	

	
	$mobile = array(
			'name'=>'mobile',
			'value'=>(isset($_POST['mobile'])? $_POST['mobile'] : $fields['mobile']),
			'id'=>'mobile',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_mobile = form_dynamic($mobile);
	$view .= form_field_display( $form_mobile  , "Mobile"  );
	
	

	$marketAnalysiss =  array( );
	$query = "SELECT marketAnalysis_id , label FROM marketAnalysis";	
	$result = my_query($query);
	while($row_marketAnalysis = my_fetch_array($result)){
		$marketAnalysiss[$row_marketAnalysis['marketAnalysis_id']] = $row_marketAnalysis['label'];
	}
	$level = array(
		'name'=>'marketAnalysis',
		'value'=>( isset($_POST['marketAnalysis_id']) ? $_POST['marketAnalysis_id'] : $fields['marketAnalysis_id']) ,
	);
	$form_marketAnalysis = form_radiobutton($marketAnalysis , $marketAnalysiss);
	$view .= form_field_display(  $form_marketAnalysis   , "MarketAnalysis"    ); 
	

	$leadGenerators =  array( );
	$query = "SELECT leadGenerator_id , label FROM leadGenerator";	
	$result = my_query($query);
	while($row_leadGenerator = my_fetch_array($result)){
		$leadGenerators[$row_leadGenerator['leadGenerator_id']] = $row_leadGenerator['label'];
	}
	$level = array(
		'name'=>'leadGenerator',
		'value'=>( isset($_POST['leadGenerator_id']) ? $_POST['leadGenerator_id'] : $fields['leadGenerator_id']) ,
	);
	$form_leadGenerator = form_radiobutton($leadGenerator , $leadGenerators);
	$view .= form_field_display(  $form_leadGenerator   , "LeadGenerator"    ); 
	

	
	$lastpass = array(
			'name'=>'lastpass',
			'value'=>(isset($_POST['lastpass'])? $_POST['lastpass'] : $fields['lastpass']),
			'id'=>'lastpass',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_lastpass = form_dynamic($lastpass);
	$view .= form_field_display( $form_lastpass  , "Lastpass"  );
	
	

	
	$deviceId = array(
			'name'=>'deviceId',
			'value'=>(isset($_POST['deviceId'])? $_POST['deviceId'] : $fields['deviceId']),
			'id'=>'deviceId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_deviceId = form_dynamic($deviceId);
	$view .= form_field_display( $form_deviceId  , "DeviceId"  );
	
	

	
	$description = array(
			'name'=>'description',
			'value'=>(isset($_POST['description'])? $_POST['description'] : $fields['description']),
			'id'=>'description',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_description = form_dynamic($description);
	$view .= form_field_display( $form_description  , "Description"  );
	
	

	
	$activationCode = array(
			'name'=>'activationCode',
			'value'=>(isset($_POST['activationCode'])? $_POST['activationCode'] : $fields['activationCode']),
			'id'=>'activationCode',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_activationCode = form_dynamic($activationCode);
	$view .= form_field_display( $form_activationCode  , "ActivationCode"  );
	
	

	$myTimes =  array( );
	$query = "SELECT myTime_id , label FROM myTime";	
	$result = my_query($query);
	while($row_myTime = my_fetch_array($result)){
		$myTimes[$row_myTime['myTime_id']] = $row_myTime['label'];
	}
	$level = array(
		'name'=>'myTime',
		'value'=>( isset($_POST['myTime_id']) ? $_POST['myTime_id'] : $fields['myTime_id']) ,
	);
	$form_myTime = form_radiobutton($myTime , $myTimes);
	$view .= form_field_display(  $form_myTime   , "MyTime"    ); 
		 
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
	 
	$view .= form_field_display( $form_submit.' '.$form_cancel, "&nbsp;" , "<hr />"  );
	$view .= form_footer( );	
	
	return  $view;
} 
?>