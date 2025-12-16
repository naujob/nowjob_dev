<?php 

$file_name = $_POST["file_name"];

// 임시로 저장된 정보(tmp_name)
$tempFile = $_FILES[$file_name]['tmp_name'];

// 파일타입 및 확장자 체크
$fileTypeExt = explode("/", $_FILES[$file_name]['type']);

// 파일 타입 
$fileType = $fileTypeExt[0];

// 파일 확장자
$fileExt = $fileTypeExt[1];

// 확장자 검사
$extStatus = false;

switch($fileExt){
	case 'jpeg':
	case 'jpg':
	case 'gif':
	case 'bmp':
	case 'png':
		$extStatus = true;
		break;
	default:
		echo json_encode(["code"=>"90","msg"=>"이미지 전용 확장자(jpg,jpeg,gif,bmp,png)외에는 사용이 불가합니다.","url"=>""]); 
		exit;
		break;
}

// 이미지 파일이 맞는지 검사. 
if($fileType == 'image'){
	// 허용할 확장자를 jpg, bmp, gif, png로 정함, 그 외에는 업로드 불가
	if($extStatus){
		// 임시 파일 옮길 디렉토리 및 파일명
		$img_name = uniqid("img_") . "." . $fileExt;
		$resFile = "/website/nodejs/bot/uploads/" . $img_name;
		
		// 임시 저장된 파일을 우리가 저장할 디렉토리 및 파일명으로 옮김
		$imageUpload = move_uploaded_file($tempFile, $resFile);
		
		// 업로드 성공 여부 확인
		if($imageUpload == true){
			echo json_encode(["code"=>"00","msg"=>"업로드 성공","url"=>"/bot/uploads/" . $img_name]); 
		}else{
			echo json_encode(["code"=>"92","msg"=>"이미지 업로드를 실패하였습니다.","url"=>""]); 
		}
	}	// end if - extStatus
	else {
		echo json_encode(["code"=>"90","msg"=>"이미지 전용 확장자(jpg,jpeg,gif,bmp,png)외에는 사용이 불가합니다.","url"=>""]); 
		exit;
	}	
}
else {
	echo json_encode(["code"=>"91","msg"=>"이미지 파일이 아닙니다.","url"=>""]); 
	exit;
}
?>