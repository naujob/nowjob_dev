<style>
.aside {display:none;}
.menu_nav {display:none;}
</style>

<div class="admin_main">
	<div class="left">

		<div class="section">
			<h3 class="sub-title mb-10">쇼핑몰 현황</h3>
			<div class="tab main js-tab cont_desc_type" style="width:100%;">
	            <a data-tab_id="#tab_1" href="javascript:void(0);" class="on">회원 가입현황</a>
	            <a data-tab_id="#tab_2" href="javascript:void(0);" class="">채용공고 등록현황</a>
	            <a data-tab_id="#tab_3" href="javascript:void(0);" class="">이력서 등록현황</a>
	            <a data-tab_id="#tab_4" href="javascript:void(0);" class="">결제 현황</a>
	        </div>
	        <div class="tab-content-wrap">
	            <div id="tab_1" class="tab-article on">
	            	<a href="/admin/member/member_list" style="float:right;text-decoration:underline;">전체 회원 현황 보기</a>
					<div class="" style="clear:both;">
						<canvas id="cahrt_1" ></canvas>
					</div>
	            </div>
	            <div id="tab_2" class="tab-article">
	            	<a href="/admin/member/recruit_list" style="float:right;text-decoration:underline;">전체 채용공고 보기</a>
	                <div class="" style="clear:both;">
						<canvas id="cahrt_2" ></canvas>
					</div>
	            </div>
	            <div id="tab_3" class="tab-article">
	            	<a href="/admin/member/resume_list" style="float:right;text-decoration:underline;">전체 이력서 보기</a>
	                <div class="" style="clear:both;">
						<canvas id="cahrt_3"></canvas>
					</div>

	            </div>
	            <div id="tab_4" class="tab-article">
	            	<a href="/admin/order/order_list" style="float:right;text-decoration:underline;">전체 결제 보기</a>
	               <div class="" style="clear:both;">
						<canvas id="cahrt_4"></canvas>
					</div>
	            </div>
	        </div>
		</div>
	</div>
	<div class="right">
		<div class="section">
			<h3 class="sub-title mb-10">
				1:1문의
				<a href="/admin/site/inquiry_list" class="more"><img src="/resource/admin/img/icon/main_more.gif" /></a>
			</h3>
			<table class="table-type-main" style="width:100%;">
	            <colgroup>
	                <col width="*" />
	                <col width="80px" />
	            </colgroup>
	            <tbody>
					<?php foreach($info["report_list"] as $row) : ?>
						<?php
							$current_time = new DateTime();
							// 데이터베이스에서 가져온 날짜와 시간
							$reg_dt = new DateTime($row["reg_dt"]);
							// 현재 시간에서 5일 전
							$three_days_ago = $current_time->sub(new DateInterval('P5D'));

							// 현재 시간에서 5일 이내인지 확인
							$is_within_three_days = $reg_dt >= $three_days_ago;
						?>
					<tr>
		                <td>
											<a href="/admin/site/inquiry_edit?idx=<?=$row["bd_idx"]?>"><?=$row["bd_subject"]?>
												<?= $is_within_three_days ? '<span style="background-color: #F86048; color: #fff; font-size: 0.8em; padding: 2px 5px; border-radius: 10px; text-align: center;">N</span>' : '' ?>
											</a>
										</td>
		                <td><?=substr($row["reg_dt"],0,10)?></td>
		            </tr>
		            <?php endforeach; ?>
		            <?php if(count($info["report_list"])==0) : ?>
		            <tr>
		                <td colspan="2" class="tc" style="height:100px;background:#f9f9f9;">내용이 없습니다.</td>
		            </tr>
		            <?php endif; ?>
				</tbody>
	        </table>
		</div>

		<div class="section">
			<h3 class="sub-title mb-10">
				커뮤니티 게시글
				<a href="/admin/site/community_list" class="more"><img src="/resource/admin/img/icon/main_more.gif" /></a>
			</h3>
			<table class="table-type-main" style="width:100%;">
	            <colgroup>
	                <col width="*" />
	                <col width="80px" />
	            </colgroup>
	            <tbody>
					<?php foreach($info["community_list"] as $row) : ?>
						<?php
							$current_time = new DateTime();
							// 데이터베이스에서 가져온 날짜와 시간
							$reg_dt = new DateTime($row["reg_dt"]);
							// 현재 시간에서 3일 전
							$three_days_ago = $current_time->sub(new DateInterval('P5D'));

							// 현재 시간에서 3일 이내인지 확인
							$is_within_three_days = $reg_dt >= $three_days_ago;
						?>
					<tr>
		                <td>
											<a href="/admin/site/community_edit?idx=<?=$row["bd_idx"]?>"><?=$row["bd_subject"]?>
												<?= $is_within_three_days ? '<span style="background-color: #F86048; color: #fff; font-size: 0.8em; padding: 2px 5px; border-radius: 10px; text-align: center;">N</span>' : '' ?>
											</a>
										</td>
		                <td><?=substr($row["reg_dt"],0,10)?></td>
		            </tr>
		            <?php endforeach; ?>
		            <?php if(count($info["community_list"])==0) : ?>
		            <tr>
		                <td colspan="2" class="tc" style="height:100px;background:#f9f9f9;">내용이 없습니다.</td>
		            </tr>
		            <?php endif; ?>
				</tbody>
	        </table>
		</div>
	</div>
</div>

