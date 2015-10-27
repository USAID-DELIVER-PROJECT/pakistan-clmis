<?
class DBAccess extends config {

      var $DBlink;
      var $Result;
      var $LastMsg;
	  //var $DBname='niit-hamid';
      function connectToDB()
      {
          $this->DBlink = mysql_pconnect( $this->db_host, $this->db_user, $this->db_pass );
          if (!$this->DBlink)
             die("Could not connect to mysql");
          mysql_select_db( $this->db_name, $this->DBlink)
             or die( "Couldn't connect to database : ".mysql_error() );
      }


      //function to check the prexistance of a field
      function GetRecord($table, $fnm, $fval)
      {
          $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval'" , $this->DBlink );
          if ( ! $this->Result )
          die( "getRow fatal error: ".mysql_error() );
          return mysql_fetch_array( $this->Result );
      }

      //function to check username and password
      function CheckUser($table, $fnm, $fval, $fnm1, $fval1)
      {
           $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval'&&$fnm1='$fval1'" , $this->DBlink );
           if ( ! $this->Result )
           		return false;
           return mysql_num_rows( $this->Result );

      }
	  
	  //returns number of records a query will generate
	  function RecordsInQuery($query)
		{
			$this->Result = mysql_query ( $query , $this->DBlink );
			   if ( ! $this->Result )
					return false;
			   return mysql_num_rows( $this->Result );
		}

      //getting total no of a particular record
      function GetNumberOfRecords($table, $fnm, $fval)
      {
           $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval'" , $this->DBlink );
           if ( ! $this->Result )
           		return false;
           return mysql_num_rows( $this->Result );

	}
	
	   //getting total no of a particular record
	   function OverloadsGetNumberOfRecord($table, $fnm, $fval,$fnm1, $fval1)
	       {
	    $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval'&&$fnm1='$fval1'" , $this->DBlink );
	    if ( ! $this->Result )
	       die( "getRow fatal error: ".mysql_error() );
	    return mysql_num_rows( $this->Result );
	
	}
	
	//function to get the manximum of all
	function GetRecordByCriteria($table, $fnm, $fval, $required)
	{
	    $this->Result = mysql_query ( "SELECT $required FROM $table WHERE $fnm='$fval'" , $this->DBlink );
	    if ( ! $this->Result )
	        return false;
	
	    while($row= mysql_fetch_array( $this->Result )){
	        $back = $row["$required"];
	    }
	    return $back;
	}
	
