<?php
class memberModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}


	######################################
	# nice 모률 처리 START
	######################################
	function getNiceEncData()
	{
		$CI = &get_instance();
		$CI->load->model('settingmodel', 'setting_model');

		$nice_setting = $CI->setting_model->getSettingInfo(["st_group" => "나이스인증"]);

		# 본인인증 처리
		$sitecode = $nice_setting["sitecode"];
		$sitepasswd = $nice_setting["sitepasswd"];
		$cb_encode_path = $nice_setting["path"];
		$authtype = "";
		$customize 	= "";
		$reqseq = session_id();

		# 세션 생성
		$_SESSION["REQ_SEQ"] = $reqseq;

		$reqseq = `$cb_encode_path SEQ $sitecode`;
		$returnurl = $nice_setting["success"];;	// 성공시 이동될 URL
		$errorurl = $nice_setting["fail"];;		// 실패시 이동될 URL

		$_SESSION["REQ_SEQ"] = $reqseq;
		$plaindata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
			"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
			"9:AUTH_TYPE" . strlen($authtype) . ":" . $authtype .
			"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
			"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
			"9:CUSTOMIZE" . strlen($customize) . ":" . $customize;

		$enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

		if ($enc_data == -1) {
			$returnMsg = "암/복호화 시스템 오류입니다.";
			$enc_data = "";
		} else if ($enc_data == -2) {
			$returnMsg = "암호화 처리 오류입니다.";
			$enc_data = "";
		} else if ($enc_data == -3) {
			$returnMsg = "암호화 데이터 오류 입니다.";
			$enc_data = "";
		} else if ($enc_data == -9) {
			$returnMsg = "입력값 오류 입니다.";
			$enc_data = "";
		}

		return $enc_data;
	}

	function getNiceReturnData($type = "success")
	{
		$CI = &get_instance();
		$CI->load->model('settingmodel', 'setting_model');

		$nice_setting = $CI->setting_model->getSettingInfo(["st_group" => "나이스인증"]);

		$sitecode = $nice_setting["sitecode"];
		$sitepasswd = $nice_setting["sitepasswd"];
		$cb_encode_path = $nice_setting["path"];

		$enc_data = $_REQUEST["EncodeData"];

		$return = [
			"code" => "",
			"msg" => "",
			"errcode" => "",
			"requestnumber" => "",
			"responsenumber" => "",
			"authtype" => "",
			"name" => "",
			"birthdate" => "",
			"gender" => "",
			"nationalinfo" => "",
			"dupinfo" => "",
			"conninfo" => "",
			"mobileno" => "",
			"mobileco" => "",
		];

		if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
			$return["code"] = "90";
			$return["msg"] = "문자열 오류 발생";
			return $return;
		}

		if (base64_encode(base64_decode($enc_data)) != $enc_data) {
			$return["code"] = "91";
			$return["msg"] = "인코딩 오류 발생";
			return $return;
		}

		if ($enc_data != "") {
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화

			if ($plaindata == -1) {
				$return["code"] = "92";
				$return["msg"] = "암/복호화 시스템 오류";
				return $return;
			} else if ($plaindata == -4) {
				$return["code"] = "92";
				$return["msg"] = "복호화 처리 오류";
				return $return;
			} else if ($plaindata == -5) {
				$return["code"] = "93";
				$return["msg"] = "HASH값 불일치 - 복호화 데이터는 리턴됨";
				return $return;
			} else if ($plaindata == -6) {
				$return["code"] = "93";
				$return["msg"] = "복호화 데이터 오류";
				return $return;
			} else if ($plaindata == -9) {
				$return["code"] = "93";
				$return["msg"] = "입력값 오류";
				return $return;
			} else if ($plaindata == -12) {
				$return["code"] = "93";
				$return["msg"] = "사이트 비밀번호 오류";
				return $return;
			} else {
				if ($type == "success") {
					# 정상 처리
					$ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;

					$return["requestnumber"] = $this->getNiceValue($plaindata, "REQ_SEQ");
					$return["responsenumber"] = $this->getNiceValue($plaindata, "RES_SEQ");
					$return["authtype"] = $this->getNiceValue($plaindata, "AUTH_TYPE");
					$return["name"] = $this->getNiceValue($plaindata, "UTF8_NAME");
					$return["birthdate"] = $this->getNiceValue($plaindata, "BIRTHDATE");
					$return["gender"] = $this->getNiceValue($plaindata, "GENDER");
					$return["nationalinfo"] = $this->getNiceValue($plaindata, "NATIONALINFO");
					$return["dupinfo"] = $this->getNiceValue($plaindata, "DI");
					//$return["conninfo"] = $this->getNiceValue($plaindata , "CI");
					$return["mobileno"] = $this->getNiceValue($plaindata, "MOBILE_NO");
					//$return["mobileco"] = $this->getNiceValue($plaindata , "MOBILE_CO");

					if (strcmp($_SESSION["REQ_SEQ"], $return["requestnumber"]) != 0) {
						$return["code"] = "94";
						$return["msg"] = "세션 정보 불일치";
						return $return;
					} else {
						$return["code"] = "00";
						$return["msg"] = "처리 성공";
						return $return;
					}
				} else {
					# 정상 처리
					$ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;

					$return["errcode"] = $this->getNiceValue($plaindata, "ERR_CODE");
					$return["authtype"] = $this->getNiceValue($plaindata, "AUTH_TYPE");
					$return["code"] = "97";
					$return["msg"] = "본인인증 확인 실패하였습니다.[errorCode : " . $return["errcode"] . "]";
					return $return;
				}
			}
		}
	}

	function getNiceValue($str, $name)
	{
		try {
			$pos1 = 0;  //length의 시작 위치
			$pos2 = 0;  //:의 위치

			while ($pos1 <= strlen($str)) {
				$pos2 = strpos($str, ":", $pos1);
				$len = substr($str, $pos1, $pos2 - $pos1);
				$key = substr($str, $pos2 + 1, $len);
				$pos1 = $pos2 + $len + 1;
				if ($key == $name) {
					$pos2 = strpos($str, ":", $pos1);
					$len = substr($str, $pos1, $pos2 - $pos1);
					$value = substr($str, $pos2 + 1, $len);
					return $value;
				} else {
					// 다르면 스킵한다.
					$pos2 = strpos($str, ":", $pos1);
					$len = substr($str, $pos1, $pos2 - $pos1);
					$pos1 = $pos2 + $len + 1;
				}
			}
		} catch (Exception $ex) {
			return "";
		}
	}


	######################################
	# END nice 모률 처리
	######################################



	######################################
	# 회원처리 START
	######################################

	# 주소 GPS정보 등록
	function insertAddress($data)
	{
		return $this->db_insert("st_address", $data);
	}

	# 주소 GPS정보 수정
	function updateAddress($data, $where)
	{
		return $this->db_update("st_address", $data, $where);
	}

	# 주소 GPS정보 수정
	function getAddress($where)
	{
		return $this->db_row("st_address", $where);
	}

	# 주소 GPS정보 삭제
	function deleteAddress($where)
	{
		return $this->db_delete("st_address", $where);
	}

	# 주소 GPS정보 수정
	function getAddressList($where)
	{
		$addr_list = $this->db_list("st_address", $where, "*", ["ad_idx" => "desc"]);

		foreach ($addr_list as $key => $row) {
			if ($row["ad_addr"] == "지역 선택 없음") {
				unset($addr_list[$key]);
			}
		}

		return $addr_list;
	}

	# 비회원 수정
	function updateGuest($data, $where)
	{
		return $this->db_update("st_guest", $data, $where);
	}

	# 비회원 조회
	function getGuest($where)
	{
		//error_log("getGuest ".implode(",",$where), "3", "/testlog/php_log");
		return $this->db_row("st_guest", $data, $where);
	}

	# 회원정보 조회 : 기본
	function getMember($where)
	{
		return $this->db_row("st_member", $where);
	}

	# 회원추가정보 조회
	function getMemberInfo($mm_idx)
	{
		$sql = "
			select aa.*
                ,mi.ma_age
                ,ad.ad_addr
                ,ifnull(bb.cnt,0) as req_cnt
                ,ifnull(cc.cnt,0) as fav_cnt
                ,ifnull(dd.cnt,0) as hit_cnt
                ,ifnull(eval_num,5) as eval_num
			FROM st_member aa
				left join st_member_info mi
					on aa.mm_idx = mi.mm_idx
                left join (
                	select mm_idx,count(*) as cnt
                    FROM st_member_request
                    group by mm_idx
                ) bb on aa.mm_idx = bb.mm_idx
                left join (
                	select mm_idx,count(*) as cnt
                    FROM st_bookmark
                    where bk_type = 'RECRUIT'
                    group by mm_idx
                ) cc on aa.mm_idx = cc.mm_idx
                left join (
                    select aa.mm_idx,sum(aa.ru_hit)  as cnt
                    FROM st_resume aa
                    group by aa.mm_idx
                ) dd on aa.mm_idx = dd.mm_idx
                left join (
                    select ru_mm_idx
                        ,sum(iv_non_cnt) as non_cnt
                        ,sum(iv_abs_cnt) as abs_cnt
                        ,sum(iv_lv_cnt) as lv_cnt
                        ,ROUND(avg((case when iv_eval_yn = 'Y' then ((iv_eval_num1 + iv_eval_num2 + iv_eval_num3)/3) else 0 end)),1) as eval_num
                        ,count(*) as cnt
                    FROM st_interview
                    group by ru_mm_idx
                ) iv
                    on aa.mm_idx = iv.ru_mm_idx
         	left join st_address ad
                on aa.mm_idx = ad.mm_idx and ad.ad_type = 'MEMBER'
			where aa.mm_idx = '$mm_idx'
		";

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	# 회원정보 등록
	function insertMember($data)
	{
		return $this->db_insert("st_member", $data);
	}

	# 회원정보 수정
	function updateMember($data, $where)
	{
		# 업데이트 자동 추가
		$data["up_dt"] = get_today();
		return $this->db_update("st_member", $data, $where);
	}

	# 회원정보 등록
	function insertMemberInfo($data)
	{
		return $this->db_insert("st_member_info", $data);
	}

	# 회원정보 등록
	function insertMemberCareer($data)
	{
		return $this->db_insert("st_member_career", $data);
	}

	# 회원정보 등록
	function insertMemberCerti($data)
	{
		return $this->db_insert("st_member_certi", $data);
	}

	function getMemberCareerList($where)
	{
		return $this->db_list("st_member_career", $where, "*", ["mc_idx" => "asc"]);
	}

	function getMemberCertiList($where)
	{
		return $this->db_list("st_member_certi", $where, "*", ["mc_idx" => "asc"]);
	}

	function isMemberDevice($where)
	{
		return $this->db_is_data("st_member_device", $where);
	}

	# 회원기기정보 조회
	function getMemberDevice($where)
	{
		return $this->db_row("st_member_device", $where);
	}

	# 회원기기정보 조회
	function deleteMemberDevice($where)
	{
		return $this->db_delete("st_member_device", $where);
	}

	# 회원기기정보 등록
	function insertMemberDevice($data)
	{
		return $this->db_insert("st_member_device", $data);
	}

	# 회원기기정보 업데이트
	function updateMemberDevice($data, $where)
	{
		return $this->db_update("st_member_device", $data, $where);
	}

	# 회원기기정보 업데이트
	function setMemberDevice($data, $where)
	{
		if ($this->isMemberDevice($where) == true) {
			$data["up_dt"] = get_today();
			return $this->updateMemberDevice($data, $where);
		} else {
			return $this->insertMemberDevice($data);
		}
	}

	//회원 리스트
	function getMemberList($params)
	{
		//컬럼
		$cols = [
			"aa.*,bb.ma_work",
			"(case when mm_type = 'COMP' then '기업' else '개인' end) as mm_type_nm",
			"(SELECT ad_addr FROM st_address WHERE ad_type = 'RESUME' AND mm_idx = aa.mm_idx ORDER BY ad_idx DESC LIMIT 1) as ad_addr",
			"(
				case when mm_status = 'Y' then '가입회원'
					   when mm_status = 'N' then '탈퇴회원'
             when mm_status = 'S'  then '자격정지'
             when mm_status = 'SY' then '자격정지'
             when mm_status = 'SN' then '자격정지'
             when mm_status = 'SH' then '자격정지'
					 else ''
			    end) as mm_status_nm",
			"(
				case when mm_join_type = 'KAKAO' then '카카오'
					 when mm_join_type = 'APPLE' then '애플'
					 when mm_join_type = 'NAVER' then '네이버'
					 when mm_join_type = 'GOOGLE' then '구글'
					 when mm_join_type = 'WECHAT' then '위쳇'
					 when mm_join_type = 'WEB' then '이메일'
					 else ''
			    end) as mm_join_type_nm"
		];

		//바디
		$body = "
			st_member aa
                left join st_member_info bb
                    on aa.mm_idx = bb.mm_idx
		";

		//조건
		$where = [];

		$where[] = 	" and mm_status in ('Y','N','S','SY','SN','SH')  ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_phone like '%" . $this->inject($params["search_value"]) . "%'
                              or (SELECT ad_addr FROM st_address WHERE ad_type = 'RESUME' AND mm_idx = aa.mm_idx ORDER BY ad_idx DESC LIMIT 1) like '%" . $this->inject($params["search_value"]) . "%'
                              or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_id") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_phone") {
				$where[] = 	" and (mm_phone like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and ((SELECT ad_addr FROM st_address WHERE ad_type = 'RESUME' AND mm_idx = aa.mm_idx ORDER BY ad_idx DESC LIMIT 1) like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		//등급 설정
		if ($params["mm_type"] != "") {
			$where[] = 	" and (aa.mm_type = '" . $this->inject($params["mm_type"]) . "')  ";
		}

		//회원상태 설정
		if ($params["mm_status"] != "") {
			if ($params["mm_status"] == 'S') {
				$where[] = 	" and (mm_status in('S','SY','SN','SH') )  ";
			} else {
				$where[] = 	" and (mm_status = '" . $this->inject($params["mm_status"]) . "')  ";
			}
		}

		//회원상태 설정
		if ($params["mm_sex"] != "") {
			$where[] = 	" and (mm_sex = '" . $this->inject($params["mm_sex"]) . "')  ";
		}

		//회원상태 설정
		if ($params["mm_nation"] != "") {
			$where[] = 	" and (mm_nation = '" . $this->inject($params["mm_nation"]) . "')  ";
		}

		//정렬
		$order_by = "aa.mm_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}


	# 업체 리스트 조회
	function getRecoCompList() {}

	######################################
	# END 회원처리
	######################################

	######################################
	# 이력서 처리 START
	######################################

	# 이력서 조회 : 기본
	function getResume($where)
	{
		return $this->db_row("st_resume", $where);
	}

	# 이력서 조회 : 기본
	function getResumeDetail($ru_idx, $mm_idx)
	{
		$sql = "
            select bb.*
                ,group_concat(ad.ad_addr) as ad_addr
                ,aa.mm_name,aa.mm_id
                ,aa.mm_nation
                ,aa.mm_sex
                ,mi.ma_age
                ,aa.mm_thumb
                ,(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn
                ,ifnull(mq.cnt,0) as req_cnt
                ,ifnull(bk.cnt,0) as fav_cnt
                ,ifnull(iv.non_cnt,0) as non_cnt
                ,ifnull(iv.abs_cnt,0) as abs_cnt
                ,ifnull(iv.lv_cnt,0) as lv_cnt
                ,ifnull(iv.eval_num,0) as eval_num
                ,(ifnull(iv.eval_num,0) * 20) as eval_per
                ,(SELECT pm_idx FROM st_promotion WHERE rc_idx = bb.ru_idx AND pm_code = 'M001' AND pm_status = 'Y' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx
            from st_member aa
            left join st_member_info mi
                on aa.mm_idx = mi.mm_idx
            inner join st_resume bb
                on aa.mm_idx = bb.mm_idx
            inner join st_address ad
                    on bb.mm_idx = ad.mm_idx and bb.ru_idx = ad.ad_key and ad.ad_type = 'RESUME'
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '$mm_idx'
            left join (
                select ru_idx,count(*) as cnt
                FROM st_member_request
                group by ru_idx
            ) mq
                on bb.ru_idx = mq.ru_idx
            left join (
                select bk_key,count(*) as cnt
                FROM st_bookmark
                where bk_type = 'RESUME'
                group by bk_key
            ) bk
                on bb.ru_idx = bk.bk_key
            left join (
                select ru_idx
                    ,sum(iv_non_cnt) as non_cnt
                    ,sum(iv_abs_cnt) as abs_cnt
                    ,sum(iv_lv_cnt) as lv_cnt
                    ,ROUND(avg((case when iv_eval_yn = 'Y' then ((iv_eval_num1 + iv_eval_num2 + iv_eval_num3)/3) else 0 end)),1) as eval_num
                    ,count(*) as cnt
                FROM st_interview
                group by ru_idx
            ) iv
                on bb.ru_idx = iv.ru_idx
            where bb.ru_idx = '$ru_idx'
       ";

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	# 이력서 조회 : 기본
	function getResumeList($where)
	{
		return $this->db_list("st_resume", $where, "", ["ru_idx" => "desc"]);
	}

	# 이력서 조회 : 기본
	function getResumeListV2($params)
	{
		//컬럼
		$cols = [
			"aa.*,group_concat(ad.ad_addr) as ad_addr"
		];

		//바디
		$body = "
			st_resume aa
                left join st_address ad
                    on aa.mm_idx = ad.mm_idx and aa.ru_idx = ad.ad_key and ad.ad_type = 'RESUME'
		";

		//조건
		$where = [];
		$where[] = 	" and (aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "')  ";

		//정렬
		$order_by = "aa.ru_idx desc";
		$group_by = "group by aa.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}


	# 이력서 등록
	function insertResume($data)
	{
		return $this->db_insert("st_resume", $data);
	}

	# 회원정보 수정
	function updateResume($data, $where)
	{
		# 업데이트 자동 추가
		$data["up_dt"] = get_today();
		return $this->db_update("st_resume", $data, $where);
	}

	# 이력서 삭제
	function deleteResume($where)
	{
		return $this->db_delete("st_resume", $where);
	}

	# 이력서 등록
	function getResumeCount($where)
	{
		return $this->db_count("st_resume", $where);
	}

	# 다이렉트 채팅용 채용공고 자동 생성/재사용 (해당 기업의 전용 더미 공고 — 실제 공고와 무관)
	function insertDirectChatRecruit($mm_idx)
	{
		// 이미 이 기업의 "채팅 전용" 더미 공고가 있으면 재사용 (실제로 등록한 진짜 공고는 절대 재사용 대상이 아님)
		$existing_auto = $this->db_row("st_recruit", array("mm_idx" => $mm_idx, "rc_auto_yn" => "Y"));
		if ($existing_auto) {
			return $existing_auto["rc_idx"];
		}

		$member = $this->getMember(array("mm_idx" => $mm_idx));
		if (!$member) {
			return null;
		}

		$today = get_today();
		$data = array(
			"mm_idx" => $mm_idx,
			"rc_status" => "Y",
			"rc_auto_yn" => "Y",   // 더미 표식 — 모든 목록 화면에서 제외됨
			"rc_show_yn" => "N",   // 공개 목록에도 노출 안 함 (이중 안전장치)
			"rc_name" => trim($member["mm_comp_name"]) != "" ? $member["mm_comp_name"] . " 채용공고" : "채용공고",
			"rc_sdate" => $today,
			"rc_edate" => date("Y-m-d", strtotime("+2 months")),
			"rc_work" => "생산",
			"rc_work_type" => "주간,야간,2교대",
			"rc_price_type" => "일급,주급,월급",
			"up_dt" => $today,
		);

		if ($this->db->insert("st_recruit", $data)) {
			$rc_idx = $this->db->insert_id();

			if ($rc_idx && !empty($member["mm_addr"])) {
				$this->insertAddress(array(
					"ad_type" => "RECRUIT",
					"ad_key" => $rc_idx,
					"ad_addr" => $member["mm_addr"],
					"ad_zipcode" => isset($member["mm_zipcode"]) ? $member["mm_zipcode"] : "",
					"ad_doro" => isset($member["mm_doro"]) ? $member["mm_doro"] : "",
					"ad_jibun" => isset($member["mm_jibun"]) ? $member["mm_jibun"] : "",
				));
			}

			return $rc_idx;
		} else {
			$error = $this->db->error();
			$error_msg = isset($error["message"]) ? $error["message"] : "Unknown error";
			return null;
		}
	}

	# 다이렉트 채팅용 이력서 자동 생성/재사용 (해당 개인의 전용 더미 이력서 — 실제 이력서와 무관)
	function insertDirectChatResume($mm_idx)
	{
		// 이미 이 개인의 "채팅 전용" 더미 이력서가 있으면 재사용 (실제로 작성한 진짜 이력서는 절대 재사용 대상이 아님)
		$existing_auto = $this->db_row("st_resume", array("mm_idx" => $mm_idx, "ru_auto_yn" => "Y"));
		if ($existing_auto) {
			return $existing_auto["ru_idx"];
		}

		$member = $this->getMember(array("mm_idx" => $mm_idx));
		if (!$member) {
			return null;
		}

		$data = array(
			"mm_idx" => $mm_idx,
			"ru_name" => $member["mm_name"],
			"ru_work" => "생산",
			"ru_work_type" => "주간,야간,2교대",
			"ru_status" => "Y",
			"ru_show_yn" => "N",    // 공개 인재목록에 노출 안 함
			"ru_auto_yn" => "Y",    // 더미 표식 — "내 이력서" 관리화면에서 제외됨
			"reg_dt" => get_today(),
			"up_dt" => get_today()
		);

		if ($this->db->insert("st_resume", $data)) {
			$ru_idx = $this->db->insert_id();
			return $ru_idx;
		} else {
			$error = $this->db->error();
			$error_msg = isset($error["message"]) ? $error["message"] : "Unknown error";
			return null;
		}
	}

	# 다이렉트 채팅방에 넣을 개인회원의 실제 이력서 선택 (대표이력서 우선 → 최신 실제 이력서, 없으면 null)
	function getChatResumeIdx($mem_mm_idx)
	{
		if (empty($mem_mm_idx)) {
			return null;
		}
		$row = $this->db->query(
			"SELECT ru_idx FROM st_resume
			 WHERE mm_idx = ? AND ru_auto_yn != 'Y' AND (ru_status IS NULL OR ru_status != 'N')
			 ORDER BY (ru_show_yn = 'Y') DESC, ru_idx DESC
			 LIMIT 1",
			array($mem_mm_idx)
		)->row_array();
		return $row ? $row["ru_idx"] : null;
	}

	# 다이렉트 채팅방에 넣을 기업회원의 진행중인 실제 채용공고 선택 (가장 최근 공고, 없으면 null)
	function getChatRecruitIdx($comp_mm_idx)
	{
		if (empty($comp_mm_idx)) {
			return null;
		}
		$row = $this->db->query(
			"SELECT rc_idx FROM st_recruit
			 WHERE mm_idx = ? AND rc_status = 'Y' AND rc_auto_yn != 'Y' AND rc_edate >= CURDATE()
			 ORDER BY rc_idx DESC
			 LIMIT 1",
			array($comp_mm_idx)
		)->row_array();
		return $row ? $row["rc_idx"] : null;
	}

	# 같은 기업(공고 주인) ↔ 개인(이력서 주인) 쌍의 채팅방 조회
	# 정식/다이렉트 구분 없이 쌍만으로 찾는다 (같은 쌍은 방 하나 원칙).
	# 레거시로 방이 여러 개면 정식(RECRUIT/RESUME) 요청이 있는 방 우선, 그 다음 최신 방.
	function findRecruitRoomByPair($comp_mm_idx, $mem_mm_idx)
	{
		if (empty($comp_mm_idx) || empty($mem_mm_idx) || $comp_mm_idx == $mem_mm_idx) {
			return null;
		}
		$row = $this->db->query(
			"SELECT room.rm_idx
			 FROM st_room room
			 INNER JOIN st_recruit rc ON room.rc_idx = rc.rc_idx
			 INNER JOIN st_resume  ru ON room.ru_idx = ru.ru_idx
			 WHERE room.rm_type = 'recruit'
			 AND rc.mm_idx = ? AND ru.mm_idx = ?
			 ORDER BY EXISTS (
				SELECT 1 FROM st_member_request f
				WHERE f.rm_idx = room.rm_idx AND f.mq_type IN ('RECRUIT', 'RESUME')
			 ) DESC, room.rm_idx DESC
			 LIMIT 1",
			array($comp_mm_idx, $mem_mm_idx)
		)->row_array();
		return ($row && !empty($row["rm_idx"])) ? $row["rm_idx"] : null;
	}

	# 채팅방 요청 기록 상태 (방 분류 기준)
	# formal: 정식(RECRUIT/RESUME) 요청이 1건 이상 있으면 true → 정식 방, 없으면 순수 채팅방
	# any: 요청 기록(레거시 _DIRECT 포함)이 1건이라도 있으면 true
	function getRoomRequestState($rm_idx)
	{
		$state = array("formal" => false, "any" => false);
		if (empty($rm_idx)) {
			return $state;
		}
		$row = $this->db->query(
			"SELECT SUM(CASE WHEN mq_type IN ('RECRUIT', 'RESUME') THEN 1 ELSE 0 END) AS formal_cnt,
			        COUNT(*) AS any_cnt
			 FROM st_member_request WHERE rm_idx = ?",
			array($rm_idx)
		)->row_array();
		if ($row) {
			$state["formal"] = ((int)$row["formal_cnt"] > 0);
			$state["any"] = ((int)$row["any_cnt"] > 0);
		}
		return $state;
	}

	# 같은 정식 요청(같은 공고 + 같은 이력서)을 이미 보냈는지 확인하고, 이동할 채팅방 번호를 반환
	# - 지원취소(mq_status='cancel')된 요청은 제외
	# - 요청 기록에 rm_idx가 없으면(레거시) 쌍 기준 방으로 대체
	# - 내가 그 방을 나간 상태면 null (재전송으로 취급해 정상 흐름을 탄다)
	function findActiveRequestRoom($mq_type, $mm_idx, $rc_idx, $ru_idx, $user_id, $user_phone = null)
	{
		if (empty($mq_type) || empty($mm_idx) || empty($rc_idx) || empty($ru_idx)) {
			return null;
		}
		$row = $this->db->query(
			"SELECT rm_idx FROM st_member_request
			 WHERE mq_type = ? AND mm_idx = ? AND rc_idx = ? AND ru_idx = ?
			 AND (mq_status IS NULL OR mq_status != 'cancel')
			 ORDER BY mq_idx DESC
			 LIMIT 1",
			array($mq_type, $mm_idx, $rc_idx, $ru_idx)
		)->row_array();
		if (!$row) {
			return null;
		}

		$rm_idx = !empty($row["rm_idx"]) ? $row["rm_idx"] : null;
		if (!$rm_idx) {
			$owner = $this->db->query(
				"SELECT rc.mm_idx AS comp_mm_idx, ru.mm_idx AS mem_mm_idx
				 FROM st_recruit rc, st_resume ru
				 WHERE rc.rc_idx = ? AND ru.ru_idx = ?",
				array($rc_idx, $ru_idx)
			)->row_array();
			if ($owner) {
				$rm_idx = $this->findRecruitRoomByPair($owner["comp_mm_idx"], $owner["mem_mm_idx"]);
			}
		}
		if (!$rm_idx) {
			return null;
		}

		$exited = get_exited_rm_idxs(array($rm_idx), $user_id, $user_phone);
		if (!empty($exited)) {
			return null;
		}
		return $rm_idx;
	}

	# 이력서 경력 등록
	function insertResumeCareer($data)
	{
		return $this->db_insert("st_resume_career", $data);
	}

	# 이력서 자격증 등록
	function insertResumeCerti($data)
	{
		return $this->db_insert("st_resume_certi", $data);
	}

	# 이력서 경력 등록
	function deleteResumeCareer($where)
	{
		return $this->db_delete("st_resume_career", $where);
	}

	# 이력서 자격증 등록
	function deleteResumeCerti($where)
	{
		return $this->db_delete("st_resume_certi", $where);
	}

	# 이력서 경력 조회
	function getResumeCareerList($where)
	{
		return $this->db_list("st_resume_career", $where, "*", ["ru_idx" => "asc"]);
	}

	# 이력서 자격증 조회
	function getResumeCertiList($where)
	{
		return $this->db_list("st_resume_certi", $where, "*", ["rc_idx" => "asc"]);
	}

	######################################
	# END 이력서 처리
	######################################

	######################################
	# 공고 처리 START
	######################################

	# 공고 조회 : 기본
	function getRecruit($where)
	{
		return $this->db_row("st_recruit", $where, "*", ["rc_idx" => "desc"]);
	}

	# 공고 조회 : 자세히
	function getRecruitDetail($rc_idx, $mm_idx)
	{
		$sql = "
			select bb.*
				,cc.ad_addr
				,cc.ad_doro
				,cc.ad_detail
				,(case
					when rc_nation = '내국인,외국인' then '내국인,외국인'
					when rc_nation = '내국인' then '내국인'
					when rc_nation = '외국인' then '외국인'
					else '무관' end) as rc_nation_nm
				,(case
					when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
					when rc_sage <> '' then concat(rc_sage, '세 이상')
					when rc_eage <> '' then concat(rc_eage, '세 이하')
					else '나이무관' end) as rc_age_nm
				,(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn
				,aa.mm_phone
				,aa.mm_comp_name
        ,ifnull(bk.cnt,0) as fav_cnt
        ,aa.mm_id,
        (SELECT pm_idx FROM st_promotion WHERE rc_idx = bb.rc_idx AND pm_code = 'C001' AND pm_status = 'Y' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx
			FROM st_member aa
			inner join st_recruit bb
				on aa.mm_idx = bb.mm_idx
			left join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
			left join st_bookmark dd
				on bb.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '$mm_idx'
            left join (
                select bk_key,count(*) as cnt
                FROM st_bookmark
                where bk_type = 'RECRUIT'
                group by bk_key
            ) bk
                on bb.rc_idx = bk.bk_key
			where bb.rc_idx = '$rc_idx'
		";

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	# 공고 조회 : 기본
	function getRecruitRecent($mm_idx)
	{
		$sql = "
			select *
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and rc_show_yn = 'Y'
			order by aa.rc_idx desc
			limit 0,1
		";
		//and rc_status = 'Y'

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	# 공고 조회 : count
	function getRecruitCountForTab($mm_idx)
	{
		$sql = "
			select count(*) as allcnt,
            sum(case when aa.rc_sdate <= now() and aa.rc_edate >= now() then 1 else 0 end) ingcnt,
            sum(case when aa.rc_sdate > now()                           then 1 else 0 end) waitcnt,
            sum(case when aa.rc_edate < now()                           then 1 else 0 end) closecnt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 기본 - 게재중인
	function getRecruitActaiveList($mm_idx)
	{
		//error_log("\ngetRecruitActaiveList\n", "3", "/testlog/php_log");
		/*
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         CONCAT('(', GROUP_CONCAT(sr_room.rm_idx ORDER BY sr_room.rm_idx ASC SEPARATOR ', '), ')') AS rm_idx_list,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt
			FROM st_recruit aa
      LEFT JOIN st_room AS sr_room ON aa.rc_idx = sr_room.rc_idx
      LEFT JOIN st_resume AS ru ON ru.ru_idx = sr_room.ru_idx / * 공고밑에 지원한 지원자 목록 없으면 채팅방 추가 않하도록 * /
      LEFT JOIN st_member AS mem ON mem.mm_idx = ru.mm_idx    / * 공고밑에 지원한 지원자 목록 없으면 채팅방 추가 않하도록 * /
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now()
      AND (mem.stop_dt IS NULL OR mem.stop_dt = '0000-00-00 00:00:00') / * 공고밑에 지원한 지원자 목록 없으면 채팅방 추가 않하도록 * /
      GROUP BY aa.rc_idx
			order by max_up_dt desc, aa.rc_idx desc
		";
    */

		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_idx FROM st_promotion WHERE rc_idx = aa.rc_idx AND pm_code = 'C001' AND pm_status = 'Y'
          AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT mq.rm_idx ORDER BY mq.rm_idx ASC SEPARATOR ', '), ')')
          FROM st_member_request mq
          INNER JOIN st_resume ru ON mq.ru_idx = ru.ru_idx
          INNER JOIN st_member mem ON ru.mm_idx = mem.mm_idx
          WHERE mq.mq_type = 'RECRUIT' AND mq.rc_idx = aa.rc_idx
          AND mem.mm_status = 'Y'
         ), '()') AS rm_idx_list,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt,
         (SELECT ad_addr FROM st_address WHERE ad_type = 'RECRUIT' AND ad_key = aa.rc_idx LIMIT 1) as ad_addr
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
      AND EXISTS (
          SELECT 1 FROM st_member_request mq
          INNER JOIN st_resume ru ON mq.ru_idx = ru.ru_idx
          INNER JOIN st_member mem ON ru.mm_idx = mem.mm_idx
          WHERE mq.rc_idx = aa.rc_idx
          AND mq.mq_type = 'RECRUIT'
          AND mem.mm_status = 'Y'
      )
      GROUP BY aa.rc_idx
			order by max_up_dt desc, aa.rc_idx desc
		";

		//error_log("\n".$sql."\n", "3", "/testlog/php_log");
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 기본 - 게재중인 (모든 공고, 제안 조건 없음)
	# 진행중인 채용공고만 표시하도록 rc_edate 조건 추가
	function getRecruitActaiveListAll($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT rm_idx ORDER BY rm_idx ASC SEPARATOR ', '), ')')
          FROM st_room
          WHERE rc_idx = aa.rc_idx
          AND (ru_idx IN (SELECT ru_idx FROM st_member_request WHERE mq_type = 'RECRUIT' AND rc_idx = aa.rc_idx))
         ), '()') AS rm_idx_list,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now()
      GROUP BY aa.rc_idx
			order by max_up_dt desc, aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 채팅-기업-제안받은 리스트
	function getRecruitActaiveList2($mm_idx)
	{
		// 제안을 보낸 인재가 있는 공고만 조회
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RESUME'
         AND   rc_idx = aa.rc_idx
         AND   mm_idx = '$mm_idx') reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
				 WHERE vw.mv_type='resume'
				 AND   req.mq_type = 'RESUME'
				 AND   req.rc_idx = aa.rc_idx
				 AND   req.mm_idx = '$mm_idx'
				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_idx FROM st_promotion WHERE rc_idx = aa.rc_idx AND pm_code = 'C001' AND pm_status = 'Y'
          AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT mq.rm_idx ORDER BY mq.rm_idx ASC SEPARATOR ', '), ')')
          FROM st_member_request mq
          INNER JOIN st_resume ru ON mq.ru_idx = ru.ru_idx
          INNER JOIN st_member mem ON ru.mm_idx = mem.mm_idx
          WHERE mq.mq_type = 'RESUME' AND mq.rc_idx = aa.rc_idx AND mq.mm_idx = '$mm_idx'
          AND mem.mm_status = 'Y'
         ), '()') AS rm_idx_list,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt,
         (SELECT ad_addr FROM st_address WHERE ad_type = 'RECRUIT' AND ad_key = aa.rc_idx LIMIT 1) as ad_addr
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
			and aa.rc_auto_yn != 'Y'
			AND EXISTS (
			    SELECT 1 FROM st_member_request mq
			    INNER JOIN st_resume ru ON mq.ru_idx = ru.ru_idx
			    INNER JOIN st_member mem ON ru.mm_idx = mem.mm_idx
			    WHERE mq.rc_idx = aa.rc_idx
			    AND mq.mm_idx = '$mm_idx'
			    AND mq.mq_type = 'RESUME'
			    AND mem.mm_status = 'Y'
			)
			order by max_up_dt desc, aa.rc_idx desc
		";
		// 24.11.25 제안보낸인재에서 같은 공고-이력서의 채팅방이 채용공고에 있으면 그 채팅방 번호를 조회해서
		// 채용공고리스트와 같은 채팅방이면 카운트 함께 보여주도록 수정
		// 변경전 쿼리:   CONCAT('(', GROUP_CONCAT(sr_room.rm_idx ORDER BY sr_room.rm_idx ASC SEPARATOR ', '), ')') AS rm_idx_list,
		// 2024.12.XX 제안을 보낸 인재가 있는 공고만 조회하도록 INNER JOIN st_member_request 추가
		// 2024.12.XX GROUP_CONCAT 서브쿼리 단순화 및 LEFT JOIN 조건 수정 (sr_room.ru_idx = mq.ru_idx)
		// 2024.12.XX INNER JOIN을 EXISTS로 변경하여 중복 행 방지 및 GROUP BY 문제 해결

		// 디버깅을 위한 SQL 로그 활성화
		//error_log("\n[getRecruitActaiveList2] SQL Query:\n" . $sql . "\n", 3, "/testlog/php_log");

		$query = $this->db->query($sql);

		// SQL 에러 확인 추가 (MY_Model.php 패턴과 동일하게)
		$error = $this->db->error();
		if ($error["code"] != 0) {
			//error_log("[getRecruitActaiveList2] SQL Error: " . $error["message"]);
			//error_log("[getRecruitActaiveList2] SQL Query: " . $sql);
			return [];
		}

		$result = $query->result_array();
		

		return $result;
	}

	# 지원한 공고목록
	function getRecruitActaiveListMem($mm_idx)
	{

		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RESUME'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RESUME'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         req.rm_idx,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt
			FROM st_member_request req
			INNER JOIN st_room room ON room.rm_idx = req.rm_idx /* chat_view.php와 동일하게 방에 저장된 rc_idx를 우선 사용 (방 재사용 시 최신 공고명 표시) */
			INNER JOIN st_recruit aa ON aa.rc_idx = room.rc_idx
			INNER JOIN st_member comp ON aa.mm_idx = comp.mm_idx /* membermodel.getRecruitActaiveListMem queryXlog */
			where req.mq_type = 'RECRUIT' AND req.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
			and comp.mm_status = 'Y'
			order by max_up_dt desc, aa.rc_idx desc
		";

		//error_log("\n".$sql."\n", "3", "/testlog/php_log");
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 기본 - 게재중인
	function getRecruitActaiveListDist($mm_idx, $lat, $lng)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now()
			order by aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 대기중인
	function getRecruitWaitList($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_sdate > now()
			order by aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 마감된
	function getRecruitCloseList($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_edate < now()
			order by aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고조회 : 기본
	function getRecruitAllList($mm_idx)
	{

		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
				 AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
			order by aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고조회 : 기본
	function getRecruitList($where)
	{
		return $this->db_list("st_recruit", $where, "", ["rc_idx" => "desc"]);
	}

	# 공고조회 : 기본
	function getAddrRecruitList($mm_idx)
	{
		$sql = "
			select aa.*
                ,trim(bb.ad_addr) as ad_addr
			FROM st_recruit aa
                inner join st_address bb
                    on aa.rc_idx = bb.ad_key and bb.ad_type = 'RECRUIT'
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
			order by aa.rc_idx desc
		";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고조회 : 기본
	function getRecruitListV2($params)
	{
		//컬럼
		$cols = [
			"aa.*,group_concat(ad.ad_addr) as ad_addr"
		];

		//바디
		$body = "
			st_recruit aa
                left join st_address ad
                    on aa.mm_idx = ad.mm_idx and aa.rc_idx = ad.ad_key and ad.ad_type = 'RECRUIT'
		";

		//조건
		$where = [];
		$where[] = 	" and aa.rc_status = 'Y'  ";
		$where[] = 	" and (aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "')  ";

		//정렬
		$order_by = "aa.rc_idx desc";
		$group_by = "group by aa.rc_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 공고 등록
	function insertRecruit($data)
	{
		return $this->db_insert("st_recruit", $data);
	}

	# 공고 수정
	function updateRecruit($data, $where)
	{
		# 업데이트 자동 추가
		$data["up_dt"] = get_today();
		return $this->db_update("st_recruit", $data, $where);
	}

	# 공고 삭제
	function deleteRecruit($where)
	{
		return $this->db_delete("st_recruit", $where);
	}

	# 공고 카운터
	function getRecruitCount($where)
	{
		return $this->db_count("st_recruit", $where);
	}

	function getRecruitTodayCnt($mm_idx)
	{
		$sql = "
			select count(*) as cnt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and date_format(reg_dt,'%Y%m%d') = date_format(now(),'%Y%m%d')
		";

		$query = $this->db->query($sql);
		$info = $query->row_array();
		if ($info == null) {
			return 0;
		} else {
			return $info["cnt"];
		}
	}

	# 지원하기 중복이면 삭제후 재등록한다.
	function setMemberRequest($data, $where)
	{
		$is_data = $this->db_is_data("st_member_request", $where);
		if ($is_data) {
			$this->db_delete("st_member_request", $where);
		}

		return $this->db_insert("st_member_request", $data);
	}

	# 지원 삭제
	function deleteMemberRequest($where)
	{
		return $this->db_delete("st_member_request", $where);
	}

	# 지원하기 중복이면 삭제후 재등록한다.
	function checkMemberRequestPoint($data)
	{
		$mq_type = $data["mq_type"];
		$mm_idx = $data["mm_idx"];
		$mq_idxs = $data["mq_idxs"];

		if ($mq_type == "RESUME") {
			$sql = "
				SELECT 1 as idx,count(*) as cnt FROM `st_member_request`
				WHERE mm_idx = '$mm_idx'
				and mq_type = 'RESUME'
				and reg_dt <= concat(date_format(date_sub(now(),INTERVAL 1 DAY),'%Y-%m-%d'),' 23:59:59')
				UNION ALL
				SELECT 2 as idx,count(*) as cnt FROM `st_member_request`
				WHERE mm_idx = '$mm_idx'
				and mq_type = 'RESUME'
				and reg_dt > concat(date_format(date_sub(now(),INTERVAL 1 DAY),'%Y-%m-%d'),' 23:59:59')
			";

			$query = $this->db->query($sql);
			$list_info = $query->result_array();

			$prev_cnt = $list_info[0]["cnt"];
			$today_cnt = $list_info[1]["cnt"];
			$total_cnt = $prev_cnt + $today_cnt;

			# 전체 갯수가 10보다 작으면 계속 무료
			# 전체 갯수가 10보다 크면서 오늘 갯수가 2보다 작으면 무료
			# 전체 갯수가 10보다 크면서 오늘갯수가 2보다 크면 유료
			# 처리하려는 갯수 오늘 남은갯수 : 2 - 오늘 추가한갯수 최소치 0

			if ($total_cnt + count($mq_idxs) <= 10) {
				return 0;
			} else if ($total_cnt + count($mq_idxs) > 10 && ($today_cnt + count($mq_idxs)) <= 2) {
				return 0;
			} else {
				# 요청한 갯수에서 남은 갯수 뺀후 과금
				return count($mq_idxs) - (2 - ($today_cnt > 2 ? 2 : $today_cnt));
			}
		} else {
			$sql = "
				SELECT 1 as idx,count(*) as cnt FROM `st_member_request`
				WHERE mm_idx = '$mm_idx'
				and mq_type = 'RECRUIT'
				and reg_dt <= concat(date_format(date_sub(now(),INTERVAL 1 DAY),'%Y-%m-%d'),' 23:59:59')
				UNION ALL
				SELECT 2 as idx,count(*) as cnt FROM `st_member_request`
				WHERE mm_idx = '$mm_idx'
				and mq_type = 'RECRUIT'
				and reg_dt > concat(date_format(date_sub(now(),INTERVAL 1 DAY),'%Y-%m-%d'),' 23:59:59')
			";

			$query = $this->db->query($sql);
			$list_info = $query->result_array();

			$prev_cnt = $list_info[0]["cnt"];
			$today_cnt = $list_info[1]["cnt"];
			$total_cnt = $prev_cnt + $today_cnt;

			# 전체 갯수가 10보다 작으면 계속 무료
			# 전체 갯수가 10보다 크면서 오늘 갯수가 2보다 작으면 무료
			# 전체 갯수가 10보다 크면서 오늘갯수가 2보다 크면 유료
			# 처리하려는 갯수 오늘 남은갯수 : 2 - 오늘 추가한갯수 최소치 0

			if ($total_cnt + count($mq_idxs) <= 10) {
				return 0;
			} else if ($total_cnt + count($mq_idxs) > 10 && ($today_cnt + count($mq_idxs)) <= 2) {
				return 0;
			} else {
				# 요청한 갯수에서 남은 갯수 뺀후 과금
				return count($mq_idxs) - (2 - ($today_cnt > 2 ? 2 : 0));
			}
		}
	}


	# 지원하기 중복이면 삭제후 재등록한다.
	function checkMemberRequest($data)
	{
		$mq_type = $data["mq_type"];
		$mm_idx = $data["mm_idx"];
		$rc_idx = $data["rc_idx"];
		$ru_idx = $data["ru_idx"];

		if ($mq_type == "RECRUIT") {
			$sql = "
				select ifnull(bb.rc_name,'') as subject
				FROM st_member_request aa
					inner join st_recruit bb
						on aa.mq_type = 'RECRUIT' and aa.rc_idx = bb.rc_idx
				where aa.mm_idx = '$mm_idx'
				and aa.rc_idx = '$rc_idx'
			";
		} else {
			$sql = "
				select ifnull(bb.ru_name,'') as subject
				FROM st_member_request aa /* queryxlog */
					inner join st_resume bb
						on aa.mq_type = 'RESUME' and aa.ru_idx = bb.ru_idx
				where aa.mm_idx = '$mm_idx'
				and aa.ru_idx = '$ru_idx'
        and aa.rc_idx = '$rc_idx'
			";
			//and aa.rc_idx = '$rc_idx' 를 2024.07.15에 추가함
			//제안 보낼때, 최근 채용공고 자동 세팅되도록 해서 지정되게함
		}

		$query = $this->db->query($sql);
		$info = $query->row_array();
		return $info;
	}

	######################################
	# 공고 이력서 처리
	######################################

	function getRecruitSearchParamsList($page_size = 20)
	{
		$data = [];

		$mm_idx = $this->user->getUserIdx();
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
		$save_yn = input_post("save_yn", "N");

		$addr_info = $this->user->getAddrInfo();

		//return $addr_info;//test

		# 저장인경우 파라미터 그대로 사용하고 아니면 기존 검색 정보 가져와 설정한다.
		if ($save_yn == "N") {
			$site_log = $this->site_model->getSiteLog([
				"session_id" => session_id(),
				"sl_type" => "SEARCH_RECRUIT",
				"sl_step" => "검색"
			]);

			if ($site_log == null) {
				if ($this->user->isLogin()) {
					$site_log = $this->site_model->getSiteLog([
						"mm_idx" => $mm_idx,
						"sl_type" => "SEARCH_RECRUIT",
						"sl_step" => "검색"
					]);
				}
			}

			if ($site_log != null) {
				$json_info = json_decode($site_log["sl_txt"], true);
				$text = $json_info["text"];
				$skm = $json_info["skm"];
				$ekm = $json_info["ekm"];
				$category = $json_info["category"];
				$work_type = $json_info["work_type"];
				$week = $json_info["week"];
				$price_type = $json_info["price_type"];
				$sex = $json_info["sex"];
				$sage = $json_info["sage"];
				$eage = $json_info["eage"];
			}
		}

		$params = [
			"pagesize" => $page_size,
			"page" => $page,
			"gps_x" => $addr_info["ad_gps_x"],
			"gps_y" => $addr_info["ad_gps_y"],
			"text" => $text,
			"mm_idx" => $mm_idx,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"ad_addr" => $addr_info["ad_addr"]
		];

		$result = $this->getRecruitSearchList($params);
		$list = $result["list"];

		foreach ($list as $key => $row) {
			$list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$list[$key]["rt_price"] = getRecuitPrice($row);
		}

		# 검색어 저장
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
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);

			$this->site_model->deleteSiteLog(
				[
					"mm_idx" => $mm_idx,
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		} else {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		}

		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"mm_idx" => $mm_idx,
				"sl_type" => "SEARCH_RECRUIT",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list" => $list,
			"result_count" => $result["count"],
		];

		return $data;
	}

	function getRecruitSearchParamsListMap($param, $page_size = 20)
	{
		$data = [];

		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$text = $param["text"];
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = $param["category"];
		$work_type = $param["work_type"];
		$week = $param["week"];
		$price_type = $param["price_type"];
		$sex = $param["sex"];
		$sage = $param["sage"];
		$eage = $param["eage"];
		$save_yn = input_post("save_yn", "N");
		$comp_idx = $param["comp_idx"];

		$addr_info = $this->user->getAddrInfo();

		# 저장인경우 파라미터 그대로 사용하고 아니면 기존 검색 정보 가져와 설정한다.
		if ($save_yn == "N") {
			$site_log = $this->site_model->getSiteLog([
				"session_id" => session_id(),
				"sl_type" => "SEARCH_RECRUIT",
				"sl_step" => "검색"
			]);

			if ($site_log == null) {
				if ($this->user->isLogin()) {
					$site_log = $this->site_model->getSiteLog([
						"mm_idx" => $mm_idx,
						"sl_type" => "SEARCH_RECRUIT",
						"sl_step" => "검색"
					]);
				}
			}
			/*
            if($site_log!=null){
                $json_info = json_decode($site_log["sl_txt"],true);
                $text = $json_info["text"];
                $skm = $json_info["skm"];
                $ekm = $json_info["ekm"];
                $category = $json_info["category"];
                $work_type = $json_info["work_type"];
                $week = $json_info["week"];
                $price_type = $json_info["price_type"];
                $sex = $json_info["sex"];
                $sage = $json_info["sage"];
                $eage = $json_info["eage"];
            }
            */
		}

		$params = [
			"pagesize" => $page_size,
			"page" => $page,
			"gps_x" => $addr_info["ad_gps_x"],
			"gps_y" => $addr_info["ad_gps_y"],
			"text" => $text,
			"mm_idx" => $mm_idx,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"comp_idx" => $comp_idx,
		];

		$result = $this->getRecruitSearchList($params);
		$list = $result["list"];

		foreach ($list as $key => $row) {
			$list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$list[$key]["rt_price"] = getRecuitPrice($row);
		}

		# 검색어 저장
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

		/* //24.10.21 검색조건 저장을 삭제하는 부분이 있어서
           //오류 발생하므로(검색조건이 먹지 않음)
           //삭제하는 부분 주석처리
        if($this->user->isLogin()){
            $this->site_model->deleteSiteLog(
                [
                     "session_id"=>session_id()
                    ,"sl_type"=>"SEARCH_RECRUIT"
                    ,"sl_step"=>"검색"
                ]
            );

            $this->site_model->deleteSiteLog(
                [
                     "mm_idx"=>$mm_idx
                    ,"sl_type"=>"SEARCH_RECRUIT"
                    ,"sl_step"=>"검색"
                ]
            );
        }else{
            $this->site_model->deleteSiteLog(
                [
                     "session_id"=>session_id()
                    ,"sl_type"=>"SEARCH_RECRUIT"
                    ,"sl_step"=>"검색"
                ]
            );
        }

        $this->site_model->setSiteLog(
            [
                "session_id"=>session_id()
                ,"mm_idx"=>$mm_idx
                ,"sl_type"=>"SEARCH_RECRUIT"
                ,"sl_step"=>"검색"
                ,"sl_txt"=>json_encode($search_txt,JSON_UNESCAPED_UNICODE)
            ]
        );
        */

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list" => $list,
			"result_count" => $result["count"],
		];

		return $data;
	}

	# 업체 리스트 조회 : 메인화면용
	function getRecruitSearchList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];
		if ($gps_x == '') $gps_x = "''";
		if ($gps_y == '') $gps_y = "''";

		# 지역검색 제외
		$is_addr = true;
		if ($gps_x == "127.028361548" && $gps_y == "37.496486063" || ($gps_x == "") || ($gps_y == "")) {
			$is_addr = false;
		}

		//컬럼
		$cols = [
			"aa.*",
			"aa.ad_addr",
			"aa.ad_gps_x",
			"aa.ad_gps_y",
			"gps_km as distance",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p.pm_idx",
			"p.reg_dt as pm_reg_dt"
		];

		# 거리 처리
		$add_addr = "";
		//if($params["skm"] > 0){
		//	$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
		//				-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) >= " . $params["skm"];
		//}

		if ($params["ekm"] > 0 && $is_addr == true) {
			$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
						-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) <= " . $params["ekm"];
		}

		//바디
		$body = "
            (
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
                    ,gps_km
                    ,cc.ad_addr
                    ,cc.ad_gps_x
                    ,cc.ad_gps_y
                    ,aa.mm_comp_name
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join (
                    SELECT *
                        ,ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
                            -radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1)
                        AS gps_km
                    FROM st_address
                    where 1=1
                    $add_addr
                ) cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join st_promotion po
                    on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
                group by bb.rc_idx
            ) aa
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      LEFT JOIN st_promotion p
        ON aa.rc_idx = p.rc_idx AND p.pm_code = 'C001' AND NOW() BETWEEN p.pm_sdate AND p.pm_edate and p.pm_status = 'Y'
		";
		if ($search_addr != "") {
			$body .= " AND p.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion pj ON aa.rc_idx = pj.rc_idx AND pj.pm_code in('C002','C003') AND NOW() BETWEEN pj.pm_sdate AND pj.pm_edate  ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";

		# 검색어
		if ($params["text"] != "") {
			//$where[] = 	" and (aa.rc_name like '%".$this->inject($params["text"])."%')";
			if ($params["text"] == "일" || $params["text"] == "일요일") {
				$where[] = 	" and rc_week like '%일%' ";
			} else if ($params["text"] == "월" || $params["text"] == "월요일") {
				$where[] = 	" and rc_week like '%월%' ";
			} else if ($params["text"] == "화" || $params["text"] == "화요일") {
				$where[] = 	" and rc_week like '%화%' ";
			} else if ($params["text"] == "수" || $params["text"] == "수요일") {
				$where[] = 	" and rc_week like '%수%' ";
			} else if ($params["text"] == "목" || $params["text"] == "목요일") {
				$where[] = 	" and rc_week like '%목%' ";
			} else if ($params["text"] == "금" || $params["text"] == "금요일") {
				$where[] = 	" and rc_week like '%금%' ";
			} else if ($params["text"] == "토" || $params["text"] == "토요일") {
				$where[] = 	" and rc_week like '%토%' ";
			} else if ($params["text"] == "남" || $params["text"] == "남자") {
				$where[] =	 " and aa.rc_sex like '%남%' ";
			} else if ($params["text"] == "여" || $params["text"] == "여자") {
				$where[] =	 " and aa.rc_sex like '%여%' ";
			} else {
				$where[] = 	" and (
            aa.rc_name like '%" . $this->inject($params["text"]) . "%' or
            aa.rc_nation like '%" . $this->inject($params["text"]) . "%' or
            aa.ad_addr like '%" . $this->inject($params["text"]) . "%' or
            rc_work like '%" . $this->inject($params["text"]) . "%' or
            rc_price_type like '%" . $this->inject($params["text"]) . "%' or
            rc_work_type like '%" . $this->inject($params["text"]) . "%' or
            rc_hash like '%" . $this->inject($params["text"]) . "%'
          )";
			}
		}

		# 나이
		if ($params["sage"] != "") {
			$where[] = 	" and (aa.rc_sage != 0 and aa.rc_sage >= '" . $this->inject($params["sage"]) . "')";
		}

		# 나이
		if ($params["eage"] != "") {
			$where[] = 	" and (aa.rc_eage != 0 and aa.rc_eage <= '" . $this->inject($params["eage"]) . "')";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		//comp_idx
		if ($params["comp_idx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["comp_idx"] . "' ";
		}

		//정렬
		//$order_by = "p.pm_idx desc, ";
		//$order_by  = " p.pm_idx desc ";
		$order_by  = " case when (case when ifnull(p.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc ";
		if ($params["ad_addr"] == "지역 선택 없음") {
			//$order_by .= " ,                   IFNULL(pj.up_dt, '2000-01-01 00:00:00') desc ";
			$order_by .= " ,GREATEST(aa.up_dt, IFNULL(pj.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by .= " ,distance asc ";
		}
		$group_by = "group by aa.rc_idx /* query_log */ ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 업체 리스트 조회 : 웹용
	function getRecruitWebSearchList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"aa.ad_addr",
			"aa.ad_gps_x",
			"aa.ad_gps_y",
			"aa.distance",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		# 거리 처리
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $params["addr1"];
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $params["addr2"];
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $params["addr3"];
		}

		//바디
		$body = "
            (
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
                    ,cc.ad_addr
                    ,cc.ad_gps_x
                    ,cc.ad_gps_y
                    ,aa.mm_comp_num
                    ,aa.mm_comp_name
                    ,'99.9' distance
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join st_address cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join st_promotion po
                    on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
                group by bb.rc_idx
            ) aa
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		  LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.rc_idx AND p0.pm_code = 'C001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p0.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";


		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
					aa.rc_name like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_nation like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_sex like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_price_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_hash like '%" . $this->inject($params["text"]) . "%'
				)";
		}

		# 나이
		if ($params["sage"] != "") {
			$where[] = 	" and (aa.rc_sage != 0 and aa.rc_sage >= '" . $this->inject($params["sage"]) . "')";
		}

		# 나이
		if ($params["eage"] != "") {
			$where[] = 	" and (aa.rc_eage != 0 and aa.rc_eage <= '" . $this->inject($params["eage"]) . "')";
		}

		if ($search_addr != "") { // && $params["multiAddr1"]=="" && $params["multiAddr2"]=="" && $params["multiAddr3"]==""
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' OR p0.pm_addr LIKE '%" . $search_addr . "%' OR p1.pm_addr LIKE '%" . $search_addr . "%' OR p2.pm_addr LIKE '%" . $search_addr . "%') ";
		}

		//if($params["multiAddr1"]!=""){
		//  $multiAddr1_where = $this->getMultiAnd($params["multiAddr1"],"aa.ad_addr");
		//	$where[] = $multiAddr1_where;
		//}
		//if($params["multiAddr2"]!=""){
		//  $multiAddr2_where = $this->getMultiAnd($params["multiAddr2"],"aa.ad_addr");
		//	$where[] = $multiAddr2_where;
		//}
		//if($params["multiAddr3"]!=""){
		//  $multiAddr3_where = $this->getMultiAnd($params["multiAddr3"],"aa.ad_addr");
		//	$where[] = $multiAddr3_where;
		//}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		# company 지정
		//error_log("membermodel:".$params["compidx"], "3", "/testlog/php_log");

		if ($params["compidx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["compidx"] . "' ";
		}

		//정렬
		if ($params["order_by"] == "recent") {
			//$order_by  = " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			//$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			//$order_by .= " reg_dt desc ";
			//$order_by  = " p0.pm_idx desc, ";
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			//$order_by  = " p0.pm_idx desc, ";
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}
		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 웹용
	function getRecruitWebSearchList2025($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"aa.ad_addr",
			"aa.ad_gps_x",
			"aa.ad_gps_y",
			"aa.distance",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		# 거리 처리
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $params["addr1"];
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $params["addr2"];
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $params["addr3"];
		}

		//바디
		$body = "
            ( 
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
                    ,cc.ad_addr
                    ,cc.ad_gps_x
                    ,cc.ad_gps_y
                    ,aa.mm_comp_num
                    ,aa.mm_comp_name
                    ,'99.9' distance
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join st_address cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join st_promotion po
                    on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
                group by bb.rc_idx
            ) aa
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		  LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.rc_idx AND p0.pm_code = 'C001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";




		if ($search_addr != "") {
			$body .= " AND p0.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}

		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";


		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
					aa.rc_name like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_nation like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_sex like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_price_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_hash like '%" . $this->inject($params["text"]) . "%'
				)";
		}

		/*
			# 나이
			if($params["sage"]!=""){
				$where[] = 	" and (aa.rc_sage != 0 and aa.rc_sage >= '".$this->inject($params["sage"])."')";
			}

			# 나이
			if($params["eage"]!=""){
				$where[] = 	" and (aa.rc_eage != 0 and aa.rc_eage <= '".$this->inject($params["eage"])."')";
			}
			*/
		# 나이 교집합
		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = $this->inject($params["sage"]);
			$eage = $this->inject($params["eage"]);
			$where[] = " and (
					(aa.rc_sage = 0 and aa.rc_eage = 0)
					or
					(aa.rc_sage <= '{$eage}' and aa.rc_eage >= '{$sage}')
				)";
		}

		if ($search_addr != "") { // && $params["multiAddr1"]=="" && $params["multiAddr2"]=="" && $params["multiAddr3"]==""
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' OR p0.pm_addr LIKE '%" . $search_addr . "%' OR p1.pm_addr LIKE '%" . $search_addr . "%' OR p2.pm_addr LIKE '%" . $search_addr . "%') ";
		}

		//if($params["multiAddr1"]!=""){
		//  $multiAddr1_where = $this->getMultiAnd($params["multiAddr1"],"aa.ad_addr");
		//	$where[] = $multiAddr1_where;
		//}
		//if($params["multiAddr2"]!=""){
		//  $multiAddr2_where = $this->getMultiAnd($params["multiAddr2"],"aa.ad_addr");
		//	$where[] = $multiAddr2_where;
		//}
		//if($params["multiAddr3"]!=""){
		//  $multiAddr3_where = $this->getMultiAnd($params["multiAddr3"],"aa.ad_addr");
		//	$where[] = $multiAddr3_where;
		//}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd2025($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd2025($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd2025($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd2025($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		# company 지정
		//error_log("membermodel:".$params["compidx"], "3", "/testlog/php_log");

		if ($params["compidx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["compidx"] . "' ";
		}

		//정렬
		if ($params["order_by"] == "recent") {
			//$order_by  = " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			//$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			//$order_by .= " reg_dt desc ";
			//$order_by  = " p0.pm_idx desc, ";
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			//$order_by  = " p0.pm_idx desc, ";
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}
		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 멀티지역
	function getRecruitSearchList2025($params)
	{

		$currentLongitude = isset($params["currentLongitude"]) ? $this->inject($params["currentLongitude"]) : "126.9780";
		$currentLatitude  = isset($params["currentLatitude"])  ? $this->inject($params["currentLatitude"])  : "37.5665";
		$search_addr = isset($params["search_addr"]) && is_array($params["search_addr"]) ? array_map([$this, 'inject'], $params["search_addr"]) : [];

		$cols = [
			"aa.*",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		# 검색 지역 처리 (RECRUIT + EXPOSE_1 + EXPOSE_2 모두 포함)
		$addr_condition_aa = $this->make_addr_like_condition("aa.s_addr", $search_addr);
		$addr_condition_aa_raw = $this->make_addr_like_condition("aa.ad_addr", $search_addr);
		$addr_condition_expose1 = $this->make_addr_like_condition("aa.expose_addr1", $search_addr);
		$addr_condition_expose1s = $this->make_addr_like_condition("aa.expose_s_addr1", $search_addr);
		$addr_condition_expose2 = $this->make_addr_like_condition("aa.expose_addr2", $search_addr);
		$addr_condition_expose2s = $this->make_addr_like_condition("aa.expose_s_addr2", $search_addr);
		$addr_condition_p0 = $this->make_addr_like_condition("p0.pm_addr", $search_addr);
		$addr_condition_p1 = $this->make_addr_like_condition("p1.pm_addr", $search_addr);
		$addr_condition_p2 = $this->make_addr_like_condition("p2.pm_addr", $search_addr);

		$body = "
            (
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
                    ,cc.ad_addr
        						,CONCAT(cc.ad_doro, ' ', cc.ad_jibun) AS s_addr
                    ,cc.ad_gps_x
                    ,cc.ad_gps_y
                    ,aa.mm_comp_num
                    ,aa.mm_comp_name
                    ,ROUND(
                      6371 * acos(
                      cos(radians($currentLongitude)) * cos(radians(cc.ad_gps_x)) * cos(radians(cc.ad_gps_y) - radians($currentLatitude)) +
                      sin(radians($currentLongitude)) * sin(radians(cc.ad_gps_x))
                      ), 1
                    ) AS distance
                    ,IFNULL(e1.ad_addr, '') AS expose_addr1
                    ,IFNULL(CONCAT(IFNULL(e1.ad_doro,''), ' ', IFNULL(e1.ad_jibun,'')), '') AS expose_s_addr1
                    ,IFNULL(e2.ad_addr, '') AS expose_addr2
                    ,IFNULL(CONCAT(IFNULL(e2.ad_doro,''), ' ', IFNULL(e2.ad_jibun,'')), '') AS expose_s_addr2
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                left join st_address cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join st_address e1
                    on aa.mm_idx = e1.mm_idx and e1.ad_type = 'RECRUIT_EXPOSE_1' and e1.ad_key = bb.rc_idx
                left join st_address e2
                    on aa.mm_idx = e2.mm_idx and e2.ad_type = 'RECRUIT_EXPOSE_2' and e2.ad_key = bb.rc_idx
                left join st_promotion po
                    on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
                group by bb.rc_idx
            ) aa
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		  LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.rc_idx AND p0.pm_code = 'C001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";

		if ($addr_condition_p0)
			$body .= " AND $addr_condition_p0 ";

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($addr_condition_p1)
			$body .= " AND $addr_condition_p1 ";

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($addr_condition_p2)
			$body .= " AND $addr_condition_p2 ";

		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";


		# 검색어 (주소 포함 - RECRUIT + EXPOSE_1 + EXPOSE_2)
		if ($params["text"] != "") {
			$text = $this->inject($params["text"]);
			$where[] = 	" and (
					aa.rc_name like '%{$text}%' or
					aa.rc_nation like '%{$text}%' or
					aa.rc_sex like '%{$text}%' or
					aa.rc_work like '%{$text}%' or
					aa.rc_work_type like '%{$text}%' or
					aa.rc_price_type like '%{$text}%' or
					aa.rc_hash like '%{$text}%' or
					aa.s_addr like '%{$text}%' or
					aa.ad_addr like '%{$text}%' or
					aa.expose_addr1 like '%{$text}%' or
					aa.expose_s_addr1 like '%{$text}%' or
					aa.expose_addr2 like '%{$text}%' or
					aa.expose_s_addr2 like '%{$text}%'
				)";
		}

		# 나이 교집합
		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = $this->inject($params["sage"]);
			$eage = $this->inject($params["eage"]);
			$where[] = " and (
					(aa.rc_sage = 0 and aa.rc_eage = 0)
					or
					(aa.rc_sage <= '{$eage}' and aa.rc_eage >= '{$sage}')
				)";
		}

		$all_addr_conditions = array_filter([
			$addr_condition_aa,
			$addr_condition_aa_raw,
			$addr_condition_expose1,
			$addr_condition_expose1s,
			$addr_condition_expose2,
			$addr_condition_expose2s,
			$addr_condition_p0,
			$addr_condition_p1,
			$addr_condition_p2
		]);
		if (!empty($all_addr_conditions)) {
			$where[] = " AND (" . implode(" OR ", $all_addr_conditions) . ") ";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAndAll2025($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAndAll2025($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAndAll2025($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAndAll2025($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		# company 지정

		if ($params["compidx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["compidx"] . "' ";
		}

		//정렬
		if ($params["order_by"] == "recent") {
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by  = " case when (case when ifnull(p0.pm_idx,0) > 0 then 1 else 0 end) = 1 then rand() else 0 end desc, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}
		$tmp = json_encode($search_addr);
		$group_by = "group by aa.rc_idx /* $tmp */";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 핀맵용
	function getRecruitPinsMapList2025($params)
	{

		$currentLongitude = isset($params["currentLongitude"]) ? $this->inject($params["currentLongitude"]) : "126.9780";
		$currentLatitude  = isset($params["currentLatitude"])  ? $this->inject($params["currentLatitude"])  : "37.5665";
		$search_addr = isset($params["search_addr"]) && is_array($params["search_addr"])
			? array_map([$this, 'inject'], $params["search_addr"])
			: [];
		$inClause = implode(',', array_map(function ($v) {
			return "'" . addslashes($v) . "'";
		}, $params["pins"]));
		$pagesize = count($params["pins"]);

		//컬럼
		$cols = [
			"aa.*",
			"aa.ad_addr",
			"aa.ad_gps_x",
			"aa.ad_gps_y",
			"aa.distance",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '대',' ~ ',rc_eage,'대')
				when rc_sage <> '' then concat(rc_sage, '세 ~')
				when rc_eage <> '' then concat('~ ',rc_eage, '대')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		# 검색 지역 처리
		$search_addr_step = isset($params["search_addr_step"]) ? $params["search_addr_step"] : [];
		$addr_condition_aa = $this->make_addr_like_condition("aa.s_addr", $search_addr, $search_addr_step);
		$addr_condition_p0 = $this->make_addr_like_condition("p0.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p1 = $this->make_addr_like_condition("p1.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p2 = $this->make_addr_like_condition("p2.pm_addr", $search_addr, $search_addr_step);

		//바디
		$body = "
				(
					select aa.mm_id
						,aa.mm_status
						,bb.*
						,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
						,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
						,cc.ad_addr
						,CONCAT(cc.ad_doro, ' ', cc.ad_jibun) AS s_addr
						,cc.ad_gps_x
						,cc.ad_gps_y
						,aa.mm_comp_num
						,aa.mm_comp_name
						,ROUND(
						  6371 * acos(
							cos(radians($currentLongitude)) * cos(radians(cc.ad_gps_x)) * cos(radians(cc.ad_gps_y) - radians($currentLatitude)) +
							sin(radians($currentLongitude)) * sin(radians(cc.ad_gps_x))
						  ), 1
						) AS distance
					FROM st_member aa
					inner join st_recruit bb
						on aa.mm_idx = bb.mm_idx
					INNER JOIN st_address cc 
					  ON aa.mm_idx = cc.mm_idx 
					  AND cc.ad_type = 'RECRUIT' 
					  AND cc.ad_key = bb.rc_idx
					  AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
					  AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1
					left join st_promotion po
						on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
					group by bb.rc_idx
				) aa
				left join st_bookmark dd
					on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
			  LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.rc_idx AND p0.pm_code = 'C001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($addr_condition_p0)
			$body .= " AND $addr_condition_p0 ";

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($addr_condition_p1)
			$body .= " AND $addr_condition_p1 ";

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($addr_condition_p2)
			$body .= " AND $addr_condition_p2 ";


		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";
		$where[] = 	" and aa.rc_idx IN (" . $inClause . ") ";

		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
					aa.rc_name like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_nation like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_sex like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_price_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_hash like '%" . $this->inject($params["text"]) . "%'
				)";
		}

		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = $this->inject($params["sage"]);
			$eage = $this->inject($params["eage"]);
			$where[] = " and (
					(aa.rc_sage = 0 and aa.rc_eage = 0)
					or
					(aa.rc_sage <= '{$eage}' and aa.rc_eage >= '{$sage}')
				)";
		}

		$all_addr_conditions = array_filter([$addr_condition_aa, $addr_condition_p0, $addr_condition_p1, $addr_condition_p2]);
		if (!empty($all_addr_conditions)) {
			$where[] = " AND (" . implode(" OR ", $all_addr_conditions) . ") ";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd2025($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd2025($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd2025($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd2025($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		if ($params["compidx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["compidx"] . "' ";
		}


		$order_by  = " aa.distance ASC ";

		$group_by = "group by aa.rc_idx  ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $pagesize, $group_by, false, true);
	}

	# 업체 리스트 조회 : 맵용
	function getRecruitMapList2025($params)
	{

		$currentLongitude = isset($params["currentLongitude"]) ? $this->inject($params["currentLongitude"]) : "126.9780";
		$currentLatitude  = isset($params["currentLatitude"])  ? $this->inject($params["currentLatitude"])  : "37.5665";
		$search_addr = isset($params["search_addr"]) && is_array($params["search_addr"])
			? array_map([$this, 'inject'], $params["search_addr"])
			: [];
		//컬럼
		$cols = [
			"aa.*",
			"aa.ad_addr",
			"aa.ad_gps_x",
			"aa.ad_gps_y",
			"aa.distance",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '대',' ~ ',rc_eage,'대')
				when rc_sage <> '' then concat(rc_sage, '세 ~')
				when rc_eage <> '' then concat('~ ',rc_eage, '대')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		# 검색 지역 처리
		$search_addr_step = isset($params["search_addr_step"]) ? $params["search_addr_step"] : [];
		$addr_condition_aa = $this->make_addr_like_condition("aa.s_addr", $search_addr, $search_addr_step);
		$addr_condition_p0 = $this->make_addr_like_condition("p0.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p1 = $this->make_addr_like_condition("p1.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p2 = $this->make_addr_like_condition("p2.pm_addr", $search_addr, $search_addr_step);

		//바디
		$body = "
				(
					select aa.mm_id
						,aa.mm_status
						,bb.*
						,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
						,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
						,cc.ad_addr
						,CONCAT(cc.ad_doro, ' ', cc.ad_jibun) AS s_addr
						,cc.ad_gps_x
						,cc.ad_gps_y
						,aa.mm_comp_num
						,aa.mm_comp_name
						,ROUND(
						  6371 * acos(
							cos(radians($currentLongitude)) * cos(radians(cc.ad_gps_x)) * cos(radians(cc.ad_gps_y) - radians($currentLatitude)) +
							sin(radians($currentLongitude)) * sin(radians(cc.ad_gps_x))
						  ), 1
						) AS distance
					FROM st_member aa
					inner join st_recruit bb
						on aa.mm_idx = bb.mm_idx
					INNER JOIN st_address cc 
					  ON aa.mm_idx = cc.mm_idx 
					  AND cc.ad_type = 'RECRUIT' 
					  AND cc.ad_key = bb.rc_idx
					  AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
					  AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1
					left join st_promotion po
						on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
					group by bb.rc_idx
				) aa
				left join st_bookmark dd
					on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
			  LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.rc_idx AND p0.pm_code = 'C001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($addr_condition_p0)
			$body .= " AND $addr_condition_p0 ";

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.rc_idx AND p1.pm_code = 'C002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($addr_condition_p1)
			$body .= " AND $addr_condition_p1 ";

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.rc_idx AND p2.pm_code = 'C003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($addr_condition_p2)
			$body .= " AND $addr_condition_p2 ";


		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";


		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
					aa.rc_name like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_nation like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_sex like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_work_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_price_type like '%" . $this->inject($params["text"]) . "%' or
					aa.rc_hash like '%" . $this->inject($params["text"]) . "%'
				)";
		}

		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = $this->inject($params["sage"]);
			$eage = $this->inject($params["eage"]);
			$where[] = " and (
					(aa.rc_sage = 0 and aa.rc_eage = 0)
					or
					(aa.rc_sage <= '{$eage}' and aa.rc_eage >= '{$sage}')
				)";
		}

		$all_addr_conditions = array_filter([$addr_condition_aa, $addr_condition_p0, $addr_condition_p1, $addr_condition_p2]);
		if (!empty($all_addr_conditions)) {
			$where[] = " AND (" . implode(" OR ", $all_addr_conditions) . ") ";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd2025($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd2025($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd2025($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd2025($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		if ($params["compidx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $params["compidx"] . "' ";
		}


		$order_by  = " aa.distance ASC ";

		$group_by = "group by aa.rc_idx  ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 관리자용
	function getRecruitSearchListV2($params)
	{

		//컬럼
		$cols = [
			"bb.*",
			"aa.mm_name,aa.mm_id,aa.mm_comp_name,aa.mm_type,aa.mm_phone",
			"cc.ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
			inner join st_recruit bb
				on aa.mm_idx = bb.mm_idx
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
			left join st_bookmark dd
				on bb.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];

		$where[] = 	" and rc_status in ('Y','H') ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%'
							  or rc_name like '%" . $this->inject($params["search_value"]) . "%'
                              or cc.ad_addr like '%" . $this->inject($params["search_value"]) . "%'
                              or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and (cc.ad_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "rc_name") {
				$where[] = 	" and (bb.rc_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "bb.rc_work");
			$where[] = $cate_where;
		}

		if ($params["show_yn"] != "") {
			if ($params["show_yn"] == "H") {
				$where[] = 	" and (bb.rc_status = '" . $this->inject($params["show_yn"]) . "')";
			} else {
				$where[] = 	" and (bb.rc_show_yn = '" . $this->inject($params["show_yn"]) . "') and rc_status <> 'H' ";
			}
		}

		//정렬
		$order_by = "bb.rc_idx desc";
		$group_by = "group by bb.rc_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	######################################
	# 인재정보 처리
	######################################

	function getResumeSearchParamsList($page_size = 10)
	{
		$data = [];

		//error_log("getResumeSearchParamsList [".$params["category"]."]", "3", "/testlog/php_log");

		$mm_idx = $this->user->getUserIdx();
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
		$save_yn = input_post("save_yn", "N");

		$addr_info = $this->user->getAddrInfo();

		# 저장인경우 파라미터 그대로 사용하고 아니면 기존 검색 정보 가져와 설정한다.
		if ($save_yn == "N") {
			$site_log = $this->site_model->getSiteLog([
				"session_id" => session_id(),
				"sl_type" => "SEARCH_RESUME",
				"sl_step" => "검색"
			]);

			if ($site_log == null) {
				if ($this->user->isLogin()) {
					$site_log = $this->site_model->getSiteLog([
						"mm_idx" => $mm_idx,
						"sl_type" => "SEARCH_RESUME",
						"sl_step" => "검색"
					]);
				}
			}

			if ($site_log != null) {
				$json_info = json_decode($site_log["sl_txt"], true);
				$text = $json_info["text"];
				$skm = $json_info["skm"];
				$ekm = $json_info["ekm"];
				$category = $json_info["category"];
				$work_type = $json_info["work_type"];
				$week = $json_info["week"];
				$price_type = $json_info["price_type"];
				$sex = $json_info["sex"];
				$sage = $json_info["sage"];
				$eage = $json_info["eage"];
			}
		}

		$params = [
			"pagesize" => $page_size,
			"page" => $page,
			"gps_x" => $addr_info["ad_gps_x"],
			"gps_y" => $addr_info["ad_gps_y"],
			"text" => $text,
			"mm_idx" => $mm_idx,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"ad_addr" => $addr_info["ad_addr"]
		];

		$result = $this->getResumeSearchList($params);
		$list = $result["list"];

		foreach ($list as $key => $row) {
			$list[$key]["ru_resume_img"] = getResumeImg($row);
			$list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
			$list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"]);
			$list[$key]["ru_price_type"] = getPriceTypePoint($row["ru_price_type"], $row);
			$list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);
			$list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
			$list[$key]["mm_name"] = getAstrik($row["mm_name"]);
		}

		# 검색어 저장
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
					"mm_idx" => $mm_idx,
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
				"mm_idx" => $mm_idx,
				"sl_type" => "SEARCH_RESUME",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list" => $list,
			"result_count" => $result["count"],
		];

		return $data;
	}

	function getResumeSearchParamsListMap($param, $page_size = 20)
	{
		$data = [];

		//error_log("getResumeSearchParamsListMap 1[".$param["category"]."]", "3", "/testlog/php_log");

		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$text = $param["text"];
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = $param["category"];
		$work_type = $param["work_type"];
		$week = $param["week"];
		$price_type = $param["price_type"];
		$sex = $param["sex"];
		$sage = $param["sage"];
		$eage = $param["eage"];
		$save_yn = input_post("save_yn", "N");

		//error_log("getResumeSearchParamsListMap 2[".$category."]", "3", "/testlog/php_log");

		$addr_info = $this->user->getAddrInfo();

		# 저장인경우 파라미터 그대로 사용하고 아니면 기존 검색 정보 가져와 설정한다.
		if ($save_yn == "N") {
			$site_log = $this->site_model->getSiteLog([
				"session_id" => session_id(),
				"sl_type" => "SEARCH_RESUME",
				"sl_step" => "검색"
			]);

			if ($site_log == null) {
				if ($this->user->isLogin()) {
					$site_log = $this->site_model->getSiteLog([
						"mm_idx" => $mm_idx,
						"sl_type" => "SEARCH_RESUME",
						"sl_step" => "검색"
					]);
				}
			}

			/*
            if($site_log!=null){
                $json_info = json_decode($site_log["sl_txt"],true);
                $text = $json_info["text"];
                $skm = $json_info["skm"];
                $ekm = $json_info["ekm"];
                $category = $json_info["category"];
                $work_type = $json_info["work_type"];
                $week = $json_info["week"];
                $price_type = $json_info["price_type"];
                $sex = $json_info["sex"];
                $sage = $json_info["sage"];
                $eage = $json_info["eage"];
            }
            */
		}

		//error_log("getResumeSearchParamsListMap 3[".$category."]", "3", "/testlog/php_log");

		$params = [
			"pagesize" => $page_size,
			"page" => $page,
			"gps_x" => $addr_info["ad_gps_x"],
			"gps_y" => $addr_info["ad_gps_y"],
			"text" => $text,
			"mm_idx" => $mm_idx,
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

		//error_log("getResumeSearchParamsListMap 4[".$category."]", "3", "/testlog/php_log");

		//error_log("getResumeSearchParamsListMap 5[".$params["category"]."]", "3", "/testlog/php_log");

		$result = $this->getResumeSearchList($params);
		$list = $result["list"];

		foreach ($list as $key => $row) {
			$list[$key]["ru_resume_img"] = getResumeImg($row);
			$list[$key]["ru_work_img"] = getWorkingImg($row["ru_work"]);
			$list[$key]["ru_work_type"] = getWorkType($row["ru_work_type"]);
			$list[$key]["ru_price_type"] = getPriceTypePoint($row["ru_price_type"], $row);
			$list[$key]["ru_my_type"] = getMyType($row["ru_my_type"]);
			$list[$key]["ru_week"] = getWeekYoil($row["ru_week"]);
			$list[$key]["mm_name"] = getAstrik($row["mm_name"]);
		}

		# 검색어 저장
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
					"mm_idx" => $mm_idx,
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
				"mm_idx" => $mm_idx,
				"sl_type" => "SEARCH_RESUME",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list" => $list,
			"result_count" => $result["count"],
		];

		return $data;
	}

	# 업체 리스트 조회 : 메인화면용
	function getResumeSearchList($params)
	{

		//error_log("getResumeSearchList [".$params["category"]."]", "3", "/testlog/php_log");

		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];
		if ($gps_x == '') $gps_x = "''";
		if ($gps_y == '') $gps_y = "''";

		# 지역검색 제외
		$is_addr = true;
		if ($gps_x == "127.028361548" && $gps_y == "37.496486063" || ($gps_x == "") || ($gps_y == "")) {
			$is_addr = false;
		}

		//컬럼
		$cols = [
			"bb.*",
			"cc.ad_addr",
			"cc.ad_gps_x",
			"cc.ad_gps_y",
			"aa.mm_name",
			"aa.mm_nation",
			"aa.mm_id",
			"mi.ma_age",
			"aa.mm_birth",
			"aa.mm_sex",
			"aa.mm_thumb",
			"distance",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt"
		];

		$add_addr = "";

		# 거리 처리
		//if($params["skm"] > 0){
		//	$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
		//				-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) >= " . $params["skm"];
		//}

		if ($params["ekm"] > 0 && $is_addr == true) {
			$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
						-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) <= " . $params["ekm"];
		}

		//바디
		$body = "
			st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join (
				SELECT *
					,ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
						-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1)
					AS distance
				FROM st_address
				where 1=1
				$add_addr
			) cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      LEFT JOIN st_promotion p0 ON p0.rc_idx = bb.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		//if($search_addr!=""){ $body .= " AND p0.pm_addr LIKE '%".$search_addr."%' "; }
		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = bb.ru_idx AND p1.pm_code = 'M002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		//if($search_addr!=""){ $body .= " AND p1.pm_addr LIKE '%".$search_addr."%' "; }
		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = bb.ru_idx AND p2.pm_code = 'M003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		//if($search_addr!=""){ $body .= " AND p2.pm_addr LIKE '%".$search_addr."%' "; }
		$body .= " LEFT JOIN st_promotion p ON bb.ru_idx = p.rc_idx AND p.pm_code in('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and bb.ru_status = 'Y' and bb.ru_show_yn = 'Y' ";


		# 검색어
		if ($params["text"] != "") {
			if ($params["text"] == "일" || $params["text"] == "일요일") {
				$where[] = 	" and ru_week like '%일%' ";
			} else if ($params["text"] == "월" || $params["text"] == "월요일") {
				$where[] = 	" and ru_week like '%월%' ";
			} else if ($params["text"] == "화" || $params["text"] == "화요일") {
				$where[] = 	" and ru_week like '%화%' ";
			} else if ($params["text"] == "수" || $params["text"] == "수요일") {
				$where[] = 	" and ru_week like '%수%' ";
			} else if ($params["text"] == "목" || $params["text"] == "목요일") {
				$where[] = 	" and ru_week like '%목%' ";
			} else if ($params["text"] == "금" || $params["text"] == "금요일") {
				$where[] = 	" and ru_week like '%금%' ";
			} else if ($params["text"] == "토" || $params["text"] == "토요일") {
				$where[] = 	" and ru_week like '%토%' ";
			} else if ($params["text"] == "남" || $params["text"] == "남자") {
				$where[] =	 " and aa.mm_sex = '남' ";
			} else if ($params["text"] == "여" || $params["text"] == "여자") {
				$where[] =	 " and aa.mm_sex = '여' ";
			} else {
				$where[] = 	" and (
    				aa.mm_name like '%" . $this->inject($params["text"]) . "%' or
    				aa.mm_nation like '%" . $this->inject($params["text"]) . "%' or
    				ru_work like '%" . $this->inject($params["text"]) . "%' or
    				ru_name like '%" . $this->inject($params["text"]) . "%' or
    				mm_addr like '%" . $this->inject($params["text"]) . "%'  or
    				ru_price_type like '%" . $this->inject($params["text"]) . "%' or
    				ru_work_type like '%" . $this->inject($params["text"]) . "%' or
    				ru_my_type like '%" . $this->inject($params["text"]) . "%'
    			)";
			}
		}

		# 나이
		if ($params["sage"] != "") {
			$where[] = 	" and (mi.ma_age >= '" . $this->inject($params["sage"]) . "')";
		}

		# 나이
		if ($params["eage"] != "") {
			$where[] = 	" and (mi.ma_age <= '" . $this->inject($params["eage"]) . "')";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "bb.ru_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd($params["work_type"], "bb.ru_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd($params["week"], "bb.ru_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd($params["price_type"], "bb.ru_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd($params["sex"], "mi.ma_sex");
			$where[] = $sex_where;
		}

		//정렬
		//$order_by = "p0.pm_idx desc, distance asc";
		$order_by  = "p0.pm_idx desc ";
		if ($params["ad_addr"] == "지역 선택 없음") {
			$order_by .= " ,GREATEST(bb.up_dt, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by .= ", IFNULL(distance,9999999) asc ";
		}
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 인재 리스트 조회 : 웹용
	function getResumeWebSearchList2025($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		$add_addr = "";

		# 거리 처리
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $params["addr1"];
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $params["addr2"];
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $params["addr3"];
		}

		//바디
		$body = "
			(
				select bb.*
					,COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr
					,cc.ad_gps_x
					,cc.ad_gps_y
					,aa.mm_name
					,aa.mm_nation
					,aa.mm_status
					,mi.ma_age
          ,aa.mm_birth
					,mi.ma_sex
          ,aa.mm_sex
          ,aa.mm_thumb
				 	,(select count(*) as cnt FROM st_bookmark where bk_type = 'RESUME' and bk_key = bb.ru_idx) as book_cnt
				FROM st_member aa
				inner join st_member_info mi
	                on aa.mm_idx = mi.mm_idx
				inner join st_resume bb
					on aa.mm_idx = bb.mm_idx and bb.ru_status = 'Y'  and bb.ru_show_yn = 'Y'
				left join st_address cc
					on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			) aa
			left join st_bookmark dd
				on aa.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$body .= " LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p0.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.ru_idx AND p1.pm_code = 'M002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.ru_idx AND p2.pm_code = 'M003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p  ON  p.rc_idx = aa.ru_idx AND p.pm_code in('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
				aa.mm_name like '%" . $this->inject($params["text"]) . "%' or
				aa.mm_nation like '%" . $this->inject($params["text"]) . "%' or
				ru_work like '%" . $this->inject($params["text"]) . "%' or
				ru_name like '%" . $this->inject($params["text"]) . "%' or
				ru_addr like '%" . $this->inject($params["text"]) . "%'  or
				ru_price_type like '%" . $this->inject($params["text"]) . "%' or
				ru_work_type like '%" . $this->inject($params["text"]) . "%' or
				ru_my_type like '%" . $this->inject($params["text"]) . "%'
			)";
		}

		# 나이
		$today = date("Ymd");

		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = (int)$this->inject($params["sage"]);
			$eage = (int)$this->inject($params["eage"]);

			$min_birth = date("Ymd", strtotime("-{$eage} years", strtotime($today)));
			$max_birth = date("Ymd", strtotime("-{$sage} years", strtotime($today)));

			$where[] = " and (
        (aa.mm_birth = 0)
        or
        (aa.mm_birth <= '{$max_birth}' and aa.mm_birth >= '{$min_birth}')
      )";
		}

		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' OR p0.pm_addr LIKE '%" . $search_addr . "%' OR p1.pm_addr LIKE '%" . $search_addr . "%' OR p2.pm_addr LIKE '%" . $search_addr . "%') ";
		}

		if ($params["multiAddr1"] != "") {
			$multiAddr1_where = $this->getMultiAnd($params["multiAddr1"], "aa.ad_addr");
			$where[] = $multiAddr1_where;
		}
		if ($params["multiAddr2"] != "") {
			$multiAddr2_where = $this->getMultiAnd($params["multiAddr2"], "aa.ad_addr");
			$where[] = $multiAddr2_where;
		}
		if ($params["multiAddr3"] != "") {
			$multiAddr3_where = $this->getMultiAnd($params["multiAddr3"], "aa.ad_addr");
			$where[] = $multiAddr3_where;
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.ru_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAndAll2025($params["work_type"], "aa.ru_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAndAll2025($params["week"], "aa.ru_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAndAll2025($params["price_type"], "aa.ru_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex = str_replace("자", "", $params["sex"]);
			$sex_where = $this->getMultiAndAll2025($sex, "aa.mm_sex");
			$where[] = $sex_where;
		}

		//정렬 (프로모션 우선 → 최신순, 프로모션 그룹 내부는 매 요청마다 랜덤)
		if ($params["order_by"] == "recent") {
			//$order_by  = " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			//$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			//$order_by .= " reg_dt desc ";
			$order_by  = " (p0.pm_idx IS NULL) ASC, (CASE WHEN p0.pm_idx IS NOT NULL THEN RAND() ELSE 0 END),  ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by  = " (p0.pm_idx IS NULL) ASC, (CASE WHEN p0.pm_idx IS NOT NULL THEN RAND() ELSE 0 END),  ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}

		$group_by = "group by aa.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 인재 리스트 조회 : 멀티지역
	function getResumeSearchList2025($params)
	{
		$search_addr = isset($params["search_addr"]) && is_array($params["search_addr"]) ? array_map([$this, 'inject'], $params["search_addr"]) : [];
		//log_message('info', '[getResumeSearchList2025] params: ' . json_encode($params));
		//log_message('info', '[getResumeSearchList2025] search_addr: ' . json_encode($search_addr));
		// params: {"pagesize":"5","page":"1","text":"\uacbd\uae30","mm_idx":"4","category":"","work_type":"","week":"","price_type":"","sex":"","sage":"","eage":"","order_by":"recent","search_addr":[],"addrName":"","searchAreaStr":"","ru_work":""}

		//컬럼
		$cols = [
			"aa.*",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt",
			"'" . $this->inject($params["addrName"]) . "' as addrName",
			"'" . $this->inject($params["searchAreaStr"]) . "' as searchAreaStr"
		];

		# 검색 지역 처리
		$search_addr_step = isset($params["search_addr_step"]) ? $params["search_addr_step"] : [];
		$addr_condition_aa = $this->make_addr_like_condition("aa.s_addr", $search_addr, $search_addr_step); // aa.s_addr 컬럼에 대해 검색 주소 조건 생성
		$addr_condition_p0 = $this->make_addr_like_condition("p0.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p1 = $this->make_addr_like_condition("p1.pm_addr", $search_addr, $search_addr_step);
		$addr_condition_p2 = $this->make_addr_like_condition("p2.pm_addr", $search_addr, $search_addr_step);

		// 디버그: 변수 값 확인
		// error_log("=== 주소 검색 조건 디버그 ===");
		// error_log("search_addr: " . json_encode($search_addr, JSON_UNESCAPED_UNICODE));
		// error_log("search_addr_step: " . json_encode($search_addr_step, JSON_UNESCAPED_UNICODE));
		
		// error_log("=== 디버그 끝 ===");

		//바디
		$body = "
			(
				select bb.*
					,COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr
          ,MIN(CONCAT(cc.ad_doro, ' ', cc.ad_jibun)) AS s_addr
					,MIN(cc.ad_gps_x) AS ad_gps_x
					,MIN(cc.ad_gps_y) AS ad_gps_y
					,aa.mm_name
					,aa.mm_nation
					,aa.mm_status
					,mi.ma_age
          ,aa.mm_birth
					,mi.ma_sex
          ,aa.mm_sex
          ,aa.mm_thumb
				 	,(select count(*) as cnt FROM st_bookmark where bk_type = 'RESUME' and bk_key = bb.ru_idx) as book_cnt
				FROM st_member aa
				left join st_member_info mi
	                on aa.mm_idx = mi.mm_idx
				inner join st_resume bb
					on aa.mm_idx = bb.mm_idx and bb.ru_status = 'Y'  and bb.ru_show_yn = 'Y'
				left join st_address cc
					on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
				GROUP BY bb.ru_idx
			) aa
			left join st_bookmark dd
				on aa.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		$body .= " LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($addr_condition_p0)
			$body .= " AND $addr_condition_p0 ";

		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.ru_idx AND p1.pm_code = 'M002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($addr_condition_p1)
			$body .= " AND $addr_condition_p1 ";

		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.ru_idx AND p2.pm_code = 'M003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($addr_condition_p2)
			$body .= " AND $addr_condition_p2 ";

		$body .= " LEFT JOIN st_promotion p  ON  p.rc_idx = aa.ru_idx AND p.pm_code in('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		# 나이

		# 나이
		$today = date("Ymd");

		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = (int)$this->inject($params["sage"]);
			$eage = (int)$this->inject($params["eage"]);

			$min_birth = date("Ymd", strtotime("-{$eage} years", strtotime($today)));
			$max_birth = date("Ymd", strtotime("-{$sage} years", strtotime($today)));

			$where[] = " and (
        (aa.mm_birth = 0)
        or
        (aa.mm_birth <= '{$max_birth}' and aa.mm_birth >= '{$min_birth}')
      )";
		}
        // array_filter:배열의 각 요소를 하나씩 검사해서, 조건에 맞는(True인) 요소만 남기고 나머지는 제거
		$all_addr_conditions = array_filter([$addr_condition_aa, $addr_condition_p0, $addr_condition_p1, $addr_condition_p2]);
		if (!empty($all_addr_conditions)) {
			$where[] = " AND (" . implode(" OR ", $all_addr_conditions) . ") ";
			// where []: 배열에 요소 추가, implode:배열의 요소들을 합쳐서 문자열로 만듦
		}

		# 검색어
		if ($params["text"] != "") {
			$search_text = $this->inject($params["text"]);
			$sex_condition = "";

			// 성별 키워드 포함 확인
			if (strpos($search_text, "여자") !== false || strpos($search_text, "여성") !== false || strpos($search_text, "여") !== false) {
				$sex_condition = " or aa.mm_sex = '여' ";
			} elseif (strpos($search_text, "남자") !== false || strpos($search_text, "남성") !== false || strpos($search_text, "남") !== false) {
				$sex_condition = " or aa.mm_sex = '남' ";
			}

			$where[] = 	" and (
				aa.mm_name like '%" . $search_text . "%' or
				aa.mm_nation like '%" . $search_text . "%' or
				ru_work like '%" . $search_text . "%' or
				ru_name like '%" . $search_text . "%' or
				aa.ru_addr like '%" . $search_text . "%' or
				aa.s_addr like '%" . $search_text . "%' or
				aa.ad_addr like '%" . $search_text . "%' or
				ru_price_type like '%" . $search_text . "%' or
				ru_work_type like '%" . $search_text . "%' or
				ru_my_type like '%" . $search_text . "%'
				" . $sex_condition . "
			)";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll2025($params["category"], "aa.ru_work");
			$where[] = $cate_where;
		}

		# 근무 직종
		if ($params["ru_work"] != "") {
			$ru_work_where = $this->getMultiAndAll2025($params["ru_work"], "bb.ru_work");
			$where[] = $ru_work_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAndAll2025($params["work_type"], "aa.ru_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAndAll2025($params["week"], "aa.ru_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAndAll2025($params["price_type"], "aa.ru_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex = str_replace("자", "", $params["sex"]);
			$sex_where = $this->getMultiAndAll2025($sex, "aa.mm_sex");
			$where[] = $sex_where;
		}

		//정렬
		if ($params["order_by"] == "recent") {
			$order_by  = " p0.pm_idx desc,  ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else if ($params["order_by"] == "onlydate") {
			$order_by = " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by  = " p0.pm_idx desc,  ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}

		$group_by = "group by aa.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 웹용
	function getResumeWebSearchList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		$add_addr = "";

		# 거리 처리
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $params["addr1"];
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $params["addr2"];
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $params["addr3"];
		}

		//바디
		$body = "
			(
				select bb.*
					,COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr
					,cc.ad_gps_x
					,cc.ad_gps_y
					,aa.mm_name
					,aa.mm_nation
					,aa.mm_id
					,aa.mm_status
					,mi.ma_age
          ,aa.mm_birth
					,mi.ma_sex
          ,aa.mm_sex
          ,aa.mm_thumb
				 	,(select count(*) as cnt FROM st_bookmark where bk_type = 'RESUME' and bk_key = bb.ru_idx) as book_cnt
				FROM st_member aa
				inner join st_member_info mi
	                on aa.mm_idx = mi.mm_idx
				inner join st_resume bb
					on aa.mm_idx = bb.mm_idx and bb.ru_status = 'Y'  and bb.ru_show_yn = 'Y'
				left join st_address cc
					on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			) aa
			left join st_bookmark dd
				on aa.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$body .= " LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p0.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.ru_idx AND p1.pm_code = 'M002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.ru_idx AND p2.pm_code = 'M003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p  ON  p.rc_idx = aa.ru_idx AND p.pm_code in('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
				aa.mm_name like '%" . $this->inject($params["text"]) . "%' or
				aa.mm_nation like '%" . $this->inject($params["text"]) . "%' or
				ru_work like '%" . $this->inject($params["text"]) . "%' or
				ru_name like '%" . $this->inject($params["text"]) . "%' or
				ru_addr like '%" . $this->inject($params["text"]) . "%'  or
				ru_price_type like '%" . $this->inject($params["text"]) . "%' or
				ru_work_type like '%" . $this->inject($params["text"]) . "%' or
				ru_my_type like '%" . $this->inject($params["text"]) . "%'
			)";
		}

		# 나이
		//    if($params["sage"]!=""){
		//			$where[] = 	" and (aa.ma_age >= '".$this->inject($params["sage"])."')";
		//		}
		//
		//    # 나이
		//    if($params["eage"]!=""){
		//			$where[] = 	" and (aa.ma_age <= '".$this->inject($params["eage"])."')";
		//		}

		if ($params["age"] != "") {
			$age_where = $this->getMultiAndAge($params["age"], "aa.ma_age");
			$where[] = $age_where;
		}


		if ($search_addr != "") { // && $params["multiAddr1"]=="" && $params["multiAddr2"]=="" && $params["multiAddr3"]==""
			//$where[] = 	" and (aa.ad_addr like '%".$search_addr."%')";
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' OR p0.pm_addr LIKE '%" . $search_addr . "%' OR p1.pm_addr LIKE '%" . $search_addr . "%' OR p2.pm_addr LIKE '%" . $search_addr . "%') ";
		}
		if ($params["multiAddr1"] != "") {
			$multiAddr1_where = $this->getMultiAnd($params["multiAddr1"], "aa.ad_addr");
			$where[] = $multiAddr1_where;
		}
		if ($params["multiAddr2"] != "") {
			$multiAddr2_where = $this->getMultiAnd($params["multiAddr2"], "aa.ad_addr");
			$where[] = $multiAddr2_where;
		}
		if ($params["multiAddr3"] != "") {
			$multiAddr3_where = $this->getMultiAnd($params["multiAddr3"], "aa.ad_addr");
			$where[] = $multiAddr3_where;
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.ru_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd($params["work_type"], "aa.ru_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd($params["week"], "aa.ru_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd($params["price_type"], "aa.ru_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd($params["sex"], "aa.ma_sex");
			$where[] = $sex_where;
		}

		//정렬 (프로모션 우선 → 최신순, 프로모션 그룹 내부는 매 요청마다 랜덤)
		if ($params["order_by"] == "recent") {
			//$order_by  = " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			//$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			//$order_by .= " reg_dt desc ";
			$order_by  = " (p0.pm_idx IS NULL) ASC, (CASE WHEN p0.pm_idx IS NOT NULL THEN RAND() ELSE 0 END),  ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by  = " (p0.pm_idx IS NULL) ASC, (CASE WHEN p0.pm_idx IS NOT NULL THEN RAND() ELSE 0 END),  ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}

		$group_by = "group by aa.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 인재 리스트 조회 : 맵용
	function getResumeMapList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];
		$inClause = implode(',', array_map(function ($v) {
			return "'" . addslashes($v) . "'";
		}, $params["pins"]));
		$pagesize = count($params["pins"]);
		//컬럼
		$cols = [
			"aa.*",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"p0.pm_idx",
			"p0.reg_dt as pm_reg_dt",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt"
		];

		$add_addr = "";


		//바디
		$body = "
			(
				select bb.*
					,COALESCE(cc.ad_addr, bb.ru_addr) AS ad_addr
					,cc.ad_gps_x
					,cc.ad_gps_y
					,aa.mm_name
					,aa.mm_nation
					,aa.mm_status
					,mi.ma_age
          ,aa.mm_birth
					,mi.ma_sex
          ,aa.mm_sex
          ,aa.mm_thumb
				 	,(select count(*) as cnt FROM st_bookmark where bk_type = 'RESUME' and bk_key = bb.ru_idx) as book_cnt
				FROM st_member aa
				inner join st_member_info mi
	                on aa.mm_idx = mi.mm_idx
				inner join st_resume bb
					on aa.mm_idx = bb.mm_idx and bb.ru_status = 'Y'  and bb.ru_show_yn = 'Y'
				left join st_address cc
					on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			) aa
			left join st_bookmark dd
				on aa.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$body .= " LEFT JOIN st_promotion p0 ON p0.rc_idx = aa.ru_idx AND p0.pm_code = 'M001' AND p0.pm_sdate < NOW() AND p0.pm_edate > NOW() AND p0.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p0.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p1 ON p1.rc_idx = aa.ru_idx AND p1.pm_code = 'M002' AND p1.pm_sdate < NOW() AND p1.pm_edate > NOW() AND p1.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p1.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p2 ON p2.rc_idx = aa.ru_idx AND p2.pm_code = 'M003' AND p2.pm_sdate < NOW() AND p2.pm_edate > NOW() AND p2.pm_status = 'Y' ";
		if ($search_addr != "") {
			$body .= " AND p2.pm_addr LIKE '%" . $search_addr . "%' ";
		}
		$body .= " LEFT JOIN st_promotion p  ON  p.rc_idx = aa.ru_idx AND p.pm_code in('M002','M003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";
		$where[] = 	" and aa.ru_idx IN (" . $inClause . ") ";

		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (
				aa.mm_name like '%" . $this->inject($params["text"]) . "%' or
				aa.mm_nation like '%" . $this->inject($params["text"]) . "%' or
				ru_work like '%" . $this->inject($params["text"]) . "%' or
				ru_name like '%" . $this->inject($params["text"]) . "%' or
				ru_addr like '%" . $this->inject($params["text"]) . "%'  or
				ru_price_type like '%" . $this->inject($params["text"]) . "%' or
				ru_work_type like '%" . $this->inject($params["text"]) . "%' or
				ru_my_type like '%" . $this->inject($params["text"]) . "%'
			)";
		}

		# 나이
		//    if($params["sage"]!=""){
		//			$where[] = 	" and (aa.ma_age >= '".$this->inject($params["sage"])."')";
		//		}
		//
		//    # 나이
		//    if($params["eage"]!=""){
		//			$where[] = 	" and (aa.ma_age <= '".$this->inject($params["eage"])."')";
		//		}

		if ($params["age"] != "") {
			$age_where = $this->getMultiAndAge($params["age"], "aa.ma_age");
			$where[] = $age_where;
		}


		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' OR p0.pm_addr LIKE '%" . $search_addr . "%' OR p1.pm_addr LIKE '%" . $search_addr . "%' OR p2.pm_addr LIKE '%" . $search_addr . "%') ";
		}
		if ($params["multiAddr1"] != "") {
			$multiAddr1_where = $this->getMultiAnd($params["multiAddr1"], "aa.ad_addr");
			$where[] = $multiAddr1_where;
		}
		if ($params["multiAddr2"] != "") {
			$multiAddr2_where = $this->getMultiAnd($params["multiAddr2"], "aa.ad_addr");
			$where[] = $multiAddr2_where;
		}
		if ($params["multiAddr3"] != "") {
			$multiAddr3_where = $this->getMultiAnd($params["multiAddr3"], "aa.ad_addr");
			$where[] = $multiAddr3_where;
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.ru_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAnd($params["work_type"], "aa.ru_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAnd($params["week"], "aa.ru_week");
			$where[] = $week_where;
		}

		# 금여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAnd($params["price_type"], "aa.ru_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAnd($params["sex"], "aa.ma_sex");
			$where[] = $sex_where;
		}

		//정렬
		if ($params["order_by"] == "recent") {
			$order_by  = " p0.pm_idx desc,  ";
			$order_by .= " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		} else {
			$order_by  = " p0.pm_idx desc,  ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p1.pm_edate, NOW()), 4) DESC, ";
			$order_by .= " MOD(TIMESTAMPDIFF(HOUR, p2.pm_edate, NOW()), 8) DESC, ";
			$order_by .= " book_cnt desc ";
		}

		$group_by = "group by aa.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $pagesize, $group_by, false, true);
	}


	# 이력서 리스트 조회 : 관리자
	function getResumeSearchListV2($params)
	{
		//컬럼
		$cols = [
			"bb.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"aa.mm_name",
			"aa.mm_nation",
			"aa.mm_sex",
			"mi.ma_age",
		];

		$add_addr = "";

		//바디
		$body = "
			st_member aa
      inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];
		$where[] = 	" and ru_status in ('Y','HY','HN') and aa.mm_status = 'Y' ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%'
							  or ru_name like '%" . $this->inject($params["search_value"]) . "%'
                              or cc.ad_addr like '%" . $this->inject($params["search_value"]) . "%'
                              or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and (cc.ad_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "rc_name") {
				$where[] = 	" and (bb.rc_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "bb.ru_work");
			$where[] = $cate_where;
		}

		if ($params["show_yn"] != "") {
			if ($params["show_yn"] == "H") {
				$where[] = 	" and (bb.ru_status in('HY','HN'))";
			} else {
				$where[] = 	" and (bb.ru_show_yn = '" . $this->inject($params["show_yn"]) . "') and ru_status <> 'H' ";
			}
		}


		//정렬
		$order_by = "bb.ru_idx desc ";
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	// 주소 배열과 step 배열을 받아서 계층적 LIKE 조건을 생성 (location_data 역조회 포함)
	function make_addr_like_condition($column, $addr_array, $step_array = [])
	{
		if (empty($addr_array) || !is_array($addr_array)) return "";

		// 시도 공식명 → Daum 축약명 매핑 (addr_popup2025_new_tpl.php의 JS replaceRegionNames와 동일)
		$sido_abbr_map = array(
			"서울특별시" => "서울",
			"부산광역시" => "부산",
			"대구광역시" => "대구",
			"인천광역시" => "인천",
			"광주광역시" => "광주",
			"대전광역시" => "대전",
			"울산광역시" => "울산",
			"세종특별자치시" => "세종",
			"경기도" => "경기",
			"강원특별자치도" => "강원",
			"충청북도" => "충북",
			"충청남도" => "충남",
			"전북특별자치도" => "전북",
			"전라남도" => "전남",
			"경상북도" => "경북",
			"경상남도" => "경남",
			"제주특별자치도" => "제주"
		);

		$conditions = [];

		foreach ($addr_array as $idx => $addr) {
			$step = isset($step_array[$idx]) ? intval($step_array[$idx]) : 3;
			$escaped_addr = addslashes($addr);

			// step=1 (시도): 해당 시도의 모든 시군구+읍면동 검색
			if ($step == 1) {
				$temp_conditions = [];

				// 1. 시도+시군구, 시군구 이름으로 매칭 (계층적 주소 처리)
				$sql = "SELECT DISTINCT sido_name, gugun_name FROM location_data
						WHERE sido_name = '{$escaped_addr}' AND step = 2";
				try {
					$gugun_result = $this->db->query($sql)->result_array();
					if (!empty($gugun_result)) {
						foreach ($gugun_result as $row) {
							$gugun_name = $row['gugun_name'];
							$escaped_gugun = addslashes($gugun_name);

							// 시도+시군구로 매칭 (정확도 높음: "경기도 시흥시")
							$full_gugun = addslashes($row['sido_name'] . ' ' . $gugun_name);
							$temp_conditions[] = "$column LIKE '%{$full_gugun}%'";

							// 축약명 버전도 매칭 (대전광역시 → 대전): "대전 서구"
							if (isset($sido_abbr_map[$row['sido_name']])) {
								$abbr_sido = $sido_abbr_map[$row['sido_name']];
								$abbr_full_gugun = addslashes($abbr_sido . ' ' . $gugun_name);
								$temp_conditions[] = "$column LIKE '%{$abbr_full_gugun}%'";
							}

							// 부분주소 처리: gugun_name만 저장된 경우도 매칭
							// 단, location_data에서 이 gugun_name이 해당 sido에만 속하는지 확인 (cross-region 방지)
							$check_sql = "SELECT COUNT(DISTINCT sido_name) as sido_count FROM location_data
										  WHERE gugun_name = '{$escaped_gugun}' AND step = 2";
							try {
								$check_result = $this->db->query($check_sql)->row_array();
								// gugun_name이 유일하면 (한 시도에만 속함) 부분주소 매칭 추가
								if (!empty($check_result) && $check_result['sido_count'] == 1) {
									$temp_conditions[] = "$column LIKE '%{$escaped_gugun}%'";
								}
							} catch (Exception $e) {
								// 오류 무시
							}
						}
					}
				} catch (Exception $e) {
					// 오류 무시
				}

				// 2. 전체 주소로도 매칭 (st_address에 "경기도 시흥시 정왕동" 있는 경우)
				$sql = "SELECT DISTINCT CONCAT(sido_name, ' ', gugun_name, ' ', b_dong_name) as full_addr FROM location_data
						WHERE sido_name = '{$escaped_addr}' AND step = 3";
				try {
					$result = $this->db->query($sql)->result_array();
					if (!empty($result)) {
						$dong_conditions = array_map(function($row) use ($column, $sido_abbr_map) {
							return "$column LIKE '%" . addslashes($row['full_addr']) . "%'";
						}, $result);
						$temp_conditions[] = "(" . implode(" OR ", $dong_conditions) . ")";

						// 축약명 버전도 매칭 (대전광역시 → 대전)
						if (isset($sido_abbr_map[$escaped_addr]) || in_array($escaped_addr, array_values($sido_abbr_map))) {
							$search_sido = isset($sido_abbr_map[$escaped_addr]) ? $escaped_addr : array_search($escaped_addr, $sido_abbr_map);
							if ($search_sido && isset($sido_abbr_map[$search_sido])) {
								$abbr_dong_conditions = array_map(function($row) use ($column, $search_sido, $sido_abbr_map) {
									$abbr_sido = $sido_abbr_map[$search_sido];
									$gugun_name = $row['gugun_name'];
									$b_dong_name = $row['b_dong_name'];
									$abbr_full_addr = $abbr_sido . ' ' . $gugun_name . ' ' . $b_dong_name;
									return "$column LIKE '%" . addslashes($abbr_full_addr) . "%'";
								}, $result);
								$temp_conditions[] = "(" . implode(" OR ", $abbr_dong_conditions) . ")";
							}
						}
					}
				} catch (Exception $e) {
					$temp_conditions[] = "$column LIKE '%{$escaped_addr}%'";
				}

				// 3. 폴백: 시도명 단독 매칭 (구군 정보 없이 동명만 저장된 경우: "대전 관저동", "대전 용운동")
				// 공식명 버전 (예: "대전광역시")
				$temp_conditions[] = "$column LIKE '%{$escaped_addr}%'";

				// 축약명 버전 (예: "대전" 으로도 검색)
				if (isset($sido_abbr_map[$escaped_addr])) {
					$abbr_sido = $sido_abbr_map[$escaped_addr];
					$escaped_abbr_sido = addslashes($abbr_sido);
					$temp_conditions[] = "$column LIKE '%{$escaped_abbr_sido}%'";
				}

				if (!empty($temp_conditions)) {
					$conditions[] = "(" . implode(" OR ", $temp_conditions) . ")";
				}
			}
			// step=2 (시군구): 해당 시군구의 모든 읍면동 검색 (시도+구군+동명으로 정확히 필터)
			else if ($step == 2) {
				$addr_parts = explode(" ", trim($addr));
				if (count($addr_parts) >= 2) {
					$sido = addslashes($addr_parts[0]);
					$gugun = addslashes($addr_parts[1]);
					$temp_conditions = [];

					// 1. 전체 주소 매칭 (경기도 시흥시 정왕동)
					$sql = "SELECT DISTINCT CONCAT(sido_name, ' ', gugun_name, ' ', b_dong_name) as full_addr FROM location_data
							WHERE sido_name = '{$sido}' AND gugun_name = '{$gugun}' AND step = 3";
					try {
						$result = $this->db->query($sql)->result_array();
						if (!empty($result)) {
							$dong_conditions = array_map(function($row) use ($column) {
								return "$column LIKE '%" . addslashes($row['full_addr']) . "%'";
							}, $result);
							$temp_conditions[] = "(" . implode(" OR ", $dong_conditions) . ")";
						}
					} catch (Exception $e) {
						// 오류 무시
					}

					// 축약명 버전도 매칭 (대전광역시 → 대전)
					if (isset($sido_abbr_map[$sido])) {
						$abbr_sido = $sido_abbr_map[$sido];
						try {
							$abbr_sql = "SELECT DISTINCT CONCAT(sido_name, ' ', gugun_name, ' ', b_dong_name) as full_addr FROM location_data
									WHERE sido_name = '{$sido}' AND gugun_name = '{$gugun}' AND step = 3";
							$abbr_result = $this->db->query($abbr_sql)->result_array();
							if (!empty($abbr_result)) {
								$abbr_dong_conditions = array_map(function($row) use ($column, $abbr_sido) {
									$gugun_name = $row['gugun_name'];
									$b_dong_name = $row['b_dong_name'];
									$abbr_full_addr = $abbr_sido . ' ' . $gugun_name . ' ' . $b_dong_name;
									return "$column LIKE '%" . addslashes($abbr_full_addr) . "%'";
								}, $abbr_result);
								$temp_conditions[] = "(" . implode(" OR ", $abbr_dong_conditions) . ")";
							}
						} catch (Exception $e) {
							// 오류 무시
						}
					}

					// 2. 시도+시군구 매칭 (경기도 시흥시)
					$full_gugun = $sido . ' ' . $gugun;
					$temp_conditions[] = "$column LIKE '%{$full_gugun}%'";

					// 축약명 버전: 시도+시군구 매칭 (대전 서구)
					if (isset($sido_abbr_map[$sido])) {
						$abbr_sido = $sido_abbr_map[$sido];
						$abbr_full_gugun = $abbr_sido . ' ' . $gugun;
						$temp_conditions[] = "$column LIKE '%{$abbr_full_gugun}%'";
					}

					// 3. 부분주소 매칭: gugun_name만 저장된 경우 (시흥시)
					// 단, location_data에서 이 gugun_name이 해당 sido에만 속하는지 확인
					$check_sql = "SELECT COUNT(DISTINCT sido_name) as sido_count FROM location_data
								  WHERE gugun_name = '{$gugun}' AND step = 2";
					try {
						$check_result = $this->db->query($check_sql)->row_array();
						if (!empty($check_result) && $check_result['sido_count'] == 1) {
							$temp_conditions[] = "$column LIKE '%{$gugun}%'";
						}
					} catch (Exception $e) {
						// 오류 무시
					}

					if (!empty($temp_conditions)) {
						$conditions[] = "(" . implode(" OR ", $temp_conditions) . ")";
					}
				}
			}
			// step=3 (읍면동): 정확한 읍면동 검색
			else {
				$conditions[] = "$column LIKE '%{$escaped_addr}%'";
			}
		}

		return !empty($conditions) ? " (" . implode(" OR ", $conditions) . ") " : "";
	}

	# 멀티 조건 만들기
	function getMultiAnd2025($arry, $col_name)
	{
		$arr_category = array_filter(explode("^", $arry)); // 빈 값 제거
		$count = count($arr_category);

		$where_category = "";
		if ($count > 0) {
			$where_category .= " and (";

			// 1. 컬럼이 공란인 경우
			$where_category .= " {$col_name} = '' ";

			// 2. 모든 선택지가 다 포함된 경우도 무조건 포함 (like '%월%' and ... )
			$all_included = [];
			foreach ($arr_category as $val) {
				$escaped = addslashes(trim($val));
				$all_included[] = "{$col_name} like '%{$escaped}%'";
			}
			$where_category .= " or (" . implode(" and ", $all_included) . ")";
			/*
        // 3. 일부만 포함되더라도 포함
        foreach ($arr_category as $val) {
            $escaped = addslashes(trim($val));
            $where_category .= " or {$col_name} like '%{$escaped}%'";
        }
*/
			$where_category .= " )";
		}

		return $where_category;
	}

	# 멀티 조건 만들기(일부포함)
	function getMultiAndAll2025($arry, $col_name)
	{
		$arr_category = array_filter(explode("^", $arry)); // 빈 값 제거
		$count = count($arr_category);

		$where_category = "";
		if ($count > 0) {
			$where_category .= " and (";

			// 1. 컬럼이 공란인 경우
			$where_category .= " {$col_name} = '' ";

			// 2. 모든 선택지가 다 포함된 경우도 무조건 포함 (like '%월%' and ... )
			$all_included = [];
			foreach ($arr_category as $val) {
				$escaped = addslashes(trim($val));
				$all_included[] = "{$col_name} like '%{$escaped}%'";
			}
			$where_category .= " or (" . implode(" and ", $all_included) . ")";

			// 3. 일부만 포함되더라도 포함
			foreach ($arr_category as $val) {
				$escaped = addslashes(trim($val));
				$where_category .= " or {$col_name} like '%{$escaped}%'";
			}

			$where_category .= " )";
		}

		return $where_category;
	}

	function getMultiAnd($arry, $col_name)
	{
		$arr_category = explode("^", $arry);

		$where_category = "";
		if (count($arr_category) > 0) {
			$where_category .= " and (";
			foreach ($arr_category as $key => $row) {
				if ($key == 0) {
					$where_category .= " $col_name like '%" . $row . "%' ";
				} else {
					$where_category .= " or $col_name like '%" . $row . "%' ";
				}
			}
			$where_category .= " )";
		}
		return $where_category;
	}

	function getMultiAndAge($arry, $col_name)
	{
		$arr_category = explode("^", $arry);

		$where_category = "";
		if (count($arr_category) > 0) {
			$where_category .= " and (";
			foreach ($arr_category as $key => $row) {
				if ($key == 0) {
					$where_category .= " $col_name like '" . substr($row, 0, 1) . "%' ";
				} else {
					$where_category .= " or $col_name like '" . substr($row, 0, 1) . "%' ";
				}
			}
			$where_category .= " )";
		}
		return $where_category;
	}


	# 마이페이지 북마크 : 즐겨찾기 이력서 리스트
	function getBookMarkResumeList($params)
	{
		//컬럼
		$cols = [
			"bb.*",
			"bk.bk_idx",
			"bk.reg_dt as bk_reg_dt",
			"aa.mm_name",
			"aa.mm_id",
			"aa.mm_phone",
			"aa.mm_nation",
			"aa.mm_birth",
			"aa.mm_thumb",
			"aa.mm_sex",
			"mem.mm_name as mem_mm_name",
			"mem.mm_comp_name as mem_mm_comp_name",
			"mem.mm_id as mem_mm_id",
			"mem.mm_phone as mem_mm_phone",
			"mi.ma_age",
			"mi.ma_sex",
			"(SELECT ad_addr FROM st_address WHERE mm_idx = aa.mm_idx AND ad_type = 'RESUME' AND ad_key = bb.ru_idx LIMIT 1) as ad_addr",  // 서브쿼리로 주소 하나만 가져오기 (중복 방지)
			"(SELECT pm_idx FROM st_promotion WHERE rc_idx = bb.ru_idx AND pm_code = 'M001' AND pm_status = 'Y' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
            inner join st_bookmark bk
                on bk.bk_key = bb.ru_idx and bk.bk_type = 'RESUME'
            inner join st_member mem
            	on bk.mm_idx = mem.mm_idx
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and bk.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		//정렬
		$order_by = "bk.bk_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 마이페이지 북마크 : 지원받은 공고
	function getBookMarkRequestResumeList($params)
	{
		//컬럼
		$cols = [
			"bb.*",
			"mq.mq_idx",
			"mq.reg_dt as mq_reg_dt",
			"'지원중' as mq_status",
			"aa.mm_name",
			"aa.mm_id",
			"aa.mm_phone",
			"aa.mm_nation",
			"aa.mm_birth",
			"aa.mm_sex",
			"aa.mm_thumb",
			"mi.ma_age",
			"ad.ad_addr",

			"aa.mm_name as target_name",
			"rcmm.mm_comp_name as user_name",
			"bb.ru_img1 as target_img",
			"rc.rc_img1 as user_img",
			"aa.mm_id as target_id",
			"rcmm.mm_id as user_id",
			"rc.rc_idx",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"mq_status as mq_status_val",
			"mq.mq_memo",
			"rc.rc_name",
			"(SELECT ifnull(max(vw.reg_dt),'') FROM st_member_view vw
    				 WHERE vw.mv_type='resume'
    				 AND   mq.mq_type = 'RECRUIT'
   				 AND   mq.rc_idx = rc.rc_idx
    				 AND   vw.mv_key = mq.ru_idx
            AND   vw.mm_idx = aa.mm_idx
            and vw.mm_idx = rc.mm_idx) view_dt",
			"(SELECT pm_idx FROM st_promotion WHERE rc_idx = bb.ru_idx AND pm_code = 'M001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx"
		];

		//바디
		$body = "
			st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join st_address ad
                on aa.mm_idx = ad.mm_idx and ad.ad_type = 'RESUME' and ad.ad_key = bb.ru_idx
            inner join st_member_request mq
                on mq.ru_idx = bb.ru_idx 
            inner join st_recruit rc
                on mq.rc_idx = rc.rc_idx
            inner join st_member rcmm
                on rc.mm_idx = rcmm.mm_idx
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];
		//$where[] = 	" and aa.mm_status = 'Y' ";
		
		// mq_type 조건: RECRUIT이거나, RESUME이면서 mm_idx가 다른 경우
		$where[] = " and (mq.mq_type = 'RECRUIT' OR (mq.mq_type = 'RESUME' AND mq.mm_idx != rc.mm_idx)) ";
		// 지원취소된 항목 제외 (mq_status가 'cancel'이 아닌 것만)
		$where[] = " and (mq.mq_status IS NULL OR mq.mq_status != 'cancel') ";

		if (isset($params["gbn"]) && $params["gbn"] != "") {
			if ($params["gbn"] == "view") {
				$where[] = 	" AND bb.ru_idx     IN(  SELECT vw.mv_key FROM st_member_view vw, st_member_request req 				 WHERE vw.mv_type='resume' 				 AND   req.mq_type = 'RECRUIT'				 AND   req.rc_idx = rc.rc_idx 				 AND   vw.mv_key = req.ru_idx 				 AND   vw.mm_idx = rc.mm_idx) ";
			}
			if ($params["gbn"] == "noview") {
				$where[] = 	" AND bb.ru_idx NOT IN(  SELECT vw.mv_key FROM st_member_view vw, st_member_request req 				 WHERE vw.mv_type='resume' 				 AND   req.mq_type = 'RECRUIT'				 AND   req.rc_idx = rc.rc_idx 				 AND   vw.mv_key = req.ru_idx 				 AND   vw.mm_idx = rc.mm_idx) ";
			}
			if ($params["gbn"] == "yet") {
				$where[] = 	" AND bb.ru_idx NOT IN(  SELECT vw.mv_key FROM st_member_view vw, st_member_request req 				 WHERE vw.mv_type='resume' 				 AND   req.mq_type = 'RECRUIT'				 AND   req.rc_idx = rc.rc_idx 				 AND   vw.mv_key = req.ru_idx 				 AND   vw.mm_idx = rc.mm_idx) ";
				$where[] = 	" and (mq.mq_status not in('pass','fail') or mq.mq_status is null ) ";
			}
		}


		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and rc.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["rc_idx"]) && $params["rc_idx"] != "") {
			$where[] = 	" and mq.rc_idx = '" . $this->inject($params["rc_idx"]) . "' ";
		}

		if (isset($params["mq_status"]) && $params["mq_status"] != "") {
			$where[] = 	" and mq.mq_status = '" . $this->inject($params["mq_status"]) . "' ";
		}

		//정렬
		$order_by = "mq.mq_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}


	# 마이페이지 북마크 : 즐겨찾기 이력서 리스트
	function getBookMarkRecruitList($params)
	{
		//컬럼
		$cols = [
			"bb.*",
			"cc.ad_addr",
			"bk.bk_idx",
			"bk.reg_dt as bk_reg_dt",
			"aa.mm_name",
			"aa.mm_comp_name",
			"aa.mm_id",
			"aa.mm_phone",
			"mem.mm_name as mem_mm_name",
			"mem.mm_comp_name as mem_mm_comp_name",
			"mem.mm_id as mem_mm_id",
			"mem.mm_phone as mem_mm_phone",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '대',' ~ ',rc_eage,'대')
				when rc_sage <> '' then concat(rc_sage, '세 ~')
				when rc_eage <> '' then concat('~', rc_eage, '대')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"

		];

		//바디
		$body = "
            st_member aa
            inner join st_recruit bb
                on aa.mm_idx = bb.mm_idx
            inner join st_bookmark bk
                on bk.bk_key = bb.rc_idx and bk.bk_type = 'RECRUIT'
            inner join st_member mem
            	on bk.mm_idx = mem.mm_idx
            left join st_address cc
                on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
            left join st_bookmark dd
                on bb.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and bk.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["search_type"]) && isset($params["search_value"])) {
			if ($params["search_value"] != "") {
				if ($params["search_type"] == "") {
					$where[] = 	" and (mem.mm_id like '%" . $this->inject($params["search_value"]) . "%'
				 				  or bb.rc_name like '%" . $this->inject($params["search_value"]) . "%'
	                              or mem.mm_name like '%" . $this->inject($params["search_value"]) . "%') ";
				} else if ($params["search_type"] == "mem_id") {
					$where[] = 	" and (mem.mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "mem_name") {
					$where[] = 	" and (mem.mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "rc_name") {
					$where[] = 	" and (bb.rc_name like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "ad_addr") {
					$where[] = 	" and (ad.ad_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
				}
			}
		}

		//정렬
		$order_by = "bk.bk_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 마이페이지 북마크 : 제안받은 공고
	function getBookMarkRequestRecruitList($params)
	{
		//컬럼
		$cols = [
			"cc.*",
			"bb.mq_idx",
			"bb.reg_dt as req_reg_dt",
			"'지원중' as req_status",
			"ee.ad_addr",
			"dd.mm_name",
			"dd.mm_id",
			"dd.mm_phone",
			"ff.bk_idx",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when ff.bk_idx is not null then 'Y' else 'N' end) as book_yn"

		];

		//바디
		$body = "
            st_resume aa
			inner join st_member_request bb
		    	on aa.ru_idx = bb.ru_idx and bb.mq_type = 'RESUME'
		    inner join st_recruit cc
		    	on bb.rc_idx = cc.rc_idx
		    inner join st_member dd
		    	on cc.mm_idx = dd.mm_idx
		    left join st_address ee
		        on dd.mm_idx = ee.mm_idx and ee.ad_type = 'RECRUIT' and ee.ad_key = cc.rc_idx
			left join st_bookmark ff
				on cc.rc_idx = ff.bk_key and ff.bk_type = 'RECRUIT' and ff.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		# 조건
		$where = [];
		$where[] = 	" and dd.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["ru_idx"]) && $params["ru_idx"] != "") {
			$where[] = 	" and aa.ru_idx = '" . $this->inject($params["ru_idx"]) . "' ";
		}



		//정렬
		$order_by = "bb.mq_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}


	# 마이페이지 북마크 : 지원중인 공고
	function getMemRequestRecruitList($params)
	{
		//컬럼
		$cols = [
			"cc.*",
			"ee.ad_addr",
			"bb.mq_idx",
			"bb.reg_dt as req_reg_dt",
			"'지원중' as req_status",
			"bb.mq_idx",
			"aa.ru_name",
			"aa.ru_idx",
			"dd.mm_name",
			"dd.mm_id",
			"dd.mm_phone",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '무관' end) as rc_age_nm",
			"(case when ff.bk_idx is not null then 'Y' else 'N' end) as book_yn"

		];

		//바디
		$body = "
            st_resume aa
			inner join st_member_request bb
		    	on aa.ru_idx = bb.ru_idx and bb.mq_type = 'RECRUIT'
		    inner join st_recruit cc
		    	on bb.rc_idx = cc.rc_idx
		    inner join st_member dd
		    	on cc.mm_idx = dd.mm_idx
		    left join st_address ee
		        on dd.mm_idx = ee.mm_idx and ee.ad_type = 'RECRUIT' and ee.ad_key = cc.rc_idx
			left join st_bookmark ff
				on cc.rc_idx = ff.bk_key and ff.bk_type = 'RECRUIT' and ff.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		# 조건
		$where = [];
		$where[] = 	" and dd.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["rc_idx"]) && $params["rc_idx"] != "") {
			$where[] = 	" and bb.rc_idx = '" . $this->inject($params["rc_idx"]) . "' ";
		}

		if (isset($params["ru_idx"]) && $params["ru_idx"] != "") {
			$where[] = 	" and bb.ru_idx = '" . $this->inject($params["ru_idx"]) . "' ";
		}

		//정렬
		$order_by = "bb.mq_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 마이페이지 북마크 : 중고상품 블겨찾기
	function getBookMarkTradeList($params)
	{
		//컬럼
		$cols = [
			"aa.*
				,(case when cc.bk_idx is not null then 'Y' else 'N' end) as book_yn
                ,group_concat(bb.ad_addr) as ad_addr"
		];

		//바디
		$body = "
                st_trade aa
                inner join st_bookmark bk
                    on aa.td_idx = bk.bk_key and bk.bk_type = 'TRADE'
                left join st_bookmark cc
                    on aa.td_idx = cc.bk_key and cc.bk_type = 'TRADE' and cc.mm_idx = '$mm_idx'
                left join st_address bb
					on aa.td_idx = bb.ad_key and bb.ad_type = 'TRADE'
		";

		# 조건
		$where = [];
		$where[] = 	" and bk.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		//정렬
		$order_by = "bk.bk_idx desc";
		$group_by = "group by aa.td_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}


	# 채팅방 : 지원한 공고에서
	// 연결된 채용공고에 지원한 이력서 목록을 반환
	function getRequestRecruitList($params)
	{
		/*
		//컬럼
		$cols = [
			"bb.*",
      "aa.mm_name as target_name",
      "rcmm.mm_comp_name as user_name",
			"bb.ru_img1 as target_img",
			"rc.rc_img1 as user_img",
      "aa.mm_name",
      "aa.mm_nation",
      "aa.mm_id as target_id",
      "rcmm.mm_id as user_id",
      "mi.ma_age",
      "ad.ad_addr",
      "rc.rc_idx",
      "rc.rc_name",
      "(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
      //"(SELECT min(rm_idx) FROM st_room WHERE mm_idx <> bb.mm_idx AND rc_idx = rc.rc_idx AND ru_idx = bb.ru_idx) rm_idx,"
      "room.rm_idx",
		];

		//바디
		$body = " / * membermodel.getRequestRecruitList querylog * /
			st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
            inner join st_member_request mq
                on mq.ru_idx = bb.ru_idx 
            inner join st_recruit rc
                on mq.rc_idx = rc.rc_idx
            inner join st_member rcmm
                on rc.mm_idx = rcmm.mm_idx
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '".$this->inject($params["mm_idx"])."'
            left join st_address ad
                on aa.mm_idx = ad.mm_idx and ad.ad_type = 'MEMBER'
            LEFT JOIN st_room room
    		        ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx
		";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";
		// mq_type 조건: RECRUIT이거나, RESUME이면서 mm_idx가 다른 경우
		$where[] = " and (mq.mq_type = 'RECRUIT' OR (mq.mq_type = 'RESUME' AND mq.mm_idx != rc.mm_idx)) ";
    $where[] = 	" and rc.mm_idx = '".$this->inject($params["mm_idx"])."' ";

    if($params["ru_idx"]!=""){
      $where[] = 	" and mq.rc_idx = '".$params["ru_idx"]."' ";
    }
		//정렬
		$order_by = "mq.mq_idx desc";

 		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
    */
		$sql = "SELECT ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
       ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
       ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
       ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
       user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt, MAX(mq_apply_idx) mq_apply_idx, MAX(mq_apply_dt) mq_apply_dt
FROM (
 SELECT
                         bb.*,
 aa.mm_name AS target_name,
 rcmm.mm_comp_name AS user_name,
 bb.ru_img1 AS target_img,
 rc.rc_img1 AS user_img,
 aa.mm_name,
 aa.mm_nation,
 aa.mm_sex,
 aa.mm_thumb,
 aa.mm_id AS target_id,
 rcmm.mm_id AS user_id,
 mi.ma_age,
 ad.ad_addr,
 rc.rc_idx,
 rc.rc_name,
 (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
 p.pm_idx,
 mq.mq_idx AS mq_apply_idx, mq.reg_dt AS mq_apply_dt,
 room.rm_idx, room.up_dt rm_up_dt
                         FROM
                        st_member aa
            LEFT JOIN st_member_info mi
                ON aa.mm_idx = mi.mm_idx
                        INNER JOIN st_resume bb
                                ON aa.mm_idx = bb.mm_idx
            INNER JOIN st_member_request mq
                ON mq.ru_idx = bb.ru_idx
                AND (
                    mq.mq_type = 'RECRUIT'
                    OR (mq.mq_type = 'RESUME' AND mq.mm_idx != '" . $this->inject($params["mm_idx"]) . "')
                )
            INNER JOIN st_recruit rc
                ON mq.rc_idx = rc.rc_idx
            INNER JOIN st_member rcmm
                ON rc.mm_idx = rcmm.mm_idx
            LEFT JOIN st_bookmark dd
                ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
            LEFT JOIN st_address ad
                ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
            LEFT JOIN st_promotion p
                ON p.pm_code = 'M001' AND p.pm_sdate <= NOW() AND p.pm_edate >= NOW() AND p.rc_idx = bb.ru_idx
            LEFT JOIN st_room room
                        ON room.rm_idx = mq.rm_idx
                         WHERE 1=1
  AND aa.mm_status = 'Y'
  AND rc.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		if ($params['ru_idx'] != "") {
			$sql .= 	" and mq.rc_idx = '" . $params["ru_idx"] . "' ";
		}
		$sql .= "                ORDER BY mq.mq_idx DESC
) innertable
GROUP BY
       ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
       ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
       ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
       ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
       user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, mm_idx
order by rm_up_dt desc
        LIMIT 0 , 200 ";

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = 200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}

	# 배치 버전: 여러 공고를 한 번에 조회
	function getRequestRecruitListBatch($params)
	{
		$sql = "SELECT ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
       ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
       ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
       ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
       user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt, MAX(mq_apply_idx) mq_apply_idx, MAX(mq_apply_dt) mq_apply_dt
FROM (
 SELECT
                         bb.*,
 aa.mm_name AS target_name,
 rcmm.mm_comp_name AS user_name,
 bb.ru_img1 AS target_img,
 rc.rc_img1 AS user_img,
 aa.mm_name,
 aa.mm_nation,
 aa.mm_sex,
 aa.mm_thumb,
 aa.mm_id AS target_id,
 rcmm.mm_id AS user_id,
 mi.ma_age,
 ad.ad_addr,
 rc.rc_idx,
 rc.rc_name,
 (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
 p.pm_idx,
 mq.mq_idx AS mq_apply_idx, mq.reg_dt AS mq_apply_dt,
 room.rm_idx, room.up_dt rm_up_dt
                         FROM
                        st_member aa
            LEFT JOIN st_member_info mi
                ON aa.mm_idx = mi.mm_idx
                        INNER JOIN st_resume bb
                                ON aa.mm_idx = bb.mm_idx
            INNER JOIN st_member_request mq
                ON mq.ru_idx = bb.ru_idx
                AND (
                    mq.mq_type = 'RECRUIT'
                    OR (mq.mq_type = 'RESUME' AND mq.mm_idx != '" . $this->inject($params["mm_idx"]) . "')
                )
            INNER JOIN st_recruit rc
                ON mq.rc_idx = rc.rc_idx
            INNER JOIN st_member rcmm
                ON rc.mm_idx = rcmm.mm_idx
            LEFT JOIN st_bookmark dd
                ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
            LEFT JOIN st_address ad
                ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
            LEFT JOIN st_promotion p
                ON p.pm_code = 'M001' AND p.pm_sdate <= NOW() AND p.pm_edate >= NOW() AND p.rc_idx = bb.ru_idx
            LEFT JOIN st_room room
                        ON room.rm_idx = mq.rm_idx
                         WHERE 1=1
  AND aa.mm_status = 'Y'
  AND rc.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		if (!empty($params['ru_idx_list']) && is_array($params['ru_idx_list'])) {
			$ru_idx_list = array_filter(array_map('intval', $params['ru_idx_list']));
			if (!empty($ru_idx_list)) {
				$sql .= " and mq.rc_idx IN (" . implode(',', $ru_idx_list) . ") ";
			}
		}

		$sql .= "                ORDER BY mq.mq_idx DESC
) innertable
GROUP BY
       ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
       ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
       ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
       ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
       user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, mm_idx
order by rm_up_dt desc
        LIMIT 0 , 200 ";

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = 200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}

	# 채팅방 : 제안한 공고에서
	function getRequestRecruitListSended($params)
	{
		$sql = "SELECT ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
     ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
     ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
     ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
     user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt, MAX(mq_apply_idx) mq_apply_idx, MAX(mq_apply_dt) mq_apply_dt
      FROM (
          SELECT bb.*,
              aa.mm_name AS target_name,
              rcmm.mm_comp_name AS user_name,
              bb.ru_img1 AS target_img,
              rc.rc_img1 AS user_img,
              aa.mm_name,
              aa.mm_nation,
              aa.mm_sex,
              aa.mm_thumb,
              aa.mm_id AS target_id,
              rcmm.mm_id AS user_id,
              mi.ma_age,
              ad.ad_addr,
              rc.rc_idx,
              rc.rc_name,
              (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
              p.pm_idx,
              room.rm_idx, room.up_dt rm_up_dt, mq.mq_idx AS mq_apply_idx, mq.reg_dt AS mq_apply_dt
          FROM st_member aa
          LEFT JOIN st_member_info mi
              ON aa.mm_idx = mi.mm_idx
          INNER JOIN st_resume bb
              ON aa.mm_idx = bb.mm_idx
          INNER JOIN st_member_request mq
              ON mq.mq_type = 'RESUME'
              and mq.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
              and mq.rc_idx = '" . $params["ru_idx"] . "'
              and bb.ru_idx = mq.ru_idx
          INNER JOIN st_recruit rc
              ON mq.rc_idx = rc.rc_idx
          INNER JOIN st_member rcmm
              ON rc.mm_idx = rcmm.mm_idx
          LEFT JOIN st_bookmark dd
              ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
          LEFT JOIN st_address ad
              ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
          LEFT JOIN st_promotion p
              ON p.pm_code = 'M001' AND p.pm_sdate <= NOW() AND p.pm_edate >= NOW() AND p.rc_idx = bb.ru_idx
          LEFT JOIN st_room room
              ON room.rm_idx = mq.rm_idx
          WHERE 1=1
          AND aa.mm_status = 'Y'
          and mq.rc_idx = rc.rc_idx
          AND rc.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		if ($params['ru_idx'] != "") { //ru 로 받았지만, rc임
			$sql .= 	" and mq.rc_idx = '" . $params["ru_idx"] . "' ";
		}
		$sql .= "                ORDER BY mq.mq_idx DESC
          ) innertable
          GROUP BY
               ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
               ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
               ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
               ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
               user_name, target_img, user_img, mm_name, mm_nation, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, mq_apply_idx, mq_apply_dt
          order by rm_up_dt desc
          LIMIT 0 , 200 ";

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = 200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}

	# 배치 버전: 여러 공고의 지원한 인재를 한 번에 조회
	function getRequestRecruitListSendedBatch($params)
	{
		$sql = "SELECT ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
     ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
     ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
     ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
     user_name, target_img, user_img, mm_name, mm_nation, mm_sex, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt, MAX(mq_apply_idx) mq_apply_idx, MAX(mq_apply_dt) mq_apply_dt
      FROM (
          SELECT bb.*,
              aa.mm_name AS target_name,
              rcmm.mm_comp_name AS user_name,
              bb.ru_img1 AS target_img,
              rc.rc_img1 AS user_img,
              aa.mm_name,
              aa.mm_nation,
              aa.mm_sex,
              aa.mm_thumb,
              aa.mm_id AS target_id,
              rcmm.mm_id AS user_id,
              mi.ma_age,
              ad.ad_addr,
              rc.rc_idx,
              rc.rc_name,
              (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
              p.pm_idx,
              room.rm_idx, room.up_dt rm_up_dt, mq.mq_idx AS mq_apply_idx, mq.reg_dt AS mq_apply_dt
          FROM st_member aa
          LEFT JOIN st_member_info mi
              ON aa.mm_idx = mi.mm_idx
          INNER JOIN st_resume bb
              ON aa.mm_idx = bb.mm_idx
          INNER JOIN st_member_request mq
              ON mq.mq_type = 'RESUME'
              and mq.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
              and bb.ru_idx = mq.ru_idx
          INNER JOIN st_recruit rc
              ON mq.rc_idx = rc.rc_idx
          INNER JOIN st_member rcmm
              ON rc.mm_idx = rcmm.mm_idx
          LEFT JOIN st_bookmark dd
              ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
          LEFT JOIN st_address ad
              ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
          LEFT JOIN st_promotion p
              ON p.pm_code = 'M001' AND p.pm_sdate <= NOW() AND p.pm_edate >= NOW() AND p.rc_idx = bb.ru_idx
          LEFT JOIN st_room room
              ON room.rm_idx = mq.rm_idx
          WHERE 1=1
          AND aa.mm_status = 'Y'
          and mq.rc_idx = rc.rc_idx
          AND rc.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		if (!empty($params['ru_idx_list']) && is_array($params['ru_idx_list'])) {
			$ru_idx_list = array_filter(array_map('intval', $params['ru_idx_list']));
			if (!empty($ru_idx_list)) {
				$sql .= " and mq.rc_idx IN (" . implode(',', $ru_idx_list) . ") ";
			}
		}

		$sql .= "                ORDER BY mq.mq_idx DESC
          ) innertable
          GROUP BY
               ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ru_price_type,
               ru_month_price, ru_work_type, ru_week_yn,ru_week, ru_work_dt, ru_edu, ru_my_type,
               ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5,
               ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name,
               user_name, target_img, user_img, mm_name, mm_nation, mm_thumb, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, pm_idx, mq_apply_idx, mq_apply_dt
          order by rm_up_dt desc
          LIMIT 0 , 200 ";

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = 200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}


	/* //2024.07.15
  // 제안 보낼때, 공고가 없어도 되나 하고 없는 함수를 만들었지만,
  // 있어야 할것 같아서, 기본 공고를 넣도록 하고 함수를 복원함
  # 제안보낸 인재
function getRequestRecruitList2($params){
  //컬럼
  $cols = [
    "bb.*",
    "aa.mm_name as target_name",
//    "rcmm.mm_comp_name as user_name",
    "bb.ru_img1 as target_img",
//    "rc.rc_img1 as user_img",
    "aa.mm_name",
    "aa.mm_nation",
    "aa.mm_id as target_id",
//    "rcmm.mm_id as user_id",
    "mi.ma_age",
    "ad.ad_addr",
//    "rc.rc_idx",
//    "rc.rc_name",
    "(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
    //"(SELECT min(rm_idx) FROM st_room WHERE mm_idx <> bb.mm_idx AND rc_idx = rc.rc_idx AND ru_idx = bb.ru_idx) rm_idx,"
//    "room.rm_idx",
  ];

  //바디
  $body = " / * membermodel.getRequestRecruitList2 queryxlog * /
    st_member aa
          inner join st_member_info mi
              on aa.mm_idx = mi.mm_idx
    inner join st_resume bb
      on aa.mm_idx = bb.mm_idx
          inner join st_member_request mq
              on mq.ru_idx = bb.ru_idx and mq.mq_type = 'RESUME'
--          inner join st_recruit rc
--              on mq.rc_idx = rc.rc_idx
--          inner join st_member rcmm
--              on rc.mm_idx = rcmm.mm_idx
          left join st_bookmark dd
              on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '".$this->inject($params["mm_idx"])."'
          left join st_address ad
              on aa.mm_idx = ad.mm_idx and ad.ad_type = 'MEMBER'
--          LEFT JOIN st_room room
--              ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx
  ";

  # 조건
  $where = [];
  $where[] = 	" and aa.mm_status = 'Y' ";
  $where[] = 	" and mq.mm_idx = '".$this->inject($params["mm_idx"])."' ";

  if($params["ru_idx"]!=""){
    $where[] = 	" and mq.rc_idx = '".$params["ru_idx"]."' ";
  }
  //정렬
  $order_by = "mq.mq_idx desc";

  return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
}
*/

	//2024.07.15 채팅 수정 중 rc_idx가 필수 조인이 아니어서 주석 처리하고 위에 새로 생성
	//     특정 공고를 지정하지 않고도, 제안된 모든 인원 조회 하도록 하기 위해 --> 다시 살림
	# 제안보낸 인재
	function getRequestRecruitList2($params)
	{
		//컬럼
		$cols = [
			"bb.*",
			"aa.mm_name as target_name",
			"rcmm.mm_comp_name as user_name",
			"bb.ru_img1 as target_img",
			"rc.rc_img1 as user_img",
			"aa.mm_name",
			"aa.mm_nation",
			"aa.mm_sex",
			"aa.mm_id as target_id",
			"rcmm.mm_id as user_id",
			"mi.ma_age",
			"ad.ad_addr",
			"rc.rc_idx",
			"rc.rc_name",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			//"(SELECT min(rm_idx) FROM st_room WHERE mm_idx <> bb.mm_idx AND rc_idx = rc.rc_idx AND ru_idx = bb.ru_idx) rm_idx,"
			"room.rm_idx",
		];

		//바디
		$body = " /* membermodel.getRequestRecruitList2 queryxlog */
  st_member aa
        inner join st_member_info mi
            on aa.mm_idx = mi.mm_idx
  inner join st_resume bb
    on aa.mm_idx = bb.mm_idx
        inner join st_member_request mq
            on mq.ru_idx = bb.ru_idx and mq.mq_type = 'RESUME'
        inner join st_recruit rc
            on mq.rc_idx = rc.rc_idx
        inner join st_member rcmm
            on rc.mm_idx = rcmm.mm_idx
        left join st_bookmark dd
            on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
        left join st_address ad
            on aa.mm_idx = ad.mm_idx and ad.ad_type = 'RESUME' and ad.ad_key = bb.ru_idx
        LEFT JOIN st_room room
            ON room.rm_idx = mq.rm_idx
";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";
		$where[] = 	" and mq.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		if ($params["ru_idx"] != "") {
			$where[] = 	" and mq.rc_idx = '" . $params["ru_idx"] . "' ";
		}
		//정렬
		$order_by = "mq.mq_idx desc";

		//return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);

		$sql = "SELECT ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ";
		$sql .= " ru_price_type, ru_month_price, ru_work_type, ru_week_yn, ru_week, ";
		$sql .= " ru_work_dt, ru_edu, ru_my_type, ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5, ";
		$sql .= " ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name, ";
		$sql .= " user_name, target_img, user_img, mm_name, mm_nation, mm_birth, mm_sex, mm_thumb, ";
		$sql .= " target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt, ";
		$sql .= " (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = innertable.rc_idx AND reg_dt <> up_dt) AS max_up_dt ";
		$sql .= " FROM ( ";
		$sql .= " SELECT  ";
		$sql .= "                         bb.*, ";
		$sql .= " aa.mm_name AS target_name, ";
		$sql .= " rcmm.mm_comp_name AS user_name, ";
		$sql .= " bb.ru_img1 AS target_img, ";
		$sql .= " rc.rc_img1 AS user_img, ";
		$sql .= " aa.mm_name, ";
		$sql .= " aa.mm_nation, ";
		$sql .= " aa.mm_birth, ";
		$sql .= " aa.mm_sex, ";
		$sql .= " aa.mm_thumb, ";
		$sql .= " aa.mm_id AS target_id, ";
		$sql .= " rcmm.mm_id AS user_id, ";
		$sql .= " mi.ma_age, ";
		$sql .= " ad.ad_addr, ";
		$sql .= " rc.rc_idx, ";
		$sql .= " rc.rc_name, ";
		$sql .= " (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn, ";
		$sql .= " room.rm_idx, room.up_dt rm_up_dt ";
		$sql .= "                         FROM   ";
		$sql .= "  st_member aa ";
		$sql .= "        LEFT JOIN st_member_info mi ";
		$sql .= "            ON aa.mm_idx = mi.mm_idx ";
		$sql .= "  INNER JOIN st_resume bb ";
		$sql .= "    ON aa.mm_idx = bb.mm_idx ";
		$sql .= "        INNER JOIN st_member_request mq ";
		$sql .= "            ON mq.ru_idx = bb.ru_idx AND mq.mq_type = 'RESUME' ";
		$sql .= "        INNER JOIN st_recruit rc ";
		$sql .= "            ON mq.rc_idx = rc.rc_idx ";
		$sql .= "        INNER JOIN st_member rcmm ";
		$sql .= "            ON rc.mm_idx = rcmm.mm_idx ";
		$sql .= "        LEFT JOIN st_bookmark dd ";
		$sql .= "            ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' ";
		$sql .= "  AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$sql .= "  LEFT JOIN st_address ad ";
		$sql .= "            ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'RESUME' AND ad.ad_key = bb.ru_idx ";
		$sql .= "        LEFT JOIN st_room room ";
		$sql .= "            ON room.rm_idx = mq.rm_idx ";
		$sql .= "                 WHERE 1=1 ";
		$sql .= "  AND aa.mm_status = 'Y' ";
		$sql .= "  AND mq.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";

		if ($params['ru_idx'] != "") {
			$sql .= 	" and mq.rc_idx = '" . $params["ru_idx"] . "' ";
		}
		$sql .= "                 ORDER BY mq.mq_idx DESC ";
		$sql .= "                         LIMIT 0 , 200 ";
		$sql .= " ) innertable ";
		$sql .= " GROUP BY  ";
		$sql .= "       ru_idx, mm_idx, ru_name, ru_show_yn, ru_work, ru_work_img, ru_addr, ";
		$sql .= "       ru_price_type, ru_month_price, ru_work_type, ru_week_yn, ru_week, ";
		$sql .= "       ru_work_dt, ru_edu, ru_my_type, ru_desc, ru_thumb, ru_img1, ru_img2, ru_img3, ru_img4, ru_img5, ";
		$sql .= "       ru_ip, ru_status, stop_desc, stop_dt, ru_hit, reg_dt, up_dt, target_name, ";
		$sql .= "       user_name, target_img, user_img, mm_name, mm_nation, mm_birth, ";
		$sql .= "       target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn ";
		$sql .= " ORDER BY max_up_dt desc, rm_up_dt desc ";

		//$query = $this->db->query($sql);
		//return $query->row_array();

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = count($list); //200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}


	/* //2024.07.15 특정 공고가 연결되지 않는것 같아서
   // 공고 연결하지 않도록 수정한 쿼리
   // 지정하도록 하는게 맞아서 주석 처리
# 제안받은 공고
function getRequestRecruitList2Mem($params){
    //컬럼
    //"bb.*",
    $cols = [
//      "rc.*",
      "mq.reg_dt",
      "aa.mm_name as target_name",
//      "rcmm.mm_comp_name as user_name",
      "bb.ru_img1 as target_img",
//      "rc.rc_img1 as user_img",
      "aa.mm_name",
      "aa.mm_nation",
      "aa.mm_id as target_id",
//      "rcmm.mm_id as user_id",
      "mi.ma_age",
      "ad.ad_addr",
//      "rc.rc_idx",
//      "rc.rc_name",
      "(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
      //"(SELECT min(rm_idx) FROM st_room WHERE mm_idx <> bb.mm_idx AND rc_idx = rc.rc_idx AND ru_idx = bb.ru_idx) rm_idx,"
//      "room.rm_idx",
      "mq.ru_idx"
    ];
    //바디
    $body = " / * membermodel.getRequestRecruitList2Mem querylog * /
      st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
      inner join st_resume bb
        on aa.mm_idx = bb.mm_idx
            inner join st_member_request mq
                on mq.ru_idx = bb.ru_idx and mq.mq_type = 'RESUME'
--            inner join st_recruit rc
--                on mq.rc_idx = rc.rc_idx
--            inner join st_member rcmm
--                on rc.mm_idx = rcmm.mm_idx
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '".$this->inject($params["mm_idx"])."'
            left join st_address ad
                on aa.mm_idx = ad.mm_idx and ad.ad_type = 'MEMBER'
--            LEFT JOIN st_room room
--                ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx
    ";

    # 조건
    $where = [];
    $where[] = 	" and aa.mm_status = 'Y' ";
    $where[] = 	" and bb.mm_idx = '".$this->inject($params["mm_idx"])."' ";

    //if($params["ru_idx"]!=""){
    //  $where[] = 	" and mq.rc_idx = '".$params["ru_idx"]."' ";
    //}
    //정렬
    $order_by = "mq.mq_idx desc";

    return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
}
*/

	//2024.07.15
	//제안받을때 공고 선택이 없어서
	//제안받은 리스트에 공고 지정 없도록 수정 -> 제안보낼때 기본값 넣도록 수정하고 이함수 살림
	# 제안받은 공고
	function getRequestRecruitList2Mem($params)
	{
		//컬럼
		//"bb.*",
		/*
    $cols = [
      "rc.*",
      "mq.reg_dt",
      "aa.mm_name as target_name",
      "rcmm.mm_comp_name as user_name",
      "bb.ru_img1 as target_img",
      "rc.rc_img1 as user_img",
      "aa.mm_name",
      "aa.mm_nation",
      "aa.mm_id as target_id",
      "rcmm.mm_id as user_id",
      "mi.ma_age",
      "ad.ad_addr",
      "rc.rc_idx",
      "rc.rc_name",
      "(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
      //"(SELECT min(rm_idx) FROM st_room WHERE mm_idx <> bb.mm_idx AND rc_idx = rc.rc_idx AND ru_idx = bb.ru_idx) rm_idx,"
      "room.rm_idx",
      "mq.ru_idx"
    ];
    //바디
    $body = " / * membermodel.getRequestRecruitList2Mem querylog * /
      st_member aa
            inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
      inner join st_resume bb
        on aa.mm_idx = bb.mm_idx
            inner join st_member_request mq
                on mq.ru_idx = bb.ru_idx and mq.mq_type = 'RESUME'
            inner join st_recruit rc
                on mq.rc_idx = rc.rc_idx
            inner join st_member rcmm
                on rc.mm_idx = rcmm.mm_idx
            left join st_bookmark dd
                on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '".$this->inject($params["mm_idx"])."'
            left join st_address ad
                on aa.mm_idx = ad.mm_idx and ad.ad_type = 'MEMBER'
            LEFT JOIN st_room room
                ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx
    ";

    # 조건
    $where = [];
    $where[] = 	" and aa.mm_status = 'Y' ";
    $where[] = 	" and bb.mm_idx = '".$this->inject($params["mm_idx"])."' ";

    //if($params["ru_idx"]!=""){
    //  $where[] = 	" and mq.rc_idx = '".$params["ru_idx"]."' ";
    //}
    //정렬
    $order_by = "mq.mq_idx desc";

    return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
    */
		$sql = "SELECT rc_idx, mm_idx, rc_show_yn, rc_name, rc_sdate, rc_edate, rc_nation, rc_sex, rc_sage, rc_eage,
       rc_work, rc_work_type, rc_day_price, rc_week_yn, rc_week,
       rc_am_stime, rc_am_etime, rc_pm_stime, rc_pm_etime, rc_work_dt, rc_price_type, rc_month_price, rc_life_type,
       rc_desc, rc_hash, rc_thumb, rc_img1, rc_img2, rc_img3, rc_img4, rc_img5, rc_status, stop_desc,
       stop_dt, rc_hit, -- reg_dt,
       up_dt, rc_price_rep, mq_reg_dt reg_dt, target_name, user_name, target_img, user_img,
       mm_name, mm_nation, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, ru_idx, MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt,
       IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT rm_idx ORDER BY rm_idx ASC SEPARATOR ', '), ')')
         FROM st_room
         WHERE rc_idx = innertable.rc_idx
         AND ru_idx IN (SELECT ru_idx FROM st_member_request WHERE mq_type = 'RESUME' AND rc_idx = innertable.rc_idx AND mm_idx = '" . $this->inject($params["mm_idx"]) . "')
       ), '()') AS rm_idx_list,
       (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = innertable.rc_idx AND reg_dt <> up_dt) AS max_up_dt
FROM (
SELECT
                         rc.*,
 mq.reg_dt mq_reg_dt,
 aa.mm_name AS target_name,
 rcmm.mm_comp_name AS user_name,
 bb.ru_img1 AS target_img,
 rc.rc_img1 AS user_img,
 aa.mm_name,
 aa.mm_nation,
 aa.mm_id AS target_id,
 rcmm.mm_id AS user_id,
 mi.ma_age,
 ad.ad_addr,
 -- rc.rc_idx,
 -- rc.rc_name,
 (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
 room.rm_idx, room.up_dt rm_up_dt,
 mq.ru_idx, mq.mq_idx
                         FROM
      st_member aa
            LEFT JOIN st_member_info mi
                ON aa.mm_idx = mi.mm_idx
      INNER JOIN st_resume bb
        ON aa.mm_idx = bb.mm_idx
            INNER JOIN st_member_request mq
                ON mq.ru_idx = bb.ru_idx AND mq.mq_type = 'RESUME'
            LEFT JOIN st_room room
                ON room.rm_idx = mq.rm_idx
            INNER JOIN st_recruit rc
                ON rc.rc_idx = COALESCE(room.rc_idx, mq.rc_idx) -- chat_view.php와 동일하게 방에 저장된 rc_idx를 우선 사용 (방 재사용 시 최신 공고명 표시)
            INNER JOIN st_member rcmm
                ON rc.mm_idx = rcmm.mm_idx
            LEFT JOIN st_bookmark dd
                ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
            LEFT JOIN st_address ad
                ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
 WHERE 1=1
  AND aa.mm_status = 'Y'
  AND bb.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
  AND rc.rc_status = 'Y'
  AND rc.rc_edate >= NOW()
  ) innertable
GROUP BY
       rc_idx, mm_idx, rc_show_yn, rc_name, rc_sdate, rc_edate, rc_nation, rc_sex, rc_sage, rc_eage,
       rc_work, rc_work_type, rc_day_price, rc_week_yn, rc_week,
       rc_am_stime, rc_am_etime, rc_pm_stime, rc_pm_etime, rc_work_dt, rc_price_type, rc_month_price, rc_life_type,
       rc_desc, rc_hash, rc_thumb, rc_img1, rc_img2, rc_img3, rc_img4, rc_img5, rc_status, stop_desc,
       stop_dt, rc_hit, reg_dt, up_dt, rc_price_rep, reg_dt, target_name, user_name, target_img, user_img,
       mm_name, mm_nation, target_id, user_id, ma_age, ad_addr, rc_idx, rc_name, book_yn, ru_idx
ORDER BY max_up_dt desc, rm_up_dt DESC, mq_idx DESC
LIMIT 0 , 200
";

		$db_result["query"] = $sql;
		$result = $this->db->query($sql);
		$list = $result->result_array();
		$db_result["list"] = $list;
		$page = 1;
		$db_result["count"] = 200;
		$limit = 200;
		$db_result["pagenation"] = get_page_ajax($page, $db_result["count"], $limit);
		return $db_result;
	}


	# 아웃소싱 리스트
	function getOutSourcingList($comp_num)
	{
		$sql = "
            select *
            FROM st_member
            where mm_type = 'COMP'
            and mm_comp_type = 'COMP'
            and mm_comp_num = '$comp_num'
            order by reg_dt desc
       ";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 북마크 삭제
	function deleteBookmark($where)
	{
		return $this->db_delete("st_bookmark", $where);
	}

	# 광고구매내역 - 개인
	function getAdMemList($params)
	{

		//컬럼
		$cols = [
			"aa.*, m.mm_name, r.ru_name, r.ru_work_dt"
		];

		//바디
		$body = "
			st_promotion aa
      left join st_member m on m.mm_idx = aa.mm_idx
      left join st_resume r on r.ru_idx = aa.rc_idx
		";

		# 조건
		$where = [];

		$where[] = 	" and aa.pm_code in('M001','M002','M003') and aa.pm_status in ('Y') ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (m.mm_name like '%" . $this->inject($params["search_value"]) . "%'
                              or aa.pm_addr like '%" . $this->inject($params["search_value"]) . "%'
                              or r.ru_name like '%" . $this->inject($params["search_value"]) . "%' )  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (m.mm_name like '%" . $this->inject($params["search_value"]) . "%' )  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and (aa.pm_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "ru_name") {
				$where[] = 	" and (r.ru_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["mm_nation"] != "") {
			if ($params["mm_nation"] == "I") {
				//내국인
				$where[] = 	" and (m.mm_nation = '내국인') ";
			} else if ($params["mm_nation"] == "O") {
				//외국인
				$where[] = 	" and (m.mm_nation = '외국인') ";
			} else if ($params["mm_nation"] == "X") {
				//미확인
				$where[] = 	" and (m.mm_nation not in ('내국인','외국인') ) ";
			}
		}

		if ($params["ad_gbn"] == "" || $params["ad_gbn"] == "ing") {
			$where[] = 	" and (aa.pm_sdate <= now() and aa.pm_edate >= now() ) ";
		} else if ($params["ad_gbn"] == "yet") {
			$where[] = 	" and (aa.pm_sdate > now() ) ";
		} else if ($params["ad_gbn"] == "cls") {
			$where[] = 	" and (aa.pm_edate < now() ) ";
		} else if ($params["ad_gbn"] == "all") {
		}

		if ($params["pm_code"] != "") {
			$where[] = 	" and (aa.pm_code = '" . $params["pm_code"] . "') ";
		}

		//정렬
		$order_by = "aa.pm_idx desc /* queryXlog */ ";
		$group_by = "group by aa.pm_idx";

		//error_log("\nad_list params".implode(",",$params), "3", "/testlog/php_log");
		//error_log("ad_list ".implode(",",$where), "3", "/testlog/php_log");

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 광고구매내역 - 기업
	function getAdCompList($params)
	{

		//컬럼
		$cols = [
			"aa.*, m.mm_name, r.rc_name, r.rc_work "
		];

		//바디
		$body = "
			st_promotion aa
      left join st_member m on m.mm_idx = aa.mm_idx
      left join st_recruit r on r.rc_idx = aa.rc_idx
		";

		# 조건
		$where = [];

		$where[] = 	" and aa.pm_code in('C001','C002','C003','C004') and aa.pm_status in ('Y') ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (m.mm_name like '%" . $this->inject($params["search_value"]) . "%'
                              or aa.pm_addr like '%" . $this->inject($params["search_value"]) . "%'
                              or r.rc_name like '%" . $this->inject($params["search_value"]) . "%' )  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (m.mm_name like '%" . $this->inject($params["search_value"]) . "%' )  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and (aa.pm_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "rc_name") {
				$where[] = 	" and (r.rc_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["mm_nation"] != "") {
			if ($params["mm_nation"] == "I") {
				//내국인
				$where[] = 	" and (m.mm_nation = '내국인') ";
			} else if ($params["mm_nation"] == "O") {
				//외국인
				$where[] = 	" and (m.mm_nation = '외국인') ";
			} else if ($params["mm_nation"] == "X") {
				//미확인
				$where[] = 	" and (m.mm_nation not in ('내국인','외국인') ) ";
			}
		}

		if ($params["ad_gbn"] == "" || $params["ad_gbn"] == "ing") {
			$where[] = 	" and (aa.pm_sdate <= now() and aa.pm_edate >= now() ) ";
		} else if ($params["ad_gbn"] == "yet") {
			$where[] = 	" and (aa.pm_sdate > now() ) ";
		} else if ($params["ad_gbn"] == "cls") {
			$where[] = 	" and (aa.pm_edate < now() ) ";
		} else if ($params["ad_gbn"] == "all") {
		}

		if ($params["rc_work"] != "") {
			$where[] = 	" and (r.rc_work like '%" . $params["rc_work"] . "%') ";
		}

		if ($params["pm_code"] != "") {
			$where[] = 	" and (aa.pm_code = '" . $params["pm_code"] . "') ";
		}

		//정렬
		$order_by = "aa.pm_idx desc /* queryXlog */ ";
		$group_by = "group by aa.pm_idx";

		//error_log("\nad_list params".implode(",",$params), "3", "/testlog/php_log");
		//error_log("ad_list ".implode(",",$where), "3", "/testlog/php_log");

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 채팅방 목록 - 공고, 이력서가 모두 오픈상태인
	# 마감된 공고의 채팅방도 목록에 표시되도록 rc_edate 조건 제거
	function getRoomListActaive($mm_idx)
	{

		$sql = "  SELECT rm.rm_idx
              FROM st_room rm
              LEFT JOIN st_recruit rc
                 ON  rc.rc_show_yn = 'Y' AND rc.rc_status = 'Y'
              LEFT JOIN st_resume ru
                 ON  ru.ru_show_yn = 'Y' AND ru.ru_status = 'Y'
              WHERE rm.rc_idx = rc.rc_idx
              AND   rm.ru_idx = ru.ru_idx ";

		//error_log("\n".$sql."\n", "3", "/testlog/php_log");
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# A.I 매칭 리스트 - 기업회원용 (이력서) - 날짜그룹 기준 페이징 조회
	function getMatchResumeListByDate($mm_idx, $page = 1, $day_group_size = 3, $cutoff_days = 14)
	{
		# 1) 최근 cutoff_days 이내 매칭 발송이 있었던 날짜 목록 (알림톡 + SMS, 중복없이, 최신순)
		$sql_dates = "
			SELECT DISTINCT DATE(sent_dt) as sent_date
			FROM (
				SELECT sent_dt FROM st_match_alimtalk_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
				UNION ALL
				SELECT sent_dt FROM st_match_sms_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
			) combined
			GROUP BY DATE(sent_dt)
			ORDER BY sent_date DESC
		";

		$all_dates = $this->db->query($sql_dates, [$mm_idx, $cutoff_days, $mm_idx, $cutoff_days])->result_array();
		$all_dates = array_map(function ($r) { return $r["sent_date"]; }, $all_dates);
       /**
		*		[
				*	"2026-07-07",
				*	"2026-07-06",
				*	"2026-07-04"
				*]

	    */
		$total_dates = count($all_dates);
		$total_pages = $total_dates > 0 ? ceil($total_dates / $day_group_size) : 1;
		$start = ($page - 1) * $day_group_size;
		// 1. 전체 날짜에서 이번 페이지에 해당하는 날짜만 3개 자릅니다. (예: 1페이지)
		$page_dates = array_slice($all_dates, $start, $day_group_size);
        # page_dates: 현재 페이지에 표시할 날짜 배열  $page_dates = ['2026-06-15', '2026-06-14', '2026-06-13']
		$result = [
			"groups" => [],
			"total_dates" => $total_dates,
			"total_pages" => $total_pages,
			"page" => $page,
		];

		if (empty($page_dates)) {
			return $result;
		}

		# 2) 해당 날짜들에 매칭된 이력서 상세 조회 (알림톡 + SMS 합침)
		$placeholders = implode(",", array_fill(0, count($page_dates), "?"));
		$sql_list = "
			SELECT
				s.sent_dt,
				r.*,
				m.mm_nation, m.mm_birth, m.mm_sex, m.mm_thumb,
				COALESCE(addr.ad_addr, r.ru_addr) as ad_addr,
				(case when bk.bk_idx is not null then 'Y' else 'N' end) as book_yn
			FROM (
				SELECT sent_dt, target_key, sender_type, receiver_mm_idx FROM st_match_alimtalk_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND DATE(sent_dt) IN ($placeholders)
				UNION ALL
				SELECT sent_dt, target_key, sender_type, receiver_mm_idx FROM st_match_sms_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND DATE(sent_dt) IN ($placeholders)
			) s
			INNER JOIN st_resume r
				ON r.ru_idx = s.target_key AND r.ru_status = 'Y' AND r.ru_show_yn = 'Y'
			INNER JOIN st_member m
				ON m.mm_idx = r.mm_idx AND m.mm_status = 'Y'
			LEFT JOIN st_address addr
				ON addr.mm_idx = m.mm_idx AND addr.ad_type = 'RESUME' AND addr.ad_key = r.ru_idx
			LEFT JOIN st_bookmark bk
				ON bk.bk_key = r.ru_idx AND bk.bk_type = 'RESUME' AND bk.mm_idx = ?
			ORDER BY s.sent_dt DESC, r.ru_idx DESC
		";
		$params = array_merge([$mm_idx], $page_dates, [$mm_idx], $page_dates, [$mm_idx]);
		$rows = $this->db->query($sql_list, $params)->result_array();

		# 3) 날짜별로 그룹핑 (page_dates 순서 유지)
		$grouped = [];
		foreach ($page_dates as $d) {
			$grouped[$d] = [];
		}
		foreach ($rows as $row) {
			$d = substr($row["sent_dt"], 0, 10);
			if (isset($grouped[$d])) {
				$grouped[$d][] = $row;
			}
		}

		foreach ($page_dates as $d) {
			$result["groups"][] = [
				"sent_date" => $d,
				"list" => $grouped[$d],
			];
		}

		return $result;
	}

	# A.I 매칭 리스트 - 개인회원용 (채용공고) - 날짜그룹 기준 페이징 조회
	function getMatchRecruitListByDate($mm_idx, $page = 1, $day_group_size = 3, $cutoff_days = 14)
	{
		# 1) 최근 cutoff_days 이내 매칭 발송이 있었던 날짜 목록 (알림톡 + SMS, 중복없이, 최신순)
		$sql_dates = "
			SELECT DISTINCT DATE(sent_dt) as sent_date
			FROM (
				SELECT sent_dt FROM st_match_alimtalk_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
				UNION ALL
				SELECT sent_dt FROM st_match_sms_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
			) combined
			GROUP BY DATE(sent_dt)
			ORDER BY sent_date DESC
		";
		$all_dates = $this->db->query($sql_dates, [$mm_idx, $cutoff_days, $mm_idx, $cutoff_days])->result_array();
		$all_dates = array_map(function ($r) { return $r["sent_date"]; }, $all_dates);

		$total_dates = count($all_dates);
		$total_pages = $total_dates > 0 ? ceil($total_dates / $day_group_size) : 1;
		$start = ($page - 1) * $day_group_size;
		$page_dates = array_slice($all_dates, $start, $day_group_size);

		$result = [
			"groups" => [],
			"total_dates" => $total_dates,
			"total_pages" => $total_pages,
			"page" => $page,
		];

		if (empty($page_dates)) {
			return $result;
		}

		# 2) 해당 날짜들에 매칭된 채용공고 상세 조회 (알림톡 + SMS 합침)
		$placeholders = implode(",", array_fill(0, count($page_dates), "?"));
		$sql_list = "
			SELECT
				s.sent_dt,
				c.*,
				a.ad_addr,
				(case when b.bk_idx is not null then 'Y' else 'N' end) as book_yn,
				(case
					when c.rc_nation = '내국인,외국인' then '국적무관'
					when c.rc_nation = '내국인' then '내국인'
					when c.rc_nation = '외국인' then '외국인'
					else '국적무관' end) as rc_nation_nm,
				(case
					when c.rc_sage <> '' and c.rc_eage <> '' then concat(c.rc_sage, '대', ' ~ ', c.rc_eage, '대')
					when c.rc_sage <> '' then concat(c.rc_sage, '대 ~')
					when c.rc_eage <> '' then concat('~ ', c.rc_eage, '대')
					else '나이무관' end) as rc_age_nm
			FROM (
				SELECT sent_dt, target_key, sender_type, receiver_mm_idx FROM st_match_alimtalk_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND DATE(sent_dt) IN ($placeholders)
				UNION ALL
				SELECT sent_dt, target_key, sender_type, receiver_mm_idx FROM st_match_sms_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND DATE(sent_dt) IN ($placeholders)
			) s
			INNER JOIN st_recruit c
				ON c.rc_idx = s.target_key AND c.rc_status = 'Y' AND c.rc_show_yn = 'Y'
			INNER JOIN st_member m
				ON m.mm_idx = c.mm_idx AND m.mm_status = 'Y'
			LEFT JOIN st_address a
				ON a.mm_idx = m.mm_idx AND a.ad_type = 'RECRUIT' AND a.ad_key = c.rc_idx
			LEFT JOIN st_bookmark b
				ON b.bk_key = c.rc_idx AND b.bk_type = 'RECRUIT' AND b.mm_idx = ?
			ORDER BY s.sent_dt DESC, c.rc_idx DESC
		";
		$params = array_merge([$mm_idx], $page_dates, [$mm_idx], $page_dates, [$mm_idx]);
		$rows = $this->db->query($sql_list, $params)->result_array();

		# 3) 날짜별로 그룹핑 (page_dates 순서 유지)
		$grouped = [];
		foreach ($page_dates as $d) {
			$grouped[$d] = [];
		}
		foreach ($rows as $row) {
			$d = substr($row["sent_dt"], 0, 10);
			if (isset($grouped[$d])) {
				$grouped[$d][] = $row;
			}
		}

		foreach ($page_dates as $d) {
			$result["groups"][] = [
				"sent_date" => $d,
				"list" => $grouped[$d],
			];
		}

		return $result;
	}

	# A.I 매칭 배지: 개인회원 - 안읽은 채용공고 매칭 존재 여부
	function hasUnreadMatchRecruit($mm_idx, $cutoff_days = 14)
	{
		$sql = "
			SELECT 1
			FROM (
				SELECT sent_dt FROM st_match_alimtalk_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND read_yn = 'N'
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
				UNION ALL
				SELECT sent_dt FROM st_match_sms_sent
				WHERE sender_type = 'MEM'
				  AND receiver_mm_idx = ?
				  AND read_yn = 'N'
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
			) combined
			LIMIT 1
		";
		$result = $this->db->query($sql, [$mm_idx, $cutoff_days, $mm_idx, $cutoff_days])->row_array();
		return !empty($result);
	}

	# A.I 매칭 배지: 기업회원 - 안읽은 이력서 매칭 존재 여부
	function hasUnreadMatchResume($mm_idx, $cutoff_days = 14)
	{
		$sql = "
			SELECT 1
			FROM (
				SELECT sent_dt FROM st_match_alimtalk_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND read_yn = 'N'
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
				UNION ALL
				SELECT sent_dt FROM st_match_sms_sent
				WHERE sender_type = 'COMP'
				  AND receiver_mm_idx = ?
				  AND read_yn = 'N'
				  AND sent_dt >= DATE_SUB(NOW(), INTERVAL ? DAY)
			) combined
			LIMIT 1
		";
		$result = $this->db->query($sql, [$mm_idx, $cutoff_days, $mm_idx, $cutoff_days])->row_array();
		return !empty($result);
	}

	# A.I 매칭 읽음 처리: 개인회원 - 모든 안읽은 채용공고 매칭을 읽음으로 표시
	function markMatchRecruitAsRead($mm_idx)
	{
		$this->db->query(
			"UPDATE st_match_alimtalk_sent
			 SET read_yn = 'Y'
			 WHERE sender_type = 'MEM'
			   AND receiver_mm_idx = ?
			   AND read_yn = 'N'",
			[$mm_idx]
		);
		$this->db->query(
			"UPDATE st_match_sms_sent
			 SET read_yn = 'Y'
			 WHERE sender_type = 'MEM'
			   AND receiver_mm_idx = ?
			   AND read_yn = 'N'",
			[$mm_idx]
		);
	}

	# A.I 매칭 읽음 처리: 기업회원 - 모든 안읽은 이력서 매칭을 읽음으로 표시
	function markMatchResumeAsRead($mm_idx)
	{
		$this->db->query(
			"UPDATE st_match_alimtalk_sent
			 SET read_yn = 'Y'
			 WHERE sender_type = 'COMP'
			   AND receiver_mm_idx = ?
			   AND read_yn = 'N'",
			[$mm_idx]
		);
		$this->db->query(
			"UPDATE st_match_sms_sent
			 SET read_yn = 'Y'
			 WHERE sender_type = 'COMP'
			   AND receiver_mm_idx = ?
			   AND read_yn = 'N'",
			[$mm_idx]
		);
	}

	# 050 비즈콜 — 이력서(ru_idx)로 구직자 실제 전화번호 조회
	function getPhoneByRuIdx($ru_idx)
	{
		$sql = "
			SELECT aa.mm_phone
			FROM st_member aa
			INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx
			WHERE bb.ru_idx = '" . $this->db->escape_str($ru_idx) . "'
		";
		$result = $this->db->query($sql)->row_array();
		return isset($result['mm_phone']) ? $result['mm_phone'] : null;
	}

	# 이 이력서에 할당된 가상번호가 있으면 그대로 반환 (만료되지 않은 번호만), 없으면 null 반환
	function getActiveVirtualNumber($ru_idx)
	{
		$sql = "
			SELECT v_number
			FROM virtual_numbers
			WHERE target_id = " . (int)$ru_idx . "
			AND status = 'ASSIGNED'
			AND (expire_at IS NULL OR expire_at > NOW())
			LIMIT 1
		";
		$result = $this->db->query($sql)->row_array();
		return isset($result['v_number']) ? $result['v_number'] : null;
	}

	# 델피콤이 배정한 가상번호를 로컬 DB에 upsert (원자적 처리)
	function upsertAssignedVirtualNumber($vNumber, $ru_idx, $realNumber, $expireHours)
	{
		$vNumber = $this->db->escape_str($vNumber);
		$realNumber = $this->db->escape_str($realNumber);
		$expireHours = (int)$expireHours;
		$ru_idx = (int)$ru_idx;

		$sql = "
			INSERT INTO virtual_numbers (v_number, status, target_id, real_number, expire_at, updated_at)
			VALUES ('" . $vNumber . "', 'ASSIGNED', " . $ru_idx . ", '" . $realNumber . "', DATE_ADD(NOW(), INTERVAL " . $expireHours . " HOUR), NOW())
			ON DUPLICATE KEY UPDATE
				status = 'ASSIGNED',
				target_id = " . $ru_idx . ",
				real_number = '" . $realNumber . "',
				expire_at = DATE_ADD(NOW(), INTERVAL " . $expireHours . " HOUR),
				updated_at = NOW()
		";
		$this->db->query($sql);
	}

	# 통화 발생 시 만료시간 연장
	function renewVirtualNumberExpire($vNumber, $expireHours)
	{
		$sql = "
			UPDATE virtual_numbers SET
				expire_at = DATE_ADD(NOW(), INTERVAL " . (int)$expireHours . " HOUR),
				updated_at = NOW()
			WHERE v_number = '" . $this->db->escape_str($vNumber) . "'
			AND status = 'ASSIGNED'
		";
		$this->db->query($sql);
	}

	# 델피콤 쪽에서 자체 만료된 번호를 로컬 DB에서 회수 (cron용)
	function releaseExpiredVirtualNumbers()
	{
		$sql = "
			UPDATE virtual_numbers SET
				status = 'IDLE',
				target_id = NULL,
				real_number = NULL,
				expire_at = NULL,
				updated_at = NOW()
			WHERE status = 'ASSIGNED'
			AND expire_at IS NOT NULL
			AND expire_at < NOW()  
		";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	# 통화 로그를 bizcall_logs에 기록
	function insertBizcallLog($data)
	{
		return $this->db_insert("bizcall_logs", $data);
	}

	# 순수 채팅방(다이렉트) 목록 공통 조건
	# 순수 채팅방 = 정식(RECRUIT/RESUME) 요청 기록이 하나도 없는 recruit 방
	# (새 다이렉트 채팅방은 요청 기록이 없고, 레거시 다이렉트 방은 _DIRECT 기록만 있음)
	# 정식 요청이 1건이라도 생기면 정식 목록 쿼리 쪽으로 넘어가므로 한 방은 한 목록에만 나온다.
	private function _pureChatRoomCondition()
	{
		return "
			room.rm_type = 'recruit'
			AND NOT EXISTS (
				SELECT 1 FROM st_member_request f
				WHERE f.rm_idx = room.rm_idx AND f.mq_type IN ('RECRUIT', 'RESUME')
			)
		";
	}

	# 개인회원이 보낸(내가 만든) 순수 채팅방 목록 조회
	function getDirectChatListSentMem($mm_idx)
	{
		$sql = "
			SELECT
				room.rm_idx,
				'채팅방' AS rc_name,
				IFNULL(rc.rc_thumb, '') AS rc_thumb,
				rc.rc_work,
				IFNULL(comp.mm_id, 'Unknown') AS mm_id,
				IFNULL(comp.mm_comp_name, 'Unknown') AS mm_comp_name,
				IFNULL(comp.mm_thumb, '') AS target_img,
				'' AS rt_recom_badge,
				'' AS rt_work_img,
				IFNULL(NULLIF(room.up_dt, '0000-00-00 00:00:00'), room.reg_dt) AS reg_dt
			FROM st_room room
			INNER JOIN st_recruit rc ON rc.rc_idx = room.rc_idx
			INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
			LEFT JOIN st_member comp ON comp.mm_idx = rc.mm_idx
			WHERE " . $this->_pureChatRoomCondition() . "
			AND ru.mm_idx = ?
			AND room.mm_idx = ?
			ORDER BY reg_dt DESC
		";
		$query = $this->db->query($sql, [$mm_idx, $mm_idx]);
		return $query->result_array();
	}

	# 개인회원이 받은(기업이 만든) 순수 채팅방 목록 조회
	function getDirectChatListReceivedMem($mm_idx)
	{
		$sql = "
			SELECT
				room.rm_idx,
				'채팅방' AS rc_name,
				IFNULL(rc.rc_thumb, '') AS rc_thumb,
				rc.rc_work,
				comp.mm_id AS target_id,
				comp.mm_name AS target_name,
				IFNULL(comp.mm_thumb, '') AS target_img,
				comp.mm_sex,
				comp.mm_comp_name AS mm_comp_name,
				IFNULL(NULLIF(room.up_dt, '0000-00-00 00:00:00'), room.reg_dt) AS reg_dt
			FROM st_room room
			INNER JOIN st_recruit rc ON rc.rc_idx = room.rc_idx
			INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
			INNER JOIN st_member comp ON comp.mm_idx = rc.mm_idx
			WHERE " . $this->_pureChatRoomCondition() . "
			AND ru.mm_idx = ?
			AND room.mm_idx != ?
			ORDER BY reg_dt DESC
		";
		$query = $this->db->query($sql, [$mm_idx, $mm_idx]);
		return $query->result_array();
	}

	# 기업회원이 보낸(내가 만든) 순수 채팅방 목록 조회
	function getDirectChatListSentComp($mm_idx)
	{
		$sql = "
			SELECT
				room.rm_idx,
				room.rc_idx,
				room.ru_idx,
				'채팅방' AS rc_name,
				IFNULL(rc.rc_thumb, '') AS rc_thumb,
				rc.rc_work,
				mem.mm_id AS target_id,
				mem.mm_name AS target_name,
				IFNULL(ru.ru_img1, '') AS target_img,
				mem.mm_sex,
				IFNULL(NULLIF(room.up_dt, '0000-00-00 00:00:00'), room.reg_dt) AS reg_dt
			FROM st_room room
			INNER JOIN st_recruit rc ON rc.rc_idx = room.rc_idx
			INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
			LEFT JOIN st_member mem ON mem.mm_idx = ru.mm_idx
			WHERE " . $this->_pureChatRoomCondition() . "
			AND rc.mm_idx = ?
			AND room.mm_idx = ?
			ORDER BY reg_dt DESC
		";
		$query = $this->db->query($sql, [$mm_idx, $mm_idx]);
		return $query->result_array();
	}

	# 기업회원이 받은(개인이 만든) 순수 채팅방 목록 조회
	function getDirectChatListReceivedComp($mm_idx)
	{
		$sql = "
			SELECT
				room.rm_idx,
				room.rc_idx,
				room.ru_idx,
				'채팅방' AS rc_name,
				IFNULL(rc.rc_thumb, '') AS rc_thumb,
				rc.rc_work,
				mem.mm_id AS target_id,
				mem.mm_name AS target_name,
				IFNULL(mem.mm_thumb, '') AS target_img,
				mem.mm_sex,
				IFNULL(NULLIF(room.up_dt, '0000-00-00 00:00:00'), room.reg_dt) AS reg_dt
			FROM st_room room
			INNER JOIN st_recruit rc ON rc.rc_idx = room.rc_idx
			INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
			LEFT JOIN st_member mem ON mem.mm_idx = ru.mm_idx
			WHERE " . $this->_pureChatRoomCondition() . "
			AND rc.mm_idx = ?
			AND room.mm_idx != ?
			ORDER BY reg_dt DESC
		";
		$query = $this->db->query($sql, [$mm_idx, $mm_idx]);
		return $query->result_array();
	}

	// 5분 제한: 최근 5분 이내 중복 제안/지원 여부 확인
	function checkDuplicateRequestWithinFiveMin($mq_type, $rc_idx, $ru_idx)
	{
		// 5분 이내 중복 제안 체크 (RECRUIT/RESUME 공용)
		$query = "
			SELECT sum(cnt) as total_cnt FROM (
				SELECT count(*) cnt FROM st_member_request
				WHERE mq_type = ?
				  AND reg_dt >= date_sub(now(), interval 5 minute)
				  AND rc_idx = ?
				  AND ru_idx = ?
				UNION ALL
				SELECT count(*) cnt FROM st_member_request_dup
				WHERE mq_type = ?
				  AND reg_dt >= date_sub(now(), interval 5 minute)
				  AND rc_idx = ?
				  AND ru_idx = ?
			) as combined
		";

		$result = $this->db->query($query, [$mq_type, $rc_idx, $ru_idx, $mq_type, $rc_idx, $ru_idx]);
		$count = $result->row_array();

		return (isset($count["total_cnt"]) && intval($count["total_cnt"]) > 0);
	}
}
