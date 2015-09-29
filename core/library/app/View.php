<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class View extends Palm {
		private $layout = array();
		private $element = array();
		private $viewName= "";
		private $css = array();
		private $js = array();
        public function __construct($viewName=""){$this->viewName = $viewName; }
		public function layout($layout,$buffer=false)
		{
			$layout = APP_COMPONENT.strtolower($this->viewName).DS."layouts".DS.$layout.".pm";
			if (Basic::fileExist($layout)) {
				$this->layout = $layout;
				extract($this->getData(), EXTR_SKIP);
				ob_start();
				//$this->googleTracking();
				include($layout);
				if($buffer) {
					return ob_get_contents();
				}else{
					ob_get_flush();
				}
			} else {
				echo "ERROR: view is not defined.<br/>View : $layout ";
			}
		}
		public function element($element,$vars=array(),$buffer=false){
			$element = APP_COMPONENT.strtolower($this->viewName).DS."elements".DS.$element.".pm";
			if(file_exists($element)){
				$this->element = $element ;
				extract($vars, EXTR_SKIP);
				ob_start();
				include($element);
				if($buffer) {
					return ob_get_contents();
				}else{
					ob_get_flush();
				}
			} else {
				echo "ERROR: view is not defined.<br/>View : $element ";
			}
		}
		public function template($template="",$buffer=false){
			if(Basic::fileExist(TEMPLATE.$template.".pm")){
				extract($this->getData(),EXTR_SKIP);
				ob_start();
				// $this->googleTracking();
				include(TEMPLATE.$template.".pm");
				if($buffer) {
					return ob_get_contents();
				}else{
					ob_get_flush();
				}
			}else{
				echo "ERROR: Template is not defined.";
			}
		}
		public function block($block="",$vars=array(),$buffer=false){
			if(Basic::fileExist(TEMPLATE."blocks".DS.$block.".pm")){
				extract($vars,EXTR_SKIP);
				ob_start();
				include(TEMPLATE."blocks".DS.$block.".pm");
				if($buffer) {
					return ob_get_contents();
				}else{
					ob_get_flush();
				}
			}else{
				echo "ERROR: block is not defined.";
			}
		}
		public function googleTracking(){
			echo "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,article,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-63363037-1', 'auto');
  ga('send', 'pageview');

</script>";
		}
	}
