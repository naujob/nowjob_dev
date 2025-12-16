<form id="frm" class="controls" method="post">
    <input type="hidden" name="pu_idx" value="<?=getData($info,"pu_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">푸시 발송</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>

                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">푸시 관리명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w300">
                            <?=inputText("pu_adm_title","pu_adm_title",getData($info,"pu_adm_title"),"","",200)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">제목<em class="ess"></em></th>
                    <td>
					    <div class="input-text w300">
                            <?=inputText("pu_title","pu_title",getData($info,"pu_title"),"","",200)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">내용<em class="ess"></em></th>
                    <td>
					    <div class="input-text w600">
                            <?=inputTextArea("pu_desc","pu_desc",getData($info,"pu_desc"),"","",200)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">이미지(선택)</th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
						    <?=inputImage("pu_img","pu_img",getData($info,"pu_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">발송상태</th>
                    <td>
                        <?php if(getData($info,"pu_status")=="Y") : ?>
                    	        <span class="order-cancel">발생완료</span>
                        <?php else : ?>
                                <span class="order-payed">발생대기</span>
                        <?php endif; ?>
					    <p class="exc1">내용 저장후 푸시 발송이 가능합니다.(최종 데이터 확인후 발송)</p>
					</td>
          </tr>
                <!--
                <tr>
                    <th scope="col">테스트토큰<em class="ess"></em></th>
                    <td>
					              <div class="input-text w300">
                            <?=inputText("pu_token","pu_token",getData($info,"pu_token"),"","",200)?>
                        </div>
					          </td>
                </tr>
                -->
                <?php if(getData($info,"pu_status")=="Y") : ?>
                <tr>
                    <th scope="col">발송 결과</th>
                    <td>
                        <span class="">발송 대상</span> <?=getData($info,"pu_success_cnt",0) + getData($info,"pu_fail_cnt",0)?>
                    	<span class="">발송 성공</span> <?=getData($info,"pu_success_cnt")?>
                        <span class="">발송 실패</span> <?=getData($info,"pu_fail_cnt")?>
					          </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
            <?php if(getData($info,"pu_status","")=="N") : ?>
            <button type="button" id="btnSend" class="btn-l btn-type11 w150 ml-10">발송하기</button>
            <?php endif; ?>
            <!-- <button type="button" id="btnSendTest" class="btn-l btn-type11 w200 ml-10">발송하기(테스트)</button> -->
        </div>
    </div>
</form>

<script>
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });

	    $("[name=bn_period_type]").change(function(){
			if($("[name=bn_period_type]:checked").val()=="D"){
				$(".cont_date").removeClass("hidden");
			}else{
				$(".cont_date").addClass("hidden");
			}
		});

	    $("#btnSave").click(function(){
	        if($("#pu_adm_title").val()==""){
	            COMMON.alert("푸시 관리명을 입력해주세요.",function(){$("#pu_adm_title").focus();});
	            return;
	        }

	        if($("#pu_title").val()==""){
	            COMMON.alert("제목을 입력해주세요.",function(){$("#pu_title").focus();});
	            return;
	        }

	        if($("#pu_desc").val()==""){
	            COMMON.alert("내용을 입력해주세요.",function(){$("#pu_desc").focus();});
	            return;
	        }

	        var form = $('#frm');
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/site/push_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href = "/admin/site/push_edit?idx=" + result.data.idx;
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });

        $("#btnSend").click(function(){
	        COMMON.confirm("푸시 발송 하시겠습니까?",function(){
    			var params = {};
                params["idx"] = $("[name=pu_idx]").val();
    			comAjax("/admin/site/send_fcm",params,function(result){
    				if(result.code == "00"){
    					location.reload();
    				}else{
    					COMMON.alert(result.msg);
    				}
    			});
            })
	    });

      $("#btnSendTest").click(function(){
        COMMON.confirm("테스트 푸시 발송 하시겠습니까?",function(){
        var params = {};
        params["idx"] = $("[name=pu_idx]").val();
        params["pu_title"] = $("[name=pu_title]").val();
        params["pu_desc"] = $("[name=pu_desc]").val();
        params["pu_token"] = $("[name=pu_token]").val();
        console.log(params);
        comAjax("/admin/site/send_fcm_test",params,function(result){
          console.log(result);
          if(result.code == "00"){
            COMMON.alert('성공');
          }else{
            COMMON.alert(result.msg);
          }
        });
          })
    });

	});
</script>
