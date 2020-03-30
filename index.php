<?php

 namespace Rest;

//if(Config::DEBUG_MODE)
	// sleep(0.5);

//Allow AJAX Request
 use MzQ\app;
 use MzQ\Config;
 use MzQ\Router;

 header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
    date_default_timezone_set('Asia/Kabul');




    include_once  'functions.php';
    include_once  'app.php';
    include_once  'Config.php';
    include_once  'MyPDO.php';
    include_once  'Router.php';
    include_once  'HttpStatusCode.php';


    include_once  'api/v1/Parents/LoginRestricted.php';


function load_classphp($directory) {
    if(is_dir($directory)) {
        $scan = scandir($directory);
        unset($scan[0], $scan[1]); //unset . and ..
        foreach($scan as $file) {
            if(is_dir($directory."/".$file)) {
                load_classphp($directory."/".$file);
            } else {
                if(strpos($file, '.php') !== false) {

                    include_once($directory."/".$file);
                }
            }
        }
    }
}
load_classphp('api/v1/');


//Display Errors On OutPut
if(Config::DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting( E_ALL );
}
else {
     ini_set('display_errors', 0);
}


 require  'vendor/autoload.php';



/**
 * age header client ya userAgent nabod ya chizi ke mikhaym nist mitonim request ro block konim
 */




if(!isset($_GET["ROUTE" ]) || !isset($_GET["ACTION"]))   {
       app::outApi("اطلاعات صحیحی دریافت نشد");

}
else {
     $route     = $_GET["ROUTE"];
     $action    = $_GET["ACTION"];


     new  Router($route , $action);


}













