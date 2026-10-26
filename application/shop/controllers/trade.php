<?php
    class Trade extends CI_Controller{
		function __construct() {
	        parent::__construct();
			
			$this->load->model('membermodel','member_model');
			$this->load->model('sitemodel','site_model');
			$this->load->model('settingmodel','setting_model');
			$this->load->model('commonmodel','common_model');
	    }

		# 메인화면
		function index(){
            $addr_info = $this->user->getAddrInfo();

			//검색어 가져오기
			$search_txt = [
		        "text" => "",
		        "skm" => "",
		        "ekm" => "30",
		        "category" => "",
		        "sprice" => "0",
		        "eprice" => "50",
            ];

			$site_log = $this->site_model->getSiteLog([
                "session_id"=>session_id()
                ,"sl_type"=>"SEARCH_TRADE"
                ,"sl_step"=>"검색"
	        ]);

			if($site_log==null){
				if($this->user->isLogin()){
					$site_log = $this->site_model->getSiteLog([
		                "mm_idx"=>$this->mm_idx
		                ,"sl_type"=>"SEARCH_TRADE"
		                ,"sl_step"=>"검색"
		        	]);
				}
			}
			
			if($site_log!=null){
				$json_info = json_decode($site_log["sl_txt"],true);
				$search_txt["text"] = $json_info["text"];
				$search_txt["skm"] = $json_info["skm"];
				$search_txt["ekm"] = $json_info["ekm"];
				$search_txt["category"] = explode("^",$json_info["category"]);
				$search_txt["sprice"] = $json_info["sprice"];
				$search_txt["eprice"] = $json_info["eprice"];
			}

            $data["addr_info"] = $addr_info;
			$data["category_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$data["search_txt"]  = $search_txt;
			$this->layout->shop('/trade/index',$data);
		}

		# 메인화면 - 웹용
		function wlist(){
			$addr_info = $this->user->getAddrInfo();
			$mm_idx = $this->user->getUserIdx();
			$tag_info = $this->setting_model->getSettingInfo(["st_group"=>"태그","st_key"=>"trade"]);
			
            $data["addr_info"] = $addr_info;
			$data["tag_list"] = $tag_info["trade"]!="" ? explode(",",$tag_info["trade"]):[];
			$data["category_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$data["trade_reco_list"] = $this->common_model->getFavTradeList($addr_info,$mm_idx,12);
			$data["search_txt"]  = $search_txt;
			$this->layout->shop('/trade/wlist',$data);
		}

		# 메인화면 맵
		function index_map(){
            $addr_info = $this->user->getAddrInfo();
			$kakao_key = $this->setting_model->getSettingInfo(["st_group"=>"카카오","st_key"=>"인증키"]);
			
			//검색어 가져오기
			$search_txt = [
		        "text" => "",
		        "skm" => "",
		        "ekm" => "30",
		        "category" => "",
		        "sprice" => "0",
		        "eprice" => "50",
            ];

			$site_log = $this->site_model->getSiteLog([
                "session_id"=>session_id()
                ,"sl_type"=>"SEARCH_TRADE"
                ,"sl_step"=>"검색"
	        ]);

			if($site_log==null){
				if($this->user->isLogin()){
					$site_log = $this->site_model->getSiteLog([
		                "mm_idx"=>$this->mm_idx
		                ,"sl_type"=>"SEARCH_TRADE"
		                ,"sl_step"=>"검색"
		        	]);
				}
			}
			
			if($site_log!=null){
				$json_info = json_decode($site_log["sl_txt"],true);
				$search_txt["text"] = $json_info["text"];
				$search_txt["skm"] = $json_info["skm"];
				$search_txt["ekm"] = $json_info["ekm"];
				$search_txt["category"] = explode("^",$json_info["category"]);
				$search_txt["sprice"] = $json_info["sprice"];
				$search_txt["eprice"] = $json_info["eprice"];
			}

			$result = $this->site_model->getTradeSearchParamsList(100);
			
			$data["data"] = $result;
			$data["kakao_key"] = $kakao_key;
            $data["addr_info"] = $addr_info;
			$data["category_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$data["search_txt"]  = $search_txt;
			$this->layout->shop('/trade/index_map',$data);
		}

        # 상세화면
		function trade_detail($td_idx = 0){
            $data = [];
			
			$mm_idx = $this->user->getUserIdx();
			$kakao_key = $this->setting_model->getSettingInfo(["st_group"=>"카카오","st_key"=>"인증키"]);
			$trade_info = $this->site_model->getTrade(["td_idx"=>$td_idx]);
			$reco_list = $this->site_model->getTradeRecoList($td_idx,$mm_idx);
			$addr_list = $this->member_model->getAddressList(["ad_type"=>"TRADE","ad_key"=>$td_idx]);
			$mem_info = $this->member_model->getMember(["mm_idx"=>$mm_idx]);
			$mem_trade_info = $this->member_model->getMember(["mm_idx"=>$trade_info["mm_idx"]]);
			
			if($td_idx !=0 && $trade_info==null){
				go_location("/trade","잘못된 접근입니다.");
			}

			// 탈퇴 등으로 삭제 처리(td_status='N')된 글은 상세보기 차단 (보류/거래완료인 H는 그대로 노출)
			if($td_idx !=0 && $trade_info!=null && $trade_info["td_status"]=="N"){
				go_location("/trade","삭제되었거나 더 이상 볼 수 없는 게시글입니다.");
			}

			# hit수 업데이트
			$trade_info["td_hit"] = $trade_info["td_hit"] + 1;
			$this->site_model->updateTrade(["td_hit"=>$trade_info["td_hit"]],["td_idx"=>$td_idx]);
			
			# 내가본 중고상품
			$view_list = [];
			if($this->user->isLogin()==true){
				if($td_idx!=0){
					$this->site_model->insertMemberView(["mv_type"=>"trade","mv_key"=>$td_idx,"mm_idx"=>$mm_idx]);
					$view_list = $this->site_model->getMemberViewList("trade",$mm_idx);
				}
			}
		
			$data["view_list"] = $view_list;
			$data["kakao_key"] = $kakao_key;
			$data["info"] = $trade_info;
			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$mm_idx]);
			$data["reco_list"] = $reco_list;
			$data["addr_list"] = $addr_list;
			$data["mem_info"] = $mem_info;
			$data["mem_trade_info"] = $mem_trade_info;
			
			$data["header_class"] = "transparency";
			$this->layout->shop('/trade/trade_detail',$data);
		}
		
		# 상세화면
		function trade_write($td_idx = 0){
            $data = [];
			$mm_idx = $this->user->getUserIdx();
			
			# 로그인확인후 이동처리
			if($this->user->isLogin()==false){
				go_back("로그인후 이용 가능합니다.");	
			}
			
			$trade_info = $this->site_model->getTrade(["td_idx"=>$td_idx]);
			
			if($td_idx !=0 && $trade_info==null){
				go_location("/trade","잘못된 접근입니다.");
			}
			
			$data["info"] = $trade_info;
			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$mm_idx]);
			$data["addr_list"]  = $this->member_model->getAddressList(["ad_type"=>"MEMBER","mm_idx"=>$mm_idx,"ad_key"=>$mm_idx]);
			$data["td_category_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$data["td_hash_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고해시태그"]);
			
			if($this->common->getLocation()=="W"){
				$data["term1"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_goods_pc"]);
				$data["term2"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_term_pc"]);	
			}else{
				$data["term1"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_goods_m"]);
				$data["term2"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_term_m"]);
			}
			
			$this->layout->shop('/trade/trade_write',$data);
		}
    }
?>
