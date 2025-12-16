<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section view-header">
            <div class="profile">
                <div class="container">
                    <div class="thumb-wrap">
                        <label class="form-pic">
                            <input type="file" name="profile2" accept="image/*" class="js-file-preview">
                            <span class="preview"><img data-src="<?= getMemberImg($mem_info) ?>" alt="" class="lozad"></span>
                        </label>
                        <!-- 평점 -->
                        <p class="star-rating v2">
                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                            <span class="star" style="width:80%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p>
                    </div>
                    <div class="profile-detail">
                        <!-- 이름 -->
                        <p class="name"><em><?= $mem_info["mm_name"] ?></em>님</p>
                        <div class="meta">
                            <!-- 연락처 -->
                            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
                            <!-- 메일주소 -->
                            <!-- <p class="email"><?= $mem_info["mm_id"] ?></p> -->
                            <!-- 포인트 -->
                            <p class="point"><?= number_format($mem_info["mm_point"]) ?>P</p>
                        </div>
                    </div>
                    <ul class="profile-record">
                        <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                        <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                        <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                    </ul>
                </div>
            </div>
            <!-- 20220922 수정 -->
            <div class="summary">
                <div class="container">
                    <h3 class="secondary-title">희망근무조건</h3>
                    <table class="info-table">
                        <caption><span class="sr-text">희망근무조건</span></caption>
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="label">업무구분</td>
                                <td class="value">
                                    <?= getWorkingImg($info["ru_work"]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">지역</td>
                                <td class="value">
                                    <?php foreach ($addr as $key => $ad) : ?>
                                        <?= $key == 0 ? $ad["ad_addr"] : " ," . $ad["ad_addr"] ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">급여형태</td>
                                <td class="value"><?= $info["ru_work_type"] ?></td>
                            </tr>
                            <?php if (strpos($info["ru_price_type"], "월급") !== false && $info["ru_month_price"] != "0") : ?>
                                <tr>
                                    <td class="label">희망연봉</td>
                                    <td class="value"><?= number_format($info["ru_month_price"]) ?>원</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="label">근무형태</td>
                                <td class="value"><?= $info["ru_price_type"] ?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <ul class="working-day">
                                        <?php if (isset($info["ru_week_yn"]) && $info["ru_week_yn"] == "Y") : ?>
                                            <li aria-hidden="false">무관</li>
                                        <?php else : ?>
                                            <?= getWeekYoil($info["ru_week"]) ?>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무시작일</td>
                                <td class="value"><?= (get_date($info["ru_work_dt"])) ?>(<?= get_yoil($info["ru_work_dt"]) ?>)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="section view-career">
            <div class="container">
                <h3 class="secondary-title">경력소개</h3>
                <table class="info-table">
                    <caption><span class="sr-text">경력</span></caption>
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="label">최종학력</td>
                            <td class="value"><?= $info["ru_edu"] ?></td>
                        </tr>
                        <tr>
                            <td class="label">경력사항</td>
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
            </div>
        </div>
        <div class="section view-introduce">
            <div class="container">
                <h3 class="secondary-title">자기소개</h3>
                <div class="introduce-wrap">
                    <?= str_replace("\n", "<br/>", $info["ru_desc"]) ?>
                </div>
                <div class="hashtag">
                    <?= getMyType($info["ru_my_type"]) ?>
                </div>
            </div>
        </div>
        <!-- <div class="section view-attachment">
            <div class="container">
                <h3 class="secondary-title">첨부사진</h3>
                <div class="pic-group">
                    <?php if ($info["ru_img1"] != "") : ?>
                        <div class="pic-card"><img data-src="<?= $info["ru_img1"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img2"] != "") : ?>
                        <div class="pic-card"><img data-src="<?= $info["ru_img2"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img3"] != "") : ?>
                        <div class="pic-card"><img data-src="<?= $info["ru_img3"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img4"] != "") : ?>
                        <div class="pic-card"><img data-src="<?= $info["ru_img4"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img5"] != "") : ?>
                        <div class="pic-card"><img data-src="<?= $info["ru_img5"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div> -->
        <div class="view-footer">
            <button type="button" id="btnDelete" aria-haspopup="dialog" class="btn btn-secondary js-remove">삭제</button>
            <button type="button" id="btnEdit" class="btn btn-primary">수정</button>
        </div>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<input type="hidden" name="ru_name" value="<?= $info["ru_name"] ?>" />
<script>
    $(document).ready(function() {
        $("#btnDelete").click(function() {
            var ru_name = $("[name=ru_name]").val();
            var ru_idx = $("[name=ru_idx]").val();
            openPopup('confirm', {
                content: '<em>“' + ru_name + '”</em><br> 이력서를 삭제하시겠습니까?<br> 등록하신 모든 데이터는<br> 삭제됩니다.',
                firstButton: '취소',
                secondButton: '삭제',
                secondCallback: function() {
                    var params = {};
                    params["ru_idx"] = ru_idx;
                    comAjax("/mypage_proc/resume_delete", params, function(ret) {
                        if (ret.code == "00") {
                            location.replace("/mypage/resume_list");
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
            });
        });

        $("#btnEdit").click(function() {
            comAjax("/mypage_proc/resume_start", {}, function(ret) {
                if (ret.code == "00") {
                    location.href = "/mypage/resume_write1/" + $("[name=ru_idx]").val();
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });
    });
</script>