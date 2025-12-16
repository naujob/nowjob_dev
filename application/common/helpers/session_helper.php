<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

# 관리자 아이디 가져오기
//"st_type" : ,
//"master_yn" : 마스터여부,
//"mg_idx"  : 고유키값,
//"admin_id" : 아이디,
//"admin_name"  이름,
//"st_icon_pc" : 숏컷아이콘,
//"st_site_name" : 싸이트명,
//"st_domain" : 도메인,
function getAdminInfo($col_name = ""){
	if(isset($_SESSION["ADMIN_INFO"])){
		$admin = $_SESSION["ADMIN_INFO"];
		if($col_name==""){
			return $admin;
		}else{
			return $admin[$col_name];
		}
	}else{
		return "";
	}
}