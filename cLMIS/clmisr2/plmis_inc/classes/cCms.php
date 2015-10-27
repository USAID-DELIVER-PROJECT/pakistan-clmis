<?php 
/*********************************************************
	Author:				Syed Aun Irtaza
	Created Date:		26/09/2009
	Purpose:			Class for cms table
	*********************************************************/
	 class cCms
	 {
	 	private $id;
		private $title;
		private $description;
		private $ministry; 
			
		/*********************************************************
			Purpose:	For Assigning default values
		*********************************************************/
		function __construct(){				
			
		$this->id=0;
		$this->title="";
		$this->description=""; 
		$this->ministry="";
						
		}
		/////////       Setter n Getter     ///////////////////
		
		function fnSetId($var)
		{
			$this->id=$var;	
		}
		function fnGetId()
		{
			return $this->id;	
		}
		
		function fnSetTitle($var)
		{
			$this->title=$var;	
		}
		function fnGetTitle()
		{
			return $this->title;	
		}
		
		function fnSetDescription($var)
		{
			$this->description=$var;	
		}
		function fnGetDescription()
		{
			return $this->description;	
		}
		function fnSetMinistry($var)
		{
			$this->ministry=$var;	
		}
		
		////////////////////////////////////////////////
		
		///// validation/////
		function validate(){
			if(empty($this->title)){
				$error = "&bull;&nbsp;&nbsp;Title for content is required.";
			}
			if(empty($this->description)){
				$error .= "<br />&bull;&nbsp;&nbsp;Description about content is required.";
			}
			return $error;
		}
		
		////////////  DML Functions   //////////////////
		function Add($table){
			$sql = "INSERT INTO ".$table." SET
					title			='".mysql_real_escape_string($this->title)."',
					stkid			='".mysql_real_escape_string($this->ministry)."',
					description		='".htmlspecialchars($this->description)."'";	
			return $sql;	
			
		}
		
		function Update($table,$id){
			 	$sql = "Update ".$table." SET
					title			='".mysql_real_escape_string($this->title)."',
					stkid			='".mysql_real_escape_string($this->ministry)."',
					description		='".htmlspecialchars($this->description)."'
					Where id		= ".mysql_real_escape_string($id).";";
			return $sql;
			
		}
		
		
		function SqlCount($table,$whr_cls=""){
			$sql="SELECT 1 FROM ".$table." Where 1=1 ".$whr_cls.";";
			return $sql;
		}
		
		function Select($table,$col,$whr_cls=""){
			$sql="SELECT ".$col." FROM ".$table." Where 1=1 ".$whr_cls;
			return $sql;
		}
		
		function Delete($table,$whr_cls){
			$sql="DELETE FROM ".$table." Where 1=1 ".$whr_cls.";";
			return $sql;
		}
		

}	
?>