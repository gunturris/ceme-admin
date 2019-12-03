<?php

function user_overtime_detail($finger_id){
    
    my_set_code_js( " 
    
    
$(document).ready(function() {	
	
	/**
	 * store the value of and then remove the title attributes from the
	 * abbreviations (thus removing the default tooltip functionality of
         * the abbreviations)
	 */
	$('abbr').each(function(){		
		
		$(this).data('title',$(this).attr('title'));
		$(this).removeAttr('title');
	
	});

        /**
	 * when abbreviations are mouseover-ed show a tooltip with the data from the title attribute
	 */	
	$('abbr').click(function() {		
		
		// first remove all existing abbreviation tooltips
		$('abbr').next('.tooltip').remove();
	    eval('var tx='+ $(this).data('title') );
        var tx_json = JSON.stringify(tx) 
        console.log(tx_json)
        resp = JSON.parse(tx_json)
        console.log(resp)
        
        if( resp.s == '1'){
            sts = 'start';
        }else{
            sts = 'end';
        }
        
		// create the tooltip
		$(this).after('<span class=\"tooltip\"><b>OT '+ sts +' time on '+resp.date+':</b>  <table width=\"\"><tr><td width=\"60%\" align=\"right\"><input type=\"textfield\" id=\"check_'+resp.date+'_'+sts+'\" value=\"'+resp.time+'\" size=\"7\"></td><td width=\"20%\"><input type=\"button\" value=\"GO\" onclick=\"javascript:updateOvertimeStart( '+ $(this).data('title')+' , \''+sts+'\' )\" /></td><td width=\"20%\"><input type=\"button\" value=\" X \" onclick=\"javascript:closeTooltip()\" /></td></tr></table></span>');
		
		// position the tooltip 4 pixels above and 4 pixels to the left of the abbreviation
		var left = $(this).position().left + $(this).width() + 4;
		var top = $(this).position().top - 4;
		$(this).next().css('left',left);
		$(this).next().css('top',top);				
		
	});
	
	 
	$('abbr_close').click(function(){
			
		$(this).next('.tooltip').remove();				

	});	
	 
});

function updateOvertimeStart(vk , sts){
    console.log(vk)
    console.log(sts)
    vcxtime = $('#check_'+ vk.date +'_'+sts ).val()
     
    $.get( 'ajax_update_overtime.php' , { 
            s: vk.s , 
            finger_id:{$finger_id}, 
            date:  vk.date  , 
            time:  vcxtime  , 
            id: {$_GET['id']} 
        } , 
        function( data ) {
            console.log(sts)
            if( sts == 'start' )
                $('#t_start_'+ vk.date  ).html(data)
            else
                $('#t_end_'+ vk.date  ).html(data)
            console.log(data) 
        }
    );
    
    $('abbr').next('.tooltip').remove();
}

function resetOvertimeActivity( date , finger_id ){ 

    vconf = confirm('Are you sure to reset?')
    if( vconf ){
        $.get( 'ajax_update_overtime.php' , { 
                date: date , 
                finger_id:{$finger_id} , 
                reset:1

            } , 
            function( data ) {
                $('#t_start_'+  date  ).html('00:00')
                $('#t_end_'+  date  ).html('00:00')
                console.log(data) 
            }
        );
    }
    return
}

function closeTooltip( ){
    $('abbr').next('.tooltip').remove(); 

}
    ");
    
my_set_code_css(
"
.tooltip
{
	position:absolute;
	background-color:#eeeefe;
	border: 1px solid #aaaaca;
	font-size: smaller;
	padding:4px;
	width: 180px;
	box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);	
}"
);    
    global $box;
    $navigasi = array(
		'<input class="submit-green" type="button" value="Print" onclick="javascript:;"/>',
	 	'<input class="submit-green" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&id='.$finger_id.'\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi );
    
    $emp = my_get_data_by_id('emp' , 'finger_id' , $finger_id );
    $unit =  my_get_data_by_id('departments' , 'departemen_id' , $emp['departemen_id'] );
    $shift =  my_get_data_by_id('shift_group' , 'shift_id' , $emp['shift_id'] );
    
	$periode_year = $_SESSION['periode_year'];
	$periode_month = $_SESSION['periode_month'];
    $periode = "{$periode_year}-{$periode_month}-01";
    
    $view = '
    <table width="100%">
        
        <tr>
            <td width="15%" style="border-right:0"><b>Employee ID</b></td>
            <td width="84%">'. $emp['nik'] .' / '. $emp['realname'] .' </td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Unit</b></td>
            <td>'. $unit['departemen_code'] .' / '. $unit['departemen_name'] .'</td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Shift</b></td>
            <td>'. $shift['shift_code'] .' / '. $shift['shift_group_name'] .'</td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Periode</b></td>
            <td>'. date("m - Y" , strtotime($periode)).'</td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Total OT hours</b></td>
            <td>'. get_total_ot_periode($periode , $finger_id) .'</td>
        </tr>
    
    </table>
    ';
    return $view . list_overtime_user($finger_id ,  $periode );
}

function list_overtime_user($finger_id , $periode){ 
    $header = array( 
		'Date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'Attendance'=>array('style'=>'text-align:center;border-bottom:2px solid;width:13%'),        
		'OT Start'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'OT End'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'Overtime'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'Hours'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),   
		'Remark'=>array('style'=>'text-align:center;border-bottom:2px solid;width:32%'),   
	//	'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:5%'),   
	);
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    
    $month_dates = get_range_date_of_month( $periode_year ,$periode_month );
    $overtimes = get_detail_overtime_emp($finger_id , $month_dates['start_date'] , $month_dates['end_date']);
    $dates = list_kalender( $month_dates['start_date'] , $month_dates['end_date'] );
    
    $row = array();
    foreach( $dates as $date ){
        $nday = date('N' , strtotime($date) );
        
        $d = date('d' , strtotime($date) );
        $ot = get_total_time_micro( $overtimes[$d]['ot_start_time'] , $overtimes[$d]['ot_end_time']);   //fake
        
        if( is_null($overtimes[$d]['time_out'] ))
            $attendance = "00:00 - 00:00" ;
        else
            $attendance = date("H:i" , strtotime($overtimes[$d]['time_in'] )) ." - ".  date("H:i" , strtotime( $overtimes[$d]['time_out'] ));
        
        $start_ot_init = '<span id="t_start_'.$date.'">' . ( is_null($overtimes[$d]['ot_start_time']) ? "00:00" : date("H:i" , strtotime($overtimes[$d]['ot_start_time'] )) ) . '</span>';
        $start_ot  = $start_ot_init . '&nbsp; - &nbsp; 
        <abbr title="{uid:'.$finger_id.',date:\''.$date.'\',time:\''.date("H:i" , strtotime($overtimes[$d]['ot_start_time'] )).':00'.'\' , s :1 }"><a href="javascript:;">Edit</a></abbr> 
        <!-- a href="javascript:resetOvertimeActivity(\''.$date.'\' , \''. $finger_id .'\' , \'start\');">Void</a -->';
         
        $end_ot_init = '<span id="t_end_'.$date.'">' . ( is_null($overtimes[$d]['ot_end_time']) ? "00:00" :  date("H:i" , strtotime($overtimes[$d]['ot_end_time'] )) ) . '</span>';
        $end_ot  = $end_ot_init . '&nbsp; - &nbsp; 
        <abbr title="{uid:'.$finger_id.',date:\''.$date.'\',time:\''.date("H:i" , strtotime($overtimes[$d]['ot_end_time'] )).':00'.'\' , s :2 }"><a href="javascript:;">Edit</a></abbr> 
        <!-- a href="javascript:resetOvertimeActivity(\''.$date.'\' , \''. $finger_id .'\' , \'end\');">Void</a //-->';
		$reset_link ='<a href="javascript:resetOvertimeActivity(\'' .$date.'\' , \''. $finger_id .'\'  );">Reset</a>';
        $remark =  get_remark($finger_id , $date);
        $row[] = array(
                'Date'  =>  date('d/m/Y' , strtotime($date) )  , 
                'Attendance'   => position_text_align(  $attendance , 'center'),  
                'Start'   => position_text_align(  /* $start_ot */ $start_ot_init , 'center'),  
                'End'   => position_text_align( /* $end_ot  */ $end_ot_init , 'center'),  
                'Overtime'   => position_text_align(  $ot , 'center'),  
                'Hour'   => position_text_align ( time_to_decimal($ot) , 'center'),  
                'Remark'=> '<span id="remark_'.$date.'">'.$remark.'</span>',
        //        'Act'   => position_text_align ( 'X' , 'center'), 
            );
        
    }
    $datas = table_rows($row);
	return table_builder( $header , $datas , 4 , false );  
    
}

function get_remark($finger_id , $date){
    return "";
}
 

function get_detail_overtime_emp($finger_id , $start_date , $end_date ){
    $query = "SELECT * FROM overtime_log
                WHERE emp_finger_id = {$finger_id}
                AND ( overtime_date BETWEEN '{$start_date}' AND '{$end_date}' )  ";
	 
    $result = my_query($query);
    if( my_num_rows($result) > 0){
        $datas = array();
        while( $row = my_fetch_array($result) ){
            $day = date('d' , strtotime($row['overtime_date']) );
            $datas[$day] = $row; 
        }
        return $datas;
    }
    
    return false;
}

function get_total_time_micro($value1 ,$value2 ){
      
	$start = strtotime($value1);
	$end = strtotime($value2);
	
	$total = $end - $start;
	$hours = floor($total / 3600 );
	$minutes = round( ($total - ($hours * 3600) ) / 60 );
	$hours = sprintf('%02d' , $hours);
	$minutes = sprintf('%02d' , $minutes);
	return   "{$hours}:{$minutes}:00"; 
}
