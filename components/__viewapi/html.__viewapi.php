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

 
function symbol_breadcumb(){
	return '&nbsp; '.button_icon( 'b_nextpage.png' ,array() ).' &nbsp; ';
}

function button_icon( $icon , $urlproperty = false , $buttonproperty=false ){
	$view ='';
	if($urlproperty){
		if(! is_array($urlproperty) ) return false;
			if($urlproperty)
				$view .='<a ';
			
			foreach($urlproperty as $key=>$value){
				$view .= $key . '="' . $value.'" ';
			}
				
			if($urlproperty)
				$view .='>';
	}	
	
		$view .='<img style="margin:0 1px 0 2px;" width="14px" src="icon/'.$icon.'" border="0" ';
		if($buttonproperty){
			if(is_array($buttonproperty)){
				foreach($buttonproperty as $bkey=>$bvalue){
					$view .= $bkey . '="' . $bvalue.'" ';
				}
			}
		}
		$view .='/>';
	if($urlproperty) $view .='</a>';

	return $view;
}
function big_button_icon( $icon , $urlproperty = false , $buttonproperty=false ){
	$view ='';
	if($urlproperty){
		if(! is_array($urlproperty) ) return false;
			if($urlproperty)
				$view .='<a ';
			
			foreach($urlproperty as $key=>$value){
				$view .= $key . '="' . $value.'" ';
			}
				
			if($urlproperty)
				$view .='>';
	}	
	
		$view .='<img style="border:1px solid green;" src="http://'.$_SERVER['HTTP_HOST'].'/assets/icons/'.$icon.'" border="0" ';
		if($buttonproperty){
			if(is_array($buttonproperty)){
				foreach($buttonproperty as $bkey=>$bvalue){
					$view .= $bkey . '="' . $bvalue.'" ';
				}
			}
		}
		$view .='/>';
	if($urlproperty) $view .='</a>';

	return $view;
}

function button_icon_besar( $icon , $urlproperty = false , $buttonproperty=false ){
	$view ='';
	if($urlproperty){
		if(! is_array($urlproperty) ) return false;
			if($urlproperty)
				$view .='<a ';
			
			foreach($urlproperty as $key=>$value){
				$view .= $key . '="' . $value.'" ';
			}
				
			if($urlproperty)
				$view .='>';
	}	
	
		$view .='<img style="margin:0 1px 0 2px;" width="22px" src="http://'.$_SERVER['HTTP_HOST'].'/assets/icons/'.$icon.'" border="0" ';
		if($buttonproperty){
			if(is_array($buttonproperty)){
				foreach($buttonproperty as $bkey=>$bvalue){
					$view .= $bkey . '="' . $bvalue.'" ';
				}
			}
		}
		$view .='/>';
	if($urlproperty) $view .='</a>';

	return $view;
}
 
function hari_kerja_lembur($karyawan_id , $date){
	return false;
}

function is_hari_libur($date){

	

	//CHECK SABTU/MINGGU
	$daycode = date( 'w',strtotime( $date));
	$libur = array('0','6');
	if(in_array( $daycode , $libur)){
		return true;
	}
	
	//CHECK HARI BESAR
	$query = "SELECT * FROM global_hari_libur WHERE tanggal = '{$date}' ";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		return true;
	}
	return false;
}

function tab_page($tab_options , $contents ){

	if(! is_array($tab_options)) return false;
	my_set_code_css(
	'/*h1 {font-size: 3em; margin: 20px 0;}*/
.containerx {width: 900px; height:250px; margin: 10px auto;}
ul.tabs {
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 25px;
	border-bottom: 1px solid #999;
	border-left: 1px solid #999;
	width: 100%;
}
ul.tabs li {
	float: left;
	margin: 0;
	padding: 0;
	height: 24px;
	line-height: 24px;
	border: 1px solid #999;
	border-left: none;
	margin-bottom: -1px;
	background: #000;
	overflow: hidden;
	position: relative; 
}
ul.tabs li a {
	text-decoration: none;
	color: #fff;
	display: block;
	font: 15px arial; 
	font-weight:bold;
	padding: 0 20px;
	border: 1px solid #e0e0e0;
	outline: none;
	height:22px;

}
ul.tabs li a:hover {
	background: #fff;
	color: #000;
}	
html ul.tabs li.active,  html ul.tabs li.active a:hover  {
	background: #e0e0e0;
	border-bottom: 1px solid #e0e0e0; 
}
html ul.tabs li.active a{
	color:#000;
}
.tab_container {
	border: 1px solid #999;
	border-top: none;
	clear: both;
	float: left; 
	width: 100%;
	background: #e0e0e0;
	-moz-border-radius-bottomright: 5px;
	-khtml-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-khtml-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.tab_content {
	padding: 20px;
	font-size: 1.2em;
}
.tab_content h2 {
	font-weight: normal;
	padding-bottom: 10px;
	border-bottom: 1px dashed #ddd;
	font-size: 1.8em;
}
.tab_content h3 a{
	color: #254588;
} 
');

$sestab = isset($_SESSION['tab_page']) ? '"'. $_SESSION['tab_page'] .'"': '"ul.tabs li:first"';
$sestabcon = isset($_SESSION['tab_page']) ? '"'. $_SESSION['tab_page'] .'"': '".tab_content:first"';
	my_set_code_js('
	$(document).ready(function() {

		//Default Action
		$(".tab_content").hide(); //Hide all content
		//$("ul.tabs li#tab3").addClass("active").show(); //Activate first tab
		//$(".tab_content:first").show(); //Show first tab content
		$('.$sestab.').addClass("active").show(); //Activate first tab
		$('.$sestabcon.').show(); //Show first tab content
		
		//On Click Event
		$("ul.tabs li").click(function() {
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active content
			return false;
		});

	}); 
	');
	
	$view = '<div class="containerx">
<ul class="tabs">';
		foreach($tab_options as $label=>$ref){
        $view .='<li><a href="#'. $ref .'">'. $label .'</a></li> '; 
		}
    $view .= '</ul>'."\n";
	
	$view .= '<div class="tab_container">'."\n";
	foreach($contents as $tabref=>$content){
		$view .= '<div id="'.$tabref.'" class="tab_content">'; 
		$view .= $content;
		$view .='</div>'."\n";
	}
	$view .= '</div></div>'."\n";
	
	unset($_SESSION['tab_page']);
	return $view;
}

function iframe_page( $page , $height ){
	$view .= '<IFRAME src="'.$page.'" MARGINWIDTH="0"  MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING=AUTO WIDTH="840" HEIGHT="'.$height.'">test</IFRAME> ';
	return $view;
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
 