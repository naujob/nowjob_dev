<?php
    class Community extends CI_Controller{
      # 전역변수 선언
    	public $mm_idx;
		public $mm_id;

		function __construct() {
	        parent::__construct();

          $this->load->model('membermodel','member_model');
			$this->load->model('sitemodel','site_model');
            $this->load->model('boardmodel','board_model');
            $this->load->model('commonmodel','common_model');

            $this->mm_id = $this->user->getUserId();
      			$this->mm_idx = $this->user->getUserIdx();
	    }

        function community_list(){
			$page = input_get("page",1);
			$sort = input_get("sort","reg_dt");
			$tb_id = "community";

			$data = [];
			$params = [
		        "pagesize" => 10,
		        "page" => $page,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "tb_id" => $tb_id,
		        "sort" => $sort,
		        "mm_id" => ""
		    ];
        //"mm_id" => $this->mm_id

			$result = $this->board_model->getBoardList($params);
			$best_list = $this->board_model->getCommunityBestList();

			//리턴 설정
			$data = [
				"sort"=>$sort,
				"best_list" => $best_list,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
                "pagging"=> $result["pagenation"]
			];

			$this->layout->shop('/community/community_list',$data);
        }

        function community_view($bd_idx){
            $data = [];
			$date_status = input_get("date_status","ing");

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["date_status"] = $date_status;
			$data["info"] = $this->board_model->getBoard($bd_idx);
			$data["fav_cnt"] = $this->board_model->getBoardFavCnt($bd_idx);
			$data["cmt_list"] = $this->board_model->getBoardCommentList($bd_idx);

			$data["prev"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"next");
			$data["next"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"prev");

			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/community/community_view',$data);
        }

		# 커뮤니티 등록
		function community_write($bd_idx = 0){
			$data = [];

			$mm_id = $this->user->getUserId();
			$data["info"] = $this->board_model->getBoard($bd_idx);

			if($data["info"]!=null && $mm_id!=$data["info"]["mm_id"]){
				go_back("잘못된 접근입니다.");
			}
			$this->layout->shop('/community/community_write',$data);
		}

        function event_list(){

            //$mm_id = $this->user->getUserType();
			$page = input_get("page",1);
			$tb_id = "event";
			$date_status = input_get("date_status","ing");
			$mm_id_yn = input_get("mm_id_yn","N");

			$bd_show_type = "";

			if($this->user->isLogin()==true){
				$bd_show_type = $this->user->getUserType();
			}


			$data = [];

			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "bd_show_type" => $bd_show_type,
		        "date_status" => $date_status,
		        "tb_id" => $tb_id,
		        "mm_id" => ($mm_id_yn=="Y" ? $mm_id:"")
		    ];

			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
                "date_status" => $date_status,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
                "pagging"=> $result["pagenation"]
			];

			$this->layout->shop('/community/event_list',$data);
        }

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

			$data["prev"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"next");
			$data["next"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"prev");
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/community/event_view',$data);
        }

		function notice_list(){
            //$mm_id = $this->user->getUserType();
			$page = input_get("page",1);
			$tb_id = "notice";
			$sort = input_get("sort","reg_dt");

			$data = [];

			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "tb_id" => $tb_id,
		        "sort" => $sort,
		    ];

			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
				"sort"=> $sort,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
                "pagging"=> $result["pagenation"]
			];

			$this->layout->shop('/community/notice_list',$data);
        }

        function notice_view($bd_idx){
            $data = [];
			$date_status = input_get("date_status","ing");

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["date_status"] = $date_status;
			$data["info"] = $this->board_model->getBoard($bd_idx);
			$data["fav_cnt"] = $this->board_model->getBoardCmtFavCnt($bd_idx);
			$data["cmt_list"] = $this->board_model->getBoardCommentList($bd_idx);

			$data["prev"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"next");
			$data["next"] = $this->board_model->getBoardNextPrev($bd_idx,$data["info"]["tb_id"],"prev");
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/community/notice_view',$data);
        }

		function faq_list(){
            //$mm_id = $this->user->getUserType();
			$page = input_get("page",1);
			$tb_id = "faq";
			$bd_cate = input_get("bd_cate","회원가입/로그인");

			$bd_show_type = "";
			if($this->user->isLogin()==true){
				$bd_show_type = $this->user->getUserType();
			}

			$data = [];

			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "tb_id" => $tb_id,
		        "bd_show_type" => $bd_show_type,
		        "bd_cate" => $bd_cate,
		    ];

            $setting = $this->board_model->getTable(0,"faq");
			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
                "category_list"=> explode(";",$setting["tb_category"]),
				"bd_cate"=> $bd_cate,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
                "pagging"=> $result["pagenation"]
			];

			$this->layout->shop('/community/faq_list',$data);
        }
    }
?>
