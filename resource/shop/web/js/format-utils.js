/**
 * 포맷팅 유틸리티 함수 모음
 * 공고, 이력서 데이터 포맷팅 (연령, 급여, 근무형태, 해시태그)
 */

/**
 * 연령 레이블 변환 (범위 표시)
 * @param {string} age - 원본 연령 문자열 (예: "40세 이상", "50세 이하")
 * @returns {string} 포맷팅된 연령 (예: "40 ~", "~ 50", "나이무관")
 */
function getConvertAgeLabel(age) {
  let cleaned = age.replaceAll(" 이상", "").replaceAll(" 이하", "");
  if (!age.includes("나이무관")) {
    if (!age.includes("이상")) {
      cleaned = "~ " + cleaned;
    }
    if (!age.includes("이하")) {
      cleaned = cleaned + " ~";
    }
  }
  // "나이무관"일 때만 사전에 걸려 번역됨. 숫자 조합("~ 50" 등)은 사전에 없어 그대로 통과
  return typeof t === "function" ? t(cleaned) : cleaned;
}

/**
 * 급여 유형 변환 (복수 타입 강조)
 * @param {string} rc_price_type - 급여 타입 (예: "일급,주급,월급")
 * @param {string} rc_price_rep - 강조할 대표 타입
 * @returns {string} HTML 형식의 급여 타입 (<em>대표</em><em class="off">기타</em>)
 */
function getConvertPriceType(rc_price_type, rc_price_rep) {
  // rc_price_type: "일급,주급", rc_price_rep: "주급"
  // 1개: 기본색 #1b9d7d
  // 2개 이상: rc_price_rep과 일치하는 것은 기본색(on), 다른 것은 회색(off)
  if (!rc_price_type) {
    return '';
  }

  const types = rc_price_type.split(',').map(type => type.trim());
  // 비교(===)는 원문 기준, 화면 표시(${})만 번역
  const tr = typeof t === "function" ? t : (s) => s;

  if (types.length === 1) {
    return `<em>${tr(types[0])}</em>`;
  }

  let result = '';
  types.forEach(type => {
    if (type === rc_price_rep) {
      result += `<em class="on">${tr(type)}</em>`;
    } else {
      result += `<em class="off">${tr(type)}</em>`;
    }
  });

  return result;
}

/**
 * 이력서 급여 타입 추출 (모든 값 처리)
 * @param {string} price_type - 급여 타입 (예: "일급,주급,월급")
 * @returns {string} 모든 급여 타입을 em 태그로 감싼 HTML
 */
function getPriceTypeJS(price_type) {
  if (!price_type) {
    return "";
  }

  const tr = typeof t === "function" ? t : (s) => s;
  if (price_type === "일급,주급,월급") {
    return `<em>${tr("급여협의")}</em>`;
  }

  const types = price_type.split(',').map(item => item.trim()).filter(item => item);

  if (types.length === 0) {
    return "";
  }

  // 모든 값을 em 태그로 감싸고 쉼표로 분리
  return types.map(type => `<em>${tr(type)}</em>`).join(', ');
}

/**
 * 이력서 급여 타입 추출 (첫 번째 값만 처리)
 * @param {string} price_type - 급여 타입 (예: "일급,주급,월급")
 * @returns {string} 첫 번째 급여 타입만 em 태그로 감싼 HTML
 */
function getPriceTypeJSFirst(price_type) {
  if (!price_type) {
    return "";
  }

  const tr2 = typeof t === "function" ? t : (s) => s;
  if (price_type === "일급,주급,월급") {
    return `<em>${tr2("급여협의")}</em>`;
  }

  const types = price_type.split(',').map(item => item.trim()).filter(item => item);

  if (types.length === 0) {
    return "";
  }

  // 첫 번째 값만 em 태그로 반환 (기본색 #1b9d7d)
  return `<em>${tr2(types[0])}</em>`;
}

