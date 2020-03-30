<?php

namespace MzQ;


use MzQ\Core\Errors;

/**
 *  Main App Starter
 *
 * @author ali
 */
class Router {



    /**
     *  Navigate the user to the target
     * @param String $route
     */

    public function __construct($route , $action ) {


        $json_str = file_get_contents('php://input');
        $json = json_decode($json_str);
        try {
            $class = "MzQ\Core\\".$route ;
            $instance = null;
            if(class_exists($class))   {
                $instance = new $class($json, false);
                if(method_exists($instance, $action))
                    $instance->{$action}($json);
                else new  Errors("Wrong Router" );

            }else new  Errors("Wrong Router" );
        }
        catch(\Exception $exc) {
            echo json_encode($exc);
        }










    }
}
