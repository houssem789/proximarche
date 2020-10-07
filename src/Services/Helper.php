<?php

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class Helper
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function valid_date($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


}
