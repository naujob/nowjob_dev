<script>
	function showPass() {
		var window = popupCenter();
		if (window != undefined && window != null) {
			document.saForm.setAttribute("target", "sa_popup");
			document.saForm.setAttribute("post", "post");
			document.saForm.setAttribute("action", "https://sa.inicis.com/auth");
			document.saForm.submit();
		}
	}

	function popupCenter() {
		var _width = 400;
		var _height = 620;
		var xPos = (document.body.offsetWidth / 2) - (_width / 2); // 가운데 정렬
		xPos += window.screenLeft; // 듀얼 모니터일 때
		return window.open("", "sa_popup", "width=" + _width + ", height=" + _height + ", left=" + xPos + ", menubar=yes, status=yes, titlebar=yes, resizable=yes");
	}
</script>
<?php

$mid = 'INIiasTest';                            // 테스트 MID 입니다. 계약한 상점 MID 로 변경 필요
$apiKey = 'TGdxb2l3enJDWFRTbTgvREU3MGYwUT09';   // 테스트 MID 에 대한 apiKey
$mTxId = 'test_20210625';
$reqSvcCd = '01';
$reservedMsg = 'isUseToken=Y';                // 결과조회 응답시 개인정보SEED 암호화 처리 요청

//hash key 만들기
$plainText1 = hash("sha256", (string)$mid . (string)$mTxId . (string)$apiKey);
$authHash = $plainText1;

$userName = '';            	// 사용자 이름
$userPhone = '';    		// 사용자 전화번호
$userBirth = '';        		// 사용자 생년월일

$flgFixedUser = 'N';		// 특정사용자 고정시 Y

if ($flgFixedUser == "Y") {
	$plainText2 = hash("sha256", (string)$userName . (string)$mid . (string)$userPhone . (string)$mTxId . (string)$userBirth . (string)$reqSvcCd);
	$userHash = $plainText2;
}
?>

<form name="saForm">
	<?php $app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/'); ?>

	<input type="hidden" name="mid" value="<?php echo $mid ?>">
	<input type="hidden" name="reqSvcCd" value="<?php echo $reqSvcCd ?>">
	<input type="hidden" name="mTxId" value="<?php echo $mTxId ?>">
	<input type="hidden" name="authHash" value="<?php echo $authHash ?>">
	<input type="hidden" name="flgFixedUser" value="<?php echo $flgFixedUser ?>">
	<input type="hidden" name="userName" value="<?php echo $userName ?>">
	<input type="hidden" name="userPhone" value="<?php echo $userPhone ?>">
	<input type="hidden" name="userBirth" value="<?php echo $userBirth ?>">
	<input type="hidden" name="userHash" value="<?php echo $userHash ?>">
	<input type="hidden" name="reservedMsg" value="<?php echo $reservedMsg ?>">
	<input type="hidden" name="directAgency" value="">
	<input type="hidden" name="successUrl" value="<?= $app_base_url ?>/member/join_pass_success?join_type=<?= $join_type ?>">
	<input type="hidden" name="failUrl" value="<?= $app_base_url ?>/member/join_pass_fail?join_type=<?= $join_type ?>">
	<!-- successUrl/failUrl 은 분리하여도 됩니다. !-->

</form>
