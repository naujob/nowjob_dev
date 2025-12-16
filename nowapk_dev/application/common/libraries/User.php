<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class user
{
	private $ci;

	function __construct()
	{
		$ci = &get_instance();
	}

	function isLogin()
	{
		$this->CI = &get_instance();
		$user_info =  $this->CI->common->getSession("USER_INFO");
		if ($user_info == null) {
			return false;
		} else {
			return true;
		}
	}

	function getCurrentUserInfo()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('settingmodel', 'setting_model');
		$info = $this->CI->member_model->getMember(["mm_idx" => $this->getUserIdx()]);
		return $info;
	}

	//회원비회원같이 사용함.
	function getUserId()
	{
		$user_info = $this->getUser();
		return $user_info["mm_id"];
	}

	//회원비회원같이 사용함.
	function getUserPoint()
	{
		$user_info = $this->getCurrentUserInfo();
		return isset($user_info["mm_point"]) ? $user_info["mm_point"] : 0;
	}

	//회원비회원같이 사용함.
	function getUserName()
	{
		$user_info = $this->getCurrentUserInfo();

		//return isset($user_info["mm_name"]) ? $user_info["mm_name"]:"";

		return isset($user_info["mm_name"]) &&
			strpos($user_info["mm_name"], '010') !== 0 &&
			strpos($user_info["mm_name"], 'kakao') !== 0 &&
			strpos($user_info["mm_name"], 'apple') !== 0
			? $user_info["mm_name"]
			: "guest";

		//$user_info = $this->getUser();
		//return $user_info["mm_name"];
	}

	//회원비회원같이 사용함.
	function getUserIdx()
	{
		if ($this->isLogin() == true) {
			$user_info = $this->getUser();
			return $user_info["mm_idx"];
		} else {
			return -1;
		}
	}

	//회원비회원같이 사용함.
	function getUserImg()
	{
		$user_info = $this->getUser();
		//print_r($user_info);
		if ($user_info["mm_img"] == "") {
			//return $user_info["mm_sex"];
			if ($user_info["mm_sex"] == "남") {
				return "/resource/shop/web/img/profile_default_m.png";
			} else if ($user_info["mm_sex"] == "여") {
				return "/resource/shop/web/img/profile_default_w.png";
			} else {
				return "/resource/shop/mobile/img/common/noimage1.png";
			}
		} else {
			return $user_info["mm_img"];
		}
	}

	//회원비회원같이 사용함.
	function getUserType()
	{
		$user_info = $this->getUser();
		return $user_info["mm_type"];
	}

	//회원비회원같이 사용함.
	function getJoinType()
	{
		$user_info = $this->getUser();
		return $user_info["mm_join_type"];
	}

	function setUser($info)
	{
		$this->CI = &get_instance();
		$this->CI->common->setSession("USER_INFO", $info);
	}

	//회원,비회원정보를 가져온다.
	function getUser()
	{
		$this->CI = &get_instance();
		$user_info =  $this->CI->common->getSession("USER_INFO");
		$user = array();

		if ($user_info) {
			$user = [
				"mm_idx" => $user_info["mm_idx"],
				"mm_id" => $user_info["mm_id"],
				"mm_point" => isset($user_info["mm_point"]) ? $user_info["mm_point"] : 0,
				"mm_name" => $user_info["mm_name"],
				"mm_type" => $user_info["mm_type"],
				"mm_join_type" => $user_info["mm_join_type"],
				"mm_img" => $user_info["mm_img"],
				"ad_addr" => $user_info["ad_addr"],
				"ad_gps_x" => $user_info["ad_gps_x"],
				"ad_gps_y" => $user_info["ad_gps_y"],
				"mm_sex" => $user_info["mm_sex"]
			];
			return $user;
		}
		return null;
	}

	function getGuest()
	{
		$this->CI = &get_instance();
		$guest_info =  $this->CI->common->getSession("GUEST_INFO");

		if ($guest_info["gt_session_id"] != session_id()) {
			$guest_info = null;
		}

		if ($guest_info == null) {
			$info = [
				"gt_session_id" => session_id(),
				"gt_conn_ip" => $this->CI->common->getIpAddr(),
				"ad_zipcode" => "",
				"ad_jibun" => "",
				"ad_doro" => "",
				"ad_detail" => "",
				"ad_addr" => "",
				"ad_gps_x" => "",
				"ad_gps_y" => "",
			];

			$result = $this->CI->common->insertGuest($info);
			$this->CI->common->setSession("GUEST_INFO", $result);
			$guest_info =  $this->CI->common->getSession("GUEST_INFO");

			return $guest_info;
		} else {
			return $guest_info;
		}
	}

	function getAddrInfo()
	{
		$arr_text = "";

		$this->CI = &get_instance();

		$user_info =  $this->CI->common->getSession("USER_INFO");

		if ($user_info == null) {
			$guest_info = $this->getGuest();

			try {
				$arr_temp = explode(" ", $guest_info["ad_addr"]);
				$arr_text = $arr_temp[count($arr_temp) - 3];
				$arr_text .= " " . $arr_temp[count($arr_temp) - 2];
				$arr_text .= " " . $arr_temp[count($arr_temp) - 1];
			} catch (Exception $ex) {
				$arr_text = $user_info["ad_addr"];
			}


			return [
				"ad_addr" => $arr_text,
				"ad_gps_x" => $guest_info["ad_gps_x"],
				"ad_gps_y" => $guest_info["ad_gps_y"],
			];
		} else {
			try {
				$arr_temp = explode(" ", $user_info["ad_addr"]);
				$arr_text = $arr_temp[count($arr_temp) - 3];
				$arr_text .= " " . $arr_temp[count($arr_temp) - 2];
				$arr_text .= " " . $arr_temp[count($arr_temp) - 1];
			} catch (Exception $ex) {
				$arr_text = $user_info["ad_addr"];
			}

			return [
				"ad_addr" => $arr_text,
				"ad_gps_x" => $user_info["ad_gps_x"],
				"ad_gps_y" => $user_info["ad_gps_y"],
			];
		}
	}
}
