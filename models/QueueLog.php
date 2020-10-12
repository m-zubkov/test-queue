<?php

namespace app\models;

class QueueLog
{
    /** @var string */
    private $logPath;

    public function __construct()
    {
        $this->logPath = \Yii::getAlias('@runtime') . "/account/";
    }

    public function init()
    {
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath);
        }
    }

    /**
     * @param int $account_id
     * @param string $message
     */
    public function saveLog(int $account_id, string $message)
    {
        $file = "{$this->logPath}{$account_id}.log";
        $fp = fopen($file, 'a');
        fwrite($fp, $message . "\r\n");
        fclose($fp);
    }

    /**
     * @param int $account_id
     * @return false|string
     * @throws \Exception
     */
    public function getLog(int $account_id)
    {
        $file = "{$this->logPath}{$account_id}.log";
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            throw new \Exception("Не найден файл лога {$file} для аккаунта {$account_id}");
        }
    }

    /**
     * @param int $account_id
     */
    public function deleteLog(int $account_id)
    {
        $file = "{$this->logPath}{$account_id}.log";
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
