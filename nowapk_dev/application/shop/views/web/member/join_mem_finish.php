<div id="content" class="page page-join page-member-type1">
    <main class="page-content">
        <div class="container">

            <div class="join-welcome">
                <h2 class="primary-title">가입을 축하드립니다</h2>
                <p>나우잡의 제공하는 채용정보및 다양한혜택을<br> 이용하실 수 있습니다.</p>
                <div class="form-action-wrap" id="non_mem_last_cont" style="display:none;">
				</div>
                <div class="form-action-wrap">
                    <a href="/" class="btn-round btn-tertiary">메인으로 바로가기</a>
<?php if($this->user->getUserType() == 'MEM'){ ?>
                    <a href="/mypage/resume_list" class="btn-round btn-tertiary" style="margin-left:20px;">이력서 등록하러 가기</a>
<?php } ?>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<script>
if(getCookie("non_mem_last_cont")!='' && getCookie("non_mem_last_cont")!='null'){
	var msg = '';
	msg = '<a href="'+getCookie("non_mem_last_cont")+'" class="btn-round btn-tertiary" style="width: 564px;">채용공고 계속보기</a><br />';
	$('#non_mem_last_cont').append(msg);
	$('#non_mem_last_cont').show();
	setCookie('non_mem_last_cont', '', 1);
}
</script>