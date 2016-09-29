<?
//Connect to postgre DataBase
class MSSQLConnection implements IDbPersist{

	//Retrieve a key=>value array with name of fields from specified table
	function describleTable($name_table){
		
		
		$sql = " 
		      SELECT
				OBJECT_NAME(c.OBJECT_ID) TableName
				,c.name AS column_name
				,SCHEMA_NAME(t.schema_id) AS SchemaName
				,t.name AS TypeName
				,t.is_user_defined
				,t.is_assembly_type
				,c.max_length
				,c.PRECISION
				,c.scale
				FROM sys.columns AS c
				JOIN sys.types AS t ON c.user_type_id=t.user_type_id
				where OBJECT_NAME(c.OBJECT_ID) = '".$name_table."'
				ORDER BY c.OBJECT_ID ";		
		
		///die ( $sql );
		$mount = $this->fetchData( $sql );
		
		$saida = array();
		
		for ( $i = 0; $i < count($mount); $i++){
			
			$key =  $mount[$i]["column_name"];
			
			$saida[ $key  ] = "";
		}
		
		return $saida;
		
	}
	
	public static $_Conn;
	public static $_nameConn = "conn_mssql";
	
	
	//Connect to MS SQL DataBase
	function connect(){
		
		
		
		//$str_conection = "host=".constant("pg_host")." port=".constant("pg_port")." dbname=".constant("pg_database")." user=".constant("pg_user")." password=".constant("pg_pass");
	
		$conn =   mssql_connect(constant("mssql_host"),constant("mssql_user"),constant("mssql_pass")) or die (" error on connect ");
		
		$select = mssql_select_db(constant("mssql_database")) or die($this->error_msg("Error with select database"));
		
		
		if ( !$conn ){
			die(" trouble when trying connect to MS SQL host ");
		}
		
		$GLOBALS[MSSQLConnection::$_nameConn] = $conn;
		//MSSQLConnection::$_Conn = $conn;
		
	}
	
	//Get Current Connection.. if null  create new connection
	function getConnection(){
		
		if ( ! isset( $GLOBALS[MSSQLConnection::$_nameConn]  ))
			$this->connect();
		
		
		//MSSQLConnection::$_Conn
		
		return  $GLOBALS[MSSQLConnection::$_nameConn];
		//return MSSQLConnection::$_Conn;
		
	}
	
	//Disconnect and clear memory
	function disconnect(){
		
		$conn = $this->getConnection();
		
		mssql_close( $conn);
		
		MSSQLConnection::$_Conn = null;
		
	}
	
	
	//Execute any command
	function executeCommand($sql){
		
		
		
		
		$conn = $this->getConnection();
		//die ( $sql );
		$rt = mssql_query($conn, $sql);
		
		if ( ! $rt ){
			die("querie error: " . $sql );
		}
	}
	
	//Retrieve data, in key=>value array format.
	function fetchData($sql){
		
		$conn = $this->getConnection();
		
		$fet = mssql_query($conn, $sql);
		
		if ( ! $fet ){
			die("Error on querie: " . $sql );	
		}
		
		$arr = array();
		
		while ( $subr = mssql_fetch_array($fet)){
			
			$arr[ count($arr) ] = $subr;
		}   
		
		return $arr;
		
	}
	
	//format field using the database specified character
	function formatField($field){
		
		return "[". trim( $field )."]";
	}
	
}

?>