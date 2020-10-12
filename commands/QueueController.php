<?php

namespace app\commands;

use app\models\QueueGenerator;
use app\models\QueueLog;
use app\models\QueueProcessor;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class QueueController extends Controller
{
    /**
     * @throws \yii\db\Exception
     */
    public function actionGenerate()
    {
        (new QueueGenerator(10000, 1000))->generate();
    }

    /**
     * @param int $account_number
     * @throws \yii\db\Exception
     */
    public function actionGenerateConsec($account_number = 1000)
    {
        (new QueueGenerator(10000, 1000))->generateConsec();
    }

    /**
     * @return int
     */
    public function actionProcess()
    {
        try {
            $threadNumber = 1000;
            $start = microtime(true);
            (new QueueProcessor($threadNumber))->run();
            $memoryFormat = function($bytes, $precision = 2) {
                $units = array('B', 'KB', 'MB', 'GB', 'TB');

                $bytes = max($bytes, 0);
                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);

                // Uncomment one of the following alternatives
                $bytes /= pow(1024, $pow);
                // $bytes /= (1 << (10 * $pow));

                return round($bytes, $precision) . ' ' . $units[$pow];
            };
            Console::output(Console::ansiFormat("Threads number: " . $threadNumber,[Console::FG_YELLOW]));
            Console::output(Console::ansiFormat(
                sprintf("Done for %.2f seconds", microtime(true) - $start),
                [Console::FG_YELLOW]
            ));
            $mem = memory_get_peak_usage(true);
            Console::output(Console::ansiFormat(
                "Memory usage: {$memoryFormat($mem)} ({$mem})",
                [Console::FG_YELLOW]
            ));
            return ExitCode::OK;
        } catch (\Exception $e) {
            Console::output(Console::ansiFormat("Ошибка: {$e->getMessage()}: {$e->getTraceAsString()}", [Console::FG_RED]));
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    public function actionLog($account_id)
    {
        Console::output((new QueueLog())->getLog($account_id));
    }
}