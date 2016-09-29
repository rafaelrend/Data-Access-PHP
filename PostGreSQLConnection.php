<?
//Connect to postgre DataBase
class PostGreSQLConnection implements IDbPersist{

       function describleTable($name_table){
		
                $arps = explode(".", $name_table );
           
                $nome_tabela = $name_table;
                $filtro_sql = "";
                
                if ( count($arps) > 1 ){
                    $nome_tabela = $arps[1];
                    
                    $filtro_sql .= " and table_schema = '".$arps[0]."' ";
                }
		
		$sql = " SELECT column_name FROM information_schema.columns WHERE table_name = '".$nome_tabela."' ".
                        $filtro_sql." ";		
                
		//die ( $sql );
		$mount = $this->fetchData( $sql );
		
		$saida = array();
		
		for ( $i = 0; $i < count($mount); $i++){
			
			$key =  $mount[$i]["column_name"];
			
			$saida[ $key  ] = "";
		}
		
		return $saida;
		
	}
	
	public static $_Conn;
	public static $_nomeConn = "conn_pgsql";
	
	function garanteSchema(){
		
		$conn = $this->getConnection();
		
		if ( constant("pg_schema") != "" ){
			
			pg_query($conn, " set search_path = \"".constant("pg_schema")."\"");	
		}	
	}
	
	function connect(){
		
		$str_conection = "host=".constant("pg_host")." port=".constant("pg_port")." dbname=".constant("pg_database")." user=".constant("pg_user")." password=".constant("pg_pass");
		
		//die ( $str_conection );
		$conn =   pg_connect($str_conection);
		
		if ( constant("pg_schema") != "" ){
			
			pg_query($conn, " set search_path = \"".constant("pg_schema")."\"");	
		}
		

		
		if ( constant("pg_initialcommand") != "" ){
				
			pg_exec($conn, constant("pg_initialcommand"));
		}
		if ( constant("pg_client_encoding") != "" ){
			
		    pg_set_client_encoding($conn, constant("pg_client_encoding"));
		}
		
		
		if ( !$conn ){
			die(" trouble when tried connect to PostGreSQL host ");
		}
		
		$GLOBALS[PostGreSQLConnection::$_nomeConn] = $conn;
		//PostGreSQLConnection::$_Conn = $conn;
		
	}
	
	
	function getConnection(){
		
		if ( ! isset( $GLOBALS[PostGreSQLConnection::$_nomeConn]  ))
			$this->connect();
		
		
		//PostGreSQLConnection::$_Conn
		
		return  $GLOBALS[PostGreSQLConnection::$_nomeConn];
		//return PostGreSQLConnection::$_Conn;
		
	}
	
	function disconnect(){
		
		$conn = $this->getConnection();
		
		pg_close( $conn);
		
		PostGreSQLConnection::$_Conn = null;
		
	}
	
	function executeCommand($sql){
		
		
		
		
		$conn = $this->getConnection();
		//die ( $sql );
		$rt = pg_query($conn, $sql);
		if ( ! $rt ){
                    $rt = @pg_query($conn, $sql);
                }
		if ( ! $rt ){
		   die("erro na querie:" . $sql );
		}
                
                return $rt;
	}
	
	function fetchData($sql){
		
		$conn = $this->getConnection();
		
		$fet = pg_query($conn, $sql);
		
		if ( ! $fet ){
                    
                         var_dump(debug_backtrace());  
			die("Erro na querie: " . $sql );	
		}
		
		$arr = array();
		
		while ( $subr = pg_fetch_array($fet)){
			
			$arr[ count($arr) ] = $subr;
		}   
		
		return $arr;
		
	}
	
	function formatField($field){
		
             $arps = explode(".", $field);
             
             
             
             if ( count($arps) > 1 )
                 return $field;
            
            
		return "\"". trim( $field )."\"";
	}
	
}

?>