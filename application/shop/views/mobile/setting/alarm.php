<div class="page page-settings-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">알림</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false">
            <div class="options-wrap">

                <div class="section">
                    <div class="container">
                        <!-- 옵션 목록 -->
                        <div class="options-list">
                            <div class="top">
                                <span class="label">
                                    <strong>전체 푸시 알림</strong>
                                    <small>전체푸시 알림을 받습니다.</small>
                                </span>
                                <span class="value">
                                    <label class="btn-toggle">
                                        <input type="checkbox" name="alarm_all" value="1" data-controls="pushOpt" class="js-toggle-list" <?=$alarm_all_checked?>>
                                        <span class="btn"></span>
                                    </label>
                                </span>
                            </div>

                            <!-- 하위 옵션 목록 -->
                            <ul id="pushOpt" class="options-list" <?=$alarm_all_checked=="checked" ? "":"hidden"?>>
                                <li>
                                    <span class="label">채팅메세지 알림</span>
                                    <span class="value">
                                        <label class="btn-toggle">
                                            <input type="checkbox" name="alarm_chat" value="Y" <?=$mem_info["alarm_chat"]=="Y" ? "checked":""?>>
                                            <span class="btn"></span>
                                        </label>
                                    </span>
                                </li>
                                <li>
                                    <span class="label">
                                        <?= ($mem_info["mm_type"] == "COMP") ? "이력서 매칭 알림" : "채용공고 매칭 알림" ?>
                                    </span>
                                    <span class="value">
                                        <label class="btn-toggle">
                                            <input type="checkbox" name="alarm_resume" value="Y" <?=$mem_info["alarm_resume"]=="Y" ? "checked":""?>>
                                            <span class="btn"></span>
                                        </label>
                                    </span>
                                </li>
                                <!-- 관심등록 알림 - 사용 안함 (주석처리)
                                <li>
                                    <span class="label">관심등록 알림</span>
                                    <span class="value">
                                        <label class="btn-toggle">
                                            <input type="checkbox" name="alarm_fav" value="Y" <?=$mem_info["alarm_fav"]=="Y" ? "checked":""?>>
                                            <span class="btn"></span>
                                        </label>
                                    </span>
                                </li>
                                -->
                                <?php if($mem_info["mm_type"] == "COMP") : ?>
                                <li>
                                    <span class="label">개인회원 지원 알림</span>
                                    <span class="value">
                                        <label class="btn-toggle">
                                            <input type="checkbox" name="alarm_apply" value="Y" <?=$mem_info["alarm_apply"]=="Y" ? "checked":""?>>
                                            <span class="btn"></span>
                                        </label>
                                    </span>
                                </li>
                                <li>
                                    <span class="label">추천인재알림</span>
                                    <span class="value">
                                        <label class="btn-toggle">
                                            <input type="checkbox" name="alarm_adv" value="Y" <?=$mem_info["alarm_adv"]=="Y" ? "checked":""?>>
                                            <span class="btn"></span>
                                        </label>
                                    </span>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <!-- //옵션목록 -->

                    </div>
                </div>
                <div class="section">
                    <div class="container">

                        <div class="options-list">
                            <div class="top">
                                <span class="label">
                                    <strong>방해금지 시간대 설정</strong>
                                    <small>설정한 시간동안은 알림을 받지 않습니다</small>
                                </span>
                                <span class="value">
                                    <label class="btn-toggle">
                                        <input type="checkbox" name="alarm_deny_yn" value="Y" data-controls="dndModeOpt" class="js-toggle-list" <?=$mem_info["alarm_deny_yn"]=="Y" ? "checked":""?>>
                                        <span class="btn"></span>
                                    </label>
                                </span>
                            </div>

                            <!-- 하위 옵션 목록 -->
                            <ul id="dndModeOpt" class="options-list" <?=$mem_info["alarm_deny_yn"]=="Y" ? "":"hidden"?>>
                                <li>
                                    <div class="form-toggle-group align-justify">
                                        <?php foreach($alarm_yoil_list as $yoil) : ?>
                                        <label class="form-toggle-btn2">
                                            <input type="checkbox" name="alarm_deny_yoil[]" value="<?=$yoil?>"  <?=in_array_return($yoil,$alarm_deny_yoil_array,"check")?>>
                                            <span class="btn"><?=$yoil?></span>
                                        </label>
                                        <?php endforeach;?>
                                    </div>
                                </li>
                                <li>
                                    <!-- 20221022 수정 -->
                                    <div class="inline-group">
                                        <div class="col">
                                            <label>
                                                <span class="form-label">시작시간</span>
                                                <input type="text" name="alarm_deny_stime" id="alarm_deny_stime" value="<?=$mem_info["alarm_deny_stime"]?>" placeholder="00:00" readonly maxlength="5" required class="form-text js-time">
                                            </label>
                                        </div>
                                        <span class="entity">~</span>
                                        <div class="col">
                                            <label>
                                                <span class="form-label">종료시간</span>
                                                <input type="text" name="alarm_deny_etime" id="alarm_deny_etime" value="<?=$mem_info["alarm_deny_etime"]?>" placeholder="00:00" readonly required class="form-text js-time">
                                            </label>
                                        </div>
                                    </div>

                                    <script>
                                        //20221022 수정
                                        /* 시간설정 팝업표시 */
                                        window.addEventListener('DOMContentLoaded', function () {
                                            if (typeof Rolldate == 'undefined') return;
                                            new Rolldate({
                                                el: '#alarm_deny_stime',
                                                format: 'hh:mm',
                                                hour12: true,
                                                lang: { title: '시작시간', description:'시작시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                                            });
                                            new Rolldate({
                                                el: '#alarm_deny_etime',
                                                format: 'hh:mm',
                                                hour12: true,
                                                lang: { title: '종료시간', description:'종료시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                                            });
                                        });
                                    </script>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
            <div class="sticky-bottom view-footer">
                <button type="button" id="btnSave" class="btn btn-primary">설정</button>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
    /* 하위 체크박스 표시, 숨김 */
    (function () {
        function showList (el) {
            var target = document.getElementById(el.getAttribute('data-controls'));
            if (!target) return;
            if(el.checked) {
                target.hidden = false;
            } else {
                target.hidden = true;
            }
        }
        document.querySelectorAll('.js-toggle-list').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                showList(this);
            });
            showList(checkbox);
        });
    }());

    $(document).ready(function(){
    	$("[name=alarm_all]").click(function(){
    		var check = $(this).is(":checked");
    		$("[name=alarm_chat]").prop("checked",check);
    		$("[name=alarm_resume]").prop("checked",check);
    		// $("[name=alarm_fav]").prop("checked",check);  // 관심등록 알림 - 사용 안함 (주석처리)
    		$("[name=alarm_apply]").prop("checked",check);
    		$("[name=alarm_adv]").prop("checked",check);
    	})
    	
    	$("#btnSave").click(function(){
            var alarm_deny_yoil = "";

            if($("[name=alarm_deny_yn]").is(":checked")==true){
                for(var icnt =0;icnt < $("[name='alarm_deny_yoil[]']:checked").length;icnt++){
                    if(alarm_deny_yoil==""){
                        alarm_deny_yoil = $("[name='alarm_deny_yoil[]']:checked").eq(icnt).val();
                    }else{
                        alarm_deny_yoil = alarm_deny_yoil + "," + $("[name='alarm_deny_yoil[]']:checked").eq(icnt).val();
                    }
                }

                if(alarm_deny_yoil==""){
                    commonAlert("방해금지 요일을 선택해주세요.",function(){

                    });
                    return;
                }

                if($("[name=alarm_deny_stime]").val()==""){
                    commonAlert("방해금지 시작시간을 선택해주세요.",function(){
                        $("[name=alarm_deny_stime]").focus();
                    });
                    return;
                }

                if($("[name=alarm_deny_etime]").val()==""){
                    commonAlert("방해금지 종료시간을 선택해주세요.",function(){
                        $("#change_pwd_c").focus();
                    });
                    return;
                }

                if($("[name=alarm_deny_stime]").val() >= $("[name=alarm_deny_etime]").val()){
                    commonAlert("시작시간을 종료시간 이전 시간으로 선택해주세요.",function(){
                        $("#change_pwd_c").focus();
                    });
                    return;
                }
            }

    		var params = {};
            params["alarm_chat"] = $("[name=alarm_chat]").is(":checked") ? "Y":"N";
            params["alarm_resume"] = $("[name=alarm_resume]").is(":checked") ? "Y":"N";
            // params["alarm_fav"] = $("[name=alarm_fav]").is(":checked") ? "Y":"N";  // 관심등록 알림 - 사용 안함 (주석처리)
            params["alarm_apply"] = $("[name=alarm_apply]").is(":checked") ? "Y":"N";
            params["alarm_adv"] = $("[name=alarm_adv]").is(":checked") ? "Y":"N";
            params["alarm_deny_yn"] = $("[name=alarm_deny_yn]").is(":checked") ? "Y":"N";
            params["alarm_deny_yoil"] = alarm_deny_yoil;
            params["alarm_deny_stime"] = $("[name=alarm_deny_stime]").val();
            params["alarm_deny_etime"] = $("[name=alarm_deny_etime]").val();
            comAjax("/setting_proc/alarm",params,function(ret){
                if(ret.code=="00"){
                    commonAlert("알람 설정이 적용되었습니다.",function(){
                        location.reload();
                    });
                }else{
                	commonAlert(ret.msg,function(){});
                }
            });
    	});
    });


</script>
