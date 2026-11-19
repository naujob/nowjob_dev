<?php
// 인재 상세(hr_post) 표시값 — 뷰(resume/hr_post.php)와 JSON 응답(format=data)이 이 함수 하나로 값을 만든다.
// 반환 배열의 *_html 키는 그대로 innerHTML 에 넣는 마크업, 나머지는 글자다.
require_once(get_base_url() . '/application/shop/views/mobile/_partial/detail_v2_helpers.php');

if (!function_exists('hrPostFields')) {
  /**
   * 인재 상세 표시값 계산
   * @param array  $info             이력서 정보 (번역 적용 후)
   * @param array  $mem_info         회원 정보
   * @param array  $addr             희망지역 주소 목록
   * @param array  $career_list      경력 목록
   * @param array  $certi_list       자격증 목록
   * @param string $auto_notice_text 자동번역 안내 문구 ('' 이면 숨김)
   * @param bool   $is_own           본인 이력서 여부
   * @param string $user_type        로그인 회원 유형 (없으면 null)
   * @return array 표시값
   */
  function hrPostFields($info, $mem_info, $addr, $career_list, $certi_list, $auto_notice_text, $is_own, $user_type)
  {
    $f = array();

    // 나이·국적
    $hp_age = getKoreanAge($mem_info["mm_birth"]);
    $hp_age_txt = $hp_age == "0" ? "" : $hp_age . t("대");
    $hp_nation = trim((string) $mem_info["mm_nation"]);

    // 급여
    $ptypes = array_values(array_filter(array_map('trim', explode(",", (string) $info["ru_price_type"])), 'strlen'));
    $nego = (!$ptypes || count($ptypes) > 2 || in_array("급여협의", $ptypes));
    $pay = (!$nego && in_array("월급", $ptypes) && (int) $info["ru_month_price"] > 0) ? number_format((int) $info["ru_month_price"]) : "";

    // 근무형태·요일
    $wt = array_values(array_filter(array_map('trim', explode(",", (string) $info["ru_work_type"])), 'strlen'));
    $wt_txt = (count($wt) > 2 || in_array("근무협의", $wt)) ? t("근무협의") : ($wt ? implode("·", array_map('t', $wt)) : "-");
    $week_any = (isset($info["ru_week_yn"]) && $info["ru_week_yn"] === "Y");
    $week_src = (isset($info["ru_week"]) && trim((string) $info["ru_week"]) !== "") ? $info["ru_week"] : "월,화,수,목,금";
    $week_sum = $week_any ? array(t("무관"), "") : jdWeekSummary($week_src);

    // 희망지역
    $areas = array();
    foreach ($addr as $ad) {
      if ($ad["ad_addr"] != "") $areas[] = $ad["ad_addr"];
    }
    $area_head = '';
    $area_cond = '';
    foreach ($areas as $a) {
      $safe = htmlspecialchars($a, ENT_QUOTES, 'UTF-8');
      $short = htmlspecialchars(removeBigAddr($a), ENT_QUOTES, 'UTF-8');
      $area_head .= '<span class="jd-work addr-display-profile" data-addr="' . $safe . '">' . $short . '</span>';
      $area_cond .= '<span class="jd-work addr-item" data-addr="' . $safe . '">' . $short . '</span>';
    }
    $area_dash = $areas ? "" : "-";

    // 프로필 (본인 / 타인에 따라 내용이 다르다)
    if ($is_own) {
      $f["profile_title_html"] = $mem_info["mm_name"] . t('님');
      $f["profile_meta_html"] = $mem_info["mm_phone"] . '<span class="hp-bar"></span>' . number_format($mem_info["mm_point"]) . 'P';
    } else {
      $f["profile_title_html"] = (isset($info["ru_name"]) && trim($info["ru_name"]) != "") ? htmlspecialchars($info["ru_name"]) : getAstrik($mem_info["mm_name"]);
      $f["profile_meta_html"] = htmlspecialchars(t_list($hp_nation), ENT_QUOTES, 'UTF-8') . (($hp_nation != "" && $hp_age_txt != "") ? '<span class="hp-bar"></span>' : '') . $hp_age_txt;
    }

    // 상단 요약 칸
    $pay_html = '';
    if ($nego) {
      $pay_html = '<span class="jd-chip">' . t('급여협의') . '</span>';
    } else {
      foreach ($ptypes as $p) {
        $pay_html .= '<span class="jd-chip">' . t($p) . '</span>';
      }
      if ($pay != "") {
        $pay_html .= '<b class="jd-pay">' . $pay . '<small>' . t('원') . '</small></b>';
      }
    }
    $f["pay_html"] = $pay_html;
    $f["wt_txt_html"] = $wt_txt;
    $f["week_html"] = $week_sum[0] . ($week_sum[1] != "" ? '<span class="jd-sub">' . $week_sum[1] . '</span>' : '');
    $f["areas_html"] = $area_head . $area_dash;

    // 근무조건 목록
    $f["cond_work_html"] = jdWorkText($info["ru_work"]);
    $f["cond_areas_html"] = $area_cond . $area_dash;
    $f["cond_pay_html"] = '<span class="jd-pt">' . ($nego ? t("급여협의") : implode("·", array_map('t', $ptypes))) . '</span>'
      . ($pay != "" ? '<b class="jd-pay">' . $pay . '<small>' . t('원') . '</small></b>' : '');
    $f["cond_wt_html"] = '<span>' . $wt_txt . '</span>';
    $f["cond_nation_txt"] = $hp_nation != "" ? htmlspecialchars(t_list($hp_nation), ENT_QUOTES, 'UTF-8') : "-";
    $f["cond_age_txt"] = $hp_age_txt != "" ? $hp_age_txt : "-";
    $f["cond_week_html"] = $week_any ? t("무관") : jdWeekDays($week_src);

    // 자기소개
    $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", (string) $info["ru_desc"]))), 'strlen');
    $intro = '';
    if ($lines) {
      foreach ($lines as $l) {
        $intro .= "<p>" . $l . "</p>";
      }
    } else {
      $intro = '<p class="hp-empty">' . t('등록된 자기소개가 없습니다.') . '</p>';
    }
    $f["intro_html"] = $intro . jdTags($info["ru_my_type"]);

    // 경력·자격증
    $c = '<p class="hp-sub">' . t('경력') . '</p>';
    if ($career_list) {
      $c .= '<ul class="hp-list">';
      foreach ($career_list as $row) {
        $c .= '<li><span class="hp-ic">' . jdIcon("bag") . '</span><div class="hp-t"><b>' . $row["rc_name"] . '</b></div><i>' . $row["rc_date"] . '</i></li>';
      }
      $c .= '</ul>';
    } else {
      $c .= '<p class="hp-empty">' . t('등록된 경력이 없습니다.') . '</p>';
    }
    $c .= '<p class="hp-sub">' . t('자격증') . '</p>';
    if ($certi_list) {
      $c .= '<ul class="hp-list">';
      foreach ($certi_list as $row) {
        $c .= '<li><span class="hp-ic">' . jdIcon("award") . '</span><div class="hp-t"><b>' . $row["rc_name"] . '</b></div><i>' . $row["rc_dt"] . '</i></li>';
      }
      $c .= '</ul>';
    } else {
      $c .= '<p class="hp-empty">' . t('등록된 자격증이 없습니다.') . '</p>';
    }
    $f["career_html"] = $c;

    // 자동번역 안내
    $f["auto_notice_text"] = (string) $auto_notice_text;

    return $f;
  }
}

