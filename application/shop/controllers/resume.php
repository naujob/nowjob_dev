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

    // 초기 인재정보 데이터 로드 (실시간 쿼리)
    $sql_initial = "
      SELECT
        bb.ru_idx, aa.mm_idx, bb.ru_name, bb.ru_work, bb.ru_price_type, bb.ru_work_type, bb.ru_week,
        bb.ru_work_img,
        COALESCE(NULLIF(aa.mm_thumb, ''), aa.mm_img1, aa.mm_img2, aa.mm_img3, '/resource/uploads/member/noimage4.png') AS ru_resume_img,
        COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr,
        aa.mm_nation, aa.mm_sex, aa.mm_birth,
        (SELECT COUNT(*) FROM st_bookmark WHERE bk_type = 'RESUME' AND bk_key = bb.ru_idx) AS book_cnt,
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
      LIMIT 20
    ";
    $query_initial = $this->db->query($sql_initial);
    $initial_resumes = $query_initial->result_array();
    $use_server_render = isset($initial_resumes) && count($initial_resumes) > 0;

    // 초기 데이터 포맷팅
    if ($use_server_render) {
      $initial_resumes = $this->_formatResumeList($initial_resumes);

      // 기업회원인 경우 채팅방 정보 추가
      if ($this->user->getUserType() == "COMP") {
        $initial_resumes = $this->_addChatRoomInfo($initial_resumes);
      }
    }

    // 전체 이력서 개수 조회 (실시간 쿼리)
    $sql_count = "
      SELECT COUNT(DISTINCT bb.ru_idx) as total
      FROM st_member aa
      INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y'
      WHERE aa.mm_status = 'Y'
    ";
    $query_count = $this->db->query($sql_count);
    $count_result = $query_count->row_array();
    $initial_total_count = isset($count_result['total']) ? $count_result['total'] : 0;

    // 추천인재 데이터 로드 (서버사이드 렌더링)
    $reco_params = [
      "addr1" => isset($addr_info['ad_addr1']) ? $addr_info['ad_addr1'] : '',
      "addr2" => isset($addr_info['ad_addr2']) ? $addr_info['ad_addr2'] : '',
      "addr3" => isset($addr_info['ad_addr3']) ? $addr_info['ad_addr3'] : '',
      "mm_idx" => $this->mm_idx,
      "limit" => 8
    ];
    $initial_reco = $this->common_model->getFavResumeList2025($reco_params);
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
    $data["up_list"] = $this->common_model->getHomeUpCompList($addr_info, 8);
    $data["initial_resumes"] = $initial_resumes;
    $data["initial_total_count"] = $initial_total_count;
    $data["use_server_render"] = $use_server_render;
    $data["initial_reco"] = $initial_reco['list'];
    $data["has_initial_reco"] = $has_initial_reco;

    $this->layout->shop('/resume/hr_list', $data);
  }

  // 인재 정보 데이터 포맷팅 (뷰와 AJAX 응답에서 공통 사용)
  private function _formatResumeList($list)
  {
    $formatted_list = [];
    foreach ($list as $row) {
      $ru_work = isset($row['ru_work']) ? $row['ru_work'] : '';
      $ru_work_type = isset($row['ru_work_type']) ? $row['ru_work_type'] : '';
      $ru_price_type = isset($row['ru_price_type']) ? $row['ru_price_type'] : '';
      $ru_week = isset($row['ru_week']) ? trim($row['ru_week']) : '';
      $mm_birth = isset($row['mm_birth']) ? $row['mm_birth'] : '';
      $mm_nation = isset($row['mm_nation']) ? $row['mm_nation'] : '';
      $ad_addr = isset($row['ad_addr']) ? $row['ad_addr'] : '';

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      if (($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $formatted_list[] = [
        'ru_idx' => isset($row['ru_idx']) ? $row['ru_idx'] : '',
        'mm_idx' => isset($row['mm_idx']) ? $row['mm_idx'] : '',
        'pm_idx' => isset($row['pm_idx']) ? $row['pm_idx'] : null,
        'book_yn' => isset($row['book_yn']) ? $row['book_yn'] : 'N',
        'ru_name' => (mb_strlen($row['ru_name']) > 18) ? mb_substr($row['ru_name'], 0, 18) . '...' : $row['ru_name'],
        'ru_resume_img' => getResumeImg($row),
        'ru_work_img' => getWorkingImg($ru_work),
        'ru_work_type' => getWorkTypeWithColor($ru_work_type),
        'ru_price_type' => getPriceType($ru_price_type),
        'ru_week' => getWeekYoil($ru_week),
        'mm_nation' => $mm_nation,
        'mm_age' => getKoreanAge($mm_birth),
        'ad_addr' => removeBigAddr($ad_addr),
        'chat_rm_idx' => ''
      ];
    }
    return $formatted_list;
  }

  // 추천인재 데이터 포맷팅 (search_reco_list2025()와 동일)
  private function _formatRecoList($list)
  {
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
        'ru_name' => isset($row['ru_name']) ? $row['ru_name'] : '',
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

  // 기업회원 대상 각 인재별 채팅방 정보 추가
  private function _addChatRoomInfo($list)
  {
    if (empty($list)) {
      return $list;
    }

    // 인재 idx 배열 생성
    $ru_idxs = [];
    foreach ($list as $row) {
      $ru_idxs[] = $row['ru_idx'];
    }

    // st_member_request + st_room 배치 조회
    // 각 인재에 대해 현재 로그인한 기업(mm_idx)과의 최근 지원/제안 이력을 조회해 채팅방 확인
    $chat_rooms = $this->db->query("
      SELECT
        mr.ru_idx,
        mr.rc_idx,
        r.rm_idx
      FROM st_member_request mr
      LEFT JOIN st_room r ON r.rc_idx = mr.rc_idx AND r.ru_idx = mr.ru_idx
      WHERE mr.ru_idx IN (" . implode(',', array_fill(0, count($ru_idxs), '?')) . ")
        AND (
          (mr.mq_type = 'RESUME' AND mr.mm_idx = ?)
          OR (mr.mq_type = 'RECRUIT' AND mr.rc_idx IN (
            SELECT rc_idx FROM st_recruit WHERE mm_idx = ?
          ))
        )
      ORDER BY mr.ru_idx, mr.mq_idx DESC
    ", array_merge($ru_idxs, [$this->mm_idx, $this->mm_idx]))->result_array();

    // 결과를 ru_idx 기준으로 매핑 (최신 이력만 사용)
    $room_map = [];
    foreach ($chat_rooms as $room) {
      if (!isset($room_map[$room['ru_idx']])) {
        $room_map[$room['ru_idx']] = isset($room['rm_idx']) ? $room['rm_idx'] : '';
      }
    }

    // 목록에 chat_rm_idx 추가
    foreach ($list as &$row) {
      $row['chat_rm_idx'] = isset($room_map[$row['ru_idx']]) ? $room_map[$row['ru_idx']] : '';
    }

    return $list;
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

    if ($ru_idx == 0) {
      // error_log("푸시알림 디버깅 - ru_idx가 0이므로 리다이렉트");
      redirect('https://nowjob.co.kr/resume/hr_list');
    }
    
    // 개인회원인 경우 자신의 이력서인지 확인
    if ($this->mm_type !== "COMP") {
      // 이력서 소유자 확인
      $resume_info = $this->member_model->getResume(["ru_idx" => $ru_idx]);
      if ($resume_info && isset($resume_info["mm_idx"])) {
        // 자신의 이력서인 경우 접근 허용
        if ($resume_info["mm_idx"] == $this->mm_idx) {
          // 자신의 이력서이므로 계속 진행
        } else {
          // 자신의 이력서가 아닌 경우 리다이렉트
         
          go_location("https://nowjob.co.kr/mypage/resume_list", "기업회원 전용 서비스입니다.");
        }
      } else {
        // 이력서 정보를 찾을 수 없는 경우
        
        go_location("https://nowjob.co.kr/mypage/resume_list", "기업회원 전용 서비스입니다.");
      }
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
    $data["top_button"] = "btn-back";
    $info["ru_hit"] = $info["ru_hit"] + 1;
    $data["view_list"] = $view_list;
    $data["reco_list"] = $reco_list["list"];
    // hr_post.php에서는 모든 게재 중인 공고를 조회 (제안 조건 없음)
    $data["recruit_list"] = $this->member_model->getRecruitActaiveListAll($this->mm_idx);
    $data["info"] = $info;
    $data["addr"] = $addr;
    $data["kakao_key"] = $kakao_key;
    $data["book_yn"] = ($bookmark == null ? "N" : "Y");
    $data["mem_info"] = $this->member_model->getMemberInfo($info["mm_idx"]);
    $data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $info["ru_idx"]]);
    $data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $info["ru_idx"]]);

    // 기업회원과 인재 사이의 지원/제안 이력 확인 (양방향)
    $data["already_requested"] = false;
    $data["chat_rm_idx"] = "";
    $data["chat_rc_idx"] = "";
    $data["chat_tab"] = "2"; // PC에서 기본값: 탭2(제안한 인재)
    if ($this->user->getUserType() == "COMP") {
      $latest_request = $this->db->query("
        SELECT rc_idx, mq_type FROM st_member_request
        WHERE ru_idx = ?
        AND (
          (mq_type = 'RESUME' AND mm_idx = ?)
          OR (mq_type = 'RECRUIT' AND rc_idx IN (SELECT rc_idx FROM st_recruit WHERE mm_idx = ?))
        )
        ORDER BY mq_idx DESC LIMIT 1
      ", [$ru_idx, $this->mm_idx, $this->mm_idx])->row_array();

      if ($latest_request && !empty($latest_request["rc_idx"])) {
        $room = $this->common_model->getRoom(["rc_idx" => $latest_request["rc_idx"], "ru_idx" => $ru_idx]);
        if ($room && !empty($room["rm_idx"])) {
          $data["already_requested"] = true;
          $data["chat_rm_idx"] = $room["rm_idx"];
          $data["chat_rc_idx"] = $latest_request["rc_idx"];
          // RESUME(기업이 제안): 탭2(제안한 인재) / RECRUIT(인재가 지원): 탭1(지원자 목록)
          $data["chat_tab"] = ($latest_request["mq_type"] == "RESUME") ? "2" : "1";
        }
      }
    }

    $this->layout->shop('/resume/hr_post', $data);
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
