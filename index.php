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
	<div id="legend">Iteration <span>0</span></div>
	<div id="company1"><svg style="height: 500px;"> </svg></div>
	<script type="text/javascript" src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/nv.d3.min.js" charset="utf-8"></script>
	<script type="text/javascript">
		var iteration = 0;
		var company1Chart = null;
		var $company1Canvas = null;
		var companyDataVar = [
			<?php echo implode(', ', $experiment->json(0)); ?>
		];
		function companyData() {
			var iterationData = companyDataVar[iteration];

			if (!reachedMaxIteration()) {
				iteration++;
			}

			return iterationData;
		};
		function updateCompany1Chart() {
			$company1Canvas.datum(companyData());
			company1Chart.update();

			if (!reachedMaxIteration()) {
				setTimeout(function () {
					updateCompany1Chart();
				}, 250);
			}
		}

		function reachedMaxIteration() {
			return iteration > (companyDataVar.length - 1);
		}
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

			$company1Canvas = d3.select('#company1 svg')
				.datum(companyData())
				.call(chart);

			nv.utils.windowResize(chart.update);

			company1Chart = chart;
			updateCompany1Chart();
			return chart;
		});
	</script>
</body>
</html>
