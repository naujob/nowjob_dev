 <!-- 채팅팝업 -->
 <?php $user_type = $this->user->getUserType(); ?>
 <?php
	$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);
	?>
 <input type="hidden" name="rm_status" value="" />
 <input type="hidden" name="recruit_phone" value="" />
 <input type="hidden" name="recruit_name" value="" />
<style>
	.chat_noread_cnt {
		position: absolute;
		top: 25px;
		right: 8px;
		width: 26px;
		height: 26px;
		color: #fff;
		z-index: 100;
		font-size: 14px;
		background-color: rgb(248, 96, 72);
		text-align: center;
		line-height: 26px;
		border-radius: 13px;
	}

	/* 채팅 뷰 채용정보 카드 스타일 (mobile chat_view.php와 동일, main.css 참조 방지) */
	.chat-view-header .chat-recruit-content-li {
		position: relative !important;
		width: 100% !important;
		height: 115px !important;
		overflow: hidden !important;
		font-size: 12px !important;
		border-radius: 10px !important;
		background: #fff !important;
		margin-bottom: 10px !important;
		transition: background 0.2s ease !important;
		cursor: pointer !important;
	}

	.chat-view-header .chat-recruit-content-li:hover {
		background: #f8f8f8 !important;
	}

	.chat-view-header .chat-recruit-content-li * {
		padding: 0 !important;
		margin: 0 !important;
	}

	.chat-view-header .chat-recruit-content-info {
		width: 100% !important;
		height: 100% !important;
		padding: 10px !important;
	}

	.chat-view-header .chat-recruit-wicon {
		margin: 0 !important;
	}
	
	.chat-view-header .chat-recruit-wicon img {
		height: 19px !important;
	}

	.chat-view-header .chat-recruit-title {
		font-size: 18px !important;
		font-weight: bold !important;
		margin: 0 !important;
	}

	.chat-view-header .chat-recruit-title a {
		color: #000 !important;
		text-decoration: none !important;
	}

	.chat-view-header .chat-recruit-summary3 {
		position: absolute !important;
		top: 6px !important;
		right: 10px !important;
		color: #000 !important;
		height: 17px !important;
		text-align: right !important;
		font-size: 16px !important;
		font-weight: 600 !important;
	}

	.chat-view-header .chat-recruit-summary3 em {
		position: relative !important;
		display: inline-block !important;
		font-style: normal !important;
		padding-left: 5px !important;
	}

	.chat-view-header .chat-recruit-summary3 em:not(:last-child) {
		padding-right: 5px !important;
	}

	.chat-view-header .chat-recruit-summary3 em:not(:last-child)::after {
		content: "" !important;
		position: absolute !important;
		top: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		width: 1px !important;
		height: 0.5em !important;
		margin: auto !important;
		border-right: 1px solid #777777 !important;
	}

	.chat-view-header .chat-recruit-summary3 em.wtype1 {
		color: #3a70db !important;
	}

	.chat-view-header .chat-recruit-summary3 em.wtype2 {
		color: #F86048 !important;
	}

	.chat-view-header .chat-recruit-summary3 em.wtype3 {
		color: #1B9D7D !important;
	}

	.chat-view-header .chat-recruit-content-body {
		position: relative !important;
		height: 95px !important;
		overflow: hidden !important;
	}

	.chat-view-header .chat-recruit-desc {
		position: relative !important;
		height: 95px !important;
		margin: 0 0 0 105px !important;
		cursor: pointer !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 4px !important;
	}

	.chat-view-header .chat-recruit-desc .ellipsis {
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		white-space: nowrap !important;
	}

	.chat-view-header .chat-recruit-img {
		position: absolute !important;
		width: 95px !important;
		height: 95px !important;
		border: 0 !important;
		border-radius: 5px !important;
		color: #888 !important;
		overflow: hidden !important;
	}

	.chat-view-header .chat-recruit-img img {
		width: 100% !important;
		height: 100% !important;
		object-fit: cover !important;
		display: block !important;
	}

	.chat-view-header .chat-recruit-summary {
		display: flex !important;
		font-size: 16px !important;
		gap: 5px !important;
		position: relative !important;
		margin: 0 !important;
	}

	.chat-view-header .chat-recruit-summary p {
		position: relative !important;
		padding-right: 5px !important;
		margin: 0 !important;
		color: #999 !important;
	}

	.chat-view-header .chat-recruit-summary p:not(:last-child)::after {
		content: "" !important;
		position: absolute !important;
		top: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		width: 1px !important;
		height: 0.5em !important;
		margin: auto !important;
		border-right: 1px solid #ACACAC !important;
	}

	.chat-view-header .chat-recruit-summary>* {
		min-width: 0 !important;
		max-width: 100% !important;
		margin-right: 4px !important;
		margin-bottom: -3px !important;
	}

	.chat-view-header .chat-recruit-summary.area p {
		color: #000 !important;
		font-weight: 600 !important;
	}

	.chat-view-header .chat-recruit-summary2 {
		position: absolute !important;
		right: 0px !important;
		bottom: 0px !important;
		font-size: 18px !important;
		font-weight: 700 !important;
		letter-spacing: 0 !important;
		color: #222 !important;
		display: flex !important;
		flex-direction: column !important;
		align-items: flex-end !important;
	}

	.chat-view-header .chat-recruit-summary2 p {
		margin: 0 !important;
		text-align: right !important;
	}

	.chat-view-header .chat-recruit-summary2 .period {
		display: flex !important;
		gap: 5px !important;
		justify-content: flex-end !important;
		position: relative !important;
		font-size: 16px !important;
		font-weight: 600 !important;
	}

	.chat-view-header .chat-recruit-summary2 .amount em {
		font-style: normal !important;
	}

	.chat-view-header .chat-recruit-summary2 .period em {
		position: relative !important;
		display: inline-block !important;
		font-style: normal !important;
		color: #777 !important;
	}

	.chat-view-header .chat-recruit-summary2 .period em.on {
		color: #1B9D7D !important;
	}

	.chat-view-header .chat-recruit-btn-wrapper {
		display: block !important;
		text-align: center !important;
		padding: 10px !important;
		background: #f8f8f8 !important;
		border-top: 1px solid #e0e0e0 !important;
		text-decoration: none !important;
		color: #333 !important;
	}

	.chat-view-header .chat-recruit-btn-wrapper:hover {
		background: #e8e8e8 !important;
	}

	/* 채팅 뷰 인재정보 카드 스타일 (mobile chat_view.php와 동일, main.css 참조 방지) */
	.chat-view-header .chat-resume-content-li {
		position: relative !important;
		width: 100% !important;
		height: 115px !important;
		overflow: hidden !important;
		font-size: 12px !important;
		border-radius: 10px !important;
		background: #fff !important;
		margin-bottom: 10px !important;
		transition: background 0.2s ease !important;
		cursor: pointer !important;
	}

	.chat-view-header .chat-resume-content-li:hover {
		background: #f8f8f8 !important;
	}

	.chat-view-header .chat-resume-content-li * {
		padding: 0 !important;
		margin: 0 !important;
	}

	.chat-view-header .chat-resume-content-info {
		width: 100% !important;
		height: 100% !important;
		padding: 10px !important;
	}

	.chat-view-header .chat-resume-wicon img {
		height: 19px !important;
	}

	.chat-view-header .chat-resume-title {
		font-size: 18px !important;
		font-weight: bold !important;
		/* margin은 chat-resume-desc > * 선택자로 제거되므로 gap으로 간격 조정 */
	}

	.chat-view-header .chat-resume-title a {
		color: #000 !important;
		text-decoration: none !important;
	}

	.chat-view-header .chat-resume-content-body {
		position: relative !important;
		overflow: hidden !important;
		height: 95px !important;
	}

	.chat-view-header .chat-resume-desc {
		position: relative !important;
		height: 100% !important;
		margin: 0 0 0 110px !important;
		cursor: pointer !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 4px !important;
	}

	/* chat-resume-desc 내부 자식 요소들의 margin 제거하여 gap으로 일정한 간격 유지 */
	.chat-view-header .chat-resume-desc > * {
		margin: 0 !important;
	}

	.chat-view-header .chat-resume-desc .ellipsis {
		overflow: hidden !important;
		text-overflow: ellipsis !important;
		white-space: nowrap !important;
	}

	.chat-view-header .chat-resume-img {
		position: absolute !important;
		width: 95px !important;
		height: 95px !important;
		border: 0 !important;
		border-radius: 5px !important;
		color: #888 !important;
		overflow: hidden !important;
	}

	.chat-view-header .chat-resume-img img {
		width: 100% !important;
		height: 100% !important;
		object-fit: cover !important;
		display: block !important;
	}

	.chat-view-header .chat-resume-summary {
		display: flex !important;
		font-size: 16px !important;
		gap: 5px !important;
		position: relative !important;
	}

	.chat-view-header .chat-resume-summary p {
		position: relative !important;
		padding-right: 5px !important;
		margin: 0 !important;
		color: #000 !important;
		font-weight: 600 !important;
	}

	.chat-view-header .chat-resume-summary p:not(:last-child)::after {
		content: "" !important;
		position: absolute !important;
		top: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		width: 1px !important;
		height: 0.5em !important;
		margin: auto !important;
		border-right: 1px solid #ACACAC !important;
	}

	.chat-view-header .chat-resume-summary>* {
		min-width: 0 !important;
		max-width: 100% !important;
		margin-right: 4px !important;
		margin-bottom: -3px !important;
	}

	.chat-view-header .chat-resume-summary2 {
		position: absolute !important;
		right: 0px !important;
		bottom: 0px !important;
		font-size: 16px !important;
		font-weight: 600 !important;
		letter-spacing: 0 !important;
		color: #1b9d7d !important;
		display: flex !important;
		flex-direction: column !important;
		align-items: flex-end !important;
	}

	.chat-view-header .chat-resume-summary2 p {
		margin: 0 !important;
		text-align: right !important;
	}

	.chat-view-header .chat-resume-summary2 .pricetype {
		display: flex !important;
		gap: 5px !important;
		justify-content: flex-end !important;
		position: relative !important;
	}

	/* pricetype 내부 em 태그 기본 폰트 스타일 제거 (mobile chat_view.php와 동일) */
	.chat-view-header .chat-resume-summary2 .pricetype em {
		position: relative !important;
		display: inline-block !important;
		font-style: normal !important;
	}

	.chat-view-header .chat-resume-summary2 .pricetype em:not(:last-child) {
		padding-right: 5px !important;
	}

	.chat-view-header .chat-resume-summary2 .pricetype em:not(:last-child)::after {
		content: "" !important;
		position: absolute !important;
		top: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		width: 1px !important;
		height: 0.5em !important;
		margin: auto !important;
		border-right: 1px solid #777777 !important;
	}

	.chat-view-header .chat-resume-summary3 {
		position: absolute !important;
		right: 0px !important;
		top: 0px !important;
		font-size: 16px !important;
		font-weight: 600 !important;
		letter-spacing: 0 !important;
		color: #333 !important;
		display: flex !important;
		flex-direction: column !important;
		align-items: flex-end !important;
	}

	.chat-view-header .chat-resume-summary3 p {
		margin: 0 !important;
		text-align: right !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype {
		display: flex !important;
		gap: 5px !important;
		justify-content: flex-end !important;
		position: relative !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em {
		position: relative !important;
		display: inline-block !important;
		font-style: normal !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em.wtype1 {
		color: #3a70db !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em.wtype2 {
		color: #F86048 !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em.wtype3 {
		color: #1B9D7D !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em:not(:last-child) {
		padding-right: 5px !important;
	}

	.chat-view-header .chat-resume-summary3 .worktype em:not(:last-child)::after {
		content: "" !important;
		position: absolute !important;
		top: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		width: 1px !important;
		height: 0.5em !important;
		margin: auto !important;
		border-right: 1px solid #777777 !important;
	}

	.chat-view-header .working-day {
		display: flex !important;
		margin: 0 0 -4px !important;
		list-style: none !important;
	}

	.chat-view-header .working-day>li {
		width: 1.8em !important;
		height: 1.8em !important;
		margin: 0 0.5em 4px 0 !important;
		font-size: 13px !important;
		color: #BEBFC9 !important;
		line-height: 1.5 !important;
		text-align: center !important;
		text-indent: -1px !important;
		border-radius: 100% !important;
		border: 1px solid #BEBFC9 !important;
		background: #fff !important;
		float: left !important;
	}

	.chat-view-header .working-day>li:last-child {
		margin-right: 0 !important;
	}

	.chat-view-header .working-day li[aria-hidden=false] {
		color: #fff !important;
		border-color: transparent !important;
		background-color: #1B9D7D !important;
	}

	.chat-view-header .chat-resume-btn-wrapper {
		display: block !important;
		text-align: center !important;
		padding: 10px !important;
		background: #f8f8f8 !important;
		border-top: 1px solid #e0e0e0 !important;
		text-decoration: none !important;
		color: #333 !important;
	}

	.chat-view-header .chat-resume-btn-wrapper:hover {
		background: #e8e8e8 !important;
	}

	/* chat-view 넓이 확대 */
	.chat-view {
		width: 500px !important;
		min-width: 500px;
	}

	/* title 클래스 font-size 설정 (main.css 참조 방지를 위해 구체적인 선택자 사용) */
	#chatPopup .title-wrap p.title,
	#chatPopup .add-new p.title {
		font-size: 1.3rem;
	}

	/* angle-link 클래스 font-weight 설정 (main.css 참조 방지를 위해 구체적인 선택자 사용) */
	#chatPopup .angle-link {
		font-weight: 400;
	}

	/* latest 클래스 내부 category-label height 설정 (main.css 참조 방지를 위해 구체적인 선택자 사용) */
	#chatPopup p.latest .category-label {
		height: 19px;
	}
