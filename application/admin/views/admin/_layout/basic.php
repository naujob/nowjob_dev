<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta property="og:title" content="<?=$g_setting["st_site_name"]?>">
    <meta property="og:type" content="website">
    <meta property="og:description" content="<?=$g_setting["st_site_name"]?> 솔류션">
    <meta property="og:image" content="/assets/vendor_owner_app/images/og.jpg">
    <title><?=$g_setting["st_site_name"]?></title>
    <link rel="shortcut icon" href="/resource/favicon.ico">

	<!-- css -->
	<link rel="stylesheet" href="/resource/admin/template/bootstrap/css/bootstrap.min.css">
	<link href='/resource/admin/css/font.css' rel="stylesheet" />
    <link href="/resource/admin/css/reset.css" rel="stylesheet" />
    <link href="/resource/admin/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/resource/admin/css/common.css" rel="stylesheet" />
    <link href="/resource/admin/css/hook.css" rel="stylesheet" />
    <link href="/resource/admin/template/preview/magnific-popup.css" rel="stylesheet" />
	<link href="/resource/admin/template/tagit/tagsinput.css" rel="stylesheet" />
	<link href="/resource/admin/template/summernote/summernote.min.css" rel="stylesheet">

  	<!-- js -->
    <script src="/resource/admin/js/jquery-3.5.1.min.js"></script>
    <script src="/resource/admin/js/jquery-ui.min.js"></script>
	<script src="/resource/admin/template/chartjs/chart.js"></script>
	<script src="/resource/admin/template/table/tableHeadFixer.js"></script>

    <!--에디터-->
	<script type="text/javascript" src="/resource/admin/template/preview/jquery.magnific-popup.min.js" charset="utf-8"></script>
	<script src="/resource/admin/template/bootstrap/js/bootstrap.min.js"></script>
	<script src="/resource/admin/template/tagit/tagsinput.js"></script>
	<script src="/resource/admin/template/summernote/summernote.min.js"></script>

	<link type="text/css" rel="stylesheet" href="/resource/admin/template/air-datepicker/css/datepicker.min.css">
    <script src="/resource/admin/template/air-datepicker/js/datepicker.min.js"></script>
	<script src="/resource/admin/template/air-datepicker/js/i18n/datepicker.ko.js"></script>

	<!--슬라이드-->
	<link href="/resource/admin/template/slider/keen-slider.min.css" rel="stylesheet">
	<script src="/resource/admin/template/slider/keen-slider.js"></script>

</head>
<body>
<div id="wrap">
    <div id="header">
        <div class="contents">
        	<div class="admin-menu">
                <div class="site_name">
                	<a href="/">
                		<?php if($g_setting["st_admin_type"]=="admin") : ?>
                			본사 관리자
                		<?php else : ?>
                			<?=$g_setting["st_site_name"]?> 관리자
                		<?php endif; ?>
                	</a>
                </div>
                <div class="right_btn">
					<ul>

						<li class="li ml-20"><a href="<?=get_site_domain()?>" class="btn-m btn-type9 fs13 " target="_blank">내홈페이지</a></li>
						<li class="li ml-10"><a href="/admin/home/logout" class="btn-m btn-type9 fs13 ">로그아웃</a></li>
					</ul>
				</div>
            </div>
                <?php $navCurrentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                <div class="nav">
				<div class="nav_box">
					<ul>
						<?php foreach($top_menu as $menu) : ?>
						<!-- 활성화시 hit클래스 추가 -->
            <?php ?>
						<li class=""><a href="<?=$menu["action"]?>" class="btns <?=$menu["active"]?>"><?=$menu["name"]?></a></li>
            <?php ?>
						<?php endforeach; ?>
                        <li class="<?=$navCurrentUrl === '/admin/setting/version_policy' ? 'hit' : ''?>">
                            <a href="/admin/setting/version_policy" class="btns <?=$navCurrentUrl === '/admin/setting/version_policy' ? 'hit' : ''?>">앱 업데이트 정책</a>
                        </li>
					</ul>
				</div>
			</div>
        </div>
    </div>
    <div id="container">
        <div class="aside">
            <div class="gnb">
                <ul>
                  <?php $left_menu_idx = 0; ?>
        					<?php foreach($left_menu as $p_menu) : ?>
        					<li>
                                <a href="javascript:void(0);" class="depth1 <?=$p_menu["active"]?>"><?=$p_menu["name"]?></a>
                                <ul style="<?=$p_menu["active"]=="on" ? "display:block;":""?>">
                                	<?php foreach($p_menu["children"] as $menu) : ?>
                								<li><a href='<?=$menu["action"]?>' class='<?=$menu["active"]?>'><?=$menu["name"]?></a></li>
                							<?php endforeach; ?>
                						</ul>
                  </li>
                  <?php endforeach; ?>                                                                                                                                                                                                                        <li>
                    <?php
                    // 현재 페이지 URL 가져오기 (쿼리스트링 제외)
                    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

                    // 표시할 URL 목록
                    $allowedUrls = [
                        "/admin/setting/manage/user",
                        "/admin/setting/manage/user_edit",
                        "/admin/setting/basic/config_point",
                        "/admin/setting/basic/config_point_edit",
                        "/admin/setting/basic/config_usepoint",
                        "/admin/setting/basic/config_usepoint_edit",
                        "/admin/setting/basic/policy",
                        "/admin/setting/version_policy"
                    ];
                     ?>
                    <?php if(in_array($currentUrl, $allowedUrls)){ //$p_menu["name"]=="기본설정" && $left_menu_idx == 0
                      $left_menu_idx++;
                      ?>
                      <li>
                        <?php if($currentUrl != "/admin/setting/manage/user"){ ?>
                        <a href="javascript:void(0);" class="depth1 on">기본설정</a>
                        <?php } ?>
                        <ul style="display:block;">
                            <li><a href="/admin/setting/manage/user">운영자 계정관리</a></li>
                            <li><a href="/admin/setting/basic/config_point" class="">구매포인트 설정</a></li>
                            <li><a href="/admin/setting/basic/config_usepoint" class="">사용포인트 설정</a></li>
                            <li><a href="/admin/setting/basic/policy" class="">이용약관 관리</a></li>
                            <li><a href="/admin/setting/version_policy" class="">앱 업데이트 정책</a></li>
                        </ul>
                      </li>
                    <?php } ?>
        				</ul>
            </div>
        </div>

		<div class="menu_nav">
			<h2 class="page-title mb-50">
        <?php
        if($currentUrl=="/admin/setting/manage/user")                 $short_menu_title="운영자 계정관리";
        if($currentUrl=="/admin/setting/manage/user_edit")            $short_menu_title="운영자 등록/수정";
        if($currentUrl=="/admin/setting/basic/config_point")          $short_menu_title="구매포인트 설정";
        if($currentUrl=="/admin/setting/basic/config_point_edit")     $short_menu_title="구매포인트 설정";
        if($currentUrl=="/admin/setting/basic/config_usepoint")       $short_menu_title="사용포인트 설정";
        if($currentUrl=="/admin/setting/basic/config_usepoint_edit")  $short_menu_title="구매포인트 설정";
        if($currentUrl=="/admin/setting/basic/policy")                $short_menu_title="이용약관 관리";
        if($currentUrl=="/admin/setting/version_policy")              $short_menu_title="앱 업데이트 정책";
        if($currentUrl=="/admin/ppurio/buy_recom")                    $short_menu_title="추천인재 구매상품 개인";
        if($currentUrl=="/admin/ppurio/sendPpurioSmsUnread")         $short_menu_title="지원자 알림 SMS 발송";
        if($currentUrl=="/admin/ppurio/sendCorPpurioSms")            $short_menu_title="매칭 이력서 SMS 발송";
        if($currentUrl=="/admin/ppurio/sendMemPpurioSms")            $short_menu_title="매칭 채용공고 SMS 발송";
        ?>
        <?=$short_menu_title?>
			</h2>
	        <div class="page-info">
	            <a href="/">홈</a>
	            <?php foreach($short_menu as $num => $short) : ?>
	            	<span>&#47;</span>
	            	<a href="<?=$short["action"]?>" class="<?=(count($short_menu) == $num + 1) ? "on":""?>"><?=$short["name"]?></a>
	            <?php endforeach; ?>
	        </div>
		</div>
        <div class="contents">
            <?=$contents?>
        </div>
    </div>
