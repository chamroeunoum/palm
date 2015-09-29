<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class XML {
		private $db = "" ;
		private $doc = null ;
		private $xpath = null ;
		private static $instance = null ;
		private function __construct(){}
		private function XML(){}
		public static function getInstance(){
			if( XML::$instance === null ){
				XML::$instance = new XML();
			}
			return XML::$instance ;
		}
		public function open($databaseName=""){
			if(Basic::fileExist(DBXML.$databaseName.".xml")){
				$this->db = DBXML.$databaseName.".xml";
				$this->doc = new DOMDocument('1.0', 'utf-8');
				// let's have a nice output
				$this->doc->preserveWhiteSpace = false;
				$this->doc->formatOutput = true;
				$this->doc->load($this->db);
				$this->xpath = new DOMXPath($this->doc);
			}
		}
		public function query($query){
			$result = array();
			$nodes = $this->xpath->query($query);
			$result = $this->getResult($nodes);
			return $result ;
		}
		private function getResult($nodes){
			$result = array() ;
			$record = array("attrs"=>array(),"childs"=>array());
			foreach ( $nodes AS $node ){
				if($node->hasChildNodes()){
					foreach( $node->childNodes AS $subNode ){
						if($subNode->nodeName!=="#text"){
							if($subNode->hasAttributes()){
								$attributes = array();
								foreach($subNode->attributes AS $key => $val ){
									$attributes = array_merge( $attributes , array( $key => $val->value ) );
								}
								$record["attrs"] = $attributes ;
							}
							if($subNode->hasChildNodes()){
								$textcontent = array() ;
								foreach( $subNode->childNodes AS $subSubNode){
									$textcontent = array_merge($textcontent , array($subSubNode->nodeName => $subSubNode->nodeValue));
								}
								$record["childs"] = $textcontent;
							}
							$result[] = $record ; 
						}
					}
				}
				else{
					if($node->nodeName!=="#text"){
						if($node->hasAttributes()){
							$attributes = array();
							foreach($node->attributes AS $key => $val ){
								$attributes = array_merge( $attributes , array( $key => $val->value ) );
							}
							$record["attrs"] = $attributes ;
						}
						$result[] = $record ;
					}
				}
			}
			return $result;
		}
		public function getRecords($table="",$where=array()){
			if($table!=""){
				$query = "//$table" ;
				if(!empty($where)){
					$conditions = array();
					foreach($where AS $field=>$val ){
						$conditions[]='@'.$field.'="'.$val.'"';
					}
					$query.="/".(substr($table,0,strlen($table)-1))."[".implode(",",$conditions)."]";
				}
				return $this->query($query);
			}
		}
		public function save($table="", $nodes=array()){
			$this->doc->formatOutput = true;
			$table = $this->doc->getELementsByTagName($table)->item(0);
			$autoId = $table->getAttribute("autoId") + 1 ;
			$record = $this->doc->createElement((substr($table->nodeName,0,strlen($table->nodeName)-1)));
			if(!empty($nodes)){
				$record->setAttribute("id", $autoId );
				foreach($nodes AS $key => $val )
					$record->appendChild($key,$val);
				$table->appendChild($record);
				$table->setAttribute("autoId",$autoId);
				// substitute xincludes
				$this->doc->xinclude();
				$this->doc->save($this->db);
			}
		}
		//delete node specify by attribute value
		public function remove($table="", $id) {
			$table = (substr($table,0,strlen($table)-1)) ;
		    if( $table!='' || $id!='' )
		   		 $nodeList = $this->xpath->query('//'.$table.'[@id="'.$id.'"]');
		    else
		    	$nodeList = $this->xpath->query('//'.$table.'') ;
		    if ($nodeList->length){
		        $node = $nodeList->item(0) ;
		        $node->parentNode->removeChild($node) ;
		    }
		    $this->doc->save($this->db) ;
		}
		//function to update xml attribute
		public function updateAttribute($table="",$nodeName, $attrName, $idSearch,$newAttrValue) {
			$table = (substr($table,0,strlen($table)-1)) ;
		    if( $attrName !='' || $idSearch !='' )
		    $nodeList = $this->xpath->query('//'.$table.'/'.$nodeName.'[@'.$attrName.'="'.$idSearch.'"]');
		    else
		    $nodeList = $this->xpath->query('//'.$table.'/'.$nodeName );
		    
		    if ($nodeList->length){
		        $node = $nodeList->item(0) ;
		        $node->setAttribute( $attrName , $newAttrValue ) ;
		    }
		    $this->doc->save($this->db) ;
		}
		public function updateContent($table="",$node, $idSearch, $nodes=array()){
			$table = (substr($table,0,strlen($table)-1)) ;
			if( $table!='' || $idSearch!='' )
		    $nodeList = $this->xpath->query("//".$table.'/'.$node.'[@id="'.$idSearch.'"]');
		    else
		    $nodeList = $this->xpath->query('//'.$table.'/'.$node );
		    
		    if(is_array( $nodes ) && !empty( $nodes )){
		    	foreach( $nodeList AS $node ){
		    		if( $node->hasChildNodes()){
		    			foreach( $node->hasChilds AS $subnode ){
		    				$subnode->nodeValue = $nodes[$subnode->nodeName];
		    			}
		    		}
		    	}
		    }
		    $this->doc->save($this->db);
		}
		
		public function getPage($table="", $page=1, $perpage=2, $nodesearch ,$searchColume="" ){
			$page = $page < 1 ? 1 : $page ;
			$perpage = $perpage < 2 ? 2 : $perpage - 1 ;
			$start = ($page+($perpage*($page-1))) ;
			$stop = $start + $perpage ;
			$totalRecord = $this->xpath->evaluate("count(//" . $table . "/" . $nodesearch .")");
			$totalPage = ceil( $totalRecord / ($perpage + 1) ) ;
			$nodeList = $this->xpath->query("//" . $table . "/" . $nodesearch . "[position()>=" . $start . " and position()<=". ( $stop ) . "]" );
			$records = array();
			foreach ( $nodeList AS $node ){
				$record = array() ;
				if( $node->hasChildNodes()){
					foreach( $node->childNodes AS $subNode ){
						$record = array_merge($record , array($subNode->nodeName => $subNode->nodeValue) );
					}
				}
				$records[] = $record ;
			}
			return array("searchNode"=>$nodesearch,"page"=>$page,"perpage"=>$perpage,"totalPage"=>$totalPage,"totalRecord"=>$totalRecord,"records"=>($records===null?array():$records)) ;
		}
	}
