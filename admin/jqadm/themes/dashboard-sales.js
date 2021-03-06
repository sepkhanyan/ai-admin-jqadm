/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard.Sales = {

	colors: ['#30a0e0', '#00b0a0', '#ff7f0e', '#e03028', '#00c8f0', '#00d0b0', '#c8d830', '#f8b820'],

	config: {
		type: 'line',
		data: {},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tooltips: {
				mode: 'index',
				position: 'nearest',
				intersect: false,
				callbacks: {}
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					distribution: 'series',
					gridLines: {
						drawOnChartArea: false
					},
				}],
				yAxes: [{
					display: true,
					gridLines: {
						drawOnChartArea: false
					},
				}]
			},
			legend: false,
		}
	},

	limit: 10000,


	addLegend: function(chart, selector) {
		const legend = chart.generateLegend();
		document.querySelector(selector + ' .chart-legend').appendChild(legend);

		legend.querySelectorAll('.item').forEach(function(item) {
			item.addEventListener('click', function() {
				const index = item.dataset.index;
				const meta = chart.getDatasetMeta(index);

				meta.hidden = !meta.hidden;
				item.classList.toggle('disabled');

				chart.update();
			});
		});
	},

	color: function(index) {
		return this.colors[index % this.colors.length];
	},

	context: function(selector) {
		const canvas = document.querySelector(selector + ' .chart canvas');
		if(!canvas) {
			throw "Unable to create canvas for " + selector + " .chart canvas";
		}
		return canvas.getContext('2d');
	},

	done: function(selector) {
		document.querySelectorAll(selector + ' .loading').forEach(function(el) {
			el.classList.remove('loading');
		});
	},

	gradient: function(ctx, index) {
		const gradient = ctx.createLinearGradient(0,0 , 0,280);

		gradient.addColorStop(0, Color(this.colors[index % this.colors.length]).alpha(0.5).rgbaString());
		gradient.addColorStop(0.66, Color('#ffffff').alpha(0.5).rgbaString());
		gradient.addColorStop(1, '#ffffff');

		return gradient;
	},

	legend: function(chart) {
		const legend = document.createElement('div');
		legend.classList.add('legend');

		chart.config.data.datasets.forEach(function(dset, idx) {

			const label = document.createElement('span');
			label.classList.add('label');
			label.appendChild(document.createTextNode(dset.label));

			const color = document.createElement('span');
			color.classList.add('color');
			color.style.backgroundColor = dset.borderColor;

			const item = document.createElement('div');
			item.classList.add('item');
			item.dataset.index = idx;

			item.appendChild(color);
			item.appendChild(label);
			legend.appendChild(item);
		});

		return legend;
	},

	log: function(response) {
		if(response.responseJSON && response.responseJSON.errors && response.responseJSON.errors[0] ) {
			console.error('[Aimeos] Failed fetching data:', response.responseJSON.errors[0].title);
		} else {
			console.error('Aimeos] Error:', response);
		}
	},


	init: function() {

		Aimeos.lazy(".order-salesday .chart", this.chartDay.bind(this));
		Aimeos.lazy(".order-salesmonth .chart", this.chartMonth.bind(this));
		Aimeos.lazy(".order-salesweekday .chart", this.chartWeekday.bind(this));
	},


	chartDay : function() {

		const self = this;
		const ctx = this.context('.order-salesday');
		const keys = "order.base.currencyid,order.cdate";
		const startdate = moment().utc().subtract(30, 'days');
		const enddate = moment().utc();
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit, "order.base.product.price", "sum").then(function(response) {

			let num = 0;
			const dsets = [], date = startdate.clone();

			for(const entry of response.data) {
				let data = [];

				do {
					let day = date.toISOString().substr(0, 10);

					data.push({x: date.toISOString(), y: entry['attributes'][day] || 0});
					date.add(1, 'days');
				} while(date.isBefore(enddate, 'day'));

				dsets.push({
					pointRadius: 2,
					label: entry['id'], data: data,
					borderColor: self.color(num),
					backgroundColor: self.gradient(ctx, num),
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.options.scales.xAxes[0].type = 'time';
			config.options.scales.xAxes[0].time = {unit: 'day'};
			config.options.legendCallback = self.legend;
			config.options.tooltips.callbacks.title = function(item) {
				return moment.utc(item[0].label).format('ll');
			};
			config.options.tooltips.callbacks.labelColor = function(item) {
				return {borderColor: '#000', backgroundColor: self.color(item.datasetIndex)};
			};

			self.addLegend(new Chart(ctx, config), '.order-salesday');

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-salesday');
		});
	},


	chartMonth : function() {

		const self = this;
		const ctx = this.context('.order-salesmonth');
		const keys = "order.base.currencyid,order.cmonth";
		const startdate = moment().utc().subtract(12, 'months');
		const enddate = moment().utc();
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cmonth", this.limit, "order.base.product.price", "sum").then(function(response) {

			let num = 0;
			const dsets = [], date = startdate.clone();

			for(const entry of response.data) {
				let data = [];

				do {
					let month = date.toISOString().substr(0, 7);

					data.push({x: date.toISOString(), y: entry['attributes'][month] || 0});
					date.add(1, 'months');
				} while(date.isBefore(enddate, 'month'));

				dsets.push({
					pointRadius: 2,
					label: entry['id'], data: data,
					borderColor: self.color(num),
					backgroundColor: self.gradient(ctx, num),
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.options.legendCallback = self.legend;
			config.options.scales.xAxes[0].type = 'time';
			config.options.scales.xAxes[0].time = {unit: 'month'};
			config.options.tooltips.callbacks.title = function(item) {
				return moment(item[0].label).format('MMM YYYY');
			};
			config.options.tooltips.callbacks.labelColor = function(item) {
				return {borderColor: '#000', backgroundColor: self.color(item.datasetIndex)};
			};

			self.addLegend(new Chart(ctx, config), '.order-salesmonth');

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-salesmonth');
		});
	},


	chartWeekday : function() {

		const self = this;
		const ctx = this.context('.order-salesweekday');
		const keys = "order.base.currencyid,order.cwday";
		const startdate = moment().utc().subtract(12, 'months');
		const enddate = moment().utc();
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit, "order.base.product.price", "sum").then(function(response) {

			let num = 0;
			const dsets = [];

			for(const entry of response.data) {
				let data = [];

				for(const wday in [...Array(7).keys()]) {
					data[wday] = entry['attributes'][wday] || 0;
				}

				dsets.push({
					pointRadius: 2,
					label: entry['id'], data: data,
					borderColor: self.color(num),
					backgroundColor: self.gradient(ctx, num),
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.data.labels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			config.options.legendCallback = self.legend;
			config.options.tooltips.callbacks.labelColor = function(item) {
				return {borderColor: '#000', backgroundColor: self.color(item.datasetIndex)};
			};

			self.addLegend(new Chart(ctx, config), '.order-salesweekday');

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-salesweekday');
		});
	}
};



$(function() {

	Aimeos.Dashboard.Sales.init();
});
