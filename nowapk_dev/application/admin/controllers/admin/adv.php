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

		function member_edit_proc(){
			$proc_mode = input_post("proc_mode","");
			$idx = input_post("idx",0);

			if($proc_mode=="delete"){
				$result = $this->member_model->updateMember(["mm_status"=>"H"],["mm_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","삭제 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}else if($proc_mode=="return"){
				$result = $this->member_model->updateMember(["mm_status"=>"Y"],["mm_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","자격정지 해제 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}else if($proc_mode=="stop"){
                $mm_stop_desc = input_post("mm_stop_desc","");
				$result = $this->member_model->updateMember(["mm_status"=>"S","mm_stop_desc"=>$mm_stop_desc,"stop_dt"=>get_today()],["mm_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","강제정지 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
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

		//광고구매 리스트
		function memad_list(){ //recruit_list
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

			$this->layout->admin('admin/adv/memad_list',$data);
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

            if($proc_mode=="delete"){
				$result = $this->member_model->updateResume(["ru_status"=>"H"],["ru_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","비노출 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}else if($proc_mode=="stop"){
                $stop_desc = input_post("stop_desc","");
				$result = $this->member_model->updateResume(["ru_status"=>"H","stop_desc"=>$stop_desc,"stop_dt"=>get_today()],["ru_idx"=>$idx]);

                if($result["code"]=="00"){
    				json_return("00","관리자 비공개 처리 완료");
    			}else{
    				json_return("90","데이터 처리시 오류가 발생하였습니다.");
    			}
			}
		}

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
    }
?>
