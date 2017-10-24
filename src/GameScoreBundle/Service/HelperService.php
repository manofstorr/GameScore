<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 12/10/2017
 * Time: 10:41
 */

namespace GameScoreBundle\Service;


class HelperService
{

    public function datetimeStringValidator(string $datetimeStringToValidate): bool
    {
        $delimiter = '#';
        $pattern = '^[\d]{4}\-[\d]{2}-[\d]{2}\s[\d]{2}:[\d]{2}:[\d]{2}$';
        // todo : make validation on sub-strings : month between 01 and 12 etc
        $test = preg_match($delimiter . $pattern . $delimiter, $datetimeStringToValidate, $matches);
        return ($test === 1);
    }

}
