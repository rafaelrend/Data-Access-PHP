<?
class connAccess{


       public static function fastOne(IDbPersist $oConn,  $table, $where = "", $order = ""){
	   
	         $ar = connAccess::fastQuerie( $oConn,  $table, $where, $order );
		
		
			 if ( count($ar) > 0 ){
			 
			       $ret = $ar[0];
				   connAccess::removeIntegerColumns( $ret );
			       return $ret;
			 } 
			 
			 return null;
	   }
	   
	   public static function removeIntegerColumns( &$reg ){
	   
			 foreach( $reg as $key=>$value ){
			      if ( is_numeric($key) )
				    unset($reg[ $key ] );
			 }
	          
	   }

       public static function fastQuerie(IDbPersist $oConn,  $table, $where = "", $order = ""){
	           
			   $sql = " select * from " . $table;
			   
			
			   if ( $where != "" )
			       $sql .= " where " . $where;
				   
				   
			   if ( $order != "" )
			       $sql .= " order by " . $order;
				   
				//echo("<pre>". $sql . "</pre>");
	            return $oConn->fetchData( $sql );
			     
	   }
	   
	   public static function fetchData(IDbPersist $oConn,  $sql ){
	   
	            return $oConn->fetchData( $sql );
	   }
	   
	    public static function executeScalar(IDbPersist $oConn,  $sql ){
	   
	            $ar =  $oConn->fetchData( $sql );
				if ( count($ar) > 0 )
				     return $ar[0][0];
					 
			return "";
	   }
	   
	   public static function executeCommand(IDbPersist $oConn,  $sql ){
	          $oConn->executeCommand( $sql );
	   }
	   
	   public static function formatValue( $value ){
			   
				  if ( is_null($value) ){
					  return "NULL";
				  }
				  
				  if ( is_double($value) )
				     return $value;
					 
				  if ( is_integer($value))
				     return $value;
					
				if ( is_numeric($value) && strlen($value) > 1 && substr($value,0,1) == "0" ){                                   
                                    
			                  return "'".str_replace("'","''",$value)."'";
                                }
                                  
                                  if ( is_numeric($value) && strlen($value) < strlen("556638027774725") -2 )
				     return $value;
					
					 
			return "'".str_replace("'","''",$value)."'";
	   }
	   
	   public static function Insert(IDbPersist $oConn, $arr, $table, $pk, $auto_increment = true){
	   
	         if ( $auto_increment ){
			       unset( $arr[$pk] );
			 }
	         $sql = " insert into ". $oConn->formatField($table) . " ( " ;
			 
			 $fields = "";
			 $values = "";
			 
			 foreach( $arr as $key=>$value ){
			        if ( $fields  != "" ){
					    $fields  .= " ,";
				        $values .= " , ";
			         }
			        $fields .= $oConn->formatField($key);
			        $values .= connAccess::formatValue($arr[$key]);
			        //echo( $key . " .. " . $arr[$key] . "<br>");
			  }
			  
		$sql .= $fields .") values ( " . $values . " ) ";
	
		//die ( $sql );
                
               // if ( $table == "usuario")
                 //   die ( $sql );
                
		$oConn->executeCommand( $sql );
		
		//if ( $table == "inspecao_item_deleted" )
		  //  die( $sql);
		
		return connAccess::executeScalar($oConn, "select max( ".$oConn->formatField($pk). ") as maxid from ". 
			                $oConn->formatField($table) );
	   }
	   
	   
	   
	   public static function Update(IDbPersist $oConn, $arr, $table, $pk){
	   
	    
	         $sql = " update ". $oConn->formatField($table) . " set " ;
			 
			 $fields = "";
			 $value = "";
			 
			 foreach( $arr as $key=>$value ){
			        
					if ( $key == "" )
					   continue;
					
					if ( $key == $pk )
					   continue;
					   
			        if ( is_numeric($key) )
					   continue;  
					  
			        if ( $fields  != "" ){
					    $fields  .= " ,";
			         }
			     
				 $fields .= $oConn->formatField($key) . " = " . 
					          connAccess::formatValue($arr[$key]);
			  
			  }
			  
			  $sql .= $fields ." where  ". $oConn->formatField($pk) . " = " . connAccess::formatValue( $arr[$pk]);
			  
			 // print_r( $arr );
			 // die ( $sql );
			  $vai = $oConn->executeCommand( $sql );
	                  //echo ( $sql );
		if ( !$vai && @$_GET["debug"]=="1" ){
			die("pau na querie: " . $sql);	
		}
	
	   }
	   
	    public static function Delete(IDbPersist $oConn, $arr, $table, $pk){
	   
	         $sql = " delete from  ". $oConn->formatField($table) . " " ;

			  
			  $sql .= " where  ". $oConn->formatField($pk) . " = " . connAccess::formatValue( $arr[$pk]);
			 
			  $oConn->executeCommand( $sql );
	   
	   }
	
		/*
		  @Insert NULL value at blank fields.
		*/
		public static function nullBlankColumns(&$registro)
		{
			foreach ($registro as $key=>$value)
			{
				if ( $registro[$key] == null ||
						$registro[$key] == "")
				{ 
					try{
						$registro[$key] = null ;
						
					}
					catch(exception $exp){}
				}
			}
		}
		
	public static function preencheArrayForm(&$registro, &$form, $exceto="", $prefixo = "")
	{
		if ( ! is_array($registro))
			return;
		
		
		foreach ($registro as $key=>$value)
		{/*
				print_r( $form );
				echo $key. " -- ". strpos($exceto , "|". $key."|")."<br>";*/
			
			if ( strlen($exceto)==0 || !strpos($exceto , "|". $key."|") )
			{
				
				if ( ! array_key_exists($prefixo.$key,$form))
					continue; 
				
				
				try{
					$registro[$key] = $form[$prefixo.$key];
					if ( $registro[$key] == null || $registro[$key] == "")
						$registro[$key]  = @$_POST[$prefixo.$key];
				}
				catch(exception $exp){}
			}
		}
		
	}

}


?>