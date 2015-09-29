<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class MySql {
		private $host = "" ;
		private $user = "" ;
		private $pass = "" ;
		private $db = "" ;

		private $pdo = null ;
		private static $instance = null ;
		private static $lastInsertId = 0;

		private function __construct(){}
		private function MySql(){}
		public static function getInstance(){
			if( MySql::$instance === null ){
				MySql::$instance = new MySql();
			}
			return MySql::$instance ;
		}
		public static function stringEscape($string){
			//return mysql_real_escape_string($string);
			return htmlentities($string,ENT_QUOTES);
		}
		public function open($host,$user,$pass,$db){
			$this->host = $host ;
			$this->user = $user ;
			$this->pass = $pass ;
			$this->db = $db ;
			$this->connect() ;
		}
		private function connect(){
			if(!is_resource($this->pdo)){
				try{
					$this->pdo = new PDO('mysql:host='.Config::MYSQL_HOST.';dbname='.Config::MYSQL_DB_NAME.';charset=utf8;', Config::MYSQL_USER, Config::MYSQL_PASS);
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
				}
			}
		}
		public function query($query){
			$result = null ;
			if($query!=="" && $query!==null){
				$this->connect();
				try {
					$result = $this->pdo->query($query) ;
				}
				catch( PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
				}
			}
			return $result;
		}
		
		public function getRecords($table,$fields=array(),$where=array(),$operator="AND"){
			$result = null ;
			if (isset($table)&&$table!=""){
				$query = "SELECT ".(is_array($fields)&&!empty($fields)?implode(",",$fields):"*")." FROM $table " ;
                if(is_array($where)&&!empty($where)){
                    $wheres = array();
                    foreach( $where AS $field => $value ){
                        $wheres[] = $field.($value!=""?" '".$this->stringEscape($value)."' ":"") ; 
                    }
                    $query .= " WHERE ".implode(" ".$operator." ",$wheres)." ; " ;
                }
				$result = $this->query($query);
			}
			return $result;
		}
		public function getAsArray($table,$fields=array(),$where=array()){
			$result = $this->getRecords($table,$fields,$where);
			if($result!==null){
				$rows = $result ;
				$result = array();
				while(($row=$rows->fetch(PDO::FETCH_ASSOC)))$result[]=$row;
			}
			return $result ;
		}
		public function getAsObject($table,$fields=array(),$where=array()){
			$result = $this->getRecords($table,$fields,$where);
			if($result!==null){
				$rows = $result ;
				$result = array();
				while(($row = $rows->fetch(PDO::FETCH_OBJ)))$result[]=$row;
			}
			return $result ;
		}
		public function getInsertId(){
			return $this->pdo->lastInsertId();
		}
		public function save($table, $fields=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)){
				$query = "INSERT INTO $table SET " ;
				$temp = array();
				foreach($fields as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape($fieldValue) ."' ";
				}
				$query .= implode( " , " , $temp ) . " ; " ;
				return $this->query($query)?$this->findAsObject($table,array("id="=>$this->pdo->lastInsertId("id"))):false;
			} 
			return false;
		}
		public function update($table,$fields=array(),$where=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)&&is_array($where)&&!empty($where)){
				$query = "UPDATE $table SET " ;
				foreach($fields as $fieldName => $fieldValue ){
					$temp[] = $fieldName . "='" . $this->stringEscape($fieldValue) . "' ";
				}
				$query .= implode( " , " , $temp ) ;
				$temp=array();
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . "'" . $this->stringEscape($fieldValue) . "' " ;
				}
				$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				return $this->query($query)?$this->findAsObject($table,$where):false;
			}
			return false;
		}
		public function delete($table,$where=array(),$deleteSet=false){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$queryget= "SELECT * FROM $table ";
				$query = "DELETE FROM $table " ;
				if(!$deleteSet&&!empty($where)){
					//$query .= " WHERE " . implode( " " , $where ) . " ; " ;
					foreach($where as $fieldName => $fieldValue ){
						$temp[] = $fieldName . "'" . $this->stringEscape($fieldValue) . "' " ;
					}
					$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
					$queryget .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				}
				else if($deleteSet&&!empty($where)){
					$where = is_array($where)&&!empty($where)?implode(",",$where):$where;
					$query .= " WHERE id IN (" . $where . ") ; " ;
					$queryget .= " WHERE id IN (" . $where . ") ; " ;
				}
				$result = $this->query($queryget);
				if(is_object($result)){
					$rows = $result ;
					$result = array();
					while(($row=$rows->fetch(PDO::FETCH_OBJ))!==false)$result[]=$row;
				}
				return $this->query($query)?$result:false;
			}
			return false;
		}
		public function findAsArray($table,$where=array()){		
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " '" . $this->stringEscape($fieldValue) . "' " ;
				}
				$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				$result = $this->query($query);
				if($result!==null){
					$rows = $result ;
					$result = array();
					while(($row=$rows->fetch(PDO::FETCH_ASSOC))!==false)$result[]=$row;
				}
				return $result===null?$result:(empty($result)?null:$result[0]);
			}
		}
		public function findAsObject($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				$temp=array();
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " '" . $this->stringEscape($fieldValue) . "' " ;
				}
				$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				$result = $this->query($query);
				if($result!==null){
					$rows = $result ;
					$result = array();
					while(($row=$rows->fetch(PDO::FETCH_OBJ))!==false)$result[]=$row;
				}
				return $result===null?$result:(empty($result)?null:$result[0]);
			}
		}
		public function getPage($table="",$page=1,$perpage=20,$search="",$searchFields = array() , $sortFields = array() , $sortCriteria = "ASC" ,$wheres = array() ){
			$page=$page<1?1:$page;
			$perpage=$perpage<1?20:$perpage;
			$sortCriteria=$sortCriteria===""?"ASC":$sortCriteria;
			$searchString=array();
			$query = " SELECT * FROM $table " ;
			$searchString["search"]=array("value"=>"","fields"=>array());
			$searchString["wheres"]=array();
			if(!empty($searchFields)&&(isset($search)&&$search!=="")){
				$searchString["search"]=array("value"=>$search,"fields"=>$searchFields);
				$tempSearchFields=array();
				foreach($searchFields AS $field ){
					$tempSearchFields[]= " `$field` LIKE '%".$this->stringEscape($search)."%' " ;
				}
				$query .= " WHERE " . implode(" OR ",$tempSearchFields);
			}
			if(!empty($wheres)){
				$tempWhereFields=array();
				foreach($wheres AS $field=>$val ){
					$searchString["wheres"][str_replace("=","",$field)]=$val;
					$tempWhereFields[]= " $field'".$this->stringEscape($val)."' " ;
				}
				//$query .= " WHERE " . implode(" OR ",$tempSearchFields);
				$query .= (strpos($query,"WHERE")!==false?" AND ":" WHERE ") . "(". implode(" AND ",$tempWhereFields) .")";
			}
			if(!empty($sortFields)){
				$tempSortFields=array();
				foreach($sortFields AS $field ){
					$tempSortFields[]= " `$field` " ;
				}
				$query .= " ORDER BY " . implode(" , ",$tempSortFields) . " " . $sortCriteria . " " ;
			}

			$result = $this->query($query);
			$totalRecords = $result->rowCount();
			$totalPages = ceil($totalRecords/$perpage);
			$query .= " LIMIT " .($page-1)*$perpage . " , " . $perpage;
			$result = $this->query($query);
			if($result!==null){
				$rows = $result ;
				$result = array();
				while($row = $rows->fetch(PDO::FETCH_ASSOC) )$result[]=$row;
			}
			return array("conditions"=>$searchString,"page"=>$page,"perpage"=>$perpage,"totalpages"=>$totalPages,"totalrecords"=>$totalRecords,"records"=>($result===null?array():$result)) ;
		}
		public function close(){
			if(is_resource($this->pdo))$this->pdo = null; 
		}
		public function __destruct(){
			$this->close();
		}
	}
		