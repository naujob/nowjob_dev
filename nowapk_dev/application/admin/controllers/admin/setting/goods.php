<?php
    class Goods extends MY_Controller{
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
	    } 
		
		//상품설정
		public function goods_setting(){
			//파라미터 설정
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingEtcInfo(),
			];
			$this->layout->admin('admin/setting/goods/goods_setting',$data);
		}
		
		//상품 설정 등록
		public function goods_setting_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingEtc($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//배송 설정
		public function dlv_setting(){
			//파라미터 설정
			$data['info'] = [];
			$this->layout->admin('admin/setting/goods/dlv_setting',$data);
		}
		
		//상품 상세 이용안내
		public function detail_setting(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","Y");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			
			//데이터 설정
			$result = $this->setting_model->getTempList($page);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/setting/goods/detail_setting',$data);
		}
		
		//상품 상세 이용안내 등록/수정
		public function detail_setting_edit(){
			//파라미터 설정
			$data['info'] = [];
			$this->layout->admin('admin/setting/goods/detail_setting_edit',$data);
		}
		
		//도서산간 추가배송비 설정
		public function dlv_add_setting(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","Y");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			
			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"use_yn" => $use_yn,
			];
			$result = $this->setting_model->getDlvAddList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/setting/goods/dlv_add_setting',$data);
		}
		
		//도서산간 추가배송비 설정
		public function dlv_add_setting_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			$data['info'] = $this->setting_model->getDlvAddInfo($idx);
			
			//리턴 처리
			$this->layout->admin('admin/setting/goods/dlv_add_setting_edit',$data);
		}
		
		//도서산간 추가배송비  등록,수정 처리
		public function dlv_add_setting_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			
			$proc_mode = $params["proc_mode"];
			unset($params["proc_mode"]);
			if($proc_mode=="insert"){
				//저장
	
				$result = $this->setting_model->insertDlvAdd($params);
			}else if($proc_mode=="update"){
				//저장
				$where = ["aa_idx"=>$params["aa_idx"]];
				$result = $this->setting_model->updateDlvAdd($params,$where);
			}else if($proc_mode=="delete"){
				//저장
				$where = ["aa_idx"=>$params["aa_idx"]];
				$result = $this->setting_model->deleteDlvAdd($where);
			}
			
			json_return($result["code"],$result["msg"],"");
		}
		
    }
?>