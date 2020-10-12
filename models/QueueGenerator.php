<?php

namespace app\models;

use yii\helpers\Console;

class QueueGenerator
{
    /** @var int  */
    private $maxEventCount;

    /** @var int  */
    private $accountNumber;

    public function __construct(int $maxEventCount, int $accountNumber)
    {
        $this->maxEventCount = 10000;
        $this->accountNumber = 1000;
    }

    public function generate()
    {
        $command = \Yii::$app->db->createCommand("insert into queue(account_id, message) values(:account_id, :message)");
        $eventsCount = 0;
        $eventNumber = 0;
        while ($eventsCount < $this->maxEventCount) {
            $n = rand(1, 10);
            $account_number = rand(1, $this->accountNumber);
            $eventsCount += $n;
            Console::output(Console::ansiFormat("Генерация {$eventsCount} события(й) для аккаунта {$account_number}", [Console::FG_YELLOW]));
            for ($i = 1; $i <= $n; $i++) {
                $eventNumber++;
                $command->bindValues([
                        ':account_id' => $account_number,
                        ':message' => "Событие {$account_number}.{$eventNumber}"
                    ])
                    ->execute()
                ;
            }
        }
    }

    public function generateConsec()
    {
        $command = \Yii::$app->db->createCommand("insert into queue(account_id, message) values(:account_id, :message)");
        $eventsCount = 0;
        $account_id = 1;
        $eventNumber = 0;
        while ($eventsCount < $this->maxEventCount) {
            if ($account_id > $this->accountNumber) {
                $account_id = 1;
            }

            $n = rand(1, 10);
            $eventsCount += $n;
            Console::output(Console::ansiFormat("Генерация {$n} события(й) для аккаунта {$account_id}", [Console::FG_YELLOW]));
            for ($i = 1; $i <= $n; $i++) {
                $eventNumber++;
                $command->bindValues([
                        ':account_id' => $account_id,
                        ':message' => "Событие {$account_id}.{$eventNumber}"
                    ])
                    ->execute()
                ;
            }
            $account_id++;
        }
    }
}