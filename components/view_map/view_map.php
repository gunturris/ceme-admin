<?php

my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('view_map' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;

$modulname = '<li>Anda berada di:</li>
<li>&nbsp; Layanan &nbsp; </li>
<li>&nbsp; Tampilan ketersediaan tukang cukur &nbsp; </li>';

if($task == 'view_popup_page'){
	$content = detail_info_kursi_id($id);
	generate_my_web($content, '' , 'plain.php');
	exit;
}
elseif($task == 'selesai'){
	
	$datas = array( 
			'datetime_updated'	=> my_type_data_function('NOW()'), 
			'status_cukur'		=> my_type_data_str('SELESAI'), 
			'selesai_cukur'		=>  my_type_data_function('NOW()') 
		);
	my_update_record('registrasi_paket_item' ,'registrasi_paket_item_id' , $id , $datas);
	
	my_direct($_SERVER['HTTP_REFERER']);
}
elseif($task == 'mulai'){
	 
	$datas = array( 
			'datetime_updated'	=> my_type_data_function('NOW()'), 
			'status_cukur'		=> my_type_data_str('KERJA'), 
			'mulai_cukur'		=>  my_type_data_function('NOW()')
		);
	my_update_record('registrasi_paket_item' ,'registrasi_paket_item_id' , $id , $datas);
	my_direct($_SERVER['HTTP_REFERER']);
}
elseif($task == 'newwin'){
	$title = "Detail info penggunaan kursi";
	facebox_page('index.php?com='.$_GET['com'].'&task=view_popup_page&id='.$id , $title , 210	); 
	
}else{
	load_facebox_script();
	$content = map_view();
}
generate_my_web($content, $modulname );
?>