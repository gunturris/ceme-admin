<?php

function list_transaction_deposit(){
    
    
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
		'Dealer' => array( 'style' => 'width:20%;text-align:center;' ), 
		'Player' => array( 'style' => 'width:20%;text-align:center;' ), 
		'Transaction' => array( 'style' => 'width:10%;text-align:right;' ),
		'Bank' => array( 'style' => 'width:15%;text-align:right;' ),
		'Amount' => array( 'style' => 'width:5%;text-align:right;' ),
		'Message' => array( 'style' => 'width:30%;text-align:right;' ),
        
	);
    
    $query 	= "SELECT * FROM debits WHERE approved=1 LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 

        $dealer = my_get_data_by_id( 'dealer' , 'dealerId' , $ey['dealerId'] ); 
        $player = my_get_data_by_id( 'players' , 'ID' , $ey['playerId'] ); 
		$row[] = array( 
            'dealer'    => position_text_align($dealer['busname'], 'center' ),
            'player'    => position_text_align($player['username'], 'center' ),
            'trx'       => position_text_align( $ey['tranId'] , 'center' ),
            'bank'      => position_text_align( $ey['amount'] , 'left' ),
            'betamount' => position_text_align( rp_format($ey['amount']),  'right'),
            'message'   => $ey['message'],  
		);
	}
	
	$datas = table_rows($row);
    
	return table_builder($headers , $datas , 9, false   ); 
    
}