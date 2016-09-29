<?php

require_once("PostGreSQLConnection.php");
require_once("MySQLConnection.php");

class FactoryConn{
    
    static function getConn( $tipo ){
    
              $saida = null;
              
              if ( $tipo == "postgres")
                  $saida = new PostGreSQLConnection();
              
              
              if ( $tipo == "mysql")
                  $saida = new MYSQLConnection();
        
              return $saida;
    }

}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

