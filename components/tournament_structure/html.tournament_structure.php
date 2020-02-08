<?php

    function list_tournament_structure(){
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
		'StructurID' => array( 'style'=>'width:10%;text-align:center;' ), 
		'Level' => array( 'style'=>'width:15%;text-align:center;' ), 
		'Ante' => array('style'=> 'width:10%;text-align:center;' ), 
		'SB' => array( 'style'=>'width:10%;text-align:center;' ), 
		'BB' => array( 'style'=>'width:15%;text-align:center;' ), 
		'Mins' => array( 'style'=>'width:10%;text-align:center;' ), 
		'Key' => array( 'style'=>'width:10%;text-align:center;' ), 
		'Break' => array('style'=> 'width:12%;text-align:center;' ), 
		'act'=>array('style'=>'width:5%text-align:right')
	);

	
	
	$query 	= "SELECT * FROM tournament_structure ";
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
            'structureId' => position_text_align( $ey['structureId'],  'center'),
            'level' => position_text_align( $ey['level'],  'center'),
            'ante' => position_text_align( $ey['ante'],  'center'),
            'sb' => position_text_align( $ey['sb'],  'center'),
            'bb' => position_text_align( $ey['bb'],  'center'),
            'mins' =>position_text_align(  $ey['mins'],  'center'),
            'key' =>position_text_align(  0,  'center'),
            'isBreak' => position_text_align( $ey['isBreak'],'center'),  
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

	
function edit_tournament_structure($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_tournament_structure" , "form_tournament_structure"  );
	$fields = my_get_data_by_id('tournament_structure','id', $id);


	
	$structureId = array(
			'name'=>'structureId',
			'value'=>(isset($_POST['structureId'])? $_POST['structureId'] : $fields['structureId']),
			'id'=>'structureId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_structureId = form_dynamic($structureId);
	$view .= form_field_display( $form_structureId  , "StructurID"  );
	
	

	
	$level = array(
			'name'=>'level',
			'value'=>(isset($_POST['level'])? $_POST['level'] : $fields['level']),
			'id'=>'level',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_level = form_dynamic($level);
	$view .= form_field_display( $form_level  , "Level"  );
	
	

	
	$ante = array(
			'name'=>'ante',
			'value'=>(isset($_POST['ante'])? $_POST['ante'] : $fields['ante']),
			'id'=>'ante',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_ante = form_dynamic($ante);
	$view .= form_field_display( $form_ante  , "Ante"  );
	
	

	
	$sb = array(
			'name'=>'sb',
			'value'=>(isset($_POST['sb'])? $_POST['sb'] : $fields['sb']),
			'id'=>'sb',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_sb = form_dynamic($sb);
	$view .= form_field_display( $form_sb  , "SB"  );
	
	

	
	$bb = array(
			'name'=>'bb',
			'value'=>(isset($_POST['bb'])? $_POST['bb'] : $fields['bb']),
			'id'=>'bb',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bb = form_dynamic($bb);
	$view .= form_field_display( $form_bb  , "BB"  );
	
	

	
	$mins = array(
			'name'=>'mins',
			'value'=>(isset($_POST['mins'])? $_POST['mins'] : $fields['mins']),
			'id'=>'mins',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_mins = form_dynamic($mins);
	$view .= form_field_display( $form_mins  , "Mins"  );
	
	

	
	$isBreak = array(
			'name'=>'isBreak',
			'value'=>(isset($_POST['isBreak'])? $_POST['isBreak'] : $fields['isBreak']),
			'id'=>'isBreak',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_isBreak = form_dynamic($isBreak);
	$view .= form_field_display( $form_isBreak  , "Break"  );
	
		 
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

function submit_tournament_structure($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['structureId']	=  my_type_data_str($_POST['structureId']);
	 $datas['level']	=  my_type_data_str($_POST['level']);
	 $datas['ante']	=  my_type_data_str($_POST['ante']);
	 $datas['sb']	=  my_type_data_str($_POST['sb']);
	 $datas['bb']	=  my_type_data_str($_POST['bb']);
	 $datas['mins']	=  my_type_data_str($_POST['mins']);
	 $datas['isBreak']	=  my_type_data_str($_POST['isBreak']);
	 
	if($id > 0){
		return my_update_record( 'tournament_structure' , 'id' , $id , $datas );
	}
	return my_insert_record( 'tournament_structure' , $datas );
}

function form_tournament_structure_validate(){
	return false;
}
	