<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class common
{
	private $ci;

	function __construct()
	{
		$ci = &get_instance();
	}

	function today()
	{
		return date("Y-m-d H:i:s");
	}

	//현재 URL
	function currentUrl()
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	//게스트 등록
	function insertGuest($info)
	{
		$db = DB('default', true);

		//중복확인
		$sql = "select *
    			FROM st_guest
    			where gt_session_id = '" . ($info["gt_session_id"]) . "' ";

		$tmp_result = $db->query($sql);
		$guest_info = $tmp_result->row_array();

		if ($guest_info != null) {
			return $guest_info;
		} else {
			if (!$result = $db->insert("st_guest", $info)) {
				return null;
			}
			$tmp_result = $db->query($sql);
			$guest_info = $tmp_result->row_array();
			return $guest_info;
		}
	}


	//카테고리 리스트 가져오기
	function getCategory()
	{
		$db = DB('default', true);
		$sql = "select *
    			FROM st_category
    			where del_yn = 'N'
				order by ct_sort asc ";

		$tmp_result = $db->query($sql);
		$category_list = $tmp_result->result_array();

		$result = [];
		foreach ($category_list as  $ct) {
			if ($ct["ct_depth"] == "1") {
				$result[] = array(
					"ct_idx" => $ct["ct_idx"],
					"ct_name" => $ct["ct_name"],
					"list" => []
				);
			}
		}

		//2댑스 처리
		foreach ($result as $pt_key => $pt) {
			foreach ($category_list as  $ct) {
				if ($ct["ct_depth"] == "2") {
					if ($pt["ct_idx"] == $ct["ct_parent"]) {
						$result[$pt_key]["list"][] = [
							"ct_idx" => $ct["ct_idx"],
							"ct_name" => $ct["ct_name"],
							"list" => []
						];
					}
				}
			}
		}

		//3댑스 처리
		foreach ($result as $pt_key => $pt) {
			foreach ($pt["list"] as $pt_key2 => $pt2) {
				foreach ($category_list as  $ct) {
					if ($ct["ct_depth"] == "3") {
						if ($pt2["ct_idx"] == $ct["ct_parent"]) {
							$result[$pt_key]["list"][$pt_key2]["list"][] = [
								"ct_idx" => $ct["ct_idx"],
								"ct_name" => $ct["ct_name"]
							];
						}
					}
				}
			}
		}

		return $result;
	}

	//설정정보 가져오기
	function getSetting()
	{
		$db = DB('default', true);
		$sql = "select *
    			FROM st_setting
				where st_use_yn = 'Y'";

		$result = $db->query($sql);
		$row = $result->row();

		if ($row != null) {
			return $this->getClsArray($row);
		} else {
			return null;
		}
	}

	//세션 설정정보 가져오기
	function getSessionDataList($session_key, $minute = 60)
	{
		if ($force == false && $this->getSession($session_key) != null) {
			if (time() - $_SESSION[$session_key . "_TIME"] > $minute * 60) {
				//세션 재생성
			} else {
				return $this->getSession($session_key);
			}
		}

		if ($session_key == "WORK_TYPE") {
			$db = DB('default', true);
			$sql = "select *
	    			FROM st_code
					where use_yn = 'Y'
					and cd_grp = '희망업무'
					";

			$stmt = $db->query($sql);
			$list = $stmt->result_array();

			$return = [];
			foreach ($list as $row) {
				$return[$row["cd_val"]] = ["icon" => $row["cd_val2"], "img1" => $row["cd_val3"], "img2" => $row["cd_val4"]];
			}
		} else if ($session_key == "ADDR_LIST") {
			$db = DB('default', true);
			$sql = "select ca_ad1,ca_num
	    			FROM st_code_addr
	    			group by ca_ad1,ca_num
	    			order by ca_num asc
					";
			$stmt = $db->query($sql);
			$list = $stmt->result_array();

			$return = $list;
		} else if ($session_key == "TAG_LIST") {
			$db = DB('default', true);
			$sql = "select *
	    			FROM st_setting
	    			where st_group = '태그'
	    			and st_key = 'search'
					";
			$stmt = $db->query($sql);
			$info = $stmt->row_array();

			if ($info != null) {
				$return = explode(",", $info["st_value"]);
			} else {
				return [];
			}
		}

		# 세션 처리
		$this->setSession($session_key, $return);
		$this->setSession($session_key . "_TIME", time());

		return $return;
	}

	//결제 정보 획득
	function getPgInfo($pg_name, $info, $o_num, $price)
	{
		if ($pg_name == "이니시스") {
			require_once(getcwd() . '/application/common/pg/inicis/libs/INIStdPayUtil.php');
			$SignatureUtil = new INIStdPayUtil();

			$mid = ($info["sp_test_yn"] == "N" ? $info["sp_test_id"] : $info["sp_shop_id"]);
			$signKey = ($info["sp_test_yn"] == "N" ? $info["sp_test_key"] : $info["sp_shop_key"]);
			$timestamp = $SignatureUtil->getTimestamp();
			$price = $price;
			$mKey = $SignatureUtil->makeHash($signKey, "sha256");

			$params = array(
				"oid" => $o_num,
				"price" => $price,
				"timestamp" => $timestamp
			);
			$sign = $SignatureUtil->makeSignature($params);

			return [
				"proc_mode" => $info["sp_test_yn"],
				"mid" => $mid,
				"mKey" => $mKey,
				"timestamp" => $timestamp,
				"sign" => $sign,
				"price" => $price,
				"o_num" => $o_num,
			];
		}
	}

	//결제 정보 획득
	function gpCancel($pg_name, $pg_info, $paymethod, $type, $tid, $msg, $price, $confirm_price)
	{
		try {
			$this->CI = &get_instance();
			if ($pg_name == "이니시스") {
				$clientIp = $this->CI->common->getIpAddr();
				$mid = ($pg_info["sp_test_yn"] == "N" ? $pg_info["sp_test_id"] : $pg_info["sp_shop_id"]);
				$signKey = ($pg_info["sp_test_yn"] == "N" ? $pg_info["sp_test_cancel_key"] : $pg_info["sp_shop_cancel_key"]);
				$timestamp = date("YmdHis");

				if ($paymethod == "CARD") {
					$paymethod = "Card";
				}

				//해쉬키 생성
				if ($type == "Refund") {
					$hashData = hash("sha512", $signKey . $type . $paymethod . $timestamp . $clientIp . $mid . $tid);
				} else {
					$hashData = hash("sha512", $signKey . $type . $paymethod . $timestamp . $clientIp . $mid . $tid . $price . $confirm_price);
				}

				$params = array(
					'type' => $type,
					'paymethod' => $paymethod,
					'timestamp' => $timestamp,
					'clientIp' => $clientIp,
					'mid' => $mid,
					'tid' => $tid,
					'msg' => $msg,
					'hashData' => $hashData
				);

				if ($type == "PartialRefund") {
					$params["price"] = $price;
					$params["confirmPrice"] = $confirm_price;
				}

				$url = "https://iniapi.inicis.com/api/v1/refund";  		// 전송 URL

				$ch = curl_init();                                      //curl 초기화
				curl_setopt($ch, CURLOPT_URL, $url);                    // 전송 URL 지정하기
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     	//요청 결과를 문자열로 반환
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      		//connection timeout 10초
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));       //POST data
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8')); // 전송헤더 설정
				curl_setopt($ch, CURLOPT_POST, 1);                       // post 전송

				$response = curl_exec($ch);
				curl_close($ch);

				$json_result = json_decode($response);

				return [
					"code" => $json_result->resultCode,
					"msg" => "[이니시스 메시지]" . $json_result->resultMsg,
					"log" => $response,
				];
			}
		} catch (Exception $ex) {
			return [
				"code" => "91",
				"msg" => "[취소 처리 오류]" . $ex->getMessage(),
				"log" => "오류내용 : " . $ex->getMessage()
			];
		}
	}

	//테이블 기본키 얻기
	function getTblIdx($table)
	{
		$db = DB('default', true);
		$db->set('idx', 'idx + 1', FALSE)->where(array("table_name" => $table))->update('st_table_idx');
		$query = $db->get_where("st_table_idx", array("table_name" => $table));
		$row = $query->row_array();
		$ret = ($row["alias"] . str_pad(($row["idx"]), $row["size"] - strlen($row["alias"]), "0", STR_PAD_LEFT));
		return $ret;
	}

	//주문키 생성
	function getOrderKey()
	{
		$db = DB('default', true);

		$o_num = "";
		for ($i = 0; $i < 10; $i++) {
			$rand = $this->getRandNum();
			$o_num =  strtoupper(sprintf("OD" . substr(date("Y"), 2, 2) . '-' . date("md") . '-' . '%04x', mt_rand(0, 0xffff)));
			$query = $db->get_where("st_order", array("o_num" => $o_num));
			$row = $query->row_array();
			if ($row == null) {
				break;
			}
		}

		//없으면 새로 생성하여 리턴
		return $o_num;
	}

	//랜덤 숫자 생성 4자리
	function getRandNum()
	{
		$rand = mt_rand(10, 10000);
		return str_pad($rand, 4, "0", STR_PAD_LEFT);
	}

	//랜덤 숫자 생성 6자리
	function getRandNum6()
	{
		$rand = mt_rand(10, 1000000);
		return str_pad($rand, 6, "0", STR_PAD_LEFT);
	}

	//**'배열(Array)'**강제로 변환
	function getClsArray($data)
	{   
		return json_decode(json_encode($data), true);
	}

	//DB 데이터 암호화
	function getSqlPass($value)
	{
		$db = DB('default', true);
		$sql = "select password('" . $value . "') as pass;";
		$query = $db->query($sql);
		$row = $query->row_array();
		//var_dump($row["pass"]);
		return $row["pass"];
	}

	// 페이지 이동처리
	function goLocation($loc, $msg = "")
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

	// 페이지 이동처리
	function scriptReplace($loc, $msg = "")
	{
		//안드로이드용 replace
		echo "
			<script>
			function _replace(url){
			  if(history.replaceState){
			    history.replaceState(null, document.title, url);
			    history.go(0);
			  }else{
			    location.replace(url);
			  }
			}
			</script>
		";

		if ($msg != "") {
			$msg = str_replace("'", "\'", $msg);
			echo "<script>alert('" . $msg . "');</script>";
		}

		if (strpos($loc, "http") === false) {
			$loc = get_current_domain() . $loc;
		}

		echo "<script>_replace('$loc');</script>";
		exit;
	}

	// 뒤로가기 처리
	function goBack($msg = "")
	{
		if ($msg != "") {
			$msg = str_replace("'", "\'", $msg);
			echo "<script>alert('" . $msg . "');</script>";
		}
		echo "<script>history.go(-1);</script>";
		exit;
	}

	//파라미터제거
	function removeParam($param, $data)
	{
		$url = $_SERVER['REQUEST_URI'];
		$url = str_replace("?" . $param . "=" . $data, "", $url);
		$url = str_replace("&" . $param . "=" . $data, "", $url);
		return $url;
	}

	//세션등록
	function setSession($name, $data)
	{
		$_SESSION[$name] = $data;
	}

	//세션얻기
	function getSession($name)
	{
		if (!isset($_SESSION[$name])) {
			return null;
		} else {
		// $array: 데이터를 쓰기 쉽게 하기 위해서 배열로 변환
			$array = $this->getClsArray($_SESSION[$name]);
			return $array;
		}
	}

	//세션삭제
	function removeSession($name)
	{
		if (isset($_SESSION[$name])) {
			$_SESSION[$name] = null;
		}
	}

	//접속경로 확인 (태블릿은 PC로 취급)
	function getLocation()
	{
		$headers = apache_request_headers(); // 헤더에 적힌 정보들을 배열에 담는 함수
		if (isset($headers["os_type"]) && $headers["os_type"] != "") {
			return "M";
		}

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		// 태블릿 감지 (iPad, Android 태블릿 등) → PC로 처리
		if (preg_match('/(iPad|Android)/i', $user_agent)) {
			// iPad는 무조건 태블릿
			if (preg_match('/iPad/i', $user_agent)) {
				return "W";
			}
			// Android의 경우, "Mobile" 키워드가 없으면 태블릿
			if (preg_match('/Android/i', $user_agent) && !preg_match('/Mobile/i', $user_agent)) {
				return "W";
			}
		}

		// 모바일 기기 감지
		$mobilechk = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/i';
		if (preg_match($mobilechk, $user_agent)) {
			return "M";
		} else {
			return "W";
		}
	}

	//접속 아이피 가져오기
	function getIpAddr()
	{
		if (
			!empty($_SERVER['HTTP_CLIENT_IP']) &&
			getenv('HTTP_CLIENT_IP')
		) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (
			!empty($_SERVER['HTTP_X_FORWARDED_FOR']) &&
			getenv('HTTP_X_FORWARDED_FOR')
		) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (
			!empty($_SERVER['REMOTE_HOST']) &&
			getenv('REMOTE_HOST')
		) {
			return $_SERVER['REMOTE_HOST'];
		} elseif (
			!empty($_SERVER['REMOTE_ADDR']) &&
			getenv('REMOTE_ADDR')
		) {
			return $_SERVER['REMOTE_ADDR'];
		}
		return "";
	}

	function getBrowser()
	{
		$userAgent = $_SERVER["HTTP_USER_AGENT"];

		if (preg_match("/MSIE*/", $userAgent)) {
			// 익스플로러

			if (preg_match("/MSIE 6.0[0-9]*/", $userAgent)) {
				$browser = "Explorer 6";
			} elseif (preg_match("/MSIE 7.0*/", $userAgent)) {
				$browser = "Explorer 7";
			} elseif (preg_match("/MSIE 8.0*/", $userAgent)) {
				$browser = "Explorer 8";
			} elseif (preg_match("/MSIE 9.0*/", $userAgent)) {
				$browser = "Explorer 9";
			} elseif (preg_match("/MSIE 10.0*/", $userAgent)) {
				$browser = "Explorer 10";
			} else {
				// 익스플로러 기타
				$browser = "Explorer ETC";
			}
		} elseif (preg_match("/Trident*/", $userAgent &&  preg_match("/rv:11.0*/", $userAgent &&  preg_match("/Gecko*/", $userAgent)))) {
			$browser = "Explorer 11";
		} elseif (preg_match("/(Mozilla)*/", $userAgent)) {
			// 모질라 (파이어폭스)
			$browser = "mozilla";
		} elseif (preg_match("/(Nav|Gold|X11|Mozilla|Nav|Netscape)*/", $userAgent)) {
			// 네스케이프, 모질라(파이어폭스)
			$browser = "Netscape/mozilla";
		} elseif (preg_match("/Opera*/", $userAgent)) {
			// 오페라
			$browser = "Opera";
		} else {
			$browser = "Other";
		}

		return  $browser;
	}

	//OS정로 리턴
	function getOS()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$os_platform  = "Unknown";
		$os_array     = array(
			'/windows nt 10/i'      =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		);

		foreach ($os_array as $regex => $value)
			if (preg_match($regex, $user_agent))
				$os_platform = $value;

		return $os_platform;
	}

	function getRefers()
	{
		$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

		if ($refer == null) {
			return "";
		} else {
			return $refer;
		}
	}

	function getCurrentUrl()
	{
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			$link = "https";
		} else {
			$link = "http";
		}

		$link .= "://";
		$link .= $_SERVER['HTTP_HOST'];
		$link .= $_SERVER['REQUEST_URI'];
		return  $link;
	}

	//이미지 업로드
	function img_upload($path, $ipt_name)
	{
		$this->CI = &get_instance();

		$real_upload_path = "/resource/uploads/";

		if (!file_exists(getcwd() . $real_upload_path . $path)) {
			mkdir(getcwd() . $real_upload_path . $path, 0755, true);
		}

		$root =  getcwd() . $real_upload_path . $path;

		$config['upload_path'] = $root;     //'/website/mall/resource/upload/' . $path;
		$config['allowed_types'] = '*';
		$config['max_size']	= '1000048';
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;

		if (isset($_FILES[$ipt_name]) == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "파일이 존재하지 않습니다.";
			return $result;
		}

		$chk_ext = $this->file_type_chk($_FILES[$ipt_name]["name"], "img");
		if ($chk_ext == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "허용되지 않은 파일 확장자 입니다.";
			return $result;
		}

		$info = $this->CI->load->library('upload', $config);
		$result = array(
			'IS_FILE' => FALSE,
			'URL' => "",
			'ERROR' => "",
			'FILE_NAME' => ""
		);

		if (! $this->CI->upload->do_upload($ipt_name)) {
			$error = array('error' => $this->CI->upload->display_errors());
			//var_dump($error);
			$result["IS_FILE"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = $this->CI->upload->display_errors('', '');
			$result["SUCCESS"] = false;
			if (empty($_FILES[$ipt_name]['name'])) {
				$result["EMPTY"] = "EMPTY";
			} else {
				$result["EMPTY"] = "";
			}
		} else {
			$data = array('upload_data' => $this->CI->upload->data());
			$file_name = $data['upload_data']['raw_name'];
			$url = $real_upload_path . $path . "/" . $file_name . $data['upload_data']['file_ext'];
			$result["IS_FILE"] = TRUE;
			$result["URL"] = $url;
			$result["FILE_NAME"] = $file_name . $data['upload_data']['file_ext'];
			$result["ORIG_NAME"] = $data['upload_data']['orig_name'];
			$result["ERROR"] = "";
			$result["WIDTH"] = $data['upload_data']['image_width'];
			$result["HEIGHT"] = $data['upload_data']['image_height'];
			$result["SIZE"] = $data['upload_data']['file_size'];
			$result["TYPE"] = $data['upload_data']['image_type'];
			$result["EMPTY"] = "";
			$result["SUCCESS"] = true;
			$result["TYPE_NUM"] = $this->getExtNum($data['upload_data']['file_ext']);
		}

		return $result;
	}

	//이미지 업르도 리사이즈
	function img_upload_resize($path, $ipt_name, $size = 800)
	{
		$this->CI = &get_instance();
		$real_upload_path = "/resource/uploads/";
		if (!file_exists(getcwd() . $real_upload_path . $path)) {
			mkdir(getcwd() . $real_upload_path . $path, 0755, true);
		}

		$root =  getcwd() . $real_upload_path . $path;

		//리자이즈 경로 추가
		if (!file_exists($root . "/resize")) {
			mkdir($root . "/resize", 0755, true);
		}

		$config['upload_path'] = $root;     //'/website/mall/resource/upload/' . $path;
		$config['allowed_types'] = '*';
		$config['max_size']	= '8192';
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;

		if (isset($_FILES[$ipt_name]) == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "파일이 존재하지 않습니다.";
			return $result;
		}

		$chk_ext = $this->file_type_chk($_FILES[$ipt_name]["name"], "img");
		if ($chk_ext == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "허용되지 않은 파일 확장자 입니다.";
			return $result;
		}

		$info = $this->CI->load->library('upload', $config);
		$result = array(
			'IS_FILE' => FALSE,
			'URL' => "",
			'ERROR' => "",
			'FILE_NAME' => ""
		);

		if (! $this->CI->upload->do_upload($ipt_name)) {
			$error = array('error' => $this->CI->upload->display_errors());
			$result["IS_FILE"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = $this->CI->upload->display_errors('', '');
			$result["SUCCESS"] = false;
			if (empty($_FILES[$ipt_name]['name'])) {
				$result["EMPTY"] = "EMPTY";
			} else {
				$result["EMPTY"] = "";
			}
		} else {

			$data = array('upload_data' => $this->CI->upload->data());
			$file_name = $data['upload_data']['raw_name'];
			$url = "/resource/uploads/" . $path . "/" . $file_name . $data['upload_data']['file_ext'];

			#모바일 이미지 돌리기
			$this->img_rotate($root . "/" . $file_name . $data['upload_data']['file_ext']);

			#파일 사이즈
			$img_size = filesize($root . "/" . $file_name . $data['upload_data']['file_ext']);
			$img_size = $img_size / 1024;
			$quilty = 100;

			if ($img_size > 3000) {
				$quilty = 77;
			} else if ($img_size > 2000) {
				$quilty = 83;
			} else if ($img_size > 1000) {
				$quilty = 85;
			} else if ($img_size > 100) {
				$quilty = 90;
			}

			//1MB가 넘는 이미지는 퀄리티를 낮춘다. 77%
			$config_resize['image_library'] = 'gd2';
			$config_resize['source_image'] =  $root . "/" . $file_name . $data['upload_data']['file_ext'];
			$config_resize['new_image'] =  $root . "/resize/" . $file_name . $data['upload_data']['file_ext'];
			$config_resize['maintain_ratio']   = TRUE;
			$config_resize['quality']       = $quilty;
			$config_resize['width']       = $size;
			$config_resize['height']       = $size;

			$this->CI->load->library('image_lib');
			$this->CI->image_lib->initialize($config_resize);
			if (! $this->CI->image_lib->resize()) {
				echo $this->CI->image_lib->display_errors();
				$this->CI->image_lib->clear();
				return "";
			} else {
				$this->CI->image_lib->clear();
				$url = '/resource/uploads/' . $path . '/resize/'  . $file_name . $data['upload_data']['file_ext'];

				//리사이즈 성공시 기존파일 삭제
				if (unlink($root . "/" . $file_name . $data['upload_data']['file_ext'])) {
				}
				$result["RESIZE"] = true;
			}

			$result["IS_FILE"] = TRUE;
			$result["URL"] = $url;
			$result["FILE_NAME"] = $file_name . $data['upload_data']['file_ext'];
			$result["ORIG_NAME"] = $data['upload_data']['orig_name'];
			$result["ERROR"] = "";
			$result["WIDTH"] = $size;
			$result["HEIGHT"] = $size;
			$result["SIZE"] = $data['upload_data']['file_size'];
			$result["TYPE"] = $data['upload_data']['image_type'];
			$result["EMPTY"] = "";
			$result["SUCCESS"] = true;
			$result["TYPE_NUM"] = $this->getExtNum($data['upload_data']['file_ext']);
		}

		return $result;
	}

	function img_rotate($img_path)
	{
		//이미지 정보 가져오기.(정보가 없는 이미지는 패스)
		try {
			$exifData = @exif_read_data($img_path);

			// 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨
			if (isset($exifData['Orientation'])) {
				if ($exifData['Orientation'] == 6) {
					$degree = 270;
				}
				// 반시계방향으로 90도 돌려줘야 정상
				else if ($exifData['Orientation'] == 8) {
					$degree = 90;
				} else if ($exifData['Orientation'] == 3) {
					$degree = 180;
				}

				if ($degree) {
					if ($exifData['FileType'] == 1) {
						$source = imagecreatefromgif($img_path);
						$source = imagerotate($source, $degree, 0);
						imagegif($source, $img_path);
					} else if ($exifData['FileType'] == 2) {
						$source = imagecreatefromjpeg($img_path);
						$source = imagerotate($source, $degree, 0);
						imagejpeg($source, $img_path);
					} else if ($exifData['FileType'] == 3) {
						$source = imagecreatefrompng($img_path);
						$source = imagerotate($source, $degree, 0);
						imagepng($source, $img_path);
					}
					imagedestroy($source);
				}
			}
		} catch (Exception $ex) {
		}
	}

	//이미지 업로드후 URL을얻는다.
	function file_upload_url($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";

		if ($del == "Y") {
			return "";
		} else {
			$img = $this->file_upload($path, $ipt_name);

			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
			}
			return $url;
		}
	}

	//파일 업로드
	function file_upload($path, $ipt_name)
	{
		$this->CI = &get_instance();
		$real_upload_path = "/resource/uploads/";

		if (!file_exists(getcwd() . $real_upload_path . $path)) {
			mkdir(getcwd() . $real_upload_path . $path, 0755, true);
		}

		$root =  getcwd() . $real_upload_path . $path;

		$config['upload_path'] = $root;
		$config['max_size']	= '40048';
		$config['allowed_types'] = "*";
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;

		if (isset($_FILES[$ipt_name]) == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "";
			return $result;
		}

		if ($_FILES[$ipt_name]["name"] == "") {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "";
			return $result;
		}

		$chk_ext = $this->file_type_chk($_FILES[$ipt_name]["name"], "file");

		if ($chk_ext == false) {
			$result["SUCCESS"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = "허용되지 않은 파일 확장자 입니다.";
			return $result;
		}

		$info = $this->CI->load->library('upload', $config);
		$result = array(
			'IS_FILE' => FALSE,
			'URL' => "",
			'ERROR' => "",
			'FILE_NAME' => ""
		);

		if (! $this->CI->upload->do_upload($ipt_name)) {
			$error = array('error' => $this->CI->upload->display_errors());
			$result["IS_FILE"] = FALSE;
			$result["URL"] = "";
			$result["FILE_NAME"] = "";
			$result["ERROR"] = $this->CI->upload->display_errors('', '');
			$result["SUCCESS"] = false;
			if (empty($_FILES[$ipt_name]['name'])) {
				$result["EMPTY"] = "EMPTY";
			} else {
				$result["EMPTY"] = "";
			}
		} else {
			$data = array('upload_data' => $this->CI->upload->data());
			$file_name = $data['upload_data']['raw_name'];
			$url = $real_upload_path . $path . "/" . $file_name . $data['upload_data']['file_ext'];
			$result["IS_FILE"] = TRUE;
			$result["URL"] = $url;
			$result["FILE_NAME"] = $file_name . $data['upload_data']['file_ext'];
			$result["ORIG_NAME"] = $data['upload_data']['orig_name'];
			$result["ERROR"] = "";
			$result["WIDTH"] = $data['upload_data']['image_width'];
			$result["HEIGHT"] = $data['upload_data']['image_height'];
			$result["SIZE"] = $data['upload_data']['file_size'];
			$result["TYPE"] = $data['upload_data']['image_type'];
			$result["EMPTY"] = "";
			$result["SUCCESS"] = true;
			$result["TYPE_NUM"] = $this->getExtNum($data['upload_data']['file_ext']);
		}

		return $result;
	}

	//이미지 업로드후 URL + 원본파일명을 리턴받는다.
	function img_upload_url_name($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$name = isset($_POST[$ipt_name . "_name_ori"]) ? ($_POST[$ipt_name . "_name_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";
		if ($del == "Y") {
			return array("name" => "", "url" => "");
		} else {
			$img = $this->img_upload($path, $ipt_name);

			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
				$name = $_FILES[$ipt_name]["name"];
			}
			return array("name" => $name, "url" => $url);
		}
	}

	//업로드후 URL + 원본파일명을 리턴받는다.
	function img_upload_url($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";

		if ($del == "Y") {
			return "";
		} else {
			$img = $this->img_upload($path, $ipt_name);
			//$img = $this->img_upload_resize($path, $ipt_name);

			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
			}
			return $url;
		}
	}

	//업로드후 URL + 원본파일명을 리턴받는다.
	function img_upload_resize_url($path, $ipt_name, $size = 800)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";

		if ($del == "Y") {
			return "";
		} else {
			//$img = $this->img_upload($path, $ipt_name);
			$img = $this->img_upload_resize($path, $ipt_name, $size);

			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
			}
			return $url;
		}
	}

	//업로드후 URL + 원본파일명을 리턴받는다.
	function img_upload_url_auto($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";
		if ($del == "Y") {
			return "";
		} else {
			$img = $this->img_upload($path, $ipt_name);
			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
			}
			return $url;
		}
	}

	//이미지 리사이즈 업로드후 URL + 원본파일명을 리턴받는다.
	function img_upload_url_name_resize($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$name = isset($_POST[$ipt_name . "_name_ori"]) ? ($_POST[$ipt_name . "_name_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";
		if ($del == "Y") {
			return array("name" => "", "url" => "");
		} else {
			$img = $this->img_upload_resize($path, $ipt_name);
			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
				$name = $_FILES[$ipt_name]["name"];
			}
			return array("name" => $name, "url" => $url);
		}
	}

	//업로드후 URL + 원본파일명을 리턴받는다.
	function file_upload_url_name($path, $ipt_name)
	{
		$url = isset($_POST[$ipt_name . "_ori"]) ? ($_POST[$ipt_name . "_ori"]) : "";;
		$name = isset($_POST[$ipt_name . "_name_ori"]) ? ($_POST[$ipt_name . "_name_ori"]) : "";;
		$del = isset($_POST[$ipt_name . "_del"]) ? ($_POST[$ipt_name . "_del"]) : "N";
		$error = "";
		if ($del == "Y") {
			return array("name" => "", "url" => "", "error" => "");
		} else {
			$img = $this->file_upload($path, $ipt_name);
			if ($img["SUCCESS"] == true) {
				$url = $img["URL"];
				$name = $_FILES[$ipt_name]["name"];
			}
			return array("name" => $name, "url" => $url, "error" => $error);
		}
	}

	//파일 유형 검증
	function file_type_chk($file_name, $type = "img")
	{
		try {
			$return = false;
			$extension = pathinfo($file_name, PATHINFO_EXTENSION);
			if ($type == "file") {
				$extArray = ['hwp', 'xls', 'doc', 'xlsx', 'docx', 'pdf', 'jpg', 'jpeg', 'gif', 'png', 'txt', 'ppt', 'pptx', 'zip'];
				foreach ($extArray as $ext) {
					if (strtolower($ext) == strtolower($extension)) {
						$return = true;
					}
				}
				return $return;
			} else if ($type == "img") {
				$extArray = ['jpg', 'jpeg', 'gif', 'png'];
				foreach ($extArray as $ext) {
					if (strtolower($ext) == strtolower($extension)) {
						$return = true;
					}
				}
				return $return;
			}
		} catch (Exception $ex) {
			return false;
		}
	}

	//확장자별 각도
	function getExtNum($ext)
	{
		if (strtolower($ext) == ".pdf") {
			return 0;
		} else if (strtolower($ext) == ".gif") {
			return 1;
		} else if (strtolower($ext) == ".jpg") {
			return 2;
		} else if (strtolower($ext) == ".jpg2") {
			return 2;
		} else if (strtolower($ext) == ".png") {
			return 3;
		} else if (strtolower($ext) == ".bmp") {
			return 6;
		} else {
			return 9;
		}
	}

	//CURL GET방식 처리한다.
	function httpGet($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);

		curl_close($ch);
		return $output;
	}

	//이메일 발송 로직
	function mailer($sendor, $sendor_email, $_email, $_title, $_content)
	{
		$sender = $sendor;
		$sender_mail = $sendor_email;

		//네이버 깨짐현상
		if (preg_match("/@naver.com/i", $_email)) {
			$_header = "From: \"" . $sender . "\" <" . $sender_mail . "> \r\n";
		} else {
			$_header = "From: \"" . "=?UTF-8?B?" . base64_encode($sender) . "?="  . "\" <" . $sender_mail . "> \r\n";
		}
		$_header .= "MIME-Version: 1.0\r\n";
		$_header .= "Content-Type: text/html; charset=utf-8\r\n";
		$_header .= "Reply-To:" . $sender_mail . "\r\n";

		return @mail($_email, '=?UTF-8?B?' . base64_encode($_title) . '?=', $_content, $_header,  "-f" . $sender_mail);
	}

	function getReturn($code, $msg = "")
	{
		return ["code" => $code, "msg" => $msg];
	}

	function getRemainCoin($sms_id, $sms_key)
	{

		$postvars = '"username":"' . $sms_id . '"';
		$postvars = $postvars . ', "key":"' . $sms_key . '"';
		$postvars = '{' . $postvars . '}';      //JSON 데이터

		$url = "https://directsend.co.kr/index.php/api_v2/remaining_money";

		//헤더정보
		$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, TRUE);
	}

	function getSmsData($data, $array)
	{
		$template = [
			"USER_NAME" => "{{USER_NAME}}",
			"USER_ID" => "{{USER_ID}}",
			"USER_EMAIL" => "{{USER_EMAIL}}",
			"USER_JOIN_DT" => "{{USER_JOIN_DT}}",
			"ORDER_NO" => "{{ORDER_NO}}",
			"ORDER_USER_NAME" => "{{ORDER_USER_NAME}}",
			"ORDER_PRICE" => "{{ORDER_PRICE}}",
			"ORDER_STATUS" => "{{ORDER_STATUS}}",
			"ORDER_DT" => "{{ORDER_DT}}",
			"PAY_METHOD" => "{{PAY_METHOD}}",
			"CANCEL_PRICE" => "{{CANCEL_PRICE}}",
			"CANCEL_DT" => "{{CANCEL_DT}}",
			"REFUND_PRICE" => "{{REFUND_PRICE}}",
			"REFUND_DT" => "{{REFUND_DT}}",
			"CHANGE_PRICE" => "{{CHANGE_PRICE}}",
			"CHANGE_DT" => "{{CHANGE_DT}}",
			"SMS_AUTH" => "{{SMS_AUTH}}",
			"ACC_INFO" => "{{ACC_INFO}}",
			"BANK_RES_DT" => "{{BANK_RES_DT}}",
			"URL" => "{{URL}}",
			"PWD" => "{{PWD}}",
		];


		foreach ($template as $key => $tmp) {
			if (isset($array[$key])) {
				$data = str_replace($tmp, $array[$key], $data);
			}
		}
		return $data;
	}

	//sms 발송처리 뿌리오
	function sendSms($phone, $msg, $auth = "")
	{
		$db = DB('default', true);

		$_api_url = 'https://message.ppurio.com/api/send_utf8_json.php';     // UTF-8 인코딩과 JSON 응답용 호출 페이지
		// Android SMS Retriever API용 앱 해시값 (AppSignatureHelper로 추출한 11자리 값)
		// 해시값 추출 방법: 앱에서 AppSignatureHelper 실행 후 로그 확인
		$android_hash = "b7Yd0JKDM2U"; // Android SMS Retriever API 앱 해시값 (릴리즈 서명 기준)
		$msg = $msg . "\n" . $android_hash;

		$_param['userid'] = 'dongwonstec124';           // [필수] 뿌리오 아이디
		$_param['callback'] = '0328330018';    // [필수] 발신번호 - 숫자만 07077122610
		$_param['phone'] = $phone;       // [필수] 수신번호 - 여러명일 경우 |로 구분 '010********|010********|010********'
		$_param['msg'] = $msg;   // [필수] 문자내용 - 이름(names)값이 있다면 [*이름*]가 치환되서 발송됨
		$_param['names'] = '';            // [선택] 이름 - 여러명일 경우 |로 구분 '홍길동|이순신|김철수'
		$_param['appdate'] = '';  // [선택] 예약발송 (현재시간 기준 10분이후 예약가능)
		$_param['subject'] = '';          // [선택] 제목 (30byte)
		$_param['file1'] = ''; // [선택] 포토발송 (jpg, jpeg만 지원  300 K  이하)

		$_curl = curl_init();
		curl_setopt($_curl, CURLOPT_URL, $_api_url);
		curl_setopt($_curl, CURLOPT_POST, true);
		curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($_curl, CURLOPT_POSTFIELDS, $_param);
		$_result = curl_exec($_curl);
		curl_close($_curl);
		$arr_info = json_decode($_result, true);

		//발송 리스트 입력
		$sms_insert = [
			"sl_type" => "비밀번호인증",
			"sl_phone" => $phone,
			"sl_content" => $msg,
			"sl_status" => $arr_info["result"] == "ok" ? "Y" : "N",
			"sl_auth" => $auth,
			"sl_result" => $arr_info["result"],
			"reg_id" => "system"
		];

		if (!$result = $db->insert('st_sms_send_list', $sms_insert)) {
		}

		$result = [
			"code" => $arr_info["result"] == "ok" ? "00" : "99",
			"text" => $arr_info["result"],
		];

		return $result;
	}

	// 뿌리오 설정 로드 (PpurioClient와 동일한 방식)
	private function getPpurioConfig()
	{
		if (!isset($this->_ppurio_config)) {
			$this->_ppurio_config = array();
			if (function_exists('config_item')) {
				$cfg = config_item('ppurio');
				if (is_array($cfg)) {
					$this->_ppurio_config = $cfg;
					return $this->_ppurio_config;
				}
			}
            // SHAREDPATH:/var/www/html/DEV_NOWAPK/application/common/
            // APPPATH:/var/www/html/DEV_NOWAPK/application/shop/

			$configPath = (defined('SHAREDPATH') ? SHAREDPATH : APPPATH . 'common/') . 'config/ppurio.php';
			if (file_exists($configPath)) {
				$config = array();
				include $configPath;
				if (isset($config['ppurio']) && is_array($config['ppurio'])) {
					$this->_ppurio_config = $config['ppurio'];
				}
			}
		}
		return $this->_ppurio_config;
	}

	// 뿌리오 토큰 발급 (24시간 유효) - v1 공식 API
	private function getPpurioAccessToken()
	{
		$token_url = 'https://message.ppurio.com/v1/token';
		$ppurio_config = $this->getPpurioConfig();

		$account = isset($ppurio_config['account']) ? $ppurio_config['account'] : '';
		$access_key = isset($ppurio_config['access_key']) ? $ppurio_config['access_key'] : '';

		if (empty($account) || empty($access_key)) {
			return ['ok' => false, 'error' => 'ppurio 설정 누락(account/access_key)'];
		}

		$authHeader = 'Basic ' . base64_encode($account . ':' . $access_key);

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $token_url,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => ['Authorization: ' . $authHeader],
			CURLOPT_POSTFIELDS => '',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		]);

		$response = curl_exec($ch);
		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($response === false) {
			return ['ok' => false, 'error' => 'cURL 오류: ' . $curl_error];
		}

		if ($httpStatus < 200 || $httpStatus >= 300) {
			return ['ok' => false, 'error' => 'HTTP ' . $httpStatus];
		}

		$decoded = json_decode($response, true);
		$token = isset($decoded['token']) ? $decoded['token'] : (isset($decoded['access_token']) ? $decoded['access_token'] : '');

		if (empty($token)) {
			// API 응답 전체를 로그에 남김 (디버깅용)
			
			return ['ok' => false, 'error' => 'token 누락'];
		}

		return ['ok' => true, 'access_token' => $token];
	}

	// 알림 발송용 SMS - Ppurio 공식 API v1 기준
	function sendNotificationSms($phone, $msg, $messageType = 'SMS')
	{
		// 파라미터 유효성 검사
		if (empty($phone) || empty($msg)) {
			return [
				"code" => "99",
				"text" => "phone 또는 msg가 비어있습니다.",
				"result" => "fail"
			];
		}

		$db = DB('default', true);

		// 토큰 발급
		$tokenResult = $this->getPpurioAccessToken();
		if (!isset($tokenResult['ok']) || !$tokenResult['ok']) {
			$error_msg = isset($tokenResult['error']) ? $tokenResult['error'] : '토큰 발급 실패';

			$sms_insert = [
				"sl_type" => "알림발송",
				"sl_phone" => $phone,
				"sl_content" => $msg,
				"sl_status" => "N",
				"sl_auth" => "",
				"sl_result" => "token_error: " . $error_msg,
				"reg_id" => "system"
			];
			$db->insert('st_sms_send_list', $sms_insert);

			return [
				"code" => "99",
				"text" => "토큰 발급 실패: " . $error_msg,
				"result" => "fail"
			];
		}

		$accessToken = $tokenResult['access_token'];
		$api_url = 'https://message.ppurio.com/v1/message';

		$ppurio_config = $this->getPpurioConfig();
		$account = isset($ppurio_config['account']) ? $ppurio_config['account'] : '';
		$fromNumber = isset($ppurio_config['from']) ? $ppurio_config['from'] : '0328330018';

		// 수신자 정보 구성
		$target = [
			'to' => preg_replace('/[^0-9]/', '', $phone) // 숫자만 추출
		];

		// 요청 본문 구성 (공식 API v1 기준)
		$payload = [
			'account' => $account,
			'messageType' => $messageType, // SMS/LMS/MMS
			'from' => $fromNumber,
			'duplicateFlag' => 'N',
			'content' => $msg,
			'targetCount' => 1,
			'targets' => [$target],
			'refKey' => 'notify-' . date('YmdHis') . '-' . mt_rand(10000, 99999)
		];

		// cURL 요청
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $api_url,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json'
			],
			CURLOPT_POSTFIELDS => json_encode($payload),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		]);

		$response = curl_exec($ch);
		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_error = curl_error($ch);
		curl_close($ch);

		// 응답 처리
		if ($response === false) {
			$sms_insert = [
				"sl_type" => "알림발송",
				"sl_phone" => $phone,
				"sl_content" => $msg,
				"sl_status" => "N",
				"sl_auth" => "",
				"sl_result" => "curl_error: " . $curl_error,
				"reg_id" => "system"
			];
			$db->insert('st_sms_send_list', $sms_insert);

			return [
				"code" => "99",
				"text" => "API 통신 오류: " . $curl_error,
				"result" => "fail"
			];
		}

		$is_success = ($httpStatus >= 200 && $httpStatus < 300);
		$arr_info = json_decode($response, true);

		// 발송 결과 로그
		$sms_insert = [
			"sl_type" => "알림발송",
			"sl_phone" => $phone,
			"sl_content" => $msg,
			"sl_status" => $is_success ? "Y" : "N",
			"sl_auth" => "",
			"sl_result" => "HTTP_" . $httpStatus,
			"reg_id" => "system"
		];
		$db->insert('st_sms_send_list', $sms_insert);

		if (!$is_success) {
			return [
				"code" => "99",
				"text" => "HTTP " . $httpStatus,
				"result" => "fail",
				"response" => $arr_info
			];
		}

		return [
			"code" => "00",
			"text" => "발송 성공",
			"result" => "ok",
			"response" => $arr_info
		];
	}

	//sms 발송처리
	function sendTalk($data)
	{
		//데이터 가져오기
		$setting = $this->getSettingEtc();

		//미발송처리면 발송하지 않는다.
		if ($setting["st_kakao_yn"] == "N") {
			return getReturn("10", "발송 설정  하지 않음");
		}

		//SMS발송 기본정보 설정
		$key = $setting["st_direct_key"];
		$username = $setting["st_direct_id"];
		$sendor = $setting["st_sms_sendor"];
		$kakao_plus_id = "";

		//데이터 가져오기
		$ss_code = $data["setting"]["code"];
		$db = DB('default', true);
		$sql = "select *
    			FROM st_sms_setting
    			where ss_code ='$ss_code'";

		$qry = $db->query($sql);
		$setting_sms = $qry->row_array();

		if ($setting_sms == null) {
			return null;
		}

		//발송 내용 처리
		$content = $this->getSmsData($setting_sms["ss_content"], $data["data"]);

		//발송 데이터 설정
		$receiver = array();
		$receiver_info = array("name" => "박현종", "mobile" => $data["setting"]["mobile"], "note1" => "", "note2" => "", "note3" => "", "note4" => "", "note5" => "");
		array_push($receiver, $receiver_info);

		$postvars = array(
			'sender' => $sendor,
			'username' => $username,
			'key' => urlencode($key),
			'title' => $setting_sms["ss_subject"],
			'message' => $content,
			'receiver' => $receiver
		);

		//URL
		$url = "https://directsend.co.kr/index.php/api_v2/sms_change_word";

		//헤더정보
		$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postvars));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);

		//결과값 확인
		$arr_info = json_decode($response);

		//발송 리스트 입력
		$sms_insert = [
			"ss_type" => $setting_sms["ss_type"],
			"ss_code" => $setting_sms["ss_code"],
			"ss_subject" => $setting_sms["ss_subject"],
			"sl_phone" => $data["setting"]["mobile"],
			"sl_email" => "",
			"sl_content" => $content,
			"sl_status" => "Y",
			"sl_auth" => (isset($data["data"]["SMS_AUTH"]) ? $data["data"]["SMS_AUTH"] : ""),
			"sl_return" => "",
			"reg_id" => "system"
		];
		if (!$result = $db->insert('st_sms_send_list', $sms_insert)) {
		}

		return $arr_info;
	}

	//페이지 가져오기
	function getPageInfo($page, $total, $size)
	{
		$paging['last_page'] = ceil($total / $size);
		$paging['current_page'] = $page;
		$paging['suffix'] = '';
		$paging['url'] =  $this->removeParam("page", $page);
		return $this->getPagination($paging);
	}

	//페이지 처리
	function getPagination($configure = false)
	{
		if (is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
			$digit = strpos($configure['url'], "?") == false ? "?" : "&";
			$configure['last_page'] = (int)$configure['last_page'];

			$html_return_string = '<div class="box-pagenation"><ul>';
			$page = (int)(($configure['current_page'] - 1) / 10) * 10;

			if (($page != 0) && ($configure['last_page'] > 10)) {
				if (($configure['last_page'] > 20) && ($configure['current_page'] > 20)) {
					$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=1' . $configure['suffix']) . '">처음</a></li>';
				}
				$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . ($page - 9) . $configure['suffix']) . '">&laquo;</a></li>';
			}

			$loop_page_count = (($configure['last_page'] - $page) >= 10) ? 10 : $configure['last_page'] - $page;

			for ($i = $page + 1; $i <= $page + $loop_page_count; $i++) {
				if ($i == $configure['current_page']) {
					$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . $i . $configure['suffix']) . '" class="current">' . $i . '</a></li>';
				} else {
					$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . $i . $configure['suffix']) . '">' . $i . '</a></li>';
				}
			}

			if (($configure['last_page'] > 10) && ($page + 10 < $configure['last_page'])) {
				$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . ($page + 11) . $configure['suffix']) . '" class="nextpage">다음</a></li>';
				if (($configure['last_page'] - $page) > 20) {
					if (is_int($configure['last_page'] / 10)) {
						$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . (((int)($configure['last_page'] / 10)) * 10) . $configure['suffix']) . '">&raquo;</a></li>';
					} else {
						$html_return_string .= '<li><a href="' . ($configure['url'] . $digit . 'page=' . (((int)($configure['last_page'] / 10)) * 10 + 1) . $configure['suffix']) . '">&raquo;</a></li>';
					}
				}
			}

			$html_return_string .= '</ul></div>';

			return $html_return_string;
		} else {
			return false;
		}
	}

	function getMailBody($mode, $info)
	{
		$http_host = $_SERVER['HTTP_HOST'];
		$current_url = "http://" . $http_host;

		if ($mode == "order") {
			$body = "
			<div style='border:none;max-width:800px;text-align:center;margin-top:30px;border:solid 1px #eee;padding:20px;'>
				<main role='main'>
					<div>
						<h2>주문완료 안내메일</h2>
					</div>
					<section style='width:100%;margin-top:30px;'>
						<table style='border:solid 1px #ddd;width:100%;border-collapse:collapse;' >
							<caption style='text-align:left;color:#333;height:30px;'>결제정보</caption>
							<tbody>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>주문번호</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_num"] . "</td>
								</tr>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>주문일시</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["reg_dt"] . "</td>
								</tr>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>결제수단</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_paymethod"] . "</td>
								</tr>

								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>총 결제금액</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . number_format($info["order"]["o_total_price"]) . "원</td>
								</tr>
			";

			if ($info["order"]["o_paymethod"] == "VBANK") {
				$body .= "
									<tr>
										<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>입금은행</th>
										<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_bank_name"] . "</td>
									</tr>
									<tr>
										<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>계좌정보</th>
										<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_bank_num"] . "</td>
									</tr>
									<tr>
										<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>입금기한</th>
										<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_bank_res_dt"] . "</td>
									</tr>
				";
			}

			$body .= "
							</tbody>
						</table>
					</section>
			";

			$body .= "
					<section style='width:100%;margin-top:30px;'>
						<table style='border:solid 1px #ddd;width:100%;border-collapse:collapse;' >
							<caption style='text-align:left;color:#333;height:30px;'>주문상품</caption>
							<colgroup>
							</colgroup>
							<thead>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;width:100px;'>이미지</th>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>상품명</th>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>주문정보</th>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>결제금액</th>
								</tr>
							</thead>
							<tbody>
			";

			foreach ($info["order"]["seller"] as $seller) {
				foreach ($seller["goods"] as $prd) {
					$body .= "
										<tr>
											<td style='padding:0 10px;height:35px;border:solid 1px #ddd;padding:10px;'>
												<a href='$current_url/goods/detail/" . $prd["gd_idx"] . "' target='_blank'><img style='width:100px;' src='$current_url" . getGoodsListImg($prd) . "' alt='detail' /></a>
											</td>
											<td style='padding:0 10px;height:35px;border:solid 1px #ddd;'>
												" . $prd["gd_name"] . "
											</td>
											<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:center;'>
												" . $prd["op_price"] . "원 * " . $prd["op_cnt"] . "개
											</td>
											<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:center;'>
												" . ($prd["op_price"] * $prd["op_cnt"]) . "원
											</td>
										</tr>
					";
				}
			}
			$body .= "
							</tbody>
						</table>
					</section>
					<section style='width:100%;margin-top:30px;'>
						<table style='border:solid 1px #ddd;width:100%;border-collapse:collapse;' >
							<caption style='text-align:left;color:#333;height:30px;'>주문정보</caption>
							<tbody>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>이름</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_oname"] . "</td>
								</tr>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>연락처</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_ohp"] . "</td>
								</tr>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>주소</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_raddr_doro"] . " " . $info["order"]["o_raddr_dtl"] . "</td>
								</tr>
								<tr>
									<th style='background:#eee;height:40px;padding:0 10px;width:140px;border:solid 1px #ddd;'>배송문구</th>
									<td style='padding:0 10px;height:35px;border:solid 1px #ddd;text-align:left;'>" . $info["order"]["o_message"] . "</td>
								</tr>
							</tbody>
						</table>
						<div style='margin-top:30px;'>
							<a style='display:inline-block;padding:12px 30px;background:#eee;border:solid 1px #ccc;' target='_blank' href='$current_url/mypage'>주문확인</a>
						</div>
					</section>
				</main>
			</div>
			";

			return $body;
		}
	}
}
