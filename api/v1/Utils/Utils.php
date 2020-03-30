<?php
class Utils {



    public static function toJson($message = "") {
        $response = array();
        $response['state'] = $message;
        echo json_encode($response);
        exit;
    }


    public static function printOrReturn($value , $system = false) {


        if($system)  {
            return $value;
        }
        else {
            echo json_encode ($value);
        }
        exit;
    }


    public static function dayName($i) {
        switch ($i) {
             case 1 : return "شنبه" ;
             case 2 : return "یک شنبه" ;
             case 3 : return "دو شنبه" ;
             case 4 : return "سه شنبه" ;
             case 5 : return "چهار شنبه" ;
             case 6 : return "پنج شنبه" ;
             case 7 : return "جمعه" ;

        }
    }

}
