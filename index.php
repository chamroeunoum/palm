<?php
/* @date : 4 March 2015
 * @author : Chamroeun OUM
 * @description : In this file, we use it to define paths of the Framework
 */
	define("_EXE",1);
	date_default_timezone_set("Asia/Phnom_Penh");
	if(!defined("DS"))define("DS",DIRECTORY_SEPARATOR);
	if(!defined("SERVER"))define("SERVER",(isset($_SERVER["REQUEST_SCHEME"])?$_SERVER["REQUEST_SCHEME"]:"http")."://".$_SERVER["HTTP_HOST"]);
	if(!defined("REAL_PATH"))define("REAL_PATH",dirname(__FILE__).DS);
	if(!defined("ROOT"))define("ROOT",str_replace(array("index.php"),"",$_SERVER["SCRIPT_NAME"]));
	if(!defined("CORE"))define("CORE","core".DS."library".DS);
	if(!defined("CORE_APP"))define("CORE_APP",CORE."app".DS);
	if(!defined("CORE_HTML"))define("CORE_HTML",CORE."html".DS);
	if(!defined("CORE_PERFORM"))define("CORE_PERFORM",CORE."perform".DS);
	if(!defined("CORE_UTIL"))define("CORE_UTIL",CORE."util".DS);
	if(!defined("APP"))define("APP","app".DS);
	if(!defined("TEMPLATE"))define("TEMPLATE","template".DS);
	if(!defined("DATA_FOLDER"))define("DATA_FOLDER","data".DS);
	if(!defined("APP_COMPONENT"))define("APP_COMPONENT","app".DS."components".DS);
	if(!defined("APP_PLUGIN"))define("APP_PLUGIN","app".DS."plugins".DS);
	if(!defined("APP_SERVICE"))define("APP_SERVICE","app".DS."services".DS);
	if(!defined("APP_LIBS"))define("APP_LIBS","app".DS."libs".DS);
	if(!defined("APP_CONFIG"))define("APP_CONFIG","config".DS);
	if(!defined("DBXML"))define("DBXML","data".DS."dbxml".DS);
	if(!defined("WEB"))define("WEB",SERVER.ROOT."web/");
	if(!defined("CSS"))define("CSS",WEB."css/");
	if(!defined("JS"))define("JS",WEB."js/");
	if(!defined("IMG"))define("IMG",WEB."img/");
	require_once(CORE."wakeup.php");