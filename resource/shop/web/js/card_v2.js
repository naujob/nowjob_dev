/* PC 목록 카드: 카드 누르면 상세 새 창, 고르는 화면은 사진 체크로 선택 + 아래 선택 바 */
(function ($) {
  function cards($list) {
    return $list.find('.nc-card');
  }

  function picked($list) {
    return cards($list).filter('.is-on');
  }

  function setOn($card, on) {
    $card.toggleClass('is-on', on).find('.nc-chk input').prop('checked', on);
  }

  // 선택 바 문구·전체 선택 상태 갱신
  function update($list) {
    var id = $list.attr('id');
    var $bar = $('.nc-selbar[data-list="#' + id + '"]');
    var $all = $('.nc-all[data-list="#' + id + '"]');
    var $on = picked($list);
    var n = $on.length;
    if ($bar.length) {
      var txt;
      if ($list.data('select') === 'single') {
        txt = n ? '<b>' + $('<i>').text($on.first().find('.nc-title').text()).html() + '</b> ' + t('인재를 골랐어요') : '';
      } else {
        txt = '<b>' + n + '</b>' + ($bar.data('unit') || t('건')) + ' ' + t('선택됨');
      }
      $bar.find('.nc-seln').html(txt);
      $bar.toggleClass('is-empty', !n);
    }
    if ($all.length) {
      var total = cards($list).length;
      $all.toggleClass('is-on', total > 0 && n === total);
    }
  }

  $(document).on('click', '.nc-list .nc-card', function (e) {
    if ($(e.target).closest('.btnBookMark, a, button').length) return;
    var $card = $(this);
    var $list = $card.closest('.nc-list');
    var mode = $list.data('select');
    if (mode) {
      e.preventDefault();
      var on = !$card.hasClass('is-on');
      if (mode === 'single') cards($list).each(function () { setOn($(this), false); });
      setOn($card, on);
      update($list);
      return;
    }
    if (typeof window.ncBeforeOpen === 'function' && window.ncBeforeOpen($card) === false) return;
    var href = $card.data('href');
    if (href) window.open(href, '_blank');
  });

  $(document).on('click', '.nc-all', function () {
    var $list = $($(this).data('list'));
    var all = picked($list).length === cards($list).length;
    cards($list).each(function () { setOn($(this), !all); });
    update($list);
  });

  $(document).on('click', '.nc-selbar .nc-clear', function () {
    var $list = $($(this).closest('.nc-selbar').data('list'));
    cards($list).each(function () { setOn($(this), false); });
    update($list);
  });

  // 페이지 코드에서 쓰는 도구: 고른 카드 목록, 선택 초기화
  window.ncCard = {
    picked: function (sel) { return picked($(sel)); },
    clear: function (sel) { var $l = $(sel); cards($l).each(function () { setOn($(this), false); }); update($l); },
    update: function (sel) { update($(sel)); }
  };

  $(function () {
    $('.nc-list[data-select]').each(function () { update($(this)); });
  });
})(jQuery);
