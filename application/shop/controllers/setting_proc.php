<?php
    class Setting_proc extends CI_Controller{
		function __construct() {
	        parent::__construct();
			$this->load->model('membermodel','member_model');
			$this->load->model('mongomodel','mongo_model');
			$this->load->model('boardmodel','board_model');
			//$this->load->model('settingmodel','setting_model');
			//$this->load->model('sitemodel','site_model');

            # 일괄 로직 처리 : 되나?
            if($this->user->isLogin()==false){
                json_return("10","로그인후 이용해주세요.");
                exit;
            }
	    }

        # 비밀번호 변경 처리
		function change_pwd(){
			$mm_pwd = input_post("mm_pwd","");
			$change_pwd = input_post("change_pwd","");

			# 로그인처리
			if($mm_pwd==""){
				json_return("90","기존 비밀번호를 입력해주세요.");
				exit;
			}

			if($change_pwd==""){
				json_return("90","새비밀번호를 입력해주세요.");
				exit;
			}

            if($mm_pwd==$change_pwd){
				json_return("90","기존 비밀번호와 새비밀번호가 같습니다.");
				exit;
			}

			# 정보조회후 로그인처리
			$mm_id = $this->user->getUserId();
			$mem_info = $this->member_model->getMember(["mm_id"=>$mm_id,"mm_pwd"=>$this->common->getSqlPass($mm_pwd)]);
			if($mem_info==null){
				json_return("90","기존 비밀번호를 확인해주세요.");
				exit;
			}

            # 접속 처리
            $this->member_model->updatemember(["mm_pwd"=>$this->common->getSqlPass($change_pwd)],["mm_id"=>$mm_id]);

			json_return("00","비밀번호 변경완료.");
		}

        # 회원탈퇴
        function member_fire(){
            $fire_reason = input_post("fire_reason","");
            $fire_reason_desc = input_post("fire_reason_desc","");

            # 파라미터 확인
            if($fire_reason==""){
                json_return("90","탈퇴 사유를 선택해주세요.");
                exit;
            }

            # 정보조회후 로그인처리
            $mm_id = $this->user->getUserId();
            $mem_info = $this->member_model->getMember(["mm_id"=>$mm_id]);
            if($mem_info==null){
                json_return("90","회원정보가 조회되지 않습니다. 관리자에게 문의해주세요.");
                exit;
            }

            # 접속 처리
            $update = [
                "mm_status"=>"N",
                "fire_dt"=>get_today(),
                "fire_reason"=>$fire_reason,
                "fire_reason_desc"=>$fire_reason_desc,
                "mm_name"=>"탈퇴회원",
                "mm_phone"=>"",
                "mm_di"=>"",
                "mm_birth"=>""
            ];
            $this->member_model->updatemember($update,["mm_id"=>$mm_id]);

            # 로그아웃 처리
            $this->common->removeSession("USER_INFO");

            json_return("00","탈퇴 처리 완료.");
        }

        # 알람설정
        function alarm(){
            $alarm_chat = input_post("alarm_chat","");
            $alarm_resume = input_post("alarm_resume","");
            $alarm_fav = input_post("alarm_fav","");
            $alarm_apply = input_post("alarm_apply","");
            $alarm_adv = input_post("alarm_adv","");
            $alarm_deny_yn = input_post("alarm_deny_yn","");
            $alarm_deny_yoil = input_post("alarm_deny_yoil","");
            $alarm_deny_stime = input_post("alarm_deny_stime","");
            $alarm_deny_etime = input_post("alarm_deny_etime","");

            # 정보조회후 로그인처리
            $mm_id = $this->user->getUserId();
            $mem_info = $this->member_model->getMember(["mm_id"=>$mm_id]);
            if($mem_info==null){
                json_return("90","회원정보가 조회되지 않습니다. 관리자에게 문의해주세요.");
                exit;
            }

            # 접속 처리
            $update = [
                "alarm_chat"=>$alarm_chat,
                "alarm_resume"=>$alarm_resume,
                "alarm_fav"=>$alarm_fav,
                "alarm_apply"=>$alarm_apply,
                "alarm_adv"=>$alarm_adv,
                "alarm_deny_yn"=>$alarm_deny_yn,
                "alarm_deny_yoil"=>$alarm_deny_yoil,
                "alarm_deny_stime"=>str_replace(" ","",$alarm_deny_stime),
                "alarm_deny_etime"=>str_replace(" ","",$alarm_deny_etime),
            ];

            $result = $this->member_model->updatemember($update,["mm_id"=>$mm_id]);

			#몽고 업데이트
			$update["mm_id"] = $mm_id;
			$this->mongo_model->updateUserInfo($update);

            return json_return($result["code"],$result["msg"]);
        }

		# 게시판 리스트
		function board_list(){
			$mm_id = $this->user->getUserId();
			$page = input_post("page",1);
			$tb_id = input_post("tb_id","");
			$date_status = input_post("date_status","");
			$mm_id_yn = input_post("mm_id_yn","N");

			$bd_show_type = "";

			if($tb_id=="event" || $tb_id=="faq"){
				if($this->user->isLogin()==true){
					$bd_show_type = $this->user->getUserType();
				}
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
				"result_list" => $result["list"],
				"result_count" => $result["count"],
			];

			json_return("00","조회성공",$data);
		}

		# 게시판 리스트
		function my_report_list(){
			$mm_idx = $this->user->getUserIdx();
			$page = input_post("page",1);

			$data = [];
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "mm_id" => $mm_idx
		    ];

			$result = $this->board_model->getMyReportList($params);

			//리턴 설정
			$data = [
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
			];

			json_return("00","조회성공",$data);
		}

		# 좋아요 처리
		function board_cmt_fav(){
			$bd_idx = input_post("bd_idx","");
			$bc_idx = input_post("bc_idx","");
			$mm_idx = $this->user->getUserIdx();

			$result = $this->board_model->setBoardCmtFav(["bd_idx"=>$bd_idx,"bc_idx"=>$bc_idx,"mm_idx"=>$mm_idx],["bd_idx"=>$bd_idx,"bc_idx"=>$bc_idx,"mm_idx"=>$mm_idx]);
			$fav_cnt = $this->board_model->getBoardCmtFavCnt($bc_idx);

			json_return("00","좋아요",["fav_cnt"=>$fav_cnt]);
		}

        # 좋아요 처리
		function board_fav(){
			$bd_idx = input_post("bd_idx","");
			$mm_idx = $this->user->getUserIdx();

			$result = $this->board_model->setBoardFav(["bd_idx"=>$bd_idx,"mm_idx"=>$mm_idx],["bd_idx"=>$bd_idx,"mm_idx"=>$mm_idx]);
			$fav_cnt = $this->board_model->getBoardFavCnt($bd_idx);

			json_return("00","좋아요",["fav_cnt"=>$fav_cnt]);
		}

		# 코멘트 처리
		function board_comment_proc(){
			$proc_mode = input_post("proc_mode","insert");

			if($proc_mode=="insert"){
				$mm_idx = $this->user->getUserIdx();
				$bd_idx = input_post("bd_idx","");
				$parent_idx = input_post("parent_idx",0);
				$bc_content = input_post("bc_content","");

				$insert_data = [
					"bd_idx"=>$bd_idx,
					"mm_idx"=>$mm_idx,
					"parent_idx"=>$parent_idx,
					"bc_content"=>$bc_content,
				];

				$result = $this->board_model->insertBoardComment($insert_data);
				json_return("00","처리성공");
			}else if($proc_mode=="delete"){
				$mm_idx = $this->user->getUserIdx();
				$depth = input_post("depth","");
				$bc_idx = input_post("bc_idx","");

				if($depth=="1"){
					$this->board_model->deleteBoardComment(["parent_idx"=>$bc_idx,"mm_idx"=>$mm_idx]);
				}else{
					$this->board_model->deleteBoardComment(["bc_idx"=>$bc_idx,"mm_idx"=>$mm_idx]);
				}
				json_return("00","처리성공");
			}
		}

        # 신고하기
		function board_report(){
			$bd_idx = input_post("bd_idx","");
            $br_desc = input_post("br_desc","");
			$br_type = input_post("br_type","");
			$mm_idx = $this->user->getUserIdx();

			$info = [
                "br_key" => $bd_idx,
                "mm_idx" => $mm_idx,
                "br_type" => $br_type,
                "br_desc" => $br_desc,
            ];
			$result = $this->board_model->insertBoardReport($info);

			json_return($result["code"],"처리완료");
		}

		# 삭제하기
		function board_delete(){
			$bd_idx = input_post("bd_idx","");
			$mm_id = $this->user->getUserId();

			$info = [
                "bd_idx" => $bd_idx,
                "mm_id" => $mm_id,
            ];
			$result = $this->board_model->deleteBoard($info);

			json_return($result["code"],"처리완료");
		}

		# 신고글 삭제하기
		function report_delete(){
			$br_idx = input_post("br_idx","");
			$mm_idx = $this->user->getUserIdx();

			$info = [
                "br_idx" => $br_idx,
                "mm_idx" => $mm_idx,
            ];
			$result = $this->board_model->deleteBoardReport($info);

			json_return($result["code"],"처리완료");
		}

		# 커뮤니티 작성하기
		function community_write(){
			$mm_id = $this->user->getUserId();
			$tb_id = "community";

			$proc_mode = input_post("proc_mode","");
			$bd_idx = input_post("bd_idx","");
			$bd_cate = input_post("bd_cate","");
			$bd_subject = input_post("bd_subject","");
			$bd_content = input_post("bd_content","");

			$bd_img1 = $this->common->img_upload_url("board","bd_img1");
			$bd_img2 = $this->common->img_upload_url("board","bd_img2");

			$info = [
				"tb_id" => $tb_id,
				"mm_id" => $mm_id,
                "bd_cate" => $bd_cate,
                "bd_subject" => $bd_subject,
                "bd_content" => $bd_content,
                "bd_img1" => $bd_img1,
                "bd_img2" => $bd_img2,
            ];

			$result = [];
			if($proc_mode=="insert"){
				$result = $this->board_model->insertBoard($info);
			}else{
				$result = $this->board_model->updateBoard($info,["bd_idx"=>$bd_idx]);
			}

			json_return($result["code"],$result["msg"]);
		}

		# 커뮤니티 작성하기
		function report_write(){
			$mm_id = $this->user->getUserId();
			$tb_id = "report";

			$proc_mode = input_post("proc_mode","");
			$bd_idx = input_post("bd_idx","");
			$bd_cate = input_post("bd_cate","");
			$bd_subject = input_post("bd_subject","");
			$bd_content = input_post("bd_content","");

			$bd_img1 = $this->common->img_upload_url("board","bd_img1");
			$bd_img2 = $this->common->img_upload_url("board","bd_img2");

			$info = [
				"tb_id" => $tb_id,
				"mm_id" => $mm_id,
                "bd_subject" => $bd_subject,
                "bd_content" => $bd_content,
                "bd_img1" => $bd_img1,
                "bd_img2" => $bd_img2,
                "bd_cate" => $bd_cate,
            ];

			$result = [];
			if($proc_mode=="insert"){
				$result = $this->board_model->insertBoard($info);
			}else{
				$result = $this->board_model->updateBoard($info,["bd_idx"=>$bd_idx]);
			}

			json_return($result["code"],$result["msg"]);
		}
    }
?>
