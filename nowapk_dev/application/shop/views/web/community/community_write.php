<style>
    /* 카테고리 버튼 */
    .category-btn {
        border: none;
        background: none;
        padding: 0;
        margin: 0;
        font: inherit;
        cursor: pointer;
        outline: none;

        color: #666;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        text-transform: uppercase;

        border-radius: 35px;
        padding: 5px 20px;
        background-color: #fff;

    }

    .category-btn.active {
        font-weight: bold;
        color: #fff;
        background-color: #57CCAF
    }

    .category-filter {
        display: inline-flex;
        gap: 30px;
        margin-bottom: 0px;
        position: absolute;
        padding-left: 7px;
        margin-top: 10px;
    }

    /* 카테고리 버튼 끝 */
</style>
<div id="content" class="page page-community">
    <main class="page-content">
        <div class="container">
            <h2 class="primary-title">커뮤니티</h2>

            <div class="board-write">
                <form id="frm" onsubmit="return false">
                    <input type="hidden" name="proc_mode" value="<?= getProcMode($info) ?>" />
                    <input type="hidden" name="bd_idx" value="<?= getData($info, "bd_idx") ?>" />
                    <div class="form-row">
                        <div class="form-label">
                            <p>구분</p>
                        </div>
                        <div class="value">
                            <div class="category-filter">
                                <button type="button" class="category-btn <?= getData($info, "bd_cate") == "업무" ? "active" : "" ?>" data-category="업무">업무</button>
                                <button type="button" class="category-btn <?= getData($info, "bd_cate") == "일상" ? "active" : "" ?>" data-category="일상">일상</button>
                                <button type="button" class="category-btn <?= getData($info, "bd_cate") == "질문" ? "active" : "" ?>" data-category="질문">질문</button>
                                <button type="button" class="category-btn <?= getData($info, "bd_cate") == "자유" ? "active" : "" ?>" data-category="자유">자유</button>
                                <input type="hidden" name="bd_cate" value="<?= getData($info, "bd_cate") ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row toggle-form">
                        <div class="form-label">
                            <p>제목</p>
                        </div>
                        <div class="value">
                            <label>
                                <span class="sr-text">제목입력</span>
                                <input type="text" name="bd_subject" value="<?= getData($info, "bd_subject") ?>" placeholder="제목을 입력해 주세요" aria-required="true" required class="form-text">
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <p>내용</p>
                        </div>
                        <div class="value">
                            <label>
                                <span class="sr-text">내용입력</span>
                                <textarea name="bd_content" maxlength="500" placeholder="최대 500자" aria-required="true" required class="form-textarea"><?= getData($info, "bd_content") ?></textarea>
                            </label>
                        </div>
                    </div>

                    <div class="form-action-wrap">
                        <button type="button" id="btnCancel" class="btn-round btn-secondary">취소</button>
                        <button type="button" id="btnSave" class="btn-round btn-tertiary"><?= getProcMode($info) == "insert" ? "작성완료" : "수정완료" ?></button>
                    </div>
                </form>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
    $(document).ready(function() {
        // 카테고리 버튼 클릭 이벤트
        $('.category-btn').click(function() {
            var category = $(this).data('category');

            // 모든 버튼에서 active 클래스 제거
            $('.category-btn').removeClass('active');
            // 클릭된 버튼에 active 클래스 추가
            $(this).addClass('active');

            // hidden input 값 업데이트
            $('input[name="bd_cate"]').val(category);
        });

        $("#btnCancel").click(function() {
            history.back();
        });

        $("#btnSave").click(function() {
            var proc_mode = $("[name=proc_mode]").val();
            if ($("[name=bd_cate]").val() == "") {
                commonAlert("구분을 선택해주세요.", function() {
                    $('.category-btn').first().focus();
                });
                return;
            }

            if ($("[name=bd_subject]").val() == "") {
                commonAlert("제목을 입력해주세요.", function() {
                    $("[name=bd_subject]").focus();
                });
                return;
            }

            if ($("[name=bd_content]").val() == "") {
                commonAlert("내용을 입력해주세요.", function() {
                    $("[name=bd_content]").focus();
                });
                return;
            }

            if ($("[name=bd_content]").val().length < 10) {
                commonAlert("내용은 10자 이상 입력해주세요.", function() {
                    $("[name=bd_content]").focus();
                });
                return;
            }

            var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjax("/setting_proc/community_write", params, function(ret) {
                if (ret.code == "00") {
                    commonAlert("게시글이 " + (proc_mode == "insert" ? "작성" : "수정") + "되었습니다.", function() {
                        location.replace("/community/community_list");
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });
    });
</script>