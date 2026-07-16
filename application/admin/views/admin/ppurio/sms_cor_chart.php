<style>
	.admin_main div {
		float: none;
	}
.aside {display:none;}
.menu_nav {display:none;}

.sms-chart-page {
	display: flex;
	flex-direction: column;
	background: #fff;
	padding: 20px;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.sms-chart-header {
	margin-bottom: 15px;
	padding-bottom: 10px;
	border-bottom: 2px solid #f0f0f0;
}

.sms-chart-header h3 {
	margin: 0 0 8px 0;
	font-size: 18px;
	font-weight: 600;
	color: #333;
}

.sms-chart-header a {
	color: #0066cc;
	text-decoration: none;
	font-size: 13px;
	display: inline-block;
}

.sms-chart-header a:hover {
	text-decoration: underline;
}

.sms-chart-controls {
	display: flex;
	flex-direction: row;
	margin-bottom: 20px;
	padding: 15px;
	background: #f9f9f9;
	border-radius: 4px;
	gap: 10px;
	align-items: center;
}

.sms-chart-controls label {
	display: flex;
	gap: 8px;
	align-items: center;
	font-size: 14px;
	font-weight: 500;
}

.sms-chart-controls input[type="date"] {
	padding: 6px 10px;
	border: 1px solid #ddd;
	border-radius: 3px;
	font-size: 14px;
}

.sms-chart-controls button {
	white-space: nowrap;
}

.sms-chart-tabs {
	display: flex;
	flex-wrap: wrap;
	gap: 5px;
	margin-bottom: 20px;
	padding-bottom: 10px;
	border-bottom: 1px solid #ddd;
}

.sms-chart-tabs a {
	padding: 10px 15px;
	background: none;
	border: none;
	border-bottom: 3px solid transparent;
	color: #666;
	font-size: 14px;
	cursor: pointer;
	transition: all 0.3s;
	text-decoration: none;
}

.sms-chart-tabs a:hover {
	color: #0066cc;
}

.sms-chart-tabs a.on {
	color: #0066cc;
	border-bottom-color: #0066cc;
	font-weight: 600;
}

.sms-chart-wrapper {
	position: relative;
	height: 350px;
	margin-bottom: 30px;
	background: #fff;
	padding: 15px;
	border: 1px solid #f0f0f0;
	border-radius: 4px;
}

.sms-chart-wrapper canvas {
	max-height: 320px;
	width: 100% !important;
	height: auto !important;
}

.sms-chart-legend-section {
	padding-top: 20px;
	border-top: 1px solid #ddd;
}

.sms-chart-legend-section h4 {
	margin: 0 0 15px 0;
	font-size: 14px;
	font-weight: 600;
	color: #333;
}

.sms-chart-legend {
	list-style: none;
	padding: 0;
	margin: 0;
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	gap: 10px;
}

.sms-chart-legend li {
	padding: 8px 10px;
	background: #f9f9f9;
	border-radius: 3px;
	font-size: 13px;
	border-left: 3px solid #ddd;
}

.sms-chart-legend li strong {
	display: flex;
	align-items: center;
	gap: 6px;
	margin-bottom: 2px;
}

.sms-chart-legend .dot {
	display: inline-block;
	width: 10px;
	height: 10px;
	border-radius: 2px;
	flex-shrink: 0;
}
</style>

<div class="admin_main">
	<div class="sms-chart-page">
		<div class="sms-chart-header">
			<h3>차원별 발송 추이</h3>
			<a href="/admin/ppurio/sms_cor_reslut">목록으로</a>
		</div>

		<div class="sms-chart-controls">
			<label>
				발송일
				<input type="date" id="chart_sdate" value="<?=$search["sdate"]?>">
				~
				<input type="date" id="chart_edate" value="<?=$search["edate"]?>">
			</label>
			<button type="button" id="btnChartSearch" class="btn-m btn-type10">조회</button>
		</div>

		<div class="sms-chart-tabs js-chart-tab">
			<a href="javascript:void(0);" data-dim="업종" class="on">업종</a>
			<a href="javascript:void(0);" data-dim="연령">연령</a>
			<a href="javascript:void(0);" data-dim="성별">성별</a>
			<a href="javascript:void(0);" data-dim="국적">국적</a>
			<a href="javascript:void(0);" data-dim="근무형태">근무형태</a>
			<a href="javascript:void(0);" data-dim="급여형태">급여형태</a>
		</div>

		<div class="sms-chart-wrapper">
			<canvas id="sms_chart"></canvas>
		</div>

		<div class="sms-chart-legend-section">
			<h4>항목별 매칭수</h4>
			<ul id="sms_chart_legend" class="sms-chart-legend"></ul>
		</div>
	</div>
</div>

<script>
var chartInstance = null;
var currentDimension = "업종";

// 초기 차트 데이터 (PHP에서 렌더링)
var initialChart = <?=json_encode($chart)?>;

function renderChart(chartData) {
	// 기존 차트 제거
	if(chartInstance){
		chartInstance.destroy();
		chartInstance = null;
	}

	var ctx = document.getElementById("sms_chart").getContext("2d");

	// 데이터셋 구성
	var datasets = [];
	for(var i = 0; i < chartData.series.length; i++){
		var serie = chartData.series[i];
		datasets.push({
			label: serie.name,
			data: serie.data,
			borderColor: serie.color,
			backgroundColor: serie.color.replace("1)", "0.1)"),
			borderWidth: 2,
			fill: false,
			tension: 0.3,
			pointRadius: 4,
			pointBackgroundColor: serie.color,
			pointBorderColor: "#fff",
			pointBorderWidth: 1,
		});
	}

	chartInstance = new Chart(ctx, {
		type: 'line',
		data: {
			labels: chartData.dates,
			datasets: datasets,
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false,
				},
			},
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						stepSize: 1,
					},
				},
				x: {
					type: 'category',
				},
			},
		}
	});

	// 범례 렌더링
	var legendHtml = "";
	for(var i = 0; i < chartData.series.length; i++){
		var serie = chartData.series[i];
		var dot = '<span class="dot" style="background-color:' + serie.color + ';"></span>';
		legendHtml += '<li><strong>' + dot + serie.name + '</strong> ' + serie.total + '건</li>';
	}
	document.getElementById("sms_chart_legend").innerHTML = legendHtml;
}

