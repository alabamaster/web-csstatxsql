<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'inc/config.php';
require_once 'inc/func.php';
require_once 'inc/SteamUserFunctions.php';

$Time = new TimePlayers();

$id = $_GET['id'];

if ( $id != true ) {
	$url = $main['url'];
	header("Location: $url");
}

$row = DB::run('SELECT * FROM `csstats` WHERE `id` = ?', [$id])->fetch(PDO::FETCH_ASSOC);

$kdratio = round($row['kills'] / ($row['deaths'] +1), 2);

//$stats_weapons = 1;
//$stats_maps = 1;
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
			.circle_maps {
				border-radius: 50%/50%;
				width: 75px;
				height: 75px;
				margin-top: 20px;
			}
			.circle_weapons {
				border-radius: 50%/50%;
				width: 75px;
				height: 75px;
				margin-top: 20px;
			}
		</style>

		<title><?=$row['name']?></title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
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
			<div class="row mx-md-n1 mt-1" id="1">
				<?php 
					// статус бана
					if ( $main['userIconBan'] == 1 ) {
						$ban = DB::run('SELECT `ban_reason`, `ban_created`, `ban_length`, `expired` FROM `amx_bans` WHERE `ban_length` != -1 AND `ban_length` != 1 AND `player_id` = ? OR `player_nick` = ?', [ $row['steamid'], $row['name'] ])->fetch(PDO::FETCH_ASSOC);

						if ( $ban ) {
							if ( $ban['ban_length'] == -1 ) {
								$ban_status = '';
							} elseif ( $ban['expired'] == 1 ) {
								$ban_status = '';
							} elseif ( $ban['ban_length'] == 0 ) {
								$ban_status = 'навсегда';
								echo '
									<div class="col-md-12 px-md-1 py-md-1">
										<div class="block" style="padding: 5px;background-color: #ffcbcb;">
											<span class="fa-stack fa-lg">
												<i class="fa fa-user-o fa-stack-1x"></i>
												<i class="fa fa-ban fa-stack-2x text-danger"></i>
											</span> Игрок забанен <b>'.$ban_status.'</b>. Причина <b>'.$ban['ban_reason'].'</b>
										</div>
									</div>
								';
							} elseif ( ($ban['ban_created'] + $ban['ban_length'] * 60) < time() ) {
								$ban_status = '';
							} else {
								$ban_status = 'на ' . $ban['ban_length'] . ' мин.';
								echo '
									<div class="col-md-12 px-md-1 py-md-1">
										<div class="block" style="padding: 5px;background-color: #ffcbcb;">
											<span class="fa-stack fa-lg">
												<i class="fa fa-user-o fa-stack-1x"></i>
												<i class="fa fa-ban fa-stack-2x text-danger"></i>
											</span> Игрок забанен <b>'.$ban_status.'</b>. Причина <b>'.$ban['ban_reason'].'</b>
										</div>
									</div>
								';
							}
						}
					}
				?>
				<div class="col-md-12 px-md-1 py-md-1">
					<div class="block">
						<div class="row">
							<div class="col-md-6">
