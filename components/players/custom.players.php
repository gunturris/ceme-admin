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
 