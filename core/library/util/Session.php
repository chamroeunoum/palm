<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Session {
		public static function start(){
			session_save_path( DATA_FOLDER . "tmp" . DS );
			if ( !isset( $_SESSION ) ) session_start() ;
		}
		
		public static function destroy(){
			session_destroy();
		}
		
		public static function exist( $sessionName ){
			if ( isset( $_SESSION ) && isset($_SESSION[ $sessionName ]) ){
				return true;
			}
			return false ;
		}
		
		public static function get( $sessionName ){
			if ( isset( $_SESSION[ $sessionName ] ) ){
				return $_SESSION[ $sessionName ];
			}
			return null ;
		}
		
		public static function set( $sessionName , $value = false ){
			return $_SESSION[ $sessionName ] = $value ;
		}
		
		public static function kill($sessionName){
			unset( $_SESSION[$sessionName]);
		}
		public static function check($name,$val){
			return isset($_SESSION[$name])&&$_SESSION[$name]==$val?true:false;
		}
	}
