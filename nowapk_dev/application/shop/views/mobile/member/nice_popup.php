<form name="form_chk" method="post">
	<input type="hidden" name="m" value="checkplusService">
	<input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>

<script>
	function fnPopup() {
		//window.open('', 'popupChk' + name_ver, 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		//document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		//document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		//alert("이거외안되~~~");
		//document.form_chk.submit();
		location.href = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>";
	}

	fnPopup();
</script>