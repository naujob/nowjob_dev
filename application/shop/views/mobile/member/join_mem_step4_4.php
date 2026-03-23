<div class="page page-join member-type1" style="display:none;">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">개인회원 회원가입</h1>
        <a href="#" aria-describedby="skipTooltip" class="action js-toggle-tooltip">건너뛰기</a>
        <!-- 툴팁내용 -->
        <div role="tooltip" id="skipTooltip" class="tooltip center">
            <strong>건너뛰기를 하셨네요?</strong><br>
            정보를 입력하시면 나에게 맞는 채용을<br> 더 쉽게 찾으실 수 있습니다
        </div>
    </div><!-- //top-bar -->

    <script>
        /* 건너뛰기 이동 전 툴팁표시 */
        document.querySelector('.js-toggle-tooltip').addEventListener('click', function (e) {
            //if (document.querySelector('#skipTooltip').classList.contains('on')) {
            //    location.href = this.getAttribute('href');
            //}
        });
    </script>

    <div class="page-content">
        <div class="container">

            <ol class="progress-steps">
                <li>1</li>
                <li>2</li>
                <li>3</li>
                <li title="헌재위치" class="active">4</li>
            </ol>

            <div class="addition-info-form">
                <form id="frm" onsubmit="return false">
                   <input type="hidden" name="proc_type" value="join_mem_step4_4" />
                   <input type="hidden" name="arr_certi" value="" />

                    <div class="form-row">
                        <p class="form-label">자기소개 <em>최대500자</em></p>
                        <label>
                            <span class="sr-text">자기소개 입력</span>
                            <textarea name="ma_desc" maxlength="500" class="form-textarea js-autoheight"><?=$info["ma_desc"]?></textarea>
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
                                            max = 10,
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
                                <input type="file" name="mm_img1" accept="image/*" class="js-file-preview">
                                <span class="label">프로필 대표사진을<br> 첨부해주세요</span>
                                <span class="preview">
	                            	<?php if(getData($info,"mm_img1")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img1")?>" />
	                            		<input type='hidden' name='mm_img1_ori' value='<?=getData($info,"mm_img1")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="mm_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"mm_img2")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img2")?>" />
	                            		<input type='hidden' name='mm_img2_ori' value='<?=getData($info,"mm_img2")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="mm_img3" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"mm_img3")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img3")?>" />
	                            		<input type='hidden' name='mm_img3_ori' value='<?=getData($info,"mm_img3")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="mm_img4" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"mm_img4")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img4")?>" />
	                            		<input type='hidden' name='mm_img4_ori' value='<?=getData($info,"mm_img4")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                            <label class="form-pic">
                                <input type="file" name="mm_img5" accept="image/*" class="js-file-preview">
                                <span class="preview">
	                            	<?php if(getData($info,"mm_img5")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img5")?>" />
	                            		<input type='hidden' name='mm_img5_ori' value='<?=getData($info,"mm_img5")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                        </div>
                        <p class="help-msg is-fail">사진을 추가하시면 취업활동에 더 좋습니다.<br> 기본프로필을 사용시 면접에 영향이 갈수도 있습니다.</p>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button type="button" class="btn btn-secondary" id="btnPrev">이전</button>
                        <button type="button" class="btn btn-primary" id="btnStep">등록완료</button>
                    </div>

                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<script>

function fPassClick(){
  setTimeout(function(){
    var params = {};
    params["ma_type"] = "MEM";
    params["proc_type"] = "PART";
    params["uuid"] = "<?=$uuid?>";
    try {
      var joinType = localStorage.getItem("join_sns_type") || "";
      var joinKey = localStorage.getItem("join_sns_key") || "";
      if (joinType) params["join_type"] = joinType;
      if (joinKey) params["join_key"] = joinKey;
    } catch (e) {}
          comAjax("/member_proc/join_complete_proc",params,function(ret){
              if(ret.code=="00"){
                  location.href="/member/join_mem_finish";
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
        },5000);

}

	$(document).ready(function(){
    fPassClick();
		//건너띄기
		$(".js-toggle-tooltip").click(function(){
      fPassClick();
		});

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

            var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjaxLoading("/member_proc/join_mem_step4_4",params,function(ret){
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

    	var str_ma_week = "";
    	var str_ma_career = "";
    	var str_ma_certi = "";
    	var str_ma_type = "";
    	var str_img = "";

    	if(info.ma_week){
	    	for(var icnt=0;icnt < info.ma_week.length;icnt++){
	    		str_ma_week += "<li aria-hidden='false'>"+info.ma_week[icnt]+"</li>";
	    	}
    	}

    	if(info.arr_career){
    		for(var icnt=0;icnt < info.arr_career.split("&&&").length;icnt++){
    			var temp = info.arr_career.split("&&&")[icnt].split("|||");
    			if(temp.length  > 2){
    				str_ma_career += "<li><span>"+temp[0]+"</span><span>"+temp[1]+"</span><em>"+temp[2]+"(1년 2개월)</em></li>";
    			}
	    	}
    	}

    	if(info.arr_certi){
    		for(var icnt=0;icnt < info.arr_certi.split("&&&").length;icnt++){
    			var temp = info.arr_certi.split("&&&")[icnt].split("|||");
    			if(temp.length  > 1){
    				str_ma_certi += "<li><span>자격증</span><span>"+temp[0]+"</span><em>"+temp[1]+"</em></li>";
    			}
	    	}
    	}

    	if(info.ma_my_type){
    		for(var icnt=0;icnt < info.ma_my_type.length;icnt++){
    			str_ma_type +="<p class='tag'>"+info.ma_my_type[icnt]+"</p>";
	    	}
    	}

    	if(info.mm_img1){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img1+"' alt=''></div>";
    	}

    	if(info.mm_img2){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img2+"' alt=''></div>";
    	}

    	if(info.mm_img3){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img3+"' alt=''></div>";
    	}

    	if(info.mm_img4){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img4+"' alt=''></div>";
    	}

    	if(info.mm_img5){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img5+"' alt=''></div>";
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
                                    info.ma_work_img +
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                '<td class="label">지역</td>'+
                                '<td class="value">'+info.ad_addr+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">급여형태</td>'+
                                '<td class="value">'+(info.ma_price_type.length > 2 ? "협의":info.ma_price_type)+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무형태</td>'+
                                '<td class="value">'+(info.ma_work_type.length > 2 ? "협의":info.ma_work_type)+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무요일</td>'+
                                '<td class="value">'+
                                    '<ul class="working-day">'+
                                        str_ma_week +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시작일</td>'+
                                '<td class="value">'+info.ma_work_dt + '('+info.ma_work_dt_yoil+')' + '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">최종학력</td>'+
                                '<td class="value">'+info.ma_edu+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">경력사항</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                       str_ma_career +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">자격증</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                        str_ma_certi +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">자기소개</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value introduce">'+info.ma_desc+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    '<div class="hashtag">'+
                                        str_ma_type +
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
            secondButton: '가입완료',
            firstCallback: function () {

            },
            secondCallback: function () {
				var params = {};
				params["ma_type"] = "MEM";
				params["proc_type"] = "ALL";
				params["uuid"] = "<?=$uuid?>";
				try {
				  var joinType = localStorage.getItem("join_sns_type") || sessionStorage.getItem("join_sns_type") || "";
				  var joinKey = localStorage.getItem("join_sns_key") || sessionStorage.getItem("join_sns_key") || "";
				  if (joinType) params["join_type"] = joinType;
				  if (joinKey) params["join_key"] = joinKey;
				} catch (e) {}
	            comAjax("/member_proc/join_complete_proc",params,function(ret){
	                if(ret.code=="00"){
                        try {
                            localStorage.removeItem("join_sns_type");
                            localStorage.removeItem("join_sns_key");
                            sessionStorage.removeItem("join_sns_type");
                            sessionStorage.removeItem("join_sns_key");
                            document.cookie = "join_sns_type=; path=/; max-age=0";
                            document.cookie = "join_sns_key=; path=/; max-age=0";
                        } catch (e) {}
	                    location.href="/member/join_mem_finish";
	                }else{
	                    commonAlert(ret.msg,function(){});
	                    return;
	                }
	            });
            }
        });
    }
</script>
