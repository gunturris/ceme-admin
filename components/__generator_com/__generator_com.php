<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('__generator_com' );

$headers = array();
$headers['No'] = "'width'=>'5%','style'=>'text-align:center;'"; 
$headers['Penanggung jawab'] = "'width'=>'50%','style'=>'text-align:center;'"; 
$headers['Penerima/ Tujuan'] = "'width'=>'40%','style'=>'text-align:center;'";  
$headers['Perihal'] = "'width'=>'40%','style'=>'text-align:center;'";  
$headers['Tanggal'] = "'width'=>'5%','style'=>'text-align:center;'";  
$module_name = "surat_keluar";
 
generate_files($module_name,  $headers) ;
exit;