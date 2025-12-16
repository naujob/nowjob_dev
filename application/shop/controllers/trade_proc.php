<?php
    class Trade_proc extends CI_Controller{
		function __construct() {
	        parent::__construct();

			$this->load->model('membermodel','member_model');
			$this->load->model('sitemodel','site_model');
			$this->load->model('settingmodel','setting_model');
	    }

		# 메인화면
		function search_list(){
            $result = $this->site_model->getTradeSearchParamsList();
            json_return("00","조회성공",$result);
		}

		# 상품등록 처리
		function trade_write(){

            # 데이터 추가.
			$proc_mode = input_post("proc_mode","insert");
			$mm_idx = $this->user->getUserIdx();
			$td_idx = input_post("td_idx","");
            $td_name = input_post("td_name","");
			$td_hash = input_post("td_hash","");
            $td_desc = input_post("td_desc","");
			$td_category = input_post("td_category","");
			$td_price = input_post("td_price","");
			$td_price_req_yn = input_post("td_price_req_yn","");

			$ad_zipcode = input_post("ad_zipcode","");
			$ad_jibun = input_post("ad_jibun","");
			$ad_doro = input_post("ad_doro","");
			$ad_addr = input_post("ad_addr","");
			$ad_gps_x = input_post("ad_gps_x","");
			$ad_gps_y = input_post("ad_gps_y","");

            # 이미지 처리.
            $td_img1 = $this->common->img_upload_resize_url("member","td_img1",1200);
            $td_img2 = $this->common->img_upload_resize_url("member","td_img2",1200);
            $td_img3 = $this->common->img_upload_resize_url("member","td_img3",1200);
            $td_img4 = $this->common->img_upload_resize_url("member","td_img4",1200);
            $td_img5 = $this->common->img_upload_resize_url("member","td_img5",1200);

			$td_thumb = $this->common->img_upload_resize_url("member","td_img1",600);

			# 로그인확인후 이동처리
			if($this->user->isLogin()==false){
				json_return("91","로그인후 이용해주세요.");
			}

			if($proc_mode=="insert"){
				# 트랜젝션 시작
				$this->db->trans_begin();

				$info = [
					"mm_idx"=>$mm_idx,
					"td_name"=>$td_name,
					"td_img1"=>$td_img1,
					"td_img2"=>$td_img2,
					"td_img3"=>$td_img3,
					"td_img4"=>$td_img4,
					"td_img5"=>$td_img5,
					"td_thumb"=>$td_thumb,
					"td_category"=>arr_implode(",",$td_category),
					"td_hash"=>arr_implode(",",$td_hash),
					"td_desc"=>$td_desc,
					"td_price"=>$td_price,
					"td_price_req_yn"=>$td_price_req_yn,
				];
				$result = $this->site_model->insertTrade($info);

				if($result["code"]!="00"){
					$this->db->trans_rollback();
					json_return("91","[01]중고 거래 등록시 오류가 발생하였습니다.");
				}

				foreach($ad_zipcode as $idx=>$row){
					$arr_addr = [
	            		"mm_idx"=>$mm_idx,
	            		"ad_type"=>"TRADE",
	            		"ad_key"=>$result["key"],
	            		"ad_zipcode"=>$ad_zipcode[$idx],
	            		"ad_jibun"=>$ad_jibun[$idx],
	            		"ad_doro"=>$ad_doro[$idx],
	            		"ad_detail"=>"",
	            		"ad_addr"=>$ad_addr[$idx],
	            		"ad_gps_x"=>$ad_gps_x[$idx],
	            		"ad_gps_y"=>$ad_gps_y[$idx],
	            	];

					$result_addr = $this->member_model->insertAddress($arr_addr);
					if($result_addr["code"]!="00"){
						$this->db->trans_rollback();
						json_return("92","[02]중고 거래 등록시 오류가 발생하였습니다.");
					}
				}

				$this->db->trans_commit();
				json_return("00","등록완료",["key"=>$result["key"]]);
			}else if($proc_mode="update"){
				$info = [
					"mm_idx"=>$mm_idx,
					"td_name"=>$td_name,
					"td_img1"=>$td_img1,
					"td_img2"=>$td_img2,
					"td_img3"=>$td_img3,
					"td_img4"=>$td_img4,
					"td_img5"=>$td_img5,
					"td_thumb"=>$td_thumb,
					"td_category"=>arr_implode(",",$td_category),
					"td_hash"=>arr_implode(",",$td_hash),
					"td_desc"=>$td_desc,
					"td_price"=>$td_price,
					"td_price_req_yn"=>$td_price_req_yn,
				];

				$result = $this->site_model->updateTrade($info,["td_idx"=>$td_idx]);

				if($result["code"]!="00"){
					$this->db->trans_rollback();
					json_return("91","[01]중고 거래 등록시 오류가 발생하였습니다.");
				}

				$result["key"] = $td_idx;

				# 기존지역 삭제

				$this->member_model->deleteAddress(["ad_type"=>"TRADE","ad_key"=>$td_idx]);
				foreach($ad_zipcode as $idx=>$row){
					$arr_addr = [
	            		"mm_idx"=>$mm_idx,
	            		"ad_type"=>"TRADE",
	            		"ad_key"=>$td_idx,
	            		"ad_zipcode"=>$ad_zipcode[$idx],
	            		"ad_jibun"=>$ad_jibun[$idx],
	            		"ad_doro"=>$ad_doro[$idx],
	            		"ad_detail"=>"",
	            		"ad_addr"=>$ad_addr[$idx],
	            		"ad_gps_x"=>$ad_gps_x[$idx],
	            		"ad_gps_y"=>$ad_gps_y[$idx],
	            	];

					$result_addr = $this->member_model->insertAddress($arr_addr);
					if($result_addr["code"]!="00"){
						$this->db->trans_rollback();
						json_return("92","[02]중고 거래 등록시 오류가 발생하였습니다.");
					}
				}

				$this->db->trans_commit();
				json_return("00","등록완료",["key"=>$result["key"]]);
			}
		}

		# 상품 판매 종료
		function trade_status_proc(){
            $td_idx = input_post("td_idx");
            $mm_idx = $this->user->getUserIdx();

			$trade_info = $this->site_model->getTrade(["td_idx"=>$td_idx]);
			if($trade_info==null){
				json_return("90","상품 정보를 조회 할수 없습니다.");
			}

			if($trade_info["mm_idx"]!=$mm_idx){
				json_return("91","본인 상품만 수정할 수 있습니다.");
			}

			$result = $this->site_model->updateTrade(["td_status"=>"N"],["td_idx"=>$td_idx]);
			json_return($result["code"],$result["code"]=="00" ? "처리완료":"상품정보 수정시 오류가 발생하였습니다.");
		}

		# 메인화면 - 웹용
		function search_web_list(){
			$addr_info = $this->user->getAddrInfo();
            $mm_idx = $this->user->getUserIdx();
	        $page = input_post("page",1);
			$pagesize = input_post("pagesize",10);
	        $text = input_post("text","");
			$addr1 = input_post("addr1","");
			$addr2 = input_post("addr2","");
			$addr3 = input_post("addr3","");
	        $category = input_post("category","");
	        $sprice = input_post("sprice","");
	        $eprice = input_post("eprice","");
			$order_by = input_post("order_by","recent");

            $params = [
	            "pagesize" => $pagesize,
	            "page" => $page,
	            "text" => $text,
	            "mm_idx" => $mm_idx,
	            "addr1" => $addr1,
	            "addr2" => $addr2,
	            "addr3" => $addr3,
	            "category" => $category,
	            "sprice" => $sprice,
	            "eprice" => $eprice,
	            "order_by" => $order_by,
	        ];

	        $result = $this->site_model->getTradeWebSearchList($params);

			json_return("00","조회성공",$result);
		}
    }
?>
