<?php

namespace MzQ\Core;

use MzQ\app;
use MzQ\MyPDO;
use MzQ\Parents\LoginRestricted;
use SimpleXLSX;

class Commuting  extends LoginRestricted {



    public function __construct($json, $system = false) {
        if(!$system)
        parent::__construct($json);
    }


    public function Get($json) {

        if(!app::hasKeys($json, [
            "ID"
        ])) {
            new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($json, ["ID"]));
        }

        if($this->getUser()['admin'] < 1) {
            $res = MyPDO::doSelect("SELECT * FROM commuting WHERE ID = ? AND city = ? ORDER BY dateTime DESC",
                [$json->ID,$this->getUser()['city'] ]);

            app::outApi($res);
        }

        $res = MyPDO::doSelect("SELECT * FROM commuting WHERE ID = ?  ORDER BY dateTime DESC ",
            [$json->ID ]);
        app::outApi($res);



    }


    public function Insert($json) {
        if(!app::hasKeys($json, [
            "data"
        ])) {
            new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($json, ["data"]));
        }

        foreach ($json->data as $entry) {

            if(!app::hasKeys($entry, [
                "ID", "city", "dateTime", "longitude", "latitude", "type"
            ])) {
                new Errors("اطلاعات ناصحیح دریافت شد", app::missingKeys($entry, ["ID", "city", "dateTime", "longitude", "latitude", "type"]));
            }


            if($this->getUser()['admin'] < 1) {
                if($this->getUser()['city'] != $entry->city) {
                    new Errors("دسترسی ندارید");
                }
                $user = MyPDO::doSelect("SELECT * FROM people WHERE ID = ? AND city = ? " ,
                    [$entry->ID, $entry->city] , false, true);

                if($user == null || $user instanceof \PDOException)
                    new Errors("کاربر یافت نشد");

            }
            MyPDO::doQuery("INSERT INTO `commuting` (`username`, `ID`, `city`, `dateTime`, `longitude`, `latitude`, `type`) 
                            VALUES (?, ?, ?, ?, ?, ?, ? ) ",
                [
                    $this->getUser()['username'],
                    $entry->ID,
                    $entry->city,
                    $entry->dateTime,
                    $entry->longitude,
                    $entry->latitude,
                    $entry->type

                ]);
        }
        app::out("انجام شد" , HTTP_OK);
    }


}
