(function ($) {
	$(function () {

			jQuery(".flot-graph").livequery(function() {
				
				var $this = $(this);
				
				var options;
				
				switch ($this.attr('data-type')) {
					case 'line-time':
						options = {
							xaxis: {
								mode: "time", 
								minTickSize: [1, 'day']
							}, 
							points: 
								{
								show: true
							}, 
							lines: {
								show: true
							}
						};
						break;
					case 'bar-time':
						options = {
							xaxis: {
								mode: "time", 
								minTickSize: [1, 'day']
							},
							stack: 1,
							bars: {
									show: true,
									barWidth : 10*60*60*1000, //1h
									fill:1
							}
						};
						break;
					case 'bar':
						
						var labels;
						
						$.ajax({
					
							url: $(this).attr('data-url'),
							async: false,
							data: {
								labels: true,
								'Range': $("select[name=Range]").val(),
								'StartDate': $("input[name=StartDate]").val(),
								'EndDate': $("input[name=EndDate]").val(),
								'SecurityID': $("input[name=SecurityID]").val(),
								'ID': $("input[name=ID]").val()
							},
							type: 'POST',
							dataType: 'json',
							success: function (data) {
								labels = data;

							}

						});
						
						
						options = {
							xaxis: {
								show: true,
								ticks: labels
							},
							series: {stack: 0,
								lines: {show: false, steps: false },
								bars: {show: true, barWidth: 0.9, align: 'center'}
							},
							legend: {
								container: $('.legend'),
								noColumns: 3 
							}
						};
						break;
					default:
						options = {
							xaxis: {
								mode: "time", 
								minTickSize: [1, 'day']
							}, 
							points: 
								{
								show: true
							}, 
							lines: {
								show: true
							}
						};
				}
				
				$.ajax({
					
					url: $(this).attr('data-url'),
					data: {
						'Range': $("select[name=Range]").val(),
						'StartDate': $("input[name=StartDate]").val(),
						'EndDate': $("input[name=EndDate]").val(),
						'SecurityID': $("input[name=SecurityID]").val(),
						'ID': $("input[name=ID]").val()
					},
					type: 'POST',
					dataType: 'json',
					success: function (data) {
						
						renderGraph($this, data, options);

					}
					
				});
				
			});
	
		function renderGraph($el, data, options) {

			jQuery.plot($el, data, options);
			
		}
		
	});
	
}(jQuery));

