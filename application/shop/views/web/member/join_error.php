<div id="content" class="page page-join page-member-type1">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title">회원가입</h2>

            <div class="join-step1">
                <!--div class="join-auth">
                    <h3 class="title">본인인증에 앞서</h3>
                    <p class="desc">최하단의 “문자인증하기” 버튼을 클릭시PASS앱을 따로 설치하지 않고<br> 본인인증을 하실 수 있습니다!</p>
                    <button type="button" class="btn-round btn-primary">본인인증</button>
                </div-->

                <div class="error-msg">
                    <div class="container">
                        <img src="/resource/shop/web/img/join_has_id.svg" alt="" class="sign">
                        <p class="title">이미 가입된<br> <em>아이디가 존재</em>합니다!</p>
                        <p class="desc">아래에서 아이디를 선택하시면 자동으로 로그인됩니다.</p>
                    </div>
                </div>

                <ul class="account-list">
                    <li>
                        <a href="javascript:void(0);" id="btnLink"  role="button" aria-haspopup="dialog" class="angle-link">
                            <small class="meta"><?=$mem_info["mm_type"]=="MEM" ? "개인회원":"기업회원"?></small>
                            <strong class="value"><?=$mem_info["mm_id"]?></strong>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->
<script>
	$(document).ready(function(){
		$("#btnLink").click(function(){
			openPopup('confirm', {
	            content: '<?=$mem_info["mm_id"]?><br> 아이디로 로그인 하시겠습니까?',
	            secondCallback: function () {
	                var params = {};
	    			comAjax("/member_proc/join_error_proc",params,function(ret){
		                if(ret.code=="00"){
		                    location.href="/"
		                }else{
		                	commonAlert(ret.msg,function(){});
		                }
		            });
	            }
	        });
		});
	});
</script>
