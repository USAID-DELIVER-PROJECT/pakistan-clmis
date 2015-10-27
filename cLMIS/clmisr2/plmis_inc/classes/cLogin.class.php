<?php

		/*********************************************************
			Author:	Mohsin Tariq
			Created Date: 1-12-2008
			Modified By:
			Modification Date:
			Purpose: Admin functions
		*********************************************************/




	class cLogin{
		
		private $ID;
		private $login;
		private $password;
		

		
		function __construct(){
			$this->ID=0;
			$this->login="";
			$this->password="";
		}
		
		function fnSetID($var){
			$this->ID=$var;
		}
		
		function fnGetID(){
			return $this->ID;
		}
		
		function fnSetLogin($var){
			$this->login=$var;
		}
		
		function fnGetLogin(){
			return $this->login;
		}
		
		function fnSetPassword($var){
			$this->password=$var;
		}
		
		function fnGetPassword(){
			return $this->password;
		}


		
		
	////////////////// Validate///////////////////////////////	
		function validate(){
				
		if(!$this->login)
			$error .= "&nbsp; &bull; &nbsp;Please enter Login.<br>";
				
		if(!$this->password)
			$error .= "&nbsp; &bull; &nbsp;Please enter Password.<br>";
			
		return $error;	
		}
		
	//////////////////////////////////////////////////////////////////////////////		
		

		
		function fnLogin($table){
			$qry="Select * from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."';";
			return $qry;
		}
		function fnLogindealer($table){
			$qry="Select * from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."' and dealer=1;";
			return $qry;
		}
		function fnLogindealerUsers($table){
			$qry="Select * from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."';";
			return $qry;
		}
		function fnLoginCountdealer($table){
			 $qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."' and dealer=1;";
			return $qry;
		}
	function fnLoginCount($table){
			 $qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."' and rStatus=1;";
			return $qry;
		}
	function fnLoginCountResellerUser($table){
			 $qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."' and isactive=1;";
			return $qry;
		}	
	function fnLoginCountPco($table){
			 $qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(base64_encode($this->password)))."' and cStatus=1;";
			return $qry;
		}
		function fnLoginCounttest($table){
			 $qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."' AND pasword ='".htmlspecialchars(mysql_real_escape_string(($this->password)))."';";
			return $qry;
		}
		function fnUserCheck($table){
			$qry="Select 1 from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."';"; 
			return $qry;
		}

		function fnUserDelete($table){
			 $qry="Delete from ".$table." WHERE userName ='".htmlspecialchars(mysql_real_escape_string($this->login))."';"; 
			return $qry;
		}
		
		function fnLogout(){
			session_destroy();
		}
		
	}
?>