<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * 반응형 이미지 변형 URL 가져오기
 * img_upload_responsive()가 한 장을 sm(400)/md(800)/lg(1200)으로 만들어 두므로,
 * DB에 저장된 lg URL에서 경로만 바꿔 원하는 크기를 꺼낸다.
 *
 * @param string $url  저장된 이미지 URL (보통 lg)
 * @param string $size sm | md | lg
 */
if (!function_exists('get_img_variant')) {
	function get_img_variant($url, $size)
	{
		if ($url == "") {
			return "";
		}
		// 반응형으로 올린 이미지가 아니면 그대로 사용
		if (preg_match('#/resize/(sm|md|lg)/#', $url) != 1) {
			return $url;
		}
		return preg_replace('#/resize/(sm|md|lg)/#', '/resize/' . $size . '/', $url);
	}
}

/**
 * srcset 속성값 생성 (브라우저가 화면 크기/해상도에 맞는 변형을 고른다)
 * 반응형 이미지가 아니면 빈 문자열 → 호출부에서 srcset을 붙이지 않는다.
 */
if (!function_exists('get_img_srcset')) {
	function get_img_srcset($url)
	{
		if ($url == "" || preg_match('#/resize/(sm|md|lg)/#', $url) != 1) {
			return "";
		}
		return get_img_variant($url, "sm") . " 400w, "
			. get_img_variant($url, "md") . " 800w, "
			. get_img_variant($url, "lg") . " 1200w";
	}
}

