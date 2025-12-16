<?php
    class Setting extends My_Controller{
		function __construct() {
	        parent::__construct();
			$this->load->model('membermodel','member_model');
			$this->load->model('settingmodel','setting_model');
			$this->load->model('boardmodel','board_model');
			//$this->load->model('sitemodel','site_model');
	    }

		# 설정메인
		function index(){
			$data = [];
			$this->layout->shop('/setting/index',$data);
		}

		# 비밀번호 변경
		function change_pwd(){
			$data = [];
			$this->layout->shop('/setting/change_pwd',$data);
		}

		# 회원탈퇴1
		function fire_step1(){
			$data = [];
			$this->layout->shop('/setting/fire_step1',$data);
		}

		# 회원탈퇴2
		function fire_step2(){
			$data = [];
			$this->layout->shop('/setting/fire_step2',$data);
		}

		# 알림
		function alarm(){
			$data = [];

            # 회원정보 조회
            $mm_id = $this->user->getUserId();
            $data["mem_info"] = $this->member_model->getMember(["mm_id"=>$mm_id]);

            $data["alarm_all_checked"] = "";
            if($data["mem_info"]["alarm_chat"]=="Y" || $data["mem_info"]["alarm_resume"]=="Y" || $data["mem_info"]["alarm_fav"]=="Y" || $data["mem_info"]["alarm_adv"]=="Y"){
                $data["alarm_all_checked"] = "checked";
            }

            $data["alarm_yoil_list"] = ["일","월","화","수","목","금","토"];
            $data["alarm_deny_yoil_array"] = explode(",",$data["mem_info"]["alarm_deny_yoil"]);

			$this->layout->shop('/setting/alarm',$data);
		}

		# 공지사항
		function notice(){
			$page = input_get("page",1);
			$data = [];

			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		        "bd_aws_yn" => $bd_aws_yn,
		        "tb_id" => $tb_id
		    ];

			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->shop('/setting/notice',$data);
		}

		# 공지사항 상세
		function notice_view($bd_idx){
			$data = [];


			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$data["fav_cnt"] = $this->board_model->getBoardCmtFavCnt($bd_idx);
			$data["cmt_list"] = $this->board_model->getBoardCommentList($bd_idx);
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);

			$this->layout->shop('/setting/notice_view',$data);
		}

		# 커뮤니티
		function community(){
			$data = [];
			$this->layout->shop('/setting/community',$data);
		}

		# 커뮤니티 상세
		function community_view($bd_idx){
            $data = [];

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$data["fav_cnt"] = $this->board_model->getBoardCmtFavCnt($bd_idx);
			$data["cmt_list"] = $this->board_model->getBoardCommentList($bd_idx);
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);

			$this->layout->shop('/setting/community_view',$data);
		}

		# 커뮤니티 등록
		function community_write($bd_idx = 0){
			$data = [];

			$mm_id = $this->user->getUserId();
			$data["info"] = $this->board_model->getBoard($bd_idx);

			if($data["info"]!=null && $mm_id!=$data["info"]["mm_id"]){
				go_back("잘못된 접근입니다.");
			}
			$this->layout->shop('/setting/community_write',$data);
		}

		# 이벤트
		function event(){
			$data = [];
			$date_status = input_get("date_status","ing");

			$data["date_status"] = $date_status;
			$this->layout->shop('/setting/event',$data);
		}

		# 이벤트 상세
		function event_view($bd_idx){
			$data = [];
			$date_status = input_get("date_status","ing");

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["date_status"] = $date_status;
			$data["info"] = $this->board_model->getBoard($bd_idx);
			$data["fav_cnt"] = $this->board_model->getBoardCmtFavCnt($bd_idx);
			$data["cmt_list"] = $this->board_model->getBoardCommentList($bd_idx);
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/setting/event_view',$data);
		}

		# FAQ
		function faq(){
			$data = [];

			$bd_cate = input_get("bd_cate","회원가입/로그인");

			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "bd_cate" => $bd_cate,
		        "date_status" => "",
		        "tb_id" => "faq"
		    ];

            $setting = $this->board_model->getTable(0,"faq");
			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
                "category_list"=> explode(";",$setting["tb_category"]),
				"bd_cate"=>$bd_cate,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
			];

			$this->layout->shop('/setting/faq',$data);
		}

		# 문의하기
		function report(){
			$data = [];
			$this->layout->shop('/setting/report',$data);
		}

		# 문의하기 상세
		function report_view($bd_idx){
			$data = [];
			$date_status = input_get("date_status","ing");

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/setting/report_view',$data);
		}

		# 문의하기 등록
		function report_write($bd_idx){
			$data = [];

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$this->layout->shop('/setting/report_write',$data);
		}

		# 나의신고내역
		function my_report(){
			$data = [];
			$this->layout->shop('/setting/my_report',$data);
		}

		# 나의신고내역 상세
		function my_report_view($br_idx){
			$data = [];
			if($br_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoardReport($br_idx);
			$this->layout->shop('/setting/my_report_view',$data);
		}

		# 개인정보 취급방침
		function site_introduce(){
			$data = [];
			$this->layout->shop('/setting/site_introduce',$data);
		}

		# 개인정보 취급방침
		function site_policy(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"policy_m"]);
			$data["info"] = $info;
			$this->layout->shop('/setting/site_policy',$data);
		}

		# 개인정보 취급방침 기업
		function site_policy2(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"policy2_m"]);
			$data["info"] = $info;
			$this->layout->shop('/setting/site_policy2',$data);
		}

		# 서비스이용약관
		function site_privacy(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"privacy_m"]);
			$data["info"] = $info;
			$this->layout->shop('/setting/site_privacy',$data);
		}

		# 제3자 개인정보 수집동의
		function site_terms2(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"privacy3_m"]);
			$data["info"] = $info;
			$this->layout->shop('/setting/site_terms2',$data);
		}

		# 위치정보이용약관
		function site_terms3(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"location_m"]);
			$data["info"] = $info;
			$this->layout->shop('/setting/site_terms3',$data);
		}

		# 중고거래 이용약관
		function site_terms4(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_goods_m"]);

			$data["info"] = $info;
			$this->layout->shop('/setting/site_terms4',$data);
		}

		# 광고 수신동의
		function site_terms5(){
			$data = [];
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"adv_m"]);

			$data["info"] = $info;
			$this->layout->shop('/setting/site_terms5',$data);
		}

        # 중고거래 이용약관
        function site_terms6(){
            $data = [];
            $info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_term_m"]);

            $data["info"] = $info;
            $this->layout->shop('/setting/site_terms6',$data);
        }
    }
?>
