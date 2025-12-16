<input type="hidden" name="user_type" value="<?= $user_type ?>" />
<!-- iOS 포함 모든 환경에서 사용자 아이디를 일관되게 참조하기 위한 hidden 필드 -->
<input type="hidden" id="user_id" name="user_id" value="<?= $this->user->getUserId(); ?>" />
<input type="hidden" name="rm_status" value="<?= $room["rm_status"] ?>" />
<input type="hidden" name="recruit_phone" value="<?= $recruit["mm_phone"] ?>" />
<input type="hidden" name="recruit_name" value="<?= $recruit["mm_comp_name"] ?>" />

<!-- 채팅 뷰에서만 angle-link 화살표 제거 및 썸네일 라운드 처리 -->
<style>
    /* angle-link 기본 화살표 제거 (채팅 뷰 전용 강제 덮어쓰기) */
    .page-chat-view .angle-link {
        background: none !important;
        /* 기본 배경 화살표 제거 */
    }

    /* thumb 이미지 라운드 */
    .page-chat-view .thumb img {
        border-radius: 8px;
        overflow: hidden;
    }

    /* 채팅 뷰 전용: recruit 카드 패딩 축소 (오른쪽/아래) */
    .page-chat-view .item-info.recruit.angle-link {
        padding-right: 1.5rem !important;
        padding-bottom: 0.75rem !important;
    }

    /* 메인 CSS 기본 마진 무효화 */
    .page-chat-view .item-info.recruit .content-wrap>* {
        margin: 0 !important;
    }

    /* 썸네일 래퍼 크기 조정 - 110x110px (6.875rem) 정사각형 */
    .page-chat-view .thumb-wrap {
        width: 6.875rem !important;
        height: 7.875rem !important;
        flex-shrink: 0 !important;
    }

    /* thumb 컨테이너 크기 조정 - main.css의 padding 방식 유지 */
    .page-chat-view .thumb {
        width: 6.875rem !important;
        height: 0 !important;
        padding: 112% 0 0 !important;
        position: relative !important;
    }

    /* 이미지 꽉 채우기 - object-fit: cover, main.css의 중앙 정렬 방식 유지 */
    .page-chat-view .thumb img {
        border-radius: 8px !important;
        display: block !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        width: 100% !important;
        height: 100% !important;
        min-height: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
        -webkit-transform: translate(-50%, -50%) !important;
        -ms-transform: translate(-50%, -50%) !important;
        transform: translate(-50%, -50%) !important;
    }




    /* 채팅 상세 패널 내 em 기울임 제거 */
    #chatSummary em,
    #chatSummary .pay .amount {
        font-style: normal !important;
    }

    /* info-table 값 폰트 굵기 제거 */
    .page-chat-view .info-table .value,
    #chatSummary .info-table .value {
        font-weight: normal !important;
    }

    /* 채용정보 라벨/링크 1줄 배치 */
    #chatSummary .title-wrap .label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-bottom: 0.7rem;

    }

    #chatSummary .title-wrap .label-row .line-label {
        margin: 0;
    }

    #chatSummary .title-wrap .label-row .view-all {
        margin: 0;
    }

    /* 배경형 썸네일 (이미지를 background로 표시) */
    .page-chat-view .thumb.thumb-bg {
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }

    .page-chat-view .thumb.thumb-bg a {
        display: block;
        width: 100%;
        height: 100%;
    }


    /* recruit 카드 레이아웃 균형 개선 - 세로축 중앙 정렬 및 간격 */
    .page-chat-view .item-info.recruit {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        gap: 1rem !important;
    }

    /* recruit content-wrap 균형적 가로 배치 */
    .page-chat-view .item-info.recruit .content-wrap {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: space-between !important;
        gap: 0.5rem !important;
        flex: 1 !important;
    }

    /* meta top 영역 균형 배치 */
    .page-chat-view .item-info.recruit .meta.top {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }

    /* category와 working-time 균형 배치 */
    .page-chat-view .item-info.recruit .meta.top .category {
        margin: 0 !important;
        flex-shrink: 0 !important;
    }

    .page-chat-view .item-info.recruit .meta.top .working-time {
        margin: 0 0 0 auto !important;
        flex: 0 0 auto !important;
    }

    /* title 영역 균형 */
    .page-chat-view .item-info.recruit .title {
        margin: 0 !important;
        font-size: 1.3rem !important;
        line-height: 1.4 !important;
    }

    /* meta 영역 완전 정렬 - 수직 중앙 정렬 */
    .page-chat-view .item-info.recruit .meta {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        width: 100% !important;
        justify-content: flex-start !important;
    }

    .page-chat-view .item-info.recruit .meta>p {
        margin: 0 !important;
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }

    .page-chat-view .item-info.recruit .meta>span {
        margin: 0 !important;
        font-size: 0.875rem !important;
    }

    /* 급여 유형(period)을 메타 라인의 오른쪽 끝으로 이동 - 수평 정렬 */
    .page-chat-view .item-info.recruit .meta .period {
        margin-left: auto !important;
        text-align: right !important;
        font-size: 0.875rem !important;
    }

    /* area-pay 영역 균형 배치 */
    .page-chat-view .item-info.recruit .area-pay {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }

    .page-chat-view .item-info.recruit .area-pay .area {
        flex: 1 !important;
        margin: 0 !important;
        font-size: 1rem !important;
        text-align: left !important;
    }

    .page-chat-view .item-info.recruit .area-pay .amount {
        flex-shrink: 0 !important;
        margin: 0 !important;
        font-size: 1rem !important;
        text-align: right !important;
        font-weight: 500 !important;
    }

    /* person 카드 레이아웃 균형 개선 - 세로축 중앙 정렬 및 간격 */
    .page-chat-view .item-info.person {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        gap: 1rem !important;
    }

    /* person 카드 패딩 축소 (오른쪽/아래) */
    .page-chat-view .item-info.person.angle-link {
        padding-right: 1.5rem !important;
        padding-bottom: 0.75rem !important;
    }

    /* person content-wrap 균형적 가로 배치 */
    .page-chat-view .item-info.person .content-wrap {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: space-between !important;
        gap: 0.5rem !important;
        flex: 1 !important;
    }

    /* person 메인 CSS 기본 마진 무효화 */
    .page-chat-view .item-info.person .content-wrap>* {
        margin: 0 !important;
    }

    /* person meta top 영역 균형 배치 */
    .page-chat-view .item-info.person .meta.top {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }

    /* person category와 condition 균형 배치 */
    .page-chat-view .item-info.person .meta.top .category {
        margin: 0 !important;
        flex-shrink: 0 !important;
    }

    .page-chat-view .item-info.person .meta.top .condition {
        margin: 0 0 0 auto !important;
        flex: 0 0 auto !important;
    }

    .page-chat-view .item-info.person .meta.top .condition .working-time {
        margin: 0 !important;
    }

    /* person title 영역 균형 */
    .page-chat-view .item-info.person .title-wrap {
        margin: 0 !important;
    }

    .page-chat-view .item-info.person .title {
        margin: 0 !important;
        font-size: 1.3rem !important;
        line-height: 1.4 !important;
    }

    /* person working-day 요일 아이콘 크기 작고 균형 */
    .page-chat-view .item-info.person .working-day {
        margin: 0 !important;
        display: flex !important;
        gap: 0.25rem !important;
    }

    .page-chat-view .item-info.person .working-day>li {
        width: 24px !important;
        height: 24px !important;
        line-height: 24px !important;
        font-size: 0.75rem !important;
        margin: 0 !important;
    }

    /* person meta 영역 완전 정렬 - 수직 중앙 정렬 */
    .page-chat-view .item-info.person .meta {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        width: 100% !important;
        justify-content: flex-start !important;
    }

    .page-chat-view .item-info.person .meta>p {
        margin: 0 !important;
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }

    .page-chat-view .item-info.person .meta>span {
        margin: 0 !important;
        font-size: 0.875rem !important;
    }

    /* person period 오른쪽 정렬 - 수평 정렬 */
    .page-chat-view .item-info.person .meta .period {
        margin-left: auto !important;
        text-align: right !important;
        font-size: 1rem !important;
    }

    /* 채팅 상단 나가기 버튼 */
    .top-bar--chat {
        display: flex;
        align-items: center;
    }

    .top-bar--chat .title {
        flex: 1;
        max-width: none;
        text-align: left;
        white-space: normal;
        word-break: keep-all;
    }

    .top-bar--chat .btn-exit {
        margin-left: auto;

        display: flex;
        align-items: center;
    }

    .top-bar--chat .btn-exit img {
        width: 1.5rem;
        height: 1.5rem;
        display: block;
    }