/**
 * 근무 형태 추출 및 스타일 적용 (모든 값 처리)
 * @param {string} work_type - 근무 형태 문자열 (예: "주간,야간,2교대" 또는 HTML <span>주간</span><span>야간</span>)
 * @returns {string} 스타일이 적용된 근무 형태 (<em class="wtype1">주간</em><em class="wtype2">야간</em>)
 */
function getWorkType(work_type) {
  if (!work_type) {
    return "";
  }

  let items = [];

  // HTML span 형식 처리
  if (work_type.includes('<span>')) {
    const matches = work_type.match(/<span>(.*?)<\/span>/g);
    if (matches && matches.length > 0) {
      items = matches.map(m => m.replace(/<\/?span>/g, '').trim());
    }
  } else {
    // 쉼표로 구분된 일반 문자열 처리
    items = work_type.split(',').map(item => item.trim()).filter(item => item);
  }

  if (items.length === 0) {
    return "";
  }

  // 모든 값을 처리하여 HTML 생성 (비교는 원문 기준, 표시만 번역)
  const trWork = typeof t === "function" ? t : (s) => s;
  return items.map((text, index) => {
    let cls = '';
    if (text === '주간') {
      cls = 'wtype1';
    } else if (text === '야간') {
      cls = 'wtype2';
    } else if (text === '2교대') {
      cls = 'wtype3';
    }
    let result = `<em class="${cls}">${trWork(text)}</em>`;
    // 마지막 항목이 아니면 쉼표 추가
    if (index < items.length - 1) {
      result += ',';
    }
    return result;
  }).join('');
}

/**
 * 근무 형태 추출 및 스타일 적용 (첫 번째 값만)
 * @param {string} work_type - 근무 형태 문자열 (예: "주간,야간,2교대" 또는 HTML <span>주간</span><span>야간</span>)
 * @returns {string} 스타일이 적용된 근무 형태 (첫 번째 값만, <em class="wtype1">주간</em>)
 */
function getWorkTypeFirst(work_type) {
  if (!work_type) {
    return "";
  }

  let items = [];

  // HTML span 형식 처리
  if (work_type.includes('<span>')) {
    const matches = work_type.match(/<span>(.*?)<\/span>/g);
    if (matches && matches.length > 0) {
      items = matches.map(m => m.replace(/<\/?span>/g, '').trim());
    }
  } else {
    // 쉼표로 구분된 일반 문자열 처리
    items = work_type.split(',').map(item => item.trim()).filter(item => item);
  }

  if (items.length === 0) {
    return "";
  }

  // 첫 번째 값만 처리하여 HTML 생성 (비교는 원문 기준, 표시만 번역)
  const text = items[0];
  let cls = '';
  if (text === '주간') {
    cls = 'wtype1';
  } else if (text === '야간') {
    cls = 'wtype2';
  } else if (text === '2교대') {
    cls = 'wtype3';
  }
  const trWorkFirst = typeof t === "function" ? t : (s) => s;
  return `<em class="${cls}">${trWorkFirst(text)}</em>`;
}

/**
 * 근무 형태 추출 (쉼표 없음)
 * @param {string} work_type - 근무 형태 문자열 (예: "주간,야간,2교대")
 * @returns {string} 스타일이 적용된 근무 형태 (쉼표 없음, <em class="wtype1">주간</em><em class="wtype2">야간</em>)
 */
function getWorkTypeNoComma(work_type) {
  if (!work_type) {
    return "";
  }

  let items = [];

  // HTML span 형식 처리
  if (work_type.includes('<span>')) {
    const matches = work_type.match(/<span>(.*?)<\/span>/g);
    if (matches && matches.length > 0) {
      items = matches.map(m => m.replace(/<\/?span>/g, '').trim());
    }
  } else {
    // 쉼표로 구분된 일반 문자열 처리
    items = work_type.split(',').map(item => item.trim()).filter(item => item);
  }

  if (items.length === 0) {
    return "";
  }

  // 모든 값을 처리하여 HTML 생성 (쉼표 없음, 비교는 원문 기준, 표시만 번역)
  const trWorkNC = typeof t === "function" ? t : (s) => s;
  return items.map(text => {
    let cls = '';
    if (text === '주간') {
      cls = 'wtype1';
    } else if (text === '야간') {
      cls = 'wtype2';
    } else if (text === '2교대') {
      cls = 'wtype3';
    }
    return `<em class="${cls}">${trWorkNC(text)}</em>`;
  }).join('');
}

