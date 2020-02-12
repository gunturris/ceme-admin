<?php

function players_total($criteria = false){
    if(! $criteria ){
        $query = "SELECT COUNT(*) AS total_datas FROM players";
        $result = my_query($query);
        $row = my_fetch_array($result);
        return $row['total_datas'];
    }
    return 0;
}


function detail($player_id){
    
    
    $view = '<div class="row">';
    $view .= '<div class="col-md-6">';
    $view .= form_field_display( $order['_id'] , 'Invoice No'  );   
    $view .= form_field_display( $order['order_time_create'] , 'Waktu transaksi'  );   
    $view .= form_field_display( date('Y-m-d H:i' , strtotime($orderdelv['qdelv_start']) ) .' - '.
                                 date(' H:i' , strtotime($orderdelv['qdelv_end'] ) ) , 'Waktu antar'  );  
    $view .= '</div><div class="col-md-6">';
     
    $view .= form_field_display( $customer['fullname']    , 'Customer name'  );   
    $view .= form_field_display( $orderdelv['alamat']    , 'Alamat kirim'  );   
    $view .= form_field_display( $orderdelv['cat_alamat']   , 'Alamat info'  );   
    $view .= form_field_display(  $customer['nohp']  , 'Handphone'  );   
    $view .= '</div></div>';
    $view .= $split_line;
    $view .= form_field_display(  'Rp. '.rp_format($order['order_net'] ) , 'Total belanja'  );   
    $view .= form_field_display(  'Rp. '.rp_format($order['order_ongkir'] ) , 'Ongkos kirim'  );   
    $view .= form_field_display(  'Rp. '.rp_format(0 ) , 'Biaya admin  (0%)'  );
    
    $view .= form_field_display(  '<span style="color:red;">Rp. '.rp_format(0 ) .'</span>', 'Penggunaan saldo'  );   
    $view .= form_field_display(  '<span style="color:red;">Rp. '.rp_format(0 ).'</span>' , 'Total tagihan'  );   
    $view .= form_field_display(  '<span style="color:red;"> '.$voucher['kodepromo'].' - ( <i> Rp. '.rp_format($voucher['priceref_1']).' </i>)</span>' , 'Kode voucher'  );   
    
    return $view;
    
}