	//function to get the manximum of all
	function OverloadsGetRecordByCriteria($table, $required)
	{
	    $this->Result = mysql_query ( "SELECT $required FROM $table " , $this->DBlink );
	    if ( ! $this->Result )
	        return false;
	
	    while($row= mysql_fetch_array( $this->Result )){
	        $back = $row["$required"];
	    }
	    return $back;
	}
	
	
	//function to insert data into the table
	function InsertRecord($table, $insert, $vals)
	{
		
	//echo  " INSERT INTO $table ($insert) VALUES ($vals) <br>";
		//exit;
		//echo '<br>';
		
	    $this->Result = mysql_query( " INSERT INTO ".$table." (".$insert.")
	            VALUES (".$vals.")", $this->DBlink) or die(mysql_error()) ;
	            return mysql_insert_id( $this->DBlink);
				//return true;
	
	}
	
	
	//function to retrieve a single field
	function GetSingleField($table, $fnm, $fval, $required,$fld2=null,$val2=null,$fld3=null,$val3=null,$fld4=null,$val4=null)
	{
	
		$append = "";
		if($fld2 or ($fld2 > -1)){
			$append .= " and $fld2 = '$val2' ";
		}
		if($fld3 or ($fld3 > -1)){
			$append .= " and $fld3 = '$val3' ";
		}
		if($fld4 or ($fld4 > -1)){
			$append .= " and $fld4 = '$val4' ";
		}
		$query = "SELECT * FROM $table WHERE $fnm='$fval' ".$append;
		//echo "<br> $required : ".$query;
	    $this->Result = mysql_query ( $query , $this->DBlink );
	    if ( ! $this->Result )
	       return false;
	    while($row= mysql_fetch_array( $this->Result )){
	        $back = $row["$required"];
	    }
	    return $back;
	}
	
	function OverloadsGetSingleField($table, $fnm, $fval, $fnm1, $fval1, $required)
	{
	    $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval' && $fnm1='$fval1'" , $this->DBlink );
	    if ( ! $this->Result )
	       return false;
	    while($row= mysql_fetch_array( $this->Result )){
	        $back = $row["$required"];
	    }
	    return $back;
	
	}
	
	//function to get an array of something
	function ArrayOfSingleField($table, $fnm, $fval, $required)
	{
	    $this->Result = mysql_query ( "SELECT * FROM $table WHERE $fnm='$fval'" , $this->DBlink );
	    if ( ! $this->Result )
	       return false;
	    while($row= mysql_fetch_array( $this->Result )){
	        $RecArray[] = $row["$required"];
	    }
	    return $RecArray;
	
	}
	
	
	//function to modify an existing record
	function ModifyRecord($table, $fnm, $fval, $fnm1, $fval1)
	{
	    $query="UPDATE $table set $fnm1='$fval1' WHERE $fnm='$fval'";
		
		$this->Result = mysql_query($query, $this->DBlink) ;
	    if (! $this->Result)
	       return false; 
	
	    return true;
	
	}
	
	//function to modify fields by passing query
	function CustomModify($Cquery)
	{
		$query=$Cquery;
		 $query;
	    $this->Result = mysql_query($query, $this->DBlink) or die(mysql_error()) ;
	    if (! $this->Result)
	       return false;
		 else return true;	
	}
	
	/*//function to modify fields by passing query
	function CustomModifynew($Cquery)
	{
		$query=$Cquery;
	    $this->Result = mysql_query($query) ;
	    if (! $this->Result)
	       return false;
		 else return true;	
	}*/
	
	//fucntion to modify existing field with two where parammeters
	function OverloadsModifyRecord($table, $fnm, $fval, $fnm0, $fval0, $fnm1, $fval1)
	{
	    $query="UPDATE $table set $fnm1='$fval1' WHERE $fnm='$fval'&&$fnm0='$fval0'";
	    $this->Result = mysql_query($query, $this->DBlink);
	    if (! $this->Result)
	       return false;
	    else  return true;
	
	}
	
	//function to delete a whole record
	function DeleteSingleRecord($table, $frn, $fval, $frn1, $fval1)
	{
	    $query="DELETE FROM $table WHERE $frn='$fval' && $frn1='$fval1'";
	    $this->Result = mysql_query($query, $this->DBlink);
	    if (! $this->Result)
	       return false;
	       
	     return true;
	
	}
	
	//delete function
	//function to delete a whole record
	function DeleteSetOfRecords($table, $frn, $fval)
	{
	    $query="DELETE FROM $table WHERE $frn='$fval'";
		$this->Result = mysql_query($query, $this->DBlink) ;
	    if (! $this->Result)
	       return false;
	       
	     return true;
	
	}
	
	//function to delete all records
	function DeleteAllRecords($table)
	{
	    $query="DELETE FROM $table ";
		//$query="TRUNCATE TABLE $table"; 
	    $this->Result = mysql_query($query, $this->DBlink);
	    if (! $this->Result)
	       return false;
	       
	     return true;
	
	}
	
	//function to delete all records
	function TruncateTable($table)
	{
	    //$query="DELETE FROM $table ";
		$query="TRUNCATE TABLE $table"; 
	    $this->Result = mysql_query($query, $this->DBlink);
	    if (! $this->Result)
	       return false;
	       
	     return true;
	
	}
	
	function CustomQuery($Query_C)
	{
	    $Return_Result[]=NULL;
	    $Count=0;
	    $Query = "$Query_C";
	 //  echo $Query ."<br>";
		//exit;
		
		$Show_Query_Reuslt = mysql_query($Query, $this->DBlink) or die(mysql_error());
	    $Query_Result_Final = mysql_fetch_assoc($Show_Query_Reuslt);
	
	    //checking that if there is no result 
	    //if no result mysql fetch assoc array size is == 1
	    //modified 18-6-2004
	    if(sizeof($Query_Result_Final)==1 && $Query_Result_Final==null)
	    {
	    	return null;
	    }
	
	    do{
	        $Return_Result[$Count]=$Query_Result_Final;
	        $Count++;
	    }
		while($Query_Result_Final=mysql_fetch_assoc($Show_Query_Reuslt));    
	    return $Return_Result;
	
	}	
	
	//funtion to free and close sql result and connection
	function DBDisconnect()
	{
		//if($this->Result)
			//mysql_free_result($this->Result);
	
	    mysql_close($this->DBlink);
	
	}
	
	//function to report bug incase of database errors
	function ReportBug($line,$file,$error)
	{
		return true;
	}
	
	/****************************************************/
	//Taufeeq getting single record by sql
	function getRecordBySql($sql){
		
		 $result = mysql_query($sql,$this->DBlink);
		$row = mysql_fetch_assoc($result);
		return $row;
	}
	/****************************************************/

	//Taufeeq update single Record
	function updateRecord($table,$fields,$values,$where){
		$fields = explode(",",$fields);
		$values = explode(",",$values);
		
		$subsql = "";
		foreach ($fields as $k=>$field){
			if(strpos($field,"+") === false){
				$subsql.= "$field = '".$values[$k]."', ";
			}else{
				$subsql.= "$field  '".$values[$k]."', ";
			}
		}
		$subsql = rtrim($subsql,", ");
		$sql = "UPDATE $table SET $subsql WHERE $where ";
		
		//echo $sql."<br>";
		$result = mysql_query($sql,$this->DBlink);
		return mysql_affected_rows($this->DBlink);
	}
	/****************************************************/

	//Taufeeq: deleting a record
	function deleteRecord($table,$where){
		$sql = "DELETE FROM $table WHERE $where";
		mysql_query($sql,$this->DBlink) or die(mysql_error());
		
		return mysql_affected_rows($this->DBlink);
	}
	/****************************************************/
	
	
	
	/*****************************************************/
	/*
	// Multiple fields Update function 
	// Added: Waqar Ahmad 10-Jan-2008
	
	@param $table 		Table Name
	@param $fields 		Field Names 	e.g (field1,field2,field3,.......)
	@param $values 		Field Values 	e.g (value1,value2,value3,.......)
	@param $where1      Field Name on which the update has to be made
	@param $where2		Value of $where1 to search and match
	@desc  Both comma seperated values must be in sorted form.
	
	*/
	
	function updateMultipleFields($table,$fields,$values,$where1,$where2)
	{
			//die("Table:".$table ." ,<br>Fields: ". $fields." , <br>Values: ".$values." , <br>Where 1: ". $where1." , <br>Where 2: ".$where2);

			if($table == '' || $fields == '' || $values =='' || $where1 == '' || $where2 == '')
				return false;
				
				
				
				$fields = split(",",$fields);
				$values = split(",", $values);
				
				
				
				if(count($fields) != count($values))
					return false;
				
				
				
				$buildQuery = "UPDATE $table SET ";
				
				for($i=0;$i<count($fields);$i++)
				{
					$buildQuery .= $fields[$i] ."=".str_replace("|",",",$values[$i]) .", ";
				}
				
				$buildQuery = substr($buildQuery,0,strlen($buildQuery)-2);
				
				$buildQuery .= " where $where1=$where2";
				
				
				mysql_query($buildQuery,$this->DBlink) or die(mysql_error());
				
				
				
				return mysql_affected_rows($this->DBlink);
				
				
			
	}
	
	function totalRecordsInQuery($sql)
	{
		if(empty($sql))
			return false;
			
			$q = mysql_query($sql);
			$num = mysql_num_rows($q);
			
			return $num;
	}
	
	
}
?>