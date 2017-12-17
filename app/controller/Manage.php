<?php

namespace app\controller;

use app\model\Card;
use BestLang\core\BLLog;
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

    public function upload()
    {
        if (BLRequest::method() == 'POST' && isset($_FILES['file'])) {
            BLLog::log('copy');
            $uploadFile = 'temp/' . $_FILES['file']['name'];
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $zip = new \ZipArchive();
                $zip->open($uploadFile);
                $time = time();
                $extDir = 'temp/extract_' . $time . '/';
                $zip->extractTo($extDir);
                $count = 0;
                foreach (array_slice(scandir($extDir), 2) as $img) {
                    $imgarr = getimagesize($extDir . $img);
                    $maxw = $imgarr[0];
                    $maxh = $imgarr[1];
                    $imgType = $imgarr[2];
                    if ($maxw > 0) {
                        $count++;
                        $targetw = 600;
                        $targeth = $maxh * 600 / $maxw;
                        $smallimg = imagecreatetruecolor($targetw, $targeth);
                        imagecopyresampled(
                            $smallimg,
                            imagecreatefromjpeg($extDir . $img),
                            0, 0, 0, 0,
                            $targetw, $targeth, $maxw, $maxh
                        );
                        imagejpeg($smallimg, 'cards/' . $time . '_' . $count . '.jpg');
                    }
                }
                unlink($extDir);
                return $this->view('upload', ['imported' => $count]);
            }
        }
        return $this->view('upload');
    }
}