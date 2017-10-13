<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 16:12
 */

namespace GameScoreBundle\Service;


class LoadPageLogWriter
{
    public function test($date, $user)
    {
        $logFilePath = '..\web\files\homepage_loads.txt';
        $logData = 'Home visited @' . $date->format('Y-m-d H:i:s') . PHP_EOL;
        file_put_contents($logFilePath, $logData, FILE_APPEND | LOCK_EX);
    }
}