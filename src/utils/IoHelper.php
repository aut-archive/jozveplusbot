<?php

/**
 * Created by PhpStorm.
 * User: mohamadamin
 * Date: 12/26/16
 * Time: 6:33 PM
 */

namespace Util;

use \Entity\Jozve;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\File;

class IoHelper {

    private $jozve;
    private $telegram;

    /**
     * IoHelper constructor.
     * @param $telegram Telegram
     */
    public function __construct($telegram) {
        $this->telegram = $telegram;
    }

    public function saveJozve() {

        $directory = '../../storage/records'.DIRECTORY_SEPARATOR.$this->jozve->getFileId();
        mkdir($directory);
        $this->telegram->setDownloadPath($directory);

        $json = \GuzzleHttp\json_decode($this->jozve);
        $response = Request::getFile(['file_id' => $this->jozve->getFileId()]);

        if ($response->isOk()) {
            /** @var File document*/
            $document = $response->getResult();
            Request::downloadFile($document);
        }

        $booklet = $directory.DIRECTORY_SEPARATOR.'booklet.json';
        file_put_contents($booklet, $json.PHP_EOL, FILE_APPEND | LOCK_EX);

    }

    /**
     * @return Jozve
     */
    public function getJozve()
    {
        return $this->jozve;
    }

    /**
     * @param Jozve $jozve
     */
    public function setJozve($jozve)
    {
        $this->jozve = $jozve;
    }

}