//배너 리스트
function getBannerList($bg_code, $limit = 50)
{
	$db = DB('default', true);
	$bg_code = inject($bg_code);
	$sql = "SELECT bn.*
		 FROM st_banner_group bg
		inner join st_banner bn
	    	on bg.bg_idx = bn.bg_idx
		where bg.bg_status = 'Y'
		and bn.bn_status = 'Y'
		and bg.bg_code = '$bg_code'
		and (bn_period_type = 'A' OR ((date_format(bn_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(bn_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d'))))
		order by bn.bn_sort desc,bn_idx desc
		limit 0,$limit
	";

	$qry = $db->query($sql);
	$list = $qry->result_array();

	$result = "";
	$device_type = device_type();

	$banner_list = [];
	foreach ($list as $row) {
		$bn_name = $row["bn_name"];
		$bn_text1 = $row["bn_text1"];
		$bn_text2 = $row["bn_text2"];
		$bn_text3 = $row["bn_text3"];

		$bn_link_type = ($row["bn_link_type"] == "C" ? "_self" : ($row["bn_link_type"] == "N" ? "N" : "_blank"));
		$bn_link = $row["bn_link"];
		$bn_bg_color = $row["bn_bg_color"];
		$bg_img = $row["bn_pc_img"];
		$bg_thumb = $row["bn_m_img"];
		$bn_link_yn = "N";

		if ($bn_link == "") {
			$bn_link = "javascript:void(0);";
			$bn_link_type = "_self";
		}

		if ($bn_link_type != "N" && $bn_link != "") {
			$bn_link_yn = "Y";
		}

		$style = "";
		if ($bn_bg_color != "") {
			$style .= "background:#$bn_bg_color;";
		}

		$banner_list[] = [
			"bd_idx" => $row["bn_idx"],
			"bn_name" => $bn_name,
			"bn_text1" => $bn_text1,
			"bn_text2" => $bn_text2,
			"bn_text3" => $bn_text3,
			"bn_link_yn" => $bn_link_yn,
			"bn_link_type" => $bn_link_type,
			"bn_link" => $bn_link,
			"bn_bg_color" => $bn_bg_color,
			"bg_img" => $bg_img,
			"bg_thumb" => $bg_thumb,
			"style" => $style,
		];
	}

	return $banner_list;
}

# 설정정보 가져오기
function getSettingInfo($where)
{
	$CI = &get_instance();
	$CI->load->model('settingmodel', 'setting_model');
	return $CI->setting_model->getSettingInfo($where);
}

# 회원 이미지
function getMemberImg($info)
{
	$device_type = device_type();
	$loc = ($device_type == "W" ? "web" : "mobile");
	if ($info["mm_sex"] == "남") {
		$default = "/resource/shop/web/img/profile_default_m.png";
	} else if ($info["mm_sex"] == "여") {
		$default = "/resource/shop/web/img/profile_default_w.png";
	} else {
		$default = "/resource/shop/mobile/img/common/noimage4.png";
	}

	if ($info == null) {
		return $default;
	}

	if ($info["mm_thumb"] != "") {
		return $info["mm_thumb"];
	} else if ($info["mm_img1"] != "") {
		return $info["mm_img1"];
	} else if ($info["mm_img2"] != "") {
		return $info["mm_img2"];
	} else if ($info["mm_img3"] != "") {
		return $info["mm_img3"];
	} else if ($info["mm_img4"] != "") {
		return $info["mm_img4"];
	} else if ($info["mm_img5"] != "") {
		return $info["mm_img5"];
	}
	return $default;
}

# 회원 이미지
function getCompImg($info)
{
	$default = "/resource/shop/mobile/img/common/noimage4.png";
	if ($info == null) {
		return $default;
	}

	// 회사 이미지 우선순위 반환 (mm_thumb -> mm_img1~5)
	// 한국어 주석: 가능한 실제 업로드 이미지를 우선적으로 사용
	if (!empty($info["mm_thumb"])) return $info["mm_thumb"]; // 썸네일 우선
	if (!empty($info["mm_img1"])) return $info["mm_img1"];
	if (!empty($info["mm_img2"])) return $info["mm_img2"];
	if (!empty($info["mm_img3"])) return $info["mm_img3"];
	if (!empty($info["mm_img4"])) return $info["mm_img4"];
	if (!empty($info["mm_img5"])) return $info["mm_img5"];

	// 업무 아이콘 기반 대체 이미지 (세션의 WORK_TYPE 매핑 사용)
	$CI = &get_instance();
	$target_list = $CI->common->getSessionDataList("WORK_TYPE");
	$arr_work = isset($info["ma_work"]) ? explode(",", $info["ma_work"]) : [];
	if (count($arr_work) > 0 && isset($target_list[$arr_work[0]])) {
		$img = $target_list[$arr_work[0]];
		if ($img != null) {
			return ($info["mm_idx"] % 2 == 0) ? $img["img1"] : $img["img2"];
		}
	}

	// 최종 기본 이미지 반환
	return $default;
}



# 채용 이미지 가져오기
function getRecruitImg($info, $img_type = "thumb")
{
	$device_type = device_type();
	$loc = ($device_type == "W" ? "web" : "mobile");
	$alias = ($device_type == "W" ? "pc" : "mo");
	$default = "/resource/shop/mobile/img/common/noimage4.png";

	if ($info["rc_work"] == "") {
		return $default;
	}

	$work_type = explode(",", $info["rc_work"]); //explode(",", "주간,야간,2교대") => ["주간", "야간", "2교대"]
	$type = $work_type[0];
	$random = mt_rand(1, 3); // 1~3 사이의 랜덤 숫자 생성
	/** 사용되고 있지 않는 데이터 */
	if ($img_type == "thumb") {
		$default = "/resource/shop/" . $loc . "/img/empty/" . $type . "/" . $alias . "_섬네일" . $random . ".png";
	} else {
		$default = "/resource/shop/" . $loc . "/img/empty/" . $type . "/" . $alias . "_상세" . $random . ".png";
	}


	if ($info == null) {
		return $default;
	}
	/**실제 업로드된 이미지 확인(우선순위 순서) */
	if ($info["rc_thumb"] != "") {
		return $info["rc_thumb"];
	} else if ($info["rc_img1"] != "") {
		return $info["rc_img1"];
	} else if ($info["rc_img2"] != "") {
		return $info["rc_img2"];
	} else if ($info["rc_img3"] != "") {
		return $info["rc_img3"];
	} else if ($info["rc_img4"] != "") {
		return $info["rc_img4"];
	} else if ($info["rc_img5"] != "") {
		return $info["rc_img5"];
	}
	/**실제 이미지가 없으면 st_code 테이블에서 admin 경로 가져오기*/
	$db = DB('default', true);
	//$sql = " SELECT CASE WHEN FLOOR(RAND() * 2) = 0 THEN cd_val3 WHEN FLOOR(RAND() * 2) = 0 THEN cd_val3 WHEN FLOOR(RAND() * 2) = 0 THEN cd_val3 ELSE cd_val4 END AS random_value FROM st_code WHERE cd_grp = '희망업무' AND cd_key = '".$type."' order by rand() limit 1 ";
	$sql = " SELECT CASE WHEN IFNULL(cd_val12,'') <> '' THEN CASE FLOOR(RAND() * 10) WHEN 0 THEN cd_val3 WHEN 1 THEN cd_val4 WHEN 2 THEN cd_val5 WHEN 3 THEN cd_val6 WHEN 4 THEN cd_val7 WHEN 5 THEN cd_val8 WHEN 6 THEN cd_val9 WHEN 7 THEN cd_val10 WHEN 8 THEN cd_val11 ELSE cd_val12 END ELSE CASE WHEN IFNULL(cd_val6,'') <> '' THEN CASE FLOOR(RAND() * 4) WHEN 0 THEN cd_val3 WHEN 1 THEN cd_val4 WHEN 2 THEN cd_val5 ELSE cd_val6 END ELSE CASE FLOOR(RAND() * 2) WHEN 0 THEN cd_val3 ELSE cd_val4 END END END AS random_value FROM st_code WHERE cd_grp = '희망업무' AND cd_key = '" . $type . "' order by rand() limit 1 ";
	$qry = $db->query($sql);
	$result = $qry->result_array();
	return $result[0]["random_value"];

	//return $default;
}

# 이력서 이미지 가져오기
function getResumeImg($info)
{
	$device_type = device_type();
	$loc = ($device_type == "W" ? "web" : "mobile");

	// if($info["mm_sex"]=="남"){
	//     //$default = "/resource/shop/".$loc."/img/empty/user/man.png";
	//     $default = "/resource/shop/web/img/profile_default_m.png";
	// }else{
	//     //$default = "/resource/shop/".$loc."/img/empty/user/girl.png";
	//     $default = "/resource/shop/web/img/profile_default_w.png";
	// }
	if ($info["mm_sex"] == "남") {
		//$default = "/resource/shop/".$loc."/img/empty/user/man.png";
		$default = "/resource/shop/web/img/avatar_icon2_m.png";
	} else if ($info["mm_sex"] == "여") {
		$default = "/resource/shop/web/img/avatar_resume_w.png";
	} else {
		//$default = "/resource/shop/".$loc."/img/empty/user/girl.png";
		//$default = "/resource/shop/web/img/profile_default_w.png";
		$default = "/resource/shop/mobile/img/common/noimage4.png";
	}

	if ($info == null) {
		return $default;
	}

	if ($info["ru_thumb"] != "") {
		return $info["ru_thumb"];
	} else if ($info["ru_img1"] != "") {
		return $info["ru_img1"];
	} else if ($info["ru_img2"] != "") {
		return $info["ru_img2"];
	} else if ($info["ru_img3"] != "") {
		return $info["ru_img3"];
	} else if ($info["ru_img4"] != "") {
		return $info["ru_img4"];
	} else if ($info["ru_img5"] != "") {
		return $info["ru_img5"];
	}
	if ($info["mm_thumb"] != "") {
		return $info["mm_thumb"];
	}
	return $default;
}



# 중고상품 이미지
function getTradeImg($info)
{
	$default = "/resource/shop/mobile/img/common/noimage4.png";
	if ($info == null) {
		return $default;
	}

	if ($info["td_thumb"] != "") {
		return $info["td_thumb"];
	} else if ($info["td_img1"] != "") {
		return $info["td_img1"];
	} else if ($info["td_img2"] != "") {
		return $info["td_img2"];
	} else if ($info["td_img3"] != "") {
		return $info["td_img3"];
	} else if ($info["td_img4"] != "") {
		return $info["td_img4"];
	} else if ($info["td_img5"] != "") {
		return $info["td_img5"];
	}
	return $default;
}


# 구분
function getWorkingImg($work, $max = 10)
{
	$CI = &get_instance();
	if ($work == null || $work == "") {
		return "";
	}

	$target_list = $CI->common->getSessionDataList("WORK_TYPE");
	$arr = explode(",", $work);
	$return = "";

	foreach ($arr as $key => $txt) {
		if ($key + 1 <= $max) {
			$src = $target_list[$txt];
			$return .= " <img src='" . $src["icon"] . "' class='category-label'> ";
		}
	}
	return $return;
}

# 이력서 정보를 채팅화면 카드용으로 가공 (이미지/뱃지/근무조건/공개상태 변환)
# chat.php(최초 로딩)와 chat_proc.php(제안/지원 후 실시간 갱신) 양쪽에서 공용으로 사용
function enrichResumeForChatCard($resume)
{
	if (empty($resume)) {
		return $resume;
	}

	$row = $resume;
	$resume["ru_resume_img"] = getResumeImg($row);
	$resume["ru_work_img"] = getWorkingImg(isset($row["ru_work"]) ? $row["ru_work"] : "");
	$resume["ru_work_type"] = getWorkTypeWithColor(isset($row["ru_work_type"]) ? $row["ru_work_type"] : "");
	$resume["ru_price_type"] = getPriceType(isset($row["ru_price_type"]) ? $row["ru_price_type"] : "");

	$ru_week = isset($row["ru_week"]) ? trim($row["ru_week"]) : "";
	if ($ru_week === "") {
		$ru_week = "월,화,수,목,금";
	}
	$resume["ru_week"] = getWeekYoil($ru_week);

	$resume["ru_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);
	$resume["status_label"] = (isset($row["ru_status"]) && $row["ru_status"] === "Y") ? "공개" : "비공개";

	return $resume;
}

# 가능 요일
function getWeekYoil($week)
{
	$arr_week = ["월", "화", "수", "목", "금", "토", "일"];

	// 빈값이면 모든 요일 표시
	$week = trim($week);
	if ($week === "" || $week === null) {
		$return = "";
		foreach ($arr_week as $yo) {
			$return .= "<li aria-hidden='false'>$yo</li>";
		}
		return $return;
	}

	$arr_check = explode(",", $week);

	$return = "";
	foreach ($arr_week as $yo) {
		$check = "true";
		foreach ($arr_check as $tmp) {
			if (trim($tmp) == $yo) {
				$check = "false";
			}
		}

		$return .= "<li aria-hidden='$check'>$yo</li>";
	}

	return $return;
}


# 나의 타입
function getMyType($type, $max = 3)
{
	if ($type == null || $type == "") {
		return "";
	}
	//echo "1:[".$type."]";
	$type = str_replace("<p class='tag' style='width:100%;text-align:right;'>", "", $type);
	$type = str_replace("</p>", "", $type);
	//echo "2:[".$type."]";
	$type = str_replace("#", "", $type);
	$type = str_replace(" ", ",", $type);
	$arr_type = explode(",", $type);
	//print_r($arr_type);
	$return = "<p class='tag' style='width:100%;text-align:right;'> ";
	//echo "\nreturn:[".$return."]";
	# 3개까지만 노출
	foreach ($arr_type as $key => $tag) {
		//echo "[".$tag."]";
		if (trim($tag) == "") $max++;
		if (trim($tag) != "" && $key < $max) {
			$return .= " #$tag";
		}
		//echo "\nreturn:[".$return."]";
	}
	$return .= "</p>";
	//echo "\nreturn:[".$return."]";
	return $return;
}

# 나의 타입
function getMyTypeLeft($type, $max = 3)
{
	if ($type == null || $type == "") {
		return "";
	}
	//echo "1:[".$type."]";
	$type = str_replace("<p class='tag' style='width:auto;text-align:left;'>", "", $type);
	$type = str_replace("</p>", "", $type);
	//echo "2:[".$type."]";
	$type = str_replace("#", "", $type);
	$type = str_replace(" ", ",", $type);
	$arr_type = explode(",", $type);
	//print_r($arr_type);
	$return = "<p class='tag' style='width:auto;text-align:left;'> ";
	//echo "\nreturn:[".$return."]";
	# 3개까지만 노출
	foreach ($arr_type as $key => $tag) {
		//echo "[".$tag."]";
		if (trim($tag) == "") $max++;
		if (trim($tag) != "" && $key < $max) {
			$return .= " #$tag";
		}
		//echo "\nreturn:[".$return."]";
	}
	$return .= "</p>";
	//echo "\nreturn:[".$return."]";
	return $return;
}

# 나의 타입
function getRecuitPrice($info, $class_name = "num")
{
	if ($info == null) {
		return "";
	}

	// 한국어 주석: 급여 유형에 "협의"나 "무관"이 포함되면 금액 유무와 관계없이 "급여협의"를 노출
	if (strpos($info["rc_price_type"], "협의") !== false || strpos($info["rc_price_type"], "무관") !== false) {
		return "<span>급여협의</span>";
	}

	if ($info["rc_price_type"] == "일급,주급,월급") { //$info["rc_price_type"]=="주급"
		return "<span>급여협의</span>";
		//}else if(strpos($info["rc_price_type"],"주급")!==false){
		//	return "<span >급여협의</span>";
	} else if ($info["rc_price_type"] == "월급") {
		if ($info["rc_month_price"] == "") {
			return "<em class='$class_name'></em>";
		} else {
			return "<em class='$class_name'>" . number_format($info["rc_month_price"]) . "</em> 원";
		}
	} else if ($info["rc_price_type"] == "일급") {
		if ($info["rc_day_price"] == "") {
			return "<em class='$class_name'></em>";
		} else {
			return "<em class='$class_name'>" . number_format($info["rc_day_price"]) . "</em> 원";
		}
	} else if ($info["rc_price_type"] == "주급") {
		if ($info["rc_week_price"] == "") {
			return "<em class='$class_name'></em>";
		} else {
			return "<em class='$class_name'>" . number_format($info["rc_week_price"]) . "</em> 원";
		}
	} else {
		//if($info["rc_day_price"]==""){ return "<em class='$class_name'></em>"; }
		//else{ return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원"; }

		//두개가 선택된 경우
		if ($info["rc_day_price"] != "0") {
			return "<em class='$class_name'>" . number_format($info["rc_day_price"]) . "</em> 원";
		} else if ($info["rc_week_price"] != "0") {
			return "<em class='$class_name'>" . number_format($info["rc_week_price"]) . "</em> 원";
		} else if ($info["rc_month_price"] != "0") {
			return "<em class='$class_name'>" . number_format($info["rc_month_price"]) . "</em> 원";
		} else {
			return "<em class='$class_name'></em>";
		}
	}
	// todo

	/*
	}else if(strpos($info["rc_price_type"],"주급")!==false && strpos($info["rc_price_type"],"일급")!==false){
		return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원";
	}else if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"월급")!==false){
        if($info["rc_month_price"] >= $info["rc_day_price"]){
            return "<em class='$class_name'>".number_format($info["rc_month_price"])."</em> 원";
        }else{
            return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원";
        }
		//return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원(일급), <em class='$class_name'>".number_format($info["rc_month_price"])."</em> 원(월급)";
	}else if(strpos($info["rc_price_type"],"일급")!==false){
		return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원";
	}else if(strpos($info["rc_price_type"],"월급")!==false){
		return "<em class='$class_name'>".number_format($info["rc_month_price"])."</em> 원";
	}
  */

	return "-";
}

# 나의 타입
function getPriceType($price_type, $max = 3)
{
	$num = 0;
	$return = "";
	$count = 0;

	if (count(explode(",", $price_type)) > 2) {
		return $return . "<em>급여협의</em>";
	}

	if (strpos($price_type, "일급") !== false) {
		if ($count > 0) {
			$return .= "<em class='off'>일급</em>";
		} else {
			$return .= "<em>일급</em>";
		}
		$count++;
		$num++;
	}

	if ($num >= $max) {
		return $return;
	}

	if (strpos($price_type, "주급") !== false) {
		if ($count > 0) {
			$return .= "<em class='off'>주급</em>";
		} else {
			$return .= "<em>주급</em>";
		}
		$count++;
		$num++;
	}

	if ($num >= $max) {
		return $return;
	}

	if (strpos($price_type, "월급") !== false) {
		if ($count > 0) {
			$return .= "<em class='off'>월급</em>";
		} else {
			$return .= "<em>월급</em>";
		}
		$count++;
		$num++;
	}
	return $return;
}

# 나의 타입
function getPriceTypePoint($price_type, $info = [])
{
	$point = "";
	$return = "";
	$arr_type = explode(",", $price_type);

	/*
    if($info["rc_price_type"]=="주급"){
		$point = "주급";
	}else if(strpos($info["rc_price_type"],"주급")!==false){
		$point = "주급";
	}else if(strpos($info["rc_price_type"],"주급")!==false && strpos($info["rc_price_type"],"일급")!==false){
		$point = "일급";
	}else if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"월급")!==false){
		$point = "일급";
		//return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원(일급), <em class='$class_name'>".number_format($info["rc_month_price"])."</em> 원(월급)";
	}else if(strpos($info["rc_price_type"],"일급")!==false){
		$point = "일급";
	}else if(strpos($info["rc_price_type"],"월급")!==false){
		$point = "월급";
	}
    */

	if ($info["rc_price_type"] == "일급,월급") {
		if ($info["rc_month_price"] != "0") {
			$point = "월급";
		} else if ($info["rc_day_price"] != "0") {
			$point = "일급";
		}
	} else if ($info["rc_price_type"] == "주급") {
		$point = "주급";
	} else if (strpos($info["rc_price_type"], "주급") !== false) {
		$point = "주급";
	} else if (strpos($info["rc_price_type"], "주급") !== false && strpos($info["rc_price_type"], "일급") !== false) {
		$point = "일급";
	} else if (strpos($info["rc_price_type"], "일급") !== false && strpos($info["rc_price_type"], "월급") !== false) {
		if ($info["rc_month_price"] >= $info["rc_day_price"]) {
			$point = "월급";
		} else {
			$point = "일급";
		}

		if ($info["rc_day_price"] == 0) {
			unset($arr_type[0]);
		}

		if ($info["rc_month_price"] == 0) {
			unset($arr_type[1]);
		}
		//return "<em class='$class_name'>".number_format($info["rc_day_price"])."</em> 원(일급), <em class='$class_name'>".number_format($info["rc_month_price"])."</em> 원(월급)";
	} else if (strpos($info["rc_price_type"], "일급") !== false) {
		$point = "일급";
	} else if (strpos($info["rc_price_type"], "월급") !== false) {
		$point = "월급";
	}

	if ($info["rc_price_rep"] == "일급" || $info["rc_price_rep"] == "주급" || $info["rc_price_rep"] == "월급") {
		$point = $info["rc_price_rep"];
	}


	if (count($arr_type) > 2) {
		return "<em>" . "급여협의" . "</em>";
	} else {
		foreach ($arr_type as $key => $txt) {
			if ($txt == $point) {
				$return .= ($return != "" ? "," : "") . "<em>" . $txt . "</em>";
			} else {
				$return .= ($return != "" ? "," : "") . "<span>" . $txt . "</span>";
			}
		}
	}
	return $return;
}


# 나의 타입
function getWorkType($work_type, $tag = "span", $max = 10)
{
	$return = "";
	$arr_type = explode(",", $work_type);

	if (count(explode(",", $work_type)) > 2) {
		return "<$tag>" . "근무협의" . "</$tag>";
	}

	foreach ($arr_type as $key => $txt) {
		if ($key < $max) {
			$return .= "<$tag>" . $txt . "</$tag>";
		}
	}

	return $return;
}
// getWorkType() 동일하지만 쉼표 제외
function getWorkTypeWithoutComma($work_type, $tag = "span", $max = 10)
{
	$return = "";
	$arr_type = explode(",", $work_type);

	if (count(explode(",", $work_type)) > 2) {
		return "<$tag>" . "근무협의" . "</$tag>";
	}

	foreach ($arr_type as $key => $txt) {
		if ($key < $max) {
			$return .= "<$tag>" . $txt . "</$tag>";
		}
	}

	return $return;
}
function getWorkTypeV2($work_type)
{
	$arr_type = explode(",", $work_type);
	if (count(explode(",", $work_type)) > 2) {
		return "<span>근무협의</span>";
	}
	return $work_type;
}

# 나의 타입
function getWorkTypePoint($work_type)
{
	$return = "";
	$arr_type = explode(",", $work_type);

	if (count($arr_type) > 2) {
		return "<span>" . "근무협의" . "</span>";
	} else {
		$return = "<span>";
		foreach ($arr_type as $key => $txt) {
			$return .= ($return != "<span>" ? ", " : "") . "" . $txt . "";
		}
		// foreach($arr_type as $key=>$txt){
		// 	if($txt=="주간"){
		// 		$return .= ($return!="" ? ",":"") . "<span>" . $txt . "</span>";
		// 	}else{
		// 		$return .= ($return!="" ? ", ":"") . "<span>" . $txt . "</span>";
		// 	}
		// }
		$return .= "</span>";
	}
	return $return;
}

# 근무타입 색상 포함 버전 (색상 클래스 + em 태그 래핑)
function getWorkTypeWithColor($work_type)
{
	$arr_type = explode(",", $work_type); // 쉼표로 구분된 근무 타입 문자열을 배열로 변환 ex: "주간,야간,2교대" => ["주간", "야간", "2교대"]

	// 3개 이상이면 "근무협의"만 표시
	if (count($arr_type) > 2) {
		return '<div class="worktype"><em class="wtype0">근무협의</em></div>';
	}

	$worktype_map = [
		"주간"  => "wtype1",
		"야간"  => "wtype2",
		"2교대" => "wtype3",
		"근무협의" => "wtype0"
	];

	$return = '<div class="worktype">';
	foreach ($arr_type as $key => $txt) {
		$txt = trim($txt);
		$class = isset($worktype_map[$txt]) ? $worktype_map[$txt] : "wtype0";
		$return .= '<em class="' . $class . '">' . $txt . '</em>';
	}
	$return .= '</div>';

	return $return;
}

# 나의 타입
function getLifeType($type, $add_class = "")
{
	$arr_target = [
		"국민연금"	=> "benefit1-1" . $add_class,
		"고용보험"	=> "benefit1-2" . $add_class,
		"산재보험"	=> "benefit1-3" . $add_class,
		"건강보험"	=> "benefit1-4" . $add_class,
		"연차" => "benefit2-1" . $add_class,
		"월차" => "benefit2-2" . $add_class,
		"여름휴가" => "benefit2-3" . $add_class,
		"야근근로수당" => "benefit3-1" . $add_class,
		"휴일근로수당" => "benefit3-2" . $add_class,
		"장기근속수당" => "benefit3-3" . $add_class,
		"연장근로수당" => "benefit3-4" . $add_class,
		"인센티브제" => "benefit3-5" . $add_class,
		"퇴직금" => "benefit3-6" . $add_class,
		"조식제공"	=> "benefit4-1" . $add_class,
		"중식제공"	=> "benefit4-2" . $add_class,
		"석식제공"	=> "benefit4-3" . $add_class,
		"근무복지급" => "benefit4-4" . $add_class,
		"통근버스운행" => "benefit4-5" . $add_class,
		"차량유류비지원" => "benefit4-6" . $add_class,
		"기숙사운영" => "benefit4-7" . $add_class
	];

	$return = "";
	if ($type == null || $type == "") {
		return "";
	}

	$arr_type = explode(",", $type);
	foreach ($arr_type as $txt) {
		$class = $arr_target[$txt];
		$return .= "<li class='item'><p class='benefit-label " . $class . "'>" . $txt . "</p></li>";
	}
	return $return;
}

function getHashTag($hash, $max = 10)
{
	$return = "";
	if ($hash == null || $hash == "") {
		return "";
	}
	//echo "1:[".$hash."]";
	$arr_hash = explode(",", $hash);
	//print_r($arr_hash);
	foreach ($arr_hash as $key => $txt) {
		if ($key < $max) {
			if (strpos($txt, "#") === false && trim($txt) != "") {
				$txt = "#" . trim($txt);
			}
			if (trim($txt) != "") {
				$return .= " <p class='tag'>$txt</p>";
			}
		}
	}
	return $return;
}


function getAstrik($txt)
{
	if (strlen($txt) >= 3) {
		$strim = mb_substr($txt, 0, 1, "UTF-8") . "**";
	} else if (strlen($txt) < 3) {
		$strim = mb_substr($txt, 0, 1, "UTF-8") . "*";
	}

	return $strim;
}

function getAstrikEmail($txt)
{
	if (strlen($txt) < 5) {
		return $txt;
	}

	$temp = explode("@", $txt);

	if (count($temp) > 1) {
		return "" . substr($temp[0], 0, 1) . "*****@" . $temp[1];
	} else {
		return $txt;
	}
}

function getAstrikKakao($txt)
{
	$result = "";
	if (strlen($txt) < 5) {
		return "*****";
	}

	for ($icnt = 0; $icnt < strlen($txt); $icnt++) {
		$result .= "*";
	}

	return $result;
}

function getAstrik2($txt)
{
	$strim = mb_substr($txt, '0', -2) . "**";
	return $strim;
}

function getAgeList($type)
{
	$return = [];
	for ($icnt = 20; $icnt < 60; $icnt++) {
		$return[] = $icnt;
	}
	return $return;
}

# 결제 상태
function getOrderStatus($order_status)
{
	$str = "";

	if ($order_status == "결제대기") {
		return "<span class='order-wait'>$order_status</span>";
	} else if ($order_status == "결제완료") {
		return "<span class='order-payed'>$order_status</span>";
	} else if ($order_status == "주문취소") {
		return "<span class='order-cancel'>결제취소</span>";
	}

	return $str;
}

# 적립상태
function getPointStatus($order_status)
{
	$str = "";

	if ($order_status == "plus") {
		return "<span class='order-payed'>적립</span>";
	} else if ($order_status == "minus") {
		return "<span class='order-cancel'>사용</span>";
	}

	return $str;
}

# 적립상태
function getCompTYpe($order_status)
{
	$str = "";

	if ($order_status == "PERSON") {
		return "<span class='order-wait'>개인</span>";
	} else if ($order_status == "COMP") {
		return "<span class='order-payed'>사업자</span>";
	} else if ($order_status == "OUT") {
		return "<span class='order-dlv-send'>아웃소싱</span>";
	}

	return $str;
}

function getUseYn($use_yn)
{
	$str = "";

	if ($use_yn == "Y") {
		return "<span class='order-payed'>사용</span>";
	} else if ($use_yn == "N") {
		return "<span class='order-cancel'>미사용</span>";
	}
	return $str;
}

function getLabel($str)
{
	switch ($str) {
		case "업무":
			return "cate1";
			break;
		case "일상":
			return "cate2";
			break;
		case "질문":
			return "cate3";
			break;
		case "자유":
			return "cate4";
			break;
	}
}

function getVisibled($use_yn)
{
	$str = "";

	if ($use_yn == "Y") {
		return "<span class='order-payed'>노출</span>";
	} else if ($use_yn == "N") {
		return "<span class='order-wait'>비노출</span>";
	}

	return $str;
}

# 공고 노출여부
function getRecruitStatus($yn)
{
	$str = "";

	if ($yn == "Y") {
		return "<span class='order-payed'>공개채용</span>";
	} else if ($yn == "N") {
		return "-";
	}

	return $str;
}

function getMemberStatus($yn)
{
	$str = "";

	if ($yn == "Y") {
		return "<span class='status-able'>회원가입</span>";
	} else if ($yn == "N") {
		return "<span class='status-disable'>탈퇴회원</span>";
	} else if ($yn == "S") {
		return "<span class='order-payed' style='color:#f33;border:solid 1px #f33;'>자격정지</span>";
	}

	return $str;
}

# 공고 노출여부
function getRecruitUseYn($yn)
{
	$str = "";

	if ($yn == "Y") {
		return "<span class='order-payed'>정상</span>";
	} else if ($yn == "H") {
		return "<span class='order-payed' style='color:#f33;border:solid 1px #f33;'>관리자비공개</span>";
	}

	return $str;
}


# 이력서 노출여부
function getResumeStatus($yn)
{
	$str = "";

	if ($yn == "Y") {
		return "<span class='order-payed'>공개이력서</span>";
	} else if ($yn == "N") {
		return "-";
	}

	return $str;
}

# 이력서 노출여부
function getPayMethod($paymethod)
{
	$str = "";

	if ($paymethod == "CARD") {
		return "카드결제";
	} else if ($paymethod == "BANK") {
		return "실시간계좌이체";
	} else if ($paymethod == "VBANK") {
		return "가상계좌";
	} else if ($paymethod == "COUPON") {
		return "쿠폰결제";
	}

	return $str;
}


# 업무 구분
function getAdminWorkingImg($work)
{
	$CI = &get_instance();
	if ($work == null || $work == "") {
		return "";
	}

	$target_list = $CI->common->getSessionDataList("WORK_TYPE");
	$arr = explode(",", $work);
	$return = "";

	foreach ($arr as $txt) {
		$src = $target_list[$txt];
		$return .= " <img style='height:22px;' src='" . $src["icon"] . "' class='category-label'> ";
	}
	return $return;
}

function getKoreanAge($birth)
{
	if ($birth == null || $birth == "") {
		return 0;
	}

	$birth = trim((string)$birth);
	// Normalize to YYYYMMDD (strip non-digits like "-" and ".")
	$birth = preg_replace('/[^0-9]/', '', $birth);
	// Expect YYYYMMDD. If invalid, return 0 to avoid exceptions.
	if (strlen($birth) !== 8 || !ctype_digit($birth)) {
		return 0;
	}
	$month = substr($birth, 4, 2);
	$day = substr($birth, 6, 2);
	if ($month === "00" || $day === "00") {
		return 0;
	}

	try {
		$birthday = DateTime::createFromFormat('Ymd', $birth);
		if (!$birthday) {
			return 0;
		}
		$referenceDate = new DateTime(Date('Y-m-d'));
		$age = $birthday->diff($referenceDate)->y;

		// 나이를 범위로 변환 (20세 → "20", 26세 → "20", 30세 → "30" 등)
		if ($age == 0) {
			return 0;
		}
		$age_range = (int)($age / 10) * 10;
		return (string)$age_range;
	} catch (Exception $e) {
		return 0;
	}
}

/**
 * 연령대 코드를 대표 생년월일(YYYYMMDD)로 변환
 * QuickInfo 바텀시트에서 "30대"처럼 범위로 받은 값을 st_member.mm_birth에 저장할 때 사용한다.
 * 각 연령대의 중간 나이를 대표값으로 쓴다. (20s=25세, 70s_plus=75세)
 *
 * @param  string $age_group "20s"|"30s"|"40s"|"50s"|"60s_plus"|"70s_plus"
 * @return string YYYYMMDD 형식 문자열. 알 수 없는 값이면 빈 문자열
 */
if (!function_exists('getBirthByAgeGroup')) {
	function getBirthByAgeGroup($age_group)
	{
		$age_group = trim((string)$age_group);
		if ($age_group == "") {
			return "";
		}

		// 연령대별 대표(중간) 나이
		$age_mid_list = [
			"20s" => 25,
			"30s" => 35,
			"40s" => 45,
			"50s" => 55,
			"60s_plus" => 65,
			"70s_plus" => 75,
		];

		if (!isset($age_mid_list[$age_group])) {
			return "";
		}

		$birth_year = intval(date('Y')) - $age_mid_list[$age_group];
		// 가입 시점(join_complete_proc)과 동일하게 YYYYMMDD 형식으로 통일
		return sprintf('%04d0101', $birth_year);
	}
}

/**
 * 생년월일을 연령대 코드로 역변환
 * QuickInfo 바텀시트에서 이미 알고 있는 생년을 미리 선택된 상태로 렌더링할 때 사용한다.
 * 가입 경로별로 형식이 제각각이라(YYYYMMDD / YYYY-MM-DD / 네이버식 YYYY0000) 모두 허용하고,
 * 월·일이 없어도 연도만으로 판단한다.
 *
 * @param  string $birth 생년월일
 * @return string "20s"~"70s_plus". 20세 미만이거나 알 수 없으면 빈 문자열(기본값 유지용)
 */
if (!function_exists('getAgeGroupByBirth')) {
	function getAgeGroupByBirth($birth)
	{
		if ($birth == null || $birth == "") {
			return "";
		}

		// 하이픈·점 등 구분자를 제거해 숫자만 남김
		$birth = preg_replace('/[^0-9]/', '', trim((string)$birth));
		if (strlen($birth) < 4) {
			return "";
		}

		$birth_year = intval(substr($birth, 0, 4));
		$current_year = intval(date('Y'));
		// 월·일이 비어 있는 값(YYYY0000)도 있으므로 연도만으로 나이 산출
		$age = $current_year - $birth_year;

		if ($age < 20 || $age > 120) {
			return "";
		}
		if ($age < 30) {
			return "20s";
		}
		if ($age < 40) {
			return "30s";
		}
		if ($age < 50) {
			return "40s";
		}
		if ($age < 60) {
			return "50s";
		}
		if ($age < 70) {
			return "60s_plus";
		}
		return "70s_plus";
	}
}

// function getTimeText($time)
// {
// 	// 빈값 또는 유효하지 않은 값 처리
// 	if (empty($time) || $time == '0000-00-00 00:00:00') {
// 		return '';
// 	}

// 	// 타임스탐프로 변환
// 	$time_unix = strtotime($time);
// 	if ($time_unix === false) {
// 		return '';
// 	}

// 	$now_unix = time();
// 	$diff_seconds = $now_unix - $time_unix;

// 	// 24시간 이내 (86400초 = 24시간)
// 	if ($diff_seconds < 86400) {
// 		$diff_minutes = floor($diff_seconds / 60);

// 		// 1시간 미만 (60분 미만)
// 		if ($diff_minutes < 60) {
// 			// 1분 미만일 경우 "방금 전"으로 표시
// 			if ($diff_minutes < 1) {
// 				return "방금 전";
// 			}
// 			return $diff_minutes . "분 전";
// 		}
// 		// 1시간 이상
// 		else {
// 			$diff_hours = floor($diff_minutes / 60);
// 			return $diff_hours . "시간 전";
// 		}
// 	}
// 	// 24시간 이후
// 	else {
// 		$diff_days = floor($diff_seconds / 86400);
// 		if ($diff_days < 30) {
// 			return $diff_days . "일 전";
// 		} else {
// 			$diff_months = floor($diff_days / 30);
// 			return $diff_months . "달 전";
// 		}
// 	}
// }
function getTimeText($time)
{
	$time = strtotime($time);
	$timeLater = time() - $time;
	if ($timeLater < 60) return "방금 전";
	else if ($timeLater < 60 * 60) return floor($timeLater / 60) . "분 전";
	else if ($timeLater < 60 * 60 * 24) return floor($timeLater / (60 * 60)) . "시간 전";
	else if ($timeLater < 60 * 60 * 24 * 30) return floor($timeLater / (60 * 60 * 24)) . "일 전";
	else return floor($timeLater / (60 * 60 * 24 * 30)) . "달 전";
}
function getAddrSimple($addr)
{
	$arr = explode(" ", $addr);
	$return = "";
	for ($i = 0; $i < count($arr); $i++) {
		if ($i > 0) {
			$return .= " " . $arr[$i];
		}
	}
	return $return;
}

// ============================================================
// 인재 카드 공통 함수 (bookmark_person, hr_list 공유)
// ============================================================

/**
 * 주소 축약 - 도/시 이름을 짧게 변환
 * 예: "서울특별시 강남구 역삼동" → "서울시 역삼동"
 */
function fRemoveBigAdr($arg)
{
	$parts1 = explode(",", $arg);
	$arg = $parts1[0];

	$replacements = [
		"서울특별시 " => "서울시 ",
		"경기도 " => "",
		"강원특별자치도 " => "",
		"충청남도 " => "",
		"충청북도 " => "",
		"경상남도 " => "",
		"경상북도 " => "",
		"전라남도 " => "",
		"전북특별자치도 " => "",
		"제주특별자치도 " => "",
		"세종특별자치시 " => "세종시 ",
		"광주광역시 " => "광주시 ",
		"인천광역시 " => "인천시 ",
		"울산광역시 " => "울산시 ",
		"부산광역시 " => "부산시 ",
		"대구광역시 " => "대구시 ",
		"대전광역시 " => "대전시 ",
		"경기 " => "",
		"강원 " => "",
		"충남 " => "",
		"충북 " => "",
		"경남 " => "",
		"경북 " => "",
		"전남 " => "",
		"전북 " => "",
		"제주 " => ""
	];

	foreach ($replacements as $search => $replace) {
		$arg = str_replace($search, $replace, $arg);
	}

	$parts = explode(" ", $arg);
	if (count($parts) >= 3) {
		return $parts[0] . " " . $parts[2];
	} else {
		return $arg;
	}
}



/**
 * 근무형태를 <em class="wtypeN"> HTML로 변환
 * 주간+야간+2교대 모두 선택 시 "근무협의"로 통합
 */
function getWorkType2025($work_type)
{
	if (!$work_type) {
		return "";
	}

	$items = [];

	if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
		$items = $matches[1];
	} else {
		$items = preg_split('/\s*,\s*/', $work_type);
	}

	$item_set = array_unique(array_map('trim', $items));

	$has_jugan = in_array('주간', $item_set);
	$has_yagan = in_array('야간', $item_set);
	$has_2shift = in_array('2교대', $item_set);

	if ($has_jugan && $has_yagan && $has_2shift) {
		return '<div class="worktype"><em class="wtype0">근무협의</em></div>';
	}

	$result = '<div class="worktype">';
	$count = 0;
	foreach ($item_set as $text) {
		if ($text === '') continue;

		if ($count > 0) {
			$result .= '<span class="work-divider">|</span>';
		}

		$cls = '';
		if ($text === '주간') {
			$cls = 'wtype1';
		} else if ($text === '야간') {
			$cls = 'wtype2';
		} else if ($text === '2교대') {
			$cls = 'wtype3';
		}

		$result .= '<em class="' . $cls . '">' . $text . '</em>';
		$count++;
	}
	$result .= '</div>';

	return $result;
}

// ============================================================
// 채용공고 카드 공통 함수 (recruit_card_row 파셜 공유)
// ============================================================

/**
 * 채용공고 근무형태를 <em class="wtypeN"> HTML로 변환 (공고 카드 파셜용)
 * getWorkType2025 래퍼 함수
 */
function getConvertWorkType($work_type)
{
	return getWorkType2025($work_type);
}

/**
 * 채용공고 급여유형을 <em> HTML로 변환
 * 2개 이상일 때: rc_price_rep과 일치하는 것은 #1b9d7d, 다른 것은 #777 색상 적용
 */
function getConvertPriceType($rc_price_type, $rc_price_rep)
{
	// rc_price_type: "일급,주급", rc_price_rep: "주급"
	// 1개: 기본색 #1b9d7d
	// 2개 이상: rc_price_rep과 일치하는 것은 기본색, 다른 것은 회색
	if (!$rc_price_type) {
		return '';
	}
	$types = array_map('trim', explode(',', $rc_price_type));

	if (count($types) === 1) {
		return '<em>' . $types[0] . '</em>';
	}

	$result = '';
	foreach ($types as $type) {
		if ($type === $rc_price_rep) {
			$result .= '<em class="on">' . $type . '</em>';
		} else {
			$result .= '<em class="off">' . $type . '</em>';
		}
	}

	return $result;
}

/**
 * 채용공고 급여금액 포맷 변환
 */
function getConvertPrice($rc_price_pay, $rc_price_type = '')
{
	if (!$rc_price_pay || $rc_price_type === '급여협의') {
		return '';
	}
	return number_format($rc_price_pay) . " 원";
}

/**
 * 급여유형을 <em> HTML로 변환
 * 일급+주급+월급 모두 포함 시 "급여협의"로 통합
 * 2개 이상일 때: 첫 번째 항목은 기본색(#1b9d7d), 나머지는 회색(#777, off 클래스)
 */
function getConvertResumePriceType($price_type)
{
	$allTypes = ['일급', '주급', '월급'];

	$containsAll = true;
	foreach ($allTypes as $type) {
		if (strpos($price_type, $type) === false) {
			$containsAll = false;
			break;
		}
	}

	if ($containsAll) {
		return '<em>급여협의</em>';
	}
	// 쉼표로 분리 후 공백 제거
	$types = preg_split('/\s*,\s*/', $price_type);
	// types = ["일급", "주급"] 또는 ["일급", "주급", "월급"] 등
	$result = '';
	$count = 0;
	$validTypes = array();
	foreach ($types as $type) {
		$type = trim($type);
		if ($type !== '') {
			$validTypes[] = $type;
		}
	}

	// 2개 이상일 때는 첫 번째만 기본색, 나머지는 off 클래스
	foreach ($validTypes as $index => $type) {
		if (count($validTypes) > 1 && $index > 0) {
			$result .= '<em class="off">' . $type . '</em>';
		} else {
			$result .= '<em>' . $type . '</em>';
		}
	}

	return $result;
}

/**
 * 회사명과 랜덤 설명을 조합한 회사 설명 생성
 * @param string $comp_name 회사명
 * @return string 회사명이 포함된 랜덤 설명 ("에서" 다음에 줄바꿈)
 */
function getRandomCompDesc($comp_name)
{
	$descriptions = [
		"에서<br>내일을 만들어 나갈 인재를 모집합니다.",
		"에서<br>함께 할 인재를 모집합니다.",
		"에서<br>직원을 모집합니다.",
		"에서<br>함께 성장할 인재를 모집합니다.",
		"에서<br>함께 할 인재를 찾습니다."
	];

	// 랜덤 인덱스 선택 (0 ~ count-1)
	$randomIndex = mt_rand(0, count($descriptions) - 1);

	// 회사명에만 색상 적용 + 랜덤 설명 조합
	return '<span style="color: #222;">' . $comp_name . '</span>' . '<span style="color: #666;">' . $descriptions[$randomIndex] . '</span>';
}

/**
 * 나이 범위를 포맷팅된 문자열로 변환
 * @param string $rc_sage 시작 나이 (예: "20", "0", "")
 * @param string $rc_eage 종료 나이 (예: "50", "0", "")
 * @return string 포맷팅된 나이 문자열
 * 예: ("20", "50") → "20 ~ 50대"
 *     ("20", "") → "20세 ~"
 *     ("", "50") → "~ 50대"
 *     ("", "") → "나이무관"
 *     ("0", "0") → "나이무관"
 */
function formatAgeRange($rc_sage, $rc_eage)
{
	$sage = trim((string)$rc_sage);
	$eage = trim((string)$rc_eage);

	if (($sage === '' || $sage === '0') && ($eage === '' || $eage === '0')) {
		return '나이무관';
	}

	if ($sage !== '' && $sage !== '0' && $eage !== '' && $eage !== '0') {
		return $sage . ' ~ ' . $eage . '대';
	}

	if ($sage !== '' && $sage !== '0') {
		return $sage . '세 ~';
	}

	if ($eage !== '' && $eage !== '0') {
		return '~ ' . $eage . '대';
	}

	return '나이무관';
}

/**
 * 급여 타입 포맷팅 (첫 번째 값만)
 * 추천인재 슬라이더에서 사용
 * @param string $price_type 급여 타입 (예: "일급,주급,월급")
 * @return string 첫 번째 급여 타입 HTML (예: "<em>일급</em>")
 */
function getPriceTypeFirst($price_type)
{
	if (!$price_type) {
		return "";
	}

	if ($price_type === "일급,주급,월급") {
		return "<em>급여협의</em>";
	}

	$types = array_map('trim', explode(',', $price_type));
	$types = array_filter($types);

	if (empty($types)) {
		return "";
	}

	return "<em>" . reset($types) . "</em>";
}

/**
 * 근무 형태 포맷팅 (첫 번째 값만)
 * 추천인재 슬라이더에서 사용
 * @param string $work_type 근무 형태 (예: "주간,야간,2교대" 또는 HTML <span>주간</span><span>야간</span>)
 * @return string 첫 번째 근무 형태 HTML (예: "<em class='wtype1'>주간</em>")
 */
function getWorkTypeFirst($work_type)
{
	if (!$work_type) {
		return "";
	}

	$items = [];

	// HTML span 형식 처리
	if (strpos($work_type, '<span>') !== false) {
		if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
			$items = $matches[1];
		}
	} else {
		// 쉼표로 구분된 일반 문자열 처리
		$items = preg_split('/\s*,\s*/', $work_type);
	}

	$items = array_filter(array_map('trim', $items));

	if (empty($items)) {
		return "";
	}

	// 첫 번째 값만 처리
	$text = reset($items);
	$cls = '';
	if ($text === '주간') {
		$cls = 'wtype1';
	} else if ($text === '야간') {
		$cls = 'wtype2';
	} else if ($text === '2교대') {
		$cls = 'wtype3';
	}

	return '<em class="' . $cls . '">' . $text . '</em>';
}

/**
 * 모바일/PC 구분하여 제목 길이 반환
 * @return int 모바일은 25, PC는 50
 */
function getTruncateLength()
{
	$isMobile = preg_match('/(Mobile|Android|iPhone|iPad|iPod)/', $_SERVER['HTTP_USER_AGENT']);
	return $isMobile ? 25 : 50;
}

/**
 * 채팅방 참여자 배열(rm_users)에서 특정 사용자가 나간 상태(exit_yn='Y')인지 판단
 * @param array $rm_users MongoDB room 문서의 rm_users 배열
 * @param string $user_id 로그인 사용자 ID (mm_id)
 * @param string|null $user_phone 로그인 사용자 전화번호 (mm_phone, 선택)
 * @return bool
 */
if (!function_exists('is_room_user_exited')) {
	function is_room_user_exited($rm_users, $user_id, $user_phone = null)
	{
		if (empty($rm_users) || !is_array($rm_users)) {
			return false;
		}
		foreach ($rm_users as $room_user) {
			$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
			$is_match = ($room_user_id == $user_id);
			if (!$is_match && $user_phone !== null && $user_phone !== "") {
				$is_match = ($room_user_id == $user_phone);
			}
			if ($is_match) {
				return isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y";
			}
		}
		return false;
	}
}

// 탈퇴 후 같은 정보(전화번호·DI·SNS계정)로 로그인·재가입을 막는 기간(일)
// 이 기간이 지나면 정리 배치(cleanup_withdrawn_members)가 개인정보를 삭제한다 - 차단 기간과 정리 시점은 항상 같은 값이어야 함
// (2026-10-21 15일 → 5일로 변경. 코드 주석의 "15일"은 이 상수를 뜻함. 화면 안내 문구도 이 상수를 출력)
if (!defined('WITHDRAW_BLOCK_DAYS')) {
	define('WITHDRAW_BLOCK_DAYS', 5);
}

/**
 * 최근 탈퇴(WITHDRAW_BLOCK_DAYS일 이내) 회원이면 재가입 가능일(Y-m-d), 아니면 false
 * - 대상 상태: 'N'(본인 탈퇴), 'H'(관리자 삭제)
 * - fire_dt가 비어 있으면 false (영구 차단 방지, 정리 배치가 fire_dt를 채움)
 * @param array|null $mem_info st_member 행
 * @return string|false
 */
if (!function_exists('get_withdrawn_rejoin_date')) {
	function get_withdrawn_rejoin_date($mem_info)
	{
		if (empty($mem_info) || !isset($mem_info["mm_status"]) || !in_array($mem_info["mm_status"], ["N", "H"], true)) {
			return false;
		}
		if (empty($mem_info["fire_dt"]) || substr($mem_info["fire_dt"], 0, 4) == "0000") {
			return false;
		}
		$rejoin_ts = strtotime($mem_info["fire_dt"] . " +" . WITHDRAW_BLOCK_DAYS . " days");
		if ($rejoin_ts === false || $rejoin_ts <= time()) {
			return false;
		}
		return date("Y-m-d", $rejoin_ts);
	}
}

/**
 * 탈퇴 회원 공통 안내 문구 (모든 로그인·재가입 경로에서 동일하게 사용)
 * @param array $mem_info 최근 탈퇴 회원 행 (findRecentWithdrawn 결과)
 * @return string
 */
if (!function_exists('withdrawn_message')) {
	function withdrawn_message($mem_info)
	{
		$rejoin_dt = get_withdrawn_rejoin_date($mem_info);
		if ($rejoin_dt === false) {
			return "탈퇴한 회원입니다.";
		}
		return "탈퇴한 회원입니다.\n(" . $rejoin_dt . " 이후 재가입 가능합니다.)";
	}
}

/**
 * 채팅방 참여자(user_id = mm_id 또는 전화번호)가 탈퇴 회원인지 확인
 * - 탈퇴(N)/관리자삭제(H)면 true
 * - 회원 행이 없으면(탈퇴 정리 후 mm_id가 del_{mm_idx}로 바뀌고 전화번호가 비워진 경우) true
 * - 조회 실패 시 false (오류 때문에 정상 회원의 복구를 막지 않음)
 * @param string $user_id 채팅 user_id
 * @return bool
 */
if (!function_exists('is_withdrawn_chat_user')) {
	function is_withdrawn_chat_user($user_id)
	{
		if ($user_id === "" || $user_id === null) {
			return false;
		}
		try {
			$CI =& get_instance();
			$row = $CI->db->query(
				"SELECT mm_status FROM st_member WHERE mm_id = ? OR mm_phone = ? ORDER BY (mm_status = 'Y') DESC LIMIT 1",
				array($user_id, $user_id)
			)->row_array();
			if (!$row) {
				return true;
			}
			return in_array($row["mm_status"], array("N", "H"), true);
		} catch (Exception $e) {
			return false;
		}
	}
}

/**
 * MongoDB room의 exit_yn 상태를 Y→N으로 복구 (나간 사용자 복구)
 * @param string $mongo_uri MongoDB 연결 URI
 * @param string $mongo_db MongoDB 데이터베이스명
 * @param int $rm_idx 채팅방 ID
 */
if (!function_exists('reset_room_exit_status')) {
	function reset_room_exit_status($mongo_uri, $mongo_db, $rm_idx)
	{
		if (empty($rm_idx)) {
			return;
		}

		try {
			@$mongo = new Mongo($mongo_uri);
			$database = $mongo->{$mongo_db};
			$collection = $database->room;

			// 채팅방 조회
			$query = ['rm_idx' => (int)$rm_idx];
			$room = $collection->findOne($query);

			if (!$room) {
				return;
			}

			// rm_users 배열 처리
			// 재요청(제안/지원/채팅)이 들어오면 대화가 다시 활성화되는 것이므로,
			// 요청자 본인 여부와 상관없이 나갔던(exit_yn=Y) 모든 참여자를 복구한다.
			// 단, 탈퇴 회원은 복구하지 않는다 (탈퇴 시 나감 처리는 영구적이어야 함 - chat_server.js 상대방 복구와 동일 정책)
			$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
			$updated = false;

			foreach ($rm_users as $key => $room_user) {
				if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
					$room_user_id = isset($room_user["user_id"]) ? $room_user["user_id"] : "";
					if (is_withdrawn_chat_user($room_user_id)) {
						continue;
					}
					$rm_users[$key]["exit_yn"] = "N";
					// exit_dt는 보존 (메시지 필터링 기준점으로 사용)
					$updated = true;
				}
			}

			// rm_status가 CLOSED였다면 활성 상태로 되돌림
			$update_data = [];
			if ($updated) {
				$update_data['$set']['rm_users'] = $rm_users;
			}

			if (isset($room["rm_status"]) && $room["rm_status"] == "CLOSED") {
				$update_data['$set']['rm_status'] = "N";
			}

			// 업데이트가 필요한 경우만 실행
			if (!empty($update_data)) {
				$collection->update($query, $update_data);
			}
		} catch (Exception $e) {
			// MongoDB 연결 실패는 무시 - 제안/지원/채팅 흐름 자체를 막지 않음
		}
	}
}

/**
 * 방의 rm_users 중 exit_dt가 한 번이라도 설정된 적 있는 참여자가 있는지 확인
 * (현재 exit_yn 상태는 보지 않음 - 본인/상대방 구분 없이 둘 중 한쪽이라도 나간 적 있으면 true.
 *  exit_dt는 reset_room_exit_status()가 exit_yn만 복구하고 절대 지우지 않으므로
 *  "과거에 나간 적 있는지"를 판별하는 영구적인 신호로 쓸 수 있다)
 * @param int $rm_idx 채팅방 ID
 * @return bool
 */
if (!function_exists('has_room_exit_history')) {
	function has_room_exit_history($rm_idx)
	{
		if (empty($rm_idx)) {
			return false;
		}
		try {
			$uri = get_mongo_uri();
			$dbName = get_mongo_db_name();
			@$mongo = new Mongo($uri);
			$collection = $mongo->{$dbName}->room;
			$room = $collection->findOne(array('rm_idx' => (int)$rm_idx), array('rm_users' => 1));
			if (!$room || empty($room['rm_users'])) {
				return false;
			}
			foreach ($room['rm_users'] as $room_user) {
				if (isset($room_user['exit_dt']) && !empty($room_user['exit_dt'])) {
					return true;
				}
			}
		} catch (Exception $e) {
			error_log("[has_room_exit_history] MongoDB Exception: " . $e->getMessage());
		}
		return false;
	}
}

/**
 * 여러 rm_idx를 한 번에 MongoDB에서 조회해 현재 사용자가 나간 방 id 목록만 반환
 * @param array $rm_idxs rm_idx 배열
 * @param string $user_id 사용자 ID (mm_id)
 * @param string $user_phone 사용자 전화번호 (대체 매칭용)
 * @return array 나간 방 rm_idx 배열
 */
if (!function_exists('get_exited_rm_idxs')) {
	function get_exited_rm_idxs($rm_idxs, $user_id, $user_phone = null)
	{
		if (empty($rm_idxs)) {
			return array();
		}

		$exited = array();
		try {
			$uri = get_mongo_uri();
			$dbName = get_mongo_db_name();
			@$mongo = new Mongo($uri);
			$collection = $mongo->{$dbName}->room;

			// rm_idx 배열을 정수로 변환 (레거시 Mongo 드라이버 호환성, PHP 5.4)
			$rm_idx_ints = array();
			foreach (array_unique($rm_idxs) as $idx) {
				$rm_idx_ints[] = (int)$idx;
			}
			$query = array('rm_idx' => array('$in' => $rm_idx_ints));
			$cursor = $collection->find($query, array('rm_idx' => 1, 'rm_users' => 1));

			foreach ($cursor as $room) {
				$rm_users = isset($room['rm_users']) ? $room['rm_users'] : array();
				if (is_room_user_exited($rm_users, $user_id, $user_phone)) {
					$exited[] = $room['rm_idx'];
				}
			}
		} catch (Exception $e) {
			// MongoDB 오류 시 빈 배열 반환 (기존 동작 유지, 배지는 그대로 보여줌)
			error_log("[get_exited_rm_idxs] MongoDB Exception: " . $e->getMessage());
		}
		return $exited;
	}
}
