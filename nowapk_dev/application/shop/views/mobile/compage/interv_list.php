<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">면접자 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="profile-list">
            <ol class="list">
                <?php foreach($result_list as $row) : ?>
                <li>
                    <div class="item-info person">
                        <div class="thumb-wrap">
                            <div class="thumb"><img data-src="<?=$row["mm_img1"]?>" alt="" class="lozad"></div>
                        </div>
                        <div class="content-wrap">
                            <p class="name"><em><?=$row["mm_name"]?></em>님</p>
                            <div class="meta">
                                <p class="status"><span style="background:#35f;color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;"><?=$row["iv_status"]?></span></p>
                                <p class="tel"><?=$row["mm_phone"]?></p>
                                <p class="email"><?=$row["mm_id"]?></p>
                            </div>
                            <div class="date txt-color2">
                                <p>면접일: <span><?=substr($row["iv_req_dt"],0,10)?></span></p>
                            </div>
                            <div class="action">
                                 <?php if($row["iv_status"]=="면접확정") : ?>
                                     <button type="button" aria-haspopup="dialog" data-mm_name="<?=$row["mm_name"]?>" data-iv_idx="<?=$row["iv_idx"]?>" class="btn-round btn-tertiary btnSave1">채용확인</button>
                                <?php elseif($row["iv_status"]=="채용확정") : ?>
                                    <button type="button" aria-haspopup="dialog" data-mm_name="<?=$row["mm_name"]?>" data-iv_idx="<?=$row["iv_idx"]?>" class="btn-round btn-tertiary btnSave2">출근확인</button>
                                <?php elseif($row["iv_status"]=="출근") : ?>
                                    <button type="button" aria-haspopup="dialog" data-mm_name="<?=$row["mm_name"]?>" data-iv_idx="<?=$row["iv_idx"]?>" class="btn-round btn-tertiary btnSave3">재직확인</button>
                                <?php elseif($row["iv_status"]=="재직중") : ?>
                                    <button type="button" aria-haspopup="dialog" data-mm_name="<?=$row["mm_name"]?>" data-iv_idx="<?=$row["iv_idx"]?>" class="btn-round btn-tertiary btnSave4">인재평가</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div><!-- //page-content -->
</div>

<!-- 출근일자 입력양식 -->
<template id="startDateTemp">
    <div class="alert-msg">
        <p>출근일자를 입력해주세요</p>
        <div class="form-wrap">
            <form action="/">
                <!-- 20221122 수정 -->
                <div class="form-row">
                    <div class="calendar">
                        <div class="js-datepicker"></div>
                        <label>
                            <span class="sr-text">날짜 선택</span>
                            <input type="text" name="interviewDate" readonly aria-required="true" required class="form-text js-date-value">
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<!-- //출근일자 입력양식 -->

<script>
    $(".btnSave1").click(function(){
        var mm_name = $(this).data("mm_name");
        var iv_idx = $(this).data("iv_idx");

        openPopup('confirm', {
            content: '<em>“'+mm_name+'”</em> 님을<br> 채용하셨습니까?',
            firstButton: '면접불참',
            secondButton: '미채용',
            thirdButton: '채용',
            firstCallback: function () {
                chageStatus(iv_idx,"면접불참");
            },
            secondCallback: function () {
                chageStatus(iv_idx,"미채용");
            },
            thirdCallback: function () {
                chageStatus(iv_idx,"채용확정");
            }
        });
    });

    $(".btnSave2").click(function(){
        var mm_name = $(this).data("mm_name");
        var iv_idx = $(this).data("iv_idx");

        openPopup('confirm', {
            content: '<em>“'+mm_name+'”</em> 님이<br> 출근하셨습니까?',
            firstButton: '미출근',
            secondButton: '출근',
            firstCallback: function () {
                chageStatus(iv_idx,"미출근");
            },
            secondCallback: function () {
                var content = document.querySelector('#startDateTemp').innerHTML;
                openPopup('modal', {
                    title: '출근일자 입력',
                    content: content,
                    firstButton: '입력',
                    firstCallback: function () {
                        chageStatusDate(iv_idx,"출근",$("[name='interviewDate']").val());
                    },
                });

                /* 달력표시 */
                function chgMonth (instance) {
                    var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                        month = Number(instance.currentMonth) + 1,
                        year = instance.currentYear;
                    $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                }

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
                    onChange: function (selectedDates, dateStr, instance) {
                        if(!dateStr) return;
                        var dayTxt = new Date(selectedDates).getDay();
                        var dayArr = ['일','월','화','수','목','금','토'];
                        instance.element.parentNode.querySelector('.js-date-value').value = dateStr +'('+ dayArr[dayTxt]+')';
                    },
                    onMonthChange: function (selectedDates, dateStr, instance) {
                        chgMonth(this);
                    },
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                        if (day == 0) {
                            dayElem.classList.add('sun');
                        } else if (day == 6) {
                            dayElem.classList.add('sat');
                        }
                    },
                    onReady: function () {
                        chgMonth(this);
                    }
                });
            }
        });
    });

    $(".btnSave3").click(function(){
        var mm_name = $(this).data("mm_name");
        var iv_idx = $(this).data("iv_idx");

        openPopup('confirm', {
            content: '<em>“'+mm_name+'”</em> 님이<br> 재직중인가요?',
            firstButton: '무단퇴사',
            secondButton: '퇴사',
            thirdButton: '재직중',
            firstCallback: function () {
                chageStatus(iv_idx,"무단퇴사");
            },
            secondCallback: function () {
                chageStatus(iv_idx,"퇴사");
            },
            thirdCallback: function () {
                chageStatus(iv_idx,"재직중");
            }
        });
    });

    $(".btnSave4").click(function(){
        var iv_idx = $(this).data("iv_idx");
        location.href="/compage/interv_detail/" + iv_idx;
    });

    function chageStatus(iv_idx,iv_status){
        var params = {};
        params["iv_idx"] = iv_idx;
        params["iv_status"] = iv_status;
        comAjax("/compage_proc/chage_interview_proc",params,function(ret){
            if(ret.code=="00"){
                location.reload();
            }else{
            	commonAlert(ret.msg,function(){});
            }
        });
    }

    function chageStatusDate(iv_idx,iv_status,dt){
        var params = {};
        params["iv_idx"] = iv_idx;
        params["iv_status"] = iv_status;
        params["iv_hire_dt"] = dt.substr(0,10);
        comAjax("/compage_proc/chage_interview_proc",params,function(ret){
            if(ret.code=="00"){
                location.reload();
            }else{
            	commonAlert(ret.msg,function(){});
            }
        });
    }
</script>
