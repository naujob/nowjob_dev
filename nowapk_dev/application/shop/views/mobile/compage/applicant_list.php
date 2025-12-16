<?php
function timeElapsedString($datetime, $full = false)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => '년',
        'm' => '개월',
        'w' => '주',
        'd' => '일',
        'h' => '시간',
        'i' => '분',
        's' => '초',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' 전' : '방금 전';
}

function fWorkImg($str)
{

    $workList = array_unique(explode(',', $str));

    // 각 단어를 이미지 태그로 변환
    $result = array_map(function ($work) {
        switch ($work) {
            case '/resource/uploads/admin/96a9e5ff8f7b6e0db82dc62743893bbd.png':
                return '<img src="/resource/uploads/admin/96a9e5ff8f7b6e0db82dc62743893bbd.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/78150efc63e46df52aa80385f4.png':
                return '<img src="/resource/uploads/admin/0d548978150efc63e46df52aa80385f4.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/0a74dd279aa909668a2ec4720b2fd3ec.png':
                return '<img src="/resource/uploads/admin/0a74dd279aa909668a2ec4720b2fd3ec.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/e74fa3c1fe93835d3c6f7ad4ecaacd77.png':
                return '<img src="/resource/uploads/admin/e74fa3c1fe93835d3c6f7ad4ecaacd77.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/2d83db66386a8e1ad404f2fd48e3f79c.png':
                return '<img src="/resource/uploads/admin/2d83db66386a8e1ad404f2fd48e3f79c.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/7a710e0f902f2c03e5b358fa9c52ce42.png':
                return '<img src="/resource/uploads/admin/7a710e0f902f2c03e5b358fa9c52ce42.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/5bbf09fb49560ef98d69320b5427bbc4.png':
                return '<img src="/resource/uploads/admin/5bbf09fb49560ef98d69320b5427bbc4.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/68cbb430bc2e25903d6b14c03c73de31.png':
                return '<img src="/resource/uploads/admin/68cbb430bc2e25903d6b14c03c73de31.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/f275ec8d6882e94b9549fd1e868f3745.png':
                return '<img src="/resource/uploads/admin/f275ec8d6882e94b9549fd1e868f3745.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/ba1480a5bdcb90a13e5fa456ead881b3.png':
                return '<img src="/resource/uploads/admin/ba1480a5bdcb90a13e5fa456ead881b3.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/864e7c47fa39284c66de727a3e6e7b93.png':
                return '<img src="/resource/uploads/admin/864e7c47fa39284c66de727a3e6e7b93.png" class="category-label" style="height: 18px;">';
            case '/resource/uploads/admin/533534388eb9372c4e61021a648d7abf.png':
                return '<img src="/resource/uploads/admin/533534388eb9372c4e61021a648d7abf.png" class="category-label" style="height: 18px;">';
                // 필요한 경우, 다른 작업 카테고리에 대한 추가 이미지 태그를 추가합니다.
            default:
                return $work; // 기본적으로 원래 텍스트를 반환합니다.
        }
    }, $workList);

    // 결과 배열을 문자열로 결합하여 반환
    return implode(' ', $result);
}

