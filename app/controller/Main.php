<?php

namespace app\controller;

use app\model\Card;
use BestLang\core\controller\BLController;
use BestLang\core\util\BLRequest;

class Main extends BLController
{
    public function index()
    {
        $kw = BLRequest::get('kw');
        if (isset($kw)) {
            $cards = Card::query()->where('tags', 'LIKE', '%' . $kw . '%')->limit(12)->get();
            $title = '搜索结果 - ' . $kw;
        } else {
            $cards = Card::query()->orderBy('random()')->limit(12)->get();
            $title = '随机卡片';
        }
        return $this->view('index', ['cards' => $cards, 'title' => $title]);
    }
}