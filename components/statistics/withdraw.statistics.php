<?php


function list_statistic_withdraw(){
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
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Withdraw' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	
	$query 	= "SELECT DATE(ts) as ts, count(*)as cnt FROM `credits` where valid='1' group by DATE(ts) LIMIT 15";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['cnt']), 'right' ),  
		);
	}
	
	$datas = table_rows($row);
    
	$navigasi =statistic_header_box();
	$box = header_box( 'Withdraw sumary' , $navigasi ); 
	return table_builder($headers , $datas , 9, false   ); 
}
