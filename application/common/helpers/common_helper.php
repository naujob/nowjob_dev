<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function get_mongo_uri()
{
	$CI = &get_instance();
	return isset($CI->config->config['mongo_uri']) ? $CI->config->config['mongo_uri'] : 'mongodb://localhost';
}

function get_mongo_db_name()
{
	$CI = &get_instance();
	return isset($CI->config->config['mongo_db']) ? $CI->config->config['mongo_db'] : 'chat';
}

function get_mongodb($name)
{
	$m = new MongoClient("mongodb://127.0.0.1/");
	$db = $m->chat;
	$collection = null;
	if ($name == "user") {
		$collection = $db->user;
	} else if ($name == "alarm") {
		$collection = $db->alarm;
	} else if ($name == "noti") {
		$collection = $db->notification;
	} else if ($name == "room") {
		$collection = $db->room;
	} else if ($name == "table_key") {
		$collection = $db->table_key;
	}

	return $collection;
}

function key_mongodb($tbl_name)
{
	$collection = get_mongodb("table_key");
	$info = $collection->findOne(['_id' => $tbl_name]);
	$collection->update(
		['_id' => $tbl_name],
		['$set' => ['seq' => $info['seq'] + 1]]
	);
	return $info["seq"];
}

# 배열 확인 리턴
function in_array_return($taraget, $arr, $format = "bool")
{
	$is_array = true;
	if (!is_array($arr)) {
		$is_array = false;
	}

	if ($is_array == true) {
		if (in_array($taraget, $arr)) {
			if ($format == "check") {
				return "checked";
			} else if ($format == "select") {
				return "selected";
			} else if ($format == "bool") {
				return true;
			}
		}
	}

	if ($format == "checked") {
		return "";
	} else if ($format == "selected") {
		return "";
	} else if ($format == "bool") {
		return false;
	}
}

function equal_return($data, $target, $format = "bool")
{
	if ($data == $target) {
		if ($format == "check") {
			return "checked";
		} else if ($format == "select") {
			return "selected";
		} else if ($format == "bool") {
			return true;
		}
	} else {
		if ($format == "checked") {
			return "";
		} else if ($format == "selected") {
			return "";
		} else if ($format == "bool") {
			return false;
		}
	}
}

# GET 기본값 설정
function input_get($namne, $default = "")
{
	$CI = &get_instance();
	$val = $CI->input->get($namne);
	if ($val == null) {
		$val = $default;
	}
	return $val;
}

# POST 기본값 설정
function input_post($namne, $default = "")
{
	$CI = &get_instance();
	$val = $CI->input->post($namne);
	if ($val == null) {
		$val = $default;
	}
	return $val;
}

# 전체 post 값 가져온다.
function input_post_all($xss = TRUE)
{
	$CI = &get_instance();
	return $CI->input->post(NULL, $xss);
}

# 전체 post 값 가져온다.
function input_get_all($xss = TRUE)
{
	$CI = &get_instance();
	return $CI->input->get(NULL, $xss);
}

# json 데이터 결과 처리
function json_return($code, $msg = "", $arr_data = array())
{
	$CI = &get_instance();
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	echo json_encode(["code" => $code, "msg" => $msg, "data" => $arr_data]);
	//$CI->output->set_content_type('application/json')->set_output(json_encode($arr_data, JSON_UNESCAPED_UNICODE));
	exit;
}

//체크박스 처리
function get_chk_string($chk_list, $digit = "^")
{
	$str_checked = "";
	foreach ($chk_list as $ins) {
		if ($str_checked == "") {
			$str_checked = $ins;
		} else {
			$str_checked .=  $digit . $ins;
		}
	}
	return $str_checked;
}

function check_app_type()
{
	$sUserAgent = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($sUserAgent, 'iPhone') || strpos($sUserAgent, 'itouch') || strpos($sUserAgent, 'ipod')) {
	} else {
	}
}

function get_yyyymmdd($date)
{
	return str_replace("-", ".", substr($date, 0, 10));
}

# yyyy-mm-dd 리턴
function get_date($date)
{
	return substr($date, 0, 10);
}

# 요일 구하기
function get_yoil($date)
{
	if ($date == null || $date == "") {
		return "";
	}

	$yoil = array("일", "월", "화", "수", "목", "금", "토");
	return $yoil[date('w', strtotime($date))];
}

# 두날짜 비교하여 년월 구하기
function get_date_diff_txt($firstDate, $secondDate)
{
	if ($firstDate == null || $secondDate == "" || $secondDate == null || $secondDate == "") {
		return "";
	}

	$dateDifference = abs(strtotime($secondDate) - strtotime($firstDate));
	$years  = floor($dateDifference / (365 * 60 * 60 * 24));
	$months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

	if ($years > 0) {
		return $years . "년 " . $months . "개월";
	} else {
		return $months . "개월";
	}
}

function arr_implode($dig, $arr)
{
	if ($arr == null || $arr == "") {
		return "";
	} else {
		return implode($dig, $arr);
	}
}

function chk_remote_file($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	// don't download content
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if (curl_exec($ch) !== FALSE) {
		return true;
	} else {
		return false;
	}
}

//엑셀 컬럼 가져오기
function excel_colnm($num)
{
	$numeric = $num % 26;
	$letter = chr(65 + $numeric);
	$num2 = intval($num / 26);
	if ($num2 > 0) {
		return excel_colnm($num2 - 1) . $letter;
	} else {
		return $letter;
	}
}

