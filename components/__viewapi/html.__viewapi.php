<?php 


//New Function
function get_range_date_of_month($year , $month){
    $initDate = $year."-".$month."-01";
      
    $datas = array();
    
    $datas['start_date'] = date("Y-m-d" , strtotime($initDate ));
    $datas['end_date'] = date("Y-m-t" , strtotime($initDate ));
    return $datas;
}


function is_time_format($time){
    return preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time);
}
 
function additional_menu_on_list( $tombols , $text =false ,$property_table = false){
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'1',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '<table width="99%" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";
	$view .= '<tr><td align="left" width="40%" valign="bottom">';
	if($text){
		$view .= $text;
	}else{
		$view .='&nbsp;';
	}
	$view .= '</td><td align="right" width="60%" valign="top">';
		foreach($tombols as $label=>$subdatas){
			$view .= '<input type="button" class="main_button" value= "'.$label.'" ';
			if(! is_array($subdatas))return false;
			foreach($subdatas as $ds=>$pr){
				$view .= $ds .'="'. $pr .'" ';
			}			
			$view .='/> ';
		}
	$view .= '</td></tr>'."\n";
	$view .= '</table>'."\n";
	return $view;
}

function button_menu_on_top($datas,$left=false){

	$view ='<div id="menu-top" align="right">';
	if($left)$view .='<div style="float:left;width:350px;text-align:left;">'.$left.'</div>';
	foreach( $datas as $label=>$subdatas){
		$view .= '<div id="eachmenu"><a href="javscript:;" ';
			if(! is_array($subdatas))return false;
			foreach($subdatas as $ds=>$pr){
				$view .= $ds .'="'. $pr .'" ';
			}
		$view .= '>'.strtoupper($label).'</a></div>';
	}
	$view .='<div style="clear:both"></div></div>';
	return $view;
}
 
function message_plainpage($message){
	generate_my_web("<center><b>{$message}</b></center>","","plain.php");
	exit;
}


function detail_header_view(  $label , $fields = array() ,$navigasi = false){ 
	$button = '';
	if(is_array($navigasi)){ 
		foreach($navigasi as $tombol){
			$button .= $tombol;
		} 
	}
	$viewed = '
	<div class="box-head">
			'.$label.'
			<div class="right">'.$button.'</div>
		</div>
	<div class="table_form">

	<table width="100%"   style="border-collapse:collapse;border-color:white" cellspacing="0" cellpadding="2">
	'; 

	foreach($fields as $key=>$value){	
	$key_text	= ucfirst(str_replace('_',' ', $key));
	$value_text = $value;
	$viewed .='
	<tr bgcolor="" style="_border-top: 1px solid #CDCDCD;_border-bottom: 1px solid #CDCDCD;border-top: 1px solid #CDCDCD;border-bottom: 1px solid #CDCDCD; ">
		<td width="25%" class="label_form" valign="top" style="padding:4px"><span class="head_detail_label" >'.$key_text.'</span></td>
		<td width="75%" class="data_form" style="padding:4px">'.$value_text.'</td>
	</tr>
	';
	}
	
	$viewed .= '
	</table>
	</div><br/>
	';
	return $viewed;
}

function company_header_cetak($name){
$viewed = ' 
	<table width="780">
		<tr>
			<td width="50%"><span style="font-size:16px;"> LAPORAN '.$name.'</span></td>
			<td width="50%" align="right"><span style="font-size:11px;">Tanggal cetak: 
			'.date('d-m-Y').'</span></td> 
		</tr>
		<tr>
			<td colspan="2"><span style="font-size:24px;"> PT. Glorindo Fileatex</span></td>
		</tr>
		<tr>
			<td colspan="2" style="border-bottom:2px solid #000;"><span style="font-size:12px;"> Jl. MH. Thamrin Kav. 8-9 <br/>
			Kebon Melati, Tanah Abang Jakarta Pusat <br/>DKI Jakarta, Indonesia</span></td>
		</tr>
	</table><br/> 
	'; 
	return $viewed ;
}

