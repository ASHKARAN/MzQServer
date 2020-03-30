<?php

namespace MzQ\Core;

use MzQ\app;
use MzQ\MyPDO;
use MzQ\Parents\LoginRestricted;
use SimpleXLSX;

class Data  extends LoginRestricted {


    private  $ID = 0 ;
    private  $CITY = 1 ;
    private  $FNAME = 2 ;
    private  $LNAME = 3 ;
    private  $FATHER = 4 ;
    private  $PLAQUE = 5 ;
    private  $OFFICE = 6 ;
    private  $COMMENTS = 7 ;

    public function __construct($json, $system = false) {
        if(!$system)
        parent::__construct($json);
    }


    public function Login($json) {
        app::outApi($this->getUser());
    }

    public function Get($json) {

        if(!app::hasKeys($json, [
            "city"
        ])) {
            new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($json, ["city"]));
        }

        $city = MyPDO::doSelect("SELECT * FROM city WHERE name = ?" , [$json->city] , false, false);

        if($city == null || $city instanceof \Exception) {
            new Errors("شهر پیدا نشد");
        }

//        if($this->getUser()['admin'] < 1) {
//
//            if($this->getUser()['city'] != $city['name']) {
//               new Errors("Access denied");
//            }
//        }

        $path = dirname(dirname(dirname(dirname(__FILE__)))) . "/json/".$city['alias'].".json"  ;

//        header("Content-type: application/json");
//        header("Cache-Control: no-store, no-cache");
//        header('Content-Disposition: attachment; filename="'.$json->city.".json" .'"');
//
//        header('Content-Length: ' . filesize($path));
//



        header('Content-disposition: attachment; filename='.$city['alias'].".json" .'');
        header('Content-type: application/json');
        echo file_get_contents($path );




    }

    /**
     * read all excel files and update the database
     * @param $json
     */
    public function Update($json) {
         $this->AdminRequired();

        $path = dirname(dirname(dirname(dirname(__FILE__)))) . "/xlsx/"  ;


        if(!app::hasKeys($json, [
            "city"
        ])) {
            new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($json, ["city"]));
        }



        if ( $xlsx = SimpleXLSX::parse("$path".$json->city.".xlsx") ) {
           $entry =  $xlsx->rows();
           $firstRow = 0 ;
            foreach ($entry as $person) {
                if($firstRow == 0) {
                    $firstRow = 1 ;
                    continue;
                }

                 $res =   MyPDO::doQuery(
                     "REPLACE INTO  people (`ID`, `city`, `fName`, `lName`, `father`, `plaque`, `office`, `comments`)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?  ) 
                               ",
                        [
                            $person[$this->ID],
                            $person[$this->CITY   ],
                            $person[$this->FNAME  ],
                            $person[$this->LNAME  ],
                            $person[$this->FATHER ],
                            $person[$this->PLAQUE ],
                            $person[$this->OFFICE   ],
                            $person[$this->COMMENTS   ]
                        ]);
           }

           $this->ExportJson($json, true);
            app::out("file updated", HTTP_OK);
        } else {
            new Errors("فایل یافت نشد");
        }

    }


    /**
     * read the data the from database and create a json file for android application
     * @param $json
     * @param bool $system
     */
    public function ExportJson($json , $system = false) {
         $this->AdminRequired();

        $path = dirname(dirname(dirname(dirname(__FILE__)))) . "/json/"  ;


        if(!app::hasKeys($json, [
            "city"
        ])) {
            new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($json, ["city"]));
        }


        $city = MyPDO::doSelect("SELECT * FROM city WHERE alias = ?" , [$json->city] , false, false);

        if($city == null || $city instanceof \Exception) {
            new Errors("شهر یافت نشد");
        }

        $people = MyPDO::doSelect("SELECT * FROM people WHERE city = ? " , [ $city['name'] ]);
//        $response = array();
//
//        foreach ($people as $person) {
//            $commuting = MyPDO::doSelect("SELECT * FROM commuting WHERE  ID = ? AND city = ?" ,
//                [$person['ID'], $person['city']]);
//            $person['commuting'] = $commuting;
//            $response[] = $person;
//        }

        file_put_contents($path .$json->city.".json" , json_encode($people));

       if(!$system) {
           app::out("json file created", HTTP_OK);
       }

    }






}
