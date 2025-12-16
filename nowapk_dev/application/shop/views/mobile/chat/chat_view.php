<input type="hidden" name="user_type" value="<?= $user_type ?>" />
<input type="hidden" name="rm_status" value="<?= $room["rm_status"] ?>" />
<input type="hidden" name="recruit_phone" value="<?= $recruit["mm_phone"] ?>" />
<input type="hidden" name="recruit_name" value="<?= $recruit["mm_comp_name"] ?>" />

<div class="page page-chat-view">
    <div class="top-bar">
        <!--<a href="javascript:_replace('/chat/chat_list');" class="btn-replace"><span class="sr-text">이전으로</span></a>-->
        <a href="javascript:history.back();" class="btn-replace"><span class="sr-text">이전으로</span></a>
        <h1 class="title" id="rm_subject">1:1채팅</h1>
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
                        <!-- 썸네일: 이미지 폴백 처리 -->
                        <div class="thumb">
                            <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>">
                                <img src="<?= getResumeImg($resume) ?>" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
                            </a>
                        </div>
                        <!-- 추천 배지 -->
                        <p class="recomm-badge"><span>추천</span></p>

                        <p class="star-rating">
                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                            <span class="star" style="width:100%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p>
                        <!-- 공고보기 -->
                        <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" class="btn-line">인재정보</a>
                    </div>
                    <div class="content-wrap">
                        <div class="meta top">
                            <!-- 직종라벨 -->
                            <p class="category" style="display:none;"><?= getWorkingImg($resume["ru_work"]) ?></p>
                            <!-- 조건 -->
                            <p class="condition">
                                <em class="period"><?= (strpos($resume["ru_price_type"], ",") !== false ? "협의" : ($resume["ru_price_type"])) ?></em>,
                                <span class="working-time"><?= (strpos($resume["ru_work_type"], ",") !== false ? "협의" : ($resume["ru_work_type"])) ?></span>
                            </p>
                        </div>
                        <div class="title-wrap">
                            <!-- 이름 -->
                            <p class="title"><strong><a href="/resume/hr_post/<?= $resume["ru_idx"] ?>"><?= $resume["mm_name"] ?></a></strong></p>
                            <!-- 추가사항 -->
                            <div class="meta">
                                <p><?= $resume["mm_nation"] ?></p>
                                <span>/</span>
                                <p><?= $resume["ma_age"] ?></p>
                            </div>
                        </div>
                        <ul class="working-day">
                            <?= getWeekYoil($resume["ru_week"]) ?>
                        </ul>
                        <!-- 해시태그 -->
                        <div class="hashtag">
                            <?= $resume["ru_my_type"] ?>
                        </div>
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
                <div id="comp_top_1" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="add-new">
                            <p class="title">* 면접일을 확정하셔야 연락처가 공유됩니다.</p>
                            <div class="btn-wrap">
                                <button type="button" aria-expanded="false" aria-controls="interviewForm" id="interviewShow" class="btn-round btn-primary js-toggle-form">면접일 확정</button>
                                <button type="button" aria-expanded="false" aria-controls="startDateForm" id="dateShow" class="btn-round btn-light js-toggle-form">채용확정</button>
                            </div>
                        </div>
                        <!-- 면접일 입력폼 -->
                        <div id="interviewForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <!-- 20221107 수정 -->
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
                        <!-- 근로인 입력폼 -->
                        <div id="startDateForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <!-- 20221107 수정 -->
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
                                //20221107 수정
                                var calendars = [];

                                /* 입력폼 열기,닫기 */
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
                <!-- //일정 -->
            <?php else : ?>
                <input type="hidden" id="otherid" value="<?= $recruit["mm_id"] ?>">
                <div class="item-info recruit angle-link">
                    <div class="thumb-wrap">
                        <!-- 썸네일 -->
                        <div class="thumb">
                            <a href="/recruit/job_post/<?= $recruit["ru_idx"] ?>"><img data-src="<?= getRecruitImg($recruit) ?>" alt="" class="lozad"></a>
                        </div>
                        <a href="/recruit/job_post/<?= $recruit["ru_idx"] ?>" class="btn-line">지원한공고</a>
                    </div>
                    <div class="content-wrap">
                        <div class="meta top">
                            <!-- 직종라벨 -->
                            <p class="category">
                                <?= getWorkingImg($recruit["rc_work"]) ?>
                            </p>
                            <!-- 지역 -->
                            <p class="area"><?= $recruit["ad_addr"] ?></p>
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
                        </div>
                        <!-- 지불방식 -->
                        <div class="pay">
                            <p class="period"><?= getPriceType($recruit["rc_price_type"]) ?></p>
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
        <!--//chat-view-header-->
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
                            <!-- 제목 -->
                            <h3 class="secondary-title"><?= $resume["mm_name"] ?></h3>
                            <!-- 모집기간 -->
                            <div class="person-meta">
                                <p><?= $resume["mm_nation"] ?></p>
                                <span>/</span>
                                <p><?= $resume["ma_age"] ?>세</p>
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
                                    <td class="label">근무가능일</td>
                                    <td class="value"><?= substr($resume["ru_work_dt"], 0, 10) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">근무형태</td>
                                    <td class="value"><?= $resume["ru_work_type"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">최종학력</td>
                                    <td class="value"><?= $resume["ru_edu"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">간단경력</td>
                                    <td class="value">
                                        <ul class="period-list">
                                            <?php foreach ($career_list as $row) : ?>
                                                <li>
                                                    <span><?= $row["rc_type"] ?></span>
                                                    <span><?= $row["rc_name"] ?></span>
                                                    <?php
                                                    $is_date = false;
                                                    $sdate = "";
                                                    $edate = "";
                                                    $arr_date = explode("~", $row["rc_date"]);
                                                    if (count($arr_date) > 1) {
                                                        $is_date = true;
                                                        $sdate = str_replace(".", "-", trim($arr_date[0])) . "-01";
                                                        $edate = str_replace(".", "-", trim($arr_date[1])) . "-01";
                                                    }
                                                    ?>
                                                    <em><?= $row["rc_date"] ?><?= $is_date ? "(" . get_date_diff_txt($sdate, $edate) . ")" : "" ?></em>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">자기소개</td>
                                    <td class="value">
                                        <div class="summary"><?= str_replace("\n", "<br/>", $row["ru_desc"]) ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">자격증</td>
                                    <td class="value">
                                        <ul class="period-list">
                                            <?php foreach ($certi_list as $row) : ?>
                                                <li>
                                                    <span>자격증</span>
                                                    <span><?= $row["rc_name"] ?></span>
                                                    <em><?= $row["rc_dt"] ?></em>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- 상세페이지 이동 -->
                        <div class="align-right"><a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" class="view-all">더보기</a></div>
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
                            <!-- 제목 -->
                            <h3 class="secondary-title"><?= $recruit["rc_name"] ?></h3>
                            <!-- 모집기간 -->
                            <p class="recruit-period">
                                <span>모집기간</span>
                                <em><?= $recruit["rc_sdate"] ?> ~ <?= $recruit["rc_edate"] ?></em>
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
                                    <td class="value">
                                        <!-- 직종라벨 -->
                                        <?= getWorkingImg($recruit["rc_work"]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">급여</td>
                                    <td class="value">
                                        <span class="pay">
                                            <em class="amount"><?= getRecuitPrice($recruit) ?></em>
                                            <span>(<?= getPriceType($recruit["rc_price_type"]) ?>)</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">근무형태</td>
                                    <td class="value"><span class="working-time"><?= getWorkType($recruit["rc_work_type"]) ?></span></td>
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
                                    <td class="label">희망국적</td>
                                    <td class="value"><?= $recruit["rc_nation_nm"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">희망성별</td>
                                    <td class="value"><?= $recruit["rc_sex"] ?></td>
                                </tr>
                                <tr>
                                    <td class="label">희망나이</td>
                                    <td class="value">
                                        <?= $recruit["rc_sage"] != 0 ? "rc_sdate" . "세이상" : "" ?>
                                        <?= $recruit["rc_sage"] != 0 || $recruit["rc_eage"] != 0 ? "~" : "" ?>
                                        <?= $recruit["rc_edate"] != 0 ? $recruit["rc_eage"] . "세이하" : "" ?>
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
                        <!-- 상세페이지 이동 -->
                        <div class="align-right"><a href="/recruit/job_post/<?= $recruit["rc_idx"] ?>" class="view-all">더보기</a></div>
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
    var last_send_dt = "";
    var rm_idx = "<?= $rm_idx ?>";
    var rm_info = {};
    var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;

    //대화내용 중복 차단 내역 초기화
    let processedIdx = [];

    // 채팅방 진입 시 processedIdx 초기화
    function resetProcessedIdx() {
        processedIdx = [];
        console.log('processedIdx 배열 초기화됨');
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
<?php if ($this->common->getSession("os_type") == "android") : ?>
    <script>
        console.log('android');
        var g_user_id = "";
        var first_yn = "N";

        // getCurrentDate 함수 정의
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

        // getFormatDate 함수 정의
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

        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = $("#user_id").val();

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

        //메시지 리스트 리턴 처리
        socket.on("message_list_return", function(ret) {
            console.log('안드로이드 message_list_return:', ret);

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

        //메시지 삽입 리턴 처리
        socket.on("message_insert_return", function(ret) {
            console.log('=== 서버에서 메시지 수신 ===');
            console.log('안드로이드 message_insert_return:', ret);
            console.log('응답 코드:', ret.code);
            console.log('응답 메시지:', ret.msg);
            console.log('데이터:', ret.data);

            // 서버 응답 로그

            if (ret.code == "00") {
                console.log('메시지 전송 성공');
                //상대방이 보낼때 발생함
                var message_list = ret.data;
                for (var icnt = 0; icnt < message_list.length; icnt++) {
                    console.log('수신된 메시지:', message_list[icnt]);
                    showMessage(message_list[icnt], false);
                    //메시지 리스트 조회
                    var params = {
                        rm_idx: Number(rm_idx),
                        mg_idx: Number(message_list[icnt].mg_idx),
                        user_id: g_user_id
                    };
                    socket.emit("message_read_return", params);
                }
                scrollBottom();
            } else {
                console.log('메시지 전송 실패:', ret.msg);
                alert('메시지 전송에 실패했습니다: ' + ret.msg);
            }
        });

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

            // 채팅 메시지 전송 시작

            console.log('=== 채팅 메시지 전송 시작 ===');
            console.log('안드로이드 send_message 호출됨');
            console.log('rm_idx:', rm_idx);
            console.log('g_user_id:', g_user_id);
            console.log('rm_info:', rm_info);
            console.log('메시지 내용:', txt);

            // FCM 토큰 상태 확인
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

            //채팅방 실시간 쏘기 - Socket.IO만 사용
            console.log('소켓 메시지 전송 시도');
            socket.emit("message_insert", message, rm_info.rm_users);
            console.log('소켓 메시지 전송 완료');

            //채팅창 클리어
            $("#chatTxt").val(""); //.focus();
            console.log('=== 채팅 메시지 전송 완료 ===');
        }

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

        //메시지 정보 리턴
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

        //방 조인
        function room_join_return(data) {
            //alert('join_return:685');
            $(".readcount").html("");
        }

        //접속 끊길때 방 정리
        function room_leave_alarm(ret) {
            alert("접속이 끊겼습니다.");
            socket.emit("room_leave", {
                rm_idx: Number(rm_idx)
            });
        }

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

            if (processedIdx.includes(info.mg_idx)) {
                return; // Exit if the idx is already in the array
            }
            processedIdx.push(info.mg_idx);

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
                        var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
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
    <script>
        console.log('web');
        var g_user_id = "";
        var first_yn = "N";

        // 웹에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = $("#user_id").val();
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
                    //채팅방 실시간 쏘기
                    socket.emit("message_insert", message, rm_info.rm_users);
                    $("#chatTxt").val(""); //.focus();
                    return;
                });


            }

        }

        //메시지 정보 리턴 (중복 제거 - 위의 안드로이드용 리스너와 동일)
        // socket.on("message_insert_return", function(ret) {
        //     //alert('message_insert_return');
        //     //상대방이 보낼때 발생함
        //     var message_list = ret.data;
        //     for (var icnt = 0; icnt < message_list.length; icnt++) {
        //         showMessage(message_list[icnt], false);
        //         //메시지 리스트 조회
        //         var params = {
        //             rm_idx: Number(rm_idx),
        //             mg_idx: Number(message_list[icnt].mg_idx),
        //             user_id: g_user_id
        //         };
        //         socket.emit("message_read_return", params);
        //     }
        //     scrollBottom();
        // });

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


        //방생성 확인 및 방번호 확인
        socket.on("message_list_return", function(ret) {
            //alert("message_list_return");
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

        //방 조인
        socket.on("room_join_return", function(ret) {
            //alert('room_join_return');//test
            $(".readcount").html("");
        });

        //접속 끊길때 방 정리
        socket.on("room_leave_alarm", function(ret) {
            alert("접속이 끊겼습니다.");
            APP_NOWAPK.room_leave(JSON.stringify({
                rm_idx: Number(rm_idx)
            }));
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

            if (processedIdx.includes(info.mg_idx)) {
                return; // Exit if the idx is already in the array
            }
            processedIdx.push(info.mg_idx);

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
                        var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
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