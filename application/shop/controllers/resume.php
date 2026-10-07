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
    // URL의 page 파라미터 읽어서 정수 검증
    $requested_page = (int) input_get('page', 1);
    if ($requested_page < 1) {
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

    // 페이지별 OFFSET 계산
    $offset = ($requested_page - 1) * 20;

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
      LIMIT $offset, 20
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

    // 오늘 등록된 이력서 개수 조회
    $sql_today_count = "
      SELECT COUNT(DISTINCT bb.ru_idx) as total
      FROM st_member aa
      INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y' AND DATE(bb.reg_dt) = CURDATE()
      WHERE aa.mm_status = 'Y'
    ";
    $query_today_count = $this->db->query($sql_today_count);
    $today_count_result = $query_today_count->row_array();
    $today_recruit_count = isset($today_count_result['total']) ? $today_count_result['total'] : 0;

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
    $data["today_recruit_count"] = $today_recruit_count;
    $data["use_server_render"] = $use_server_render;
    $data["initial_reco"] = $initial_reco['list'];
    $data["has_initial_reco"] = $has_initial_reco;
    $data["initial_page"] = $requested_page;

    $this->layout->shop('/resume/hr_list', $data);
  }

  // 인재 정보 데이터 포맷팅 (뷰와 AJAX 응답에서 공통 사용)
  private function _formatResumeList($list)
  {
    $truncate_length = getTruncateLength();
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
        'ru_name' => (mb_strlen($row['ru_name']) > $truncate_length) ? mb_substr($row['ru_name'], 0, $truncate_length) . '...' : $row['ru_name'],
        'ru_resume_img' => getResumeImg($row),
        'ru_work_img' => getWorkingImg($ru_work),
        'ru_work_type' => getWorkTypeWithColor($ru_work_type),
        'ru_price_type' => getPriceType($ru_price_type),
        'ru_week' => getWeekYoil($ru_week),
        'mm_nation' => $mm_nation,
        'mm_age' => getKoreanAge($mm_birth),
        'ad_addr' => removeBigAddr($ad_addr),
        'chat_rm_idx' => '',
        'chat_type' => ''
      ];
    }
    return $formatted_list;
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

  // 기업회원 대상 각 인재별 채팅방 정보 추가
  private function _addChatRoomInfo($list)
  {
    if (empty($list)) {
      return $list;
    }

    // 정식 인재 idx와 그들의 mm_idx(이력서 작성자) 추출
    $ru_idxs = array();
    $mm_idxs = array();
    foreach ($list as $row) {
      $ru_idxs[] = $row['ru_idx'];
      if (!in_array($row['mm_idx'], $mm_idxs)) {
        $mm_idxs[] = $row['mm_idx'];
      }
    }

    // 각 mm_idx의 더미 이력서(ru_auto_yn='Y') 조회
    $dummy_result = array();
    if (!empty($mm_idxs)) {
      $dummy_result = $this->db->query("
        SELECT mm_idx, ru_idx
        FROM st_resume
        WHERE mm_idx IN (" . implode(',', array_fill(0, count($mm_idxs), '?')) . ")
          AND ru_auto_yn = 'Y'
      ", $mm_idxs)->result_array();

      foreach ($dummy_result as $dummy) {
        if (!in_array($dummy['ru_idx'], $ru_idxs)) {
          $ru_idxs[] = $dummy['ru_idx'];
        }
      }
    }

    // ru_idx → mm_idx 역방향 맵 구성 (정식 + 더미 함께)
    $ru_idx_to_mm_idx = array();
    // 정식 이력서
    foreach ($list as $row) {
      $ru_idx_to_mm_idx[$row['ru_idx']] = $row['mm_idx'];
    }
    // 더미 이력서
    foreach ($dummy_result as $dummy) {
      $ru_idx_to_mm_idx[$dummy['ru_idx']] = $dummy['mm_idx'];
    }

    // st_member_request 배치 조회
    // mq_type으로 아이콘 종류 판단 (rc_auto_yn 사용 안 함)
    // 정식(RECRUIT/RESUME) 요청 우선 → 최신 순으로 정렬 (레거시 _DIRECT 기록보다 정식 방을 우선 선택)
    $chat_rooms = $this->db->query("
      SELECT
        mr.ru_idx,
        mr.mq_type,
        mr.rm_idx
      FROM st_member_request mr
      WHERE mr.ru_idx IN (" . implode(',', array_fill(0, count($ru_idxs), '?')) . ")
        AND (
          (mr.mq_type IN ('RESUME','RESUME_DIRECT') AND mr.mm_idx = ?)
          OR (mr.mq_type IN ('RECRUIT','RECRUIT_DIRECT') AND mr.rc_idx IN (
            SELECT rc_idx FROM st_recruit WHERE mm_idx = ?
          ))
        )
      ORDER BY (mr.mq_type IN ('RECRUIT','RESUME')) DESC, mr.mq_idx DESC
    ", array_merge($ru_idxs, array($this->mm_idx, $this->mm_idx)))->result_array();

    // mm_idx 단위로 첫 등장 1건만 채택 (정렬상 정식 요청이 있으면 정식이 먼저 나옴)
    $room_by_mm_idx = array();
    foreach ($chat_rooms as $room) {
      // ru_idx → mm_idx 역방향 조회
      $owner_mm_idx = isset($ru_idx_to_mm_idx[$room['ru_idx']]) ? $ru_idx_to_mm_idx[$room['ru_idx']] : null;

      // 해당 인재의 요청을 이미 찾았으면 스킵
      if ($owner_mm_idx === null || isset($room_by_mm_idx[$owner_mm_idx])) {
        continue;
      }

      // mq_type으로 아이콘 종류 판단
      $chat_type = (strpos($room['mq_type'], '_DIRECT') !== false) ? 'direct' : 'propose';

      $room_by_mm_idx[$owner_mm_idx] = array(
        'rm_idx' => isset($room['rm_idx']) ? $room['rm_idx'] : '',
        'chat_type' => $chat_type
      );
    }

    // 요청 기록이 없는 순수 채팅방(채팅하기) 보완: 기록이 없는 인재는 기업↔인재 쌍의 방을 찾아 direct 배지로 표시
    $missing_mm_idxs = array();
    foreach ($mm_idxs as $owner_mm_idx) {
      if (!isset($room_by_mm_idx[$owner_mm_idx])) {
        $missing_mm_idxs[] = $owner_mm_idx;
      }
    }
    if (!empty($missing_mm_idxs)) {
      $pure_rooms = $this->db->query("
        SELECT ru.mm_idx, room.rm_idx
        FROM st_room room
        INNER JOIN st_recruit rc ON rc.rc_idx = room.rc_idx
        INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
        WHERE room.rm_type = 'recruit'
          AND rc.mm_idx = ?
          AND ru.mm_idx IN (" . implode(',', array_fill(0, count($missing_mm_idxs), '?')) . ")
        ORDER BY room.rm_idx DESC
      ", array_merge(array($this->mm_idx), $missing_mm_idxs))->result_array();

      foreach ($pure_rooms as $pure_room) {
        if (!isset($room_by_mm_idx[$pure_room['mm_idx']])) {
          $room_by_mm_idx[$pure_room['mm_idx']] = array(
            'rm_idx' => $pure_room['rm_idx'],
            'chat_type' => 'direct'
          );
        }
      }
    }

    // 나간 방(exit_yn='Y')을 배치로 조회
    $rm_idxs_to_check = array();
    foreach ($room_by_mm_idx as $room_info) {
      if (!empty($room_info['rm_idx'])) {
        $rm_idxs_to_check[] = $room_info['rm_idx'];
      }
    }

    $exited_rm_idxs = array();
    if (!empty($rm_idxs_to_check)) {
      $exited_rm_idxs = get_exited_rm_idxs($rm_idxs_to_check, $this->user->getUserId(), $this->user->getUserPhone());
      error_log("[resume.php _addChatRoomInfo] rm_idxs_to_check=" . json_encode($rm_idxs_to_check) . ", exited_rm_idxs=" . json_encode($exited_rm_idxs));
    }
    $exited_set = array_flip($exited_rm_idxs);

    // 목록에 chat_rm_idx와 chat_type 추가
    // mm_idx로 직접 조회 (우선순위 비교 없음)
    foreach ($list as &$row) {
      $owner_mm_idx = $row['mm_idx'];

      if (isset($room_by_mm_idx[$owner_mm_idx])) {
        $rm_idx = $room_by_mm_idx[$owner_mm_idx]['rm_idx'];

        // 나간 방이면 배지 숨김
        if (isset($exited_set[$rm_idx])) {
          $row['chat_rm_idx'] = '';
          $row['chat_type'] = '';
        } else {
          $row['chat_rm_idx'] = $rm_idx;
          $row['chat_type'] = $room_by_mm_idx[$owner_mm_idx]['chat_type'];
        }
      } else {
        $row['chat_rm_idx'] = '';
        $row['chat_type'] = '';
      }
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