</style>

<script>
    // 뒤로/나가기 버튼 대상 페이지 결정
    (function() {
        var params = new URLSearchParams(window.location.search);
        var from = params.get('from') || '';
        var backMap = {
            applied: '/chat/chat_list', // 지원한 공고
            proposed_job: '/chat/chat_list2', // 제안받은 공고
            talent_received: '/chat/index_comp', // 제안받은 인재
            talent_sent: '/chat/chat_list3' // 제안보낸 인재
        };
        var backUrl = backMap[from] || '/chat/chat_list';

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-chat-back').forEach(function(a) {
                a.dataset.backUrl = backUrl;
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    // 나가기 버튼인 경우 채팅방 나가기 처리
                    if (this.classList.contains('btn-exit')) {
                        exitChatRoom(backUrl);
                    } else {
                        // 뒤로가기 버튼은 바로 이동
                        location.href = this.dataset.backUrl || '/chat/chat_list';
                    }
                });
            });
        });

        // 채팅방 나가기 함수
        function exitChatRoom(redirectUrl) {
            var rm_idx = "<?= $rm_idx ?>";
            var g_user_id = ensureValidUserId();

            if (!g_user_id) {
                return;
            }

            if (!rm_idx || rm_idx == "") {
                commonAlert("채팅방 정보가 없습니다.", function() {
                    location.href = redirectUrl || '/chat/chat_list';
                });
                return;
            }

            // 확인 메시지
            if (typeof commonConfirm === "function") {
                commonConfirm("채팅방을 나가시겠습니까?", function() {
                    doExitRoom(rm_idx, g_user_id, redirectUrl);
                });
            } else {
                if (confirm("채팅방을 나가시겠습니까?")) {
                    doExitRoom(rm_idx, g_user_id, redirectUrl);
                }
            }
        }

        // 실제 나가기 처리
        function doExitRoom(rm_idx, g_user_id, redirectUrl) {
            comAjax("/chat_proc/exit_room", {
                rm_idx: rm_idx,
                user_id: g_user_id
            }, function(ret) {
                if (ret.code == "00") {
                    // 시스템 메시지를 소켓으로 전송
                    if (ret.data && ret.data.system_message && typeof socket !== 'undefined' && socket.connected) {
                        var systemMsg = ret.data.system_message;
                        // rm_info가 없으면 기본값 설정
                        if (!rm_info || !rm_info.rm_users) {
                            rm_info = {
                                rm_users: [{
                                    user_id: $('#otherid').val()
                                }, {
                                    user_id: g_user_id
                                }]
                            };
                        }
                        // 소켓으로 시스템 메시지 전송
                        socket.emit("message_insert", systemMsg, rm_info.rm_users);
                    }

                    // 목록 페이지로 이동
                    location.href = redirectUrl || '/chat/chat_list';
                } else {
                    commonAlert(ret.msg || "채팅방 나가기에 실패했습니다.", function() {});
                }
            });
        }
    })();
</script>

