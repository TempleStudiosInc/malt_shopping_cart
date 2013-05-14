<?php
	$date_range = explode(' - ', Arr::get($params, 'date_range'));
	$start_date = date('Y-m-d 00:00:00', strtotime($date_range[0]));
	$end_date = date('Y-m-d 23:59:59', strtotime($date_range[1]));
	
	$shares_orm = ORM::factory('Share');
	$shares_orm->select(DB::expr('COUNT(id) AS share_count'));
	$shares_orm->select(DB::expr('DATE_FORMAT(date_generated, "%m-%d-%Y") AS date_generated_group'));
	$shares_orm->where('date_generated', 'BETWEEN', array($start_date, $end_date));
	$shares_orm->group_by(DB::expr('YEAR(date_generated)'));
	$shares_orm->group_by(DB::expr('MONTH(date_generated)'));
	$shares_orm->group_by(DB::expr('DAY(date_generated)'));
	$shares_orm = $shares_orm->find_all();
	
	$shares = array();
	foreach ($shares_orm as $stat)
	{
		$shares[$stat->date_generated_group] = (int) $stat->share_count;
	}
?>

<div class="span12">
	<div class="row-fluid">
		<div class="span4">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Date</th>
						<th style="text-align:right;">Shares</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($shares as $date => $share_count)
					{
						echo '<tr>';
						echo '<td>'.$date.'</td>';
						echo '<td style="text-align:right;">'.number_format($share_count).'</td>';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
		<div class="span8">
			<?php
				$unique_chart_id = uniqid();
			?>
			<div id="bar_chart_container_<?php echo $unique_chart_id ?>" class="well" style="background:white;">
				<canvas id="bar_chart_<?php echo $unique_chart_id ?>" width="800" height="300"></canvas>
			</div>
			<?php
				$labels = array();
				$data = array();
				foreach ($shares as $stat_label => $stat_data)
				{
					
					$labels[] = $stat_label;
					$data[] = $stat_data;
				}
			?>
			<script>
				var lineChartData = {
					labels : <?php echo json_encode($labels); ?>,
					datasets : [{
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,1)",
							pointColor : "rgba(151,187,205,1)",
							pointStrokeColor : "#fff",
							data : <?php echo json_encode($data); ?>,
							datasetFill : false
					}]
				}
				
				$(function () {
					var chart_1 = new Chart($('#bar_chart_<?php echo $unique_chart_id ?>').get('0').getContext('2d')).Line(lineChartData);
				})
			</script>
		</div>
	</div>
	<div class="row-fluid">
		<?php
			$date_range = explode(' - ', Arr::get($params, 'date_range'));
			$start_date = date('Y-m-d 00:00:00', strtotime($date_range[0]));
			$end_date = date('Y-m-d 23:59:59', strtotime($date_range[1]));
			
			$shares_orm = ORM::factory('Share');
			$shares_orm->select(DB::expr('COUNT(id) AS share_count'));
			$shares_orm->where('date_generated', 'BETWEEN', array($start_date, $end_date));
			$shares_orm->where('network', '!=', '0');
			$shares_orm->group_by('network');
			$shares_orm->order_by('share_count', 'desc');
			$shares_orm = $shares_orm->find_all();
			
			$shares = array();
			foreach ($shares_orm as $stat)
			{
				$shares[$stat->network] = (int) $stat->share_count;
			}
		?>
		
		<div class="span4">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Social Network</th>
						<th style="text-align:right;">Shares</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$colors = array(
						'facebook' => '#3B5998',
						'twitter' => '#4099FF',
						'google_plus' => '#C73214',
						'pinterest' => '#C72520'
					);
					foreach ($shares as $network => $share_count)
					{
						echo '<tr>';
						echo '<td style="background-color:'.$colors[$network].'; color:white; font-weight:bold;">'.ucwords(str_replace('_', ' ', $network)).'</td>';
						echo '<td style="text-align:right;">'.number_format($share_count).'</td>';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
		<div class="span8">
			<?php
				$unique_chart_id = uniqid();
			?>
			<div id="bar_chart_container_<?php echo $unique_chart_id ?>" class="well" style="background:white;">
				<canvas id="pie_chart_<?php echo $unique_chart_id ?>" width="800" height="300"></canvas>
			</div>
			<?php
				$labels = array();
				$data = array();
				foreach ($shares as $stat_label => $stat_data)
				{
					
					$labels[] = $stat_label;
					$data[] = array(
						'value' => $stat_data,
						'color' => $colors[$stat_label]
					);
				}
			?>
			<script>
				var pieData = <?php echo json_encode($data); ?>;
				
				$(function () {
					var chart_1 = new Chart($('#pie_chart_<?php echo $unique_chart_id ?>').get('0').getContext('2d')).Pie(pieData);
				})
			</script>
		</div>
	</div>
	<div class="row-fluid">
		<?php
			$shares_orm = ORM::factory('Share');
			$shares_orm->select(DB::expr('COUNT(id) AS share_count'));
			$shares_orm->where('date_generated', 'BETWEEN', array($start_date, $end_date));
			$shares_orm->group_by('page_url');
			$shares_orm->order_by('share_count', 'DESC');
			$shares_orm->limit(10);
			$shares_orm = $shares_orm->find_all();
			
			$shares = array();
			foreach ($shares_orm as $stat)
			{
				$shares[parse_url($stat->page_url, PHP_URL_PATH)] = (int) $stat->share_count;
			}
		?>
		<div class="span6">
			<h5>Top Shared Pages</h5>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Date</th>
						<th style="text-align:right;">Shares</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($shares as $page_url => $share_count)
					{
						echo '<tr>';
						echo '<td>'.$page_url.'</td>';
						echo '<td style="text-align:right;">'.number_format($share_count).'</td>';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
		<?php
			$shares_orm = ORM::factory('Share');
			$shares_orm->select(DB::expr('COUNT(id) AS share_count'));
			$shares_orm->where('date_generated', 'BETWEEN', array($start_date, $end_date));
			$shares_orm->where('user_id', '!=', 0);
			$shares_orm->group_by('user_id');
			$shares_orm->order_by('share_count', 'DESC');
			$shares_orm->limit(10);
			$shares_orm = $shares_orm->find_all();
			
			$shares = array();
			foreach ($shares_orm as $stat)
			{
				$shares[$stat->user->email] = (int) $stat->share_count;
			}
		?>
		<div class="span6">
			<h5>Top Sharing Users</h5>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>User</th>
						<th style="text-align:right;">Shares</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($shares as $date => $share_count)
					{
						echo '<tr>';
						echo '<td>'.$date.'</td>';
						echo '<td style="text-align:right;">'.number_format($share_count).'</td>';
						echo '</tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>