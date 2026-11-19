<?php
// 공고 상세 화면의 표시값 (2a 단계)
// 뷰(recruit/job_post.php)와 JSON 응답(format=data)이 이 함수 하나로 값을 만들어 어긋나지 않게 한다.
// 반환 배열의 *_html 키는 그대로 innerHTML에 넣는 마크업, 나머지 키는 글자 또는 flag다.
require_once(get_base_url() . '/application/shop/views/mobile/_partial/detail_v2_helpers.php');

if (!function_exists('formatPhoneNumber')) {
  /**
   * 전화번호를 하이픈 형식으로 바꾼다 (숫자 외 문자는 제거. 형식이 맞지 않으면 숫자만 남긴 값)
   * 뷰(job_post.php)와 JSON 응답(format=data)이 함께 쓰므로 이 파일에 둔다.
   * @param string $number 원본 번호
   * @return string 표시용 번호
   */
  function formatPhoneNumber($number)
  {
    $number = preg_replace('/[^0-9]/', '', $number);

    if (preg_match('/^01[016789]\d{7,8}$/', $number)) {
      return preg_replace('/^(\d{3})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^02\d{7,8}$/', $number)) {
      return preg_replace('/^(02)(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^0[3-9]{1}[0-9]{1}\d{7,8}$/', $number)) {
      return preg_replace('/^(0\d{2})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^(15|16|18)\d{6}$/', $number)) {
      return preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $number);
    }

    return $number;
  }
}

if (!function_exists('jobPostLabelMap')) {
  /**
   * 2b: 공고 화면 고정 라벨 번역표
   * 현재 화면 언어가 중국어면 {한국어 => 중국어}, 한국어면 {중국어 => 한국어} 를 돌려준다.
   * 클라이언트는 언어를 바꿀 때 화면의 라벨 글자(텍스트 노드·aria-label 등)를 이 표로 바꾼다.
   * @return array 바꿀 글자 => 바뀔 글자
   */
  function jobPostLabelMap()
  {
    // 공고 화면에 나오는 고정 문구 (한국어 원문). 값이 바뀌는 DB 값은 여기에 넣지 않는다.
    static $keys = array(
      '공고 내용 바로가기', '구인정보', '국적', '근무요일', '근무지', '근무형태', '급여', '급여형태',
      '길찾기', '나이', '네이버지도', '다른 앱으로 공유', '닫기', '등록하기', '마감', '마감된 공고입니다',
      '모집기간', '복리후생', '삭제', '상세업무', '상세업무정보', '새 창으로 크게 보기', '성별', '수정하기',
      '업무구분', '이 공고 신고하기', '이전으로', '재등록', '전화', '전화하기', '조회수', '즐겨찾기 추가',
      '지원', '지원하기', '채용공고', '채용공고 마감', '채용공고 삭제', '채용공고 재등록', '총 즐겨찾기수',
      '취소', '카카오맵', '카카오톡', '크게 보기', '티맵', '공유하기', '신고하기', '신고사유입력',
      '네이버지도로 길찾기', '카카오맵으로 길찾기', '티맵으로 길찾기', '위치 정보가 없습니다.',
      '모바일 앱에서만 길안내가 가능합니다.', '표시할 내용이 없습니다.', '이력서 등록', '로그인후 이용해주세요!',
      '로그인 후 이용해주세요.', '본인의 공고만 가능한 기능입니다.', '기업회원만 가능한 기능입니다.',
      '개인회원만 가능한 기능입니다.', '확인', '삭제된 공고는 복구할 수 없습니다.',
    );

    $map = array();
    if (site_lang() === 'zh') {
      // 한국어 => 중국어
      foreach ($keys as $ko) {
        $map[$ko] = t($ko);
      }
    } else {
      // 중국어 => 한국어 (사전을 뒤집는다. 사전에 없는 문구는 넣지 않는다)
      $dict = site_lang_dict();
      foreach ($keys as $ko) {
        if (isset($dict[$ko]) && $dict[$ko] !== '') {
          $map[$dict[$ko]] = $ko;
        }
      }
    }
    return $map;
  }
}

