<?php
    class Trade extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('membermodel','member_model');
			$this->load->model('ordermodel','order_model');
			$this->load->model('sitemodel','site_model');
	    }

		//상품 목록
		function trade_list(){
			//파라미터 설정
			$page = input_get("page",1);
            $search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$category = input_get("category","");
			$str_category = "";
			$sprice = input_get("sprice","");
			$eprice = input_get("eprice","");
            $show_yn = input_get("show_yn","");

			# 카테고리 검색 데이터 만들기
			if($category!=""){
				$str_category = implode("^",$category);
			}

	        $params = [
	            "pagesize" => 20,
	            "page" => $page,
	            "search_type" => $search_type,
	            "search_value" => $search_value,
	            "category" => $str_category,
	            "sprice" => $sprice,
	            "eprice" => $eprice,
                "show_yn" => $show_yn,
	        ];

	        $result = $this->site_model->getTradeSearchListV2($params);

			$category_list = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$select_category = getKeyValue($category_list,"cd_val","cd_val");

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
					"category" => $category,
					"sprice" => $sprice,
					"eprice" => $eprice,
                    "show_yn" => $show_yn,

				],
				"page_size" => $page_size,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"select_category" => $select_category,
			];

			$this->layout->admin('admin/trade/trade_list',$data);
		}

		//회원 일괄처리
		public function trade_list_proc(){
			//파라미터 설정
			$proc_mode = input_post("proc_mode","");
			$chk_list = input_post("chk_list","");
			$add_sale_depth = input_post("add_sale_depth","");
			$mg_idx = input_post("mg_idx","");

			if($proc_mode=="hidden"){
				$arr_list = explode(",",$chk_list);
				foreach($arr_list as $chk){
					$result = $this->site_model->updateTrade(["td_status"=>"H"],["td_idx"=>$chk]);
				}
			}

			json_return("00","일괄 처리 완료","");
		}

		# 중거 거래 상세 정보
		function trade_edit(){
			$idx = input_get("idx","");
			$info = $this->site_model->getTradeDetail($idx);
			$chat_list = $this->site_model->getTradeChatList(["td_idx"=>$idx,"page"=>1,"pagesize"=>"10"]);
			$fav_list = $this->site_model->getTradeFavList(["td_idx"=>$idx,"page"=>1,"pagesize"=>"10"]);

			//리턴 처리
		    $data["info"] = $info;
			$data["chat_list"] = $chat_list["list"];
			$data['fav_list'] = $fav_list["list"];

			$this->layout->admin('admin/trade/trade_edit',$data);
		}

		function trade_edit_proc(){
			$proc_mode = input_post("proc_mode","");
			$idx = input_post("idx",0);

			if($proc_mode=="delete"){
				$result = $this->site_model->updateTrade(["td_status"=>"H"],["td_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","비노출 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}else if($proc_mode=="stop"){
                $mm_stop_desc = input_post("mm_stop_desc","");
				$result = $this->site_model->updateTrade(["td_status"=>"H","stop_desc"=>$mm_stop_desc,"stop_dt"=>get_today()],["td_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","비공개 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}
		}
    }
?>
