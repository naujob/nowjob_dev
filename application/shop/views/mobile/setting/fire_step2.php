 <div class="page page-settings-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">회원탈퇴</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="leave-form">
            <div class="container">
                <form id="frm" onsubmit="return false">
                    <p class="primary-title"><em>나우잡</em>은 어떠셨나요?</p>

                    <div class="desc">
                        <p>탈퇴를하시려는 이유를 말씀해주세요. 개선에 중요한 자료로 사용하고자 합니다. 불편하셨던 점, 개선했으면 하는 점 등 말씀해주시면 감사드립니다.</p>
                    </div>

                    <div class="form-row">
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="기록 및 삭제 목적">
                            <span class="label">기록 및 삭제 목적</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="이용이 불편(장애 및 프로그램 오류)">
                            <span class="label">이용이 불편(장애 및 프로그램 오류)</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="포인트가 비싸서">
                            <span class="label">포인트가 비싸서</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="다른 사이트가 더 좋아서">
                            <span class="label">다른 사이트가 더 좋아서</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="딱히 쓸 이유를 못 느껴서">
                            <span class="label">딱히 쓸 이유를 못 느껴서</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="다른 회원들의 비매너">
                            <span class="label">다른 회원들의 비매너</span>
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="fire_reason" value="기타">
                            <span class="label">기타</span>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="sr-text">상세내용입력</span>
                            <textarea id="fire_reason_desc" name="fire_reason_desc" class="form-textarea js-autoheight"></textarea>
                        </label>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button type="button" id="btnNext" class="btn btn-primary js-leave">회원탈퇴</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>


<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
        if($("[name=fire_reason]").is(":checked")==false){
            commonAlert("탈퇴 사유를 선택해주세요.",function(){
            });
            return;
        }

        openPopup('confirm', {
            content: '탈퇴 하시겠습니까?',
            secondCallback: function () {
                var params = $("#frm").serialize();
                comAjax("/setting_proc/member_fire",params,function(ret){
                    if(ret.code=="00"){
                        commonAlert("탈퇴 처리되었습니다.이용해주셔서 감사합니다^^",function(){
                            location.replace("/member/login");
                        });
                    }else{
                    	commonAlert(ret.msg,function(){});
                    }
                });
            }
        });
	});
});
</script>
