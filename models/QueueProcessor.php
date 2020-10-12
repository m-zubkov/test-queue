<?php

namespace app\models;

class QueueProcessor
{
    /** @var int */
    private $threadNumber;

    /** @var QueueLog  */
    private $logger;

    public function __construct(int $threadNumber)
    {
        $this->threadNumber = $threadNumber;
        $this->logger = new QueueLog();
    }

    public function run()
    {
        $this->clearLogs();
        $pool = new \Pool($this->threadNumber);
        $workers = [];
        while ($events = $this->getEvents()) {
            $ids = [];
            foreach ($events as $event) {
                if (!isset($workers[$event['account_id']])) {
                    $workerId = $pool->submit(new QueueTask($event['account_id'], $event['message']));
                    $workers[$event['account_id']] = $workerId;
                } else {
                    $pool->submitTo($workers[$event['account_id']], new QueueTask($event['account_id'], $event['message']));
                }

                $ids[] = $event['id'];
            }

            $this->deleteEvents($ids);
        }
        while ($pool->collect()) {
            usleep(1000);
        }
        $pool->shutdown();
    }

    private function clearLogs()
    {
        for ($i = 1; $i <= 1000; $i++) {
            $this->logger->deleteLog($i);
        }
    }

    /**
     * @return array|false|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    protected function getEvent()
    {
        return \Yii::$app->db->createCommand("select * from queue order by id asc limit 1")->queryOne();
    }

    /**
     * @return array|false|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    protected function getEvents()
    {
        return \Yii::$app->db->createCommand("select * from queue order by id asc limit 100")->queryAll();
    }


    /**
     * @param int $id
     */
    private function deleteEvent(int $id)
    {
        \Yii::$app->db->createCommand('delete from queue where id = :id', ['id' => $id])->execute();
    }

    /**
     * @param int[] $ids
     */
    private function deleteEvents($ids)
    {
        \Yii::$app->db->createCommand('delete from queue where id in(' . implode(',', $ids) . ')')->execute();
    }
}