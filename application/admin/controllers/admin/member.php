<?php
    class Member extends MY_Controller{
		function __construct() {
      parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('membermodel','member_model');
			$this->load->model('ordermodel','order_model');
			$this->load->model('sitemodel','site_model');
	  }

		//회원관리
		function member_list(){
			//파라미터 설정
			$page = input_get("page",1);
      $mm_status = input_get("mm_status","");
      $mm_sex = input_get("mm_sex","");
      $mm_nation = input_get("mm_nation","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

      //회원 리스트
      $params = [
          "pagesize" => 20,
          "page" => $page,
          "search_type" => $search_type,
          "search_value" => $search_value,
          "mm_status" => $mm_status,
          "mm_sex" => $mm_sex,
          "mm_nation" => $mm_nation,
          "mm_type"=>"MEM",
      ];
	  
      $result = $this->member_model->getMemberList($params);

			//리턴 설정
			$data = [
				"search" =>[
          "mm_status" => $mm_status,
          "mm_sex" => $mm_sex,
          "mm_nation" => $mm_nation,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"sum_info" => $sum_info,
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/member/member_list',$data);
		}

		//회원 엑셀다운로드
		function member_list_excel(){
			$this->load->library('excel');

			//파라미터 설정
			$page = input_get("page",1);
			$mm_status = input_get("mm_status","");
			$mm_sex = input_get("mm_sex","");
			$mm_nation = input_get("mm_nation","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

      //회원 리스트
      $params = [
          "pagesize" => 100000,
          "page" => 1,
          "search_type" => $search_type,
          "search_value" => $search_value,
          "mm_status" => $mm_status,
          "mm_sex" => $mm_sex,
          "mm_nation" => $mm_nation,
          "mm_type"=>"MEM",
      ];
      $result = $this->member_model->getMemberList($params);

		# get data
      $file_name = $this->excel->getFilename("개인회원_리스트");

      $num = 1;

      $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
      $last_column = end($columns);

      # 셀크기 조절
      $column_widths = [
          'A' => 50,  // 이름
          'B' => 30,  // 연락처
          'C' => 70,  // 이메일
          'D' => 10,  // 나이
          'E' => 10,  // 성별
          'F' => 10,  // 국적
          'G' => 15,  // 지역
      ];

      foreach ($column_widths as $column => $width) {
          $this->excel->getActiveSheet()->getColumnDimension($column)->setWidth($width);
      }

			//폰트 설정
			$block_bold_big = array(
          'font'  => array(
              'bold'  => true,
              'color' => array('rgb' => '000000'),
              'size'  => 16
          )
      );
            // 헤더 설정
			$this->excel->getActiveSheet()->setCellValue('A' . $num, "이름");
			$this->excel->getActiveSheet()->setCellValue("B" . $num, "연락처");
			$this->excel->getActiveSheet()->setCellValue("C" . $num, "이메일");
			$this->excel->getActiveSheet()->setCellValue('D' . $num, "나이");
			$this->excel->getActiveSheet()->setCellValue("E" . $num, "성별");
			$this->excel->getActiveSheet()->setCellValue("F" . $num, "국적");
			$this->excel->getActiveSheet()->setCellValue("G" . $num, "지역");


      $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');
      $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $num++;

	        # 출력 리스트 데이터
	        if (count($result["list"]) > 0) {
	            $no = 0;
	            foreach ($result["list"] as $row) {
	                // 나이 계산 (mm_birth: 출생연도 YYYY, 과거 YYYYMMDD 값도 앞 4자리로 계산)
	                $age = "";
	                if (!empty($row['mm_birth']) && strlen($row['mm_birth']) >= 4) {
	                    $age = intval(date('Y')) - intval(substr($row['mm_birth'], 0, 4));
	                }

	                $tableData = array_combine(
	                    array_values($columns), //0부터 새 번호 붙임
	                    [
	                        $row['mm_name'],
	                        $row['mm_phone'],
	                        $row['mm_id'],
	                        $age,
	                        $row['mm_sex'],
	                        $row['mm_nation'],
	                        $row['ad_addr'],
	                    ]
	                );
	                foreach ($tableData as $k => $v) {
                    $this->excel->getActiveSheet()->setCellValue($k . ($num), $v); //엑셀 데이터 입력
		                $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		                $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						        $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                  }
	                $num++;
	            }
	        }

	        # 엑셀 저장
	        $this->excel->setFooter($file_name);
		}

		//회원 일괄처리
		public function member_list_proc(){
			//파라미터 설정
			$proc_mode = input_post("proc_mode","");
			$chk_list = input_post("chk_list","");
			$add_sale_depth = input_post("add_sale_depth","");
			$mg_idx = input_post("mg_idx","");

			if($proc_mode=="hidden"){
				$arr_list = explode(",",$chk_list);
				foreach($arr_list as $chk){
					$result = $this->member_model->updateMember(["mm_status"=>"H"],["mm_idx"=>$chk]);
				}
			}

			json_return("00","일괄 처리 완료","");
		}

		//회원관리
		function comp_list(){
            //파라미터 설정
			$page = input_get("page",1);
			$mm_status = input_get("mm_status","");
            $mm_sex = input_get("mm_sex","");
            $mm_nation = input_get("mm_nation","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

            //회원 리스트
            $params = [
                "pagesize" => 20,
                "page" => $page,
                "search_type" => $search_type,
                "search_value" => $search_value,
                "mm_status" => $mm_status,
                "mm_sex" => $mm_sex,
                "mm_nation" => $mm_nation,
                "mm_type"=>"COMP",
            ];
            $result = $this->member_model->getMemberList($params);

			//리턴 설정
			$data = [
				"search" =>[
                    "mm_status" => $mm_status,
                    "mm_sex" => $mm_sex,
                    "mm_nation" => $mm_nation,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"sum_info" => $sum_info,
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/member/comp_list',$data);
		}

		//채용공고 엑셀다운로드
		function comp_list_excel(){
			$this->load->library('excel');

			//파라미터 설정
			$page = input_get("page",1);
			$mm_status = input_get("mm_status","");
            $mm_sex = input_get("mm_sex","");
            $mm_nation = input_get("mm_nation","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

            //회원 리스트
            $params = [
                "pagesize" => 100000,
                "page" => 1,
                "search_type" => $search_type,
                "search_value" => $search_value,
                "mm_status" => $mm_status,
                "mm_sex" => $mm_sex,
                "mm_nation" => $mm_nation,
                "mm_type"=>"COMP",
            ];
            $result = $this->member_model->getMemberList($params);

			# get data
	        $file_name = $this->excel->getFilename("기업회원_리스트");

	        $num = 1;

	        $columns = ['A', 'B', 'C'];
	        $last_column = end($columns);

	        # 셀크기 조절
	        $excel_cols = [];
	        $tableDimension = array_fill_keys(array_values($columns), 10);
			foreach($tableDimension as $key => $val){
				$excel_cols[$key] = 20;
				$this->excel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
			}

			//폰트 설정
			$block_bold_big = array(
	            'font'  => array(
	                'bold'  => true,
	                'color' => array('rgb' => '000000'),
	                'size'  => 16
	            )
	        );

			$this->excel->getActiveSheet()->setCellValue('A' . $num, "이름");
			$this->excel->getActiveSheet()->setCellValue("B" . $num, "연락처");
			$this->excel->getActiveSheet()->setCellValue("C" . $num, "이메일");


	        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');
	        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	        $num++;

	        # 출력 리스트 데이터
	        if (count($result["list"]) > 0) {
	            $no = 0;
	            foreach ($result["list"] as $row) {
	                $tableData = array_combine(
	                    array_values($columns),
	                    [
	                        $row['mm_name'],
	                        $row['mm_phone'],
	                        $row['mm_id'],
	                    ]
	                );
	                foreach ($tableData as $k => $v) {
                        $this->excel->getActiveSheet()->setCellValue($k . ($num), $v);
		                $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		                $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }
	                $num++;
	            }
	        }

	        # 엑셀 저장
	        $this->excel->setFooter($file_name);
		}

		//회원관리
		function member_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
			$info = $this->member_model->getMember(["mm_idx"=>$idx]);
            $add = $this->member_model->getMemberInfo($idx);

			$career_list = $this->member_model->getMemberCareerList(["mm_idx"=>$info["mm_idx"]]);
			$certi_list = $this->member_model->getMemberCertiList(["mm_idx"=>$info["mm_idx"]]);
			$resume_list = $this->member_model->getResumeListV2(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$point_list = $this->order_model->getPointList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$order_list = $this->order_model->getOrderList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$fav_list = $this->member_model->getBookMarkRecruitList(["page"=>1,"pagesize"=>"10","mm_idx"=>$info["mm_idx"]]);

			$data = [
				"info" => $info,
                "add" => $add,
                "career_list" => $career_list,
    			"certi_list" => $certi_list,
				"resume_list" => $resume_list["list"],
				"point_list" => $point_list["list"],
				"order_list" => $order_list["list"],
				"fav_list" => $fav_list["list"],
			];

			$this->layout->admin('admin/member/member_edit',$data);
		}

    //회원생성
		function member_reg(){
			//파라미터 설정
      $type = strtoupper(input_get("type","MEM"));

			//데이터 설정
			$info = $this->member_model->getMember(["mm_idx"=>$idx]);
            $add = $this->member_model->getMemberInfo($idx);

			$career_list = $this->member_model->getMemberCareerList(["mm_idx"=>$info["mm_idx"]]);
			$certi_list = $this->member_model->getMemberCertiList(["mm_idx"=>$info["mm_idx"]]);
			$resume_list = $this->member_model->getResumeListV2(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$point_list = $this->order_model->getPointList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$order_list = $this->order_model->getOrderList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$fav_list = $this->member_model->getBookMarkRecruitList(["page"=>1,"pagesize"=>"10","mm_idx"=>$info["mm_idx"]]);

			$data = [
        "type" => $type,
				"info" => $info,
        "add" => $add,
        "career_list" => $career_list,
  			"certi_list" => $certi_list,
				"resume_list" => $resume_list["list"],
				"point_list" => $point_list["list"],
				"order_list" => $order_list["list"],
				"fav_list" => $fav_list["list"],
			];

			$this->layout->admin('admin/member/member_reg',$data);
		}

		function member_edit_proc(){
			$proc_mode = input_post("proc_mode","");
			$idx = input_post("idx",0);
      $mm_status = input_post("mm_status","");

			if($proc_mode=="delete"){

        // 관리자 삭제: 탈퇴 처리(상태 'H')가 아니라 회원 행과 연관 데이터를 실제로 DELETE (mm_idx 기준)
        $result = $this->member_model->deleteMemberCompletely($idx);

        if($result["code"]=="00"){
          json_return("00","삭제 처리 완료");
        }else{
          json_return("90",$result["msg"]);
        }
			}else if($proc_mode=="return"){
				$result = $this->member_model->updateMember(["mm_status"=>"Y","mm_stop_desc"=>"","stop_dt"=>""],["mm_idx"=>$idx]);

          if($result["code"]=="00"){
    				json_return("00","자격정지 해제 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}else if($proc_mode=="stop"){
          $mm_stop_desc = input_post("mm_stop_desc","");
				  $result = $this->member_model->updateMember(["mm_status"=>"S".$mm_status,"mm_stop_desc"=>$mm_stop_desc,"stop_dt"=>get_today()],["mm_idx"=>$idx]);

          if($result["code"]=="00"){
    				json_return("00","강제정지 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}

      }else if($proc_mode=="stopcancle"){

          if($mm_status=="SY"){
            $result = $this->member_model->updateMember(["mm_status"=>"Y","mm_stop_desc"=>"","stop_dt"=>""],["mm_idx"=>$idx]);
          }else if($mm_status=="SN"){
            $result = $this->member_model->updateMember(["mm_status"=>"N","mm_stop_desc"=>"","stop_dt"=>""],["mm_idx"=>$idx]);
          }else if($mm_status=="SH"){
            $result = $this->member_model->updateMember(["mm_status"=>"H","mm_stop_desc"=>"","stop_dt"=>""],["mm_idx"=>$idx]);
          }

          if($result["code"]=="00"){
    				json_return("00","강제정지 해제 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
		}else if($proc_mode=="agree_ad"){
			$current_agree_ad = input_post("current_agree_ad","N");
			$new_agree_ad = ($current_agree_ad === "Y") ? "N" : "Y";
			$result = $this->member_model->updateMember(["mm_agree_ad"=>$new_agree_ad],["mm_idx"=>$idx]);

			if($result["code"]=="00"){
				json_return("00","광고수신동의 ".($new_agree_ad === "Y" ? "동의" : "미동의")." 처리 완료");
			}else{
				json_return("90","데이터 처리시 오류가 발생하였습니다.");
			}
		}
	}

    function member_reg_proc(){
			$mm_id = input_post("mm_id");
      $mm_name = input_post("mm_name");
      // 개인정보 보호: 입력값은 출생연도 4자리만 저장한다
      $mm_birth = maskBirthToYear(input_post("mm_birth"));
      $mm_nation = input_post("mm_nation");
      $mm_sex = input_post("mm_sex");
      $mm_phone = input_post("mm_phone");
      $mm_comp_name = input_post("mm_comp_name");
      $mm_comp_num = input_post("mm_comp_num");
      $mm_type = input_post("mm_type");
			$info = $this->member_model->getMember(["mm_id"=>$mm_id]);
			// 휴대폰번호가 이미 가입된 회원(활성/15일 이내 탈퇴)의 번호인지 확인 (shop 가입과 동일 기준)
			$duplicate_check = checkDuplicatePhoneForSns($mm_phone);

			if($info && $info["mm_idx"]!=""){
        json_return("99","이미 존재하는 아이디 입니다.");
			}else if(!empty($duplicate_check["is_duplicate"])){
        json_return("99",$duplicate_check["message"]);
			}else{
        $this->db->trans_begin();
        $sql1  = " INSERT INTO st_member (mm_join_type, mm_type,mm_comp_type, mm_comp_name, mm_comp_num, mm_comp_num_yn, mm_comp_auth, mm_comp_out_yn, mm_id, mm_pwd, mm_point, mm_name, mm_status, mm_phone, mm_sex, mm_birth, mm_nation, mm_ci, mm_di, reg_dt, up_dt) VALUES('WEB', ";
        if($mm_type=="COMP"){
          $sql1 .= " '".$mm_type."', '".$mm_type."', ";
        }else{
          $sql1 .= " '".$mm_type."', 'PERSON', ";
        }
        $sql1 .= " '".$mm_comp_name."', '".$mm_comp_num."', ";
        $sql1 .= " 'N','','N', '".$mm_id."', ";
        // 비밀번호를 입력받지 않으므로 빈값 저장 (로그인은 휴대폰 NICE/SMS 인증, 관리자 로그인은 토큰)
        $sql1 .= " '', ";
        $sql1 .= " 0, '".$mm_name."', 'Y','".$mm_phone."', ";
        $sql1 .= " '".$mm_sex."','".$mm_birth."','".$mm_nation."','".$mm_id."', 'MC0**".$mm_id."', ";
        $sql1 .= " now(), now()) ";

        $query1 = $this->db->query($sql1);
        $affectedRows1 = $this->db->affected_rows();
        if($query1 && $affectedRows1 ==1){
          $this->db->trans_commit();
          json_return("00","처리완료");
				}else{
          $this->db->trans_rollback();
					json_return("92","오류가 발생하였습니다. 관리자에 문의 해주세요.");
				}
			}
		}

		function member_repwd(){
			$mm_idx = input_post("mm_idx");
			$info = $this->member_model->getMember(["mm_idx"=>$mm_idx]);

			$authNum = rand(100000, 999999);
			$authNum = "pwd" . $authNum;
			$phone = $info["mm_phone"];
			$result = $this->common->sendSms($phone,"나우잡 임시 비밀번호 발송 [".$authNum."]",$authNum);

			if($result["code"]=="00"){
				$result = $this->member_model->updateMember(["mm_pwd"=>$this->common->getSqlPass($authNum)],["mm_idx"=>$mm_idx]);
				if($result["code"]=="00"){
					json_return("00","처리완료");
				}else{
					json_return("92","비밀번호 업데이트시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
				}
			}else{
				json_return("99","SMS발송시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
			}
		}

		function out_proc(){
			$mm_idx = input_post("mm_idx");
			$result = $this->member_model->updateMember(["mm_comp_out_yn"=>"Y"],["mm_idx"=>$mm_idx]);
			json_return($result["code"],$result["msg"]);
		}

		function clear_mm_di(){
			$mm_idx = input_post("mm_idx");
			$result = $this->member_model->updateMember(["mm_di"=>"CLEAR000000000000001"],["mm_idx"=>$mm_idx]);
			json_return($result["code"],$result["msg"]);
		}

		//관리자 자동 로그인 토큰 발급 (관리자 세션 필수, 60초 유효, 비밀번호 사용 안 함)
		function issue_auto_login_token(){
			if(!$this->common->getSession("ADMIN_INFO")){
				json_return("99","관리자 로그인이 필요합니다.");
			}

			$mm_idx = input_post("mm_idx");
			$mem_info = $this->member_model->getMember(["mm_idx"=>$mm_idx]);
			if(!$mem_info || $mem_info["mm_status"] != "Y"){
				json_return("99","로그인 가능한 회원이 아닙니다.");
			}

			$token = make_auto_login_token($mem_info["mm_idx"]);
			if($token === ""){
				json_return("99","자동 로그인 서명 키(auto_login_secret)가 설정되어 있지 않습니다.");
			}
			json_return("00","",["token"=>$token]);
		}

		//회원관리
		function comp_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
			$info = $this->member_model->getMember(["mm_idx"=>$idx]);
            $add = $this->member_model->getMemberInfo($idx);

			$career_list = $this->member_model->getMemberCareerList(["mm_idx"=>$info["mm_idx"]]);
			$certi_list = $this->member_model->getMemberCertiList(["mm_idx"=>$info["mm_idx"]]);
			$recruit_list = $this->member_model->getRecruitListV2(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$point_list = $this->order_model->getPointList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$order_list = $this->order_model->getOrderList(["mm_idx"=>$info["mm_idx"],"page"=>1,"pagesize"=>"10"]);
			$fav_list = $this->member_model->getBookMarkResumeList(["page"=>1,"pagesize"=>"10","mm_idx"=>$info["mm_idx"]]);

			$data = [
				"info" => $info,
                "add" => $add,
                "career_list" => $career_list,
    			"certi_list" => $certi_list,
				"recruit_list" => $recruit_list["list"],
				"point_list" => $point_list["list"],
				"order_list" => $order_list["list"],
				"fav_list" => $fav_list["list"],
			];

			$this->layout->admin('admin/member/comp_edit',$data);
		}

		//채용공고 리스트
		function recruit_list(){
            //파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$show_yn = input_get("show_yn","");
			$category = input_get("category","");


			# 카테고리 검색 데이터 만들기
			if($category!=""){
				$str_category = implode("^",$category);
			}

            //회원 리스트
            $params = [
                "pagesize" => 20,
                "page" => $page,
                "search_type" => $search_type,
                "search_value" => $search_value,
                "show_yn" => $show_yn,
                "category" => $str_category,
            ];
            $result = $this->member_model->getRecruitSearchListV2($params);

			$category_list = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$select_category = getKeyValue($category_list,"cd_val","cd_val");

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
					"show_yn" => $show_yn,
	                "category" => $category,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"select_category" => $select_category,
			];

			$this->layout->admin('admin/member/recruit_list',$data);
		}

		//회원 일괄처리
		public function recruit_list_proc(){
			//파라미터 설정
			$proc_mode = input_post("proc_mode","");
			$chk_list = input_post("chk_list","");
			$add_sale_depth = input_post("add_sale_depth","");

			if($proc_mode=="hidden"){
				$arr_list = explode(",",$chk_list);
				foreach($arr_list as $chk){
					$result = $this->member_model->updateRecruit(["rc_status"=>"H"],["rc_idx"=>$chk]);
				}
			}

			json_return("00","일괄 처리 완료","");
		}

		//이력서 채용공고 상세
		function recruit_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
            $info = $this->member_model->getRecruitDetail($idx,"");
			$mem = $this->member_model->getMember(["mm_idx"=>$info["mm_idx"]]);

			$resume_list = $this->member_model->getBookMarkRequestResumeList( ["pagesize" => 10,"page" => 1,"rc_idx"=>$idx]);

			$data = [
				"mem" => $mem,
                "info" => $info,
                "resume_list" => $resume_list["list"],
			];

			$this->layout->admin('admin/member/recruit_edit',$data);
		}

		function recruit_edit_proc(){
			$proc_mode = input_post("proc_mode","");
			$idx = input_post("idx",0);

			if($proc_mode=="delete"){
				$result = $this->member_model->updateRecruit(["rc_status"=>"H"],["rc_idx"=>$idx]);

        if($result["code"]=="00"){
    				json_return("00","비노출 처리 완료");
  			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
  			}
			}else if($proc_mode=="stop"){
        $stop_desc = input_post("stop_desc","");
				$result = $this->member_model->updateRecruit(["rc_status"=>"H","stop_desc"=>$stop_desc,"stop_dt"=>get_today()],["rc_idx"=>$idx]);

        if($result["code"]=="00"){
    				json_return("00","관리자 비공개 처리 완료");
  			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
  			}
      }else if($proc_mode=="stopcancle"){

  				$result = $this->member_model->updateRecruit(["rc_status"=>"Y"],["rc_idx"=>$idx]);

          if($result["code"]=="00"){
      				json_return("00","관리자 비공개 해제 완료");
    			}else{
      				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}

		}

		//채용공고 리스트
		function resume_list(){
            //파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$show_yn = input_get("show_yn","");
			$category = input_get("category","");


			# 카테고리 검색 데이터 만들기
			if($category!=""){
				$str_category = implode("^",$category);
			}

            //회원 리스트
            $params = [
                "pagesize" => 20,
                "page" => $page,
                "search_type" => $search_type,
                "search_value" => $search_value,
                "show_yn" => $show_yn,
                "category" => $str_category,
            ];
            $result = $this->member_model->getResumeSearchListV2($params);

			$category_list = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$select_category = getKeyValue($category_list,"cd_val","cd_val");

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
					"show_yn" => $show_yn,
	                "category" => $category,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"select_category" => $select_category,
			];

			$this->layout->admin('admin/member/resume_list',$data);
		}

		//회원 일괄처리
		public function resume_list_proc(){
			//파라미터 설정
			$proc_mode = input_post("proc_mode","");
			$chk_list = input_post("chk_list","");
			$add_sale_depth = input_post("add_sale_depth","");

			if($proc_mode=="hidden"){
				$arr_list = explode(",",$chk_list);
				foreach($arr_list as $chk){
					$result = $this->member_model->updateResume(["ru_status"=>"H"],["ru_idx"=>$chk]);
				}
			}

			json_return("00","일괄 처리 완료","");
		}

		//이력서 채용공고 상세
		function resume_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
            $info = $this->member_model->getResumeDetail($idx,"");
			$mem = $this->member_model->getMember(["mm_idx"=>$info["mm_idx"]]);
			$career_list = $this->member_model->getResumeCareerList(["ru_idx"=>$info["ru_idx"]]);
			$certi_list = $this->member_model->getResumeCertiList(["ru_idx"=>$info["ru_idx"]]);


			$recruit_list = $this->member_model->getMemRequestRecruitList( ["pagesize" => 10,"page" => 1,"ru_idx"=>$idx]);
			$request_list = $this->member_model->getBookMarkRequestRecruitList( ["pagesize" => 10,"page" => 1,"ru_idx"=>$idx]);

			$data = [
				"mem" => $mem,
                "info" => $info,
                "career_list" => $career_list,
                "certi_list" => $certi_list,
                "recruit_list" => $recruit_list["list"],
                "request_list" => $request_list["list"],
			];

			$this->layout->admin('admin/member/resume_edit',$data);
		}

		function resume_edit_proc(){
			$proc_mode = input_post("proc_mode","");
			$idx = input_post("idx",0);
      $ru_status = input_post("ru_status","");

      if($proc_mode=="delete"){
				    $result = $this->member_model->updateResume(["ru_status"=>"H"],["ru_idx"=>$idx]);

            if($result["code"]=="00"){
    				      json_return("00","비노출 처리 완료");
    			  }else{
    				      json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			  }
			}else if($proc_mode=="stop"){

            $stop_desc = input_post("stop_desc","");
				    $result = $this->member_model->updateResume(["ru_status"=>"H".$ru_status,"stop_desc"=>$stop_desc,"stop_dt"=>get_today()],["ru_idx"=>$idx]);

            if($result["code"]=="00"){
    				      json_return("00","관리자 비공개 처리 완료");
    			  }else{
    				      json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			  }
      }else if($proc_mode=="stopcancle"){

            if($ru_status=="HN"){
              $result = $this->member_model->updateResume(["ru_status"=>"N","stop_desc"=>"","stop_dt"=>""],["ru_idx"=>$idx]);
            }else{
              $result = $this->member_model->updateResume(["ru_status"=>"Y","stop_desc"=>"","stop_dt"=>""],["ru_idx"=>$idx]);
            }

            if($result["code"]=="00"){
    				      json_return("00","관리자 비공개 해제 처리 완료");
    			  }else{
    				      json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			  }
			}
		}//resume_edit_proc

		//회원관리
		function fav_recruit_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

            //회원 리스트
            $params = [
                "pagesize" => 20,
                "page" => $page,
                "search_type" => $search_type,
                "search_value" => $search_value,
            ];
            $result = $this->member_model->getBookMarkRecruitList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"sum_info" => $sum_info,
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/member/fav_recruit_list',$data);
		}

		//회원관리
		function fav_resume_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

            //회원 리스트
            $params = [
                "pagesize" => 20,
                "page" => $page,
                "search_type" => $search_type,
                "search_value" => $search_value,
            ];
            $result = $this->member_model->getBookMarkResumeList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"sum_info" => $sum_info,
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/member/fav_resume_list',$data);
		}

		function fav_list_proc(){
			$idx = input_post("idx",0);

			$result = $this->site_model->deleteBookmark(["bk_idx"=>$idx]);

			if($result["code"]=="00"){
				json_return("00","삭제 완료");
			}else{
				json_return("90","즐겨찾기 삭제시 오류가 발생하였습니다.");
			}
		}

    //개인-광고 리스트
		function memad_list(){
            //파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$show_yn = input_get("show_yn","");
			$category = input_get("category","");
      $mm_nation = input_get("mm_nation","");
      $ad_gbn = input_get("ad_gbn","");
      $pm_code = input_get("pm_code","");


			# 카테고리 검색 데이터 만들기
			if($category!=""){
				$str_category = implode("^",$category);
			}

      //회원 리스트
      $params = [
          "pagesize" => 20,
          "page" => $page,
          "search_type" => $search_type,
          "search_value" => $search_value,
          "show_yn" => $show_yn,
          "category" => $str_category,
          "mm_nation" => $mm_nation,
          "ad_gbn" => $ad_gbn,
          "pm_code" => $pm_code,
      ];
      $result = $this->member_model->getAdMemList($params);

			$category_list = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
			$select_category = getKeyValue($category_list,"cd_val","cd_val");

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
					"show_yn" => $show_yn,
          "category" => $category,
          "mm_nation" => $mm_nation,
          "ad_gbn" => $ad_gbn,
          "pm_code" => $pm_code,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"select_category" => $select_category,
			];

			$this->layout->admin('admin/member/memad_list',$data);
		}

    //기업-광고 리스트
		function memco_list(){
      //파라미터 설정
      $page = input_get("page",1);
      $search_type = input_get("search_type","");
      $search_value = input_get("search_value","");
      $show_yn = input_get("show_yn","");
      $category = input_get("category","");
      $rc_work = input_get("rc_work","");
      $mm_nation = input_get("mm_nation","");
      $ad_gbn = input_get("ad_gbn","");
      $pm_code = input_get("pm_code","");


      # 카테고리 검색 데이터 만들기
      if($category!=""){
        $str_category = implode("^",$category);
      }

      //회원 리스트
      $params = [
          "pagesize" => 20,
          "page" => $page,
          "search_type" => $search_type,
          "search_value" => $search_value,
          "show_yn" => $show_yn,
          "category" => $str_category,
          "rc_work" => $rc_work,
          "mm_nation" => $mm_nation,
          "ad_gbn" => $ad_gbn,
          "pm_code" => $pm_code,
      ];
      $result = $this->member_model->getAdCompList($params);

      $category_list = $this->site_model->getCodeList(["cd_grp"=>"희망업무"]);
      $select_category = getKeyValue($category_list,"cd_val","cd_val");

      //리턴 설정
      $data = [
        "search" =>[
          "search_type" => $search_type,
          "search_value" => $search_value,
          "show_yn" => $show_yn,
          "category" => $category,
          "rc_work" => $rc_work,
          "mm_nation" => $mm_nation,
          "ad_gbn" => $ad_gbn,
          "pm_code" => $pm_code,
        ],
        "start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
        "result_list" => $result["list"],
        "result_count" => $result["count"],
        "pagenation" => $result["pagenation"],
        "select_category" => $select_category,
      ];

      $this->layout->admin('admin/member/memco_list',$data);

		}

    //광고구매 등록
    function point_promotion(){
      # 데이터 추가.
      $pm_code = input_post("pm_code","");
      $pm_sdate = input_post("pm_sdate","");
      $pm_addr = input_post("pm_addr","");
      $pm_edate = date("Y-m-d 23:59:59");
      $pm_resume = input_post("pm_resume","");
      $pm_duration = input_post("pm_duration","");
      $pm_usepoint = 0;
      $ad_point = input_post("ad_point","");
      $mm_idx = input_post("mm_idx","");

      # 데이터 조회
      $mem_info = $this->member_model->getMember(["mm_idx"=>$mm_idx]);
      if($pm_code=="M001" || $pm_code=="M002" || $pm_code=="M003"){
        $promotion_info = $this->setting_model->getCodeInfo(["cd_grp"=>"광고구매","cd_key"=>$pm_code]);
      }else{
        $promotion_info = $this->setting_model->getCodeInfo(["cd_grp"=>"업체광고구매","cd_key"=>$pm_code]);
      }

      if($promotion_info==null){
        json_return("91","처리할 데이터가 존재하지 않습니다.");
      }

      if($pm_code=="M001"){
        //# + 7일 처리
        //$pm_sdate = $pm_sdate . " 00:00:00";
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 2000; }
        if($pm_duration=='5'){ $pm_usepoint = 5000; }
        if($pm_duration=='10'){ $pm_usepoint = 10000; }
        if($pm_duration=='20'){ $pm_usepoint = 20000; }
        if($pm_duration=='30'){ $pm_usepoint = 30000; }

      }else if($pm_code=="M002"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 1000; }
        if($pm_duration=='5'){ $pm_usepoint = 2500; }
        if($pm_duration=='10'){ $pm_usepoint = 5000; }
        if($pm_duration=='20'){ $pm_usepoint = 10000; }
        if($pm_duration=='30'){ $pm_usepoint = 15000; }

      }else if($pm_code=="M003"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 800; }
        if($pm_duration=='5'){ $pm_usepoint = 2000; }
        if($pm_duration=='10'){ $pm_usepoint = 4000; }
        if($pm_duration=='20'){ $pm_usepoint = 8000; }
        if($pm_duration=='30'){ $pm_usepoint = 12000; }
      }

      if($pm_code=="C001"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 4000; }
        if($pm_duration=='5'){ $pm_usepoint = 10000; }
        if($pm_duration=='10'){ $pm_usepoint = 20000; }
        if($pm_duration=='20'){ $pm_usepoint = 40000; }
        if($pm_duration=='30'){ $pm_usepoint = 60000; }

      }else if($pm_code=="C002"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 1000; }
        if($pm_duration=='5'){ $pm_usepoint = 2500; }
        if($pm_duration=='10'){ $pm_usepoint = 5000; }
        if($pm_duration=='20'){ $pm_usepoint = 10000; }
        if($pm_duration=='30'){ $pm_usepoint = 15000; }

      }else if($pm_code=="C003"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 800; }
        if($pm_duration=='5'){ $pm_usepoint = 2000; }
        if($pm_duration=='10'){ $pm_usepoint = 4000; }
        if($pm_duration=='20'){ $pm_usepoint = 8000; }
        if($pm_duration=='30'){ $pm_usepoint = 12000; }

      }else if($pm_code=="C004"){
        $pm_sdate = $pm_sdate . ":00";
        if($pm_duration=='2'){ $timestamp = strtotime($pm_sdate . " +2 days"); }
        if($pm_duration=='5'){ $timestamp = strtotime($pm_sdate . " +6 days"); }
        if($pm_duration=='10'){ $timestamp = strtotime($pm_sdate . " +14 days"); }
        if($pm_duration=='20'){ $timestamp = strtotime($pm_sdate . " +29 days"); }
        if($pm_duration=='30'){ $timestamp = strtotime($pm_sdate . " +44 days"); }
        $pm_edate = date("Y-m-d H:i:59", $timestamp);

        if($pm_duration=='2'){ $pm_usepoint = 1000; }
        if($pm_duration=='5'){ $pm_usepoint = 2500; }
        if($pm_duration=='10'){ $pm_usepoint = 5000; }
        if($pm_duration=='20'){ $pm_usepoint = 10000; }
        if($pm_duration=='30'){ $pm_usepoint = 15000; }

      }

      if($ad_point=="0"){
        $pm_usepoint = $ad_point;
      }

      if($mem_info["mm_point"] < $pm_usepoint){
        json_return("91","보유하신 포인트가 부족합니다.");
      }

      # 트랙젝션 시작
      $this->db->trans_begin();

      # 프로모션 데이터 등록
      $promotion = [
        "mm_idx" => $mm_idx,
        "pm_code"=> $promotion_info["cd_key"],
        "pm_name" => $promotion_info["cd_val"]."(".$pm_duration."일)",
        "pm_point" => $pm_usepoint,
        "pm_sdate" => $pm_sdate,
        "pm_edate" => $pm_edate,
        "pm_addr" => $pm_addr,
        "rc_idx" => $pm_resume,
      ];
      $result_promotion = $this->site_model->insertPromotionInfo($promotion);

      if($result_promotion["code"]!="00"){
         $this->db->trans_rollback();
        json_return("91","데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
      }

      # 포인트 취소
      $last_point = $mem_info["mm_point"] - $pm_usepoint; //$promotion_info["cd_val3"];
      $insert = [
        "mm_idx" => $mm_idx,
        "o_num"=>"",
        "pm_idx"=>$result_promotion["key"],
        "pt_type" => "PROMOTION",
        "pl_type" => "minus",
        "pl_point" => $pm_usepoint,
        "pl_last_point" => $last_point,
        "pl_desc" => "광고구매(" . $promotion_info["cd_val"] . ")",
        "reg_id" => "system",
      ];
      $result_point = $this->site_model->insertPointList($insert);

      if($result_point["code"]!="00"){
        $this->db->trans_rollback();
        json_return("91","데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
      }

      $result_member = $this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$mm_idx]);

      if($result_member["code"]!="00"){
        $this->db->trans_rollback();
        json_return("91","데이터 등록시 오류가 발생하였습니다. 관리자에게 문의 부탁드립니다.");
      }

      # 트랙젝션 완료
      $this->db->trans_commit();
      json_return("00","처리완료");
    }

    //마감처리
    function promotion_close(){
      # 데이터 추가.
      $pm_idx = input_post("pm_idx","");

      # 트랙젝션 시작
      $this->db->trans_begin();

      $sql = "update st_promotion set pm_edate = now() where pm_idx = '".$pm_idx."' ";
      $query = $this->db->query($sql);

      # 트랙젝션 완료
      $this->db->trans_commit();

      json_return("00","처리완료");
    }

    function recruit_write(){
			$rc_idx = input_post("rc_idx","");
			$rc_desc = input_post("rc_desc","");

			if($rc_idx==""){
			    json_return("99","공고번호가 확인되지 않습니다.");
			}

      //update
      # 트랙젝션 시작
      $this->db->trans_begin();

      $sql = "update st_recruit set rc_desc = '$rc_desc' where rc_idx = '".$rc_idx."' ";
      $query = $this->db->query($sql);

      # 트랙젝션 완료
      $this->db->trans_commit();

			$arr_recruit = [
				"rc_idx"=>$rc_idx,
			];

			json_return("00","처리완료",$arr_recruit);
		}

    function resume_write(){
			$ru_idx = input_post("ru_idx","");
			$ru_desc = input_post("ru_desc","");

			if($ru_idx==""){
			    json_return("99","이력서가 확인되지 않습니다.");
			}

      //update
      # 트랙젝션 시작
      $this->db->trans_begin();

      $sql = "update st_resume set ru_desc = '$ru_desc' where ru_idx = '".$ru_idx."' ";
      $query = $this->db->query($sql);

      # 트랙젝션 완료
      $this->db->trans_commit();

			$arr_resume = [
				"ru_idx"=>$ru_idx,
			];

			json_return("00","처리완료",$arr_resume);
		}

    function recruit_delete(){
			$rc_idx = input_post("rc_idx","");

			if($rc_idx==""){
			    json_return("99","공고번호가 확인되지 않습니다.");
			}

      //update
      # 트랙젝션 시작
      $this->db->trans_begin();

      $sql1 = "insert into st_recruit_del_history select * from st_recruit where rc_idx = '".$rc_idx."' ";
      $query1 = $this->db->query($sql1);

      // 영향 받은 행의 수를 얻기
      $affectedRows1 = $this->db->affected_rows();
      if($query1 && $affectedRows1 ==1){

          $sql2 = "delete from st_recruit where rc_idx = '".$rc_idx."' ";
          $query2 = $this->db->query($sql2);
          $affectedRows2 = $this->db->affected_rows();

          if($query2 && $affectedRows2 ==1){
            # 트랙젝션 완료
            $this->db->trans_commit();

      			$arr_recruit = [
      				"rc_idx"=>$rc_idx,
      			];

      			json_return("00","처리완료",$arr_recruit);
          }else{

            $this->db->trans_rollback();
            $arr_recruit = [
      				"rc_idx"=>$rc_idx,
      			];
            json_return("02","삭제오류. 관리자에게 문의하세요.",$arr_recruit);
          }


      }else{

          $this->db->trans_rollback();
          $arr_recruit = [
    				"rc_idx"=>$rc_idx,
    			];
          json_return("01","백업오류. 관리자에게 문의하세요.",$arr_recruit);
      }
		}

    function resume_delete(){
			$ru_idx = input_post("ru_idx","");

			if($ru_idx==""){
			    json_return("99","이력서가 확인되지 않습니다.");
			}

      //update
      # 트랙젝션 시작
      $this->db->trans_begin();

      $sql1 = "insert into st_resume_del_history select * from st_resume where ru_idx = '".$ru_idx."' ";
      $query1 = $this->db->query($sql1);

      // 영향 받은 행의 수를 얻기
      $affectedRows1 = $this->db->affected_rows();
      if($query1 && $affectedRows1 ==1){

          $sql2 = "delete from st_resume where ru_idx = '".$ru_idx."' ";
          $query2 = $this->db->query($sql2);
          $affectedRows2 = $this->db->affected_rows();

          if($query2 && $affectedRows2 ==1){
            # 트랙젝션 완료
            $this->db->trans_commit();

      			$arr_resume = [
      				"ru_idx"=>$ru_idx,
      			];

      			json_return("00","처리완료",$arr_resume);
          }else{

            $this->db->trans_rollback();
            $arr_resume = [
      				"ru_idx"=>$ru_idx,
      			];
            json_return("02","삭제오류. 관리자에게 문의하세요.",$arr_resume);
          }


      }else{

          $this->db->trans_rollback();
          $arr_resume = [
    				"ru_idx"=>$ru_idx,
    			];
          json_return("01","백업오류. 관리자에게 문의하세요.",$arr_resume);
      }
		}

    function member_birthsave(){
			$mm_idx = input_post("mm_idx");
      // 개인정보 보호: 입력값은 출생연도 4자리만 저장한다
      $mm_birth = maskBirthToYear(input_post("mm_birth"));

			$result = $this->member_model->updateMember(["mm_birth"=>$mm_birth],["mm_idx"=>$mm_idx]);
			if($result["code"]=="00"){
				json_return("00","처리완료");
			}else{
				json_return("92","생년월일 저장시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
			}
		}

    function member_gendersave(){
			$mm_idx = input_post("mm_idx");
      $mm_sex = input_post("mm_sex");

			$result = $this->member_model->updateMember(["mm_sex"=>$mm_sex],["mm_idx"=>$mm_idx]);
			if($result["code"]=="00"){
				json_return("00","처리완료");
			}else{
				json_return("92","성별 저장시 오류가 발생하였습니다. 관리자에 문의 해주세요.");
			}
		}

  }//class


?>
