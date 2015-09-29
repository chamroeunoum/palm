<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Basic {
		public static function pr($arr=array()){
			print_r($arr);
		}
		public static function eco($str=""){
			echo $str;
		}
		public static function need($file){
			Basic::fileExist($file)?require_once($file):die("Error: Import file error.");
		}
		public static function keyExist($key="",$array=array()){
			return array_key_exists($key,$array);
		}
		public static function createFile($fil="no_name"){
			return fopen($fil, "a");
		}
		public static function pathInfo($path){
			return pathinfo($path);
		}
		public static function deleteFile($file){
			return unlink($file);
		}
		public static function fileInfo($file){
			return array( 
				"size"=>filesize($file),
				"owner"=>fileowner($file),
				"group"=>filegroup($file),
				"create"=>filectime($file),
				"modifie"=>filemtime($file),
				"access"=>fileatime($file),
				"perms"=>fileperms($file),
				"type"=>filetype($file),
				"status"=>stat($file),
				"basename"=>basename($file),
				"dirname"=>dirname($file),
			);
		}
		public static function mimeContentType($file){
			return pathinfo($file, PATHINFO_EXTENSION);
		}
		public static function getMVCName($mvcName=""){
			$mvcName = explode("_",$mvcName);
			foreach($mvcName AS $key => $name )$mvcName[$key]=ucfirst($name);
			return implode("",$mvcName);
		}
		public static function fileSize($file){
			return filesize($file);
		}
		public static function openFolder($folder="."){
			return opendir($folder);
		}
		public static function readFolder($handle=false){
			return readdir($handle);
		}
		public static function closeFolder($handle=false){
			closedir($handle);
		}
		public static function createFolder($folder=""){
			return mkdir($folder);
		}
		public static function getFolders($folder="."){
			$handle=Basic::openFolder($folder);
			$files = array();
			if($handle!==false){
				while(($file=Basic::readFolder($handle))!==false){
					if(is_dir($file))$files[]=$file;
				}
			}
			return $files;
		}
		public static function getFiles($folder="."){
			$handle=Basic::openFolder($folder);
			$files = array();
			if($handle!==false){
				while(($file=Basic::readFolder($handle))!==false){
					if(is_file($folder.$file))$files[]=$file;
				}
			}
			return $files;
		}
		public static function fileExist($fil){
			return file_exists($fil);
		}
		public static function fileGetContent($file){
			return file_get_contents($file);
		}
		public static function fileCopy($src="",$des=""){
			return copy($src,$des);
		}
		public static function sizeFormat($size){
			$temp=$size/1024;
			if ($temp<1024){
				return (int)$temp . "." . ($size%1024) . " KB" ;
			}else{
				$kb = $size/1024;
				$size= $kb/1024;
				return (int)$size.".".($size%1024). " MB";
			}
		}
		public static function stringEscape($string){
			//return mysql_real_escape_string($string);
			return htmlentities($string,ENT_QUOTES);
		}
		public static function getPlural($str=""){
			$plural="";
			$case = substr($str,strlen($str)-1);
			switch($case){
				case "y":
					$plural = substr($str,0,strlen($str)-1)."ies";
				break;
				case "s":
					$plural = substr($str,0,strlen($str)-1)."es";
				break;
				default:
					$plural = $str."s";
					break;
			}
			return $plural;
		}
		public static function toKnumber($text){
			return str_replace(array("0","1","2","3","4","5","6","7","8","9"),array("០","១","២","៣","៤","៥","៦","៧","៨","៩"),$text);
		}
		public static function toLnumber($text){
			return str_replace(array("០","១","២","៣","៤","៥","៦","៧","៨","៩"),array("0","1","2","3","4","5","6","7","8","9"),$text);
		}
	}
