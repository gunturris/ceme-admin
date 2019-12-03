<?php

function list_off_dates(){
    my_set_code_js("
        function deleteConfirm(id){
            tc = confirm('Are you sure?');
            if(tc){
                location.href='index.php?com={$_GET['com']}&task=delete&id=' + id
            }
            return;
        }
    ");
    global $box;
	$header = array(    
		'Date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),  
		'Note'=>array('style'=>'text-align:center;border-bottom:2px solid;width:59%'),  
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
	);
    
    
    $query = "SELECT * FROM off_dates ORDER BY off_date DESC ";
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
						'onclick'=>'javascript:deleteConfirm('.$ey['id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
		$row[] = array( 
			'gender'=>  position_text_align (date( 'd-m-Y' , strtotime($ey['off_date']) ) ,   'center'), 
			'department'=> $ey['note'],
			'action'=> position_text_align ( $edit_button ." ". $delete_button ,   'center')
		);
		 
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Add data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi );
    
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 4 , false ,$paging   ); 
    
    
}


function off_date_form($id = 0){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_unit_add" , "form_unit_add"  );
	$fields = my_get_data_by_id('off_dates','id', $id);
 
	 
	$note = array(
			'name'=>'note',
			'value'=>(isset($_POST['note'])? $_POST['note'] : $fields['note']),
			'id'=>'note',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_note = form_dynamic($note);
	$view .= form_field_display( $form_note  , "Holiday event name"  );
    
    
    
    if(  isset($fields['off_date'] ) ){
        $date_r = $fields['off_date'];
        
    }else{
        $date_r = date("Y-m-d");
        
        
    }
    $off_date = array(
			'name'=>'off_date',
			'value'=>(isset($_POST['off_date'])? $_POST['off_date'] : $date_r),
			'id'=>'off_date', 
			'size'=>'45'
		);
	$form_off_date = form_calendar($off_date);
	$view .= form_field_display( $form_off_date  , "Event date"  );
    
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

function off_date_submit($id = 0){
    
    $datas = array();
    $datas['off_date'] = my_type_data_str($_POST['off_date']);
    $datas['note'] = my_type_data_str($_POST['note']);
    if( $id > 0 ){
        return my_update_record('off_dates' ,'id' , $id , $datas);
        
    }
    return my_insert_record('off_dates' , $datas);
}

function off_date_validate($id){
    $errsubmit = false;
	$err = array();
    
    if(trim($_POST['note']) == ''){
		$errsubmit = true;
		$err[] = "Holiday event name empty";
	} 
    
	if( $errsubmit){
		return $err;
	}
	return false;
}