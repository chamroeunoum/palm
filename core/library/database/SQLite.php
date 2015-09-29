<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class SQLite {
		protected $datas = array();
		private $dbFile = "" ;
		private $sqlite = null ;
		private static $instance = null;
 		private function __construct($dbFile="") {
			$this->dbFile = $dbFile;
 		}
		
		public function connect($dbFile=""){
			$dbFile!==""?$this->dbFile = $dbFile:false;
			if($this->dbFile!==""){
				if( $this->sqlite === null )$this->sqlite = new SQLite3($this->dbFile);
				return $this->sqlite;
			}
			return false;
		}
		
		public static function getInstance($dbFile=""){
			if(SQLite::$instance===null){
				SQLite::$instance = new SQLite($dbFile);
			}
			return SQLite::$instance;
		}
			
		public function stringEscape($string){
			return SQLite3::escapeString($string);
		}
			
		public function queryData($query){
			$result = $this->sqlite->query($query);
			return $result;
		}
		
		public function getRecords($table,$fields=array(),$where=array(),$operator="AND"){
			$result = null ;
			if (isset($table)&&$table!=""){
				$query = "SELECT ".(!empty($fields)?implode(",",$fields):"*")." FROM $table " ;
                if(is_array($where)&&!empty($where)){
                    $conditions = array();
                    foreach( $where as $key => $val ){
						$conditions[] = $key." = " .":".$key;
					}
                    $query .= " WHERE ".implode(" ".$operator." ",$conditions)." ; " ;		
                }			
				$result = $this->queryData($query);
			}
			return $result;
		}
		
		public function getAsArray($table,$fields=array(),$where=array()){
			$query = $this->getRecords($table,$fields,$where);
			$result= array();
			 while(($row = $query->fetchArray(SQLITE3_ASSOC))!==false){
			 	$result[] = $row;
			}
			return $result;
		}
		
		public function getAsObject($table,$fields=array(),$where=array()){
			$obj = $this->getRecords($table,$fields,$where);
				while(($row = $obj->fetchArray(SQLITE3_ASSOC))!==false){
			 		$result[] = (object) $row;
				}	
			return $result;
		}
		
		public function countRows($table){
			return $this->sqlite->querySingle("SELECT COUNT(*) FROM $table");
		}
		
		public function save($table,$fields=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)){
				$insert="INSERT INTO $table (".implode(",",array_keys($fields)).") " ;
				$vars = array();
				foreach( $fields as $key => $val ){
					$vars[] = ":".$key;
				}
				$insert .= "VALUES(".implode(",",$vars).") ";				
				$stmt = $this->sqlite->prepare($insert);
				// Bind parameters to statement variables
				foreach ($fields as $key => $val ) {
				  	 $stmt->bindParam(":$key", $$key);
			    }
				// Loop through all arr and execute prepared insert statement
			 	foreach($fields as $key => $val ){
					// Set values to bound variables
		      		$$key = $val;
				}
			    // Execute statement
			    $stmt->execute();
				return true;
			} 
			return false;
		}
		
		public function delete($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query="DELETE FROM $table " ;
				$vars = array();		
				$query .= "WHERE ";
				foreach( $where as $key => $val ){
					$vars[] = $key." = " .":".$key;
				}
				$query .= implode(" AND ",$vars). " ;" ;
				$stmt = $this->sqlite->prepare($query);
				// Bind parameters to statement variables
				foreach ($where as $key => $val ) {
				  	 $stmt->bindParam(":$key", $$key);
			    }
				// Loop thru all arr and execute prepared insert statement
			 	foreach($where as $key => $val ){
					// Set values to bound variables
		      		$$key = $val;
				}
			    // Execute statement
			    $stmt->execute();
				return true;
			} 
			return false;
		}
		
		public function update($table,$fields=array(),$where=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)){
				$query="UPDATE $table SET " ;
				foreach($fields as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape($fieldValue) . "' ";
				}
				$query .= implode( " , " , $temp ) ;
				$query .= "WHERE ";
				foreach( $where as $key => $val ){
					$vars[] = $key." = " .":".$key;
				}
				$query .= implode(" AND ",$vars). " ;" ;

				$stmt = $this->sqlite->prepare($query);
				// Bind parameters to statement variables
				foreach ($where as $key => $val ) {
				  	 $stmt->bindParam(":$key", $$key);
			    }
				// Loop thru all arr and execute prepared insert statement
			 	foreach($where as $key => $val ){
					// Set values to bound variables
		      		$$key = $val;
				}
			    // Execute statement
			    $stmt->execute();
				return true;
			} 
			return false;
		}
		
		public function findAsArray($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				$vars = array();		
				$query .= "WHERE ";
				foreach( $where as $key => $val ){
					$vars[] = $key." = " .":".$key;
				}
				$query .= implode(" AND ",$vars). " ;" ;
				$stmt = $this->sqlite->prepare($query);
				// Bind parameters to statement variables
				foreach ($where as $key => $val ) {
				  	 $stmt->bindParam(":$key", $$key);
			    }
				// Loop thru all arr and execute prepared insert statement
			 	foreach($where as $key => $val ){
					// Set values to bound variables
		      		$$key = $val;
				}
			    // Execute statement
			    $res = $stmt->execute();
				$result= array();
				 while(($row = $res->fetchArray(SQLITE3_ASSOC))!==false){
				 	$result[] = $row;
				}
				return $result;	
			} 	
		}
		
		public function findAsObject($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				$vars = array();		
				$query .= "WHERE ";
				foreach( $where as $key => $val ){
					$vars[] = $key." = " .":".$key;
				}
				$query .= implode(" AND ",$vars). " ;" ;
				$stmt = $this->sqlite->prepare($query);
				// Bind parameters to statement variables
				foreach ($where as $key => $val ) {
				  	 $stmt->bindParam(":$key", $$key);
			    }
				// Loop thru all arr and execute prepared insert statement
			 	foreach($where as $key => $val ){
					// Set values to bound variables
		      		$$key = $val;
				}
			    // Execute statement
			    $res = $stmt->execute();
				$result= array();
				 while(($row = $res->fetchArray(SQLITE3_ASSOC))!==false){
				 	$result[] = (object) $row;
				}
				return $result;	
			} 	
		}
		
		public function getPage($table="",$page=1,$perpage=20,$search="",$searchFields = array() , $sortFields = array() , $sortCriteria = "ASC" ){
			$page=$page<1?1:$page;
			$perpage=$perpage<1?20:$perpage;
			$sortCriteria=$sortCriteria===""?"ASC":$sortCriteria;
			$query = " SELECT * FROM $table " ;
			if(!empty($searchFields)&&$search!==""){
				$tempSearchFields=array();
				foreach($searchFields AS $field ){
					$tempSearchFields[]= " `$field` LIKE '%".$this->stringEscape($search)."%' " ;
				}
				$query .= " WHERE " . implode(" OR ",$tempSearchFields);
				
			}
			if(!empty($sortFields)){
				$tempSortFields=array();
				foreach($sortFields AS $field ){
					$tempSortFields[]= " `$field` " ;
				}
				$query .= " ORDER BY " . implode(" , ",$tempSortFields) . " " . $sortCriteria . " " ;		
			}
			$totalRecords = $this->countRows($table);
			$totalPages = ceil($totalRecords/$perpage);
			$query .= " LIMIT ".(($page-1)*$perpage)." , ".$perpage." ; " ;
			$querys = $this->queryData($query);
			$result= array();
				 while(($row = $querys->fetchArray(SQLITE3_ASSOC))!==false){
				 	$result[] = (object) $row;
				}
			return array("search"=>$search,"page"=>$page,"perpage"=>$perpage,"totalpages"=>$totalPages,"totalrecords"=>$totalRecords,"records"=>($result===null?array():$result)) ;
		}
		
		public function get($key){
			return isset($this->datas[$key])?$this->datas[$key]:NULL;
		}
		
		public function close(){
			if(is_resource($this->sqlite))$this->sqlite->close(); 
		}
	}	