<?php
    class Design extends MY_Controller{
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
	    } 
		
		//디자인 관리
		public function setting(){
			//파라미터 설정
			$data['list'] = $this->setting_model->getSettingDesignList();
			$this->layout->admin('admin/setting/design/setting',$data);
		}
		
		//디자인 관리
		public function setting_edit(){
			$idx = input_get("idx","");
			$design_type = input_get("design_type","");
			
			//파라미터 설정
			$data['info'] = $this->setting_model->getSettingInfo();
			if($design_type==""){
				$design_type = $data['info']["st_design_web"];
			}
			
			$data["design_type"] = $design_type;
			$data["design_info"] = $this->setting_model->getSettingDesign($idx);
			$this->layout->admin('admin/setting/design/setting_edit',$data);
		}
		
		//싸이트 아이콘 설정 처리
		public function setting_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			$proc_mode = $params["proc_mode"];
			
			if($proc_mode=="insert"){
				$insert_color = [
					"design_type"=>getData($params,"st_design_web",""),
					"design_desc"=>getData($params,"design_desc",""),
					"design_yn"=>"N",
					"color_1"=>getData($params,"color_1",""),
					"color_2"=>getData($params,"color_2",""),
					"color_3"=>getData($params,"color_3",""),
					"color_4"=>getData($params,"color_4",""),
					"color_5"=>getData($params,"color_5",""),
					"color_6"=>getData($params,"color_6",""),
					"color_7"=>getData($params,"color_7",""),
					"color_8"=>getData($params,"color_8",""),
					"color_9"=>getData($params,"color_9",""),
					"color_10"=>getData($params,"color_10",""),
					"color_11"=>getData($params,"color_11",""),
					"color_12"=>getData($params,"color_12",""),
					"color_13"=>getData($params,"color_13",""),
					"color_14"=>getData($params,"color_14",""),
					"color_15"=>getData($params,"color_15",""),
					"color_16"=>getData($params,"color_16",""),
					"color_17"=>getData($params,"color_17",""),
					"color_18"=>getData($params,"color_18",""),
					"color_19"=>getData($params,"color_19",""),
					"color_20"=>getData($params,"color_20",""),
					"color_21"=>getData($params,"color_21",""),
					"color_22"=>getData($params,"color_22",""),
					"color_23"=>getData($params,"color_23",""),
					"color_24"=>getData($params,"color_24",""),
					"color_25"=>getData($params,"color_25",""),
					"color_26"=>getData($params,"color_26",""),
					"color_27"=>getData($params,"color_27",""),
					"color_28"=>getData($params,"color_28",""),
					"color_29"=>getData($params,"color_29",""),
					"color_30"=>getData($params,"color_30",""),
					"color_31"=>getData($params,"color_31",""),
					"color_32"=>getData($params,"color_32",""),
					"color_33"=>getData($params,"color_33",""),
					"color_34"=>getData($params,"color_34",""),
					"color_35"=>getData($params,"color_35",""),
					"color_36"=>getData($params,"color_36",""),
					"color_37"=>getData($params,"color_37",""),
					"color_38"=>getData($params,"color_38",""),
					"color_39"=>getData($params,"color_39",""),
					"color_40"=>getData($params,"color_40",""),
					"color_41"=>getData($params,"color_41",""),
					"color_42"=>getData($params,"color_42",""),
					"color_43"=>getData($params,"color_43",""),
					"color_44"=>getData($params,"color_44",""),
					"color_45"=>getData($params,"color_45",""),
					"color_46"=>getData($params,"color_46",""),
					"color_47"=>getData($params,"color_47",""),
					"color_48"=>getData($params,"color_48",""),
					"color_49"=>getData($params,"color_49",""),
					"color_50"=>getData($params,"color_50","")
				];
				
				//저장
				$result = $this->setting_model->insertSettingDesign($insert_color);
				
				json_return($result["code"],$result["msg"],["idx"=>$result["key"]]);
			}else if($proc_mode=="update"){
				$update_color = [
					"design_type"=>getData($params,"st_design_web",""),
					"design_desc"=>getData($params,"design_desc",""),
					"color_1"=>getData($params,"color_1",""),
					"color_2"=>getData($params,"color_2",""),
					"color_3"=>getData($params,"color_3",""),
					"color_4"=>getData($params,"color_4",""),
					"color_5"=>getData($params,"color_5",""),
					"color_6"=>getData($params,"color_6",""),
					"color_7"=>getData($params,"color_7",""),
					"color_8"=>getData($params,"color_8",""),
					"color_9"=>getData($params,"color_9",""),
					"color_10"=>getData($params,"color_10",""),
					"color_11"=>getData($params,"color_11",""),
					"color_12"=>getData($params,"color_12",""),
					"color_13"=>getData($params,"color_13",""),
					"color_14"=>getData($params,"color_14",""),
					"color_15"=>getData($params,"color_15",""),
					"color_16"=>getData($params,"color_16",""),
					"color_17"=>getData($params,"color_17",""),
					"color_18"=>getData($params,"color_18",""),
					"color_19"=>getData($params,"color_19",""),
					"color_20"=>getData($params,"color_20",""),
					"color_21"=>getData($params,"color_21",""),
					"color_22"=>getData($params,"color_22",""),
					"color_23"=>getData($params,"color_23",""),
					"color_24"=>getData($params,"color_24",""),
					"color_25"=>getData($params,"color_25",""),
					"color_26"=>getData($params,"color_26",""),
					"color_27"=>getData($params,"color_27",""),
					"color_28"=>getData($params,"color_28",""),
					"color_29"=>getData($params,"color_29",""),
					"color_30"=>getData($params,"color_30",""),
					"color_31"=>getData($params,"color_31",""),
					"color_32"=>getData($params,"color_32",""),
					"color_33"=>getData($params,"color_33",""),
					"color_34"=>getData($params,"color_34",""),
					"color_35"=>getData($params,"color_35",""),
					"color_36"=>getData($params,"color_36",""),
					"color_37"=>getData($params,"color_37",""),
					"color_38"=>getData($params,"color_38",""),
					"color_39"=>getData($params,"color_39",""),
					"color_40"=>getData($params,"color_40",""),
					"color_41"=>getData($params,"color_41",""),
					"color_42"=>getData($params,"color_42",""),
					"color_43"=>getData($params,"color_43",""),
					"color_44"=>getData($params,"color_44",""),
					"color_45"=>getData($params,"color_45",""),
					"color_46"=>getData($params,"color_46",""),
					"color_47"=>getData($params,"color_47",""),
					"color_48"=>getData($params,"color_48",""),
					"color_49"=>getData($params,"color_49",""),
					"color_50"=>getData($params,"color_50","")
				];
				
				//저장
				$result = $this->setting_model->updateSettingDesign($update_color,["design_idx"=>$params["design_idx"]]);
				
				json_return($result["code"],$result["msg"],["idx"=>$params["design_idx"]]);
			}else if($proc_mode=="delete"){
				//저장
				$where = ["design_idx"=>$params["design_idx"]];
				$result = $this->setting_model->deleteSettingDesign($where);
				
				json_return($result["code"],$result["msg"],"");
			}else if($proc_mode=="change"){
				//저장
				$design_info = $this->setting_model->getSettingDesign($params["design_idx"]);
				
				//메인설정 변경
				$result = [];
				$update = [
					"st_design_web"=>$design_info["design_type"],
					"st_design_mobile"=>$design_info["design_type"],
				];
				$where = ["st_type"=>"shopping"];
				$result = $this->setting_model->updateSettingBasic($update,$where);
				
				//디자인 변경
				$result = $this->setting_model->updateSettingDesign(["design_yn"=>"N"],["1"=>"1"]);
				$result = $this->setting_model->updateSettingDesign(["design_yn"=>"Y"],["design_idx"=>$params["design_idx"]]);
				
				json_return($result["code"],$result["msg"],"");
			}
		}
    }
?>