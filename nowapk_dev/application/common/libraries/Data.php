<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class data {
	private $ci;
	
	function __construct() {
		$ci =& get_instance();
	} 
	
	//숫자타입확인
	function chkNumber($data){
		if($data==null){
			return 0;
		}
		return $data;
	}
	
	//쿼리 in 조건만들기
	function qryArrIn($arr){
		$str_ret = "";
		
		for($i=0;$i < count($arr);$i++){
			
			if($str_ret==""){
				$str_ret = "'" . $arr[$i] . "'";
			}else{
				$str_ret = $str_ret . ",'" . $arr[$i] . "'";
			}
		}
		
		return $str_ret;
	}
	
	//array 그룹by처리
	function arrayGroupBy(array $data, $by_column)
	{
	    $result = [];
	
	    foreach ($data as $item) {
	        $column = $item[$by_column];
	        unset($item[$by_column]);
	        if (isset($result[$column])) {
	            $result[$column][] = $item;
	        } else {
	            $result[$column] = array($item);
	        }
	    }
	
	    return $result;
	}
	
	function setSmilepayLog($type,$msg)
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('file');
		$this->CI->load->helper('url');
		
	    $filename = $type . "_" .date("Ymd").".log";
		$new_file_path = FCPATH . 'resource/log/smilepay/' . $filename ;
		
		if (!write_file($new_file_path, $msg . "\n\n","a+"))
        {
			return false;
        }else{
        	return true;
        }
	}	
	
	function getArrayString($array){
		$data = "";
		foreach($array as $key=>$val){
			if($data == ""){
				$data = $data . $key . ":" . $val;	
			}else{
				$data =  $data . "$" . $key . ":" . $val;
			}
			
		}
		return $data;
	}
	
	function removeSpc($temp){
		$temp = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $temp);
		return $temp;
	}
	
	function chgSpcStr($str){
		$str = str_replace("&lt;","<",$str);
		$str = str_replace("&gt;",">",$str);
		$str = str_replace("&quot;","\"",$str);
		$str = str_replace("&#39;","'",$str);
		return $str;
	}

}