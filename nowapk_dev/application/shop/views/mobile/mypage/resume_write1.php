<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서 <?= $info["ru_idx"] != "0" ? "수정" : "등록" ?></h1>
        <ol class="progress-steps">
            <li title="헌재위치" class="active">1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" name="ru_work_img" value="" />
            <div class="section addition-info-form">
                <div class="container">
                    <div class="form-row">
                        <label>
                            <span class="form-label">이력서명<em>인재정보 제목으로 등록됩니다<!-- 이력서명은 노출되지 않습니다 --></em></span>
                            <input type="text" name="ru_name" placeholder="이력서명을 작성해주세요" value="<?= $info["ru_name"] ?>" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망업무 <em>희망업무는 2개까지 선택 가능합니다</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 표시 허용 희망업무 화이트리스트
                            $allowed_work_list = ["생산", "사무직", "운전배달", "물류", "식당", "서비스", "매장관리", "건설", "상담"];
                            foreach ($ru_wrok_list as $row) :
                                // 허용 목록 이외 항목은 출력 제외
                                if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                    continue;
                                }
                            ?>
                                <label class="form-toggle-btn2 sm">
                                    <input type="checkbox" name="ru_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $info["ru_work"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망지역<em>희망지역은 최대2개까지 가능합니다</em></p>
                        <div class="inline-group">
                            <ul class="added-location" id="addr_location">
                                <?php foreach ($addr_list as $row) : ?>
                                    <?php if (!empty($row["ad_addr"])) : ?>
                                        <li>
                                            <input type="hidden" name="ad_zipcode[]" value="<?= $row["ad_zip_code"] ?>" />
                                            <input type="hidden" name="ad_jibun[]" value="<?= $row["ad_jibun"] ?>" />
                                            <input type="hidden" name="ad_doro[]" value="<?= $row["ad_doro"] ?>" />
                                            <input type="hidden" name="ad_addr[]" value="<?= $row["ad_addr"] ?>" />
                                            <input type="hidden" name="ad_gps_x[]" value="<?= $row["ad_gps_x"] ?>" />
                                            <input type="hidden" name="ad_gps_y[]" value="<?= $row["ad_gps_y"] ?>" />
                                            <p><?= $row["ad_addr"] ?></p>
                                            <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            <!-- 20220929 수정 -->
                            <a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="btn-add-more js-toggle-panel"><span class="sr-text">추가작성</span></a>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망급여<em>다중선택가능</em>
                            <span class="add_text <?= count($info["ru_price_type"]) > 2 ? "" : "hidden" ?>" style="margin-left:0px;font-weight:normal;">(급여협의)</span>
                        </p>
                        <div class="form-toggle-group col-3">
                            <?php foreach ($ru_price_type_list as $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="ru_price_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["ru_price_type"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-row cont-price <?= (strpos($info["ru_price_type"], "월급") !== false ? "" : "hidden") ?>">
                            <div id="amount3" class="row" style="padding-left:60px;width:270px;">
                                <div class="label" style="width:90px;position:absolute;left:0px;top:10px;">
                                    희망연봉
                                </div>
                                <label class="inline-group">
                                    <input type="text" name="ru_month_price" value="<?= number_format($info["ru_month_price"]) ?>" class="form-text is-currency comma" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <span class="is-abs">원</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 20221003 수정 -->
                    <div class="form-row">
                        <p class="form-label">근무형태 <em>다중선택가능</em>
                            <span class="add_text <?= count($info["ru_work_type"]) > 2 ? "" : "hidden" ?>" style="margin-left:0px;font-weight:normal;">(협의가능)</span>
                        </p>
                        <div class="form-toggle-group col-3">
                            <?php foreach ($ru_work_type_list as $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="ru_work_type[]" class="<?= $row["cd_val"] != "2교대" ? "js-check-time" : "" ?>" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["ru_work_type"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                //선택목록 제한
                (function() {
                    document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                        item.addEventListener('change', function() {
                            var childItems = document.querySelectorAll('[name="' + this.getAttribute('name') + '"]:checked');

                            if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                this.checked = false;
                            }
                        });
                    });

                    //20221003 수정
                    /* 근무시간 문구 표시 */
                    function timeChecked(el) {
                        var total = document.querySelectorAll('.js-check-time').length;
                        var isChecked = document.querySelectorAll('.js-check-time:checked');
                        var row = el.closest('.form-row');
                        var msg = row.querySelector('.help-msg');

                        if (!msg) {
                            msg = document.createElement('P');
                            msg.classList.add('help-msg');
                            msg.innerHTML = '주간 고정, 야간 고정을 선택하셨습니다.';
                        }

                        if (isChecked.length == total) {
                            row.appendChild(msg);
                        } else {
                            msg.remove();
                        }
                    }

                    document.querySelectorAll('.js-check-time').forEach(function(item) {
                        item.addEventListener('change', function() {
                            timeChecked(item);
                        });

                        timeChecked(item);
                    });
                }());
            </script>

            <div class="sticky-bottom">
                <button id="btnStep" type="button" class="btn btn-primary">다음</button>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<script>
    // 검색 완료 선택 처리
    function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {

        var html = "";
        html += '<li>';
        html += '<input type="hidden" name="ad_zipcode[]" value="' + zipcode + '" />';
        html += '<input type="hidden" name="ad_jibun[]" value="' + jibun + '" />';
        html += '<input type="hidden" name="ad_doro[]" value="' + doro + '" />';
        html += '<input type="hidden" name="ad_addr[]" value="' + addr + '" />';
        html += '<input type="hidden" name="ad_gps_x[]" value="' + x + '" />';
        html += '<input type="hidden" name="ad_gps_y[]" value="' + y + '" />';
        html += '<p>' + addr + '</p>';
        html += '<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>';
        html += '</li>';

        $("#addr_location").append(html);

        //팝업 종료
        $("#changeArea").removeClass("on");
    }

    function showAddrInfo(maxlen) {
        if ($("#addr_location").find("li").length >= maxlen) {
            commonAlert("희망지역은 " + maxlen + "개까지 등록 가능합니다.", function() {});
            return;
        }
        $("#changeArea").addClass("on");
    }

    $(document).ready(function() {

        $("[name='ru_price_type[]']").change(function() {
            if ($("[name='ru_price_type[]']:checked").length > 2) {
                $(this).closest(".form-row").find(".add_text").removeClass("hidden");
            } else {
                $(this).closest(".form-row").find(".add_text").addClass("hidden");
            }

            if ($("[name='ru_price_type[]']").eq(2).is(":checked")) {
                $(".cont-price").removeClass("hidden");
            } else {
                $(".cont-price").addClass("hidden");
            }
        });

        $("[name='ru_work_type[]']").change(function() {
            if ($("[name='ru_work_type[]']:checked").length > 2) {
                $(this).closest(".form-row").find(".add_text").removeClass("hidden");
            } else {
                $(this).closest(".form-row").find(".add_text").addClass("hidden");
            }
        });

        //처리
        $("#btnStep").click(function() {

            if ($("[name='ru_name']").val() == "") {
                commonAlert("이력서명을 입력해주세요.", function() {
                    $("[name='ru_name']").focus();
                });
                return;
            }
            if ($("[name='ru_work[]']").is(":checked") == false) {
                commonAlert("희망업무는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("#addr_location li").filter(function() {
                    return $(this).find('input[name="ad_addr[]"]').val().trim() !== "";
                }).length == 0) {
                commonAlert("희망지역을 선택해주세요.", function() {});
                return;
            }

            //if($("#addr_location").find("li").length==0){
            //    commonAlert("희망지역을 선택해주세요.",function(){});
            //    return;
            //}

            if ($("[name='ru_price_type[]']").is(":checked") == false) {
                commonAlert("희망급여는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_work_type[]']").is(":checked") == false) {
                commonAlert("근무형태는 하나이상 선택해주세요.", function() {});
                return;
            }

            var ru_work_img = "";
            for (var icnt = 0; icnt < $("[name='ru_work[]']:checked").length; icnt++) {
                if (ru_work_img == "") {
                    ru_work_img = $("[name='ru_work[]']:checked").eq(icnt).data("img");
                } else {
                    ru_work_img += "," + $("[name='ru_work[]']:checked").eq(icnt).data("img");
                }
            }
            $("[name=ru_work_img]").val(ru_work_img);
            $("[name=ru_month_price]").val($("[name=ru_month_price]").val().replaceAll(",", ""));

            var params = $("#frm").serialize();
            comAjax("/mypage_proc/resume_write1", params, function(ret) {
                if (ret.code == "00") {
                    if ($("[name=ru_idx]").val() != "0") {
                        //_replace("/mypage/resume_write2/" + $("[name=ru_idx]").val());
                        location.href = "/mypage/resume_write2/" + $("[name=ru_idx]").val();
                    } else {
                        //_replace("/mypage/resume_write2");
                        location.href = "/mypage/resume_write2";
                    }
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        $(".comma").on("keyup", function() {
            $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
        });
    });
</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>