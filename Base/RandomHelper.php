<?php
namespace AntonPavlov\PersonalSite\Base;

trait RandomHelper
{

    public function getRandomNum($start, $end)
    {
        $num = 0;
        if (substr(phpversion(), 0, 1) === '7') {
            $num = random_int($start, $end);
        } else {
            $num = rand($start, $end);
        }
        
        return $num;
    }

}