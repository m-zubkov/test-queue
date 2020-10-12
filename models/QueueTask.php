<?php

namespace app\models;

class QueueTask extends \Threaded
{
    /** @var int */
    private $account_id;

    /** @var string */
    private $message;

    /**
     * QueueTask constructor.
     * @param int $account_id
     * @param string $message
     */
    public function __construct(int $account_id, string $message)
    {
        $this->account_id = $account_id;
        $this->message = $message;
    }

    public function run()
    {
        usleep(1000000);
        //(new \app\models\QueueLog())->saveLog($this->account_id, $this->message);

        $file = \Yii::getAlias('@runtime') . "/account/{$this->account_id}.log";
        file_put_contents($file, $this->message . "\r\n", FILE_APPEND);
    }
}
