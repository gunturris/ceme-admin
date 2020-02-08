<?php

    function list_tournament_payout(){
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
		'PayoutID' => array( 'style'=>'width:5%;text-align:center;' ), 
		'Position from' => array( 'style'=>'width:20%;text-align:center;' ), 
		'Position to' => array( 'style'=>'width:15%;text-align:center;' ), 
		'Player from' => array( 'style'=>'width:15%;text-align:center;' ), 
		'Player to' => array( 'style'=>'width:15%;text-align:center;' ), 
		'Percent' => array( 'style'=>'width:15%;text-align:center;' ), 
		'act'=>array('width:5%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM tournament_payout ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'payoutId' =>position_text_align( $ey['payoutId'],  'center'),
		'positionFrom' => position_text_align($ey['positionFrom'],   'center'),
		'positionTo' =>position_text_align( $ey['positionTo'],   'center'),
		'playersFrom' =>position_text_align( $ey['playersFrom'],   'center'),
		'playersTo' => position_text_align($ey['playersTo'],   'center'),
		'percent' => position_text_align($ey['percent'],   'center'),
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( ' ' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_tournament_payout($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_tournament_payout" , "form_tournament_payout"  );
	$fields = my_get_data_by_id('tournament_payout','id', $id);


	
	$payoutId = array(
			'name'=>'payoutId',
			'value'=>(isset($_POST['payoutId'])? $_POST['payoutId'] : $fields['payoutId']),
			'id'=>'payoutId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_payoutId = form_dynamic($payoutId);
	$view .= form_field_display( $form_payoutId  , "Payout ID"  );
	
	

	
	$positionFrom = array(
			'name'=>'positionFrom',
			'value'=>(isset($_POST['positionFrom'])? $_POST['positionFrom'] : $fields['positionFrom']),
			'id'=>'positionFrom',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_positionFrom = form_dynamic($positionFrom);
	$view .= form_field_display( $form_positionFrom  , "Position from"  );
	
	

	
	$positionTo = array(
			'name'=>'positionTo',
			'value'=>(isset($_POST['positionTo'])? $_POST['positionTo'] : $fields['positionTo']),
			'id'=>'positionTo',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_positionTo = form_dynamic($positionTo);
	$view .= form_field_display( $form_positionTo  , "Position to"  );
	
	

	
	$playersFrom = array(
			'name'=>'playersFrom',
			'value'=>(isset($_POST['playersFrom'])? $_POST['playersFrom'] : $fields['playersFrom']),
			'id'=>'playersFrom',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playersFrom = form_dynamic($playersFrom);
	$view .= form_field_display( $form_playersFrom  , "Player from"  );
	
	

	
	$playersTo = array(
			'name'=>'playersTo',
			'value'=>(isset($_POST['playersTo'])? $_POST['playersTo'] : $fields['playersTo']),
			'id'=>'playersTo',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playersTo = form_dynamic($playersTo);
	$view .= form_field_display( $form_playersTo  , "Player to"  );
	
	

	
	$percent = array(
			'name'=>'percent',
			'value'=>(isset($_POST['percent'])? $_POST['percent'] : $fields['percent']),
			'id'=>'percent',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_percent = form_dynamic($percent);
	$view .= form_field_display( $form_percent  , "Percent"  );
	
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Save ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'submit-green'
	);
	$form_submit= form_dynamic($submit); 
	
	$cancel = array(
		'value' => ( '  Cancel  '),
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

function submit_tournament_payout($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['payoutId']	=  my_type_data_str($_POST['payoutId']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['positionFrom']	=  my_type_data_str($_POST['positionFrom']);
	 $datas['positionTo']	=  my_type_data_str($_POST['positionTo']);
	 $datas['playersFrom']	=  my_type_data_str($_POST['playersFrom']);
	 $datas['playersTo']	=  my_type_data_str($_POST['playersTo']);
	 $datas['percent']	=  my_type_data_str($_POST['percent']);
	 
	if($id > 0){
		return my_update_record( 'tournament_payout' , 'id' , $id , $datas );
	}
	return my_insert_record( 'tournament_payout' , $datas );
}

function form_tournament_payout_validate(){
	return false;
}
	