<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab9"; ?>
            <?php $tab_on2 = "menu3"; ?>
            <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="board-write">
                    <form id="frm" onsubmit="return false">
                        <input type="hidden" name="proc_mode" value="<?= getProcMode($info) ?>" />
                        <input type="hidden" name="bd_idx" value="<?= getData($info, "bd_idx") ?>" />
                        <div class="form-row">
                            <div class="form-label">
                                <p>구분</p>
                            </div>
                            <div class="value">
                                <label class="form-selectbox">
                                    <label class="sr-text">구분 선택</label>
                                    <select name="bd_cate" aria-required="true" required class="js-toggle-form">
                                        <option value="">선택하세요</option>
                                        <option value="계정" <?= getData($info, "bd_cate") == "계정" ? "selected" : "" ?>>계정</option>
                                        <option value="시스템" <?= getData($info, "bd_cate") == "시스템" ? "selected" : "" ?>>시스템</option>
                                        <option value="이력서" <?= getData($info, "bd_cate") == "이력서" ? "selected" : "" ?>>이력서</option>
                                        <option value="입사지원" <?= getData($info, "bd_cate") == "입사지원" ? "selected" : "" ?>>입사지원</option>
                                        <option value="채용정보" <?= getData($info, "bd_cate") == "채용정보" ? "selected" : "" ?>>채용정보</option>
                                        <option value="서비스" <?= getData($info, "bd_cate") == "서비스" ? "selected" : "" ?>>서비스</option>
                                        <option value="이벤트" <?= getData($info, "bd_cate") == "이벤트" ? "selected" : "" ?>>이벤트</option>
                                        <option value="세금계산서발행" <?= getData($info, "bd_cate") == "세금계산서발행" ? "selected" : "" ?>>세금계산서발행</option>
                                    </select>
                                    <span class="liner"></span>
                                </label>
                                <script>
                                    (function() {
                                        var jsSelectbox = document.querySelector('.js-toggle-form');

                                        //jsSelectbox.options[0].selected = true;

                                        jsSelectbox.addEventListener('change', function() {
                                            var taxForm = document.querySelector('.tax-form');
                                            var toggleForms = document.querySelectorAll('.toggle-form');
                                            var requiredInputs = taxForm.querySelectorAll('[aria-required]');

                                            Object.values(this.options).forEach(function(opt) {
                                                if (opt.selected && opt.textContent.indexOf('세금계산서') > -1) {
                                                    taxForm.hidden = false;
                                                    requiredInputs.forEach(function(input) {
                                                        input.required = true;
                                                    });
                                                    toggleForms.forEach(function(form) {
                                                        var required = form.querySelector('[aria-required]');

                                                        form.hidden = true;

                                                        if (required) {
                                                            required.required = false;
                                                        }
                                                    });
                                                } else {
                                                    taxForm.hidden = true;
                                                    requiredInputs.forEach(function(input) {
                                                        input.required = false;
                                                    });
                                                    toggleForms.forEach(function(form) {
                                                        var required = form.querySelector('[aria-required]');

                                                        form.hidden = false;

                                                        if (required) {
                                                            required.required = true;
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    }());
                                </script>
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
                        <!-- 세금계산서 추가 폼 -->
                        <div class="form-row tax-form" hidden>
                            <div class="value">
                                <p class="sr-text">세금계산서 정보</p>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="form-label">대표자 성명</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">대표자 성명입력</span>
                                                    <input type="text" name="bizName" placeholder="대표자 성명을 입력해 주세요" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                            <td class="form-label">전화번호</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">전화번호입력</span>
                                                    <input type="text" name="bizPhone" placeholder="전화번호를 입력해 주세요" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="form-label">이메일</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">이메일입력</span>
                                                    <input type="email" name="bizEmail" placeholder="이메일을 입력해 주세요" autocomplete="off" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                            <td class="form-label">사업자등록번호</td>
                                            <td colspan="3" class="value">
                                                <label class="col">
                                                    <span class="sr-text">사업자등록번호입력</span>
                                                    <input type="text" name="bizCode" placeholder="사업자등록번호를 입력해 주세요" autocomplete="off" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="form-label">상호명</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">상호명입력</span>
                                                    <input type="text" name="bizTitle" placeholder="상호명을 입력해 주세요" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                            <td class="form-label">업태</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">업태입력</span>
                                                    <input type="text" name="bizType" placeholder="업태를 입력해 주세요" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="form-label">종목</td>
                                            <td class="value">
                                                <label>
                                                    <span class="sr-text">종목입력</span>
                                                    <input type="text" name="bizSubject" placeholder="종목을 입력해 주세요" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                            <td class="form-label">주소</td>
                                            <td colspan="3" class="value">
                                                <label>
                                                    <span class="sr-text">주소입력</span>
                                                    <input type="text" name="bizAddr" placeholder="주소를 입력해 주세요" aria-required="true" class="form-text">
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- //세금계산서 추가 폼 -->
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
                        <div class="form-row toggle-form">
                            <div class="form-label">
                                <p>첨부사진</p>
                            </div>
                            <div class="value">
                                <div class="form-file js-show-filename">
                                    <input type="text" disabled class="form-text file-name">
                                    <label class="btn-round btn-dark">
                                        <span class="sr-text">이미지 첨부하기</span>
                                        <input type="file" name="bd_img1" accept="image/*">
                                        <span>첨부하기</span>
                                    </label>
                                    <span class="preview">
                                        <?php if (getData($info, "bd_img1") != "") : ?>
                                            <input type='hidden' name='bd_img1_ori' value='<?= getData($info, "bd_img1") ?>' />
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="form-file js-show-filename">
                                    <input type="text" disabled class="form-text file-name">
                                    <label class="btn-round btn-dark">
                                        <span class="sr-text">이미지 첨부하기</span>
                                        <input type="file" name="bd_img2" accept="image/*">
                                        <span>첨부하기</span>
                                    </label>
                                    <span class="preview">
                                        <?php if (getData($info, "bd_img1") != "") : ?>
                                            <input type='hidden' name='bd_img1_ori' value='<?= getData($info, "bd_img1") ?>' />
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="form-action-wrap">
                            <button type="button" id="btnCancel" class="btn-round btn-secondary">취소</button>
                            <button type="button" id="btnSave" class="btn-round btn-tertiary"><?= getProcMode($info) == "insert" ? "작성완료" : "수정완료" ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
    $(document).ready(function() {
        $("#btnCancel").click(function() {
            history.back();
        });

        $("#btnSave").click(function() {
            var proc_mode = $("[name=proc_mode]").val();
            if ($("[name=bd_cate]").val() == "") {
                commonAlert("구분을 선택해주세요.", function() {
                    $("[name=bd_cate]").focus();
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
            fileAjax("/setting_proc/report_write", params, function(ret) {
                if (ret.code == "00") {
                    commonAlert("게시글이 " + (proc_mode == "insert" ? "작성" : "수정") + "되었습니다.", function() {
                        location.replace("/compage/qna_list");
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });
    });
</script>