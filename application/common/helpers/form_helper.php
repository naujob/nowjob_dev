<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//데이터 가져오기
function getData($obj,$colnm,$default=""){
	if($obj==null){
		return $default;
	}else {
		if(isset($obj[$colnm])){
			return $obj[$colnm];
		}else{
			return $default;
		}
	}
}

//NULL 확인
function isEmpty($obj){
	if(empty($obj)==true){
		return true;
	}else{
		return false;
	}
}

//처리 모드 가져오기
function getProcMode($info){
	if($info==null){
		return "insert";
	}else{
		return "update";
	}
}

//리스트 키,밸류 가져오기
function getKeyValue($list,$key,$value,$all = ""){
	$arr = [];
	if($all!=""){
		$arr[""] = $all;
	}
	foreach($list as $row){
		$arr[$row[$key]] = $row[$value];
	}
	return $arr;
}

//텍스트 박스 생성
function inputText($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<input type='text' id='$id' name='$name' placeholder='$placeholder' maxlength='$maxlen' value='$value' $add_info />";
}

function inputHidden($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<input type='hidden' id='$id' name='$name' placeholder='$placeholder' maxlength='$maxlen' value='$value' $add_info />";
}

//텍스트 박스 생성
function inputTextArea($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<textarea type='text' id='$id' name='$name' placeholder='$placeholder' maxlength='$maxlen' $add_info >$value</textarea>";
}


//텍스트 박스 생성
function inputPassword($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<input type='password' id='$id' name='$name' placeholder='$placeholder' maxlength='$maxlen' value='$value' $add_info />";
}

//넘버 박스 생성
function inputNumber($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<input type='number' id='$id' name='$name'  placeholder='$placeholder' maxlength='$maxlen' value='$value' $add_info  oninput=\"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');\" />";
}

//넘버 박스 생성
function inputTextNumber($id,$name,$value="",$placeholder="",$add_info="",$maxlen=10000){
	echo "<input type='text' id='$id' name='$name'  placeholder='$placeholder' maxlength='$maxlen' value='$value' $add_info  oninput=\"this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');\" />";
}

//텍스트 박스 생성
function inputImage($id,$name,$value="",$placeholder=""){
	echo "<label class='w300'>";
    echo "    <input type='file' id='$id' name='$name' class='file_input_hidden' accept='image/x-png,image/gif,image/jpeg' />";
    echo "    <span id='file_name'>".($placeholder)."</span>";
    echo "    <button type='button' class='btn-s w100'>파일 찾기</button>";
    echo "    <input type='hidden' id='$id"."_"."file_set_name' name='$id"."_"."file_set_name' value='' />";
    echo "</label>";
	if($value!=""){
	    echo "<div class='preview'>";
		echo "	<img src='$value' alt=''>";
		echo "	<a href='$value' class='btn-img-preivew' data-img='$value'>이미지 보기</a>";
		echo "</div>";
		echo "<input type='hidden' name='$name"."_"."ori' value='$value' />";
		echo "<label style='line-height:30px;vertical-align:middle;margin-left:5px;'><input type='checkbox' name='$name"."_"."del' value='Y' style='appearance:auto;vertical-align:-2px;' />선택삭제</label>";
	}

	echo "</div>";
}

//텍스트 박스 생성
function inputFile($id,$name,$value="",$value_nm="",$placeholder=""){
	echo "<label class='w300'>";
    echo "    <input type='file' id='$id' name='$name' class='file_input_hidden' accept='.hwp, .xls, .doc, .xlsx, .docx, .pdf, .jpg, .jpeg, .gif, .png, .txt, .ppt, .pptx'  onchange='getRealPath(this, '$id"."_"."file_name', '$id"."_"."file_set_name')'/>";
    echo "    <span id='file_name'>".($placeholder)."</span>";
    echo "    <button type='button' class='btn-s w100'>파일 찾기</button>";
    echo "    <input type='hidden' id='$id"."_"."file_set_name' name='$id"."_"."file_set_name' value=''/>";
    echo "</label>";
	if($value!=""){
	    echo "<div class='download'>";
		echo "	<a href='$value' target='_blank' class='btn-download' >첨부파일 다운로드</a>";
		echo "</div>";
		echo "<input type='hidden' name='$name"."_"."ori' value='$value' />";
		echo "<input type='hidden' name='$name"."_"."_name_ori' value='$value_nm' />";
		echo "<label style='line-height:30px;vertical-align:middle;margin-left:5px;'><input type='checkbox' name='$name"."_"."del' value='Y' style='appearance:auto;vertical-align:-2px;' />선택삭제</label>";
	}
	echo "</div>";
}


//선택박스 생성
function inputSelect($id,$name,$list,$value="",$add_info=""){
	$select = "<select id='$id' name='$name' $add_info>\n";

	foreach($list as $key => $list_value){

		$select .= "<option value='$key' ".($key==$value ? "selected":"").">$list_value</option>\n";
	}

	$select .= "</select>";

	echo $select;
}

//선택박스 생성
function inputSelectKeyValue($id,$name,$list,$key_code,$value_code,$value="",$add_info=""){
	$select = "<select id='$id' name='$name' $add_info>\n";

	foreach($list as $list_value){
		$key = $list_value[$key_code];
		$row_value = $list_value[$value_code];
		$select .= "<option value='$key' ".($key==$value ? "selected":"").">$row_value</option>\n";
	}

	$select .= "<select/>";

	echo $select;
}


//선택박스 생성
function inputRadio($id,$name,$list,$value="",$add_info=""){
	$radio = "";

	foreach($list as $key=> $list_value){
		$item = "
			<div class='input-radio'>
	            <label>
	                <input type='radio' id='$id"."_"."$key' $add_info name='$name' value='$key' ". ($key==$value ? "checked":"") ."  />
	                <span>$list_value</span>
	            </label>
	        </div>
		";
		$radio .= $item;
	}
	echo $radio;

}

//선택박스 생성
function inputCheck($id,$name,$list,$value=""){
	$radio = "";
	foreach($list as $key=> $list_value){
		$item = "
			<div class='input-checkbox'>
	            <label>
	                <input type='checkbox' id='$id"."_"."$key' name='$name' value='$key' ". checkValues($value,$key) ."  />
	                <span ".($list_value=="" ? "style='padding-left:5px;'":"")."><strong>$list_value</strong></span>
	            </label>
	        </div>
		";
		$radio .= $item;
	}
	echo $radio;
}

function checkValues($values,$key){
	//값이 없으면 리턴
	if($values==""){
		return "";
	}

	$arr_values = [];
	if(is_array($values)){
		$arr_values = $values;
	}else{
		$arr_values = explode("^",$values);
	}

	foreach($arr_values as $value){
		if($value==$key){
			return "checked";
			break;
		}
	}
	return "";
}