function detail_header_view_cetak(  $label , $fields ,$navigasi = false){ 
	$button = '';
	if(is_array($navigasi)){ 
		foreach($navigasi as $tombol){
			$button .= $tombol;
		} 
	}
	$viewed = ' 
	<table width="780"   style="border-collapse:collapse;border-color:white" cellspacing="0" cellpadding="2">
	'; 

	foreach($fields as $key=>$value){	
	$key_text	= ucfirst(str_replace('_',' ', $key));
	$value_text = $value;
	$viewed .='
	<tr>
		<td width="25%"><b>'.$key_text.'</b></td>
		<td width="75%">'.$value_text.'</td>
	</tr>
	';
	}
	
	$viewed .= '
	</table>
	 <br/>
	';
	return $viewed;
}

function detail_rows_view($label , $value ,$merge = false , $a="30%" , $b="68%"){
	if($merge)
	$view  ='
	<tr  style="height:28px; border-top: 1px solid;border-bottom: 1px solid #CDCDCD;border-top: 1px solid #CDCDCD; ">
		<td colspan="2" width="100%"   valign="top" style="padding:4px">'. ucfirst($label). '</td> 
	</tr>';

	else
	$view  ='
	<tr  style="height:28px; border-top: 1px solid;border-bottom: 1px solid #CDCDCD;border-top: 1px solid #CDCDCD; ">
		<td width="'.$a.'"  valign="top" style="padding:4px;font:12px verdana"><b>'. ucfirst($label) .'</b></td>
		<td width="'.$b.'"style="padding:4px;font:12px verdana">'.$value . ' </td>
	</tr>';
	return $view;
}

function detail_footer_view(){
	 $viewed = '
	  </table> ';
	return $viewed;
}

function rupiah_format($number){
	return  number_format($number, 2, ',', '.') ;
}

 

function my_set_code_js_jquery($code){
	global $js_jquery_code;
	if(isset($js_jquery_code))$js_jquery_code .= $code ;
	else $js_jquery_code=$code;
	if(!defined('JS_JQUERY_CODE'))define('JS_JQUERY_CODE' , $code );
	return $js_jquery_code;
}
  
 
function rupiah_terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return rupiah_terbilang($x - 10) . "belas";
  elseif ($x < 100)
    return rupiah_terbilang($x / 10) . " puluh" . rupiah_terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . rupiah_terbilang($x - 100);
  elseif ($x < 1000)
    return rupiah_terbilang($x / 100) . " ratus" . rupiah_terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . rupiah_terbilang($x - 1000);
  elseif ($x < 1000000)
    return rupiah_terbilang($x / 1000) . " ribu" . rupiah_terbilang($x % 1000);
  elseif ($x < 1000000000)
    return rupiah_terbilang($x / 1000000) . " juta" . rupiah_terbilang($x % 1000000);
}
 

function print_report_button_script($pemeriksaan ){
	my_set_code_js('
function prinrReport(i){
	var lnxopen = \'index.php?com=report&task='.$pemeriksaan.'&id=\' + i;
	window.open(lnxopen,\'mywin\',\'left=20,top=20,width=800,height=600,toolbar=0,resizable=0\');
}	
	'); 
}

function print_report_button($pemeriksaan , $pemeriksaan_id){
	$fields = my_get_data_by_id($pemeriksaan, 'pemeriksaan_id' ,$pemeriksaan_id);
	$print_button = ' <img src="templates/icons/printer.gif" border="0"/>';
	if($fields)
	$view = '<a href="javascript:prinrReport('.$pemeriksaan_id.')">'. $print_button .'</a>';
	else
	$view = '<a href="javascript:alert(\'Data belum tersedia\')">'. $print_button .'</a>';
	return $view;
}

function notice_text( $value , $normal ){
	if(value_is_between( $value , $normal )){
		if($value == "+" OR $value =="-"){
			$value= label_positif_negatif($value);
		}
		return $value;
	}
	if($value == "+" OR $value =="-"){
		$value= label_positif_negatif($value);
	}
	return '<span style="color:red">'.$value.'</span>';
}
  
 
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
 