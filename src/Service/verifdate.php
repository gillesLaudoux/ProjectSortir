<?php

namespace App\Service;

use DateTime;

class verifdate

{
    public function dateDiff($date1, $date2): bool
    {

        $now = new DateTime();


        if ($date1 < $date2 && $now < $date1 && $now < $date2) {
            $isDateValid = true;
        } else {
            $isDateValid = false;
        }
        return $isDateValid;
    }
}




