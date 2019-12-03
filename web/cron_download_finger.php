<?php 
ini_set("display_error",0);
$conn = mysql_connect('localhost','root','');
mysql_select_db('mesin_log');

function connection_download($host , $Key , $port = "80" ){
	$Connect = fsockopen($host, $port, $errno, $errstr, 1); 
  
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
		fputs($Connect, "Content-Type: text/xml".$newLine);
		fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
		fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		} 
		return $buffer;
	}
	return false;
}

function parse_data($data,$p1,$p2){
	$data=" ".$data;
	$hasil="";
	$awal=strpos($data,$p1);
	if($awal!=""){
		$akhir=strpos(strstr($data,$p1),$p2);
		if($akhir!=""){
			$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
		}
	}
	return $hasil;	
}
 
function save_data_download($mesin_lokasi_id , $host , $key , $port , $conn){ 
	$parseContent = connection_download($host , $key , $port); 
		 
	$buffer=parse_data($parseContent,"<GetAttLogResponse>","</GetAttLogResponse>"); 
	$buffer=explode("\n",$buffer);
	$recs =0;
	 
	for($a=0;$a<count($buffer);$a++){
		$data	= 	Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN	=	Parse_Data($data,"<PIN>","</PIN>");
		$DateTime = Parse_Data($data,"<DateTime>","</DateTime>");
		//$Verified =	Parse_Data($data,"<Verified>","</Verified>");
		$Status = Parse_Data($data,"<Status>","</Status>"); 
		print("Status:" .$Status." - NIK?PIN: " . $PIN." | Waktu absen :" . $DateTime. "\n");
		if(! check_data_exists($PIN , $DateTime  , $conn )){ 
			record_each_data($mesin_lokasi_id , $PIN , $DateTime , $conn );
			$recs++;
		}
		 
	} 
	return $recs;
}

function record_each_data($mesin_lokasi_id , $finger_id , $datetime_log , $conn ){
	$query = "INSERT INTO swaptime SET created_on = NOW() , created_by = 'ACCKITLOG' , data_source = '0' , ";
	$query .= " datetime_added = NOW() , synch_status = '0' , version = 0 ,   ";
	$query .= " mecine_finger_id =  '{$mesin_lokasi_id}' , finger_id = '{$finger_id}' , swap_datetime_log = '{$datetime_log}'  ";
	print($query ."\n\n");
	return mysql_query($query , $conn );  
}

function check_data_exists($finger_id , $datetime , $conn ){
	$query = "SELECT * FROM swaptime WHERE  finger_id = '{$finger_id}' AND swap_datetime_log = '{$datetime}' ";
	#print($query);
	$result = mysql_query($query , $conn );  
	if( mysql_num_rows($result) > 0){
		return true;
	}
	return false;
}

function clear_log_machine($host_machine , $port){

	 
	$Connect = fsockopen( $host_machine , $port , $errno, $errstr, 1);
	if($Connect){
		$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
		$buffer=parse_data($buffer,"<Information>","</Information>");
	  
		return $buffer;
	} 
	
	return false;
} 

$hosts = array(
	1 => "10.1.3.156", #<<<< IP mesin absen1
	2 => "10.1.3.157",  #<<<< IP mesin absen2
	//3 => "192.168.3.16", 
	//4 => "192.168.3.17"
	);
foreach($hosts as $key => $host){
	$ff +=  save_data_download( $key  , $host , "0" , "80" , $conn);
	//clear_log_machine($host , "80");
}

echo $ff;
