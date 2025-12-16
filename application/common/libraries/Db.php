<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class data {
	private $ci;
	
	function __construct() {
		$ci =& get_instance();
	} 
	
	//숫자확인
	function chkNumber($data){
		if($data==null){
			return 0;
		}
		return $data;
	}
	
	//숫자확인
	function chkEmpty($data){
		if($data==null){
			return "";
		}
		return $data;
	}
	
}