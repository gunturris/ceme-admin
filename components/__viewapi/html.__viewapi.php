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
