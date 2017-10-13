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
    public function test(\DateTime $date, User $user)
    {
        $logFilePath = '..\web\files\homepage_loads.txt';
        //var_dump($user);
        $logData = $date->format('Y-m-d H:i:s')
            . ' : '
            .'Home visited'
            . ' : '
            . $user->getUsername() . ' (id:' . $user->getId() .')'
            . PHP_EOL;
        file_put_contents($logFilePath, $logData, FILE_APPEND | LOCK_EX);
    }
}
