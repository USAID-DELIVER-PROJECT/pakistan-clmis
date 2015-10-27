<?php

include_once("../../plmis_inc/common/CnnDb.php");


$id = $_REQUEST["id"];

      $query = "SELECT
                geo_indicator_values.geo_indicator_id,
                geo_indicator_values.start_value,
                geo_indicator_values.end_value,
                geo_indicator_values.interval,
                geo_indicator_values.description,
                geo_color.color_code
                FROM
                geo_indicators
                INNER JOIN geo_indicator_values ON geo_indicators.id = geo_indicator_values.geo_indicator_id
                INNER JOIN geo_color ON geo_indicator_values.geo_color_id = geo_color.id
                WHERE
                geo_indicator_values.geo_indicator_id = ".$id;

      $result = mysql_query($query);
         if($result){
             $row = mysql_fetch_all($result);
       }
       else{
           echo "Failed";
       }

        echo json_encode($row);
       

       function mysql_fetch_all($result)
        {
            $all = array();
            while ($row = mysql_fetch_assoc($result)){
                $all[] = $row;
            }
            return $all;
        }

       

?>