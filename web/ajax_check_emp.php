<?php
require_once("../config.php");
    
$query = " SELECT * FROM  emp WHERE  nik LIKE '{$_GET['q']}%'  ";

$res = my_query($query);

while( $rw=my_fetch_array($res) ){
    echo strtoupper($rw['nik']."/ ".$rw['realname'])."\n";
}
exit;
    
 