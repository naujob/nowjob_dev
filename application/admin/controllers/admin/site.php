<?php
    class Site extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('goodsmodel','goods_model');
			$this->load->model('sitemodel','site_model');
			$this->load->model('boardmodel','board_model');
			$this->load->model('membermodel','member_model');
	    }

		//배너  그룹 관리
		function banner_group_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$bg_status = input_get("bg_status","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "bg_status" => $bg_status,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		        "hidden_yn" => "",
		    ];
			$result = $this->site_model->getBannerGroupList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"bg_status" => $bg_status,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/banner_group_list',$data);
		}

		//배너 그룹 관리
		function banner_group_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getBannerGroup(["bg_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;

		    $this->layout->admin('admin/site/banner_group_edit',$data);
		}

		//진열상품 관리  등록,수정
		function banner_group_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all(FALSE);

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
			        "bg_name" => $params["bg_name"],

			        "bg_code" => $this->common->getTblIdx("st_banner_group"),
		        	"bg_desc" => $params["bg_desc"],
			        "bg_status" => $params["bg_status"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->site_model->insertBannerGroup($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"bg_name" => $params["bg_name"],

		        	"bg_desc" => $params["bg_desc"],
			        "bg_status" => $params["bg_status"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["bg_idx"=>$params["bg_idx"]];
		        $result = $this->site_model->updateBannerGroup($update,$where);
				$primary_key = $params["bg_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["bg_idx"=>$params["idx"]];
		        $result = $this->site_model->deleteBannerGroup($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//배너 관리
		function banner_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$bg_idx = input_get("bg_idx","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 조회
		    $info = $this->site_model->getBannerGroup(["bg_idx"=>$bg_idx]);

			if($info==null){
				go_location("/admin/site/banner_group_list","잘못된 접근입니다.");
			}


			$result = $this->site_model->getBannerList(["bg_idx"=>$bg_idx]);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"info" => $info,
				"result_list" => $result,
			];

			$this->layout->admin('admin/site/banner_list',$data);
		}

		//배너 관리
		function banner_edit(){
			//파라미터 설정
			$bg_idx = input_get("bg_idx","");
			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getBanner(["bn_idx"=>$idx]);

		    $group_info = $this->site_model->getBannerGroup(["bg_idx"=>$bg_idx]);

		    //리턴 처리
		    $data["bg_idx"] = $bg_idx;
		    $data["info"] = $info;
			$data["group_info"] = $group_info;

			//브랜드 리스트
			$brand_params = [
		        "pagesize" => 1000,
		        "page" => 1,
		        "search_type" => "",
		        "search_value" => "",
		    ];

		    $this->layout->admin('admin/site/banner_edit',$data);
		}

		//배너관리  등록,수정
		function banner_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$bn_pc_img = $this->common->img_upload_url("site","bn_pc_img");
			$bn_m_img = $this->common->img_upload_url("site","bn_m_img");

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		    	$info = $this->site_model->getBannerGroup(["bg_idx"=>$params["bg_idx"]]);
		        $insert = [
			        "bg_idx" => $params["bg_idx"],
		        	"bn_name" => $params["bn_name"],
			        "bn_status" => $params["bn_status"],
			        "bn_store_yn" => "N",
			        "bn_period_type" => $params["bn_period_type"],
			        "bn_sdate" => $params["bn_sdate"],
			        "bn_edate" => $params["bn_edate"],
			        "bn_link_type" => $params["bn_link_type"],
			        "bn_link" => $params["bn_link"],
			        "bn_pc_img" => $bn_pc_img,
			        "bn_m_img" => $bn_m_img,
			        "bn_bg_color" => $params["bn_bg_color"],
			        "bn_text1" => $params["bn_text1"],
			        "bn_text2" => $params["bn_text2"],
			        "bn_text3" => isset($params["bn_text3"]) ? $params["bn_text3"]:"",
			        "bn_sort" => $params["bn_sort"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];



		        //등록
		        $result = $this->site_model->insertBanner($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		    	$info = $this->site_model->getBannerGroup(["bg_idx"=>$params["bg_idx"]]);
		        $update = [
		        	"bg_idx" => $params["bg_idx"],
		        	"bn_name" => $params["bn_name"],
			        "bn_status" => $params["bn_status"],
			        "bn_store_yn" => "N",
			        "bn_period_type" => $params["bn_period_type"],
			        "bn_sdate" => $params["bn_sdate"],
			        "bn_edate" => $params["bn_edate"],
			        "bn_link_type" => $params["bn_link_type"],
			        "bn_link" => $params["bn_link"],
			        "bn_pc_img" => $bn_pc_img,
			        "bn_m_img" => $bn_m_img,
			        "bn_bg_color" => $params["bn_bg_color"],
			        "bn_text1" => $params["bn_text1"],
			        "bn_text2" => $params["bn_text2"],
			        "bn_text3" => isset($params["bn_text3"]) ? $params["bn_text3"]:"",
			        "bn_sort" => $params["bn_sort"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["bn_idx"=>$params["bn_idx"]];
		        $result = $this->site_model->updateBanner($update,$where);
				$primary_key = $params["bn_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["bn_idx"=>$params["idx"]];
		        $result = $this->site_model->deleteBanner($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//팝업 관리
		function popup_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$pp_status = input_get("pp_status","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "pp_status" => $pp_status,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getPopupList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"pp_status" => $pp_status,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/popup_list',$data);
		}

		//배너 관리
		function popup_edit(){
			//파라미터 설정

			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getPopup(["pp_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/popup_edit',$data);
		}

		//배너관리  등록,수정
		function popup_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$pp_pc_img = $this->common->img_upload_url("site","pp_pc_img");
			$pp_m_img = $this->common->img_upload_url("site","pp_m_img");

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
			        "pp_idx" => $params["pp_idx"],
		        	"pp_name" => $params["pp_name"],
		        	"pp_type" => $params["pp_type"],
			        "pp_status" => $params["pp_status"],
			        "pp_sdate" => $params["pp_sdate"],
			        "pp_edate" => $params["pp_edate"],
			        "pp_link_type" => $params["pp_link_type"],
			        "pp_link" => $params["pp_link"],
			        "pp_link_m" => $params["pp_link_m"],
			        "pp_pc_img" => $pp_pc_img,
			        "pp_m_img" => $pp_m_img,
			        "pp_top" => $params["pp_top"],
			        "pp_left" => $params["pp_left"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->site_model->insertPopup($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"pp_idx" => $params["pp_idx"],
		        	"pp_name" => $params["pp_name"],
		        	"pp_type" => $params["pp_type"],
			        "pp_status" => $params["pp_status"],
			        "pp_sdate" => $params["pp_sdate"],
			        "pp_edate" => $params["pp_edate"],
			        "pp_link_type" => $params["pp_link_type"],
			        "pp_link" => $params["pp_link"],
			        "pp_link_m" => $params["pp_link_m"],
			        "pp_pc_img" => $pp_pc_img,
			        "pp_m_img" => $pp_m_img,
			        "pp_top" => $params["pp_top"],
			        "pp_left" => $params["pp_left"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["pp_idx"=>$params["pp_idx"]];
		        $result = $this->site_model->updatePopup($update,$where);
				$primary_key = $params["pp_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["pp_idx"=>$params["idx"]];
		        $result = $this->site_model->deletePopup($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//팝업 관리
		function push_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getPUshList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/push_list',$data);
		}

		//배너 관리
		function push_edit(){
			//파라미터 설정

			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getPush(["pu_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/push_edit',$data);
		}

		//배너관리  등록,수정
		function push_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$pu_img = $this->common->img_upload_url("site","pu_img");

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		        	"pu_adm_title" => $params["pu_adm_title"],
			        "pu_title" => $params["pu_title"],
			        "pu_desc" => $params["pu_desc"],
			        "pu_img" => $pu_img,
			        "pu_status" => "N",
		        ];

		        //등록
		        $result = $this->site_model->insertPush($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"pu_adm_title" => $params["pu_adm_title"],
		        	"pu_title" => $params["pu_title"],
			        "pu_desc" => $params["pu_desc"],
			        "pu_img" => $pu_img,
		        ];

		        //수정
		        $where = ["pu_idx"=>$params["pu_idx"]];
		        $result = $this->site_model->updatePush($update,$where);
				$primary_key = $params["pu_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["pu_idx"=>$params["idx"]];
		        $result = $this->site_model->deletePush($where);
		    }

		    json_return($result["code"],$result["msg"],["idx"=>$primary_key]);
		}

		function send_fcm(){
			$idx = input_post("idx","");

            # 타겟 토큰 가져오기
            $temp_list = $this->site_model->getFcmTargetList();
            $token_list = [];

            foreach($temp_list as $temp){
                $yoil = get_yoil(get_today());
                $time = get_today("H:i");

                $skip = false;
                if($temp["token"] !="" && $temp["alarm_deny_yn"]=="Y" && $temp["alarm_deny_yoil"]==$yoil && ($temp["alarm_deny_stime"] < $time && $temp["alarm_deny_etime"] > $time)){
                    $skip = true;
                }

                if($skip == false){
                    $token_list[] = $temp["token"];
                }
            }

            if($token_list==[]){
                json_return("91","발송 가능 대상자가 없습니다.");
            }

            $push_info = $this->site_model->getPush(["pu_idx"=>$idx]);

            $info = [
                "title"=>$push_info["pu_title"],
                "body"=>$push_info["pu_desc"],
                "img"=> get_site_domain() . $push_info["pu_img"],
                "url"=>"",
            ];
            $fcm_result = sendFcm($info,$token_list);

            $update = [
                "pu_status"=>"Y",
                "pu_success_cnt"=>$fcm_result["success"],
                "pu_fail_cnt"=>$fcm_result["failure"],
            ];

            $result = $this->site_model->updatePush($update,["pu_idx"=>$idx]);

            json_return($result["code"],($result["code"]=="00" ? "처리완료":"발송실패"));
		}

    function send_fcm_test(){
			$idx      = input_post("idx","");
      $pu_title = input_post("pu_title","");
      $pu_desc  = input_post("pu_desc","");
      $pu_token = input_post("pu_token","");

      $token_list[] = $pu_token;

      $info = [
          "title"=>$pu_title,
          "body"=>$pu_desc,
          "img"=> "",
          "url"=>"",
      ];
      $fcm_result = sendFcm($info,$token_list);
      json_return("99",$fcm_result);
      exit;

      $result["code"] = "00";

      json_return($result["code"],($result["code"]=="00" ? "처리완료":"발송실패"));
		}

        function push_test(){
            $api_key = 'AAAAnxT3SxM:APA91bFJ8J15zetEFWbt-TDpK5qbl9pkDnTbm220mGU4-P-0YO-cIF9ETasrx1BtT7A41HI8KHejbVO6nGmjn6rTS_dUS7zsDv8vXSIcVrvzHmvRFVDQKzmgxwoXYvsH4orqRQwpqdXI';	//IOS
            //$api_key = "AAAA3ugDuzk:APA91bGMLsfQLJUdjxaqSWZmmSYrQ8APcjTBO8eSG4MGq62T26eSDtBj0c06C-vGDo7O08RMklk-elgbyOWks4S3i41TmasQPvwJEYDD7PyUNbUzDKvndCLSVeewyGtV5EWUbfXr6JJ8";	//ANDROID
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            $info = [
                "title"=>"테스트입니다.",
                "body"=>"테스트입니다...",
                "url"=>"",

            ];
            $token_list[] = "eqURTIGTFkgOo1jaQxIpR0:APA91bFwUMYF86PD_O1yqyd46JrGqlqAs83AiaVnKQ4FoXXcNBlzYVXcGjjAzxjcJqJ1wmp_Q9GbbeB5mxTmarLt8_Tlxs3R8y4SI6V5sNyH4sGy_fRErOXYhb3RjUh2XR9mHghnkAxZ";
            $token_list[] = "eNjnFS-9TzeC62jOatGs6M:APA91bHPsbWNiPYVoRbsQnRfh3rflX9WwrzdP2C81n6bmyzBvZ6cE41kuPE27QrrU-2cBCAqQrqDnPa16dyQJzsVUETAM1liD9aToGk_fVnfMxpaAvjTnTzNIhi04gS9RXWmAvkxxbDF";

            $notification = [
                'title' =>$info["title"],
                'body' => $info["body"],
                'url' => $info["url"],
            ];
            $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
            $fcmNotification = [
                'registration_ids' => $token_list, //multple token array
                //'to'        => $token, //single token
                'notification' => $notification,
                'data' => $extraNotificationData
            ];
            $headers = [
                'Authorization: key=' . $api_key,
                'Content-Type:application/json'
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);

            $json_result = json_decode($result,true);

            var_dump($json_result);
        }

		//페이지 관리
		function page_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$vw_status = input_get("vw_status","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "vw_status" => $vw_status,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getPageList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"vw_status" => $vw_status,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/page_list',$data);
		}

		//페이지 관리
		function page_edit(){
			//파라미터 설정

			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getPage(["vw_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/page_edit',$data);
		}

		//배너관리  등록,수정
		function page_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all(FALSE);

			$vw_img = $this->common->img_upload_url("site","vw_img");

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		        	"vw_name" => $params["vw_name"],
			        "vw_status" => $params["vw_status"],
			        "vw_code" => $params["vw_code"],
			        "vw_pc_content" => $params["vw_pc_content"],
			        "vw_m_content" => $params["vw_m_content"],
			        "vw_img" => $vw_img,
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->site_model->insertPage($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"vw_idx" => $params["vw_idx"],
		        	"vw_name" => $params["vw_name"],
			        "vw_status" => $params["vw_status"],
			        "vw_code" => $params["vw_code"],
			        "vw_pc_content" => $params["vw_pc_content"],
			        "vw_m_content" => $params["vw_m_content"],
			        "vw_img" => $vw_img,
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["vw_idx"=>$params["vw_idx"]];
		        $result = $this->site_model->updatePage($update,$where);
				$primary_key = $params["vw_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["vw_idx"=>$params["idx"]];
		        $result = $this->site_model->deletePage($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//이벤트,공지사항,QNA,1:1문의,제휴문의 처리
		function board_list($tb_id,$list_page,$edit_page){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$bd_aws_yn = input_get("bd_aws_yn","");
			$bd_cate = input_get("bd_cate","");
			$sdate = input_get("sdate","");
			$edate = input_get("edate","");
			$tb_id = $tb_id;

			//데이터 설정
			{
				$params = [
			        "pagesize" => 20,
			        "page" => $page,
			        "search_type" => $search_type,
			        "search_value" => $search_value,
			        "bd_aws_yn" => $bd_aws_yn,
			        "tb_id" => $tb_id,
			        "bd_cate" => $bd_cate,
			        "sdate" => $sdate,
			        "edate" => $edate
			    ];

				$result = $this->board_model->getBoardList($params);

				//테이블 리스트
				$params = [
			        "pagesize" => 100,
			        "page" => 1,
			        "search_type" => "",
			        "search_value" => "",
			        "show_yn" => "Y",
			    ];
				$temp_table = $this->board_model->getTableList($params);
				$table_list = getKeyValue($temp_table["list"],"tb_id","tb_name");

				$setting = $this->board_model->getTable(0,$tb_id);
			}

			//리턴 설정
			$data = [
				"search" =>[
					"table_list" => $table_list,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"bd_aws_yn" => $bd_aws_yn,
					"tb_id" => $tb_id,
					"bd_cate" => $bd_cate,
			        "sdate" => $sdate,
			        "edate" => $edate
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"list_page" => $list_page,
				"edit_page" => $edit_page,
			];

			$this->layout->admin($list_page,$data);
		}

		//게시글 상세
		function board_edit($tb_id,$list_page,$edit_page){
			//파라미터 설정
		    $idx = input_get("idx",0);
			$tb_id = input_get("tb_id",$tb_id);

		    //데이터 조회
		    $info = $this->board_model->getBoard($idx);
			$setting = $this->board_model->getTable(0,$tb_id);

			//테이블 리스트
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		        "search_type" => "",
		        "search_value" => "",
		        "show_yn" => "Y"
		    ];
			$temp_table = $this->board_model->getTableList($params);
			$table_list = getKeyValue($temp_table["list"],"tb_id","tb_name");

			//서브 카테고리
			$sub_list = [];
			$tb_category = explode(";",$setting["tb_category"]);
			foreach($tb_category as $ct){
				$sub_list[$ct] = $ct;
			}

		    //리턴 처리
		    $data = [
		    	"admin_name"=>($info==null ? getAdminInfo("admin_name") : $info["admin_name"]),
		    	"info"=>$info,
		    	"tb_id"=>$tb_id,
		    	"setting"=>$setting,
		    	"sub_list"=>$sub_list,
		    	"table_list"=>$table_list,
		    	"list_page" => $list_page,
				"edit_page" => $edit_page,
		    ];

		    $this->layout->admin($edit_page,$data);
		}

		//테이블 등록,수정
		function board_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$bd_file1 = "";
			$bd_file2 = "";
			$bd_file3 = "";
			$bd_img1 = "";

			if($params["proc_mode"]=="insert"){
				$bd_file1 = $this->common->file_upload_url_name("board","bd_file1");
				$bd_file2 = $this->common->file_upload_url_name("board","bd_file2");
				$bd_file3 = $this->common->file_upload_url_name("board","bd_file3");
				$bd_img1 = $this->common->img_upload_url("board","bd_img1");
				if($bd_file1["error"]!=""){
					json_return("96",$bd_file1["error"],"");
					exit;
				}

				if($bd_file2["error"]!=""){
					json_return("96",$bd_file2["error"],"");
					exit;
				}

				if($bd_file3["error"]!=""){
					json_return("96",$bd_file3["error"],"");
					exit;
				}
			}

		    //처리
		    $result = [];
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "mm_id" => $params["mm_id"],
		            "bd_content" => $params["bd_content"],
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_img1" => $bd_img1,
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //저장
		        $result = $this->board_model->insertBoard($insert);
		    }else if($params["proc_mode"]=="update"){
		        $update = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "mm_id" => $params["mm_id"],
		            "bd_content" => $params["bd_content"],
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_img1" => $bd_img1,
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //저장
		        $where = ["bd_idx"=>$params["bd_idx"]];
		        $result = $this->board_model->updateBoard($update,$where);
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["bd_idx"=>$params["idx"]];
		        $result = $this->board_model->deleteBoard($where);
		    }


		    json_return($result["code"],$result["msg"],"");
		}

		//공지사항
		function notice_list(){
			$this->board_list("notice","/admin/site/notice_list","/admin/site/notice_edit");
		}

		//공지사항 등록/수정 관리
		function notice_edit(){
			$this->board_edit("notice","/admin/site/notice_list","/admin/site/notice_edit");
		}

		//공지사항
		function community_list(){
			$this->board_list("community","/admin/site/community_list","/admin/site/community_edit");
		}

		//공지사항 등록/수정 관리
		function community_edit(){
			$this->board_edit("community","/admin/site/community_list","/admin/site/community_edit");
		}

		//이벤트
		function event_list(){
			$this->board_list("event","/admin/site/event_list","/admin/site/event_edit");
		}

		//이벤트 등록/수정 관리
		function event_edit(){
			$this->board_edit("event","/admin/site/event_list","/admin/site/event_edit");
		}

		//FAQ
		function qna_list(){
			$this->board_list("faq","/admin/site/qna_list","/admin/site/qna_edit");
		}

		//FAQ 등록/수정 관리
		function qna_edit(){
			$this->board_edit("faq","/admin/site/qna_list","/admin/site/qna_edit");
		}

		//1:1문의
		function inquiry_list(){
			$this->board_list("report","/admin/site/inquiry_list","/admin/site/inquiry_edit");
		}

		//1:1문의 등록/수정 관리
		function inquiry_edit(){
			$this->board_edit("report","/admin/site/inquiry_list","/admin/site/inquiry_edit");
		}

		//제휴문의 등록/수정 관리
		function report_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->board_model->getBoardReport($idx);

		    //리턴 처리
		    $data = [
		    	"info"=>$info
		    ];

		    $this->layout->admin('admin/site/report_edit',$data);
		}

		//적립금 설정
		function setting_deposit(){
			//파라미터 설정
			$page = input_get("page",1);
			$gi_aws_yn = input_get("gi_aws_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "gi_aws_yn" => $gi_aws_yn,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getPointList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"gi_aws_yn" => $gi_aws_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/setting_deposit',$data);
		}


		//적립금 발급 등록/수정 관리
		function setting_deposit_edit(){

			//파라미터 설정

			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getPoint($idx);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/setting_deposit_edit',$data);
		}

		//적립금 발급  등록,수정 처리
		function setting_deposit_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

		    //처리
		    $result = [];
		    $primary_key = 0;
		    //if($params["proc_mode"]=="update"){
		        $update = [
		        	"pt_status" => $params["pt_status"],
		        	"pt_point" => $params["pl_point"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정  //todo
		        $where = ["pt_idx"=>$params["pt_idx"]];
		        //$result = $this->site_model->updatePoint($update,$where);

            $pl_point = $params["pl_point"];
            if($params[pl_type]=="minus"){
              $pl_point = $pl_point * -1;
            }

            $sql = " insert into st_point_list
                     (mm_idx, pt_type, pl_sub_type, pl_type, pl_point, pl_last_point, pl_desc, reg_id, reg_dt)
                     values(
                      (select min(mm_idx) from st_member where mm_id = '".$params[mm_id]."'), 'EVENT', 'system', '".$params[pl_type]."',
                      '".$pl_point."',
                      ( (select mm_point from st_member
                       where mm_idx = (select min(mm_idx) from st_member where mm_id = '".$params[mm_id]."') )
                       + '".$pl_point."'),
                      '포인트수동발급(".$params[pl_desc].")', 'admin', now()
                       ) ";
            $query = $this->db->query($sql);

            $sql2 = "  update st_member set mm_point = mm_point + '".$pl_point."'
                      where mm_id = '".$params[mm_id]."' ";
            $query = $this->db->query($sql2);

				    $primary_key = $params["pt_idx"];
		    //}

		    //json_return($result["code"],$result["msg"],"");
        json_return("00","저장되었습니다.","");
		}



		//적립금 발급 리스트
		function deposit_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$pt_type = input_get("pt_type","");
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-3 months")));
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "pt_type" => $pt_type,
		        "sdate" => $sdate,
		        "edate" => $edate,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getPointListList($params);

			//데이터 설정
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		        "search_type" => "",
		        "search_value" => "",
		    ];
			$temp_group = $this->site_model->getPointList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"pt_type" => $pt_type,
					"sdate" => $sdate,
					"edate" => $edate,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/deposit_list',$data);
		}

		//적립금 설정
		function deposit_edit(){
			//관리자 권한이 아니면 팅김처리
			$admin_info = $this->common->getSession("ADMIN_INFO");
			if($admin_info["master_yn"]=="N"){
				//go_location("/admin/site/deposit_list","발급권한이 없습니다.");
			}

			//파라미터 설정
			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getPointListInfo($idx);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/deposit_edit',$data);
		}

		//적립금 발급  등록,수정 처리
		function deposit_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
				$mem_info = $this->member_model->getMember(["mm_idx"=>$params["mm_idx"]]);

				if($mem_info==null){
					json_return("97","회원정보가 존재하지 않습니다.","");
					exit;
				}

				$last_point = 0;
				if($params["pl_type"]=="plus"){
					$last_point = $mem_info["mm_point"] + $params["pl_point"];
				}else{
					$last_point = $mem_info["mm_point"] - $params["pl_point"];

					if($last_point < 0){
						json_return("98","차감 가능한 회원 포인트가 부족합니다.","");
						exit;
					}
				}

		        $insert = [
		        	"mm_idx" => $params["mm_idx"],
		        	"pt_type" => $params["pt_type"],
		        	"pl_type" => $params["pl_type"],
		        	"pl_sub_type" => $params["pl_sub_type"],
		        	"pl_point" => ($params["pl_type"]=="plus" ? $params["pl_point"]:($params["pl_point"] * -1)),
		        	"pl_last_point" => $last_point,
		        	"pl_desc" => $params["pl_desc"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];
		        $result = $this->site_model->insertPointList($insert);

				//회원 정보 갱신한다.
				if($result["code"]=="00"){
					$result = $this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$params["mm_idx"]]);
				}
		    }

		    json_return($result["code"],"처리되었습니다.","");
		}

		// 적립금 발급 관리
		function setting_deposit_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$gi_aws_yn = input_get("gi_aws_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "gi_aws_yn" => $gi_aws_yn,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getGoodsInquiryList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"gi_aws_yn" => $gi_aws_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/setting_deposit',$data);
		}


		//쿠폰 설정
		function setting_coupon(){
			//파라미터 설정
			$page = input_get("page",1);
			$cp_status = input_get("cp_status","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		       	"cp_status" => $cp_status,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getCouponList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"cp_status" => $cp_status,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/site/setting_coupon',$data);
		}


		//쿠폰 등록/수정 관리
		function setting_coupon_edit(){
			//관리자 권한이 아니면 팅김처리
			$admin_info = $this->common->getSession("ADMIN_INFO");

			if($admin_info["master_yn"]=="N"){
				go_location("/admin/site/setting_coupon","발급권한이 없습니다.");
			}

			//파라미터 설정
			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getCoupon(["cp_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/site/setting_coupon_edit',$data);
		}

		//진열상품 관리  등록,수정
		function setting_coupon_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all(FALSE);

        /*
        $sql = " select min(mm_idx) mm_idx from st_member where mm_id = '".$params[mm_id]."' ";
        $query = $this->db->query($sql);
        $result["msg"] = $query;
        json_return($result["code"],$result["msg"],"");
        exit;
        */

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
  		        $insert = [
  		        	"cp_name" => $params["cp_name"],
  			        "cp_auto_yn" => $params["cp_auto_yn"],
  			        "cp_auto_type" => $params["cp_auto_type"],
  			        "cp_auto_sdate" => $params["cp_auto_sdate"],
  			        "cp_auto_edate" => $params["cp_auto_edate"],
  			        "cp_use_type" => $params["cp_use_type"],
  			        "cp_use_sdate" => $params["cp_use_sdate"],
  			        "cp_use_edate" => $params["cp_use_edate"],
  			        "cp_use_day" => $params["cp_use_day"],
  			        "cp_status" => $params["cp_status"],
  			        "cp_dan" => $params["cp_dan"],
  			        "cp_price" => $params["cp_price"],
  			        "cp_limit_price" => $params["cp_limit_price"],
  		          "reg_id" => getAdminInfo("admin_id"),
  		        ];

		          //등록
		          $result = $this->site_model->insertCoupon($insert);
				      $primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
  		        $update = [
  		        	"cp_name" => $params["cp_name"],
  			        "cp_auto_yn" => $params["cp_auto_yn"],
  			        "cp_auto_type" => $params["cp_auto_type"],
  			        "cp_auto_sdate" => $params["cp_auto_sdate"],
  			        "cp_auto_edate" => $params["cp_auto_edate"],
  			        "cp_use_type" => $params["cp_use_type"],
  			        "cp_use_sdate" => $params["cp_use_sdate"],
  			        "cp_use_edate" => $params["cp_use_edate"],
  			        "cp_use_day" => $params["cp_use_day"],
  			        "cp_status" => $params["cp_status"],
  			        "cp_dan" => $params["cp_dan"],
  			        "cp_price" => $params["cp_price"],
  			        "cp_limit_price" => $params["cp_limit_price"],
  		            "up_id" => getAdminInfo("admin_id"),
  		            "up_dt" => get_today(),
  		        ];

  		        //수정
  		        $where = ["cp_idx"=>$params["cp_idx"]];
  		        $result = $this->site_model->updateCoupon($update,$where);
  				    $primary_key = $params["cp_idx"];
		    }else if($params["proc_mode"]=="delete"){
    		    	//발급된 쿠폰이 있으면 삭제 불가능하다.
    		    	$is_data = $this->site_model->isCouponUsed(["cp_idx"=>$params["idx"]]);

      				if($is_data){
      					json_return("90","발급된 쿠폰이 있어 삭제가 불가능합니다.\n발급된 쿠폰을 모두 삭제후 삭제해주세요.","");
      					exit;
      				}
  		        //삭제
  		        $where = ["cp_idx"=>$params["idx"]];
  		        $result = $this->site_model->deleteCoupon($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}


		//쿠폰 설정
		function coupon_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$cp_idx = input_get("cp_idx","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		       	"cp_idx" => $cp_idx,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];
			$result = $this->site_model->getCouponListList($params);

			//데이터 설정
			$params = [
		        "pagesize" => 200,
		        "page" => 1,
		       	"cp_status" => "Y",
		        "search_type" => "",
		        "search_value" => "",
		    ];
			$coupon_result = $this->site_model->getCouponList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"cp_idx" => $cp_idx,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$data["coupon_list"] = $coupon_result["list"];
			$this->layout->admin('admin/site/coupon_list',$data);
		}


		//쿠폰 등록/수정 관리
		function coupon_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			$cp_idx = input_get("cp_idx",0);

		    //데이터 조회
		    $info = $this->site_model->getCouponListInfo($idx);

			//데이터 설정
			$params = [
		        "pagesize" => 200,
		        "page" => 1,
		       	"cp_status" => "Y",
		        "search_type" => "",
		        "search_value" => "",
		    ];
			$result = $this->site_model->getCouponList($params);

		    //리턴 처리
		    $data["cp_idx"] = $cp_idx;
		    $data["info"] = $info;
			$data["coupon_list"] = $result["list"];
		    $this->layout->admin('admin/site/coupon_edit',$data);
		}

		//진열상품 관리  등록,수정
		function coupon_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all(FALSE);

        $sql = " select min(mm_idx) mm_idx from st_member where mm_id = '".$params[mm_id]."' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $params["mm_idx"] = $result[0]["mm_idx"];

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
			        "cp_idx" => $params["cp_idx"],
		        	"mm_idx" => $params["mm_idx"],
			        "cl_use_type" => $params["cl_use_type"],
			        "cl_use_sdate" => get_today(),
			        "cl_use_edate" => date('Y-m-d', strtotime(get_today(). ' + ' . $params["cl_use_day"] . ' days')) . ' 23:59:59',
			        "cl_price" => $params["cl_price"],
			        "cl_limit_price" => $params["cl_limit_price"],
			        "cl_use_yn" => $params["cl_use_yn"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->site_model->insertCouponList($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"cp_idx" => $params["cp_idx"],
		        	"mm_idx" => $params["mm_idx"],
			        "cl_use_type" => $params["cl_use_type"],
			        "cl_use_sdate" => get_today(),
			        "cl_use_edate" => date('Y-m-d', strtotime(get_today(). ' + ' . $params["cl_use_day"] . ' days')) . ' 23:59:59',
			        "cl_price" => $params["cl_price"],
			        "cl_limit_price" => $params["cl_limit_price"],
			        "cl_use_yn" => $params["cl_use_yn"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["cl_idx"=>$params["cl_idx"]];
		        $result = $this->site_model->updateCouponList($update,$where);
				$primary_key = $params["cl_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["cl_idx"=>$params["idx"]];
		        $result = $this->site_model->deleteCouponList($where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}


		//키워드 관리
		public function setting_tag(){
			//파라미터 설정
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingInfo(["st_group"=>"태그"]),
			];
			$this->layout->admin('admin/site/setting_tag',$data);
		}

		//무통장 입금 은행 관리
		public function setting_tag_proc(){
			//파라미터 설정
			$params = input_post_all();

			//저장
			$update = [
				"st_value"=>$params["mem"]
			];

			$result = $this->setting_model->updateSettingBasic($update,["st_group"=>"태그","st_key"=>"mem"]);

			$update = [
				"st_value"=>$params["comp"]
			];
			$result = $this->setting_model->updateSettingBasic($update,["st_group"=>"태그","st_key"=>"comp"]);

			$update = [
				"st_value"=>$params["search"]
			];
			$result = $this->setting_model->updateSettingBasic($update,["st_group"=>"태그","st_key"=>"search"]);

			$update = [
				"st_value"=>$params["trade"]
			];
			$result = $this->setting_model->updateSettingBasic($update,["st_group"=>"태그","st_key"=>"trade"]);

			json_return($result["code"],$result["msg"],"");
		}

		function report_list(){
			$page = input_post("page",1);
			$br_type = input_get("br_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			$data = [];
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "br_type" => $br_type,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		    ];

			$result = $this->board_model->getMyReportList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"br_type" => $br_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
			];

			$this->layout->admin('admin/site/report_list',$data);

		}

    function mem_point_chk(){

      //$mem_id = input_get("mem_id",0);
      $mm_id = input_post("mm_id",0);

      //json_return("90","조회오류:".$mm_id);

	    //데이터 조회
      $info = $this->site_model->getPointMemId($mm_id);

	    //리턴 처리
	    //$data["info"] = $info;
	    //return $data["info"];
      json_return("00","조회 성공",["data"=>$info]);

		}//mem_point_chk


  }//class
?>
