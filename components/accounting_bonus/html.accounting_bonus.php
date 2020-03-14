<?php


function list_acounting_bonus(){
    
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
		'Tournament' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Lucky wheel' => array( 'style' => 'width:25%;text-align:center;' ),
		'Random bonus' => array( 'style' => 'width:25%;text-align:center;' ), 
        
	);
    
    $query 	= "SELECT * FROM debits WHERE approved=1 LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 
 
		$row[] = array( 
            'date'    => position_text_align( 0, 'center' ),
            'tournament'    => position_text_align(0, 'center' ),
            'lwheel'       => position_text_align(0, 'center' ), 
            'random'       => position_text_align(0, 'center' ), 
            
		);
	}
	
	$datas = table_rows($row);
    
	return table_builder($headers , $datas , 9, false   ); 
}