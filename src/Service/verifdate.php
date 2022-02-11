<?php

namespace App\Service;

class verifdate

{
    public function dateDif($date1, $date2): bool
    {
        $diff = date_diff($date1, $date2);
        if ($diff < 0) {
            $isDateValid = true;
        } else {
            $isDateValid = false;
        }
        return $isDateValid;
    }
}