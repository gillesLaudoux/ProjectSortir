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

    /** Return true if dateNow < parameter */
    public function dueDateValid($dueDateNightOut) : bool {

        $now = new \DateTime();

        if($now<=$dueDateNightOut){return true;}

        return false;
    }
}
