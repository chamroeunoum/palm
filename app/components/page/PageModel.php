<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class PageModel extends Model {
		public function __construct(){
			$this->calledClass(get_class($this));
		}

        public function getPage(){
			$result = $this->query( "SELECT * FROM users ");
			return $result->fetch(PDO::FETCH_OBJ) ;
        }
	}