</style>
 <div id="chatPopup" class="layer-popup">
 	<div role="dialog" aria-labelledby="chatTitle" aria-modal="true" class="popup-container">
 		<div class="popup-content">
 			<h4 id="chatTitle" class="sr-text">채팅</h4>
 			<div class="chat-container is-viewing">
 				<?php if ($this->user->isLogin() == false) : ?>
 					<!-- 로그인 안내 -->
 					<div class="chat-error">
 						<p class="headline">
 							<strong>로그인전 이시군요?</strong>
 							<span>로그인을 하시면 채팅을 진행하실수 있습니다.</span>
 						</p>
 						<div class="action-wrap">
 							<a href="#" role="button" aria-controls="chatPopup" class="btn-round btn-secondary js-close-chat">취소</button>
 								<a href="/member/login" class="btn-round btn-primary">로그인</a>
 						</div>
 					</div>
 				<?php else : ?>
 					<!-- 채팅목록 -->
 					<div class="chat-list">
 						<div class="chat-list-header">
 							<!-- 탭목록 -->
 							<ul role="tablist" class="tabs">
 								<?php if ($user_type == "MEM") { ?>
 									<li class="on chat_tab1" style="width:50%;">
 										<a href="#chatPanel1" role="tab" aria-selected="true" aria-controls="chatPanel1" id="chatTab1" class="tab" style="width: 100%;"><span style="width: 100%; text-align: center;">지원한 공고</span></a>
 									</li>
 									<li class="chat_tab2" style="width:50%;">
 										<a href="#chatPanel2" role="tab" aria-selected="true" aria-controls="chatPanel2" id="chatTab2" class="tab" style="width: 100%;"><span style="width: 100%; text-align: center;">제안받은 공고</span></a>
 									</li>
 								<?php } ?>
 								<?php if ($user_type == "COMP") { ?>
 									<li class="on chat_tab1" style="width:50%;">
 										<a href="#chatPanel1" role="tab" aria-selected="true" aria-controls="chatPanel1" id="chatTab1" class="tab" style="width: 100%;"><span style="width: 100%; text-align: center;">제안받은 인재</span></a>
 									</li>
 									<li class="chat_tab2" style="width:50%;">
 										<a href="#chatPanel2" role="tab" aria-selected="true" aria-controls="chatPanel2" id="chatTab2" class="tab" style="width: 100%;"><span style="width: 100%; text-align: center;">제안보낸 인재</span></a>
 									</li>
 								<?php } ?>
 								<!--
																<li class="chat_tab3">
                                    <a href="#chatPanel3" role="tab" aria-selected="false" aria-controls="chatPanel3" id="chatTab3" class="tab"><span>중고거래</span></a>
                                </li>
															-->
 							</ul>
 						</div>
 						<div class="chat-list-body">

 							<div role="tabpanel" id="chatPanel1" aria-labelledby="chatTab1" tabindex="0" class="tab-panel" hidden>
 								<!-- 기업회원 제안받은 인재 -->
 								<ol class="list" id="chat_list1" style="width:100%;float:left;">

 								</ol>
 								<div class="chat-empty" id="chat_empty1" style="display:none;">
 									<p>지원자 목록이 없습니다.</p>
 								</div>
 							</div>
 							
 							<div role="tabpanel" id="chatPanel2" aria-labelledby="chatTab2" tabindex="0" class="tab-panel" hidden>
 								<!-- 기업회원 제안보낸 인재 -->
 								<ol class="list" id="chat_list2" style="width:100%;float:left;">

 								</ol>
 								<div class="chat-empty" id="chat_empty2" style="display:none;">
 									<p>진행중인 대화가 없어요!</p>
 								</div>
 							</div>
 							<!-- 중고거래 내용 -->
 							<div role="tabpanel" id="chatPanel3" aria-labelledby="chatTab3" tabindex="0" class="tab-panel" hidden>
 								<ol class="list" id="chat_list3">

 								</ol>
 								<div class="chat-empty" id="chat_empty3" style="display:none;">
 									<p>진행중인 대화가 없어요!</p>
 								</div>
 							</div>
 						</div>
 					</div>

 					<!-- 채팅상세 -->
 					<div class="chat-view" tabindex="0" hidden>
 						<div class="chat-view-header">
 							<!-- 회사명 -->
 							<h4 class="view-title" id="top_name">1:1 채팅</h4>

 							<!-- 채용정보 -->
							<?php if ($user_type == "MEM") : ?>
								<div id="top_recruit" class="chat-recruit-content-li recomm" style="display:none;">
									<div class="chat-recruit-content-info">
										<div class="chat-recruit-content-body">
											<div class="chat-recruit-img">
												<a id="top_link" href="" aria-label="채용공고 보기">
													<img id="top_img" src="" alt="" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'" />
												</a>
												<!-- 북마크 버튼 (웹 채팅 팝업에서는 사용 안 함)
												<button type="button" data-bk_type="RECRUIT" id="top_bookmark" class="btnBookMark btn-bookmark js-toggle-bookmark"><span class="sr-text">즐겨찾기 추가</span></button>
												-->
											</div>
											<div class="chat-recruit-desc">
												<div class="chat-recruit-wicon" id="top_work_img">
													<!-- 직종 아이콘 -->
												</div>
												<div class="chat-recruit-title ellipsis">
													<a id="top_title_link" href=""><strong id="top_title"></strong></a>
												</div>
												<div class="chat-recruit-summary">
													<p id="top_nation"></p>
													<p id="top_age"></p>
												</div>
												<div class="chat-recruit-summary area">
													<p class="js-chat-addr" id="top_area" data-addr=""></p>
												</div>
												<div class="chat-recruit-summary2">
													<p class="period" id="top_price_type"></p>
													<p class="amount" id="top_price"></p>
												</div>
											</div>
										</div>
										<div class="chat-recruit-summary3" id="top_work_type">
											<!-- 근무 타입 -->
										</div>
									</div>
									<!-- 주요정보패널 보기 -->
									<a href="#chatSummary" id="detailgo" role="button" aria-haspopup="dialog" aria-controls="chatSummary" class="chat-recruit-btn-wrapper js-toggle-summary"><span class="sr-text">주요정보보기</span></a>
								</div>
							<?php else : ?>
								<div id="top_recruit" class="chat-resume-content-li recomm" style="display:none;">
									<div class="chat-resume-content-info">
										<div class="chat-resume-content-body">
											<div class="chat-resume-img">
												<a id="top_link" href="" aria-label="이력서 보기">
													<img id="top_img" src="" alt="" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'" />
												</a>
												<!-- 북마크 버튼 (웹 채팅 팝업에서는 사용 안 함)
												<button type="button" data-bk_type="RESUME" id="top_bookmark" class="btnBookMark btn-bookmark js-toggle-bookmark"><span class="sr-text">즐겨찾기 추가</span></button>
												-->
											</div>
											<div class="chat-resume-desc">
												<div class="chat-resume-wicon" id="top_work_img">
													<!-- 직종 아이콘 -->
												</div>
												<div class="chat-resume-title ellipsis">
													<a id="top_title_link" href=""><strong id="top_name_mem"></strong></a>
												</div>
												<div class="chat-resume-summary">
													<ul class="working-day" id="top_weekday"></ul>
												</div>
												<div class="chat-resume-summary">
													<p id="top_nation"></p>
													<p id="top_age"></p>
													<p class="js-chat-resume-addr" id="top_area" data-addr=""></p>
												</div>
												<div class="chat-resume-summary2" id="top_price_type">
													<!-- 가격 타입 -->
												</div>
												<div class="chat-resume-summary3" id="top_work_type">
													<!-- 근무 타입 -->
												</div>
											</div>
										</div>
									</div>
									<!-- 인재정보 보기 -->
									<a href="#chatSummary" id="detailgo" role="button" aria-haspopup="dialog" aria-controls="chatSummary" class="chat-resume-btn-wrapper js-toggle-summary"><span class="sr-text">인재정보보기</span></a>
								</div>
								<!-- //인재정보 -->
 							<?php endif; ?>

 							

 							<!-- 일정 -->
 							<?php if ($user_type == "COMP") : ?>
 								<!-- comp_top_1 주석처리 시작 -->
 								<!--
							<div id="comp_top_1" class="chat-appointment">
								<div class="container">

									<div class="add-new">
										<p class="title">* 면접일을 확정하셔야 연락처가 공유됩니다.</p>
										<div class="btn-wrap">
											<button type="button" aria-expanded="false" aria-controls="interviewForm" id="interviewShow" class="btn-round btn-primary js-toggle-form">면접일 확정</button>
											<button type="button" aria-expanded="false" aria-controls="startDateForm" id="dateShow" class="btn-round btn-light js-toggle-form">채용확정</button>
										</div>
									</div>
									면접일 입력폼
								<div id="interviewForm" class="appointment-form" hidden>
 									<div class="container">
 										<form action="/">
 											<p class="form-label">면접일을 입력해주세요.</p>
 											<label class="form-fulldate">
 												<span class="sr-text">날짜 선택</span>
 												<input type="date" name="interviewDate" aria-required="true" required class="js-datepicker js-chatting-date">
 												<span class="form-text"></span>
 											</label>
 											<div class="btn-wrap">
 												<button type="reset" aria-expanded="false" aria-controls="interviewForm" class="btn-round btn-secondary js-toggle-form">취소</button>
 												<button type="button" class="btn-round btn-tertiary" id="btnInerview">확인</button>
 											</div>
 										</form>
									</div>
								</div>
								근로인 입력폼
								<div id="startDateForm" class="appointment-form" hidden>
 									<div class="container">
 										<form action="/">
 											<p class="form-label align-center">근무 시작일을 입력해주세요.</p>
 											<label class="form-fulldate">
 												<span class="sr-text">날짜 선택</span>
 												<input type="date" name="startDate" aria-required="true" required class="js-datepicker js-chatting-date">
 												<span class="form-text"></span>
 											</label>
 											<div class="btn-wrap">
 												<button type="reset" aria-expanded="false" aria-controls="startDateForm" class="btn-round btn-secondary js-toggle-form">취소</button>
 												<button type="button" class="btn-round btn-tertiary" id="btnStartWrok">확인</button>
 											</div>
 										</form>
 									</div>
 								</div>
 								<script>
 									(function() {

 										/* 면접일, 근로일 날짜 커스텀 */
 										function getCustomDate(el) {
 											var date = new Date(el.value);
 											var weekDays = ['일', '월', '화', '수', '목', '금', '토'];
 											var weekDay = date.getDay() == 6 || date.getDay() == 0 ? '(<em class="txt-color1">' + weekDays[date.getDay()] + '요일</em>)' : '(' + weekDays[date.getDay()] + '요일)';

 											el.parentNode.querySelector('.form-text').innerHTML = date.getFullYear() + '년 ' +
 												(date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) +
 												'월 ' + (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + '일' + weekDay;
 										}

 										/* 입력폼 열기,닫기 */
 										document.querySelectorAll('.js-toggle-form').forEach(function(btn) {
 											btn.addEventListener('click', function() {
 												var id = this.getAttribute('aria-controls'),
 													isOpen = this.getAttribute('aria-expanded'),
 													formCon = null;

 												if (!id || !document.getElementById(id)) return;

 												formCon = document.getElementById(id);

 												if (isOpen == 'false') {
 													document.querySelectorAll('.appointment-form').forEach(function(el) {
 														el.hidden = true;
 														el.querySelector('form').reset();
 														el.querySelector('.js-chatting-date').value = flatpickr.formatDate(new Date, 'Y-m-d');
 														getCustomDate(el.querySelector('.js-chatting-date'));
 													});
 													document.querySelectorAll('.js-toggle-form').forEach(function(el) {
 														el.setAttribute('aria-expanded', 'false');
 													});
 													formCon.hidden = false;
 													formCon.querySelector('.js-toggle-form').setAttribute('aria-expanded', 'true');
 													this.setAttribute('aria-expanded', 'true');
 												} else {
 													formCon.hidden = true;
 													formCon.querySelector('.js-toggle-form').setAttribute('aria-expanded', 'false');
 													this.setAttribute('aria-expanded', 'false');
 												}

 											});
 										});

 										/* 날짜값 변경 */
 										document.querySelectorAll('.form-fulldate').forEach(function(el) {
 											el.querySelector('input').addEventListener('change', function() {
 												getCustomDate(this);
 											});
 										});

 										/* 달력표시 */
 										function chgMonth(instance) {
 											var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
 												month = Number(instance.currentMonth) + 1,
 												year = instance.currentYear;
 											$con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
 										}

 										window.addEventListener('DOMContentLoaded', function() {
 											if (typeof flatpickr == 'undefined') return false;

 											//달력 생성
 											var calendar = flatpickr('.js-chatting-date', {
 												locale: 'ko',
 												dateFormat: 'Y-m-d',
 												ariaDateFormat: 'Y-m-d',
 												nextArrow: '<span class="sr-text">다음</span>',
 												prevArrow: '<span class="sr-text">이전</span>',
 												disableMobile: true,
 												defaultDate: new Date(),
 												minDate: new Date(),
 												onMonthChange: function(selectedDates, dateStr, instance) {
 													chgMonth(instance);
 												},
 												onDayCreate: function(dObj, dStr, fp, dayElem) {
 													var day = new Date(dayElem.getAttribute('aria-label')).getDay();
 													if (day == 0) {
 														dayElem.classList.add('sun');
 													} else if (day == 6) {
 														dayElem.classList.add('sat');
 													}
 												},
 												onReady: function(selectedDates, dateStr, instance) {
 													chgMonth(this);
 													getCustomDate(instance.element);
 												}
 											});
 										});

 									}());
 								</script>
 						</div>
 					</div>
 					-->
 								<!-- comp_top_1 주석처리 끝 -->
 								<!-- //일정 -->
 							<?php else : ?>
 								<!-- //채용정보 -->
 								<div id="mem_top_2" class="chat-appointment" style="display:none;">
 									<div class="container">
 										<div class="inline-group">
 											<p class="col date" id="top_dt1"><?= substr($room["rm_itv_dt"], 0, 4) ?>년 <?= substr($room["rm_itv_dt"], 5, 2) ?>월 <?= substr($room["rm_itv_dt"], 8, 2) ?>일</p>
 											<button type="button" class="btn-round btn-secondary bntInterviewCancel">면접취소</button>
 										</div>
 									</div>
 								</div>
 								<div id="mem_top_3" class="chat-appointment" style="display:none;">
 									<div class="container">
 										<div class="inline-group">
 											<p class="col date" id="top_dt2"><?= substr($room["rm_rec_dt"], 0, 4) ?>년 <?= substr($room["rm_rec_dt"], 5, 2) ?>월 <?= substr($room["rm_rec_dt"], 8, 2) ?>일</p>
 											<button type="button" class="btn-round btn-secondary bntRecruitCancel">채용취소</button>
 										</div>
 									</div>
 								</div>
 							<?php endif; ?>
 						</div>
 						<div class="chat-view-body">
 							<div class="content" id="chat_body">
 								<div class="container" id="chat_view">
 								</div>
 							</div>
 							<!-- 입력폼 -->
 							<div class="write-form">
 								<label>
 									<span class="sr-text">메세지입력</span>
 									<input type="text" id="chatTxt" name="chatTxt" autocomplete="off" placeholder="메세지를 입력해주세요.">
 								</label>
 								<button type="button" id="btnSend" class="btn-primary">전송</button>
 							</div>
 						</div>

 						<?php if ($user_type == "MEM") : ?>
 							<!-- 상세정보 패널 -->
 							<div id="chatSummary" class="layer-panel" tabindex="0">
 								<div class="panel-container">
 									<div class="panel-header">
 										<h2 class="sr-text">채용주요정보</h2>
 									</div>
 									<div class="panel-body">
 										<div class="panel-content">

 											<div class="item-info-summary">
 												<div class="title-wrap">
 													<!-- 제목 -->
 													<h3 class="secondary-title" id="bt_title">대구 달성군 14만원 칸막이일</h3>
 													<!-- 모집기간 -->
 													<p class="recruit-period">
 														<span>모집기간</span>
 														<em id="bt_date">2022.06.01 ~ 2022.08.01</em>
 													</p>
 												</div>
 												<p class="line-label">주요정보</p>
 												<table class="info-table">
 													<caption><span class="sr-text">구인정보</span></caption>
 													<colgroup>
 														<col>
 														<col>
 													</colgroup>
 													<tbody>
 														<tr>
 															<td class="label">업무구분</td>
 															<td class="value" id="bt_work_img">
 																<!-- 직종라벨 -->
 																<img src="static/img/job_cate2_label1.svg" alt="건설" class="category-label">
 															</td>
 														</tr>
 														<tr>
 															<td class="label">급여</td>
 															<td class="value">
 																<span class="pay" id="bt_price_info">
 																	<em class="amount">140,000</em>
 																	<span>(일급)</span>
 																</span>
 															</td>
 														</tr>
 														<tr>
 															<td class="label">근무형태</td>
 															<td class="value"><span class="working-time" id="bt_work_type">주간,야간</span></td>
 														</tr>
 														<tr>
 															<td class="label">근무시간</td>
 															<td class="value"><span class="time" id="bt_time">07:00~18:00</span></td>
 														</tr>
 														<tr>
 															<td class="label">희망국적</td>
 															<td class="value" id="bt_nation">외국인</td>
 														</tr>
 														<tr>
 															<td class="label">희망성별</td>
 															<td class="value" id="bt_sex">남자,여자</td>
 														</tr>
 														<tr>
 															<td class="label">희망나이</td>
 															<td class="value" id="bt_age">20세이상~55세이하</td>
 														</tr>
 														<tr>
 															<td class="label">근무요일</td>
 															<td class="value">
 																<ul class="working-day" id="bt_yoil">
 																	<!-- 선택된 요일 : aria-hidden="false" -->
 																	<li aria-hidden="false">일</li>
 																	<li aria-hidden="true">월</li>
 																	<li aria-hidden="true">화</li>
 																	<li aria-hidden="false">수</li>
 																	<li aria-hidden="true">목</li>
 																	<li aria-hidden="false">금</li>
 																	<li aria-hidden="true">토</li>
 																</ul>
 															</td>
 														</tr>
 													</tbody>
 												</table>
 												<!-- 상세페이지 이동 -->
 												<div class="align-right"><a href="#" id="bt_link" class="view-all">더보기</a></div>
 											</div>
 										</div>
 									</div>
 									<div class="panel-footer">
 										<button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-summary">확인</button>
 									</div>
 								</div>
 							</div>
 						<?php else : ?>
 							<!-- 상세정보 패널 -->
 							<div id="chatSummary" class="layer-panel" tabindex="0">
 								<div class="panel-container">
 									<div class="panel-header">
 										<h2 class="sr-text">인재주요정보</h2>
 									</div>
 									<div class="panel-body">
 										<div class="panel-content">

 											<div class="item-info-summary">
 												<div class="title-wrap">
 													<!-- 제목 -->
 													<h3 class="secondary-title" id="bt_title">홍길동</h3>
 													<!-- 모집기간 -->
 													<div class="person-meta">
 														<p id="bt_nation">내국인</p>
 														<span>/</span>
 														<p id="bt_age">46세</p>
 														<span>/</span>
 														<p id="bt_area">인천 연수구</p>
 													</div>
 												</div>
 												<p class="line-label">인재 상세 정보</p>
 												<table class="info-table">
 													<caption><span class="sr-text">구인정보</span></caption>
 													<colgroup>
 														<col>
 														<col>
 													</colgroup>
 													<tbody>
 														<tr>
 															<td class="label">근무가능일시</td>
 															<td class="value"><span class="time" id="bt_work_dt">07:00~18:00</span></td>
 														</tr>
 														<tr>
 															<td class="label">최종학력</td>
 															<td class="value" id="bt_edu">중학교</td>
 														</tr>
 														<tr>
 															<td class="label">간단경력</td>
 															<td class="value">
 																<ul class="period-list" id="bt_career">

 																</ul>
 															</td>
 														</tr>
 														<tr>
 															<td class="label">자기소개</td>
 															<td class="value">
 																<div class="summary" id="bt_desc">입력하신 자기소개 내용이 나옵니다.입력하신 자기소개 내용이 나옵니다.입력하신 자기소개 내용이 나옵니다. 입력하신 자기소개 내용이 나옵니다.입력하신 자기소개 내용이 나옵니다.입력하신 자기소개 내용이 나옵니다.</div>
 															</td>
 														</tr>
 														<tr>
 															<td class="label">자격증</td>
 															<td class="value">
 																<ul class="period-list" id="bt_certi">
 																	<li>
 																		<span>굴삭기 운전기능사</span>
 																		<span>기능사</span>
 																		<em>2019.02</em>
 																	</li>
 																</ul>
 															</td>
 														</tr>
 													</tbody>
 												</table>
 												<!-- 상세페이지 이동 -->
 												<div class="align-right"><a href="#" id="bt_link" class="view-all">더보기</a></div>
 											</div>

 										</div>
 									</div>
 									<div class="panel-footer">
 										<button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-summary">확인</button>
 									</div>
 								</div>
 							</div>
 						<?php endif; ?>
 						<!-- //상세정보 패널 -->
 						<button type="button" class="btn-close-chat-view js-close-chat-view"><span class="sr-text">채팅 내용 닫기</span></button>
 					</div>
 				<?php endif; ?>
 			</div>
 		</div>
 		<button type="button" aria-controls="chatPopup" class="btn-close-chat js-close-chat show"><span class="sr-text">채팅닫기</span></button>
 	</div>
 </div>
 <!-- //채팅팝업 -->
 <!--
 기업회원 제안받은 인재 / 제안보낸 인재 탭의 항목 표시
-->
 <template id="tpl_chat">
 	<li class="list-item">
 		<a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
 			<div class="item-info person" style="width:100%;">
 				<div class="thumb-wrap">
 					<div class="thumb" style="border-radius: 50%; overflow: hidden;">
 						<img src="{{rc_thumb}}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
 					</div>
 				</div>
				<div class="content-wrap" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
					<div class="timestamp" style="font-size: 12px;color:#777;">{{mm_comp_name}}</div>
					<div class="title-wrap">
						<p class="title"><strong>{{rc_name}}</strong></p>
					</div>
					<!-- 채팅방 개수 -->
					<div class="meta" style="font-size: 12px;color:#999;">
						<p class="room-count-text">채팅방 {{room_count}}명</p>
					</div>
				</div>
 			</div>
 		</a>
 	</li>
 </template>
 <!-- //채팅팝업 -->

 <!-- 
개인회원 지원한공고탬플릿
-->
 <template id="tpl_chat_mem">
 	<li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
 		<a href="#" class="angle-link js-open-view" style="padding:0;" data-regdt="{{reg_dt}}">
 			<div class="item-info person" style="width:100%;">
 				<div class="thumb-wrap">
 					<div class="thumb" style="border-radius: 50%; overflow: hidden;">
 						<img src="{{rc_thumb}}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
 					</div>
 				</div>
 				<div class="content-wrap">
 					<div class="timestamp" style="font-size: 12px;color:#777;">{{mm_comp_name}}</div>
 					
 					<div class="title-wrap">
 						<p class="title"><strong>{{rc_name}}</strong></p>
 					</div>
 					<!-- 마지막 메시지 -->
 					<div class="meta" style="font-size: 12px;color:#999;">
 						<p class="last-msg">{{last_msg_preview}}</p>
 						
 					</div>
 				</div>
 			</div>
 		</a>
 	</li>
 </template>

 <!-- 
개인의 제안받은 공고 탬플릿
-->
 <template id="tpl_chat2">
 	<!-- 개인의 제안받은 공고 목록 항목 -->
 	<li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
 		<a href="#" class="angle-link js-open-view angle-link-resume" style="padding:0;"
 			aria-haspopup="dialog"
 			data-ru_idx="{{ru_idx}}"
 			data-rc_idx="{{rc_idx}}"
 			data-target_id="{{target_id}}"
 			data-user_id="{{user_id}}"
 			data-target_img="{{target_img}}"
 			data-user_img="{{user_img}}"
 			data-target_name="{{target_name}}"
 			data-user_name="{{user_name}}"
			data-subject="{{rc_name}}">
			<div class="item-info person" style="width:100%;">
				<div class="thumb-wrap">
					<div class="thumb" style="border-radius: 50%; overflow: hidden;">
						<img src="{{rc_thumb}}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
					</div>
				</div>
				<div class="content-wrap">
					<div class="timestamp" style="font-size: 12px;color:#777;">{{mm_comp_name}}</div>
					
					<div class="title-wrap">
						<p class="title"><strong>{{rc_name}}</strong></p>
					</div>
					<!-- 마지막 메시지 -->
					<div class="meta" style="font-size: 12px;color:#999;">
						<p class="last-msg">{{last_msg_preview}}</p>
						
					</div>
				</div>
			</div>
		</a>
	</li>
</template>

 <!-- 
용도: 기업회원용 - 특정 채용공고에 지원한 인재 목록 항목 탬플릿
-->
 <template id="tpl_chat_by_recruite">
 	<li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}">
 		<a href="#" class="angle-link js-open-view angle-link-resume"
 			aria-haspopup="dialog"
 			data-ru_idx="{{ru_idx}}"
 			data-rc_idx="{{rc_idx}}"
 			data-target_id="{{target_id}}"
 			data-user_id="{{user_id}}"
 			data-target_img="{{target_img}}"
 			data-user_img="{{user_img}}"
 			data-target_name="{{target_name}}"
 			data-user_name="{{user_name}}"
 			data-subject="{{rc_name}}">

			<div class="thumb"><img src="{{ru_thumb}}" alt=""></div>
			<div class="mid">
				<p class="latest">{{meminfo}}</p>
				<p class="name">{{ru_name}}</p>
				<!-- <p class="latest"></p> -->
				<p class="time">{{last_msg_preview}}</p>
			</div>
 		</a>
 		<button type="button" onclick="deleteItem(this.parentNode);" class="btn-primary btn-trash js-del-item">
 			<span class="sr-text">삭제</span>
 		</button>
 	</li>
 </template>

 <!-- 
