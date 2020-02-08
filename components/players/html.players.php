<?php

    function list_players(){
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
		'Dealer' => array( 'width:20%;','text-align:left;' ), 
		'Username' => array( 'width:15%;','text-align:center;' ), 
		'Email' => array( 'width:15%;','text-align:left;' ), 
		'Rank' => array( 'width:5%;','text-align:left;' ), 
		'Bank name' => array( 'width:10%;','text-align:right;' ), 
		'Bank acc-no' => array( 'width:10%;','text-align:left;' ), 
		'Transfer limit' => array( 'width:15%;','text-align:right;' ), 
		'Last login' => array( 'width:15%;','text-align:right;' ), 
		'act'=>array('width:10%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM players ";
	//$result = my_query($query);
	
     
	//PAGING CONTROL START
	$total_records = players_total( );
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['ID'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['ID'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

        $dealer = my_get_data_by_id('dealers' , 'dealerId' ,  $ey['dealerId']);
		$row[] = array( 
            'dealerId' =>$dealer['busname'],  
            'username' => $ey['username'],  
            'email' => $ey['email'],  
            'rank' => 0,//$ey['rank'],  
            'bankName' => $ey['bankName'],  
            'bankAccount' => $ey['bankAccount'],  
            'transferLimit' => position_text_align(  rp_format($ey['transferLimit']) , 'right'),  
            'lastlogin' => date('Y-m-d' , $ey['lastlogin']),  
            'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( '' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas , 9, false , $paging  ); 
}

	
function edit_players($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_players" , "form_players"  );
	$fields = my_get_data_by_id('players','ID', $id);


	$dealers = array();
    $query_dealers = "SELECT * FROM dealers";
    $resdealers = my_query($query_dealers);
    while( $row_dealers = my_fetch_array($resdealers) ){
        $dealers[$row_dealers['dealerId']] = $row_dealers['busname'];
    }
    
	$dealerId = array(
			'name'=>'dealerId',
			'value'=>(isset($_POST['dealerId'])? $_POST['dealerId'] : $fields['dealerId']),
			'id'=>'dealerId', 
		);
	$form_dealerId = form_dropdown($dealerId , $dealers);
	$view .= form_field_display( $form_dealerId  , "Dealer"  );
	
	
 
	$username = array(
			'name'=>'username',
			'value'=>(isset($_POST['username'])? $_POST['username'] :  $fields['username']),
			'id'=>'username',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_username = form_dynamic($username);
	$view .= form_field_display( $form_username  , "Username" );
	

	
	$email = array(
			'name'=>'email',
			'value'=>(isset($_POST['email'])? $_POST['email'] : $fields['email']),
			'id'=>'email',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_email = form_dynamic($email);
	$view .= form_field_display( $form_email  , "Email"  );
	
	

	
	$rank = array(
			'name'=>'rank',
			'value'=>(isset($_POST['rank'])? $_POST['rank'] : 'TIDAK ADA FIELD'),
			'id'=>'rank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rank = form_dynamic($rank);
	$view .= form_field_display( $form_rank  , "Rangking"  );
	
	

	
	$bankName = array(
			'name'=>'bankName',
			'value'=>(isset($_POST['bankName'])? $_POST['bankName'] : $fields['bankName']),
			'id'=>'bankName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankName = form_dynamic($bankName);
	$view .= form_field_display( $form_bankName  , "Bank name"  );
	
	

	
	$bankAccount = array(
			'name'=>'bankAccount',
			'value'=>(isset($_POST['bankAccount'])? $_POST['bankAccount'] : $fields['bankAccount']),
			'id'=>'bankAccount',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankAccount = form_dynamic($bankAccount);
	$view .= form_field_display( $form_bankAccount  , "Account number"  );
	
	

	
	$transferLimit = array(
			'name'=>'transferLimit',
			'value'=>(isset($_POST['transferLimit'])? $_POST['transferLimit'] : $fields['transferLimit']),
			'id'=>'transferLimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_transferLimit = form_dynamic($transferLimit);
	$view .= form_field_display( $form_transferLimit  , "Transfer limit "  );
	
		 
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

function submit_players($id){
	 
	$datas = array(); 
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['username']	=  my_type_data_str($_POST['username']);
	 $datas['email']	=  my_type_data_str($_POST['email']);
	/* $datas['password']	=  my_type_data_str($_POST['password']);
	 $datas['avatar']	=  my_type_data_str($_POST['avatar']);
	 $datas['datecreated']	=  my_type_data_str($_POST['datecreated']);
	 $datas['lastlogin']	=  my_type_data_str($_POST['lastlogin']);
	 $datas['ipaddress']	=  my_type_data_str($_POST['ipaddress']);
	 $datas['sessname']	=  my_type_data_str($_POST['sessname']);
	 $datas['banned']	=  my_type_data_str($_POST['banned']);
	 $datas['approve']	=  my_type_data_str($_POST['approve']);
	 $datas['lastmove']	=  my_type_data_str($_POST['lastmove']);
	 $datas['waitimer']	=  my_type_data_str($_POST['waitimer']);
	 $datas['code']	=  my_type_data_str($_POST['code']);
	 $datas['GUID']	=  my_type_data_str($_POST['GUID']);
	 $datas['vID']	=  my_type_data_str($_POST['vID']);
	 $datas['gID']	=  my_type_data_str($_POST['gID']);
	 $datas['timetag']	=  my_type_data_str($_POST['timetag']);
	 $datas['deviceId']	=  my_type_data_str($_POST['deviceId']);
	 $datas['deviceToken']	=  my_type_data_str($_POST['deviceToken']);
	 $datas['myTime']	=  my_type_data_str($_POST['myTime']);
	 $datas['country']	=  my_type_data_str($_POST['country']);
	 $datas['bot']	=  my_type_data_str($_POST['bot']);
	 $datas['facebookId']	=  my_type_data_str($_POST['facebookId']);
	 $datas['walletAddress']	=  my_type_data_str($_POST['walletAddress']);
     */
	 $datas['bankName']	=  my_type_data_str($_POST['bankName']);
	 $datas['bankAccount']	=  my_type_data_str($_POST['bankAccount']);
	 $datas['transferLimit']	=  my_type_data_str($_POST['transferLimit']);
    /*
	 $datas['ts']	=  my_type_data_str($_POST['ts']);
	 $datas['rekening']	=  my_type_data_str($_POST['rekening']);
	 $datas['securePassword']	=  my_type_data_str($_POST['securePassword']);
	 $datas['language']	=  my_type_data_str($_POST['language']);
	 $datas['leagueId']	=  my_type_data_str($_POST['leagueId']);
	 $datas['fbAccessToken']	=  my_type_data_str($_POST['fbAccessToken']);
	 $datas['displayName']	=  my_type_data_str($_POST['displayName']);
	 $datas['vip']	=  my_type_data_str($_POST['vip']);
	 $datas['agent']	=  my_type_data_str($_POST['agent']);
	 $datas['membercard']	=  my_type_data_str($_POST['membercard']);
	 */
	if($id > 0){
		return my_update_record( 'players' , 'ID' , $id , $datas );
	}
    $id = mt_rand(10000000 , 99999999);
    $datas['ID']	=  my_type_data_int($id);
	return my_insert_record( 'players' , $datas );
}

function form_players_validate(){
	return false;
}
	