<div class="page page-mypage-view">
  <div class="top-bar">
    <a href="/compage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">관심인재 관리</h1>
  </div><!-- //top-bar -->
  <div class="page-content">
    <div class="tab-container">
      <ul class="tab-list">
        <li class="on"><span class="tab">즐겨찾기한 인재</span></li>
        <li><a href="javascript:_replace('/compage/bookmark_person2');" class="tab">지원한 인재</a></li>
      </ul>
    </div>
    <div class="tab-panel">
      <div class="section my-bookmark">
        <div class="container">
          <h2 class="sr-text">즐겨찾기한 인재</h3>
            <div class="result-wrap">
              <!-- 목록 -->
              <ol class="result-list result-list-card">
                <?php foreach ($list as $row) :
                  $bk_type = 'RECRUIT';
                  $show_job_link = false;
                  $show_recomm_badge = false;
                  include(get_base_url() . '/application/shop/views/mobile/_partial/resume_card.php');
                endforeach; ?>
              </ol>
              <!-- //목록 -->
            </div>
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    // content-li 영역 클릭 시 인재 상세정보로 이동
    $(document).on('click', '.content-li', function(e) {
      // 즐겨찾기 버튼 클릭은 제외
      if ($(e.target).closest('.btnBookMark').length > 0) {
        return;
      }

      var ruIdx = $(this).data('ru_idx');
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        location.href = url;
      }
    });

    // 주소 정리 함수 적용 (add.js의 fRemoveBigAdrJS 사용)
    $(document).ready(function() {
      $('.area-addr[data-addr]').each(function() {
        var $this = $(this);
        var originalAddr = $this.data('addr');
        if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
          var formattedAddr = fRemoveBigAdrJS(originalAddr);
          $this.text(formattedAddr);
        }
      });
    });
  });
</script>