템플릿: tpl_chat_org
용도: 인재 상세 정보 표시용 (현재 미사용)
사용 위치: 현재 코드에서 사용되지 않음
표시 위치: 미사용
사용자: 미사용
기능: 
  - 이력서 보기 버튼
  - 1:1 채팅하기 버튼
  - 인재 상세 정보 표시
참고: 레거시 템플릿 또는 향후 사용 예정
-->
 <template id="tpl_chat_org">
 	<li class="list-item">
 		<div class="item-info person">
 			<div class="thumb-wrap">
 				<div class="thumb">
 					<img src="{{ru_resume_img}}" alt="">
 				</div>
 				<p class="star-rating">
 					<span class="star" style="width:100%;"></span>
 					<span class="sr-text">평점</span>
 					<span class="sr-text">5</span>
 				</p>
 				<!-- 공고보기 -->
 				<a href="/recruit/recruit_detail/{{rc_idx}}" class="btn-line">지원한 공고</a>
 			</div>
 			<div class="content-wrap">
 				<div class="meta top">
 					<!-- 직종라벨 -->
 					<p class="category">
 						{{ru_work_img}}
 					</p>
 					<p class="condition"><em class="period">{{ru_price_type}}</em>,<span class="working-time">{{ru_work_type}}</span></p>
 				</div>
 				<div class="title-wrap">
 					<p class="title"><strong>{{mm_name}}</strong></p>
 					<div class="meta">
 						<p>{{mm_nation}}</p>
 						<span>/</span>
 						<p>{{ma_age}}세</p>
 					</div>
 				</div>
 				<!-- 지역 -->
 				<div class="meta">
 					<p class="loc">{{ad_addr}}</p>
 				</div>
 				<!-- 해시태그 -->
 				<div class="hashtag">
 					{{ru_my_type}}
 				</div>
 			</div>
 		</div>
 		<!-- //인재정보 -->
 		<div class="inline-group">
 			<a href="/resume/resume_detail/{{ru_idx}}" class="btn-view-resume">이력서 보기</a>
 			<a href="#" aria-haspopup="dialog"
 				data-ru_idx="{{ru_idx}}"
 				data-rc_idx="{{rc_idx}}"
 				data-target_id="{{target_id}}"
 				data-user_id="{{user_id}}"
 				data-target_img="{{target_img}}"
 				data-user_img="{{user_img}}"
 				data-target_name="{{target_name}}"
 				data-user_name="{{user_name}}"
 				data-subject="{{rc_name}}" class="btn-view-chat js-action1">1:1 채팅하기</a>
 		</div>
 	</li>
 </template>

 <script>
 	var room_list_all = {};

 	//대화내용 중복 차단 내역 초기화
 	let processedIdx = [];

	function maskingName(name) {
		if (name == null) {
			return "";
		}
		if (name.length <= 2) {
			return name.replace(name.substring(0, 1), "*");
		}

		return name.substring(0, 1) + "**";
	}

	// 주소 정리 함수 (chat_view.php와 동일)
	function fRemoveBigAdrJS(arg) {
		// null 또는 undefined 체크
		if (!arg || typeof arg !== 'string') {
			return '';
		}
		const parts1 = arg.split(",");
		arg = parts1[0];
		arg = arg.replace("서울특별시 ", "서울시 ");
		arg = arg.replace("경기 ", "");
		arg = arg.replace("강원 ", "");
		arg = arg.replace("충남 ", "");
		arg = arg.replace("충북 ", "");
		arg = arg.replace("경남 ", "");
		arg = arg.replace("경북 ", "");
		arg = arg.replace("전남 ", "");
		arg = arg.replace("전북 ", "");
		arg = arg.replace("제주 ", "");
		arg = arg.replace("경기도 ", "");
		arg = arg.replace("강원특별자치도 ", "");
		arg = arg.replace("충청남도 ", "");
		arg = arg.replace("충청북도 ", "");
		arg = arg.replace("경상남도 ", "");
		arg = arg.replace("경상북도 ", "");
		arg = arg.replace("전라남도 ", "");
		arg = arg.replace("전북특별자치도 ", "");
		arg = arg.replace("제주특별자치도 ", "");
		arg = arg.replace("세종특별자치시 ", "세종시 ");
		arg = arg.replace("광주광역시 ", "광주시 ");
		arg = arg.replace("인천광역시 ", "인천시 ");
		arg = arg.replace("울산광역시 ", "울산시 ");
		arg = arg.replace("부산광역시 ", "부산시 ");
		arg = arg.replace("대구광역시 ", "대구시 ");
		arg = arg.replace("대전광역시 ", "대전시 ");
		const parts = arg.split(" ");
		if (parts.length >= 3) {
			// parts[2]가 숫자를 포함하고 있으면 parts[1]만 결합 (parts[2]는 제외)
			if (/\d/.test(parts[2])) {
				return parts[0] + " " + parts[1];
			} else {
				return parts[0] + " " + parts[2];
			}
		} else {
			return arg;
		}
	}
 </script>
 <?php if ($this->user->isLogin() !== false) : ?>
 	<script>
 		//console.log("script...");


 		var chat_point = "<?= $point_info["cd_val"] ?>";
 		var first_yn = "N";
 		var first_message = {};
 		var first_users = new Array();
 		var mm_type = "<?= $user_type ?>";
 		var click_type = "list";
 		var g_user_id = "";
 		var chat_tab_id = "1";
 		var last_send_dt = "";
 		var rm_idx = "";
 		var rm_info = {};
 		var rm_start = 0;
 		var rm_length = 20;
 		var rm_count = 0;
 		$(document).ready(function() {
 			$("#top_trade").hide();
 			$("#top_recruit").show();

 			//룸정보 가져오기
 			g_user_id = $("#user_id").val();

 			$(".js-open-chat").click(function() {
 				if (mm_type == "MEM") {
 					//$(".chat_tab1").html("");
 					//setShow("2");
 					setShow("1");
 				} else {
 					setShow("1");
 				}
 			});

 			$(".chat_tab1").click(function() {
 				setShow("1");
 			});

 			$(".chat_tab2").click(function() {
 				setShow("2");
 			});

			$(".chat_tab3").click(function() {
				setShow("3");
			});

			// top_recruit 클릭 시 상세페이지로 이동
			$(document).on("click", "#top_recruit", function(e) {
				// 링크나 버튼을 클릭한 경우는 기본 동작 유지
				if ($(e.target).is("a") || $(e.target).closest("a").length > 0 || 
					$(e.target).is("button") || $(e.target).closest("button").length > 0) {
					return;
				}
				
				// 채용정보인 경우 (data-rc_idx가 있으면)
				var rc_idx = $(this).attr("data-rc_idx");
				if (rc_idx) {
					window.location.href = "/recruit/job_post/" + rc_idx;
					return;
				}
				
				// 인재정보인 경우 (data-ru_idx가 있으면)
				var ru_idx = $(this).attr("data-ru_idx");
				if (ru_idx) {
					window.location.href = "/resume/hr_post/" + ru_idx;
				}
			});

			$("#chatTxt").keyup(function(e) {
 				if (e.keyCode === 13) {
 					e.preventDefault();
 					send_message();
 				}
 			});


 		}); //ready

 		//var noReadCountProc = false;
 		function fSearchNoReadCount() {
 			//alert(1);
 			//if(noReadCountProc) return;
 			//noReadCountProc = true;

 			//공고리스트에 미확인 채팅건수 조회 확인
 			comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
 				//console.log('fSearchNoReadCount');
 				//console.log(ret);

 				// Extract the result object
 				const results = ret.data.result;

 				//채팅방 별로 no read count
 				var readCounts = {};
 				var data = results;
 				// 데이터 처리
 				for (var key in data) {
 					if (data.hasOwnProperty(key)) {
 						var rm_idx = data[key].rm_idx;
 						var rm_messages = data[key].rm_messages;
 						var count = 0;

 						//console.log(rm_idx);

 						// rm_messages 배열을 순회하면서 read_yn === 'N'인 건수를 카운트
 						rm_messages.forEach(function(message) {
 							//console.log(message);
 							if (message.mg_reads) {
 								message.mg_reads.forEach(function(read) {
 									//console.log('read---'+$("#user_id").val());
 									//console.log(read);
 									if (read.user_id != $("#user_id").val()) {
 										//console.log('not same');
 									} else {
 										//console.log('same');
 									}
 									if (read.read_yn === 'N' && read.user_id === $("#user_id").val()) {
 										count++;
 										//console.log('++');
 									} else {
 										//console.log('00');
 									}
 								});
 							}
 						});

 						// 결과 저장
 						readCounts[rm_idx] = count;
 					}
 				}

 				// 결과를 화면에 출력 (디버그용)
 				for (var rm_idx in readCounts) {
 					if (readCounts.hasOwnProperty(rm_idx)) {
 						//console.log('rm_idx: ' + rm_idx + ', unread count: ' + readCounts[rm_idx]);
 					}
 				}

 				//console.log(readCounts);

 				//////////////

 				// Iterate over each rm_idx and log the required information
 				for (const key in results) {
 					if (results.hasOwnProperty(key)) {
 						const result = results[key];
 						const {
 							rm_idx,
 							rm_subject,
 							rm_messages
 						} = result;
 						let readNCount = 0;

 						// Count the number of read_yn === 'Y'
 						rm_messages.forEach(message => {
 							message.mg_reads.forEach(read => {
 								if (read.read_yn === 'N' && read.user_id === $("#user_id").val()) {
 									readNCount++;
 								}
 							});
 						});

 						// Log the information
 						//console.log(`rm_idx: ${rm_idx}, rm_subject: ${rm_subject}, read_yn N count: ${readNCount}`);

 						<?php if ($user_type == "MEM") { ?>
 							//alert(rm_idx+':'+readNCount);
 							if (readNCount > 0) {
 								$('#chat_list1 .room_idx_' + rm_idx).append('<span class="chat_noread_cnt" style="top:44px; right:30px;">' + readNCount + '</span>');
 								$('#chat_list2 .room_idx_' + rm_idx).append('<span class="chat_noread_cnt" style="top:44px; right:30px;">' + readNCount + '</span>');
 							}
 						<?php } ?>
 						<?php if ($user_type == "COMP") { ?>
 							//alert('read');
 							$('#chat_list1 .content-wrap').each(function() { //#chat_list1 .content-wrap
 								//alert('cont');
 								//$(this).data('noreadcnt','0');

 								var roomIdxList = $(this).data('room_idx_list');
 								//alert(roomIdxList);
 								if (roomIdxList != undefined) {
 									//alert('if');
 									//alert($(this).data('noreadcnt'));
 									//alert(rm_idx);

 									// 괄호를 제거하고, 문자열을 콤마로 분리하여 배열로 변환
 									var roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',');

 									// 숫자 값으로 변환
 									roomIdxArray = roomIdxArray.map(function(idx) {
 										return parseInt(idx.trim(), 10);
 									});

 									if (roomIdxArray.includes(rm_idx)) {
 										//console.log("roomIdxArray:"+rm_idx);
 										//console.log(parseInt($(this).data('noreadcnt')));
 										//console.log(readNCount);

 										$(this).data('noreadcnt', parseInt($(this).data('noreadcnt')) + readNCount);
 										//console.log($(this).html());
 										//console.log($(this).data('noreadcnt'));

 										var $chatNoreadCnt = $(this).find('.chat_noread_cnt');
 										//if ($chatNoreadCnt.length==0) {
 										//console.log('set1');
 										if ($(this).data('noreadcnt') > 0) {
 											//console.log('set2');
 											$(this).append('<span class="chat_noread_cnt c11">' + $(this).data('noreadcnt') + '</span>');
 										}
 										//}
 									};


 									// if (roomIdxList.indexOf('('+rm_idx+')') > -1) {
 									// 		alert('ifif');
 									// 		$(this).append('<span class="chat_noread_cnt">'+$(this).data('noreadcnt')+'</span>');
 									// }
 								}
 							});
 							$('#chat_list1 .room-item').each(function() {
 								if ($(this).hasClass('room_idx_' + rm_idx)) {
 									if (readNCount > 0) {
 										$(this).append('<span class="chat_noread_cnt c12" style="top:32px; right:30px;">' + readNCount + '</span>');
 									}

 									//$(this).parent().parent().find('.angle-link').first().append('<span class="chat_noread_cnt" style="top:44px; right:30px;">'+readNCount+'</span>');

 									//채팅목록의 부모창 (채용공고 에 카운트 up 하는 부분 숨김, 이미 data 속성으로 처리했음)
 									//var $firstAngleLink = $(this).parent().parent().find('.angle-link').first();
 									//var $chatNoreadCnt = $firstAngleLink.find('.chat_noread_cnt');

 									//if ($chatNoreadCnt.length) {
 									//	// If chat_noread_cnt exists, update the count
 									//	var currentCount = parseInt($chatNoreadCnt.text(), 10);
 									//	var newCount = currentCount + readNCount;
 									//	$chatNoreadCnt.text(newCount);
 									//} else {
 									//	// If chat_noread_cnt doesn't exist, append new element
 									//	$firstAngleLink.append('<span class="chat_noread_cnt" style="top:44px; right:30px;">' + readNCount + '</span>');
 									//}

 								}
 							});
 							$('#chat_list2 .content-wrap').each(function() {
 								var roomIdxList = $(this).data('room_idx_list');
 								if (roomIdxList != undefined) {
 									var roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',');

 									roomIdxArray = roomIdxArray.map(function(idx) {
 										return parseInt(idx.trim(), 10);
 									});

 									if (roomIdxArray.includes(rm_idx)) {
 										$(this).data('noreadcnt', parseInt($(this).data('noreadcnt')) + readNCount);

 										var $chatNoreadCnt = $(this).find('.chat_noread_cnt');
 										//if ($chatNoreadCnt.length==0) {
 										if ($(this).data('noreadcnt') > 0) {
 											$(this).append('<span class="chat_noread_cnt c11">' + $(this).data('noreadcnt') + '</span>');
 										}
 										//}
 									};
 								}
 							});

 							if (readNCount > 0) {
 								$('#chat_list2 .room_idx_' + rm_idx).append('<span class="chat_noread_cnt" style="top:33px; right:30px;">' + readNCount + '</span>');
 							}
						<?php } ?>

					}
				}

				// 전체 안읽은 메시지 카운트 계산하여 noreadcountall 업데이트
				var countAll = 0;
				for (const key in results) {
					if (results.hasOwnProperty(key)) {
						const result = results[key];
						const { rm_messages } = result;
						
						rm_messages.forEach(message => {
							if (message.mg_reads) {
								message.mg_reads.forEach(read => {
									if (read.read_yn === 'N' && read.user_id === $("#user_id").val()) {
										countAll++;
									}
								});
							}
						});
					}
				}

				// noreadcountall 업데이트
				if (countAll > 0) {
					if (countAll > 99) {
						$('#noreadcountall').html('99+');
						$('#noreadcountall').css('width', '40px');
					} else {
						$('#noreadcountall').html(countAll);
						$('#noreadcountall').css('width', '30px');
					}
					$('#noreadcountall').show();
				} else {
					$('#noreadcountall').hide();
				}


			}); //comAjax

		}

		//더블클릭 이벤트 처리
		// 기업회원 제안받은 인재 / 제안보낸 인재 탭의 항목 클릭시 채팅방 진입
		$(document).on("click", ".room-item", function() {
			//대화내용 중복 차단 내역 초기화
			processedIdx = [];

			//alert('제안보낸리스트 클릭');

			//제안보낸 목록 클릭시
			// 초기화
			click_type = "list";
			rm_start = 0;
			$("#chat_view").html("");
			rm_idx = $(this).data("target_id");
			// get_chat_info 중복 호출 방지 플래그 초기화
			window.chat_info_called = false;
			//alert('1:'+rm_idx);


			//룸 호출
			socket.emit("room_join", {
				rm_idx: Number(rm_idx),
				con_user_id: g_user_id
			});
			//alert(rm_idx);
			//alert(g_user_id);
			// 채팅방 정보 조회
			socket.emit("room_info", {
				rm_idx: Number(rm_idx),
				con_user_id: g_user_id
			});
			//alert('room_info_call');//test

 			//2024.08.02 대화내용 있으면 가져오도록 추가
 			//메시지 리스트 조회
 			var params = {
 				rm_idx: Number(rm_idx),
 				skip: 0,
 				limit: 1000 //rm_length
 			};
 			//console.log(params);
 			//alert('1');
 			socket.emit("message_list", params);


 			//$('.chat-view').show();
 			$('.chat-view').css('display', 'flex');
 			//alert(11);
 			//alert($('#top_name_mem').html());
 			//alert($(this).data("regdt"));
 			//$('#top_work_img').before("<p>"+$(this).data("regdt")+"</p>");
 			$('#top_reg_dt').html($(this).data("regdt"));

			var $li = $(this); // 클릭한 li 요소
			var $ol = $li.closest('ol'); // 부모 ol 요소

			// 클릭한 li 요소를 부모 ol의 맨 윗줄로 이동
			// 2024.08.06 맨위로 이동하는 것 제거
			//console.log($(this).parent().attr('id'));
			//console.log($(this).parent().parent().parent().attr('id'));
			if ($(this).parent().attr('id') == 'chat_list2' || $(this).parent().attr('id') == 'chat_list1') {
				//$li.prependTo($ol);
				//$li.find('.chat_noread_cnt').html(0);
				//$li.find('.chat_noread_cnt').hide();
			} else if ($(this).parent().parent().parent().attr('id') == 'chat_list1') {
				//$(this).parent().parent().prependTo($ol);
				//$(this).parent().parent().find('.chat_noread_cnt').html(0);
				//$(this).parent().parent().find('.chat_noread_cnt').hide();
			}

			// 채팅방 진입 시 안읽은 메시지 카운트 제거
			// 지원한 공고, 제안받은 공고, resumebox_ 내부 인재 목록 등 모든 room-item 클릭 시 처리
			// chat_noread_cnt, chat_noread_cnt c11 등 모든 변형 제거
			// 클릭한 room-item 내부의 모든 chat_noread_cnt 제거
			$li.find('.chat_noread_cnt').remove();
			$li.find('[class*="chat_noread_cnt"]').remove();
			// room_idx_ 클래스를 가진 모든 요소에서 chat_noread_cnt 제거 (다른 위치에 있을 수 있음)
			$('.room_idx_' + rm_idx).find('.chat_noread_cnt').remove();
			$('.room_idx_' + rm_idx).find('[class*="chat_noread_cnt"]').remove();
			//$('.chat-list-body').scrollTop(0);
			//미확인 카운트수 다시 조회(화면 하단 플로팅)
			socket.emit("message_user_read_count", {
				user_id: $("#user_id").val()
			});

			//공고리스트에 미확인 채팅건수 조회 확인 및 noreadcountall 업데이트
			fSearchNoReadCount();


 		});

 		/////////////////////////////////////////////////////////
 		// 리턴 처리
 		/////////////////////////////////////////////////////////

 		//회원리스트 가져오기
 		//console.log("room_list_return set");
 		socket.on("room_list_return", function(ret) {
 			//console.log("room_list_return 1");
 			//alert('room_list_return 1');
 			//console.log(ret);

 			room_list_all = ret;
 			//console.log(room_list_all);

 			g_user_id = $("#user_id").val();

 			if (chat_tab_id == '2') {
 				$("#chat_list" + chat_tab_id).html("");
 			}

 			if (ret != null) {
 				if (chat_tab_id == '2') {
 					if (ret.length == 0) {
 						$("#chat_empty" + chat_tab_id).show();
 					} else {
 						$("#chat_empty" + chat_tab_id).hide();
 					}
 				}
 				for (var icnt = 0; icnt < ret.length; icnt++) {
 					//console.log('chatlist:');
 					//console.log(ret);
 					var img_html = getRoomImg(ret[icnt], g_user_id);
 					var subject = getRoomSubject(ret[icnt], g_user_id);
 					var desc = getRoomDesc(ret[icnt], "chat");
 					var not_read = ret[icnt].not_read;
 					var title = ret[icnt].rm_subject;
 					if (chat_tab_id == "3") {
 						subject = maskingName(subject);
 					}

 					var html = "";
 					html += "<li class='list-item msg-item room-item room_idx_" + ret[icnt].rm_idx + "' data-target_id='" + ret[icnt].rm_idx + "'>";
 					html += "    <a href='#' class='angle-link js-open-view'>";
 					html += "        <div class='thumb'><img src='" + img_html + "' alt='' ></div>";
 					if (chat_tab_id == "3") {
 						html += "        <div class='mid' style='padding-top:3px;'>";
 						html += "            <p class='name'>" + title + "</p>";
 						html += "            <p class='name'>" + subject + "</p>";
 					} else {
 						html += "        <div class='mid'>";
 						html += "            <p class='name'>" + subject + "</p>";
 					}
 					html += "            <p class='latest'>" + desc + "</p>";
 					html += "            <p class='time'>" + getFormatDate(new Date(ret[icnt].rm_last_dt), 'chat') + "</p>";
 					if (not_read > 0) {
 						html += "    <p class='badge'><span>" + not_read + "</span></p>";
 					}
 					html += "        </div>";
 					html += "    </a>";
 					html += "    <button type='button' onclick='deleteItem(this.parentNode);' class='btn-primary btn-trash js-del-item'><span class='sr-text'>삭제</span></button>";
 					html += "</li>";

 					//적용
 					if (chat_tab_id == '2') {
 						$("#chat_list" + chat_tab_id).append(html);
 					}
 					//alert(html);
 				}
 			}

 			if (click_type == "chat" || click_type == "trade") {
 				for (var icnt = 0; icnt < $(".room-item").length; icnt++) {
 					if (rm_idx == $(".room-item").eq(icnt).attr("data-target_id")) {
 						$(".room-item").eq(icnt).find(".js-open-view").addClass("on");
 						//뷰 초기화
 						$("#chat_view").html("");
 						//alert('2');
 						//뷰 보이게 처리
 						$(".chat-view").removeAttr("hidden");
 						//alert(22);

 						//방번호 초기화
 					}
 					//alert($(".room-item").eq(icnt).attr("data-target_id"));
 				}
 				click_type = "list";
 			}
 		});

 		//회원리스트 가져오기2
 		var roomIdxArr = [];
 		socket.on("room_list_return2", function(ret) {
 			//console.log('room_list_return2');
 			//alert('room_list_return2');

 			g_user_id = $("#user_id").val();
 			//$("#chat_list" + chat_tab_id).html("");
 			if (ret != null) {
 				//console.log(ret);
 				if (ret.length == 0) {
 					//$("#chat_empty" + chat_tab_id).show();
 				} else {
 					//$("#chat_empty" + chat_tab_id).hide();
 				}
 				for (var icnt = 0; icnt < ret.length; icnt++) {
 					//console.log('chatlist:');
 					//console.log(ret);
 					var img_html = getRoomImg(ret[icnt], g_user_id);
 					var subject = getRoomSubject(ret[icnt], g_user_id);
 					var desc = getRoomDesc(ret[icnt], "chat");
 					var not_read = ret[icnt].not_read;
 					var title = ret[icnt].rm_subject;
 					if (chat_tab_id == "3") {
 						subject = maskingName(subject);
 					}
 					roomIdxArr.push(ret[icnt].rm_idx);

 					var html = "";
 					html += "<li class='list-item msg-item room-item room_idx_" + ret[icnt].rm_idx + "' data-target_id='" + ret[icnt].rm_idx + "'>";
 					html += "    <a href='#' class='angle-link js-open-view'>";
 					html += "        <div class='thumb'><img src='" + img_html + "' alt='' ></div>";
 					if (chat_tab_id == "3") {
 						html += "        <div class='mid' style='padding-top:3px;'>";
 						html += "            <p class='name'>" + title + "</p>";
 						html += "            <p class='name'>" + subject + "</p>";
 					} else {
 						html += "        <div class='mid'>";
 						html += "            <p class='name'>" + subject + "</p>";
 					}
 					html += "            <p class='latest'>" + desc + "</p>";
 					html += "            <p class='time'>" + getFormatDate(new Date(ret[icnt].rm_last_dt), 'chat') + "</p>";
 					if (not_read > 0) {
 						html += "    <p class='badge'><span>" + not_read + "</span></p>";
 					}
 					html += "        </div>";
 					html += "    </a>";
 					html += "    <button type='button' onclick='deleteItem(this.parentNode);' class='btn-primary btn-trash js-del-item'><span class='sr-text'>삭제</span></button>";
 					html += "</li>";

 					//적용
 					//$("#chat_list" + chat_tab_id).append(html);
 					//alert(html);
 				}
 			}

 			if (click_type == "chat" || click_type == "trade") {
 				for (var icnt = 0; icnt < $(".room-item").length; icnt++) {
 					if (rm_idx == $(".room-item").eq(icnt).attr("data-target_id")) {
 						//$(".room-item").eq(icnt).find(".js-open-view").addClass("on");
 						//뷰 초기화
 						//$("#chat_view").html("");
 						//뷰 보이게 처리
 						//$(".chat-view").removeAttr("hidden");

 						//방번호 초기화
 					}
 					//alert($(".room-item").eq(icnt).attr("data-target_id"));
 				}
 				click_type = "list";
 			}
 		});

 		//실시간 메시지 정보 리스트
 		socket.on("message_list_refresh", function(ret) {
 			//alert("message_list_refresh");
 			g_user_id = $("#user_id").val();
 			socket.emit("room_list", {
 				user_id: g_user_id,
 				rm_type: (chat_tab_id == "2" ? "chat" : "trade")
 			});
 		});

 		//console.log('user_id:'+g_user_id);

 		//[공통] : 알림 메시지 수신
 		socket.on("realtime_message", function(ret) {
 			//alert("realtime_message");
 			if (ret.type == "chat") {
 				//alert(ret.not_read);
 			}
 		});

		function setShow(type) {
			if (type == "1") {
				$(".chat_tab1").addClass("on");
				$(".chat_tab2").removeClass("on");
				$(".chat_tab3").removeClass("on");
				$("#chatPanel1").addClass("on");
				$("#chatPanel1").removeAttr("hidden");
				$("#chatPanel2").attr("hidden", "");
				$("#chatPanel3").attr("hidden", "");

				// //지원자목록에 2단계펼침 여부 확인
				// if(chatDetailShow){
				// 	$('.chat-list').css('width','690px');
				// }else{
				// 	$('.chat-list').css('width','345px');
				// }

				chat_tab_id = "1";
				$("#top_trade").hide();
				$("#top_recruit").show();
				$(".chat-view").attr("hidden", "");

				getChatTargetList(1);

 				//1:1 채팅 전체 리스트 get
 				//공고별과 비교 하기 위해
 				//console.log('room_list2 call');
 				socket.emit("room_list2", {
 					user_id: g_user_id,
 					rm_type: "chat"
 				});
 				$('.chat-view').hide();


			} else if (type == "2") {
				$(".chat_tab1").removeClass("on");
				$(".chat_tab2").addClass("on");
				$(".chat_tab3").removeClass("on");
				$("#chatPanel2").addClass("on");
				$("#chatPanel2").removeAttr("hidden");
				$("#chatPanel1").attr("hidden", "");
				$("#chatPanel3").attr("hidden", "");

				chat_tab_id = "2";
				$("#top_trade").hide();
				$("#top_recruit").show();
				$(".chat-view").attr("hidden", "");

				<?php if ($user_type == "MEM") { ?>
					getChatTargetList(2); //제안받은공고탭
				<?php } ?>
				<?php if ($user_type == "COMP") { ?>
					//24.10.17 채용공고리스트로 변경
					getChatTargetList(2);
				<?php } ?>

 			} else if (type == "3") {
 				$(".chat_tab1").removeClass("on");
 				$(".chat_tab2").removeClass("on");
 				$(".chat_tab3").addClass("on");
 				$("#chatPanel3").addClass("on");
 				$("#chatPanel3").removeAttr("hidden");
 				$("#chatPanel1").attr("hidden", "");
 				$("#chatPanel2").attr("hidden", "");

 				$('.chat-list').css('width', '345px');

 				chat_tab_id = "3";
 				$("#top_trade").show();
 				$("#top_recruit").hide();
 				$(".chat-view").attr("hidden", "");
 				//alert(66);
 				socket.emit("room_list", {
 					user_id: g_user_id,
 					rm_type: "trade"
 				});

 			}
 		}

		function getChatTargetList(arg) {
			
			//alert(arg);
			var getUrl = "get_chat_target_list_recruit";
			<?php if ($user_type == "MEM") { ?>
				if (arg == '1') getUrl = "get_chat_target_list_recruit_mem"; //지원한공고탭 회사정보가져오기
				if (arg == '2') getUrl = "get_chat_target_list2_mem"; //제안받은공고탭 회사정보가져오기
			<?php } ?>
			<?php if ($user_type == "COMP") { ?>
				if (arg == '1') getUrl = "get_chat_target_list_recruit"; //기업 회원의 제안받은 인재 탭에서  회사공고 목록
				if (arg == '2') getUrl = "get_chat_target_list_recruit2"; //기업회원 제안보낸 인재 탭에서  회사공고 목록
			<?php } ?>

 			comAjax("/chat_proc/" + getUrl, {}, function(ret) { //_recruit


 				//console.log("get_chat_target_list_recruit:"+g_user_id);
 				//socket.emit("room_list2",{user_id : g_user_id,rm_type : "chat"});
 				//채팅목록 call
 				//socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

				$("#chat_list" + arg).html("");
				//console.log('chat_list1');
				//console.log(ret);


				if (ret.code == "00") {
					// 배열이 아닐 경우를 대비하여 배열 체크 및 기본값 설정
					var list = Array.isArray(ret.data.list) ? ret.data.list : [];
					list.forEach(function(row) {
						//console.log(row);

 						<?php if ($user_type == "MEM") { ?>
 							// 탭1: 지원한 공고 (tpl_chat_mem), 탭2: 제안받은 공고 (tpl_chat2)
 							var $clone = (arg == '2') ? $("#tpl_chat2").clone() : $("#tpl_chat_mem").clone();
 						<?php } ?>
 						<?php if ($user_type == "COMP") { ?>
 							var $clone = $("#tpl_chat").clone(); //기업회원 제안보낸인재/채용공고 탭의의 공고 목록 항목
 						<?php } ?>

 						var $content = $clone.html();

 						$content = $content.replaceAll("{{room_idx}}", row.rm_idx);
 						$content = $content.replaceAll("{{room_idx_list}}", row.rm_idx_list);
 						$content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
 						$content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
 						$content = $content.replaceAll("{{mm_name}}", row.mm_name);
 						$content = $content.replaceAll("{{ru_week}}", row.ru_week);
 						$content = $content.replaceAll("{{distance}}", row.distance + "km");
 						$content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
 						$content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
 						$content = $content.replaceAll("{{ma_age}}", row.ma_age);
 						$content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
 						$content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
 						$content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
 						$content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
 						$content = $content.replaceAll("{{mm_id}}", row.mm_id);
 						$content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
 						$content = $content.replaceAll("{{target_id}}", row.target_id);
 						$content = $content.replaceAll("{{user_id}}", row.user_id);
 						$content = $content.replaceAll("{{target_img}}", row.target_img);
 						$content = $content.replaceAll("{{user_img}}", row.user_img);
 						$content = $content.replaceAll("{{target_name}}", row.target_name);
 						$content = $content.replaceAll("{{user_name}}", row.user_name);

						$content = $content.replaceAll("{{rc_name}}", row.rc_name);
						$content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb);
						$content = $content.replaceAll("{{reg_dt}}", row.reg_dt);
						$content = $content.replaceAll("{{rc_work}}", row.rc_work);
						// 회사명 추가
						$content = $content.replaceAll("{{mm_comp_name}}", row.mm_comp_name || "");
						
						// 마지막 메시지 표시 (HTML 태그 제거 및 줄바꿈 처리)
						var lastMsg = (row.last_msg_preview || "").replace(/\n/g, "<br/>");
						$content = $content.replaceAll("{{last_msg_preview}}", lastMsg);

 						// 연령이 0세로 내려오는 경우는 무관 처리
 						var hasStartAge = row.rc_sage && row.rc_sage !== "0";
 						var hasEndAge = row.rc_eage && row.rc_eage !== "0";
 						var agePart = "";
 						if (hasStartAge || hasEndAge) {
 							if (hasStartAge) {
 								agePart += row.rc_sage + "세 이상";
 							}
 							if (hasStartAge && hasEndAge) {
 								agePart += " ~ ";
 							}
 							if (hasEndAge) {
 								agePart += row.rc_eage + "세 이하";
 							}
 						}

 						var nationStr = row.rc_nation || "";
 						if (agePart !== "") {
 							if (nationStr !== "") {
 								nationStr += " | ";
 							}
 							nationStr += agePart;
 						}
 						if (nationStr === "") {
 							nationStr = "무관";
 						}
						$content = $content.replaceAll("{{rc_nation}}", nationStr);
						
						<?php if ($user_type == "MEM") { ?>
							// 탭2 (제안받은 공고)일 때만 추가 처리
							if (arg == '2') {
								// tpl_chat2 템플릿용 필드 처리
								$content = $content.replaceAll("{{rc_work}}", row.rc_work || "");
								$content = $content.replaceAll("{{rc_nation}}", row.rc_nation || "");
							}
						<?php } ?>

						// 채팅방 개수 계산
						var roomCount = 0;
						if (row.rm_idx_list && row.rm_idx_list !== "()" && row.rm_idx_list.trim() !== "") {
							// 괄호 제거하고 콤마로 분리
							var roomIdxArray = row.rm_idx_list.replace(/[()]/g, '').split(',');
							// 빈 문자열 필터링하고 숫자로 변환
							roomIdxArray = roomIdxArray.filter(function(idx) {
								return idx.trim() !== '';
							});
							roomCount = roomIdxArray.length;
						}
						$content = $content.replaceAll("{{room_count}}", roomCount);

						//복사한 내용 다시 넣기
						$clone.html($content);

 						$("#chat_list" + arg).append($clone.html());

 						//미확인 채팅 있는지 확인
 						//socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : g_user_id});
 						//socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

 					});

 					//하위 채팅창의 날짜 최신순으로 채용공고를 정렬
 					// list-item들을 순회하며 fShowChatListByRecruite를 실행
 					$('#chat_list1 .list-item a').each(function() {
 						//2024.08.05 안 읽은 글을 읽음처리 해버려서 주석 처리
 						//$(this).click();
 					});

 					// fShowChatListByRecruite가 완료된 후 정렬 수행 (예시로 1초 후 정렬 수행)
 					// setTimeout(function() {
 					//     var $list = $('#chat_list1');
 					//     var $listItems = $list.children('.list-item');
 					//
 					//     $listItems.sort(function(a, b) {
 					//         var dateA = $(a).find('.time').text();
 					//         var dateB = $(b).find('.time').text();
 					//
 					//         // class="time" p 태그가 없는 경우를 처리
 					//         if (!dateA) return 1;
 					//         if (!dateB) return -1;
 					//
 					//         dateA = new Date(dateA);
 					//         dateB = new Date(dateB);
 					//
 					//         return dateB - dateA; // 최신순 정렬
 					//     });
 					//
 					//     $list.append($listItems);
 					// }, 2000); // 예시로 1초 대기 후 정렬

 					//hide show 처리
 					if ($("#chat_list" + arg).find("li").length == 0) {
 						$("#chat_empty" + arg).show();
 						$("#chat_list" + arg).hide();
 					} else {
 						$("#chat_list" + arg).show();
 						$("#chat_empty" + arg).hide();
 					}

 				} else {
 					commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
 					return;
 				}

 				fSearchNoReadCount();

 			});
 		}

		
		//룸생성처리
		$(document).on("click", ".angle-link-resume", function() { //.btn-view-chat
			//alert($('#top_name_mem').html());
			//alert($(this).data("regdt"));


			//console.log('angle-link-resume');
			first_yn = "N";
			var g_rm_type = "recruit";
			var g_td_idx = 0;
			var target_id = $(this).data("target_id");
			var user_id = $(this).data("user_id");
			var target_img = $(this).data("target_img");
			var user_img = $(this).data("user_img");
			var target_name = $(this).data("target_name");
			var user_name = $(this).data("user_name");
			var subject = $(this).data("subject");

			//키값 설정
			g_rc_idx = $(this).data("rc_idx");
			g_ru_idx = $(this).data("ru_idx");

 			var params = {};
 			params["rm_type"] = g_rm_type;
 			params["rc_idx"] = g_rc_idx;
 			params["ru_idx"] = g_ru_idx;
 			params["td_idx"] = g_td_idx;

 			comAjax("/chat_proc/insert_room_check", params, function(chk) {
 				if (chk.code == "10") {
 					commonAlert(chk.msg, function() {});
 					return;
 				}

 				if (chk.code == "90") {
 					openPopup('confirm', {
 						content: "포인트가 부족합니다.<br/><em>포인트 구매</em>를 하시겠습니까?",
 						secondCallback: function() {
 							location.href("/compage/point_charge_step1");
 						}
 					});
 					return;
 				}

 				if (chk.data.first_yn == "Y") {
 					openPopup('confirm', {
 						content: '1:1채팅시 <em>-' + chat_point + '</em>포인트가 차감됩니다.<br> 채팅을 진행하시겠습니까?',
 						secondCallback: function() {


 							comAjax("/chat_proc/insert_room", params, function(ret2) {
 								if (ret2.code == "00") {
 									var chat_suer = [];
 									chat_suer.push({
 										user_id: user_id,
 										img: user_img,
 										user_name: user_name
 									});
 									chat_suer.push({
 										user_id: target_id,
 										img: target_img,
 										user_name: target_name
 									});

 									var room_info = {
 										user_id: user_id,
 										user_list: chat_suer,
 										rm_idx: Number(ret2.data.rm_idx),
 										rc_idx: g_rc_idx,
 										rm_type: "chat",
 										rm_img: "",
 										rm_subject: subject,
 										rm_desc: "",
 									};

 									//기본 문구 전송
 									first_yn = ret2.data.first_yn;
 									first_message = {
 										rm_idx: Number(ret2.data.rm_idx),
 										user_id: user_id,
 										chat_type: "recruit",
 										mg_type: "text",
 										mg_text: "1:1 채팅을 시작합니다!",
 										mg_req_dt: "",
 										mg_img: "",
 										mg_file: "",
 										mg_imoti: "",
 										mg_dt: getCurrentDate(),
 										mg_reads: new Array(),
 									};
 									first_message.mg_reads.push({
 										read_yn: "Y",
 										user_id: user_id
 									});
 									first_message.mg_reads.push({
 										read_yn: "N",
 										user_id: target_id
 									});

 									//회원정보
 									first_users.push({
 										user_id: user_id,
 										user_nick: user_name
 									});
 									first_users.push({
 										user_id: target_id,
 										user_nick: target_name
 									});

 									//발생성
 									click_type = "chat";
 									socket.emit("room_insert", room_info);
 								} else {
 									commonAlert(ret2.msg, function() {});
 									return;
 								}
 							});
 						}
 					});
 				} else {
 					comAjax("/chat_proc/insert_room", params, function(ret2) {
 						if (ret2.code == "00") {
 							var chat_suer = [];
 							chat_suer.push({
 								user_id: user_id,
 								img: user_img,
 								user_name: user_name
 							});
 							chat_suer.push({
 								user_id: target_id,
 								img: target_img,
 								user_name: target_name
 							});

 							var room_info = {
 								user_id: user_id,
 								user_list: chat_suer,
 								rm_idx: Number(ret2.data.rm_idx),
 								rm_type: "chat",
 								rm_img: "",
 								rm_subject: subject,
 								rm_desc: "",
 							};

 							//기본 문구 전송
 							first_yn = ret2.data.first_yn;
 							first_message = {
 								rm_idx: Number(ret2.data.rm_idx),
 								user_id: user_id,
 								chat_type: "recruit",
 								mg_type: "text",
 								mg_text: "1:1 채팅을 시작합니다!",
 								mg_req_dt: "",
 								mg_img: "",
 								mg_file: "",
 								mg_imoti: "",
 								mg_dt: getCurrentDate(),
 								mg_reads: new Array(),
 							};
 							first_message.mg_reads.push({
 								read_yn: "Y",
 								user_id: user_id
 							});
 							first_message.mg_reads.push({
 								read_yn: "N",
 								user_id: target_id
 							});

 							//회원정보
 							first_users.push({
 								user_id: user_id,
 								user_nick: user_name
 							});
 							first_users.push({
 								user_id: target_id,
 								user_nick: target_name
 							});

 							//발생성
 							click_type = "chat";
 							socket.emit("room_insert", room_info);
 						} else {
 							commonAlert(ret2.msg, function() {});
 							return;
 						}
 					});
 				}
 			});
 		});

 		//방생성 확인 및 방번호 확인
 		socket.on("room_insert_return", function(ret) {
 			//console.log('room_insert_return1409');
 			//alert('room_insert_return1409');

 			if (ret.code == "00") {
 				if (click_type == "trade") {
 					$("body").addClass('has-popup');
 					$(".js-open-chat").addClass("is-trigger");
 					$("#chatPopup").addClass("in");
 					$(".btn-close-chat").addClass("btn-close-chat");
 					$(".js-close-chat").addClass("show");

 					//채팅방 실시간 쏘기
 					if (first_yn == "Y") {
 						socket.emit("message_insert", first_message, first_users);
 					} else {
 						setShow("3");
 					}

 					//페이징 시작 초기화
 					rm_start = 0;
 					rm_idx = ret.data;

 					//룸 호출
 					socket.emit("room_join", {
 						rm_idx: Number(rm_idx),
 						con_user_id: g_user_id
 					});
 					//alert(1);
 					socket.emit("room_info", {
 						rm_idx: Number(rm_idx),
 						con_user_id: g_user_id
 					});

 				} else {
 					$("body").addClass('has-popup');
 					$(".js-open-chat").addClass("is-trigger");
 					$("#chatPopup").addClass("in");
 					$(".btn-close-chat").addClass("btn-close-chat");
 					$(".js-close-chat").addClass("show");

 					//채팅방 실시간 쏘기
 					if (first_yn == "Y") {
 						socket.emit("message_insert", first_message, first_users);
 					} else {
 						//두번째 탭 활성화
 						//setShow("2");
 					}

 					//페이징 시작 초기화
 					rm_start = 0;
 					rm_idx = ret.data;

 					//룸 호출
 					socket.emit("room_join", {
 						rm_idx: Number(rm_idx),
 						con_user_id: g_user_id
 					});
 					//alert(2);
 					socket.emit("room_info", {
 						rm_idx: Number(rm_idx),
 						con_user_id: g_user_id
 					});
 				}
 			} else {
 				commonAlert(ret.msg, function() {});
 			}
 		});
 	</script>

	<!-- 채팅 상세 처리 -->
	<script>
		//지원자목록에 2단계펼침 여부 확인
		var chatDetailShow = false;

		//방생성 확인 및 방번호 확인
		socket.on("room_info_return", function(ret) {
			//alert('room_info_return1471');
			//alert('return');//test

			if (ret.code != "00") {
				alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
			} else {
				//채팅방 정보
				rm_info = ret.data;
				// get_chat_info 중복 호출 방지 - 이미 호출되었으면 스킵
				if (window.chat_info_called) {
					return;
				}
				window.chat_info_called = true; // 호출 전에 플래그 설정

				//상세정보 가져오기
				//$("#top_recruit").hide();
				//$("#top_trade").hide();
			
			// ajax_first_num 초기화하여 중복 호출 알림 방지
			if (typeof ajax_first_num !== 'undefined') {
				ajax_first_num = 0;
			}
			
			comAjaxV2("/chat_proc/get_chat_info", {
				rm_idx: rm_idx,
				mm_type: mm_type,
				chat_tab_id: chat_tab_id
			}, function(ret) {
				if (ret.code == "00") {

					if (ret.data.type == "recruit") {
						
						// 채용정보 표시 (MEM: 지원한 공고/제안받은 공고, COMP: 제안받은 인재/제안보낸 인재)
						// 표시할 정보는 ret.data에 포함된 정보에 따라 결정
						
						if (mm_type == "MEM") {
								// 개인회원: 지원한 공고 또는 제안받은 공고의 채용정보 표시
								$("#top_recruit").show();
								$("#top_trade").hide();
								
								$("#top_name").html(ret.data.mm_comp_name || "");
								
								// 이미지 및 링크 설정
								var recruitImg = ret.data.rt_recruit_img || '/resource/shop/mobile/img/common/noimage4.png';
								$("#top_img").attr("src", recruitImg);
								var recruitLink = ret.data.rc_idx ? "/recruit/job_post/" + ret.data.rc_idx : "";
								if (recruitLink) {
									$("#top_link").attr("href", recruitLink);
									$("#top_title_link").attr("href", recruitLink);
								}
								
								// 즐겨찾기 버튼 설정
								if (ret.data.rc_idx) {
									$("#top_bookmark").attr("data-bk_key", ret.data.rc_idx);
									if (ret.data.book_yn == "Y") {
										$("#top_bookmark").addClass("on");
									} else {
										$("#top_bookmark").removeClass("on");
									}
								}
								
								// 직종 아이콘
								$("#top_work_img").html(ret.data.rt_work_img || "");
								
								// 제목
								$("#top_title").html(ret.data.rc_name || "");
								
								// 국적 처리 (무관 또는 내국인,외국인인 경우 국적무관으로 표시)
								var nationDisplay = ret.data.rc_nation_nm || "";
								if (nationDisplay == "무관" || nationDisplay == "내국인,외국인") {
									nationDisplay = "국적무관";
								}
								$("#top_nation").html(nationDisplay);
								
								// 나이 데이터가 0세(또는 0세 문자열)로 내려오면 무관 처리
								var rcSAge = parseInt(ret.data.rc_sage, 10);
								var rcEAge = parseInt(ret.data.rc_eage, 10);
								var rcAgeLabel = ret.data.rc_age_nm || "";
								var hasOnlyZeroNumber = ((isNaN(rcSAge) || rcSAge === 0) && (isNaN(rcEAge) || rcEAge === 0));
								var hasZeroWord = rcAgeLabel.replace(/\s/g, "").indexOf("0세") !== -1;
								if (hasOnlyZeroNumber || hasZeroWord) {
									rcAgeLabel = "무관";
								}
								$("#top_age").html(rcAgeLabel);
								$("#bt_age").html(rcAgeLabel);
								
								// 주소 처리 (fRemoveBigAdrJS 함수 사용)
								if (ret.data.ad_addr) {
									var addrDisplay = fRemoveBigAdrJS(ret.data.ad_addr);
									$("#top_area").html(addrDisplay).attr("data-addr", ret.data.ad_addr).closest(".chat-recruit-summary.area").show();
								} else {
									$("#top_area").closest(".chat-recruit-summary.area").hide();
								}
								
								// 가격 타입 및 급여 (값이 있을 때만 표시)
								if (ret.data.rt_price_type) {
									$("#top_price_type").html(ret.data.rt_price_type).show();
								} else {
									$("#top_price_type").hide();
								}
								if (ret.data.rt_price) {
									$("#top_price").html(ret.data.rt_price).show();
								} else {
									$("#top_price").hide();
								}
								
								// 근무 타입 처리 (서버에서 이미 처리된 경우 그대로 사용, 필요시 클라이언트에서 변환)
								var workTypeHtml = ret.data.rt_work_type || "";
								// <span> 태그를 <em> 태그로 변환하고 클래스 추가, 쉼표 제거
								if (workTypeHtml) {
									workTypeHtml = workTypeHtml.replace(/<span>(.*?)<\/span>/g, function(match, text) {
										text = text.trim();
										var cls = '';
										if (text === '주간') {
											cls = 'wtype1';
										} else if (text === '야간') {
											cls = 'wtype2';
										} else if (text === '2교대') {
											cls = 'wtype3';
										}
										return '<em class="' + cls + '">' + text + '</em>';
									});
									// 쉼표 제거
									workTypeHtml = workTypeHtml.replace(/,/g, '');
									$("#top_work_type").html(workTypeHtml).closest(".chat-recruit-summary3").show();
								} else {
									$("#top_work_type").closest(".chat-recruit-summary3").hide();
								}
								
								// 주요정보보기 링크
								if (ret.data.rc_idx) {
									$("#detailgo").attr("href", "/recruit/job_post/" + ret.data.rc_idx);
								}
								
								// top_recruit에 data-rc_idx 추가
								if (ret.data.rc_idx) {
									$("#top_recruit").attr("data-rc_idx", ret.data.rc_idx);
								}

								$("#bt_title").html(ret.data.rc_name || "");
								$("#bt_date").html(ret.data.rt_date || "");
								$("#bt_work_img").html(ret.data.rt_work_img || "");
								$("#bt_price_info").html(ret.data.rt_price_info || "");
								$("#bt_work_type").html(ret.data.rt_work_type || "");
								$("#bt_nation").html(ret.data.rc_nation_nm || "");
								$("#bt_sex").html(ret.data.rc_sex || "");
								$("#bt_yoil").html(ret.data.rt_week || "");
								$("#bt_time").html(ret.data.rt_time || "");
								if (ret.data.rc_idx) {
									$("#bt_link").attr("href", "/recruit/recruit_detail/" + ret.data.rc_idx);
								}
							$("#top_name").data('chatotherid', ret.data.mm_id || "");

						} else {
							
							// 기업회원: 제안받은 인재 또는 제안보낸 인재의 인재정보 표시
							$("#top_recruit").show();
							$("#top_trade").hide();
								
								$("#top_name").html(ret.data.mm_name || "");
								
								// 이미지 및 링크 설정
								var resumeImg = ret.data.ru_resume_img || '/resource/shop/mobile/img/common/noimage4.png';
								$("#top_img").attr("src", resumeImg);
								var resumeLink = ret.data.ru_idx ? "/resume/hr_post/" + ret.data.ru_idx : "";
								if (resumeLink) {
									$("#top_link").attr("href", resumeLink);
									$("#top_title_link").attr("href", resumeLink);
								}
								
								// 즐겨찾기 버튼 설정
								if (ret.data.ru_idx) {
									$("#top_bookmark").attr("data-bk_key", ret.data.ru_idx);
									if (ret.data.book_yn == "Y") {
										$("#top_bookmark").addClass("on");
									} else {
										$("#top_bookmark").removeClass("on");
									}
								}
								
								// 직종 아이콘
								$("#top_work_img").html(ret.data.ru_work_img || "");
								
								// 이력서명 (이름 대신 이력서명 표시)
								$("#top_name_mem").html(ret.data.ru_name || ret.data.mm_name || "");
								
								// 근무희망요일
								$("#top_weekday").html(ret.data.ru_week || "");
								
								// 국적 및 나이
								$("#top_nation").html(ret.data.mm_nation || "");
								var ageDisplay = ret.data.ma_age || "";
								if (ageDisplay) {
									ageDisplay = ageDisplay + "세";
								}
								$("#top_age").html(ageDisplay);
								
								// 주소 처리 (fRemoveBigAdrJS 함수 사용)
								if (ret.data.ad_addr) {
									var addrDisplay = fRemoveBigAdrJS(ret.data.ad_addr);
									$("#top_area").html(addrDisplay).attr("data-addr", ret.data.ad_addr).show();
								} else {
									$("#top_area").hide();
								}
								
								// 가격 타입 처리
								if (ret.data.ru_price_type) {
									var priceTypeHtml = ret.data.ru_price_type || "";
									$("#top_price_type").html('<p class="pricetype">' + priceTypeHtml + '</p>').closest(".chat-resume-summary2").show();
								} else {
									$("#top_price_type").closest(".chat-resume-summary2").hide();
								}
								
								// 근무 타입 처리 (서버에서 이미 처리된 경우 그대로 사용, 필요시 클라이언트에서 변환)
								var workTypeHtml = ret.data.ru_work_type || "";
								// <span> 태그를 <em> 태그로 변환하고 클래스 추가, 쉼표 제거
								if (workTypeHtml) {
									workTypeHtml = workTypeHtml.replace(/<span>(.*?)<\/span>/g, function(match, text) {
										text = text.trim();
										var cls = '';
										if (text === '주간') {
											cls = 'wtype1';
										} else if (text === '야간') {
											cls = 'wtype2';
										} else if (text === '2교대') {
											cls = 'wtype3';
										}
										return '<em class="' + cls + '">' + text + '</em>';
									});
									// 쉼표 제거
									workTypeHtml = workTypeHtml.replace(/,/g, '');
									$("#top_work_type").html('<p class="worktype">' + workTypeHtml + '</p>').closest(".chat-resume-summary3").show();
								} else {
									$("#top_work_type").closest(".chat-resume-summary3").hide();
								}
								
								// 인재정보보기 링크
								if (ret.data.ru_idx) {
									$("#detailgo").attr("href", "/resume/hr_post/" + ret.data.ru_idx);
								}
								
								// top_recruit에 data-ru_idx 추가
								if (ret.data.ru_idx) {
									$("#top_recruit").attr("data-ru_idx", ret.data.ru_idx);
								}

								$("#bt_title").html(ret.data.mm_name || "");
								$("#bt_nation").html(ret.data.mm_nation || "");
								$("#bt_age").html(ret.data.ma_age || "");
								$("#bt_area").html(ret.data.ad_addr || "");
								$("#bt_work_dt").html(ret.data.ru_work_dt || "");
								$("#bt_edu").html(ret.data.ru_edu || "");
								$("#bt_desc").html(ret.data.ru_desc || "");

								var bt_career = "";
								if (ret.data.career_list && ret.data.career_list.length > 0) {
									for (var icnt = 0; icnt < ret.data.career_list.length; icnt++) {
										bt_career += "<li>";
										bt_career += "      <span>" + (ret.data.career_list[icnt].rc_type || "") + "</span>";
										bt_career += "      <span>" + (ret.data.career_list[icnt].rc_name || "") + "</span>";
										bt_career += "      <em>" + (ret.data.career_list[icnt].rc_date || "") + "</em>";
										bt_career += "</li>";
									}
								}

								var bt_certi = "";
								if (ret.data.certi_list && ret.data.certi_list.length > 0) {
									for (var icnt = 0; icnt < ret.data.certi_list.length; icnt++) {
										bt_certi += "<li>";
										bt_certi += "      <span>자격증</span>";
										bt_certi += "      <span>" + (ret.data.certi_list[icnt].rc_name || "") + "</span>";
										bt_certi += "      <em>" + (ret.data.certi_list[icnt].rc_dt || "") + "</em>";
										bt_certi += "</li>";
									}
								}
								$("#bt_career").html(bt_career);
								$("#bt_certi").html(bt_certi);

								if (ret.data.ru_idx) {
									$("#bt_link").attr("href", "/resume/resume_detail/" + ret.data.ru_idx);
								}
							}

							$("[name='recruit_phone']").val(ret.data.recruit_phone);
							$("[name='recruit_name']").val(ret.data.recruit_name);
						} else {
							// comp_top_1이 주석처리되어 있어도 에러 방지를 위한 체크
							if ($("#comp_top_1").length > 0) {
								$("#comp_top_1").hide();
							}
							$("#top_name_mem").html(maskingName(ret.data.mm_name));
							$("#td_img").attr("src", ret.data.td_img1);
							$("#td_title").html(ret.data.td_name);
							$("#td_area").html(ret.data.td_area);
							$("#td_price").html(ret.data.td_price);
							$("#td_date").html(ret.data.reg_dt);
							if (ret.data.td_price_req_yn == "Y") {
								$("#td_price_yn").show();
							} else {
								$("#td_price_yn").hide();
							}
							$("#td_link").attr("href", "/trade/trade_detail/" + ret.data.td_idx);
						}
					}


					//메시지 리스트 조회
 					var params = {
 						rm_idx: Number(rm_idx),
 						skip: rm_start,
 						limit: rm_length
 					};
 					//console.log(params);
 					//alert(2);
 					socket.emit("message_list", params);
 				});
 			}

 			//미확인 카운트수 다시 조회(화면 하단 플로팅)
 			socket.emit("message_user_read_count", {
 				user_id: $("#user_id").val()
 			});

 		});

 		//방접속시 알림
 		socket.on("room_join_alarm", function(ret_user_id) {
 			$(".room_idx_" + rm_idx).find(".badge").html("<span></span>");
 			//내가 읽은게 아니라 내가 보낸 글에도 숫자가 없어져서 주석 처리 $(".readcount").html("");
 		});

 		var pagging_yn = false;
 		$("#chat_body").scroll(function() {
 			var scroll_top = $(this).scrollTop();
 			if ($(this).scrollTop() < 300 && pagging_yn == false) {
 				//alert('3');

 				if ($("#chat_view").html() != "") {
 					pagging_yn = true;

 					//메시지 리스트 조회
 					var params = {
 						rm_idx: Number(rm_idx),
 						skip: (++rm_start * rm_length),
 						limit: rm_length
 					};
 					//alert(3);
 					socket.emit("message_list", params);
 				}
 			}
 		});

 		$("#btnSend").click(function() {
 			send_message();
 		});

 		/////////////////////////////////////////////////////////
 		// 리턴 처리
 		/////////////////////////////////////////////////////////

 		function send_message() {
 			//alert('send_message');
 			var txt = $("#chatTxt").val();
 			if (txt == "") {
 				return;
 			}

 			var message = {
 				rm_idx: Number(rm_idx),
 				user_id: g_user_id,
 				chat_type: "recruit",
 				mg_type: "text",
 				mg_text: txt,
 				mg_req_dt: "",
 				mg_img: "",
 				mg_file: "",
 				mg_imoti: "",
 				mg_dt: getCurrentDate(),
 				mg_reads: new Array(),
 			};
 			//console.log(message);
 			//console.log(rm_info);

 			//리드 카운터 초기화 한다.
 			if (rm_info != null) {
 				//alert('send_message1');
 				for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
 					if (g_user_id == rm_info.rm_users[icnt].user_id) {
 						message.mg_reads.push({
 							read_yn: "Y",
 							user_id: rm_info.rm_users[icnt].user_id
 						});
 					} else {
 						message.mg_reads.push({
 							read_yn: "N",
 							user_id: rm_info.rm_users[icnt].user_id
 						});
 					}
 				}
 			} else {
 				//alert('send_message2');
 				first_yn = "Y";
 				var rm_info = {
 					rm_users: [{
 							user_id: $("#top_name").data('chatotherid')
 						},
 						{
 							user_id: g_user_id
 						}
 					]
 				};

 				//mongo db에 새로운 방 생성
 				var params = {
 					rm_idx: rm_idx,
 					user_id1: g_user_id,
 					user_id2: $("#top_name").data('chatotherid'),
 					user_name1: g_user_id,
 					user_name2: $("#top_name").data('chatotherid')
 				};
 				comAjax("/chat_proc/newroommake", params, function(ret) {
 					//console.log(ret);
 					//alert('message_insert1');
 					socket.emit("message_insert", message, rm_info.rm_users);
 					$("#chatTxt").val(""); //.focus();
 					return; // 첫 번째 메시지 전송 후 종료
 				});

 			}
 			//console.log('for //');
 			//console.log(rm_info.rm_users);

 			//채팅방 실시간 쏘기
 			//alert('message_insert2');
 			//24.08.02 위에서 emit message_insert 했으므로, 메시지가 두번 가서 주석 처리
 			//socket.emit("message_insert", message, rm_info.rm_users);
 			//console.log('message_insert');

 			//채팅창 클리어
 			$("#chatTxt").val(""); //.focus();
 		}

 		//메시지 정보 리턴
 		socket.on("message_insert_return", function(ret) {
 			//console.log("message_insert_return");
 			//console.log(ret);
 			//alert("message_insert_return");
 			//alert(first_yn);
 			if (first_yn == "Y") {
 				first_yn = "N";
 				if (click_type == "trade") {
 					setShow("3");
 				} else {
 					//alert('setShow 2');
 					if (!$('.chat_tab2').hasClass('on')) {
 						//alert('on');
 						// 2024.08.05 전송 후 화면 새로고침 안되도록 주석 처리
 						//setShow("2");
 					}
 					//alert('setShow 2-1');
 				}
 			}

 			//전송하면서 해당 채팅방 목록을 방목록 맨위로 이동
 			if ($('.chat_tab1').hasClass('on')) {

 				<?php if ($user_type == "MEM") { ?>
 					var targetLi = $('#chat_list1 li.room_idx_' + rm_idx);
 					// 만약 해당 li 요소가 존재하면
 					if (targetLi.length) {
 						// 해당 li 요소를 목록의 가장 위로 이동시킴
 						targetLi.prependTo('#chat_list1');
 					}
 				<?php } ?>
 				<?php if ($user_type == "COMP") { ?>
 					$('#chat_list1 li').each(function() {
 						var $contentWrapDiv = $(this).find('div.content-wrap');

 						// 해당 div의 data-room_idx_list 속성을 가져옴
 						var roomIdxList = $contentWrapDiv.data('room_idx_list');
 						if (roomIdxList != undefined) {

 							// 괄호를 제거하고, 문자열을 콤마로 분리하여 배열로 변환
 							var roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',');
 							// 숫자 값으로 변환
 							roomIdxArray = roomIdxArray.map(function(idx) {
 								return parseInt(idx.trim(), 10);
 							});

 							if (roomIdxArray.includes(rm_idx)) {

 								// 해당 li 요소를 목록의 가장 위로 이동시킴
 								$(this).prependTo('#chat_list1');
 								return false; // 첫 번째 조건에 맞는 요소를 찾으면 반복문을 종료

 							}

 						}

 					});
 				<?php } ?>

 			} else if ($('.chat_tab2').hasClass('on')) {

 				var targetLi = $('#chat_list2 li.room_idx_' + rm_idx);
 				// 만약 해당 li 요소가 존재하면
 				if (targetLi.length) {
 					// 해당 li 요소를 목록의 가장 위로 이동시킴
 					//targetLi.prependTo('#chat_list2');
 				}
 			} //.chat_tab2

 			var message_list = ret.data;
 			for (var icnt = 0; icnt < message_list.length; icnt++) {
 				//alert('a');
 				showMessage(message_list[icnt], false);
 				//메시지 리스트 조회
 				var params = {
 					rm_idx: Number(rm_idx),
 					mg_idx: Number(message_list[icnt].mg_idx),
 					user_id: g_user_id
 				};
 				socket.emit("message_read_return", params);
 			}
 			//웹에서만 리스트 다시 부름
 			//alert('room_list:'+g_user_id);
 			//alert('room_list:chat_tab_id:'+chat_tab_id);
 			socket.emit("room_list", {
 				user_id: g_user_id,
 				rm_type: (chat_tab_id == "2" ? "chat" : "trade")
 			});
 			scrollBottom();
 		});

 		//메시지 정보 리턴
 		socket.on("message_read_count_return", function(ret) {
 			//console.log("message_read_count_return1687");
 			//alert("message_read_count_return");
 			//리턴받은 카운트 메시지 정보 업데이트
 			$("[id^=mg_idx_]").last().prop("id", "mg_idx_" + ret.data.mg_idx)
 			if (ret.data.count == 0) {
 				$("[id^=mg_idx_]").last().find(".readcount").html("");
 				$(".room_idx_" + rm_idx).find(".badge").html("<span></span>");
 			} else {
 				$(".room_idx_" + rm_idx).find(".badge").html("<span>" + ret.data.count + "</span>");
 				$("[id^=mg_idx_]").last().find(".readcount").html(ret.data.count);
 			}
 		});


 		//방생성 확인 및 방번호 확인
		socket.on("message_list_return", function(ret) {
			//지원한 공고 목록에서 공고 클릭시 해당 채팅창 room 조회 결과
			//console.log(ret);
			//console.log("message_list_return1703");
			//alert("message_list_return1703");
			//메시지 리스트 가져오기
			var message_list = ret.data;
			for (var icnt = 0; icnt < message_list.length; icnt++) {
				//alert('b');
				//2024.08.05 글을 읽음 처리 해버려서 주석 처리
				showMessage(message_list[icnt].rm_messages, true);
			}
			if (pagging_yn == false) {
				scrollBottom();
			}
			pagging_yn = false;
			
			// room_info_return 이벤트가 발생하지 않는 경우를 대비하여
			// message_list_return 이후에 get_chat_info를 호출
			// 단, 이미 get_chat_info가 호출되었는지 확인
			if (rm_idx && !window.chat_info_called) {
				window.chat_info_called = true; // 중복 호출 방지
				
				// ajax_first_num 초기화하여 중복 호출 알림 방지
				if (typeof ajax_first_num !== 'undefined') {
					ajax_first_num = 0;
				}
				
				//상세정보 가져오기
				comAjaxV2("/chat_proc/get_chat_info", {
					rm_idx: rm_idx,
					mm_type: mm_type,
					chat_tab_id: chat_tab_id
				}, function(ret) {
					if (ret.code == "00") {

						if (ret.data.type == "recruit") {
							
							// 채용정보 표시 (MEM: 지원한 공고/제안받은 공고, COMP: 제안받은 인재/제안보낸 인재)
							// 표시할 정보는 ret.data에 포함된 정보에 따라 결정
							
							if (mm_type == "MEM") {
								
								$("#top_recruit").show();
								$("#top_trade").hide();
								
								$("#top_name").html(ret.data.mm_comp_name || "");
								
								// 이미지 및 링크 설정
								var recruitImg = ret.data.rt_recruit_img || '/resource/shop/mobile/img/common/noimage4.png';
								$("#top_img").attr("src", recruitImg);
								var recruitLink = ret.data.rc_idx ? "/recruit/job_post/" + ret.data.rc_idx : "";
								if (recruitLink) {
									$("#top_link").attr("href", recruitLink);
									$("#top_title_link").attr("href", recruitLink);
								}
								
								// 즐겨찾기 버튼 설정
								if (ret.data.rc_idx) {
									$("#top_bookmark").attr("data-bk_key", ret.data.rc_idx);
									if (ret.data.book_yn == "Y") {
										$("#top_bookmark").addClass("on");
									} else {
										$("#top_bookmark").removeClass("on");
									}
								}
								
								// 직종 아이콘
								$("#top_work_img").html(ret.data.rt_work_img || "");
								
								// 제목
								$("#top_title").html(ret.data.rc_name || "");
								
								// 국적 처리 (무관 또는 내국인,외국인인 경우 국적무관으로 표시)
								var nationDisplay = ret.data.rc_nation_nm || "";
								if (nationDisplay == "무관" || nationDisplay == "내국인,외국인") {
									nationDisplay = "국적무관";
								}
								$("#top_nation").html(nationDisplay);
								
								// 나이 데이터가 0세(또는 0세 문자열)로 내려오면 무관 처리
								var rcSAge = parseInt(ret.data.rc_sage, 10);
								var rcEAge = parseInt(ret.data.rc_eage, 10);
								var rcAgeLabel = ret.data.rc_age_nm || "";
								var hasOnlyZeroNumber = ((isNaN(rcSAge) || rcSAge === 0) && (isNaN(rcEAge) || rcEAge === 0));
								var hasZeroWord = rcAgeLabel.replace(/\s/g, "").indexOf("0세") !== -1;
								if (hasOnlyZeroNumber || hasZeroWord) {
									rcAgeLabel = "무관";
								}
								$("#top_age").html(rcAgeLabel);
								$("#bt_age").html(rcAgeLabel);
								
								// 주소 처리 (fRemoveBigAdrJS 함수 사용)
								if (ret.data.ad_addr) {
									var addrDisplay = fRemoveBigAdrJS(ret.data.ad_addr);
									$("#top_area").html(addrDisplay).attr("data-addr", ret.data.ad_addr).closest(".chat-recruit-summary.area").show();
								} else {
									$("#top_area").closest(".chat-recruit-summary.area").hide();
								}
								
								// 가격 타입 및 급여 (값이 있을 때만 표시)
								if (ret.data.rt_price_type) {
									$("#top_price_type").html(ret.data.rt_price_type).show();
								} else {
									$("#top_price_type").hide();
								}
								if (ret.data.rt_price) {
									$("#top_price").html(ret.data.rt_price).show();
								} else {
									$("#top_price").hide();
								}
								
								// 근무 타입 처리 (서버에서 이미 처리된 경우 그대로 사용, 필요시 클라이언트에서 변환)
								var workTypeHtml = ret.data.rt_work_type || "";
								// <span> 태그를 <em> 태그로 변환하고 클래스 추가, 쉼표 제거
								if (workTypeHtml) {
									workTypeHtml = workTypeHtml.replace(/<span>(.*?)<\/span>/g, function(match, text) {
										text = text.trim();
										var cls = '';
										if (text === '주간') {
											cls = 'wtype1';
										} else if (text === '야간') {
											cls = 'wtype2';
										} else if (text === '2교대') {
											cls = 'wtype3';
										}
										return '<em class="' + cls + '">' + text + '</em>';
									});
									// 쉼표 제거
									workTypeHtml = workTypeHtml.replace(/,/g, '');
									$("#top_work_type").html(workTypeHtml).closest(".chat-recruit-summary3").show();
								} else {
									$("#top_work_type").closest(".chat-recruit-summary3").hide();
								}
								
								// 주요정보보기 링크
								if (ret.data.rc_idx) {
									$("#detailgo").attr("href", "/recruit/job_post/" + ret.data.rc_idx);
								}
								
								// top_recruit에 data-rc_idx 추가
								if (ret.data.rc_idx) {
									$("#top_recruit").attr("data-rc_idx", ret.data.rc_idx);
								}

								$("#bt_title").html(ret.data.rc_name || "");
								$("#bt_date").html(ret.data.rt_date || "");
								$("#bt_work_img").html(ret.data.rt_work_img || "");
								$("#bt_price_info").html(ret.data.rt_price_info || "");
								$("#bt_work_type").html(ret.data.rt_work_type || "");
								$("#bt_nation").html(ret.data.rc_nation_nm || "");
								$("#bt_sex").html(ret.data.rc_sex || "");
								$("#bt_yoil").html(ret.data.rt_week || "");
								$("#bt_time").html(ret.data.rt_time || "");
								if (ret.data.rc_idx) {
									$("#bt_link").attr("href", "/recruit/recruit_detail/" + ret.data.rc_idx);
								}
								$("#top_name").data('chatotherid', ret.data.mm_id || "");

							} else {
								
								$("#top_recruit").show();
								$("#top_trade").hide();
								
							$("#top_name").html(ret.data.mm_name || "");
							
							// 이미지 및 링크 설정
							var resumeImg = ret.data.ru_resume_img || '/resource/shop/mobile/img/common/noimage4.png';
							$("#top_img").attr("src", resumeImg);
							var resumeLink = ret.data.ru_idx ? "/resume/hr_post/" + ret.data.ru_idx : "";
							if (resumeLink) {
								$("#top_link").attr("href", resumeLink);
								$("#top_title_link").attr("href", resumeLink);
							}
							
							// 즐겨찾기 버튼 설정
							if (ret.data.ru_idx) {
								$("#top_bookmark").attr("data-bk_key", ret.data.ru_idx);
								if (ret.data.book_yn == "Y") {
									$("#top_bookmark").addClass("on");
								} else {
									$("#top_bookmark").removeClass("on");
								}
							}
							
							// 직종 아이콘
							$("#top_work_img").html(ret.data.ru_work_img || "");
							
							// 이력서명 (이름 대신 이력서명 표시)
							$("#top_name_mem").html(ret.data.ru_name || ret.data.mm_name || "");
							
							// 근무희망요일
							$("#top_weekday").html(ret.data.ru_week || "");
							
							// 국적 및 나이
							$("#top_nation").html(ret.data.mm_nation || "");
							var ageDisplay = ret.data.ma_age || "";
							if (ageDisplay) {
								ageDisplay = ageDisplay + "세";
							}
							$("#top_age").html(ageDisplay);
							
							// 주소 처리 (fRemoveBigAdrJS 함수 사용)
							if (ret.data.ad_addr) {
								var addrDisplay = fRemoveBigAdrJS(ret.data.ad_addr);
								$("#top_area").html(addrDisplay).attr("data-addr", ret.data.ad_addr).show();
							} else {
								$("#top_area").hide();
							}
							
							// 가격 타입 처리
							if (ret.data.ru_price_type) {
								var priceTypeHtml = ret.data.ru_price_type || "";
								$("#top_price_type").html('<p class="pricetype">' + priceTypeHtml + '</p>').closest(".chat-resume-summary2").show();
							} else {
								$("#top_price_type").closest(".chat-resume-summary2").hide();
							}
							
							// 근무 타입 처리 (서버에서 이미 처리된 경우 그대로 사용, 필요시 클라이언트에서 변환)
							var workTypeHtml = ret.data.ru_work_type || "";
							// <span> 태그를 <em> 태그로 변환하고 클래스 추가, 쉼표 제거
							if (workTypeHtml) {
								workTypeHtml = workTypeHtml.replace(/<span>(.*?)<\/span>/g, function(match, text) {
									text = text.trim();
									var cls = '';
									if (text === '주간') {
										cls = 'wtype1';
									} else if (text === '야간') {
										cls = 'wtype2';
									} else if (text === '2교대') {
										cls = 'wtype3';
									}
									return '<em class="' + cls + '">' + text + '</em>';
								});
								// 쉼표 제거
								workTypeHtml = workTypeHtml.replace(/,/g, '');
								$("#top_work_type").html('<p class="worktype">' + workTypeHtml + '</p>').closest(".chat-resume-summary3").show();
							} else {
								$("#top_work_type").closest(".chat-resume-summary3").hide();
							}
							
							// 인재정보보기 링크
							if (ret.data.ru_idx) {
								$("#detailgo").attr("href", "/resume/hr_post/" + ret.data.ru_idx);
							}
							
							// top_recruit에 data-ru_idx 추가
							if (ret.data.ru_idx) {
								$("#top_recruit").attr("data-ru_idx", ret.data.ru_idx);
							}

								$("#bt_title").html(ret.data.mm_name || "");
								$("#bt_nation").html(ret.data.mm_nation || "");
								$("#bt_age").html(ret.data.ma_age || "");
								$("#bt_area").html(ret.data.ad_addr || "");
								$("#bt_work_dt").html(ret.data.ru_work_dt || "");
								$("#bt_edu").html(ret.data.ru_edu || "");
								$("#bt_desc").html(ret.data.ru_desc || "");

								var bt_career = "";
								if (ret.data.career_list && ret.data.career_list.length > 0) {
									for (var icnt = 0; icnt < ret.data.career_list.length; icnt++) {
										bt_career += "<li>";
										bt_career += "      <span>" + (ret.data.career_list[icnt].rc_type || "") + "</span>";
										bt_career += "      <span>" + (ret.data.career_list[icnt].rc_name || "") + "</span>";
										bt_career += "      <em>" + (ret.data.career_list[icnt].rc_date || "") + "</em>";
										bt_career += "</li>";
									}
								}

								var bt_certi = "";
								if (ret.data.certi_list && ret.data.certi_list.length > 0) {
									for (var icnt = 0; icnt < ret.data.certi_list.length; icnt++) {
										bt_certi += "<li>";
										bt_certi += "      <span>자격증</span>";
										bt_certi += "      <span>" + (ret.data.certi_list[icnt].rc_name || "") + "</span>";
										bt_certi += "      <em>" + (ret.data.certi_list[icnt].rc_dt || "") + "</em>";
										bt_certi += "</li>";
									}
								}
								$("#bt_career").html(bt_career);
								$("#bt_certi").html(bt_certi);

								if (ret.data.ru_idx) {
									$("#bt_link").attr("href", "/resume/resume_detail/" + ret.data.ru_idx);
								}
							}

							$("[name='recruit_phone']").val(ret.data.recruit_phone);
							$("[name='recruit_name']").val(ret.data.recruit_name);
						} else {
							// comp_top_1이 주석처리되어 있어도 에러 방지를 위한 체크
							if ($("#comp_top_1").length > 0) {
								$("#comp_top_1").hide();
							}
							$("#top_name_mem").html(maskingName(ret.data.mm_name));
							$("#td_img").attr("src", ret.data.td_img1);
							$("#td_title").html(ret.data.td_name);
							$("#td_area").html(ret.data.td_area);
							$("#td_price").html(ret.data.td_price);
							$("#td_date").html(ret.data.reg_dt);
							if (ret.data.td_price_req_yn == "Y") {
								$("#td_price_yn").show();
							} else {
								$("#td_price_yn").hide();
							}
							$("#td_link").attr("href", "/trade/trade_detail/" + ret.data.td_idx);
						}
					}
				});
			}
		});

 		//방 조인
 		socket.on("room_join_return", function(ret) {
 			//alert("room_join_return");//test
 			$(".room_idx_" + rm_idx).find(".badge").html("<span></span>");
 			//$(".readcount").html("");
 		});

 		//접속 끊길때 방 정리
 		socket.on("room_leave_alarm", function(ret) {
 			//alert("접속이 끊겼습니다.");
 			socket.emit("room_leave", {
 				rm_idx: Number(rm_idx)
 			});
 		});

 		//방해제 처리
 		socket.on("room_leave_return", function(ret) {
 			//alert("방에 접속하셨습니다.");
 		});


 		$("#btnInerview").click(function() {
 			if ($("[name='interviewDate']").val() == "") {
 				commonAlert("날짜를 선택해주세요.", function() {

 				});
 				return;
 			}

 			$("#interviewForm").attr("hidden", "");
 			$("#interviewShow").attr("aria-expanded", false);
 			sendResume("면접일정확인", "", $("[name='interviewDate']").val().substr(0, 10));
 		});

 		//면접 취소
 		$(document).on("click", ".bntInterviewCancel", function() {
 			sendResume("개인면접취소요청", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
 		});

 		//면접 취소
 		$(document).on("click", ".bntInterviewCompCancel", function() {
 			sendResume("기업면접취소요청", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
 		});

 		//면접 날짜 변경
 		$(document).on("click", ".bntInterviewChange", function() {
 			sendResume("개인면접변경요청", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
 		});

 		//면접 날짜 변경
 		$(document).on("click", ".bntInterviewChangeOK", function() {
 			sendResume("개인면접변경수락", "", getCurrentDate());

 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
 		});

 		//면접 수락
 		$(document).on("click", ".bntInterviewOK", function() {
 			sendResume("개인면접수락", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "면접수락");
 		});

 		//면접 수락
 		$(document).on("click", ".btnCancelOK", function() {
 			sendResume("면접취소", "", getCurrentDate());

 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "면접취소");
 		});


 		$("#btnStartWrok").click(function() {
 			if ($("[name='startDate']").val() == "") {
 				commonAlert("날짜를 선택해주세요.", function() {

 				});
 				return;
 			}

 			$("#startDateForm").attr("hidden", "");
 			$("#dateShow").attr("aria-expanded", false);
 			sendResume("채용일정확인", "", $("[name='startDate']").val().substr(0, 10));
 		});

 		//면접 취소
 		$(document).on("click", ".bntRecruitCancel", function() {
 			sendResume("개인채용취소요청", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
 		});

 		//면접 취소
 		$(document).on("click", ".bntRecruitCompCancel", function() {
 			sendResume("기업채용취소요청", "", getCurrentDate());

 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
 		});

 		//면접 날짜 변경
 		$(document).on("click", ".bntRecruitChange", function() {
 			sendResume("개인채용변경요청", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
 		});

 		//면접 날짜 변경
 		$(document).on("click", ".bntRecruitChangeOK", function() {
 			sendResume("개인채용변경수락", "", getCurrentDate());

 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
 		});

 		//면접 수락
 		$(document).on("click", ".bntRecruitOK", function() {
 			sendResume("개인채용수락", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "채용수락");
 		});

 		//면접 수락
 		$(document).on("click", ".btnRecruitCancelOK", function() {
 			sendResume("채용취소", "", getCurrentDate());

 			//상태 변경 처리
 			var mg_idx = $(this).attr("data-mg_idx");
 			setEnabledButton($(this), rm_idx, mg_idx, "채용취소");
 		});

 		function changeStatus() {
 			var status = $("[name=rm_status]").val();
 			// comp_top_1이 주석처리되어 있어도 에러 방지를 위한 체크
 			if ($("#comp_top_1").length > 0) {
 				$("#comp_top_1").show();
 			}

 			$("#mem_top_2").hide();
 			$("#mem_top_3").hide();
 			if (status == "개인면접수락") {
 				$("#mem_top_2").show();
 				$("#mem_top_3").hide();
 			} else if (status == "개인채용수락") {
 				$("#mem_top_3").show();
 				$("#mem_top_2").hide();
 			}
 		}

 		changeStatus();

 		function sendResume(mg_type, mg_text, mg_req_dt) {
 			//수락인경우 날짜 업데이트
 			var rm_dt = mg_req_dt;

 			comAjax("/chat_proc/set_status", {
 				"rm_status": mg_type,
 				"rm_idx": rm_idx,
 				rm_dt: rm_dt
 			}, function(ret) {

 				//상태값 변경
 				$("[name=rm_status]").val(mg_type);
 				changeStatus();

 				var message = {
 					rm_idx: Number(rm_idx),
 					user_id: g_user_id,
 					mg_type: mg_type,
 					mg_req_dt: mg_req_dt,
 					mg_text: mg_text,
 					mg_dt: getCurrentDate(),
 					mg_reads: new Array(),
 				};

 				//리드 카운터 초기화 한다.
 				for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
 					if (g_user_id == rm_info.rm_users[icnt].user_id) {
 						message.mg_reads.push({
 							read_yn: "Y",
 							user_id: rm_info.rm_users[icnt].user_id
 						});
 					} else {
 						message.mg_reads.push({
 							read_yn: "N",
 							user_id: rm_info.rm_users[icnt].user_id
 						});
 					}
 				}

 				//채팅방 실시간 쏘기
 				socket.emit("message_insert", message, rm_info.rm_users);
 			});
 		}

 		//메시지 출력 처리
 		var user_type = "<?= $user_type ?>";

 		function showMessage(info, is_prepend) {

 			if (processedIdx.includes(info.mg_idx)) {
 				return; // Exit if the idx is already in the array
 			}
 			processedIdx.push(info.mg_idx);

			//alert('showMessage');

			//console.log(is_prepend);
 			//console.log('showMessage');
 			//alert('showMessage');
 			//유형에 따른 처리
 			if (info.mg_type == "timeline") {
 				//날짜 구분선이 이미 있으면 return
 				if ($('.date_' + getFormatDate(new Date(info.mg_dt), 'ymd')).length > 0) return;

 				var content = "<p class='chat-divider date_" + getFormatDate(new Date(info.mg_dt), 'ymd') + "'>";
 				content += "         " + getFormatDate(new Date(info.mg_dt), 'kor');
 				content += "</p>";
 			} else {
 				var your_text_yn = (info.user_id == g_user_id ? "Y" : "N");
 				var item = info.user_id + "^" + getFormatDate(new Date(info.mg_dt), 'ymd') + "^" + getFormatDate(new Date(info.mg_dt), 'hh:mm');

 				if (info.mg_type == "text") {

 					if (your_text_yn == "Y") {
 						var readcount = 0;
 						for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
 							if (info.mg_reads[icnt].read_yn == "N") {
 								readcount++;
 							}
 						}

 						//이미 추가한 대화내용이면 return
 						if ($('#mg_idx_' + info.mg_idx).length > 0) return;

 						var content = "<div class='msg-item chat-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "' >";
 						content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
 						if (readcount > 0) {
 							content += "    <div class='readcount'>" + readcount + "</div>";
 						} else {
 							content += "    <div class='readcount'></div>";
 						}
 						content += "    </div>";
 						content += "    <div class='meta'>";
 						content += "        <p class='date-time'>" + getFormatDate(new Date(info.mg_dt), 'hh:mm') + "</p>";
 						content += "    </div>";
 						content += "</div>";
 					} else {
 						var content = "<div class='msg-item chat-bubble diff' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "' >";
 						content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />") + "</div>";
 						content += "    <div class='meta'>";
 						content += "        <p class='date-time'>" + getFormatDate(new Date(info.mg_dt), 'hh:mm') + "</p>";
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "file") {

 				} else if (info.mg_type == "img") {

 				} else if (info.mg_type == "면접일정확인") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					$("#top_dt1").html(dt);
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접일 확정요청 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "로 면접일 요청이 왔습니다.수락하시겠습니까?(수락시 연락처 공개)</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-secondary btn-round bntInterviewCancel' data-mg_idx='" + info.mg_idx + "'>면접취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntInterviewChange' data-mg_idx='" + info.mg_idx + "'>변경신청</button>";
 							content += "        <button type='button' class='btn-tertiary btn-round bntInterviewOK' data-mg_idx='" + info.mg_idx + "'>면접수락</button>";
 						} else {
 							content += "        <button type='button' class='btn-secondary btn-round bntInterviewCancel' data-mg_idx='" + info.mg_idx + "' disabled>면접취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntInterviewChange' data-mg_idx='" + info.mg_idx + "' disabled>변경신청</button>";
 							content += "        <button type='button' class='btn-tertiary btn-round bntInterviewOK' data-mg_idx='" + info.mg_idx + "' disabled>면접수락</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "개인면접수락") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접 수락 하였습니다.";
 						content += "</p>";
 					} else {
 						var recruit_name = $("[name=recruit_name]").val();
 						var recruit_phone = $("[name=recruit_phone]").val();
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "로 면접이 확정되었습니다.<br> 연락처 공유드립니다.</div>";
 						content += "    <div class='alert-footer-bar'>";
 						content += "         <p class='call'>" + recruit_name + " : <a href='tel:" + recruit_phone + "'>" + recruit_phone + "</a></p>";
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "개인면접변경요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>면접일 변경 요청이 왔습니다<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-secondary btn-round bntInterviewCompCancel' data-mg_idx='" + info.mg_idx + "'>면접취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntInterviewChangeOK' data-mg_idx='" + info.mg_idx + "'>변경수락</button>";
 						} else {
 							content += "        <button type='button' class='btn-secondary btn-round bntInterviewCompCancel' data-mg_idx='" + info.mg_idx + "' disabled>면접취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntInterviewChangeOK' data-mg_idx='" + info.mg_idx + "' disabled>변경수락</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접일 변경 요청 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "개인면접변경수락") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접일 변경 수락 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접일 변경 수락 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "개인면접취소요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "면접 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
 						} else {
 							content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접 취소 신청 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "기업면접취소요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접 취소 신청 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "면접 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
 						} else {
 							content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "면접취소") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접이 취소 되었습니다.";
 						content += "</p>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 면접이 취소 되었습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "채용일정확인") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					$("#top_dt2").html(dt);
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용 확정 요청 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "로 근로예정일 요청이 왔습니다<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-secondary btn-round bntRecruitCancel' data-mg_idx='" + info.mg_idx + "'>요청취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntRecruitChange' data-mg_idx='" + info.mg_idx + "'>변경신청</button>";
 							content += "        <button type='button' class='btn-tertiary btn-round bntRecruitOK' data-mg_idx='" + info.mg_idx + "'>요청수락</button>";
 						} else {
 							content += "        <button type='button' class='btn-secondary btn-round bntRecruitCancel' data-mg_idx='" + info.mg_idx + "' disabled>요청취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntRecruitChange' data-mg_idx='" + info.mg_idx + "' disabled>변경신청</button>";
 							content += "        <button type='button' class='btn-tertiary btn-round bntRecruitOK' data-mg_idx='" + info.mg_idx + "' disabled>요청수락</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "개인채용수락") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용 수락 하였습니다.";
 						content += "</p>";
 					} else {
 						var recruit_name = $("[name=recruit_name]").val();
 						var recruit_phone = $("[name=recruit_phone]").val();
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "로 채용이 확정되었습니다.<br> 연락처 공유드립니다.</div>";
 						content += "    <div class='alert-footer-bar'>";
 						content += "         <p class='call'>" + recruit_name + " : <a href='tel:" + recruit_phone + "'>" + recruit_phone + "</a></p>";
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "개인채용변경요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>근로예정일 변경 요청이 왔습니다<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-secondary btn-round bntRecruitCompCancel' data-mg_idx='" + info.mg_idx + "'>채용취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntRecruitChangeOK' data-mg_idx='" + info.mg_idx + "'>변경수락</button>";
 						} else {
 							content += "        <button type='button' class='btn-secondary btn-round bntRecruitCompCancel' data-mg_idx='" + info.mg_idx + "' disabled>채용취소</button>";
 							content += "        <button type='button' class='btn-primary btn-round bntRecruitChangeOK' data-mg_idx='" + info.mg_idx + "' disabled>변경수락</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 근로예정일 변경요청 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "개인채용변경수락") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 근로예정일 변경 수락 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 근로예정일 변경 수락 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "개인채용취소요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "채용 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
 						} else {
 							content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용 취소 신청 하였습니다.";
 						content += "</p>";
 					}
 				} else if (info.mg_type == "기업채용취소요청") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용 취소 신청 하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
 						content += "    <div class='content'>" + dt + "채용 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
 						content += "    <div class='alert-footer'>";
 						if (info.mg_imoti == null) {
 							content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
 						} else {
 							content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
 						}
 						content += "    </div>";
 						content += "</div>";
 					}
 				} else if (info.mg_type == "채용취소") {
 					var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
 					if (user_type == "COMP") {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용을 취소하였습니다.";
 						content += "</p>";
 					} else {
 						var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
 						content += dt + " 채용을 취소하였습니다.";
 						content += "</p>";
 					}
 				}
 			}

 			if (is_prepend == true) {
 				//alert('4');
 				$("#chat_view").prepend(content);
 			} else {
 				//alert('5');
 				$("#chat_view").append(content);
 			}
 		}

 		function scrollBottom() {
 			$("#chat_body").scrollTop($("#chat_body")[0].scrollHeight);
 		}

 		function setEnabledButton($this, rm_idx, mg_idx, text) {
 			socket.emit("message_update_status", {
 				rm_idx: rm_idx,
 				mg_idx: parseInt(mg_idx),
 				mg_imoti: text
 			});

 			//상태 변경 UI 처리
 			$this.closest("div").find("button").prop("disabled", true);
 		}
		// 기업회원 제안받은 인재 / 제안보낸 인재 탭의 회사 공고 클릭시 아래 인재정보 가져오는 함수
		function fShowChatListByRecruite(ru_idx, obj) {
			//console.log('fShowChatListByRecruite:'+ru_idx);

 			if ($(obj).data('open') == null || $(obj).data('open') == 'N') {
 				$(obj).data('open', 'Y');
 			} else {
 				$(obj).data('open', 'N');
 				$(".resumebox_" + ru_idx + "").remove();
 				return;
 			}

 			chatDetailShow = true;

 			//$('.chat-list').css('width','690px');
 			//$('#chat_list1').css('width','50%');
 			//$('#chat_list1').css('height','539px');
 			//$('#chat_list1').css('border-right','1px solid #ccc');
 			//$('#chat_list1_1').css('width','50%');

 			// 현재 클릭한 공고가 어느 리스트에 속해있는지 확인
 			var parentListId = $(obj).closest('ol').attr('id'); // chat_list1 또는 chat_list2
 			var isChatList2 = (parentListId === 'chat_list2');

 			//지원자목록-공고목록 (기업회원 전용: 채용공고 번호로 해당 공고에 지원한 인재 목록 조회)
 			// 제안보낸 인재 탭인 경우 제안을 보낸 인재 목록 조회
 			var params = {};
 			params["rc_idx"] = ru_idx; // 기업회원: rc_idx는 채용공고 번호
 			params["chatgbn"] = parentListId; // chat_list1 또는 chat_list2

			comAjax("/chat_proc/get_chat_target_list_by_recruit_new", params, function(ret) {
				$(".resumebox_" + ru_idx + "").remove();

				//console.log("get_chat_target_list");
				//console.log(ret);


				if (ret.code == "00") {
					// 배열이 아닐 경우를 대비하여 배열 체크 및 기본값 설정
					var list = Array.isArray(ret.data.list) ? ret.data.list : [];
					list.forEach(function(row) {
						//console.log(row);
 						//console.log('req:'+ru_idx+',res:'+row.rc_idx);

 						// 채용공고 번호로 비교
 						if (ru_idx == row.rc_idx) {
 							//요청한 공고와 채팅방 공고번호가 일치하면 리스트 표시

 							var $clone = $("#tpl_chat_by_recruite").clone();
 							var $content = $clone.html();

 							$content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
 							$content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
 							$content = $content.replaceAll("{{mm_name}}", row.mm_name);
 							$content = $content.replaceAll("{{ru_week}}", row.ru_week);
 							$content = $content.replaceAll("{{distance}}", row.distance + "km");
 							$content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
 							$content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
 							$content = $content.replaceAll("{{ma_age}}", row.ma_age);
 							$content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
 							$content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
 							$content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
 							$content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
 							$content = $content.replaceAll("{{ru_thumb}}", row.ru_resume_img); // resumebox_ 이미지 표시를 위한 추가
 							$content = $content.replaceAll("{{mm_id}}", row.mm_id);
 							$content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
 							$content = $content.replaceAll("{{target_id}}", row.target_id);
 							$content = $content.replaceAll("{{user_id}}", row.user_id);
							$content = $content.replaceAll("{{target_img}}", row.target_img);
							$content = $content.replaceAll("{{user_img}}", row.user_img);
							$content = $content.replaceAll("{{target_name}}", row.target_name);
							$content = $content.replaceAll("{{user_name}}", row.user_name);
							// 이력서 제목 표시를 위해 ru_name 필드 추가
							$content = $content.replaceAll("{{ru_name}}", row.ru_name || row.target_name || "");

							$content = $content.replaceAll("{{rc_name}}", row.rc_name);
							$content = $content.replaceAll("{{room_idx}}", row.rm_idx); //test row.room_idx 204
							//$content = $content.replaceAll("{{rc_thumb}}",'/resource/uploads/member/58ae42eee368a36e2c2de2694d3d94c3.png'); //row.rc_thumb
							$content = $content.replaceAll("{{target_name}}", row.target_name);
							// 마지막 대화내용 표시 (HTML 태그 제거 및 줄바꿈 처리)
							var lastMsg = (row.last_msg_preview || "").replace(/\n/g, "<br/>");
							$content = $content.replaceAll("{{last_msg_preview}}", lastMsg);
							$content = $content.replaceAll("{{up_dt}}", row.up_dt);

							// getWorkingImg 함수로 처리된 ru_work_img 사용
							var meminfo = row.ru_work_img || "";
							$content = $content.replaceAll("{{meminfo}}", meminfo);

 							//복사한 내용 다시 넣기
 							$clone.html($content);

 							//resumebox_ru_idx  인재목록 추가
 							// 구체적 위치:
 							// - obj: 클릭한 채용공고 항목의 <a> 태그 (tpl_chat 템플릿의 onclick="fShowChatListByRecruite('{{rc_idx}}',this)"에서 전달)
 							// - $(obj).parent(): <a> 태그의 부모인 <li class="list-item"> (채용공고 항목)
 							// - append 위치: <li> 태그 내부의 <a> 태그 바로 다음에 추가됨
 							// 최종 DOM 구조:
 							//   <ol id="chat_list1"> 또는 <ol id="chat_list2">
 							//     <li class="list-item"> (채용공고 항목)
 							//       <a onclick="fShowChatListByRecruite(...)"> (공고 링크)
 							//         <!-- 채용공고 정보 -->
 							//       </a>
 							//       <div class="resumebox_{rc_idx}"> ← 여기에 추가됨
 							//         <li class="list-item msg-item room-item..."> (인재 항목)
 							//           <!-- 인재 정보 -->
 							//         </li>
 							//       </div>
 							//     </li>
 							//   </ol>
 							$(obj).parent().append("<div class='resumebox_" + ru_idx + "'>" + $clone.html() + "</div>");

 						} //if

 					});

 					//hide show 처리
 					/*
       	if($("#chat_list1_1").find("li").length == 0){
            $("#chat_empty1_1").show();
            $("#chat_list1_1").hide();
        }else{
            $("#chat_list1_1").show();
            $("#chat_empty1_1").hide();
        }
				*/

 				} else {
 					commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
 					return;
 				}

 				//안 읽은 글 수 표시
 				//$(obj).find('.content-wrap').data('noreadcnt','0');
 				$('.content-wrap').data('noreadcnt', '0');
 				fSearchNoReadCount();

 			});
 		}
 	</script>
 <?php endif; ?>