<div class="page page-chat-view">
    <div class="top-bar top-bar--chat">
        <!--<a href="javascript:_replace('/chat/chat_list');" class="btn-replace"><span class="sr-text">이전으로</span></a>-->
        <a href="javascript:void(0);" class="btn-replace js-chat-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" id="rm_subject"><?= !empty($recruit["mm_comp_name"]) ? $recruit["mm_comp_name"] : "1:1채팅" ?></h1>
        <a href="javascript:void(0);" class="btn-exit js-chat-back" aria-label="채팅방 나가기">
            <img src="/resource/shop/mobile/img/chat/rm_exit_btn.svg" alt="채팅방 나가기">
        </a>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="chat-view-header">
            <!-- 채용정보 -->
            <?php if ($user_type == "COMP") : ?>
                <!-- 채용정보 -->
                <input type="hidden" id="otherid" value="<?= $resume["mm_id"] ?>">
                <div class="item-info person angle-link">
                    <div class="thumb-wrap">
                        <!-- 즐겨찾기 버튼 -->
                        <button type="button" data-bk_type="RESUME" data-bk_key="<?= $resume["ru_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $resume["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                        <!-- 썸네일: 배경이미지 + 폴백 -->
                        <?php
                        // getResumeImg() 함수가 이미 성별에 따른 기본 아바타를 반환함
                        $resumeThumb = getResumeImg($resume);

                        // 기본 아바타 경로 설정 (이미지 로드 실패 시 사용)
                        $mmSex = isset($resume["mm_sex"]) ? trim($resume["mm_sex"]) : "";
                        $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png"; // 기본값은 여성 아바타
                        if ($mmSex === "남") {
                            $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
                        } else if ($mmSex === "여") {
                            $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                        }
                        ?>
                        <div class="thumb thumb-bg" style="background-image:url('<?= $resumeThumb ?>');" data-default-img="<?= $defaultAvatar ?>">
                            <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" aria-label="이력서 보기"></a>
                        </div>
                        <!-- 추천 배지 -->
                        <p class="recomm-badge"><span>추천</span></p>

                        <!-- <p class="star-rating"> -->
                        <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                        <!-- <span class="star" style="width:100%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p> -->
                        <!-- 공고보기 -->
                        <!-- <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" class="btn-line">인재정보</a> -->
                    </div>
                    <div class="content-wrap">
                        <div class="meta top">
                            <!-- 직종라벨 -->
                            <p class="category"><?= getWorkingImg($resume["ru_work"]) ?></p>
                            <!-- 조건 -->
                            <p class="condition">
                                <span class="working-time"><?= (strpos($resume["ru_work_type"], ',') !== false) ? str_replace(',', '|', $resume["ru_work_type"]) : $resume["ru_work_type"] ?></span>
                            </p>
                        </div>
                        <div class="title-wrap">
                            <!-- 이력서 제목 -->
                            <p class="title"><strong><a href="/resume/hr_post/<?= $resume["ru_idx"] ?>"><?= !empty($resume["ru_name"]) ? $resume["ru_name"] : $resume["mm_name"] ?></a></strong></p>
                        </div>
                        <ul class="working-day">
                            <?php
                            $ru_week = isset($resume["ru_week"]) ? trim($resume["ru_week"]) : "";
                            $week_display = ($ru_week !== "") ? $ru_week : "월,화,수,목,금,토,일";
                            echo getWeekYoil($week_display);
                            ?>
                        </ul>
                        <!-- 추가사항 -->
                        <div class="meta">
                            <p><?= $resume["mm_nation"] ?></p>
                            <span>|</span>
                            <p><?= $resume["ma_age"] ?>세</p>
                            <?php
                            // 주소에서 '시'가 포함된 토큰을 찾고, 없으면 두 번째 토큰 사용
                            $city_name = "";
                            if (!empty($resume["ad_addr"])) {
                                $parts = preg_split('/\s+/', trim($resume["ad_addr"]));
                                // '시'가 포함된 첫 토큰을 우선 사용
                                foreach ($parts as $p) {
                                    if (mb_strpos($p, "시") !== false) {
                                        $city_name = $p;
                                        break;
                                    }
                                }
                                // '시'가 없으면 두 번째 토큰을 사용 (인덱스 1)
                                if ($city_name === "" && count($parts) >= 2) {
                                    $city_name = $parts[1];
                                }
                            }
                            if ($city_name !== "") : ?>
                                <span>|</span>
                                <p><?= $city_name ?></p>
                            <?php endif; ?>
                            <?php if (!empty($resume["ru_price_type"])) : ?>
                                <p class="period" style="margin-left: auto;"><?= (strpos($resume["ru_price_type"], ',') !== false) ? str_replace(',', '|', $resume["ru_price_type"]) : $resume["ru_price_type"] ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- 해시태그 -->
                        <!-- <div class="hashtag">
                            <?= $resume["ru_my_type"] ?>
                        </div> -->
                    </div>
                    <!-- 인재정보 보기 -->
                    <a href="#chatSummary" role="button" aria-haspopup="dialog" aria-controls="chatSummary" class="btn-wrapper js-toggle-panel"><span class="sr-text">인재정보보기</span></a>
                </div>
                <!-- //채용정보 -->

                <div id="comp_top_2" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt1"><?= substr($room["rm_itv_dt"], 0, 4) ?>년 <?= substr($room["rm_itv_dt"], 5, 2) ?>월 <?= substr($room["rm_itv_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntInterviewCompCancel">면접취소</button>
                        </div>
                    </div>
                </div>

                <div id="comp_top_3" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt2"><?= substr($room["rm_rec_dt"], 0, 4) ?>년 <?= substr($room["rm_rec_dt"], 5, 2) ?>월 <?= substr($room["rm_rec_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntRecruitCompCancel">채용취소</button>
                        </div>
                    </div>
                </div>

                <!-- 일정 -->
                <!-- 사용 안 함 - 주석처리 시작 -->
                <!--
                <div id="comp_top_1" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="add-new">
                            <p class="title">* 면접일을 확정하셔야 연락처가 공유됩니다.</p>
                            <div class="btn-wrap">
                                <button type="button" aria-expanded="false" aria-controls="interviewForm" id="interviewShow" class="btn-round btn-primary js-toggle-form">면접일 확정</button>
                                <button type="button" aria-expanded="false" aria-controls="startDateForm" id="dateShow" class="btn-round btn-light js-toggle-form">채용확정</button>
                            </div>
                        </div>
                        <div id="interviewForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <p class="form-label align-center">면접일을 입력해주세요.</p>
                                    <div class="calendar">
                                        <div class="js-datepicker"></div>
                                        <label>
                                            <span class="sr-text">날짜 선택</span>
                                            <input type="text" name="interviewDate" readonly aria-required="true" required class="form-text js-date-value">
                                        </label>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="reset" aria-expanded="false" aria-controls="interviewForm" class="btn-round btn-secondary js-toggle-form">취소</button>
                                        <button type="button" class="btn-round btn-tertiary" id="btnInerview">확인</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="startDateForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <p class="form-label align-center">근로일을 입력해주세요.</p>
                                    <div class="calendar">
                                        <div class="js-datepicker"></div>
                                        <label>
                                            <span class="sr-text">날짜 선택</span>
                                            <input type="text" name="startDate" readonly aria-required="true" required class="form-text js-date-value">
                                        </label>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="reset" aria-expanded="false" aria-controls="startDateForm" class="btn-round btn-secondary js-toggle-form">취소</button>
                                        <button type="button" class="btn-round btn-tertiary" id="btnStartWrok">확인</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>
                            (function() {
                                var calendars = [];
                                document.querySelectorAll('.js-toggle-form').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        var id = this.getAttribute('aria-controls'),
                                            isOpen = this.getAttribute('aria-expanded'),
                                            formCon = null;
                                        if (!id || !document.getElementById(id)) return;
                                        formCon = document.getElementById(id);
                                        if (isOpen == 'false') {
                                            document.querySelectorAll('.appointment-form').forEach(function(el, idx) {
                                                el.hidden = true;
                                                el.querySelector('.js-date-value').value = '';
                                                if (calendars[idx]) {
                                                    calendars[idx].clear();
                                                }
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
                                function chgMonth(instance) {
                                    var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                        month = Number(instance.currentMonth) + 1,
                                        year = instance.currentYear;
                                    $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                                }
                                window.addEventListener('DOMContentLoaded', function() {
                                    if (typeof flatpickr == 'undefined') return false;
                                    calendars = flatpickr('.js-datepicker', {
                                        inline: true,
                                        locale: 'ko',
                                        dateFormat: 'Y-m-d',
                                        ariaDateFormat: 'Y-m-d',
                                        altInput: true,
                                        nextArrow: '<span class="sr-text">다음</span>',
                                        prevArrow: '<span class="sr-text">이전</span>',
                                        disableMobile: true,
                                        onChange: function(selectedDates, dateStr, instance) {
                                            if (!dateStr) return;
                                            var dayTxt = new Date(selectedDates).getDay();
                                            var dayArr = ['일', '월', '화', '수', '목', '금', '토'];
                                            instance.element.parentNode.querySelector('.js-date-value').value = dateStr + '(' + dayArr[dayTxt] + ')';
                                        },
                                        onMonthChange: function(selectedDates, dateStr, instance) {
                                            chgMonth(this);
                                        },
                                        onDayCreate: function(dObj, dStr, fp, dayElem) {
                                            var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                                            if (day == 0) {
                                                dayElem.classList.add('sun');
                                            } else if (day == 6) {
                                                dayElem.classList.add('sat');
                                            }
                                        },
                                        onReady: function() {
                                            chgMonth(this);
                                        }
                                    });
                                });
                            }());
                        </script>
                    </div>
                </div>
                -->
                <!-- 사용 안 함 - 주석처리 끝 -->
                <!-- //일정 -->
            <?php else : ?>
                <input type="hidden" id="otherid" value="<?= $recruit["mm_id"] ?>">
                <div class="item-info recruit angle-link">
                    <div class="thumb-wrap">
                        <!-- 썸네일 -->
                        <?php
                        // getRecruitImg() 함수가 이미 기본 이미지를 반환함
                        $recruitThumb = getRecruitImg($recruit);
                        $defaultRecruitImg = "/resource/shop/mobile/img/common/noimage4.png";
                        ?>
                        <div class="thumb thumb-bg" style="background-image:url('<?= $recruitThumb ?>');" data-default-img="<?= $defaultRecruitImg ?>">
                            <a href="/recruit/job_post/<?= $recruit["ru_idx"] ?>" aria-label="채용공고 보기"></a>
                        </div>
                        <!-- <a href="/recruit/job_post/<?= $recruit["ru_idx"] ?>" class="btn-line">지원한공고</a> -->
                    </div>
                    <div class="content-wrap">
                        <div class="meta top">
                            <!-- 직종라벨 -->
                            <p class="category">
                                <?= getWorkingImg($recruit["rc_work"]) ?>
                            </p>
                            <!-- 근무시간 -->
                            <p class="working-time"><?= getWorkType($recruit["rc_work_type"]) ?></p>
                        </div>
                        <!-- 제목 -->
                        <!-- 주요정보패널 링크 -->
                        <p class="title"><strong><a href="/recruit/job_post/<?= $recruit["rc_idx"] ?>"><?= $recruit["rc_name"] ?></a></strong></p>
                        <!-- 추가조건 -->
                        <div class="meta">
                            <p><?= $recruit["rc_nation_nm"] ?></p>
                            <span>/</span>
                            <p><?= $recruit["rc_age_nm"] ?></p>
                            <p class="period"><?= getPriceType($recruit["rc_price_type"]) ?></p>
                        </div>
                        <div class="area-pay">
                            <p class="area js-chat-addr" data-addr="<?= htmlspecialchars($recruit["ad_addr"], ENT_QUOTES) ?>"></p>
                            <p class="amount"><?= getRecuitPrice($recruit) ?></p>
                        </div>
                    </div>
                    <!-- 주요정보패널 보기 -->
                    <a href="#chatSummary" role="button" aria-haspopup="dialog" aria-controls="chatSummary" class="btn-wrapper js-toggle-panel"><span class="sr-text">주요정보보기</span></a>
                </div>
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
                <div class="container" id="chat_list">

                </div>
            </div>
            <!-- 입력폼 -->
            <div class="write-form">
                <label>
                    <span class="sr-text">메세지입력</span>
                    <input type="text" id="chatTxt" name="chatTxt" autocorrect="off" autocapitalize="off" autocomplete="off" style="text-align:left;" placeholder="메세지를 입력해주세요.">
                </label>
                <button type="button" id="btnSend" class="btn-primary">전송</button>
            </div>
        </div>

    </div><!-- //page-content -->
</div>

<style>
    /* 채팅 상세 패널 헤더 패딩 제거 */
    #chatSummary .panel-header {
        padding: 1rem 0 0;
    }

    /* secondary-title 왼쪽에 프로필 이미지 표시 (인재정보 패널) */
    #chatSummary .item-info-summary .title-wrap .secondary-title {
        display: flex;
        align-items: center;
        min-height: 2.5rem;
    }

    /* person-meta 패딩 제거 및 수평 정렬 */
    #chatSummary .person-meta {
        padding: 0;
        margin: 0;
        margin-bottom: -1.5rem;
        align-items: center;
    }

    /* person-meta 내부 요소 수평 정렬 */
    #chatSummary .person-meta p {
        display: inline-flex;
        align-items: center;
        margin: 0;
        vertical-align: middle;
    }

    /* ru_work 아이콘과 다음 요소 사이 간격 (이미지를 포함하는 p 태그에만) */
    #chatSummary .person-meta p:has(img),
    #chatSummary .person-meta p:has(svg) {
        margin-right: 0.5rem;
    }

    #chatSummary .person-meta span {
        display: inline-flex;
        align-items: center;
        margin: 0;
        vertical-align: middle;
    }

    /* 인재정보 패널 폰트 크기 증가 */
    #chatSummary .item-info-summary .secondary-title {
        font-size: 1.5rem;
    }

    #chatSummary .item-info-summary .person-meta {
        font-size: 1rem;
    }

    #chatSummary .item-info-summary .info-table .label {
        font-size: 1rem;
    }

    #chatSummary .item-info-summary .info-table .value {
        font-size: 1.125rem;
    }

    #chatSummary .item-info-summary .info-table .summary {
        font-size: 1.125rem;
    }

    /* 자격증이 1개일 때 | 구분자 숨기기 */
    #chatSummary .period-list.single-item span:first-child:after {
        content: none;
    }
