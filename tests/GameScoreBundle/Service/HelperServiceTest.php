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

    /**
     * @dataProvider valuesForDatetimeStringValidatorOK
     */
    public function testDatetimeStringValidatorOK($dateString)
    {
        $helperService = new HelperService();
        $this->assertTrue($helperService->datetimeStringValidator($dateString));
    }

    /**
     * @dataProvider valuesForDatetimeStringValidatorNOK
     */
    public function testDatetimeStringValidatorNOK($dateString)
    {
        $helperService = new HelperService();
        $this->assertFalse($helperService->datetimeStringValidator($dateString));
    }

    public function valuesForDatetimeStringValidatorOK()
    {
        return [
            ["1982-01-04 12:12:31"],
            ["2016-11-10 10:00:00"],
            ["2016-12-10 10:05:00"]
        ];
    }

    public function valuesForDatetimeStringValidatorNOK()
    {
        return [
            ["Bonjour"],
            ["100-01-04 12:12:31"],
            ["2016-13-10 10:00:00"],
            ["2016-12-10 10:70:00"],
            ["2016-12-10 43:00:00"],
            ["2016-12-10 12:00:76"]
        ];
    }



}