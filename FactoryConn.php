<?php

require_once("PostGreSQLConnection.php");
require_once("MySQLConnection.php");
require_once("ODBCConnection.php");
require_once("MSSQLConnection.php");

class FactoryConn{
    
    static function getConn( $tipo, $arr_conection, $var_name = "connection_system" ){
    
              $saida = null;
              
              if ( $tipo == self::DB_POSTGRESQL)
                  $saida = new PostGreSQLConnection ($arr_conection, $var_name);
              
              
              if ( $tipo == self::DB_MYSQL)
                  $saida = new MYSQLConnection($arr_conection, $var_name);
              
              
              if ( $tipo == self::DB_ODBC)
                  $saida = new ODBCConnection($arr_conection, $var_name);
              
              
              if ( $tipo == self::DB_SQLSERVER)
                  $saida = new MSSQLConnection($arr_conection, $var_name);
        
              return $saida;
    }
    
    
    
    const DB_POSTGRESQL = "postgres";
    const DB_MYSQL = "mysql";
    const DB_SQLSERVER = "sql server";
    const DB_ODBC = "odbc";

}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

