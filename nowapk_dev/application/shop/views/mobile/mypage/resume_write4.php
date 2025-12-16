 <div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서 <?=$info["ru_idx"]!="0" ? "수정":"등록"?></h1>
        <ol class="progress-steps">
            <li>1</li>
            <li>2</li>
            <li>3</li>
            <li title="헌재위치" class="active">4</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section addition-info-form">
            <div class="container">
               <form id="frm" onsubmit="return false">
               		<input type="hidden" name="arr_certi" value="" />
                    <div class="form-row">
                        <p class="form-label">자기소개<em>최대500자</em></p>
                        <label>
                            <span class="sr-text">자기소개 입력</span>
                            <textarea name="ru_desc" maxlength="500" class="form-textarea js-autoheight"><?=$info["ru_desc"]?></textarea>
                        </label>
                        <p class="help-msg">개인 연락처 및 카카오톡 아이디 첨부 시 게시물 삭제 및 이용제제 처리될 수 있습니다.</p>
                    </div>
                    <div class="form-row">
                        <p class="form-label">자격증 <em>허위사실 기재시 합격이 취소될 수 있습니다</em></p>
                        <!-- 20220929 수정 -->
                        <!-- 목록 -->
                        <ol class="added-list">
                        	<?php $arr_count = 0; ?>
	                       	<?php if($info["arr_certi"]!="") :
	                       		$arr_crtyi = explode("&&&",$info["arr_certi"]);
								$arr_count = count($arr_career);
								foreach($arr_crtyi as $row):
									$arr_temp = explode("|||",$row);
							?>
								<li>
	                               <div class="form-group">
	                                    <div class="col md">
	                                        <label>
	                                            <span class="sr-text">자격증명 입력</span>
	                                            <input type="text" name="license[]" maxlength="13" placeholder="자격증명" value="<?=$arr_temp[0]?>" autocomplete="off" class="form-text name">
	                                        </label>
	                                    </div>
	                                    <div class="col">
	                                        <label>
	                                            <span class="sr-text">취득일자 입력</span>
	                                            <input type="text" name="regdate[]" placeholder="취득일자" value="<?=$arr_temp[1]?>" autocomplete="off" class="form-text num js-datepicker">
	                                        </label>
	                                    </div>
	                                </div>
	                                <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
	                           </li>
							<?php endforeach; ?>
							<?php endif; ?>
                        </ol>
                        <button type="button" class="btn-add-item js-add-license" style="<?=($arr_count>=3) ? "display:none;":""?>">등록</button>

                        <!-- 입력폼 템플릿 -->
                        <template id="licenseTemp">
                            <li>
                                <div class="form-group">
                                    <div class="col md">
                                        <label>
                                            <span class="sr-text">자격증명 입력</span>
                                            <input type="text" name="license[]" maxlength="13" placeholder="자격증명" autocomplete="off" class="form-text name">
                                        </label>
                                    </div>
                                    <div class="col">
                                        <label>
                                            <span class="sr-text">취득일자 입력</span>
                                            <input type="text" name="regdate[]" placeholder="취득일자" autocomplete="off" class="form-text num js-datepicker">
                                        </label>
                                    </div>
                                </div>
                                <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                            </li>
                        </template>

                        <script>
                            (function () {
                                function chgYear (instance) {
                                    var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                        year = instance.currentYear;
                                    $con.innerHTML = '<span class="month">' + year + '</span>';
                                }

                                function initDatepicker (el) {
                                    if (typeof flatpickr == 'undefined') return;

                                    //달력 생성
                                    flatpickr('.js-datepicker', {
                                        locale: 'ko',
                                        nextArrow: '<span class="sr-text">다음</span>',
                                        prevArrow: '<span class="sr-text">이전</span>',
                                        disableMobile: true,
                                        plugins: [
                                            new monthSelectPlugin({
                                                shorthand: true,
                                                dateFormat: "Y.m",
                                                altFormat: "Y.m",
                                            })
                                        ],
                                        onYearChange: function (selectedDates, dateStr, instance) {
                                            chgYear(this);
                                        },
                                        onReady: function () {
                                            chgYear(this);
                                        },
                                        onClose: function (selectedDates, dateStr, instance) {
                                            if (selectedDates.length > 1) {
                                                document.querySelector('.flatpickr-next-month').click();
                                            }
                                        },
                                        onOpen: function (selectedDates, dateStr, instance) {
                                            if (selectedDates.length > 1) {
                                                document.querySelector('.flatpickr-prev-month').click();
                                            }
                                        },
                                        onChange: function (selectedDates, dateStr, instance) {
                                            instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function (month) {
                                                month.classList.remove('startRange', 'inRange', 'endRange')
                                            });
                                        }
                                    });
                                }

                                window.addEventListener('DOMContentLoaded', function () {

                                    //자격증 목록 추가
                                    document.querySelector('.js-add-license').addEventListener('click', function (e) {
                                        e.preventDefault();
                                        var btn = this,
                                            max = 5,
                                            temp = document.getElementById('licenseTemp').content.cloneNode(true),
                                            list = btn.parentNode.querySelector('.added-list'),
                                            lastItem;

                                        if (list.children.length <= max) {
                                            list.appendChild(temp);
                                            lastItem = list.children[list.children.length - 1];

                                            initDatepicker(lastItem.querySelector('.js-datepicker'));
                                            //lastItem.querySelector('.js-del-item').addEventListener('click', function () {
                                            //    if(list.children.length <= max) {
                                            //        btn.style.display = '';
                                            //    }
                                            //});
                                        }

                                        if (list.children.length == max) {
                                            btn.style.display = 'none';
                                        }
                                    });

                                    //경력 삭제 수정
                                   $(document).on("click",".js-del-item",function(){
                                   		$('.js-add-career').show();
                                   });

                                   for(var icnt=0;icnt < document.querySelector('.added-list').children.length;icnt++){
                                   		initDatepicker(document.querySelector('.added-list').children[icnt].querySelector('.js-datepicker'));
                                   }
                                });
                            }());
                        </script>
                    </div>
                    <div class="form-row">
                        <p class="form-label">첨부사진</p>
                        <div class="form-pic-group">
                            <label class="form-pic first">
                                <input type="file" name="ru_img1" accept="image/*" class="js-file-preview">
                                <span class="label">프로필 대표사진을<br> 첨부해주세요</span>
                                <span class="preview">
	                            	<?php if(getData($info,"ru_img1")!="") : ?>
	                            		<img src="<?=getData($info,"ru_img1")?>" />
	                            		<input type='hidden' name='ru_img1_ori' value='<?=getData($info,"ru_img1")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="ru_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"ru_img2")!="") : ?>
	                            		<img src="<?=getData($info,"ru_img2")?>" />
	                            		<input type='hidden' name='ru_img2_ori' value='<?=getData($info,"ru_img2")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="ru_img3" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"ru_img3")!="") : ?>
	                            		<img src="<?=getData($info,"ru_img3")?>" />
	                            		<input type='hidden' name='ru_img3_ori' value='<?=getData($info,"ru_img3")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="ru_img4" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"ru_img4")!="") : ?>
	                            		<img src="<?=getData($info,"ru_img4")?>" />
	                            		<input type='hidden' name='ru_img4_ori' value='<?=getData($info,"ru_img4")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="ru_img5" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"ru_img5")!="") : ?>
	                            		<img src="<?=getData($info,"ru_img5")?>" />
	                            		<input type='hidden' name='ru_img5_ori' value='<?=getData($info,"ru_img5")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                        </div>
                        <p class="help-msg is-fail">사진을 추가하시면 취업활동에 더 좋습니다.<br> 기본프로필을 사용시 면접에 영향이 갈수도 있습니다.</p>
                    </div>
                    <div class="sticky-bottom">
                        <button type="button" class="btn btn-secondary" id="btnPrev">이전</button>
                        <button type="button" class="btn btn-primary" id="btnStep"><?=$info["ru_idx"]!="0" ? "수정":"등록"?>완료</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?=$info["ru_idx"]?>" />
