<?php
class MYSQLConnection implements IDbPersist{

       function describleTable($name_table){
	   
	   
              $sql = "SHOW /*!32332 FULL */ COLUMNS FROM `".$name_table."` ;";
			  
			  $mount = $this->fetchData( $sql );
	   
	          $saida = array();
			  
			  for ( $i = 0; $i < count($mount); $i++){
			  
			        $key =  $mount[$i]["Field"];
				  
			        $saida[ $key  ] = "";
			  }
			  
			  return $saida;
	   
	   }
	   var $default_db = "MYSQL_CONN";
	   public static $_Conn;
	   
	   function connect(){
	   
	    $conn = null;
		
		$mysql_host = constant("mysql_host");
		$mysql_user = constant("mysql_user");
		$mysql_pass = constant("mysql_pass");
		$mysql_initialcommand = constant("mysql_initialcommand");
		$mysql_db = constant("mysql_db");
		$port = 3306;
		
		if ( @$_SESSION[SessionFacade::nomeSchema(). "_linha_query"] != "" ){
			
			
			$arp = explode("\t", @$_SESSION[SessionFacade::nomeSchema(). "_linha_query"]);	
			
			
			$mysql_host = $arp[1];
			$mysql_db = $arp[3];
			$mysql_user = $arp[4];
			$mysql_pass = $arp[5];
			$port = $arp[6];
			
		}else{
			//die("conexão vazia!");
			//return;	
		}
	
	       // $_SESSION["_linha_row_query"] =$sel_base;
           //  $_SESSION["_linha_query"]
	  
		$conn =   mysql_connect($mysql_host,$mysql_user,$mysql_pass);
									 
									 if ( !$conn ){
										  die(" trouble when tried connect to mysql host ");
									 }
									
		if ( $mysql_initialcommand != "" ){
										
										
		             	mysql_query($mysql_initialcommand, $conn);
		 }
									
									 
		     mysql_select_db($mysql_db, $conn);
			
			 
			 $GLOBALS[$this->default_db] = $conn;
			 
	   }
	
	function newConnection( $host, $user, $pass, $initialcommand, $db , $nome_db){
		
		$conn =   mysql_connect($host,$user,$pass);
		
		if ( !$conn ){
			die(" trouble when tried connect to mysql host ". $host);
		}
		
		if ( $initialcommand != "" ){		
			mysql_query($initialcommand, $conn);
		}
		
		
		mysql_select_db($db, $conn);
		
		$this->default_db = $nome_db;
		
		$GLOBALS[$this->default_db] = $conn;
		return $this;
		
	}
	   
	   
	   function getConnection(){
	   
	         if ( ! isset( $GLOBALS[$this->default_db]  ))
			      $this->connect();
				  
		
		       return $GLOBALS[$this->default_db];
	   
	   }
	   
	   function disconnect(){
	      
		   $conn = $this->getConnection();
		   
		   mysql_close( $conn);
		   
		   $GLOBALS[$this->default_db] = null;
	   
	   }
	   
	   function executeCommand($sql){
	    
		   $conn = $this->getConnection();
		   
		// $sai =  mysql_query($sql, $conn);
	   
		$fet = @mysql_query($sql, $conn);
		
		if ( !$fet ){
			//die ( "pau na querie: " . $sql);	
		}
	
		return $fet;
	   }
	   
	   function fetchData($sql){
	   
		   $conn = $this->getConnection();
		   
		    $fet = @mysql_query($sql, $conn);
			
		if ( !$fet ){
                    
                    
                         var_dump(debug_backtrace());  
                    
			die ( "pau na querie: " . $sql);	
		}
	        
			$arr = array();
			
			while ( $subr = mysql_fetch_array($fet)){
			
			    $arr[ count($arr) ] = $subr;
			}   
			
			return $arr;
	   
	   }
	   
	   function formatField($field){
	   
	      return "`". trim( $field )."`";
	   }

}

?>