<?php
class Compage_proc extends CI_Controller
{
  # 전역변수 선언
  public $mm_idx;
  public $mm_id;

  function __construct()
  {
    parent::__construct();

    # 모델 로드
    $this->load->model('sitemodel', 'site_model');
    $this->load->model('settingmodel', 'setting_model');
    $this->load->model('membermodel', 'member_model');
    $this->load->model('ordermodel', 'order_model');
    $this->load->model('commonmodel', 'common_model');
    $this->load->model('recruitmodel', 'recruit_model');

    # 로그인확인후 이동처리
    if ($this->user->isLogin() == false) {
      json_return("99", "로그인후 이용가능한 기능입니다.");
    }

    $this->mm_id = $this->user->getUserId();
    $this->mm_idx = $this->user->getUserIdx();
  }

  // SmartEditor2 HTML 정제
  private function cleanSmartEditorDesc($html_content)
  {
    $html_content = trim($html_content);

    if (empty($html_content)) {
      return '';
    }

    $allowed_tags = '<p><br><b><i><u><strong><em><a><img><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6><span>';
    $clean_html = strip_tags($html_content, $allowed_tags); // 첫 번째 입력값 ($text): 태그를 지우고 싶은 원본 문자열 (필수), 두 번째 입력값 ($allowed_tags): "이 태그는 지우지 말고 남겨줘!" 하고 예외로 둘 태그들 (선택)

    return $clean_html;
  }

  function point_charge_step1()
  {
    # 데이터 추가.
    $point_key = input_post("point_key", "");
    $point_price = input_post("point_price", "");

    #로그 생성
    $sl_txt = [
      "point_key" => $point_key,
      "point_price" => $point_price
    ];

    $this->site_model->setSiteLog(
      [
        "mm_idx" => $this->mm_idx,
        "session_id" => session_id(),
        "sl_type" => "POINT_ORDER",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "POINT_ORDER",
        "sl_step" => "스텝1"
      ]
    );
    json_return("00", "처리완료");
  }