/**
 * 해시태그 추출 및 포맷팅
 * @param {string} hash - 해시태그 HTML 문자열
 * @param {number} max - 최대 표시 개수 (기본값: 10)
 * @returns {string} 포맷팅된 해시태그 HTML
 */
function getHashTagJS(hash, max = 10) {
  let result = "";
  // 서버(getMyType)가 내려준 HTML 태그를 공백으로 치환한 뒤 해시태그 토큰만 추출
  let tmp = String(hash || "").replace(/<[^>]*>/g, " ");
  let arr_hash = tmp.trim().split(/\s+/);
  arr_hash.forEach(function(txt, key) {
    if (key < max) {
      if (!txt.includes("#") && txt.trim() !== "") {
        txt = "#" + txt.trim();
      }
      if (txt.trim() !== "") {
        result += ` <p class='tag'>${txt}</p>`;
      }
    }
  });

  return result;
}

/**
 * 근무 형태 변환 (쉼표 구분, JavaScript 버전)
 * @param {string} work_type - 근무 형태 문자열 (예: "주간", "주간,야간")
 * @returns {string} 스타일이 적용된 근무 형태 (쉼표로 구분)
 */
function getWorkTypeWithColorJS(work_type) {
  if (!work_type) {
    return "";
  }

  const arr_type = work_type.split(",").map(item => item.trim()); //.map(함수):열의 각 요소를 하나씩 꺼내서 변환 후 새 배열을 반환, .trim():문자열 양쪽 공백 제거

  const trWorkColor = typeof t === "function" ? t : (s) => s;
  // 3개 이상이면 "근무협의"만 표시
  if (arr_type.length > 2) {
    return `<div class="worktype"><em class="wtype0">${trWorkColor("근무협의")}</em></div>`;
  }

  const worktype_map = {// 근무 형태에 따른 클래스 매핑
    "주간": "wtype1",
    "야간": "wtype2",
    "2교대": "wtype3",
    "근무협의": "wtype0"
  };

  let result = '<div class="worktype">';
  arr_type.forEach((txt, key) => {
    txt = txt.trim();
    const cls = worktype_map[txt] || "wtype0";
    result += `<em class="${cls}">${trWorkColor(txt)}</em>`;
    // 2개 이상일 때 마지막이 아니면 쉼표 추가
    if (arr_type.length > 1 && key < arr_type.length - 1) {
      result += ',';
    }
  });
  result += '</div>';

  return result;
}

/**
 * 안전한 trim (null/undefined 방어)
 * @param {string} value - 원본 값
 * @returns {string} trim된 문자열 또는 빈 문자열
 */
function safeTrim(value) {
  if (value === null || value === undefined) {
    return "";
  }
  return String(value).trim();
}



/**
 * 추천 인재 목록 로드 공통 함수
 * @param {Object} options - 옵션 객체
 * @param {string} options.containerId - 슬라이더 컨테이너 ID (기본: 'my-keen-slider')
 * @param {string} options.tplId - 템플릿 ID (기본: 'tpl_reco_list')
 * @param {string} options.excludeRuIdx - 제외할 이력서 idx (hr_post에서 사용)
 * @param {number} options.limit - 로드 개수 (기본: 10)
 * @param {number} options.perView - 슬라이더 perView (기본: 5)
 * @param {boolean} options.showMmIdx - mm_idx 처리 여부 (기본: false)
 */
