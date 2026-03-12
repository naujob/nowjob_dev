<style>
  /* 최근본공고 페이지의 뒤로가기 버튼 위치 조정 */
  .page-recruit .top-bar .btn-replace {
    left: -10px;
  }

  /* iOS 스크롤 활성화 */
  .page-recruit,
  .page-content {
    -webkit-overflow-scrolling: touch;
    touch-action: pan-y;
  }
</style>
<div class="page-recruit">

  <div class="top-bar">
    <a href="javascript:history.back();" class="btn-replace"><span class="sr-text">최근본공고</span></a>
    <h1 class="title" id="rm_subject">최근본공고</h1>
  </div>


  <div class="page-content" style="padding-bottom: 0;">

    <div class="section is-last">
      <div class="container">
        <h2 class="sr-text">채용공고</h2>
        <!-- 목록 -->
        <ol class="result-list result-list-card" id="ajaxList" data-page="1">
          <?php if (!empty($view_list)) { ?>
            <?php
            $card_options = ['use_js_addr' => true];
            foreach ($view_list as $row):
              include(APPPATH . 'views/mobile/_partial/recruit_card_row.php');
            endforeach;
            ?>
          <?php } else { ?>
            <?php if ($this->user->isLogin()) : ?>
              <li style="margin-top: 50%; text-align: center; color: #777;">최근 본 공고가 없습니다.</li>
            <?php else : ?>
              <li style="margin-top: 50%; text-align: center; color: #777;">로그인 하시면,<br> 최근 본 공고를 확인 하실 수 있습니다.</li>
            <?php endif; ?>
          <?php } ?>
        </ol>
        <!-- //목록 -->
        <!-- 빈목록 -->
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
        <!-- //빈목록 -->
      </div>
    </div>

  </div><!-- //page-content -->

</div>
<script>
  $(document).ready(function() {
    // iOS 스크롤 활성화
    $('body').css({
      'overflow': 'auto',
      'overflow-y': 'auto',
      '-webkit-overflow-scrolling': 'touch'
    });
    
    $('html').css({
      'overflow': 'auto',
      'overflow-y': 'auto'
    });
    
    // 스크롤 컨테이너에도 iOS 스크롤 적용
    ['.page-recruit', '.page-content'].forEach(function(selector) {
      var $container = $(selector);
      if ($container.length && $container.css('overflow') !== 'hidden') {
        $container.css({
          'overflow-y': 'auto',
          '-webkit-overflow-scrolling': 'touch',
          'touch-action': 'pan-y'
        });
      }
    });
    
    // 주소 변환 처리 - fRemoveBigAdrJS 함수 사용
    $('.summary.area p[data-addr]').each(function() {
      var originalAddr = $(this).attr('data-addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var convertedAddr = fRemoveBigAdrJS(originalAddr);
        $(this).text(convertedAddr);
      }
    });
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      location.href = url;
    }
  });
</script>