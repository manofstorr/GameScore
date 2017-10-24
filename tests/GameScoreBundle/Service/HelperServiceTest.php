<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 24/10/2017
 * Time: 11:55
 */

namespace tests\GameScoreBundle\Service;

use PHPUnit\Framework\TestCase;
use GameScoreBundle\Service\HelperService;

class HelperServiceTest extends TestCase
{

    public function testDatetimeStringValidator()
    {
        $helperService = new HelperService();
        $dateString = "2012-12-12 00:00:01";
        $this->assertTrue($helperService->datetimeStringValidator($dateString));
    }

}