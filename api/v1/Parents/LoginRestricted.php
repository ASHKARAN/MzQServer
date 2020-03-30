<?php

namespace MzQ\Parents;

use MzQ\app;
use MzQ\Core\Errors;
use MzQ\MyPDO;

class LoginRestricted {


    private $userInfo;
    public function __construct($json) {

        if(!app::hasKeys($json, [
            "token"
        ])) {
            new Errors("اطلاعات ناصحیح", app::missingKeys($json, ["token"]));
        }

        $user = MyPDO::doSelect("SELECT * FROM users WHERE token = ?" , [$json->token], false, false);

        if($user == null || $user instanceof \Exception)
            new Errors("احراز هویت با شکست مواجه شد");

        $this->userInfo = $user;
    }


    public function getUser() {
        return $this->userInfo;
    }

    public function AdminRequired() {
        if($this->getUser()['admin'] == 0)   new Errors("دسترسی ندارید");
    }









}
