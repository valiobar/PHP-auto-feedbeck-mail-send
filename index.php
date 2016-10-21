<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">

	<title>Customers Feedback</title>

	<style>html,body{background:#55C1E6 }</style>

	<link rel="stylesheet" href="interactivegraph/css/graph.css">
<meta name="robots" content="noindex,follow" />
</head>

<body >

<!-- Graph HTML -->
<div  id="graph-wrapper">
	<div class="graph-info">
		<a href="javascript:void(0)" class="visitors">Last 30 days feedbacks</a>
		<a href="javascript:void(0)" class="returning">Privios 30 days feedbacks </a>

		<a href="#" id="bars"><span></span></a>
		<a href="#" id="lines" class="active"><span></span></a>
	</div>

	<div class="graph-container">
		<div id="graph-lines"></div>
		<div id="graph-bars"></div>
	</div>
</div>



<table id="customers" style="display: none">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Order</th>
		<th>Mark</th>
		<th>Notes</th>
	</tr>

</table>
<!-- end Graph HTML -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="interactivegraph/js/jquery.flot.min.js"></script>
<script>
$(document).ready(function () {
    $('#customers').hide();
	// Graph Data ##############################################
	var graphData = [{
		label: "last",
			data: [ [1,<?=$_SESSION[data][last][1]?>], [2,<?=$_SESSION[data][last][2]?>], [3,<?=$_SESSION[data][last][3]?>], [4,<?=$_SESSION[data][last][4]?>], [5,<?=$_SESSION[data][last][5]?>] ],
			color: '#71c73e',

		}, {
			// Returning Visits
		label: 'previous',
			data: [[1,<?=$_SESSION[data][privios][1]?>], [2,<?=$_SESSION[data][privios][2]?>], [3,<?=$_SESSION[data][privios][3]?>], [4,<?=$_SESSION[data][privios][4]?>], [5,<?=$_SESSION[data][privios][5]?>] ],
			color: '#77b7c5',
			points: { radius: 4, fillColor: '#77b7c5' }
		}
	];

	// Lines Graph #############################################
	$.plot($('#graph-lines'), graphData, {
		series: {
			points: {
				show: true,
				radius: 5
			},
			lines: {
				show: true
			},
			shadowSize: 0
		},
		grid: {
			color: '#646464',
			borderColor: 'transparent',
			borderWidth: 20,
			hoverable: true,
			clickable: true
		},
		xaxis: {
			tickColor: 'transparent',
			tickDecimals: 2
		},
		yaxis: {
			tickSize: 10
		},
		legend :{
			show:false
		}
	});

	// Bars Graph ##############################################
	$.plot($('#graph-bars'), graphData, {
		series: {
			bars: {
				show: true,
				barWidth: .9,
				align: 'center'
			},
			shadowSize: 0
		},
		grid: {
			color: '#646464',
			borderColor: 'transparent',
			borderWidth: 20,
			hoverable: true
		},
		xaxis: {
			tickColor: 'transparent',
			tickDecimals: 2
		},
		yaxis: {
			tickSize: 10
		}
	});

	// Graph Toggle ############################################
	$('#graph-bars').hide();

	$('#lines').on('click', function (e) {
		$('#bars').removeClass('active');
		$('#graph-bars').fadeOut();
		$(this).addClass('active');
		$('#graph-lines').fadeIn();
		e.preventDefault();
	});

	$('#bars').on('click', function (e) {
		$('#lines').removeClass('active');
		$('#graph-lines').fadeOut();
		$(this).addClass('active');
		$('#graph-bars').fadeIn().removeClass('hidden');
		e.preventDefault();
	});

	// Tooltip #################################################
	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
			top: y - 16,
			left: x + 20
		}).appendTo('body').fadeIn();
	}

	var previousPoint = null;

	$('#graph-lines').bind("plotclick", function (event, pos, item) {
		if (item) {
		//	alert("You clicked at " +Math.round(pos.x) + ", " + Math.round(pos.y)+item.series.label);
			var period  = item.series.label;
			var mark =Math.round(pos.x);


			$.post( "customerStatistic.php", { period: period , mark: mark}, function( data ) {
				$('#customers').empty();
				$('#customers').show();
				for (var i = 0; i < data.length; i++) {
				 var $email= data[i].email;
					var	$name = data[i].name;
					var	$order=data[i].order_id;
					var	$mark = data[i].mark;
					var	$notes =data[i].notes;
					$('#customers').append("<tr><th>"+ $name +"</th><th>" +$email+ "</th><th>" +$order+ "</th><th>" +$mark+ "</th><th>" +$notes+ "</th></tr>");
				}
				
				

			}, "json");
			//return false; // keeps the page from not refreshing

		//	highlight(item.series, item.datapoint);
		//	alert("You clicked a point!");
		}
	});


	$('#graph-lines, #graph-bars').bind('plothover', function (event, pos, item) {
		if (item) {

			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				$('#tooltip').remove();
				var x = item.datapoint[0],
					y = item.datapoint[1];
					showTooltip(item.pageX, item.pageY, y + ' marks ' + x );
			}
		} else {
			$('#tooltip').remove();
			previousPoint = null;
		}
	});

});
</script>

</body>

</html>