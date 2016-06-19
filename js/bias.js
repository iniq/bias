"use strict";

var datasets = [];
var totalDatasets = 0;
var average = null;

function initializeVisualization() {
	$('#loadingPlacard').hide();
	$('#graphContainer').show();

	average.register();
	for (var i = 0; i < datasets.length; i++) {
		datasets[i].register();
	}
}

function fetchDataset(datasetIndex) {
	$.getJSON('single.php', function (data) {
		datasets.push(new CompanyDataset(datasetIndex, data));
		$('#loadedDatasetCount').text(datasets.length);
		$('#loadingPlacard progress').val(datasets.length);

		if (datasets.length == totalDatasets) {
			average = new AveragedDataset('Average', datasets);
			window.setTimeout(initializeVisualization, 500);
		}
	});
}

$(document).ready(function () {
	for (var i = 0; i < totalDatasets; i++) {
		fetchDataset(i);
	}
});

class CompanyDataset {
	constructor(companyIndex, dataset) {
		this.companyIndex = companyIndex;
		this.iteration = 0;
		this.chart = null;
		this.$canvas = null;
		this.data = dataset;
		this.$iterationLabel = $('#company'+ this.companyIndex +' span');
	}

	initialize(chart, $canvas) {
		var self = this;
		this.chart = chart;
		this.$canvas = $canvas;

		setTimeout(function () {
			self.update();
		}, 1250);
	};

	companyData() {
		var iterationData = this.data[this.iteration];

		if (!this.reachedMaxIteration()) {
			this.iteration++;
		}

		return iterationData;
	};

	update() {
		this.$canvas.datum(this.companyData());
		this.chart.update();
		this.$iterationLabel.text(this.iteration);

		if (!this.reachedMaxIteration()) {
			var self = this;

			setTimeout(function () {
				self.update();
			}, 250);
		}
	};

	reachedMaxIteration() {
		return this.iteration >= this.data.length;
	};

	register() {
		var self = this;

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

			var $canvas = d3.select('#company'+ self.companyIndex +' svg')
				.datum(self.companyData())
				.call(chart);

			nv.utils.windowResize(chart.update);

			self.initialize(chart, $canvas);
			return chart;
		});
	};
}

class AveragedDataset extends CompanyDataset {
	constructor(selectorKey, companyDatasets) {
		super(selectorKey, null);
		this.data = this.averageDatasets(companyDatasets);
	}

	averageDatasets(companyDatasets) {
		var averagedData = [];
		var iterationData = [];
		var maximumIterationCount = 0;

		for (var i = 0; i < companyDatasets.length; i++) {
			maximumIterationCount = Math.max(companyDatasets[i].data.length, maximumIterationCount);
		}

		for (var iterationNumber = 0; iterationNumber < maximumIterationCount; iterationNumber++) {
			var iterationData = [];
			for (var datasetIndex = 0; datasetIndex < companyDatasets.length; datasetIndex++) {
				var datasetIteration = companyDatasets[datasetIndex].data[Math.min((companyDatasets[datasetIndex].data.length - 1), iterationNumber)];

				for (var genderIndex = 0; genderIndex < datasetIteration.length; genderIndex++) {
					var gender = datasetIteration[genderIndex];

					if (typeof iterationData[genderIndex] === 'undefined') {
						iterationData[genderIndex] = {
							'color': gender.color,
							'key': gender.key,
							'values': []
						};
					}

					for (var companyLevelIndex = 0; companyLevelIndex < gender.values.length; companyLevelIndex++) {
						var level = gender.values[companyLevelIndex];

						if (typeof iterationData[genderIndex].values[companyLevelIndex] === 'undefined') {
							iterationData[genderIndex].values[companyLevelIndex] = {
								'label': level.label,
								'value': 0,
								'componentValues': []
							};
						}
						iterationData[genderIndex].values[companyLevelIndex].componentValues.push(level.value);

						var pushedValues = iterationData[genderIndex].values[companyLevelIndex].componentValues.length;
						var sum = 0;
						for (var i = 0; i < pushedValues; i++) {
							sum += iterationData[genderIndex].values[companyLevelIndex].componentValues[i];
						}
						iterationData[genderIndex].values[companyLevelIndex].value = (sum / pushedValues);
					}
				}
			}

			averagedData.push(iterationData);
		}

		return averagedData;
	}
}
