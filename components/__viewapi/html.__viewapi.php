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