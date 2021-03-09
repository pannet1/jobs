<?php
$hostname = gethostname();
DEFINE("PROJECT_ROOT_DIR", __DIR__.'/');

/*
 * The purpose of this file is to have one central file hold all the requirements
 * to build up this app (index.php, cli or unit-test for example all share the same
 * requirements) 
 */
$composer = PROJECT_ROOT_DIR.'vendor/autoload.php';
$env = PROJECT_ROOT_DIR.'app/config/env.ini';
$route = PROJECT_ROOT_DIR.'app/config/route.ini';

require($composer);
$f3 = Base::instance();
$f3->config($env,false);
$f3->config($route, true);

//$f3->config(PROJECT_ROOT_DIR.'app/config/cli_routes.ini', false);
// In a production environment you want to disable logging on the database as this consumes memory
// and can slow down any long running scripts you may have.
if ($hostname=="pannet1") {    
    $f3->DEBUG = 3;
} else {
    $f3->DEBUG = 0;
	$f3->set('CACHE','memcache=localhost');
	if($f3->exists('DB'))
		$f3->DB->log(false);
}

// This file will be all the other plugins, services, tools that you need to put in the Fat-Free class
require(PROJECT_ROOT_DIR.'app/config/services.php');

//\Template::instance()->extend('pagebrowser', '\Pagination::renderTag');
$f3->run();