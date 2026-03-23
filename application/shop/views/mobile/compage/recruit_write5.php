<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고등록</h1>
        <ol class="progress-steps">
            <li>1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
            <li title="헌재위치" class="active">5</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false">
        	<input type="hidden" name="rc_idx" value="<?=$info["rc_idx"]?>" />
            <div class="section addition-info-form">
                <div class="container">
                    <div class="form-row">
                        <p class="form-label">상세업무정보 <em>최대500자</em></p>
                        <label>
                            <span class="sr-text">상세업무정보 입력</span>
                            <textarea name="rc_desc" maxlength="500" class="form-textarea js-autoheight"><?=strip_tags($info["rc_desc"])?></textarea>
                        </label>
                    </div>

                    <div class="form-row">
                        <p class="form-label">해시태그</p>
                        <label>
                            <span class="sr-text">상세업무정보 입력</span>
                            <textarea name="rc_hash" maxleng="100" placeholder="예) #경력무관, #나이무관 #생산" class="form-textarea sm"><?=$info["rc_hash"]?></textarea>
                        </label>
                    </div>

                    <div class="form-row">
                         <p class="form-label">첨부사진</p>
                        <div class="form-pic-group">
                            <label class="form-pic first">
                                <input type="file" name="rc_img1" accept="image/*" class="js-file-preview">
                                <span class="label">사진을<br> 첨부해주세요</span>
                                <span class="preview">
	                            	<?php if(getData($info,"rc_img1")!="") : ?>
	                            		<img src="<?=getData($info,"rc_img1")?>" />
	                            		<input type='hidden' name='rc_img1_ori' value='<?=getData($info,"rc_img1")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="rc_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"rc_img2")!="") : ?>
	                            		<img src="<?=getData($info,"rc_img2")?>" />
	                            		<input type='hidden' name='rc_img2_ori' value='<?=getData($info,"rc_img2")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="rc_img3" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"rc_img3")!="") : ?>
	                            		<img src="<?=getData($info,"rc_img3")?>" />
	                            		<input type='hidden' name='rc_img3_ori' value='<?=getData($info,"rc_img3")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="rc_img4" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"rc_img4")!="") : ?>
	                            		<img src="<?=getData($info,"rc_img4")?>" />
	                            		<input type='hidden' name='rc_img4_ori' value='<?=getData($info,"rc_img4")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="rc_img5" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"rc_img5")!="") : ?>
	                            		<img src="<?=getData($info,"rc_img5")?>" />
	                            		<input type='hidden' name='rc_img5_ori' value='<?=getData($info,"rc_img5")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                        </div>
                        <p class="help-msg">사진을 추가하시면 구인활동에 더 좋습니다.</p>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary"><?=$info["rc_idx"]!="0" ? "수정":"등록"?>완료</button>
                    </div>

                </div>
            </div>

        </form>
    </div><!-- //page-content -->