// 초기 렌더링
renderChart(initialChart);

// 탭 클릭 이벤트
document.querySelectorAll('.js-chart-tab a').forEach(function(btn){
	btn.addEventListener('click', function(e){
		e.preventDefault();
		var dim = this.getAttribute('data-dim');
		currentDimension = dim;

		// 탭 활성화
		document.querySelectorAll('.js-chart-tab a').forEach(function(b){
			b.classList.remove('on');
		});
		this.classList.add('on');

		// AJAX로 데이터 조회
		var sdate = document.getElementById('chart_sdate').value;
		var edate = document.getElementById('chart_edate').value;

		if(!sdate || !edate){
			alert('발송일을 선택해주세요.');
			return;
		}

		$.ajax({
			url: '/admin/ppurio/sms_cor_chart_data',
			type: 'GET',
			dataType: 'json',
			data: {
				sdate: sdate,
				edate: edate,
				dimension: dim,
			},
			success: function(response){
				if(response.code === '00'){
					renderChart(response.data);
				} else {
					alert('데이터 조회 실패: ' + response.msg);
				}
			},
			error: function(){
				alert('차트 데이터 조회 중 오류가 발생했습니다.');
			}
		});
	});
});

// 조회 버튼 클릭 이벤트
document.getElementById('btnChartSearch').addEventListener('click', function(){
	var sdate = document.getElementById('chart_sdate').value;
	var edate = document.getElementById('chart_edate').value;

	if(!sdate || !edate){
		alert('발송일을 선택해주세요.');
		return;
	}

	$.ajax({
		url: '/admin/ppurio/sms_cor_chart_data',
		type: 'GET',
		dataType: 'json',
		data: {
			sdate: sdate,
			edate: edate,
			dimension: currentDimension,
		},
		success: function(response){
			if(response.code === '00'){
				renderChart(response.data);
			} else {
				alert('데이터 조회 실패: ' + response.msg);
			}
		},
		error: function(){
			alert('차트 데이터 조회 중 오류가 발생했습니다.');
		}
	});
});
</script>
