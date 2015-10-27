<?php
    include("CnnDb.php");   //Include Database Connection File
    include("FunctionLib.php"); 
    header("Content-type:text/xml");
    //echo $_GET['stk'];
    echo "<?xml version='1.0'?>";
    echo "\n<root>";

 /*
        echo '<sel>';
        echo '<optvalue></optvalue>';    
        echo '<optlabel>Select</optlabel>';        
        echo '</sel>';
*/     
    //GET Stakeholders Products
    if($_GET['act']=='prov'){
      $strSQL="SELECT dist_id, wh_id, rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM
               tbl_warehouse  WHERE prov_id = ". $_GET['prov'] . " ORDER BY wh_name,wh_type_id";     
      
      $rsTemp1=safe_query($strSQL);
      while($rsRow1=mysql_fetch_array($rsTemp1))
      {
        echo '<sel>';
        echo '<optvalue>'.$rsRow1['wh_id'].'</optvalue>';    
        echo '<optlabel>'.$rsRow1['wh_name'] . ' [' . trim($rsRow1['wh_type_id']) .']'.'</optlabel>';        
        echo '</sel>';

      }
    }
    
    //GET All Warehouses
    if($_GET['act']=='all'){
        $strSQL="SELECT dist_id, wh_id, rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM
                           tbl_warehouse  ORDER BY wh_name,wh_type_id";
        $rsTemp1=safe_query($strSQL);
        while($rsRow1=mysql_fetch_array($rsTemp1))
        {
            echo '<sel>';
            echo '<optvalue>'.$rsRow1['wh_id'].'</optvalue>';    
            echo '<optlabel>'.$rsRow1['wh_name'] . ' [' . trim($rsRow1['wh_type_id']) .']'.'</optlabel>';        
            echo '</sel>';

        }
    }

    if($_GET['act']=='oth'){
        
        $strSQL="SELECT dist_id, wh_id, rpad(wh_type_id,7,' ') As wh_type_id, wh_name FROM
                           tbl_warehouse WHERE prov_id IS NULL ORDER BY wh_name,wh_type_id";
        
        $rsTemp1=safe_query($strSQL);
        while($rsRow1=mysql_fetch_array($rsTemp1))
        {
            echo '<sel>';
            echo '<optvalue>'.$rsRow1['wh_id'].'</optvalue>';    
            echo '<optlabel>'.$rsRow1['wh_name'] . ' [' . trim($rsRow1['wh_type_id']) .']'.'</optlabel>';        
            echo '</sel>';
            
        }
    }

    echo "</root>";
?>