<?php

require_once('lib/Experiment.php');

$experiment = new Experiment(1);
?></!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/nv.d3.css" type="text/css" />
	<title>Consequences of Subconscious Bias in Corporate Promotions</title>
</head>
<body>
	<?php $experiment->runExperiment(); ?>
	<div id="chart1"><svg style="height: 500px;"> </svg></div>
	<div id="company2"><svg style="height: 500px;"> </svg></div>
	<script type="text/javascript" src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/nv.d3.min.js" charset="utf-8"></script>
	<script type="text/javascript">
		function staticData() { return <?php echo $experiment->json(0); ?>};
		nv.addGraph(function() {
			var chart = nv.models.multiBarHorizontalChart()
				.x(function(d) { return d.label })
				.y(function(d) { return d.value })
				.margin({top: 30, right: 20, bottom: 50, left: 175})
				.showValues(true)
				.stacked(true)
				.showControls(false);

			chart.yAxis
				.tickFormat(d3.format(',.2f'));

			d3.select('#chart1 svg')
				.datum(staticData())
				.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
	</script>
</body>
</html>
