<style>
.angle-link {
    padding: 0;
    background: none;
}

.resume-list #btnDefault {
  display: block;
    width: 100%;
    padding: 1.3rem 0;
    background-color: white;
    color: #1B9D7D;
    border: none;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-bottom: 1px solid #E5E5E5;
}

#btnResume {
    display: block;
    width: 100%;
    padding: 1.3rem 0;
    background-color: #57CCAF;
    color: #fff;
    border: none;
    font-size: 1.3rem;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
</style>
<div class="page page-mypage">
    <div class="top-bar">
        <a href="/mypage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" style="margin-left: 1.5rem;">이력서</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form action="/">
            <div class="resume-list">
                <ul class="list">
                    <?php foreach ($resume_list as $row) : ?>
                        <li>
                            <div class="selectable-item">
                                <label class="form-checkbox">
                                    <input type="radio" name="rd_show_yn" value="<?= $row["ru_idx"] ?>" data-ru_name="<?= $row["ru_name"] ?>" <?= $row["ru_show_yn"] == "Y" ? "checked" : "" ?>>
                                    <span class="label"></span>
                                    <span class="sr-text">선택</span>
                                </label>
                                <a href="/mypage/resume_view/<?= $row["ru_idx"] ?>" class="angle-link">
                                    <strong class="resume-name"><?= $row["ru_name"] ?></strong>
                                    <?php if ($row["ru_show_yn"] == "Y") : ?>
                                        <em class="important-label">대표</em>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($resume_list) == 0) : ?>
                        <div class="is-empty">
                            <!-- <p>등록된 채용공고가 없습니다.</p> -->
                        </div>
                    <?php endif; ?>
                </ul>
                <button id="btnDefault" type="button" class="btn btn-primary">대표 이력서로 설정</button>
            </div>
            <?php if (count($resume_list) > 0) : ?>
                <div class="logs-footer">
                    <div class="container">
                        <!-- 20220929 수정 -->
                        <p class="help-msg">이력서 등록은 최대 2개까지만 가능합니다.</p>
                    </div>
                </div>
            <?php endif; ?>
            <div class="sticky-bottom view-footer">
                <a href="javascript:void(0);" id="btnResume" class="btn-action">이력서등록</a>
            </div>
        </form>

        <!-- 마이페이지 바로가기 버튼 -->
        <!-- <a href="/mypage" class="quick-mypage-link" aria-label="마이페이지로 이동"></a> -->
    </div><!-- //page-content -->
</div>


<script>
    window.addEventListener('DOMContentLoaded', function() {
        // resume_list 페이지 로드 후 히스토리 상태 저장
        history.pushState({page: 'resume_list'}, null, location.href);

        // 뒤로가기 처리
        window.addEventListener('popstate', function(e) {
            // resume_list에서 뒤로가기 → mypage로 이동
            if (!e.state || e.state.page === 'resume_list') {
                location.href = "/mypage/index";
            }
        });

        //처리
        $("#btnResume").click(function() {
            comAjax("/mypage_proc/resume_start", {}, function(ret) {
                if (ret.code == "00" && ret.data.passAuthStat == "N") {

                    openPopup('modal', {
                        content: '<div style="padding: 50px;">인증 후에 이력서를 등록 하실 수 있습니다.</div>',
                        firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; cursor: no-drop; background: #BEBFC9;" onclick="return false;">닫기</div>',
                        secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">PASS 인증</div>',
                        firstCallback: function() {},
                        secondCallback: function() {
                            location.href = "/mypage/index?gbn=pass";
                        }
                    });

                } else if (ret.code == "00") {
                    //_replace("/mypage/resume_write1");
                    location.href = "/mypage/resume_write1";
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        //이력서 설정
        $("#btnDefault").click(function() {
            var ru_name = $("[name=rd_show_yn]:checked").attr("data-ru_name");
            if ($("[name=rd_show_yn]:checked").length == 0) {
                commonAlert("공개 이력서로 설정하실 이력서를 선택해주세요.", function() {

                });
                return;
            }

            openPopup('confirm', {
                content: '<em>"' + ru_name + '"</em><br> 이력서를 공개 이력서로 설정하시겠습니까?',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function() {
                    var params = {};
                    params["ru_idx"] = $("[name=rd_show_yn]:checked").val();
                    comAjax("/mypage_proc/resume_default", params, function(ret) {
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
    });

</script>