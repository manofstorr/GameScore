<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 16:12
 */

namespace GameScoreBundle\Service;

use UserBundle\Entity\User;

class LoadPageLogWriter
{
    public function test(\DateTime $date, $user)
    {
        if ($user) {
            $userId = $user->getId();
            $userName = $user->getUsername();
        } else {
            $userId = 0;
            $userName = 'Anonymous';
        }
        $logFilePath = '..\web\files\homepage_loads.txt';
        $logData = $date->format('Y-m-d H:i:s')
            . ' : '
            .'Home visited'
            . ' : '
            . $userName . ' (id:' . $userId .')'
            . PHP_EOL;
        file_put_contents($logFilePath, $logData, FILE_APPEND | LOCK_EX);
    }
}
