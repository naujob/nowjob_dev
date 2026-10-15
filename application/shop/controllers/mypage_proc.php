<?php
class Mypage_proc extends CI_Controller
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

    $this->site_model->deleteSiteLog(
      [
        "session_id" => session_id(),
        "sl_type" => "POINT_ORDER",
        "sl_step" => "스텝1"
      ]
    );

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

    # 시작세션 생성
    $this->common->setSession("ORDER_PROC", "NOWJOB_STEP1");

    json_return("00", "처리완료");
  }

  function point_promotion()
  {
    # 데이터 추가.
    $pm_code = input_post("pm_code", "");
    $pm_sdate = input_post("pm_sdate", "");
    $pm_addr = input_post("pm_addr", "");
    $pm_edate = date("Y-m-d 23:59:59");
    $pm_resume = input_post("pm_resume", "");
    $pm_duration = input_post("pm_duration", "");
    $pm_usepoint = 0;

    # 데이터 조회
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $promotion_info = $this->setting_model->getCodeInfo(["cd_grp" => "광고구매", "cd_key" => $pm_code]);

    if ($promotion_info == null) {
      json_return("91", "처리할 데이터가 존재하지 않습니다.");
    }


    //if($mem_info["mm_point"] < $promotion_info["cd_val3"]){
    //	json_return("91","보유하신 포인트가 부족합니다.");
    //}

    if ($pm_code == "M001") {
      //# + 7일 처리
      //$pm_sdate = $pm_sdate . " 00:00:00";
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +14 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +29 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +44 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint = 2000;
      }
      if ($pm_duration == '5') {
        $pm_usepoint = 5000;
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
    } else if ($pm_code == "M002") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +14 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +29 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +44 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint = 1000;
      }
      if ($pm_duration == '5') {
        $pm_usepoint = 2500;
      }
      if ($pm_duration == '10') {
        $pm_usepoint = 5000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 10000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 15000;
      }
    } else if ($pm_code == "M003") {
      $pm_sdate = $pm_sdate . ":00";
      if ($pm_duration == '2') {
        $timestamp = strtotime($pm_sdate . " +2 days");
      }
      if ($pm_duration == '5') {
        $timestamp = strtotime($pm_sdate . " +6 days");
      }
      if ($pm_duration == '10') {
        $timestamp = strtotime($pm_sdate . " +14 days");
      }
      if ($pm_duration == '20') {
        $timestamp = strtotime($pm_sdate . " +29 days");
      }
      if ($pm_duration == '30') {
        $timestamp = strtotime($pm_sdate . " +44 days");
      }
      $pm_edate = date("Y-m-d H:i:59", $timestamp);

      if ($pm_duration == '2') {
        $pm_usepoint = 800;
      }
      if ($pm_duration == '5') {
        $pm_usepoint = 2000;
      }
      if ($pm_duration == '10') {
        $pm_usepoint = 4000;
      }
      if ($pm_duration == '20') {
        $pm_usepoint = 8000;
      }
      if ($pm_duration == '30') {
        $pm_usepoint = 12000;
      }
    }

    if ($mem_info["mm_point"] < $pm_usepoint) {
      json_return("91", "보유하신 포인트가 부족합니다.");
    }

    # 트랙젝션 시작
    $this->db->trans_begin();

    # 프로모션 데이터 등록
    $promotion = [
      "mm_idx" => $this->mm_idx,
      "pm_code" => $promotion_info["cd_key"],
      "pm_name" => $promotion_info["cd_val"] . "(" . $pm_duration . "일)",
      "pm_point" => $pm_usepoint,
      "pm_sdate" => $pm_sdate,
      "pm_edate" => $pm_edate,
      "pm_addr" => $pm_addr,
      "rc_idx" => $pm_resume,
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

  // (debug endpoint removed)

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
  function resume_start()
  {
    # 로그 삭제
    $this->site_model->deleteSiteLog([
      "mm_idx" => $this->mm_idx,
      "sl_type" => "RESUME"
    ]);

    $passAuthStat = "";
    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

    // SNS 로그인 여부 확인
    $is_sns_login = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER","GOOGLE","WECHAT"]);
    // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
    $is_authenticated = ($mem_info != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login));

    if (!$is_authenticated) {
      $passAuthStat = "N";
    } else {
      $passAuthStat = "Y";
    }

    json_return("00", "처리완료", ["passAuthStat" => $passAuthStat]);
  }

  # 웹용 이력서 저장
  function resume_write()
  {
    # 파라미터 처리
    $ru_name = input_post("ru_name", "");
    $ru_work = input_post("ru_work", "");
    $ru_addr = input_post("ru_addr", "");
    $ru_work_img = input_post("ru_work_img", "");
    $ru_price_type = input_post("ru_price_type", "");
    $ru_work_type = input_post("ru_work_type", "");
    $ru_nationality = input_post("ru_nationality", ""); // 국적 추가
    $ad_type = input_post("ad_type", "resume"); // st_address 테이블의 ad_type
    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");
    $arr_certi = input_post("arr_certi", "");
    $ru_desc = input_post("ru_desc", "");
    $ru_img1_ori = input_post("ru_img1_ori", "");
    $ru_img2_ori = input_post("ru_img2_ori", "");
    $ru_img3_ori = input_post("ru_img3_ori", "");
    $ru_img4_ori = input_post("ru_img4_ori", "");
    $ru_img5_ori = input_post("ru_img5_ori", "");
    $ru_edu = input_post("ru_edu", "");
    $ru_work_dt = input_post("ru_work_dt", "");
    $ru_week_yn = input_post("ru_week_yn", "N");
    $ru_week_yn = ($ru_week_yn == "" ? "N" : $ru_week_yn);
    $ru_week = input_post("ru_week", "");
    $arr_career = input_post("arr_career", "");
    // ru_my_type은 체크박스 배열이므로 $_POST에서 직접 가져오기
    $ru_my_type = isset($_POST["ru_my_type"]) ? (array)$_POST["ru_my_type"] : [];
    $ru_month_price = input_post("ru_month_price", "0");

    # 데이터 검증
    if ($ru_name == "") {
      json_return("99", "이력서 관리명이 확인되지 않습니다.");
    }

    if ($ru_work == "") {
        json_return("99", "희망업무 선택이 확인되지 않습니다.");
    }

    if ($ru_price_type == "") {
      json_return("99", "희망급여 선택이 확인되지 않습니다.");
    }

    if ($ru_work_type == "") {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    if ($ru_nationality == "") {
      json_return("99", "국적 선택이 확인되지 않습니다.");
    }

    //if($ru_week_yn=="N" && $ru_week==""){
    //  json_return("99","근무요일 선택이 확인되지 않습니다.");
    //}

    //if($ru_edu==""){
    //  json_return("99","최종학력 선택이 확인되지 않습니다.");
    //}

    //if($ru_work_dt==""){
    //  json_return("99","최종학력 선택이 확인되지 않습니다.");
    //}


    #로그 생성1
    $sl_txt = [
      "ru_name" => $ru_name,
      "ru_work" => $ru_work,
      "ru_addr" => $ru_addr,
      "ru_work_img" => $ru_work_img == "" ? [] : explode(",", $ru_work_img),
      "ru_price_type" => $ru_price_type,
      "ru_month_price" => $ru_month_price,
      "ru_work_type" => $ru_work_type,
      "ru_nationality" => $ru_nationality, // 국적 추가
      "ad_type" => $ad_type, // st_address 테이블의 ad_type
      "ad_zipcode" => $ad_zipcode,
      "ad_jibun" => $ad_jibun,
      "ad_doro" => $ad_doro,
      "ad_addr" => $ad_addr,
      "ad_gps_x" => $ad_gps_x,
      "ad_gps_y" => $ad_gps_y
    ];


    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝1"
      ]
    );


    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "session_id" => session_id(), "sl_type" => "RESUME", "sl_step" => "스텝4"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($ru_img1_ori != $json_info["ru_img1"]) {
          file_remove($json_info["ru_img1"]);
        }
        if ($ru_img2_ori != $json_info["ru_img2"]) {
          file_remove($json_info["ru_img2"]);
        }
        if ($ru_img3_ori != $json_info["ru_img3"]) {
          file_remove($json_info["ru_img3"]);
        }
        if ($ru_img4_ori != $json_info["ru_img4"]) {
          file_remove($json_info["ru_img4"]);
        }
        if ($ru_img5_ori != $json_info["ru_img5"]) {
          file_remove($json_info["ru_img5"]);
        }
      } catch (Exception $ex) {
      }
    }

    #로그 생성2
    $sl_txt = [
      "ru_edu" => $ru_edu,
      "ru_work_dt" => $ru_work_dt,
      "ru_week_yn" => $ru_week_yn,
      "ru_week" => $ru_week
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝2"
      ]
    );

    #로그 생성3
    // ru_my_type 배열을 문자열로 변환하여 저장
    $ru_my_type_str = "";
    if (is_array($ru_my_type) && count($ru_my_type) > 0) {
      $ru_my_type_str = implode(",", $ru_my_type);
    } elseif (!empty($ru_my_type) && !is_array($ru_my_type)) {
      $ru_my_type_str = $ru_my_type;
    }
    
    $sl_txt = [
      "arr_career" => $arr_career,
      "ru_my_type" => $ru_my_type_str
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝3",
        "sl_status" => "Y"
      ]
    );

    # 이미지 처리.
    $ru_img1 = $this->common->img_upload_resize_url("member", "ru_img1", 1200);
    $ru_img2 = $this->common->img_upload_resize_url("member", "ru_img2", 1200);
    $ru_img3 = $this->common->img_upload_resize_url("member", "ru_img3", 1200);
    $ru_img4 = $this->common->img_upload_resize_url("member", "ru_img4", 1200);
    $ru_img5 = $this->common->img_upload_resize_url("member", "ru_img5", 1200);
    $ru_thumb = $this->common->img_upload_resize_url("member", "ru_img1", 600);

    #로그 생성
    $sl_txt = [
      "arr_certi" => $arr_certi,
      "ru_desc" => $ru_desc,
      "ru_edu" => $ru_edu, // 최종학력도 스텝4에 저장 (스텝2와 중복 저장으로 안정성 향상)
      "ru_work_dt" => $ru_work_dt, // 근무희망일도 스텝4에 저장 (스텝2와 중복 저장으로 안정성 향상)
      "ru_week_yn" => $ru_week_yn, // 근무요일무관도 스텝4에 저장
      "ru_week" => $ru_week, // 근무요일도 스텝4에 저장
      "ru_img1" => $ru_img1,
      "ru_img2" => $ru_img2,
      "ru_img3" => $ru_img3,
      "ru_img4" => $ru_img4,
      "ru_img5" => $ru_img5,
      "ru_thumb" => $ru_thumb,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4"
      ]
    );


    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }

    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");

    $arr_mem = [
      "mm_name" => $mem_info["mm_name"],
      "mm_sex" => $mem_info["mm_sex"],
      "mm_nation" => $mem_info["mm_nation"],
      "mm_birth" => $mem_info["mm_birth"],
      "mm_phone" => $mem_info["mm_phone"],
      "mm_id" => $mem_info["mm_id"],
      "ru_addr" => $step1["ad_addr"],
      "ru_work" => $step1["ru_work"],
      "ru_work_img" => $step1["ru_work_img"],
      "ru_price_type" => $step1["ru_price_type"],
      "ru_work_type" => $step1["ru_work_type"],
      "ru_month_price" => $step1["ru_month_price"],
      "ru_week_yn" => $step2["ru_week_yn"],
      "ru_week" => $step2["ru_week"],
      "ru_edu" => $step2["ru_edu"],
      "ru_work_dt" => substr($step2["ru_work_dt"], 0, 10),
      "ru_work_dt_yoil" => get_yoil($step2["ru_work_dt"]),
      "arr_career" => $step3["arr_career"],
      "ru_my_type" => $step3["ru_my_type"],
      "arr_certi" => $step4["arr_certi"],
      "ru_desc" => $step4["ru_desc"],
      "ru_img1" => $step4["ru_img1"],
      "ru_img2" => $step4["ru_img2"],
      "ru_img3" => $step4["ru_img3"],
      "ru_img4" => $step4["ru_img4"],
      "ru_img5" => $step4["ru_img5"],
    ];

    json_return("00", "처리완료", $arr_mem);
  }

  # 스텝1
  function resume_write1()
  {
    # 데이터 추가.
    $ru_name = input_post("ru_name", "");

    // 배열 데이터는 $_POST에서 직접 가져오기 (CodeIgniter의 input->post는 [] 표기법을 제대로 처리하지 못함)
    $ru_work = isset($_POST["ru_work"]) ? (array)$_POST["ru_work"] : [];
    $ru_price_type = isset($_POST["ru_price_type"]) ? (array)$_POST["ru_price_type"] : [];
    $ru_work_type = isset($_POST["ru_work_type"]) ? (array)$_POST["ru_work_type"] : [];

    $ru_addr = input_post("ru_addr", "");
    $ru_work_img = input_post("ru_work_img", "");
    $ru_nationality = input_post("ru_nationality", "");
    $ru_salary_negotiation = input_post("ru_salary_negotiation", "N");
    $ru_work_negotiation = input_post("ru_work_negotiation", "N");
    $quickinfo_gender = input_post("quickinfo_gender", "");
    $quickinfo_age = input_post("quickinfo_age", "");

    // 주소 배열 데이터 처리
    $ad_zipcode = isset($_POST["ad_zipcode"]) ? (array)$_POST["ad_zipcode"] : [];
    $ad_jibun = isset($_POST["ad_jibun"]) ? (array)$_POST["ad_jibun"] : [];
    $ad_doro = isset($_POST["ad_doro"]) ? (array)$_POST["ad_doro"] : [];
    $ad_addr = isset($_POST["ad_addr"]) ? (array)$_POST["ad_addr"] : [];
    $ad_detail = isset($_POST["ad_detail"]) ? (array)$_POST["ad_detail"] : [];
    $ad_gps_x = isset($_POST["ad_gps_x"]) ? (array)$_POST["ad_gps_x"] : [];
    $ad_gps_y = isset($_POST["ad_gps_y"]) ? (array)$_POST["ad_gps_y"] : [];

    # 데이터 검증 (배열인 경우 count 체크)
    if ($ru_name == "" ) {
      json_return("99", "이력서 관리명이 확인되지 않습니다.");
    }

    if (empty($ru_work) || count($ru_work) == 0) {
      json_return("99", "희망업무 선택이 확인되지 않습니다.");
    }

    if (empty($ru_price_type) || count($ru_price_type) == 0) {
      json_return("99", "희망급여 선택이 확인되지 않습니다.");
    }

    if (empty($ru_work_type) || count($ru_work_type) == 0) {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }

    // 국적은 회원가입 때 받은 st_member.mm_nation 을 쓰므로 이력서 작성에서는 입력받지 않는다.
    // (빈값으로 넘어오면 resume_complete() 의 mm_nation 갱신 분기가 건너뛰어 기존 값이 유지된다)

    #로그 생성
    $sl_txt = [
      "ru_name" => $ru_name,
      "ru_work" => $ru_work,
      "ru_addr" => $ru_addr,
      "ru_work_img" => $ru_work_img == "" ? [] : explode(",", $ru_work_img),
      "ru_price_type" => $ru_price_type,
      "ru_work_type" => $ru_work_type,
      "ru_nationality" => $ru_nationality,
      "quickinfo_gender" => $quickinfo_gender,
      "quickinfo_age" => $quickinfo_age,
      "ru_salary_negotiation" => $ru_salary_negotiation,
      "ru_work_negotiation" => $ru_work_negotiation,
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
        "sl_type" => "RESUME",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝1"
      ]
    );
    json_return("00", "처리완료");
  }

  # QuickInfo 자동이력서: 서버에서 스텝1/4 저장 후 완료 처리
  function resume_autofill_complete()
  {
    // 기본 입력값
    $ru_name = input_post("ru_name", "");
    $ru_addr = input_post("ru_addr", "");
    $ru_nationality = input_post("ru_nationality", "");
    $ru_salary_negotiation = input_post("ru_salary_negotiation", "Y");
    $ru_work_negotiation = input_post("ru_work_negotiation", "Y");
    $quickinfo_gender = input_post("quickinfo_gender", "");
    $quickinfo_age = input_post("quickinfo_age", "");
    $ad_type = input_post("ad_type", "resume"); // st_address 테이블의 ad_type (기본값: resume)

    // 배열 입력값
    $ru_work = isset($_POST["ru_work"]) ? (array)$_POST["ru_work"] : [];
    $ru_price_type = isset($_POST["ru_price_type"]) ? (array)$_POST["ru_price_type"] : [];
    $ru_work_type = isset($_POST["ru_work_type"]) ? (array)$_POST["ru_work_type"] : [];

    $ad_zipcode = isset($_POST["ad_zipcode"]) ? (array)$_POST["ad_zipcode"] : [];
    $ad_jibun = isset($_POST["ad_jibun"]) ? (array)$_POST["ad_jibun"] : [];
    $ad_doro = isset($_POST["ad_doro"]) ? (array)$_POST["ad_doro"] : [];
    $ad_addr = isset($_POST["ad_addr"]) ? (array)$_POST["ad_addr"] : [];
    $ad_detail = isset($_POST["ad_detail"]) ? (array)$_POST["ad_detail"] : [];
    $ad_gps_x = isset($_POST["ad_gps_x"]) ? (array)$_POST["ad_gps_x"] : [];
    $ad_gps_y = isset($_POST["ad_gps_y"]) ? (array)$_POST["ad_gps_y"] : [];

    // 주소 텍스트 기본값 보정
    if ($ru_addr == "") {
      if (isset($ad_addr[0]) && $ad_addr[0] != "") {
        $ru_addr = $ad_addr[0];
      }
    }

    // 기본값 자동 채움
    if (empty($ru_work)) {
      $ru_work = ["생산", "물류"];
    }
    if (empty($ru_price_type)) {
      $ru_price_type = ["일급", "월급"];
    }
    if (empty($ru_work_type)) {
      $type_list = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
      foreach ($type_list as $row) {
        if (isset($row["cd_val"])) $ru_work_type[] = $row["cd_val"];
        if (count($ru_work_type) >= 2) break;
      }
    }
    if ($ru_nationality == "") {
      $ru_nationality = "내국인";
    }
    if ($ru_name == "" || $ru_name == $ru_addr) {
    $seed = $ru_addr != "" ? $ru_addr : "우리 동네";
    $title_templates = [
      "[지역명]준비된 인재입니다!",
      "[지역명]바로 출근 가능합니다.",
      "[지역명]추천인재",
      "열심히 할수있습니다!",
      "최선을 다하는 인재이력서",
      "성실함과 꾸준함을 갖춘 인재",
      "책임감있는 최고의 인재",
      "찾고있는[지역명]의 인재",
      "출근준비되어있습니다.",
      "즉시줄근가능!추천인재입니다.",
      "바로 출근부터 면접까지!",
      "[지역명]에서 일찾는중",
      "[지역명]근처 일자리 찾는중"
    ];
    $pick_idx = 0;
    if (!empty($title_templates)) {
      $max_idx = count($title_templates) - 1;
      if (function_exists('random_int')) {
        $pick_idx = random_int(0, $max_idx);
      } else {
        $pick_idx = mt_rand(0, $max_idx);
      }
    }
    $template = $title_templates[$pick_idx];
    $ru_name = str_replace("[지역명]", $seed, $template);
    }

    // 최소 유효성 검사
    if ($ru_name == "") {
      json_return("99", "이력서 관리명이 확인되지 않습니다.");
    }
    if (empty($ru_work)) {
      json_return("99", "희망업무 선택이 확인되지 않습니다.");
    }
    if (empty($ru_price_type)) {
      json_return("99", "희망급여 선택이 확인되지 않습니다.");
    }
    if (empty($ru_work_type)) {
      json_return("99", "근무형태 선택이 확인되지 않습니다.");
    }
    if ($ru_nationality == "") {
      json_return("99", "국적 선택이 확인되지 않습니다.");
    }

    // 희망업무 아이콘 경로를 코드테이블(cd_val -> cd_val2)에서 서버가 직접 조회
    // 클라이언트가 보낸 경로를 믿지 않으므로 위변조 여지가 없다
    $ru_work_img = [];
    $work_code_list = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
    foreach ($ru_work as $work_val) {
      foreach ($work_code_list as $code_row) {
        if (isset($code_row["cd_val"]) && $code_row["cd_val"] == $work_val) {
          $ru_work_img[] = isset($code_row["cd_val2"]) ? $code_row["cd_val2"] : "";
          break;
        }
      }
    }

    // 스텝1 로그 저장
    $sl_txt_step1 = [
      "ru_name" => $ru_name,
      "ru_work" => $ru_work,
      "ru_addr" => $ru_addr,
      "ru_work_img" => $ru_work_img,
      "ru_price_type" => $ru_price_type,
      "ru_work_type" => $ru_work_type,
      "ru_nationality" => $ru_nationality,
      "quickinfo_gender" => $quickinfo_gender,
      "quickinfo_age" => $quickinfo_age,
      "ru_salary_negotiation" => $ru_salary_negotiation,
      "ru_work_negotiation" => $ru_work_negotiation,
      "ad_type" => $ad_type,
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
        "sl_type" => "RESUME",
        "sl_step" => "스텝1",
        "sl_txt" => json_encode($sl_txt_step1, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝1"
      ]
    );

    // 스텝4 기본값 생성
    $ru_desc = input_post("ru_desc", "");
    if ($ru_desc == "") $ru_desc = $ru_name;
    $ru_week_yn = input_post("ru_week_yn", "N");
    $ru_week = input_post("ru_week", "월,화,수,목,금");
    // 최종학력은 요청값 그대로 사용 (미선택 시 빈 값 유지, 기본값을 넣지 않음)
    $ru_edu = input_post("ru_edu", "");
    $ru_my_type = input_post("ru_my_type", []);
    if (empty($ru_my_type)) {
      $type_list = $this->site_model->getCodeList(["cd_grp" => "나의강점"]);
      $ru_my_type = [];
      foreach ($type_list as $row) {
        if (isset($row["cd_val"])) $ru_my_type[] = $row["cd_val"];
        if (count($ru_my_type) >= 2) break;
      }
    }

    $sl_txt_step4 = [
      "arr_certi" => "",
      "arr_career" => "",
      "ru_desc" => $ru_desc,
      "ru_my_type" => $ru_my_type,
      "ru_week_yn" => $ru_week_yn,
      "ru_week" => $ru_week,
      "ru_edu" => $ru_edu,
      "ru_work_dt" => "",
      "ru_img1" => "",
      "ru_img2" => "",
      "ru_img3" => "",
      "ru_img4" => "",
      "ru_img5" => "",
      "ru_thumb" => "",
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt_step4, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4"
      ]
    );

    if (!isset($_POST["ru_idx"])) {
      $_POST["ru_idx"] = 0;
    }
    return $this->resume_complete();
  }

  // 자동완성 전용 엔드포인트 (resume_autofill_complete 래핑)
  function resume_autofill_quick()
  {
    return $this->resume_autofill_complete();
  }

  # 스텝2
  function resume_write2()
  {
    # 데이터 추가.
    $ru_edu = input_post("ru_edu", "");
    $ru_work_dt = input_post("ru_work_dt", "");
    $ru_week_yn = input_post("ru_week_yn", "N");
    $ru_week_yn = ($ru_week_yn == "" ? "N" : $ru_week_yn);
    $ru_week = input_post("ru_week", "");

    if ($ru_week_yn == "N" && $ru_week == "") {
      json_return("99", "근무요일 선택이 확인되지 않습니다.");
    }

    if ($ru_edu == "") {
      json_return("99", "최종학력 선택이 확인되지 않습니다.");
    }

    if ($ru_work_dt == "") {
      json_return("99", "최종학력 선택이 확인되지 않습니다.");
    }

    #로그 생성
    $sl_txt = [
      "ru_edu" => $ru_edu,
      "ru_work_dt" => $ru_work_dt,
      "ru_week_yn" => $ru_week_yn,
      "ru_week" => $ru_week
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝2",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝2"
      ]
    );
    json_return("00", "처리완료");
  }

  # 스텝3
  function resume_write3()
  {
    # 데이터 추가.
    $arr_career = input_post("arr_career", "");
    $ru_my_type = input_post("ru_my_type", "");

    #로그 생성
    $sl_txt = [
      "arr_career" => $arr_career,
      "ru_my_type" => $ru_my_type
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝3",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝3",
        "sl_status" => "Y"
      ]
    );
    json_return("00", "처리완료");
  }

  # 스텝4
  function resume_write4()
  {
    # 데이터 추가.
    $arr_certi = input_post("arr_certi", "");
    $arr_career = input_post("arr_career", "");
    $ru_desc = input_post("ru_desc", "");
    $ru_my_type = input_post("ru_my_type", "");

    // ru_my_type이 배열인 경우 문자열로 변환
    if (is_array($ru_my_type)) {
      $ru_my_type = arr_implode(",", $ru_my_type);
    }
    $ru_week_yn = input_post("ru_week_yn", "N");
    $ru_week_yn = ($ru_week_yn == "" ? "N" : $ru_week_yn);
    $ru_week = input_post("ru_week", "");
    $ru_edu = input_post("ru_edu", "");
    $ru_work_dt = input_post("ru_work_dt", "");

    $ru_img1_ori = input_post("ru_img1_ori", "");
    $ru_img2_ori = input_post("ru_img2_ori", "");
    $ru_img3_ori = input_post("ru_img3_ori", "");
    $ru_img4_ori = input_post("ru_img4_ori", "");
    $ru_img5_ori = input_post("ru_img5_ori", "");

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "session_id" => session_id(), "sl_type" => "RESUME", "sl_step" => "스텝4"]
    );

    if ($log_info != null) {
      try {
        $json_info = json_decode($log_info["sl_txt"], true);
        if ($ru_img1_ori != $json_info["ru_img1"]) {
          file_remove($json_info["ru_img1"]);
        }
        if ($ru_img2_ori != $json_info["ru_img2"]) {
          file_remove($json_info["ru_img2"]);
        }
        if ($ru_img3_ori != $json_info["ru_img3"]) {
          file_remove($json_info["ru_img3"]);
        }
        if ($ru_img4_ori != $json_info["ru_img4"]) {
          file_remove($json_info["ru_img4"]);
        }
        if ($ru_img5_ori != $json_info["ru_img5"]) {
          file_remove($json_info["ru_img5"]);
        }
      } catch (Exception $ex) {
      }
    }

    # 이미지 처리.
    $ru_img1 = $this->common->img_upload_resize_url("member", "ru_img1", 1200);
    $ru_img2 = $this->common->img_upload_resize_url("member", "ru_img2", 1200);
    $ru_img3 = $this->common->img_upload_resize_url("member", "ru_img3", 1200);
    $ru_img4 = $this->common->img_upload_resize_url("member", "ru_img4", 1200);
    $ru_img5 = $this->common->img_upload_resize_url("member", "ru_img5", 1200);
    $ru_thumb = $this->common->img_upload_resize_url("member", "ru_img1", 600);

    #로그 생성
    $sl_txt = [
      "arr_certi" => $arr_certi,
      "arr_career" => $arr_career,
      "ru_desc" => $ru_desc,
      "ru_my_type" => $ru_my_type,
      "ru_week_yn" => $ru_week_yn,
      "ru_week" => $ru_week,
      "ru_edu" => $ru_edu,
      "ru_work_dt" => $ru_work_dt,
      "ru_img1" => $ru_img1,
      "ru_img2" => $ru_img2,
      "ru_img3" => $ru_img3,
      "ru_img4" => $ru_img4,
      "ru_img5" => $ru_img5,
      "ru_thumb" => $ru_thumb,
    ];

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4",
        "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
      ],
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝4"
      ]
    );


    # 전체 등록 파일 조회 후 리턴
    $return_data = [];
    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "session_id" => session_id(), "sl_type" => "RESUME"]
    );

    if ($list == null) {
      json_return("10", "등록 데이터가 존재하지 않습니다.", $list);
    }


    $mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step4 = $this->site_model->getStepData($list, "스텝4");
    // 회원 정보와 이력서 스텝 데이터를 하나의 배열로 합치기. 데이터가 없는 경우 기본값 처리.
    $arr_mem = [
      "mm_name" => $mem_info["mm_name"],
      "mm_sex" => $mem_info["mm_sex"],
      "mm_nation" => $mem_info["mm_nation"],
      "mm_birth" => $mem_info["mm_birth"],
      "mm_phone" => $mem_info["mm_phone"],
      "mm_id" => $mem_info["mm_id"],
      "ru_addr" => isset($step1["ad_addr"]) ? $step1["ad_addr"] : "",
      "ru_work" => isset($step1["ru_work"]) ? $step1["ru_work"] : "",
      "ru_work_img" => isset($step1["ru_work_img"]) ? $step1["ru_work_img"] : "",
      "ru_price_type" => isset($step1["ru_price_type"]) ? $step1["ru_price_type"] : "",
      "ru_work_type" => isset($step1["ru_work_type"]) ? $step1["ru_work_type"] : "",
      // ru_month_price는 step1에 저장되지 않고 입력도 받지 않으므로 기본값 "0" 처리
      "ru_month_price" => "0",
      // step2, step3 제거 → step4에서 가져오기
      "ru_week_yn" => isset($step4["ru_week_yn"]) ? $step4["ru_week_yn"] : "N",
      "ru_week" => isset($step4["ru_week"]) ? $step4["ru_week"] : "",
      "ru_edu" => isset($step4["ru_edu"]) ? $step4["ru_edu"] : "",
      "ru_work_dt" => isset($step4["ru_work_dt"]) ? substr($step4["ru_work_dt"], 0, 10) : "",
      "ru_work_dt_yoil" => isset($step4["ru_work_dt"]) ? get_yoil($step4["ru_work_dt"]) : "",
      "arr_career" => isset($step4["arr_career"]) ? $step4["arr_career"] : "",
      "ru_my_type" => isset($step4["ru_my_type"]) ? $step4["ru_my_type"] : "",
      "arr_certi" => isset($step4["arr_certi"]) ? $step4["arr_certi"] : "",
      "ru_desc" => isset($step4["ru_desc"]) ? $step4["ru_desc"] : "",
      "ru_img1" => isset($step4["ru_img1"]) ? $step4["ru_img1"] : "",
      "ru_img2" => isset($step4["ru_img2"]) ? $step4["ru_img2"] : "",
      "ru_img3" => isset($step4["ru_img3"]) ? $step4["ru_img3"] : "",
      "ru_img4" => isset($step4["ru_img4"]) ? $step4["ru_img4"] : "",
      "ru_img5" => isset($step4["ru_img5"]) ? $step4["ru_img5"] : "",
    ];
    //Front-end의 미리보기 팝업에서 사용하기 위해 배열로 반환 처리
    json_return("00", "처리완료", $arr_mem);
  }

  # 이력서 등록 완료
  function resume_complete()
  {

    $ru_idx = input_post("ru_idx", 0);
    $preview_mode = input_post("preview_mode", 0);

    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "session_id" => session_id(), "sl_type" => "RESUME"]
    );

    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2"); // 최종학력, 근무희망일은 스텝2에 저장됨
    $step3 = $this->site_model->getStepData($list, "스텝3"); // 간단경력 데이터는 스텝3에 저장됨
    $step4 = $this->site_model->getStepData($list, "스텝4");

    # step1, step4 데이터 유효성 검사
    if (!is_array($step1) || !isset($step1["ru_name"])) {
      json_return("99", "스텝1 데이터가 존재하지 않습니다.");
    }
    if (!is_array($step4) || !isset($step4["ru_desc"])) {
      json_return("99", "스텝4 데이터가 존재하지 않습니다.");
    }

    // QuickInfo 성별/나이(연령대) -> 생년월일 변환
    // 변환 규칙은 data_helper의 getBirthByAgeGroup()으로 일원화 (70대 포함, YYYYMMDD 형식)
    $quickinfo_gender = isset($step1["quickinfo_gender"]) ? $step1["quickinfo_gender"] : "";
    $quickinfo_age = isset($step1["quickinfo_age"]) ? $step1["quickinfo_age"] : "";
    $quickinfo_birth = getBirthByAgeGroup($quickinfo_age);
    // 서버에 이미 저장된 생년월일(카카오/네이버/본인인증 등)이 있으면 QuickInfo 연령대 값으로 덮어쓰지 않음
    // 연령대 값은 월일이 임의로 채워지므로, 서버 값(연도만 있는 YYYY0000 포함)이 비어 있을 때만 사용
    if ($quickinfo_birth != "") {
      $cur_mem = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
      $cur_birth = isset($cur_mem["mm_birth"]) ? trim($cur_mem["mm_birth"]) : "";
      if ($cur_birth != "") {
        $quickinfo_birth = "";
      }
    }

    # 이력서 갯수 확인후 처리
    $count = $this->member_model->getResumeCount(["mm_idx" => $this->mm_idx, "ru_status" => "Y"]);
    if ($ru_idx == 0 && $count >= 5) {
      //2024.09.01 5개 제한 해제 -> 09.04 그런 의미가 아니라고 해서 적용
      return json_return("90", "이력서는 최대 5개까지만 등록 가능합니다.");
    }

    //실명인증여부 확인 24.09.01
    //$mem_info = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
    ////return json_return("90","실명인증.".$mem_info["mm_di"]);
    //if(substr($mem_info["mm_di"],0,2) != "MC"){
    //  return json_return("91","실명인증 후 이력서를 저장하실 수 있습니다.");
    //}

    if ($ru_idx != 0) {
      if ($preview_mode == 1) {
        json_return("00", "미리보기 저장이 완료되었습니다.", ["ru_idx" => $ru_idx]);
      }
      # 트랜젝션 시작
      $this->db->trans_start();
      // 이력서 수정 처리
      # 데이터 처리
      // ru_work_dt: timestamp 필드이므로 빈 문자열일 경우 NULL로 처리 (스텝2에 저장되므로 스텝2에서 먼저 확인)
      $ru_work_dt = (isset($step2["ru_work_dt"]) && trim($step2["ru_work_dt"]) != "") ? $step2["ru_work_dt"] : ((isset($step4["ru_work_dt"]) && trim($step4["ru_work_dt"]) != "") ? $step4["ru_work_dt"] : null);
      // ru_month_price: int 필드이므로 빈 문자열일 경우 0으로 처리
      $ru_month_price = isset($step1["ru_month_price"]) && $step1["ru_month_price"] != "" ? intval($step1["ru_month_price"]) : 0;

      // ru_week: 빈값일 경우 기본값(월~금) 설정
      $ru_week_yn = isset($step4["ru_week_yn"]) ? $step4["ru_week_yn"] : (isset($step2["ru_week_yn"]) ? $step2["ru_week_yn"] : "N");
      $ru_week = isset($step4["ru_week"]) ? $step4["ru_week"] : (isset($step2["ru_week"]) ? $step2["ru_week"] : "");
      if (is_array($ru_week)) {
        $ru_week = arr_implode(",", $ru_week);
      }
      // 요일무관이 아니고 근무요일이 빈값일 경우 기본값(월~금) 설정
      if ($ru_week_yn != "Y" && (empty($ru_week) || trim((string) $ru_week) == "")) {
        $ru_week = "월,화,수,목,금";
      }

      // 최종학력: 스텝2에 저장되므로 스텝2에서 먼저 확인, 없으면 스텝4에서 확인
      $ru_edu = isset($step2["ru_edu"]) && trim($step2["ru_edu"]) != "" ? $step2["ru_edu"] : (isset($step4["ru_edu"]) && trim($step4["ru_edu"]) != "" ? $step4["ru_edu"] : "");

      $arr_resume = [
        // 기본 정보 (NOT NULL)
        "ru_name" => isset($step1["ru_name"]) ? $step1["ru_name"] : "",
        "ru_work" => isset($step1["ru_work"]) && is_array($step1["ru_work"]) ? arr_implode(",", $step1["ru_work"]) : (isset($step1["ru_work"]) ? $step1["ru_work"] : ""),
        "ru_work_img" => isset($step1["ru_work_img"]) && is_array($step1["ru_work_img"]) ? arr_implode(",", $step1["ru_work_img"]) : (isset($step1["ru_work_img"]) ? $step1["ru_work_img"] : ""),
        "ru_addr" => isset($step1["ru_addr"]) ? $step1["ru_addr"] : "",
        "ru_price_type" => isset($step1["ru_price_type"]) && is_array($step1["ru_price_type"]) ? arr_implode(",", $step1["ru_price_type"]) : (isset($step1["ru_price_type"]) ? $step1["ru_price_type"] : ""),
        "ru_work_type" => isset($step1["ru_work_type"]) && is_array($step1["ru_work_type"]) ? arr_implode(",", $step1["ru_work_type"]) : (isset($step1["ru_work_type"]) ? $step1["ru_work_type"] : ""),
        "ru_month_price" => $ru_month_price,
        "ru_work_dt" => $ru_work_dt,
        "ru_edu" => $ru_edu,
        // 자기소개 관련 (NOT NULL)
        // ru_my_type은 step3 또는 step4에 저장됨 (모바일은 step4, 웹은 step3)
        "ru_my_type" => (isset($step3["ru_my_type"]) && trim((string)$step3["ru_my_type"]) != "") ? (is_array($step3["ru_my_type"]) ? arr_implode(",", $step3["ru_my_type"]) : $step3["ru_my_type"]) : ((isset($step4["ru_my_type"]) && trim((string)$step4["ru_my_type"]) != "") ? $step4["ru_my_type"] : ""),
        "ru_desc" => isset($step4["ru_desc"]) ? $step4["ru_desc"] : "",
        // 이미지 관련 (NOT NULL)
        "ru_thumb" => isset($step4["ru_thumb"]) ? $step4["ru_thumb"] : "",
        "ru_img1" => isset($step4["ru_img1"]) ? $step4["ru_img1"] : "",
        "ru_img2" => isset($step4["ru_img2"]) ? $step4["ru_img2"] : "",
        "ru_img3" => isset($step4["ru_img3"]) ? $step4["ru_img3"] : "",
        "ru_img4" => isset($step4["ru_img4"]) ? $step4["ru_img4"] : "",
        "ru_img5" => isset($step4["ru_img5"]) ? $step4["ru_img5"] : "",
        // 기타 상태 관련 (NOT NULL)
        "ru_week_yn" => $ru_week_yn,
        "ru_week" => $ru_week,
        "ru_ip" => $this->common->getIpAddr(),
        // 선택 필드 (데이터베이스 테이블에 컬럼이 존재하는 경우에만 사용)
        // "ru_nationality" => isset($step1["ru_nationality"]) ? $step1["ru_nationality"] : "",
        // "ru_salary_negotiation" => isset($step1["ru_salary_negotiation"]) ? $step1["ru_salary_negotiation"] : "N",
        // "ru_work_negotiation" => isset($step1["ru_work_negotiation"]) ? $step1["ru_work_negotiation"] : "N",
      ];

      $result_resume = $this->member_model->updateResume($arr_resume, ["ru_idx" => $ru_idx]);

      if ($result_resume["code"] == "00") {
        // 이력서 수정 시 회원의 국적 정보도 함께 업데이트
        if ((isset($step1["ru_nationality"]) && $step1["ru_nationality"] != "") || $quickinfo_gender != "" || $quickinfo_birth != "") {
          $member_update = [];
          if (isset($step1["ru_nationality"]) && $step1["ru_nationality"] != "") {
            $member_update["mm_nation"] = $step1["ru_nationality"];
          }
          if ($quickinfo_gender != "") {
            $member_update["mm_sex"] = $quickinfo_gender;
          }
          if ($quickinfo_birth != "") {
            $member_update["mm_birth"] = $quickinfo_birth;
          }
          if (!empty($member_update)) {
            $this->member_model->updateMember($member_update, ["mm_idx" => $this->mm_idx]);
          }
        }

        $this->member_model->deleteResumeCareer(["ru_idx" => $ru_idx]);
        // 간단경력 데이터는 스텝3에 저장되므로 스텝3에서 먼저 확인, 없으면 스텝4에서 확인
        $arr_career = isset($step3["arr_career"]) && $step3["arr_career"] != "" ? $step3["arr_career"] : (isset($step4["arr_career"]) && $step4["arr_career"] != "" ? $step4["arr_career"] : "");
        if ($arr_career != "") {
          $arr_temp = explode("&&&", $arr_career); // explode("자르는기준", "원래글자") 상자(배열)로 만드는 코드
          foreach ($arr_temp as $row) {
            if (trim($row) == "") continue;
            $arr_data = explode("|||", $row);
            if (count($arr_data) < 2) continue;
            $info = [
              "ru_idx" => $ru_idx,
              "rc_type" => "",
              "rc_name" => isset($arr_data[0]) ? $arr_data[0] : "",
              "rc_date" => isset($arr_data[1]) ? $arr_data[1] : "",
            ];

            $result_career = $this->member_model->insertResumeCareer($info);
            if ($result_career["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }

        $this->member_model->deleteResumeCerti(["ru_idx" => $ru_idx]);
        if (isset($step4["arr_certi"]) && $step4["arr_certi"] != "") {
          $arr_temp = explode("&&&", $step4["arr_certi"]);
          foreach ($arr_temp as $row) {
            if (trim($row) == "") continue;
            $arr_data = explode("|||", $row);
            if (count($arr_data) < 2) continue;
            $info = [
              "ru_idx" => $ru_idx,
              "rc_name" => isset($arr_data[0]) ? $arr_data[0] : "",
              "rc_dt" => isset($arr_data[1]) ? $arr_data[1] : "",
            ];

            $result_certi = $this->member_model->insertResumeCerti($info);
            if ($result_certi["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }


        # 지역등록
        // POST 파라미터 우선, step1 fallback, 최종 기본값 "RESUME"
        $ad_type_post = input_post("ad_type", "");
        $ad_type_step1 = isset($step1["ad_type"]) ? $step1["ad_type"] : "";
        $ad_type = !empty($ad_type_post) ? strtoupper($ad_type_post) : (!empty($ad_type_step1) ? strtoupper($ad_type_step1) : "RESUME");

        // 주소 필드: 문자열이면 배열로 변환, 빈값이면 빈 배열
        $ad_zipcode_raw = isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "";
        $ad_zipcode = is_array($ad_zipcode_raw) ? $ad_zipcode_raw : ($ad_zipcode_raw != "" ? [$ad_zipcode_raw] : []);

        $ad_jibun_raw = isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "";
        $ad_jibun = is_array($ad_jibun_raw) ? $ad_jibun_raw : ($ad_jibun_raw != "" ? [$ad_jibun_raw] : []);

        $ad_doro_raw = isset($step1["ad_doro"]) ? $step1["ad_doro"] : "";
        $ad_doro = is_array($ad_doro_raw) ? $ad_doro_raw : ($ad_doro_raw != "" ? [$ad_doro_raw] : []);

        $ad_addr_raw = isset($step1["ad_addr"]) ? $step1["ad_addr"] : "";
        $ad_addr = is_array($ad_addr_raw) ? $ad_addr_raw : ($ad_addr_raw != "" ? [$ad_addr_raw] : []);

        $ad_detail_raw = isset($step1["ad_detail"]) ? $step1["ad_detail"] : "";
        $ad_detail = is_array($ad_detail_raw) ? $ad_detail_raw : ($ad_detail_raw != "" ? [$ad_detail_raw] : []);

        $ad_gps_x_raw = isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "";
        $ad_gps_x = is_array($ad_gps_x_raw) ? $ad_gps_x_raw : ($ad_gps_x_raw != "" ? [$ad_gps_x_raw] : []);

        $ad_gps_y_raw = isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "";
        $ad_gps_y = is_array($ad_gps_y_raw) ? $ad_gps_y_raw : ($ad_gps_y_raw != "" ? [$ad_gps_y_raw] : []);

        $this->member_model->deleteAddress(["ad_type" => $ad_type, "mm_idx" => $this->mm_idx, "ad_key" => $ru_idx]);
        if (!empty($ad_addr) && isset($ad_addr[0]) && $ad_addr[0] != "") {
          foreach ($ad_addr as $idx => $row) {
            $arr_addr = [
              "mm_idx" => $this->mm_idx,
              "ad_type" => $ad_type,
              "ad_key" => $ru_idx,
              "ad_zipcode" => isset($ad_zipcode[$idx]) ? $ad_zipcode[$idx] : "",
              "ad_jibun" => isset($ad_jibun[$idx]) ? $ad_jibun[$idx] : "",
              "ad_doro" => isset($ad_doro[$idx]) ? $ad_doro[$idx] : "",
              "ad_detail" => isset($ad_detail[$idx]) ? $ad_detail[$idx] : "",
              "ad_addr" => isset($ad_addr[$idx]) ? $ad_addr[$idx] : "",
              "ad_gps_x" => isset($ad_gps_x[$idx]) ? $ad_gps_x[$idx] : "",
              "ad_gps_y" => isset($ad_gps_y[$idx]) ? $ad_gps_y[$idx] : "",
            ];

            $result_addr = $this->member_model->insertAddress($arr_addr);
            if ($result_addr["code"] != "00") {
              $this->db->trans_rollback();
              json_return("92", "[02]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }

        $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]);

        // 트랜잭션 완료 및 상태 확인
        $this->db->trans_complete();

        // 트랜잭션 실패 확인
        if ($this->db->trans_status() === FALSE) {
          return json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
        }

        // UPDATE 케이스는 포인트 지급 로직이 없으므로 바로 return
        return json_return("00", "이력서등록완료", ["ru_idx" => $ru_idx]);
      } else {
        $this->db->trans_rollback();
        return json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
      }
    } else {
      if ($preview_mode == 1) {
        json_return("00", "미리보기 저장이 완료되었습니다.", ["ru_idx" => 0]);
      }

      # 트랜젝션 시작
      $this->db->trans_start();
      // 이력서 등록 처리
      if ($count == 0) {
        //최초등록이면 공개 처리
        $ru_show_yn = "Y";
      } else {
        $ru_show_yn = "N";
      }

      # 데이터 처리
      // ru_work_dt: timestamp 필드이므로 빈 문자열일 경우 NULL로 처리 (스텝2에 저장되므로 스텝2에서 먼저 확인)
      $ru_work_dt = (isset($step2["ru_work_dt"]) && trim($step2["ru_work_dt"]) != "") ? $step2["ru_work_dt"] : ((isset($step4["ru_work_dt"]) && trim($step4["ru_work_dt"]) != "") ? $step4["ru_work_dt"] : null);
      // ru_month_price: int 필드이므로 빈 문자열일 경우 0으로 처리
      $ru_month_price = isset($step1["ru_month_price"]) && $step1["ru_month_price"] != "" ? intval($step1["ru_month_price"]) : 0;

      // ru_week: 빈값일 경우 기본값(월~금) 설정
      $ru_week_yn = isset($step4["ru_week_yn"]) ? $step4["ru_week_yn"] : (isset($step2["ru_week_yn"]) ? $step2["ru_week_yn"] : "N");
      $ru_week = isset($step4["ru_week"]) ? $step4["ru_week"] : (isset($step2["ru_week"]) ? $step2["ru_week"] : "");
      if (is_array($ru_week)) {
        $ru_week = arr_implode(",", $ru_week);
      }
      // 요일무관이 아니고 근무요일이 빈값일 경우 기본값(월~금) 설정
      if ($ru_week_yn != "Y" && (empty($ru_week) || trim((string) $ru_week) == "")) {
        $ru_week = "월,화,수,목,금";
      }

      // 최종학력: 스텝2에 저장되므로 스텝2에서 먼저 확인, 없으면 스텝4에서 확인
      $ru_edu = isset($step2["ru_edu"]) && trim($step2["ru_edu"]) != "" ? $step2["ru_edu"] : (isset($step4["ru_edu"]) && trim($step4["ru_edu"]) != "" ? $step4["ru_edu"] : "");

      $arr_resume = [
        // 회원 식별 (NOT NULL)
        "mm_idx" => $this->mm_idx,
        // 기본 정보 (NOT NULL)
        "ru_name" => isset($step1["ru_name"]) ? $step1["ru_name"] : "",
        "ru_work" => isset($step1["ru_work"]) && is_array($step1["ru_work"]) ? arr_implode(",", $step1["ru_work"]) : (isset($step1["ru_work"]) ? $step1["ru_work"] : ""),
        "ru_work_img" => isset($step1["ru_work_img"]) && is_array($step1["ru_work_img"]) ? arr_implode(",", $step1["ru_work_img"]) : (isset($step1["ru_work_img"]) ? $step1["ru_work_img"] : ""),
        "ru_addr" => isset($step1["ru_addr"]) ? $step1["ru_addr"] : "",
        "ru_price_type" => isset($step1["ru_price_type"]) && is_array($step1["ru_price_type"]) ? arr_implode(",", $step1["ru_price_type"]) : (isset($step1["ru_price_type"]) ? $step1["ru_price_type"] : ""),
        "ru_work_type" => isset($step1["ru_work_type"]) && is_array($step1["ru_work_type"]) ? arr_implode(",", $step1["ru_work_type"]) : (isset($step1["ru_work_type"]) ? $step1["ru_work_type"] : ""),
        "ru_month_price" => $ru_month_price,
        "ru_work_dt" => $ru_work_dt,
        "ru_edu" => $ru_edu,
        // 자기소개 관련 (NOT NULL)
        // ru_my_type은 step3 또는 step4에 저장됨 (모바일은 step4, 웹은 step3)
        "ru_my_type" => (isset($step3["ru_my_type"]) && trim((string)$step3["ru_my_type"]) != "") ? (is_array($step3["ru_my_type"]) ? arr_implode(",", $step3["ru_my_type"]) : $step3["ru_my_type"]) : ((isset($step4["ru_my_type"]) && trim((string)$step4["ru_my_type"]) != "") ? (is_array($step4["ru_my_type"]) ? arr_implode(",", $step4["ru_my_type"]) : $step4["ru_my_type"]) : ""),
        "ru_desc" => isset($step4["ru_desc"]) ? $step4["ru_desc"] : "",
        // 이미지 관련 (NOT NULL)
        "ru_thumb" => isset($step4["ru_thumb"]) ? $step4["ru_thumb"] : "",
        "ru_img1" => isset($step4["ru_img1"]) ? $step4["ru_img1"] : "",
        "ru_img2" => isset($step4["ru_img2"]) ? $step4["ru_img2"] : "",
        "ru_img3" => isset($step4["ru_img3"]) ? $step4["ru_img3"] : "",
        "ru_img4" => isset($step4["ru_img4"]) ? $step4["ru_img4"] : "",
        "ru_img5" => isset($step4["ru_img5"]) ? $step4["ru_img5"] : "",
        // 기타 상태 관련 (NOT NULL)
        "ru_show_yn" => $ru_show_yn,
        "ru_week_yn" => $ru_week_yn,
        "ru_week" => $ru_week,
        "ru_ip" => $this->common->getIpAddr(),
        "ru_status" => "Y", // 기본값 Y
        "stop_desc" => "", // 기본값 빈 문자열
        "ru_hit" => 0, // 조회수 초기값 (st_resume.ru_hit는 NOT NULL, 기본값 없음)
        // 선택 필드 (데이터베이스 테이블에 컬럼이 존재하는 경우에만 사용)
        // "ru_nationality" => isset($step1["ru_nationality"]) ? $step1["ru_nationality"] : "", // 국적 추가 - 테이블에 컬럼이 없으므로 주석 처리
        // "ru_salary_negotiation" => isset($step1["ru_salary_negotiation"]) ? $step1["ru_salary_negotiation"] : "N",
        // "ru_work_negotiation" => isset($step1["ru_work_negotiation"]) ? $step1["ru_work_negotiation"] : "N",
      ];

      $result_resume = $this->member_model->insertResume($arr_resume);

      if ($result_resume["code"] == "00") {
        // 이력서 등록 시 회원의 국적 정보도 함께 업데이트
        if ((isset($step1["ru_nationality"]) && $step1["ru_nationality"] != "") || $quickinfo_gender != "" || $quickinfo_birth != "") {
          $member_update = [];
          if (isset($step1["ru_nationality"]) && $step1["ru_nationality"] != "") {
            $member_update["mm_nation"] = $step1["ru_nationality"];
          }
          if ($quickinfo_gender != "") {
            $member_update["mm_sex"] = $quickinfo_gender;
          }
          if ($quickinfo_birth != "") {
            $member_update["mm_birth"] = $quickinfo_birth;
          }
          if (!empty($member_update)) {
            $this->member_model->updateMember($member_update, ["mm_idx" => $this->mm_idx]);
          }
        }

        // 간단경력 데이터는 스텝3에 저장되므로 스텝3에서 먼저 확인, 없으면 스텝4에서 확인
        $arr_career = isset($step3["arr_career"]) && $step3["arr_career"] != "" ? $step3["arr_career"] : (isset($step4["arr_career"]) && $step4["arr_career"] != "" ? $step4["arr_career"] : "");
        if ($arr_career != "") {
          $arr_temp = explode("&&&", $arr_career);
          foreach ($arr_temp as $row) {
            if (trim($row) == "") continue;
            $arr_data = explode("|||", $row);
            if (count($arr_data) < 2) continue;
            $info = [
              "ru_idx" => $result_resume["key"],
              "rc_type" => "",
              "rc_name" => isset($arr_data[0]) ? $arr_data[0] : "",
              "rc_date" => isset($arr_data[1]) ? $arr_data[1] : "",
            ];
            $result_career = $this->member_model->insertResumeCareer($info);

            if ($result_career["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }


        if (isset($step4["arr_certi"]) && $step4["arr_certi"] != "") {
          $arr_temp = explode("&&&", $step4["arr_certi"]);
          foreach ($arr_temp as $row) {
            if (trim($row) == "") continue;
            $arr_data = explode("|||", $row);
            if (count($arr_data) < 2) continue;
            $info = [
              "ru_idx" => $result_resume["key"],
              "rc_name" => isset($arr_data[0]) ? $arr_data[0] : "",
              "rc_dt" => isset($arr_data[1]) ? $arr_data[1] : "",
            ];
            $result_certi = $this->member_model->insertResumeCerti($info);

            if ($result_certi["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }

        # 지역등록
        // POST 파라미터 우선, step1 fallback, 최종 기본값 "RESUME"
        $ad_type_post = input_post("ad_type", "");
        $ad_type_step1 = isset($step1["ad_type"]) ? $step1["ad_type"] : "";
        $ad_type = !empty($ad_type_post) ? strtoupper($ad_type_post) : (!empty($ad_type_step1) ? strtoupper($ad_type_step1) : "RESUME");

        // 주소 필드: 문자열이면 배열로 변환, 빈값이면 빈 배열
        $ad_zipcode_raw = isset($step1["ad_zipcode"]) ? $step1["ad_zipcode"] : "";
        $ad_zipcode = is_array($ad_zipcode_raw) ? $ad_zipcode_raw : ($ad_zipcode_raw != "" ? [$ad_zipcode_raw] : []);

        $ad_jibun_raw = isset($step1["ad_jibun"]) ? $step1["ad_jibun"] : "";
        $ad_jibun = is_array($ad_jibun_raw) ? $ad_jibun_raw : ($ad_jibun_raw != "" ? [$ad_jibun_raw] : []);

        $ad_doro_raw = isset($step1["ad_doro"]) ? $step1["ad_doro"] : "";
        $ad_doro = is_array($ad_doro_raw) ? $ad_doro_raw : ($ad_doro_raw != "" ? [$ad_doro_raw] : []);

        $ad_addr_raw = isset($step1["ad_addr"]) ? $step1["ad_addr"] : "";
        $ad_addr = is_array($ad_addr_raw) ? $ad_addr_raw : ($ad_addr_raw != "" ? [$ad_addr_raw] : []);

        $ad_detail_raw = isset($step1["ad_detail"]) ? $step1["ad_detail"] : "";
        $ad_detail = is_array($ad_detail_raw) ? $ad_detail_raw : ($ad_detail_raw != "" ? [$ad_detail_raw] : []);

        $ad_gps_x_raw = isset($step1["ad_gps_x"]) ? $step1["ad_gps_x"] : "";
        $ad_gps_x = is_array($ad_gps_x_raw) ? $ad_gps_x_raw : ($ad_gps_x_raw != "" ? [$ad_gps_x_raw] : []);

        $ad_gps_y_raw = isset($step1["ad_gps_y"]) ? $step1["ad_gps_y"] : "";
        $ad_gps_y = is_array($ad_gps_y_raw) ? $ad_gps_y_raw : ($ad_gps_y_raw != "" ? [$ad_gps_y_raw] : []);

        if (!empty($ad_addr) && isset($ad_addr[0]) && $ad_addr[0] != "") {

          foreach ($ad_addr as $idx => $row) {

            //$this->db->trans_rollback();
            //return json_return("90","11.[".$ad_zipcode[$idx]."]");

            $arr_addr = [
              "mm_idx" => $this->mm_idx,
              "ad_type" => $ad_type,
              "ad_key" => $result_resume["key"],
              "ad_zipcode" => isset($ad_zipcode[$idx]) ? $ad_zipcode[$idx] : "",
              "ad_jibun" => isset($ad_jibun[$idx]) ? $ad_jibun[$idx] : "",
              "ad_doro" => isset($ad_doro[$idx]) ? $ad_doro[$idx] : "",
              "ad_detail" => isset($ad_detail[$idx]) ? $ad_detail[$idx] : "",
              "ad_addr" => isset($ad_addr[$idx]) ? $ad_addr[$idx] : "",
              "ad_gps_x" => isset($ad_gps_x[$idx]) ? $ad_gps_x[$idx] : "",
              "ad_gps_y" => isset($ad_gps_y[$idx]) ? $ad_gps_y[$idx] : "",
            ];

            $result_addr = $this->member_model->insertAddress($arr_addr);
            if ($result_addr["code"] != "00") {
              $this->db->trans_rollback();
              json_return("92", "[02]이력서 등록시 오류가 발생하였습니다.");
            }
          } //foreach
        }

        $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]);

        // 트랜잭션 완료 및 상태 확인
        $this->db->trans_complete();

        // 트랜잭션 실패 확인
        if ($this->db->trans_status() === FALSE) {
          return json_return("91", "[01]이력서 등록시 오류가 발생하였습니다.");
        }

        // 이력서 등록 성공 - 포인트 지급은 별도 처리 (실패해도 이력서 등록은 성공으로 처리)
        //2024.08.15 최초 등록시 5천 포인트 추가 지급
        try {
          //1.이미 지급되었는지 확인 (포인트리스트에서)
          # 트랜젝션 시작
          $this->db->trans_start();
          // 포인트 지급 처리
          $query_point  = "select count(*) as cnt from st_point_list ";
          $query_point .= "where mm_idx = '" . $this->mm_idx . "' ";
          $query_point .= "and   pl_desc like '%첫 이력서 등록%' ";

          $result_point = $this->db->query($query_point);
          $count_point = $result_point->row_array();
          $cnt_point = 0;
          if ($count_point != null) {
            $cnt_point = $count_point["cnt"];
          }

          $query_recruit  = "select count(*) as cnt from st_resume ";
          $query_recruit .= "where mm_idx = '" . $this->mm_idx . "' and ru_status = 'Y' ";

          $result_recruit = $this->db->query($query_recruit);
          $count_recruit = $result_recruit->row_array();
          $cnt_recruit = 0;
          if ($count_recruit != null) {
            $cnt_recruit = $count_recruit["cnt"];
          }

          //2.포인트 지급처리
          if ($cnt_point == 0 && $cnt_recruit == 1) {
            //2.1. 포인트 지급히스토리 추가
            $pay_point = 5000;
            $insert = [
              "mm_idx" => $this->mm_idx,
              "o_num" => "",
              "pm_idx" => 0,
              "pt_type" => "PROMOTION",
              "pl_type" => "plus",
              "pl_point" => $pay_point,
              "pl_last_point" => $pay_point,
              "pl_desc" => "첫 이력서 등록 포인트 지급(5,000P)",
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
              // 포인트 지급 실패는 로그만 남기고 이력서 등록은 성공으로 처리
              //log_message('error', '포인트 지급 실패 - mm_idx: ' . $this->mm_idx);
            }
          }
          $this->db->trans_complete();
        } catch (Exception $e) {
          // 포인트 지급 실패는 로그만 남기고 이력서 등록은 성공으로 처리
          //log_message('error', '포인트 지급 중 오류: ' . $e->getMessage());
        }

        return json_return("00", "이력서등록완료", ["ru_idx" => $result_resume["key"]]);
      } else {
        $this->db->trans_rollback();
        return json_return("91", "[01]이력서 등록시 오류가 발생하였습니다.");
      }
    }
  } //resume_complete

  # 이력서 삭제
  function resume_delete()
  {
    $ru_idx = input_post("ru_idx");
    $result = $this->member_model->updateResume(["ru_status" => "N"], ["ru_idx" => $ru_idx]);
    json_return($result["code"], $result["msg"]);
  }

  # 이력서 개수 조회
  function getResumeCount()
  {
    // 현재 로그인된 회원의 이력서 개수 조회
    $count = $this->member_model->getResumeCount([
      "mm_idx" => $this->mm_idx,
      "ru_status" => "Y"
    ]);

    json_return("00", "조회완료", ["count" => $count]);
  }

  #
  function resume_default()
  {
    $ru_idx = input_post("ru_idx");
    $this->member_model->updateResume(["ru_show_yn" => "N"], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateResume(["ru_show_yn" => "Y"], ["ru_idx" => $ru_idx]);

    json_return($result["code"], $result["msg"]);
  }
}
