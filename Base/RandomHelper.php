<?php
namespace AntonPavlov\PersonalSite\Base;

/**
 * Выдача случайных чисел
 *
 * Выдаёт случайное число вне зависимости от версии PHP, установленной на сервере
 * Сделан из-за того, что мой удалённый сервер пока не поддерживает PHP 7 и, соответственно, random_int()
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
trait RandomHelper
{

    /**
     * Выдаёт случайное число вне зависимости от версии PHP, установленной на сервере
     *
     * @param int $start число, задающее начало диапазона
     * @param int $end число, задающее конец диапазона
     *
     * @return int
     */
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