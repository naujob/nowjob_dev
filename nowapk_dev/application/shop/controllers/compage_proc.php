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
    if ($mem_info == null || (strpos($mem_info["mm_di"], "MC0") !== 0)) {
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
    $rc_show_yn = input_post("rc_show_yn", "");
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

    $rc_week = input_post("rc_week", "");
    $rc_week_yn = input_post("rc_week_yn", "");
    $rc_life_type = input_post("rc_life_type", "");
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = input_post("rc_desc", "");

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
    $rc_img1 = $this->common->img_upload_url("member", "rc_img1", 1200);
    $rc_img2 = $this->common->img_upload_url("member", "rc_img2", 1200);
    $rc_img3 = $this->common->img_upload_url("member", "rc_img3", 1200);
    $rc_img4 = $this->common->img_upload_url("member", "rc_img4", 1200);
    $rc_img5 = $this->common->img_upload_url("member", "rc_img5", 1200);
    $rc_thumb = $this->common->img_upload_url("member", "rc_img1", 600);

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

  # 스텝1(검토용)
  function recruit_write1_new()
  {
    # 데이터 추가.
    $rc_show_yn = input_post("rc_show_yn", "");
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
    $rc_week = input_post("rc_week", "");
    $rc_week_yn = input_post("rc_week_yn", "");

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

  # 스텝5(검토용)
  function recruit_write5_new()
  {
    # 데이터 추가.
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = input_post("rc_desc", "");

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
    $rc_img1 = $this->common->img_upload_url("member", "rc_img1", 1200);
    $rc_img2 = $this->common->img_upload_url("member", "rc_img2", 1200);
    $rc_img3 = $this->common->img_upload_url("member", "rc_img3", 1200);
    $rc_img4 = $this->common->img_upload_url("member", "rc_img4", 1200);
    $rc_img5 = $this->common->img_upload_url("member", "rc_img5", 1200);
    $rc_thumb = $this->common->img_upload_url("member", "rc_img1", 600);

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

      $arr_recruit = [
        "rc_show_yn" => $step1["rc_show_yn"],
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_nation" => arr_implode(",", $step2["rc_nation"]),
        "rc_sex" => arr_implode(",", $step2["rc_sex"]),
        "rc_sage" => $step2["rc_sage"],
        "rc_eage" => $step2["rc_eage"],
        "rc_work" => arr_implode(",", $step2["rc_work"]),
        "rc_work_type" => arr_implode(",", $step3["rc_work_type"]),
        "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
        "rc_week" => arr_implode(",", $step3["rc_week"]),
        "rc_am_stime" => $step3["rc_am_stime"],
        "rc_am_etime" => $step3["rc_am_etime"],
        "rc_pm_stime" => $step3["rc_pm_stime"],
        "rc_pm_etime" => $step3["rc_pm_etime"],
        "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
        "rc_price_type" => $step3["rc_price_type"],
        "rc_price_rep" => $step3["rc_price_rep"],
        "rc_price_pay" => $step3["rc_price_pay"],
        "rc_day_price" => $step3["rc_day_price"],
        "rc_week_price" => $step3["rc_week_price"],
        "rc_month_price" => $step3["rc_month_price"],
        "rc_life_type" => arr_implode(",", $step4["rc_life_type"]),
        "rc_desc" => $step5["rc_desc"],
        "rc_hash" => $step5["rc_hash"],
        "rc_desc" => $step5["rc_desc"],
        "rc_img1" => $step5["rc_img1"],
        "rc_img2" => $step5["rc_img2"],
        "rc_img3" => $step5["rc_img3"],
        "rc_img4" => $step5["rc_img4"],
        "rc_img5" => $step5["rc_img5"],
        "rc_thumb" => $step5["rc_thumb"],
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
        "ad_zipcode" => $step1["ad_zipcode"],
        "ad_jibun" => $step1["ad_jibun"],
        "ad_doro" => $step1["ad_doro"],
        "ad_detail" => $step1["ad_detail"],
        "ad_addr" => $step1["ad_addr"],
        "ad_gps_x" => $step1["ad_gps_x"],
        "ad_gps_y" => $step1["ad_gps_y"],
      ];

      $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx]);
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 수정시 오류가 발생하였습니다.");
      }

      # 기업 이미지 업데이트
      if ($step5["mm_img1"] != "") {
        $this->member_model->updateMember(["mm_img1" => $step5["mm_img1"]], ["mm_idx" => $this->mm_idx]);
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
      json_return("00", "수정 성공", ["alarm_list" => null]);
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

      $arr_recruit = [
        "mm_idx" => $this->mm_idx,
        "rc_show_yn" => $step1["rc_show_yn"],
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_nation" => arr_implode(",", $step2["rc_nation"]),
        "rc_sex" => arr_implode(",", $step2["rc_sex"]),
        "rc_sage" => $step2["rc_sage"],
        "rc_eage" => $step2["rc_eage"],
        "rc_work" => arr_implode(",", $step2["rc_work"]),
        "rc_work_type" => arr_implode(",", $step3["rc_work_type"]),
        "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
        "rc_week" => arr_implode(",", $step3["rc_week"]),
        "rc_am_stime" => $step3["rc_am_stime"],
        "rc_am_etime" => $step3["rc_am_etime"],
        "rc_pm_stime" => $step3["rc_pm_stime"],
        "rc_pm_etime" => $step3["rc_pm_etime"],
        "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
        "rc_price_type" => $step3["rc_price_type"],
        "rc_price_rep" => $step3["rc_price_rep"],
        "rc_price_pay" => $step3["rc_price_pay"],
        "rc_day_price" => $step3["rc_day_price"],
        "rc_week_price" => $step3["rc_week_price"],
        "rc_month_price" => $step3["rc_month_price"],
        "rc_life_type" => arr_implode(",", $step4["rc_life_type"]),
        "rc_desc" => $step5["rc_desc"],
        "rc_hash" => $step5["rc_hash"],
        "rc_desc" => $step5["rc_desc"],
        "rc_img1" => $step5["rc_img1"],
        "rc_img2" => $step5["rc_img2"],
        "rc_img3" => $step5["rc_img3"],
        "rc_img4" => $step5["rc_img4"],
        "rc_img5" => $step5["rc_img5"],
        "rc_thumb" => $step5["rc_thumb"],
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
      /*
        $query_recruit  = "select count(*) as cnt from st_recruit ";
        $query_recruit .= "where mm_idx = '".$this->mm_idx."' ";
        */
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
        "ad_zipcode" => $step1["ad_zipcode"],
        "ad_jibun" => $step1["ad_jibun"],
        "ad_doro" => $step1["ad_doro"],
        "ad_detail" => $step1["ad_detail"],
        "ad_addr" => $step1["ad_addr"],
        "ad_gps_x" => $step1["ad_gps_x"],
        "ad_gps_y" => $step1["ad_gps_y"],
      ];

      $result_addr = $this->member_model->insertAddress($arr_addr);
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 등록시 오류가 발생하였습니다.");
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

      # 기업 이미지 업데이트
      if ($step5["mm_img1"] != "") {
        $this->member_model->updateMember(["mm_img1" => $step5["mm_img1"]], ["mm_idx" => $this->mm_idx]);
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

  # 웹등록
  function recruit_write()
  {
    $rc_show_yn = input_post("rc_show_yn", "");
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

    $rc_week = input_post("rc_week", "");
    $rc_week_yn = input_post("rc_week_yn", "");
    $rc_life_type = input_post("rc_life_type", "");
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = input_post("rc_desc", "");

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
    $rc_img1 = $this->common->img_upload_url("member", "rc_img1", 1200);
    $rc_img2 = $this->common->img_upload_url("member", "rc_img2", 1200);
    $rc_img3 = $this->common->img_upload_url("member", "rc_img3", 1200);
    $rc_img4 = $this->common->img_upload_url("member", "rc_img4", 1200);
    $rc_img5 = $this->common->img_upload_url("member", "rc_img5", 1200);
    $rc_thumb = $this->common->img_upload_url("member", "rc_img1", 600);

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

  # 스텝1
  function recruit_write1()
  {
    # 데이터 추가.
    $rc_show_yn = input_post("rc_show_yn", "");
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
    $rc_week = input_post("rc_week", "");
    $rc_week_yn = input_post("rc_week_yn", "");

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

  # 스텝5
  function recruit_write5()
  {
    # 데이터 추가.
    $rc_hash = input_post("rc_hash", "");
    $rc_desc = input_post("rc_desc", "");

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
    $rc_img1 = $this->common->img_upload_url("member", "rc_img1", 1200);
    $rc_img2 = $this->common->img_upload_url("member", "rc_img2", 1200);
    $rc_img3 = $this->common->img_upload_url("member", "rc_img3", 1200);
    $rc_img4 = $this->common->img_upload_url("member", "rc_img4", 1200);
    $rc_img5 = $this->common->img_upload_url("member", "rc_img5", 1200);
    $rc_thumb = $this->common->img_upload_url("member", "rc_img1", 600);

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

  # 이력서 등록 완료
  function recruit_complete()
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

      $arr_recruit = [
        "rc_show_yn" => $step1["rc_show_yn"],
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_nation" => arr_implode(",", $step2["rc_nation"]),
        "rc_sex" => arr_implode(",", $step2["rc_sex"]),
        "rc_sage" => $step2["rc_sage"],
        "rc_eage" => $step2["rc_eage"],
        "rc_work" => arr_implode(",", $step2["rc_work"]),
        "rc_work_type" => arr_implode(",", $step3["rc_work_type"]),
        "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
        "rc_week" => arr_implode(",", $step3["rc_week"]),
        "rc_am_stime" => $step3["rc_am_stime"],
        "rc_am_etime" => $step3["rc_am_etime"],
        "rc_pm_stime" => $step3["rc_pm_stime"],
        "rc_pm_etime" => $step3["rc_pm_etime"],
        "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
        "rc_price_type" => $step3["rc_price_type"],
        "rc_price_rep" => $step3["rc_price_rep"],
        "rc_price_pay" => $step3["rc_price_pay"],
        "rc_day_price" => $step3["rc_day_price"],
        "rc_week_price" => $step3["rc_week_price"],
        "rc_month_price" => $step3["rc_month_price"],
        "rc_life_type" => arr_implode(",", $step4["rc_life_type"]),
        "rc_desc" => $step5["rc_desc"],
        "rc_hash" => $step5["rc_hash"],
        "rc_desc" => $step5["rc_desc"],
        "rc_img1" => $step5["rc_img1"],
        "rc_img2" => $step5["rc_img2"],
        "rc_img3" => $step5["rc_img3"],
        "rc_img4" => $step5["rc_img4"],
        "rc_img5" => $step5["rc_img5"],
        "rc_thumb" => $step5["rc_thumb"],
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
        "ad_zipcode" => $step1["ad_zipcode"],
        "ad_jibun" => $step1["ad_jibun"],
        "ad_doro" => $step1["ad_doro"],
        "ad_detail" => $step1["ad_detail"],
        "ad_addr" => $step1["ad_addr"],
        "ad_gps_x" => $step1["ad_gps_x"],
        "ad_gps_y" => $step1["ad_gps_y"],
      ];

      $result_addr = $this->member_model->updateAddress($arr_addr, ["ad_type" => "RECRUIT", "mm_idx" => $this->mm_idx, "ad_key" => $rc_idx]);
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 수정시 오류가 발생하였습니다.");
      }

      # 기업 이미지 업데이트
      if ($step5["mm_img1"] != "") {
        $this->member_model->updateMember(["mm_img1" => $step5["mm_img1"]], ["mm_idx" => $this->mm_idx]);
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
      json_return("00", "수정 성공", ["alarm_list" => null]);
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

      $arr_recruit = [
        "mm_idx" => $this->mm_idx,
        "rc_show_yn" => $step1["rc_show_yn"],
        "rc_name" => $step1["rc_name"],
        "rc_sdate" => $rc_sdate,
        "rc_edate" => $rc_edate,
        "rc_nation" => arr_implode(",", $step2["rc_nation"]),
        "rc_sex" => arr_implode(",", $step2["rc_sex"]),
        "rc_sage" => $step2["rc_sage"],
        "rc_eage" => $step2["rc_eage"],
        "rc_work" => arr_implode(",", $step2["rc_work"]),
        "rc_work_type" => arr_implode(",", $step3["rc_work_type"]),
        "rc_week_yn" => $step3["rc_week_yn"] == "Y" ? "Y" : "N",
        "rc_week" => arr_implode(",", $step3["rc_week"]),
        "rc_am_stime" => $step3["rc_am_stime"],
        "rc_am_etime" => $step3["rc_am_etime"],
        "rc_pm_stime" => $step3["rc_pm_stime"],
        "rc_pm_etime" => $step3["rc_pm_etime"],
        "rc_work_dt" => substr($step3["rc_work_dt"], 0, 10),
        "rc_price_type" => $step3["rc_price_type"],
        "rc_price_rep" => $step3["rc_price_rep"],
        "rc_price_pay" => $step3["rc_price_pay"],
        "rc_day_price" => $step3["rc_day_price"],
        "rc_week_price" => $step3["rc_week_price"],
        "rc_month_price" => $step3["rc_month_price"],
        "rc_life_type" => arr_implode(",", $step4["rc_life_type"]),
        "rc_desc" => $step5["rc_desc"],
        "rc_hash" => $step5["rc_hash"],
        "rc_desc" => $step5["rc_desc"],
        "rc_img1" => $step5["rc_img1"],
        "rc_img2" => $step5["rc_img2"],
        "rc_img3" => $step5["rc_img3"],
        "rc_img4" => $step5["rc_img4"],
        "rc_img5" => $step5["rc_img5"],
        "rc_thumb" => $step5["rc_thumb"],
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
      /*
        $query_recruit  = "select count(*) as cnt from st_recruit ";
        $query_recruit .= "where mm_idx = '".$this->mm_idx."' ";
        */
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
        "ad_zipcode" => $step1["ad_zipcode"],
        "ad_jibun" => $step1["ad_jibun"],
        "ad_doro" => $step1["ad_doro"],
        "ad_detail" => $step1["ad_detail"],
        "ad_addr" => $step1["ad_addr"],
        "ad_gps_x" => $step1["ad_gps_x"],
        "ad_gps_y" => $step1["ad_gps_y"],
      ];

      $result_addr = $this->member_model->insertAddress($arr_addr);
      if ($result_addr["code"] != "00") {
        $this->db->trans_rollback();
        json_return("92", "[02]채용공고 등록시 오류가 발생하였습니다.");
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

      # 기업 이미지 업데이트
      if ($step5["mm_img1"] != "") {
        $this->member_model->updateMember(["mm_img1" => $step5["mm_img1"]], ["mm_idx" => $this->mm_idx]);
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

      #####################################################
      # 알림톡(개인회원 대상) 발송 - 신규 채용공고 등록 완료 트리거
      #####################################################
      try {
        // PpurioClient 클래스 직접 로드
        $ppurioPath = (defined('SHAREDPATH') ? SHAREDPATH : APPPATH . 'common/') . 'libraries/PpurioClient.php';
        if (file_exists($ppurioPath)) {
          require_once($ppurioPath);
        }
        if (!class_exists('CI_PpurioClient')) {
          throw new Exception('PpurioClient class not found');
        }
        $ppurio = new CI_PpurioClient();

        // 템플릿 변수 구성 (개인회원 템플릿 요구사항)
        // 개인회원 템플릿: name=개인회원이름, var1=추천공고명, var2=추천공고 URL
        $personal_vars = array(
          isset($arr_recruit['rc_name']) ? $arr_recruit['rc_name'] : '',
          site_url('recruit/job_post/' . $result_recruit['key'])
        );

        // 대상: st_promotion 테이블에서 pm_name에 "추천인재등록"이 포함된 개인회원만
        $sql = "
          SELECT DISTINCT mb.mm_idx, mb.mm_id, mb.mm_phone, mb.mm_name
          FROM st_promotion pm
          INNER JOIN st_member mb ON pm.mm_idx = mb.mm_idx
          WHERE pm.pm_name LIKE '%추천인재등록%'
            AND pm.pm_status = 'Y'
            AND mb.mm_type = 'MEM'
            AND mb.mm_status = 'Y'
            AND mb.mm_phone != ''
            AND mb.mm_phone IS NOT NULL
        ";
        $query = $this->db->query($sql);
        $target_list = $query->result_array();

        $target_count = (!empty($target_list) && is_array($target_list)) ? count($target_list) : 0;
        error_log('[ALIMTALK] 채용공고 등록 완료 - 추천인재등록 대상 개인회원 수: ' . $target_count);

        if (!empty($target_list) && is_array($target_list)) {
          $sent_count = 0;
          $skip_count = 0;
          foreach ($target_list as $tgt) {
            $phone = isset($tgt['mm_phone']) ? trim($tgt['mm_phone']) : '';
            if ($phone != '') {
              $memberName = isset($tgt['mm_name']) ? trim($tgt['mm_name']) : '';
              error_log('[ALIMTALK] 개인회원 발송 시도 - mm_id=' . $tgt['mm_id'] . ', phone=' . $phone . ', name=' . $memberName);
              // name=개인회원 이름, var1=공고명, var2=공고URL
              $ppurio->sendPersonalAlimtalk($phone, $personal_vars, array('name' => $memberName));
              $sent_count++;
            } else {
              $skip_count++;
              error_log('[ALIMTALK] 건너뛰기 - 전화번호 없음: mm_id=' . $tgt['mm_id']);
            }
          }
          error_log('[ALIMTALK] 개인회원 발송 완료 - 총 대상: ' . $target_count . '명, 발송: ' . $sent_count . '명, 건너뛰기: ' . $skip_count . '명');
        } else {
          error_log('[ALIMTALK] 알림톡 발송 건너뛰기 - 추천인재등록 대상 개인회원 없음');
        }
      } catch (Exception $e) {
        error_log('[ALIMTALK] 발송 실패: ' . $e->getMessage());
        if (function_exists('log_message')) {
          log_message('error', 'Kakao Alimtalk send (recruit_complete) failed: ' . $e->getMessage());
        }
      }

      json_return("00", "등록 성공", ["target_name" => $mem_info["mm_comp_name"]]);
    }
  }

  # 이력서 삭제
  function recruit_delete()
  {
    $rc_idx = input_post("rc_idx");
    $result = $this->member_model->updateRecruit(["rc_status" => "N"], ["rc_idx" => $rc_idx]);
    json_return($result["code"], $result["msg"]);
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
      $result = $this->member_model->updateRecruit(["rc_edate" => date("Y-m-d", strtotime("-1 days")) . " 23:59:59"], ["rc_idx" => $idx]);
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
      $sql1 = "insert into st_recruit_del_history select * from st_recruit where rc_idx = '" . $idx . "' ";
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
      $result = $this->member_model->updateMember(["mm_comp_name" => $mm_comp_name], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_num" => $mm_comp_num], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_auth" => $mm_comp_auth], ["mm_idx" => $this->mm_idx]);
      $result = $this->member_model->updateMember(["mm_comp_type" => $mm_comp_type], ["mm_idx" => $this->mm_idx]);
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

    # 트랙젝션 시작
    $this->db->trans_begin();

    $sql = "delete from st_member_request where mm_idx = '" . $this->user->getUserIdx() . "' and ru_idx = '" . $ru_idx . "' ";
    $query = $this->db->query($sql);
    $affectedRows = $this->db->affected_rows();

    if ($query && $affectedRows > 0) { //==1
      # 트랙젝션 완료
      $this->db->trans_commit();
      json_return("00", "처리완료");
    } else {

      $this->db->trans_rollback();

      //오류로그
      $errsql = "insert into st_error values(null,'" . $this->user->getUserIdx() . "','resume_propose_cancle','ru_idx:" . $ru_idx . "',now(),'" . $_SERVER['REMOTE_ADDR'] . "','" . $_SERVER['HTTP_USER_AGENT'] . "') ";
      $errquery = $this->db->query($errsql);

      json_return("01", "삭제오류. 관리자에게 문의하세요.");
    }
  }
}
