var datasets = [];

function companyDataset(companyIndex, dataset) {
	this.companyIndex = companyIndex;
	this.iteration = 0;
	this.chart = null;
	this.$canvas = null;
	this.data = dataset;
	this.$iterationLabel = $('#company'+ this.companyIndex +' span');

	this.initialize = function(chart, $canvas) {
		var self = this;
		this.chart = chart;
		this.$canvas = $canvas;

		setTimeout(function () {
			self.update();
		}, 1250);
	};

	this.companyData = function() {
		var iterationData = this.data[this.iteration];

		if (!this.reachedMaxIteration()) {
			this.iteration++;
		}

		return iterationData;
	};

	this.update = function() {
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

	this.reachedMaxIteration = function() {
		return this.iteration >= (this.data.length - 1);
	};

	this.setup = function() {
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

			$canvas = d3.select('#company'+ self.companyIndex +' svg')
				.datum(self.companyData())
				.call(chart);

			nv.utils.windowResize(chart.update);

			self.initialize(chart, $canvas);
			return chart;
		});
	};

	this.setup();
}
