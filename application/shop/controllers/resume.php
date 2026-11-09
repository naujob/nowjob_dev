<?php
class Resume extends CI_Controller
{
  # 전역변수 선언
  public $mm_idx;
  public $mm_id;

  function __construct()
  {
    parent::__construct();

    $this->load->model('membermodel', 'member_model');
    $this->load->model('settingmodel', 'setting_model');
    $this->load->model('sitemodel', 'site_model');
    $this->load->model('commonmodel', 'common_model');

    $this->mm_id = $this->user->getUserId();
    $this->mm_idx = $this->user->getUserIdx();
    $this->mm_type = $this->user->getUserType();
  }

  # 리뉴얼전 URL 처리 
  function index()
  {
    redirect('https://nowjob.co.kr/resume/hr_list');
  }
  function wlist()
  {
    redirect('https://nowjob.co.kr/resume/hr_list');
  }
  function index_map()
  {
    redirect('https://nowjob.co.kr/resume/hr_map');
  }
  function resume_detail($ru_idx = null)
  {
    if ($ru_idx === null) {
      redirect('https://nowjob.co.kr/resume/hr_list');
    }
    redirect("https://nowjob.co.kr/resume/hr_post/{$ru_idx}");
  }

  function hr_list()
  {
    // 앱을 완전히 종료했다 다시 켠 경우 클라이언트가 reset_search=1로 다시 요청한다
    // → 저장된 검색조건을 지우고, 조건 없는 원래 URL로 돌려보낸다
    if (isset($_GET['reset_search']) && $_GET['reset_search'] === '1') {
      $this->_clearResumeSearchLog();
      $query = $_GET;
      unset($query['reset_search']);
      $location = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . ($query ? '?' . http_build_query($query) : '');
      header('Location: ' . $location, true, 302);
      exit;
    }

    // URL의 page 파라미터 읽어서 정수 검증
    // URL에 page가 없으면(하단 메뉴 등으로 재진입) 마지막으로 보던 페이지(쿠키 resume_list_page)를 서버에서 바로 렌더링
    // → 클라이언트가 1페이지를 버리고 다시 조회하던 이중 조회 제거 (recruit.php::job_list()와 동일 패턴)
    if (isset($_GET['page'])) {
      $requested_page = (int) input_get('page', 1);
    } else {
      $requested_page = isset($_COOKIE['resume_list_page']) ? (int) $_COOKIE['resume_list_page'] : 1;
    }
    if ($requested_page < 1 || $requested_page > 500) {
      $requested_page = 1;
    }

    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();

    //검색어 가져오기 - 기본값으로 초기화 (클라이언트에서 sessionStorage로 복원)
    $search_txt = [
      "text" => "",
      "addr" => "",
      "category" => "",
      "work_type" => "",
      "week" => "",
      "price_type" => "",
      "sex" => "",
      "sage" => "",
      "eage" => "",
      "addrName" => "",
      "searchAreaStr" => "",
    ];

    $search_params = $this->_getSavedSearchParams();
    $has_saved_condition = $this->_hasResumeSearchCondition($search_params);
    $search_params_arg = $has_saved_condition ? $search_params : null;

    // PC 목록은 클라이언트 AJAX(search_list2025)로 다시 그리므로 서버에서 목록을 만들지 않는다
    $is_web = (getDeviceType() == "web");
    // PC 첫 화면(검색조건 없는 진입)은 서버가 목록·추천을 그린다 (조건이 있으면 JS가 AJAX로 조회)
    $pc_ssr = $is_web && isPcListSsrEntry();
    $initial_resumes = array();
    $loaded_count = 0;
    $initial_pagenation = '';
    if ($pc_ssr) {
      // 검색조건 없이 최신순 1페이지 (PC JS와 같은 pagesize 30)
      $pc_loaded = $this->member_model->getResumeSearchList2025(array(
        'pagesize' => 30, 'page' => 1, 'text' => '', 'mm_idx' => $this->mm_idx,
        'category' => '', 'work_type' => '', 'week' => '', 'price_type' => '', 'sex' => '',
        'sage' => '', 'eage' => '', 'order_by' => 'recent', 'search_addr' => array(),
        'search_addr_step' => array(), 'addrName' => '', 'searchAreaStr' => '', 'ru_work' => ''
      ));
      $initial_resumes = isset($pc_loaded['list']) ? $pc_loaded['list'] : array();
      $loaded_count = isset($pc_loaded['count']) ? (int) $pc_loaded['count'] : 0;
      $initial_pagenation = isset($pc_loaded['pagenation']) ? $pc_loaded['pagenation'] : '';
    } elseif (!$is_web) {
      $loaded = $this->_loadInitialResumes($requested_page, $search_params_arg);

      // 쿠키로 복원한 페이지가 그사이 이력서가 줄어 비었으면 1페이지로 다시 조회
      if (!isset($_GET['page']) && $requested_page > 1 && empty($loaded["list"])) {
        $requested_page = 1;
        $loaded = $this->_loadInitialResumes($requested_page, $search_params_arg);
      }
      $initial_resumes = $loaded["list"];
      $loaded_count = $loaded["count"];
    }

    $use_server_render = count($initial_resumes) > 0;

    // 초기 데이터 포맷팅
    // PC 첫 화면은 빈 목록이어도 포맷해서 파셜이 "결과 없음"을 그리게 한다
    if ($use_server_render || $pc_ssr) {
      $initial_resumes = formatResumeListRows($initial_resumes, getTruncateLength());

      // 기업회원인 경우 채팅방 정보 추가 (PC 카드는 채팅 정보를 쓰지 않으므로 모바일만)
      if (!$pc_ssr && $this->user->getUserType() == "COMP") {
        $initial_resumes = $this->member_model->addResumeChatBadgeInfo($initial_resumes, $this->mm_idx, $this->user->getUserId(), $this->user->getUserPhone());
      }
    }

    // 조건이 없을 때의 전체 개수 (마퀴 배너 "전체이력서"용, 필터와 무관한 값)
    $all_resume_count = $is_web ? 0 : $this->_countAllResumes();
    // 페이지네이션용 개수: 조건이 있으면 필터된 개수, 없으면 전체 개수
    $initial_total_count = ($has_saved_condition || $pc_ssr) ? $loaded_count : $all_resume_count;

    // 화면에 표시되지 않는 "오늘 등록된 이력서 개수"(today_recruit_count) 조회는 제거함 — hr_list.php는 today_resume_active_count만 사용
    $today_recruit_count = 0;

    // 오늘 등록·수정된 이력서 개수 조회
    // reg_dt/up_dt를 DATE()로 가공하지 않고 범위 비교해 인덱스를 쓸 수 있게 함 (recruitmodel.php::getRecruitTodayTotalCnt와 동일 패턴)
    $sql_today_active_count = "
      SELECT COUNT(DISTINCT bb.ru_idx) as total
      FROM st_member aa
      INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y'
        AND (
          (bb.reg_dt >= CURDATE() AND bb.reg_dt < CURDATE() + INTERVAL 1 DAY)
          OR (bb.up_dt >= CURDATE() AND bb.up_dt < CURDATE() + INTERVAL 1 DAY)
        )
      WHERE aa.mm_status = 'Y'
    ";
    $query_today_active_count = $this->db->query($sql_today_active_count);
    $today_active_result = $query_today_active_count->row_array();
    $today_resume_active_count = isset($today_active_result['total']) ? $today_active_result['total'] : 0;

    // 추천인재 데이터 로드 (서버사이드 렌더링)
    $reco_params = [
      "addr1" => isset($addr_info['ad_addr1']) ? $addr_info['ad_addr1'] : '',
      "addr2" => isset($addr_info['ad_addr2']) ? $addr_info['ad_addr2'] : '',
      "addr3" => isset($addr_info['ad_addr3']) ? $addr_info['ad_addr3'] : '',
      "mm_idx" => $this->mm_idx,
      "limit" => 8
    ];
    // PC 추천인재 슬라이더: 주소 조건 없이 10명 (PC JS getRecoListCommon과 같은 조건)
    if ($pc_ssr) {
      $reco_params = array("addr1" => "", "addr2" => "", "addr3" => "", "mm_idx" => $this->mm_idx, "limit" => 10);
    }
    $initial_reco = ($is_web && !$pc_ssr) ? array("list" => array()) : $this->common_model->getFavResumeList2025($reco_params);
    $has_initial_reco = isset($initial_reco['list']) && count($initial_reco['list']) > 0;

    // 추천인재 데이터 포맷팅 (search_reco_list2025()와 동일한 포맷)
    if ($has_initial_reco) {
      $initial_reco['list'] = $this->_formatRecoList($initial_reco['list']);
    }

    $params["mm_idx"] = $this->mm_idx;
    $params["page"] = 1;
    $params["pagesize"] = 100;
    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "mem"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $data["tag_list"] = $tag_info["mem"] != "" ? explode(",", $tag_info["mem"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["addr_info"] = $addr_info;
    $data["mm_type"] = $this->mm_type;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    // getHomeUpCompList(지금 뜨는 업체) 조회 제거: PC/모바일 hr_list 뷰 어디에도 $up_list를 쓰지 않음(화면에 안 쓰이는 조회였음)
    $data["initial_resumes"] = $initial_resumes;
    $data["initial_total_count"] = $initial_total_count;
    $data["all_resume_count"] = $all_resume_count;
    $data["initial_search_state"] = $is_web ? null : $this->_getInitialSearchState($search_params);
    // 저장된 조건이 목록에 적용됐는지 (앱 재실행 시 조건 초기화 판단에 사용)
    $data["has_saved_search_condition"] = (!$is_web && $has_saved_condition);
    $data["today_recruit_count"] = $today_recruit_count;
    $data["today_resume_active_count"] = $today_resume_active_count;
    $data["use_server_render"] = $use_server_render;
    $data["initial_reco"] = $initial_reco['list'];
    $data["has_initial_reco"] = $has_initial_reco;
    $data["initial_page"] = $requested_page;
    $data["pc_ssr"] = $pc_ssr;
    $data["initial_pagenation"] = $initial_pagenation;

    $this->layout->shop('/resume/hr_list', $data);
  }

  // 저장된 인재 검색조건(site_log SEARCH_RESUME)을 지운다 (세션·회원 기록 모두)
  private function _clearResumeSearchLog()
  {
    $this->site_model->deleteSiteLog(array(
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RESUME",
      "sl_step" => "검색"
    ));
    if ($this->user->isLogin()) {
      $this->site_model->deleteSiteLog(array(
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RESUME",
        "sl_step" => "검색"
      ));
    }
  }

  // 저장된 인재 검색조건(site_log SEARCH_RESUME)을 목록 조회 파라미터 형식으로 변환
  // 저장 시 "^"로 이어 붙인 값을 그대로 쓰므로 search_list2025()가 받는 형식과 같다
  private function _getSavedSearchParams()
  {
    $site_log = $this->site_model->getSiteLog(array(
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RESUME",
      "sl_step" => "검색"
    ));
    if ($site_log == null && $this->user->isLogin()) {
      $site_log = $this->site_model->getSiteLog(array(
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RESUME",
        "sl_step" => "검색"
      ));
    }

    $json_info = ($site_log != null) ? json_decode($site_log["sl_txt"], true) : null;
    if (!is_array($json_info)) {
      $json_info = array();
    }

    $get_str = function ($key) use ($json_info) {
      return (isset($json_info[$key]) && is_string($json_info[$key])) ? $json_info[$key] : "";
    };
    // 나이는 숫자만 허용 (목록 조회 조건에 그대로 들어감)
    $get_age = function ($key) use ($json_info) {
      $age = isset($json_info[$key]) ? (int) $json_info[$key] : 0;
      return $age > 0 ? (string) $age : "";
    };

    $search_area = parseSearchAreaList($get_str("searchAreaStr"));

    return array(
      "search_addr" => array_map(function ($area) { return $area["addr"]; }, $search_area),
      "search_addr_step" => array_map(function ($area) { return $area["step"]; }, $search_area),
      "searchAreaStr" => json_encode($search_area, JSON_UNESCAPED_UNICODE),
      "addrName" => $get_str("addrName"),
      "category" => $get_str("category"),
      "work_type" => $get_str("work_type"),
      "week" => $get_str("week"),
      "price_type" => $get_str("price_type"),
      "sex" => $get_str("sex"),
      "sage" => $get_age("sage"),
      "eage" => $get_age("eage"),
    );
  }

  // 첫 화면 인재 목록. 저장 조건이 있으면 목록 AJAX와 같은 모델 함수로, 없으면 조건 없는 직접 쿼리로 가져온다
  // 반환: list(원본 행), count(모델을 쓸 때만 필터된 전체 개수, 직접 쿼리는 null)
  private function _loadInitialResumes($page, $search_params)
  {
    if ($search_params !== null) {
      $result = $this->member_model->getResumeSearchList2025(array(
        "pagesize" => 20,
        "page" => $page,
        "text" => "",
        "mm_idx" => $this->mm_idx,
        "category" => $search_params["category"],
        "work_type" => $search_params["work_type"],
        "week" => $search_params["week"],
        "price_type" => $search_params["price_type"],
        "sex" => $search_params["sex"],
        "sage" => $search_params["sage"],
        "eage" => $search_params["eage"],
        "order_by" => "recent",
        "search_addr" => $search_params["search_addr"],
        "search_addr_step" => $search_params["search_addr_step"],
        "addrName" => $search_params["addrName"],
        "searchAreaStr" => $search_params["searchAreaStr"],
        "ru_work" => "",
      ));
      return array("list" => $result["list"], "count" => (int) $result["count"]);
    }

    $offset = ($page - 1) * 20;
    $sql = "
      SELECT
        bb.ru_idx, aa.mm_idx, bb.ru_name, bb.ru_work, bb.ru_price_type, bb.ru_work_type, bb.ru_week,
        bb.ru_work_img,
        COALESCE(NULLIF(aa.mm_thumb, ''), aa.mm_img1, aa.mm_img2, aa.mm_img3, '/resource/uploads/member/noimage4.png') AS ru_resume_img,
        COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr,
        aa.mm_nation, aa.mm_sex, aa.mm_birth,
        p0.pm_idx,
        GREATEST(
          CASE WHEN bb.up_dt = '0000-00-00 00:00:00' THEN bb.reg_dt ELSE bb.up_dt END,
          IFNULL(p.up_dt, '2000-01-01 00:00:00')
        ) AS up_dt,
        bb.reg_dt, bb.ru_show_yn, bb.ru_status
      FROM st_member aa
      INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y'
      LEFT JOIN st_address cc ON aa.mm_idx = cc.mm_idx AND cc.ad_type = 'RESUME' AND cc.ad_key = bb.ru_idx
      LEFT JOIN st_promotion p0 ON p0.rc_idx = bb.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y'
      LEFT JOIN st_promotion p ON p.rc_idx = bb.ru_idx AND p.pm_code IN ('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
      WHERE aa.mm_status = 'Y'
      GROUP BY bb.ru_idx
      ORDER BY p0.pm_idx DESC, GREATEST(CASE WHEN bb.up_dt = '0000-00-00 00:00:00' THEN bb.reg_dt ELSE bb.up_dt END, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC
      LIMIT $offset, 20
    ";
    return array("list" => $this->db->query($sql)->result_array(), "count" => null);
  }

  // 필터와 무관한 전체 인재 개수
  private function _countAllResumes()
  {
    $sql = "
      SELECT COUNT(DISTINCT bb.ru_idx) as total
      FROM st_member aa
      INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y'
      WHERE aa.mm_status = 'Y'
    ";
    $count_result = $this->db->query($sql)->row_array();
    return isset($count_result['total']) ? (int) $count_result['total'] : 0;
  }

  // 저장된 조건이 하나라도 걸려 있는지 (지역/업직종/근무형태/요일/급여/성별/나이)
  private function _hasResumeSearchCondition($params)
  {
    foreach (array("search_addr", "category", "work_type", "week", "price_type", "sex") as $key) {
      if (!empty($params[$key])) {
        return true;
      }
    }
    return ($params["sage"] != "" || $params["eage"] != "");
  }

  // 서버가 목록에 적용한 검색조건을 클라이언트 상태(sessionStorage) 형식으로 변환
  // 키는 main_visual.php의 getAdvancedSearchStorageKey()와 같은 규칙(jobListAdvancedSearch_resume_{userIdx|guest})
  private function _getInitialSearchState($search_params)
  {
    $is_login = $this->user->isLogin();
    $split = function ($val) {
      return ($val != "") ? explode("^", $val) : array();
    };

    $addr_name = $search_params["addrName"];
    $regions = ($addr_name != "") ? explode(", ", $addr_name) : array();
    $search_area = json_decode($search_params["searchAreaStr"], true);
    $area_count = is_array($search_area) ? count($search_area) : 0;
    if (count($regions) != $area_count) {
      $regions = array();
      if (is_array($search_area)) {
        foreach ($search_area as $area) {
          $parts = explode(" ", $area["addr"]);
          $regions[] = ($area["step"] < 3) ? end($parts) . " 전체" : end($parts);
        }
      }
    }

    $state = array(
      "userId" => $is_login ? (string) $this->user->getUserIdx() : "",
      "addrName" => implode(", ", $regions),
      "searchAreaStr" => $search_params["searchAreaStr"],
      "search_addr" => $search_params["search_addr"],
      "selectedRegions" => $regions,
      "category" => $split($search_params["category"]),
      "work_type" => $split($search_params["work_type"]),
      "week" => $split($search_params["week"]),
      "price_type" => $split($search_params["price_type"]),
      "sex" => $split($search_params["sex"]),
      "nationality" => array(),
      "sage" => $search_params["sage"],
      "eage" => $search_params["eage"],
      "priceNegotiable" => in_array("협의", $split($search_params["price_type"])),
      "workingDay" => false,
    );

    $scope_key = "jobListAdvancedSearch_resume_" . ($is_login ? $this->user->getUserIdx() : "guest");
    return array("key" => $scope_key, "state" => $state);
  }

  // 추천인재 데이터 포맷팅 (search_reco_list2025()와 동일)
  private function _formatRecoList($list)
  {
    $truncate_length = getTruncateLength();
    $formatted_list = [];
    foreach ($list as $row) {
      $ru_work = isset($row['ru_work']) ? $row['ru_work'] : '';
      $ru_work_type = isset($row['ru_work_type']) ? $row['ru_work_type'] : '';
      $ru_price_type = isset($row['ru_price_type']) ? $row['ru_price_type'] : '';
      $ru_week = isset($row['ru_week']) ? trim($row['ru_week']) : '';
      $ru_week_yn = isset($row['ru_week_yn']) ? $row['ru_week_yn'] : 'N';
      $mm_birth = isset($row['mm_birth']) ? $row['mm_birth'] : '';
      $mm_nation = isset($row['mm_nation']) ? $row['mm_nation'] : '';
      $ad_addr = isset($row['ad_addr']) ? $row['ad_addr'] : '';

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      if ($ru_week_yn !== "Y" && ($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $formatted_list[] = [
        'ru_idx' => isset($row['ru_idx']) ? $row['ru_idx'] : '',
        'mm_idx' => isset($row['mm_idx']) ? $row['mm_idx'] : '',
        'book_yn' => isset($row['book_yn']) ? $row['book_yn'] : 'N',
        'ru_name' => (isset($row['ru_name']) && mb_strlen($row['ru_name']) > $truncate_length) ? mb_substr($row['ru_name'], 0, $truncate_length) . '...' : (isset($row['ru_name']) ? $row['ru_name'] : ''),
        'ru_resume_img' => getResumeImg($row),
        'ru_work_img' => getWorkingImg($ru_work),
        'ru_work_type' => getWorkTypeFirst($ru_work_type),
        'ru_price_type' => getPriceTypeFirst($ru_price_type),
        'ru_week' => getWeekYoil($ru_week),
        'mm_nation' => $mm_nation,
        'mm_age' => getKoreanAge($mm_birth),
        'ad_addr' => removeBigAddr($ad_addr),
        'ru_my_type' => isset($row['ru_my_type']) ? getMyType($row['ru_my_type']) : ''
      ];
    }
    return $formatted_list;
  }


  function hr_map()
  {
    $data = [];
    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    //검색어 가져오기 - 기본값으로 초기화 (클라이언트에서 sessionStorage로 복원)
    $search_txt = [
      "text" => "",
      "addrName" => "",
      "searchAreaStr" => "",
      "mapLevel" => "",
      "category" => "",
      "work_type" => "",
      "week" => "",
      "price_type" => "",
      "sex" => "",
      "sage" => "",
      "eage" => "",
      "addrName" => "",
      "searchAreaStr" => "",
    ];
    $data["mm_type"] = $this->mm_type;
    $data["ad_addr"] = $addr_info["ad_addr"];
    $data["ad_gps_x"] = $addr_info["ad_gps_x"];
    $data["ad_gps_y"] = $addr_info["ad_gps_y"];
    $data["kakao_key"] = $kakao_key;
    $data["search_txt"]  = $search_txt;

    $this->layout->shop('/resume/hr_map', $data);
  }

  function hr_post($ru_idx = 0)
  {
    // 푸시알림 디버깅 로그 추가
    // error_log("푸시알림 디버깅 - hr_post 접근: ru_idx=" . $ru_idx . ", mm_type=" . $this->mm_type . ", mm_idx=" . $this->mm_idx);

    $is_ajax = $this->input->is_ajax_request();
    $isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';

    if ($ru_idx == 0 && !$isPreview) {
      // error_log("푸시알림 디버깅 - ru_idx가 0이므로 리다이렉트");
      redirect('https://nowjob.co.kr/resume/hr_list');
    }

    // 모든 사용자가 이력서 상세 접근 가능 (hr_post.php에서 프로필 블러로 제어)

    $data = [];

    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

    // site_log 우선 로드 (미리보기 시)
    if ($isPreview) {
      $list = $this->site_model->getSiteLogList(["mm_idx" => $this->mm_idx, "session_id" => session_id(), "sl_type" => "RESUME"]);
      $step1 = $this->site_model->getStepData($list, "스텝1");

      if (is_array($step1) && !empty($step1) && isset($step1["ru_name"])) {
        $step2 = $this->site_model->getStepData($list, "스텝2");
        $step3 = $this->site_model->getStepData($list, "스텝3");
        $step4 = $this->site_model->getStepData($list, "스텝4");

        // 회원 정보 조회 (미리보기용)
        $mem_info_preview = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

        $info = [
          "ru_idx" => $ru_idx,
          "mm_idx" => $this->mm_idx,
          "mm_name" => isset($mem_info_preview["mm_name"]) ? $mem_info_preview["mm_name"] : "",
          "mm_sex" => isset($mem_info_preview["mm_sex"]) ? $mem_info_preview["mm_sex"] : "",
          "mm_birth" => isset($mem_info_preview["mm_birth"]) ? $mem_info_preview["mm_birth"] : "",
          "mm_nation" => isset($mem_info_preview["mm_nation"]) ? $mem_info_preview["mm_nation"] : "",
          "mm_thumb" => isset($mem_info_preview["mm_thumb"]) ? $mem_info_preview["mm_thumb"] : "",
          "mm_img1" => isset($mem_info_preview["mm_img1"]) ? $mem_info_preview["mm_img1"] : "",
          "ru_name" => isset($step1["ru_name"]) ? $step1["ru_name"] : "",
          "ru_work" => isset($step1["ru_work"]) && is_array($step1["ru_work"]) ? arr_implode(",", $step1["ru_work"]) : (isset($step1["ru_work"]) ? $step1["ru_work"] : ""),
          "ru_price_type" => isset($step1["ru_price_type"]) && is_array($step1["ru_price_type"]) ? arr_implode(",", $step1["ru_price_type"]) : (isset($step1["ru_price_type"]) ? $step1["ru_price_type"] : ""),
          "ru_work_type" => isset($step1["ru_work_type"]) && is_array($step1["ru_work_type"]) ? arr_implode(",", $step1["ru_work_type"]) : (isset($step1["ru_work_type"]) ? $step1["ru_work_type"] : ""),
          "ru_month_price" => isset($step1["ru_month_price"]) ? intval($step1["ru_month_price"]) : 0,
          "ru_work_dt" => isset($step2["ru_work_dt"]) && trim($step2["ru_work_dt"]) != "" ? $step2["ru_work_dt"] : (isset($step4["ru_work_dt"]) ? $step4["ru_work_dt"] : ""),
          "ru_edu" => isset($step2["ru_edu"]) && trim($step2["ru_edu"]) != "" ? $step2["ru_edu"] : (isset($step4["ru_edu"]) ? $step4["ru_edu"] : ""),
          "ru_my_type" => isset($step3["ru_my_type"]) ? $step3["ru_my_type"] : (isset($step4["ru_my_type"]) ? $step4["ru_my_type"] : ""),
          "ru_desc" => isset($step4["ru_desc"]) ? $step4["ru_desc"] : "",
          "ru_thumb" => isset($step4["ru_thumb"]) ? $step4["ru_thumb"] : "",
          "ru_img1" => isset($step4["ru_img1"]) ? $step4["ru_img1"] : "",
          "ru_img2" => isset($step4["ru_img2"]) ? $step4["ru_img2"] : "",
          "ru_img3" => isset($step4["ru_img3"]) ? $step4["ru_img3"] : "",
          "ru_img4" => isset($step4["ru_img4"]) ? $step4["ru_img4"] : "",
          "ru_img5" => isset($step4["ru_img5"]) ? $step4["ru_img5"] : "",
          "ru_week_yn" => isset($step4["ru_week_yn"]) ? $step4["ru_week_yn"] : (isset($step2["ru_week_yn"]) ? $step2["ru_week_yn"] : "N"),
          "ru_week" => isset($step4["ru_week"]) ? (is_array($step4["ru_week"]) ? arr_implode(",", $step4["ru_week"]) : $step4["ru_week"]) : (isset($step2["ru_week"]) ? $step2["ru_week"] : ""),
          "ru_hit" => 0,
        ];

        // 주소 배열 구성
        $ad_addr_raw = isset($step1["ad_addr"]) ? $step1["ad_addr"] : "";
        $ad_addr_arr = is_array($ad_addr_raw) ? $ad_addr_raw : ($ad_addr_raw != "" ? [$ad_addr_raw] : []);
        $ad_zipcode_raw = isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "";
        $ad_zipcode_arr = is_array($ad_zipcode_raw) ? $ad_zipcode_raw : ($ad_zipcode_raw != "" ? [$ad_zipcode_raw] : []);
        $ad_jibun_raw = isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "";
        $ad_jibun_arr = is_array($ad_jibun_raw) ? $ad_jibun_raw : ($ad_jibun_raw != "" ? [$ad_jibun_raw] : []);
        $ad_doro_raw = isset($step1["ad_doro"]) ? $step1["ad_doro"] : "";
        $ad_doro_arr = is_array($ad_doro_raw) ? $ad_doro_raw : ($ad_doro_raw != "" ? [$ad_doro_raw] : []);
        $ad_gps_x_raw = isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "";
        $ad_gps_x_arr = is_array($ad_gps_x_raw) ? $ad_gps_x_raw : ($ad_gps_x_raw != "" ? [$ad_gps_x_raw] : []);
        $ad_gps_y_raw = isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "";
        $ad_gps_y_arr = is_array($ad_gps_y_raw) ? $ad_gps_y_raw : ($ad_gps_y_raw != "" ? [$ad_gps_y_raw] : []);

        $addr = [];
        foreach ($ad_addr_arr as $idx => $addrVal) {
          if ($addrVal == "") continue;
          $addr[] = [
            "ad_zipcode" => isset($ad_zipcode_arr[$idx]) ? $ad_zipcode_arr[$idx] : "",
            "ad_jibun" => isset($ad_jibun_arr[$idx]) ? $ad_jibun_arr[$idx] : "",
            "ad_doro" => isset($ad_doro_arr[$idx]) ? $ad_doro_arr[$idx] : "",
            "ad_addr" => $addrVal,
            "ad_gps_x" => isset($ad_gps_x_arr[$idx]) ? $ad_gps_x_arr[$idx] : "",
            "ad_gps_y" => isset($ad_gps_y_arr[$idx]) ? $ad_gps_y_arr[$idx] : "",
          ];
        }

        // 경력/자격증 파싱
        $career_list = [];
        $arr_career = isset($step3["arr_career"]) && $step3["arr_career"] != "" ? $step3["arr_career"] : (isset($step4["arr_career"]) ? $step4["arr_career"] : "");
        if ($arr_career != "") {
          foreach (explode("&&&", $arr_career) as $row) {
            $d = explode("|||", $row);
            if (count($d) >= 2) $career_list[] = ["rc_name" => $d[0], "rc_date" => $d[1]];
          }
        }
        $certi_list = [];
        if (isset($step4["arr_certi"]) && $step4["arr_certi"] != "") {
          foreach (explode("&&&", $step4["arr_certi"]) as $row) {
            $d = explode("|||", $row);
            if (count($d) >= 2) $certi_list[] = ["rc_name" => $d[0], "rc_dt" => $d[1]];
          }
        }
      } else {
        $info = $this->member_model->getResumeDetail($ru_idx, $this->mm_idx);
        $addr = $this->member_model->getAddressList(["ad_type" => "RESUME", "ad_key" => $ru_idx]);
        $career_list = $this->member_model->getResumeCareerList(["ru_idx" => $ru_idx]);
        $certi_list = $this->member_model->getResumeCertiList(["ru_idx" => $ru_idx]);
      }
    } else {
      $info = $this->member_model->getResumeDetail($ru_idx, $this->mm_idx);
      $addr = $this->member_model->getAddressList(["ad_type" => "RESUME", "ad_key" => $ru_idx]);
      $career_list = $this->member_model->getResumeCareerList(["ru_idx" => $ru_idx]);
      $certi_list = $this->member_model->getResumeCertiList(["ru_idx" => $ru_idx]);
    }

    $bookmark = $this->site_model->getBookmark(["bk_type" => "RESUME", "mm_idx" => $this->mm_idx, "bk_key" => $ru_idx]);

    if ($info == null) {
      go_location("/resume/hr_list", "인재 정보를 조회 하지 못하였습니다.");
    }

    if (!$isPreview) {
      $sqlcnt = "UPDATE st_resume SET ru_hit = ru_hit + 1 WHERE ru_idx = '" . $ru_idx . "' ";
      $querycnt = $this->db->query($sqlcnt);
    }

    # 내가본 인재
    $view_list = [];
    if (!$isPreview && $this->user->isLogin() == true) {
      if ($ru_idx != 0) {
        $ddd = $this->site_model->insertMemberView(["mv_type" => "resume", "mv_key" => $ru_idx, "mm_idx" => $this->mm_idx]);
        $view_list = $this->site_model->getMemberViewList("resume", $this->mm_idx);

        foreach ($view_list as $key => $row) {
          $view_list[$key]["ru_resume_img"] = getResumeImg($row);
          $view_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
          $view_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
          $view_list[$key]["mm_age"] = getKoreanAge($row["mm_birth"]);
          $view_list[$key]["mm_birth"] = "";
        }
      }
    }

    # 추천 인재
    $params = [
      "pagesize" => 100,
      "page" => 1,
      "gps_x" => $addr_info["ad_gps_x"],
      "gps_y" => $addr_info["ad_gps_y"],
      "mm_idx" => $this->mm_idx
    ];
    $reco_list = $this->common_model->getFavResumeList($addr_info, $params);

    # 데이터 설정
    $data["top_button"] = "btn-back";
    $info["ru_hit"] = isset($info["ru_hit"]) ? $info["ru_hit"] + 1 : 0;
    $data["view_list"] = $view_list;
    $data["reco_list"] = $reco_list["list"];
    // hr_post.php에서는 모든 게재 중인 공고를 조회 (제안 조건 없음)
    $data["recruit_list"] = $this->member_model->getRecruitActaiveListAll($this->mm_idx);
    $data["info"] = $info;
    $data["addr"] = $addr;
    $data["kakao_key"] = $kakao_key;
    $data["book_yn"] = ($bookmark == null ? "N" : "Y");
    $data["mem_info"] = $this->member_model->getMemberInfo($info["mm_idx"]);
    $data["career_list"] = $career_list;
    $data["certi_list"] = $certi_list;
    $data["is_popup"] = $is_ajax;

    // 기업회원과 인재 사이의 지원/제안 이력 확인 (양방향)
    // 다이렉트 채팅(더미 ru_idx)도 포함하도록 수정
    $data["already_requested"] = false;
    $data["chat_rm_idx"] = "";
    $data["chat_rc_idx"] = "";
    $data["chat_tab"] = "2"; // PC에서 기본값: 탭2(제안한 인재)
    if ($this->user->getUserType() == "COMP") {
      // 정식 ru_idx + 더미 ru_idx 둘 다 조회
      $dummy_ru_idx_row = $this->db->query(
        "SELECT ru_idx FROM st_resume WHERE mm_idx = ? AND ru_auto_yn = 'Y'",
        [$info['mm_idx']]
      )->row_array();
      $ru_idx_candidates = [$ru_idx];
      if ($dummy_ru_idx_row) {
        $ru_idx_candidates[] = $dummy_ru_idx_row['ru_idx'];
      }

      // rm_idx를 직접 SELECT (LEFT JOIN 제거)
      $latest_request = $this->db->query("
        SELECT rc_idx, mq_type, rm_idx FROM st_member_request
        WHERE ru_idx IN (" . implode(',', array_fill(0, count($ru_idx_candidates), '?')) . ")
        AND (
          (mq_type IN ('RESUME','RESUME_DIRECT') AND mm_idx = ?)
          OR (mq_type IN ('RECRUIT','RECRUIT_DIRECT') AND rc_idx IN (SELECT rc_idx FROM st_recruit WHERE mm_idx = ?))
        )
        ORDER BY (mq_type IN ('RECRUIT','RESUME')) DESC, mq_idx DESC LIMIT 1
      ", array_merge($ru_idx_candidates, array($this->mm_idx, $this->mm_idx)))->row_array();

      if ($latest_request && !empty($latest_request["rm_idx"])) {
        // 나간 방 확인
        $exited_rm_idxs = get_exited_rm_idxs(array($latest_request["rm_idx"]), $this->user->getUserId(), $this->user->getUserPhone());
        if (empty($exited_rm_idxs)) {
          $data["already_requested"] = true;
          $data["chat_rm_idx"] = $latest_request["rm_idx"];
          $data["chat_rc_idx"] = $latest_request["rc_idx"];
          // RESUME/RESUME_DIRECT(기업이 제안): 탭2(제안한 인재) / RECRUIT/RECRUIT_DIRECT(인재가 지원): 탭1(지원자 목록)
          $data["chat_tab"] = in_array($latest_request["mq_type"], ["RESUME","RESUME_DIRECT"]) ? "2" : "1";
        }
      }
    }

    if ($is_ajax) {
      $this->load->view('mobile/resume/hr_post', $data);
    } else {
      $this->layout->shop('/resume/hr_post', $data);
    }
  }

  function hr_list_new()
  {
    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();

    //검색어 가져오기
    $search_txt = [
      "text" => "",
      "addr" => "",
      "category" => "",
      "work_type" => "",
      "week" => "",
      "price_type" => "",
      "sex" => "",
      "sage" => "",
      "eage" => "",
      "addrName" => "",
      "searchAreaStr" => "",
    ];

    $site_log = $this->site_model->getSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RESUME",
      "sl_step" => "검색"
    ]);

    if ($site_log == null) {
      if ($this->user->isLogin()) {
        $site_log = $this->site_model->getSiteLog([
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]);
      }
    }

    if ($site_log != null) {
      $json_info = json_decode($site_log["sl_txt"], true);
      $search_txt["text"] = $json_info["text"];
      $search_txt["addr"] = $json_info["addr"];
      $search_txt["category"] = explode("^", $json_info["category"]);
      $search_txt["work_type"] = explode("^", $json_info["work_type"]);
      $search_txt["week"] = explode("^", $json_info["week"]);
      $search_txt["price_type"] = explode("^", $json_info["price_type"]);
      $search_txt["sex"] = explode("^", $json_info["sex"]);
      $search_txt["sage"] = $json_info["sage"];
      $search_txt["eage"] = $json_info["eage"];
      $search_txt["addr"] = $json_info["addr"];
      $search_txt["addrName"] = $json_info["addrName"];
      $search_txt["searchAreaStr"] = $json_info["searchAreaStr"];
    }

    $params["mm_idx"] = $this->mm_idx;
    $params["page"] = 1;
    $params["pagesize"] = 100;
    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "mem"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $data["tag_list"] = $tag_info["mem"] != "" ? explode(",", $tag_info["mem"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["addr_info"] = $addr_info;
    $data["mm_type"] = $this->mm_type;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;

    $this->layout->shop('/resume/hr_list_new', $data);
  }

  function hr_post_new($ru_idx = 0)
  {
    if ($ru_idx == 0) {
      redirect('https://nowjob.co.kr/resume/hr_list');
    }
    if ($this->mm_type !== "COMP") {
      go_location("https://nowjob.co.kr/resume/hr_list", "기업회원 전용 서비스입니다.");
    }
    $data = [];

    $addr_info = $this->user->getAddrInfo();
    $info = $this->member_model->getResumeDetail($ru_idx, $this->mm_idx);
    $addr = $this->member_model->getAddressList(["ad_type" => "RESUME", "ad_key" => $ru_idx]);
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $bookmark = $this->site_model->getBookmark(["bk_type" => "RESUME", "mm_idx" => $this->mm_idx, "bk_key" => $ru_idx]);

    if ($info == null) {
      go_location("/resume/hr_list", "인재 정보를 조회 하지 못하였습니다.");
    }

    $sqlcnt = "UPDATE st_resume SET ru_hit = ru_hit + 1 WHERE ru_idx = '" . $ru_idx . "' ";
    $querycnt = $this->db->query($sqlcnt);

    # 내가본 인재
    $view_list = [];
    if ($this->user->isLogin() == true) {
      if ($ru_idx != 0) {
        $ddd = $this->site_model->insertMemberView(["mv_type" => "resume", "mv_key" => $ru_idx, "mm_idx" => $this->mm_idx]);
        $view_list = $this->site_model->getMemberViewList("resume", $this->mm_idx);

        foreach ($view_list as $key => $row) {
          $view_list[$key]["ru_resume_img"] = getResumeImg($row);
          $view_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
          $view_list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
          $view_list[$key]["mm_age"] = getKoreanAge($row["mm_birth"]);
          $view_list[$key]["mm_birth"] = "";
        }
      }
    }

    # 추천 인재
    $params = [
      "pagesize" => 100,
      "page" => 1,
      "gps_x" => $addr_info["ad_gps_x"],
      "gps_y" => $addr_info["ad_gps_y"],
      "mm_idx" => $this->mm_idx
    ];
    $reco_list = $this->common_model->getFavResumeList($addr_info, $params);

    # 데이터 설정
    $data["top_button"] = (strpos($_SERVER['HTTP_REFERER'], 'nowjob.co.kr') !== false ? "btn-back" : "btn-home");
    $info["ru_hit"] = $info["ru_hit"] + 1;
    $data["view_list"] = $view_list;
    $data["reco_list"] = $reco_list["list"];
    $data["recruit_list"] = $this->member_model->getRecruitActaiveList($this->mm_idx);
    $data["info"] = $info;
    $data["addr"] = $addr;
    $data["kakao_key"] = $kakao_key;
    $data["book_yn"] = ($bookmark == null ? "N" : "Y");
    $data["mem_info"] = $this->member_model->getMemberInfo($info["mm_idx"]);
    $data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $info["ru_idx"]]);
    $data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $info["ru_idx"]]);
    $this->layout->shop('/resume/hr_post_new', $data);
  }
}
