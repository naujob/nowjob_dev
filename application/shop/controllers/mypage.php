<?php
  class Mypage extends CI_Controller{
    # 전역변수 선언
    public $mm_idx;
    public $mm_id;
    public $mm_type;

    # 생성자
    function __construct() {
      parent::__construct();

      $this->load->model('membermodel','member_model');
      $this->load->model('sitemodel','site_model');
      $this->load->model('settingmodel','setting_model');
      $this->load->model('ordermodel','order_model');
      $this->load->model('boardmodel','board_model');
      $this->load->model('recruitmodel','recruit_model');

      # 로그인확인후 이동처리
      if($this->user->isLogin()==false){
        go_location("/member/login");
      }

      $this->mm_id = $this->user->getUserId();
      $this->mm_idx = $this->user->getUserIdx();
      $this->mm_type = $this->user->getUserType();

      # 로그인확인후 이동처리
      if($this->mm_type!="MEM"){
        go_location("/recruit/job_list", "개인회원 전용 서비스입니다.");
      }

      # 자동 쿠폰 발급 처리
      if($this->mm_idx!=""){
        $this->site_model->getAutoRegistCoupon($this->mm_idx);
      }
    }

		# 분기 처리 회원,사업자
		function index(){
			$data = [];

      $gbn = $this->input->get('gbn');

			$data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);
			$data["hit_cnt"] = $data["mem_info"]["hit_cnt"];
			$data["req_cnt"] = $data["mem_info"]["req_cnt"];
			$data["fav_cnt"] = $data["mem_info"]["fav_cnt"];
      $data["enc_data"] = $this->member_model->getNiceEncData();
      $data["gbn"] = $gbn;

			# 자동 쿠폰 발급 처리
			$this->site_model->getAutoRegistCoupon($this->mm_idx);

      # 웹접속인경우 경로 이동처리
      if(device_type()=="W"){
          //go_location("/mypage/point_charge_step1");
          go_location("/mypage/mypage");
      }

			$this->layout->shop('/mypage/index',$data);
		}
    
    # 마이페이지
		function mypage($reqgbn = null){
      $data = [];

			$data["info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
      $data["enc_data"] = $this->member_model->getNiceEncData();
      $data["reqgbn"] = $reqgbn;
			$this->layout->shop('/mypage/mypage',$data);
		}
    
    # 마이페이지(검토용)
		function index_new(){
			$data = [];

      $gbn = $this->input->get('gbn');

			$data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);
			$data["hit_cnt"] = $data["mem_info"]["hit_cnt"];
			$data["req_cnt"] = $data["mem_info"]["req_cnt"];
			$data["fav_cnt"] = $data["mem_info"]["fav_cnt"];
      $data["enc_data"] = $this->member_model->getNiceEncData();
      $data["gbn"] = $gbn;

			# 자동 쿠폰 발급 처리
			$this->site_model->getAutoRegistCoupon($this->mm_idx);

      # 웹접속인경우 경로 이동처리
      if(device_type()=="W"){
          //go_location("/mypage/point_charge_step1");
          go_location("/mypage/mypage_new");
      }

			$this->layout->shop('/mypage/index_new',$data);
		}    
    
    # 마이페이지(검토용)
		function mypage_new($reqgbn = null){
      $data = [];

			$data["info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
      $data["enc_data"] = $this->member_model->getNiceEncData();
      $data["reqgbn"] = $reqgbn;
			$this->layout->shop('/mypage/mypage_new',$data);
		}

		# 포인트 충전 스텝1
		function point_charge_step1(){
			$data = [];

			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$data["point_list"] = $this->setting_model->getCodeList(["cd_grp"=>"구매포인트","use_yn"=>"Y"]);

			#가입 당일 구입시 30% 할인추가.
			$mem_info = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$reg_dt = substr($mem_info["reg_dt"],0,10);

			if($reg_dt==Date('Y-m-d')){
				foreach($data["point_list"] as $key=>$row){
					$data["point_list"][$key]["cd_val"] = $data["point_list"][$key]["cd_val"] - round($data["point_list"][$key]["cd_val"]*0.3);
				}
			}

			$this->layout->shop('/mypage/point_charge_step1',$data);
		}

		# 포인트 충전 스텝2
		function point_charge_step2($reqidx = 0){
			$data = [];

			# 데이터 검증및 뒤로가기 처리
			//$is_check = $this->site_model->checkSiteLog("POINT_ORDER","스텝1");
			//if($is_check==false){
			//	$this->common->scriptReplace("/mypage/point_charge_step1","정상적인 결제 진행이 아닙니다. 포인트 결제를 다시 진행해주세요.");
			//}

			# 자동 쿠폰 발급 처리
			$this->site_model->getAutoRegistCoupon($this->mm_idx);

			$info = $this->site_model->getStepTempData("POINT_ORDER","스텝1");
			$data["point_key"] = $info["point_key"];
			$data["point_price"] = $info["point_price"];

      if($reqidx != "" && $reqidx != "0" ){

        $query_point  = "select * from st_coupon_list ";
        $query_point .= "where cl_idx = '".$reqidx."' ";

        $result_point = $this->db->query($query_point);
        $count_point = $result_point->row_array();
        if($count_point!=null){
          $cnt_point = $count_point["cl_price"];
        }

          if($cnt_point < 2000){
            $data["point_key"] = "P001";
            $data["point_price"] = "1000";
          }else if($cnt_point < 5000){
            $data["point_key"] = "P002";
            $data["point_price"] = "2000";
          }else if($cnt_point < 10000){
            $data["point_key"] = "P003";
            $data["point_price"] = "5000";
          }else if($cnt_point < 50000){
            $data["point_key"] = "P004";
            $data["point_price"] = "10000";
          }else if($cnt_point < 100000){
            $data["point_key"] = "P005";
            $data["point_price"] = "50000";
          }else{
            $data["point_key"] = "P006";
            $data["point_price"] = "100000";
          }
      }

			# 사용가능 쿠폰조회
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		       	"cp_idx" => "",
		        "search_type" => "",
		        "search_value" => "",
		        "mm_idx" => $this->mm_idx,
		        "cl_use_yn" => "N",
		        "cl_limit_price" => $data["point_price"],
		    ];
			$result = $this->site_model->getCouponListList($params);

			$data["coupon_list"] = $result["list"];
			$data["coupon_count"] = $this->site_model->getCouponListCount($this->mm_idx,"","N");
			$data["valid_count"] = $this->site_model->getCouponListCount($this->mm_idx,$data["point_price"],"N");
			$data["pg_info"] = $this->order_model->getPgInfo();
      $data["reqidx"] = "".$reqidx;


			$this->layout->shop('/mypage/point_charge_step2',$data);
		}

		# 포인트 충전 스텝3
		function point_charge_step3($o_num){
			$data = [];

			$this->common->removeSession("ORDER_PROC");

			if($o_num==null){
				go_location("/mypage/point_charge_step1","정상적인 결제 진행아 아닙니다. 포인트 결제를 다시 진행해주세요.");
			}

			$order_info = $this->order_model->getOrderMaster($o_num);

			if($order_info==null){
				go_location("/mypage/point_charge_step1","정상적인 결제 진행아 아닙니다. 포인트 결제를 다시 진행해주세요.");
			}

			$data["order_info"] = $order_info;

			$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$order_info["o_goods_code"]]);
			$data["point_info"] = $point_info;

			$this->layout->shop('/mypage/point_charge_step3',$data);
		}

		# 포인트 충전 리스트
		function point_charge_list(){
			$data = [];
			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$data["order_list"] = $this->order_model->getChargePointList($this->mm_idx);
			$this->layout->shop('/mypage/point_charge_list',$data);
		}

		# 포인트 사용 리스트
		function point_use_list(){
			$data = [];
			$data["use_list"] = $this->order_model->getUsePointList($this->mm_idx);
			$this->layout->shop('/mypage/point_use_list',$data);
		}

		# 광고 구매
		function point_promotion_buy(){
			$data = [];

			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$data["point1"] = $this->setting_model->getCodeInfo(["cd_key"=>"M001"]);
			$data["point2"] = $this->setting_model->getCodeInfo(["cd_key"=>"M002"]);
      $data["point3"] = $this->setting_model->getCodeInfo(["cd_key"=>"M003"]);
			$data["addr_list"]  = $this->member_model->getAddressList(["ad_type"=>"MEMBER","mm_idx"=>$this->mm_idx,"ad_key"=>$this->mm_idx]);
			$data["promotion_list"] = $this->setting_model->getCodeList(["cd_grp"=>"광고구매"]);
      $data["resume_list"] = $this->member_model->getResumeList(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);
			$this->layout->shop('/mypage/point_promotion_buy',$data);
		}

		# 광고 구매 진행.
		function point_promotion_payment($type){
			$data = [];
			$sdate = "";
			$edate = "";
			if($type==null){
				go_location("/mypage/point_promotion_buy","잘못된 접근입니다.");
			}

			$promotion_info = $this->setting_model->getCodeInfo(["cd_grp"=>"광고구매","cd_key"=>$type]);
			if($promotion_info==null){
				go_location("/mypage/point_promotion_buy","잘못된 접근입니다.");
			}

			$data["type"] = $type;
			$data["addr_list"]  = $this->member_model->getAddressList(["ad_type"=>"MEMBER","mm_idx"=>$this->mm_idx,"ad_key"=>$this->mm_idx]);
			$data["mem_info"] = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$data["promotion_info"] = $promotion_info;

      $data["resume_list"] = $this->member_model->getResumeList(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y","ru_show_yn"=>"Y"]);

			$this->layout->shop('/mypage/point_promotion_payment',$data);
		}

		# 광고 관리
		function point_promotion_list(){
			$data = [];
			$data["promotion_list"] = $this->site_model->getPromotionList(["mm_idx"=>$this->mm_idx,"pm_status"=>"Y"]);
			$this->layout->shop('/mypage/point_promotion_list',$data);
		}

		# 이력서 리스트
		function resume_list(){
			$data = [];

			$data["resume_list"] = $this->member_model->getResumeList(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);
			$this->layout->shop('/mypage/resume_list',$data);
		}

		# 이력서 리스트(검토용)
		function resume_list_new(){
			$data = [];

			$data["resume_list"] = $this->member_model->getResumeList(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);
			$this->layout->shop('/mypage/resume_list_new',$data);
		}
    
		# 이력서 상세(검토용)
		function resume_view_new($ru_idx = 0){
			$data = [];
			$info = $this->member_model->getResumeDetail($ru_idx,$this->mm_idx);

			if($info==null){
				go_location("/mypage/resume_list","이력서 정보를 조회 하지 못하였습니다.");
			}

			$data["info"] = $info;
			$data["addr"] = $this->member_model->getAddressList(["ad_type"=>"RESUME","ad_key"=>$ru_idx]);
			$data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);
			$data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx"=>$info["ru_idx"]]);
			$data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx"=>$info["ru_idx"]]);
			$this->layout->shop('/mypage/resume_view_new',$data);
		}

		# 이력서 상세
		function resume_view($ru_idx = 0){
			$data = [];
			$info = $this->member_model->getResumeDetail($ru_idx,$this->mm_idx);

			if($info==null){
				go_location("/mypage/resume_list","이력서 정보를 조회 하지 못하였습니다.");
			}

			$data["info"] = $info;
			$data["addr"] = $this->member_model->getAddressList(["ad_type"=>"RESUME","ad_key"=>$ru_idx]);
			$data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);
			$data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx"=>$info["ru_idx"]]);
			$data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx"=>$info["ru_idx"]]);
			$this->layout->shop('/mypage/resume_view',$data);
		}

    # 웹용 이력서 등록(검토용)
		function resume_write_new($ru_idx = 0){
			$data = [];
			$proc_mode = "";

			# 이력서 갯수 확인후 처리
			$count = $this->member_model->getResumeCount(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);

			if($ru_idx == 0 && $count >=5){
        //2024.09.01 5개 제한 해제
				go_location("/mypage/resume_list","이력서는 최대 5개 까지만 등록가능합니다.");
			}

			# 데이터 조회
			$info = [
				"ru_idx"=>$ru_idx,
				"ru_name"=>"",
				"ru_work"=>"",
				"ru_addr"=>"",
				"ru_price_type"=>"",
				"ru_work_type"=>"",
			];
			$resume_info = $this->member_model->getResume(["ru_idx"=>$ru_idx]);

			if($ru_idx !=0 && $resume_info==null){
				go_location("/mypage/resume_list","이력서가 존재하지 않습니다.");
			}

			# 데이터 리스트
			$data["info"]  = $resume_info;
			$data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx"=>$info["ru_idx"]]);
			$data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx"=>$info["ru_idx"]]);     
      $data["addr_list"] = $this->member_model->getAddressList(["ad_type"=>"RESUME","mm_idx"=>$this->mm_idx,"ad_key"=>$ru_idx]);
			$data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
			$data["ru_work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
			$data["ru_week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
      $data["ru_edu_list"]  = $this->site_model->getCodeList(["cd_grp"=>"최종학력"]);
      $data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_my_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"나의강점"]);
      $data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);

			$this->layout->shop('/mypage/resume_write_new',$data);
		}
    
    # 웹용 이력서 등록
		function resume_write($ru_idx = 0){
			$data = [];
			$proc_mode = "";

			# 이력서 갯수 확인후 처리
			$count = $this->member_model->getResumeCount(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);

			if($ru_idx == 0 && $count >=5){
        //2024.09.01 5개 제한 해제
				go_location("/mypage/resume_list","이력서는 최대 5개 까지만 등록가능합니다.");
			}

			# 데이터 조회
			$info = [
				"ru_idx"=>$ru_idx,
				"ru_name"=>"",
				"ru_work"=>"",
				"ru_addr"=>"",
				"ru_price_type"=>"",
				"ru_work_type"=>"",
			];
			$resume_info = $this->member_model->getResume(["ru_idx"=>$ru_idx]);

			if($ru_idx !=0 && $resume_info==null){
				go_location("/mypage/resume_list","이력서가 존재하지 않습니다.");
			}

			# 데이터 리스트
			$data["info"]  = $resume_info;
			$data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx"=>$info["ru_idx"]]);
			$data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx"=>$info["ru_idx"]]);     
      $data["addr_list"] = $this->member_model->getAddressList(["ad_type"=>"RESUME","mm_idx"=>$this->mm_idx,"ad_key"=>$ru_idx]);
			$data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
			$data["ru_work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
			$data["ru_week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
      $data["ru_edu_list"]  = $this->site_model->getCodeList(["cd_grp"=>"최종학력"]);
      $data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_my_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"나의강점"]);
      $data["mem_info"] = $this->member_model->getMemberInfo($this->mm_idx);

			$this->layout->shop('/mypage/resume_write',$data);
		}

		# 이력서 등록1
		function resume_write1($ru_idx = 0){
			$data = [];
			$proc_mode = "";

			# 이력서 갯수 확인후 처리
			$count = $this->member_model->getResumeCount(["mm_idx"=>$this->mm_idx,"ru_status"=>"Y"]);

			if($ru_idx == 0 && $count >=5){
				go_location("/mypage/resume_list","이력서는 최대 5개 까지만 등록가능합니다.");
			}

			# 데이터 조회
			// 회원의 국적 정보 불러오기
			$mem_info = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$info = [
				"ru_idx"=>$ru_idx,
				"ru_name"=>"",
				"ru_work"=>"",
				"ru_addr"=>"",
				"ru_price_type"=>"",
				"ru_work_type"=>"",
				"ru_desc"=>"",
				"ru_my_type"=>"",
				"ru_week_yn"=>"N",
				"ru_week"=>"",
				"arr_career"=>"",
				"arr_certi"=>"",
				"ru_nationality"=>isset($mem_info["mm_nation"]) ? $mem_info["mm_nation"] : "내국인", // 회원의 국적 정보 불러오기
			];
			$resume_info = $this->member_model->getResume(["ru_idx"=>$ru_idx]);

			if($ru_idx !=0 && $resume_info==null){
				go_location("/mypage/resume_list","이력서가 존재하지 않습니다.");
			}

			if($resume_info!=null){
				$info["ru_name"] = $resume_info["ru_name"];
				$info["ru_work"] = array_map('trim', explode(",",$resume_info["ru_work"]));
				$info["ru_addr"] = $resume_info["ru_addr"];
				$info["ru_price_type"] = array_map('trim', explode(",",$resume_info["ru_price_type"]));
				$info["ru_work_type"] = array_map('trim', explode(",",$resume_info["ru_work_type"]));
				$info["ru_desc"] = $resume_info["ru_desc"];
				$info["ru_my_type"] = isset($resume_info["ru_my_type"]) ? array_map('trim', explode(",", $resume_info["ru_my_type"])) : [];
				$info["ru_week_yn"] = isset($resume_info["ru_week_yn"]) ? $resume_info["ru_week_yn"] : "N";
				$info["ru_week"] = isset($resume_info["ru_week"]) ? $resume_info["ru_week"] : "";
				// 이력서가 있으면 회원의 국적 정보 사용 (이미 위에서 설정됨)

				$data["addr_list"] = $this->member_model->getAddressList(["ad_type"=>"RESUME","mm_idx"=>$this->mm_idx,"ad_key"=>$ru_idx]);

				$arr_career = "";
				$career_list = $this->member_model->getResumeCareerList(["ru_idx"=>$ru_idx]);
				if($career_list!=null){
					foreach($career_list as $row){
						if($arr_career==""){
							$arr_career = $row["rc_type"] . "|||" . $row["rc_name"] . "|||" . $row["rc_date"];
						}else{
							$arr_career .= "&&&" . $row["rc_type"] . "|||" . $row["rc_name"] . "|||" . $row["rc_date"];
						}
					}
					$info["arr_career"] = $arr_career;
				}

				$arr_certi = "";
				$certi_list = $this->member_model->getResumeCertiList(["ru_idx"=>$ru_idx]);
				if($certi_list!=null){
					foreach($certi_list as $row){
						if($arr_certi==""){
							$arr_certi = $row["rc_name"] . "|||" . $row["rc_dt"];
						}else{
							$arr_certi .= "&&&" . $row["rc_name"] . "|||" . $row["rc_dt"];
						}
					}
					$info["arr_certi"] = $arr_certi;
				}
			}else{
				$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx,"RESUME","스텝1");
				if($temp_info!=null){
					$info["ru_name"] = $temp_info["ru_name"];
					$info["ru_work"] = $temp_info["ru_work"];
					$info["ru_addr"] = $temp_info["ru_addr"];
					$info["ru_price_type"] = $temp_info["ru_price_type"];
					$info["ru_work_type"] = $temp_info["ru_work_type"];
					// 임시 저장된 국적 정보가 있으면 사용
					if(isset($temp_info["ru_nationality"]) && $temp_info["ru_nationality"] != ""){
						$info["ru_nationality"] = $temp_info["ru_nationality"];
					}

					$data["addr_list"] = [];
					foreach($temp_info["ad_zipcode"] as $idx => $row){
						$data["addr_list"][] = [
							"ad_zipcode"=>$temp_info["ad_zipcode"][$idx],
							"ad_jibun"=>$temp_info["ad_jibun"][$idx],
							"ad_doro"=>$temp_info["ad_doro"][$idx],
							"ad_detail"=>$temp_info["ad_detail"][$idx],
							"ad_addr"=>$temp_info["ad_addr"][$idx],
							"ad_gps_x"=>$temp_info["ad_gps_x"][$idx],
							"ad_gps_y"=>$temp_info["ad_gps_y"][$idx],
						];
					}
				}else{
					$add_info = $this->member_model->getMemberInfo($this->mm_idx);
					if($add_info!=null){
						$info["ru_name"] = "";
						$info["ru_work"] = explode(",",$add_info["ma_work"]);
						$info["ru_addr"] = "";
						$info["ru_price_type"] = explode(",",$add_info["ma_price_type"]);
						$info["ru_work_type"] = explode(",",$add_info["ma_work_type"]);
						$data["addr_list"]  = $this->member_model->getAddressList(["ad_type"=>"MEMBER","mm_idx"=>$this->mm_idx,"ad_key"=>$this->mm_idx]);
					}else{
						$data["addr_list"] = [];
					}
				}
			}

			# 데이터 리스트
			$data["info"]  = $info;

			$data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_price_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망급여"]);
			$data["ru_work_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무형태"]);
			$data["ru_week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);
			$data["ru_my_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"나의강점"]);

			$this->layout->shop('/mypage/resume_write1',$data);
		}

		# 이력서 등록2
		function resume_write2($ru_idx = 0){
			$data = [];

			# 데이터 검증및 뒤로가기 처리
			$is_check = $this->site_model->checkSiteLog("RESUME","스텝1");
			if($is_check==false){
				//go_location("/mypage/resume_list","데이터 가 유효하지 않습니다. 이력서 등록을 다시 진행해주세요.");
				$this->common->scriptReplace("/mypage/index");
			}

			# 데이터 조회
			$info = [
				"ru_idx"=>$ru_idx,
				"ru_week"=>"ru_name",
				"ru_week_yn"=>"",
				"ru_work_dt"=>"",
				"ru_edu"=>"",
			];
			$resume_info = $this->member_model->getResume(["ru_idx"=>$ru_idx]);

			if($ru_idx !=0 && $resume_info==null){
				go_location("/mypage/resume_list","이력서가 존재하지 않습니다.");
			}

			if($resume_info!=null){
				$info["ru_week"] = explode(",",$resume_info["ru_week"]);
				$info["ru_week_yn"] = $resume_info["ru_week_yn"];
				$info["ru_work_dt"] = $resume_info["ru_work_dt"];
				$info["ru_edu"] = $resume_info["ru_edu"];
			}else{
				$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx,"RESUME","스텝2");
				if($temp_info!=null){
					$info["ru_week"] = $temp_info["ru_week"];
					$info["ru_week_yn"] = $temp_info["ru_week_yn"];
					$info["ru_work_dt"] = $temp_info["ru_work_dt"];
					$info["ru_edu"] = $temp_info["ru_edu"];
				}else{
					$add_info = $this->member_model->getMemberInfo($this->mm_idx);
					if($add_info!=null){
						$info["ru_week"] = explode(",",$add_info["ma_week"]);
						$info["ru_week_yn"] = $add_info["ma_week_yn"];
						$info["ru_work_dt"] = $add_info["ma_work_dt"];
						$info["ru_edu"] = $add_info["ma_edu"];
					}
				}
			}

			# 데이터 리스트
			$data["info"] = $info;
			$data["ru_edu_list"]  = $this->site_model->getCodeList(["cd_grp"=>"최종학력"]);
			$data["ru_week_list"]  = $this->site_model->getCodeList(["cd_grp"=>"근무요일"]);

			$this->layout->shop('/mypage/resume_write2',$data);
		}

		# 이력서 등록3
		function resume_write3($ru_idx = 0){
			$data = [];

			# 데이터 검증및 뒤로가기 처리
			$is_check = $this->site_model->checkSiteLog("RESUME","스텝2");
			if($is_check==false){
				//go_location("/mypage/resume_list","데이터 가 유효하지 않습니다. 이력서 등록을 다시 진행해주세요.");
				$this->common->scriptReplace("/mypage/index");
			}

			# 데이터 조회
			$info = [
				"ru_idx"=>$ru_idx,
				"arr_career"=>"",
				"ru_my_type"=>"",
			];
			$resume_info = $this->member_model->getResume(["ru_idx"=>$ru_idx]);

			if($ru_idx !=0 && $resume_info==null){
				go_location("/mypage/resume_list","이력서가 존재하지 않습니다.");
			}

			if($resume_info!=null){
				$arr_career = "";
				$career_list = $this->member_model->getResumeCareerList(["ru_idx"=>$ru_idx]);

				foreach($career_list as $row){
					if($arr_career==""){
						$arr_career = $row["rc_type"] . "|||" . $row["rc_name"] . "|||" . $row["rc_date"];
					}else{
						$arr_career .= "&&&" . $row["rc_type"] . "|||" . $row["rc_name"] . "|||" . $row["rc_date"];
					}
				}

				$info["arr_career"] = $arr_career;
				$info["ru_my_type"] = explode(",",$resume_info["ru_my_type"]);
			}else{
				$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx,"RESUME","스텝3");
				if($temp_info!=null){
					$info["arr_career"] = $temp_info["arr_career"];
					$info["ru_my_type"] = $temp_info["ru_my_type"];
				}else{
					$add_info = $this->member_model->getMemberInfo($this->mm_idx);
					if($add_info!=null){
						$arr_career = "";
						$career_list = $this->member_model->getMemberCareerList(["mm_idx"=>$this->mm_idx]);

						$info["arr_career"] = "";
						if($career_list!=null){
							foreach($career_list as $row){
								if($arr_career==""){
									$arr_career = $row["mc_type"] . "|||" . $row["mc_name"] . "|||" . $row["mc_date"];
								}else{
									$arr_career .= "&&&" . $row["mc_type"] . "|||" . $row["mc_name"] . "|||" . $row["mc_date"];
								}
							}
							$info["arr_career"] = $arr_career;
						}
						$info["ru_my_type"] = $add_info["ma_my_type"];
					}
				}
			}

			# 데이터 리스트
			$data["info"] = $info;
			$data["ru_wrok_list"]  = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$data["ru_my_type_list"]  = $this->site_model->getCodeList(["cd_grp"=>"나의강점"]);

			$this->layout->shop('/mypage/resume_write3',$data);
		}

		# 이력서 등록4
		function resume_write4($ru_idx = 0){
			$target = ($ru_idx && $ru_idx != 0) ? "/mypage/resume_write1/" . $ru_idx : "/mypage/resume_write1";
			$this->common->scriptReplace($target);
		}

		# 북마크 공고(검토용)
		function bookmark_recruit1_new(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
		  ];
      
			$result = $this->recruit_model->getBookMarkRecruitList($params);

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];
      
			$this->layout->shop('/mypage/bookmark_recruit1_new',$data);
		}
    
		# 제안받은 공고(검토용)
		function bookmark_recruit2_new(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
		  ];
      
			$result = $this->recruit_model->getBookMarkRequestRecruitList($params);

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];
      
			$this->layout->shop('/mypage/bookmark_recruit2_new',$data);
		}

		# 지원한 공고(검토용)
		function recruit_list_new(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
      ];
			$result = $this->recruit_model->getMemRequestRecruitList($params);

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];
			$this->layout->shop('/mypage/recruit_list_new',$data);
		}

		# 북마크 공고
		function bookmark_recruit1(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
		  ];

			$result = $this->recruit_model->getBookMarkRecruitList($params);

			$temp_list = $result["list"];
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
			}
			$result["list"] = $temp_list;

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];

			$this->layout->shop('/mypage/bookmark_recruit1',$data);
		}
    
		# 제안받은 공고
		function bookmark_recruit2(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
		  ];

			$result = $this->recruit_model->getBookMarkRequestRecruitList($params);

			$temp_list = $result["list"];
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
			}
			$result["list"] = $temp_list;

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];

			$this->layout->shop('/mypage/bookmark_recruit2',$data);
		}

		# 지원한 공고
		function recruit_list(){
      $data = [];

			$params = [
        "pagesize" => 200,
        "page" => 1,
        "mm_idx"=>$this->mm_idx,
      ];
			$result = $this->recruit_model->getMemRequestRecruitList($params);

			$temp_list = $result["list"];
			foreach ($temp_list as $key => $row) {
				$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
			}
			$result["list"] = $temp_list;

			$data["resume"] = $this->member_model->getResume(["mm_idx"=>$this->mm_idx,"ru_show_yn"=>"Y"]);
      $data["list"] = $result["list"];
			$data["count"] = $result["count"];
			$this->layout->shop('/mypage/recruit_list',$data);
		}

		# 즐겨찾기 관리
		function bookmark_list(){
            $data = [];

            //데이터 설정
			$params = [
		        "pagesize" => 200,
		        "page" => 1,
                "mm_idx"=>$this->mm_idx,
		    ];
			$result = $this->member_model->getBookMarkTradeList($params);

            $data["list"] = $result["list"];
			$data["count"] = $result["count"];
			$this->layout->shop('/compage/bookmark_list',$data);
		}

		function coupon_list(){
      $gbn = $this->input->get('gbn');
			$data = [];

			//데이터 설정
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		       	"cp_idx" => "",
		        "search_type" => "",
		        "search_value" => "",
		        "mm_idx" => $this->mm_idx,
            "cl_use_yn" => $gbn,
		        //"cl_use_yn" => "N",
		    ];
			$result = $this->site_model->getCouponListList($params);

			$data["coupon_list"] = $result["list"];
			$data["coupon_count"] = $result["count"];
			$this->layout->shop('/mypage/coupon_list',$data);
		}

		function trade_list(){
			$data = [];

			//데이터 설정
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		        "mm_idx" => $this->mm_idx,
		    ];
			$result = $this->site_model->getTradeSearchListV2($params);

			$data["result_list"] = $result["list"];
			$data["result_count"] = $result["count"];
			$this->layout->shop('/mypage/trade_list',$data);
		}

		function trade_edit($td_idx = 0){
			$data = [];

			# 로그인확인후 이동처리
			if($this->user->isLogin()==false){
				go_back("로그인후 이용 가능합니다.");
			}

			$mm_idx = $this->user->getUserIdx();
			$trade_info = $this->site_model->getTrade(["td_idx"=>$td_idx]);

			if($td_idx !=0 && $trade_info==null){
				go_location("/trade","잘못된 접근입니다.");
			}

			$data["info"] = $trade_info;
			$data["addr_list"]  = $this->member_model->getAddressList(["ad_type"=>"TRADE","mm_idx"=>$mm_idx,"ad_key"=>$td_idx]);
			$data["td_category_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고카테고리"]);
			$data["td_hash_list"]  = $this->site_model->getCodeList(["cd_grp"=>"중고해시태그"]);

      if($this->common->getLocation()=="W"){
				$data["term1"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_goods_pc"]);
				$data["term2"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_term_pc"]);
			}else{
				$data["term1"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_goods_m"]);
				$data["term2"] = $this->setting_model->getSettingInfo(["st_group"=>"이용약관","st_key"=>"trade_term_m"]);
			}

			$this->layout->shop('/mypage/trade_edit',$data);
		}

		# 문의하기 리스트
		function qna_list(){
			$page = input_get("page",1);
			$tb_id = "report";
			$sort = input_get("sort","reg_dt");
			$mm_id_yn = input_get("mm_id_yn","N");

			$data = [];

			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => "",
		        "search_value" => "",
		        "bd_aws_yn" => "",
		        "tb_id" => $tb_id,
		        "sort" => $sort,
		        "mm_id" => $this->mm_id,
		    ];

			$result = $this->board_model->getBoardList($params);

			//리턴 설정
			$data = [
				"sort"=>$sort,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
                "pagging"=> $result["pagenation"]
			];

			$this->layout->shop('/mypage/qna_list',$data);
		}

		# 문의하기 상세
		function qna_view($bd_idx){
			$data = [];
			$date_status = input_get("date_status","ing");

			if($bd_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$this->board_model->updateBoard(["bd_hit"=>$data["info"]["bd_hit"] + 1],["bd_idx"=>$bd_idx]);
			$this->layout->shop('/mypage/qna_view',$data);
		}

		# 문의하기 등록
		function qna_write($bd_idx = 0){
			$data = [];

			$data["info"] = $this->board_model->getBoard($bd_idx);
			$this->layout->shop('/mypage/qna_write',$data);
		}

		# 문의하기 리스트
		function my_report_list(){
			$page = input_get("page",1);

			$data = [];
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "mm_id" => ($mm_id_yn=="Y" ? $mm_id:"")
		    ];

			$result = $this->board_model->getMyReportList($params);

			//리턴 설정
			$data = [
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
			];

			$this->layout->shop('/mypage/my_report_list',$data);
		}

		# 문의하기 상세
		function my_report_view($br_idx){
			$data = [];
			if($br_idx==null){
				$this->common->goBack("잘못된 접근입니다.");
			}

			$data["info"] = $this->board_model->getBoardReport($br_idx);
			$this->layout->shop('/mypage/my_report_view',$data);
		}

		# A.I 매칭 리스트 (개인회원)
		function match_list()
		{
			$data = [];

			$page = (int) $this->input->get('page');
			if ($page < 1) $page = 1;

			// 매칭 리스트 열람 시 안읽음 상태 해제
			$this->member_model->markMatchRecruitAsRead($this->mm_idx);

			$result = $this->member_model->getMatchRecruitListByDate($this->mm_idx, $page, 3, 14);

			$data["groups"] = $result["groups"];
			$data["page"] = $result["page"];
			$data["total_pages"] = $result["total_pages"];
			$data["user_type"] = $this->user->getUserType();
			$this->layout->shop('/mypage/match_list', $data);
		}

  }
?>
