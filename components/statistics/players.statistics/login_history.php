<?php
 
function list_statistic_login_history(){
    
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
		'Player name' => array( 'style' => 'width:35%;text-align:left;' ), 
		'Email' => array( 'style' => 'width:35%;text-align:left;' ), 
		'Last login' => array( 'style' => 'width:30%;text-align:center;' ),
         
	);

	
	
	$query 	= "select  * from players ORDER BY lastlogin DESC LIMIT 20 ";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'playername' => position_text_align( $ey['username'], 'left' ),
            'email' => position_text_align( $ey['email'], 'left' ),
            'lastlogin' => position_text_align( date('Y-m-d H:i' , $ey['lastlogin']) , 'center' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas ,3, false   ); 
}
