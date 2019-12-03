<?php

function map_view(){
	
	
	$view = '<table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid;border-collapse:collapse;">';
	$view .= '<tr style="height:28px;">';
	$view .= '	<th width="100"><b>Nama terapis</b></th> ';
	for($i=0; $i<=23; $i++){
		$jam = sprintf( '%02d' , $i);
		$view .= '<th style="border:1px solid;border-collapse:collapse;" width="20px"><b>'.$jam.'</b></th> ';
	}
	$view .= '</tr>';
	
	$query = "SELECT * FROM tukang_cukur WHERE cabang_id = {$_SESSION['cabang_id']}";
	$result  = my_query($query);
	$m = 1;
	while($row = my_fetch_array($result) ){
	$m++;
		$default_bg = ( $m % 2 ) == 0 ? '#CDCDCD': '#FFFFFF' ;
		$view .= '<tr style="height:24px;background-color:'.$default_bg.'">';
		$view .= '	<td style="border:1px solid;border-collapse:collapse;padding-left:5px;" align="left">'.$row['nama'].'</td> ';
		
		for($i=0; $i<=23; $i++){
			$jamx = sprintf( '%02d' , $i);
			$registrasi_paket_item_id = get_kursi_by_tukang_cukur_and_waktu((int)$row['tukang_cukur_id'] , $jamx);
			if($registrasi_paket_item_id){
				$bgcolor = get_color_background($registrasi_paket_item_id);
				$view .= '	<td align="center" width="20px" style="border:1px solid;border-collapse:collapse;background-color:'.$bgcolor.'">
					<a href="index.php?com='.$_GET['com'].'&task=newwin&id='.$registrasi_paket_item_id.'" rel="facebox">[ # ]</a></th> ';
			}else{
				$view .= '	<td align="center" width="20px" style="border:1px solid;border-collapse:collapse;">  -</th> ';
			}
		}	
		
		$view .= '</tr>';
	}
	$view .='</table>';
	$view .= legenda();
	
	$navigasi = array(
		'<input class="button" type="button" value="Daftar antrian" onclick="javascript:location.href=\'index.php?com=antrian\'"/>' 
	);
	$box = header_box( 'Tampilan pengelolan waktu layanan' , $navigasi ); 
	return $box.$view;
}

function get_tukang_cukur_by_kursi_and_waktu($kursi_id , $jam){
	$query = "SELECT registrasi_paket_item_id FROM registrasi_paket_item a
		INNER JOIN registrasi_paket b ON a.registrasi_paket_id = b.registrasi_paket_id
		INNER JOIN registrasi c ON c.registrasi_id = b.registrasi_id
		WHERE HOUR(a.rencana_cukur) = '{$jam}' AND a.kursi_id = {$kursi_id} 
		AND DATE( c.tanggal_datang ) = DATE(NOW())";
		 
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['registrasi_paket_item_id'];
	}
	return false;
}

function get_kursi_by_tukang_cukur_and_waktu($tukang_cukur_id , $jam){
	$query = "SELECT registrasi_paket_item_id FROM registrasi_paket_item a
		INNER JOIN registrasi_paket b ON a.registrasi_paket_id = b.registrasi_paket_id
		INNER JOIN registrasi c ON c.registrasi_id = b.registrasi_id
		WHERE HOUR(a.rencana_cukur) = '{$jam}' AND a.tukang_cukur_id = {$tukang_cukur_id} 
		AND DATE( c.tanggal_datang ) = DATE(NOW())";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['registrasi_paket_item_id'];
	}
	return false;
}

function get_color_background($registrasi_paket_item_id){
	//CHECK selesai
	$query = "SELECT * FROM registrasi_paket_item 
		WHERE registrasi_paket_item_id = {$registrasi_paket_item_id}";
	$result = my_query($query);	
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		if( $row['status_cukur'] == 'SELESAI'){
			return 'red';
		}elseif($row['status_cukur'] == 'KERJA'){ 
			return 'green';
		}else{
			return 'yellow';
		}
	}
	return 'yellow';
}

