<?php

    function list_texas_groups(){
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
		'DealerID' => array( 'style'=>'width:25%;text-align:left;' ), 
		'Group name' => array( 'style'=>'width:25%;text-align:left;' ), 
		'Table low' => array( 'style'=>'width:22%;text-align:left;' ), 
		'Table limit' => array( 'style'=>'width:22%;text-align:left;' ), 
		'act'=>array('width:5%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM texas_groups ";
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
        
        $dealer = my_get_data_by_id('dealers' , 'dealerId' , $ey['dealerId']);
		$row[] = array( 
		'dealerId' => $dealer['busname'],  
		'name' => $ey['name'],  
		'tablelow' => position_text_align( rp_format( $ey['tablelow']),  'right'),
		'tablelimit' => position_text_align( rp_format( $ey['tablelimit']),  'right'),
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

	
function edit_texas_groups($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_texas_groups" , "form_texas_groups"  );
	$fields = my_get_data_by_id('texas_groups','id', $id);


	
	$dealerId = array(
			'name'=>'dealerId',
			'value'=>(isset($_POST['dealerId'])? $_POST['dealerId'] : $fields['dealerId']),
			'id'=>'dealerId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_dealerId = form_dynamic($dealerId);
	$view .= form_field_display( $form_dealerId  , "DealerID"  );
	
	

	
	$name = array(
			'name'=>'name',
			'value'=>(isset($_POST['name'])? $_POST['name'] : $fields['name']),
			'id'=>'name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_name = form_dynamic($name);
	$view .= form_field_display( $form_name  , "Group name"  );
	
	

	
	$tablelow = array(
			'name'=>'tablelow',
			'value'=>(isset($_POST['tablelow'])? $_POST['tablelow'] : $fields['tablelow']),
			'id'=>'tablelow',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tablelow = form_dynamic($tablelow);
	$view .= form_field_display( $form_tablelow  , "Table low"  );
	
	

	
	$tablelimit = array(
			'name'=>'tablelimit',
			'value'=>(isset($_POST['tablelimit'])? $_POST['tablelimit'] : $fields['tablelimit']),
			'id'=>'tablelimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tablelimit = form_dynamic($tablelimit);
	$view .= form_field_display( $form_tablelimit  , "Table limit"  );
	
		 
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

function submit_texas_groups($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['name']	=  my_type_data_str($_POST['name']);
	 $datas['tablelow']	=  my_type_data_str($_POST['tablelow']);
	 $datas['tablelimit']	=  my_type_data_str($_POST['tablelimit']);
	 
	if($id > 0){
		return my_update_record( 'texas_groups' , 'id' , $id , $datas );
	}
	return my_insert_record( 'texas_groups' , $datas );
}

function form_texas_groups_validate(){
	return false;
}
	