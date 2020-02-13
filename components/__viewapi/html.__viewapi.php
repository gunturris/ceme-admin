<?php 

function dropdown_multi_rows_extends_dua( $parent , $child , $file_combo , $opsi_pilihan   ){

	my_set_file_js(array( 
		'components/system/jquery/combomulti/jquery.chainedSelects.js'
	));  
	my_set_code_js(' 
	$(function()
	{  
		$(\'#'.$parent['id'].'\').chainSelect(\'#'.$child['id'].'\',\''.$file_combo.'\',
		{ 
			before:function (target) 
			{ 
				$("#'.$child['id'].'loading").css("display","block");  
				$("#'.$child['id'].'default").css("display","none"); 
				$(target).css("display","none");
			},
			after:function (target) 
			{ 
				$("#'.$child['id'].'loading").css("display","none");  
				$("#'.$child['id'].'default").css("display","none"); 
				$(target).css("display","inline");
			},
			parameters : {\'tingkat\' : $("#tingkat").val() }
		}); 
		settings.parameters.tingkat =  $("#tingkat").val();
	});
	' );
	my_set_code_css('
	#'.$child['id'].'default
		{  
			background:#ff0000;
			color:#fff;
			font-size:14px;
			font-familly:Arial;
			padding:2px; 
			display:block;
			float:left;
		} 
	
	#'.$child['id'].'loading
		{  
			background:#ff0000;
			color:#fff;
			font-size:14px;
			font-familly:Arial;
			padding:2px; 
			display:none;
			float:left;
		} 
	'); 

	
	if($opsi_pilihan ){
	}else{
		$opsi_pilihan='<option>[- Pilih atas dulu -]</option>' ;
	}	
	$vi = '
	<span id="'.$child['id'].'loading" style="float:left;">Loading ...</span>
	<select name="'.$child['name'].'" id="'.$child['id'].'">
	'.$opsi_pilihan.'
	</select>' ;
	return $vi;	
}
 

function jam_formulir($name , $value){
	$datas = array(); 
	for( $i=0; $i<=23; $i++ ){
		$n=0;
		while($n <= 45){
			$datas[] = sprintf('%02d',$i).':'. sprintf('%02d',$n);
			$n +=  15;
		}
	} 
	if(!$value){
		$value = date('H').':00';
	}else{
		$value = substr($value,0,5);
	} 
	//$text = '<select name="'.$name.'"  scrolling="yes" multiple="multiple">';
	$text = '<select name="'.$name.'"  scrolling="yes" >';
	foreach($datas as $data){
		if($value == $data)
			$text .= '<option value="'.$data.'" selected >'.$data.'</option>'."\n";
		else
			$text .= '<option value="'.$data.'">'.$data.'</option>'."\n";
	}
	$text .= '</select>';
	return $text;
}
 
function rp_format($number){
	return  number_format( (int) $number, 0,
	',',
	'.') ;
}
 