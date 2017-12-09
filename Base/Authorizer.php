<?php
namespace AntonPavlov\PersonalSite\Base;

use AntonPavlov\PersonalSite\Access;
use AntonPavlov\PersonalSite\Exceptions\NotAuthorizedException;

/**
 * Авторизация пользователей (пока временная, без БД)
 *
 * Содержит методы для авторизации,
 * и разлогинивания
 *
 * @package AntonPavlov\PersonalSite
 *
 * @author Anton Pavlov <mail@antonpavlov.ru>
 *
 */
trait Authorizer
{

    /**
     * @var array $authorized ['status'] - boolean, авторизован или нет, ['login'] - string, логин пользователя
     */
    private static $authorized;

    /**
     * Инициализация данных авторизации
     *
     * Проверяет существование сессии авторизации. Если она есть - копирует её во внутреннюю переменную $authorized (array)
     * Если нет - заполняет и $authorized, и сессию значениями по умолчанию (авторизован ли - false и логин - '')
     *
     * @return void
     */
    public function init()
    {
        if ((isset($_SESSION['authorized']))&&(!empty($_SESSION['authorized']))) {
            self::$authorized = $_SESSION['authorized'];
        } else {
            self::$authorized = 
                [
                    'status' => false,
                    'login' => ''
                ];
            $_SESSION['authorized'] = self::$authorized;
        }
    }

    /**
     * Пытается авторизовать пользователя.
     *
     * Если да - выдаёт true.
     * Если нет - выдаёт false и сообщение об ошибке.
     *
     * @param string $login логин, по умолчанию - пустая строка
     * @param string $password пароль, по умолчанию - пустая строка
     *
     * @throws NotAuthorizedException если указаны неверные логин-пароль, либо превышен лимит попыток авторизации
     *
     * @return bool
     */
    public function tryAuth($login = '', $password = '')
    {
        // проверяем, может пользователь уже авторизован
        if ((self::$authorized['status'] === true)&&(self::$authorized['login'] !== '')) {
            return true;
        }
        
        if (($login === '')||($password === '')) {
            throw new NotAuthorizedException('Не указан логин или пароль');
        }
        
        // проверяем, не превышен ли лимит на попытки авторизации
        if (!Authorizer::checkAuthAttempts(1, 5)) {
            throw new NotAuthorizedException('5 или более попыток авторизации за 1 минуту. Попробуйте позже');
        }

        $setAccesData = Access::get();
        if (($setAccesData['login'] !== $login)||(!password_verify($password, $setAccesData['password']))) {
            throw new NotAuthorizedException('Пользователь с указанным сочетанием логин-пароль не найден.');
        } else {
            // успешная попытка авторизации
            if (isset($_SESSION['accessTries'])) {
                unset($_SESSION['accessTries']);
            }
            
            self::$authorized = 
                [
                    'status' => true,
                    'login' => $login
                ];
            $_SESSION['authorized'] = self::$authorized;

            return true;
        }

        return false;
    }
        
    /**
     * Проверяет, не было ли превышения числа попыток авторизации в единицу времени
     *
     * @param int $minutes временной промежуток в минутах, за который проверяются попытки
     * @param int $maxAttempts максимальное число попыток
     *
     * @return bool
     */
    private function checkAuthAttempts($minutes = 1, $maxAttempts = 5)
    {
        if (isset($_SESSION['accessTries'])) {
            if (count($_SESSION['accessTries']) >= $maxAttempts) {
                // всего было больше $maxAttempts попыток
                // $_SESSION['accessTries'] - массив, где $maxAttempts-ое значение - последняя по времени попытка авторизации
                $firstTry = $_SESSION['accessTries'][0]; // Unix timestamp
                if (($firstTry + $minutes * 60) > time()) {
                    // за последнюю минуту было $maxAttempts попыток
                    $_SESSION['accessTries'][] = time();
                    array_shift($_SESSION['accessTries']);
                    return false;
                }
                
                // удаляем первый, самый старый, элемент массива
                 array_shift($_SESSION['accessTries']);
            }
        }

        // в любом случае добавляем новую попытку                    
        $_SESSION['accessTries'][] = time();

        return true;
    }

    /**
     * Разлогинивает пользователя
     *
     * @return void
     */
    public function logout()
    {
        self::$authorized['status'] = false;
        self::$authorized['login'] = '';
        if (isset($_SESSION['authorized'])) {
            unset($_SESSION['authorized']);
        }
        if (isset($_SESSION['accessTries'])) {
            unset($_SESSION['accessTries']);
        }
    }

    /**
     * Выдаёт статус авторизации
     *
     * @return bool
     */
    public function getStatus()
    {
        return self::$authorized['status'];
    }

    /**
     * Выдаёт логин авторизованного пользователя
     *
     * @return string
     */
    public function getUserLogin()
    {
        return self::$authorized['login'];
    }

}