<?php

function display_main_page(){
	$query = "SHOW TABLES";
	$hasil = my_query($query);
	
	$foldersize = filesize_r(MY_FILES_PATH);
	$gb = 1024 * 1024 * 1024;
	$mb = 1024 * 1024;
	$kb = 1024;
	if( $foldersize > $gb ){
		$fsize = sprintf("%02d",$foldersize/$gb )." Gb";
	}
	elseif( $foldersize > $mb ){
		$fsize = sprintf("%02d",$foldersize/$mb )." Mb";
	}
	elseif( $foldersize > $kb ){
		$fsize = sprintf("%02d",$foldersize/$kb )." Kb";
	}
	else{
		$fsize = $foldersize . " byte";
	}
	$view ='
	<fieldset style="width:98%"> <br/>
			<table width="98%" border="0">
			<tr>
				<td colspan="2" style="border-top:1px solid #ECECEC;"><span style="font-size:14px">Informasi database</span></td> 
			</tr>
			<tr>
				<td width="15%" style="border-right:0;"><b>Nama database</b>	 </td>
				<td width="83%">'. DATABASE_NAME.'</td>
			</tr>
			<tr>
				<td width="15%" style="border-right:0;"><b>Jumlah tabel</b></td>
				<td width="83%">'. my_num_rows($hasil).' </td>
			</tr>
			<tr>
				<td width="15%" style="border-right:0;">&nbsp;</td>
				<td width="83%"><input type="button" class="submit-green" style="width:210px;" onclick="javascript:location.href=\'index.php?com=db_backup&task=compress_sql\'" value="DOWNLOAD DATABASE"/></td>
			</tr> 
			</table> 
			 <hr style="width:100%;border:1px solid #6bd091"/>  
			<table width="98%" border="0">
			<tr>
				<td colspan="2" style="border-top:1px solid #ECECEC;"><span style="font-size:14px">Informasi lampiran dokumen</span></td> 
			</tr>
			<tr>
				<td width="15%" style="border-right:0;"><b>PATH</b>	 </td>
				<td width="83%">'. MY_FILES_PATH.'</td>
			</tr>
			<tr>
				<td width="15%" style="border-right:0;"><b>Jumlah folder</b></td>
				<td width="83%">5 </td>
			</tr>
			<tr>
				<td width="15%" style="border-right:0;"><b>File size</b></td>
				<td width="83%">'.$fsize.'</td>
			</tr>
			<tr>
				<td width="15%" style="border-right:0;">&nbsp;</td>
				<td width="83%"><input type="button" class="submit-green" style="width:210px;" onclick="javascript:location.href=\'index.php?com=db_backup&task=compress\'" value="DOWNLOAD ALL FILES"/></td>
			</tr> 
			</table>  
	</fieldset> ';
	
	return $view;
}

function filesize_r($path){
  if(!file_exists($path)) return 0;
  if(is_file($path)) return filesize($path);
  $ret = 0;
  foreach(glob($path."/*") as $fn)
    $ret += filesize_r($fn);
  return $ret;
}

function compress_file(){
	$ftar = "files/compressing_backup/backup_".date("Ymd").".tar";
	exec("tar -cf {$ftar} files/upload/" );
	if(file_exists($ftar)){
		my_direct($ftar);
	}
	return false; 
}

function compress_sql(){
	$fsql =  "files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql";
	$ftar = $_SERVER['DOCUMENT_ROOT']."/files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql.tar";
	//$link = "files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql.tar";
	$query = "SHOW TABLES";
	$hasil = my_query($query);
	$tbls='';
	while($r = my_fetch_row($hasil)){
		$tbls .= $r[0].' ';
	}
	//$mysqldump_command = "mysqldump -h ".DATABASE_HOST." -u ".DATABASE_USER." -p".DATABASE_PASSWORD."  hris_live_25_mei   > {$fsql}";
 	$mysqldump_command = "/xampp/mysql/bin/mysqldump -h ".DATABASE_HOST." -u ".DATABASE_USER." -p".DATABASE_PASSWORD." ".DATABASE_NAME."   > {$fsql}";
 
	exec($mysqldump_command);
	if(file_exists($fsql)){
		//exec("tar -cvvf {$ftar} --no-recursion {$fsql}" );
		if(file_exists($fsql)){
			my_direct($fsql );
		}
		return false;
	}
	return false;
}