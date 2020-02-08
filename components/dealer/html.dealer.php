<?php

    function list_dealer(){
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
		'Bussines name' => array( 'width:25%;','text-align:left;' ), 
		'Join date' => array( 'width:15%;','text-align:center;' ), 
		'City' => array( 'width:10%;','text-align:left;' ), 
		'Bank name' => array( 'width:10%;','text-align:right;' ), 
		'Bank acc-no' => array( 'width:10%;','text-align:left;' ), 
		'Phone' => array( 'width:10%;','text-align:right;' ), 
		'Last login' => array( 'width:10%;','text-align:right;' ), 
		'act'=>array('width:10%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM dealers ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['dealerId'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['dealerId'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
            'busname' => $ey['busname'],  
            'datejoined' => $ey['datejoined'],  
            'city' => $ey['city'],  
            'bankName' => $ey['bankName'],  
            'accountNumber' => $ey['accountNumber'],  
            'phone' => $ey['phone'],  
            'lastlogin' => $ey['lastlogin'],  
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data dealer' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_dealer($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_dealer" , "form_dealer"  );
	$fields = my_get_data_by_id('dealers','dealerId', $id);


	
	$busname = array(
			'name'=>'busname',
			'value'=>(isset($_POST['busname'])? $_POST['busname'] : $fields['busname']),
			'id'=>'busname',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_busname = form_dynamic($busname);
	$view .= form_field_display( $form_busname  , "Bussines name"  );
	
	

	$fdatejoined = date('Y-m-d');
	if($fields){
		 $fdatejoined = date('Y-m-d' , strtotime($fields['datejoined']) );
	}
	
	$datejoined = array(
			'name'=>'datejoined',
			'value'=>(isset($_POST['datejoined'])? $_POST['datejoined'] : $fdatejoined),
			'id'=>'datejoined',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_datejoined = form_calendar($datejoined);
	$view .= form_field_display( $form_datejoined  , "Joined date" );
	

	
	$city = array(
			'name'=>'city',
			'value'=>(isset($_POST['city'])? $_POST['city'] : $fields['city']),
			'id'=>'city',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_city = form_dynamic($city);
	$view .= form_field_display( $form_city  , "City"  );
	
	

	
	$bankName = array(
			'name'=>'bankName',
			'value'=>(isset($_POST['bankName'])? $_POST['bankName'] : $fields['bankName']),
			'id'=>'bankName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankName = form_dynamic($bankName);
	$view .= form_field_display( $form_bankName  , "Bank name"  );
	
	

	
	$accountNumber = array(
			'name'=>'accountNumber',
			'value'=>(isset($_POST['accountNumber'])? $_POST['accountNumber'] : $fields['accountNumber']),
			'id'=>'accountNumber',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_accountNumber = form_dynamic($accountNumber);
	$view .= form_field_display( $form_accountNumber  , "Account number"  );
	
	

	
	$phone = array(
			'name'=>'phone',
			'value'=>(isset($_POST['phone'])? $_POST['phone'] : $fields['phone']),
			'id'=>'phone',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_phone = form_dynamic($phone);
	$view .= form_field_display( $form_phone  , "Phone number"  );
	
		 
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

function submit_dealer($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['rakeTake']	=  my_type_data_str($_POST['rakeTake']);
	 $datas['rakeRate']	=  my_type_data_str($_POST['rakeRate']);
	 $datas['tournamentTake']	=  my_type_data_str($_POST['tournamentTake']);
	 $datas['hiloTake']	=  my_type_data_str($_POST['hiloTake']);
	 $datas['scratchyTake']	=  my_type_data_str($_POST['scratchyTake']);
	 $datas['bank']	=  my_type_data_str($_POST['bank']);
	 $datas['playMoneyBank']	=  my_type_data_str($_POST['playMoneyBank']);
	 $datas['playMoneyPayoutBank']	=  my_type_data_str($_POST['playMoneyPayoutBank']);
	 $datas['playMoneyTotalPayout']	=  my_type_data_str($_POST['playMoneyTotalPayout']);
	 $datas['payoutRate']	=  my_type_data_str($_POST['payoutRate']);
	 $datas['payoutBank']	=  my_type_data_str($_POST['payoutBank']);
	 $datas['totalPayout']	=  my_type_data_str($_POST['totalPayout']);
	 $datas['totalPayoutCashout']	=  my_type_data_str($_POST['totalPayoutCashout']);
	 $datas['bankName']	=  my_type_data_str($_POST['bankName']);
	 $datas['bankWalletAddress']	=  my_type_data_str($_POST['bankWalletAddress']);
	 $datas['bankAccount']	=  my_type_data_str($_POST['bankAccount']);
	 $datas['ts']	=  my_type_data_str($_POST['ts']);
	 $datas['depositTotal']	=  my_type_data_str($_POST['depositTotal']);
	 $datas['cashoutTotal']	=  my_type_data_str($_POST['cashoutTotal']);
	 $datas['autoWithdrawLimit']	=  my_type_data_str($_POST['autoWithdrawLimit']);
	 $datas['minWithdrawLimit']	=  my_type_data_str($_POST['minWithdrawLimit']);
	 $datas['minDepositLimit']	=  my_type_data_str($_POST['minDepositLimit']);
	 $datas['currencySign']	=  my_type_data_str($_POST['currencySign']);
	 $datas['decimalPoints']	=  my_type_data_str($_POST['decimalPoints']);
	 $datas['chipToBitcoinRatio']	=  my_type_data_str($_POST['chipToBitcoinRatio']);
	 $datas['useJackpot']	=  my_type_data_str($_POST['useJackpot']);
	 $datas['jackpotFee']	=  my_type_data_str($_POST['jackpotFee']);
	 $datas['signupAward']	=  my_type_data_str($_POST['signupAward']);
	 $datas['hiloTakeGold']	=  my_type_data_str($_POST['hiloTakeGold']);
	 $datas['exchangeRate']	=  my_type_data_str($_POST['exchangeRate']);
	 $datas['dailyAwardChips']	=  my_type_data_str($_POST['dailyAwardChips']);
	 $datas['dailyAwardChipsVIP']	=  my_type_data_str($_POST['dailyAwardChipsVIP']);
	 
	if($id > 0){
		return my_update_record( 'bank' , 'dealerId' , $id , $datas );
	}
	return my_insert_record( 'bank' , $datas );
}

function form_dealer_validate(){
	return false;
}
	