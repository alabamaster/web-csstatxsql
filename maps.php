<?php 
require 'inc/config.php';
require 'inc/func.php';

$Time = new TimePlayers();
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

		<!-- Main CSS -->
		<link rel="stylesheet" href="<?=$main['url']?>template/css/main-fc.css">
		<link rel="stylesheet" href="<?=$main['url']?>template/css/flag-icon.css">

		<title>Статистика карт</title>
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
					</div>
				</nav>
			</div>
			<div class="row py-sm-2">
				<div class="col">
					<div class="block">
						<?php 
							$maps = DB::run('SELECT `player_id`, `map`, SUM(kills) AS `kills`, SUM(deaths) AS `deaths`, SUM(dmg) AS dmg, SUM(connection_time) AS `connection_time` FROM `csstats_maps` GROUP BY `map` ORDER BY `kills` DESC');
						?>
						<div class="table-responsive-md">
							<table class="table">
								<thead>
									<tr>
										<th style="padding-left: 20px;">Карта</th>
										<th>Фраги</th>
										<th>Смерти</th>
										<th>Урон</th>
										<th>Общее время</th>
									</tr>
								</thead>
								<tbody>
							<?php
								$id = 1;
								$maps = $maps->FetchAll(PDO::FETCH_ASSOC);
								foreach ($maps as $r) {
									$asd = DB::run('SELECT * FROM `csstats_maps` `t1` JOIN `csstats` `t2` WHERE `t1`.`map` = ? AND `t1`.`player_id` = `t2`.`id` GROUP BY `name` ORDER BY (`t1`.`kills` - `t1`.`deaths`) LIMIT 10', [ $r['map'] ]);

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

									echo '<tr>';
									echo '<td><div class="media">';
									echo '<img src="'. $mapimage .'" class="mapimage mr-3">';
									echo '<div class="media-body">';
									echo '<p style="padding-top: 10px;"><a href="#" data-toggle="modal" data-target="#map_id'.$id.'">'.$r['map'].'</a></p>';
									echo '</div>';
									echo '</div></td>';
									echo '<td><span class="txt1">' . number_format($r['kills']) . '</span></td>';
									echo '<td><span class="txt1">' . number_format($r['deaths']) . '</span></td>';
									echo '<td><span class="txt1">' . number_format($r['dmg']) . '</span></td>';
									echo '<td><span class="txt1">' . $Time->TimeOn($r['connection_time']) . '</span></td>';
							?>

								<div class="modal" id="map_id<?=$id?>" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-xl">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Топ лучших игроков карты: <?=$r['map']?></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<div class="row">
														<div class="col">
															<?php 
																$row_id = 1;
																$asd = $asd->FetchAll(PDO::FETCH_ASSOC);
																foreach ($asd as $row_asd) {
		echo '<p style="border-bottom: 1px dashed #ccc;">#'.$row_id.' <a href="'.$main['url'].'user.php?id='.$row_asd['id'].'">'.$row_asd['name'].'</a> заработал <b style="color:#00b707">'.$row_asd['kills'].'</b> фрагов, умер <b style="color:#ff5e5e">'.$row_asd['deaths'].'</b> раз, играл на карте <b style="color:#3899ff">'.$Time->TimeOn($row_asd['connection_time']).'</b></p><br>';
																	$row_id++;
																}
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php  $id++; } ?>
								</tbody>
							</table>
						</div>
					</div>	
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
	</body>
</html>