//현재 URL
function current_url()
{
	return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

//웹 root 경로
// 예시: /var/www/html/
function get_base_url()
{
	return $_SERVER['DOCUMENT_ROOT'];
}

function make_token($num = 16)
{
	$token = openssl_random_pseudo_bytes(16);
	$token = bin2hex($token);
	return $token;
}

function make_unique($disit)
{
	return strtoupper(sprintf($disit . '-' . '%04x-%04x-%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
}

function make_pwd()
{
	return "pw" . mt_rand(100000, 999999);
}

function get_today()
{
	return Date('Y-m-d H:i:s');
}

function get_app_result($code, $msg = "", $data = null)
{
	return array(
		"code" => $code,
		"msg" => $msg,
		"data" => $data
	);
}

function get_page_info($page, $total, $size)
{
	$paging['last_page'] = ceil($total / $size);
	$paging['current_page'] = $page;
	$paging['suffix'] = '';
	$paging['url'] =  remove_param("page", $page);

	return get_pagenation($paging);
}

function get_page_ajax($page, $total, $size)
{
	$paging['last_page'] = ceil($total / $size);
	$paging['current_page'] = $page;
	$paging['suffix'] = '';
	$paging['url'] =  remove_param("page", $page);

	return get_pagenation_ajax($paging);
}

function get_excel_null($value)
{
	if (is_numeric($value) || is_float($value)) {
		return "" . $value;
	}

	if (strtoupper($value) == "NULL" || $value == null) {
		return "";
	} else {
		return $value;
	}
}

//파라미터제거
function remove_param($param, $data)
{
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace("?" . $param . "=" . $data, "", $url);
	$url = str_replace("&" . $param . "=" . $data, "", $url);
	return $url;
}

//SQL 인젠션 처리
function inject($str)
{
	return mysql_real_escape_string($str);
}

function get_pagenation($configure = false)
{
	if (is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
		$digit = strpos($configure['url'], "?") == false ? "?" : "&";
		$configure['last_page'] = (int)$configure['last_page'];

		$html_return_string = '<div class="pagination">';
		$page = (int)(($configure['current_page'] - 1) / 10) * 10;

		if (($page != 0) && ($configure['last_page'] > 10)) {
			if (($configure['last_page'] > 20) && ($configure['current_page'] > 20)) {
				$html_return_string .= '<a class="btn page-front" href="' . ($configure['url'] . $digit . 'page=1' . $configure['suffix']) . '"><span class="sr-text">맨앞으로</span></a>';
			} else {
				$html_return_string .= '<a class="btn page-front" href="' . ($configure['url'] . $digit . 'page=' . (1) . $configure['suffix']) . '"><span class="sr-text">첫 페이지</span></a>';
			}
			//$html_return_string .= '<a class="btn page-prev" href="' . ($configure['url'] . $digit . 'page=' . ($page-9) . $configure['suffix']) . '"><span class="sr-text">이전페이지</span></a>';
			$html_return_string .= '<a class="btn page-prev" href="' . ($configure['url'] . $digit . 'page=' . ($configure['current_page'] - 1) . $configure['suffix']) . '"><span class="sr-text">이전페이지</span></a>';
		} else {
			$html_return_string .= '<a class="btn page-front" href="' . ($configure['url'] . $digit . 'page=' . (1) . $configure['suffix']) . '"><span class="sr-text"><span class="sr-text">맨앞으로</span></span></a>';
			//$html_return_string .= '<a class="btn page-prev" href="' . ($configure['url'] . $digit . 'page=' . (1) . $configure['suffix']) . '"><span class="sr-text"><span class="sr-text">이전페이지</span></span></a>';
			if ($configure['current_page'] > 1) {
				$html_return_string .= '<a class="btn page-prev" href="' . ($configure['url'] . $digit . 'page=' . ($configure['current_page'] - 1) . $configure['suffix']) . '"><span class="sr-text"><span class="sr-text">이전페이지</span></span></a>';
			}
		}

		$loop_page_count = (($configure['last_page'] - $page) >= 10) ? 10 : $configure['last_page'] - $page;

		for ($i = $page + 1; $i <= $page + $loop_page_count; $i++) {
			if ($i == $configure['current_page']) {
				$html_return_string .= '<span class="page-link current">' . $i . '</span>';
			} else {
				$html_return_string .= '<a class="page-link" href="' . ($configure['url'] . $digit . 'page=' . $i . $configure['suffix']) . '">' . $i . '</a>';
			}
		}

		if (($configure['last_page'] > 10) && ($page + 10 < $configure['last_page'])) {
			//$html_return_string .= '<a class="btn page-next" href="' . ($configure['url'] . $digit . 'page=' . ($page+11) . $configure['suffix']) . '" ><span class="sr-text">다음</span></a>';
			$html_return_string .= '<a class="btn page-next" href="' . ($configure['url'] . $digit . 'page=' . ($configure['current_page'] + 1) . $configure['suffix']) . '" ><span class="sr-text">다음</span></a>';
			if (($configure['last_page'] - $page) > 20) {
				//if(is_int($configure['last_page'] / 10)) {
				//	$html_return_string .= '<a class="btn page-last" href="' . ($configure['url'] . $digit . 'page=' . (((int)($configure['last_page'] / 10))*10) . $configure['suffix']) . '"><span class="sr-text">맨뒤로</span></a>';
				//} else {
				//	$html_return_string .= '<a class="btn page-last" href="' . ($configure['url'] . $digit . 'page=' . (((int)($configure['last_page'] / 10))*10 + 1) . $configure['suffix']) . '"><span class="sr-text">맨뒤로</span></a>';
				//}
				$html_return_string .= '<a class="btn page-last" href="' . ($configure['url'] . $digit . 'page=' . ($configure['last_page']) . $configure['suffix']) . '"><span class="sr-text">맨뒤로</span></a>';
			} else {
				$html_return_string .= '<a class="btn page-last" href="' . ($configure['url'] . $digit . 'page=' . ($configure['last_page']) . $configure['suffix']) . '"><span class="sr-text">맨뒤로</span></a>';
			}
		} else {
			//$html_return_string .= '<a class="btn page-next" href="javascript:void(0);"><span class="sr-text">다음페이지</span></a>';
			if ($configure['current_page'] + 1 <= $configure['last_page']) {
				$html_return_string .= '<a class="btn page-next" href="' . ($configure['url'] . $digit . 'page=' . ($configure['current_page'] + 1) . $configure['suffix']) . '">' . ($configure['current_page'] + 1) . '</a>';
			}
			//$html_return_string .= '<a class="btn page-last" href="javascript:void(0);"><span class="sr-text">맨뒤로</span></a>';
			$html_return_string .= '<a class="btn page-last" href="' . ($configure['url'] . $digit . 'page=' . ($configure['last_page']) . $configure['suffix']) . '"><span class="sr-text">맨뒤로</span></a>';
		}

		$html_return_string .= '</div>';

		return $html_return_string;
	} else {
		return false;
	}
}

function get_pagenation_ajax($configure = false)
{
	if (is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
		$digit = strpos($configure['url'], "?") == false ? "?" : "&";
		$configure['last_page'] = (int)$configure['last_page'];

		$html_return_string = '<div class="pagination">';
		$page = (int)(($configure['current_page'] - 1) / 10) * 10;

		if (($page != 0) && ($configure['last_page'] > 10)) {
			if (($configure['last_page'] > 20) && ($configure['current_page'] > 20)) {
				$html_return_string .= '<a class="btn page-front" href="javascript:goPage(1);"><span class="sr-text">맨앞으로</span></a>';
			} else {
				$html_return_string .= '<a class="btn page-front" href="javascript:void(0);"><span class="sr-text">첫 페이지</span></a>';
			}
			$html_return_string .= '<a class="btn page-prev" href="javascript:goPage(' . ($page - 9) . ');"><span class="sr-text">이전페이지</span></a>';
		} else {
			$html_return_string .= '<a class="btn page-front" href="javascript:goPage(1);"><span class="sr-text"><span class="sr-text">맨앞으로</span></span></a>';
			$html_return_string .= '<a class="btn page-prev" href="javascript:goPage(' . ($configure['current_page'] == '1' ? '1' : ($configure['current_page'] - 1)) . ');"><span class="sr-text"><span class="sr-text">이전페이지</span></span></a>';
		}

		$loop_page_count = (($configure['last_page'] - $page) >= 10) ? 10 : $configure['last_page'] - $page;

		for ($i = $page + 1; $i <= $page + $loop_page_count; $i++) {
			if ($i == $configure['current_page']) {
				$html_return_string .= '<span class="page-link current">' . $i . '</span>';
			} else {
				$html_return_string .= '<a class="page-link" href="javascript:goPage(' . ($i) . ');">' . $i . '</a>';
			}
		}

		if (($configure['last_page'] > 10) && ($page + 10 < $configure['last_page'])) {
			$html_return_string .= '<a class="btn page-next" href="javascript:goPage(' . (($page + 11)) . ');"><span class="sr-text">다음</span></a>';
			if (($configure['last_page'] - $page) > 20) {
				if (is_int($configure['last_page'] / 10)) {
					$html_return_string .= '<a class="btn page-last" href="javascript:goPage(' . (((int)($configure['last_page'] / 10)) * 10) . ');"><span class="sr-text">다음페이지</span></a>';
				} else {
					$html_return_string .= '<a class="btn page-last" href="javascript:goPage(' . (((int)($configure['last_page'] / 10)) * 10 + 1) . ');"><span class="sr-text">맨뒤로</span></a>';
				}
			}
		} else {
			$html_return_string .= '<a class="btn page-next" href="javascript:goPage(' . ($configure['current_page'] == $configure['last_page'] ? $configure['last_page'] : ($configure['current_page'] + 1)) . ');"><span class="sr-text">다음페이지</span></a>';
			$html_return_string .= '<a class="btn page-last" href="javascript:goPage(' . ($configure['last_page']) . ');"><span class="sr-text">맨뒤로</span></a>';
		}

		$html_return_string .= '</div>';

		return $html_return_string;
	} else {
		return false;
	}
}

function get_pagenation_mobile($configure = false)
{
	if (is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
		$digit = strpos($configure['url'], "?") == false ? "?" : "&";
		$configure['last_page'] = (int)$configure['last_page'];

		$html_return_string = '<div class="box-pagenation"><ul>';
		$page = (int)(($configure['current_page'] - 1) / 5) * 5;

		if (($page != 0) && ($configure['last_page'] > 5)) {
			$html_return_string .= '<li class="prev"><a href="' . ($configure['url'] . $digit . 'page=' . ($page - 4) . $configure['suffix']) . '"><img src="/resource/shop/basic/mobile/img/icon/angle-left.svg" /></a></li>';
		} else {
			$html_return_string .= '<li class="prev disable"><a href="javascript:void(0);"><img src="/resource/shop/basic/mobile/img/icon/angle-left.svg" /></a></li>';
		}

		$loop_page_count = (($configure['last_page'] - $page) >= 5) ? 5 : $configure['last_page'] - $page;

		for ($i = $page + 1; $i <= $page + $loop_page_count; $i++) {
			if ($i == $configure['current_page']) {
				$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . $i . $configure['suffix']) . '" class="current">' . $i . '</a></li>';
			} else {
				$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . $i . $configure['suffix']) . '">' . $i . '</a></li>';
			}
		}

		if (($configure['last_page'] > 5) && ($page + 5 < $configure['last_page'])) {
			if (($configure['last_page'] - $page) > 5) {
				$html_return_string .= '<li class="next"><a href="' . ($configure['url'] . $digit . 'page=' . ($page + 6) . $configure['suffix']) . '" class="nextpage"><img src="/resource/shop/basic/mobile/img/icon/angle-right.svg" /></a></li>';
			}
		} else {
			$html_return_string .= '<li class="next disable"><a href="javascript:void(0);" class="nextpage"><img src="/resource/shop/basic/mobile/img/icon/angle-right.svg" /></a></li>';
		}

		$html_return_string .= '</ul></div>';

		return $html_return_string;
	} else {
		return false;
	}
}

function device_type()
{
	$headers = apache_request_headers();
	if (isset($headers["os_type"]) && $headers["os_type"] != "") {
		return "M";
	}

	$mobilechk = '/(iPod|iPad|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/i';
	if (empty($_SERVER['HTTP_USER_AGENT'])) {
		return "W";
	}

	if (preg_match($mobilechk, $_SERVER['HTTP_USER_AGENT'])) {
		return "M";
	} else {
		return "W";
	}
}


function go_location($loc, $msg = "")
{
	if ($msg != "") {
		$msg = str_replace("'", "\'", $msg);
		echo "<script>alert('" . $msg . "');</script>";
	}

	if (strpos($loc, "http") === false) {
		$loc = get_current_domain() . $loc;
	}

	redirect($loc, 'refresh');
	exit;
}

function go_href($loc, $msg = "")
{
	if ($msg != "") {
		$msg = str_replace("'", "\'", $msg);
		echo "<script>alert('" . $msg . "');</script>";
	}

	if (strpos($loc, "http") === false) {
		//$loc = get_current_domain() . $loc;
	}

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $loc);
	exit;
}

