<?php


function list_acounting_game(){
    
    
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
		'Date' => array( 'style' => 'width:20%;text-align:center;' ), 
		'Deposit' => array( 'style' => 'width:20%;text-align:center;' ), 
		'Withdraw' => array( 'style' => 'width:20%;text-align:center;' ),
		'Rake' => array( 'style' => 'width:20%;text-align:center;' ),
		'Chip' => array( 'style' => 'width:20%;text-align:center;' )
        
	);
    
    $query 	= "SELECT * FROM debits WHERE approved=1 LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 
 
		$row[] = array( 
            'date'    => position_text_align( 0, 'center' ),
            'deposit'    => position_text_align(0, 'center' ),
            'withdraw'       => position_text_align(0, 'center' ),
            'rake'      => position_text_align( $ey['amount'] , 'left' ),
            'chip' => position_text_align( rp_format($ey['amount']),  'right'),
            
		);
	}
	
	$datas = table_rows($row);
    
	return table_builder($headers , $datas , 9, false   ); 
    
}

function list_acounting_game_old(){
    
    
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