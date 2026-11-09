<?php
class Recruit extends CI_Controller
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
    $this->load->model('recruitmodel', 'recruit_model');

    $this->mm_id = $this->user->getUserId();
    $this->mm_idx = $this->user->getUserIdx();
    $this->mm_type = $this->user->getUserType();
  }

  # 채용목록(시급적용)
  function job_list_new()
  {

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
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);

    if ($site_log == null) {
      if ($this->user->isLogin()) {
        $site_log = $this->site_model->getSiteLog([
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RECRUIT",
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

    $data["mm_type"] = $this->mm_type;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;

    $this->layout->shop('/recruit/job_list_new', $data);
  }

  # 채용공고(시급적용)
  function job_post_new($rc_idx = null)
  {
    if ($rc_idx == null) {
      redirect('https://nowjob.co.kr/recruit/job_list');
    }
    $data = [];

    $os_type = isset($data["os_type"]) ? $data["os_type"] : "";
    $info = $this->recruit_model->getRecruitDetail($rc_idx, $this->mm_idx);
    $addr_info = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $bookmark = $this->site_model->getBookmark(["bk_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "bk_key" => $rc_idx]);

    // 비공개(rc_show_yn), 삭제·정지(rc_status), 마감(rc_edate 경과) 공고는 주소로도 열지 않는다
    if ($info == null || $info["rc_show_yn"] != "Y" || $info["rc_status"] != "Y" || strtotime($info["rc_edate"]) < time()) {
      go_location("/recruit/job_list", "채용공고를 조회 하지 못하였습니다.");
    }

    $sqlcnt = "UPDATE st_recruit SET rc_hit = rc_hit + 1 WHERE rc_idx = '" . $rc_idx . "' ";
    $querycnt = $this->db->query($sqlcnt);

    # 내가본 채용공고
    $view_list = [];
    if ($this->user->isLogin() == true) {
      if ($rc_idx != 0) {
        $ddd = $this->site_model->insertMemberView(["mv_type" => "recruit", "mv_key" => $rc_idx, "mm_idx" => $this->mm_idx]);
        $view_list = $this->recruit_model->getMemberViewList($this->mm_idx);
      }

      foreach ($view_list as $key => $row) {
        $view_list[$key]["rt_recruit_img"] = getRecruitImg($row);
        $view_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
        $view_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
        $rc_price_rep = isset($row["rc_price_rep"]) ? $row["rc_price_rep"] : "";
        $view_list[$key]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $rc_price_rep);
        $view_list[$key]["rt_price"] = getConvertPrice($row["rc_price_pay"], $row["rc_price_type"]);
      }
    }

    # 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
    if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
      $info["rc_img99"] = getRecruitImg($info, "detail");
    } else {
      $info["rc_img99"] = "";
    }

    $info["rc_hit"] = $info["rc_hit"] + 1;

    # 현재 공고 급여정보 처리 (뷰에서 직접 헬퍼함수 호출 대신 컨트롤러에서 처리)
    if (strpos($info["rc_price_type"], "협의") === false) {
      $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
      $info["rt_price_type_html"] = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
      $info["rt_price_html"] = getConvertPrice($info["rc_price_pay"], $info["rc_price_type"]);
    }

    $data["top_button"] = (strpos($_SERVER['HTTP_REFERER'], 'nowjob.co.kr') !== false ? "btn-back" : "btn-home");
    $data["os_type"] = $os_type;
    $data["view_list"] = $view_list;
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y", "ru_status" => "Y"]);
    $data["info"] = $info;
    $data["addr"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["book_yn"] = ($bookmark == null ? "N" : "Y");
    $data["mem_info"] = $this->member_model->getMember(["mm_idx" => $info["mm_idx"]]);
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/job_post_new', $data);
  }

  # 채용지도(시급적용)
  function job_map_new()
  {

    $data = [];

    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

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
      "mapLevel" => "",
    ];

    $site_log = $this->site_model->getSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);

    if ($site_log == null) {
      if ($this->user->isLogin()) {
        $site_log = $this->site_model->getSiteLog([
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RECRUIT",
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
      $search_txt["mapLevel"] = $json_info["mapLevel"];
    }

    $page = input_post("page", 1);
    $param['text'] = input_post("text", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $param['category'] = isset($_POST["category"]) ? implode("^", input_post("category", "")) : "";
    $param['work_type'] = isset($_POST["work_type"]) ? implode("^", input_post("work_type", "")) : "";
    $param['week'] = isset($_POST["week"]) ? implode("^", input_post("week", "")) : "";
    $param['price_type'] = isset($_POST["price_type"]) ? implode("^", input_post("price_type", "")) : "";
    $param['sex'] = isset($_POST["sex"]) ? implode("^", input_post("sex", "")) : "";
    $param['sage'] = input_post("sage", "");
    $param['eage'] = input_post("eage", "");
    $save_yn = input_post("save_yn", "N");

    $data["addr_info"] = $addr_info["ad_addr"];
    $data["ad_gps_x"] = $addr_info["ad_gps_x"];
    $data["ad_gps_y"] = $addr_info["ad_gps_y"];
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/job_map_new', $data);
  }

  # 지금 뜨는 업체 - 목록(시급적용)
  function companies_new()
  {
    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $mm_idx = $this->user->getUserIdx();

    # 추천 공고 : 웹용
    $params["addr1"] = "";
    $params["addr2"] = "";
    $params["addr3"] = "";
    $params["mm_idx"] = $this->mm_idx;

    $reco_list = $this->common_model->getFavRecruitWebList($params);
    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "comp"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $data["reco_list"] = $reco_list["list"];
    $data["tag_list"] = $tag_info["comp"] != "" ? explode(",", $tag_info["comp"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y"]);
    $data["addr_info"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");

    # 뜨는 업체
    $addr_info = $this->user->getAddrInfo();
    $up_list = $this->common_model->getHomeUpCompList($addr_info, 6);
    $data["up_list"] = $up_list;
    $up_list2 = $this->common_model->getHomeUpCompList($addr_info, 7);
    $data["up_list2"] = $up_list2;
    $data["all_list"] = $this->clistAll();


    $this->layout->shop('/recruit/companies_new', $data);
  }

  # 지금 뜨는 업체 - 채용목록(시급적용)
  function company_new($comp_idx = null)
  {
    if ($comp_idx === null) {
      redirect('https://nowjob.co.kr/recruit/companies');
    }
    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $mm_idx = $this->user->getUserIdx();

    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "comp"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $comp_info = $this->member_model->getMember(["mm_idx" => $comp_idx]);
    $data["comp_name"] = $comp_info['mm_comp_name'];
    $data["comp_thumb"] = $comp_info['mm_thumb'];

    $data["tag_list"] = $tag_info["comp"] != "" ? explode(",", $tag_info["comp"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y"]);
    $data["addr_info"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["comp_idx"] = $comp_idx;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/company_new', $data);
  }

  # 내가본공고(시급적용)
  function job_view_list_new()
  {
    $data = [];

    $view_list = $this->recruit_model->getMemberViewList($this->mm_idx);
    foreach ($view_list as $key => $row) {
      $view_list[$key]["rt_recruit_img"] = getRecruitImg($row);
      $view_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
      $view_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
    }
    $data["view_list"] = $view_list;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/job_view_list_new', $data);
  }


  # 내가본공고
  function job_view_list()
  {
    $data = [];

    $view_list = $this->recruit_model->getMemberViewList($this->mm_idx);
    foreach ($view_list as $key => $row) {
      $view_list[$key]["rt_recruit_img"] = getRecruitImg($row);
      $view_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
      $view_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
    }
    $data["view_list"] = $view_list;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/job_view_list', $data);
  }

  # 채용목록
  function job_list()
  {
    // 앱을 완전히 종료했다 다시 켠 경우 클라이언트가 reset_search=1로 다시 요청한다
    // → 저장된 검색조건을 지우고, 조건 없는 원래 URL로 돌려보낸다
    if (isset($_GET['reset_search']) && $_GET['reset_search'] === '1') {
      $this->_clearSearchRecruitLog();
      $query = $_GET;
      unset($query['reset_search']);
      $location = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . ($query ? '?' . http_build_query($query) : '');
      header('Location: ' . $location, true, 302);
      exit;
    }

    // PC 화면은 목록을 AJAX로 따로 불러오므로 서버에서 미리 조회한 목록을 쓰지 않는다
    $is_web = (getDeviceType() == "web");

    // 첫 화면에 적용할 검색조건: 주소(URL) 검색조건 > 저장된 조건(site_log)
    // 같은 조건으로 체크박스·칩을 그리고 목록도 조회해서, 화면 표시와 목록이 항상 일치하게 한다
    // (예전에는 목록만 조건 없이 그린 뒤 클라이언트가 sessionStorage 조건으로 다시 조회 → 이중 조회 + 깜빡임)
    $url_search_state = $this->_getUrlSearchState();
    $search_state = ($url_search_state != null) ? $url_search_state["state"] : $this->_getSavedSearchState();
    $skip_search_state_load = false;

    // 급여 파라미터(price_type)가 있으면 급여 조건만 덮어씀
    $price_type_param = input_get("price_type", "");
    if ($price_type_param !== "" && $price_type_param !== null) {
      $price_type_values = [];
      if (is_array($price_type_param)) {
        $price_type_values = $price_type_param;
      } else {
        $price_type_values = preg_split("/[\^,]/", $price_type_param);
      }
      $price_type_values = array_values(array_filter(array_map("trim", $price_type_values), "strlen"));
      if (!empty($price_type_values)) {
        $search_state["price_type"] = $price_type_values;
        $search_state["priceNegotiable"] = in_array("협의", $price_type_values);
        $skip_search_state_load = true;
      }
    }

    // 상세검색 패널(index_search.php)이 체크박스를 그릴 때 쓰는 형식
    $search_txt = [
      "text" => "",
      "addr" => "",
      "category" => $search_state["category"],
      "work_type" => $search_state["work_type"],
      "week" => $search_state["week"],
      "price_type" => $search_state["price_type"],
      "sex" => $search_state["sex"],
      "nationality" => $search_state["nationality"],
      "sage" => $search_state["sage"],
      "eage" => $search_state["eage"],
      "addrName" => $search_state["addrName"],
      "searchAreaStr" => $search_state["searchAreaStr"],
    ];

    // URL의 page 파라미터 읽어서 정수 검증
    // URL에 page가 없으면(하단 메뉴 등으로 재진입) 마지막으로 보던 페이지(쿠키 recruit_list_page)를 서버에서 바로 렌더링
    // → 클라이언트가 1페이지를 버리고 다시 조회하던 이중 조회 제거
    if (isset($_GET['page'])) {
      $requested_page = (int) input_get('page', 1);
    } else {
      $requested_page = isset($_COOKIE['recruit_list_page']) ? (int) $_COOKIE['recruit_list_page'] : 1;
    }
    if ($requested_page < 1 || $requested_page > 500) {
      $requested_page = 1;
    }

    $addr_info = $this->user->getAddrInfo();

    // 초기 채용공고 데이터 로드 및 포맷팅 (검색조건 적용 — AJAX getRecruitList()와 같은 파라미터 형식)
    $search_area = $this->_parseSearchArea($search_state["searchAreaStr"]);
    $initial_params = [
      'page' => $requested_page,
      'pagesize' => 20,
      'mm_idx' => $this->mm_idx,
      'text' => '',
      'category' => implode("^", $search_state["category"]),
      'work_type' => implode("^", $search_state["work_type"]),
      'week' => implode("^", $search_state["week"]),
      'price_type' => implode("^", $search_state["price_type"]),
      'sex' => implode("^", $search_state["sex"]),
      'nationality' => implode("^", $search_state["nationality"]),
      'sage' => $search_state["sage"],
      'eage' => $search_state["eage"],
      'order_by' => 'recent',
      'search_addr' => array_map(function ($area) { return $area["addr"]; }, $search_area),
      'search_addr_step' => array_map(function ($area) { return $area["step"]; }, $search_area),
      'compidx' => '',
      // 유료 공고 정렬 seed (페이지 간 순서 유지, 진입마다 변경)
      'seed' => resolveRecruitListSeed($requested_page)
    ];
    // PC 첫 화면(검색조건 없는 진입)은 서버가 목록·추천을 그린다 (조건이 있으면 JS가 URL 조건으로 AJAX 조회)
    $pc_ssr = $is_web && isPcListSsrEntry();
    $pc_reco_list = array();
    $initial_recruits = ['list' => [], 'count' => 0];
    if ($pc_ssr) {
      // 검색조건 없이 최신순 1페이지 (PC JS와 같은 pagesize 30)
      $initial_recruits = $this->recruit_model->getRecruitList(array(
        'page' => 1, 'pagesize' => 30, 'mm_idx' => $this->mm_idx, 'text' => '',
        'category' => '', 'work_type' => '', 'week' => '', 'price_type' => '', 'sex' => '',
        'nationality' => '', 'sage' => '', 'eage' => '', 'order_by' => 'recent',
        'search_addr' => array(), 'search_addr_step' => array(), 'compidx' => '',
        'seed' => resolveRecruitListSeed(1)
      ));
    } elseif (!$is_web) {
      $initial_recruits = $this->recruit_model->getRecruitList($initial_params);
      // 쿠키로 복원한 페이지가 그사이 공고가 줄어 비었으면 1페이지로 다시 조회
      if (!isset($_GET['page']) && $requested_page > 1 && empty($initial_recruits['list'])) {
        $requested_page = 1;
        $initial_params['page'] = 1;
        $initial_params['seed'] = resolveRecruitListSeed(1);
        $initial_recruits = $this->recruit_model->getRecruitList($initial_params);
      }
    }
    $use_server_render = isset($initial_recruits['list']) && is_array($initial_recruits['list']) && count($initial_recruits['list']) > 0;

    // 초기 데이터 포맷팅 (AJAX getRecruitList()와 같은 함수 사용 → 첫 화면과 페이지 이동 후 카드가 같음)
    // PC 카드는 채팅 배지를 쓰지 않으므로 PC 첫 화면에서는 addChatBadgeInfo를 생략한다
    if ($use_server_render || $pc_ssr) {
      $initial_recruits['list'] = formatRecruitListRows($initial_recruits['list'], getTruncateLength());
      if (!$pc_ssr) {
        $initial_recruits['list'] = $this->recruit_model->addChatBadgeInfo(
          $initial_recruits['list'],
          $this->user->getUserType(),
          $this->mm_idx,
          $this->user->getUserId(),
          $this->user->getUserPhone()
        );
      }
    }

    // PC 첫 화면 추천공고 (주소 조건 없음 — PC JS getRecoList()와 같은 조건, limit 24)
    if ($pc_ssr) {
      $pc_reco = $this->recruit_model->getRecruitRecoList(array(
        'addr1' => '', 'addr2' => '', 'addr3' => '', 'mm_idx' => $this->mm_idx, 'limit' => 24
      ));
      $pc_reco_list = $this->_formatRecruitRecoList(isset($pc_reco['list']) ? $pc_reco['list'] : array());
    }

    // 목록 개수(페이지네이션용): 검색조건이 적용된 count
    $initial_total_count = isset($initial_recruits['count']) ? (int) $initial_recruits['count'] : 0;

    // 전체 채용공고 개수(상단 배너용): 검색조건이 없으면 위 count를 재사용, 있으면 조건 없는 건수를 따로 셈
    $all_recruit_count = $initial_total_count;
    if (!$is_web && $this->_hasSearchCondition($search_state)) {
      $all_recruit_count = $this->recruit_model->getRecruitActiveTotalCnt();
    }

    // 오늘 등록된 공고 건수 조회
    $today_recruit_count = $this->recruit_model->getRecruitTodayTotalCnt();

    $data["mm_type"] = $this->mm_type;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    $data["skip_search_state_load"] = $skip_search_state_load;

    // 지금 뜨는 업체 데이터 로드 및 포맷팅
    $up_list = $this->common_model->getHomeUpCompList($addr_info, 0);
    $data["up_list"] = $up_list; // PC 웹 호환성 유지
    $data["hot_companies"] = $this->_formatHotCompanies($up_list);

    $data["initial_recruits"] = $initial_recruits;
    $data["pc_ssr"] = $pc_ssr;
    $data["pc_reco_list"] = $pc_reco_list;
    $data["initial_total_count"] = $initial_total_count;
    $data["all_recruit_count"] = $all_recruit_count;
    $data["today_recruit_count"] = $today_recruit_count;
    $data["use_server_render"] = $use_server_render;
    $data["initial_page"] = $requested_page;
    // 서버가 목록에 적용한 검색조건 → 화면이 sessionStorage에 넣고 그대로 이어 씀 (진입 시 재조회 없음)
    $is_login = $this->user->isLogin();
    $data["initial_search_state"] = [
      "key" => "jobListAdvancedSearch_recruit_" . ($is_login ? $this->user->getUserIdx() : "guest"),
      "state" => $search_state,
    ];
    // 저장된 조건(URL 조건이 아닌)이 목록에 적용됐는지 (앱 재실행 시 조건 초기화 판단에 사용)
    $data["has_saved_search_condition"] = (!$is_web && $url_search_state == null && !$skip_search_state_load && $this->_hasSearchCondition($search_state));

    $this->layout->shop('/recruit/job_list', $data);
  }

  // PC 추천공고 슬라이더용 포맷 (recruit_proc::getRecruitRecoList와 같은 값 + web/_partial/recruit_reco_card.php 키)
  private function _formatRecruitRecoList($list)
  {
    $truncate_length = getTruncateLength();
    $formatted = array();
    foreach ($list as $row) {
      $rc_name = isset($row['rc_name']) ? $row['rc_name'] : '';
      if (mb_strlen($rc_name) > $truncate_length) {
        $rc_name = mb_substr($rc_name, 0, $truncate_length) . '...';
      }
      $rc_price_type = isset($row['rc_price_type']) ? $row['rc_price_type'] : '';
      $rc_nation = isset($row['rc_nation_nm']) ? $row['rc_nation_nm'] : '';
      $formatted[] = array(
        'rc_idx' => $row['rc_idx'],
        'book_yn' => isset($row['book_yn']) ? $row['book_yn'] : 'N',
        'rc_name' => $rc_name,
        'rt_recruit_img' => getRecruitImg($row),
        'rt_work_img' => getWorkingImg(isset($row['rc_work']) ? $row['rc_work'] : ''),
        'rt_work_type' => getWorkTypeWithColor(isset($row['rc_work_type']) ? $row['rc_work_type'] : ''),
        'rt_price_type' => getConvertPriceType($rc_price_type, isset($row['rc_price_rep']) ? $row['rc_price_rep'] : ''),
        'rc_price_type' => $rc_price_type,
        'rc_price_pay' => isset($row['rc_price_pay']) ? $row['rc_price_pay'] : 0,
        'rc_nation_nm' => str_replace('만', '', $rc_nation),
        'rc_age_nm' => formatAgeRange(isset($row['rc_sage']) ? $row['rc_sage'] : 0, isset($row['rc_eage']) ? $row['rc_eage'] : 0),
        'ad_addr' => removeBigAddr(isset($row['ad_addr']) ? $row['ad_addr'] : '')
      );
    }
    return $formatted;
  }

  // 저장된 상세검색 조건(site_log SEARCH_RECRUIT)을 지운다 (세션·회원 기록 모두)
  private function _clearSearchRecruitLog()
  {
    $this->site_model->deleteSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);
    if ($this->user->isLogin()) {
      $this->site_model->deleteSiteLog([
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RECRUIT",
        "sl_step" => "검색"
      ]);
    }
  }

  // 저장된 상세검색 조건(site_log) → 모바일 상세검색 저장 형식(sessionStorage 값과 같은 형식)
  // 저장값이 없으면 조건 없는 상태를 반환
  private function _getSavedSearchState()
  {
    $site_log = $this->site_model->getSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);
    if ($site_log == null && $this->user->isLogin()) {
      $site_log = $this->site_model->getSiteLog([
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RECRUIT",
        "sl_step" => "검색"
      ]);
    }

    $json_info = ($site_log != null) ? json_decode($site_log["sl_txt"], true) : null;
    if (!is_array($json_info)) {
      $json_info = array();
    }

    // "^"로 이어 저장된 값 → 배열
    $get_list = function ($key) use ($json_info) {
      $val = (isset($json_info[$key]) && is_string($json_info[$key])) ? $json_info[$key] : "";
      return array_values(array_filter(array_map("trim", explode("^", $val)), "strlen"));
    };
    // 나이는 숫자만 허용 (화면 hidden input에 그대로 출력됨)
    $get_age = function ($key) use ($json_info) {
      $age = isset($json_info[$key]) ? (int) $json_info[$key] : 0;
      return $age > 0 ? (string) $age : "";
    };

    $search_area = $this->_parseSearchArea(isset($json_info["searchAreaStr"]) ? $json_info["searchAreaStr"] : "");

    // 지역 표시명: 저장된 addrName("강화읍, 송림동")을 지역 수만큼 나눠 쓰고, 개수가 안 맞으면 주소 끝 단어로 대신함
    $addr_name = (isset($json_info["addrName"]) && is_string($json_info["addrName"])) ? $json_info["addrName"] : "";
    $regions = ($addr_name != "") ? explode(", ", $addr_name) : array();
    if (count($regions) != count($search_area)) {
      $regions = array();
      foreach ($search_area as $area) {
        $parts = explode(" ", $area["addr"]);
        $regions[] = ($area["step"] < 3) ? end($parts) . " 전체" : end($parts);
      }
    }

    $price_type = $get_list("price_type");
    $is_login = $this->user->isLogin();

    return array(
      "userId" => $is_login ? (string) $this->user->getUserIdx() : "",
      "addrName" => implode(", ", $regions),
      "searchAreaStr" => json_encode($search_area, JSON_UNESCAPED_UNICODE),
      "search_addr" => array_map(function ($area) { return $area["addr"]; }, $search_area),
      "selectedRegions" => $regions,
      "category" => $get_list("category"),
      "work_type" => $get_list("work_type"),
      "week" => $get_list("week"),
      "price_type" => $price_type,
      "sex" => $get_list("sex"),
      "nationality" => $get_list("nationality"),
      "sage" => $get_age("sage"),
      "eage" => $get_age("eage"),
      "priceNegotiable" => in_array("협의", $price_type),
      "workingDay" => false,
    );
  }

  // 상세검색 지역(searchAreaStr JSON: [{"addr":"인천광역시 강화군 강화읍","step":3,"no":"123"}]) 파싱
  // 클라이언트가 만든 값이므로 형식이 맞는 항목만 사용 (addr 문자열, step 1~3, 최대 10곳)
  private function _parseSearchArea($searchAreaStr)
  {
    return parseSearchAreaList($searchAreaStr);
  }

  // 검색조건이 하나라도 걸려 있는지 (지역/업직종/근무형태/요일/급여/성별/국적/나이)
  private function _hasSearchCondition($search_state)
  {
    foreach (array("search_addr", "category", "work_type", "week", "price_type", "sex", "nationality") as $key) {
      if (!empty($search_state[$key])) {
        return true;
      }
    }
    return ($search_state["sage"] != "" || $search_state["eage"] != "");
  }

  // 주소의 검색 조건(area, category 등)을 모바일 상세검색 저장 형식으로 변환
  private function _getUrlSearchState()
  {
    $get_list = function ($name) {
      $val = input_get($name, "");
      if (!is_string($val)) return array();
      return array_values(array_filter(array_map("trim", explode(",", $val)), "strlen"));
    };

    $has_search = false;
    foreach (array("area", "category", "work", "pay", "week", "sex", "nation", "age") as $name) {
      if (count($get_list($name)) > 0) $has_search = true;
    }
    if (!$has_search) return null;

    // 지역 번호 → 지역 (s접두사는 검색목록 주소, 모바일은 최대 2곳)
    $short_names = array(
      "서울특별시" => "서울", "부산광역시" => "부산", "대구광역시" => "대구", "인천광역시" => "인천",
      "대전광역시" => "대전", "울산광역시" => "울산", "세종특별자치시" => "세종", "경기도" => "경기",
      "강원특별자치도" => "강원", "충청북도" => "충북", "충청남도" => "충남", "전북특별자치도" => "전북",
      "전라남도" => "전남광주통합특별시", "경상북도" => "경북", "경상남도" => "경남", "제주특별자치도" => "제주"
    );
    $search_area = array();
    $regions = array();
    foreach (array_slice($get_list("area"), 0, 2) as $token) {
      if (!preg_match('/^(s?)([0-9]+)$/', $token, $m)) continue;
      $row = $this->db->query("
        SELECT step, sido_name, gugun_name, b_dong_name, search_b_dong,
          TRIM(CONCAT_WS(' ', sido_name, NULLIF(gugun_name, ''), NULLIF(b_dong_name, ''))) AS full_addr
        FROM location_data WHERE no = ?", array((int) $m[2]))->row_array();
      if (empty($row)) continue;

      $step = (int) $row["step"];
      if ($m[1] === "s") {
        $addr = $row["search_b_dong"];
        $parts = explode(" ", $addr);
        $text = $step < 3 ? $addr . " 전체" : end($parts);
      } else {
        $addr = $row["full_addr"];
        if ($step == 1) {
          $text = strtr($row["sido_name"], $short_names) . " 전체";
        } else if ($step == 2) {
          $text = $row["gugun_name"] . " 전체";
        } else {
          $text = $row["b_dong_name"];
        }
      }
      $search_area[] = array("addr" => $addr, "step" => $step, "no" => $token);
      $regions[] = $text;
    }

    // 나이: 20,30 → 20 ~ 39
    $ages = array_map("intval", $get_list("age"));
    $ages = array_filter($ages);
    $price_type = $get_list("pay");
    $is_login = $this->user->isLogin();

    $state = array(
      "userId" => $is_login ? (string) $this->user->getUserIdx() : "",
      "addrName" => implode(", ", $regions),
      "searchAreaStr" => json_encode($search_area, JSON_UNESCAPED_UNICODE),
      "search_addr" => array_map(function ($area) { return $area["addr"]; }, $search_area),
      "selectedRegions" => $regions,
      "category" => $get_list("category"),
      "work_type" => $get_list("work"),
      "week" => $get_list("week"),
      "price_type" => $price_type,
      "sex" => $get_list("sex"),
      "nationality" => $get_list("nation"),
      "sage" => $ages ? (string) min($ages) : "",
      "eage" => $ages ? (string) (max($ages) + 9) : "",
      "priceNegotiable" => in_array("협의", $price_type),
      "workingDay" => false,
    );

    return array(
      "key" => "jobListAdvancedSearch_recruit_" . ($is_login ? $this->user->getUserIdx() : "guest"),
      "searchArea" => $search_area,
      "state" => $state,
    );
  }

  // 지금 뜨는 업체 데이터 포맷팅 (chunking + 포맷팅)
  private function _formatHotCompanies($up_list)
  {
    if (empty($up_list) || !is_array($up_list)) {
      return ['chunks' => []];
    }

    // 4개씩 chunking
    $chunked_list = array_chunk($up_list, 4);

    // 마지막 슬라이드가 4개 미만인 경우 빈 슬롯 추가
    if (!empty($chunked_list)) {
      $last_idx = count($chunked_list) - 1;
      while (count($chunked_list[$last_idx]) < 4) {
        $chunked_list[$last_idx][] = null;
      }
    }

    // 각 항목 포맷팅
    $formatted_chunks = [];
    foreach ($chunked_list as $chunk) {
      $formatted_chunk = [];
      foreach ($chunk as $row) {
        if ($row !== null) {
          $formatted_chunk[] = [
            'mm_idx' => isset($row['mm_idx']) ? $row['mm_idx'] : '',
            'mm_comp_name' => isset($row['mm_comp_name']) ? $row['mm_comp_name'] : '',
            'comp_img' => getCompImg($row),
            'comp_desc' => getRandomCompDesc(isset($row['mm_comp_name']) ? $row['mm_comp_name'] : '')
          ];
        } else {
          $formatted_chunk[] = null;
        }
      }
      $formatted_chunks[] = $formatted_chunk;
    }

    return ['chunks' => $formatted_chunks];
  }

  # 채용지도(동검색)
  function job_map_old()
  {

    $data = [];

    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

    //검색어 가져오기
    $search_txt = [
      "text" => "",
      "skm" => "",
      "ekm" => "30",
      "category" => "",
      "work_type" => "",
      "week" => "",
      "price_type" => "",
      "sex" => "",
      "sage" => "",
      "eage" => "",
    ];

    $site_log = $this->site_model->getSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);

    if ($site_log == null) {
      if ($this->user->isLogin()) {
        $site_log = $this->site_model->getSiteLog([
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RECRUIT",
          "sl_step" => "검색"
        ]);
      }
    }

    if ($site_log != null) {
      $json_info = json_decode($site_log["sl_txt"], true);
      $search_txt["text"] = $json_info["text"];
      $search_txt["skm"] = $json_info["skm"];
      $search_txt["ekm"] = $json_info["ekm"];
      $search_txt["category"] = explode("^", $json_info["category"]);
      $search_txt["work_type"] = explode("^", $json_info["work_type"]);
      $search_txt["week"] = explode("^", $json_info["week"]);
      $search_txt["price_type"] = explode("^", $json_info["price_type"]);
      $search_txt["sex"] = explode("^", $json_info["sex"]);
      $search_txt["sage"] = $json_info["sage"];
      $search_txt["eage"] = $json_info["eage"];
    }

    $page = input_post("page", 1);
    $param['text'] = input_post("text", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $param['category'] = isset($_POST["category"]) ? implode("^", input_post("category", "")) : "";
    $param['work_type'] = isset($_POST["work_type"]) ? implode("^", input_post("work_type", "")) : "";
    $param['week'] = isset($_POST["week"]) ? implode("^", input_post("week", "")) : "";
    $param['price_type'] = isset($_POST["price_type"]) ? implode("^", input_post("price_type", "")) : "";
    $param['sex'] = isset($_POST["sex"]) ? implode("^", input_post("sex", "")) : "";
    $param['sage'] = input_post("sage", "");
    $param['eage'] = input_post("eage", "");
    $save_yn = input_post("save_yn", "N");

    $data["mm_type"] = $this->mm_type;
    $data["addr_info"] = $addr_info["ad_addr"];
    $data["ad_gps_x"] = $addr_info["ad_gps_x"];
    $data["ad_gps_y"] = $addr_info["ad_gps_y"];
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;

    $this->layout->shop('/recruit/job_map_old', $data);
  }

  # 채용지도
  function job_map()
  {

    $data = [];

    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

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
      "mapLevel" => "",
    ];

    $site_log = $this->site_model->getSiteLog([
      "session_id" => session_id(),
      "sl_type" => "SEARCH_RECRUIT",
      "sl_step" => "검색"
    ]);

    if ($site_log == null) {
      if ($this->user->isLogin()) {
        $site_log = $this->site_model->getSiteLog([
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RECRUIT",
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
      $search_txt["mapLevel"] = $json_info["mapLevel"];
    }

    $page = input_post("page", 1);
    $param['text'] = input_post("text", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $param['category'] = isset($_POST["category"]) ? implode("^", input_post("category", "")) : "";
    $param['work_type'] = isset($_POST["work_type"]) ? implode("^", input_post("work_type", "")) : "";
    $param['week'] = isset($_POST["week"]) ? implode("^", input_post("week", "")) : "";
    $param['price_type'] = isset($_POST["price_type"]) ? implode("^", input_post("price_type", "")) : "";
    $param['sex'] = isset($_POST["sex"]) ? implode("^", input_post("sex", "")) : "";
    $param['sage'] = input_post("sage", "");
    $param['eage'] = input_post("eage", "");
    $save_yn = input_post("save_yn", "N");

    $data["addr_info"] = $addr_info["ad_addr"];
    $data["ad_gps_x"] = $addr_info["ad_gps_x"];
    $data["ad_gps_y"] = $addr_info["ad_gps_y"];
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/job_map', $data);
  }

  # 채용공고
  function job_post($rc_idx = null)
  {
    $preview = input_get('preview', 0); // 미리보기 모드 (draft 데이터 사용)
    $is_ajax = $this->input->is_ajax_request();

    if ($rc_idx == null) {
      redirect('https://nowjob.co.kr/recruit/job_list');
    }
    $data = [];

    $os_type = isset($data["os_type"]) ? $data["os_type"] : "";

    // preview=1이면 site_log의 최신 데이터를 우선 사용, 없을 때만 DB 데이터 조회
    if ($preview == 1) {
      // preview_mode: site_log의 최신 수정 데이터 우선
      $list = $this->site_model->getSiteLogList(
        ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
      );
      $step1 = $this->site_model->getStepData($list, "스텝1");

      if (is_array($step1) && !empty($step1) && isset($step1["rc_name"])) {
        // site_log에 데이터가 있으면 site_log에서 로드
        $step2 = $this->site_model->getStepData($list, "스텝2");
        $step3 = $this->site_model->getStepData($list, "스텝3");
        $step5 = $this->site_model->getStepData($list, "스텝5");

        // site_log에서 $info 구성
        $info = [];
        $info["rc_idx"] = $rc_idx;
        // site_log는 이미 $this->mm_idx로 필터링해 조회했고(위 getSiteLogList), 스텝1 sl_txt에는 애초에 mm_idx 키가 저장되지 않으므로 로그인한 사용자 mm_idx를 그대로 사용
        $info["mm_idx"] = $this->mm_idx;
        $info["rc_name"] = isset($step1["rc_name"]) ? $step1["rc_name"] : "";
        $info["rc_work"] = isset($step2["rc_work"]) ? (is_array($step2["rc_work"]) ? arr_implode(",", $step2["rc_work"]) : $step2["rc_work"]) : (isset($step1["rc_work"]) ? (is_array($step1["rc_work"]) ? arr_implode(",", $step1["rc_work"]) : $step1["rc_work"]) : "");
        $info["rc_work_type"] = isset($step3["rc_work_type"]) ? (is_array($step3["rc_work_type"]) ? arr_implode(",", $step3["rc_work_type"]) : $step3["rc_work_type"]) : (isset($step1["rc_work_type"]) ? (is_array($step1["rc_work_type"]) ? arr_implode(",", $step1["rc_work_type"]) : $step1["rc_work_type"]) : "");
        // 저장 규칙(compage_proc과 동일): 급여협의 → "급여협의", 서브 있음 → "메인,서브", 없음 → "메인"
        $draft_price_main = isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "";
        $draft_price_sub = isset($step1["rc_price_possible"]) ? str_replace("가능", "", $step1["rc_price_possible"]) : "";
        if ($draft_price_main == "급여협의") {
          $draft_price_type = "급여협의";
        } elseif ($draft_price_sub != "") {
          $draft_price_type = $draft_price_main . "," . $draft_price_sub;
        } else {
          $draft_price_type = $draft_price_main;
        }
        $info["rc_price_type"] = isset($step3["rc_price_type"]) ? $step3["rc_price_type"] : $draft_price_type;
        $info["rc_price_pay"] = isset($step3["rc_price_pay"]) ? intval($step3["rc_price_pay"]) : (isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0);
        $info["rc_price_rep"] = isset($step3["rc_price_rep"]) ? $step3["rc_price_rep"] : (isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "");
        $info["rc_week"] = isset($step3["rc_week"]) ? (is_array($step3["rc_week"]) ? arr_implode(",", $step3["rc_week"]) : $step3["rc_week"]) : (isset($step3["rc_day"]) ? (is_array($step3["rc_day"]) ? arr_implode(",", $step3["rc_day"]) : $step3["rc_day"]) : "");
        $info["rc_day_all"] = isset($step3["rc_day_all"]) ? $step3["rc_day_all"] : "";
        $info["rc_am_stime"] = isset($step3["rc_am_stime"]) ? $step3["rc_am_stime"] : (isset($step1["rc_am_stime"]) ? $step1["rc_am_stime"] : "");
        $info["rc_am_etime"] = isset($step3["rc_am_etime"]) ? $step3["rc_am_etime"] : (isset($step1["rc_am_etime"]) ? $step1["rc_am_etime"] : "");
        $info["rc_pm_stime"] = isset($step3["rc_pm_stime"]) ? $step3["rc_pm_stime"] : (isset($step1["rc_pm_stime"]) ? $step1["rc_pm_stime"] : "");
        $info["rc_pm_etime"] = isset($step3["rc_pm_etime"]) ? $step3["rc_pm_etime"] : (isset($step1["rc_pm_etime"]) ? $step1["rc_pm_etime"] : "");
        $info["rc_life_type"] = isset($step2["rc_life_type"]) ? (is_array($step2["rc_life_type"]) ? arr_implode(",", $step2["rc_life_type"]) : $step2["rc_life_type"]) : "";
        $info["rc_nation"] = isset($step3["rc_nation"]) ? (is_array($step3["rc_nation"]) ? arr_implode(",", $step3["rc_nation"]) : $step3["rc_nation"]) : "";
        $info["rc_sex"] = isset($step3["rc_sex"]) ? (is_array($step3["rc_sex"]) ? arr_implode(",", $step3["rc_sex"]) : $step3["rc_sex"]) : "";
        // getRecruitDetail()의 SQL CASE 문과 동일한 규칙으로 표시용 명칭 생성 (DB 조회를 거치지 않는 preview 전용 경로라 직접 계산 필요)
        $info["rc_nation_nm"] = ($info["rc_nation"] == "내국인" || $info["rc_nation"] == "외국인") ? $info["rc_nation"] : "국적무관";
        $info["rc_sex_nm"] = ($info["rc_sex"] == "남자" || $info["rc_sex"] == "여자") ? $info["rc_sex"] : "성별무관";
        $info["rc_sage"] = isset($step3["rc_sage"]) ? $step3["rc_sage"] : "";
        $info["rc_eage"] = isset($step3["rc_eage"]) ? $step3["rc_eage"] : "";
        $info["rc_desc"] = isset($step5["rc_desc"]) ? $step5["rc_desc"] : (isset($step3["rc_desc"]) ? $step3["rc_desc"] : "");
        $info["rc_hash"] = isset($step5["rc_hash"]) ? $step5["rc_hash"] : (isset($step3["rc_hash"]) ? $step3["rc_hash"] : "");
        // 모바일(스텝3)/웹(스텝1 rc_date) 두 저장 형식 모두 지원 (compage_proc.php 최종 등록 로직과 동일한 우선순위)
        list($info["rc_sdate"], $info["rc_edate"]) = get_draft_recruit_dates($step1, $step3);
        $info["rc_day_price"] = isset($step3["rc_day_price"]) ? intval($step3["rc_day_price"]) : 0;
        $info["rc_week_price"] = isset($step3["rc_week_price"]) ? intval($step3["rc_week_price"]) : 0;
        $info["rc_month_price"] = isset($step3["rc_month_price"]) ? intval($step3["rc_month_price"]) : 0;
        // 이미지는 스텝3 우선, 없으면 스텝5 (recruit_complete의 등록 로직과 동일한 우선순위)
        // site_log는 mm_idx+sl_type+sl_step 키라서 과거 웹 5단계 플로우의 스텝5 레코드가 남아 있을 수 있는데,
        // 모바일 3단계 플로우는 rc_img1을 스텝3에만 기록하므로 스텝5를 우선하면 방금 올린 썸네일이 밀린다.
        $info["rc_img1"] = isset($step3["rc_img1"]) ? $step3["rc_img1"] : (isset($step5["rc_img1"]) ? $step5["rc_img1"] : "");
        $info["rc_img2"] = isset($step3["rc_img2"]) ? $step3["rc_img2"] : (isset($step5["rc_img2"]) ? $step5["rc_img2"] : "");
        $info["rc_img3"] = isset($step3["rc_img3"]) ? $step3["rc_img3"] : (isset($step5["rc_img3"]) ? $step5["rc_img3"] : "");
        $info["rc_img4"] = isset($step3["rc_img4"]) ? $step3["rc_img4"] : (isset($step5["rc_img4"]) ? $step5["rc_img4"] : "");
        $info["rc_img5"] = isset($step5["rc_img5"]) ? $step5["rc_img5"] : "";
        // 이미지가 하나도 없을 때 getRecruitImg()가 참조하므로 함께 채운다
        $info["rc_thumb"] = isset($step3["rc_thumb"]) ? $step3["rc_thumb"] : (isset($step5["rc_thumb"]) ? $step5["rc_thumb"] : "");
        $info["rc_show_yn"] = "Y";
        $info["fav_cnt"] = 0;
        $info["rc_hit"] = 0;
      } else {
        // site_log에 데이터가 없으면 DB에서 로드 (신규 등록 초기 preview 등)
        $info = $this->recruit_model->getRecruitDetail($rc_idx, $this->mm_idx);
      }
    } else {
      // 정상 열람: DB에서 조회
      $info = $this->recruit_model->getRecruitDetail($rc_idx, $this->mm_idx);
    }

    // 주소 정보 로드 (preview 모드에서는 site_log의 step3 또는 step1 사용)
    if ($preview == 1) {
      // preview 모드: step3에서 먼저 확인
      $addr_info = (isset($step3) && is_array($step3)) ? $step3 : [];

      // step3에 주소가 없으면 step1에서 확인
      if (empty($addr_info) || !isset($addr_info["ad_doro"])) {
        $addr_info = (isset($step1) && is_array($step1)) ? $step1 : [];
      }

      // step1에도 주소가 없으면 DB에서 로드
      if (empty($addr_info) || !isset($addr_info["ad_doro"])) {
        $addr_info = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
      }
    } else {
      // 정상 조회: DB에서 로드
      $addr_info = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
    }
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $bookmark = $this->site_model->getBookmark(["bk_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "bk_key" => $rc_idx]);

    // 기업 본인 미리보기(preview=1)는 제외하고, 비공개·삭제·정지·마감 공고는 막는다
    if ($info == null || ($preview != 1 && ($info["rc_show_yn"] != "Y" || $info["rc_status"] != "Y" || strtotime($info["rc_edate"]) < time()))) {
      go_location("/recruit/job_list", "채용공고를 조회 하지 못하였습니다.");
    }

    $sqlcnt = "UPDATE st_recruit SET rc_hit = rc_hit + 1 WHERE rc_idx = '" . $rc_idx . "' ";
    $querycnt = $this->db->query($sqlcnt);

    # 내가본 채용공고
    $view_list = [];
    if ($this->user->isLogin() == true) {
      if ($rc_idx != 0) {
        $ddd = $this->site_model->insertMemberView(["mv_type" => "recruit", "mv_key" => $rc_idx, "mm_idx" => $this->mm_idx]);
        $view_list = $this->recruit_model->getMemberViewList($this->mm_idx);
      }

      foreach ($view_list as $key => $row) {
        $view_list[$key]["rt_recruit_img"] = getRecruitImg($row);
        $view_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
        $view_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
        $rc_price_rep = isset($row["rc_price_rep"]) ? $row["rc_price_rep"] : "";
        $view_list[$key]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $rc_price_rep);
        $view_list[$key]["rt_price"] = getConvertPrice($row["rc_price_pay"], $row["rc_price_type"]);
      }
    }

    # 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
    if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
      $info["rc_img99"] = getRecruitImg($info, "detail");
    } else {
      $info["rc_img99"] = "";
    }

    $info["rc_hit"] = $info["rc_hit"] + 1;

    # 현재 공고 급여정보 처리 (뷰에서 직접 헬퍼함수 호출 대신 컨트롤러에서 처리)
    if (strpos($info["rc_price_type"], "협의") === false) {
      $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
      $info["rt_price_type_html"] = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
      $info["rt_price_html"] = getConvertPrice($info["rc_price_pay"], $info["rc_price_type"]);
    }

    $data["top_button"] = (strpos($_SERVER['HTTP_REFERER'], 'nowjob.co.kr') !== false ? "btn-back" : "btn-home");
    $data["os_type"] = $os_type;
    $data["view_list"] = $view_list;
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y", "ru_status" => "Y"]);
    // echo '<pre>';
    // echo json_encode($data["resume"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // echo '</pre>';
    // die(); 

    $data["info"] = $info;
    $data["addr"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["book_yn"] = ($bookmark == null ? "N" : "Y");
    $data["mem_info"] = $this->member_model->getMember(["mm_idx" => $info["mm_idx"]]);
    $data["mm_type"] = $this->mm_type;
    $data["is_popup"] = $is_ajax;

    if ($is_ajax) {
      $this->load->view('mobile/recruit/job_post', $data);
    } else {
      $this->layout->shop('/recruit/job_post', $data);
    }
  }

  # 지금 뜨는 업체 - 목록
  function companies()
  {
    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $mm_idx = $this->user->getUserIdx();

    # 추천 공고 : 웹용
    $params["addr1"] = "";
    $params["addr2"] = "";
    $params["addr3"] = "";
    $params["mm_idx"] = $this->mm_idx;

    $reco_list = $this->common_model->getFavRecruitWebList($params);
    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "comp"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $data["reco_list"] = $reco_list["list"];
    $data["tag_list"] = $tag_info["comp"] != "" ? explode(",", $tag_info["comp"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y"]);
    $data["addr_info"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");

    # 뜨는 업체
    $addr_info = $this->user->getAddrInfo();
    $up_list = $this->common_model->getHomeUpCompList($addr_info, 6);
    $data["up_list"] = $up_list;
    $up_list2 = $this->common_model->getHomeUpCompList($addr_info, 7);
    $data["up_list2"] = $up_list2;
    $data["all_list"] = $this->clistAll();


    $this->layout->shop('/recruit/companies', $data);
  }

  # 지금 뜨는 업체 - 채용목록
  function company($comp_idx = null)
  {
    if ($comp_idx === null) {
      redirect('https://nowjob.co.kr/recruit/companies');
    }
    $addr_info = $this->user->getAddrInfo();
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $mm_idx = $this->user->getUserIdx();

    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "comp"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $comp_info = $this->member_model->getMember(["mm_idx" => $comp_idx]);
    $data["comp_name"] = $comp_info['mm_comp_name'];
    $data["comp_thumb"] = $comp_info['mm_thumb'];

    $data["tag_list"] = $tag_info["comp"] != "" ? explode(",", $tag_info["comp"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["resume"] = $this->member_model->getResume(["mm_idx" => $this->mm_idx, "ru_show_yn" => "Y"]);
    $data["addr_info"] = $addr_info;
    $data["kakao_key"] = $kakao_key;
    $data["work_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["comp_idx"] = $comp_idx;
    $data["mm_type"] = $this->mm_type;

    $this->layout->shop('/recruit/company', $data);
  }

  function clistAll()
  {
    // 뜨는 업체 전체 리스트
    //,GROUP_CONCAT(DISTINCT rc.rc_work ORDER BY rc.rc_work SEPARATOR ',') AS rc_work_list
    //inner join st_recruit rc          on aa.mm_idx = rc.mm_idx

    $sql = "
        select aa.*
              ,bb.ma_work
              ,max(rc.rc_idx) as rc_idx
              ,COUNT(distinct rc.rc_idx) rt_ing_cnt
              ,CASE WHEN IFNULL(bm.bk_idx,0) > 0 THEN 'Y' ELSE 'N' END book_yn
              ,GROUP_CONCAT(
                  DISTINCT rc_freq.rc_work
                  ORDER BY rc_freq.freq DESC, rc_freq.rc_work
                  SEPARATOR ','
                  ) AS rc_work_list
        FROM st_member aa
        inner join st_recruit rc          on aa.mm_idx = rc.mm_idx
        inner join (
          SELECT rc.mm_idx, rc.rc_work, COUNT(*) AS freq
          FROM st_recruit rc
          WHERE rc.rc_status = 'Y'
          AND rc.rc_show_yn = 'Y'
          AND rc.rc_sdate < NOW()
          AND rc.rc_edate > NOW()
          GROUP BY rc.mm_idx, rc.rc_work
          ) rc_freq ON aa.mm_idx = rc_freq.mm_idx
        left join st_member_info bb
            on aa.mm_idx = bb.mm_idx
        LEFT JOIN st_bookmark bm
            ON bm.mm_idx = '" . $this->mm_idx . "'
          AND bm.bk_type='COMPANY'
          AND bm.bk_key = aa.mm_idx
        LEFT JOIN st_promotion p
              ON p.mm_idx = aa.mm_idx AND p.pm_code = 'C004' AND p.pm_sdate < NOW() AND p.pm_edate > NOW() AND p.pm_status = 'Y'
        where aa.mm_type= 'COMP'
          and aa.mm_status = 'Y'
          and rc.rc_status = 'Y'
          AND rc_show_yn = 'Y'
  	      AND rc_sdate < NOW()
  	      AND rc_edate > NOW()
          AND p.pm_status = 'Y'
        group by aa.mm_idx
        order by rand()
      ";
    //order by rc.rc_idx desc

    $query = $this->db->query($sql);
    $upAll_list = $query->result_array();
    //$data["upAll_list"] = $upAll_list;
    $result["list"] = $upAll_list;
    $list = $result["list"];

    foreach ($list as $key => $row) {
      $list[$key]["rt_work_img"] = getWorkingImg($row["ma_work"]);
    }
    //$result["list"] = $list;

    //json_return("00","조회성공",$result);
    return $list;
  }

  # 리뉴얼전 URL 처리
  function index()
  {
    redirect('https://nowjob.co.kr/recruit/job_list');
  }
  function wlist()
  {
    redirect('https://nowjob.co.kr/recruit/job_list');
  }
  function index_map()
  {
    redirect('https://nowjob.co.kr/recruit/job_map');
  }
  function recruit_detail($rc_idx = null)
  {
    if ($rc_idx === null) {
      redirect('https://nowjob.co.kr/recruit/job_list');
    }
    redirect("https://nowjob.co.kr/recruit/job_post/{$rc_idx}");
  }

  /*

		function wlistcomp($comp_idx=0){
			$addr_info = $this->user->getAddrInfo();
      $kakao_key = $this->setting_model->getSettingInfo(["st_group"=>"카카오","st_key"=>"인증키"]);
      $mm_idx = $this->user->getUserIdx();

			# 추천 공고 : 웹용
			$params["addr1"] = "";
			$params["addr2"] = "";
			$params["addr3"] = "";
      $params["mm_idx"] = $this->mm_idx;
      $params["comp_idx"] = $comp_idx;

			$reco_list = $this->common_model->getFavRecruitWebList($params);
			$tag_info = $this->setting_model->getSettingInfo(["st_group"=>"태그","st_key"=>"comp"]);
      $tag_search = $this->setting_model->getSettingInfo(["st_group"=>"태그","st_key"=>"search"]);

			$data["reco_list"] = $reco_list["list"];
			$data["tag_list"] = $tag_info["comp"]!="" ? explode(",",$tag_info["comp"]):[];
      $data["tag_search"] = $tag_search["search"]!="" ? explode(",",$tag_search["search"]):[];
      $data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["addr_info"] = $addr_info;
      $data["kakao_key"] = $kakao_key;
			$data["work_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
			$data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
			$data["week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
			$data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");

      //start
      $addr_info = $this->user->getAddrInfo();
      $mm_idx = $this->user->getUserIdx();
      $page = input_post("page",1);
      $pagesize = input_post("pagesize",100);
      $order_by = input_post("order_by","recent");
      //$comp_idx = input_post("comp_idx","");

      $params = [
          "pagesize" => $pagesize,
          "page" => $page,
          "mm_idx" => $mm_idx,
          "order_by" => $order_by,
          "compidx"    => $comp_idx,
      ];

      $result = $this->member_model->getRecruitWebSearchList($params);

      $temp_list = $result["list"];
      foreach($temp_list as $key=>$row){
          $temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
          $temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
          $temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"],"span");
          $temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"],$row);
          $temp_list[$key]["rt_price"] = getRecuitPrice($row);
          $temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
          $temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
          $temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
      }
      $data["recruit_list"] = $temp_list;
      //list end

      $this->layout->shop('/recruit/wlistcomp',$data);
		}

    function clist(){
			$addr_info = $this->user->getAddrInfo();
      $kakao_key = $this->setting_model->getSettingInfo(["st_group"=>"카카오","st_key"=>"인증키"]);
      $mm_idx = $this->user->getUserIdx();

			# 추천 공고 : 웹용
			$params["addr1"] = "";
			$params["addr2"] = "";
			$params["addr3"] = "";
      $params["mm_idx"] = $this->mm_idx;

			$reco_list = $this->common_model->getFavRecruitWebList($params);
			$tag_info = $this->setting_model->getSettingInfo(["st_group"=>"태그","st_key"=>"comp"]);
      $tag_search = $this->setting_model->getSettingInfo(["st_group"=>"태그","st_key"=>"search"]);

			$data["reco_list"] = $reco_list["list"];
			$data["tag_list"] = $tag_info["comp"]!="" ? explode(",",$tag_info["comp"]):[];
      $data["tag_search"] = $tag_search["search"]!="" ? explode(",",$tag_search["search"]):[];
      $data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["addr_info"] = $addr_info;
      $data["kakao_key"] = $kakao_key;
			$data["work_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
			$data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
			$data["week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
			$data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");

      # 뜨는 업체
      $addr_info = $this->user->getAddrInfo();
      $up_list = $this->common_model->getHomeUpCompList($addr_info,7);
      $data["up_list"] = $up_list;
      $data["all_list"] = $this->clistAll();


      $this->layout->shop('/recruit/clist',$data);
		}

    function index_map_comp($comp_idx=0){
      $data = [];

      $addr_info = $this->user->getAddrInfo();
      $kakao_key = $this->setting_model->getSettingInfo(["st_group"=>"카카오","st_key"=>"인증키"]);

      //검색어 가져오기
      $search_txt = [
            "text" => "",
            "skm" => "",
            "ekm" => "30",
            "category" => "",
            "work_type" => "",
            "week" => "",
            "price_type" => "",
            "sex" => "",
            "sage" => "",
            "eage" => "",
            ];

      $site_log = $this->site_model->getSiteLog([
            "session_id"=>session_id()
            ,"sl_type"=>"SEARCH_RECRUIT"
            ,"sl_step"=>"검색"
      ]);

			if($site_log==null){
				if($this->user->isLogin()){
					$site_log = $this->site_model->getSiteLog([
		                "mm_idx"=>$this->mm_idx
		                ,"sl_type"=>"SEARCH_RECRUIT"
		                ,"sl_step"=>"검색"
		        	]);
				}
			}

			if($site_log!=null){
				$json_info = json_decode($site_log["sl_txt"],true);
				$search_txt["text"] = $json_info["text"];
				$search_txt["skm"] = $json_info["skm"];
				$search_txt["ekm"] = $json_info["ekm"];
				$search_txt["category"] = explode("^",$json_info["category"]);
				$search_txt["work_type"] = explode("^",$json_info["work_type"]);
				$search_txt["week"] = explode("^",$json_info["week"]);
				$search_txt["price_type"] = explode("^",$json_info["price_type"]);
				$search_txt["sex"] = explode("^",$json_info["sex"]);
				$search_txt["sage"] = $json_info["sage"];
				$search_txt["eage"] = $json_info["eage"];
			}

        $page = input_post("page",1);
        $param['text'] = input_post("text","");
        $skm = input_post("skm","");
        $ekm = input_post("ekm","");
        $param['category'] = isset($_POST["category"]) ? implode("^", input_post("category","")) : "";
        $param['work_type'] = isset($_POST["work_type"]) ? implode("^", input_post("work_type","")) : "";
        $param['week'] = isset($_POST["week"]) ? implode("^", input_post("week","")) : "";
        $param['price_type'] = isset($_POST["price_type"]) ? implode("^", input_post("price_type","")) : "";
        $param['sex'] = isset($_POST["sex"]) ? implode("^", input_post("sex","")) : "";
        $param['sage'] = input_post("sage","");
        $param['eage'] = input_post("eage","");
        $save_yn = input_post("save_yn","N");
        $param['comp_idx'] = $comp_idx;

        //$result = $this->member_model->getRecruitSearchParamsList(100);
        $result = $this->member_model->getRecruitSearchParamsListMap($param, 100);

        $data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
        $data["addr_info"] = $addr_info;
        $data["ad_gps_x"] = $addr_info["ad_gps_x"];
        $data["ad_gps_y"] = $addr_info["ad_gps_y"];
        $data["kakao_key"] = $kakao_key;
        $data["data"] = $result;
        $data["work_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
        $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
        $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
        $data["week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
        $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
        $data["search_txt"]  = $search_txt;

        $this->layout->shop('/recruit/index_map',$data);
    }

    function recruit_view_list(){
      $data = [];

      $view_list = $this->site_model->getMemberViewList("recruit",$this->mm_idx);
      $data["view_list"] = $view_list;

      $this->layout->shop('/recruit/recruit_view_list',$data);
		}

    */

  function list_up_batch()
  {
    echo date("Y-m-d H:i:s") . " ";

    # 트랙젝션 시작
    $this->db->trans_begin();

    $sql = "UPDATE st_promotion SET up_dt = NOW()
      WHERE pm_code = 'C002'
      AND NOW() BETWEEN pm_sdate AND pm_edate
      AND ifnull(up_dt,'2001-01-01 00:00:00') < DATE_SUB(NOW(), INTERVAL 4 HOUR) ";

    $query = $this->db->query($sql);

    $affected_rows1 = $this->db->affected_rows();
    echo "[" . $affected_rows1 . "]";

    $sql = "UPDATE st_promotion SET up_dt = NOW()
      WHERE pm_code = 'C003'
      AND NOW() BETWEEN pm_sdate AND pm_edate
      AND ifnull(up_dt,'2001-01-01 00:00:00') < DATE_SUB(NOW(), INTERVAL 8 HOUR) ";

    $query = $this->db->query($sql);

    $affected_rows2 = $this->db->affected_rows();
    echo "[" . $affected_rows2 . "]";

    $sql = "UPDATE st_promotion SET up_dt = NOW()
      WHERE pm_code = 'M002'
      AND NOW() BETWEEN pm_sdate AND pm_edate
      AND ifnull(up_dt,'2001-01-01 00:00:00') < DATE_SUB(NOW(), INTERVAL 4 HOUR) ";

    $query = $this->db->query($sql);

    $affected_rows3 = $this->db->affected_rows();
    echo "[" . $affected_rows3 . "]";

    $sql = "UPDATE st_promotion SET up_dt = NOW()
      WHERE pm_code = 'M003'
      AND NOW() BETWEEN pm_sdate AND pm_edate
      AND ifnull(up_dt,'2001-01-01 00:00:00') < DATE_SUB(NOW(), INTERVAL 8 HOUR) ";

    $query = $this->db->query($sql);

    $affected_rows4 = $this->db->affected_rows();
    echo "[" . $affected_rows4 . "]";

    # 트랙젝션 완료
    $this->db->trans_commit();
  }

  function chat_server_check()
  {
    echo "<br>" . date("Y-m-d H:i:s") . "start ";

    // now check start
    $url = "https://nowjob.co.kr:3030/socket.io/socket.io.js?v=0001";

    // cURL 세션 초기화
    $ch = curl_init();

    // 옵션 설정
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 인증서 검사 비활성화
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // SSL 호스트 이름 검사 비활성화

    // URL 호출하고 응답 받기
    $response = curl_exec($ch);

    // 상태 코드 확인
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);

    // cURL 세션 종료
    curl_close($ch);

    // 상태 코드 확인 및 출력
    if ($http_status == 200) {
      echo "<br>" . "응답 성공: "; // . $response;
    } else {
      echo "<br>" . "응답 실패, 상태 코드: " . $http_status;
      if ($curl_error) {
        echo "<br>" . " cURL 오류: " . $curl_error;
      }

      //telegram send start
      //$chatId = "7871633xx"; //"lee"
      //$msg = "나우잡 채팅서버가 중지되었습니다.";

      //$msg = urlencode($msg);
      //$url = "https://api.telegram.org/bot5855109xxx:AAF8G7K1Q5oFxxx_yXBSqoxxx-dOZx-Ao/sendMessage?chat_id=$chatId&text=" . $msg;

      //$ch = curl_init();                                 //curl 초기화
      //curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
      //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
      //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함

      //$response = curl_exec($ch);
      //curl_close($ch);

      //chat id 확인
      // lee 운영중인 다른 계정 참고

      //telegram send end

      //node 재실행 시작
      $nodeCommand = "node /website/nowapk/chatting/chat/chat_server.js &";
      exec($nodeCommand);
      //node 재실행 종료
    }

    // now check end
    echo "<br>" . date("Y-m-d H:i:s") . "end ";
  }

  // retiremem15day()는 삭제됨: mm_id/전화/DI 뒤에 x1만 붙이는 흉내만 내는 정리였음(실제 데이터 삭제 없음).
  // cron_proc.php::cleanup_withdrawn_members()가 공고/이력서/중고/파일/주문 등을 실제로 정리하는 역할을 대신한다.

}
