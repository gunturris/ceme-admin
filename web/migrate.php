<?php 
ini_set("display_error",1);
$conn = mysql_connect('localhost','root','123123');
mysql_select_db('medco_access_door');

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

function stor_to_machine( $finger_id , $name , $IP , $port = "80" , $Key = "0" ){ 
	$Connect = fsockopen($IP, $port, $errno, $errstr, 1);
	if($Connect){ 
		$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN>".$finger_id."</PIN><Name>".$name."</Name></Arg></SetUserInfo>";
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
$IP_Mesin = "192.168.10.11";
$query = "SELECT * FROM emp";
$result = mysql_query($query , $conn);
$i = 0;
print('<pre>');
while( $row = mysql_fetch_array($result) ){
	var_dump($row);
	//$upload_data = stor_to_machine( $row['finger_id'] , $row['nickname']  , $IP_Mesin ,  "80" ,   "0" );
	//if($upload_data)$i++;
}
//print("Uploaded:  ". $i );