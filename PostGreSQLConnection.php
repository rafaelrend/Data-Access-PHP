<?
//Connect to postgre DataBase
class PostGreSQLConnection implements IDbPersist{

       function describeTable($name_table){
		
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
	
	public $_Conn;
	public $_nomeConn = "conn_pgsql";
	var $arr_conection;
        
	function garanteSchema(){
		
		$conn = $this->getConnection();
		
		if ( constant("pg_schema") != "" ){
			
			pg_query($conn, " set search_path = \"".constant("pg_schema")."\"");	
		}	
	}
        
        
        function __construct( $arr_conection = array(), $name_conn = "conn_pgsql"  ){
            
               $this->arr_conection = $arr_conection;
               $this->_nomeConn = $name_conn;
               
        } 
        
	
	function connect(  ){
            
            if (is_null($this->arr_conection))
                return;
            
            $arr_conection = $this->arr_conection;
            
            if ( @$arr_conection["port"] == "")
                $arr_conection["port"] = 5432; 
		
		$str_conection = "host=".$arr_conection["host"]." port=". $arr_conection["port"]." dbname=". $arr_conection["dbname"]." user=".$arr_conection["user"]." password=".$arr_conection["password"];
		
		//die ( $str_conection );
		$conn =   pg_connect($str_conection);
		
		if ( @$arr_conection["schema"]  != "" ){
			
			pg_query($conn, " set search_path = \"".$arr_conection["schema"]."\"");	
		}
		

		
		if ( @$arr_conection["initialcommand"]  != "" ){
				
			pg_exec($conn, $arr_conection["initialcommand"] );
		}
		if ( @$arr_conection["client_encoding"]  != "" ){
			
		    pg_set_client_encoding($conn, $arr_conection["client_encoding"]);
		}
		
		
		if ( !$conn ){
			die(" trouble when tried connect to PostGreSQL host ");
		}
		
		$GLOBALS[$this->_nomeConn] = $conn;
		//PostGreSQLConnection::$_Conn = $conn;
		
	}
	
	
	function getConnection(){
		
		if ( ! isset( $GLOBALS[$this->_nomeConn]  ))
			$this->connect();
		
		
		//PostGreSQLConnection::$_Conn
		
		return  $GLOBALS[$this->_nomeConn];
		//return PostGreSQLConnection::$_Conn;
		
	}
	
	function disconnect(){
		
		$conn = $this->getConnection();
		
		pg_close( $conn);
		
		$this->_Conn = null;
		
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