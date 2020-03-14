<?php

function list_acounting_mega_jackpot(){
    
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
		'Table chip' => array( 'style' => 'width:20%;text-align:center;' ), 
		'Payout' => array( 'style' => 'width:20%;text-align:center;' ),
		'Added (+)' => array( 'style' => 'width:20%;text-align:center;' ),
		'Minus (-)' => array( 'style' => 'width:20%;text-align:center;' )
        
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