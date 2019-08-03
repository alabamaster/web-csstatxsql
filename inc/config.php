<?php 

/* настройки сайта */
$main = array(
	// url
	'url' 		=> 'https://csonelove.ru/test2/csstats/', // слэш в конце важен!
	'userIcons'	=> 1, // показывать иконки, настройка $user_access_cfg, 0 - нет / 1 - да (таблица статистики должна быть в БД csbans)
	'name'		=> 'Counter-Strike',
	'userIconBan' => 1 // статус бана для статистики (таблица статистики должна быть в БД csbans)
);

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
