<?php
	/* @date : 4 March 2015
	 * @author : Chamroeun OUM
	 * @description : This file we use it to load the required classes of the framework
	 */
	defined("_EXE") or die ("CORE VARIABLE IS NOT DEFINED.");
	require_once(CORE_UTIL."Import.php");
	Import::util(array("Session","Basic"));
	Import::html("HTML");
	Import::html("Element");
	Import::core(array("Palm","database".DS."MySql","Route"));
	Import::coreapp(array("Service","Controller","Model","View"));
	Import::config("Config");
	Session::start();
	$route = new Route();
	$route->spread();