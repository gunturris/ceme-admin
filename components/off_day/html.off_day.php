<?php


function list_off_day_request(){
    
    global $box;
	$header = array(    
		'Employee'=>array('style'=>'text-align:center;border-bottom:2px solid;width:25%'),  
		'Date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Absen type'=>array('style'=>'text-align:center;border-bottom:2px solid;width:14%'),  
		'Remark'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),  
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
	);
    
    
    $query = "SELECT * FROM absen_logs ORDER BY id DESC  ";
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
	$previous_page_text = ' Prev '; 
	$next_page_text = ' Next ';  
	$first_page_text = ' First '; 
	$last_page_text = ' Last ';
	
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
    while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com='.$_GET['com'].'&task=edit&id='.$ey['id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
  
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:confirmDelete('.$ey['id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
        $emp = my_get_data_by_id('emp' , 'finger_id' , $ey['emp_finger_id'] );
        $abstype =  my_get_data_by_id('absen_types' , 'type_id' , $ey['absen_type_id'] );
        
        
		$row[] = array( 
			'emp'=>  $emp['nik'] ." / ".$emp['realname'] ,  
			'date'=> position_text_align (date('d-m-Y' , strtotime($ey['request_date'] ) ), 'center'), 
			'type'=> $abstype['name'],
			'remark'=> $ey['remark'],
			'action'=> position_text_align ( $edit_button ." ". $delete_button ,   'center')
		);
		 
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Create new" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi ); 
    
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 5 , false ,$paging   ); 
    
}


function off_day_form($id = 0){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/jquery/autocomplete/jquery.autocomplete.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
     
	my_set_file_css(

		array(
			'assets/jquery/autocomplete/jquery.autocomplete.css' 
		)
	);

	my_set_code_js('  
		function findValue(li) {
			if( li == null ) return alert("No match!"); 
			if( !!li.extra ) var sValue = li.extra[0]; 
			else var sValue = li.selectValue;
		}

		function selectItem(li) {
			findValue(li);
		}

		function formatItem(row) {
			return   row[0] ;
		}


		function lookupAjax(){
			var oSuggest = $("#nama_karyawan")[0].autocompleter;
			oSuggest.findValue(); 
			return false;
		}
		$(document).ready(function() {
			$("#nama_karyawan").autocomplete(
				"ajax_check_emp.php",{ com: \'off_day\' ,task: \'ajax_check_name\'  },
				{
					delay:10,
					minChars:2,
					matchSubset:1,
					matchContains:1,
					cacheLength:5,
					onItemSelect:selectItem,
					onFindValue:findValue,
					formatItem:formatItem,
					autoFill:true
				}
			);
			 
		});
        
        /*
		function checkName(name){
			$.get("check_karyawan_names.php", { nama: name  },
			   function(data){
				 if(data ==  \'0\' ){
					alert(\'Nama karyawan \'+ name+\' tidak ditemukan\nHarap diperiksa kembali\');
					$(\'#nama_karyawan\').val(\'\');
					return false;
				 } 
			   });
		}
        */
	');
    
	$view = form_header( "form_user_add" , "form_user_add"  );
	$fields = my_get_data_by_id('absen_logs','id', $id);

    
	$empinfo = my_get_data_by_id('emp','finger_id', (int) $fields['emp_finger_id']);
    
	$nik = array(
			'name'=>'nik',
			'value'=>(isset($_POST['nik'])? $_POST['nik'] : ( $empinfo ? $empinfo['nik'].'/ '.$empinfo['realname']  : "" ) ),
			'id'=>'nama_karyawan',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_finger_id = form_dynamic($nik);
	$view .= form_field_display( $form_finger_id  , "Employee <font size='1'>(Checking by code)</font>"  );
	
    
    if(  isset($fields['request_date'] ) ){
        $date_r = $fields['request_date'];
        
    }else{
        $date_r = date("Y-m-d");
        
        
    }
    $request_date = array(
			'name'=>'request_date',
			'value'=>(isset($_POST['request_date'])? $_POST['request_date'] : $date_r),
			'id'=>'request_date', 
			'size'=>'45'
		);
	$form_request_date = form_calendar($request_date);
	$view .= form_field_display( $form_request_date  , "Date for off"  );
    
    $types = array();
    $query_types = "SELECT * FROM absen_types";
    $result_type = my_query($query_types);
    while( $row_type = my_fetch_array($result_type) ){
        $types[$row_type['type_id']] = $row_type['type_code'] .' / ' . $row_type['name'];
    }
    $absen_type_id = array(
			'name'=>'absen_type_id',
			'value'=>(isset($_POST['absen_type_id'])? $_POST['absen_type_id'] : $fields['absen_type_id'] ),
			'id'=>'absen_type_id' 
		);
    $form_types = form_dropdown($absen_type_id , $types);
	$view .= form_field_display( $form_types  , "Off day type"  );
    
    $remark = array(
			'name'=>'remark',
			'value'=>(isset($_POST['remark'])? $_POST['remark'] : $fields['remark']),
			'id'=>'remark',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_remark = form_dynamic($remark);
	$view .= form_field_display( $form_remark  , "Remark"  );
    
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


function off_day_validation(){
	$errsubmit = false;
	$err = array();
	$emp = explode("/" , $_POST['nik'] );
     
    $nik = trim( $emp[0] );
    $emp_exists = get_emp_by_nik_name($nik );
     
	if( $_POST['nik'] == ""){
		$errsubmit = true;
		$err[] = "Employee data empty";
	}elseif( ! $emp_exists ){
        $errsubmit = true;
		$err[] = "Employee data not found";
    }
	
	if(trim($_POST['remark']) == ''){
		$errsubmit = true;
		$err[] = "Remark is empty";
	} 
    
    if( (int) $_POST['absen_type_id'] == 0 ){
		$errsubmit = true;
		$err[] = "Please select type";
        
    }
    
	if( $errsubmit){
		return $err;
	}
	return false;

}

function off_day_submit( $id = 0){
    $emp = explode("/" , $_POST['nik'] );
    $nik = trim( $emp[0] );
    $empinfo = get_emp_by_nik_name($nik);
     
    $datas = array();
    $datas['request_date']  = my_type_data_str( $_POST['request_date'] );
    $datas['absen_type_id'] = my_type_data_int( $_POST['absen_type_id'] );
    $datas['emp_finger_id']        = my_type_data_int( $empinfo['finger_id'] );
    $datas['remark']        = my_type_data_str( $_POST['remark'] );
    if( $id > 0 ){
        return my_update_record(    'absen_logs', 'id' , $id , $datas   );
    }
    $datas['created_on'] = my_type_data_function('NOW()');
    return my_insert_record(    'absen_logs' , $datas   );
}
