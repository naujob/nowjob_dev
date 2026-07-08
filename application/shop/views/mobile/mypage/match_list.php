<div class="page page-mypage-view">
  <div class="top-bar">
    <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">A.I 매칭 리스트</h1>
  </div><!-- //top-bar -->
  <div class="page-content">
    <div class="section my-bookmark">
      <div class="container">
        <?php if (!empty($groups)) : ?>
          <?php
          $week_kr = ['일', '월', '화', '수', '목', '금', '토'];
          foreach ($groups as $group) :
            if (empty($group['list'])) continue;
            $ts = strtotime($group['sent_date']);
            $date_label = date('Y.n.j', $ts) . '(' . $week_kr[date('w', $ts)] . '요일)';
          ?>
            <h2 class="match-date-header"><span><?= $date_label ?></span></h2>
            <div class="result-wrap">
              <!-- 목록 -->
              <ol class="result-list result-list-card">
                <?php foreach ($group['list'] as $row) :
                  $card_options = [
                    'show_bookmark' => false,
                    'use_js_addr' => false,
                    'use_mq_idx' => false,
                    'recomm_badge' => false,
                  ];
                  include(get_base_url() . '/application/shop/views/mobile/_partial/recruit_card_row.php');
                endforeach; ?>
              </ol>
              <!-- //목록 -->
            </div>
          <?php endforeach; ?>

          <!-- 페이징 -->
          <?php if ($total_pages > 1) : ?>
            <div class="pagination-wrapper visible" style="display:flex;justify-content:center;align-items:center;gap:5px;margin:20px 0;padding:10px 0;">
              <?php if ($page > 1) : ?>
                <a href="?page=<?= $page - 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;">◀ 이전</a>
              <?php endif; ?>
              <span style="align-self:center;font-size:14px;"><?= $page ?> / <?= $total_pages ?></span>
              <?php if ($page < $total_pages) : ?>
                <a href="?page=<?= $page + 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;">다음 ▶</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php else : ?>
          <div class="is-empty_v2" style="background:#F4F4F4;padding:40px 20px;text-align:center;">
            <p>매칭된 공고가 없습니다.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div><!-- //page-content -->
</div>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    // content-li 영역 클릭 시 채용공고 상세정보로 이동
    $(document).on('click', '.content-li', function(e) {
      // 즐겨찾기 버튼 클릭은 제외
      if ($(e.target).closest('.btnBookMark').length > 0) {
        return;
      }

      var rcIdx = $(this).data('rc_idx');
      if (rcIdx) {
        var url = '/recruit/job_post/' + rcIdx;
        location.href = url;
      }
    });

    // 주소 정리 함수 적용
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
