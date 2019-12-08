<?php 

/* настройки сайта */
$main = array(
	// url
	'url' 		=> 'https://site.ru/', // слэш в конце важен!
	'userIcons'	=> 1, // показывать иконки, настройка $user_access_cfg, 0 - нет / 1 - да (таблица статистики должна быть в БД csbans)
	'name'		=> 'Counter-Strike',
	'userIconBan' => 1, // статус бана для статистики (таблица статистики должна быть в БД csbans)
	'phpGeoip' => 1, // 1 - use geoip php, 0 - api http://ip-api.com
);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'csbans');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_CHAR', 'utf8'); // latin1 or utf8

$stats_weapons = 1; // блок статистики по оружию
$stats_maps = 1; // блок статистики по картам

/* настройка флагов доступа */
// для отображения иконок в списке игроков с привилегиями
$user_access_cfg = array(
	'vip' => 'abimnopqrt', // вип
	'zvip' => 'bimnopqrt', // вип без иммунитета
	'girl' => 'abimnopqrst', // девушка
	'zgirl' => 'bimnopqrst', // девушка без иммунитета
	'admin' => 'abcdefijmnopqrtu', // админ
	'girl_adm' => 'abcdefijmnopqrstu', // девушка админ
	'full' => 'abcdefghijklmnopqrtu' // главный админ
);