function go_back($msg = "")
{
	if ($msg != "") {
		$msg = str_replace("'", "\'", $msg);
		echo "<script>alert('" . $msg . "');</script>";
	}
	echo "<script>history.go(-1);</script>";
	exit;
}

function get_current_domain()
{
	return $location = 'https://' . $_SERVER['HTTP_HOST'];
}

//API 호출
function sys_api($api_name, $data)
{
	$post_field_string = http_build_query($data, '', '&');
	$ch = curl_init(); // curl 초기화
	curl_setopt($ch, CURLOPT_URL, "http://api.sysnet.kr/app/" . $api_name);	// url 지정하기
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// 요청결과를 문자열로 반환
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);	// connection timeout : 10초
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// 원격 서버의 인증서가 유효한지 검사 여부
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);	// POST DATA
	curl_setopt($ch, CURLOPT_POST, true);	// POST 전송 여부
	$response = curl_exec($ch);
	//echo $response;
	curl_close($ch);
	$data = json_decode($response, true);
	return $data;
}

function call_curl_post($url, $data)
{
	$post_field_string = http_build_query($data, '', '&');
	$ch = curl_init(); // curl 초기화
	curl_setopt($ch, CURLOPT_URL, $url);	// url 지정하기
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// 요청결과를 문자열로 반환
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);	// connection timeout : 10초
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// 원격 서버의 인증서가 유효한지 검사 여부
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);	// POST DATA
	curl_setopt($ch, CURLOPT_POST, true);	// POST 전송 여부
	$response = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($response, true);
	return $data;
}

