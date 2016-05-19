<?php

require_once('lib/Experiment.php');

$datasetCount = 1;

$experiment = new Experiment($datasetCount);
?></!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/nv.d3.css" type="text/css" />
	<title>Consequences of Subconscious Bias in Corporate Promotions</title>
</head>
<body>
	<?php
	$experiment->runExperiment();

	for ($i = 0; $i < $datasetCount; $i++) :
		?>
		<div id="company<?php echo $i; ?>">
			<div>Iteration <span>0</span></div>
			<svg style="height: 500px;"></svg>
		</div>
		<?php
	endfor;
	?>
	<script type="text/javascript" src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/nv.d3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/bias.js" charset="utf-8"></script>
	<script type="text/javascript">
		<?php
		for ($i = 0; $i < $datasetCount; $i++) {
			echo 'datasets.push(new companyDataset('. $i .', ['. implode(', ', $experiment->json($i)) .']));';
		}
		?>
	</script>
</body>
</html>
