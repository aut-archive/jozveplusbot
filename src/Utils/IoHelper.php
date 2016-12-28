<?php
namespace App\Utils;

use App\Entity\Jozve;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\File;

class IoHelper
{

    /** @var Jozve */
    private $jozve;
    private $telegram;

    /**
     * IoHelper constructor.
     * @param $telegram Telegram
     */
    public function __construct($telegram)
    {
        $this->telegram = $telegram;
    }

    public function saveJozve($jozve)
    {
        $this->jozve = $jozve;

        $directory = BASE_DIR . '/storage/submissions' . DIRECTORY_SEPARATOR . $this->jozve->getFileId();
        mkdir($directory, 777, true/*r*/);

        $this->telegram->setDownloadPath($directory);

        $json = \GuzzleHttp\json_decode($this->jozve);
        $response = Request::getFile(['file_id' => $this->jozve->getFileId()]);

        if ($response->isOk()) {
            /** @var File document */
            $document = $response->getResult();
            Request::downloadFile($document);
        }

        $booklet = $directory . DIRECTORY_SEPARATOR . 'booklet.json';
        file_put_contents($booklet, $json . PHP_EOL, FILE_APPEND | LOCK_EX);

    }

    /**
     * @return Jozve
     */
    public function getJozve()
    {
        return $this->jozve;
    }

}