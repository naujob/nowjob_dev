 <div class="page page-join member-type1">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">개인회원 회원가입</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        
        <div class="error-msg">
            <div class="container">
                <img src="/resource/shop/mobile/img/join_has_id.svg" alt="" class="sign">
                <p class="title">이미 가입된<br> <em>아이디가 존재</em>합니다!</p>
                <p class="desc">아래에서 아이디를 선택하시면<br> 자동으로 로그인됩니다.</p>
            </div>
        </div>

        <ul class="account-list">
            <li>
                <a href="javascript:void(0);" id="btnLink" role="button" aria-haspopup="dialog" class="angle-link">
                    <small class="meta"><?=$mem_info["mm_type"]=="MEM" ? "개인회원":"기업회원"?></small>
                    <strong class="value"><?=$mem_info["mm_id"]?></strong>
                </a>
            </li>
        </ul>
    </div><!-- //page-content -->
</div>
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