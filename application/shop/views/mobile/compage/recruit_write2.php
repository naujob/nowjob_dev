<style>
    .viewLaw {
        font-size: 0.8125rem;
        font-style: normal;
        font-weight: 400;
        display: inline-block;
        padding-right: 20px;
        background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=") no-repeat right center;
        background-size: 12px 12px;
        cursor: pointer;
        margin-left: 20px;
    }

    .layer-panel.card-type .panel-header {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
        background: inherit;
        border-bottom: 4px solid #999;
    }

    .layer-panel.card-type .panel-header::before {
        content: "";
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 50%;
        height: 4px;
        background-color: #1B9D7D;
    }

    .layer-panel.card-type .panel-title {
        color: #1B9D7D;
    }

    .layer-panel.card-type .panel-body {
        padding: 0;
        font-size: 0.8125rem;
        color: #777;
    }

    .low_desc {
        padding: 25px 10px;
    }

    .low_desc em {
        color: red;
    }

    .low_content {
        padding: 25px 10px;
        background-color: #F6F6F6;
        min-height: 150px;
    }

    .low_content h4 {
        margin-bottom: 10px;
    }

    .low_link {
        height: 50px;
        padding: 10px;
        align-content: center;
    }
</style>

<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고등록</h1>
        <ol class="progress-steps">
            <li>1</li>
            <li title="헌재위치" class="active">2</li>
            <li>3</li>
            <li>4</li>
            <li>5</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
            <div class="section addition-info-form">
                <div class="container">
                    <div class="form-row">
                        <p class="form-label">업무구분 <em>최대 2개 선택가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 허용된 업무구분 목록 정의
                            $allowed_works = ['생산', '사무직', '운전배달', '물류', '식당', '서비스', '매장관리', '건설', '상담'];

                            foreach ($rc_wrok_list as $row) :
                                // 허용된 업무구분만 출력
                                if (in_array($row["cd_val"], $allowed_works)) :
                            ?>
                                    <label class="form-toggle-btn2 sm">
                                        <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $info["rc_work"], "check") ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                            <?php
                                endif;
                            endforeach; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망국적 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_nation[]" value="내국인" <?= in_array_return("내국인", $info["rc_nation"], "check") ?>>
                                <span class="btn">내국인</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_nation[]" value="외국인" <?= in_array_return("외국인", $info["rc_nation"], "check") ?>>
                                <span class="btn">외국인</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망성별 <em>다중선택가능</em><a href="#low1" role="button" aria-haspopup="dialog" aria-controls="low1" class="btn js-toggle-panel option viewLaw">남녀고용평등법 안내</a></p>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_sex[]" value="남자" <?= in_array_return("남자", $info["rc_sex"], "check") ?>>
                                <span class="btn">남자</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_sex[]" value="여자" <?= in_array_return("여자", $info["rc_sex"], "check") ?>>
                                <span class="btn">여자</span>
                            </label>
                        </div>
                    </div>

                    <!--div class="form-row">
                        <p class="form-label">희망나이</p>
                        <div class="inline-group">
                            <div class="col">
                                <label>
                                    <span class="sr-text">최소나이 선택</span>
                                    <input type="text" name="rc_sage" placeholder="최소" value="<?= $info["rc_sage"] ?>" readonly required class="form-text js-wheel">
                                </label>
                                <div class="wheel-wrapper"><div id="minWheel" class="wheel"></div></div>
                            </div>
                            <span class="entity">~</span>
                            <div class="col">
                                <label>
                                    <span class="sr-text">최대나이 선택</span>
                                    <input type="text" name="rc_eage" placeholder="최대" value="<?= $info["rc_eage"] ?>" readonly required class="form-text js-wheel">
                                </label>
                                <div class="wheel-wrapper"><div id="maxWheel" class="wheel"></div></div>
                            </div>
                        </div>
                    </div-->

                    <div class="form-row">
                        <p class="form-label">희망나이<a href="#low2" role="button" aria-haspopup="dialog" aria-controls="low2" class="btn js-toggle-panel option viewLaw">연령차별금지법 안내</a></p>
                        <div class="inline-group" style="width: 100%; display: block;">
                            <div class="col" style="width: calc(25% - 0.5rem); float: left;">
                                <select name="rc_sage" style="width:78px;height:36px;padding-left:12px; border: 1px solid #BEBFC9; color: #222; border-radius: 18px;">
                                    <option value="">선택</option>
                                    <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                        <option value="<?= $icnt ?>" <?= $info["rc_sage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <span class="entity" style="float: left; margin-top: 8px;">~</span>
                            <div class="col" style="width: calc(25% - 0.5rem); float: left;">
                                <select name="rc_eage" style="width:78px;height:36px;padding-left:12px; border: 1px solid #BEBFC9; color: #222; border-radius: 18px;">
                                    <option value="">선택</option>
                                    <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                        <option value="<?= $icnt ?>" <?= $info["rc_eage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="sticky-bottom view-footer">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </div>
            </div>

        </form>
    </div><!-- //page-content -->
</div>


<div id="low1" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">남녀고용평등법 안내</h2>
            <button type="button" aria-controls="low1" class="btn-close-popup js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="low_desc">
                    채용 시 남녀를 차별하거나, 여성 근로자를 채용할 때 직무 수행에 불필요한 용모,키,체중 등의 신체조건 및 미혼 조건을 제시 또는 요구하는 경우 남녀고용평등법 위반에 해당, 500만원 이하의 벌금이 부과될 수 있습니다.<br>
                    <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다.</em>
                </div>
                <div class="low_content">
                    <h4>차별기준</h4>
                    <p>- 특정성을 지칭하는 직종명 등 사용하는 경우</p>
                    <p>- 직종 등 남녀 분리모집 하거나 모집인원수를 남녀를 달리 정한 경우</p>
                    <p>- 학력·경력 등 동일한 자격임에도 다른 성별을 불리한 고용형태로 차등 채용</p>
                    <p>- 직무 수행상 필요하지 않은 채용조건 부과 및 성에 대한 다른 조건을 부여</p>
                    <p>- 모집·채용에 관한 정보를 제공함에 있어 성별로 다르게 취합하는 경우</p>
                </div>
                <div class="low_link">
                    <!-- a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000130&ancYnChk=0#0000" target="_blank">* 남녀고용평등법 자세히 보기</a -->
                </div>
            </div>
        </div>
    </div>
</div>

<div id="low2" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">연령차별금지법 안내</h2>
            <button type="button" aria-controls="low2" class="btn-close-popup js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="low_desc">
                    채용 시 합리적인 이유 없이 연령제한을 하는 경우 연령차별금지법 위반에 해당되어 500만원 이하의 벌금이 부과될 수 있습니다.<br>
                    <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다.</em>
                </div>
                <div class="low_content">
                    <h4>차별기준</h4>
                    <p>- 지원자의 나이를 제한하거나 특정 연령층을 선호하는 경우</p>
                </div>
                <div class="low_link">
                    <!-- a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000121&ancYnChk=0#0000" target="_blank">* 연령차별금지법 자세히 보기</a -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 20221122 수정 -->
<script>
    window.addEventListener('DOMContentLoaded', function() {
        if (typeof Wheel == undefined) return;

        function closeActive() {
            var active = document.querySelector('.js-wheel.is-focus');
            var activeWheel = document.querySelector('.wheel-wrapper.on');

            if (active) {
                active.classList.remove('is-focus');
            }

            if (activeWheel) {
                activeWheel.classList.remove('on');
                activeWheel.hidden = true;
            }
        }

        /*
        document.querySelector('[name="rc_sage"]').addEventListener('change', function (e) {
            var min = parseInt(this.value);
            var max = document.querySelector('[name="rc_eage"]');

            if (parseInt(max.value) < min) {
                max.value = min + '세';
            }
        });

        document.querySelector('[name="rc_eage"]').addEventListener('change', function (e) {
            var max = parseInt(this.value);
            var min = document.querySelector('[name="rc_sage"]');

            if (parseInt(min.value) > max) {
                min.value = max + '세';
            }
        });
		*/

        document.querySelectorAll('.js-wheel').forEach(function(el) {
            var wheel = el.parentNode.parentNode.querySelector('.wheel');

            new Wheel(wheel, {
                initIdx: 0,
                loop: true,
                width: 24,
                length: 60, //총갯수
                start: 20, //시작숫자
                altInput: el,
                label: '세',
            });

            el.addEventListener('focus', function(e) {
                /* 옵션창 표시 */
                var parent = el.parentNode;
                var xpos = parent.offsetLeft;
                var ypos = parent.offsetTop + el.offsetHeight + 10;
                var max = {
                    xpos: window.innerWidth - 100,
                    ypos: document.querySelector('.page-content').scrollHeight - 200,
                };

                closeActive();

                this.classList.add('is-focus');

                if (max.ypos <= parent.getBoundingClientRect().top + el.offsetHeight + 10) {
                    ypos = parent.offsetTop + 10;
                    wheel.parentNode.style.top = '';
                    wheel.parentNode.style.bottom = ypos + 'px';
                } else {
                    wheel.parentNode.style.bottom = '';
                    wheel.parentNode.style.top = ypos + 'px';
                }

                if (max.xpos <= parent.getBoundingClientRect().left + el.offsetWidth) {
                    wheel.parentNode.style.left = '';
                    wheel.parentNode.style.right = 0;
                } else {
                    wheel.parentNode.style.right = '';
                    wheel.parentNode.style.left = xpos + 'px';
                }

                wheel.parentNode.hidden = false;
                wheel.parentNode.classList.add('on');
            });
        });

        window.addEventListener('click', function(e) {
            /* 옵션창 숨김 */
            var target = e.target;

            if (target.classList && (target.classList.contains('js-wheel') || target.closest('.wheel-wrapper'))) return;

            closeActive();
        });
    });
</script>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        //처리
        $("#btnStep").click(function() {
            if ($("[name='rc_work[]']").is(":checked") == false) {
                commonAlert("희망업무는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='rc_sex[]']").is(":checked") == false) {
                commonAlert("희망성별은 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='rc_nation[]']").is(":checked") == false) {
                commonAlert("희망국적은 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ma_sage']").val() == "") {
                commonAlert("희망나이를 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ma_eage']").val() == "") {
                commonAlert("희망나이를 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ma_sage']").val() != "" && $("[name='ma_eage']").val() != "" && $("[name='ma_sage']").val() > $("[name='ma_eage']").val()) {
                commonAlert("희망나이는 앞에 설정한 나이가 뒤에 설정한 나이보다 클 수 없습니다.", function() {});
                return;
            }

            var params = $("#frm").serialize();
            comAjax("/compage_proc/recruit_write2", params, function(ret) {
                if (ret.code == "00") {
                    if ($("[name=rc_idx]").val() != "0") {
                        location.href = "/compage/recruit_write3/" + $("[name=rc_idx]").val();
                        //_replace("/compage/recruit_write3/" + $("[name=rc_idx]").val());
                    } else {
                        location.href = "/compage/recruit_write3";
                        //_replace("/compage/recruit_write3");
                    }
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 뒤로가기
        $("#btnPrev").click(function() {
            history.go(-1);
        });

        //업무구분 4개까지만 선택되도록 추가 23.11.21
        var maxLimit = 2; // 최대 선택 가능한 체크박스 개수

        // rc_work 체크박스 변경 이벤트 처리
        $('input[name="rc_work[]"]').change(function() {
            var checkedCount = $('input[name="rc_work[]"]:checked').length;

            // 체크된 개수가 최대 선택 가능한 개수를 초과하면 경고 메시지를 표시
            if (checkedCount > maxLimit) {

                commonAlert("업무구분은 " + maxLimit + "개까지 선택 할 수 있습니다.", function() {});

                $(this).prop('checked', false); // 체크를 해제합니다.
            }
        });

    });
</script>