<?php
/**
 * Created by PhpStorm.
 * User: mohamadamin
 * Date: 12/26/16
 * Time: 4:43 PM
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Entity\Jozve;
use Util\IoHelper;

class SubmitCommand extends UserCommand {

    protected $name = 'sendtext';                      //your command's name
    protected $description = 'ارسال متن';              //Your command description
    protected $usage = '/sendtext';                    // Usage of your command
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;
    protected $message;
    protected $conversation;
    protected $telegram;

    private $courses = ["مبانی برنامه‌نویسی (C/C++)", "آز کامپیوتر"];
    private $ioHelper;

    public function __construct(Telegram $telegram, $update) {
        parent::__construct($telegram, $update);
        $this->telegram = $telegram;
        $this->ioHelper = new IoHelper($telegram);
    }

    public function execute() {

        $message = $this->getMessage();              // get Message info
        $chat = $message->getChat();
        $user = $message->getFrom();
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $text = $message->getText(true);
        $message_id = $message->getMessageId();      //Get message Id

        $data = [];
        $data['chat_id'] = $chat_id;

        if (!empty($text)) {
            $text = '';
        }

        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        if (!isset($this->conversation->notes['state'])) {
            $state = 0;
        } else {
            $state = $this->conversation->notes['state'];
        }

        if ($text == 'بازگشت ⬅️') {
            --$state;
            $this->conversation->notes['state'] = $state;
            $this->conversation->update();
            $text = '';
        }

        switch ($state) {

            case 0:
                if (empty($text)) {
                    $data['text'] = 'نام استاد را وارد کنید';
                    $data['reply_to_message'] = $message_id;
                    $data['reply_markup'] = Keyboard::remove();
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['professor'] = $text;
                $this->conversation->notes['state'] = ++$state;
                $this->conversation->update();
                $text = '';

            case 1:
                // Todo: More keyboard
                if (empty($text) || !in_array($text, $this->courses)) { // check if course array contains the course
                    $data['text'] = 'نام درس (course) را وارد کنید';
                    $data['reply_to_message'] = $message_id;
                    $keyboardArray = [];
                    $offset = $this->getCourseOffset();
                    for ($i=$offset; $i<$offset+9; $i++) {
                        if (count($this->courses) > $i) {
                            $j = (int) floor(($i-$offset)/3);
                            $keyboardArray[$j][$i%3] = $this->courses[$i];
                        } else break;
                    }
                    $this->setCourseOffset($offset+9);
                    $keyboard = new Keyboard($keyboardArray);
                    $keyboard->setResizeKeyboard(true);
                    $data['reply_markup'] = $keyboard;
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['course'] = $text;
                $this->conversation->notes['state'] = ++$state;
                $this->conversation->update();
                $text = '';

            case 2:
                if (empty($text)) {
                    $data['text'] = 'سال مربوط به جزوه را وارد کنید';
                    $data['reply_to_message'] = $message_id;
                    $data['reply_markup'] = new Keyboard(
                        ['1391', '1392', '1393'],
                        ['1394', '1395', '1396'],
                        ['1397', '1398', '1398']
                    );
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['year'] = $text;
                $this->conversation->notes['state'] = ++$state;
                $this->conversation->update();
                $text = '';

            case 3:
                if ($message->getDocument() == null) {
                    $data['text'] = 'فایل pdf جزوه را بفرستید';
                    $data['reply_to_message'] = $message_id;
                    $data['reply_markup'] = Keyboard::remove();
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['file_id'] = $text;
                $this->conversation->notes['state'] = ++$state;
                $this->conversation->update();
                $text = '';

            case 4:
                $jozve = new Jozve(
                    '@' . $user->getUsername(),
                    $this->conversation->notes['professor'],
                    $this->conversation->notes['course'],
                    $this->conversation->notes['year'],
                    $this->conversation->notes['file_id']
                );
                $this->setCourseOffset(0);
                $this->ioHelper->setJozve($jozve);
                $this->ioHelper->saveJozve();
                $data['text'] = sprintf(
                    'جزوه‌ی سال %d استاد %s - درس %s (از طرف %s):)' . PHP_EOL . '@JozvePlusBot',
                    $jozve->getYear(),
                    $jozve->getProfessor(),
                    $jozve->getCourse(),
                    $jozve->getOwner()
                );
                $data['document'] = $jozve->getFileId();
                $data['reply_to_message'] = $message_id;
                $data['reply_markup'] = Keyboard::remove();
                $result = Request::sendMessage($data);
                $this->conversation->stop();
                $this->telegram->executeCommand('start');
                break;


            default:
                $result = null;

        }

        return $result;

    }

    private function getCourseOffset() {
        if (!isset($this->conversation->notes['course_offset'])) {
            return 0;
        } else {
            return $this->conversation->notes['course_offset'];
        }
    }

    private function setCourseOffset($courseOffset) {
        $this->conversation->notes['course_offset'] = $courseOffset;
    }

}