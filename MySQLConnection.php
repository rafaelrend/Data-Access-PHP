<?php
class MYSQLConnection implements IDbPersist{

       function describeTable($name_table){
	   
	   
              $sql = "SHOW /*!32332 FULL */ COLUMNS FROM `".$name_table."` ;";
			  
			  $mount = $this->fetchData( $sql );
	   
	          $saida = array();
			  
			  for ( $i = 0; $i < count($mount); $i++){
			  
			        $key =  $mount[$i]["Field"];
				  
			        $saida[ $key  ] = "";
			  }
			  
			  return $saida;
	   
	   }
           
          public $_Conn;
	  public $_nomeConn= "conn_mysql";
	  var $arr_conection;
          
          
            function __construct( $arr_conection = array(), $name_conn = "conn_mysql"  ){

                   $this->arr_conection = $arr_conection;
                   $this->_nomeConn = $name_conn;

            } 
          
          
          
	   function connect(){
	   
               
               if (is_null($this->arr_conection))
                   return;
            
                  $arr_conection = $this->arr_conection;
               
		$mysql_host = $arr_conection["host"];
		$mysql_user = $arr_conection["user"];
		$mysql_pass = $arr_conection["password"];
		$mysql_initialcommand =  @$arr_conection["initialcommand"] ;
		$mysql_db =  @$arr_conection["dbname"];
		$port = 3306;
                
                if ( @$arr_conection["port"] != "" )
                    $mysql_host = $arr_conection["host"].":". $arr_conection["port"];
                
		
	  
		$conn =   mysql_connect($mysql_host,$mysql_user,$mysql_pass, true);
									 
									 if ( !$conn ){
										  die(" trouble when tried connect to mysql host ");
									 }
									
		if ( $mysql_initialcommand != "" ){
										
										
		             	mysql_query($mysql_initialcommand, $conn);
		 }
									
									 
		     mysql_select_db($mysql_db, $conn);
			
			 
			 $GLOBALS[$this->_nomeConn] = $conn;
			 
	   }
	
	   
	   
	   function getConnection(){
	   
	         if ( ! isset( $GLOBALS[$this->_nomeConn]  ))
			      $this->connect();
				  
		
		       return $GLOBALS[$this->_nomeConn];
	   
	   }
	   
	   function disconnect(){
	      
		   $conn = $this->getConnection();
		   
		   mysql_close( $conn);
		   
		   $GLOBALS[$this->_nameConn] = null;
	   
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