<script>
	// ---------- 기본 line-bar 그래프 ----------
	var background_1 = 'rgba(54, 162, 235,1)';
	var background_2 = 'rgba(54, 162, 235,1)';
	var background_3 = 'rgba(54, 162, 235,1)';
	var background_4 = 'rgba(54, 162, 235,1)';

	var label = [];
	var data_1 = [];
	var data_2 = [];
	var data_3 = [];
	var data_4 = [];

	<?php $idx = 0;?>
	<?php foreach($info["member_list"] as $key=>$row) : ?>
	label[<?=$idx?>] = "<?=substr($row["date"],5,5)?>";
	data_1[<?=$idx++?>] = "<?=($row["cnt"])?>";
    <?php endforeach; ?>

    <?php $idx = 0;?>
	<?php foreach($info["recruit_list"] as $key=>$row) : ?>
	data_2[<?=$idx++?>] = "<?=($row["cnt"])?>";
    <?php endforeach; ?>

    <?php $idx = 0;?>
	<?php foreach($info["resume_list"] as $key=>$row) : ?>
	data_3[<?=$idx++?>] = "<?=($row["cnt"])?>";
    <?php endforeach; ?>

    <?php $idx = 0;?>
	<?php foreach($info["order_list"] as $key=>$row) : ?>
	data_4[<?=$idx++?>] = "<?=($row["cnt"])?>";
    <?php endforeach; ?>

	var chartData_1 = {
		labels: label,
		datasets: [
		// 전체매출
		{
			type: 'line',
			label: '회원가입',
			data: data_1,
            borderColor : background_1,
            pointBorderColor : ["rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)"],
            pointBackgroundColor : ["rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)"],
            pointBorderWidth : 1,
			borderWidth: 1,
			fill:false
		}]
	};

	var chartData_2 = {
		labels: label,
		datasets: [
		// 전체매출
		{
			type: 'line',
			label: '채용공고',
			data: data_2,
            borderColor : background_2,
            pointBorderColor : ["rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)"],
            pointBackgroundColor : ["rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)"],
            pointBorderWidth : 1,
			borderWidth: 1,
			fill:false
		}]
	};

	var chartData_3 = {
		labels: label,
		datasets: [
		// 전체매출
		{
			type: 'line',
			label: '이력서',
			data: data_3,
            borderColor : background_3,
            pointBorderColor : ["rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)"],
            pointBackgroundColor : ["rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)"],
            pointBorderWidth : 1,
			borderWidth: 1,
			fill:false
		}]
	};

	var chartData_4 = {
		labels: label,
		datasets: [
		// 전체매출
		{
			type: 'line',
			label: '결제',
			data: data_4,
            borderColor : background_4,
            pointBorderColor : ["rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)", "rgba(54, 162, 235, 1)"],
            pointBackgroundColor : ["rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)"],
            pointBorderWidth : 1,
			borderWidth: 1,
			fill:false
		}]
	};

	// ---------- 기본 line-bar 그래프 ----------

	window.onload = function() {
		var ctx_1 = document.getElementById("cahrt_1").getContext("2d");
		var myChart_1 = new Chart(ctx_1, {type: 'bar',
			data: chartData_1,
			options: {
				maintainAspectRatio: false,
				legend: {display: false},
				scales: {
					yAxes: [{
						ticks: {beginAtZero:false},
						afterFit: function(scaleInstance) {
							//scaleInstance.width = 70;
							var MaxLength = 0;
							$.each(scaleInstance.ticks, function(k, v) {
								if(MaxLength < v.length) MaxLength = v.length;
							});
							scaleInstance.width = MaxLength*10;
						}
					}]
				}
			}
		});

		var ctx_2 = document.getElementById("cahrt_2").getContext("2d");
		var myChart_2 = new Chart(ctx_2, {type: 'bar',
			data: chartData_2,
			options: {
				maintainAspectRatio: false,
				legend: {display: false},
				scales: {
					yAxes: [{
						ticks: {beginAtZero:false},
						afterFit: function(scaleInstance) {
							//scaleInstance.width = 70;
							var MaxLength = 0;
							$.each(scaleInstance.ticks, function(k, v) {
								if(MaxLength < v.length) MaxLength = v.length;
							});
							scaleInstance.width = MaxLength*10;
						}
					}]
				}
			}
		});

		var ctx_3 = document.getElementById("cahrt_3").getContext("2d");
		var myChart_3 = new Chart(ctx_3, {type: 'bar',
			data: chartData_3,
			options: {
				maintainAspectRatio: false,
				legend: {display: false},
				scales: {
					yAxes: [{
						ticks: {beginAtZero:false},
						afterFit: function(scaleInstance) {
							//scaleInstance.width = 70;
							var MaxLength = 0;
							$.each(scaleInstance.ticks, function(k, v) {
								if(MaxLength < v.length) MaxLength = v.length;
							});
							scaleInstance.width = MaxLength*10;
						}
					}]
				}
			}
		});

		var ctx_4 = document.getElementById("cahrt_4").getContext("2d");
		var myChart_4 = new Chart(ctx_4, {type: 'bar',
			data: chartData_4,
			options: {
				maintainAspectRatio: false,
				legend: {display: false},
				scales: {
					yAxes: [{
						ticks: {beginAtZero:false},
						afterFit: function(scaleInstance) {
							//scaleInstance.width = 70;
							var MaxLength = 0;
							$.each(scaleInstance.ticks, function(k, v) {
								if(MaxLength < v.length) MaxLength = v.length;
							});
							scaleInstance.width = MaxLength*10;
						}
					}]
				}
			}
		});
	};
</script>