if (!function_exists('jobPostFields')) {
  /**
   * 공고 상세 표시값 계산
   * @param array  $info             공고 정보 (번역 적용 후)
   * @param array  $mem_info         회원 정보 (회사명·전화번호 우선 사용)
   * @param array  $addr             공고 주소 정보
   * @param string $auto_notice_text 자동번역 안내 문구 ('' 이면 표시 안 함)
   * @return array 표시값
   */
  function jobPostFields($info, $mem_info, $addr, $auto_notice_text)
  {
    $f = array();

    // 마감·D-day
    $closed = (!empty($info["rc_edate"]) && strtotime($info["rc_edate"]) <= time());
    $dday = !empty($info["rc_edate"]) ? (int) round((strtotime(substr($info["rc_edate"], 0, 10)) - strtotime(date("Y-m-d"))) / 86400) : null;
    $f["dday_closed"] = $closed;
    $f["dday_txt"] = $closed ? t("마감") : ($dday === null ? "" : "D-" . max(0, $dday));

    // 급여: 협의 여부, 대표 급여형태, 나머지 급여형태
    $nego = strpos($info["rc_price_type"], "협의") !== false;
    $ptypes = array_values(array_filter(array_map('trim', explode(",", (string) $info["rc_price_type"])), 'strlen'));
    $prep = (isset($info["rc_price_rep"]) && in_array($info["rc_price_rep"], $ptypes)) ? $info["rc_price_rep"] : (isset($ptypes[0]) ? $ptypes[0] : "");
    $petc = array_values(array_diff($ptypes, array($prep)));
    $pay = number_format((int) $info["rc_price_pay"]);

    if ($nego) {
      $f["pay_html"] = '<span class="jd-chip">' . t('급여협의') . '</span>';
      $f["pay_detail_html"] = '<span class="jd-pt">' . t('급여협의') . '</span>';
    } else {
      $f["pay_html"] = '<span class="jd-chip">' . t($prep) . '</span><b class="jd-pay">' . $pay . '<small>' . t('원') . '</small></b>';
      $pe_html = '';
      foreach ($petc as $pe) {
        $pe_html .= '<i aria-hidden="true"></i><span class="jd-pt2">' . t($pe) . '</span>';
      }
      $f["pay_detail_html"] = '<span class="jd-pts"><span class="jd-pt">' . t($prep) . '</span>' . $pe_html . '</span><b class="jd-pay">' . $pay . '<small>' . t('원') . '</small></b>';
    }

    // 근무형태: 업무 구분을 주간/야간/2교대 행으로 나눈다
    if (preg_match_all('/<span>(.*?)<\/span>/', (string) $info["rc_work_type"], $wt_m)) {
      $wt = $wt_m[1];
    } else {
      $wt = preg_split('/\s*,\s*/', (string) $info["rc_work_type"]);
    }
    $wt = array_values(array_filter(array_unique(array_map('trim', $wt)), 'strlen'));
    $wt_nego = in_array('근무협의', $wt) || (in_array('주간', $wt) && in_array('야간', $wt) && in_array('2교대', $wt));
    $am = ($info["rc_am_stime"] != "" && $info["rc_am_etime"] != "") ? $info["rc_am_stime"] . "~" . $info["rc_am_etime"] : "";
    $pm = ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"] != "") ? $info["rc_pm_stime"] . "~" . $info["rc_pm_etime"] : "";
    $wt_rows = array();
    if (!$wt_nego) {
      if (in_array('2교대', $wt)) {
        if ($am != "") $wt_rows[] = array("주간", $am);
        if ($pm != "") $wt_rows[] = array("야간", $pm);
        if (!$wt_rows) $wt_rows[] = array("2교대", "");
      } else {
        if (in_array('주간', $wt)) $wt_rows[] = array("주간", $am);
        if (in_array('야간', $wt)) $wt_rows[] = array("야간", $pm);
      }
    }
    if ($wt_nego) {
      $wt_card = t("근무협의");
    } elseif (in_array('2교대', $wt)) {
      $wt_card = t("2교대");
    } elseif ($wt_rows) {
      $wt_card = implode("·", array_map(function ($r) { return t($r[0]); }, $wt_rows));
    } else {
      $wt_card = "-";
    }
    $wt_card_sub = (!$wt_nego && count($wt_rows) === 1) ? $wt_rows[0][1] : "";
    $f["wt_card_html"] = $wt_card . ($wt_card_sub != "" ? '<span class="jd-sub">' . $wt_card_sub . '</span>' : '');

    if ($wt_nego) {
      $f["wt_rows_html"] = '<span>' . t('근무협의') . '</span>';
    } elseif (!$wt_rows) {
      $f["wt_rows_html"] = '-';
    } else {
      $rows_html = '';
      foreach ($wt_rows as $r) {
        $rows_html .= '<span class="jd-wt-row"><span>' . t($r[0]) . '</span>' . ($r[1] != "" ? '<span class="jd-sub">' . $r[1] . '</span>' : '') . '</span>';
      }
      $f["wt_rows_html"] = $rows_html;
    }

    // 근무요일
    $week_any = (isset($info["rc_week_yn"]) && $info["rc_week_yn"] === "Y");
    $week_src = trim((string) $info["rc_week"]) !== "" ? $info["rc_week"] : "월,화,수,목,금";
    $week_sum = $week_any ? array(t("무관"), "") : jdWeekSummary($week_src);
    $f["week_html"] = $week_sum[0] . ($week_sum[1] != "" ? '<span class="jd-sub">' . $week_sum[1] . '</span>' : '');
    $f["week_days_html"] = $week_any ? t("무관") : jdWeekDays($week_src);

    // 나이
    $sage = trim((string) $info["rc_sage"]);
    $eage = trim((string) $info["rc_eage"]);
    $f["age_txt"] = ((int) $sage === 0 && (int) $eage === 0) ? t("무관") : formatAgeRange($sage, $eage);

    // 성별·국적 (비교는 한국어 원본, 화면에는 항목별 번역)
    $f["sex_txt"] = ($info["rc_sex"] == "남자,여자" || trim($info["rc_sex"]) == "" ? t("무관") : t_list($info["rc_sex"]));
    $f["nation_txt"] = ($info["rc_nation"] == "내국인,외국인" || trim($info["rc_nation"]) == "" ? t("무관") : t_list($info["rc_nation"]));

    // 회사·연락처·주소
    $comp = (isset($mem_info["mm_comp_name"]) && $mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : (isset($info["mm_comp_name"]) && $info["mm_comp_name"] ? $info["mm_comp_name"] : "-");
    $phone = (isset($mem_info["mm_phone"]) && $mem_info["mm_phone"]) ? formatPhoneNumber($mem_info["mm_phone"]) : (isset($info["mm_phone"]) && $info["mm_phone"] ? formatPhoneNumber($info["mm_phone"]) : "-");
    $addr_src = (isset($addr["ad_jibun"]) && $addr["ad_jibun"]) ? $addr : ((isset($info["ad_jibun"]) && $info["ad_jibun"]) ? $info : null);
    $jibun = $addr_src ? $addr_src["ad_jibun"] : "";
    $detail = ($addr_src && isset($addr_src["ad_detail"])) ? trim($addr_src["ad_detail"]) : "";
    $area = $jibun != "" ? removeBigAddr($jibun) : "-";
    $f["comp_txt"] = $comp;
    $f["phone_txt"] = $phone;
    $f["area_txt"] = htmlspecialchars($area, ENT_QUOTES, 'UTF-8');
    $f["addr_html"] = $jibun != "" ? htmlspecialchars($jibun, ENT_QUOTES, 'UTF-8') . ($detail != "" ? "<br>" . htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') : "") : "-";

    // 복리후생 유무, 해시태그, 자동번역 안내
    $f["has_ben"] = trim((string) $info["rc_life_type"]) !== "";
    $f["hash_html"] = jdTags($info["rc_hash"]);
    $f["auto_notice_text"] = (string) $auto_notice_text;

    return $f;
  }
}