</style>

<?php if ($user_type == "COMP") : ?>
    <div id="chatSummary" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <h2 class="sr-text">인재정보</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">

                    <div class="item-info-summary">
                        <div class="title-wrap">
                            <div class="label-row">
                                <p class="line-label">인재정보</p>
                                <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" class="view-all">상세보기</a>
                            </div>
                            <!-- 제목: 이력서 제목 우선, 없으면 이름 표시 -->
                            <h3 class="secondary-title">
                                <?php
                                // getResumeImg() 함수가 이미 성별에 따른 기본 아바타를 반환함
                                $resumeImg = getResumeImg($resume);

                                // onerror 핸들러용 기본 아바타 경로 설정
                                $mmSex = isset($resume["mm_sex"]) ? trim($resume["mm_sex"]) : "";
                                $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png"; // 기본값은 여성 아바타
                                if ($mmSex === "남") {
                                    $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
                                } else if ($mmSex === "여") {
                                    $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                                }
                                ?>
                                <img src="<?= $resumeImg ?>" alt="프로필" style="width: 2.5rem; height: 2.5rem; object-fit: cover; border-radius: 50%; margin-right: 0.7rem; vertical-align: middle;" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='<?= $defaultAvatar ?>';}">
                                <?= !empty($resume["ru_name"]) ? $resume["ru_name"] : $resume["mm_name"] ?>
                            </h3>
                            <!-- 모집기간 -->
                            <div class="person-meta">
                                <?php if (!empty($resume["ru_work"])) : ?>
                                    <p><?= getWorkingImg($resume["ru_work"]) ?></p>
                                <?php endif; ?>
                                <p><?= $resume["mm_nation"] ?></p>&nbsp;
                                <span>/</span>
                                &nbsp;<p><?= $resume["ma_age"] ?>세</p>
                            </div>
                        </div>
                        <table class="info-table">
                            <caption><span class="sr-text">구인정보</span></caption>
                            <colgroup>
                                <col>
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td class="label">급여형태</td>
                                    <td class="value" style="color: #1B9D7D"><?= !empty($resume["ru_price_type"]) ? ((strpos($resume["ru_price_type"], ',') !== false) ? str_replace(',', '|', $resume["ru_price_type"]) : $resume["ru_price_type"]) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="label">근무형태</td>
                                    <td class="value" style="color: #1B9D7D"><?= $resume["ru_work_type"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">근무요일</td>
                                    <td class="value">
                                        <?php
                                        // 요일무관 체크 여부 확인
                                        $isWeekUnlimited = (isset($resume["ru_week_yn"]) && $resume["ru_week_yn"] === "Y");
                                        // 저장된 요일 값 확인
                                        $ru_week = isset($resume["ru_week"]) ? trim($resume["ru_week"]) : "";
                                        $week_display = ($ru_week !== "") ? $ru_week : "월,화,수,목,금";
                                        ?>
                                        <?php if ($isWeekUnlimited) : ?>
                                            무관
                                        <?php else : ?>
                                            <ul class="working-day">
                                                <?= getWeekYoil($week_display) ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">자기소개</td>
                                    <td class="value">
                                        <div class="summary"><?= !empty($resume["ru_desc"]) ? str_replace("\n", "<br/>", $resume["ru_desc"]) : '' ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">자격증</td>
                                    <td class="value">
                                        <ul class="period-list <?= count($certi_list) == 1 ? 'single-item' : '' ?>">
                                            <?php foreach ($certi_list as $row) : ?>
                                                <li>
                                                    <span><?= $row["rc_name"] ?></span>
                                                    <em><?= $row["rc_dt"] ?></em>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom">
                    <button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //상세 정보 패널 -->
<?php else : ?>
    <!-- 상세정보 패널 -->
    <div id="chatSummary" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <h2 class="sr-text">채용주요정보</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">

                    <div class="item-info-summary">
                        <div class="title-wrap">
                            <div class="label-row">
                                <p class="line-label">채용정보</p>
                                <a href="/recruit/job_post/<?= $recruit["rc_idx"] ?>" class="view-all">상세보기</a>
                            </div>
                            <!-- 제목 -->
                            <h3 class="secondary-title"><?= $recruit["rc_name"] ?></h3>
                            <!-- 모집기간 -->
                            <p class="recruit-period">
                                <span>모집기간</span>
                                <em>
                                    <?php
                                    $rc_sdate_fmt = (!empty($recruit["rc_sdate"])) ? date("Y-m-d", strtotime($recruit["rc_sdate"])) : "";
                                    $rc_edate_fmt = (!empty($recruit["rc_edate"])) ? date("Y-m-d", strtotime($recruit["rc_edate"])) : "";
                                    ?>
                                    <?= $rc_sdate_fmt ?> ~ <?= $rc_edate_fmt ?>
                                </em>
                            </p>
                        </div>
                        <table class="info-table">
                            <caption><span class="sr-text">구인정보</span></caption>
                            <colgroup>
                                <col>
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td class="label">업무구분</td>
                                    <td class="value">
                                        <!-- 직종라벨 -->
                                        <?= getWorkingImg($recruit["rc_work"]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">급&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;여</td>
                                    <td class="value">
                                        <?php
                                        // 급여 타입: 원본 문자열을 콤마로 나눠 모두 표시 (시급,주급 동시 노출)
                                        $priceTypeRaw = isset($recruit["rc_price_type"]) ? $recruit["rc_price_type"] : "";
                                        $priceType = "";
                                        if (!empty($priceTypeRaw)) {
                                            $priceTypes = array_filter(array_map('trim', explode(',', $priceTypeRaw)));
                                            if (!empty($priceTypes)) {
                                                $priceType = implode(' | ', array_map(function ($t) {
                                                    return "<em>" . htmlspecialchars($t, ENT_QUOTES) . "</em>";
                                                }, $priceTypes));
                                            }
                                        }
                                        ?>
                                        <span class="pay">
                                            <em class="amount"><?= getRecuitPrice($recruit) ?></em>
                                            <span><?= $priceType ?></span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">근무형태</td>
                                    <td class="value"><span class="working-time" style="color:#1B9D7D;"><?= getWorkType($recruit["rc_work_type"]) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="label">근무시간</td>
                                    <td class="value">
                                        <?php if (array_search("주간", explode(",", $recruit["rc_work_type"])) !== false) : ?>
                                            <span class="time"><?= $recruit["rc_am_stime"] ?>~<?= $recruit["rc_am_etime"] ?></span>
                                        <?php elseif (array_search("야간", explode(",", $recruit["rc_work_type"])) !== false) : ?>
                                            <span class="time"><?= $recruit["rc_pm_stime"] ?>~<?= $recruit["rc_pm_etime"] ?></span>
                                        <?php elseif (array_search("2교대", explode(",", $recruit["rc_work_type"])) !== false) : ?>
                                            <span class="time"><?= $recruit["rc_am_stime"] ?>~<?= $recruit["rc_am_etime"] ?></span>
                                            <span class="time"><?= $recruit["rc_pm_stime"] ?>~<?= $recruit["rc_pm_etime"] ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">희망성별</td>
                                    <td class="value">
                                        <?php
                                        // 값이 없으면 기본으로 "무관" 표시
                                        $rc_sex = isset($recruit["rc_sex"]) ? trim($recruit["rc_sex"]) : "";
                                        echo $rc_sex !== "" ? $rc_sex : "무관";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">희망국적</td>
                                    <td class="value"><?= $recruit["rc_nation_nm"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">희망나이</td>
                                    <td class="value">
                                        <?php
                                        // 나이 범위를 "20~55세" 형태로 표시, 값이 없으면 "무관"
                                        $startAge = isset($recruit["rc_sage"]) ? (int) $recruit["rc_sage"] : 0;
                                        $endAge = isset($recruit["rc_eage"]) ? (int) $recruit["rc_eage"] : 0;
                                        if ($startAge === 0 && $endAge === 0) {
                                            echo "무관";
                                        } elseif ($startAge !== 0 && $endAge !== 0) {
                                            echo $startAge . "~" . $endAge . "세";
                                        } elseif ($startAge !== 0) {
                                            echo $startAge . "세 이상";
                                        } else {
                                            echo $endAge . "세 이하";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">근무요일</td>
                                    <td class="value">
                                        <ul class="working-day">
                                            <?= getWeekYoil($recruit["rc_week"]) ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom">
                    <button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //상세정보 패널 -->
<?php endif; ?>


<script>
    // background-image 이미지 로드 실패 시 기본 이미지로 교체
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            // thumb-bg 클래스를 가진 모든 요소에 대해 이미지 로드 확인
            document.querySelectorAll('.thumb.thumb-bg').forEach(function(thumb) {
                var bgImage = thumb.style.backgroundImage;
                if (!bgImage) return;

                // background-image URL 추출
                var imageUrl = bgImage.replace(/url\(['"]?([^'"]+)['"]?\)/, '$1');
                var defaultImg = thumb.getAttribute('data-default-img');

                if (!imageUrl || !defaultImg) return;

                // 이미지 로드 테스트
                var img = new Image();
                img.onerror = function() {
                    // 이미지 로드 실패 시 기본 이미지로 교체
                    thumb.style.backgroundImage = "url('" + defaultImg + "')";
                };
                img.src = imageUrl;
            });
        });
    })();

    var last_send_dt = "";
    var rm_idx = "<?= $rm_idx ?>";
    var rm_info = {};
    var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;
    var userIdAlertShown = false;
    // 주소 정리: 채팅 헤더에 표시되는 채용지 주소를 공용 헬퍼로 축약
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-chat-addr').forEach(function(el) {
            var addr = el.dataset.addr || '';
            el.textContent = fRemoveBigAdrJS(addr);
        });
    });

    //대화내용 중복 차단 내역 초기화
    let processedIdx = [];

    // 채팅방 진입 시 processedIdx 초기화
    function resetProcessedIdx() {
        processedIdx = [];
        console.log('processedIdx 배열 초기화됨');
    }

    // 공통 날짜/시간 유틸 ---------------------------------------------------------------------------------

    // getCurrentDate 함수 정의 (iOS/Android 공통 사용)
    function getCurrentDate() {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth();
        var today = date.getDate();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var milliseconds = date.getMilliseconds();
        return new Date(year, month, today, hours, minutes, seconds, milliseconds);
    }

    // getFormatDate 함수 정의 (iOS/Android 공통 사용)
    function getFormatDate(date, format) {
        if (date == undefined) {
            return "";
        }
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        if (format == 'kor') {
            return year + "년 " + month + "월 " + day + "일";
        } else if (format == 'ymd') {
            return year + "-" + (month < 10 ? "0" + month : month) + "-" + (day < 10 ? "0" + day : day);
        } else if (format == 'hh:mm') {
            return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes);
        }
        return "";
    }

    // 시:분을 오전/오후 형식으로 반환
    function getAmPmTime(date) {
        if (!date) return "";
        var d = new Date(date);
        var hours = d.getHours();
        var minutes = d.getMinutes();
        var period = hours < 12 ? "오전" : "오후";
        var displayHour = hours % 12 || 12;
        var mm = minutes < 10 ? "0" + minutes : minutes;
        return period + " " + displayHour + ":" + mm;
    }

    // 모든 환경에서 user_id 누락 여부를 확인하는 공통 함수 -------------------------------------------
    function ensureValidUserId() {
        var currentUserId = ($("#user_id").val() || "").trim();
        if (currentUserId === "") {
            console.error("user_id가 비어 있어 채팅을 진행할 수 없습니다.");
            if (!userIdAlertShown) {
                userIdAlertShown = true;
                if (typeof commonAlert === "function") {
                    commonAlert("로그인 정보가 없습니다. 다시 로그인해주세요.", function() {
                        location.href = '/member/login';
                    });
                } else {
                    alert("로그인 정보가 없습니다. 다시 로그인해주세요.");
                    location.href = '/member/login';
                }
            }
            return "";
        }
        return currentUserId;
    }


    if (rm_idx == null || rm_idx == "") {
        commonAlert("잘못된 접근입니다.", function() {
            location.href = "/chat/chat_list";
        });
    }

    $("#chatTxt").keyup(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            send_message();
        }
    });
