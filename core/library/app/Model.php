<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	/**
	 * @author chamroeunoum
	 */
	class Model extends Palm {
		/**
		 ** @var $driver is the identifier of database driver
		 ** Example:
		 *			1 => MYSQL
		 *			2 => XML
		 *			3 => SQLITE
		 *			4 => MSSQL
		 */
		const MYSQL = 1 ;
		const XML = 2 ;
		const SQLITE = 3 ;
		const MSSQL = 4 ;
		/* Type of the connection */
		private $driver = 1 ;
		private $calledClass = "" ;
		private $modelName = "" ;
		/* Constructor default */
		public function __construct(){}
		public function calledClass($calledClass=""){
			if($calledClass!=""){$this->calledClass = $calledClass;$this->modelName=(Basic::getPlural(str_replace("Model","",$this->calledClass)));}
		} 
		/**
		 * 
		 * @param number $driver
		 * => Example: Model::MYSQL, Model::SQLITE, Model::XML
		 */
		public function open($driver=1){
			$this->calledClass();
			switch($driver){
				case Model::XML :
					$this->driver = Model::XML;
					XML::getInstance()->open(Config::XML_DB_NAME);
					break;
				case Model::SQLITE :
					$this->driver = Model::SQLITE ;
					break;
				case Model::MSSQL :
					$this->driver = Model::MSSQL ;
					MsSql::getInstance()->open(Config::MSSQL_HOST,Config::MSSQL_USER,Config::MSSQL_PASS,Config::MSSQL_DB_NAME);
					break;
				default:
					/* Default connection is mysql*/
					$this->driver = Model::MYSQL;
					MySql::getInstance()->open(Config::MYSQL_HOST,Config::MYSQL_USER,Config::MYSQL_PASS,Config::MYSQL_DB_NAME);
					break;
			}
		}
		public function getModelName(){
			return $this->modelName;
		}
		/**
		 * @return Model::MYSQL OR Model::SQLITE OR Model::XML
		 */
		public function getConnectionType(){
			return $this->driver;
		}
		/**
		 * @param string $query
		 * @return Resource#id of driver
		 */
		public function query($query){
		    $result = null ;
		    switch($this->driver){
                case Model::XML:
                    $result = XML::getInstance()->query($query);
                    break;
                case Model::SQLITE:
                    $result = SQLITE::getInstance()->query($query);
                    break;
                case Model::MSSQL :
                	$result = MsSql::getInstance()->query($query) ;
                	break;
                default:
                    $result = MySql::getInstance()->query($query);
                    break;
		    }
            return $result ;
		}

		/**
		 * @author Noeur Phireak
		 * @param array()
		 * @return as Array();
		 */
		
		public function getRecord($where=array()){
			$result = NULL ;
			switch ($this->driver){
				case Model::XML :
					$result = XML::getInstance()->getRecords($this->modelName,$where );
					break;
				case Model::SQLITE:
					$result = SQLite::getInstance()->getRecords($this->modelName,$where );
					break;
				case Model::MSSQL:
					$result = MsSql::getInstance()->getRecords($this->modelName,$where );
				default :
					$result =MySql::getInstance()->getRecords($this->modelName,$where );
					break;
			}
			return $result ;
		}
		
		public function save($fields=array()){
		    $result = array();
		    switch($this->driver){
                case Model::XML:
                    $result = XML::getInstance()->save($this->modelName,$fields);
                    break;
                case Model::SQLITE:
                	$result = SQlite::getInstance()->save($this->modelName,$fields);
                case Model::MSSQL:
                	$return = MsSql::getInstance()->save($this->modelName,$fields);
                default:
                    $result = MySql::getInstance()->save($this->modelName,$fields);
                    break;
		    }
			return $result;
		}
		public function getAll($fields=array(),$wheres=array()){
			$result = array();
			switch($this->driver){
				case Model::XML:
					$result = XML::getInstance()->getAll($this->modelName,$fields,$wheres);
					break;
				case Model::SQLITE:
					$result = SQlite::getInstance()->getAll($this->modelName,$fields,$wheres);
				case Model::MSSQL:
					$return = MsSql::getInstance()->getAll($this->modelName,$fields,$wheres);
				default:
					$result = MySql::getInstance()->getAll($this->modelName,$fields,$wheres);
					break;
			}
			return $result;
		}
		public function update($fields=array(),$where=array()){
			$result = array() ;
			switch ($this->driver){
				case Model::XML:
					$result = XML::getInstance()->updateContent($this->modelName,$fields,$where);
					break;
				case Model::SQLITE:
					$result = SQLITE::getInstance()->update($this->modelName,$fields,$where );
					break;
				case Model::MSSQL :
					$result = MSSQL::getInstance()->update($this->modelName,$fields,$where );
					break;
				default:
					$result = Mysql::getInstance()->update($this->modelName,$fields,$where ) ;
					break;
			}
			return $result ;
		}
		
		public function delete($where=array(),$deleteSet=false){
			$result = array();
			switch($this->driver){
				case Model::XML:
					$result = XML::getInstance()->remove($this->modelName,$where,$deleteSet);
					break;
				case Model::SQLITE:
					$result = SQLITE::getInstance()->delete($this->modelName,$where,$deleteSet);
					break;
				case Model::MSSQL:
					$result = MSSQL::getInstance()->delete($this->modelName,$where,$deleteSet);
					break;
				default:
					$result = MySql::getInstance()->delete($this->modelName,$where,$deleteSet);
					break;
			}
			return $result;
		}
		public function fetchAsArray($fields=array(),$where=array()){
			$result = array();
			switch ($this->driver){
				case Model::MSSQL : 
					$result = MSSQL::getInstance()->getAsArray($this->modelName,$fields,$where);
					break;
				case Model::SQLITE:
					$result = SQLITE::getInstance()->getAsArray($this->modelName,$fields,$where);
					break;
				case Model::XML:
					$return = XML::getInstance()->getAsArray($this->modelName,$fields,$where );
					break;
				default:
					$result = MySql::getInstance()->getAsArray($this->modelName,$fields,$where);
					break;
			}
			return $result;
		}
		/**
		 * @author Phon Pisey
		 * @param array()
		 * @return as Array();
		 */
		public function fetchAsObject($fields=array(),$where=array()){
			$result = array();
			switch ($this->driver){
				case Model::MSSQL :
					$result = MSSQL::getInstance()->getAsObject($this->modelName,$fields,$where);
					break;
				case Model::SQLITE:
					$result = SQLITE::getInstance()->getAsObject($this->modelName,$fields,$where);
					break;
				case Model::XML:
					$return = XML::getInstance()->getAsObject($this->modelName,$fields,$where);
					break;
				default:
					$result = MySql::getInstance()->getAsObject($this->modelName,$fields,$where);
					break;
			}
			return $result;
		}
		public function findAsArray($where=array()){
			$result = array();
			switch ($this->driver){
				case Model::MSSQL :
					$result = MSSQL::getInstance()->findAsArray($this->modelName,$where);
					break;
				case Model::SQLITE:
					$result = SQLITE::getInstance()->findAsArray($this->modelName,$where);
					break;
				case Model::XML:
					$return = XML::getInstance()->findAsArray($this->modelName,$where );
					break;
				default:
					$result = MySql::getInstance()->findAsArray($this->modelName,$where);
					break;
			}
			return $result;
		}
		public function findAsObject($where=array()){
			$result = null ;
			switch( $this->driver ){
				case Model::XML:
					$result = XML::getInstance()->findAsObject($this->modelName,$where);
					break;
				case Model::MSSQL :
					$result = MsSql::getInstance()->findAsObject($this->modelName,$where);
					break;
				case Model::SQLITE :
					$result = SQLite::getInstance()->findAsObject($this->modelName,$where);
					break;
				default:
					$result = MySql::getInstance()->findAsObject($this->modelName,$where);
					break;
			} 
			return $result ;
		}
		public function getList($page=1,$perpage=20,$search="",$searchColumns = array() , $sortColumn = array() , $sortCriteria = "ASC" , $wheres =array()){
			$result = NULL ;
			switch ($this->driver){
				case Model::XML:
					$result = XML::getInstance()->getPage($this->modelName,$page,$perpage,$search );
				case Model::SQLITE:
					$result = SQLITE::getInstance()->getPage($this->modelName,$page,$perpage,$search,$searchColumns,$sortColumn,$sortCriteria);
					break;
				case Model::MSSQL:
					$result = MSSQL::getInstance()->getPage($this->modelName,$page,$perpage,$search,$searchColumns,$sortColumn,$sortCriteria);
					break;
				default :
					$result = MySql::getInstance()->getPage($this->modelName,$page,$perpage,$search,$searchColumns,$sortColumn,$sortCriteria,$wheres);
					break;
			}
			return $result ;
		}
		public function getInsertId(){
			return MySql::getInstance()->getInsertId();
		}
	}
