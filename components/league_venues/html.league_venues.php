<?php

    function list_league_venues(){
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
		'Venue name' => array( 'width:20%;','text-align:left;' ), 
		'Address' => array( 'width:35%;','text-align:center;' ), 
		'Fee chip' => array( 'width:5%;','text-align:left;' ), 
		'Fee dollars' => array( 'width:5%;','text-align:left;' ), 
		'Award chips' => array( 'width:10%;','text-align:right;' ), 
		'Award gold' => array( 'width:10%;','text-align:left;' ), 
		'Venue type' => array( 'width:5%;','text-align:right;' ), 
		'act'=>array('width:5%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM league_venues ";
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
		'venueName' => $ey['venueName'],  
		'address' => $ey['address'],  
		'attendFeeChips' => $ey['attendFeeChips'],  
		'attenFeeDollars' => $ey['attendFeeDollars'],  
		'awardChips' => $ey['awardChips'],  
		'awardGold' => $ey['awardGold'],  
		'venueType' => $ey['venueType'],  
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data league_venues' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_league_venues($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_league_venues" , "form_league_venues"  );
	$fields = my_get_data_by_id('league_venues','id', $id);


	
	$venueName = array(
			'name'=>'venueName',
			'value'=>(isset($_POST['venueName'])? $_POST['venueName'] : $fields['venueName']),
			'id'=>'venueName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_venueName = form_dynamic($venueName);
	$view .= form_field_display( $form_venueName  , "Venue of league"  );
	
	

	
	$address = array(
			'name'=>'address',
			'value'=>(isset($_POST['address'])? $_POST['address'] : $fields['address']),
			'id'=>'address',
			'cols'=>'35',
			'rows'=>'5'
		);
	$form_address = form_textarea($address);
	$view .= form_field_display( $form_address  , "Address"  );
	
	

	
	$attendFeeChips = array(
			'name'=>'attendFeeChips',
			'value'=>(isset($_POST['attendFeeChips'])? $_POST['attendFeeChips'] : $fields['attendFeeChips']),
			'id'=>'attendFeeChips',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_attendFeeChips = form_dynamic($attendFeeChips);
	$view .= form_field_display( $form_attendFeeChips  , "Fee chips"  );
	
	

	
	$attenFeeDollars = array(
			'name'=>'attendFeeDollars',
			'value'=>(isset($_POST['attendFeeDollars'])? $_POST['attendFeeDollars'] : $fields['attendFeeDollars']),
			'id'=>'attendFeeDollars',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_attenFeeDollars = form_dynamic($attenFeeDollars);
	$view .= form_field_display( $form_attenFeeDollars  , "Fee dollars"  );
	
	

	
	$awardChips = array(
			'name'=>'awardChips',
			'value'=>(isset($_POST['awardChips'])? $_POST['awardChips'] : $fields['awardChips']),
			'id'=>'awardChips',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_awardChips = form_dynamic($awardChips);
	$view .= form_field_display( $form_awardChips  , "Award chip"  );
	
	

	
	$awardGold = array(
			'name'=>'awardGold',
			'value'=>(isset($_POST['awardGold'])? $_POST['awardGold'] : $fields['awardGold']),
			'id'=>'awardGold',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_awardGold = form_dynamic($awardGold);
	$view .= form_field_display( $form_awardGold  , "Award gold"  );
	
	

	
	$venueType = array(
			'name'=>'venueType',
			'value'=>(isset($_POST['venueType'])? $_POST['venueType'] : $fields['venueType']),
			'id'=>'venueType',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_venueType = form_dynamic($venueType);
	$view .= form_field_display( $form_venueType  , "Type of venue "  );
	
		 
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

function submit_league_venues($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['leagueId']	=  my_type_data_str($_POST['leagueId']);
	 $datas['venueName']	=  my_type_data_str($_POST['venueName']);
	 $datas['address']	=  my_type_data_str($_POST['address']);
	 $datas['attendFeeChips']	=  my_type_data_str($_POST['attendFeeChips']);
	 $datas['attendFeeGold']	=  my_type_data_str($_POST['attendFeeGold']);
	 $datas['attendFeeDollars']	=  my_type_data_str($_POST['attendFeeDollars']);
	 $datas['awardChips']	=  my_type_data_str($_POST['awardChips']);
	 $datas['awardGold']	=  my_type_data_str($_POST['awardGold']);
	 $datas['venueType']	=  my_type_data_str($_POST['venueType']);
	 $datas['startTime']	=  my_type_data_str($_POST['startTime']);
	 $datas['startDay']	=  my_type_data_str($_POST['startDay']);
	 $datas['startHour']	=  my_type_data_str($_POST['startHour']);
	 $datas['award']	=  my_type_data_str($_POST['award']);
	 
	if($id > 0){
		return my_update_record( 'league_venues' , 'id' , $id , $datas );
	}
	return my_insert_record( 'league_venues' , $datas );
}

function form_league_venues_validate(){
	return false;
}
	