function getRecoListCommon(options) {
  const opts = {
    containerId: 'my-keen-slider',
    tplId: 'tpl_reco_list',
    excludeRuIdx: null,
    limit: 10,
    perView: 5,
    showMmIdx: false,
    ...options
  };

  const params = {
    "addr1": "",
    "addr2": "",
    "addr3": "",
    "limit": opts.limit.toString()
  };

  if (opts.excludeRuIdx) {
    params["exclude_ru_idx"] = opts.excludeRuIdx;
  }

  comAjax("/resume_proc/search_reco_list2025", params, function(ret) {
    if (ret.code !== "00") {
      return;
    }

    const list = ret.data.list || [];
    const $container = $("#" + opts.containerId);
    $container.html("");

    list.forEach(function(row) {
      const $clone = $("#" + opts.tplId).clone();
      let $content = $clone.html();

      $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);

      if (opts.showMmIdx) {
        $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
      }

      $content = $content.replaceAll("{{book_yn}}", row.book_yn === "Y" ? "on" : "");
      $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
      $content = $content.replaceAll("{{ru_week}}", row.ru_week);
      $content = $content.replaceAll("{{distance}}", (row.distance || "") + (row.distance ? "km" : ""));
      $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);

      const nationText = safeTrim(row.mm_nation);
      if (nationText === '') {
        $content = $content.replaceAll("{{mm_nation}}", "");
        $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{mm_nation}}", nationText);
      }

      $content = $content.replaceAll("{{mm_age}}", row.mm_age + "대");
      $content = $content.replaceAll("{{ru_price_type}}", getPriceTypeJSFirst(row.ru_price_type));
      $content = $content.replaceAll("{{ru_work_type}}", getWorkTypeFirst(row.ru_work_type));
      $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
      $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));

      $clone.html($content);
      $container.append($clone.html());
    });

    // main.js의 통합 슬라이더 초기화 함수 사용
    const slideCount = document.querySelectorAll('.recomm-slider.person .slider-wrap .keen-slider__slide').length;
    if (slideCount > 0) {
      // containerId 기반으로 슬라이더 선택자 결정
      const sliderSelector = opts.containerId === 'my-keen-slider'
        ? '.recomm-slider.person'
        : '.recomm-slider.recruit';

      if (typeof initRecommendationSlider === 'function') {
        initRecommendationSlider(sliderSelector, opts.perView, opts.slideWidth);
      }
    }
  });
}


// 추천인재 카드 (메인·인재목록 PC 공용, nv_reco.css)
function renderNvPeople(list, target) {
  var pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>';
  var trNv = typeof t === "function" ? t : (s) => s;
  var html = "";
  $.each((list || []).slice(0, 8), function(i, row) {
    var url = "/resume/hr_post/" + row.ru_idx;
    var area = $.trim(fRemoveBigAdrJS(row.ad_addr)).split(/\s+/).slice(0, 2).join(" ");
    html +=
      '<article class="nv-card nv-person2">' +
      '<div class="nv-pz"><div class="nv-avatar"><img src="' + row.ru_resume_img + '" alt=""></div></div>' +
      '<div class="nv-job-body">' +
      '<div class="nv-job-top"><div class="nv-badges">' + (row.ru_work_badge || "") + '</div><span class="nv-area">' + pin + area + '</span></div>' +
      '<h3 class="nv-job-title"><a href="' + url + '" target="_blank">' + $("<div>").text(row.ru_name).html() + '</a></h3>' +
      '<ul class="nv-meta"><li>' + trNv(row.mm_nation) + '</li><li>' + row.mm_age + trNv('대') + '</li></ul>' +
      '<div class="nv-pay"><span class="nv-pay-type">' + getWorkTypeFirst(row.ru_work_type) + '</span><span class="nv-pay-amt">' + getPriceTypeJSFirst(row.ru_price_type_raw) + '</span></div>' +
      '</div>' +
      '<a class="nv-cover" href="' + url + '" target="_blank" aria-hidden="true" tabindex="-1"></a>' +
      '</article>';
  });
  $(target).html(html);
}