if (!function_exists('hrPostLabelMap')) {
  /**
   * 인재 상세 고정 라벨 번역표 (공고 쪽 jobPostLabelMap 과 같은 방식)
   * 한국어 화면이면 {중국어 => 한국어}, 중국어 화면이면 {한국어 => 중국어}
   * @return array 바꿀 글자 => 바뀔 글자
   */
  function hrPostLabelMap()
  {
    static $keys = array(
      '인재정보', '이전으로', '공유하기', '프로필 조회', '받은 제안', '관심인재', '희망급여', '근무형태',
      '근무요일', '희망지역', '이력서 내용 바로가기', '근무조건', '자기소개', '경력·자격증', '업무구분',
      '국적', '나이', '급여형태', '이 이력서 신고하기', '수정', '수정하기', '삭제', '광고', '제안', '제안하기',
      '전화', '전화하기', '즐겨찾기 추가', '닫기', '카카오톡', '다른 앱으로 공유', '등록하기', '취소',
      '보내기', '선택', '진행중인 채용공고', '신고하기', '신고사유입력', '신고사유를 작성해주세요',
      '해당게시글을 신고하시겠습니까?', '이력서 삭제', '완전 삭제하시겠습니까?', '삭제된 이력서는 복구할 수 없습니다.',
      '본인의 이력서만 가능한 기능입니다.', '기업회원만 가능한 기능입니다.', '개인회원만 가능한 기능입니다.',
      '로그인 하시겠습니까?', '로그인이 필요한 서비스입니다.', '로그인후 이용해주세요!', '회원가입', '로그인',
      '등록된 채용공고가 없습니다.', '등록된 경력이 없습니다.', '등록된 자격증이 없습니다.',
      '등록된 자기소개가 없습니다.', '원', '급여협의', '님', '로 제안을 보내시겠습니까?', '경력', '자격증',
      '내용을 입력해주세요.', '신고 처리 되었습니다.', '신고 처리시 오류가 발생하였습니다.',
    );

    $map = array();
    if (site_lang() === 'zh') {
      foreach ($keys as $ko) {
        $map[$ko] = t($ko);
      }
    } else {
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
