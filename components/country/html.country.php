<?php

    function list_country(){
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
		'name' => array( 'width:35%;','text-align:left;' ), 
		'alpha2' => array( 'width:25%;','text-align:left;' ), 
		'alpha3' => array( 'width:25%;','text-align:left;' ), 
		'act'=>array('width:10%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM countries ";
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
		'name' => $ey['name'],  
		'alpha_2' => $ey['alpha_2'],  
		'alpha_3' => $ey['alpha_3'],  
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data country' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_country($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_country" , "form_country"  );
	$fields = my_get_data_by_id('countries','id', $id);


	
	$name = array(
			'name'=>'name',
			'value'=>(isset($_POST['name'])? $_POST['name'] : $fields['name']),
			'id'=>'name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_name = form_dynamic($name);
	$view .= form_field_display( $form_name  , "Name"  );
	
	

	
	$alpha_2 = array(
			'name'=>'alpha_2',
			'value'=>(isset($_POST['alpha_2'])? $_POST['alpha_2'] : $fields['alpha_2']),
			'id'=>'alpha_2',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_alpha_2 = form_dynamic($alpha_2);
	$view .= form_field_display( $form_alpha_2  , "Alpha_2"  );
	
	

	
	$alpha_3 = array(
			'name'=>'alpha_3',
			'value'=>(isset($_POST['alpha_3'])? $_POST['alpha_3'] : $fields['alpha_3']),
			'id'=>'alpha_3',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_alpha_3 = form_dynamic($alpha_3);
	$view .= form_field_display( $form_alpha_3  , "Alpha_3"  );
	
	

	
	$iso = array(
			'name'=>'iso',
			'value'=>(isset($_POST['iso'])? $_POST['iso'] : $fields['iso']),
			'id'=>'iso',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_iso = form_dynamic($iso);
	$view .= form_field_display( $form_iso  , "Iso"  );
	
	

	
	$lang = array(
			'name'=>'lang',
			'value'=>(isset($_POST['lang'])? $_POST['lang'] : $fields['lang']),
			'id'=>'lang',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_lang = form_dynamic($lang);
	$view .= form_field_display( $form_lang  , "Lang"  );
	
		 
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

function submit_country($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['name']	=  my_type_data_str($_POST['name']);
	 $datas['alpha_2']	=  my_type_data_str($_POST['alpha_2']);
	 $datas['alpha_3']	=  my_type_data_str($_POST['alpha_3']);
	 $datas['iso']	=  my_type_data_str($_POST['iso']);
	 $datas['lang']	=  my_type_data_str($_POST['lang']);
	 
	if($id > 0){
		return my_update_record( 'countries' , 'id' , $id , $datas );
	}
	return my_insert_record( 'countries' , $datas );
}

function form_country_validate(){
	return false;
}
	