</div>
<div class="wrap-loading display-none">
    <div style="color:#fff;">데이터 처리중입니다..</div>
</div>
<!--js-->
<script src="/resource/admin/js/common.ui.js"></script>
<script src="/resource/admin/js/common.js"></script>

<script>
	$("#sel_admin_type").change(function(){
		var params = {};
		params["st_admin_type"] = $("#sel_admin_type").val();
		comAjaxLoading("/admin/setting/basic/setting_change_mode",params,function(result){
			if(result.code == "00"){
				location.reload();
			}else{
				COMMON.alert(result.msg);
			}
		});
	});

  var community_cnt = parseInt('<?=$community_cnt?>');
  var report_cnt    = parseInt('<?=$report_cnt?>');
  if(community_cnt + report_cnt > 0){
      $('.nav_box a').filter(function() {
          return $(this).text() === '사이트 관리';
      }).html(function(_, text) {
          return text + '<span style="margin-left: 5px; background-color: #F86048; color: #fff; padding: 2px 8px 2px 7px; border-radius: 12px;">N</span>';
      });
  }
  if(community_cnt > 0){
      $('.aside a[href="/admin/site/community_list"]').html(function(_, html) {
          return html + '<span style="margin-left: 5px; background-color: #F86048; color: #fff; padding: 2px 8px; border-radius: 12px;">'+community_cnt+'</span>';
      });
  }
  if(report_cnt > 0){
      $('.aside a[href="/admin/site/inquiry_list"]').html(function(_, html) {
          return html + '<span style="margin-left: 5px; background-color: #F86048; color: #fff; padding: 2px 8px; border-radius: 12px;">'+report_cnt+'</span>';
      });
  }

  $(document).ready(function(){
      let currentUrl = window.location.pathname;

      // 모든 <a> 태그 검사
      $("li > a").each(function () {
          let linkUrl = $(this).attr("href");

          // 현재 페이지 URL과 일치하면 'on' 클래스 추가
          if (currentUrl === linkUrl) {
              $(this).addClass("on");
          }
      });
      if(currentUrl === "/admin/setting/basic/config_usepoint_edit") {
          $("a[href='/admin/setting/basic/config_usepoint']").addClass("on");
      }
      if(currentUrl === "/admin/setting/basic/config_point_edit") {
          $("a[href='/admin/setting/basic/config_point']").addClass("on");
      }
      if(currentUrl === "/admin/setting/manage/user_edit") {
          $("a[href='/admin/setting/manage/user']").addClass("on");
      }
  });

</script>
</body>
</html>
