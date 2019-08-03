<?php 
require 'config.php';
require 'func.php';

$Time = new TimePlayers();

$inputSearch = $_POST['search'];
htmlspecialchars($inputSearch);

$q = DB::run('SELECT * FROM `csstats` WHERE `steamid` = ? OR `name` = ? OR `ip` = ?', [ $inputSearch, $inputSearch, $inputSearch ]);
$count = $q->rowCount();
?>

<div class="row mt-md-1">
	<div class="col block">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width: 60px;"><i class="fa fa-flag"></i></th>
					<th>Ник</th>
					<th>SteamID</th>
					<th>Скилл</th>
					<th>Фрагов</th>
					<th>Смертей</th>
					<th>Время в игре</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ( $count > 0 ) {
					$q = $q->fetchAll(PDO::FETCH_ASSOC);
					foreach ($q as $row) {
						echo '<tr>';
						echo '<td style="width: 60px;"><span class="flag-icon flag-icon-'.mb_strtolower(geoip_country_code_by_name($row['ip'])).'" data-toggle="tooltip" data-placement="top" title="'.geoip_country_name_by_name($row['ip']).'"></span>
						</td>';
						echo '<td><a href="'.$main['url'].'user.php?id='.$row['id'].'">'.$row['name'].'</a></td>';
						echo '<td>'.$row['steamid'].'</td>';
						echo '<td>'.$row['skill'].'</td>';
						echo '<td>'.number_format($row['kills']).'</td>';
						echo '<td>'.number_format($row['deaths']).'</td>';
						echo '<td>'.$Time->TimeOn($row['connection_time']).'</td>';
						echo '</tr>';
					}
				} else {
					echo '<div class="blue_alert">Ничего не найдено</div>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>