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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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

    if ($info == null || $info["rc_show_yn"] != "Y") {
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
        $view_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
        $view_list[$key]["rt_price"] = getRecuitPrice($row);
      }
    }

    # 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
    if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
      $info["rc_img99"] = getRecruitImg($info, "detail");
    } else {
      $info["rc_img99"] = "";
    }

    $info["rc_hit"] = $info["rc_hit"] + 1;
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    $skip_search_state_load = false;

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
        $search_txt["price_type"] = $price_type_values;
        $skip_search_state_load = true;
      }
    }

    $data["mm_type"] = $this->mm_type;
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    $data["price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
    $data["work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
    $data["week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
    $data["work_code"]  = $this->common->getSessionDataList("WORK_TYPE");
    $data["search_txt"]  = $search_txt;
    $data["skip_search_state_load"] = $skip_search_state_load;

    $this->layout->shop('/recruit/job_list', $data);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    // FCM 푸시 알림 디버깅 로그 추가
    //error_log("job_post 함수 호출 - rc_idx: " . ($rc_idx ? $rc_idx : 'null') . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);

    if ($rc_idx == null) {
      //error_log("job_post 리다이렉트 - rc_idx가 null");
      redirect('https://nowjob.co.kr/recruit/job_list');
    }
    $data = [];

    $os_type = isset($data["os_type"]) ? $data["os_type"] : "";
    $info = $this->recruit_model->getRecruitDetail($rc_idx, $this->mm_idx);

    // 채용공고 정보 디버깅 로그
    //error_log("job_post 채용공고 조회 결과 - rc_idx: " . $rc_idx . ", info: " . ($info ? "존재" : "없음") . ", rc_show_yn: " . ($info ? $info["rc_show_yn"] : "N/A"));

    $addr_info = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
    $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
    $bookmark = $this->site_model->getBookmark(["bk_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "bk_key" => $rc_idx]);

    if ($info == null || $info["rc_show_yn"] != "Y") {
      //error_log("job_post 리다이렉트 - 채용공고 없음 또는 비활성화, rc_idx: " . $rc_idx);
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
        $view_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
        $view_list[$key]["rt_price"] = getRecuitPrice($row);
      }
    }

    # 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
    if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
      $info["rc_img99"] = getRecruitImg($info, "detail");
    } else {
      $info["rc_img99"] = "";
    }

    $info["rc_hit"] = $info["rc_hit"] + 1;
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

    $this->layout->shop('/recruit/job_post', $data);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
    $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
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
			$data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
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
			$data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
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
        $data["wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
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

  function retiremem15day()
  {
    echo date("Y-m-d H:i:s") . " start\n";

    # 트랙젝션 시작
    $this->db->trans_begin();

    // 현재 날짜보다 15일 이전의 데이터를 조회
    $sql = "
          SELECT mm_id, mm_phone
          FROM st_member
          WHERE fire_dt < DATE_SUB(NOW(), INTERVAL 15 DAY)
            AND fire_dt <> '0000-00-00 00:00:00'
            AND mm_id not like '%x%'
      ";
    //echo $sql;
    $query = $this->db->query($sql);
    $list = $query->result_array();

    //print_r($list);
    //$data["upAll_list"] = $upAll_list;

    // 일련번호 변수 초기화
    $serialNumber = 1;

    foreach ($list as $key => $row) {

      $mm_id = $row['mm_id'];
      $mm_phone = $row['mm_phone'];

      //echo "$mm_id";

      // temp 변수 생성 (mm_id + "x" + 숫자일련번호)
      //$temp = $mm_id . 'x' . $serialNumber;

      //$sql1 = "UPDATE st_promotion SET mm_id = '$temp' WHERE mm_id = '$mm_id' ";
      //echo $sql1;
      //$query = $this->db->query($sql1);

      $mm_phoneX1 = $mm_phone . 'x1';
      $temp     = $mm_id . 'x1';

      //$result = $this->member_model->updateMember(["mm_status"=>"H","mm_di"=>"del","mm_phone"=>"del"],["mm_idx"=>$idx]);

      $sql1 = "UPDATE st_member SET mm_id = '$temp', mm_phone = '$mm_phoneX1', mm_di = '$temp' WHERE mm_id = '$mm_id' ";
      //$result1 = $this->db->query($sql1);

      //$sql2 = "UPDATE st_member SET mm_id = '$temp' WHERE mm_id = '$mm_id' ";
      echo $sql1 . "\n";
      //$query = $this->db->query($sql2);

      $result1 = $this->db->query($sql1);
    }

    # 트랙젝션 완료
    $this->db->trans_commit();

    echo date("Y-m-d H:i:s") . " end\n";
  }
}
