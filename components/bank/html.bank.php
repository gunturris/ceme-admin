<?php

    function list_bank(){
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
		'Dealer' => array( 'width:25%;','text-align:left;' ), 
		'Bank name' => array( 'width:25%;','text-align:left;' ), 
		'Rake Take' => array( 'width:10%;','text-align:right;' ), 
		'Play Money' => array( 'width:10%;','text-align:right;' ), 
		'Total Payout' => array( 'width:12%;','text-align:right;' ), 
		'act'=>array('width:10%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM bank ";
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
		'dealerId' => $ey['dealerId'],  
		'bankName' => $ey['bankName'],  
		'rakeTake' => $ey['rakeTake'],  
		'playMoneyBank' => $ey['playMoneyBank'],  
		'playMoneyTotalPayout' => $ey['playMoneyTotalPayout'],  
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data bank' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_bank($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_bank" , "form_bank"  );
	$fields = my_get_data_by_id('bank','id', $id);


	
	$dealerId = array(
			'name'=>'dealerId',
			'value'=>(isset($_POST['dealerId'])? $_POST['dealerId'] : $fields['dealerId']),
			'id'=>'dealerId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_dealerId = form_dynamic($dealerId);
	$view .= form_field_display( $form_dealerId  , "Dealer"  );
	
	

	
	$bankName = array(
			'name'=>'bankName',
			'value'=>(isset($_POST['bankName'])? $_POST['bankName'] : $fields['bankName']),
			'id'=>'bankName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankName = form_dynamic($bankName);
	$view .= form_field_display( $form_bankName  , "Bank name"  );
	
	

	
	$rakeTake = array(
			'name'=>'rakeTake',
			'value'=>(isset($_POST['rakeTake'])? $_POST['rakeTake'] : $fields['rakeTake']),
			'id'=>'rakeTake',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rakeTake = form_dynamic($rakeTake);
	$view .= form_field_display( $form_rakeTake  , "Rake take"  );
	
	

	
	$playMoneyBank = array(
			'name'=>'playMoneyBank',
			'value'=>(isset($_POST['playMoneyBank'])? $_POST['playMoneyBank'] : $fields['playMoneyBank']),
			'id'=>'playMoneyBank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoneyBank = form_dynamic($playMoneyBank);
	$view .= form_field_display( $form_playMoneyBank  , "Play money"  );
	
	

	
	$playMoneyTotalPayout = array(
			'name'=>'playMoneyTotalPayout',
			'value'=>(isset($_POST['playMoneyTotalPayout'])? $_POST['playMoneyTotalPayout'] : $fields['playMoneyTotalPayout']),
			'id'=>'playMoneyTotalPayout',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoneyTotalPayout = form_dynamic($playMoneyTotalPayout);
	$view .= form_field_display( $form_playMoneyTotalPayout  , "Total payout"  );
	
		 
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

function submit_bank($id){
	 
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
		return my_update_record( 'bank' , 'id' , $id , $datas );
	}
	return my_insert_record( 'bank' , $datas );
}

function form_bank_validate(){
	return false;
}
	