// 페이지네이션 헬퍼 함수 (job_list.php, hr_list.php 공통 사용)

/**
 * 페이지네이션 HTML 생성
 * @param {number} currentPage - 현재 페이지
 * @param {number} totalPages - 전체 페이지 수
 * @returns {string} 페이지네이션 HTML
 */
function buildPaginationHtml(currentPage, totalPages) {
  if (totalPages <= 0) return '';

  var html = '';

  // 처음 페이지 버튼
  html += '<button data-page="1" ' + (currentPage === 1 ? 'disabled' : '') + '>◀◀</button>';

  // 이전 버튼
  html += '<button data-page="' + (currentPage - 1) + '" ' + (currentPage <= 1 ? 'disabled' : '') + '>◀ 이전</button>';

  // 페이지 번호 (최대 5개 표시, 현재 페이지 중심)
  var startPage = Math.max(1, currentPage - 2);
  var endPage = Math.min(totalPages, startPage + 4);
  startPage = Math.max(1, endPage - 4);

  for (var i = startPage; i <= endPage; i++) {
    var isActive = (i === currentPage) ? ' data-active="true"' : ' data-active="false"';
    html += '<button class="number"  data-page="' + i + '"' + isActive + '>' + i + '</button>';
  }

  // 다음 버튼
  html += '<button data-page="' + (currentPage + 1) + '" ' + (currentPage >= totalPages ? 'disabled' : '') + '>다음 ▶</button>';

  // 끝 페이지 버튼
  html += '<button data-page="' + totalPages + '" ' + (currentPage === totalPages ? 'disabled' : '') + '>▶▶</button>';

  return html;
}

/**
 * 페이지네이션 렌더링
 * @param {string} wrapperSelector - 페이지네이션 컨테이너 선택자
 * @param {number} currentPage - 현재 페이지
 * @param {number} totalPages - 전체 페이지 수
 * @param {string} listSelector - 리스트 컨테이너 선택자
 */
function renderPagination(wrapperSelector, currentPage, totalPages, listSelector) {
  var $wrapper = $(wrapperSelector);
  var listItemCount = $(listSelector).find("li").length;

  // 데이터 없음: 숨김
  if (totalPages <= 0 && listItemCount === 0) {
    $wrapper.removeClass('visible').html('');
    return;
  }

  // 페이지네이션 HTML 생성 및 표시
  var html = buildPaginationHtml(currentPage, totalPages);
  $wrapper.html(html).addClass('visible');

  // 페이지네이션 버튼 클릭 이벤트
  $wrapper.find('button[data-page]').on('click', function() {
    var $buttons = $wrapper.find('button.number');
    $buttons.attr('data-active', 'false');
    $(this).attr('data-active', 'true');
  });
}
