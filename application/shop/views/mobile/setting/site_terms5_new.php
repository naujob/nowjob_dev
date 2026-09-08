  <style>
  .ai-match-agree-check.form-checkbox .label:before {
    top: -9px;
  }
  .ai-match-agree-check.form-checkbox .label:after {
    top: -9px;
  }
  </style>
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">약관동의</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="section full-height">
            <div class="container">

                <!-- 약관제목 (체크박스 + 클릭하면 팝업) -->
                <label class="ai-match-agree-check form-checkbox" style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; cursor: pointer;">
                    <input type="checkbox" id="rd_agree_ad" name="rd_agree_ad" value="1" <?php echo (isset($mem_info["mm_agree_ad"]) && $mem_info["mm_agree_ad"]=="Y") ? 'checked' : ''; ?> onchange="toggleAdAgree();">
                    <span class="label"></span>
                    <div class="ai-match-terms-link" style="flex: 1; margin-left: 0.5rem;">
                        <a href="javascript:void(0);" role="button" onclick="openAdAgreeTermsPanel();" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: space-between;">
                            <span>맞춤형 서비스/광고 개인정보 수집 및 이용동의(선택)</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </label>

            </div>
        </div>

    </div><!-- //page-content -->

</div>

<!-- 약관 모달 팝업 -->
<div id="termsContentPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">맞춤형 서비스/광고 개인정보 수집 및 이용동의(선택)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <!-- 약관내용 -->
                <div class="terms-wrap">
                    <?php echo isset($info["adv_m"]) ? $info["adv_m"] : "약관 내용을 로드할 수 없습니다."; ?>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="termsContentPanel" class="btn btn-primary js-toggle-panel" onclick="checkAdAgreeContent();">확인</button>
            </div>
        </div>
    </div>
</div>

<script>
function openAdAgreeTermsPanel() {
    $('#termsContentPanel').addClass('on');
}

function checkAdAgreeContent() {
    var params = {};
    comAjax("/setting_proc/agree_ad", params, function(ret) {
        if (ret && ret.code === "00") {
            $('#rd_agree_ad').prop('checked', true);
            $('#termsContentPanel').removeClass('on');
        } else {
            commonAlert((ret && ret.msg) ? ret.msg : "처리에 실패했습니다.", function(){});
        }
    });
}

function toggleAdAgree() {
    var isChecked = $('#rd_agree_ad').prop('checked');
    var params = {};

    if (isChecked) {
        // 체크된 경우 - 동의
        comAjax("/setting_proc/agree_ad", params, function(ret) {
            if (ret && ret.code !== "00") {
                commonAlert((ret && ret.msg) ? ret.msg : "처리에 실패했습니다.", function(){
                    $('#rd_agree_ad').prop('checked', false);
                });
            }
        });
    } else {
        // 체크 해제된 경우 - 해제
        comAjax("/setting_proc/disagree_ad", params, function(ret) {
            if (ret && ret.code !== "00") {
                commonAlert((ret && ret.msg) ? ret.msg : "처리에 실패했습니다.", function(){
                    $('#rd_agree_ad').prop('checked', true);
                });
            }
        });
    }
}
</script>
