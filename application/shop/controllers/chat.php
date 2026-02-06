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
    $this->layout->shop('/chat/chat_list2', $data);
  }

  function chat_list3()
  {
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
    $this->layout->shop('/chat/chat_list3', $data);
  }

  # 채팅상세
  function chat_view()
  {
    $data = [];
    $rm_idx = input_get("rm_idx");
    $rc_idx = input_get("rc_idx"); // URL 파라미터에서 rc_idx 가져오기 (자동 이동 시)
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
        $user_exited = false;

        foreach ($rm_users as $room_user) {
          if (isset($room_user["user_id"]) && $room_user["user_id"] == $user_id) {
            // exit_yn='Y'이면 나간 채팅방
            if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
              $user_exited = true;
              break;
            }
          }
        }

        // 나간 채팅방이면 접근 차단
        if ($user_exited) {
          echo "<script>alert('나간 채팅방입니다.'); location.href='/chat/chat_list';</script>";
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
      }
      if ($final_ru_idx) {
        $data["resume"] = $this->member_model->getResumeDetail($final_ru_idx, $this->mm_idx);
        
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

    $data["user_type"] = $user_type;
    $data["rm_idx"] = $rm_idx;
    $data["opponent_user_id"] = $opponent_user_id; // 상대방 ID 전달
    
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