<?php 
$steam1 = 'STEAM_0';
$steam2 = substr($row['steamid'], 0, 7);
if ( $steam1 == $steam2 ) 
{
	$url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=A4BA25A5FA6A9C74239A408079127082&steamids='.toCommunityID($row['steamid']);
	$shit = file_get_contents($url);
	$json = json_decode($shit);

	$steam_url_ava = "<img src=\"{$json->response->players[0]->avatarfull}\" class='mr-3' style='width:60px;border-radius:3px;'>";
	$steam_url = '<a href="https://steamcommunity.com/profiles/'.toCommunityID($row['steamid']).'" target="_blank">Профиль Steam</a>';
}
?>
								<div class="media">
									<!-- <img src="" class="mr-3"> -->
									<?=@$steam_url_ava;?>
									<div class="media-body">
										<?php 
											// выводим иконку возле ника
											if ( $main['userIcons'] == 1 ) {
												$info = DB::run('SELECT * FROM `amx_amxadmins` WHERE `steamid` = ? OR `nickname` = ?', [ $row['steamid'], $row['name'] ])->fetch(PDO::FETCH_ASSOC);
												if ( $info ) {
													echo '<span style="position: relative;bottom: 2px;">'.userIcon($info['access']).'</span>';
												}
											}
										?>
										<b><?=$row['name'];?></b><br><?=@$steam_url;?>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col text-right">
										<span style="padding: 0 0 0 24px;">Первая игра <b><?=mb_substr($row['first_join'], 0, 10)?></b></span>
									</div>
									<div class="col text-left">
										<span style="padding: 0 0 0 24px;">Последняя игра <b><?=mb_substr($row['last_join'], 0, 10)?></b></span>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<span style="padding: 0 24px 0 0;">Килов <b><?=number_format($row['kills']);?></b></span>
						<span style="padding: 0 24px 0 0;">В голову <b><?=number_format($row['hs']);?></b></span>
						<span style="padding: 0 24px 0 0;">Смертей <b><?=number_format($row['deaths']);?></b></span>
						<span style="padding: 0 24px 0 0;">Урон <b><?=number_format($row['dmg']);?></b></span>
						<span style="padding: 0 24px 0 0;">Скилл <b><?=$row['skill'];?></b></span>
						<span style="padding: 0 24px 0 0;">K/D <b><?=$kdratio;?></b></span>
					</div>
				</div>
			</div>
			<div class="row mx-md-n1" id="2">
				<div class="col-md-6 px-md-1 py-md-1">
					<div class="block">
						<?php 
							$eff = floor((100 * $row['kills']) / ($row['kills'] + $row['deaths'])); // эффективность
							$acc = floor(100 * $row['hits'] / $row['shots']); // точность
							$hss = floor(100 * $row['hs'] / ($row['kills'] + $row['deaths'])); // эффективность в hs
						?>
						<div style="padding-bottom: 14px;">
							Точность
							<div class="progress">
								<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?=$eff;?>%" aria-valuenow="<?=$eff;?>" aria-valuemin="0" aria-valuemax="100"><?=$eff;?>%</div>
							</div>
						</div>
						<div style="padding-bottom: 14px;">
							Эффективность
							<div class="progress">
								<div class="progress-bar progress-bar-striped" role="progressbar" style="width: <?=$acc;?>%" aria-valuenow="<?=$acc;?>%" aria-valuemin="0" aria-valuemax="100"><?=$acc;?>%</div>
							</div>
						</div>
						<div style="padding-bottom: 14px;">
							Эффективность в HS
							<div class="progress">
								<div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: <?=$hss;?>%" aria-valuenow="<?=$hss;?>" aria-valuemin="0" aria-valuemax="100"><?=$hss;?>%</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 px-md-1 py-md-1">
					<div class="block">
						<div>
							<div class="input-group mb-3" data-toggle="tooltip" data-placement="left" title="SteamID">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1" style="width: 50px;justify-content: center;"><i class="fa fa-steam"></i></span>
								</div>
								<input type="text" class="form-control" value="<?=$row['steamid']?>" aria-describedby="basic-addon1" disabled>
							</div>
						</div>
						<div>
							<div class="input-group mb-3" data-toggle="tooltip" data-placement="left" title="IP адрес">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1" style="width: 50px;justify-content: center;"><i class="fa fa-globe"></i></span>
								</div>
								<input type="text" class="form-control" value="<?=$row['ip']?>" aria-describedby="basic-addon1" disabled>
							</div>
						</div>
						<div>
							<div class="input-group mb-3" data-toggle="tooltip" data-placement="left" title="Страна">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1" style="width: 50px;justify-content: center;">
									<span class="flag-icon flag-icon-<?=mb_strtolower(geoip_country_code_by_name($row['ip']))?>"></span>
								</span>
								</div>
								<input type="text" class="form-control" value="<?=geoip_country_name_by_name($row['ip'])?>" aria-describedby="basic-addon1" disabled>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php if ( $stats_weapons == 1 || $stats_maps == 1 ) { ?>
			<div class="row mx-md-n1" id="3">
				<?php if ( $stats_weapons == 1 ) { ?>
					<div class="col-md-6 px-md-1 py-md-1">
						<div class="block">
							<h5>Топ 3 оружия</h5>
							<?php 
								$w_id = $row['id'];
								$weapons = DB::run('SELECT `player_id`, `weapon`, `kills`, `deaths`, `dmg`, `hits` FROM `csstats_weapons` WHERE `player_id` = ? ORDER BY `kills` DESC LIMIT 3', [ $w_id ]);
								$w_count = $weapons->rowCount();

								if ( $w_count > 0 ) {
									$weapons = $weapons->FetchAll(PDO::FETCH_ASSOC);
									//while ( $r = $weapons->fetch(PDO::FETCH_ASSOC) ) {
									foreach ($weapons as $r) {
										echo '<div class="media">';
										echo '<img src="w_img/'.$r['weapon'].'.png" title="'.$r['weapon'].'" class="circle_weapons mr-3">';
										echo '<div class="media-body">';
										echo 'Название <b>' . $r['weapon'] . '</b></br>';
										echo 'Килов <b>' . number_format($r['kills']) . '</b></br>';
										echo 'Смертей <b>' . number_format($r['deaths']) . '</b></br>';
										echo 'Урон <b>' . number_format($r['dmg']) . '</b><br>';
										echo 'Попаданий <b>' . number_format($r['hits']) . '</b>';
										echo '</div>';
										echo '</div><hr>';
									}
								} else {
									echo '<div class="orange_alert">Нет информации</div>';
								}
							?>
						</div>
					</div>
				<?php } ?>
				<?php if ( $stats_maps == 1 ) { ?>
					<div class="col-md-6 px-md-1 py-md-1">
						<div class="block">
							<h5>Топ 3 карты</h5>
							<?php 
								$m_id = $row['id'];
								$maps = DB::run('SELECT `player_id`, `map`, `kills`, `deaths`, `dmg`, `connection_time` FROM `csstats_maps` WHERE `player_id` = ? GROUP BY `map` ORDER BY `connection_time` DESC LIMIT 3', [ $m_id ]);
								$m_count = $maps->rowCount();
								
								if ( $m_count > 0 ) {
									//while ( $r = $maps->fetch(PDO::FETCH_ASSOC) ) {
									$maps = $maps->FetchAll(PDO::FETCH_ASSOC);
									foreach ($maps as $r) {
										// map images
										$url = 'https://image.gametracker.com/images/maps/160x120/cs/'.$r['map'].'.jpg';
										$ch = curl_init($url);
										curl_setopt($ch, CURLOPT_HEADER, true);   
										curl_setopt($ch, CURLOPT_NOBODY, true);    
										curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.4");
										curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
										curl_setopt($ch, CURLOPT_TIMEOUT,10);
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
										$output = curl_exec($ch);
										$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
										curl_close($ch);
										//echo $httpcode;

										$mapimage = ( $httpcode == 200 ) ? $url : 'https://image.gametracker.com/images/maps/160x120/nomap.jpg';

										echo '<div class="media">';
										echo '<img src="'.$mapimage.'" class="rounded mr-3">';
										echo '<div class="media-body">';
										echo 'Название <b>' . $r['map'] . '</b></br>';
										echo 'Всего килов <b>' . number_format($r['kills']) . '</b></br>';
										echo 'Всего смертей <b>' . number_format($r['deaths']) . '</b></br>';
										echo 'Нанес урона <b>' . number_format($r['dmg']) . '</b></br>';
										echo 'Играл на карте <b>' . $Time->TimeOn($r['connection_time']) . '</b>';
										echo '</div>';
										echo '</div><hr>';
									}
								} else {
									echo '<div class="orange_alert">Нет информации</div>';
								}
							?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?> 
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
							$("#3").remove();
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