<?php
require_once("IDbPersist.php");
require_once("FactoryConn.php");
require_once("connAccess.php");



   $arr_conection = array("host"=>"localhost",
                "post"=>"5432",
                "user"=>"postgres",
                "password"=>"postgres",
                "dbname"=>"dblocal",
                "schema"=>"public",
                "initialcommand"=>"SET NAMES 'LATIN1'; SET CLIENT_ENCODING TO 'LATIN1'; ",
                "client_encoding"=>"LATIN1"
                );

   $var_name = "conn_pgsql";
   
   
   //Bridge pattern to implement 4 types of database (mysql, postgreSql, sql server , odbc) using same methods.
   //similar as doctrine or Zend framework, but using native methods to faster perfomance.
   $conn = FactoryConn::getConn( FactoryConn::DB_POSTGRESQL, $arr_conection, $var_name);
   
   $reg = $conn->describeTable("customer");
   $reg["name"] = "Name of customer";
   $reg["createdata"] = date("y-m-d");
   $reg["age"] = 20;
   $reg["height"] = 100.4;
   connAcess::nullBlankColumns($reg);
   
   //Insert new register, passing server database connection firts
   $new_id = connAcess::Insert($conn,$reg,"customer","id",true);
   
   
   //fetch data, passing server database connection firts
   $list_data = connAcess::fetchData($conn, " select * from customer ");


?>