<div class="page page-join member-type1" style="display:none;">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">개인회원 회원가입</h1>
        <a href="" aria-describedby="skipTooltip" class="action js-toggle-tooltip">건너뛰기</a>
        <!-- 툴팁내용 -->
        <div role="tooltip" id="skipTooltip" class="tooltip center">
            <strong>건너뛰기를 하셨네요?</strong><br>
            정보를 입력하시면 나에게 맞는 채용을<br> 더 쉽게 찾으실 수 있습니다.
        </div>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="container">

            <ol class="progress-steps">
                <li title="헌재위치" class="active">1</li>
                <li>2</li>
                <li>3</li>
                <li>4</li>
            </ol>

            <div class="addition-info-form">
                <form id="frm" onsubmit="return false">
                    <input type="hidden" name="proc_type" value="join_mem_step4_1" />
                    <input type="hidden" name="ma_work_img" value="" />
                    <div class="form-row">
                        <p class="form-label">희망업무 <em>희망업무는 2개까지 선택 가능합니다</em></p>
                        <div class="form-toggle-group col-4">
                        	<?php foreach($ma_wrok_list as $row) : ?>
                            <label class="form-toggle-btn2 sm">
                                <input type="checkbox" name="ma_work[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],$info["ma_work"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="form-row">
                        <p class="form-label">희망급여 <em>다중선택가능</em><span class="add_txt" style="margin-left:0px;color:#1B9D7D;font-weight:normal;font-size:12px;margin:-1px 0 0 2px;display:none;">(협의가능)</span></p>
                        <div class="form-toggle-group col-3">
                        	<?php foreach($ma_price_type_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_price_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_price_type"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">근무형태 <em>다중선택가능</em><span class="add_txt" style="margin-left:0px;color:#1B9D7D;font-weight:normal;font-size:12px;margin:-1px 0 0 2px;display:none;">(협의가능)</span></p>
                        <div class="form-toggle-group col-3">
                        	<?php foreach($ma_work_type_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_work_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_work_type"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="form-row">
                        <p class="form-label">
                    		근무요일 <em>다중선택가능</em>
                            <span class="option">
                                <label class="form-checkbox">
                                    <input type="checkbox" name="ma_week_yn" value="Y" class="sr-text" <?=$info["ma_week_yn"]=="Y" ? "checked":""?>>
                                    <span class="label">요일무관</span>
                                </label>
                            </span>
                        </p>
                        <div class="form-toggle-group align-justify">
                        	<?php foreach($ma_week_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_week"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <script>
                        //선택목록 제한
                        (function () {
                            document.querySelectorAll('input[data-select-limit]').forEach(function (item) {
                                item.addEventListener('change', function () {
                                    var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                    if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                        this.checked = false;
                                    }
                                });
                            });
                        }());
                    </script>

                    <div class="sticky-bottom">
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>

                </form>
            </div>

        </div>
    </div><!-- //page-content -->
</div>

 <script>
    /* 건너뛰기 이동 전 툴팁표시 */
    document.querySelector('.js-toggle-tooltip').addEventListener('click', function (e) {

    });
</script>
<script>
function fPassClick(){
  setTimeout(function(){
    var params = {};
    params["ma_type"] = "MEM";
    params["proc_type"] = "PART";
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
        },10); //5000

}

	//$(document).ready(function(){
  window.addEventListener('load', function(){
    fPassClick();
		//건너띄기
		$(".js-toggle-tooltip").click(function(){
      fPassClick();
		});

        $("[name='ma_price_type[]']").click(function(){
            if($("[name='ma_price_type[]']:checked").length==3){
                $(this).closest(".form-row").find(".add_txt").show();
            }else{
                $(this).closest(".form-row").find(".add_txt").hide();
            }
        });

        $("[name='ma_work_type[]']").click(function(){
            if($("[name='ma_work_type[]']:checked").length==3){
                $(this).closest(".form-row").find(".add_txt").show();
            }else{
                $(this).closest(".form-row").find(".add_txt").hide();
            }
        });

		//로그인
		$("#btnStep").click(function(){
            if($("[name='ma_work[]']").is(":checked")==false){
                commonAlert("희망업무는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_price_type[]']").is(":checked")==false){
                commonAlert("희망급여는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_work_type[]']").is(":checked")==false){
                commonAlert("근무형태는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_week_yn']").is(":checked")==false && $("[name='ma_week[]']").is(":checked")==false){
                commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
                return;
            }

            var ma_work_img = "";
            for(var icnt=0;icnt<$("[name='ma_work[]']:checked").length;icnt++){
            	if(ma_work_img==""){
            		ma_work_img = $("[name='ma_work[]']:checked").eq(icnt).data("img");
            	}else{
            		ma_work_img += "," + $("[name='ma_work[]']:checked").eq(icnt).data("img");
            	}
            }
			$("[name=ma_work_img]").val(ma_work_img);

            var params = $("#frm").serialize();
            comAjax("/member_proc/join_mem_step4_1",params,function(ret){
                if(ret.code=="00"){
                    location.href="/member/join_mem_step4_2";
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});

		$("[name=ma_week_yn]").click(function(){
			if($(this).is(":checked")){
				$("[name='ma_week[]']").prop("checked",true);
			}else{
				$("[name='ma_week[]']").prop("checked",false);
			}
		});

		$("[name='ma_week[]']").click(function(){
			if($("[name='ma_week[]']:checked").length == 7){
				$("[name=ma_week_yn]").prop("checked",true);
			}else{
				$("[name=ma_week_yn]").prop("checked",false);
			}
		});
	});
</script>