function detail_info_kursi_id($registrasi_paket_item_id){
	$detail = my_get_data_by_id('registrasi_paket_item', 'registrasi_paket_item_id', $registrasi_paket_item_id);

	$tc = my_get_data_by_id('tukang_cukur' , 'tukang_cukur_id' , $detail['tukang_cukur_id'] );
	$item = my_get_data_by_id('item' , 'item_id' , $detail['item_id'] );
	$kursi = my_get_data_by_id('kursi_kerja' , 'kursi_id' , $detail['kursi_id'] );
	$registrasi_paket = my_get_data_by_id('registrasi_paket' , 'registrasi_paket_id' , $detail['registrasi_paket_id'] );
	
	$paket = my_get_data_by_id('paket' , 'paket_id' , $registrasi_paket['paket_id'] );
	 
	$registrasi = my_get_data_by_id('registrasi','registrasi_id' , $registrasi_paket['registrasi_id']);
	$pelanggan = my_get_data_by_id('member' , 'member_id' , $registrasi['member_id'] );
	$view = form_header( "registrasi" , "registrasi"  ); 
	
	$view .= form_field_display( sprintf('%06d',$registrasi['registrasi_id'] ), "Nomor registrasi"    );
	$view .= form_field_display( date('Y-m-d' , strtotime($registrasi['tanggal_datang'])).' '.
		date('H:i' ,strtotime( $detail['rencana_cukur']) ), "Rencana treatment"    );
	$view .= form_field_display( $kursi['kode'] , "Kode kursi kerja"    );
	$view .= form_field_display( $tc['nama'] , "Nama tukang cukur"    );
	$view .= form_field_display( $pelanggan['member_number'].' - '.$pelanggan['nama'] , "Nama pelanggan"    );
	$view .= form_field_display( $paket['nama_paket'] , "Paket"    );
	
	$code = get_color_background($registrasi_paket_item_id);
	if( $code == 'yellow' ){
		$view .= form_field_display( '<input type="button" value="Mulai" 
			onclick="javascript:location.href=\'index.php?com=view_map&task=mulai&id='.$registrasi_paket_item_id.'\'" />', "&nbsp;"    );
	}elseif($code == 'green' ){
		$view .= form_field_display( '<input type="button" value="Selesai" 
			onclick="javascript:location.href=\'index.php?com=view_map&task=selesai&id='.$registrasi_paket_item_id.'\'" />', "&nbsp;"    );
	}
	
	$view .= form_footer( );
	return $view;
}

function get_registrasi_paket_item_id_by_registrasi_paket_id($registrasi_paket_id){
	$query = "SELECT registrasi_paket_pelayanan_id FROM registrasi_paket_pelayanan
		WHERE registrasi_paket_id = {$registrasi_paket_id} ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['registrasi_paket_pelayanan_id'];
}

 function close_layanan($registrasi_paket_item_id){
	 
	$registrasi_id = get_registrasi_id_by_paket_item($registrasi_paket_item_id);
	 
	$total_tagihan  = total_tagihan_lunas($registrasi_id);
	
	$datas = array(
		'registrasi_id' => my_type_data_int($registrasi_id), 	
		'total_tagihan' => my_type_data_str($total_tagihan),
		'user_id' 		=> my_type_data_int($_SESSION['user_id']),
		'datetime_added' => my_type_data_function('NOW()'),
		'payment_status' => my_type_data_str('pend'),
	);
	$registrasi_id = check_registrasi_pembayaran( $registrasi_id );
	
	if( $registrasi_id ){ 
		return my_update_record('pembayaran', 'registrasi_id', $registrasi_id , $datas );
	}
	return my_insert_record( 'pembayaran' , $datas);
}


function get_registrasi_id_by_paket_item($registrasi_paket_item_id){
	$query = "SELECT registrasi_id FROM registrasi_paket a
	INNER JOIN registrasi_paket_item b ON a.registrasi_paket_id = b.registrasi_paket_id
	WHERE b.registrasi_paket_item_id = {$registrasi_paket_item_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['registrasi_id'];
}


function check_registrasi_pembayaran($registrasi_id){
	$query = "SELECT * FROM pembayaran WHERE registrasi_id = {$registrasi_id} ";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['registrasi_id'];
	}
	return false;
}

function total_tagihan_lunas($registrasi_id){
	$query = "SELECT SUM(c.harga_paket) as hitung FROM registrasi_paket_pelayanan a
	INNER JOIN registrasi_paket b ON a.registrasi_paket_id = b.registrasi_paket_id
	INNER JOIN paket c ON b.paket_id = c.paket_id
		WHERE b.registrasi_id = {$registrasi_id} AND a.closed = 'Y' ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['hitung'];
}
function legenda(){

	$view = '<br/><table width="250px" cellpadding="2" cellspacing="2">';
	$view .= '<tr><td colspan="2"><b><i>Legenda</i></b></td></tr>';
	$query = "SELECT * FROM tukang_cukur";
	$result = my_query($query);
	//while( $row = my_fetch_array($result)){
		$view .='
		<tr style="height:22px;">
			<td width="20px" style="background-color:yellow;margin-bottom:3px;">&nbsp;</td>
			<td width="230px">&nbsp; Sedang antri</td>
		</tr>
		<tr style="height:22px;">
			<td width="20px" style="background-color:green;margin-bottom:3px;">&nbsp;</td>
			<td width="230px">&nbsp; Sedang dikerjakan</td>
		</tr>
		<tr style="height:22px;">
			<td width="20px" style="background-color:red;margin-bottom:3px;">&nbsp;</td>
			<td width="230px">&nbsp; Sudah selesai</td>
		</tr>
		';
	//}
	
	$view .= '</table>';
	return $view;
}