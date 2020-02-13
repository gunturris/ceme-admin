<?php 


//New Function
function get_range_date_of_month($year , $month){
    $initDate = $year."-".$month."-01";
      
    $datas = array();
    
    $datas['start_date'] = date("Y-m-d" , strtotime($initDate ));
    $datas['end_date'] = date("Y-m-t" , strtotime($initDate ));
    return $datas;
}

 