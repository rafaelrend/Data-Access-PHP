<?
interface IDbPersist{
       
	   function describleTable($nome_table);
	   
	   function connect();
	   
	   function disconnect();
	   
	   function executeCommand($sql);
	   
	   function fetchData($sql);
	   
	   
	   function getConnection();
	   
	   function formatField($field);

}


?>