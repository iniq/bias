<?php

$datasetCount = 20;
$showCompanies = true;

if (!empty($_GET['c'])) {
	$querystringCount = intval($_GET['c']);
	if ($querystringCount > 0) {
		$datasetCount = $querystringCount;
	}
}

if (isset($_GET['s']) && $_GET['s'] === '0') {
	$showCompanies = false;
}

?></!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="css/nv.d3.css" type="text/css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<link rel="stylesheet" href="css/bias.css" type="text/css" />

	<title>Consequences of Subconscious Bias in Corporate Promotions</title>
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1>Subconscious Bias Experiment Recreation</h1>
			<p>An attempt to recreate <a href="http://www.ruf.rice.edu/~lane/papers/male_female.pdf">this study</a>.</p>
		</div>
		<div id="loadingPlacard" class="jumbotron">
			<h1>Generating Study Results</h1>
			<p>Datasets are being created and the study simlulation is running.</p>
			<p><span id="loadedDatasetCount">0</span> of <?php echo $datasetCount; ?> company simulations complete.</p>
			<progress class="progress progress-striped progress-animated" value="0" max="<?php echo $datasetCount; ?>"></progress>
		</div>
		<div id="graphContainer">
			<div id="companyAverage" class="dynamicGraph">
				<h2>Averaged Data</h2>
				<div>Iteration <span>0</span></div>
				<svg style="height: 500px;"></svg>
			</div>
			<?php if ($showCompanies) : ?>
			<div id="individualCompaniesContainer">
				<div class="row">
					<?php
					$columnCount = 2;

					for ($i = 0; $i < $datasetCount; $i++) :
						?>
						<div id="company<?php echo $i; ?>" class="col-md-<?php echo (12 / $columnCount); ?> dynamicGraph">
							<h2>Company #<?php echo ($i + 1); ?></h2>
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
			<?php endif; ?>
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
		if (!$showCompanies) {
			echo 'registerCompanies = false;';
		}
		?>
	</script>
</body>
</html>
