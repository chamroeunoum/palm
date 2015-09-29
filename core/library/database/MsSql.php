<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class MsSql{
		private $host = "" ;
		private $dbname = "" ;
		private $user = "" ;
		private $pwd = "" ;
		
		private static $instance =  null ;
		private $link = null ;
		public function __construct(){}
		public function MsSql(){}
		
		public static function getInstance(){
			if( MsSql::$instance === null ){
				MsSql::$instance = new MsSql();
			}
			return MsSql::$instance ;
		}
		public function demoConnect(){
			echo "connected" ; 
		}
		public function open($host,$user,$pass,$db){
			$this->host = $host ;
			$this->user = $user ;
			$this->pwd = $pass ;
			$this->dbname = $db ;
			$this->connect();
		}
		private function connect(){
			if(!is_resource($this->link)){
				$this->link = sqlsrv_connect($this->host, array("database"=>$this->dbname,"uid"=>$this->user,"pwd"=>$this->pwd));
				!is_resource($this->link)?die("<br/>Description: " . sqlsrv_errors()) : true;
			}
		}
		public static function stringEscape($string){
			return sqlsrv_real_escape_string($string);	
		}
		public function numRow($result){
			return is_resource($result)? sqlsrv_num_rows($result): null;
		}
		public function query($query){
			$result = null ;
			if($query!==""&&$query!==null){
				$this->connect();
				//sqlsrv_query($this->link, "SET NAMES UTF-8");
				$result = sqlsrv_query($this->link, $query) or die( "<br/>Description: " . sqlsrv_errors() );
			}
			return $result ;
		}
		public function getRecords($table,$fields=array(),$where=array(),$operator="AND"){
			$result = null ;
			if (isset($table) && $table!=""){
				$query = "SELECT ".(!empty($fields)?implode(",",$fields):"*")." FROM $table " ;
				if(is_array($where)&&!empty($where)){
					$conditions = array();
					foreach( $where AS $field => $value ){
						$conditions[] = $field." '".$this->stringEscape($value)."' " ;
					}
					$query .= " WHERE ".implode(" ".$operator." ",$where)." ; " ;
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
				while(($row=sqlsrv_fetch_assoc($rows))!==false)$result[]=$row;
			}
			return $result ;
		}
		
		public function getAsObject($table,$fields=array(),$where=array()){
			$result = $this->getRecords($table,$fields,$where);
			if($result!==null){
				$rows = $result ;
				$result = array();
				while(($row = sqlsrv_fetch_object($rows))!==false)$result[]=$row;
			}
			return $result ;
		}
		
		public function save($table,$fields=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)){
				$query = "INSERT INTO $table SET " ;
				$temp = array();
				foreach($fields as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape($fieldValue) ."' ";
				}
				$query .= implode( " , " , $temp ) . " ; " ;
				return $this->query($query);
			}
			return false;
		}
		public function update($table,$fields=array(),$where=array()){
			if(isset($table)&&$table!==""&&is_array($fields)&&!empty($fields)&&is_array($where)&&!empty($where)){
				$query = "UPDATE $table SET " ;
				foreach($fields as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape($fieldValue) . "' ";
				}
				$query .= implode( " , " , $temp ) ;
				$temp=array();
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $fieldValue . "' " ;
				}
				$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				return $this->query($query);
			}
			return false;
		}
		public function delete($table,$where=array(),$deleteSet=false){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "DELETE FROM $table " ;
				if(!$deleteSet&&!empty($where)){
					//$query .= " WHERE " . implode( " " , $where ) . " ; " ;
					foreach($where as $fieldName => $fieldValue ){
						$temp[] = $fieldName . " = '" . $this->stringEscape( $fieldValue ) . "' " ;
					}
					$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				}
				else if($deleteSet&&!empty($where)){
					$where = is_array($where)&&!empty($where)?implode(",",$where):$where;
					$query .= " WHERE id IN (" . $where . ") ; " ;
				}
				return $this->query($query);
			}
			return false;
		}
		public function findAsArray($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				$temp=array();
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape( $fieldValue ) . "' " ;
				}
				$query .= " WHERE " . implode( " AND " , $temp ) . " ; " ;
				$result = $this->query($query);
				if($result!==null){
					$rows = $result ;
					$result = array();
					while(($row=sqlsrv_fetch_assoc($rows))!==false)$result[]=$row;
				}
				return $result===null?$result:(empty($result)?null:$result[0]);
			}
		}
		public function findAsObject($table,$where=array()){
			if(isset($table)&&$table!==""&&is_array($where)&&!empty($where)){
				$query = "SELECT * FROM $table " ;
				$temp=array();
				foreach($where as $fieldName => $fieldValue ){
					$temp[] = $fieldName . " = '" . $this->stringEscape( $fieldValue ) . "' " ;
				}
				$query .= " WHERE " . implode( " " , $temp ) . " ; " ;
				$result = $this->query($query);
				if($result!==null){
					$rows = $result ;
					$result = array();
					while(($row=sqlsrv_fetch_object($rows))!==false)$result[]=$row;
				}
				return $result===null?$result:(empty($result)?null:$result[0]);
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
			$totalRecords = $this->numRows($this->query($query));
			$totalPages = ceil( $totalRecords / $perpage );
			$query .= " LIMIT ".(($page-1) * $perpage)." , ".$perpage." ; " ;
			$result = $this->query($query);
			if($result!==null){
				$rows = $result ;
				$result = array();
				while(($row=sqlsrv_fetch_object($rows))!==false)$result[]=$row;
			}
			return array("search"=>$search,"page"=>$page,"perpage"=>$perpage,"totalpages"=>$totalPages,"totalrecords"=>$totalRecords,"records"=>($result===null?array():$result)) ;
		}
		public function close(){
			if(is_resource($this->link))sqlsrv_close($this->link);
		}
	}
	