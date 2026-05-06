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

    $params["mm_idx"] = $this->mm_idx;
    $params["page"] = 1;
    $params["pagesize"] = 100;
    $tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "mem"]);
    $tag_search = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);

    $data["tag_list"] = $tag_info["mem"] != "" ? explode(",", $tag_info["mem"]) : [];
    $data["tag_search"] = $tag_search["search"] != "" ? explode(",", $tag_search["search"]) : [];
    $data["addr_info"] = $addr_info;
    $data["mm_type"] = $this->mm_type;
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    $data["up_list"] = $this->common_model->getHomeUpCompList($addr_info, 8);

    $this->layout->shop('/resume/hr_list', $data);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
