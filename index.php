<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'inc/config.php';
require 'inc/func.php';

$Time = new TimePlayers();

// bklv?
function page($k_page=1){ // Выдает текущую страницу
	$page=1;
	if (isset($_GET['page'])){
	if ($_GET['page']=='end')$page=intval($k_page);elseif(is_numeric($_GET['page'])) $page=intval($_GET['page']);}
	if ($page<1)$page=1;
	if ($page>$k_page)$page=$k_page;
	return $page;
}
function k_page($k_post=0,$k_p_str=10){ // Высчитывает количество страниц
	if ($k_post!=0) {$v_pages=ceil($k_post/$k_p_str);return $v_pages;}
	else return 1;
}
function str($link='?',$k_page=1,$page=1){ // Вывод номеров страниц (только на первый взгляд кажется сложно ;))
	echo '<ul class="pagination pagination-sm" style="justify-content: center;">';
	if ($page < 1) $page=1;
	if ($page != 1) echo '<li class="page-item"><a class="page-link" href="'.$link.'page=1" title="Первая страница"">&lt;&lt;</a></li>';
	if ($page != 1) echo '<li class="page-item"><a class="page-link" href="'.$link.'page=1" title="Страница №1">1</a></li>'; else echo '<li class="page-item active"><a class="page-link" href="#">1</a></li>';
	for ($ot=-3; $ot<=3; $ot++){
		if ($page + $ot > 1 && $page + $ot < $k_page){
			if ($ot != 0) echo '<li class="page-item"><a class="page-link" href="'.$link.'page='.($page+$ot).'" title="Страница №'.($page+$ot).'">'.($page+$ot).'</a></li>';else echo '<li class="page-item active"><a class="page-link" href="#">'.($page+$ot).'</a></li>';
		}
	}
	if ($page != $k_page) echo '<li class="page-item"><a class="page-link" href="'.$link.'page=end" title="Страница №'.$k_page.'">'.$k_page.'</a></li>';elseif ($k_page>1)echo '<li class="page-item active"><a class="page-link" href="#">'.$k_page.'</a></li>';
	if ($page!=$k_page) echo '<li class="page-item"><a class="page-link" href="'.$link.'page=end" title="Последняя страница">&gt;&gt;</a></li>';
	echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="https://use.fontawesome.com/64ff6e1601.css">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap&subset=latin-ext" rel="stylesheet">

		<!-- https://github.com/stanlemon/jGrowl -->
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" />

		<!-- Main CSS -->
		<link rel="stylesheet" href="<?=$main['url']?>template/css/main-fc.css">
		<link rel="stylesheet" href="<?=$main['url']?>template/css/flag-icon.css">
		<style type="text/css">
			.titleBlock {
				/* цвета бэкграунда добавлять отдельно */
				border-radius: 2px;
				padding: 3px;
				text-align: center;
				color: #fff;
			}
		</style>

		<title>Статистика</title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBar1337" aria-controls="navBar1337" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navBar1337">
					<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
						<span class="navbar-brand mb-0 h1"><i class="fa fa-bar-chart fa-1x" aria-hidden="true"></i> <?=$main['name']?></span>
						<?php require 'menu.php';?>
					</ul>
					<form class="form-inline my-2 my-lg-0">
						<input class="form-control mr-sm-2" type="search" id="inputSearch" placeholder="Ник / SteamID / IP" aria-label="Search">
						<button class="btn btn-warning my-2 my-sm-0" id="btnSearch" type="submit">Найти</button>
					</form>
					</div>
				</nav>
			</div>
			<div id="searchResult"></div>
			<div class="row py-sm-2" id="1">
				<div class="col block" style="padding: 10px;">
					<div class="titleBlock" style="background-color: coral;">Топ по килам</div>
					<table class="table table-sm" style="margin-bottom: 0;">
						<thead>
							<tr>
								<th style="width: 30px;text-align: center;">#</th>
								<th>Ник</th>
								<th>Килов</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$top3_kills = DB::run('SELECT * FROM `csstats` ORDER BY (`kills`-`deaths`) DESC LIMIT 3')->FetchAll(PDO::FETCH_ASSOC);
							$k = 1;
							foreach ($top3_kills as $row) {
								$name = ( mb_strlen(mb_substr($row['name'], 0, 20), 'UTF-8') >= 20 ) ? mb_substr($row['name'], 0, 20) . '...' : $row['name'];
								echo '
									<tr>
										<td><img src="'.$main['url'].'template/img/cup'.$k.'.png" style="width: 20px; height: 20px;"></td>
										<td><a href="'.$main['url'].'user.php?id='.$row['id'].'">'.$name.'</a></td>
										<td>'.number_format($row['kills']).'</td>
									</tr>
								';
								$k++;
							}
						?>
						</tbody>
					</table>
				</div>
				<div class="col block mr-md-2 ml-md-2" style="padding: 10px;">
					<div class="titleBlock" style="background-color: #343a40;">Топ по урону</div>
					<table class="table table-sm" style="margin-bottom: 0;">
						<thead>
							<tr>
								<th style="width: 30px;text-align: center;">#</th>
								<th>Ник</th>
								<th>Урон</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$top3_dmg = DB::run('SELECT * FROM `csstats` ORDER BY `dmg` DESC LIMIT 3')->FetchAll(PDO::FETCH_ASSOC);
							$d = 1;
							foreach ($top3_dmg as $row) {
								$name = ( mb_strlen(mb_substr($row['name'], 0, 20), 'UTF-8') >= 20 ) ? mb_substr($row['name'], 0, 20) . '...' : $row['name'];
								echo '
									<tr>
										<td><img src="'.$main['url'].'template/img/cup'.$d.'.png" style="width: 20px; height: 20px;"></td>
										<td><a href="'.$main['url'].'user.php?id='.$row['id'].'">'.$name.'</a></td>
										<td>'.number_format($row['dmg']).'</td>
									</tr>
								';
								$d++;
							}
						?>
						</tbody>
					</table>
				</div>
				<div class="col block" style="padding: 10px;">
					<div class="titleBlock" style="background-color: #4d8cff;">Топ по скилу</div>
					<table class="table table-sm" style="margin-bottom: 0;">
						<thead>
							<tr>
								<th style="width: 30px;text-align: center;">#</th>
								<th>Ник</th>
								<th>Скилл</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$top3_skill = DB::run('SELECT * FROM `csstats` ORDER BY `skill` DESC LIMIT 3')->FetchAll(PDO::FETCH_ASSOC);
							$s = 1;
							foreach ($top3_skill as $row) {
								$name = ( mb_strlen(mb_substr($row['name'], 0, 20), 'UTF-8') >= 20 ) ? mb_substr($row['name'], 0, 20) . '...' : $row['name'];
								echo '
									<tr>
										<td><img src="'.$main['url'].'template/img/cup'.$s.'.png" style="width: 20px; height: 20px;"></td>
										<td><a href="'.$main['url'].'user.php?id='.$row['id'].'">'.$name.'</a></td>
										<td>'.$row['skill'].'</td>
									</tr>
								';
								$s++;
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row" id="2">
				<div class="col block">
					<table class="table table-hover">
						<thead>
							<tr>
								<th style="width: 60px;"><i class="fa fa-flag"></i></th>
								<th><!--<i class="fa fa-user-o"></i>--> Ник</th>
								<th><!--<i class="fa fa-universal-access"></i>--> Фрагов</th>
								<th><!--<i class="fa fa-user-times"></i>--> Смертей</th>
								<th><!--<i class="fa fa-clock-o"></i>--> Время в игре</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$count = DB::run('SELECT * FROM `csstats` WHERE `kills` > 0')->rowCount();
								//$count = $count->rowCount();

								$k_page = k_page($count,15);
								$page = page($k_page);
								$start = 15*$page-15;

								$q2 = DB::run("SELECT * FROM `csstats` WHERE `kills` > 0 ORDER BY (`kills`-`deaths`) DESC LIMIT $start, 15")->FetchAll(PDO::FETCH_ASSOC);

								if ( $count > 0 ) {
									foreach ($q2 as $row) {
echo '<tr>';
echo '<td style="width: 60px;"><span class="flag-icon flag-icon-'.mb_strtolower(geoip_country_code_by_name($row['ip'])).'" data-toggle="tooltip" data-placement="top" title="'.geoip_country_name_by_name($row['ip']).'"></span></td>';
echo '<td><a href="'.$main['url'].'user.php?id='.$row['id'].'">'.$row['name'].'</a></td>';
echo '<td>'.number_format($row['kills']).'</td>';
echo '<td>'.number_format($row['deaths']).'</td>';
echo '<td>'.$Time->TimeOn($row['connection_time']).'</td';										
echo '</tr>';
									}
								} else {
								    echo '<div class="red_alert">Статистика пуста</div>';
								}
							?>
						</tbody>
					</table>
					<?php 
					if ( $count > 0 ) {
						$p_url = $main['url'] . '?';
						echo str($p_url, $k_page, $page);
					}
					?>
				</div>
			</div>
		</div>
		<!-- JavaScript -->
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<!-- JS -->
		<script type="text/javascript">
			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			})
		</script>

		<!-- search -->
		<!-- https://github.com/stanlemon/jGrowl -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#btnSearch').on('click', function(){
					var search = $('#inputSearch').val().trim();

					if ( search == '' ) {
						//mess();
						$.jGrowl('Запрос не может быть пустым', {
							theme: 'bg-danger',
							position: 'bottom-right',
							life: '3000'
						});
						return false;
					}
					if ( search.length < 3 ) {
						//mess();
						$.jGrowl('Запрос должен быть больше чем 2 символа', {
							theme: 'bg-danger',
							position: 'bottom-right',
							life: '3000'
						});
						return false;
					}

					$.ajax({
						url: 'inc/js_stats_search.php',
						type: 'POST',
						cache: false,
						data: { 'search':search },
						dataType: 'html',
						beforeSend: function() {
							$('#btnSearch').prop('disabled', true);
						},
						success: function(data) {
							$("#1").remove();
							$("#2").remove();
							$("#searchResult").html(data);
							$('#btnSearch').prop('disabled', false);
						},
						error: function() {
							alert('ajax error. js_stats_search');
							$('#btnSearch').prop('disabled', false);
						}
					});
				});
			});
		</script>
	</body>
</html>