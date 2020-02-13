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
