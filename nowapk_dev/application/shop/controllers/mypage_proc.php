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
    if ($mem_info == null || (strpos($mem_info["mm_di"], "MC0") !== 0)) {
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
    $ru_my_type = input_post("ru_my_type", "");
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
      ["mm_idx" => $this->mm_idx, "sl_type" => "RESUME", "sl_step" => "스텝4"]
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
      "ru_month_price" => $step1["ru_month_price"],
      "ru_work_type" => $step1["ru_work_type"],
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
    $ru_work = input_post("ru_work", "");
    $ru_addr = input_post("ru_addr", "");
    $ru_work_img = input_post("ru_work_img", "");
    $ru_price_type = input_post("ru_price_type", "");
    $ru_month_price = input_post("ru_month_price", "");
    $ru_work_type = input_post("ru_work_type", "");

    $ad_zipcode = input_post("ad_zipcode", "");
    $ad_jibun = input_post("ad_jibun", "");
    $ad_doro = input_post("ad_doro", "");
    $ad_addr = input_post("ad_addr", "");
    $ad_gps_x = input_post("ad_gps_x", "");
    $ad_gps_y = input_post("ad_gps_y", "");

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

    #로그 생성
    $sl_txt = [
      "ru_name" => $ru_name,
      "ru_work" => $ru_work,
      "ru_addr" => $ru_addr,
      "ru_work_img" => $ru_work_img == "" ? [] : explode(",", $ru_work_img),
      "ru_price_type" => $ru_price_type,
      "ru_month_price" => $ru_month_price,
      "ru_work_type" => $ru_work_type,
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
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "RESUME",
        "sl_step" => "스텝1"
      ]
    );
    json_return("00", "처리완료");
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
    $ru_desc = input_post("ru_desc", "");

    $ru_img1_ori = input_post("ru_img1_ori", "");
    $ru_img2_ori = input_post("ru_img2_ori", "");
    $ru_img3_ori = input_post("ru_img3_ori", "");
    $ru_img4_ori = input_post("ru_img4_ori", "");
    $ru_img5_ori = input_post("ru_img5_ori", "");

    # 기존 등록 이미지 있으면 삭제
    $log_info = $this->site_model->getSiteLog(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RESUME", "sl_step" => "스텝4"]
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
      "ru_desc" => $ru_desc,
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

  # 이력서 등록 완료
  function resume_complete()
  {

    $ru_idx = input_post("ru_idx", 0);

    $list = $this->site_model->getSiteLogList(
      ["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]
    );

    $step1 = $this->site_model->getStepData($list, "스텝1");
    $step2 = $this->site_model->getStepData($list, "스텝2");
    $step3 = $this->site_model->getStepData($list, "스텝3");
    $step4 = $this->site_model->getStepData($list, "스텝4");

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
      # 트랜젝션 시작
      $this->db->trans_start();

      # 데이터 처리
      $arr_resume = [
        "ru_name" => $step1["ru_name"],
        "ru_work" => arr_implode(",", $step1["ru_work"]),
        "ru_work_img" => arr_implode(",", $step1["ru_work_img"]),
        "ru_addr" => $step1["ru_addr"],
        "ru_price_type" => arr_implode(",", $step1["ru_price_type"]),
        "ru_month_price" => $step1["ru_month_price"],
        "ru_work_type" => arr_implode(",", $step1["ru_work_type"]),
        "ru_week_yn" => $step2["ru_week_yn"],
        "ru_week" => arr_implode(",", $step2["ru_week"]),
        "ru_work_dt" => $step2["ru_work_dt"],
        "ru_edu" => $step2["ru_edu"],
        "ru_my_type" => arr_implode(",", $step3["ru_my_type"]),
        "ru_desc" => $step4["ru_desc"],
        "ru_img1" => $step4["ru_img1"],
        "ru_img2" => $step4["ru_img2"],
        "ru_img3" => $step4["ru_img3"],
        "ru_img4" => $step4["ru_img4"],
        "ru_img5" => $step4["ru_img5"],
        "ru_thumb" => $step4["ru_thumb"],
        "ru_ip" => $this->common->getIpAddr(),
      ];

      $result_resume = $this->member_model->updateResume($arr_resume, ["ru_idx" => $ru_idx]);

      if ($result_resume["code"] == "00") {
        $this->member_model->deleteResumeCareer(["ru_idx" => $ru_idx]);
        if ($step3["arr_career"] != "") {
          $arr_temp = explode("&&&", $step3["arr_career"]);
          foreach ($arr_temp as $row) {
            $arr_data = explode("|||", $row);
            $info = [
              "ru_idx" => $ru_idx,
              "rc_type" => $arr_data[0],
              "rc_name" => $arr_data[1],
              "rc_date" => $arr_data[2],
            ];

            $result_career = $this->member_model->insertResumeCareer($info);
            if ($result_career["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }

        $this->member_model->deleteResumeCerti(["ru_idx" => $ru_idx]);
        if ($step4["arr_certi"] != "") {
          $arr_temp = explode("&&&", $step4["arr_certi"]);
          foreach ($arr_temp as $row) {
            $arr_data = explode("|||", $row);

            $info = [
              "ru_idx" => $ru_idx,
              "rc_name" => $arr_data[0],
              "rc_dt" => $arr_data[1],
            ];

            $result_certi = $this->member_model->insertResumeCerti($info);
            if ($result_certi["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }


        # 지역등록
        $ad_zipcode = $step1["ad_zipcode"];
        $ad_jibun = $step1["ad_jibun"];
        $ad_doro = $step1["ad_doro"];
        $ad_addr = $step1["ad_addr"];
        $ad_gps_x = $step1["ad_gps_x"];
        $ad_gps_y = $step1["ad_gps_y"];

        $this->member_model->deleteAddress(["ad_type" => "RESUME", "mm_idx" => $this->mm_idx, "ad_key" => $ru_idx]);
        foreach ($ad_zipcode as $idx => $row) {
          $arr_addr = [
            "mm_idx" => $this->mm_idx,
            "ad_type" => "RESUME",
            "ad_key" => $ru_idx,
            "ad_zipcode" => $ad_zipcode[$idx],
            "ad_jibun" => $ad_jibun[$idx],
            "ad_doro" => $ad_doro[$idx],
            "ad_detail" => "",
            "ad_addr" => $ad_addr[$idx],
            "ad_gps_x" => $ad_gps_x[$idx],
            "ad_gps_y" => $ad_gps_y[$idx],
          ];

          $result_addr = $this->member_model->insertAddress($arr_addr);
          if ($result_addr["code"] != "00") {
            $this->db->trans_rollback();
            json_return("92", "[02]이력서 등록시 오류가 발생하였습니다.");
          }
        }

        $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]);

        $this->db->trans_complete();
        //return json_return("00","이력서등록완료");

      } else {
        $this->db->trans_rollback();
        return json_return("91", "[01]회원가입시 오류가 발생하였습니다.");
      }
    } else {


      # 트랜젝션 시작
      $this->db->trans_start();

      if ($count == 0) {
        //최초등록이면 공개 처리
        $ru_show_yn = "Y";
      } else {
        $ru_show_yn = "N";
      }

      # 데이터 처리
      $arr_resume = [
        "mm_idx" => $this->mm_idx,
        "ru_name" => $step1["ru_name"],
        "ru_work" => arr_implode(",", $step1["ru_work"]),
        "ru_work_img" => arr_implode(",", $step1["ru_work_img"]),
        "ru_addr" => $step1["ru_addr"],
        "ru_price_type" => arr_implode(",", $step1["ru_price_type"]),
        "ru_work_type" => arr_implode(",", $step1["ru_work_type"]),
        "ru_week_yn" => $step2["ru_week_yn"],
        "ru_week" => arr_implode(",", $step2["ru_week"]),
        "ru_work_dt" => $step2["ru_work_dt"],
        "ru_edu" => $step2["ru_edu"],
        "ru_my_type" => arr_implode(",", $step3["ru_my_type"]),
        "ru_desc" => $step4["ru_desc"],
        "ru_img1" => $step4["ru_img1"],
        "ru_img2" => $step4["ru_img2"],
        "ru_img3" => $step4["ru_img3"],
        "ru_img4" => $step4["ru_img4"],
        "ru_img5" => $step4["ru_img5"],
        "ru_thumb" => $step4["ru_thumb"],
        "ru_ip" => $this->common->getIpAddr(),
        "ru_show_yn" => $ru_show_yn
      ];

      $result_resume = $this->member_model->insertResume($arr_resume);

      if ($result_resume["code"] == "00") {

        if ($step3["arr_career"] != "") {
          $arr_temp = explode("&&&", $step3["arr_career"]);
          foreach ($arr_temp as $row) {
            $arr_data = explode("|||", $row);
            $info = [
              "ru_idx" => $result_resume["key"],
              "rc_type" => $arr_data[0],
              "rc_name" => $arr_data[1],
              "rc_date" => $arr_data[2],
            ];
            $result_career = $this->member_model->insertResumeCareer($info);

            if ($result_career["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }


        if ($step4["arr_certi"] != "") {
          $arr_temp = explode("&&&", $step4["arr_certi"]);
          foreach ($arr_temp as $row) {
            $arr_data = explode("|||", $row);

            $info = [
              "ru_idx" => $result_resume["key"],
              "rc_name" => $arr_data[0],
              "rc_dt" => $arr_data[1],
            ];
            $result_certi = $this->member_model->insertResumeCerti($info);

            if ($result_certi["code"] != "00") {
              $this->db->trans_rollback();
              json_return("93", "[03]이력서 등록시 오류가 발생하였습니다.");
            }
          }
        }

        # 지역등록
        $ad_zipcode = $step1["ad_zipcode"];
        $ad_jibun = $step1["ad_jibun"];
        $ad_doro = $step1["ad_doro"];
        $ad_addr = $step1["ad_addr"];
        $ad_gps_x = $step1["ad_gps_x"];
        $ad_gps_y = $step1["ad_gps_y"];

        if (count($ad_zipcode) > 0 && $ad_zipcode[0] != "") {

          foreach ($ad_zipcode as $idx => $row) {

            //$this->db->trans_rollback();
            //return json_return("90","11.[".$ad_zipcode[$idx]."]");

            $arr_addr = [
              "mm_idx" => $this->mm_idx,
              "ad_type" => "RESUME",
              "ad_key" => $result_resume["key"],
              "ad_zipcode" => $ad_zipcode[$idx],
              "ad_jibun" => $ad_jibun[$idx],
              "ad_doro" => $ad_doro[$idx],
              "ad_detail" => "",
              "ad_addr" => $ad_addr[$idx],
              "ad_gps_x" => $ad_gps_x[$idx],
              "ad_gps_y" => $ad_gps_y[$idx],
            ];

            $result_addr = $this->member_model->insertAddress($arr_addr);
            if ($result_addr["code"] != "00") {
              $this->db->trans_rollback();
              json_return("92", "[02]이력서 등록시 오류가 발생하였습니다.");
            }
          } //foreach
        }

        $this->site_model->deleteSiteLog(["mm_idx" => $this->mm_idx, "sl_type" => "RESUME"]);

        $this->db->trans_complete();
        //return json_return("00","이력서등록완료");
      } else {
        $this->db->trans_rollback();
        return json_return("91", "[01]이력서 등록시 오류가 발생하였습니다.");
      }
    }

    //2024.08.15 최초 등록시 5천 포인트 추가 지급
    //1.이미 지급되었는지 확인 (포인트리스트에서)
    # 트랜젝션 시작
    $this->db->trans_start();

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
        json_return("01", "잔액변경 오류. 관리자에게 문의하세요.");
      }
    }
    $this->db->trans_complete();

    return json_return("00", "이력서등록완료");
  } //resume_complete

  # 이력서 삭제
  function resume_delete()
  {
    $ru_idx = input_post("ru_idx");
    $result = $this->member_model->updateResume(["ru_status" => "N"], ["ru_idx" => $ru_idx]);
    json_return($result["code"], $result["msg"]);
  }

  #
  function resume_default()
  {
    $ru_idx = input_post("ru_idx");
    $this->member_model->updateResume(["ru_show_yn" => "N"], ["mm_idx" => $this->mm_idx]);
    $result = $this->member_model->updateResume(["ru_show_yn" => "Y"], ["ru_idx" => $ru_idx]);

    // 공개 이력서 설정 성공 시, 모든 기업회원에게 알림톡 발송
    if ($result["code"] == "00") {
      try {
        // 이력서 정보 조회
        $resume_info = $this->member_model->getResume(["ru_idx" => $ru_idx]);
        if ($resume_info != null) {
          // PpurioClient 클래스 직접 로드
          $ppurioPath = (defined('SHAREDPATH') ? SHAREDPATH : APPPATH . 'common/') . 'libraries/PpurioClient.php';
          if (file_exists($ppurioPath)) {
            require_once($ppurioPath);
          }
          if (!class_exists('CI_PpurioClient')) {
            throw new Exception('PpurioClient class not found');
          }
          $ppurio = new CI_PpurioClient();

          // 템플릿 변수 구성 (기업회원 템플릿 요구사항)
          // 기업회원 템플릿: name=회사명, var3=이력서 제목, var4=이력서 URL
          $corp_vars = array(
            '', // var1 비움
            '', // var2 비움
            isset($resume_info['ru_name']) ? $resume_info['ru_name'] : '', // var3: 이력서 제목
            site_url('resume/hr_post/' . $ru_idx) // var4: 이력서 URL
          );

          // 대상: st_promotion 테이블에서 pm_name에 "추천공고등록"이 포함된 기업회원만
          $sql = "
            SELECT DISTINCT mb.mm_idx, mb.mm_id, mb.mm_phone, mb.mm_comp_name
            FROM st_promotion pm
            INNER JOIN st_member mb ON pm.mm_idx = mb.mm_idx
            WHERE pm.pm_name LIKE '%추천공고등록%'
              AND pm.pm_status = 'Y'
              AND mb.mm_type = 'COMP'
              AND mb.mm_status = 'Y'
              AND mb.mm_phone != ''
              AND mb.mm_phone IS NOT NULL
          ";
          $query = $this->db->query($sql);
          $corpList = $query->result_array();

          error_log('[ALIMTALK] 이력서 공개 설정 완료 - 추천공고등록 대상 기업회원 수: ' . count($corpList));

          if (!empty($corpList) && is_array($corpList)) {
            $sent_count = 0;
            foreach ($corpList as $corp) {
              $phone = isset($corp['mm_phone']) ? trim($corp['mm_phone']) : '';
              if ($phone != '') {
                $corpName = isset($corp['mm_comp_name']) ? trim($corp['mm_comp_name']) : '';
                error_log('[ALIMTALK] 기업회원 발송 시도 - mm_id=' . $corp['mm_id'] . ', phone=' . $phone . ', name=' . $corpName);
                // name=회사명, var3=이력서 제목, var4=URL
                $ppurio->sendCorporateAlimtalk($phone, $corp_vars, array('name' => $corpName));
                $sent_count++;
              }
            }
            error_log('[ALIMTALK] 기업회원 발송 완료 - 총 대상: ' . count($corpList) . '명, 발송: ' . $sent_count . '명');
          } else {
            error_log('[ALIMTALK] 알림톡 발송 건너뛰기 - 추천공고등록 대상 기업회원 없음');
          }
        }
      } catch (Exception $e) {
        if (function_exists('log_message')) {
          log_message('error', 'Kakao Alimtalk send (resume_default) failed: ' . $e->getMessage());
        }
      }
    }

    json_return($result["code"], $result["msg"]);
  }
}