<script>
	$(document).ready(function(){
        $("#btnPrev").click(function(){
			history.go(-1);
		});

		$("#btnStep").click(function(){
            var arr_certi = "";
            for(var icnt=0;icnt < $(".added-list").find("li").length;icnt++){
                if($("[name='license[]']").eq(icnt).val()==""){
                    commonAlert("자격증명을 입력해주세요.",function(){
                        $("[name='license[]']").eq(icnt).focus();
                    });
                    return;
                }

                if($("[name='regdate[]']").eq(icnt).val()==""){
                    commonAlert("취득일자를 선택해주세요.",function(){
                        $("[name='regdate[]']").eq(icnt).focus();
                    });
                    return;
                }

                if(arr_certi==""){
                    arr_certi = $("[name='license[]']").eq(icnt).val() + "|||" + $("[name='regdate[]']").eq(icnt).val();
                }else{
                    arr_certi = arr_certi + "&&&" + $("[name='license[]']").eq(icnt).val() + "|||" + $("[name='regdate[]']").eq(icnt).val();
                }
            }
            $("[name=arr_certi]").val(arr_certi);

            if($("[name=rc_img1]").val()=="" && $("[name=rc_img1_ori]").val()==""){
            	commonAlert("첨부사진을 1나이상 선택해주세요.",function(){});
                return;
            }

            var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjaxLoading("/mypage_proc/resume_write4",params,function(ret){
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
    	var str_ru_work_img = "";
    	var str_ru_week = "";
    	var str_ru_career = "";
    	var str_ru_certi = "";
    	var str_ru_type = "";
    	var str_img = "";
    	var str_month_price = "";

    	if(info.ru_work_img){
    		for(var icnt=0;icnt < info.ru_work_img.length;icnt++){
    			str_ru_work_img += "<img src='"+info.ru_work_img[icnt]+"' alt='생산' class='category-label'>";
    		}
    	}

    	if(info.ru_week){
	    	for(var icnt=0;icnt < info.ru_week.length;icnt++){
	    		str_ru_week += "<li aria-hidden='false'>"+info.ru_week[icnt]+"</li>";
	    	}
    	}

    	if(info.arr_career){
    		for(var icnt=0;icnt < info.arr_career.split("&&&").length;icnt++){
    			var temp = info.arr_career.split("&&&")[icnt].split("|||");
    			if(temp.length  > 2){
    				str_ru_career += "<li><span>"+temp[0]+"</span><span>"+temp[1]+"</span><em>"+temp[2]+"(1년 2개월)</em></li>";
    			}
	    	}
    	}

    	if(info.arr_certi){
    		for(var icnt=0;icnt < info.arr_certi.split("&&&").length;icnt++){
    			var temp = info.arr_certi.split("&&&")[icnt].split("|||");
    			if(temp.length  > 1){
    				str_ru_certi += "<li><span>자격증</span><span>"+temp[0]+"</span><em>"+temp[1]+"</em></li>";
    			}
	    	}
    	}

    	if(info.ru_my_type){
    		for(var icnt=0;icnt < info.ru_my_type.length;icnt++){
    			str_ru_type +="<p class='tag'>"+info.ru_my_type[icnt]+"</p>";
	    	}
    	}

    	if(info.ru_img1){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img1+"' alt=''></div>";
    	}

    	if(info.ru_img2){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img2+"' alt=''></div>";
    	}

    	if(info.ru_img3){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img3+"' alt=''></div>";
    	}

    	if(info.ru_img4){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img4+"' alt=''></div>";
    	}

    	if(info.ru_img5){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img5+"' alt=''></div>";
    	}

    	if(info.ru_price_type.indexOf("월급") > -1 && info.ru_month_price !="0"){
    		 str_month_price = '<tr><td class="label">희망급여</td><td class="value">'+info.ru_month_price.format()+'원</td></tr>';
    	}

        openPopup('modal', {
            content: '<div class="regist-info">'+
                    '<h5 class="name">'+info.mm_name+'</h5>'+
                    '<table>'+
                        '<caption><span class="sr-text">회원가입정보</span></caption>'+
                        '<colgroup><col><col></colgroup>'+
                        '<tbody>'+
                            '<tr>'+
                                '<td class="label">업무</td>'+
                                '<td class="value">'+
                                    str_ru_work_img +
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                '<td class="label">지역</td>'+
                                '<td class="value">'+info.ru_addr+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">급여형태</td>'+
                                '<td class="value">'+info.ru_price_type+'</td>'+
                            '</tr>'+ str_month_price +
                            '<tr>'+
                                '<td class="label">근무형태</td>'+
                                '<td class="value">'+info.ru_work_type+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무요일</td>'+
                                '<td class="value">'+
                                    '<ul class="working-day">'+
                                        str_ru_week +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시작일</td>'+
                                '<td class="value">'+info.ru_work_dt + '('+info.ru_work_dt_yoil+')' + '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">최종학력</td>'+
                                '<td class="value">'+info.ru_edu+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">경력사항</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                       str_ru_career +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">자격증</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                        str_ru_certi +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">자기소개</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value introduce">'+info.ru_desc+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    '<div class="hashtag">'+
                                        str_ru_type +
                                    '</div>'+
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
            secondButton: ($("[name=ru_idx]").val()!="0" ? '수정완료' : '등록완료'),
            firstCallback: function () {

            },
            secondCallback: function () {
				var params = {};
				params["ru_idx"] = $("[name=ru_idx]").val();
	            comAjax("/mypage_proc/resume_complete",params,function(ret){
	                if(ret.code=="00"){
	                    //_replace("/mypage/resume_list");
                      location.href="/mypage/resume_list";
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
			_replace("/mypage/index");
		} else {

		}
	}, false);
</script>
