<?php
//Connect to ODBC DataBase
class ODBCConnection implements IDbPersist{

	//Retrieve a key=>value array with name of fields from specified table
	function describleTable($name_table){
		
		
		$outval = odbc_columns($this->getConnection(), constant("odbc_database"), "%", $name_table, "%");

		$pages = array();
		while (odbc_fetch_into($outval, $pages)) {
			//echo $pages[3] . "<br />\n"; // presents all fields of the array $pages in a new line until the array pointer reaches the end of array data
		}
		
		
	
		$saida = array();
		
		for ( $i = 0; $i < count($pages); $i++){
			
			$key =  $pages[3];
			
			$saida[ $key  ] = "";
		}
		
		return $saida;
		
	}
	
	public static $_Conn;
	public static $_nameConn = "conn_odbc";
	
	var $character_open = "[";
	var $character_close = "]";
	
	
	//Connect to MS SQL DataBase
	function connect(){        
		
		
		
		//$str_conection = "host=".constant("pg_host")." port=".constant("pg_port")." dbname=".constant("pg_database")." user=".constant("pg_user")." password=".constant("pg_pass");
		
		$conn =   odbc_connect(K_BDSERVER,constant("odbc_user"),constant("odbc_pass")) or die (" error on connect ");
			
		if ( !$conn ){
			die(" trouble when trying connect to MS SQL host ");
		}
		
		$GLOBALS[ODBCConnection::$_nameConn] = $conn;
		//MSSQLConnection::$_Conn = $conn;
		
	}
	
	//Get Current Connection.. if null  create new connection
	function getConnection(){
		
		if ( ! isset( $GLOBALS[ODBCConnection::$_nameConn]  ))
			$this->connect();
		
		
		//MSSQLConnection::$_Conn
		
		return  $GLOBALS[ODBCConnection::$_nameConn];
		//return MSSQLConnection::$_Conn;
		
	}
	
	//Disconnect and clear memory
	function disconnect(){
		
		$conn = $this->getConnection();
		
		odbc_close( $conn);
		
		ODBCConnection::$_Conn = null;
		
	}
	
	
	//Execute any command
	function executeCommand($sql){
		
		
		
		
		$conn = $this->getConnection();
		//die ( $sql );
		$rt =  odbc_exec($conn, $sql);
		
		if ( ! $rt ){
			die("querie error: " . $sql );
		}
	}
	
	//Retrieve data, in key=>value array format.
	function fetchData($sql){
		
		$conn = $this->getConnection();
		
		$fet = odbc_exec($conn, $sql);
		
		if ( ! $fet ){
			die("Error on querie: " . $sql );	
		}
		
		$arr = array();
		
		while ( $subr = odbc_fetch_array($fet)){
			
			$arr[ count($arr) ] = $subr;
		}   
		
		return $arr;
		
	}
	
	
	//format field using the database specified character
	function formatField($field){
		
		return $this->character_open. trim( $field ).$this->character_close;
	}
	
}

?>