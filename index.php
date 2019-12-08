<?php 
require_once 'inc/func.php';

$Time = new TimePlayers();
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="https://use.fontawesome.com/64ff6e1601.css">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap&subset=latin-ext" rel="stylesheet">

		<!-- https://github.com/stanlemon/jGrowl -->
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" />

		<!-- Main CSS -->
		<link rel="stylesheet" href="template/css/main-fc.css">
		<link rel="stylesheet" href="template/css/flag-icon.css">

		<title>Статистика игроков</title>
		<!-- <meta name="description" content="">-->
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
					<div class="table-responsive">
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
				</div>
				<div class="col block mr-md-2 ml-md-2" style="padding: 10px;">
					<div class="titleBlock" style="background-color: #343a40;">Топ по урону</div>
					<div class="table-responsive">
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
				</div>
				<div class="col block" style="padding: 10px;">
					<div class="titleBlock" style="background-color: #4d8cff;">Топ по скилу</div>
					<div class="table-responsive">
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
			</div>
			<div class="row" id="2">
				<div class="col block">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width: 60px;"><i class="fa fa-flag"></i></th>
									<th>Ник</th>
									<th>Фрагов</th>
									<th>Смертей</th>
									<th>Время в игре</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$sql = DB::run('SELECT * FROM `csstats` WHERE `dmg` > 0');
									$count = $sql->rowCount();


									$k_page = k_page($count,15);
									$page = page($k_page);
									$start = 15*$page-15;

									$sql2 = DB::run("SELECT * FROM `csstats` WHERE `dmg` > 0 ORDER BY (`kills`-`deaths`) DESC LIMIT $start, 15");

									if ( $count > 0 ) {
										foreach ($sql2->fetchAll() as $row) {
											// geoip
											if ( $main['phpGeoip'] == 1 ) {
												$country_code = mb_strtolower(geoip_country_code_by_name($row['ip']));
												$country_name = geoip_country_name_by_name($row['ip']);
											} else {
												$json = file_get_contents('http://ip-api.com/json/'.$row['ip'].'?lang=us');
												$array = json_decode($json, true);
												$country_code = mb_strtolower($array['countryCode']);
												$country_name = mb_strtolower($array['country']);
											}
	echo '<tr>';
	echo '<td style="width: 60px;"><span class="flag-icon flag-icon-'.$country_code.'" data-toggle="tooltip" data-placement="top" title="'.$country_name.'"></span></td>';
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
					</div>
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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