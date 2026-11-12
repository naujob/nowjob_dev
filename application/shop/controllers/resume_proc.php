<?php
class Resume_proc extends CI_Controller
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
  }

  function chage_addr()
  {
    $mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx]);
  }

  # 메인화면
  function search_list()
  {
    $result = $this->member_model->getResumeSearchParamsList();
    json_return("00", "조회성공", $result);
  }

  function search_list2025()
  {
    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();
    $page = input_post("page", 1);
    $pagesize = input_post("pagesize", 10);
    $text = input_post("text", "");
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    $sage = input_post("sage", "");
    $eage = input_post("eage", "");
    $order_by = input_post("order_by", "recent");
    $search_addr = input_post('search_addr');
    $addrName = input_post("addrName", "");
    $searchAreaStr = input_post("searchAreaStr", "");
    $ru_work = input_post("ru_work", "");

    if (empty($search_addr)) {
      $search_addr = [];
    } elseif (!is_array($search_addr)) {
      $search_addr = [$search_addr];
    }

    // searchAreaStr에서 step 정보 추출
    $search_addr_step = [];
    if (!empty($searchAreaStr)) {
      try {
        $search_area = json_decode($searchAreaStr, true);
        if (is_array($search_area)) {
          foreach ($search_area as $area) {
            if (isset($area['step'])) {
              $search_addr_step[] = intval($area['step']);
            }
          }
        }
      } catch (Exception $e) {
        // 파싱 실패 시 무시
      }
    }

    $params = [
      "pagesize" => $pagesize,
      "page" => $page,
      "text" => $text,
      "mm_idx" => $mm_idx,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      "sage" => $sage,
      "eage" => $eage,
      "order_by" => $order_by,
      "search_addr" => $search_addr,
      "search_addr_step" => $search_addr_step,
      "addrName" => $addrName,
      "searchAreaStr" => $searchAreaStr,
      "ru_work" => $ru_work,
    ];

    // 검색 실행 시 조건 저장을 같은 요청에서 처리 (save_yn=Y, 별도 저장 요청 없이 다음 진입 때 서버가 이 조건을 씀)
    if (input_post("save_yn", "") === "Y") {
      $this->_saveResumeSearchLog(array(
        "text" => $text,
        "addrName" => $addrName,
        "searchAreaStr" => $searchAreaStr,
        "mapLevel" => 6,
        "category" => $category,
        "work_type" => $work_type,
        "week" => $week,
        "price_type" => $price_type,
        "sex" => $sex,
        "sage" => $sage,
        "eage" => $eage,
      ));
    }

    $result = $this->member_model->getResumeSearchList2025($params);

    $truncate_length = getTruncateLength();
    $result["list"] = formatResumeListRows($result["list"], $truncate_length);

    // 기업회원인 경우 채팅방 정보 추가
    if ($this->user->getUserType() == "COMP") {
      $result["list"] = $this->member_model->addResumeChatBadgeInfo($result["list"], $mm_idx, $this->user->getUserId(), $this->user->getUserPhone());
    }

    $result["total"] = isset($result["count"]) ? $result["count"] : 0;

    // render=html이면 첫 화면과 같은 카드 파셜로 그린 HTML을 돌려준다 (다른 호출처는 기존 JSON 그대로)
    if (input_post("render", "") === "html") {
      // PC/모바일이 같은 기기 판별로 각자 파셜을 고른다 (PC 첫 화면과 AJAX 응답이 같은 카드)
      $card_partial = (getDeviceType() == "web") ? "web/_partial/resume_list_card" : "mobile/_partial/resume_list_card";
      $result["list_html"] = $this->load->view($card_partial, array("card_list" => $result["list"], "mm_type" => $this->user->getUserType()), true);
      $result["list_count"] = count($result["list"]);
      unset($result["list"]);
    }
    json_return("00", "조회성공", $result);
  }


  # 핀리스트

  function getPins2025()
  {
    // 좌표 없는 이력서는 지도에 찍을 수 없으므로 st_address를 INNER JOIN으로 제외 (NaN 마커 방지)
    $sql = "
      SELECT 
        bb.ru_idx, cc.ad_gps_x, cc.ad_gps_y
      FROM 
        st_member aa
        inner join st_member_info mi
            on aa.mm_idx = mi.mm_idx
        inner join st_resume bb
          on aa.mm_idx = bb.mm_idx
        inner join st_address cc
          on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
            AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
					  AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1
      WHERE 
        aa.mm_status = 'Y' and bb.ru_status = 'Y' and bb.ru_show_yn = 'Y'
      GROUP BY 
        bb.ru_idx
      ORDER BY 
        bb.ru_idx desc;  
    ";
    $result = $this->db->query($sql)->result_array();
    json_return("00", "조회성공", $result);
  }

  function search_map_list2025()
  {

    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();
    $page = input_post("page", 1);
    $pagesize = input_post("pagesize", 10);
    $text = input_post("text", "");
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    $sage = input_post("sage", "");
    $eage = input_post("eage", "");
    $order_by = input_post("order_by", "recent");
    $currentLatitude = input_post("currentLatitude", "");
    $currentLongitude = input_post("currentLongitude", "");
    $search_addr = input_post("search_addr", "");
    $pins = input_post("pins", "");

    $params = [
      "pagesize" => $pagesize,
      "page" => $page,
      "text" => $text,
      "mm_idx" => $mm_idx,
      "currentLatitude" => $currentLatitude,
      "currentLongitude" => $currentLongitude,
      "search_addr" => $search_addr,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      "sage" => $sage,
      "eage" => $eage,
      "order_by" => $order_by,
      "pins" => $pins,
    ];

    $result = $this->member_model->getResumeMapList($params);

    // 웹 중국어 페이지: 이력서명을 자동번역문으로
    $temp_list = translate_rows($result["list"], array('ru_name' => 'T'));
    foreach ($temp_list as $key => $row) {
      $temp_list[$key]["ru_resume_img"] = getResumeImg($row);
      $temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
      $temp_list[$key]["ru_work_type"] = getWorkTypeWithColor($row["ru_work_type"]);
      $temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"]);
      $temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);
      $temp_list[$key]["ru_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      $ru_week = isset($row["ru_week"]) ? trim($row["ru_week"]) : "";
      $ru_week_yn = isset($row["ru_week_yn"]) ? $row["ru_week_yn"] : "N";

      // 요일무관이 아니고 ru_week가 비어있으면 간편등록 기본값 사용
      if ($ru_week_yn !== "Y" && ($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $temp_list[$key]["ru_week"] = getWeekYoil($ru_week);
      $temp_list[$key]["mm_name"] = getAstrik($row["mm_name"]);
      $temp_list[$key]["mm_age"] = getKoreanAge($row["mm_birth"]);
      $temp_list[$key]["mm_birth"] = "";
      $temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
      $temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
      $temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
    }

    $result["list"] = $temp_list;

    json_return("00", "조회성공", $result);
  }

  function search_reco_list2025()
  {
    $addr1 = input_post("addr1", "");
    $addr2 = input_post("addr2", "");
    $addr3 = input_post("addr3", "");
    $limit = input_post("limit", "");
    $idx = input_post("idx", "");
    $mm_idx = $this->user->getUserIdx();

    $params = [
      "addr1" => $addr1,
      "addr2" => $addr2,
      "addr3" => $addr3,
      "mm_idx" => $mm_idx,
      "idx" => $idx,
      "limit" => $limit,
    ];

    $result = $this->common_model->getFavResumeList2025($params);

    // render=html이면 추천인재 카드 파셜로 그린 HTML만 돌려준다 (PC 추천 슬라이더, JS 템플릿 없이 사용)
    if (input_post("render", "") === "html") {
      $reco_cards = formatResumeRecoRows(isset($result["list"]) ? $result["list"] : array());
      json_return("00", "조회성공", array(
        "list_html" => $this->load->view("web/_partial/resume_reco_card", array("card_list" => $reco_cards, "mm_type" => $this->user->getUserType()), true),
        "list_count" => count($reco_cards)
      ));
      return;
    }

    $truncate_length = getTruncateLength();

    // 웹 중국어 페이지: 이력서명을 자동번역문으로 (말줄임 전에 적용 — render=html 분기의 formatResumeRecoRows()와 동일)
    $temp_list = translate_rows($result["list"], array('ru_name' => 'T'));
    foreach ($temp_list as $key => $row) {
      $temp_list[$key]["ru_resume_img"] = getResumeImg($row);
      $temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
      $temp_list[$key]["ru_work_badge"] = getWorkingBadge($row["ru_work"]);
      $temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"]);
      $temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"]);
      $temp_list[$key]["ru_price_type_raw"] = ($row["ru_price_type"]);
      $temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);
      $temp_list[$key]["ru_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);
      $temp_list[$key]["ru_name"] = (mb_strlen($row['ru_name']) > $truncate_length) ? mb_substr($row['ru_name'], 0, $truncate_length) . '...' : $row['ru_name'];

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      $ru_week = isset($row["ru_week"]) ? trim($row["ru_week"]) : "";
      $ru_week_yn = isset($row["ru_week_yn"]) ? $row["ru_week_yn"] : "N";

      // 요일무관이 아니고 ru_week가 비어있으면 간편등록 기본값 사용
      if ($ru_week_yn !== "Y" && ($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $temp_list[$key]["ru_week"] = getWeekYoil($ru_week);
      $temp_list[$key]["mm_name"] = getAstrik($row["mm_name"]);
      $temp_list[$key]["mm_age"] = getKoreanAge($row["mm_birth"]);
      $temp_list[$key]["mm_birth"] = "";
      $temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
      $temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
      $temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
      // 서버사이드 주소 변환 (큰 시/도 제거)
      $temp_list[$key]["ad_addr"] = removeBigAddr(isset($row["ad_addr"]) ? $row["ad_addr"] : "");
    }

    $result["list"] = $temp_list;

    json_return("00", "조회성공", $result);
  }

  # 조건 저장
  function search_save_params()
  {
    $page = input_post("page", 1);
    $text = input_post("text", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    $sage = input_post("sage", "");
    $eage = input_post("eage", "");


    #로그 생성
    $search_txt = [
      "text" => $text,
      "skm" => $skm,
      "ekm" => $ekm,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      "sage" => $sage,
      "eage" => $eage,
    ];

    if ($this->user->isLogin()) {
      $this->site_model->deleteSiteLog(
        [
          "session_id" => session_id(),
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );

      $this->site_model->deleteSiteLog(
        [
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );
    } else {
      $this->site_model->deleteSiteLog(
        [
          "session_id" => session_id(),
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );
    }

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RESUME",
        "sl_step" => "검색",
        "sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
      ]
    );
    json_return("00", "처리완료");
  }

  function search_save_params2025()
  {
    $text = input_post("text", "");
    $addrName = input_post("addrName", "");
    $searchAreaStr = input_post("searchAreaStr", "");
    $mapLevel = input_post("mapLevel", 6);
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    $sage = input_post("sage", "");
    $eage = input_post("eage", "");

    $this->_saveResumeSearchLog(array(
      "text" => $text,
      "addrName" => $addrName,
      "searchAreaStr" => $searchAreaStr,
      "mapLevel" => $mapLevel,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      "sage" => $sage,
      "eage" => $eage,
    ));
    json_return("00", "처리완료");
  }

  // 인재 검색 조건을 site_log에 저장 (기존 SEARCH_RESUME 기록을 지우고 새로 씀)
  // search_save_params2025와 search_list2025(save_yn=Y)가 함께 쓴다
  private function _saveResumeSearchLog($search_txt)
  {
    if ($this->user->isLogin()) {
      $this->site_model->deleteSiteLog(
        [
          "session_id" => session_id(),
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );

      $this->site_model->deleteSiteLog(
        [
          "mm_idx" => $this->mm_idx,
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );
    } else {
      $this->site_model->deleteSiteLog(
        [
          "session_id" => session_id(),
          "sl_type" => "SEARCH_RESUME",
          "sl_step" => "검색"
        ]
      );
    }

    $this->site_model->setSiteLog(
      [
        "session_id" => session_id(),
        "mm_idx" => $this->mm_idx,
        "sl_type" => "SEARCH_RESUME",
        "sl_step" => "검색",
        "sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
      ]
    );
  }

  # 메인화면 - 웹용
  function search_web_list2025()
  { //pc 인재리스트
    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();
    $page = input_post("page", 1);
    $pagesize = input_post("pagesize", 10);
    $text = input_post("text", "");
    $addr1 = input_post("addr1", "");
    $addr2 = input_post("addr2", "");
    $addr3 = input_post("addr3", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    $sage = input_post("sage", "");
    $eage = input_post("eage", "");
    $order_by = input_post("order_by", "recent");
    $multiAddr1 = input_post("multiAddr1", "");
    $multiAddr2 = input_post("multiAddr2", "");
    $multiAddr3 = input_post("multiAddr3", "");

    $params = [
      "pagesize" => $pagesize,
      "page" => $page,
      "text" => $text,
      "mm_idx" => $mm_idx,
      "addr1" => $addr1,
      "addr2" => $addr2,
      "addr3" => $addr3,
      "skm" => $skm,
      "ekm" => $ekm,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      "sage" => $sage,
      "eage" => $eage,
      "order_by" => $order_by,
      "multiAddr1" => $multiAddr1,
      "multiAddr2" => $multiAddr2,
      "multiAddr3" => $multiAddr3,
    ];

    $result = $this->member_model->getResumeWebSearchList2025($params);

    $temp_list = $result["list"];
    foreach ($temp_list as $key => $row) {
      $temp_list[$key]["ru_resume_img"] = getResumeImg($row);
      $temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
      $temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"]);
      $temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"]);
      $temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      $ru_week = isset($row["ru_week"]) ? trim($row["ru_week"]) : "";
      $ru_week_yn = isset($row["ru_week_yn"]) ? $row["ru_week_yn"] : "N";

      // 요일무관이 아니고 ru_week가 비어있으면 간편등록 기본값 사용
      if ($ru_week_yn !== "Y" && ($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $temp_list[$key]["ru_week"] = getWeekYoil($ru_week);
      $temp_list[$key]["mm_name"] = getAstrik($row["mm_name"]);
      $temp_list[$key]["mm_age"] = getKoreanAge($row["mm_birth"]);
      $temp_list[$key]["mm_birth"] = "";
      $temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
      $temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
      $temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
    }
    $result["list"] = $temp_list;

    json_return("00", "조회성공", $result);
  }

  # 메인화면 - 웹용
  function search_web_list()
  { //pc 인재리스트
    $addr_info = $this->user->getAddrInfo();
    $mm_idx = $this->user->getUserIdx();
    $page = input_post("page", 1);
    $pagesize = input_post("pagesize", 10);
    $text = input_post("text", "");
    $addr1 = input_post("addr1", "");
    $addr2 = input_post("addr2", "");
    $addr3 = input_post("addr3", "");
    $skm = input_post("skm", "");
    $ekm = input_post("ekm", "");
    $category = input_post("category", "");
    $work_type = input_post("work_type", "");
    $week = input_post("week", "");
    $price_type = input_post("price_type", "");
    $sex = input_post("sex", "");
    //$sage = input_post("sage","");
    //$eage = input_post("eage","");
    $age = input_post("age", "");
    $order_by = input_post("order_by", "recent");
    $multiAddr1 = input_post("multiAddr1", "");
    $multiAddr2 = input_post("multiAddr2", "");
    $multiAddr3 = input_post("multiAddr3", "");

    $params = [
      "pagesize" => $pagesize,
      "page" => $page,
      "text" => $text,
      "mm_idx" => $mm_idx,
      "addr1" => $addr1,
      "addr2" => $addr2,
      "addr3" => $addr3,
      "skm" => $skm,
      "ekm" => $ekm,
      "category" => $category,
      "work_type" => $work_type,
      "week" => $week,
      "price_type" => $price_type,
      "sex" => $sex,
      //"sage" => $sage,
      //"eage" => $eage,
      "age" => $age,
      "order_by" => $order_by,
      "multiAddr1" => $multiAddr1,
      "multiAddr2" => $multiAddr2,
      "multiAddr3" => $multiAddr3,
    ];

    $result = $this->member_model->getResumeWebSearchList($params);

    $temp_list = $result["list"];
    foreach ($temp_list as $key => $row) {

      $temp_list[$key]["ru_resume_img"] = getResumeImg($row);
      $temp_list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
      $temp_list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"]);
      $temp_list[$key]["ru_price_type"] = getPriceType($row["ru_price_type"]);
      $temp_list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);

      // 근무희망요일 처리: 간편등록시 ru_week가 비어있으면 기본값(월~금) 사용
      $ru_week = isset($row["ru_week"]) ? trim($row["ru_week"]) : "";
      $ru_week_yn = isset($row["ru_week_yn"]) ? $row["ru_week_yn"] : "N";

      // 요일무관이 아니고 ru_week가 비어있으면 간편등록 기본값 사용
      if ($ru_week_yn !== "Y" && ($ru_week === "" || $ru_week === null)) {
        $ru_week = "월,화,수,목,금";
      }

      $temp_list[$key]["ru_week"] = getWeekYoil($ru_week);
      $temp_list[$key]["mm_name"] = getAstrik($row["mm_name"]);
      $temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
      $temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
      $temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
    }
    $result["list"] = $temp_list;

    json_return("00", "조회성공", $result);
  }

  //오늘 제안했던 이려것인지 확인
  function todayapply()
  {

    $mm_idx = $this->user->getUserIdx();
    $ru_idx = input_post("ru_idx", "");
    $rc_idx = input_post("rc_idx", "");

    // 같은 공고로 같은 이력서에 이미 제안했으면 다시 제안할 수 있도록 안내 (code 02 + rm_idx)
    $active_rm_idx = $this->member_model->findActiveRequestRoom("RESUME", $mm_idx, $rc_idx, $ru_idx, $this->user->getUserId(), $this->user->getUserPhone());
    if ($active_rm_idx) {
      $exited_before = has_room_exit_history($active_rm_idx) ? "Y" : "N";
      // 당사자가 지금 나간 상태(exit_yn='Y')일 수 있으므로 미리 복구해 둔다
      // (그래야 팝업 확인 후 auto_send로 재전송할 때 발신 차단/방 조회 차단에 걸리지 않는다)
      reset_room_exit_status(get_mongo_uri(), get_mongo_db_name(), $active_rm_idx);
      json_return("02", "이미 제안을 보냈습니다. 다시 보내시겠습니까.", array("rm_idx" => $active_rm_idx, "exited_before" => $exited_before));
      return;
    }

    // 회원(이력서 소유자)이 이미 지원한 공고-이력서 조합인지 확인 (rc_idx가 있을 때만)
    if (!empty($rc_idx) && !empty($ru_idx)) {
      // ru_idx의 소유자(mm_idx) 조회
      $sql_owner = "SELECT mm_idx FROM st_resume WHERE ru_idx = ?";
      $result_owner = $this->db->query($sql_owner, array($ru_idx));
      $owner_row = $result_owner->row_array();

      if ($owner_row) {
        $owner_mm_idx = $owner_row["mm_idx"];
        // 그 소유자가 이 공고에 RECRUIT(지원)으로 보낸 활성 요청이 있는지 확인
        $active_rm_idx = $this->member_model->findActiveRequestRoom("RECRUIT", $owner_mm_idx, $rc_idx, $ru_idx, $this->user->getUserId(), $this->user->getUserPhone());
        if ($active_rm_idx) {
          $exited_before = has_room_exit_history($active_rm_idx) ? "Y" : "N";
          reset_room_exit_status(get_mongo_uri(), get_mongo_db_name(), $active_rm_idx);
          json_return("02", "이미 회원이 지원한 공고입니다. 다시 제안을 보내시겠습니까.", array("rm_idx" => $active_rm_idx, "exited_before" => $exited_before));
          return;
        }
      }
    }

    json_return("00", "조회성공", $result);
  }

  // 인재정보 캐시 갱신 (Cron 또는 HTTP 요청)
  function refreshResumeCache() {
    // 환경 판별 (dev vs prod)
    $is_dev = (strpos($_SERVER['REQUEST_URI'], '/dev_nowapk') !== false ||
               strpos($_SERVER['HTTP_HOST'], 'dev.') === 0);

    $dev_key = 'kD9mP2x5nQ5wR8vL3bT';
    $prod_key = 'ProdSeC7kEy9mL2wR';
    $expected_key = $is_dev ? $dev_key : $prod_key;

    // 보안 체크
    $provided_key = isset($_GET['key']) ? $_GET['key'] : '';
    if ($provided_key !== $expected_key) {
      http_response_code(403);
      echo json_encode(["code" => "90", "msg" => "접근 거부"]);
      return;
    }

    $start_time = microtime(true);

    try {
      // 1. 캐시 테이블 비우기
      $this->db->query("TRUNCATE TABLE vw_resume_list_cache");

      // 2. 신규 데이터 삽입 (조건 없이 최신순, 상위만)
      $sql = "
        INSERT INTO vw_resume_list_cache (
          ru_idx, mm_idx, mm_id, mm_status, ru_name, ru_work, ru_work_type,
          ru_price_type, ru_work_img, ru_resume_img, ru_show_yn, ru_status,
          ru_week, ru_my_type, ad_addr, mm_age, mm_nation, mm_sex, book_cnt, pm_idx,
          pm_code, up_dt, reg_dt, cached_at
        )
        SELECT
          bb.ru_idx, aa.mm_idx, aa.mm_id, aa.mm_status, bb.ru_name,
          bb.ru_work, bb.ru_work_type, bb.ru_price_type, bb.ru_work_img,
          COALESCE(NULLIF(aa.mm_thumb, ''), aa.mm_img1, aa.mm_img2, aa.mm_img3, '/resource/uploads/member/noimage4.png') as ru_resume_img, bb.ru_show_yn, bb.ru_status,
          bb.ru_week, bb.ru_my_type,
          COALESCE(cc.ad_addr, bb.ru_addr) as ad_addr,
          YEAR(CURDATE()) - LEFT(aa.mm_birth, 4) as mm_age,
          aa.mm_nation,
          aa.mm_sex,
          (SELECT COUNT(*) FROM st_bookmark WHERE bk_type = 'RESUME' AND bk_key = bb.ru_idx) as book_cnt,
          p0.pm_idx,
          p0.pm_code,
          GREATEST(CASE WHEN bb.up_dt = '0000-00-00 00:00:00' THEN bb.reg_dt ELSE bb.up_dt END, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt,
          bb.reg_dt,
          NOW() as cached_at
        FROM st_member aa
        INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx AND bb.ru_status = 'Y' AND bb.ru_show_yn = 'Y'
        LEFT JOIN st_address cc ON aa.mm_idx = cc.mm_idx AND cc.ad_type = 'RESUME' AND cc.ad_key = bb.ru_idx
        LEFT JOIN st_promotion p0 ON p0.rc_idx = bb.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y'
        LEFT JOIN st_promotion p ON p.rc_idx = bb.ru_idx AND p.pm_code IN ('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
        WHERE aa.mm_status = 'Y'
        GROUP BY bb.ru_idx
        ORDER BY p0.pm_idx DESC, up_dt DESC
        LIMIT 500
      ";

      $this->db->query($sql);

      $elapsed_time = microtime(true) - $start_time;

      $response = [
        "code" => "00",
        "msg" => "캐시 갱신 완료",
        "data" => [
          "environment" => $is_dev ? "development" : "production",
          "elapsed_time" => round($elapsed_time * 1000, 2) . "ms",
          "timestamp" => date("Y-m-d H:i:s")
        ]
      ];

      echo json_encode($response);

    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        "code" => "99",
        "msg" => "캐시 갱신 실패",
        "error" => $e->getMessage()
      ]);
    }
  }

  # 050 가상번호 발급/재사용 (기업이 구직자에게 전화 클릭 시)
  function get_call_number()
  {
    // 보안: 로그인 및 기업회원 여부를 서버에서도 검증 (클라이언트 체크만으로는 ru_idx를 임의로 넘겨 직접 호출 가능)
    if (!$this->user->isLogin()) {
      json_return("90", "로그인이 필요합니다.");
      return;
    }
    if ($this->user->getUserType() != "COMP") {
      json_return("97", "기업회원만 이용 가능합니다.");
      return;
    }

    $ru_idx = input_post("ru_idx", "");
    if ($ru_idx == "") {
      json_return("99", "잘못된 요청입니다.");
      return;
    }

    $phone = $this->member_model->getPhoneByRuIdx($ru_idx);
    if (!$phone) {
      json_return("98", "연락처 정보가 없습니다.");
      return;
    }

    $realNumber = preg_replace('/[^0-9]/', '', $phone);

    // 기존 유효한 번호가 있으면 재사용
    $vn = $this->member_model->getActiveVirtualNumber($ru_idx);
    if ($vn) {
      json_return("00", "success", array("vn" => $vn));
      return;
    }

    // 새로 발급 (auto_expire_mapp.do)
    $cfg = config_item('bizcall');
    $expireHours = isset($cfg['expire_hours']) ? (int)$cfg['expire_hours'] : 2160;

    require_once(dirname(APPPATH) . '/common/libraries/BizCallClient.php');
    $bizcall = new CI_BizCallClient();
    $res = $bizcall->assignAutoExpireVirtualNumber($realNumber, $ru_idx, $expireHours);

    if (!$res['ok']) {
      json_return("96", "전화 연결 설정에 실패했습니다.");
      return;
    }

    // 델피콤이 배정한 vn을 로컬 DB에 upsert
    $vn = isset($res['vn']) ? $res['vn'] : null;
    if (!$vn) {
      json_return("95", "가상번호 발급은 성공했으나 번호 정보가 없습니다.");
      return;
    }

    $this->member_model->upsertAssignedVirtualNumber($vn, $ru_idx, $realNumber, $expireHours);
    json_return("00", "success", array("vn" => $vn));
  }
} //class
