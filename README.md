Обсуждение https://dev-cs.ru/threads/7131/

## web-csstatxsql
Web для статистики CSstatsX SQL(https://dev-cs.ru/threads/573/)
1. Есть небольшая статистика по картам, в профиле игрока топ 3 карты и оружия(если включена статистика по картам и оружию)
2. Если игрок забанен, показывает сообщение (таблица статистики должна быть в одной базе с csbans/amxbans)
3. Есть иконки в профиле рядом с ником, если навести мышкол покажет тултип (таблица статистики должна быть в одной базе с csbans/amxbans)

## Настройка
1. Закинуть все файлы на веб хостинг
2. Основные настройки в inc/config.php
3. Подключение к БД в inc/func.php
4. Иконки(молнии, печенье и т.д.) настраиваются в config.php и в func.php в функции userIcon

## Демо
1. Главная https://csonelove.ru/test2/csstats/
2. Статистика стим игрока https://csonelove.ru/test2/csstats/user.php?id=863
3. Если игрок забанен https://csonelove.ru/test2/csstats/user.php?id=864
4. Статистика карт https://csonelove.ru/test2/csstats/maps.php

## Тема в стиле CS
1. Файлами из папки cs-style заменить стандартные
2. Демо https://csonelove.ru/test2/csstats-cs/
