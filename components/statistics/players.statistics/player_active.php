<?php
 
function list_statistic_player_active(){
    
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
		'Tanggal' => array( 'style' => 'width:25%;text-align:center;' ), 
		'Jumlah player' => array( 'style' => 'width:15%;text-align:center;' ), 
		'Desktop' => array( 'style' => 'width:15%;text-align:center;' ),
		'Mobile' => array( 'style' => 'width:15%;text-align:center;' ),
		'Android' => array( 'style' => 'width:15%;text-align:center;' ),
		'IOS' => array( 'style' => 'width:15%;text-align:center;' ),
         
	);

	
	
	$query 	= "select DATE(ts) as ts, SUM(bet) as bet from player_history  group by DATE(ts) LIMIT 20 ";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align(  $ey['ts'], 'center' ),
            'jumlah' => position_text_align( rp_format($ey['bet']), 'right' ), 
            'desktop' => position_text_align( 0 , 'right' ),
            'mobile' => position_text_align( 0 , 'right' ),
            'android' => position_text_align( 0 , 'right' ),
            'ios' => position_text_align( 0 , 'right' ),
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas ,3, false   ); 
}