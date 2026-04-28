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
  return cleaned;
}

/**
 * 급여 유형 변환 (복수 타입 강조)
 * @param {string} rc_price_type - 급여 타입 (예: "일급,주급,월급")
 * @param {string} rc_price_rep - 강조할 대표 타입
 * @returns {string} HTML 형식의 급여 타입 (<em class="on">대표</em><em>기타</em>)
 */
function getConvertPriceType(rc_price_type, rc_price_rep) {
  const types = rc_price_type.split(',');

  if (types.length === 1) {
    return `<em class="on">${types[0]}</em>`;
  }

  return types.map(type => {
    if (type === rc_price_rep) {
      return `<em class="on">${type}</em>`;
    } else {
      return `<em>${type}</em>`;
    }
  }).join('');
}

/**
 * 이력서 급여 타입 추출 (모든 값 처리)
 * @param {string} price_type - 급여 타입 (예: "일급,주급,월급")
 * @returns {string} 모든 급여 타입을 em 태그로 감싼 HTML
 */
function getPriceType(price_type) {
  if (!price_type) {
    return "";
  }

  if (price_type === "일급,주급,월급") {
    return "<em>급여협의</em>";
  }

  const types = price_type.split(',').map(item => item.trim()).filter(item => item);

  if (types.length === 0) {
    return "";
  }

  // 모든 값을 em 태그로 감싸고 마지막 제외 쉼표 추가
  return types.map((type, index) => {
    let result = `<em>${type}</em>`;
    if (index < types.length - 1) {
      result += ',';
    }
    return result;
  }).join('');
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

  // 모든 값을 처리하여 HTML 생성
  return items.map((text, index) => {
    let cls = '';
    if (text === '주간') {
      cls = 'wtype1';
    } else if (text === '야간') {
      cls = 'wtype2';
    } else if (text === '2교대') {
      cls = 'wtype3';
    }
    let result = `<em class="${cls}">${text}</em>`;
    // 마지막 항목이 아니면 쉼표 추가
    if (index < items.length - 1) {
      result += ',';
    }
    return result;
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
  let tmp = hash;
  tmp = tmp.replaceAll("<p class=\'tag\' style=\'width:100%;text-align:right;\'>", "");
  tmp = tmp.replaceAll("</p>", "");
  let arr_hash = tmp.trim().split(" ");
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
