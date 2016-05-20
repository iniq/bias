<?php

require_once('lib/Experiment.php');

$datasetCount = 20;

$experiment = new Experiment($datasetCount);
?></!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="css/nv.d3.css" type="text/css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<title>Consequences of Subconscious Bias in Corporate Promotions</title>
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1>Subconscious Bias Experiment Recreation</h1>
			<p>An attempt to recreate <a href="http://www.ruf.rice.edu/~lane/papers/male_female.pdf">this study</a>.</p>
		</div>
		<div id="companyAverage">
			<div>Iteration <span>0</span></div>
			<svg style="height: 500px;"></svg>
		</div>
		<div class="row">
			<?php
			$columnCount = 2;
			$experiment->runExperiment();

			for ($i = 0; $i < $datasetCount; $i++) :
				?>
				<div id="company<?php echo $i; ?>" class="col-md-<?php echo (12 / $columnCount); ?>">
					<div>Iteration <span>0</span></div>
					<svg style="height: 200px;"></svg>
				</div>
				<?php
				if ($i % $columnCount == ($columnCount - 1)) {
					echo '</div><div class="row">';
				}
			endfor;
			?>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery-1.12.3.min.js" charset="utf-8"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/nv.d3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/bias.js" charset="utf-8"></script>
	<script type="text/javascript">
		totalDatasets = <?php echo $datasetCount; ?>;
		<?php
		for ($i = 0; $i < $datasetCount; $i++) {
			echo 'datasets.push(new CompanyDataset('. $i .', ['. implode(', ', $experiment->json($i)) .']));';
		}
		?>
		average = new AveragedDataset('Average', datasets);
		initializeVisualization();
	</script>
</body>
</html>
