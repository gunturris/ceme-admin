<?php 
 
 
function page_block_design(){
 return 'Page web';
 my_set_code_css(
 '
 .title_welcome_box{
	font-size:22px;
	font-family:helvetica;
	margin-top:8px;
	margin-left:4px;
	padding-bottom:5px;
	border-bottom:2px solid;
 } 
 #calendar {margin:5px; width:98%;} 
 #calendar .th  {
	width:58px;
	text-align:center;
	background:brown;
	color:#fff;
	font-size:13px;
	padding-bottom:2px;
	padding-top:2px;
} 
 #calendar .month td {border: 1px solid grey;} 
 #calendar .tr {color:red;
 text-align:center;}  
 .td_empty{background-color:black}
 '
 );
 $design1 ='<div style="background:#fff;width:95%;height:100%;text-align:left;border:2px solid brown;padding:5px;">
 <br/>   
  asasdasd 
</div>';

$design2 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Data dan Summary</div><br/>'.data_sumary().'
 
</div>';


$design3 ='<div style="background:#fff;width:96%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Perbandingan gender</div>    <br/> <img src="index.php?com=graph&task=gender"   />
</div>';

$design4 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Periode lalu</div> <div id=\'calendar\'>'.display_caledar().'
<b><span style="color:blue">Biru untuk jumlah ijin</span><br/>
<span style="color:brown">Cokelat untuk jumlah dinas</span><br/>
<span style="color:orange">Orange untuk jumlah cuti</span>
</b></div>
</div>';
$design5 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Status seminggu akhir</div><br/> <img src="index.php?com=graph&task=bar_daily"   />
</div>';
 
$view = '<div style="width:930px;text-align:center;">';
$view .= '<div style="width: 305px;padding:2px; height: 270px;text-align:left;float:left;">'.$design1.'</div>';
$view .= '<div style="width: 305px;padding:2px; height: 270px;text-align:left;float:left;">'.$design2.'</div> ';
$view .= '<div style="width: 305px;padding:2px; height: 270px;text-align:left;float:right;">'.$design3.'</div> ';
$view .= '<div style="clear:both"></div><br/>';
$view .= '<div style="width: 460px;padding:2px; height: 380px;text-align:left;float:left;">'.$design4.'</div> '; 
$view .= '<div style="width: 460px;padding:2px; height: 380px;text-align:left;float:right;">'.$design5.'</div> '; 
$view .= '<div style="clear:both"></div>';
$view .= '</div>';

return $view;
} 

function data_sumary(){
$aaaa = '<table width="100%" cellpadding="3" cellspacing="0" border="0">';
$path = '../files/services/resume.json';  
$contents = (string) file_get_contents($path);  
$datas = json_decode($contents ,true);   
$aaaa .= detail_rows_view( '<span style="font-size:11px">Periode proses</span>' ,  ' <font size="1">'.$datas['periode_proses'] .'</font>' ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Jumlah Karyawan</span>' ,  ' <font size="1">'.$datas['jumlah_karyawan'].' Orang</font>'   ,false ,"55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Kehadiran kemarin</span>' , ' <font size="1">'.$datas['hadir_kemaren'].'%</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Perkiraan hadir besok</span>' ,' <font size="1">'.$datas['hadir_besok'].'%</font>'   ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Meeting hari ini</span>' , ' <font size="1">'.$datas['meeting_hari_ini'] .'</font>' ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Ruang meeting</span>' , ' <font size="1">'.$datas['ruang_meeting'].'</font>'  ,false , "55%", "45%");
$aaaa .= detail_footer_view();

return $aaaa; 
}

function display_caledar(){
	$objCalendar = new Calendar();
	 $objCalendar->SetCalendarDimensions("480px", "315px");
    ## *** set week day name length - "short" or "long"
    $objCalendar->SetWeekDayNameLength("long");
    ## *** set start day of week: from 1 (Sanday) to 7 (Saturday)
    $objCalendar->SetWeekStartedDay("1");
    ## *** set calendar caption 

    ## +---------------------------------------------------------------------------+
    ## | 3. Draw Calendar:                                                         | 
    ## +---------------------------------------------------------------------------+
    
   return  $objCalendar->Show();
}
  