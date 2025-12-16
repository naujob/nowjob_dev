 <div class="page page-join member-type1">
    <div class="top-bar">

        <h1 class="title">카카오 로그인 확인</h1>
    </div>
</div>
<script>
	$(document).ready(function(){
    if('<?=$code?>'=='90'){
      //회원가입
      openPopup('confirm', {
              id:'permission',
              content:'<strong>회원으로 가입된 정보가 없습니다.(카카오)</strong><small>회원 가입 진행하시겠습니까?</small>',
              firstButton:'취소',
              secondButton:'회원가입',
              firstCallback: function () {
  				          _replace("/member/login");
              },
              secondCallback:  function () {
                   _replace("/member/join_sns_start?join_key=<?=$res_id?>&join_type=" + "KAKAO");
              },
      });

    }else{
      //오류메시지
      openPopup('modal', {
              id:'permission',
              title: '안내',
              content:'<div style="padding: 20px 70px;"><?=$msg?></div>',
              firstButton:'확인',
              firstCallback: function () {
  				          _replace("/member/login");
              },
          });
    }

	});
</script>