</script>
<!-- ===== 앱(WebView) 전용 스크립트 : 안드로이드 WebView에서만 실행 ===== -->
<?php if ($this->common->getSession("os_type") == "android") : ?>
    <script>
        console.log('android');
        var g_user_id = "";
        var first_yn = "N";

        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = ensureValidUserId();
            if (!g_user_id) {
                return;
            }

            // 안드로이드에서도 소켓을 사용하여 룸 정보 조회
            socket.emit("room_join", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            socket.emit("room_info", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });

            // 메시지 리스트도 바로 조회
            var params = {
                rm_idx: Number(rm_idx),
                skip: 0,
                limit: 1000
            };
            socket.emit("message_list", params);
        });
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_info_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_info_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //방생성 확인 및 방번호 확인
        function room_info_return(data) {
            var ret = JSON.parse(data);
            if (ret.code != "00") {
                alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
            } else {
                //채팅방 정보
                rm_info = ret.data;

                //$("#rm_subject").html(getRoomSubject(rm_info,g_user_id));

                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length
                };

                // 안드로이드에서도 소켓을 사용하여 메시지 리스트 조회
                socket.emit("message_list", params);
            }
        }

        // 안드로이드에서도 소켓 이벤트 리스너 추가 (중복 제거)
        // socket.on("room_info_return", function(ret) {
        //     console.log('안드로이드 room_info_return:', ret);
        //     if (ret.code != "00") {
        //         alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
        //     } else {
        //         //채팅방 정보
        //         rm_info = ret.data;

        //         //메시지 리스트 조회
        //         var params = {
        //             rm_idx: Number(rm_idx),
        //             skip: rm_start,
        //             limit: rm_length
        //         };
        //         socket.emit("message_list", params);
        //     }
        // });

        //메시지 리스트 리턴 처리 (안드로이드/웹/iOS 공통)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_list_return");
        socket.on("message_list_return", function(ret) {
            console.log('메시지 리스트 수신:', ret);

            // 메시지 로딩 시 중복 방지 배열 초기화 (새로운 메시지 로딩이므로)
            if (pagging_yn == false) {
                resetProcessedIdx();
            }

            //메시지 리스트 가져오기
            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt].rm_messages, true);
            }
            if (pagging_yn == false) {
                scrollBottom();
            }
            pagging_yn = false;
        });

        //메시지 삽입 리턴 처리 (안드로이드/웹/iOS 공통 핸들러)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_insert_return");
        socket.on("message_insert_return", function(ret) {
            console.log('=== 서버에서 메시지 수신 ===');
            console.log('message_insert_return:', ret);

            if (ret.code != "00") {
                console.warn('메시지 전송 실패:', ret.msg);
                // 안드로이드에서만 alert 표시 (안드로이드 특수 로직)
                if (typeof APP_NOWAPK !== 'undefined') {
                    alert('메시지 전송에 실패했습니다: ' + ret.msg);
                }
                // 실패 시 임시 메시지 제거
                $("[data-temp-message='true']").remove();
                return;
            }

            // 🔥 실시간 채팅: 보내는 사람과 받는 사람 모두 처리
            var message_list = ret.data || [];
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                var received_message = message_list[icnt];
                var is_my_message = (received_message.user_id == g_user_id);

                console.log('🔥 [실시간] 수신된 메시지:', received_message);
                console.log('🔥 [실시간] 내 메시지 여부:', is_my_message);

                if (is_my_message) {
                    // 🔥 내가 보낸 메시지: 임시 메시지를 실제 메시지로 교체
                    var temp_selector = "[data-temp-message='true']";
                    var $temp_msg = $(temp_selector).last();

                    if ($temp_msg.length > 0) {
                        console.log('🔥 [실시간] 임시 메시지 교체');
                        // 임시 메시지 제거
                        $temp_msg.remove();
                    }

                    // 실제 메시지 표시 (중복 방지 체크)
                    if (!processedIdx.includes(received_message.mg_idx)) {
                        showMessage(received_message, false);
                    }
                } else {
                    // 🔥 상대방이 보낸 메시지: 새로 표시 (중복 방지 체크)
                    if (!processedIdx.includes(received_message.mg_idx)) {
                        showMessage(received_message, false);
                    }
                }

                //읽음 처리
                var params = {
                    rm_idx: Number(rm_idx),
                    mg_idx: Number(received_message.mg_idx),
                    user_id: g_user_id
                };
                socket.emit("message_read_return", params);
            }
            scrollBottom();
        });
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_join_alarm") 이벤트 핸들러를 사용하지 않음
         * 대신 socket.on("room_join_return")에서 처리함
         * 
         * @deprecated 사용되지 않음
         */
        //방접속시 알림
        function room_join_alarm(data) {
            $(".readcount").html("");
        }

        //방 조인 리턴 처리
        socket.on("room_join_return", function(ret) {
            console.log('안드로이드 room_join_return:', ret);
            $(".readcount").html("");
        });

        var pagging_yn = false;
        $("#chat_body").scroll(function() {
            var scroll_top = $(this).scrollTop();
            if ($(this).scrollTop() < 300 && pagging_yn == false) {
                pagging_yn = true;

                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length
                };
                // 안드로이드에서도 소켓을 사용하여 메시지 리스트 조회
                socket.emit("message_list", params);
            }
        });

        $("#btnSend").click(function() {
            send_message();
        });

        /////////////////////////////////////////////////////////
        // 리턴 처리
        /////////////////////////////////////////////////////////
        //alert(1);
        function send_message() {
            var txt = $("#chatTxt").val();
            if (txt == "") {
                return;
            }
            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            // 채팅 메시지 전송 시작

            console.log('=== 채팅 메시지 전송 시작 ===');
            console.log('안드로이드 send_message 호출됨');
            console.log('rm_idx:', rm_idx);
            console.log('g_user_id:', g_user_id);
            console.log('rm_info:', rm_info);
            console.log('메시지 내용:', txt);

            // FCM 토큰 상태 확인
            // 전송 로직에 사용되지 않음. (디버깅용만)
            if (typeof APP_NOWAPK !== 'undefined') {
                console.log('APP_NOWAPK 객체 존재');
                if (typeof APP_NOWAPK.get_fcm_token === 'function') {
                    var fcm_token = APP_NOWAPK.get_fcm_token();
                    console.log('FCM 토큰 상태:', fcm_token ? '등록됨' : '미등록');
                } else {
                    console.log('FCM 토큰 함수 없음');
                }
            } else {
                console.log('APP_NOWAPK 객체 없음');
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

            // rm_info가 없으면 기본값으로 설정
            if (!rm_info || !rm_info.rm_users) {
                console.log('rm_info가 없어서 기본값 설정');
                rm_info = {
                    rm_users: [{
                        user_id: $('#otherid').val()
                    }, {
                        user_id: g_user_id
                    }]
                };
            }

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

            console.log('전송할 메시지:', message);

            // 소켓 연결 상태 확인
            if (socket.connected) {
                console.log('소켓 연결 상태: 연결됨');
            } else {
                console.log('소켓 연결 상태: 끊어짐');
            }

            // 🔥 실시간 채팅: 메시지 전송 전 즉시 화면에 표시 (Optimistic Update)
            // 임시 mg_idx 생성 (서버 응답으로 실제 mg_idx로 교체됨)
            message.mg_idx = 'temp_' + Date.now();
            message.temp_yn = true; // 임시 메시지 플래그

            console.log('🔥 [실시간] 메시지 즉시 표시:', message);
            showMessage(message, false);
            scrollBottom();

            //채팅방 실시간 쏘기 - Socket.IO만 사용
            console.log('소켓 메시지 전송 시도');
            socket.emit("message_insert", message, rm_info.rm_users);
            console.log('소켓 메시지 전송 완료');

            //채팅창 클리어
            $("#chatTxt").val(""); //.focus();
            console.log('=== 채팅 메시지 전송 완료 ===');
        }
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_insert_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_insert_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //메시지 정보 리턴
        function message_insert_return(data) {
            var ret = JSON.parse(data);

            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt], false);
                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    mg_idx: Number(message_list[icnt].mg_idx),
                    user_id: g_user_id
                };
                APP_NOWAPK.message_read_return(JSON.stringify(params));
            }
            scrollBottom();
        }

        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_read_count_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_read_count_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        function message_read_count_return(data) {
            var ret = JSON.parse(data);

            //리턴받은 카운트 메시지 정보 업데이트
            $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + ret.data.mg_idx)
            if (ret.data.count == 0) {
                $("[id^=mg_idx_]").last().find(".readcount").html("");
            } else {

                $("[id^=mg_idx_]").last().find(".readcount").html(ret.data.count);
            }
        }

        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_list_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_list_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //방생성 확인 및 방번호 확인
        function message_list_return(data) {
            //alert('message_list_return');

            var ret = JSON.parse(data);

            //메시지 리스트 가져오기
            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt].rm_messages, true);
            }
            if (pagging_yn == false) {
                scrollBottom();
            }
            pagging_yn = false;
        }
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_join_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_join_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //방 조인
        function room_join_return(data) {
            //alert('join_return:685');
            $(".readcount").html("");
        }
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_leave_alarm") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_leave_alarm()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //접속 끊길때 방 정리
        function room_leave_alarm(ret) {
            alert("접속이 끊겼습니다.");
            socket.emit("room_leave", {
                rm_idx: Number(rm_idx)
            });
        }
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_leave_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_leave_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         */
        //방해제 처리
        function room_leave_return(ret) {
            alert("방에 접속하셨습니다.");
        }


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

            //상태 변경 처리
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

            //상태 변경 처리
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

            //상태 변경 처리
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

            //상태 변경 처리
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
            $("#comp_top_1").show();

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

            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

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
                APP_NOWAPK.message_insert(JSON.stringify(message), JSON.stringify(rm_info.rm_users));
            });
        }

        //메시지 출력 처리
        var user_type = $("[name='user_type']").val();

        function showMessage(info, is_prepend) {
            // 🔥 임시 메시지는 중복 체크 제외 (서버 응답으로 교체됨)
            if (!info.temp_yn && processedIdx.includes(info.mg_idx)) {
                console.log('🔥 [showMessage] 중복 메시지 무시:', info.mg_idx);
                return; // Exit if the idx is already in the array
            }

            // 임시 메시지가 아니면 processedIdx에 추가
            if (!info.temp_yn) {
                processedIdx.push(info.mg_idx);
            }

            //유형에 따른 처리
            if (info.mg_type == "timeline") {
                var content = "<p class='chat-divider'>";
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
                        // 🔥 임시 메시지인 경우 data-temp-message 속성 추가
                        var temp_attr = info.temp_yn ? " data-temp-message='true'" : "";
                        var content = "<div class='msg-item chat-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "'" + temp_attr + ">";
                        content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                        if (readcount > 0) {
                            content += "    <div class='readcount'>" + readcount + "</div>";
                        } else {
                            content += "    <div class='readcount'></div>";
                        }
                        content += "    </div>";
                        content += "    <div class='meta'>";
                        content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                        content += "    </div>";
                        content += "</div>";
                    } else {
                        var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
                        content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />") + "</div>";
                        content += "    <div class='meta'>";
                        content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                        content += "    </div>";
                        content += "</div>";
                    }
                } else if (info.mg_type == "file") {

                } else if (info.mg_type == "img") {

                } else if (info.mg_type == "system_exit") {
                    // 시스템 메시지: 채팅방 나가기 알림
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "'>";
                    content += info.mg_text;
                    content += "</p>";
                } else if (info.mg_type == "면접일정확인") {
                    var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                    $("#top_dt1").html(dt);
                    if (user_type == "COMP") {
                        var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                        content += dt + " 면접일 확정 요청 하였습니다.";
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
                        content += dt + " 면접일 변경요청 하였습니다.";
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
                        content += dt + " 근로예정일 변경 요청 하였습니다.";
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
                $("#chat_list").prepend(content);
            } else {
                $("#chat_list").append(content);
            }
        }

        function scrollBottom() {
            $("#chat_body").scrollTop($("#chat_body")[0].scrollHeight);
        }

        function setEnabledButton($this, rm_idx, mg_idx, text) {
            APP_NOWAPK.message_update_status(JSON.stringify({
                rm_idx: parseInt(rm_idx),
                mg_idx: parseInt(mg_idx),
                mg_imoti: text
            }));

            //상태 변경 UI 처리
            $this.closest("div").find("button").prop("disabled", true);
        }
    </script>
