<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
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

	$work_type = explode(",", $info["rc_work"]);
	$type = $work_type[0];
	$random = mt_rand(1, 3);
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
	$sql = " SELECT CASE WHEN IFNULL(cd_val6,'') <> '' THEN CASE FLOOR(RAND() * 4) WHEN 0 THEN cd_val3 WHEN 1 THEN cd_val4 WHEN 2 THEN cd_val5 ELSE cd_val6 END ELSE CASE FLOOR(RAND() * 2) WHEN 0 THEN cd_val3 ELSE cd_val4 END END AS random_value  FROM st_code WHERE cd_grp = '희망업무' AND cd_key = '" . $type . "' order by rand() limit 1 ";
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

	if (count(explode(",", $price_type)) > 2) {
		return ($return != "" ? "," : "") . "<em>급여협의</em>";
	}

	if (strpos($price_type, "일급") !== false) {
		$return .= ($return != "" ? "," : "") . "<em>일급</em>";
		$num++;
	}

	if ($num >= $max) {
		return $return;
	}

	if (strpos($price_type, "주급") !== false) {
		$return .= ($return != "" ? "," : "") . "<em>주급</em>";
		$num++;
	}

	if ($num >= $max) {
		return $return;
	}

	if (strpos($price_type, "월급") !== false) {
		$return .= ($return != "" ? "," : "") . "<em>월급</em>";
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
			$return .= ($return != "" ? "," : "") . "<$tag>" . $txt . "</$tag>";
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
	} else {
		$birthday = new DateTime($birth);
		$referenceDate = new DateTime(Date('Y-m-d'));
		$age = $birthday->diff($referenceDate)->y;
		return $age;
	}
}

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
