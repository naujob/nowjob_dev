<?php
class Chat extends CI_Controller
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

    # 로그인확인후 이동처리
    if ($this->user->isLogin() == false) {
      go_href("/member/login");
    }

    $this->mm_id = $this->user->getUserId();
    $this->mm_idx = $this->user->getUserIdx();
  }

  # 메인화면
  function index()
  {
    $data = [];
    $user_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

    if ($user_info["mm_type"] == "COMP") {
      go_href("/chat/index_comp");
    } else {
      go_href("/chat/chat_list");
    }
  }

  # 기업 채팅
  function index_comp()
  {
    # 기업회원만 접근 가능
    if ($this->user->getUserType() !== "COMP") {
      echo "<script>
        alert('기업회원 전용 서비스입니다.');
        location.href = '/recruit/job_list';
      </script>";
      exit;
    }

    $data = [];

    //데이터 설정
    $params = [
      "pagesize" => 200,
      "page" => 1,
      "mm_idx" => $this->mm_idx,
    ];
    $result = $this->member_model->getRequestRecruitList($params);

    $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

    $data["point_info"] = $point_info;
    $data["user_type"] = $this->user->getUserType();
    $data["list"] = $result["list"];
    $data["count"] = $result["count"];
    $this->layout->shop('/chat/index_comp', $data);
  }

  # 기업 채팅 (새 UI)
  function index_comp_new()
  {
    # 기업회원만 접근 가능
    if ($this->user->getUserType() !== "COMP") {
      echo "<script>
        alert('기업회원 전용 서비스입니다.');
        location.href = '/recruit/job_list';
      </script>";
      exit;
    }

    $data = [];

    //데이터 설정
    $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

    $data["point_info"] = $point_info;
    $data["user_type"] = $this->user->getUserType();
    $this->layout->shop('/chat/index_comp_new', $data);
  }

  function index_comp_ajax()
  {
    $data = [];

    //데이터 설정
    $params = [
      "pagesize" => 200,
      "page" => 1,
      "mm_idx" => $this->mm_idx,
    ];
    $result1 = $this->member_model->getRequestRecruitList($params);

    $mm_idx = $this->user->getUserIdx();
    $result2 = $this->member_model->getRecruitActaiveList2($mm_idx);

    $mergedList = array_merge($result1["list"], $result2);

    //return $result["list"];
    //json_return("00","조회성공",["list"=>$result2["list"]]);
    //json_return("00","조회성공",["list"=>$result2]);
    json_return("00", "조회성공", ["list" => $mergedList]);
  }

  # 채팅
  function chat_list()
  {
    # 개인회원만 접근 가능
    if ($this->user->getUserType() !== "MEM") {
      echo "<script>
        alert('개인회원 전용 서비스입니다.');
        location.href = '/recruit/job_list';
      </script>";
      exit;
    }

    $data = [];

    $headers = apache_request_headers();
    $os_type = isset($data["os_type"]) ? $data["os_type"] : "";

    # 바로가기 처리
    $rm_idx = input_get("rm_idx");
    $view = input_get("view");

    if ($view == "true") {
      go_href("/chat/chat_view?rm_idx=" . $rm_idx);
      //exit;
    }

    $data["os_type"] = $os_type;
    $data["user_type"] = $this->user->getUserType();
    $this->layout->shop('/chat/chat_list', $data);
  }

  function chat_list2()
  {
    # 통합: /chat/chat_list로 리다이렉트 (쿼리스트링 유지)
    $query_string = $_SERVER['QUERY_STRING'];
    $target_url = '/chat/chat_list';
    if (!empty($query_string)) {
      $target_url .= '?' . $query_string;
    }
    redirect($target_url, 'location', 303);
  }

  function chat_list3()
  {
    # 통합: /chat/index_comp로 리다이렉트 (쿼리스트링 유지)
    $query_string = $_SERVER['QUERY_STRING'];
    $target_url = '/chat/index_comp';
    if (!empty($query_string)) {
      $target_url .= '?' . $query_string;
    }
    redirect($target_url, 'location', 303);
  }

  # 채팅상세
  function chat_view()
  {
    $data = [];
    $rm_idx = input_get("rm_idx");
    $rc_idx = input_get("rc_idx"); // URL 파라미터에서 rc_idx 가져오기 (자동 이동 시)
    $origin_rc_idx = input_get("origin_rc_idx"); // 다이렉트 채팅 시작 시 클릭한 실제 채용공고 rc_idx
    $user_id = $this->user->getUserId();

    # 룸정보 가져오기
    $room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);

    // 나간 채팅방인지 확인 (MongoDB에서 exit_yn 체크)
    $uri = get_mongo_uri();

    $dbName = get_mongo_db_name();
    $mongo_ru_idx = null;
    $mongo_rc_idx = null;
    
    try {
      @$mongo = new Mongo($uri);
      $database = $mongo->$dbName;
      $collection = $database->room;

      $query = ['rm_idx' => (int)$rm_idx];
      $mongo_room = $collection->findOne($query);

      if ($mongo_room) {
        // MongoDB에서 ru_idx와 rc_idx 가져오기
        if (isset($mongo_room["ru_idx"])) {
          $mongo_ru_idx = $mongo_room["ru_idx"];
        }
        if (isset($mongo_room["rc_idx"])) {
          $mongo_rc_idx = $mongo_room["rc_idx"];
        }

        $rm_users = isset($mongo_room["rm_users"]) ? $mongo_room["rm_users"] : [];
        $user_phone = $this->user->getUserPhone();

        // 나간 채팅방이면 접근 차단
        if (is_room_user_exited($rm_users, $user_id, $user_phone)) {
          $current_user_type = $this->user->getUserType();

          // 사용자 타입에 따라 적절한 목록 페이지로 리다이렉트
          $redirect_url = ($current_user_type === "COMP") ? '/chat/index_comp' : '/chat/chat_list';
          echo "<script>alert('나간 채팅방입니다.'); location.href='{$redirect_url}';</script>";
          exit;
        }
      }
    } catch (Exception $e) {
      // MongoDB 오류 시 계속 진행 (기존 동작 유지)
    }

    // rc_idx와 ru_idx 결정 (우선순위: MySQL room > MongoDB > URL 파라미터)
    $final_rc_idx = null;
    $final_ru_idx = null;
    
    if ($room && isset($room["rm_type"]) && $room["rm_type"] == "recruit") {
      // MySQL room에서 가져온 값 우선 사용
      $final_rc_idx = isset($room["rc_idx"]) ? $room["rc_idx"] : null;
      $final_ru_idx = isset($room["ru_idx"]) ? $room["ru_idx"] : null;
    }
    
    // MySQL에 없으면 MongoDB에서 가져온 값 사용
    if (!$final_rc_idx && $mongo_rc_idx) {
      $final_rc_idx = $mongo_rc_idx;
    }
    if (!$final_ru_idx && $mongo_ru_idx) {
      $final_ru_idx = $mongo_ru_idx;
    }
    
    // MongoDB에도 없으면 URL 파라미터 사용 (자동 이동 시)
    if (!$final_rc_idx && $rc_idx) {
      $final_rc_idx = $rc_idx;
    }
    
    // ru_idx가 없으면 member_request 테이블에서 조회 (제안하기 완료 후 자동 이동 시)
    if (!$final_ru_idx && $final_rc_idx) {
      // 제안하기 완료 후 자동 이동 시, 해당 rc_idx에 대한 ru_idx를 찾기 위해 member_request 테이블 조회
      $member_request = $this->db->query("
        SELECT ru_idx 
        FROM st_member_request 
        WHERE mm_idx = ? 
        AND rc_idx = ? 
        AND mq_type = 'RESUME' 
        ORDER BY mq_idx DESC 
        LIMIT 1
      ", [$this->mm_idx, $final_rc_idx])->row_array();
      
      if ($member_request && isset($member_request["ru_idx"])) {
        $final_ru_idx = $member_request["ru_idx"];
      }
    }

    // recruit 타입이고 rc_idx와 ru_idx가 있으면 데이터 로드
    if (($room && isset($room["rm_type"]) && $room["rm_type"] == "recruit") || ($final_rc_idx && $final_ru_idx)) {
      if ($final_rc_idx) {
        $data["recruit"] = $this->member_model->getRecruitDetail($final_rc_idx, $this->mm_idx);
        // recruit_card_template.php 사용을 위한 변환된 필드 추가
        if (isset($data["recruit"])) {
          $row = $data["recruit"];
          // 국적 표시 정규화
          if ($row['rc_nation_nm'] == "무관" || $row['rc_nation_nm'] == "내국인,외국인") {
            $data["recruit"]["rc_nation_nm"] = "국적무관";
          }
          $data["recruit"]["rt_recruit_img"] = getRecruitImg($row);
          // rc_thumb이 null이거나 빈 값일 경우 getRecruitImg 함수로 대체 이미지 처리
          if (empty($data["recruit"]["rc_thumb"])) {
            $data["recruit"]["rc_thumb"] = getRecruitImg($row);
          }
          $data["recruit"]["rt_work_img"] = getWorkingImg($row["rc_work"]);
          $data["recruit"]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
          $data["recruit"]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]);
          $data["recruit"]["rt_price_pay"] = getConvertPrice($row["rc_price_pay"]);
          $data["recruit"]["rt_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);
        }
      }
      if ($final_ru_idx) {
        $data["resume"] = $this->member_model->getResumeDetail($final_ru_idx, $this->mm_idx);
        // resume_card_template.php(채팅화면 상단 이력서 카드) 사용을 위한 변환된 필드 추가
        $data["resume"] = enrichResumeForChatCard($data["resume"]);

        if ($final_ru_idx) {
          $data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $final_ru_idx]);
          $data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $final_ru_idx]);
        }
      }
    }

    // 상대방 ID 찾기 (MongoDB에 상대방이 입장하기 전에는 정보가 없으므로 서버에서 찾기)
    $opponent_user_id = null;
    $user_type = $this->user->getUserType();
    
    if ($user_type == "COMP") {
      // 기업회원이 제안한 경우: 이력서 소유자가 상대방
      if (isset($data["resume"]) && isset($data["resume"]["mm_id"])) {
        $opponent_user_id = $data["resume"]["mm_id"];
      }
    } else {
      // 개인회원이 지원한 경우: 채용공고 소유자가 상대방
      if (isset($data["recruit"]) && isset($data["recruit"]["mm_id"])) {
        $opponent_user_id = $data["recruit"]["mm_id"];
      }
    }
    
    // 상대방 ID를 찾지 못한 경우 member_request 테이블에서 조회
    if (!$opponent_user_id && $final_rc_idx && $final_ru_idx) {
      if ($user_type == "COMP") {
        // 제안하기의 경우: 이력서 소유자 찾기
        $resume_owner = $this->db->query("
          SELECT aa.mm_id 
          FROM st_member aa
          INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx
          WHERE bb.ru_idx = ?
          LIMIT 1
        ", [$final_ru_idx])->row_array();
        
        if ($resume_owner && isset($resume_owner["mm_id"])) {
          $opponent_user_id = $resume_owner["mm_id"];
        }
      } else {
        // 지원하기의 경우: 채용공고 소유자 찾기
        $recruit_owner = $this->db->query("
          SELECT aa.mm_id 
          FROM st_member aa
          INNER JOIN st_recruit bb ON aa.mm_idx = bb.mm_idx
          WHERE bb.rc_idx = ?
          LIMIT 1
        ", [$final_rc_idx])->row_array();
        
        if ($recruit_owner && isset($recruit_owner["mm_id"])) {
          $opponent_user_id = $recruit_owner["mm_id"];
        }
      }
    }

    // 이 방에서 개인회원(MEM)의 user_id 결정 (전화하기 버튼용)
    // 방에는 항상 MEM 1명, COMP 1명만 있으므로 두 경우 모두 $individual_user_id는 MEM을 가리킴
    $individual_user_id = null;
    if ($user_type == "MEM") {
      // 내가 MEM이면 내 user_id
      $individual_user_id = $user_id;
    } else if ($user_type == "COMP" && $opponent_user_id) {
      // 내가 COMP이면 상대방(이력서 소유자=MEM)의 user_id
      $individual_user_id = $opponent_user_id;
    }

    // MongoDB에서 개인회원이 보낸 일반 메시지 또는 지원 메시지 확인
    $show_call_btn = false;
    if ($mongo_room && isset($mongo_room["rm_messages"]) && $individual_user_id) {
      foreach ($mongo_room["rm_messages"] as $msg) {
        $msg_chat_type = isset($msg["chat_type"]) ? $msg["chat_type"] : "";
        $msg_type = isset($msg["mg_type"]) ? $msg["mg_type"] : "";
        $msg_user_id = isset($msg["user_id"]) ? $msg["user_id"] : "";
        $msg_text = isset($msg["mg_text"]) ? $msg["mg_text"] : "";

        // 조건 1: chat_type이 비어있고, text 메시지, 개인회원이 보낸 경우
        $is_general_message = empty($msg_chat_type) && $msg_type === "text" && $msg_user_id === $individual_user_id;

        // 조건 2: chat_type이 "recruit"이고, 개인회원이 보낸 지원 메시지만 (제안 메시지 제외)
        $is_apply_message = $msg_chat_type === "recruit" && $msg_type === "text" && $msg_user_id === $individual_user_id &&
                           strpos($msg_text, "지원합니다") !== false;

        if ($is_general_message || $is_apply_message) {
          $show_call_btn = true;
          break;
        }
      }
    }

    // 로그인 사용자 타입에 따라 전화 걸 대상 번호 결정
    $call_target_phone = null;
    if ($user_type == "MEM") {
      // 개인회원이면 기업 전화번호
      $call_target_phone = isset($data["recruit"]["mm_phone"]) ? $data["recruit"]["mm_phone"] : null;
    } else if ($user_type == "COMP" && !empty($opponent_user_id)) {
      // 기업회원이면 개인회원 전화번호 조회
      $opponent_member = $this->member_model->getMember(["mm_id" => $opponent_user_id]);
      if ($opponent_member && !empty($opponent_member["mm_phone"])) {
        $call_target_phone = $opponent_member["mm_phone"];
      }
    }

    $data["user_type"] = $user_type;
    $data["rm_idx"] = $rm_idx;
    $data["opponent_user_id"] = $opponent_user_id; // 상대방 ID 전달
    $data["individual_user_id"] = $individual_user_id; // 개인회원 ID 전달 (JS 실시간 반영용)
    $data["show_call_btn"] = $show_call_btn; // 전화하기 버튼 표시 여부
    $data["call_target_phone"] = $call_target_phone; // 전화 대상 번호

    // room이 없거나 rc_idx/ru_idx가 없을 때를 대비해 기본값 설정
    if (!$room) {
      $data["room"] = [
        "rm_idx" => $rm_idx,
        "rm_type" => "recruit",
        "rc_idx" => $final_rc_idx,
        "ru_idx" => $final_ru_idx
      ];
    } else {
      $data["room"] = $room;
      // room에 rc_idx와 ru_idx가 없으면 추가
      if (!isset($data["room"]["rc_idx"]) && $final_rc_idx) {
        $data["room"]["rc_idx"] = $final_rc_idx;
      }
      if (!isset($data["room"]["ru_idx"]) && $final_ru_idx) {
        $data["room"]["ru_idx"] = $final_ru_idx;
      }
    }

    // 방 분류: 정식(RECRUIT/RESUME) 요청이 1건이라도 있으면 정식 방, 없으면 순수 채팅방(다이렉트)
    // (다이렉트 채팅은 요청 기록을 남기지 않으므로 기록이 없거나 레거시 _DIRECT 기록만 있는 방이 순수 채팅방)
    $room_request_state = $this->member_model->getRoomRequestState($rm_idx);

    // 공고 카드 숨김 여부: 제안/지원 이력이 없는 방
    // - 방의 rc_idx가 더미 공고(rc_auto_yn='Y')인 레거시 다이렉트 방
    // - 요청 기록이 하나도 없는 순수 채팅방 (rc_idx가 실제 공고여도 제안/지원한 공고가 아니므로 숨김)
    // 레거시 다이렉트 방에서 방 안 제안 후 rc_idx가 실제 공고로 바뀐 경우는 기록이 있으므로 카드를 보여준다.
    $data["is_direct_chat_room"] = !$room_request_state["any"];
    if (!$data["is_direct_chat_room"] && $final_rc_idx) {
      $recruit_auto_row = $this->db->query(
        "SELECT rc_auto_yn FROM st_recruit WHERE rc_idx = ?",
        [$final_rc_idx]
      )->row_array();

      if ($recruit_auto_row && $recruit_auto_row["rc_auto_yn"] == "Y") {
        $data["is_direct_chat_room"] = true;
      }
    }

    // 다이렉트(순수 채팅방) 여부: 정식 요청이 없으면 다이렉트 (헤더/제안 패널에서 사용)
    $data["is_direct_chat"] = !$room_request_state["formal"];

    // 상대방의 실제(더미가 아닌) 이력서/채용공고 목록을 항상 mm_idx 기준으로 조회
    // is_direct_chat 판정에 의존하지 않고, 상대방 mm_idx로 직접 조회하여 항상 최신 데이터 사용
    $data["opponent_recruit_list"] = [];
    $data["opponent_resume_list"] = [];

    // 상대방 탈퇴 여부: 탈퇴(N)/관리자삭제(H)이거나, 15일 뒤 정리로 mm_id가 del_{mm_idx}로 바뀌어 조회되지 않는 경우
    // → 채팅방 헤더에 이력서명/회사명 대신 '탈퇴회원'으로 표시
    $data["opponent_withdrawn"] = false;
    if ($opponent_user_id) {
      $opponent_check = $this->member_model->getMember(["mm_id" => $opponent_user_id]);
      if (!$opponent_check || (isset($opponent_check["mm_status"]) && in_array($opponent_check["mm_status"], ["N", "H"], true))) {
        $data["opponent_withdrawn"] = true;
      }
    }

    if ($opponent_user_id) {
      $opponent_member = $this->member_model->getMember(["mm_id" => $opponent_user_id]);
      if ($opponent_member && isset($opponent_member["mm_idx"])) {
        if ($user_type == "COMP") {
          // 내가 COMP(제안하기)면 상대방은 MEM → 상대방의 실제 이력서 목록 필요
          $all_opponent_resumes = $this->member_model->getResumeList(["mm_idx" => $opponent_member["mm_idx"]]);
          $data["opponent_resume_list"] = array_values(array_filter($all_opponent_resumes, function($item) {
            return !isset($item["ru_auto_yn"]) || $item["ru_auto_yn"] !== "Y";
          }));

          // 상대방의 대표이력서(ru_show_yn = 'Y') 자동 선택용 조회
          // ru_status='Y' 필터 필수 — 위의 opponent_resume_list 조회는 필터가 없어 삭제된(ru_status='N') 이력서까지 포함될 수 있음
          // 대표이력서 자동 선택 시 삭제된 이력서가 뽑히는 걸 막기 위해 명시적으로 필터링한다.
          $data["opponent_main_resume"] = $this->member_model->getResume([
            "mm_idx" => $opponent_member["mm_idx"],
            "ru_show_yn" => "Y",
            "ru_status" => "Y",
          ]);
          // 대표이력서로 지정된 게 없으면(설정 안 한 경우) 실제 이력서 중 첫 번째로 대체
          // 단, opponent_resume_list 자체에는 ru_status 필터가 없어 삭제된 이력서가 섞여 있을 수 있으므로
          // 대체 후보를 고를 때는 ru_status가 'N'이 아닌 것만 사용한다.
          if (!$data["opponent_main_resume"]) {
            foreach ($data["opponent_resume_list"] as $candidate) {
              if (!isset($candidate["ru_status"]) || $candidate["ru_status"] !== "N") {
                $data["opponent_main_resume"] = $candidate;
                break;
              }
            }
          }

          // opponent_main_resume는 st_resume 단독조회라 mm_thumb/mm_sex가 없음(정식 제안의 getResumeDetail()은
          // st_member를 join해서 aa.mm_thumb/aa.mm_sex를 포함함) → 이미 조회해둔 opponent_member(st_member)로 보강
          if ($data["opponent_main_resume"]) {
            $data["opponent_main_resume"]["mm_thumb"] = isset($opponent_member["mm_thumb"]) ? $opponent_member["mm_thumb"] : "";
            $data["opponent_main_resume"]["mm_sex"] = isset($opponent_member["mm_sex"]) ? $opponent_member["mm_sex"] : "";
          }
        } else {
          // 내가 MEM(지원하기)면 상대방은 COMP → 상대방의 실제 채용공고 목록 필요
          $all_opponent_recruits = $this->member_model->getRecruitList(["mm_idx" => $opponent_member["mm_idx"]]);
          $data["opponent_recruit_list"] = array_values(array_filter($all_opponent_recruits, function($item) {
            return !isset($item["rc_auto_yn"]) || $item["rc_auto_yn"] !== "Y";
          }));

          // 이 채팅이 특정 채용공고(origin_rc_idx)에서 시작됐다면 그 공고를 자동 지원 대상으로 사용
          $data["opponent_main_recruit"] = null;
          if (!empty($origin_rc_idx)) {
            foreach ($data["opponent_recruit_list"] as $candidate) {
              if ($candidate["rc_idx"] == $origin_rc_idx) {
                $data["opponent_main_recruit"] = $candidate;
                break;
              }
            }
          }
          // 시작 공고를 모르거나(일반 다이렉트 채팅) 목록에 없으면(마감 등) 실제 등록된 공고 중 첫 번째로 대체
          if (!$data["opponent_main_recruit"] && count($data["opponent_recruit_list"]) > 0) {
            $data["opponent_main_recruit"] = $data["opponent_recruit_list"][0];
          }
        }
      }
    }

    // 채팅방 안 "제안하기(COMP)/지원하기(MEM)" 바텀시트에 사용할 목록
    if ($user_type == "COMP") {
      $all_recruits = $this->member_model->getRecruitActaiveListAll($this->mm_idx);
      // 자동 생성 채용공고 제외 (rc_auto_yn != 'Y')
      $data["my_recruit_list"] = array_filter($all_recruits, function($item) {
        return !isset($item["rc_auto_yn"]) || $item["rc_auto_yn"] !== "Y";
      });
      // 배열 인덱스 재정렬
      $data["my_recruit_list"] = array_values($data["my_recruit_list"]);
    } else {
      $all_resumes = $this->member_model->getResumeList(["mm_idx" => $this->mm_idx, "ru_status" => "Y"]);
      // 자동 생성 이력서 제외 (ru_auto_yn != 'Y')
      $data["my_resume_list"] = array_filter($all_resumes, function($item) {
        return !isset($item["ru_auto_yn"]) || $item["ru_auto_yn"] !== "Y";
      });
      // 배열 인덱스 재정렬
      $data["my_resume_list"] = array_values($data["my_resume_list"]);

      // "지원하기" 1단계 팝업 제거용: 본인 대표이력서 자동 선택 (없으면 실제 이력서 중 첫 번째)
      $data["my_main_resume"] = $this->member_model->getResume([
        "mm_idx" => $this->mm_idx,
        "ru_show_yn" => "Y",
        "ru_status" => "Y",
      ]);
      if (!$data["my_main_resume"] && count($data["my_resume_list"]) > 0) {
        $data["my_main_resume"] = $data["my_resume_list"][0];
      }
    }

    $this->layout->shop('/chat/chat_view', $data);
  }

  # 중고상품 채팅
  function trade_list()
  {
    $data = [];

    # 바로가기 처리
    $rm_idx = input_get("rm_idx");
    $view = input_get("view");

    if ($view == "true") {
      go_href("/chat/trade_view?rm_idx=" . $rm_idx);
      exit;
    }

    $data["user_type"] = $this->user->getUserType();
    $this->layout->shop('/chat/trade_list', $data);
  }

  # 중고상품 채팅상세
  function trade_view()
  {
    $data = [];

    $rm_idx = input_get("rm_idx");

    # 룸정보 가져오기
    $room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);
    $td_idx = $room["td_idx"];

    $mm_idx = $this->user->getUserIdx();
    $trade_info = $this->site_model->getTrade(["td_idx" => $td_idx]);
    $reco_list = $this->site_model->getTradeRecoList($td_idx, $mm_idx);
    $addr_list = $this->member_model->getAddressList(["ad_type" => "TRADE", "ad_key" => $td_idx]);
    $mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
    $mem_trade_info = $this->member_model->getMember(["mm_idx" => $trade_info["mm_idx"]]);

    # hit수 업데이트
    $trade_info["td_hit"] = $trade_info["td_hit"] + 1;
    $this->site_model->updateTrade(["td_hit" => $trade_info["td_hit"]], ["td_idx" => $td_idx]);


    $data["rm_idx"] = $rm_idx;
    $data["room"] = $room;
    $data["info"] = $trade_info;
    $data["reco_list"] = $reco_list;
    $data["addr_list"] = $addr_list;
    $data["mem_info"] = $mem_info;
    $data["mem_trade_info"] = $mem_trade_info;

    $this->layout->shop('/chat/trade_view', $data);
  }
}