</div>
<script>
  window.addEventListener('DOMContentLoaded', function() {  
        $("#btnPrev").click(function(){
			history.go(-1);
		});

		$("#btnStep").click(function(){
            if($("[name='rc_desc']").val()==""){
                commonAlert("상세업무정보를 입력해주세요.",function(){});
                return;
            }

            //if(($("[name=mm_img1]").val()==""  && $("[name=mm_img1_ori]").val()==undefined) || ($("[name=mm_img1_ori]").val()=="" && $("[name=mm_img1_ori]").val()=="")){
            //	commonAlert("기업 대표이미지를 선택해주세요.",function(){});
            //    return;
            //}

            //if(($("[name=rc_img1]").val()==""  && $("[name=rc_img1_ori]").val()==undefined) || ($("[name=rc_img1_ori]").val()=="" && $("[name=rc_img1_ori]").val()=="")){
            //	commonAlert("첨부사진을 1개 이상 선택해주세요.",function(){});
            //    return;
            //}

            var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjaxLoading("/compage_proc/recruit_write5",params,function(ret){
                if(ret.code=="00"){
                    showPreviewInfo(ret.data);
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});
	});

    function showPreviewInfo(info){
    	var str_rc_work_img = "";
    	var str_rc_week = "";
    	var str_img = "";
    	var str_price = "";
    	var str_time = "";
    	var str_hash = "";
    	var str_life_type = "";

    	if(info.rc_week){
	    	for(var icnt=0;icnt < info.rc_week.length;icnt++){
	    		str_rc_week += "<li aria-hidden='false'>"+info.rc_week[icnt]+"</li>";
	    	}
    	}

    	if(info.rc_img1){
    		str_img += "<div class='pic-card'><img src='"+info.rc_img1+"' alt=''></div>";
    	}

    	if(info.rc_img2){
    		str_img += "<div class='pic-card'><img src='"+info.rc_img2+"' alt=''></div>";
    	}

    	if(info.rc_img3){
    		str_img += "<div class='pic-card'><img src='"+info.rc_img3+"' alt=''></div>";
    	}

    	if(info.rc_img4){
    		str_img += "<div class='pic-card'><img src='"+info.rc_img4+"' alt=''></div>";
    	}

    	if(info.rc_img5){
    		str_img += "<div class='pic-card'><img src='"+info.rc_img5+"' alt=''></div>";
    	}

    	if(info.rc_price_type == "협의"){
    		str_price = '<em class="">급여협의</em>';
    	}else{
    		str_price = '<em class="currency">'+info.rc_price_pay.format()+'</em>('+info.rc_price_rep+')';
    	}


      if(info.rc_work_type.indexOf('2교대') >-1 || (info.rc_work_type.indexOf('주간') >-1 && info.rc_work_type.indexOf('야간') >-1)){
        str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
        str_time += '<br/>' + info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
      }else if(info.rc_work_type.indexOf('주간') >-1 && info.rc_work_type.indexOf('2교대') ==-1){
    		str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
    	}else if(info.rc_work_type.indexOf('야간') >-1 && info.rc_work_type.indexOf('2교대') ==-1){
    		str_time +=info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
    	}

    	for(var icnt=0;icnt < info.rc_hash.split(",").length;icnt++){
    		if(info.rc_hash.split(",")[icnt]!=""){
    			str_hash += '<p class="tag">'+info.rc_hash.split(",")[icnt].trim()+'</p>';
    		}
    	}

    	if(info.rc_life_type){
	    	for(var icnt=0;icnt < info.rc_life_type.length;icnt++){
	    		str_life_type += '<li class="item"><p class="benefit-label benefit1-1">'+info.rc_life_type[icnt]+'</p></li>';
	    	}
    	}

        openPopup('modal', {
            content: '<div class="regist-info">'+
                    '<h5 class="name">'+info.rc_name+'</h5>'+
                    '<table>'+
                        '<caption><span class="sr-text">채용공고정보</span></caption>'+
                        '<colgroup><col><col></colgroup>'+
                        '<tbody>'+
                        	'<tr>'+
                                '<td class="label">공개여부</td>'+
                                '<td class="value">'+(info.rc_show_yn == 'Y' ? '공개':'비공개')+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">업무구분</td>'+
                                '<td class="value">'+
                                    info.rc_work_img +
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                '<td class="label">금액</td>'+
                                '<td class="value txt-color2">'+
                                   str_price +
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">급여형태</td>'+
                                '<td class="value">'+(info.rc_price_type == "협의" ? '급여협의':info.rc_price_type)+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시간</td>'+
                                '<td class="value">'+str_time+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시작일</td>'+
                                '<td class="value">'+info.rc_work_dt + '('+info.rc_work_dt_yoil+')' + '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망성별</td>'+
                                '<td class="value">'+info.rc_sex+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망국적</td>'+
                                '<td class="value">'+info.rc_nation+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망나이</td>'+
                                '<td class="value">'+info.rc_sage+'세이상~'+info.rc_eage+'세이하</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무요일</td>'+
                                '<td class="value">'+
                                    '<ul class="working-day">'+
                                        str_rc_week +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    '<div class="hashtag">'+
                                        str_hash +
                                    '</div>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">복리후생</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                '<ul class="benefit-group">'+
                                    info.rc_life_type_tag +
                                '</ul>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">상세업무정보</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    info.rc_desc+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">첨부사진</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                   str_img +
                                '</td>'+
                            '</tr>'+
                        '</tbody>'+
                    '</table>'+
                '</div>',
            firstButton: '정보수정',
            secondButton: ($("[name=rc_idx]").val()!="0" ? '수정완료' : '등록완료'),
            firstCallback: function () {

            },
            secondCallback: function () {
				var params = {};
				params["rc_idx"] = $("[name=rc_idx]").val();
	            comAjax("/compage_proc/recruit_complete",params,function(ret){
	                if(ret.code=="00"){
	                    location.href="/compage/recruit_list";
	                    //_replace("/compage/recruit_list");
	                }else{
	                    commonAlert(ret.msg,function(){});
	                    return;
	                }

	            });
            }
        });
    }

    //뒤로 왔을때 마이페이지로 이동
	window.addEventListener('pageshow', function (event) {
		if (event.persisted || window.performance && window.performance.navigation.type == 2) {
			_replace("/compage/index");
		} else {

		}
	}, false);
</script>
