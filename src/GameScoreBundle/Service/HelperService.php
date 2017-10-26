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
        if (strlen($datetimeStringToValidate) !== 19) {
            return false;
        }

        // check ranges
        $year = substr($datetimeStringToValidate, 0, 4);
        if (($year < 2000 ) || ($year > 2500)) {
            return false;
        }
        // to be continued

        $delimiter = '#';
        $pattern = '^[\d]{4}\-[\d]{2}-[\d]{2}\s[\d]{2}:[\d]{2}:[\d]{2}$';

        $test = preg_match($delimiter . $pattern . $delimiter, $datetimeStringToValidate, $matches);
        return ($test === 1);
    }

}
