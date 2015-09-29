<?php
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	class FileUpload {
		public static function checkFileUpload($folder,$sizeMB=5){
			$infos = array();
			foreach($_FILES AS $name => $file ){
				$info = array(
					"control"=>$name,
					"name"=>"",
					"base64"=>"",
					"size"=>"",
					"type"=>"",
					"ext"=>"",
					"error"=>array("number"=>0,"message"=>""),
					"dimension"=>array(
							"width"=>"",
							"height"=>""
					),
					"exist"=>array()
				);
				if($_FILES[$name]['name']){
					Import::util("Image");
					if(Image::isImage($_FILES[$name]['type'])){
						$imgInfo = Image::getAttrs($_FILES[$name]['tmp_name']);
						$info["dimension"]=array("width"=>$imgInfo["width"],"height"=>$imgInfo["height"]);
					}
					$info["name"]=$_FILES[$name]['name'];
					$info["base64"]=base64_encode($_FILES[$name]['name']);
					$info["size"]=$_FILES[$name]['size'];
					$info["type"]=$_FILES[$name]['type'];
					$info["ext"]=substr($_FILES[$name]['name'],strripos($_FILES[$name]['name'],".")+1);
					if(!$_FILES[$name]['error']){
						if($_FILES[$name]['size'] > ((1024000)*$sizeMB)){
							$valid_file=false;
							$result["error"]["number"]=1;
							$result["error"]["message"]="File size is over 5 MB.";
						}
						if(Basic::fileExist($folder."/".$_FILES[$name]['name'])){
							$info["error"]["number"]=1;
							$info["error"]["message"]="File is already exist";
							$info["exist"]=array(
								"name"=>$_FILES[$name]['name'],
								"size"=>Basic::fileSize($folder."/".$_FILES[$name]['name']),
								"type"=>Basic::mimeContentType($folder."/".$_FILES[$name]['name']),
								"dimension"=>array(
									"width"=>"",
									"height"=>""
								)
							);
							if(Image::isImage($folder."/".$_FILES[$name]['name'])){
								$imgInfo = Image::getAttrs($folder."/".$_FILES[$name]['name']);
								$info["exist"]["dimension"]==array("width"=>$imgInfo["width"],"height"=>$imgInfo["height"]);
							}
						}
					}else{
						$info["error"]["number"]=1;
							
						$info["error"]["message"]=FileUpload::uploadErrorMessage($_FILES[$name]['error']);
						
					}
				}
				else{
					$info["error"]["number"]=1;
					$info["error"]["message"]="File upload is empty.";
				}
				$infos[]=$info;
			}
			return $infos;
		}
		private static function uploadErrorMessage($code){
			switch ($code) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = "The uploaded file was only partially uploaded";
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = "No file was uploaded";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Missing a temporary folder";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = "Failed to write file to disk";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "File upload stopped by extension";
					break;
				default:
					$message = "Unknown upload error";
					break;
			}
			return $message;
		}
		public static function uploadFile($controlName,$new_file_name,$sizeMB=5,$folder="data"){
			$valid_file = true ;
			$result = array();
			if($_FILES[$controlName]['name']){
				Import::util("Image");
				$imgInfo = Image::getAttrs($_FILES[$controlName]['tmp_name']);
				$result = array(
						"error"=>array("number"=>0,"message"=>""),
						"name"=>$_FILES[$controlName]['name'],
						"base64"=>base64_encode($_FILES[$controlName]['name']),
						"size"=>$_FILES[$controlName]['size'],
						"type"=>$_FILES[$controlName]['type'],
						"dimension"=>array("width"=>$imgInfo["width"],
								"height"=>$imgInfo["height"])
				);
				if(!$_FILES[$controlName]['error']){
					if($_FILES[$controlName]['size'] > ((1024000)*$sizeMB)){
						$valid_file=false;
						$result["error"]["number"]=0;
						$result["error"]["message"]=$_FILES[$controlName]['error'];
					}
					if($valid_file&&Basic::fileExist($folder)&&!Basic::fileExist($folder.'/'.$new_file_name)){
						$code=move_uploaded_file($_FILES[$controlName]['tmp_name'], $folder.'/'.$new_file_name);
						if(UPLOAD_ERR_OK!=$code){
							$valid_file=false;
							$result["error"]["number"]=1;
							$result["error"]["message"]=FileUpload::uploadErrorMessage($code);
						}
					}
					else $valid_file = false ;
				}
				else {
					$valid_file = false ;
					$result["error"]["number"]=1;
					$result["error"]["message"]=$_FILES[$controlName]['error'];
				}
			}else{
				$result["error"]["number"]=1;
				$result["error"]["message"]="Can not find file upload.";
			}
			return !$valid_file?false:$result;
		}
		public static function uploadAll($sizeMB=5,$folder="data"){
			$valid_file = true ;
			$result = array();
			foreach($_FILES AS $key => $file ){
				if($file['name']){
					if(!$file['error']){
						if($file['size'] > ((1024000)*$sizeMB))$valid_file = false;
						if($valid_file&&Basic::fileExist($folder)&&!Basic::fileExist($folder.'/'.$file["name"]))$valid_file=move_uploaded_file($_FILES[$key]['tmp_name'], $folder.'/'.$file["name"]);
						else $valid_file = false ;
					}
					else $valid_file = false ;
					$result[] = array("name"=>$file['name'],"base64"=>base64_encode($file['name']),"size"=>$file['size'],"type"=>$file['type']);
				}
			}
			return !$valid_file?false:$result;
		}
	}