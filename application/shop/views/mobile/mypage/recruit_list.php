<div class="page page-mypage-view">
  <div class="top-bar">
    <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">지원공고 관리</h1>
  </div><!-- //top-bar -->

  <div class="page-content">
    <div class="tab-panel">
      <div class="section recruit-bookmark my-bookmark">
        <div class="container">
          <h2 class="sr-text">지원한 공고</h3>
            <div class="result-wrap">
              <!-- 목록 -->
              <ol class="result-list result-list-card">
                <?php
                $card_options = ['use_mq_idx' => true, 'use_js_addr' => true];
                foreach ($list as $row):
                  include(APPPATH . 'views/mobile/_partial/recruit_card_row.php');
                endforeach;
                ?>
              </ol>
              <!-- //목록 -->
            </div>
            <div class="sticky-bottom view-footer">
              <button id="btnCancel" type="button" class="btn btn-secondary js-remove">취소하기</button>
            </div>
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>


<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<script>
  $("#btnCancel").click(function() {
    var mq_idxs = [];

    $(".content-li.on").each(function() {
      var mq_idx = $(this).data("mq_idx");
      if (mq_idx) {
        mq_idxs.push(mq_idx);
      }
    });
    if (mq_idxs.length == 0) {
      commonAlert("취소하실 지원공고를 선택해주세요.", function() {});
      return;
    }

    openPopup('confirm', {
      content: '<em>선택한</em> 공고의<br> 지원을 취소하시겠습니까?',
      secondCallback: function() {
        var params = {};
        params["proc_type"] = "member_request";
        params["mq_idxs"] = mq_idxs;
        comAjax("/common_proc/request_cancel", params, function(ret) {
          if (ret.code == "00") {
            commonAlert("취소 되었습니다.", function() {
              location.reload();
            });
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });
      }
    });
  });

  // 공고 선택 토글 - .content-li 전체 클릭 가능하게 변경
  $(document).on('click', '.content-li', function(e) {
    // .img 클릭 시에는 선택이 아닌 페이지 이동만 수행
    if ($(e.target).closest('.img').length > 0) {
      return;
    }
    $(this).toggleClass('on');
  });

  // 이미지 클릭 시 이벤트 전파 차단하여 상위 선택 이벤트 방지
  $(document).on('click', '.img', function(e) {
    e.stopPropagation(); // 상위 요소로 이벤트 전파 방지
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      location.href = url;
    }
  });

  // 주소 처리: fRemoveBigAdrJS 함수 사용
  $(document).ready(function() {
    $('.area-addr[data-addr]').each(function() {
      var originalAddr = $(this).data('addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var processedAddr = fRemoveBigAdrJS(originalAddr);
        $(this).text(processedAddr);
      }
    });
  });
</script>