function call_curl_get($url)
{
	$ch = curl_init(); // curl 초기화
	curl_setopt($ch, CURLOPT_URL, $url);	// url 지정하기
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// 요청결과를 문자열로 반환
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);	// connection timeout : 10초
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// 원격 서버의 인증서가 유효한지 검사 여부
	$response = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($response, true);
	return $data;
}

function curl_post_normal($url, $data)
{
	$post_field_string = http_build_query($data, '', '&');
	$ch = curl_init(); // curl 초기화
	curl_setopt($ch, CURLOPT_URL, $url);	// url 지정하기
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// 요청결과를 문자열로 반환
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);	// connection timeout : 10초
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// 원격 서버의 인증서가 유효한지 검사 여부
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);	// POST DATA
	curl_setopt($ch, CURLOPT_POST, true);	// POST 전송 여부
	$response = curl_exec($ch);

	return $response;
}



//암호화
function sys_encrypt($str, $secret_key = 'secret key', $secret_iv = 'secret iv')
{
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	return str_replace(
		"=",
		"",
		base64_encode(
			openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv)
		)
	);
}

function sys_decrypt($str, $secret_key = 'secret key', $secret_iv = 'secret iv')
{
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	return openssl_decrypt(
		base64_decode($str),
		"AES-256-CBC",
		$key,
		0,
		$iv
	);
}

//설정정보로 스토어 키값 획득
function get_store_key($setting)
{
	return sys_encrypt(($setting["st_admin_type"] == "admin" ? "admin" : $setting["st_store_code"]), $setting["st_sec_key"], $setting["st_sec_vi"]);
}

function get_site_domain()
{
	$server_name = $_SERVER['SERVER_NAME'];
	$server_name = str_replace("admin.", "", $server_name);
	$server_name = str_replace("home.", "", $server_name);
	$server_name = str_replace("api.", "", $server_name);
	return "https://" . $server_name;
}

function get_admin_domain()
{
	$server_name = $_SERVER['SERVER_NAME'];
	$server_name = str_replace("admin.", "", $server_name);
	$server_name = str_replace("home.", "", $server_name);
	return "http://" . "admin." . $server_name;
}

function file_ext($filename)
{
	if ($filename == "") {
		return "";
	}
	$fileinfo = pathinfo($filename);
	$ext = $fileinfo['extension'];
	return $ext;
}

function file_remove($path)
{
	if ($path != "") {
		if (is_file(get_base_url() . $path) == true) {
			unlink(get_base_url() . $path);
		}
	}
}

/* 소수점 계산 오류 수정 */
function toFixed($value, $digit = 0)
{
	return fixedPos($value) * numFloor(abs($value), $digit);
}

function numFloor($value, $digit)
{
	return floor($value * pow(10, $digit)) / pow(10, $digit);
}

function fixedPos($n = 1)
{
	if ($n > 0) {
		return 1;
	} else {
		return -1;
	}
}


//캐싱 css
function getCachCss($path)
{
	return "<link type='text/css' rel='stylesheet' href='" . $path . "?vs=" . getFileVersion($path) . "' />\n";
}

//캐싱 js
function getCashJs($path)
{
	return "<script src='" . $path . "?vs=" . getFileVersion($path) . "'></script>\n";
}


//파일 수정시간 세션설정
function getFileVersion($filepath)
{
	if (file_exists(getcwd() . $filepath)) {
		return  filemtime(getcwd() . $filepath);
	} else {
		return  "failversion";
	}
}