  function point_promotion()
  {
    # 데이터 추가.
    $pm_code = input_post("pm_code", "");
    $pm_sdate = input_post("pm_sdate", "");
    $pm_addr = input_post("pm_addr", "");
    $pm_edate = date("Y-m-d 23:59:59");
    $rc_idx = input_post("rc_idx", "");
    $pm_duration = input_post("pm_duration", "");
    $pm_usepoint = 0;

    # 데이터 조회
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $promotion_info = [];

    if (substr($pm_code, 0, 1) == "M") {
      $promotion_info = $this->setting_model->getCodeInfo(["cd_grp" => "광고구매", "cd_key" => $pm_code]);
    } else if (substr($pm_code, 0, 1) == "C") {
      $promotion_info = $this->setting_model->getCodeInfo(["cd_grp" => "업체광고구매", "cd_key" => $pm_code]);
    }

    if ($promotion_info == null) {
      json_return("91", "처리할 데이터가 존재하지 않습니다.");
    }

    // if($mem_info["mm_point"] < $promotion_info["cd_val3"]){
    // 	json_return("91","보유하신 포인트가 부족합니다.");
    // }
    //
    // if($pm_code=="M001" || $pm_code=="C001" || $pm_code=="C002"){
    // 	# + 7일 처리
    // 	$pm_sdate = $pm_sdate . " 00:00:00";
    // 	$timestamp = strtotime($pm_sdate . " +6 days");
    // 	$pm_edate = date("Y-m-d 23:59:59", $timestamp);
    // }else if($pm_code=="M002"){
    // 	# + 1일 처리
    // 	$pm_sdate = date("Y-m-d 00:00:00");
    // 	$timestamp = strtotime($pm_sdate . " +1 days");
    // 	$pm_edate = date("Y-m-d 23:59:59", $timestamp);
    // }

    if ($pm_code == "C001") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +13 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +27 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +40 days");
      }
      //if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
      //if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
      //if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
      //if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint =   4000;
      }
      if ($pm_duration == '5') {
        $pm_usepoint =  30000;
      }
      if ($pm_duration == '10') {
        $pm_usepoint =  60000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 120000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 180000;
      }
    } else if ($pm_code == "C002") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +13 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +27 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +40 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint =  1000;
      }
      if ($pm_duration == '5') {
        $pm_usepoint = 10000;
      }
      if ($pm_duration == '10') {
        $pm_usepoint = 20000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 40000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 60000;
      }
    } else if ($pm_code == "C003") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +13 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +27 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +40 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint =   800;
      }
      if ($pm_duration == '5') {
        $pm_usepoint =  5000;
      }
      if ($pm_duration == '10') {
        $pm_usepoint = 10000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 20000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 30000;
      }
    } else if ($pm_code == "C004") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +13 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +27 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +40 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint =  1000;
      }
      if ($pm_duration == '5') {
        $pm_usepoint = 10000;
      }
      if ($pm_duration == '10') {
        $pm_usepoint = 20000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 40000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 60000;
      }
    }
    if ($mem_info["mm_point"] < $pm_usepoint) {
      json_return("91", "보유하신 포인트가 부족합니다.");
    }

    $pm_edate = date("Y-m-d H:i:s", strtotime($pm_edate) - 60);

    //같은 기간에 같은 상품 이미 있으면 반려 처리
    // 003 점프업은 여러번 구매가능
    if ($promotion_info["cd_key"] == "C001") {
      //추천공고등록
      $query  = "select count(*) as cnt from st_promotion ";
      $query .= "where mm_idx = '" . $this->mm_idx . "' ";
      $query .= "and   pm_code = '" . $promotion_info["cd_key"] . "' and pm_status = 'Y' ";
      $query .= "and   pm_addr = '" . $pm_addr . "' ";
      $query .= "and   rc_idx = '" . $rc_idx . "' ";
      $query .= "and   ( ('" . $pm_sdate . "' between pm_sdate and pm_edate) or ('" . $pm_edate . "' between pm_sdate and pm_edate) ) ";

      $result = $this->db->query($query);
      $count = $result->row_array();
      $cnt = 0;
      if ($count != null) {
        $cnt = $count["cnt"];
      }
      if ($cnt > 0) {
        json_return("91", "이미 구매하신 광고 일정이 중복됩니다. 일정을 수정해주세요");
      }
    }
    if ($promotion_info["cd_key"] == "C004") {
      //지금뜨는 업체
      $query  = "select count(*) as cnt from st_promotion ";
      $query .= "where mm_idx = '" . $this->mm_idx . "' ";
      $query .= "and   pm_code = 'C004' and pm_status = 'Y' ";
      $query .= "and   ( ('" . $pm_sdate . "' between pm_sdate and pm_edate) or ('" . $pm_edate . "' between pm_sdate and pm_edate) ) ";

      $result = $this->db->query($query);
      $count = $result->row_array();
      $cnt = 0;
      if ($count != null) {
        $cnt = $count["cnt"];
      }
      if ($cnt > 0) {
        json_return("91", "이미 구매하신 광고 일정이 중복됩니다. 일정을 수정해주세요");
      }
    }


    # 트랙젝션 시작
    $this->db->trans_begin();

    # 프로모션 데이터 등록
    $promotion = [
      "mm_idx" => $this->mm_idx,
      "rc_idx" => $rc_idx,
      "pm_code" => $promotion_info["cd_key"],
      "pm_name" => $promotion_info["cd_val"] . "(" . $pm_duration . "일)",
      "pm_point" => $pm_usepoint,
      "pm_sdate" => $pm_sdate,
      "pm_edate" => $pm_edate,
      "pm_addr" => $pm_addr
    ];
    $result_promotion = $this->site_model->insertPromotionInfo($promotion);

    if ($result_promotion["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    # 포인트 취소
    $last_point = $mem_info["mm_point"] - $pm_usepoint; //$promotion_info["cd_val3"];
    $insert = [
      "mm_idx" => $this->mm_idx,
      "o_num" => "",
      "pm_idx" => $result_promotion["key"],
      "pt_type" => "PROMOTION",
      "pl_type" => "minus",
      "pl_point" => $pm_usepoint,
      "pl_last_point" => $last_point,
      "pl_desc" => "광고구매(" . $promotion_info["cd_val"] . ")",
      "reg_id" => "system",
    ];
    $result_point = $this->site_model->insertPointList($insert);

    if ($result_point["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    $result_member = $this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $this->mm_idx]);

    if ($result_member["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    # 트랙젝션 완료
    $this->db->trans_commit();
    json_return("00", "처리완료");
  }

  function point_promotion_cancel()
  {
    # 데이터 추가.
    $pm_idx = input_post("pm_idx", "");

    $promotion_info = $this->site_model->getPromotionInfo(["pm_idx" => $pm_idx]);
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

    if ($promotion_info == null) {
      json_return("91", "처리할 데이터가 존재하지 않습니다.");
    }

    # 트랙젝션 시작
    $this->db->trans_begin();

    # 프로모션 데이터 등록
    $result_promotion = $this->site_model->updatePromotionInfo(["pm_status" => "N"], ["pm_idx" => $pm_idx]);

    if ($result_promotion["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 처리시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    # 포인트 취소
    $last_point = $mem_info["mm_point"] + $promotion_info["pm_point"];
    $insert = [
      "mm_idx" => $this->mm_idx,
      "o_num" => "",
      "pm_idx" => $promotion_info["pm_idx"],
      "pt_type" => "PROMOTION",
      "pl_type" => "plus",
      "pl_point" => $promotion_info["pm_point"],
      "pl_last_point" => $last_point,
      "pl_desc" => "광고취소(" . $promotion_info["pm_name"] . ")",
      "reg_id" => "system",
    ];
    $result_point = $this->site_model->insertPointList($insert);

    if ($result_point["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    $result_member = $this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $this->mm_idx]);

    if ($result_member["code"] != "00") {
      $this->db->trans_rollback();
      json_return("91", "데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
    }

    # 트랙젝션 완료
    $this->db->trans_commit();
    json_return("00", "처리완료");
  }

  # 이력서 등록시작 : 로그 제거
  function recruit_start()
  {
    # 로그 삭제
    $this->site_model->deleteSiteLog([
      "mm_idx" => $this->mm_idx,
      "sl_type" => "RECRUIT"
    ]);

    $comAuthStat = "";
    $passAuthStat = "";
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    if ($mem_info == null || ($mem_info["mm_comp_num"] == "")) {
      $comAuthStat = "N";
    } else {
      $comAuthStat = "Y";
    }

    // SNS 로그인 여부 확인
    $is_sns_login = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
    // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
    $is_authenticated = ($mem_info != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login));

    if (!$is_authenticated) {
      $passAuthStat = "N";
    } else {
      $passAuthStat = "Y";
    }

    //if($comAuthStat == "N" || $passAuthStat == "N"){
    //  json_return("02","인증필요",[]);
    //}

    //오늘 등록 공고 수 확인
    $count = $this->member_model->getRecruitTodayCnt($this->mm_idx);

    if ($count >= 5) {
      $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U010"]);
      $use_point = $point_info["cd_val3"];

      json_return("01", "처리완료", ["use_point" => $use_point, "comAuthStat" => $comAuthStat, "passAuthStat" => $passAuthStat]);
    } else {
      json_return("00", "처리완료", ["comAuthStat" => $comAuthStat, "passAuthStat" => $passAuthStat]);
    }
  }

  # 포인트 확인
  function recruit_start_point()
  {
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U010"]);
    $use_point = $point_info["cd_val3"];

    if ($mem_info["mm_point"] < $use_point) {
      json_return("01", "포인트부족", ["mm_point" => $mem_info["mm_point"]]);
    } else {
      json_return("00", "처리완료");
    }
  }

  # 웹등록
  function recruit_write_new()
  {
    // $rc_show_yn = input_post("rc_show_yn", "");
    $rc_name = input_post("rc_name", "");
    $rc_date = input_post("rc_date", "");
    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_detail = input_post("ad_detail", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");
    $rc_work = input_post("rc_work", "");
    $rc_sex = input_post("rc_sex", "");
    $rc_nation = input_post("rc_nation", "");
    $rc_sage = input_post("rc_sage", "");
    $rc_eage = input_post("rc_eage", "");
    $rc_work_type = input_post("rc_work_type", "");
    $rc_am_stime = input_post("rc_am_stime", "");
    $rc_am_etime = input_post("rc_am_etime", "");
    $rc_pm_stime = input_post("rc_pm_stime", "");
    $rc_pm_etime = input_post("rc_pm_etime", "");
    $rc_work_dt = input_post("rc_work_dt", "");
    $rc_price_type = input_post("rc_price_type", "");
    $rc_price_rep = input_post("rc_price_rep", "");
    $rc_price_pay = input_post("rc_price_pay", "");

    // 시급전체 적용전까지 임시
    $rc_day_price = input_post("rc_day_price", "");
    $rc_week_price = input_post("rc_week_price", "");
    $rc_month_price = input_post("rc_month_price", "");

    $rc_week = input_post("rc_week", []);
    $rc_week_yn = input_post("rc_week_yn", "");
    if ($rc_week === "" || $rc_week === null) {
      $rc_week = [];
    }
    if (!is_array($rc_week)) {
      $rc_week = explode(",", $rc_week);
    }
    $rc_week = array_values(array_unique(array_filter(array_map('trim', $rc_week), function ($val) {
      return $val !== "" && $val !== null;
    })));
    if ($rc_week_yn !== "Y" && count($rc_week) === 0) {
      // 간편등록 사용자가 수정을 하지 않아도 기본 근무요일(월~금)을 저장
      $rc_week = ["월", "화", "수", "목", "금"];
    }
    $rc_life_type = input_post("rc_life_type", "");
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = $this->cleanSmartEditorDesc(input_post("rc_desc", ""));

    $rc_img1_ori = input_post("rc_img1_ori", "");
    $rc_img2_ori = input_post("rc_img2_ori", "");
    $rc_img3_ori = input_post("rc_img3_ori", "");
    $rc_img4_ori = input_post("rc_img4_ori", "");
    $rc_img5_ori = input_post("rc_img5_ori", "");
    $mm_img1_ori = input_post("mm_img1_ori", "");


    if ($rc_name == "") {
      json_return("99", "공고제목이 확인되지 않습니다.");
    }

    if ($rc_date == "") {
      json_return("99", "채용공고기간이 확인되지 않습니다.");
    }

    # 데이터 검증
    if ($ad_gps_x == "" || $ad_gps_y == "") {
      json_return("99", "GPS정보가 확인되지 않습니다.");
    }

    if ($ad_addr == "") {
      json_return("99", "선택하신 주소가 확인되지 않습니다.");
    }

    if ($rc_work == "") {
      json_return("99", "업무구분 선택이 확인되지 않습니다.");
    }

    if ($rc_work_type == "") {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    if ($rc_price_type == "") {
      json_return("99", "급여 선택이 확인되지 않습니다.");
    }


    #로그 생성
    $sl_txt = [
      // "rc_show_yn" => $rc_show_yn,
      "rc_name" => $rc_name,
      "rc_date" => $rc_date,
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_detail" => $ad_detail,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1"
      ]
    );

    #로그 생성
    $sl_txt = [
      "rc_work" => $rc_work,
      "rc_sex" => $rc_sex,
      "rc_nation" => $rc_nation,
      "rc_sage" => $rc_sage,
      "rc_eage" => $rc_eage
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2"
      ]
    );

    #로그 생성
    $sl_txt = [
      "rc_work_type" => $rc_work_type,
      "rc_week_yn" => $rc_week_yn,
      "rc_am_stime" => $rc_am_stime,
      "rc_am_etime" => $rc_am_etime,
      "rc_pm_stime" => $rc_pm_stime,
      "rc_pm_etime" => $rc_pm_etime,
      "rc_work_dt" => $rc_work_dt,
      "rc_price_type" => $rc_price_type,
      "rc_price_rep" => $rc_price_rep,
      "rc_price_pay" => $rc_price_pay,
      "rc_day_price" => $rc_day_price,
      "rc_week_price" => $rc_week_price,
      "rc_month_price" => $rc_month_price,
      "rc_week" => $rc_week
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3"
      ]
    );

    #로그 생성
    $sl_txt = [
      "rc_life_type" => $rc_life_type
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4"
      ]
    );

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝5"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($rc_img1_ori != $json_info["rc_img1"]) {
          file_remove($json_info["rc_img1"]);
        }
        if ($rc_img2_ori != $json_info["rc_img2"]) {
          file_remove($json_info["rc_img2"]);
        }
        if ($rc_img3_ori != $json_info["rc_img3"]) {
          file_remove($json_info["rc_img3"]);
        }
        if ($rc_img4_ori != $json_info["rc_img4"]) {
          file_remove($json_info["rc_img4"]);
        }
        if ($rc_img5_ori != $json_info["rc_img5"]) {
          file_remove($json_info["rc_img5"]);
        }

        if ($mm_img1_ori != $json_info["mm_img1"]) {
          file_remove($json_info["mm_img1"]);
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리.
    $rc_img1_resp = $this->common->img_upload_responsive("member", "rc_img1");
    $rc_img1 = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $rc_img2_resp = $this->common->img_upload_responsive("member", "rc_img2");
    $rc_img2 = isset($rc_img2_resp["URL_LG"]) ? $rc_img2_resp["URL_LG"] : "";

    $rc_img3_resp = $this->common->img_upload_responsive("member", "rc_img3");
    $rc_img3 = isset($rc_img3_resp["URL_LG"]) ? $rc_img3_resp["URL_LG"] : "";

    $rc_img4_resp = $this->common->img_upload_responsive("member", "rc_img4");
    $rc_img4 = isset($rc_img4_resp["URL_LG"]) ? $rc_img4_resp["URL_LG"] : "";

    $rc_img5_resp = $this->common->img_upload_responsive("member", "rc_img5");
    $rc_img5 = isset($rc_img5_resp["URL_LG"]) ? $rc_img5_resp["URL_LG"] : "";

    $rc_thumb = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $mm_img1 = $this->common->img_upload_url("member", "mm_img1");

    #로그 생성
    $sl_txt = [
      "rc_desc" => $rc_desc,
      "rc_hash" => $rc_hash,
      "rc_img1" => $rc_img1,
      "rc_img2" => $rc_img2,
      "rc_img3" => $rc_img3,
      "rc_img4" => $rc_img4,
      "rc_img5" => $rc_img5,
      "rc_thumb" => $rc_thumb,
      "mm_img1" => $mm_img1,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5"
      ]
    );


    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }


    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    $step5 = $this->site_model->getStepData($list, "스텝5");
    $work_img = $this->common->getSessionDataList("WORK_TYPE");

    $arr_recruit = [
      // "rc_show_yn" => $step1["rc_show_yn"],
      "rc_name" => $step1["rc_name"],
      "rc_sdate" => $step1["rc_sdate"],
      "rc_edate" => $step1["rc_edate"],
      "rc_nation" => $step2["rc_nation"],
      "rc_sex" => $step2["rc_sex"],
      "rc_sage" => $step2["rc_sage"],
      "rc_eage" => $step2["rc_eage"],
      "rc_work" => $step2["rc_work"],
      "rc_work_img" => getWorkingImg(arr_implode(",", $step2["rc_work"])),
      "rc_work_type" => $step3["rc_work_type"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
      "rc_week" => $step3["rc_week"],
      "rc_am_stime" => $step3["rc_am_stime"],
      "rc_am_etime" => $step3["rc_am_etime"],
      "rc_pm_stime" => $step3["rc_pm_stime"],
      "rc_pm_etime" => $step3["rc_pm_etime"],
      "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
      "rc_work_dt_yoil" => get_yoil($step3["rc_work_dt"]),
      "rc_price_type" => $step3["rc_price_type"],
      "rc_price_rep" => $step3["rc_price_rep"],
      "rc_price_pay" => $step3["rc_price_pay"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_price" => $step3["rc_week_price"],
      "rc_month_price" => $step3["rc_month_price"],
      "rc_life_type" => $step4["rc_life_type"],
      "rc_life_type_tag" => getLifeType(arr_implode(",", $step4["rc_life_type"])),
      "rc_desc" => $step5["rc_desc"],
      "rc_hash" => $step5["rc_hash"],
      "rc_desc" => $step5["rc_desc"],
      "rc_img1" => $step5["rc_img1"],
      "rc_img2" => $step5["rc_img2"],
      "rc_img3" => $step5["rc_img3"],
      "rc_img4" => $step5["rc_img4"],
      "rc_img5" => $step5["rc_img5"],
      "mm_img" => $step5["mm_img"],
    ];

    json_return("00", "처리완료", $arr_recruit);
  }

  /* 사용하지 않는 함수 - recruit_write1_1로 대체됨
  # 스텝1(검토용)
  function recruit_write1_new()
  {
    # 데이터 추가.
    $rc_show_yn = input_post("rc_show_yn", "Y");
    $rc_name = input_post("rc_name", "");
    $rc_date = input_post("rc_date", "");

    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_detail = input_post("ad_detail", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");

    if ($rc_name == "") {
      json_return("99", "공고제목이 확인되지 않습니다.");
    }

    if ($rc_date == "") {
      json_return("99", "채용공고기간이 확인되지 않습니다.");
    }

    # 데이터 검증
    if ($ad_gps_x == "" || $ad_gps_y == "") {
      json_return("99", "GPS정보가 확인되지 않습니다.");
    }

    if ($ad_addr == "") {
      json_return("99", "선택하신 주소가 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_show_yn" => $rc_show_yn,
      "rc_name" => $rc_name,
      "rc_date" => $rc_date,
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_detail" => $ad_detail,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1"
      ]
    );

    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write2_1로 대체됨
  # 스텝2(검토용)
  function recruit_write2_new()
  {
    # 데이터 추가.
    $rc_work = input_post("rc_work", "");
    $rc_sex = input_post("rc_sex", "");
    $rc_nation = input_post("rc_nation", "");
    $rc_sage = input_post("rc_sage", "");
    $rc_eage = input_post("rc_eage", "");

    if ($rc_work == "") {
      json_return("99", "업무구분 선택이 확인되지 않습니다.");
    }

    if ($rc_sex == "") {
      json_return("99", "희망성별 선택이 확인되지 않습니다.");
    }

    if ($rc_nation == "") {
      json_return("99", "희망국적 선택이 확인되지 않습니다.");
    }


    #로그 생성
    $sl_txt = [
      "rc_work" => $rc_work,
      "rc_sex" => $rc_sex,
      "rc_nation" => $rc_nation,
      "rc_sage" => $rc_sage,
      "rc_eage" => $rc_eage
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write3_1로 대체됨
  # 스텝3(검토용)
  function recruit_write3_new()
  {
    # 데이터 추가.
    $rc_work_type = input_post("rc_work_type", "");
    $rc_am_stime = input_post("rc_am_stime", "");
    $rc_am_etime = input_post("rc_am_etime", "");
    $rc_pm_stime = input_post("rc_pm_stime", "");
    $rc_pm_etime = input_post("rc_pm_etime", "");
    $rc_work_dt = input_post("rc_work_dt", "");
    $rc_price_type = input_post("rc_price_type", "");
    $rc_price_rep = input_post("rc_price_rep", "");
    $rc_price_pay = input_post("rc_price_pay", "");
    $rc_day_price = input_post("rc_day_price", "");
    $rc_week_price = input_post("rc_week_price", "");
    $rc_month_price = input_post("rc_month_price", "");
    $rc_week = input_post("rc_week", []);
    $rc_week_yn = input_post("rc_week_yn", "");
    if ($rc_week === "" || $rc_week === null) {
      $rc_week = [];
    }
    if (!is_array($rc_week)) {
      $rc_week = explode(",", $rc_week);
    }
    $rc_week = array_values(array_unique(array_filter(array_map('trim', $rc_week), function ($val) {
      return $val !== "" && $val !== null;
    })));
    if ($rc_week_yn !== "Y" && count($rc_week) === 0) {
      // 간편등록 사용자 기본 근무요일(월~금) 자동 저장
      $rc_week = ["월", "화", "수", "목", "금"];
    }

    if ($rc_work_type == "") {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    if ($rc_work_dt == "") {
      json_return("99", "근무시작일 선택이 확인되지 않습니다.");
    }

    if ($rc_price_type == "") {
      json_return("99", "급여 선택이 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_work_type" => $rc_work_type,
      "rc_week_yn" => $rc_week_yn,
      "rc_am_stime" => $rc_am_stime,
      "rc_am_etime" => $rc_am_etime,
      "rc_pm_stime" => $rc_pm_stime,
      "rc_pm_etime" => $rc_pm_etime,
      "rc_work_dt" => $rc_work_dt,
      "rc_price_type" => $rc_price_type,
      "rc_price_rep" => $rc_price_rep,
      "rc_price_pay" => $rc_price_pay,
      "rc_day_price" => $rc_day_price,
      "rc_week_price" => $rc_week_price,
      "rc_month_price" => $rc_month_price,
      "rc_week" => $rc_week
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write2_1로 대체됨
  # 스텝4(검토용)
  function recruit_write4_new()
  {
    # 데이터 추가.
    $rc_life_type = input_post("rc_life_type", "");

    #로그 생성
    $sl_txt = [
      "rc_life_type" => $rc_life_type
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write3_1로 대체됨
  # 스텝5(검토용)
  function recruit_write5_new()
  {
    # 데이터 추가.
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = $this->cleanSmartEditorDesc(input_post("rc_desc", ""));

    $rc_img1_ori = input_post("rc_img1_ori", "");
    $rc_img2_ori = input_post("rc_img2_ori", "");
    $rc_img3_ori = input_post("rc_img3_ori", "");
    $rc_img4_ori = input_post("rc_img4_ori", "");
    $rc_img5_ori = input_post("rc_img5_ori", "");


    $mm_img1_ori = input_post("mm_img1_ori", "");

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝5"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($rc_img1_ori != $json_info["rc_img1"]) {
          file_remove($json_info["rc_img1"]);
        }
        if ($rc_img2_ori != $json_info["rc_img2"]) {
          file_remove($json_info["rc_img2"]);
        }
        if ($rc_img3_ori != $json_info["rc_img3"]) {
          file_remove($json_info["rc_img3"]);
        }
        if ($rc_img4_ori != $json_info["rc_img4"]) {
          file_remove($json_info["rc_img4"]);
        }
        if ($rc_img5_ori != $json_info["rc_img5"]) {
          file_remove($json_info["rc_img5"]);
        }
        if ($mm_img1_ori != $json_info["mm_img1"]) {
          file_remove($json_info["mm_img1"]);
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리.
    $rc_img1_resp = $this->common->img_upload_responsive("member", "rc_img1");
    $rc_img1 = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $rc_img2_resp = $this->common->img_upload_responsive("member", "rc_img2");
    $rc_img2 = isset($rc_img2_resp["URL_LG"]) ? $rc_img2_resp["URL_LG"] : "";

    $rc_img3_resp = $this->common->img_upload_responsive("member", "rc_img3");
    $rc_img3 = isset($rc_img3_resp["URL_LG"]) ? $rc_img3_resp["URL_LG"] : "";

    $rc_img4_resp = $this->common->img_upload_responsive("member", "rc_img4");
    $rc_img4 = isset($rc_img4_resp["URL_LG"]) ? $rc_img4_resp["URL_LG"] : "";

    $rc_img5_resp = $this->common->img_upload_responsive("member", "rc_img5");
    $rc_img5 = isset($rc_img5_resp["URL_LG"]) ? $rc_img5_resp["URL_LG"] : "";

    $rc_thumb = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $mm_img1 = $this->common->img_upload_url("member", "mm_img1");

    #로그 생성
    $sl_txt = [
      "rc_desc" => $rc_desc,
      "rc_hash" => $rc_hash,
      "rc_img1" => $rc_img1,
      "rc_img2" => $rc_img2,
      "rc_img3" => $rc_img3,
      "rc_img4" => $rc_img4,
      "rc_img5" => $rc_img5,
      "rc_thumb" => $rc_thumb,
      "mm_img1" => $mm_img1,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5"
      ]
    );

    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }

    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    $step5 = $this->site_model->getStepData($list, "스텝5");
    $work_img = $this->common->getSessionDataList("WORK_TYPE");

    $arr_recruit = [
      "rc_show_yn" => $step1["rc_show_yn"],
      "rc_name" => $step1["rc_name"],
      "rc_sdate" => $step1["rc_sdate"],
      "rc_edate" => $step1["rc_edate"],
      "rc_nation" => $step2["rc_nation"],
      "rc_sex" => $step2["rc_sex"],
      "rc_sage" => $step2["rc_sage"],
      "rc_eage" => $step2["rc_eage"],
      "rc_work" => $step2["rc_work"],
      "rc_work_img" => getWorkingImg(arr_implode(",", $step2["rc_work"])),
      "rc_work_type" => $step3["rc_work_type"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
      "rc_week" => $step3["rc_week"],
      "rc_am_stime" => $step3["rc_am_stime"],
      "rc_am_etime" => $step3["rc_am_etime"],
      "rc_pm_stime" => $step3["rc_pm_stime"],
      "rc_pm_etime" => $step3["rc_pm_etime"],
      "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
      "rc_work_dt_yoil" => get_yoil($step3["rc_work_dt"]),
      "rc_price_type" => $step3["rc_price_type"],
      "rc_price_rep" => $step3["rc_price_rep"],
      "rc_price_pay" => $step3["rc_price_pay"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_price" => $step3["rc_week_price"],
      "rc_month_price" => $step3["rc_month_price"],
      "rc_life_type" => $step4["rc_life_type"],
      "rc_life_type_tag" => getLifeType(arr_implode(",", $step4["rc_life_type"])),
      "rc_desc" => $step5["rc_desc"],
      "rc_hash" => $step5["rc_hash"],
      "rc_desc" => $step5["rc_desc"],
      "rc_img1" => $step5["rc_img1"],
      "rc_img2" => $step5["rc_img2"],
      "rc_img3" => $step5["rc_img3"],
      "rc_img4" => $step5["rc_img4"],
      "rc_img5" => $step5["rc_img5"],
      "mm_img" => $step5["mm_img"],
    ];

    json_return("00", "처리완료", $arr_recruit);
  }
  */

  /* 사용하지 않는 함수 - recruit_complete로 대체됨
  # 이력서 등록 완료
  function recruit_complete_new()
  {
    $rc_idx = input_post("rc_idx", 0);
    $mm_comp_num = input_post("mm_comp_num", 0);
    $mm_comp_num_org = input_post("mm_comp_num_org", 0);

    $recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
    if ($rc_idx != 0 && $recruit_info == null) {
      json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
    }

    if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
      json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
    }

    // 라이브 업데이트시 삭제
    $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]);
    json_return("91", "등록 테스트 종료");


    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    $step5 = $this->site_model->getStepData($list, "스텝5");

    $rc_sdate = "";
    $rc_edate = "";
    $arr_date = explode("~", $step1["rc_date"]);
    $rc_sdate = trim($arr_date[0]) . " 00:00:00";

    if ($arr_date[1] == null || $arr_date[1] == "") {
      $rc_edate = trim($arr_date[0]) . " 23:59:59";
    } else {
      $rc_edate = trim($arr_date[1]) . " 23:59:59";
    }

    if ($rc_idx != 0) {

      # 트랜젝션 시작
      $this->db->trans_begin();

      // rc_price_type에 메인과 서브를 콤마로 구분하여 저장
      $rc_price_type_main = isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "";
      $rc_price_possible = isset($step1["rc_price_possible"]) ? $step1["rc_price_possible"] : "";
      $rc_price_type_value = "";
      
      if ($rc_price_type_main == "급여협의") {
        $rc_price_type_value = "급여협의";
      } else if ($rc_price_possible != "") {
        // 서브 급여형태가 있는 경우: "주급,일급" 형식으로 저장
        $sub_type = str_replace("가능", "", $rc_price_possible); // "일급가능" -> "일급"
        $rc_price_type_value = $rc_price_type_main . "," . $sub_type;
      } else {
        // 서브 급여형태가 없는 경우: 메인만 저장
        $rc_price_type_value = $rc_price_type_main;
      }

      $arr_recruit = [
        "rc_show_yn" => isset($step1["rc_show_yn"]) ? $step1["rc_show_yn"] : "Y",
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_work" => isset($step1["rc_work"]) && is_array($step1["rc_work"]) ? arr_implode(",", $step1["rc_work"]) : "",
        "rc_work_type" => isset($step1["rc_work_type"]) && is_array($step1["rc_work_type"]) ? arr_implode(",", $step1["rc_work_type"]) : "",
        // "rc_work_negotiation" => isset($step1["rc_work_negotiation"]) ? $step1["rc_work_negotiation"] : "N", // DB 컬럼 없음
        // "rc_price_type_main" => isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "", // DB 컬럼 없음 (rc_price_type에 저장됨)
        "rc_price_type" => $rc_price_type_value,
        // "rc_price_amount" => isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0, // DB 컬럼 없음 (rc_price_rep, rc_price_pay에 저장됨)
        "rc_price_rep" => isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "",
        "rc_price_pay" => isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        // "rc_price_daily" => isset($step1["rc_price_daily"]) ? $step1["rc_price_daily"] : "N", // DB 컬럼 없음 (rc_day_price에 저장됨)
        // "rc_price_weekly" => isset($step1["rc_price_weekly"]) ? $step1["rc_price_weekly"] : "N", // DB 컬럼 없음 (rc_week_price에 저장됨)
        // "rc_price_monthly" => isset($step1["rc_price_monthly"]) ? $step1["rc_price_monthly"] : "N", // DB 컬럼 없음 (rc_month_price에 저장됨)
        "rc_day_price" => isset($step1["rc_price_daily"]) && $step1["rc_price_daily"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_week_price" => isset($step1["rc_price_weekly"]) && $step1["rc_price_weekly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_month_price" => isset($step1["rc_price_monthly"]) && $step1["rc_price_monthly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_life_type" => isset($step2["rc_life_type"]) && is_array($step2["rc_life_type"]) ? arr_implode(",", $step2["rc_life_type"]) : "",
        "rc_nation" => isset($step3["rc_nation"]) && is_array($step3["rc_nation"]) ? arr_implode(",", $step3["rc_nation"]) : "",
        // "rc_nation_all" => isset($step3["rc_nation_all"]) ? $step3["rc_nation_all"] : "N", // DB 컬럼 없음 (rc_nation 값으로 판단)
        "rc_sex" => isset($step3["rc_sex"]) && is_array($step3["rc_sex"]) ? arr_implode(",", $step3["rc_sex"]) : "",
        // "rc_sex_all" => isset($step3["rc_sex_all"]) ? $step3["rc_sex_all"] : "N", // DB 컬럼 없음 (rc_sex 값으로 판단)
        "rc_sage" => isset($step3["rc_sage"]) ? $step3["rc_sage"] : "",
        "rc_eage" => isset($step3["rc_eage"]) ? $step3["rc_eage"] : "",
        // "rc_age_all" => isset($step3["rc_age_all"]) ? $step3["rc_age_all"] : "N", // DB 컬럼 없음 (rc_sage, rc_eage 값으로 판단)
        "rc_desc" => isset($step3["rc_desc"]) ? $step3["rc_desc"] : "",
        "rc_hash" => isset($step3["rc_hash"]) ? $step3["rc_hash"] : "",
        "rc_img1" => isset($step3["rc_img1"]) ? $step3["rc_img1"] : "",
        "rc_img2" => isset($step3["rc_img2"]) ? $step3["rc_img2"] : "",
        "rc_img3" => isset($step3["rc_img3"]) ? $step3["rc_img3"] : "",
        "rc_img4" => isset($step3["rc_img4"]) ? $step3["rc_img4"] : "",
        "rc_img5" => "",
        "rc_detail_img1" => isset($step3["rc_detail_img1"]) ? $step3["rc_detail_img1"] : "",
        "rc_detail_img2" => isset($step3["rc_detail_img2"]) ? $step3["rc_detail_img2"] : "",
        "rc_detail_img3" => isset($step3["rc_detail_img3"]) ? $step3["rc_detail_img3"] : "",
        "rc_thumb" => isset($step3["rc_thumb"]) ? $step3["rc_thumb"] : "",
      ];

      $result_recruit = $this->recruit_model->updateRecruit($arr_recruit, ["rc_idx" => $rc_idx]);

      if ($result_recruit["code"] != "00") {
        $this->db->trans_rollback();
        json_return("91", "[01]채용공고 수정시 오류가 발생하였습니다.");
      }

      $arr_addr = [
        "mm_idx" => $this->mm_idx,
        "ad_type" => "RECRUIT",
        "ad_key" => $rc_idx,
        "ad_zipcode" => isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "",
        "ad_jibun" => isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "",
        "ad_doro" => isset($step1["ad_doro"]) ? $step1["ad_doro"] : "",
        "ad_detail" => isset($step1["ad_detail"]) ? $step1["ad_detail"] : "",
        "ad_addr" => isset($step1["ad_addr"]) ? $step1["ad_addr"] : "",
        "ad_gps_x" => isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "",
        "ad_gps_y" => isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "",
      ];

      $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx]);
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 수정시 오류가 발생하였습니다.");
      }


      # 로그 삭제
      $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]);

      if ($mm_comp_num != $mm_comp_num_org && $mm_comp_num != "N") {
        //json_return("94","[error1][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");
        $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);

        //사업자번호 인증시 "지금뜨는 업체 8일권" 부여
        $promotion = [
          "mm_idx" => $this->mm_idx,
          "rc_idx" => $rc_idx,
          "pm_code" => "C002", //$promotion_info["cd_key"],
          "pm_name" => "지금뜨는 업체(7일권)", //$promotion_info["cd_val"],
          "pm_point" => "0", //$promotion_info["cd_val3"],
          "pm_sdate" => date("Y-m-d") . " 00:00:00", //$pm_sdate,
          "pm_edate" => date("Y-m-d", strtotime("+7 days")) . " 23:59:59", //$pm_edate,
          "pm_addr" => "" //$pm_addr
        ];
        $result_promotion = $this->site_model->insertPromotionInfo($promotion);
      }
      //json_return("95","[error2][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");

      $this->db->trans_commit();
      json_return("00", "수정 성공", ["alarm_list" => null, "rc_idx" => $rc_idx]);
    } else {
      # 신규등록 : 포인트 여부 확인 : 포인트 차감대상
      $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U010"]);
      $use_point = $point_info["cd_val3"];
      $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
      $count = $this->recruit_model->getRecruitTodayCnt($this->mm_idx);

      if ($count >= 5) {
        if ($mem_info["mm_point"] < $use_point) {
          json_return("93", "보유하신 포인트(" . number_format($mem_info["mm_point"]) . "P)가 부족하여 채용공고 등록이 불가능합니다.");
        }
      }

      # 트랜젝션 시작
      $this->db->trans_begin();

      // rc_price_type에 메인과 서브를 콤마로 구분하여 저장
      $rc_price_type_main = isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "";
      $rc_price_possible = isset($step1["rc_price_possible"]) ? $step1["rc_price_possible"] : "";
      $rc_price_type_value = "";
      
      if ($rc_price_type_main == "급여협의") {
        $rc_price_type_value = "급여협의";
      } else if ($rc_price_possible != "") {
        // 서브 급여형태가 있는 경우: "주급,일급" 형식으로 저장
        $sub_type = str_replace("가능", "", $rc_price_possible); // "일급가능" -> "일급"
        $rc_price_type_value = $rc_price_type_main . "," . $sub_type;
      } else {
        // 서브 급여형태가 없는 경우: 메인만 저장
        $rc_price_type_value = $rc_price_type_main;
      }

      $arr_recruit = [
        "mm_idx" => $this->mm_idx,
        "rc_show_yn" => ($preview_mode == 1) ? "N" : (isset($step1["rc_show_yn"]) ? $step1["rc_show_yn"] : "Y"), // 미리보기 모드면 임시저장("N"), 아니면 정식등록
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_work" => isset($step1["rc_work"]) && is_array($step1["rc_work"]) ? arr_implode(",", $step1["rc_work"]) : "",
        "rc_work_type" => isset($step1["rc_work_type"]) && is_array($step1["rc_work_type"]) ? arr_implode(",", $step1["rc_work_type"]) : "",
        // "rc_work_negotiation" => isset($step1["rc_work_negotiation"]) ? $step1["rc_work_negotiation"] : "N", // DB 컬럼 없음
        // "rc_price_type_main" => isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "", // DB 컬럼 없음 (rc_price_type에 저장됨)
        "rc_price_type" => $rc_price_type_value,
        // "rc_price_amount" => isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0, // DB 컬럼 없음 (rc_price_rep, rc_price_pay에 저장됨)
        "rc_price_rep" => isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "",
        "rc_price_pay" => isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        // "rc_price_daily" => isset($step1["rc_price_daily"]) ? $step1["rc_price_daily"] : "N", // DB 컬럼 없음 (rc_day_price에 저장됨)
        // "rc_price_weekly" => isset($step1["rc_price_weekly"]) ? $step1["rc_price_weekly"] : "N", // DB 컬럼 없음 (rc_week_price에 저장됨)
        // "rc_price_monthly" => isset($step1["rc_price_monthly"]) ? $step1["rc_price_monthly"] : "N", // DB 컬럼 없음 (rc_month_price에 저장됨)
        "rc_day_price" => isset($step1["rc_price_daily"]) && $step1["rc_price_daily"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_week_price" => isset($step1["rc_price_weekly"]) && $step1["rc_price_weekly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_month_price" => isset($step1["rc_price_monthly"]) && $step1["rc_price_monthly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0,
        "rc_life_type" => isset($step2["rc_life_type"]) && is_array($step2["rc_life_type"]) ? arr_implode(",", $step2["rc_life_type"]) : "",
        "rc_nation" => isset($step3["rc_nation"]) && is_array($step3["rc_nation"]) ? arr_implode(",", $step3["rc_nation"]) : "",
        // "rc_nation_all" => isset($step3["rc_nation_all"]) ? $step3["rc_nation_all"] : "N", // DB 컬럼 없음 (rc_nation 값으로 판단)
        "rc_sex" => isset($step3["rc_sex"]) && is_array($step3["rc_sex"]) ? arr_implode(",", $step3["rc_sex"]) : "",
        // "rc_sex_all" => isset($step3["rc_sex_all"]) ? $step3["rc_sex_all"] : "N", // DB 컬럼 없음 (rc_sex 값으로 판단)
        "rc_sage" => isset($step3["rc_sage"]) ? $step3["rc_sage"] : "",
        "rc_eage" => isset($step3["rc_eage"]) ? $step3["rc_eage"] : "",
        // "rc_age_all" => isset($step3["rc_age_all"]) ? $step3["rc_age_all"] : "N", // DB 컬럼 없음 (rc_sage, rc_eage 값으로 판단)
        "rc_desc" => isset($step3["rc_desc"]) ? $step3["rc_desc"] : "",
        "rc_hash" => isset($step3["rc_hash"]) ? $step3["rc_hash"] : "",
        "rc_img1" => isset($step3["rc_img1"]) ? $step3["rc_img1"] : "",
        "rc_img2" => isset($step3["rc_img2"]) ? $step3["rc_img2"] : "",
        "rc_img3" => isset($step3["rc_img3"]) ? $step3["rc_img3"] : "",
        "rc_img4" => isset($step3["rc_img4"]) ? $step3["rc_img4"] : "",
        "rc_img5" => "",
        "rc_detail_img1" => isset($step3["rc_detail_img1"]) ? $step3["rc_detail_img1"] : "",
        "rc_detail_img2" => isset($step3["rc_detail_img2"]) ? $step3["rc_detail_img2"] : "",
        "rc_detail_img3" => isset($step3["rc_detail_img3"]) ? $step3["rc_detail_img3"] : "",
        "rc_thumb" => isset($step3["rc_thumb"]) ? $step3["rc_thumb"] : "",
        "up_dt" => date("Y-m-d H:i:s")
      ];

      $result_recruit = $this->recruit_model->insertRecruit($arr_recruit);

      if ($result_recruit["code"] != "00") {
        $this->db->trans_rollback();
        json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
      }

      //2024.08.15 최초 등록시 1만 포인트 추가 지급
      //1.이미 지급되었는지 확인 (포인트리스트에서)
      $query_point  = "select count(*) as cnt from st_point_list ";
      $query_point .= "where mm_idx = '" . $this->mm_idx . "' ";
      $query_point .= "and   pl_desc like '%첫 공고 등록%' ";

      $result_point = $this->db->query($query_point);
      $count_point = $result_point->row_array();
      $cnt_point = 0;
      if ($count_point != null) {
        $cnt_point = $count_point["cnt"];
      }
      // $query_recruit  = "select count(*) as cnt from st_recruit ";
      // $query_recruit .= "where mm_idx = '".$this->mm_idx."' ";
      $cnt_recruit = 0;

      //2.포인트 지급처리
      if ($cnt_point == 0 && $cnt_recruit == 0) {
        //2.1. 포인트 지급히스토리 추가
        $pay_point = 10000;
        $insert = [
          "mm_idx" => $this->mm_idx,
          "o_num" => "",
          "pm_idx" => 0,
          "pt_type" => "PROMOTION",
          "pl_type" => "plus",
          "pl_point" => $pay_point,
          "pl_last_point" => $pay_point,
          "pl_desc" => "첫 공고 등록 포인트 지급(10,000P)",
          "reg_id" => "system",
        ];
        $result_point = $this->site_model->insertPointList($insert);

        //2.2. 회원정보에 잔액 업데이트
        $sql_mem = "update st_member set mm_point = mm_point + (" . $pay_point . ") where mm_idx = '" . $this->mm_idx . "' ";
        $query_mem = $this->db->query($sql_mem);
        $affectedRows_mem = $this->db->affected_rows();

        if ($query_mem && $affectedRows_mem == 1) {
          //업데이트완료
        } else {
          $this->db->trans_rollback();
          json_return("01", "잔액변경 오류. 관리자에게 문의하세요.");
        }
      }

      $arr_addr = [
        "mm_idx" => $this->mm_idx,
        "ad_type" => "RECRUIT",
        "ad_key" => $result_recruit["key"],
        "ad_zipcode" => isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "",
        "ad_jibun" => isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "",
        "ad_doro" => isset($step1["ad_doro"]) ? $step1["ad_doro"] : "",
        "ad_detail" => isset($step1["ad_detail"]) ? $step1["ad_detail"] : "",
        "ad_addr" => isset($step1["ad_addr"]) ? $step1["ad_addr"] : "",
        "ad_gps_x" => isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "",
        "ad_gps_y" => isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "",
      ];

      // 기존 주소 확인 (신규 등록이지만 혹시 모를 경우를 대비)
      $existing_addr = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $result_recruit["key"]]);
      if ($existing_addr != null) {
        // 기존 주소가 있으면 UPDATE
        $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $result_recruit["key"]]);
      } else {
        // 기존 주소가 없으면 INSERT
        $result_addr = $this->member_model->insertAddress($arr_addr);
      }
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 등록시 오류가 발생하였습니다.");
      }

      // 공고노출지역 저장

      $expose_list = [
        "1" => [
          "ad_type" => "RECRUIT_EXPOSE_1",
          "addr"    => isset($step1["expose_addr1_addr"])     ? $step1["expose_addr1_addr"]     : "",
          "zipcode" => isset($step1["expose_addr1_zipcode"])  ? $step1["expose_addr1_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr1_jibun"])    ? $step1["expose_addr1_jibun"]    : "",
          "doro"    => isset($step1["expose_addr1_doro_val"]) ? $step1["expose_addr1_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr1_gps_x"])    ? $step1["expose_addr1_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr1_gps_y"])    ? $step1["expose_addr1_gps_y"]    : "",
        ],
        "2" => [
          "ad_type" => "RECRUIT_EXPOSE_2",
          "addr"    => isset($step1["expose_addr2_addr"])     ? $step1["expose_addr2_addr"]     : "",
          "zipcode" => isset($step1["expose_addr2_zipcode"])  ? $step1["expose_addr2_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr2_jibun"])    ? $step1["expose_addr2_jibun"]    : "",
          "doro"    => isset($step1["expose_addr2_doro_val"]) ? $step1["expose_addr2_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr2_gps_x"])    ? $step1["expose_addr2_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr2_gps_y"])    ? $step1["expose_addr2_gps_y"]    : "",
        ],
      ];

      foreach ($expose_list as $seq => $expose) {

        $rc_idx_val = $rc_idx != 0 ? $rc_idx : $result_recruit["key"];


        $where_expose = ["ad_type" => $expose["ad_type"], "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx_val];


        if (!empty($expose["addr"])) {
          $arr_expose = [
            "mm_idx"     => $this->mm_idx,
            "ad_type"    => $expose["ad_type"],
            "ad_key"     => $rc_idx_val,
            "ad_zipcode" => $expose["zipcode"],
            "ad_jibun"   => $expose["jibun"],
            "ad_doro"    => $expose["doro"],
            "ad_addr"    => $expose["addr"],
            "ad_gps_x"   => $expose["gps_x"],
            "ad_gps_y"   => $expose["gps_y"],
          ];


          $existing_expose = $this->member_model->getAddress($where_expose);


          if ($existing_expose != null) {

            $result = $this->member_model->updateAddress($arr_expose, $where_expose);

          } else {

            $result = $this->member_model->insertAddress($arr_expose);

          }
        } else {

          $result = $this->member_model->deleteAddress($where_expose);

        }
      }

      # 포인트 차감
      if ($count >= 5) {
        # 포인트 취소
        $last_point = $mem_info["mm_point"] - $use_point;
        $insert = [
          "mm_idx" => $this->mm_idx,
          "o_num" => "",
          "pm_idx" => $result_recruit["key"],
          "pt_type" => "USE",
          "pl_type" => "minus",
          "pl_point" => $use_point,
          "pl_last_point" => $last_point,
          "pl_desc" => "채용공고등록(" . $use_point . "P차감)",
          "reg_id" => "system",
        ];
        $result_point = $this->site_model->insertPointList($insert);

        if ($result_point["code"] != "00") {
          $this->db->trans_rollback();
          json_return("93", "[03]채용공고 등록시 오류가 발생하였습니다.");
        }

        $result_member = $this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $this->mm_idx]);
      }


      # 로그 삭제
      $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]);

      #####################################################
      # 푸시 발송
      #####################################################
      $temp_list = $this->site_model->getAlarmTargetRecruit($this->mm_idx);
      $token_list = [];
      foreach ($temp_list as $temp) {
        $yoil = get_yoil(get_today());
        $time = get_today("H:i");

        $skip = false;
        if ($temp["token"] != "" && $temp["alarm_deny_yn"] == "Y" && $temp["alarm_deny_yoil"] == $yoil && ($temp["alarm_deny_stime"] < $time && $temp["alarm_deny_etime"] > $time)) {
          $skip = true;
        }

        if ($skip == false) {
          $token_list[] = $temp["token"];
        }
      }
      //error_log("\n:::::insertRecruit9: ", "3", "/testlog/php_log");

      $info = [
        "title" => "NowJob 채용정보 알림",
        "body" => "즐겨찾기하신 " . $mem_info["mm_comp_name"] . "에서 일자리를 등록 했습니다.",
        "img" => "",
        "url" => "",
      ];
      $fcm_result = sendFcm($info, $token_list);
      #####################################################

      #####################################################
      # 알림 등록
      #####################################################
      //error_log("\n:::::insertRecruit10: ", "3", "/testlog/php_log");

      $tbl_alarm = get_mongodb("alarm");
      foreach ($temp_list as $temp) {

        //추가정보 설정
        $price_time = "";
        if ($arr_recruit["rc_am_stime"] != "") {
          $price_time = $arr_recruit["rc_am_stime"] . "~" . $arr_recruit["rc_am_etime"];
        }

        if ($arr_recruit["rc_pm_stime"] != "") {
          if ($price_time == "") {
            $price_time = $arr_recruit["rc_pm_stime"] . "~" . $arr_recruit["rc_pm_etime"];
          } else {
            $price_time .= " ," . $arr_recruit["rc_pm_stime"] . "~" . $arr_recruit["rc_pm_etime"];
          }
        }

        //알림 추가
        $alarm = [
          "user_id" => $temp["mm_id"],
          "al_type" => "recruit",
          "al_key" => "" . $result_recruit["key"],
          "al_text" => "<strong>즐겨찾기한 <em>" . $mem_info["mm_comp_name"] . "</em>에서 <em>일자리를 등록</em>했습니다.</strong>",
          "al_etc" => "<p class='time'>" . $price_time . "</p><p class='pay'><span>" . $arr_recruit["rc_price_rep"] . "</span><span>" . $arr_recruit["rc_price_pay"] . "</span></p>",
          "al_url" => "",
          "show_yn" => "N",
          "reg_dt" => get_today(),
        ];

        $tbl_alarm->insert($alarm);
      }
      //error_log("\n:::::insertRecruit12: ", "3", "/testlog/php_log");

      #####################################################

      if ($mm_comp_num != $mm_comp_num_org && $mm_comp_num != "N") {
        //json_return("94","[error1][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");
        $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);

        //사업자번호 인증시 "지금뜨는 업체 8일권" 부여
        $promotion = [
          "mm_idx" => $this->mm_idx,
          "rc_idx" => $result_recruit["key"],
          "pm_code" => "C002", //$promotion_info["cd_key"],
          "pm_name" => "지금뜨는 업체(7일권)", //$promotion_info["cd_val"],
          "pm_point" => "0", //$promotion_info["cd_val3"],
          "pm_sdate" => date("Y-m-d") . " 00:00:00", //$pm_sdate,
          "pm_edate" => date("Y-m-d", strtotime("+7 days")) . " 23:59:59", //$pm_edate,
          "pm_addr" => "" //$pm_addr
        ];
        $result_promotion = $this->site_model->insertPromotionInfo($promotion);
      }

      //error_log("\n:::::insertRecruit13: ", "3", "/testlog/php_log");
      $this->db->trans_commit();
      //error_log("\n:::::insertRecruit14: ", "3", "/testlog/php_log");
      //error_log("\n:::::insertRecruit15: ".$mem_info["mm_comp_name"], "3", "/testlog/php_log");
      json_return("00", "등록 성공", ["target_name" => $mem_info["mm_comp_name"]]);
    }
  }
  */

  # 웹등록
  function recruit_write()
  {
    $rc_show_yn = input_post("rc_show_yn", "Y");
    $rc_name = input_post("rc_name", "");
    $rc_date = input_post("rc_date", "");
    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_detail = input_post("ad_detail", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");
    $rc_work = input_post("rc_work", "");
    $rc_sex = input_post("rc_sex", "");
    $rc_nation = input_post("rc_nation", "");
    $rc_sage = input_post("rc_sage", "");
    $rc_eage = input_post("rc_eage", "");
    $rc_work_type = input_post("rc_work_type", "");
    $rc_am_stime = input_post("rc_am_stime", "");
    $rc_am_etime = input_post("rc_am_etime", "");
    $rc_pm_stime = input_post("rc_pm_stime", "");
    $rc_pm_etime = input_post("rc_pm_etime", "");
    $rc_work_dt = input_post("rc_work_dt", "");
    $rc_price_type = input_post("rc_price_type", "");
    $rc_price_rep = input_post("rc_price_rep", "");
    $rc_price_pay = input_post("rc_price_pay", "");

    // 시급전체 적용전까지 임시
    $rc_day_price = input_post("rc_day_price", "");
    $rc_week_price = input_post("rc_week_price", "");
    $rc_month_price = input_post("rc_month_price", "");

    $rc_week = input_post("rc_week", []);
    $rc_week_yn = input_post("rc_week_yn", "");
    if ($rc_week === "" || $rc_week === null) {
      $rc_week = [];
    }
    if (!is_array($rc_week)) {
      $rc_week = explode(",", $rc_week);
    }
    $rc_week = array_values(array_unique(array_filter(array_map('trim', $rc_week), function ($val) {
      return $val !== "" && $val !== null;
    })));
    if ($rc_week_yn !== "Y" && count($rc_week) === 0) {
      // 간편등록 기본값(월~금)을 서버에도 저장
      $rc_week = ["월", "화", "수", "목", "금"];
    }
    $rc_life_type = input_post("rc_life_type", "");
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = $this->cleanSmartEditorDesc(input_post("rc_desc", ""));

    $rc_img1_ori = input_post("rc_img1_ori", "");
    $rc_img2_ori = input_post("rc_img2_ori", "");
    $rc_img3_ori = input_post("rc_img3_ori", "");
    $rc_img4_ori = input_post("rc_img4_ori", "");
    $rc_img5_ori = input_post("rc_img5_ori", "");
    $mm_img1_ori = input_post("mm_img1_ori", "");

    // 상세페이지 사진 필드 읽기 (웹 폼에서 업로드된 슬롯 2-4 사진)
    $rc_detail_img1 = input_post("rc_detail_img1", "");
    $rc_detail_img2 = input_post("rc_detail_img2", "");
    $rc_detail_img3 = input_post("rc_detail_img3", "");
    $rc_detail_img1_ori = input_post("rc_detail_img1_ori", "");
    $rc_detail_img2_ori = input_post("rc_detail_img2_ori", "");
    $rc_detail_img3_ori = input_post("rc_detail_img3_ori", "");

    // 공고노출지역 파라미터
    $expose_addr1_zipcode  = input_post("expose_addr1_zipcode", "");
    $expose_addr1_jibun    = input_post("expose_addr1_jibun", "");
    $expose_addr1_doro_val = input_post("expose_addr1_doro_val", "");
    $expose_addr1_addr     = input_post("expose_addr1_addr", "");
    $expose_addr1_gps_x    = input_post("expose_addr1_gps_x", "");
    $expose_addr1_gps_y    = input_post("expose_addr1_gps_y", "");
    $expose_addr2_zipcode  = input_post("expose_addr2_zipcode", "");
    $expose_addr2_jibun    = input_post("expose_addr2_jibun", "");
    $expose_addr2_doro_val = input_post("expose_addr2_doro_val", "");
    $expose_addr2_addr     = input_post("expose_addr2_addr", "");
    $expose_addr2_gps_x    = input_post("expose_addr2_gps_x", "");
    $expose_addr2_gps_y    = input_post("expose_addr2_gps_y", "");

    if ($rc_name == "") {
      json_return("99", "공고제목이 확인되지 않습니다.");
    }

    if ($rc_date == "") {
      json_return("99", "채용공고기간이 확인되지 않습니다.");
    }

    # 데이터 검증
    if ($ad_gps_x == "" || $ad_gps_y == "") {
      json_return("99", "GPS정보가 확인되지 않습니다.");
    }

    if ($ad_addr == "") {
      json_return("99", "선택하신 주소가 확인되지 않습니다.");
    }

    if ($rc_work == "") {
      json_return("99", "업무구분 선택이 확인되지 않습니다.");
    }

    if ($rc_work_type == "") {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    if ($rc_price_type == "") {
      json_return("99", "급여 선택이 확인되지 않습니다.");
    }


    #로그 생성
    $sl_txt = [
      "rc_show_yn" => $rc_show_yn,
      "rc_name" => $rc_name,
      "rc_date" => $rc_date,
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_detail" => $ad_detail,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y,
      "expose_addr1_zipcode" => $expose_addr1_zipcode,
      "expose_addr1_jibun" => $expose_addr1_jibun,
      "expose_addr1_doro_val" => $expose_addr1_doro_val,
      "expose_addr1_addr" => $expose_addr1_addr,
      "expose_addr1_gps_x" => $expose_addr1_gps_x,
      "expose_addr1_gps_y" => $expose_addr1_gps_y,
      "expose_addr2_zipcode" => $expose_addr2_zipcode,
      "expose_addr2_jibun" => $expose_addr2_jibun,
      "expose_addr2_doro_val" => $expose_addr2_doro_val,
      "expose_addr2_addr" => $expose_addr2_addr,
      "expose_addr2_gps_x" => $expose_addr2_gps_x,
      "expose_addr2_gps_y" => $expose_addr2_gps_y
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1"
      ]
    );

    #로그 생성
    // rc_life_type 처리: 배열이면 그대로 사용, 아니면 빈 배열로 설정 (간편등록 대응)
    $rc_life_type_array = [];
    if (!empty($rc_life_type)) {
      if (is_array($rc_life_type)) {
        $rc_life_type_array = $rc_life_type;
      } else {
        // 문자열로 전달된 경우 배열로 변환
        $rc_life_type_array = explode(",", $rc_life_type);
        $rc_life_type_array = array_filter(array_map('trim', $rc_life_type_array));
      }
    }

    $sl_txt = [
      "rc_work" => $rc_work,
      "rc_sex" => $rc_sex,
      "rc_nation" => $rc_nation,
      "rc_sage" => $rc_sage,
      "rc_eage" => $rc_eage,
      "rc_life_type" => $rc_life_type_array  // 간편등록 시 빈 배열로 저장
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2"
      ]
    );

    #로그 생성
    // rc_desc, rc_hash, rc_nation, rc_sex, rc_sage, rc_eage는 스텝2에도 저장되지만, 
    // recruit_complete()에서 스텝3에서도 확인하므로 추가
    $sl_txt = [
      "rc_work_type" => $rc_work_type,
      "rc_week_yn" => $rc_week_yn,
      "rc_am_stime" => $rc_am_stime,
      "rc_am_etime" => $rc_am_etime,
      "rc_pm_stime" => $rc_pm_stime,
      "rc_pm_etime" => $rc_pm_etime,
      "rc_work_dt" => $rc_work_dt,
      "rc_price_type" => $rc_price_type,
      "rc_price_rep" => $rc_price_rep,
      "rc_price_pay" => $rc_price_pay,
      "rc_day_price" => $rc_day_price,
      "rc_week_price" => $rc_week_price,
      "rc_month_price" => $rc_month_price,
      "rc_week" => $rc_week,
      "rc_nation" => $rc_nation ? (is_array($rc_nation) ? $rc_nation : explode(",", $rc_nation)) : [],
      "rc_sex" => $rc_sex ? (is_array($rc_sex) ? $rc_sex : explode(",", $rc_sex)) : [],
      "rc_sage" => $rc_sage ? $rc_sage : "",
      "rc_eage" => $rc_eage ? $rc_eage : "",
      "rc_desc" => $rc_desc ? $rc_desc : "",  // 간편등록 시 빈 문자열로 저장
      "rc_hash" => $rc_hash ? $rc_hash : "",  // 간편등록 시 빈 문자열로 저장
      "rc_detail_img1" => $rc_detail_img1,
      "rc_detail_img2" => $rc_detail_img2,
      "rc_detail_img3" => $rc_detail_img3
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3"
      ]
    );

    #로그 생성
    $sl_txt = [
      "rc_life_type" => $rc_life_type
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4"
      ]
    );

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝5"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($rc_img1_ori != $json_info["rc_img1"]) {
          file_remove($json_info["rc_img1"]);
        }
        if ($rc_img2_ori != $json_info["rc_img2"]) {
          file_remove($json_info["rc_img2"]);
        }
        if ($rc_img3_ori != $json_info["rc_img3"]) {
          file_remove($json_info["rc_img3"]);
        }
        if ($rc_img4_ori != $json_info["rc_img4"]) {
          file_remove($json_info["rc_img4"]);
        }
        if ($rc_img5_ori != $json_info["rc_img5"]) {
          file_remove($json_info["rc_img5"]);
        }

        if ($mm_img1_ori != $json_info["mm_img1"]) {
          file_remove($json_info["mm_img1"]);
        }
      } catch (Exception $ex) {
      }
    }

    # 상세페이지 사진(슬롯 2-4) 기존 파일 삭제 처리 (_ori 값이 있을 때만 비교)
    $log_info_step3 = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝3"]
    );

    if ($log_info_step3 != null) {
      try {
        $json_info_step3 = json_decode($log_info_step3["sl_txt"], true);
        if ($rc_detail_img1_ori != "" && $rc_detail_img1_ori != (isset($json_info_step3["rc_detail_img1"]) ? $json_info_step3["rc_detail_img1"] : "")) {
          if (isset($json_info_step3["rc_detail_img1"]) && $json_info_step3["rc_detail_img1"] != "") {
            file_remove($json_info_step3["rc_detail_img1"]);
          }
        }
        if ($rc_detail_img2_ori != "" && $rc_detail_img2_ori != (isset($json_info_step3["rc_detail_img2"]) ? $json_info_step3["rc_detail_img2"] : "")) {
          if (isset($json_info_step3["rc_detail_img2"]) && $json_info_step3["rc_detail_img2"] != "") {
            file_remove($json_info_step3["rc_detail_img2"]);
          }
        }
        if ($rc_detail_img3_ori != "" && $rc_detail_img3_ori != (isset($json_info_step3["rc_detail_img3"]) ? $json_info_step3["rc_detail_img3"] : "")) {
          if (isset($json_info_step3["rc_detail_img3"]) && $json_info_step3["rc_detail_img3"] != "") {
            file_remove($json_info_step3["rc_detail_img3"]);
          }
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리 (반응형 이미지: sm/md/lg)
    $rc_img1_resp = $this->common->img_upload_responsive("member", "rc_img1");
    $rc_img1 = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $rc_img2_resp = $this->common->img_upload_responsive("member", "rc_img2");
    $rc_img2 = isset($rc_img2_resp["URL_LG"]) ? $rc_img2_resp["URL_LG"] : "";

    $rc_img3_resp = $this->common->img_upload_responsive("member", "rc_img3");
    $rc_img3 = isset($rc_img3_resp["URL_LG"]) ? $rc_img3_resp["URL_LG"] : "";

    $rc_img4_resp = $this->common->img_upload_responsive("member", "rc_img4");
    $rc_img4 = isset($rc_img4_resp["URL_LG"]) ? $rc_img4_resp["URL_LG"] : "";

    $rc_img5_resp = $this->common->img_upload_responsive("member", "rc_img5");
    $rc_img5 = isset($rc_img5_resp["URL_LG"]) ? $rc_img5_resp["URL_LG"] : "";

    $rc_thumb = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $mm_img1 = $this->common->img_upload_url("member", "mm_img1");

    #로그 생성
    $sl_txt = [
      "rc_desc" => $rc_desc,
      "rc_hash" => $rc_hash,
      "rc_img1" => $rc_img1,
      "rc_img2" => $rc_img2,
      "rc_img3" => $rc_img3,
      "rc_img4" => $rc_img4,
      "rc_img5" => $rc_img5,
      "rc_thumb" => $rc_thumb,
      "mm_img1" => $mm_img1,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5"
      ]
    );


    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }


    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    $step5 = $this->site_model->getStepData($list, "스텝5");
    $work_img = $this->common->getSessionDataList("WORK_TYPE");

    $arr_recruit = [
      "rc_show_yn" => $step1["rc_show_yn"],
      "rc_name" => $step1["rc_name"],
      "rc_sdate" => $step1["rc_sdate"],
      "rc_edate" => $step1["rc_edate"],
      "rc_nation" => $step2["rc_nation"],
      "rc_sex" => $step2["rc_sex"],
      "rc_sage" => $step2["rc_sage"],
      "rc_eage" => $step2["rc_eage"],
      "rc_work" => $step2["rc_work"],
      "rc_work_img" => getWorkingImg(arr_implode(",", $step2["rc_work"])),
      "rc_work_type" => $step3["rc_work_type"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
      "rc_week" => $step3["rc_week"],
      "rc_am_stime" => $step3["rc_am_stime"],
      "rc_am_etime" => $step3["rc_am_etime"],
      "rc_pm_stime" => $step3["rc_pm_stime"],
      "rc_pm_etime" => $step3["rc_pm_etime"],
      "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
      "rc_work_dt_yoil" => get_yoil($step3["rc_work_dt"]),
      "rc_price_type" => $step3["rc_price_type"],
      "rc_price_rep" => $step3["rc_price_rep"],
      "rc_price_pay" => $step3["rc_price_pay"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_price" => $step3["rc_week_price"],
      "rc_month_price" => $step3["rc_month_price"],
      "rc_life_type" => $step4["rc_life_type"],
      "rc_life_type_tag" => getLifeType(arr_implode(",", $step4["rc_life_type"])),
      "rc_desc" => $step5["rc_desc"],
      "rc_hash" => $step5["rc_hash"],
      "rc_desc" => $step5["rc_desc"],
      "rc_img1" => $step5["rc_img1"],
      "rc_img2" => $step5["rc_img2"],
      "rc_img3" => $step5["rc_img3"],
      "rc_img4" => $step5["rc_img4"],
      "rc_img5" => $step5["rc_img5"],
      "mm_img" => $step5["mm_img"],
    ];

    json_return("00", "처리완료", $arr_recruit);
  }

  /* 사용하지 않는 함수 - recruit_write1_1로 대체됨
  # 스텝1
  function recruit_write1()
  {
    # 데이터 추가.
    $rc_show_yn = input_post("rc_show_yn", "Y");
    $rc_name = input_post("rc_name", "");
    $rc_date = input_post("rc_date", "");

    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_detail = input_post("ad_detail", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");

    if ($rc_name == "") {
      json_return("99", "공고제목이 확인되지 않습니다.");
    }

    if ($rc_date == "") {
      json_return("99", "채용공고기간이 확인되지 않습니다.");
    }

    # 데이터 검증
    if ($ad_gps_x == "" || $ad_gps_y == "") {
      json_return("99", "GPS정보가 확인되지 않습니다.");
    }

    if ($ad_addr == "") {
      json_return("99", "선택하신 주소가 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_show_yn" => $rc_show_yn,
      "rc_name" => $rc_name,
      "rc_date" => $rc_date,
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_detail" => $ad_detail,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1"
      ]
    );

    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write2_1로 대체됨
  # 스텝2
  function recruit_write2()
  {
    # 데이터 추가.
    $rc_work = input_post("rc_work", "");
    $rc_sex = input_post("rc_sex", "");
    $rc_nation = input_post("rc_nation", "");
    $rc_sage = input_post("rc_sage", "");
    $rc_eage = input_post("rc_eage", "");

    if ($rc_work == "") {
      json_return("99", "업무구분 선택이 확인되지 않습니다.");
    }

    if ($rc_sex == "") {
      json_return("99", "희망성별 선택이 확인되지 않습니다.");
    }

    if ($rc_nation == "") {
      json_return("99", "희망국적 선택이 확인되지 않습니다.");
    }


    #로그 생성
    $sl_txt = [
      "rc_work" => $rc_work,
      "rc_sex" => $rc_sex,
      "rc_nation" => $rc_nation,
      "rc_sage" => $rc_sage,
      "rc_eage" => $rc_eage
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write3_1로 대체됨
  # 스텝2
  function recruit_write3()
  {
    # 데이터 추가.
    $rc_work_type = input_post("rc_work_type", "");
    $rc_am_stime = input_post("rc_am_stime", "");
    $rc_am_etime = input_post("rc_am_etime", "");
    $rc_pm_stime = input_post("rc_pm_stime", "");
    $rc_pm_etime = input_post("rc_pm_etime", "");
    $rc_work_dt = input_post("rc_work_dt", "");
    $rc_price_type = input_post("rc_price_type", "");
    $rc_price_rep = input_post("rc_price_rep", "");
    $rc_price_pay = input_post("rc_price_pay", "");
    $rc_day_price = input_post("rc_day_price", "");
    $rc_week_price = input_post("rc_week_price", "");
    $rc_month_price = input_post("rc_month_price", "");
    $rc_week = input_post("rc_week", []);
    $rc_week_yn = input_post("rc_week_yn", "");
    if ($rc_week === "" || $rc_week === null) {
      $rc_week = [];
    }
    if (!is_array($rc_week)) {
      $rc_week = explode(",", $rc_week);
    }
    $rc_week = array_values(array_unique(array_filter(array_map('trim', $rc_week), function ($val) {
      return $val !== "" && $val !== null;
    })));
    if ($rc_week_yn !== "Y" && count($rc_week) === 0) {
      // 간편등록 기본 근무요일을 누락 없이 저장
      $rc_week = ["월", "화", "수", "목", "금"];
    }

    if ($rc_work_type == "") {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    if ($rc_work_dt == "") {
      json_return("99", "근무시작일 선택이 확인되지 않습니다.");
    }

    if ($rc_price_type == "") {
      json_return("99", "급여 선택이 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_work_type" => $rc_work_type,
      "rc_week_yn" => $rc_week_yn,
      "rc_am_stime" => $rc_am_stime,
      "rc_am_etime" => $rc_am_etime,
      "rc_pm_stime" => $rc_pm_stime,
      "rc_pm_etime" => $rc_pm_etime,
      "rc_work_dt" => $rc_work_dt,
      "rc_price_type" => $rc_price_type,
      "rc_price_rep" => $rc_price_rep,
      "rc_price_pay" => $rc_price_pay,
      "rc_day_price" => $rc_day_price,
      "rc_week_price" => $rc_week_price,
      "rc_month_price" => $rc_month_price,
      "rc_week" => $rc_week
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝3"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write2_1로 대체됨
  # 스텝4
  function recruit_write4()
  {
    # 데이터 추가.
    $rc_life_type = input_post("rc_life_type", "");

    #로그 생성
    $sl_txt = [
      "rc_life_type" => $rc_life_type
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝4"
      ]
    );
    json_return("00", "처리완료");
  }
  */

  /* 사용하지 않는 함수 - recruit_write3_1로 대체됨
  # 스텝5
  function recruit_write5()
  {
    # 데이터 추가.
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = $this->cleanSmartEditorDesc(input_post("rc_desc", ""));

    $rc_img1_ori = input_post("rc_img1_ori", "");
    $rc_img2_ori = input_post("rc_img2_ori", "");
    $rc_img3_ori = input_post("rc_img3_ori", "");
    $rc_img4_ori = input_post("rc_img4_ori", "");
    $rc_img5_ori = input_post("rc_img5_ori", "");


    $mm_img1_ori = input_post("mm_img1_ori", "");

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝5"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($rc_img1_ori != $json_info["rc_img1"]) {
          file_remove($json_info["rc_img1"]);
        }
        if ($rc_img2_ori != $json_info["rc_img2"]) {
          file_remove($json_info["rc_img2"]);
        }
        if ($rc_img3_ori != $json_info["rc_img3"]) {
          file_remove($json_info["rc_img3"]);
        }
        if ($rc_img4_ori != $json_info["rc_img4"]) {
          file_remove($json_info["rc_img4"]);
        }
        if ($rc_img5_ori != $json_info["rc_img5"]) {
          file_remove($json_info["rc_img5"]);
        }
        if ($mm_img1_ori != $json_info["mm_img1"]) {
          file_remove($json_info["mm_img1"]);
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리.
    $rc_img1_resp = $this->common->img_upload_responsive("member", "rc_img1");
    $rc_img1 = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $rc_img2_resp = $this->common->img_upload_responsive("member", "rc_img2");
    $rc_img2 = isset($rc_img2_resp["URL_LG"]) ? $rc_img2_resp["URL_LG"] : "";

    $rc_img3_resp = $this->common->img_upload_responsive("member", "rc_img3");
    $rc_img3 = isset($rc_img3_resp["URL_LG"]) ? $rc_img3_resp["URL_LG"] : "";

    $rc_img4_resp = $this->common->img_upload_responsive("member", "rc_img4");
    $rc_img4 = isset($rc_img4_resp["URL_LG"]) ? $rc_img4_resp["URL_LG"] : "";

    $rc_img5_resp = $this->common->img_upload_responsive("member", "rc_img5");
    $rc_img5 = isset($rc_img5_resp["URL_LG"]) ? $rc_img5_resp["URL_LG"] : "";

    $rc_thumb = isset($rc_img1_resp["URL_LG"]) ? $rc_img1_resp["URL_LG"] : "";

    $mm_img1 = $this->common->img_upload_url("member", "mm_img1");

    #로그 생성
    $sl_txt = [
      "rc_desc" => $rc_desc,
      "rc_hash" => $rc_hash,
      "rc_img1" => $rc_img1,
      "rc_img2" => $rc_img2,
      "rc_img3" => $rc_img3,
      "rc_img4" => $rc_img4,
      "rc_img5" => $rc_img5,
      "rc_thumb" => $rc_thumb,
      "mm_img1" => $mm_img1,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝5"
      ]
    );

    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }

    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    $step5 = $this->site_model->getStepData($list, "스텝5");
    $work_img = $this->common->getSessionDataList("WORK_TYPE");

    $arr_recruit = [
      "rc_show_yn" => $step1["rc_show_yn"],
      "rc_name" => $step1["rc_name"],
      "rc_sdate" => $step1["rc_sdate"],
      "rc_edate" => $step1["rc_edate"],
      "rc_nation" => $step2["rc_nation"],
      "rc_sex" => $step2["rc_sex"],
      "rc_sage" => $step2["rc_sage"],
      "rc_eage" => $step2["rc_eage"],
      "rc_work" => $step2["rc_work"],
      "rc_work_img" => getWorkingImg(arr_implode(",", $step2["rc_work"])),
      "rc_work_type" => $step3["rc_work_type"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
      "rc_week" => $step3["rc_week"],
      "rc_am_stime" => $step3["rc_am_stime"],
      "rc_am_etime" => $step3["rc_am_etime"],
      "rc_pm_stime" => $step3["rc_pm_stime"],
      "rc_pm_etime" => $step3["rc_pm_etime"],
      "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
      "rc_work_dt_yoil" => get_yoil($step3["rc_work_dt"]),
      "rc_price_type" => $step3["rc_price_type"],
      "rc_price_rep" => $step3["rc_price_rep"],
      "rc_price_pay" => $step3["rc_price_pay"],
      "rc_day_price" => $step3["rc_day_price"],
      "rc_week_price" => $step3["rc_week_price"],
      "rc_month_price" => $step3["rc_month_price"],
      "rc_life_type" => $step4["rc_life_type"],
      "rc_life_type_tag" => getLifeType(arr_implode(",", $step4["rc_life_type"])),
      "rc_desc" => $step5["rc_desc"],
      "rc_hash" => $step5["rc_hash"],
      "rc_desc" => $step5["rc_desc"],
      "rc_img1" => $step5["rc_img1"],
      "rc_img2" => $step5["rc_img2"],
      "rc_img3" => $step5["rc_img3"],
      "rc_img4" => $step5["rc_img4"],
      "rc_img5" => $step5["rc_img5"],
      "mm_img" => $step5["mm_img"],
    ];

    json_return("00", "처리완료", $arr_recruit);
  }
  */

  # 스텝1_1
  function recruit_write1_1()
  {
    # 데이터 추가.
    $rc_show_yn = input_post("rc_show_yn", "Y");
    $rc_name = input_post("rc_name", "");

    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_detail = input_post("ad_detail", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");

    // 공고노출지역 데이터 수신
    $expose_addr1_zipcode = input_post("expose_addr1_zipcode", "");
    $expose_addr1_jibun = input_post("expose_addr1_jibun", "");
    $expose_addr1_doro_val = input_post("expose_addr1_doro_val", "");
    $expose_addr1_addr = input_post("expose_addr1_addr", "");
    $expose_addr1_gps_x = input_post("expose_addr1_gps_x", "");
    $expose_addr1_gps_y = input_post("expose_addr1_gps_y", "");
    $expose_addr2_zipcode = input_post("expose_addr2_zipcode", "");
    $expose_addr2_jibun = input_post("expose_addr2_jibun", "");
    $expose_addr2_doro_val = input_post("expose_addr2_doro_val", "");
    $expose_addr2_addr = input_post("expose_addr2_addr", "");
    $expose_addr2_gps_x = input_post("expose_addr2_gps_x", "");
    $expose_addr2_gps_y = input_post("expose_addr2_gps_y", "");

    $rc_work = input_post("rc_work", []);
    $rc_work_type = input_post("rc_work_type", []);
    // $rc_work_negotiation = input_post("rc_work_negotiation", ""); // DB 컬럼 없음

    // 근무시간 필드 수신
    $rc_am_stime_h = input_post("rc_am_stime_h", "09");
    $rc_am_stime_m = input_post("rc_am_stime_m", "00");
    $rc_am_etime_h = input_post("rc_am_etime_h", "18");
    $rc_am_etime_m = input_post("rc_am_etime_m", "00");
    $rc_pm_stime_h = input_post("rc_pm_stime_h", "21");
    $rc_pm_stime_m = input_post("rc_pm_stime_m", "00");
    $rc_pm_etime_h = input_post("rc_pm_etime_h", "06");
    $rc_pm_etime_m = input_post("rc_pm_etime_m", "00");
    $rc_am_stime = input_post("rc_am_stime", "");
    $rc_am_etime = input_post("rc_am_etime", "");
    $rc_pm_stime = input_post("rc_pm_stime", "");
    $rc_pm_etime = input_post("rc_pm_etime", "");

    // HH:MM 포맷 합성 (hidden에서 오지 않을 경우 대비)
    if (empty($rc_am_stime)) {
        $rc_am_stime = $rc_am_stime_h . ":" . str_pad($rc_am_stime_m, 2, "0", STR_PAD_LEFT);
    }
    if (empty($rc_am_etime)) {
        $rc_am_etime = $rc_am_etime_h . ":" . str_pad($rc_am_etime_m, 2, "0", STR_PAD_LEFT);
    }
    if (empty($rc_pm_stime)) {
        $rc_pm_stime = $rc_pm_stime_h . ":" . str_pad($rc_pm_stime_m, 2, "0", STR_PAD_LEFT);
    }
    if (empty($rc_pm_etime)) {
        $rc_pm_etime = $rc_pm_etime_h . ":" . str_pad($rc_pm_etime_m, 2, "0", STR_PAD_LEFT);
    }

    $rc_price_type_main = input_post("rc_price_type_main", "");
    $rc_price_possible = input_post("rc_price_possible", ""); // 서브 급여형태 (시급가능, 일급가능, 주급가능, 월급가능)
    $rc_price_amount = input_post("rc_price_amount", "");
    $rc_price_daily = input_post("rc_price_daily", "");
    $rc_price_weekly = input_post("rc_price_weekly", "");
    $rc_price_monthly = input_post("rc_price_monthly", "");

    if ($rc_name == "") {
      json_return("99", "공고제목이 확인되지 않습니다.");
    }

    # 데이터 검증
    if ($ad_gps_x == "" || $ad_gps_y == "") {
      json_return("99", "GPS정보가 확인되지 않습니다.");
    }

    if ($ad_addr == "") {
      json_return("99", "선택하신 주소가 확인되지 않습니다.");
    }

    if (empty($rc_work) || !is_array($rc_work)) {
      json_return("99", "업무구분 선택이 확인되지 않습니다.");
    }

    if (empty($rc_work_type)) {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    // 급여협의가 아닌 경우에만 금액 검증
    if ($rc_price_type_main == "") {
      json_return("99", "급여 정보가 확인되지 않습니다.");
    }
    if ($rc_price_type_main != "급여협의" && $rc_price_amount == "") {
      json_return("99", "급여 금액이 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_show_yn" => $rc_show_yn,
      "rc_name" => $rc_name,
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_detail" => $ad_detail,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y,
      // 공고노출지역 저장
      "expose_addr1_zipcode" => $expose_addr1_zipcode,
      "expose_addr1_jibun" => $expose_addr1_jibun,
      "expose_addr1_doro_val" => $expose_addr1_doro_val,
      "expose_addr1_addr" => $expose_addr1_addr,
      "expose_addr1_gps_x" => $expose_addr1_gps_x,
      "expose_addr1_gps_y" => $expose_addr1_gps_y,
      "expose_addr2_zipcode" => $expose_addr2_zipcode,
      "expose_addr2_jibun" => $expose_addr2_jibun,
      "expose_addr2_doro_val" => $expose_addr2_doro_val,
      "expose_addr2_addr" => $expose_addr2_addr,
      "expose_addr2_gps_x" => $expose_addr2_gps_x,
      "expose_addr2_gps_y" => $expose_addr2_gps_y,
      "rc_work" => is_array($rc_work) ? $rc_work : [],
      "rc_work_type" => is_array($rc_work_type) ? $rc_work_type : [],
      // "rc_work_negotiation" => $rc_work_negotiation, // DB 컬럼 없음
      "rc_am_stime" => $rc_am_stime,
      "rc_am_etime" => $rc_am_etime,
      "rc_pm_stime" => $rc_pm_stime,
      "rc_pm_etime" => $rc_pm_etime,
      "rc_price_type_main" => $rc_price_type_main,
      "rc_price_possible" => $rc_price_possible, // 서브 급여형태 저장
      "rc_price_amount" => $rc_price_amount,
      "rc_price_daily" => $rc_price_daily,
      "rc_price_weekly" => $rc_price_weekly,
      "rc_price_monthly" => $rc_price_monthly,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝1"
      ]
    );

    $rc_idx = input_post("rc_idx", "0");
    json_return("00", "처리완료", ["rc_idx" => $rc_idx]);
  }

  # 스텝2_1
  function recruit_write2_1()
  {
    # 데이터 추가.
    $rc_life_type = input_post("rc_life_type", []);

    if (empty($rc_life_type) || !is_array($rc_life_type)) {
      json_return("99", "복리후생 선택이 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "rc_life_type" => is_array($rc_life_type) ? $rc_life_type : [],
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RECRUIT",
        "sl_step" => "스텝2"
      ]
    );
    $rc_idx = input_post("rc_idx", "0");
    json_return("00", "처리완료", ["rc_idx" => $rc_idx]);
  }

  # 채용공고 메인사진 즉시 업로드 (반응형: sm:400px/md:800px/lg:1200px)
  function recruit_main_img_upload()
  {
    $result = $this->common->img_upload_responsive("member", "rc_img1");

    if (!$result["SUCCESS"]) {
      json_return("90", "이미지가 업로드 되지 않았습니다.");
    }

    json_return("00", "업로드 되었습니다.", [
      "url" => $result["URL_SM"],
      "url_sm" => $result["URL_SM"],
      "url_md" => $result["URL_MD"],
      "url_lg" => $result["URL_LG"]
    ]);
  }

  # 스텝3_1
  function recruit_write3_1()
  {
    # 데이터 추가.
    $rc_desc = $this->cleanSmartEditorDesc(input_post("rc_desc", ""));
    $rc_hash_string = input_post("rc_hash_string", "");
    $rc_sdate = input_post("rc_sdate", "");
    $rc_edate = input_post("rc_edate", "");
    $rc_sex = input_post("rc_sex", []);
    $rc_sex_all = input_post("rc_sex_all", "");
    $rc_nation = input_post("rc_nation", []);
    $rc_nation_all = input_post("rc_nation_all", "");
    $rc_sage = input_post("rc_sage", "");
    $rc_eage = input_post("rc_eage", "");
    $rc_age_all = input_post("rc_age_all", "");
    $rc_day = input_post("rc_day", []);
    $rc_day_all = input_post("rc_day_all", "");

    $rc_img1_ori = input_post("rc_img1_ori", "");
    $rc_detail_img1 = input_post("rc_detail_img1", "");
    $rc_detail_img1_ori = input_post("rc_detail_img1_ori", "");
    $rc_detail_img2 = input_post("rc_detail_img2", "");
    $rc_detail_img2_ori = input_post("rc_detail_img2_ori", "");
    $rc_detail_img3 = input_post("rc_detail_img3", "");
    $rc_detail_img3_ori = input_post("rc_detail_img3_ori", "");

    if ($rc_desc == "") {
      json_return("99", "상세업무정보가 확인되지 않습니다.");
    }

    if ($rc_sdate == "" || $rc_edate == "") {
      json_return("99", "채용공고기간이 확인되지 않습니다.");
    }

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT", "sl_step" => "스텝3"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($rc_img1_ori != (isset($json_info["rc_img1"]) ? $json_info["rc_img1"] : "")) {
          if (isset($json_info["rc_img1"]) && $json_info["rc_img1"] != "") {
            file_remove($json_info["rc_img1"]);
          }
        }
        // 상세페이지 이미지 1
        if ($rc_detail_img1_ori != (isset($json_info["rc_detail_img1"]) ? $json_info["rc_detail_img1"] : "")) {
          if (isset($json_info["rc_detail_img1"]) && $json_info["rc_detail_img1"] != "") {
            file_remove($json_info["rc_detail_img1"]);
          }
        }
        // 상세페이지 이미지 2
        if ($rc_detail_img2_ori != (isset($json_info["rc_detail_img2"]) ? $json_info["rc_detail_img2"] : "")) {
          if (isset($json_info["rc_detail_img2"]) && $json_info["rc_detail_img2"] != "") {
            file_remove($json_info["rc_detail_img2"]);
          }
        }
        // 상세페이지 이미지 3
        if ($rc_detail_img3_ori != (isset($json_info["rc_detail_img3"]) ? $json_info["rc_detail_img3"] : "")) {
          if (isset($json_info["rc_detail_img3"]) && $json_info["rc_detail_img3"] != "") {
            file_remove($json_info["rc_detail_img3"]);
          }
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리.
    try {
      $rc_img1 = $this->common->img_upload_url("member", "rc_img1", 1200);
      $rc_thumb = $this->common->img_upload_url("member", "rc_img1", 600);
    } catch (Exception $e) {
      throw $e;
    }

    #로그 생성
    $sl_txt = [
      "rc_desc" => $rc_desc,
      "rc_hash" => $rc_hash_string,
      "rc_sdate" => $rc_sdate,
      "rc_edate" => $rc_edate,
      "rc_sex" => is_array($rc_sex) ? $rc_sex : [],
      "rc_sex_all" => $rc_sex_all,
      "rc_nation" => is_array($rc_nation) ? $rc_nation : [],
      "rc_nation_all" => $rc_nation_all,
      "rc_sage" => $rc_sage,
      "rc_eage" => $rc_eage,
      "rc_age_all" => $rc_age_all,
      "rc_day" => is_array($rc_day) ? $rc_day : [],
      "rc_day_all" => $rc_day_all,
      "rc_img1" => $rc_img1,
      "rc_detail_img1" => $rc_detail_img1,
      "rc_detail_img2" => $rc_detail_img2,
      "rc_detail_img3" => $rc_detail_img3,
      "rc_thumb" => $rc_thumb,
    ];

    try {
      $this->site_model->setSiteLog(
        [
          "session_id" => session_id(),
          "mm_idx" => $this->mm_idx,
          "sl_type" => "RECRUIT",
          "sl_step" => "스텝3",
          "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
        ],
        [
          "mm_idx" => $this->mm_idx,
          "sl_type" => "RECRUIT",
          "sl_step" => "스텝3"
        ]
      );
    } catch (Exception $e) {
      json_return("99", "데이터 저장 중 오류가 발생하였습니다: " . $e->getMessage());
    }

    $rc_idx = input_post("rc_idx", "0");
   
    json_return("00", "처리완료", ["rc_idx" => $rc_idx]);
  }

  # 채용공고 등록 완료
  function recruit_complete()
  {
   

    $rc_idx = input_post("rc_idx", 0);
    $mm_comp_num = input_post("mm_comp_num", 0);
    $mm_comp_num_org = input_post("mm_comp_num_org", 0);
    $preview_mode = input_post("preview_mode", 0); // 미리보기 모드 (1: 임시저장, 0: 정식등록)

   

    $recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
    if ($rc_idx != 0 && $recruit_info == null) {
      json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
    }

    if ($rc_idx != 0 && $this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
      json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
    }

    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]
    );

    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step5 = $this->site_model->getStepData($list, "스텝5");

    # step1, step2, step3 데이터 유효성 검사
    if (!is_array($step1) || !isset($step1["rc_name"])) {
      json_return("99", "스텝1 데이터가 존재하지 않습니다.");
    }
    if (!is_array($step2) || !isset($step2["rc_life_type"])) {
      json_return("99", "스텝2 데이터가 존재하지 않습니다.");
    }
    if (!is_array($step3) || !isset($step3["rc_desc"])) {
      json_return("99", "스텝3 데이터가 존재하지 않습니다.");
    }

    # 날짜 처리: 모바일(스텝3)과 웹(스텝1의 rc_date) 모두 지원
    $rc_sdate = "";
    $rc_edate = "";
    // 모바일 버전: 스텝3에 rc_sdate, rc_edate가 있음
    if (isset($step3["rc_sdate"]) && isset($step3["rc_edate"]) && !empty($step3["rc_sdate"]) && !empty($step3["rc_edate"])) {
      $rc_sdate = $step3["rc_sdate"] . " 00:00:00";
      $rc_edate = $step3["rc_edate"] . " 23:59:59";
    } elseif (isset($step1["rc_date"]) && !empty($step1["rc_date"])) {
      // 웹 버전: 스텝1의 rc_date를 파싱
      // 여러 형식 지원
      // 형식1: "[모집시작일] 2025-10-22 ~ [모집종료일] 2025-12-22"
      // 형식2: "2025-10-22 ~ 2025-12-22"
      // 형식3: "2025.10.22 ~ 2025.12.22" (Flatpickr altInput)
      $rc_date = $step1["rc_date"];

      // 마침표를 하이픈으로 변환 (altFormat Y.m.d -> Y-m-d)
      $rc_date = str_replace(".", "-", $rc_date);

      // 레이블 제거
      $rc_date = str_replace("[모집시작일]", "", $rc_date);
      $rc_date = str_replace("[모집종료일]", "", $rc_date);

      // " ~ " 로 분리
      $date_parts = explode(" ~ ", trim($rc_date));
      if (count($date_parts) == 2) {
        $start_date = trim($date_parts[0]);
        $end_date = trim($date_parts[1]);

        // 날짜 형식 검증 (YYYY-MM-DD)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
          $rc_sdate = $start_date . " 00:00:00";
          $rc_edate = $end_date . " 23:59:59";
        }
      }
    } elseif (isset($step1["rc_sdate"]) && isset($step1["rc_edate"])) {
      // 기존 형식 지원
      $rc_sdate = $step1["rc_sdate"] . " 00:00:00";
      $rc_edate = $step1["rc_edate"] . " 23:59:59";
    }

    // 날짜가 없으면 기본값 설정 (오늘 ~ 2개월 후)
    if (empty($rc_sdate) || empty($rc_edate)) {
      $rc_sdate = date("Y-m-d 00:00:00");
      $rc_edate = date("Y-m-d 23:59:59", strtotime("+2 months"));
    }

    if ($rc_idx != 0) {
      # 트랜젝션 시작
      $this->db->trans_begin();

      // rc_work 처리: 모바일(스텝1)과 웹(스텝2) 모두 지원
      $rc_work_value = "";
      if (isset($step1["rc_work"]) && !empty($step1["rc_work"])) {
        // 모바일 버전: 스텝1에 rc_work가 있음
        if (is_array($step1["rc_work"])) {
          $rc_work_value = arr_implode(",", $step1["rc_work"]);
        } else {
          $rc_work_value = $step1["rc_work"];
        }
      } elseif (isset($step2["rc_work"]) && !empty($step2["rc_work"])) {
        // 웹 버전: 스텝2에 rc_work가 있음
        if (is_array($step2["rc_work"])) {
          $rc_work_value = arr_implode(",", $step2["rc_work"]);
        } else {
          $rc_work_value = $step2["rc_work"];
        }
      }

      // rc_work_type 처리: 모바일(스텝1)과 웹(스텝3) 모두 지원
      $rc_work_type_value = "";
      if (isset($step1["rc_work_type"]) && !empty($step1["rc_work_type"])) {
        // 모바일 버전: 스텝1에 rc_work_type이 있음
        if (is_array($step1["rc_work_type"])) {
          $rc_work_type_value = arr_implode(",", $step1["rc_work_type"]);
        } else {
          $rc_work_type_value = $step1["rc_work_type"];
        }
      } elseif (isset($step3["rc_work_type"]) && !empty($step3["rc_work_type"])) {
        // 웹 버전: 스텝3에 rc_work_type이 있음
        if (is_array($step3["rc_work_type"])) {
          $rc_work_type_value = arr_implode(",", $step3["rc_work_type"]);
        } else {
          $rc_work_type_value = $step3["rc_work_type"];
        }
      }

      // 급여 정보 처리: 모바일(스텝1)과 웹(스텝3) 모두 지원
      $rc_price_type_value = "";
      $rc_price_rep_value = "";
      $rc_price_pay_value = 0;
      $rc_day_price_value = 0;
      $rc_week_price_value = 0;
      $rc_month_price_value = 0;

      if (isset($step1["rc_price_type_main"]) && !empty($step1["rc_price_type_main"])) {
        // 모바일 버전: 스텝1에 급여 정보가 있음
        // rc_price_type에 메인과 서브를 콤마로 구분하여 저장
        $rc_price_type_main = $step1["rc_price_type_main"];
        $rc_price_possible = isset($step1["rc_price_possible"]) ? $step1["rc_price_possible"] : "";

        if ($rc_price_type_main == "급여협의") {
          $rc_price_type_value = "급여협의";
        } else if ($rc_price_possible != "") {
          // 서브 급여형태가 있는 경우: "주급,일급" 형식으로 저장
          $sub_type = str_replace("가능", "", $rc_price_possible); // "일급가능" -> "일급"
          $rc_price_type_value = $rc_price_type_main . "," . $sub_type;
        } else {
          // 서브 급여형태가 없는 경우: 메인만 저장
          $rc_price_type_value = $rc_price_type_main;
        }

        $rc_price_rep_value = isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "";
        $rc_price_pay_value = isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_day_price_value = isset($step1["rc_price_daily"]) && $step1["rc_price_daily"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_week_price_value = isset($step1["rc_price_weekly"]) && $step1["rc_price_weekly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_month_price_value = isset($step1["rc_price_monthly"]) && $step1["rc_price_monthly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
      } else {
        // 웹 버전: 스텝3에 급여 정보가 있음
        $rc_price_type_value = isset($step3["rc_price_type"]) ? $step3["rc_price_type"] : "";
        $rc_price_rep_value = isset($step3["rc_price_rep"]) ? $step3["rc_price_rep"] : "";
        $rc_price_pay_value = isset($step3["rc_price_pay"]) ? intval($step3["rc_price_pay"]) : 0;
        $rc_day_price_value = isset($step3["rc_day_price"]) ? intval($step3["rc_day_price"]) : 0;
        $rc_week_price_value = isset($step3["rc_week_price"]) ? intval($step3["rc_week_price"]) : 0;
        $rc_month_price_value = isset($step3["rc_month_price"]) ? intval($step3["rc_month_price"]) : 0;
      }

      // rc_week 처리: 모바일(스텝3의 rc_day) 또는 웹(스텝3의 rc_week) 모두 지원
      $rc_week_value = "";
      if (isset($step3["rc_day"]) && !empty($step3["rc_day"])) {
        // 모바일 버전: 스텝3에 rc_day가 있음 (요일무관 체크 시 모든 요일 선택)
        if (isset($step3["rc_day_all"]) && $step3["rc_day_all"] == "Y") {
          // 요일무관이면 모든 요일 선택
          $rc_week_value = "일,월,화,수,목,금,토";
        } else if (is_array($step3["rc_day"])) {
          $rc_week_value = arr_implode(",", $step3["rc_day"]);
        } else {
          $rc_week_value = $step3["rc_day"];
        }
      } elseif (isset($step3["rc_week"]) && !empty($step3["rc_week"])) {
        // 웹 버전: 스텝3에 rc_week이 있음
        if (is_array($step3["rc_week"])) {
          $rc_week_value = arr_implode(",", $step3["rc_week"]);
        } else {
          $rc_week_value = $step3["rc_week"];
        }
      }
      if ($rc_week_value === "" && (!isset($step3["rc_week_yn"]) || $step3["rc_week_yn"] !== "Y")) {
        // 근무요일이 비어있을 경우 기본값(월~금)을 저장해 다른 화면에서도 동일하게 활용
        $rc_week_value = "월,화,수,목,금";
      }

      // 근무시간 처리: 모바일(스텝1) 또는 웹(스텝3)
      $rc_am_stime_value = isset($step1["rc_am_stime"]) ? $step1["rc_am_stime"] : (isset($step3["rc_am_stime"]) ? $step3["rc_am_stime"] : "");
      $rc_am_etime_value = isset($step1["rc_am_etime"]) ? $step1["rc_am_etime"] : (isset($step3["rc_am_etime"]) ? $step3["rc_am_etime"] : "");
      $rc_pm_stime_value = isset($step1["rc_pm_stime"]) ? $step1["rc_pm_stime"] : (isset($step3["rc_pm_stime"]) ? $step3["rc_pm_stime"] : "");
      $rc_pm_etime_value = isset($step1["rc_pm_etime"]) ? $step1["rc_pm_etime"] : (isset($step3["rc_pm_etime"]) ? $step3["rc_pm_etime"] : "");

      $arr_recruit = [
        "rc_show_yn" => ($preview_mode == 1) ? "N" : (isset($step1["rc_show_yn"]) ? $step1["rc_show_yn"] : "Y"), // 미리보기 모드면 임시저장("N"), 아니면 정식등록
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_work" => $rc_work_value,  // 모바일(스텝1) 또는 웹(스텝2)
        "rc_work_type" => $rc_work_type_value,  // 모바일(스텝1) 또는 웹(스텝3)
        "rc_price_type" => $rc_price_type_value,  // 모바일(스텝1) 또는 웹(스텝3)
        "rc_price_rep" => $rc_price_rep_value,
        "rc_price_pay" => $rc_price_pay_value,
        "rc_day_price" => $rc_day_price_value,
        "rc_week_price" => $rc_week_price_value,
        "rc_month_price" => $rc_month_price_value,
        "rc_week" => $rc_week_value,  // 모바일(스텝3의 rc_day) 또는 웹(스텝3의 rc_week)
        "rc_am_stime" => $rc_am_stime_value,  // 주간 출근 시간
        "rc_am_etime" => $rc_am_etime_value,  // 주간 퇴근 시간
        "rc_pm_stime" => $rc_pm_stime_value,  // 야간 출근 시간
        "rc_pm_etime" => $rc_pm_etime_value,  // 야간 퇴근 시간
        "rc_life_type" => isset($step2["rc_life_type"]) && is_array($step2["rc_life_type"]) ? arr_implode(",", $step2["rc_life_type"]) : "",
        "rc_nation" => isset($step3["rc_nation"]) && is_array($step3["rc_nation"]) ? arr_implode(",", $step3["rc_nation"]) : "",
        "rc_sex" => isset($step3["rc_sex"]) && is_array($step3["rc_sex"]) ? arr_implode(",", $step3["rc_sex"]) : "",
        "rc_sage" => isset($step3["rc_sage"]) ? $step3["rc_sage"] : "",
        "rc_eage" => isset($step3["rc_eage"]) ? $step3["rc_eage"] : "",
        "rc_desc" => isset($step3["rc_desc"]) ? $step3["rc_desc"] : (isset($step5["rc_desc"]) ? $step5["rc_desc"] : ""),  // 스텝3 우선, 없으면 스텝5 (Smart Editor HTML 유지)
        "rc_hash" => isset($step3["rc_hash"]) ? $step3["rc_hash"] : (isset($step5["rc_hash"]) ? $step5["rc_hash"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img1" => isset($step3["rc_img1"]) ? $step3["rc_img1"] : (isset($step5["rc_img1"]) ? $step5["rc_img1"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img2" => isset($step3["rc_img2"]) ? $step3["rc_img2"] : (isset($step5["rc_img2"]) ? $step5["rc_img2"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img3" => isset($step3["rc_img3"]) ? $step3["rc_img3"] : (isset($step5["rc_img3"]) ? $step5["rc_img3"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img4" => isset($step3["rc_img4"]) ? $step3["rc_img4"] : (isset($step5["rc_img4"]) ? $step5["rc_img4"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img5" => isset($step5["rc_img5"]) ? $step5["rc_img5"] : "",
        "rc_detail_img1" => isset($step3["rc_detail_img1"]) ? $step3["rc_detail_img1"] : "",
        "rc_detail_img2" => isset($step3["rc_detail_img2"]) ? $step3["rc_detail_img2"] : "",
        "rc_detail_img3" => isset($step3["rc_detail_img3"]) ? $step3["rc_detail_img3"] : "",
        "rc_thumb" => isset($step3["rc_thumb"]) ? $step3["rc_thumb"] : (isset($step5["rc_thumb"]) ? $step5["rc_thumb"] : ""),  // 스텝3 우선, 없으면 스텝5
      ];

      $result_recruit = $this->recruit_model->updateRecruit($arr_recruit, ["rc_idx" => $rc_idx]);

      if ($result_recruit["code"] != "00") {
        $this->db->trans_rollback();
        json_return("91", "[01]채용공고 수정시 오류가 발생하였습니다.");
      }

      $arr_addr = [
        "mm_idx" => $this->mm_idx,
        "ad_type" => "RECRUIT",
        "ad_key" => $rc_idx,
        "ad_zipcode" => isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "",
        "ad_jibun" => isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "",
        "ad_doro" => isset($step1["ad_doro"]) ? $step1["ad_doro"] : "",
        "ad_detail" => isset($step1["ad_detail"]) ? $step1["ad_detail"] : "",
        "ad_addr" => isset($step1["ad_addr"]) ? $step1["ad_addr"] : "",
        "ad_gps_x" => isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "",
        "ad_gps_y" => isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "",
      ];

      // 기존 주소 확인
      $existing_addr = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx]);
      if ($existing_addr != null) {
        // 기존 주소가 있으면 UPDATE
        $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx]);
      } else {
        // 기존 주소가 없으면 INSERT
        $result_addr = $this->member_model->insertAddress($arr_addr);
      }
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 수정시 오류가 발생하였습니다.");
      }

      // 노출지역 처리 (RECRUIT_EXPOSE_1, RECRUIT_EXPOSE_2)
      $expose_list = [
        "1" => [
          "ad_type" => "RECRUIT_EXPOSE_1",
          "addr"    => isset($step1["expose_addr1_addr"])     ? $step1["expose_addr1_addr"]     : "",
          "zipcode" => isset($step1["expose_addr1_zipcode"])  ? $step1["expose_addr1_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr1_jibun"])    ? $step1["expose_addr1_jibun"]    : "",
          "doro"    => isset($step1["expose_addr1_doro_val"]) ? $step1["expose_addr1_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr1_gps_x"])    ? $step1["expose_addr1_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr1_gps_y"])    ? $step1["expose_addr1_gps_y"]    : "",
        ],
        "2" => [
          "ad_type" => "RECRUIT_EXPOSE_2",
          "addr"    => isset($step1["expose_addr2_addr"])     ? $step1["expose_addr2_addr"]     : "",
          "zipcode" => isset($step1["expose_addr2_zipcode"])  ? $step1["expose_addr2_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr2_jibun"])    ? $step1["expose_addr2_jibun"]    : "",
          "doro"    => isset($step1["expose_addr2_doro_val"]) ? $step1["expose_addr2_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr2_gps_x"])    ? $step1["expose_addr2_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr2_gps_y"])    ? $step1["expose_addr2_gps_y"]    : "",
        ],
      ];

      foreach ($expose_list as $seq => $expose) {
        $where_expose = ["ad_type" => $expose["ad_type"], "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx];

        if (!empty($expose["addr"])) {
          // 노출지역 데이터가 있으면 저장
          $arr_expose = [
            "mm_idx"     => $this->mm_idx,
            "ad_type"    => $expose["ad_type"],
            "ad_key"     => $rc_idx,
            "ad_zipcode" => $expose["zipcode"],
            "ad_jibun"   => $expose["jibun"],
            "ad_doro"    => $expose["doro"],
            "ad_addr"    => $expose["addr"],
            "ad_gps_x"   => $expose["gps_x"],
            "ad_gps_y"   => $expose["gps_y"],
          ];

          $existing_expose = $this->member_model->getAddress($where_expose);
          if ($existing_expose != null) {
            // 기존 노출지역이 있으면 UPDATE
            $result_expose = $this->member_model->updateAddress($arr_expose, $where_expose);
          } else {
            // 기존 노출지역이 없으면 INSERT
            $result_expose = $this->member_model->insertAddress($arr_expose);
          }

          if ($result_expose["code"] != "00") {
            $this->db->trans_rollback();
            json_return("93", "[03]채용공고 노출지역 저장시 오류가 발생하였습니다.");
          }
        } else {
          // 노출지역 데이터가 없으면 기존 데이터 삭제
          $this->member_model->deleteAddress($where_expose);
        }
      }

      # 로그 삭제
      $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]);

      if ($mm_comp_num != $mm_comp_num_org && $mm_comp_num != "N") {
        //json_return("94","[error1][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");
        $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);

        //사업자번호 인증시 "지금뜨는 업체 8일권" 부여
        $promotion = [
          "mm_idx" => $this->mm_idx,
          "rc_idx" => $rc_idx,
          "pm_code" => "C002", //$promotion_info["cd_key"],
          "pm_name" => "지금뜨는 업체(7일권)", //$promotion_info["cd_val"],
          "pm_point" => "0", //$promotion_info["cd_val3"],
          "pm_sdate" => date("Y-m-d") . " 00:00:00", //$pm_sdate,
          "pm_edate" => date("Y-m-d", strtotime("+7 days")) . " 23:59:59", //$pm_edate,
          "pm_addr" => "" //$pm_addr
        ];
        $result_promotion = $this->site_model->insertPromotionInfo($promotion);
      }
      //json_return("95","[error2][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");

      $this->db->trans_commit();
      json_return("00", "수정 성공", ["alarm_list" => null, "rc_idx" => $rc_idx]);
    } else {
      # 신규등록 : 포인트 여부 확인 : 포인트 차감대상
      $point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U010"]);
      $use_point = $point_info["cd_val3"];
      $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
      $count = $this->recruit_model->getRecruitTodayCnt($this->mm_idx);

      if ($count >= 5) {
        if ($mem_info["mm_point"] < $use_point) {
          json_return("93", "보유하신 포인트(" . number_format($mem_info["mm_point"]) . "P)가 부족하여 채용공고 등록이 불가능합니다.");
        }
      }

      # 트랜젝션 시작
      $this->db->trans_begin();

      // rc_work 처리: 모바일(스텝1)과 웹(스텝2) 모두 지원
      $rc_work_value_new = "";
      if (isset($step1["rc_work"]) && !empty($step1["rc_work"])) {
        // 모바일 버전: 스텝1에 rc_work가 있음
        if (is_array($step1["rc_work"])) {
          $rc_work_value_new = arr_implode(",", $step1["rc_work"]);
        } else {
          $rc_work_value_new = $step1["rc_work"];
        }
      } elseif (isset($step2["rc_work"]) && !empty($step2["rc_work"])) {
        // 웹 버전: 스텝2에 rc_work가 있음
        if (is_array($step2["rc_work"])) {
          $rc_work_value_new = arr_implode(",", $step2["rc_work"]);
        } else {
          $rc_work_value_new = $step2["rc_work"];
        }
      }

      // rc_work_type 처리: 모바일(스텝1)과 웹(스텝3) 모두 지원
      $rc_work_type_value_new = "";
      if (isset($step1["rc_work_type"]) && !empty($step1["rc_work_type"])) {
        // 모바일 버전: 스텝1에 rc_work_type이 있음
        if (is_array($step1["rc_work_type"])) {
          $rc_work_type_value_new = arr_implode(",", $step1["rc_work_type"]);
        } else {
          $rc_work_type_value_new = $step1["rc_work_type"];
        }
      } elseif (isset($step3["rc_work_type"]) && !empty($step3["rc_work_type"])) {
        // 웹 버전: 스텝3에 rc_work_type이 있음
        if (is_array($step3["rc_work_type"])) {
          $rc_work_type_value_new = arr_implode(",", $step3["rc_work_type"]);
        } else {
          $rc_work_type_value_new = $step3["rc_work_type"];
        }
      }

      // 급여 정보 처리: 모바일(스텝1)과 웹(스텝3) 모두 지원
      $rc_price_type_value_new = "";
      $rc_price_rep_value_new = "";
      $rc_price_pay_value_new = 0;
      $rc_day_price_value_new = 0;
      $rc_week_price_value_new = 0;
      $rc_month_price_value_new = 0;

      if (isset($step1["rc_price_type_main"]) && !empty($step1["rc_price_type_main"])) {
        // 모바일 버전: 스텝1에 급여 정보가 있음
        // rc_price_type에 메인과 서브를 콤마로 구분하여 저장
        $rc_price_type_main = $step1["rc_price_type_main"];
        $rc_price_possible = isset($step1["rc_price_possible"]) ? $step1["rc_price_possible"] : "";

        if ($rc_price_type_main == "급여협의") {
          $rc_price_type_value_new = "급여협의";
        } else if ($rc_price_possible != "") {
          // 서브 급여형태가 있는 경우: "주급,일급" 형식으로 저장
          $sub_type = str_replace("가능", "", $rc_price_possible); // "일급가능" -> "일급"
          $rc_price_type_value_new = $rc_price_type_main . "," . $sub_type;
        } else {
          // 서브 급여형태가 없는 경우: 메인만 저장
          $rc_price_type_value_new = $rc_price_type_main;
        }

        $rc_price_rep_value_new = isset($step1["rc_price_type_main"]) ? $step1["rc_price_type_main"] : "";
        $rc_price_pay_value_new = isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_day_price_value_new = isset($step1["rc_price_daily"]) && $step1["rc_price_daily"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_week_price_value_new = isset($step1["rc_price_weekly"]) && $step1["rc_price_weekly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
        $rc_month_price_value_new = isset($step1["rc_price_monthly"]) && $step1["rc_price_monthly"] == "Y" && isset($step1["rc_price_amount"]) ? intval($step1["rc_price_amount"]) : 0;
      } else {
        // 웹 버전: 스텝3에 급여 정보가 있음
        $rc_price_type_value_new = isset($step3["rc_price_type"]) ? $step3["rc_price_type"] : "";
        $rc_price_rep_value_new = isset($step3["rc_price_rep"]) ? $step3["rc_price_rep"] : "";
        $rc_price_pay_value_new = isset($step3["rc_price_pay"]) ? intval($step3["rc_price_pay"]) : 0;
        $rc_day_price_value_new = isset($step3["rc_day_price"]) ? intval($step3["rc_day_price"]) : 0;
        $rc_week_price_value_new = isset($step3["rc_week_price"]) ? intval($step3["rc_week_price"]) : 0;
        $rc_month_price_value_new = isset($step3["rc_month_price"]) ? intval($step3["rc_month_price"]) : 0;
      }

      // 근무시간 처리: 모바일(스텝1) 또는 웹(스텝3)
      $rc_am_stime_value_new = isset($step1["rc_am_stime"]) ? $step1["rc_am_stime"] : (isset($step3["rc_am_stime"]) ? $step3["rc_am_stime"] : "");
      $rc_am_etime_value_new = isset($step1["rc_am_etime"]) ? $step1["rc_am_etime"] : (isset($step3["rc_am_etime"]) ? $step3["rc_am_etime"] : "");
      $rc_pm_stime_value_new = isset($step1["rc_pm_stime"]) ? $step1["rc_pm_stime"] : (isset($step3["rc_pm_stime"]) ? $step3["rc_pm_stime"] : "");
      $rc_pm_etime_value_new = isset($step1["rc_pm_etime"]) ? $step1["rc_pm_etime"] : (isset($step3["rc_pm_etime"]) ? $step3["rc_pm_etime"] : "");

      $arr_recruit = [
        "mm_idx" => $this->mm_idx,
        "rc_status" => "Y", // 신규 공고는 활성 상태로 등록
        "rc_show_yn" => ($preview_mode == 1) ? "N" : (isset($step1["rc_show_yn"]) ? $step1["rc_show_yn"] : "Y"), // 미리보기 모드면 임시저장("N"), 아니면 정식등록
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_work" => $rc_work_value_new,  // 모바일(스텝1) 또는 웹(스텝2)
        "rc_work_type" => $rc_work_type_value_new,  // 모바일(스텝1) 또는 웹(스텝3)
        "rc_price_type" => $rc_price_type_value_new,  // 모바일(스텝1) 또는 웹(스텝3)
        "rc_price_rep" => $rc_price_rep_value_new,
        "rc_price_pay" => $rc_price_pay_value_new,
        "rc_day_price" => $rc_day_price_value_new,
        "rc_week_price" => $rc_week_price_value_new,
        "rc_month_price" => $rc_month_price_value_new,
        "rc_am_stime" => $rc_am_stime_value_new,  // 주간 출근 시간
        "rc_am_etime" => $rc_am_etime_value_new,  // 주간 퇴근 시간
        "rc_pm_stime" => $rc_pm_stime_value_new,  // 야간 출근 시간
        "rc_pm_etime" => $rc_pm_etime_value_new,  // 야간 퇴근 시간
        "rc_life_type" => isset($step2["rc_life_type"]) && is_array($step2["rc_life_type"]) ? arr_implode(",", $step2["rc_life_type"]) : "",
        "rc_nation" => isset($step3["rc_nation"]) && is_array($step3["rc_nation"]) ? arr_implode(",", $step3["rc_nation"]) : "",
        "rc_sex" => isset($step3["rc_sex"]) && is_array($step3["rc_sex"]) ? arr_implode(",", $step3["rc_sex"]) : "",
        "rc_sage" => isset($step3["rc_sage"]) ? $step3["rc_sage"] : "",
        "rc_eage" => isset($step3["rc_eage"]) ? $step3["rc_eage"] : "",
        "rc_desc" => isset($step3["rc_desc"]) ? $step3["rc_desc"] : (isset($step5["rc_desc"]) ? $step5["rc_desc"] : ""),  // 스텝3 우선, 없으면 스텝5 (Smart Editor HTML 유지)
        "rc_hash" => isset($step3["rc_hash"]) ? $step3["rc_hash"] : (isset($step5["rc_hash"]) ? $step5["rc_hash"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img1" => isset($step3["rc_img1"]) ? $step3["rc_img1"] : (isset($step5["rc_img1"]) ? $step5["rc_img1"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img2" => isset($step3["rc_img2"]) ? $step3["rc_img2"] : (isset($step5["rc_img2"]) ? $step5["rc_img2"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img3" => isset($step3["rc_img3"]) ? $step3["rc_img3"] : (isset($step5["rc_img3"]) ? $step5["rc_img3"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img4" => isset($step3["rc_img4"]) ? $step3["rc_img4"] : (isset($step5["rc_img4"]) ? $step5["rc_img4"] : ""),  // 스텝3 우선, 없으면 스텝5
        "rc_img5" => isset($step5["rc_img5"]) ? $step5["rc_img5"] : "",
        "rc_detail_img1" => isset($step3["rc_detail_img1"]) ? $step3["rc_detail_img1"] : "",
        "rc_detail_img2" => isset($step3["rc_detail_img2"]) ? $step3["rc_detail_img2"] : "",
        "rc_detail_img3" => isset($step3["rc_detail_img3"]) ? $step3["rc_detail_img3"] : "",
        "rc_thumb" => isset($step3["rc_thumb"]) ? $step3["rc_thumb"] : (isset($step5["rc_thumb"]) ? $step5["rc_thumb"] : ""),  // 스텝3 우선, 없으면 스텝5
        "up_dt" => date("Y-m-d H:i:s")
      ];

      $result_recruit = $this->recruit_model->insertRecruit($arr_recruit);

      if ($result_recruit["code"] != "00") {
        $this->db->trans_rollback();
        json_return("91", "[01]채용공고 등록시 오류가 발생하였습니다.");
      }

      //2024.08.15 최초 등록시 1만 포인트 추가 지급
      //1.이미 지급되었는지 확인 (포인트리스트에서)
      $query_point  = "select count(*) as cnt from st_point_list ";
      $query_point .= "where mm_idx = '" . $this->mm_idx . "' ";
      $query_point .= "and   pl_desc like '%첫 공고 등록%' ";

      $result_point = $this->db->query($query_point);
      $count_point = $result_point->row_array();
      $cnt_point = 0;
      if ($count_point != null) {
        $cnt_point = $count_point["cnt"];
      }
      // $query_recruit  = "select count(*) as cnt from st_recruit ";
      // $query_recruit .= "where mm_idx = '".$this->mm_idx."' ";
      $cnt_recruit = 0;

      //2.포인트 지급처리
      if ($cnt_point == 0 && $cnt_recruit == 0) {
        //2.1. 포인트 지급히스토리 추가
        $pay_point = 10000;
        $insert = [
          "mm_idx" => $this->mm_idx,
          "o_num" => "",
          "pm_idx" => 0,
          "pt_type" => "PROMOTION",
          "pl_type" => "plus",
          "pl_point" => $pay_point,
          "pl_last_point" => $pay_point,
          "pl_desc" => "첫 공고 등록 포인트 지급(10,000P)",
          "reg_id" => "system",
        ];
        $result_point = $this->site_model->insertPointList($insert);

        //2.2. 회원정보에 잔액 업데이트
        $sql_mem = "update st_member set mm_point = mm_point + (" . $pay_point . ") where mm_idx = '" . $this->mm_idx . "' ";
        $query_mem = $this->db->query($sql_mem);
        $affectedRows_mem = $this->db->affected_rows();

        if ($query_mem && $affectedRows_mem == 1) {
          //업데이트완료
        } else {
          $this->db->trans_rollback();
          json_return("01", "잔액변경 오류. 관리자에게 문의하세요.");
        }
      }

      $arr_addr = [
        "mm_idx" => $this->mm_idx,
        "ad_type" => "RECRUIT",
        "ad_key" => $result_recruit["key"],
        "ad_zipcode" => isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "",
        "ad_jibun" => isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "",
        "ad_doro" => isset($step1["ad_doro"]) ? $step1["ad_doro"] : "",
        "ad_detail" => isset($step1["ad_detail"]) ? $step1["ad_detail"] : "",
        "ad_addr" => isset($step1["ad_addr"]) ? $step1["ad_addr"] : "",
        "ad_gps_x" => isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "",
        "ad_gps_y" => isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "",
      ];

      // 기존 주소 확인 (신규 등록이지만 혹시 모를 경우를 대비)
      $existing_addr = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $result_recruit["key"]]);
      if ($existing_addr != null) {
        // 기존 주소가 있으면 UPDATE
        $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $result_recruit["key"]]);
      } else {
        // 기존 주소가 없으면 INSERT
        $result_addr = $this->member_model->insertAddress($arr_addr);
      }
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 등록시 오류가 발생하였습니다.");
      }

      // 공고노출지역 저장
    

      $expose_list = [
        "1" => [
          "ad_type" => "RECRUIT_EXPOSE_1",
          "addr"    => isset($step1["expose_addr1_addr"])     ? $step1["expose_addr1_addr"]     : "",
          "zipcode" => isset($step1["expose_addr1_zipcode"])  ? $step1["expose_addr1_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr1_jibun"])    ? $step1["expose_addr1_jibun"]    : "",
          "doro"    => isset($step1["expose_addr1_doro_val"]) ? $step1["expose_addr1_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr1_gps_x"])    ? $step1["expose_addr1_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr1_gps_y"])    ? $step1["expose_addr1_gps_y"]    : "",
        ],
        "2" => [
          "ad_type" => "RECRUIT_EXPOSE_2",
          "addr"    => isset($step1["expose_addr2_addr"])     ? $step1["expose_addr2_addr"]     : "",
          "zipcode" => isset($step1["expose_addr2_zipcode"])  ? $step1["expose_addr2_zipcode"]  : "",
          "jibun"   => isset($step1["expose_addr2_jibun"])    ? $step1["expose_addr2_jibun"]    : "",
          "doro"    => isset($step1["expose_addr2_doro_val"]) ? $step1["expose_addr2_doro_val"] : "",
          "gps_x"   => isset($step1["expose_addr2_gps_x"])    ? $step1["expose_addr2_gps_x"]    : "",
          "gps_y"   => isset($step1["expose_addr2_gps_y"])    ? $step1["expose_addr2_gps_y"]    : "",
        ],
      ];

      foreach ($expose_list as $seq => $expose) {
       
        $rc_idx_val = $rc_idx != 0 ? $rc_idx : $result_recruit["key"];
       

        $where_expose = ["ad_type" => $expose["ad_type"], "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx_val];
  

        if (!empty($expose["addr"])) {
          $arr_expose = [
            "mm_idx"     => $this->mm_idx,
            "ad_type"    => $expose["ad_type"],
            "ad_key"     => $rc_idx_val,
            "ad_zipcode" => $expose["zipcode"],
            "ad_jibun"   => $expose["jibun"],
            "ad_doro"    => $expose["doro"],
            "ad_addr"    => $expose["addr"],
            "ad_gps_x"   => $expose["gps_x"],
            "ad_gps_y"   => $expose["gps_y"],
          ];
         

          $existing_expose = $this->member_model->getAddress($where_expose);
        

          if ($existing_expose != null) {
       
            $result = $this->member_model->updateAddress($arr_expose, $where_expose);

          } else {
         
            $result = $this->member_model->insertAddress($arr_expose);
            
          }
        } else {
        
          $result = $this->member_model->deleteAddress($where_expose);
       
        }
      }

      # 포인트 차감
      if ($count >= 5) {
        # 포인트 취소
        $last_point = $mem_info["mm_point"] - $use_point;
        $insert = [
          "mm_idx" => $this->mm_idx,
          "o_num" => "",
          "pm_idx" => $result_recruit["key"],
          "pt_type" => "USE",
          "pl_type" => "minus",
          "pl_point" => $use_point,
          "pl_last_point" => $last_point,
          "pl_desc" => "채용공고등록(" . $use_point . "P차감)",
          "reg_id" => "system",
        ];
        $result_point = $this->site_model->insertPointList($insert);

        if ($result_point["code"] != "00") {
          $this->db->trans_rollback();
          json_return("93", "[03]채용공고 등록시 오류가 발생하였습니다.");
        }

        $result_member = $this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $this->mm_idx]);
      }


      # 로그 삭제
      $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RECRUIT"]);

      #####################################################
      # 푸시 발송
      #####################################################
      $temp_list = $this->site_model->getAlarmTargetRecruit($this->mm_idx);
      $token_list = [];
      foreach ($temp_list as $temp) {
        $yoil = get_yoil(get_today());
        $time = get_today("H:i");

        $skip = false;
        if (isset($temp["token"]) && $temp["token"] != "" && isset($temp["alarm_deny_yn"]) && $temp["alarm_deny_yn"] == "Y" && isset($temp["alarm_deny_yoil"]) && $temp["alarm_deny_yoil"] == $yoil && isset($temp["alarm_deny_stime"]) && isset($temp["alarm_deny_etime"]) && ($temp["alarm_deny_stime"] < $time && $temp["alarm_deny_etime"] > $time)) {
          $skip = true;
        }

        if ($skip == false && isset($temp["token"]) && $temp["token"] != "") {
          $token_list[] = $temp["token"];
        }
      }
      //error_log("\n:::::insertRecruit9: ", "3", "/testlog/php_log");

      // FCM 토큰이 있을 때만 발송 (빈 배열 방지)
      if (!empty($token_list) && is_array($token_list) && count($token_list) > 0) {
        $info = [
          "title" => "NowJob 채용정보 알림",
          "body" => "즐겨찾기하신 " . $mem_info["mm_comp_name"] . "에서 일자리를 등록 했습니다.",
          "img" => "",
          "url" => "",
        ];
        $fcm_result = sendFcm($info, $token_list);
      }
      #####################################################

      #####################################################
      # 알림 등록
      #####################################################
      //error_log("\n:::::insertRecruit10: ", "3", "/testlog/php_log");

      $tbl_alarm = get_mongodb("alarm");
      foreach ($temp_list as $temp) {

        //추가정보 설정
        $price_time = "";
        if ($arr_recruit["rc_am_stime"] != "") {
          $price_time = $arr_recruit["rc_am_stime"] . "~" . $arr_recruit["rc_am_etime"];
        }

        if ($arr_recruit["rc_pm_stime"] != "") {
          if ($price_time == "") {
            $price_time = $arr_recruit["rc_pm_stime"] . "~" . $arr_recruit["rc_pm_etime"];
          } else {
            $price_time .= " ," . $arr_recruit["rc_pm_stime"] . "~" . $arr_recruit["rc_pm_etime"];
          }
        }

        //알림 추가
        $alarm = [
          "user_id" => $temp["mm_id"],
          "al_type" => "recruit",
          "al_key" => "" . $result_recruit["key"],
          "al_text" => "<strong>즐겨찾기한 <em>" . $mem_info["mm_comp_name"] . "</em>에서 <em>일자리를 등록</em>했습니다.</strong>",
          "al_etc" => "<p class='time'>" . $price_time . "</p><p class='pay'><span>" . $arr_recruit["rc_price_rep"] . "</span><span>" . $arr_recruit["rc_price_pay"] . "</span></p>",
          "al_url" => "",
          "show_yn" => "N",
          "reg_dt" => get_today(),
        ];

        $tbl_alarm->insert($alarm);
      }
      //error_log("\n:::::insertRecruit12: ", "3", "/testlog/php_log");

      #####################################################

      if ($mm_comp_num != $mm_comp_num_org && $mm_comp_num != "N") {
        //json_return("94","[error1][".$mm_comp_num."][".$mm_comp_num_org."][".$this->mm_idx."]");
        $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);

        //사업자번호 인증시 "지금뜨는 업체 8일권" 부여
        $promotion = [
          "mm_idx" => $this->mm_idx,
          "rc_idx" => $result_recruit["key"],
          "pm_code" => "C002", //$promotion_info["cd_key"],
          "pm_name" => "지금뜨는 업체(7일권)", //$promotion_info["cd_val"],
          "pm_point" => "0", //$promotion_info["cd_val3"],
          "pm_sdate" => date("Y-m-d") . " 00:00:00", //$pm_sdate,
          "pm_edate" => date("Y-m-d", strtotime("+7 days")) . " 23:59:59", //$pm_edate,
          "pm_addr" => "" //$pm_addr
        ];
        $result_promotion = $this->site_model->insertPromotionInfo($promotion);
      }

      //error_log("\n:::::insertRecruit13: ", "3", "/testlog/php_log");
      $this->db->trans_commit();
      //error_log("\n:::::insertRecruit14: ", "3", "/testlog/php_log");
      //error_log("\n:::::insertRecruit15: ".$mem_info["mm_comp_name"], "3", "/testlog/php_log");

      $return_data = ["target_name" => $mem_info["mm_comp_name"], "rc_idx" => $result_recruit["key"]];
      json_return("00", "등록 성공", $return_data);
    }
  }

  # 채용공고 공개 (임시저장된 공고를 공개)
  function recruit_publish()
  {
    $rc_idx = input_post("rc_idx", 0);

    if ($rc_idx == 0) {
      json_return("99", "채용공고 정보가 없습니다.");
    }

    $recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
    if ($recruit_info == null) {
      json_return("91", "채용공고 정보를 찾을 수 없습니다.");
    }

    if ($this->mm_idx != $recruit_info["mm_idx"]) {
      json_return("91", "권한이 없습니다.");
    }

    // rc_show_yn을 "Y"로 변경하여 공개
    $result = $this->recruit_model->updateRecruit(["rc_show_yn" => "Y"], ["rc_idx" => $rc_idx]);

    if ($result["code"] != "00") {
      json_return("91", "채용공고 등록 중 오류가 발생하였습니다.");
    }

    json_return("00", "채용공고가 등록되었습니다.");
  }

  # 이력서 삭제
  function recruit_delete()
  {
    $rc_idx = input_post("rc_idx");
    $result = $this->member_model->updateRecruit(["rc_status" => "N"], ["rc_idx" => $rc_idx]);
    json_return($result["code"], $result["msg"]);
  }

  # 채용공고 개수 조회
  function getRecruitCount()
  {
    // 현재 로그인된 회원의 채용공고 개수 조회
    $count = $this->recruit_model->getRecruitCount([
      "mm_idx" => $this->mm_idx,
      "rc_status" => "Y"
    ]);

    json_return("00", "조회완료", ["count" => $count]);
  }

  # 디폴트 설정
  function recruit_default()
  {
    $rc_idx_list = input_post("rc_idx_list");
    $arr_idx = explode(",", $rc_idx_list);

    $result = $this->member_model->updateRecruit(["rc_show_yn" => "N"], ["mm_idx" => $this->mm_idx]);
    foreach ($arr_idx as $idx) {
      $result = $this->member_model->updateRecruit(["rc_show_yn" => "Y"], ["rc_idx" => $idx]);
    }

    json_return($result["code"], $result["msg"]);
  }

  # 공개 설정
  function recruit_open()
  {
    $rc_idx_list = input_post("rc_idx_list");
    $arr_idx = explode(",", $rc_idx_list);

    foreach ($arr_idx as $idx) {
      $result = $this->member_model->updateRecruit(["rc_show_yn" => "Y"], ["rc_idx" => $idx]);
    }

    json_return($result["code"], $result["msg"]);
  }

  # 비공개 설정
  function recruit_close()
  {
    $rc_idx_list = input_post("rc_idx_list");
    $arr_idx = explode(",", $rc_idx_list);

    foreach ($arr_idx as $idx) {
      $result = $this->member_model->updateRecruit(["rc_show_yn" => "N"], ["rc_idx" => $idx]);
    }

    json_return($result["code"], $result["msg"]);
  }

  # 마감처리
  function recruit_end()
  {
    $rc_idx_list = input_post("rc_idx_list");
    $arr_idx = explode(",", $rc_idx_list);

    foreach ($arr_idx as $idx) {
      // 현재 공고 정보 조회 (시작일 확인용)
      $recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $idx]);

      if ($recruit_info == null) {
        continue; // 공고가 없으면 건너뛰기
      }

      // 현재 시간보다 1초 이전으로 설정하여 확실히 마감 처리
      $end_date = date("Y-m-d H:i:s", time() - 1);

      // 시작일보다 작으면 시작일로 설정 (논리적 무결성 보장)
      $start_date = $recruit_info["rc_sdate"];
      if (strtotime($end_date) < strtotime($start_date)) {
        $end_date = $start_date;
      }

      $result = $this->member_model->updateRecruit(["rc_edate" => $end_date], ["rc_idx" => $idx]);
    }

    json_return($result["code"], $result["msg"]);
  }

  # 삭제처리
  function recruit_del()
  {

    $rc_idx_list = input_post("rc_idx_list");
    $arr_idx = explode(",", $rc_idx_list);

    # 트랙젝션 시작
    $this->db->trans_begin();
    foreach ($arr_idx as $idx) {
      $sql1 = "insert into st_recruit_del_history
        (rc_idx, mm_idx, rc_show_yn, rc_name, rc_sdate, rc_edate, rc_nation, rc_sex, rc_sage, rc_eage,
         rc_work, rc_work_type, rc_day_price, rc_week_yn, rc_week, rc_am_stime, rc_am_etime, rc_pm_stime,
         rc_pm_etime, rc_work_dt, rc_price_type, rc_month_price, rc_life_type, rc_desc, rc_hash, rc_thumb,
         rc_img1, rc_img2, rc_img3, rc_img4, rc_img5, rc_status, stop_desc, stop_dt, rc_hit, reg_dt, up_dt,
         rc_price_rep, rc_week_price, rc_price_pay)
        select rc_idx, mm_idx, rc_show_yn, rc_name, rc_sdate, rc_edate, rc_nation, rc_sex, rc_sage, rc_eage,
         rc_work, rc_work_type, rc_day_price, rc_week_yn, rc_week, rc_am_stime, rc_am_etime, rc_pm_stime,
         rc_pm_etime, rc_work_dt, rc_price_type, rc_month_price, rc_life_type, rc_desc, rc_hash, rc_thumb,
         rc_img1, rc_img2, rc_img3, rc_img4, rc_img5, rc_status, stop_desc, stop_dt, rc_hit, reg_dt, up_dt,
         rc_price_rep, rc_week_price, rc_price_pay
        from st_recruit where rc_idx = '" . $idx . "'";
      $query1 = $this->db->query($sql1);

      // 영향 받은 행의 수를 얻기
      $affectedRows1 = $this->db->affected_rows();
      if ($query1 && $affectedRows1 == 1) {

        $sql2 = "delete from st_recruit where rc_idx = '" . $idx . "' ";
        $query2 = $this->db->query($sql2);
        $affectedRows2 = $this->db->affected_rows();

        if ($query2 && $affectedRows2 == 1) {
          # 트랙젝션 완료
          $this->db->trans_commit();
          json_return("00", "처리완료");
        } else {

          $this->db->trans_rollback();
          json_return("02", "삭제오류. 관리자에게 문의하세요.");
        }
      } else {

        $this->db->trans_rollback();
        json_return("01", "백업오류. 관리자에게 문의하세요.");
      }
    } //foreach

    json_return($result["code"], $result["msg"]);
  }

  # 면접 상태 변경처리
  function chage_interview_proc()
  {
    $iv_idx = input_post("iv_idx", "");
    $iv_status = input_post("iv_status", "");

    if ($iv_status == "평가완료") {
      $iv_eval_num1 = input_post("iv_eval_num1", "");
      $iv_eval_num2 = input_post("iv_eval_num2", "");
      $iv_eval_num3 = input_post("iv_eval_num3", "");

      $info = [
        "iv_status" => $iv_status,
        "iv_eval_num1" => $iv_eval_num1,
        "iv_eval_num2" => $iv_eval_num2,
        "iv_eval_num3" => $iv_eval_num3,
        "iv_eval_yn" => "Y",
      ];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    } else if ($iv_status == "출근") {
      $iv_hire_dt = input_post("iv_hire_dt", "");

      $info = [
        "iv_status" => $iv_status,
        "iv_hire_dt" => $iv_hire_dt,
      ];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    } else if ($iv_status == "면접불참") {
      $data = $this->common_model->getInterview(["iv_idx" => $iv_idx]);
      $info = [
        "iv_non_cnt" => $data["iv_non_cnt"] + 1,
        "iv_hire_dt" => $iv_hire_dt,
      ];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    } else if ($iv_status == "미출근") {
      $data = $this->common_model->getInterview(["iv_idx" => $iv_idx]);
      $info = [
        "iv_abs_cnt" => $data["iv_abs_cnt"] + 1,
        "iv_hire_dt" => $iv_hire_dt,
      ];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    } else if ($iv_status == "무단퇴사") {
      $data = $this->common_model->getInterview(["iv_idx" => $iv_idx]);
      $info = [
        "iv_lv_cnt" => $data["iv_lv_cnt"] + 1,
        "iv_hire_dt" => $iv_hire_dt,
      ];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    } else {
      $info = ["iv_status" => $iv_status];
      $result = $this->common_model->updateInterview($info, ["iv_idx" => $iv_idx]);
    }


    $result = $this->common_model->updateInterview(["iv_status" => $iv_status], ["iv_idx" => $iv_idx]);
    json_return($result["code"], $result["msg"]);
  }

  # 면점자 상세
  function interv_detail_ajax()
  {
    $data = [];
    $iv_idx = input_post("iv_idx");
    $info = $this->common_model->getInterview(["iv_idx" => $iv_idx]);

    if ($info == null) {
      go_back("잘못된 접근입니다.");
    }

    $temp = $this->member_model->getResumeDetail($info["ru_idx"], "");

    $resume = [
      "ru_idx" => $temp["ru_idx"],
      "src" => getResumeImg($temp),
      "category" => getWorkingImg($temp["ru_work"]),
      "work_type" => $temp["ru_work_type"],
      "mm_nation" => $temp["mm_nation"],
      "ma_age" => $temp["ma_age"],
      "week" => getWeekYoil($temp["ru_week"]),
      "ru_my_type" => $temp["ru_my_type"],
      "mm_name" => $temp["mm_name"],
      "ad_addr" => $temp["ad_addr"],
      "eval_num" => $temp["eval_num"],
      "eval_per" => $temp["eval_per"],
    ];

    json_return("00", "조회완료", ["resume" => $resume]);
    $this->layout->shop('/compage/interv_detail', $data);
  }


  function out_proc()
  {
    $mm_idx = input_post("mm_idx");
    $mm_comp_num = input_post("mm_comp_num");
    $yn = input_post("yn");

    $mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx, "mm_comp_num" => $mm_comp_num]);

    if ($mem_info == null) {
      json_return("90", "회원정보를 조회 할수 없습니다.");
    }

    $result = $this->member_model->updateMember(["mm_comp_num_yn" => $yn], ["mm_idx" => $mem_info["mm_idx"]]);
    json_return($result["code"], $result["code"] == "00" ? "처리완료" : "처리시 오류가 발생하였습니다.");
  }

  function com_biz_updateAll()
  {
    # 데이터 추가.
    $mm_comp_name = input_post("comp_name", "");
    $mm_comp_num = input_post("comp_num", "");
    $mm_comp_type = input_post("comp_type", "");

    //$mm_comp_auth = $this->common->img_upload_url("member","mm_comp_auth");

    //json_return($result["code"],"mm_idx:".$this->mm_idx."|".$mm_comp_name."|".$mm_comp_num."|".$mm_comp_type."|".$mm_comp_auth);

    //$result = $this->member_model->updateMember(["mm_comp_name"=>$mm_comp_name],["mm_idx"=>$this->mm_idx]);
    //$result = $this->member_model->updateMember(["mm_comp_num"=>$mm_comp_num],["mm_idx"=>$this->mm_idx]);
    //$result = $this->member_model->updateMember(["mm_comp_auth"=>$mm_comp_auth],["mm_idx"=>$this->mm_idx]);
    //$result = $this->member_model->updateMember(["mm_comp_type"=>$mm_comp_type],["mm_idx"=>$this->mm_idx]);

    //$mm_img2_ori = input_post("mm_img2_ori","");
    //$mm_img1 = $this->common->img_upload_url("member","mm_img1",1200);

    $mm_thumb_del = input_post("mm_thumb_del", "");
    if ($mm_thumb_del == "Y") {
      $result = $this->member_model->updateMember(["mm_thumb" => ""], ["mm_idx" => $this->mm_idx]);
    } else {
      $mm_thumb = $this->common->img_upload_url("member", "mm_thumb");
      if ($mm_thumb) {
        $result = $this->member_model->updateMember(["mm_thumb" => $mm_thumb], ["mm_idx" => $this->mm_idx]);

        $mem_info = $this->user->getUser();
        $this->user->setUser([
          "mm_idx" => $mem_info["mm_idx"],
          "mm_id" => $mem_info["mm_id"],
          "mm_point" => $mem_info["mm_point"],
          "mm_name" => $mem_info["mm_name"],
          "mm_type" => $mem_info["mm_type"],
          "mm_join_type" => $mem_info["mm_join_type"],
          "mm_img" => $mm_thumb,
          "ad_addr" => $mem_info["ad_addr"],
          "ad_gps_x" => $mem_info["ad_gps_x"],
          "ad_gps_y" => $mem_info["ad_gps_y"],
          "mm_sex" => $mem_info["mm_sex"],
        ]);
      }
    }
    $mm_img1 = $this->common->img_upload_url("member", "mm_img1");
    $mm_img2 = $this->common->img_upload_url("member", "mm_img2");
    $mm_img3 = $this->common->img_upload_url("member", "mm_img3");
    $mm_img4 = $this->common->img_upload_url("member", "mm_img4");
    //$profile2 = $this->common->img_upload_url("member","profile2");
    //json_return("99","profile2:".$profile2); //test
    //exit;//test
    $result = $this->member_model->updateMember(["mm_img1" => $mm_img1], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_img2" => $mm_img2], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_img3" => $mm_img3], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_img4" => $mm_img4], ["mm_idx" => $this->mm_idx]);
    //if($profile2){
    //  $result = $this->member_model->updateMember(["mm_thumb"=>$profile2],["mm_idx"=>$this->mm_idx]);
    //}

    json_return($result["code"], $result["code"] == "00" ? "처리완료[" . $mm_thumb . "]" : "처리시 오류가 발생하였습니다.");
    //json_return($result["code"],"처리완료");

  }

  function com_biz_update()
  {
    # 데이터 추가.
    $mm_comp_name = input_post("comp_name", "");
    $mm_comp_num = input_post("comp_num", "");
    $mm_comp_type = input_post("comp_type", "");
    $mm_addr = input_post("mm_addr", "");
    $mm_zipcode = input_post("mm_zipcode", "");
    $mm_doro = input_post("mm_doro", "");
    $mm_jibun = input_post("mm_jibun", "");
    $mm_email = input_post("mm_email", "");
    $compUpdateType = input_post("compUpdateType", "");

    $mm_comp_auth = $this->common->img_upload_url("member", "mm_comp_auth");

    //json_return($result["code"],"mm_idx:".$this->mm_idx."|".$mm_comp_name."|".$mm_comp_num."|".$mm_comp_type."|".$mm_comp_auth);

    if ($compUpdateType == "compname") {
      $result = $this->member_model->updateMember(["mm_comp_name" => $mm_comp_name], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_type" => $mm_comp_type], ["mm_idx" => $this->mm_idx]);
    } else if ($compUpdateType == "bizno") {
      $result = $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);
    } else if ($compUpdateType == "addr") {
      $result = $this->member_model->updateMember(["mm_zipcode" => $mm_zipcode], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_doro" => $mm_doro], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_addr" => $mm_addr], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_jibun" => $mm_jibun], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_email" => $mm_email], ["mm_idx" => $this->mm_idx]);
    } else {
      // compUpdateType이 "all"일 때: 사업자명, 사업자등록번호, 주소 모두 저장
      $result = $this->member_model->updateMember(["mm_comp_name" => $mm_comp_name], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_auth" => $mm_comp_auth], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_type" => $mm_comp_type], ["mm_idx" => $this->mm_idx]);

      // 주소 정보도 함께 저장 (주소가 입력된 경우)
      if ($mm_addr != "" || $mm_zipcode != "" || $mm_doro != "" || $mm_jibun != "") {
        $result = $this->member_model->updateMember(["mm_zipcode" => $mm_zipcode], ["mm_idx" => $this->mm_idx]);
        $result = $this->member_model->updateMember(["mm_doro" => $mm_doro], ["mm_idx" => $this->mm_idx]);
        $result = $this->member_model->updateMember(["mm_addr" => $mm_addr], ["mm_idx" => $this->mm_idx]);
        $result = $this->member_model->updateMember(["mm_jibun" => $mm_jibun], ["mm_idx" => $this->mm_idx]);
      }

      $result = $this->member_model->updateMember(["mm_email" => $mm_email], ["mm_idx" => $this->mm_idx]);
    }

    json_return($result["code"], $result["code"] == "00" ? "처리완료" : "처리시 오류가 발생하였습니다.");
  }

  function com_biz_update2()
  {
    # 데이터 추가.
    $mm_name = input_post("mm_name", "");
    $mm_phone = input_post("mm_phone", "");
    $mm_di    = input_post("mm_di", "");
    $mm_sex   = input_post("mm_sex", "");

    // di 값이 있는 경우(실명인증을 한 경우)
    // 나의 기존 di 가 아닌 다른 회원정보에 di가 있으면
    // 전화,SNS로 기존 가입한게 있으면
    // 기존아이디로 로그인 하도록 연결하고 이 회원은 로그인 못하도록 처리
    $di_already_msg = "";
    if ($mm_di != "") {
      $mem_info = $this->member_model->getMember(["mm_di" => $mm_di]);
      if ($mem_info != null && $mem_info["mm_idx"] != $this->mm_idx) {
        if ($mem_info["mm_join_type"] == "KAKAO") {
          $di_already_msg = "이미 본인인증한 회원정보가 존재합니다.\n\n이미 인증한 카카오 아이디로 로그인 해주세요.";
        }
        if ($mem_info["mm_join_type"] == "APPLE") {
          $di_already_msg = "이미 본인인증한 회원정보가 존재합니다.\n\n이미 인증한 애플 아이디로 로그인 해주세요.";
        }
        if ($mem_info["mm_join_type"] == "WEB") {
          $di_already_msg = "이미 본인인증한 회원정보가 존재합니다.\n\n이미 인증한 휴대폰번호+비밀번호로 로그인 해주세요.";
        }
      }
    }

    if ($mm_name != "") {
      $result = $this->member_model->updateMember(["mm_name" => $mm_name], ["mm_idx" => $this->mm_idx]);

      // $user_info = $this->getUser();
      // $user_info["mm_name"] = $mm_name;
      // $this->CI->common->setSession("USER_INFO",$user_info);

      // $user_info = $this->getCurrentUserInfo();
      // $this->CI->common->setSession("USER_INFO",$user_info);

    }
    if ($mm_phone != "") {
      $result = $this->member_model->updateMember(["mm_phone" => $mm_phone], ["mm_idx" => $this->mm_idx]);

      // $user_info = $this->getUser();
      // $user_info["mm_phone"] = $mm_phone;
      // $this->CI->common->setSession("USER_INFO",$user_info);

      // $user_info = $this->getCurrentUserInfo();
      // $this->CI->common->setSession("USER_INFO",$user_info);

    }
    if ($mm_di != "") {
      $result = $this->member_model->updateMember(["mm_di" => $mm_di], ["mm_idx" => $this->mm_idx]);
    }
    if ($mm_sex != "") {
      $result = $this->member_model->updateMember(["mm_sex" => $mm_sex], ["mm_idx" => $this->mm_idx]);
    }
    $result = $this->member_model->updateMember(["mem_info_up_dt" => date("Y-m-d H:i:s")], ["mm_idx" => $this->mm_idx]);
    if ($di_already_msg == "") {
      json_return($result["code"], $result["code"] == "00" ? "처리완료" : "처리시 오류가 발생하였습니다.");
    } else {
      $result = $this->member_model->updateMember(["stop_dt" => date("Y-m-d H:i:s")], ["mm_idx" => $this->mm_idx]);
      json_return("01", $di_already_msg);
    }
  }

  function com_biz_update3()
  {
    # 데이터 추가.
    $mm_pass_old = input_post("mm_pass_old", "");
    $mm_pass = input_post("mm_pass", "");
    $mm_pass2 = input_post("mm_pass2", "");

    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx, "mm_pwd" => $this->common->getSqlPass($mm_pass_old)]);
    if ($mem_info == null) {
      json_return("90", "현재 비밀번호가 일치하지 않습니다.");
      exit;
    }

    $result = $this->member_model->updateMember(["mm_pwd" => $this->common->getSqlPass($mm_pass)], ["mm_idx" => $this->mm_idx]);
    json_return($result["code"], $result["code"] == "00" ? "처리완료" : "처리시 오류가 발생하였습니다.");
  }

  function com_biz_update4()
  {
    # 데이터 추가.
    $mm_email = input_post("mm_email", "");

    $result = $this->member_model->updateMember(["mm_email" => $mm_email], ["mm_idx" => $this->mm_idx]);

    json_return($result["code"], $result["code"] == "00" ? "처리완료[" . $mm_email . "][" . $this->mm_idx . "]" : "처리시 오류가 발생하였습니다.");
    //json_return($result["code"],$result["code"]=="00" ? "처리완료[".$mm_email."][".$this->mm_idx."]":"처리시 오류가 발생하였습니다.");

  }

  function com_biz_retire()
  {
    # 데이터 추가.
    //회원정보 삭제 처리
    $result = $this->member_model->updateMember(["mm_name" => "탈퇴회원"], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_phone" => ""], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_di" => ""], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateMember(["mm_birth" => ""], ["mm_idx" => $this->mm_idx]);

    $result = $this->member_model->updateMember(["fire_dt" => date("Y-m-d H:i:s"), "mm_status" => "N"], ["mm_idx" => $this->mm_idx]);
    $this->common->setSession("COM_MAN_RETIRE", null);
    $this->common->removeSession("USER_INFO");
    json_return($result["code"], $result["code"] == "00" ? "처리완료" : "처리시 오류가 발생하였습니다.");
  }

  function resume_pass_fail()
  {
    $mq_idx = input_post("mq_idx", "");
    $mq_status = input_post("mq_status", "");
    $mq_memo = input_post("mq_memo", "");

    # 트랙젝션 시작
    $this->db->trans_begin();

    $sql = "update st_member_request set mq_status = '" . $mq_status . "', mq_memo = '" . $mq_memo . "' where mq_idx = '" . $mq_idx . "' ";
    $query = $this->db->query($sql);
    $affectedRows = $this->db->affected_rows();

    if ($query && $affectedRows == 1) {
      # 트랙젝션 완료
      $this->db->trans_commit();
      json_return("00", "처리완료");
    } else {

      $this->db->trans_rollback();
      json_return("01", "상태변경오류. 관리자에게 문의하세요.");
    }
  }

  function resume_pass_fail_array()
  {
    $mq_idx_array = input_post("mq_idx", []); // mq_idx를 배열로 받음
    $mq_status = input_post("mq_status", "");
    //$mq_memo = input_post("mq_memo","");

    # 트랙젝션 시작
    $this->db->trans_begin();

    // 배열의 갯수만큼 반복하여 쿼리 실행
    foreach ($mq_idx_array as $mq_idx) {
      $sql = "update st_member_request set mq_status = '" . $mq_status . "', mq_memo = '" . $mq_memo . "' where mq_idx = '" . $mq_idx . "' ";
      $query = $this->db->query($sql);
      $affectedRows = $this->db->affected_rows();

      if ($query && $affectedRows == 1) {
        # 트랙젝션 완료
        //$this->db->trans_commit();
        //json_return("00","처리완료");
      } else {
        $this->db->trans_rollback();
        json_return("01", "상태변경오류. 관리자에게 문의하세요.");
      }
    } //for

    # 트랙젝션 완료
    $this->db->trans_commit();
    json_return("00", "처리완료");
  }

  function resume_propose_cancle()
  {
    $ru_idx = input_post("ru_idx", "");
    $rc_idx = input_post("rc_idx", "");

    # 트랙젝션 시작
    $this->db->trans_begin();

    $sql = "delete from st_member_request where mm_idx = '" . $this->user->getUserIdx() . "' and ru_idx = '" . $ru_idx . "' ";
    $query = $this->db->query($sql);
    $affectedRows = $this->db->affected_rows();

    if ($query && $affectedRows > 0) { //==1
      # 트랙젝션 완료
      $this->db->trans_commit();
      
      // MongoDB에서 채팅방 삭제 처리
      if (!empty($ru_idx) && !empty($rc_idx)) {
        try {
          $uri = get_mongo_uri();

          $dbName = get_mongo_db_name();
          @$mongo = new Mongo($uri);
          $database = $mongo->$dbName;
          $collection = $database->room;
          
          // ru_idx와 rc_idx로 채팅방 찾기
          $query = [
            'ru_idx' => (int)$ru_idx,
            'rc_idx' => (int)$rc_idx
          ];
          
          $room = $collection->findOne($query);
          
          if ($room) {
            // 채팅방 상태를 CLOSED로 변경 (삭제 대신 상태 변경)
            $update_data = ['$set' => ['rm_status' => 'CLOSED']];
            $collection->update($query, $update_data);
          }
        } catch (Exception $e) {
          // MongoDB 오류는 무시하고 계속 진행 (제안 취소는 성공으로 처리)
        }
      }
      
      json_return("00", "처리완료");
    } else {

      $this->db->trans_rollback();

      //오류로그
      $errsql = "insert into st_error values(null,'" . $this->user->getUserIdx() . "','resume_propose_cancle','ru_idx:" . $ru_idx . "',now(),'" . $_SERVER['REMOTE_ADDR'] . "','" . $_SERVER['HTTP_USER_AGENT'] . "') ";
      $errquery = $this->db->query($errsql);

      json_return("01", "삭제오류. 관리자에게 문의하세요.");
    }
  }

  // SmartEditor2 이미지 업로드 핸들러
  function se2_image_upload()
  {
    // 이미지 파일만 허용
    if (!isset($_FILES['Filedata'])) {
      header('Content-Type: application/json');
      echo json_encode([
        'success' => 0,
        'message' => '파일이 없습니다.'
      ]);
      return;
    }

    // 이미지 업로드 처리 (리사이징 적용: 800x800)
    $img = $this->common->img_upload_resize("editor", "Filedata", 800);

    header('Content-Type: application/json');

    if ($img["SUCCESS"] == true) {
      echo json_encode([
        'success' => 1,
        'url' => $img["URL"],
        'name' => $_FILES['Filedata']['name'],
        'width' => $img["WIDTH"],
        'height' => $img["HEIGHT"]
      ]);
    } else {
      echo json_encode([
        'success' => 0,
        'message' => '이미지 업로드에 실패했습니다.'
      ]);
    }
  }
}
