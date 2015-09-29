<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Element {
		private $tag = "" ;
		private $attrs = array() ;
		private $text = "" ;
		private $entTag = true;
		private $childs = array();
		public function __construct($tag="",$attrs=array(),$endTag=false,$text=""){
			$this->tag = $tag ;
			$this->attrs = $attrs;
			$this->text = $text ;
			$this->endTag = $endTag ;
		}
		public function setTag($tag=""){$this->tag=$tag;}
		public function setAttrs($attrs=array()){$this->attrs=$attrs;}
		public function setText($text=""){$this->text=$text;}
		public function setEndTag($endTag=true){$this->endTag=$endTag;}
		public function getTag(){return $this->tag;}
		public function getAttrs(){return $this->attrs;}
		public function getText(){return $this->text;}
		public function getEndTag(){return $this->endTag;}
		public function addAttr($attr=array()){
			if(is_array($attr)&&!empty($attr)){
				$this->attrs = array_merge($this->attrs,$attr);
				return $attr;
			}
			return false ;
		}		
		public function removeAttr($attrKey=""){
			if($attrKey!==""&&isset($this->attrs[$attrKey])){
				$temp = $this->attrs[$attrKey];
				unset($this->attrs[$attrKey]);
				return $temp;
			}
			return false;
		}
		public function getAttr($attrKey=""){
			return $attrKey!==""?isset($this->attrs[$attrKey])?$this->attrs[$attrKey]:null:null;
		}
		public function childs(){
			return count($this->childs);
		}
		public function addChild($element=null){
			if(is_object($element))return $this->childs[]=$element;
			return false ;
		}
		public function removeChild($element=null){
			if(is_object($element)){
				$index = array_search($element,$this->childs) ;
				$temp = $this->childs[$index];
				unset($this->childs[$index]);
				return $temp;
			}
			return false;
		}
		public function toString(){
			$layout = "<$this->tag" ;
			foreach($this->attrs as $key => $val ){
				$layout .= " $key=\"$val\"" ;
			}
			$childLayout = "" ;
			if($this->childs()>0){
				foreach( $this->childs as $child )$childLayout.=$child->toString();
			}
			$layout .= $this->endTag?" >$this->text$childLayout</$this->tag>":" />" ;
			return $layout ;
		}
	}
