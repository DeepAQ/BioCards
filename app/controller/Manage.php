<?php

namespace app\controller;

use app\model\Card;
use BestLang\core\controller\BLController;
use BestLang\core\util\BLRequest;

class Manage extends BLController
{
    public function tag()
    {
        if (BLRequest::method() == 'POST') {
            $filename = BLRequest::post('filename');
            $tags = BLRequest::post('tags');
            if (!empty($filename) && !empty($tags)
                && Card::query()->where('filename', $filename)->count() == 0) {
                Card::insert([
                    'filename' => $filename,
                    'tags' => $tags
                ]);
            }
        }
        $tagged = array_map(function ($card) {
            return $card->filename;
        }, Card::query()->fields('filename')->get());
        $files = array_slice(scandir('cards'), 2);
        $next = false;
        foreach ($files as $file) {
            if (!in_array($file, $tagged)) {
                $next = $file;
                break;
            }
        }
        if (empty($next)) {
            return $this->view('tag', ['nomore' => true]);
        } else {
            return $this->view('tag', [
                'filename' => $next,
                'remain' => sizeof($files) - sizeof($tagged)
            ]);
        }
    }
}