<?php else : ?>
    <!-- ===== 웹 브라우저 Fallback 에서 실행 ===== -->
    <script>
        var g_user_id = "";
        var first_yn = "N";

        // 웹에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = ensureValidUserId();
            if (!g_user_id) {
                return;
            }
            //alert(Number(rm_idx));//test
            //alert(g_user_id);//test

            socket.emit("room_join", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            socket.emit("room_info", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            //alert('room_info_call');//test

            var params = {
                rm_idx: Number(rm_idx),
                skip: 0,
                limit: 1000 //rm_length
            };
            //console.log(params);
            //alert('1');
            socket.emit("message_list", params);

            // iOS/웹에서도 전송 버튼 클릭 시 메시지를 보낼 수 있도록 바인딩
            $("#btnSend").off("click.chatSend").on("click.chatSend", function() {
                send_message();
            });

        });

        //방생성 확인 및 방번호 확인
        socket.on("room_info_return", function(ret) {
            //24.11.09
            //alert('return');
            console.log(ret);
            //alert('return');//test
            if (ret.code != "00") {
                alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
            } else {
                // 채팅방 진입 시 중복 메시지 방지 배열 초기화
                resetProcessedIdx();

                //채팅방 정보
                rm_info = ret.data;
                //alert('1');//test
                //alert(rm_info);//test
                //alert(g_user_id);//test

                //$("#rm_subject").html(getRoomSubject(rm_info,g_user_id));
                //alert('2');//test

                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length
                };
                //alert(rm_idx);//test
                //alert(rm_start);//test
                //alert(rm_length);//test
                socket.emit("message_list", params);
            }
        });

        //방접속시 알림
        socket.on("room_join_alarm", function(ret_user_id) {
            //alert('alarm');
            $(".readcount").html("");
        });

        var pagging_yn = false;
        $("#chat_body").scroll(function() {
            var scroll_top = $(this).scrollTop();
            if ($(this).scrollTop() < 300 && pagging_yn == false) {
                pagging_yn = true;

                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length
                };
                socket.emit("message_list", params);
            }
        });

        /////////////////////////////////////////////////////////
        // 리턴 처리
        /////////////////////////////////////////////////////////

        function send_message() {
            //android-else
            var txt = $("#chatTxt").val();
            if (txt == "") {
                return;
            }
            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            //console.log(rm_idx);
            //console.log(g_user_id);

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

            //console.log(rm_info);
            //console.log(rm_info.rm_users);
            //console.log(rm_info.rm_users.length);

            if (rm_info != null) {

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

                // 🔥 실시간 채팅: 메시지 전송 전 즉시 화면에 표시 (Optimistic Update)
                // 임시 mg_idx 생성 (서버 응답으로 실제 mg_idx로 교체됨)
                message.mg_idx = 'temp_' + Date.now();
                message.temp_yn = true; // 임시 메시지 플래그

                console.log('🔥 [실시간] 메시지 즉시 표시:', message);
                showMessage(message, false);
                scrollBottom();

                //채팅방 실시간 쏘기
                socket.emit("message_insert", message, rm_info.rm_users);
                $("#chatTxt").val(""); //.focus();
            } else {
                first_yn = "Y";
                var rm_info = {
                    rm_users: [{
                            user_id: $('#otherid').val() //$("#top_name").data('chatotherid')
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
                    user_id2: $('#otherid').val(), //$("#top_name").data('chatotherid'),
                    user_name1: g_user_id,
                    user_name2: $('#otherid').val() //$("#top_name").data('chatotherid')
                };
                comAjax("/chat_proc/newroommake", params, function(ret) {
                    //console.log(ret);
                    //alert('message_insert1');

                    // 🔥 실시간 채팅: 메시지 전송 전 즉시 화면에 표시
                    message.mg_idx = 'temp_' + Date.now();
                    message.temp_yn = true;
                    showMessage(message, false);
                    scrollBottom();

                    //채팅방 실시간 쏘기
                    socket.emit("message_insert", message, rm_info.rm_users);
                    $("#chatTxt").val(""); //.focus();
                    return;
                });


            }

        }

        //메시지 정보 리턴
        socket.on("message_read_count_return", function(ret) {
            //리턴받은 카운트 메시지 정보 업데이트
            $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + ret.data.mg_idx)
            if (ret.data.count == 0) {
                $("[id^=mg_idx_]").last().find(".readcount").html("");
            } else {
                $("[id^=mg_idx_]").last().find(".readcount").html(ret.data.count);
            }
        });

        //메시지 리스트 리턴 처리 (안드로이드/웹/iOS 공통 핸들러)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_list_return");
        socket.on("message_list_return", function(ret) {
            console.log('메시지 리스트 수신:', ret);

            // 메시지 로딩 시 중복 방지 배열 초기화 (새로운 메시지 로딩이므로)
            if (pagging_yn == false) {
                resetProcessedIdx();
            }

            //메시지 리스트 가져오기
            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt].rm_messages, true);
            }
            if (pagging_yn == false) {
                scrollBottom();
            }
            pagging_yn = false;
        });

        //메시지 삽입 리턴 처리 (안드로이드/웹/iOS 공통 핸들러)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_insert_return");
        socket.on("message_insert_return", function(ret) {
            console.log('=== 서버에서 메시지 수신 ===');
            console.log('message_insert_return:', ret);

            if (ret.code != "00") {
                console.warn('메시지 전송 실패:', ret.msg);
                // 안드로이드에서만 alert 표시 (안드로이드 특수 로직)
                if (typeof APP_NOWAPK !== 'undefined') {
                    alert('메시지 전송에 실패했습니다: ' + ret.msg);
                }
                // 실패 시 임시 메시지 제거
                $("[data-temp-message='true']").remove();
                return;
            }

            // 🔥 실시간 채팅: 보내는 사람과 받는 사람 모두 처리
            var message_list = ret.data || [];
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                var received_message = message_list[icnt];
                var is_my_message = (received_message.user_id == g_user_id);

                console.log('🔥 [실시간] 수신된 메시지:', received_message);
                console.log('🔥 [실시간] 내 메시지 여부:', is_my_message);

                if (is_my_message) {
                    // 🔥 내가 보낸 메시지: 임시 메시지를 실제 메시지로 교체
                    var temp_selector = "[data-temp-message='true']";
                    var $temp_msg = $(temp_selector).last();

                    if ($temp_msg.length > 0) {
                        console.log('🔥 [실시간] 임시 메시지 교체');
                        // 임시 메시지 제거
                        $temp_msg.remove();
                    }

                    // 실제 메시지 표시 (중복 방지 체크)
                    if (!processedIdx.includes(received_message.mg_idx)) {
                        showMessage(received_message, false);
                    }
                } else {
                    // 🔥 상대방이 보낸 메시지: 새로 표시 (중복 방지 체크)
                    if (!processedIdx.includes(received_message.mg_idx)) {
                        showMessage(received_message, false);
                    }
                }

                //읽음 처리
                var params = {
                    rm_idx: Number(rm_idx),
                    mg_idx: Number(received_message.mg_idx),
                    user_id: g_user_id
                };
                socket.emit("message_read_return", params);
            }
            scrollBottom();
        });

        //방 조인
        socket.on("room_join_return", function(ret) {
            //alert('room_join_return');//test
            $(".readcount").html("");
        });

        //접속 끊길때 방 정리
        socket.on("room_leave_alarm", function(ret) {
            alert("접속이 끊겼습니다.");
            if (typeof APP_NOWAPK !== "undefined" && typeof APP_NOWAPK.room_leave === "function") {
                APP_NOWAPK.room_leave(JSON.stringify({
                    rm_idx: Number(rm_idx)
                }));
            }
        });

        //방해제 처리
        socket.on("room_leave_return", function(ret) {
            alert("방에 접속하셨습니다.");
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

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용수락");
        });

        //면접 수락
        $(document).on("click", ".btnRecruitCancelOK", function() {
            sendResume("채용취소", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용취소");
        });

        function changeStatus() {
            var status = $("[name=rm_status]").val();
            $("#comp_top_1").show();

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
        var user_type = $("[name='user_type']").val();

        function showMessage(info, is_prepend) {
            // 🔥 임시 메시지는 중복 체크 제외 (서버 응답으로 교체됨)
            if (!info.temp_yn && processedIdx.includes(info.mg_idx)) {
                console.log('🔥 [showMessage] 중복 메시지 무시:', info.mg_idx);
                return; // Exit if the idx is already in the array
            }

            // 임시 메시지가 아니면 processedIdx에 추가
            if (!info.temp_yn) {
                processedIdx.push(info.mg_idx);
            }

            //유형에 따른 처리
            if (info.mg_type == "timeline") {
                var content = "<p class='chat-divider'>";
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
                        // 🔥 임시 메시지인 경우 data-temp-message 속성 추가
                        var temp_attr = info.temp_yn ? " data-temp-message='true'" : "";
                        var content = "<div class='msg-item chat-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "'" + temp_attr + ">";
                        content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                        if (readcount > 0) {
                            content += "    <div class='readcount'>" + readcount + "</div>";
                        } else {
                            content += "    <div class='readcount'></div>";
                        }
                        content += "    </div>";
                        content += "    <div class='meta'>";
                        content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                        content += "    </div>";
                        content += "</div>";
                    } else {
                        var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
                        content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />") + "</div>";
                        content += "    <div class='meta'>";
                        content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                        content += "    </div>";
                        content += "</div>";
                    }
                } else if (info.mg_type == "file") {

                } else if (info.mg_type == "img") {

                } else if (info.mg_type == "system_exit") {
                    // 시스템 메시지: 채팅방 나가기 알림
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "'>";
                    content += info.mg_text;
                    content += "</p>";
                } else if (info.mg_type == "면접일정확인") {
                    var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                    $("#top_dt1").html(dt);
                    if (user_type == "COMP") {
                        var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                        content += dt + " 면접일 확정 요청 하였습니다.";
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
                        content += dt + " 면접일 변경요청 하였습니다.";
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
                        content += dt + " 근로예정일 변경 요청 하였습니다.";
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
                $("#chat_list").prepend(content);
            } else {
                $("#chat_list").append(content);
            }
        }

        function scrollBottom() {
            $("#chat_body").scrollTop($("#chat_body")[0].scrollHeight);
        }

        function setEnabledButton($this, rm_idx, mg_idx, text) {
            socket.emit("message_update_status", {
                rm_idx: parseInt(rm_idx),
                mg_idx: parseInt(mg_idx),
                mg_imoti: text
            });

            //상태 변경 UI 처리
            $this.closest("div").find("button").prop("disabled", true);
        }
    </script>
<?php endif; ?>