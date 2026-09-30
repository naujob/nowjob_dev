<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout {
    var $template_data = array();

    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

	//쇼핑몰
	function shop($view = '' , $view_data = array(), $return = FALSE)
    {
    	$headers = apache_request_headers();
        $this->CI =& get_instance();
		$setting = getSettingInfo([]);
		$device_type = getDeviceType();
		$user_info =  $this->CI->common->getSession("USER_INFO");
		$work_list = $this->CI->common->getSessionDataList("WORK_TYPE");
		$addr_list = $this->CI->common->getSessionDataList("ADDR_LIST",60*24);
		$tag_list = $this->CI->common->getSessionDataList("TAG_LIST",30);

		# 설정정보 설정
        $view_data["g_ios_publish"] = false;
		$view_data["os_type"] = $headers["os_type"];
		$view_data["g_setting"] = $setting;
		$view_data["g_addr_list"] = $addr_list;
		$view_data["g_tag_list"] = $tag_list;

		# 세션정보 설정
		if(isset($headers["os_type"])){
			$this->CI->common->setSession("os_type",(isset($headers["os_type"]) ? $headers["os_type"]:""));
		}

		//경로 설정
		$site_path = "";
		if($device_type=="web"){
			//$site_path = "/" . "web";
			$site_path = "/" . "web";
		}else{
			$site_path = "/" . "mobile";
		}

		# 검색 정보 조회
		if($device_type=="web"){
			$view_data["g_wrok_list"]  = $work_list;
			$view_data["g_price_type_list"] = [
				["cd_val"=>"일급"],
				["cd_val"=>"주급"],
				["cd_val"=>"월급"],
			];
			$view_data["g_work_type_list"] = [
				["cd_val"=>"주간"],
				["cd_val"=>"야간"],
				["cd_val"=>"2교대"],
			];;
			$view_data["g_week_list"] = [
				["cd_val"=>"일"],
				["cd_val"=>"월"],
				["cd_val"=>"화"],
				["cd_val"=>"수"],
				["cd_val"=>"목"],
				["cd_val"=>"금"],
				["cd_val"=>"토"],
			];
		}

        # 알림 카운트
        $view_data["g_alarm_cnt"] = 0;
        if($user_info!=null){
            $tbl_alarm = get_mongodb("alarm");
            $alarm_cnt = $tbl_alarm->find(["show_yn"=>"N","user_id"=>$user_info["mm_id"]])->count();
            $view_data["g_alarm_cnt"] = $alarm_cnt;
        }

		# 채용공고 등록 여부 확인 (COMP 사용자용 말풍선 표시)
		$view_data["show_recruit_bubble"] = false;
		if($this->CI->user->isLogin() && $this->CI->user->getUserType() === "COMP") {
			$this->CI->load->model('recruitmodel', 'recruit_model');
			$recruit_counts = $this->CI->recruit_model->getRecruitCountForTab($this->CI->user->getUserIdx());

			if (is_array($recruit_counts)) {
				if (empty($recruit_counts)) {
					// 결과행이 없음 = 채용공고가 없음
					$view_data["show_recruit_bubble"] = true;
				} else {
					// allcnt 값 확인 (NULL 또는 0이면 말풍선 표시)
					$allcnt = null;
					foreach ($recruit_counts[0] as $key => $value) {
						if (strtolower($key) === 'allcnt') {
							$allcnt = $value;
							break;
						}
					}

					if ($allcnt === null || $allcnt == 0) {
						$view_data["show_recruit_bubble"] = true;
					}
				}
			}
		}

		//assets 경로 정의
		defined('RES_PATH') || define('RES_PATH', "/resource/shop" . $site_path);
        $this->set('contents', $this->CI->load->view($site_path . "/" . $view, $view_data, TRUE));
        return $this->CI->load->view( $site_path . "/" .  "_layout/basic", $this->template_data, $return);
    }

	//쇼핑몰 팝업
	function shop_popup($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();
		$setting = getSettingInfo([]);
		$device_type = getDeviceType();
		$user_info =  $this->CI->common->getSession("USER_INFO");
		$this->CI->common->sessionWrokCodeList();

		//경로 설정
		$site_path = "";
		if($device_type=="web"){
			$site_path = "/" . "web";
		}else{
			$site_path = "/" . "mobile";
		}

		//assets 경로 정의
		defined('RES_PATH') || define('RES_PATH', "/resource/shop" . $site_path);
        $this->set('contents', $this->CI->load->view($site_path . "/" . $view, $view_data, TRUE));
        return $this->CI->load->view( $site_path . "/" .  "_layout/popup", $this->template_data, $return);
    }

	//쇼핌올 빈값
	function shop_empty($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();
		$setting = getSettingInfo();
		$device_type = getDeviceType();
		$user_info =  $this->CI->common->getSession("USER_INFO");
		$this->CI->common->sessionWrokCodeList();


		//경로 설정
		$site_path = "";
		if($device_type=="web"){
			$site_path = "/" . "web";
		}else{
			$site_path = "/" . "mobile";
		}

		//assets 경로 정의
		defined('RES_PATH') || define('RES_PATH', "/resource/shop" . $site_path);
        $this->set('contents', $this->CI->load->view($site_path . "/" . $view, $view_data, TRUE));
        return $this->CI->load->view( $site_path . "/" .  "_layout/popup", $this->template_data, $return);
    }

	//관리자
	function admin($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();
		$setting = getSettingInfo([]);	//@TODO:수정해야함.

		//변경해야함.
		$admin_info = $this->CI->common->getSession("ADMIN_INFO");

		if($admin_info==null){
			$this->CI->common->goLocation('/admin/home/login');
            exit;
		}

		$admin_menu_list = $this->CI->admin->getAdminMenuList($setting["st_admin_type"]);
		$admin_menu = $this->getActiveMenu($admin_menu_list);
		$fav_menu = $this->CI->admin->getFavMenuList();

		if(strpos($_SERVER['REQUEST_URI'],"/admin/member/grade_list")!==false
		){
			$view_data["show_caution1"] = true;
		}else{
			$view_data["show_caution1"] = false;
		}
		if(strpos($_SERVER['REQUEST_URI'],"/admin/goods/goods_edit")!==false
		){
			$view_data["show_caution2"] = true;
		}else{
			$view_data["show_caution2"] = false;
		}
		if(strpos($_SERVER['REQUEST_URI'],"/admin/goods/option_add_info")!==false
		){
			$view_data["show_caution3"] = true;
		}else{
			$view_data["show_caution3"] = false;
		}

		$view_data["g_setting"] = $setting;
		$view_data["g_setting_etc"] = $setting_etc;
		$view_data["is_home"] = $admin_menu["is_home"];
		$view_data["top_menu"] = $admin_menu["top_menu"];
		$view_data["left_menu"] = $admin_menu["left_menu"];
		$view_data["short_menu_title"] = $admin_menu["short_menu_title"];
		$view_data["short_menu"] = $admin_menu["short_menu"];
		$view_data["admin_info"] = $admin_info;
		$view_data["fav_menu"] = $fav_menu;

    $this->CI->load->model('boardmodel','board_model');
    $result1 = $this->CI->board_model->getBoardNewCnt("community");
    $result2 = $this->CI->board_model->getBoardNewCnt("report");

    $view_data["community_cnt"] = $result1;
    $view_data["report_cnt"] = $result2;

        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view('admin/_layout/basic', $this->template_data, $return);
    }

	//관리자 빈값
	function admin_empty($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();
		$setting = getSettingInfo([]);
		$view_data["g_setting"] = $setting;
        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view('admin/_layout/empty', $this->template_data, $return);
    }

	//관리자 팝업
	function admin_popup($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();

		//변경해야함.
		$admin_info = $this->CI->common->getSession("ADMIN_INFO");
		$setting = getSettingInfo([]);
		$view_data["g_setting"] = $setting;
		$view_data["admin_info"] = $admin_info;
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view('admin/_layout/popup', $this->template_data, $return);
    }

	//홈페이지
	function home($view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();
        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view('_layout/basic', $this->template_data, $return);
    }

	function getQuickType(){
		$type = "";
		$url = strtolower($_SERVER['REQUEST_URI']);
		if(false!==strpos($url,"/board/list")){
			$type = "board";
		}else if(false!==strpos($url,"/board/board_view")){
			$type = "board_view";
		}
		return $type;
	}

	function getBankList($info){
		$bank_list = [];
		$st_bank_list = $info["st_bank_list"];

		if($st_bank_list==""){
			return $bank_list;
		}

		$arr_bank_temp = explode("#",$st_bank_list);
		foreach($arr_bank_temp as $key=> $bank){
			$arr_bank = explode(":",$bank);
			$idx = ($key + 1);
			$bank_list[] = [
				"bank"  =>$arr_bank[0],
				"acc_name"   =>$arr_bank[1],
				"acc_num"  =>$arr_bank[2],
			];
		}

		return $bank_list;
	}

	//관리자 메뉴 리스트
	function getActiveMenu($admin_list){
		$top_menu = array();
		$left_menu = array();
		$menu_list = "";
		$short_menu_title = "";
		$short_menu = [];
		$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		$current_menu_idx = -1;
		foreach($admin_list as $num_idx => $row){
			$active = "";

			//1뎁스 메뉴 처리
			$top = [
				'name'=>$row["name"],
				'action'=>$row["action"],
			];

			//2뎁스 메뉴 처리
			if(isset($row["children"])){
				$arr_sub = array();
				foreach($row["children"] as $p_menu){

					$p_menu["active"] = "";
					if(strtolower($p_menu["action"]) == strtolower($currentUrl))
					{
						$short_menu[] = $p_menu;
						$current_menu_idx = $num_idx;
						$active = "on";
						$p_menu["active"] = $active;
						$short_menu_title = $p_menu["name"];
					}

					$arr_menu = [];
					foreach($p_menu["children"] as $menu){
						$menu["active"] = "";

						if(strtolower($menu["action"]) == strtolower($currentUrl))
						{
							$short_menu[] = $menu;
							$current_menu_idx = $num_idx;
							$active = "on";
							$p_menu["active"] = $active;
							$menu["active"] = $active;
							$short_menu_title = $menu["name"];
						}

						//관련 메뉴 찾아서 처리
						if(isset($menu["pages"])){
							foreach($menu["pages"] as $rel){
								if(strtolower($rel["action"]) == strtolower($currentUrl))
								{
									$short_menu[] = $rel;
									$current_menu_idx = $num_idx;
									$active = "on";
									$p_menu["active"] = $active;
									$menu["active"] = $active;
									$short_menu_title = $rel["name"];
								}
							}
						}
						$arr_menu[] = $menu;
					}

					$p_menu["children"] = $arr_menu;
					$arr_sub[] = $p_menu;
				}
				$row["left_list"] = $arr_sub;
			}

			if($active == "on"){
				array_unshift($short_menu,$top);
			}
			$top["active"] = $active;
			$row["active"] = $active;

			$left_menu[] = $row["left_list"];
			$top_menu[] = $top;
		}

		if($current_menu_idx==-1){
			return [
				"is_home"=> true,
				"top_menu"=>$top_menu,
				"left_menu" => [],
				"short_menu_title"=>$short_menu_title,
				"short_menu"=>$short_menu,
			];
		}else{

			return [
				"is_home" => false,
				"top_menu" => $top_menu,
				"left_menu" => $left_menu[$current_menu_idx],
				"short_menu_title"=>$short_menu_title,
				"short_menu"=>$short_menu,
			];
		}
	}

	function getMenuList(){
		$result ="";
		$db = DB('default', true);
		$sql = "SELECT   menu_name
						,menu_id
						,menu_url
						,is_parent
						,parent_id
						,icon
						,(SELECT count(*) FROM st_admin_menu BB where BB.parent_id = AA.menu_id) as child_cnt
		        FROM st_admin_menu AA
		        WHERE is_use = 'Y'
		        ORDER BY sort ASC;";

		$query = $db->query($sql);
        return $query->result_array();
	}

	function getCartCount(){
		$cart_qty = $this->CI->common->getCartCount();
		return $cart_qty;
	}
}
