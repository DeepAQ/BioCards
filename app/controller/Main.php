<?php

namespace app\controller;

use BestLang\core\controller\BLController;

class Main extends BLController
{
    public function index()
    {
        return $this->view('index');
    }

    public function phpinfo()
    {
        phpinfo();
        return true;
    }
}