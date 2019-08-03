<?php

require 'config.php';

define('DB_HOST', '127.0.0.1'); // database host
define('DB_NAME', ''); // database name
define('DB_USER', ''); // database user
define('DB_PASS', ''); // database password
define('DB_CHAR', 'utf8'); // database charset

$time = time();

/* Фильтация данных */
function fl($m){
	$m = abs($m);
	$m = intval($m);
	return $m;
}

class DB
{
    protected static $instance = null;

    public function __construct() {}
    public function __clone() {}

    public static function instance()
    {
        if (self::$instance === null)
        {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => TRUE,
            );
            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR;
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
        }
        return self::$instance;
    }
    
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = [])
    {
            if (!$args)
            {
                 return self::instance()->query($sql);
            }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}
class MyPDO extends PDO
{
    public function run($sql, $args = NULL)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}

// инконки в списке покупателей
function userIcon($user_access_db)
{
    global $user_access_cfg;

    if ( $user_access_db == $user_access_cfg['vip'] ) 
    {
        return '<span data-toggle="tooltip" title="Вип игрок"><img src="template/img/pechenka.png"></span>';
    } 
    elseif ( $user_access_db == $user_access_cfg['zvip'] ) 
    {
        return '<span data-toggle="tooltip" title="Вип игрок без иммунитета :("><img src="template/img/kakashka.png"> <img src="template/img/pechenka.png"></span>';
    } 
    elseif ( $user_access_db == $user_access_cfg['girl'] ) 
    {
        return '<span data-toggle="tooltip" title="Девушка ^_^"><img src="template/img/heart.png"></span>';
    } 
    elseif ( $user_access_db == $user_access_cfg['zgirl'] ) 
    {
       return '<span data-toggle="tooltip" title="Девушка без иммунитета :("><img src="template/img/kakashka.png"> <img src="template/img/heart.png"></span>';
    } 
    elseif ( $user_access_db == $user_access_cfg['admin'] ) 
    {
        return '<span data-toggle="tooltip" title="Админ"><img src="template/img/admin.png"></span>';
    } 
    elseif ( $user_access_db == $user_access_cfg['girl_adm'] ) 
    {
        return '<span data-toggle="tooltip" title="Девушка админ ^_^"><img src="template/img/admin.png"> <img src="template/img/heart.png"></span>';
    }
    elseif ( $user_access_db == $user_access_cfg['full'] ) 
    {
       return '<span data-toggle="tooltip" title="Главный админ #_#"><img src="template/img/admin1337.png"></span>';
    } 
    else {
        return '<span data-toggle="tooltip" title="Статус не определен"><img src="template/img/unknown_user.png"></span>';
    }
}

// puwok
class TimePlayers
{   
    function TimeOn($play_time)
    {
        if(($play_time) == 0)
        {
            return 0;
        }
            $day = $play_time;
            
            $days = floor($day / 86400);
            if($days > 0) {
                $return['days'] = $days . ' ' . self::declOfNum($days, array('день', 'дня', 'дней'));
            }
            
            $hours = floor($day / 3600);
            if($hours > 0 && $days == 0)
            {
                $return['hours'] = $hours . ' ' . self::declOfNum($hours, array('час', 'часа', 'часов'));
            }
            
            $minutes = floor($day / 60) % 60;
            if($minutes > 0 AND $hours == 0 AND $days == 0)
            {
                $return['minutes'] = $minutes . ' ' . self::declOfNum($minutes, array('минуту', 'минуты', 'минут'));
            }
            
            // начиная с php 7.2.0 count() теперь будет выдавать предупреждение о некорректных исчисляемых типов, переданных в параметр array_or_countable.
            // https://www.php.net/manual/ru/function.count.php
            if(@count($return) > 0)
            {
                $TimeOn = implode(" ", $return);
            }
            else
            {
                $seconds = $day;
                if($seconds < 60)
                {
                    $TimeOn = $seconds . ' ' . self::declOfNum($seconds, array('секунда', 'секунды', 'секунд'));
                }
            }
        return $TimeOn;
    }

    static function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
    }
}