<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class Image {
		const GIF  = 1 ;
		const JPG  = 2 ;
		const PNG  = 3 ;
		const SWF  = 4 ;
		const PSD  = 5 ;
		const BMP  = 6 ;
		const TIFF7 = 7 ; // (intel bute order)
		const TIFF8 = 8 ; // (motorola byte order)
		const JPC  = 9 ;
		const JP2  = 10 ;
		const JPX  = 11 ;
		const JB2  = 12 ;
		const SWC  = 13 ;
		const IFF  = 14 ;
		const WBMP = 15 ;
		const XBM  = 16 ;
		public static function getAttrs($image){
			list($width, $height, $type, $attr) = getimagesize($image);
			return array("width"=>$width,"height"=>$height,"type"=>$type,"dimemsion"=>$attr);
		}
		public static function isImage($img){
			return strpos($img,"image")!==false?true:false;
		}
	}