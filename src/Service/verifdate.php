<?php

namespace App\Service;

class verifdate

{
    public function dateDiff($date1, $date2): bool
    {

        if ($date1 < $date2) {
            $isDateValid = true;
        } else {
            $isDateValid = false;
        }
        return $isDateValid;
    }
}