# FCM 발송 (HTTP v1 API 버전)
# Firebase Admin SDK를 사용한 새로운 API
function sendFcm($info, $token_list)
{
	// 디버그 로그 추가
	debug_log("FCM 함수 시작", 'FCM', ['info' => $info, 'token_count' => is_array($token_list) ? count($token_list) : 0]);

	// 토큰 로그 추가
	//error_log("FCM 발송 시도 - 제목: " . (isset($info['title']) ? $info['title'] : '없음') . ", 토큰 개수: " . (is_array($token_list) ? count($token_list) : 0));

	// 입력값 검증
	if (!is_array($info) || !isset($info['title']) || !isset($info['body'])) {
		debug_log("FCM 입력값 검증 실패", 'FCM', ['info' => $info]);
		//error_log("FCM 발송 실패 - 필수 정보 누락 (title, body)");
		return [
			'success' => false,
			'error' => '필수 정보가 누락되었습니다 (title, body)',
			'result' => null
		];
	}

	// body 값이 0이거나 빈 값인 경우 기본값 설정
	if ($info['body'] === 0 || $info['body'] === '0' || empty($info['body'])) {
		debug_log("FCM body 값이 0이거나 빈 값 - 기본값으로 대체", 'FCM', ['original_body' => $info['body']]);
		$info['body'] = "알림이 도착했습니다.";
	}

	if (!is_array($token_list) || empty($token_list)) {
		//error_log("FCM 발송 실패 - 토큰 리스트가 비어있음");
		return [
			'success' => false,
			'error' => '토큰 리스트가 비어있습니다',
			'result' => null
		];
	}

	// 토큰 상세 로그 추가
	foreach ($token_list as $index => $token) {
		//error_log("FCM 토큰[" . $index . "]: " . (strlen($token) > 20 ? substr($token, 0, 20) . '...' : $token));
	}

	// Firebase 서비스 계정 파일 경로 결정 (환경별 우선순위 적용)
	$project_root = realpath(__DIR__ . '/../../../');
	$environment = defined('ENVIRONMENT') ? strtolower(ENVIRONMENT) : '';
	$is_dev_env = in_array($environment, ['development', 'dev', 'local', 'test', 'testing', 'stage', 'staging'], true);

	$candidate_paths = [];

	// 1) 환경별 전용 환경변수 우선
	if ($is_dev_env) {
		$env_service_account_file = getenv('FIREBASE_SERVICE_ACCOUNT_PATH_DEV');
		if (!$env_service_account_file) {
			$env_service_account_file = getenv('CHAT_FIREBASE_SERVICE_ACCOUNT_PATH_DEV');
		}
	} else {
		$env_service_account_file = getenv('FIREBASE_SERVICE_ACCOUNT_PATH_PROD');
		if (!$env_service_account_file) {
			$env_service_account_file = getenv('CHAT_FIREBASE_SERVICE_ACCOUNT_PATH_PROD');
		}
	}
	if (!empty($env_service_account_file)) {
		$candidate_paths[] = $env_service_account_file;
	}

	// 2) 공통 환경변수
	$env_service_account_file = getenv('FIREBASE_SERVICE_ACCOUNT_PATH');
	if (!$env_service_account_file) {
		$env_service_account_file = getenv('CHAT_FIREBASE_SERVICE_ACCOUNT_PATH');
	}
	if (!empty($env_service_account_file)) {
		$candidate_paths[] = $env_service_account_file;
	}

	// 3) 파일명 규칙 기반 fallback (개발/운영 우선순위 분리)
	if (!empty($project_root)) {
		if ($is_dev_env) {
			$candidate_paths[] = $project_root . '/firebase-service-account-dev.json';
			$candidate_paths[] = $project_root . '/chatting/chat/config/firebase-service-account-dev.json';
			$candidate_paths[] = $project_root . '/firebase-service-account.json';
			$candidate_paths[] = $project_root . '/chatting/chat/config/firebase-service-account.json';
		} else {
			$candidate_paths[] = $project_root . '/firebase-service-account-prod.json';
			$candidate_paths[] = $project_root . '/chatting/chat/config/firebase-service-account-prod.json';
			$candidate_paths[] = $project_root . '/firebase-service-account.json';
			$candidate_paths[] = $project_root . '/chatting/chat/config/firebase-service-account.json';
			$candidate_paths[] = $project_root . '/firebase-service-account-dev.json';
			$candidate_paths[] = $project_root . '/chatting/chat/config/firebase-service-account-dev.json';
		}
	}

	$service_account_file = '';
	foreach ($candidate_paths as $candidate_path) {
		if (!empty($candidate_path) && file_exists($candidate_path)) {
			$service_account_file = $candidate_path;
			break;
		}
	}

	// 서비스 계정 키 파일 존재 확인
	if (empty($service_account_file) || !file_exists($service_account_file)) {
		debug_log("FCM 서비스 계정 파일 없음", 'FCM', [
			'file' => $service_account_file,
			'project_root' => $project_root,
			'candidates' => $candidate_paths
		]);
		return [
			'success' => false,
			'error' => 'Firebase 서비스 계정 파일을 찾을 수 없습니다',
			'result' => null
		];
	}

	// FCM HTTP v1 API 설정 (환경변수 > 서비스계정 > 기본값)
	$service_account_json = json_decode(file_get_contents($service_account_file), true);
	if ($is_dev_env) {
		$project_id = getenv('CHAT_FIREBASE_PROJECT_ID_DEV');
	} else {
		$project_id = getenv('CHAT_FIREBASE_PROJECT_ID_PROD');
	}
	if (!$project_id) {
		$project_id = getenv('CHAT_FIREBASE_PROJECT_ID');
	}
	if (!$project_id && is_array($service_account_json) && isset($service_account_json['project_id'])) {
		$project_id = $service_account_json['project_id'];
	}
	if (!$project_id) {
		$project_id = 'nowjob-cb256';
	}
	$fcmUrl = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";

	// Access Token 생성
	$access_token = getFirebaseAccessToken($service_account_file);
	if (!$access_token) {
		debug_log("FCM Access Token 생성 실패", 'FCM');
		return [
			'success' => false,
			'error' => 'Firebase Access Token 생성 실패',
			'result' => null
		];
	}

	// iOS 배지 숫자: 실제 미확인 알림 개수 조회
	$badge_count = 0; // 기본값: 조회 실패 시 배지 숨김
	if (isset($info['user_id']) && !empty($info['user_id'])) {
		try {
			$tbl_alarm = get_mongodb("alarm");
			if ($tbl_alarm) {
				$alarm_cnt = $tbl_alarm->find(["show_yn" => "N", "user_id" => $info['user_id']])->count();
				$badge_count = $alarm_cnt; // 실제 알림 개수 (0이면 배지 숨김)
			}
		} catch (Exception $e) {
			// MongoDB 조회 실패 시 배지 숨김 (0)
			debug_log("알림 개수 조회 실패 - 배지 숨김", 'FCM', ['error' => $e->getMessage()]);
			$badge_count = 0;
		}
	}

	// FCM HTTP v1 API 메시지 구성
	$messages = [];

	foreach ($token_list as $token) {
		// 토큰 유효성 검사
		if (empty($token) || $token === null || $token === '') {
			debug_log("FCM 토큰이 비어있음 - 건너뜀", 'FCM', ['token' => $token]);
			continue;
		}

		// 각 알림마다 고유한 notificationId 생성 (네이티브 앱의 PendingIntent 고유성 보장을 위해)
		// 타임스탬프 + 마이크로초 + 랜덤 값 조합으로 고유성 보장
		$notificationId = (int)((microtime(true) * 1000) % PHP_INT_MAX) + mt_rand(1, 1000);

		debug_log("FCM 메시지 구성 시작", 'FCM', ['token' => substr($token, 0, 20) . '...', 'notificationId' => $notificationId]);

		$message = [
			'message' => [
				'token' => $token,
				// notification 페이로드 제거: 안드로이드 13+에서 OS가 알림을 자동 생성하여 onMessageReceived()가 호출되지 않음
				// data 페이로드만 사용하여 onMessageReceived()가 항상 호출되도록 보장
				'data' => [
					'title' => $info['title'],
					'body' => $info['body'],
					'url' => isset($info['url']) ? $info['url'] : '',
					'type' => isset($info['type']) ? $info['type'] : 'general',
					'timestamp' => (string)time(),
					'notificationId' => (string)$notificationId,  // 네이티브 앱에서 PendingIntent의 requestCode로 사용
					'click_action' => isset($info['url']) ? $info['url'] : ''
				],
				'android' => [
					'priority' => 'high',  // 메시지 우선순위 (high로 설정 시 즉시 전달)
					'ttl' => '3600s'
					// android.notification 제거: data 페이로드만 사용하여 클라이언트에서 커스텀 알림 생성
				],
				'apns' => [
					'headers' => [
						'apns-priority' => '10',
						'apns-push-type' => 'alert'  // iOS 알림 타입 명시 (소리 재생을 위해 필수)
					],
					'payload' => [
						'aps' => [
							'alert' => [
								'title' => $info['title'],
								'body' => $info['body']
							],
							'sound' => 'default',  // iOS 알림 소리 (커스텀 사운드 사용 시 네이티브 앱에 파일 추가 필요)
							'badge' => $badge_count  // 실제 미확인 알림 개수로 설정
						],
						'url' => isset($info['url']) ? $info['url'] : ''  // iOS용 커스텀 데이터 (알림 클릭 시 이동할 URL)
					]
				]
			]
		];
		$messages[] = $message;
	}

	$headers = [
		'Authorization: Bearer ' . $access_token,
		'Content-Type: application/json'
	];

	// cURL 초기화 및 설정
	$ch = curl_init();
	if (!$ch) {
		return [
			'success' => false,
			'error' => 'cURL 초기화 실패',
			'result' => null
		];
	}

	// FCM HTTP v1 API는 한 번에 하나의 메시지만 전송 가능
	$results = [];
	$success_count = 0;
	$fail_count = 0;

	// 테스트용: 토큰이 유효하지 않은 경우 건너뛰기
	debug_log("FCM 메시지 전송 시작", 'FCM', ['message_count' => count($messages)]);

	foreach ($messages as $message) {
		// 재시도 로직 (최대 3회)
		$max_retries = 3;
		$retry_count = 0;
		$message_sent = false;

		while ($retry_count < $max_retries && !$message_sent) {
			$retry_count++;
			debug_log("FCM 메시지 전송 시도", 'FCM', ['attempt' => $retry_count, 'max_retries' => $max_retries]);
			curl_setopt($ch, CURLOPT_URL, $fcmUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30초 타임아웃
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10초 연결 타임아웃

			// cURL 실행
			debug_log("FCM cURL 실행 시작", 'FCM', ['token' => substr($message['message']['token'], 0, 20) . '...', 'attempt' => $retry_count]);
			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = curl_error($ch);
			debug_log("FCM cURL 실행 완료", 'FCM', ['http_code' => $httpCode, 'error' => $error, 'result_length' => strlen($result), 'attempt' => $retry_count]);

			// 결과 처리
			if ($error) {
				debug_log("FCM cURL 오류", 'FCM', ['error' => $error, 'attempt' => $retry_count]);
				if ($retry_count >= $max_retries) {
					$fail_count++;
					$results[] = [
						'token' => $message['message']['token'],
						'success' => false,
						'error' => 'cURL 오류 (재시도 완료): ' . $error,
						'http_code' => $httpCode,
						'retry_count' => $retry_count
					];
					$message_sent = true; // 재시도 완료
				} else {
					// 재시도 대기 (지수 백오프)
					$wait_time = pow(2, $retry_count - 1); // 1초, 2초, 4초
					debug_log("FCM 재시도 대기", 'FCM', ['wait_time' => $wait_time, 'attempt' => $retry_count]);
					sleep($wait_time);
				}
			} else if ($result === false) {
				debug_log("FCM 서버 응답 없음", 'FCM', ['attempt' => $retry_count]);
				if ($retry_count >= $max_retries) {
					$fail_count++;
					$results[] = [
						'token' => $message['message']['token'],
						'success' => false,
						'error' => 'FCM 서버 응답 없음 (재시도 완료)',
						'http_code' => $httpCode,
						'retry_count' => $retry_count
					];
					$message_sent = true; // 재시도 완료
				} else {
					// 재시도 대기
					$wait_time = pow(2, $retry_count - 1);
					debug_log("FCM 재시도 대기", 'FCM', ['wait_time' => $wait_time, 'attempt' => $retry_count]);
					sleep($wait_time);
				}
			} else {
				$json_result = json_decode($result, true);
				if (json_last_error() !== JSON_ERROR_NONE) {
					debug_log("FCM JSON 파싱 오류", 'FCM', ['error' => json_last_error_msg(), 'attempt' => $retry_count]);
					if ($retry_count >= $max_retries) {
						$fail_count++;
						$results[] = [
							'token' => $message['message']['token'],
							'success' => false,
							'error' => 'JSON 파싱 오류 (재시도 완료): ' . json_last_error_msg(),
							'http_code' => $httpCode,
							'result' => $result,
							'retry_count' => $retry_count
						];
						$message_sent = true; // 재시도 완료
					} else {
						// 재시도 대기
						$wait_time = pow(2, $retry_count - 1);
						debug_log("FCM 재시도 대기", 'FCM', ['wait_time' => $wait_time, 'attempt' => $retry_count]);
						sleep($wait_time);
					}
				} else {
					$success = ($httpCode >= 200 && $httpCode < 300);
					if ($success) {
						$success_count++;
						debug_log("FCM 메시지 전송 성공", 'FCM', ['attempt' => $retry_count]);
						$results[] = [
							'token' => $message['message']['token'],
							'success' => true,
							'error' => null,
							'http_code' => $httpCode,
							'result' => $json_result,
							'retry_count' => $retry_count
						];
						$message_sent = true; // 성공
					} else {
						// HTTP 오류 상세 정보 추출
						$error_code = null;
						$error_message = null;
						if (isset($json_result['error'])) {
							$error_code = isset($json_result['error']['code']) ? $json_result['error']['code'] : null;
							$error_message = isset($json_result['error']['message']) ? $json_result['error']['message'] : null;

							// FCM 에러 코드 추출
							if (isset($json_result['error']['details']) && is_array($json_result['error']['details'])) {
								foreach ($json_result['error']['details'] as $detail) {
									if (isset($detail['errorCode'])) {
										$error_code = $detail['errorCode'];
										break;
									}
								}
							}
						}

						// iOS 토큰 타입 감지 (토큰 길이로 판단)
						$token_length = strlen($message['message']['token']);
						$is_ios_token = ($token_length > 140); // iOS FCM 토큰은 보통 150자 이상

						debug_log("FCM HTTP 오류", 'FCM', [
							'http_code' => $httpCode,
							'attempt' => $retry_count,
							'error_code' => $error_code,
							'error_message' => $error_message,
							'token_type' => $is_ios_token ? 'iOS' : 'Android',
							'token_preview' => substr($message['message']['token'], 0, 20) . '...',
							'result' => $json_result
						]);

						// SENDER_ID_MISMATCH 같은 특정 에러는 재시도하지 않음
						$should_retry = true;
						if ($error_code === 'SENDER_ID_MISMATCH' || $error_code === 'UNREGISTERED') {
							$should_retry = false; // 재시도해도 의미 없음
							debug_log("FCM 치명적 에러 - 재시도 안 함", 'FCM', [
								'error_code' => $error_code,
								'token_type' => $is_ios_token ? 'iOS' : 'Android',
								'message' => $is_ios_token ?
									'iOS 앱의 Firebase 설정(Sender ID)이 서버의 Firebase 프로젝트와 일치하지 않습니다. GoogleService-Info.plist 파일을 확인하세요.' :
									'Android 앱의 Firebase 설정(Sender ID)이 서버의 Firebase 프로젝트와 일치하지 않습니다.'
							]);
						}

						if ($retry_count >= $max_retries || !$should_retry) {
							$fail_count++;
							$results[] = [
								'token' => $message['message']['token'],
								'success' => false,
								'error' => $error_message ? $error_message : ('HTTP 오류 (재시도 완료): ' . $httpCode),
								'http_code' => $httpCode,
								'error_code' => $error_code,
								'error_message' => $error_message,
								'result' => $json_result,
								'retry_count' => $retry_count,
								'token_type' => $is_ios_token ? 'iOS' : 'Android'
							];
							$message_sent = true; // 재시도 완료
						} else {
							// 재시도 대기
							$wait_time = pow(2, $retry_count - 1);
							debug_log("FCM 재시도 대기", 'FCM', ['wait_time' => $wait_time, 'attempt' => $retry_count]);
							sleep($wait_time);
						}
					}
				}
			}
		}
	}

	// cURL 리소스 정리
	curl_close($ch);

	// 최종 결과 반환
	$overall_success = ($success_count > 0);
	debug_log("FCM 최종 결과", 'FCM', [
		'overall_success' => $overall_success,
		'success_count' => $success_count,
		'fail_count' => $fail_count,
		'total_count' => count($messages),
		'results' => $results
	]);

	return [
		'success' => $overall_success,
		'error' => $overall_success ? null : '모든 메시지 전송 실패',
		'success_count' => $success_count,
		'fail_count' => $fail_count,
		'total_count' => count($messages),
		'results' => $results
	];
}

/**
 * AI 매칭 PUSH 알림 발송
 * @param int $receiver_mm_idx 수신자 회원 idx
 * @param string $type 수신자 타입 ('COMP' or 'MEM')
 * @param string $target_name 대상 이름 (이력서명 또는 공고명)
 * @param int $target_idx 대상 idx (ru_idx 또는 rc_idx)
 */
function sendMatchPush($receiver_mm_idx, $type, $target_name, $target_idx)
{
	try {
		// 토큰 조회
		$CI =& get_instance();
		$token_result = $CI->db->query(
			"SELECT ifnull(token,'') as token, push_yn
			 FROM st_member_device
			 WHERE mm_idx = ? AND push_yn = 'Y'
			 ORDER BY up_dt DESC LIMIT 1",
			array($receiver_mm_idx)
		);
		$token_info = $token_result->row_array();

		// 토큰 없으면 조용히 return
		if (!$token_info || empty($token_info['token'])) {
			\error_log("[AI 매칭 PUSH] 토큰 없음 - mm_idx: " . $receiver_mm_idx . ", type: " . $type);
			return false;
		}

		// 메시지 구성 (타입별 분기)
		if ($type === 'COMP') {
			// 기업 수신: 이력서 매칭
			$info = array(
				'title' => 'NowJob 인재 매칭',
				'body' => (empty($target_name) ? '매칭' : $target_name) . '님이 매칭되었습니다',
				'url' => '/resume/hr_post/' . $target_idx,
				'type' => 'match_resume'
			);
		} else {
			// 개인 수신: 공고 매칭
			$info = array(
				'title' => 'NowJob 공고 매칭',
				'body' => (empty($target_name) ? '채용공고' : $target_name) . ' 공고가 매칭되었습니다',
				'url' => '/recruit/job_post/' . $target_idx,
				'type' => 'match_recruit'
			);
		}

		// PUSH 발송
		$token_list = array($token_info['token']);
		$result = sendFcm($info, $token_list);

		// 발송 결과 로그
		if (isset($result['success']) && $result['success']) {
			\error_log("[AI 매칭 PUSH] 발송 성공 - mm_idx: " . $receiver_mm_idx . ", type: " . $type . ", target: " . $target_name);
			return true;
		} else {
			$error_msg = isset($result['error']) ? $result['error'] : '미상의 오류';
			\error_log("[AI 매칭 PUSH] 발송 실패 - mm_idx: " . $receiver_mm_idx . ", type: " . $type . ", error: " . $error_msg);
			return false;
		}
	} catch (Exception $e) {
		// 예외 발생 시에도 호출부로 전파되지 않도록 로그만 남김
		\error_log("[AI 매칭 PUSH] 예외 발생 - mm_idx: " . $receiver_mm_idx . ", error: " . $e->getMessage());
		return false;
	}
}

/**
 * Firebase Access Token 생성 함수
 * @param string $service_account_file 서비스 계정 JSON 파일 경로
 * @return string|false Access Token 또는 false
 */
function getFirebaseAccessToken($service_account_file)
{
	debug_log("Firebase Access Token 생성 시작", 'FCM', ['file' => $service_account_file]);

	// 서비스 계정 파일 읽기
	$service_account = json_decode(file_get_contents($service_account_file), true);
	if (!$service_account) {
		debug_log("서비스 계정 파일 파싱 실패", 'FCM');
		return false;
	}

	// JWT 헤더
	$header = [
		'alg' => 'RS256',
		'typ' => 'JWT'
	];

	// JWT 페이로드
	$now = time();
	$payload = [
		'iss' => $service_account['client_email'],
		'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
		'aud' => 'https://oauth2.googleapis.com/token',
		'exp' => $now + 3600, // 1시간
		'iat' => $now
	];

	// JWT 생성
	$header_encoded = base64url_encode(json_encode($header));
	$payload_encoded = base64url_encode(json_encode($payload));
	$signature_input = $header_encoded . '.' . $payload_encoded;

	// 개인키로 서명
	$private_key = $service_account['private_key'];
	$signature = '';
	openssl_sign($signature_input, $signature, $private_key, OPENSSL_ALGO_SHA256);
	$signature_encoded = base64url_encode($signature);

	$jwt = $signature_input . '.' . $signature_encoded;

	// Google OAuth2 토큰 요청
	$token_url = 'https://oauth2.googleapis.com/token';
	$post_data = [
		'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
		'assertion' => $jwt
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $token_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$error = curl_error($ch);
	curl_close($ch);

	if ($error) {
		debug_log("Access Token 요청 cURL 오류", 'FCM', ['error' => $error]);
		return false;
	}

	if ($http_code !== 200) {
		debug_log("Access Token 요청 실패", 'FCM', ['http_code' => $http_code, 'response' => $response]);
		return false;
	}

	$token_data = json_decode($response, true);
	if (!$token_data || !isset($token_data['access_token'])) {
		debug_log("Access Token 응답 파싱 실패", 'FCM', ['response' => $response]);
		return false;
	}

	debug_log("Firebase Access Token 생성 성공", 'FCM', ['expires_in' => $token_data['expires_in']]);
	return $token_data['access_token'];
}

/**
 * Base64 URL 인코딩
 * @param string $data 인코딩할 데이터
 * @return string Base64 URL 인코딩된 문자열
 */
function base64url_encode($data)
{
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}


# GPS정보로 주소 검색
//카카오 rest api 함수
function kakaoRestApi($type, $parameter = array(), $content_type = 'json')
{
	// Request 정보
	$host = "https://dapi.kakao.com";
	$setting = getSettingInfo(["st_group" => "카카오"]);
	$api_key = isset($setting["REST_API_KEY"]) ? $setting["REST_API_KEY"] : "";
	$headers = array("Authorization: KakaoAK {$api_key}");

	$query = http_build_query($parameter);

	$path = "";

	switch ($type) {
		case "coord2address":      // 좌표로 주소변환
			$path = "/v2/local/geo/coord2address";
			break;

		case "address":            // 주소 검색
			$path = "/v2/local/search/address";
			break;

		case "coord2regioncode":   // 좌표로 행정구역정보 받기
			$path = "/v2/local/geo/coord2regioncode";
			break;

		default:
			// 올바른 API 구분이 아닌 경우 return;
			return array('api_result' => false, 'err_msg' => 'API 구분을 올바르게 입력해 주세요.');
	}

	$opts = array(
		CURLOPT_URL => $host . $path . '.' . $content_type . '?' . $query,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSLVERSION => 1,
		CURLOPT_HEADER => false,
		CURLOPT_HTTPHEADER => $headers
	);

	$curl_session = curl_init();
	curl_setopt_array($curl_session, $opts);
	$return_data = curl_exec($curl_session);

	// response 정보
	$decode = json_decode($return_data, true);

	if (curl_errno($curl_session)) {
		throw new Exception(curl_error($curl_session));
		$decode['api_result'] = false;
	} else {
		curl_close($curl_session);
		$decode['api_result'] = true;
	}

	return $decode;
}

/**
 * 디버그 로그 기록 함수
 * @param string $message 로그 메시지
 * @param string $type 로그 타입 (INFO, ERROR, DEBUG)
 * @param array $data 추가 데이터
 */
function debug_log($message, $type = 'INFO', $data = [])
{
	// 로그 비활성화 처리 - 로그 기록하지 않음
	return;
	
	$log_dir = './debug_log';
	if (!is_dir($log_dir)) {
		mkdir($log_dir, 0755, true);
	}

	$log_file = $log_dir . '/debug_' . date('Y-m-d') . '.log';
	$timestamp = date('Y-m-d H:i:s');
	$log_message = "[{$timestamp}] [{$type}] {$message}";

	if (!empty($data)) {
		$log_message .= " | DATA: " . json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	$log_message .= PHP_EOL;

	//file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX);
}

/**
 * 제안보내기 디버그 로그
 * @param string $step 단계
 * @param string $message 메시지
 * @param array $data 데이터
 */
function proposal_debug_log($step, $message, $data = [])
{
	debug_log("제안보내기 [{$step}] {$message}", 'PROPOSAL', $data);
}

/**
 * 간편로그인 회원가입 시 전화번호로 기존 회원 존재 여부 확인
 * @param string $phone 전화번호
 * @return array ["is_duplicate" => bool, "message" => string]
 */
function checkDuplicatePhoneForSns($phone)
{
	$CI = &get_instance();
	$result = ["is_duplicate" => false, "message" => ""];

	if (empty($phone)) {
		return $result;
	}

	// 전화번호 형식 정규화 (하이픈 제거)
	$normalized_phone = preg_replace('/[^0-9]/', '', $phone);

	// SQL로 전화번호 조회 (하이픈 제거 후 비교)
	// DB에는 '010-9002-5528' 형식으로 저장되어 있으므로 REPLACE로 하이픈 제거 후 비교
	$query = "SELECT * FROM st_member 
			  WHERE REPLACE(REPLACE(REPLACE(mm_phone, '-', ''), ' ', ''), '.', '') = '" . $CI->db->escape_str($normalized_phone) . "' 
			  AND mm_status = 'Y' 
			  LIMIT 1";
	$db_result = $CI->db->query($query);
	$mem_info = $db_result->row_array();

	if ($mem_info != null && !empty($mem_info)) {
		// 이미 가입된 회원인 경우
		// 가입 경로별 알림 문구 생성
		$join_type = isset($mem_info["mm_join_type"]) ? strtoupper($mem_info["mm_join_type"]) : "";
		if ($join_type === "WEB") {
			$provider_label = "핸드폰번호";
			$postposition = "로";
		} elseif ($join_type === "NAVER") {
			$provider_label = "네이버";
			$postposition = "로";
		} elseif ($join_type === "KAKAO") {
			$provider_label = "카카오톡";
			$postposition = "으로";
		} elseif ($join_type === "APPLE") {
			$provider_label = "애플";
			$postposition = "로";
		} elseif ($join_type === "GOOGLE") {
			$provider_label = "구글";
			$postposition = "에서";
		} elseif ($join_type === "WECHAT") {
			$provider_label = "위쳇";
			$postposition = "으로";
		} else {
			$provider_label = "NOWJOB";
			$postposition = "에서";
		}

		$result["is_duplicate"] = true;
		$result["message"] = $provider_label . $postposition . " 이미 가입된 전화번호 입니다.";
		$result["join_type"] = $join_type;
		$result["mm_phone"] = $mem_info["mm_phone"]; // 중복 회원의 전화번호
	}

	return $result;
}

/**
 * 주소에서 시/도 부분을 제거 또는 축약하는 함수
 * JavaScript의 fRemoveBigAdrJS와 동일한 로직 (PHP 버전)
 */
function removeBigAddr($addr) {
	if (empty($addr) || !is_string($addr)) {
		return '';
	}

	$raw = trim($addr);
	if (empty($raw)) {
		return '';
	}

	// 쉼표 포함 시 첫 토큰만 사용
	$parts = explode(',', $raw);
	$firstPart = trim($parts[0]);

	// 시/도 부분 제거 또는 축약
	$replacements = array(
		'서울특별시 ' => '서울시 ',
		'경기도 ' => '',
		'강원특별자치도 ' => '',
		'충청남도 ' => '',
		'충청북도 ' => '',
		'경상남도 ' => '',
		'경상북도 ' => '',
		'전라남도 ' => '',
		'전북특별자치도 ' => '',
		'제주특별자치도 ' => '',
		'세종특별자치시 ' => '세종시 ',
		'광주광역시 ' => '광주시 ',
		'인천광역시 ' => '인천시 ',
		'울산광역시 ' => '울산시 ',
		'부산광역시 ' => '부산시 ',
		'대구광역시 ' => '대구시 ',
		'대전광역시 ' => '대전시 ',
		'경기 ' => '',
		'강원 ' => '',
		'충남 ' => '',
		'충북 ' => '',
		'경남 ' => '',
		'경북 ' => '',
		'전남 ' => '',
		'전북 ' => '',
		'제주 ' => ''
	);

	foreach ($replacements as $from => $to) {
		$firstPart = str_replace($from, $to, $firstPart);
	}

	// 공백으로 분리하여 토큰 수 확인
	$tokens = explode(' ', $firstPart);
	$tokenCount = count($tokens);

	if ($tokenCount >= 3) {
		// 세 번째 토큰이 숫자를 포함하면 첫 두 토큰만 사용
		if (preg_match('/\d/', $tokens[2])) {
			return trim($tokens[0] . ' ' . $tokens[1]);
		}
		// 세 번째 토큰이 숫자를 포함하지 않으면 첫 번째 + 세 번째 토큰 사용
		return trim($tokens[0] . ' ' . $tokens[2]);
	}

	// 3개 미만의 토큰이면 그대로 반환
	return trim($firstPart);
}