?>
<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">지원자관리<? php = $_GET[gbn]; ?></h1>
    </div><!-- //top-bar -->
    <main class="page-content">
        <?php $tab_on = "tab1"; ?>
        <div class="tab-container">
            <ul class="tab-list">
                <li <?php if ($_GET[gbn] != 'yet' && $_GET[mq_status] != 'pass' && $_GET[mq_status] != 'fail') {
                        echo "class=\"on\"";
                    } ?>><a href="javascript:_replace('/compage/applicant_list?gbn=all&rcidx=<?= $_GET[rcidx] ?>');" class="tab">전체 <?= $applicant_allcnt ?></a></li>
                <li <?php if ($_GET[gbn] == 'yet') {
                        echo "class=\"on\"";
                    } ?>><a href="javascript:_replace('/compage/applicant_list?gbn=yet&rcidx=<?= $_GET[rcidx] ?>');" class="tab">미열람 <?= $applicant_yetcnt ?></a></li>
                <li <?php if ($_GET[mq_status] == 'pass') {
                        echo "class=\"on\"";
                    } ?>><a href="javascript:_replace('/compage/applicant_list?mq_status=pass&rcidx=<?= $_GET[rcidx] ?>');" class="tab">합격 <?= $applicant_passcnt ?></a></li>
                <li <?php if ($_GET[mq_status] == 'fail') {
                        echo "class=\"on\"";
                    } ?>><a href="javascript:_replace('/compage/applicant_list?mq_status=fail&rcidx=<?= $_GET[rcidx] ?>');" class="tab">불합격 <?= $applicant_failcnt ?></a></li>
            </ul>
        </div>
        <div class="container" style="margin-bottom: 110px;">
            <?php $tab_on = "tab2"; ?>
            <?php $tab_on2 = "menu1"; ?>
            <div class="tab-panel on">
                <style>
                    .tabbox {}

                    .tabbox ul {}

                    .tabbox ul li {
                        list-style: none;
                        font-size: 1.0em;
                        border-radius: 5px 5px 0 0;
                        width: fit-content;
                        padding: 15px 35px;
                        background-color: transparent;
                        float: left;
                        border-bottom: 0;
                        cursor: pointer;
                        color: #ccc;
                    }

                    .tabbox ul li .tabtitle {}

                    .tabbox ul li .tabcnt {
                        margin-left: 15px;
                    }

                    .tabbox ul li.tabon {
                        background-color: #fff;
                        font-weight: 600;
                        color: #000;
                        border-bottom: 3px solid #57CCAF;
                    }
                </style>
                <div class="manage-my-content">
                    <form action="/">
                        <h3 class="sr-text">지원자 관리</h3>
                        <div class="board-wrap">
                            <table class="board-list" style="border-top: 2px solid #ededed;">
                                <!--<colgroup><col><col style="width:17%"><col><col><col><col></colgroup>-->
                                <tbody>
                                    <!--
                                    <tr>
                                      <td style="font-weight: 600; background-color: #ededed;">이름/연령</td>
                                      <td style="font-weight: 600; background-color: #ededed;">연락처</td>
                                      <td style="font-weight: 600; background-color: #ededed;">학력 | 경력</td>
                                      <td style="font-weight: 600; background-color: #ededed;">지원일 | 열람일</td>
                                      <td style="font-weight: 600; background-color: #ededed;">지원한 공고</td>
                                      <td style="font-weight: 600; background-color: #ededed;">관리</td>
                                    </tr>
                                    -->
                                    <?php $lines = 0; ?>
                                    <?php foreach ($applicant_list as $row) : ?>
                                        <?php $lines++; //print_r($row); 
                                        ?>
                                        <tr>
                                            <td class="selectable-item" style="">
                                                <li class="list-item item-hover" style="list-style:none; font-size: 16px; ">

                                                    <label class="form-checkbox" style="top: 29%; right: 0px; z-index: 1; width: 20px; left: auto;">
                                                        <input type="checkbox" name="chk[]" data-mq-idx="<?= $row[mq_idx] ?>" value="<?= $row[mq_idx] ?>" class="sr-text">
                                                        <span class=label-red></span>
                                                        <span class="sr-text">지원하기 선택</span>
                                                    </label>


                                                    <div class="item-info colgroup" style=" padding-bottom: 0; border-bottom: 1px solid #ccc;">
                                                        <div class="thumb-wrap" style="width: 20%; height: auto;">
                                                            <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row['ru_idx'] ?>" data-user_id="<?= $row['mm_id'] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row['book_yn'] ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                                            <!-- 썸네일 -->
                                                            <div class="thumb big click-item-new" data-link="/resume/resume_detail/<?= $row['ru_idx'] ?>" style="width: 100%;">
                                                                <a href="/resume/resume_detail/<?= $row['ru_idx'] ?>" target="_blank"><img src="<?= getResumeImg($row) ?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt=""></a>
                                                            </div>
                                                            <!-- 평점 -->
                                                            <p class="star-rating">
                                                                <span class="star" style="width:100%; height:16px;"></span>
                                                                <span class="sr-text">평점</span>
                                                                <span class="sr-text">3</span>
                                                            </p>
                                                        </div>
                                                        <div class="content-wrap click-item-new" style="font-size: 1rem; height: fit-content; padding-top: 0px;" data-link="/resume/resume_detail/<?= $row['ru_idx'] ?>">
                                                            <div class="timestamp">등록일 : <?= timeElapsedString($row['reg_dt']) ?></div>
                                                            <div class="title-wrap" style="display: block; width: auto;">
                                                                <!-- 제목 -->
                                                                <div class="inline-group">
                                                                    <!-- 직종라벨 -->
                                                                    <p class="category"><?= fWorkImg($row['ru_work_img']) ?></p>
                                                                </div>
                                                                <p class="title" style=" width: 100%; max-width:100%; padding: 4px 0; font-size: 16px;"><strong><a href="/resume/resume_detail/<?= $row['ru_idx'] ?>" target="_blank"><?= $row['ru_name'] ?></a></strong></p>
                                                                <!-- 추가사항 -->
                                                                <div class="meta" style="margin-left:0;width: auto;font-size: 0.8rem;">
                                                                    <div style="width: 100%; text-align: left; display: flex; margin-bottom: 5px;">
                                                                        <p class="national"><?= $row['mm_nation'] ?></p>
                                                                        <span>&nbsp; | &nbsp;</span>
                                                                        <p class="age"><?= $row['ma_age'] ?>세</p>
                                                                    </div>
                                                                    <div style="width: 100%; text-align: left; display: block;">
                                                                        <span><?= $row['ru_work_type'] ?></span> |
                                                                        <em><?= $row['ru_price_type'] ?></em>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <!-- 태그 -->
                                                    </div>
                                                </li>
                                            </td>
                                            <!--
                                        <td style="">
                                          <p class="area" style="display: block; width: auto; overflow: initial;"><?= $row['mm_phone'] ?></p>
                                          <p class="area" style="display: block; width: auto; overflow: initial;"><?= $row['ru_addr'] ?></p>
                                        </td>
                                        <td>
                                          <p class="area" style="display: block; width: auto; overflow: initial;"><?= $row['ru_edu'] ?></p>
                                          <p class="area" style="display: block; width: auto; overflow: initial;"><?= $row['ru_career'] ?></p>
                                        </td>
                                        <td style="line-height: 26px; text-align: left;">
                                          <p class="area" style="display: block; width: auto; overflow: initial;">지원 : <?= substr($row['mq_reg_dt'], 0, 10) ?></p>
                                          <p class="area" style="display: block; width: auto; overflow: initial;">열람 : <?= substr($row['view_dt'], 0, 10) ?></p>
                                        </td>
                                        <td style="line-height: 26px;">
                                          <p class="area" style="display: block; width: auto; overflow: initial;"><a href="/recruit/recruit_detail/<?= $row['rc_idx'] ?>"><?= $row['rc_name'] ?></a></p>
                                        </td>
                                        <td class="action" style="line-height: 50px;position:relative;">
                                        </td>
                                        -->
                                            <div style="display:none;">
                                                <select id="mqidx<?= $row[mq_idx] ?>" data-mq-idx="<?= $row[mq_idx] ?>" onchange="fPassFail(this);" style="padding: 7px 10px; border: 1px solid #ccc; border-radius: 5px; width: 100px;">
                                                    <option value="">접수</option>
                                                    <option value="underrev" <?= ($row[mq_status_val] == 'underrev' ? "selected" : "") ?>>심사중</option>
                                                    <option value="reserved" <?= ($row[mq_status_val] == 'reserved' ? "selected" : "") ?>>예비합격</option>
                                                    <option value="pass" <?= ($row[mq_status_val] == 'pass'    ? "selected" : "") ?>>합격</option>
                                                    <option value="fail" <?= ($row[mq_status_val] == 'fail'    ? "selected" : "") ?>>불합격</option>
                                                </select>
                                                <textarea style=" width: 100px; height: 100px; overflow: auto; border: 1px solid #ccc; border-radius: 5px; padding: 10px; resize: none; <?= ($row[mq_status_val] == 'underrev' || $row[mq_status_val] == 'pass' || $row[mq_status_val] == 'fail' ? ($row[mq_memo] == '' ? '' : 'background-color:#ccc;') : '') ?>" placeholder="메모를 입력해주세요."><?= $row[mq_memo] ?></textarea>
                                                <a href="javascript:fPassFail($('#mqidx<?= $row[mq_idx] ?>'));" style="position: absolute; right: 15px; top: 140px; font-size: 12px; background-color: #57CCAF; width: 30px; height: 22px; padding: 0; line-height: 22px; color: #fff; border-radius: 5px;">저장</a>
                                            </div>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if ($lines == 0) { ?>
                                        <tr>
                                            <td colspan="6" style="padding: 50px;">지원자 없음</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->

    <div style="position: fixed; bottom: 59px; left: 0; width: 100%; z-index: 10;">
        <div style="background-color: #57CCAF; width: 50%; float: left; height: 40px; color: #fff; text-align: center; padding-top: 10px;" onclick="fPassFailAll('pass');">합격</div>
        <div style="background-color: #ccc;    width: 50%; float: left; height: 40px; color: #fff; text-align: center; padding-top: 10px;" onclick="fPassFailAll('fail');">불합격</div>
    </div>
    <!-- bottom-nav -->
    <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
    <!-- //bottom-nav -->

</div><!-- //page -->


<script>
    function fPassFailAll(gbn) {

        // 체크된 체크박스들을 찾음
        var checkedValues = $('input[name="chk[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        // 체크된 체크박스가 0개일 경우 경고
        if (checkedValues.length === 0) {
            alert('하나 이상을 선택해 주세요.');
            return;
        } else {
            // 체크된 체크박스의 값들을 콘솔에 기록
            console.log(checkedValues);
        }

        fPassFailArray(checkedValues, gbn);
    }


    $(document).ready(function() {
        //디폴트 설정
        $("#btnDefault").click(function() {
            var rc_idx_list = "";
            if ($("[name='rc_show_yn[]']:checked").length == 0) {
                commonAlert("공개 채용공고로 설정하실 공고를 선택해주세요.", function() {});
                return;
            }

            for (var icnt = 0; icnt < $("[name='rc_show_yn[]']:checked").length; icnt++) {
                if (rc_idx_list == "") {
                    rc_idx_list = $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                } else {
                    rc_idx_list += "," + $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                }
            }

            openPopup('confirm', {
                content: '<p>공개 채용공고로 설정 하시겠습니까?</p>',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function() {
                    var params = {};
                    params["rc_idx_list"] = rc_idx_list;
                    comAjax("/compage_proc/applicant_default", params, function(ret) {
                        if (ret.code == "00") {
                            location.reload();
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
            });
        });


        $("#btnWriteList").click(function() {
            comAjax("/compage_proc/applicant_start", {}, function(ret) {
                if (ret.code == "00") {
                    location.href = "/compage/applicant_write";
                } else if (ret.code == "01") {
                    openPopup('confirm', {
                        //20220929 수정
                        content: '<p>채용공고등록은 <em>1일 5건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>' + ret.data.use_point + 'P</span>차감</p>',
                        secondButton: '유료등록',
                        secondCallback: function() {
                            comAjax("/compage_proc/applicant_start_point", {}, function(ret2) {
                                if (ret2.code == "00") {
                                    location.href = "/compage/applicant_write";
                                } else if (ret2.code == "01") {
                                    commonAlert("보유하신 포인트가 부족합니다.(보유포인트 : " + ret2.data.mm_point.format() + "P)<br/>포인트 구매후 등록부탁드립니다.", function() {});
                                } else {
                                    commonAlert(ret2.msg, function() {});
                                    return;
                                }
                            });
                        }
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });


    }); //ready

    function fSetOpen(rc_idx) {
        openPopup('confirm', {
            content: '<p>공개 채용공고로 설정 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '설정',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/applicant_open", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }

    function fSetClose(rc_idx) {
        openPopup('confirm', {
            content: '<p>비공개 채용공고로 설정 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '설정',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/applicant_close", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }


    function fSetapplicantClose(rc_idx) {
        openPopup('confirm', {
            content: '<p>마감 처리 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '마감',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/applicant_end", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }

    function fPassFail(obj) {
        openPopup('confirm', {
            content: '<p>저장 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '변경',
            secondCallback: function() {
                var params = {};
                params["mq_idx"] = $(obj).data('mq-idx');
                params["mq_status"] = $(obj).val();
                params["mq_memo"] = $(obj).parent().find('textarea').val();
                comAjax("/compage_proc/resume_pass_fail", params, function(ret) {
                    //console.log(ret);
                    if (ret.code == "00") {
                        commonAlert("변경되었습니다.", function() {
                            location.reload();
                        });
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }

    function fPassFailArray(arr, gbn) {
        openPopup('confirm', {
            content: '<p>저장 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '변경',
            secondCallback: function() {
                var params = {};
                params["mq_idx"] = arr;
                params["mq_status"] = gbn;
                //params["mq_memo"] = $(obj).parent().find('textarea').val();
                comAjax("/compage_proc/resume_pass_fail_array", params, function(ret) {
                    //console.log(ret);
                    if (ret.code == "00") {
                        commonAlert("변경되었습니다.", function() {
                            location.reload();
                        });
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }
</script>