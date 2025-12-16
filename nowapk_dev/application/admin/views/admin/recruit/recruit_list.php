
<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/goods/goods_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">상품 목록</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w100 btnNew">상품 등록</button>
	        </div>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="*"/>
                    <col width="150px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",([""=>"-- 전체검색 --","goods_code"=>"상품코드","goods_name"=>"상품명","goods_sub_name"=>"부가설명"]),$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">상품 상태</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("gd_status","gd_status",["" => "전체", "Y"=>"노출","S"=>"품절","N"=>"비노출"],$search["gd_status"])?>
					</td>
                </tr>
                 <tr>
                    <th scope="row">브랜드</th>
                    <td class="inline-wrap">
                       	<?=inputSelect("bd_idx","bd_idx",$select_brand,$search["bd_idx"])?>
					</td>
                    <th scope="row">카테고리</th>
                    <td class="inline-wrap">
                       	<?=inputSelect("gd_cate1","gd_cate1",[],"","style='width:150px;'")?>
                       	<?=inputSelect("gd_cate2","gd_cate2",[""=>"-- 2차 카테고리선택 --"],"","style='width:150px;'")?>
                       	<?=inputSelect("gd_cate3","gd_cate3",[""=>"-- 3차 카테고리선택 --"],"","style='width:150px;'")?>
					</td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>
	    <div class="search-result">
	    	<div class="btn-table inline-wrap">
	    		<div class="left">
		            <?=inputSelect("page_size","page_size",["20"=>"20","50"=>"50","100"=>"100","500"=>"500"],$page_size,"style='width:80px;min-width:0px;'")?>
		            <button type="button" style="display:none;" class="btn-xs btn-type1 w120 btnChkSave ml-5">체크상품 수정</button>
		            <button type="button" class="btn-xs btn-type1 btnTarget ml-10" style="width:auto;">상품 업로드 대상 지정</button>
	    		</div>
	    		<div class="right">
	    			
		            <button type="button" class="btn-xs btn-excel btnExcelDownload">
		            	<img src="/resource/admin/img/icons/icon8/icon-excel.png"  />
		            	<span>상품 일괄 다운로드</span>
		            </button>
		            <button type="button" class="btn-xs btn-excel-upload btnExcelRegist">
		            	<img src="/resource/admin/img/icons/icon8/icon-import.png" />
		            	<span>상품 일괄 등록</span>
		            </button>
		            <button type="button" class="btn-xs btn-excel-upload btnExcelUpload">
		            	<img src="/resource/admin/img/icons/icon8/icon-import.png" />
		            	<span>상품 일괄 수정</span>
		            </button>
	    		</div>
	        </div>
	    </div>
	    </form>
	    <div>
	        <table class="table-type2">
	            <colgroup>
	             	<col width="50" />
	                <col width="50" />
	                <col width="80" />
	                <col width="80" />
	                <col width="120" />
	                <col width="120" />
	                <col width="100" />
	                <col width="*" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="200" />
	            </colgroup>
	            <thead>
	            <tr>
	            	<th scope="col">
	            		<?=inputCheck("chk_all","chk_all",[""=>""],"")?>
	            	</th>
	                <th scope="col">번호</th>
	                <th scope="col">정렬순서</th>
	                <th scope="col">노출여부</th>
	                <th scope="col">상품코드 <br/>ERP코드</th>
	                <th scope="col">이미지</th>
	                <th scope="col">브랜드</th>
	                <th scope="col">상품명</th>
	                <th scope="col">입고가<br/>출고가</th>
	                <th scope="col">할인금액</th>
	                <th scope="col">재고수량</th>
	                <th scope="col">등록일시</th>
	                <th scope="col">관리</th>
				</tr>
	            </thead>
	            <tbody>
	            	<?php foreach($result_list as $key=> $row) : ?>
					<tr data-gd_idx="<?=$row["gd_idx"]?>">
						<td>
							<div class='input-checkbox'>
					            <label>
					                <input type='checkbox' name='chk[]' value='<?=$row["gd_idx"]?>'   />
					                <span style='padding-left:5px;'><strong></strong></span>
					            </label>
					        </div>
						</td>
		                <td><?=$start - $key?></td>
		                <td>
		                	<div class="input-text">
	                            <label>
	                                 <?=inputNumber("" ,"ls_gd_sort[]",$row["gd_sort"],"","style='width:52px;text-align:right;background:#f9f9f9;'",10)?>
	                            </label>
	                        </div>
		                </td>
		                <td>
		                	<div>
	                   			<?=$row["gd_status_nm"]?>
	                   			<?=$row["show_yn"]=="N" ? "<br/><span style='color:#f33;'>비노출</span>":""?>
	                   		</div>
		                </td>
		                <td>
		                	<?=$row["gd_code"]?><br/>
		                	<?=$row["gd_erp_code"]=="" ? "<span style='color:#ccc;'>코드사용안함</span>":$row["gd_erp_code"]?>
		                </td>
		                <td>
		                	<img style="width:80px;" src="<?=getGoodsListImg($row)?>" />
		                </td>
		                 <td>
		                	<?=$row["bd_name"]?>
		                </td>
		                <td class="tl">
		                	<div class="input-text">
	                           <?=inputText("" ,"ls_gd_name[]",$row["gd_name"],"","style='width:100%;background:#f9f9f9;'")?>
	                        </div>
		                	<div class="fs12 mt-4">
		                		 부가설명 : <?=$row["gd_sub_name"]=="" ? "<span style='color:#ccc;'>없음</span>": "<span style='color:#aaa;'>" . $row["gd_sub_name"] . "</span>"?>
		                	</div>
		                </td>
		                <td>
		                	<div class="input-text">
	                            <label>
	                                 <?=inputNumber("" ,"ls_gd_p_price[]",$row["gd_p_price"],"","style='width:80px;text-align:right;background:#f9f9f9;'",10)?>
	                            </label>
	                   		</div><br/>
	                        <div class="input-text">
	                            <label>
	                                 <?=inputNumber("" ,"ls_gd_s_price[]",$row["gd_s_price"],"","style='width:80px;text-align:right;background:#f9f9f9;'",10)?>
	                            </label>
	                        </div>
		                </td>
		                <td>
		                	<?php if($row["gc_name"]!="") : ?>
		                	<?=($row["gc_name"])?><br/>
		                	<?=number_format($row["gc_won"])?><?=$row["gc_won_type_nm"]?>
		                	<?php endif;?>
		                </td>
		                <td>
		                	<div class="input-text">
	                            <label>
	                                 <?=inputNumber("" ,"ls_gd_stock[]",$row["gd_stock"],"","style='width:60px;text-align:right;background:#f9f9f9;'",10)?>
	                            </label>
	                   		</div>
	                   	</td>
		                <td><?=$row["reg_dt"]?></td>
		                <td>
		                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["gd_idx"]?>" style="width:auto;">수정</a>
		                	<?php if($g_setting["st_admin_type"]=="admin" || $row["st_admin_type"]=="store") : ?>
		                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete"  data-idx="<?=$row["gd_idx"]?>" style="width:auto;">삭제</a>
		                	<?php endif; ?>
		                	<a href="https://<?=$g_setting["st_domain"]?>/goods/detail/<?=$row["gd_idx"]?>" class="btn-xs btn-type1 btnPreview"  target="_blank"  style="width:auto;">미리보기</a>
		                </td>
		            </tr>
		            <?php endforeach;?>
				</tbody>
	        </table>
	    </div>
	    <div>
		    <?=$pagenation?>
		</div>
	</div>
</form>

<!-- 팝업 - 상품 대량 등록 -->
<form id="frmExcel" name="frmExcel" method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="proc_type" value="">
    <input type="hidden" name="upload_file_name" value="">
    <div class="popup popup-excell-register popRegistGoods"><!-- popup4 클래스는 팝업을 열기 위한 팝업명 -->
        <div class="ds-table">
            <div class="ds-table-cell">
                <div class="container w700">
                    <div class="header">
                        <h2>상품 대량 등록</h2>
                        <button type="button" class="btn-close" onclick="popClose('.popup-excell-register');">닫기</button>
                    </div>
                    <div class="contents">
                        <div class="info mb-20">
                            <h3 class="bold mb-10">새로운 상품을 추가 등록합니다.</h3>
                            <p class="mb-10">1. 양식 다운로드 &gt; 2. 엑셀 파일 작성 &gt; 3. 파일 업로드 및 검사 실행 &gt; 4. 오류가 없으면 등록 버튼을 클릭하여 완료</p>
                        </div>
                        <table class="table-form">
                            <colgroup>
                                <col width="150px"/>
                                <col width="*"/>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th scope="row" class="tl">양식 다운로드</th>
                                <td class="inline-wrap tl">
                                    <a href="/outer/download.php?name=goods_regist_sample.xlsx&url=<?=getcwd()?>/resource/admin/file/goods_regist_sample.xlsx" class="btn-s btn-type6 w140 btnRegistSample pt-5" >샘플 다운로드</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="tl">파일 업로드</th>
                                <td class="inline-wrap tl">
                                    <div class="input-file w300 js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                                        <label class="w300">
                                            <input type="file" name="chk_file_goods_regist" accept=".xls,.xlsx" style="width:200px;">
                                            <span></span>
                                            <button type="button" class="btn-s btn-type2 w100">파일 찾기</button>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-s btn-type2 w100 ml-20 btnRegistChk pt-4">파일 검사</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="pop_regist_bottom result mt-20 hidden">
                            <div class="gray-box tc">
                                <div class="inline-wrap mb-10">
                                    <p class="fs14 mr-10">총 건수 : <span class="txt_total">1</span>건</p>
                                    <span class="fs14">( 총 오류 건수 : <em class="fc1 bold txt_error">2건</em>)</span>
                                </div>
                                <p class="d-inline exc1 fc1 txt_error_msg">잘못된 정보가 입력 되었습니다. 파일 수정 후 재등록이 필요합니다.</p>
                            </div>

                            <div class="error-list mt-20 error_cont">
                                <strong class="d-block fs14 bold mb-10">오류 목록 (전체 <span class="txt_error"></span>건)</strong>
                                <table class="table-type1">
                                    <thead>
                                    <tr>
                                        <th scope="col">구분</th>
                                        <th scope="col">오류 내용</th>
                                    </tr>
                                    </thead>
                                    <tbody class="error_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="btn-wrap inline-wrap tc">
                        <button type="button" class="btn-m btn-type1" onclick="popClose('.popup-excell-register');">닫기</button>
                        <button type="button" class="btn-m btn-type6 hidden btnPopRegistUpload">등록</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 팝업 - 상품 일괄 수정 -->
    <div class="popup popup-excell-register popEditGoods"><!-- popup5 클래스는 팝업을 열기 위한 팝업명 -->
        <div class="ds-table">
            <div class="ds-table-cell">
                <div class="container w700">
                    <div class="header">
                        <h2>상품 일괄 수정</h2>
                        <button type="button" class="btn-close" onclick="popClose('.popup-excell-register');">닫기</button>
                    </div>
                    <div class="contents">
                        <div class="info mb-20">
                            <h3 class="bold mb-10">등록된 상품의 정보를 수정합니다.</h3>
                            <p class="mb-10">1. 양식 다운로드 &gt; 2. 엑셀 파일 작성 &gt; 3. 파일 업로드 및 검사 실행 &gt; 4. 오류가 없으면 수정 버튼을 클릭하여 완료</p>
                        </div>
                        <table class="table-type3">
                            <colgroup>
                                <col width="150px"/>
                                <col width="*"/>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th scope="row" class="tl">양식 다운로드</th>
                                <td class="tl">
                                    <a href="javascript:void(0);" class="btn-s btn-type6 w140 btnModifySample pt-5 " >상품 다운로드</a>
                                    <p class="fs12 fc3 mt-5">※상품 수에 따라 다운로드 시간이 몇 분 정도 걸릴 수 있습니다</p>
                                </td>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="tl">파일 업로드</th>
                                <td class="inline-wrap tl ">
                                    <div class="input-file w300 js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                                        <label class="w300">
                                            <input type="file" name="chk_file_goods_modify"  accept=".xls,.xlsx">
                                            <span></span>
                                            <button type="button" class="btn-s btn-type2 w100">파일 찾기</button>
                                        </label>
                                    </div>
                                    <button type="button" class="btn-s btn-type2 w100 ml-20 btnModifyChk">파일 검사</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="pop_modify_bottom result mt-20 hidden">
                            <div class="gray-box tc">
                                <div class="inline-wrap mb-10">
                                    <p class="fs14 mr-10">총 건수 : <span class="txt_total">1</span>건</p>
                                    <span class="fs14">( 총 오류 건수 : <em class="fc1 bold txt_error">2건</em>)</span>
                                </div>
                                <p class="d-inline exc1 fc1 txt_error_msg">잘못된 정보가 입력 되었습니다. 파일 수정 후 재등록이 필요합니다.</p>
                            </div>

                            <div class="error-list mt-20 error_cont">
                                <strong class="d-block fs14 bold mb-10">오류 목록 (전체 <span class="txt_error"></span>건)</strong>
                                <table class="table-type1">
                                    <thead>
                                    <tr>
                                        <th scope="col">구분</th>
                                        <th scope="col">오류 내용</th>
                                    </tr>
                                    </thead>
                                    <tbody class="error_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="btn-wrap inline-wrap tc">
                        <button type="button" class="btn-m btn-type1" onclick="popClose('.popup-excell-register');">닫기</button>
                        <button type="button" class="btn-m btn-type6 hidden btnPopModifyUpload">수정</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
	var gd_cate1 = "<?=$search["gd_cate1"]?>";
	var gd_cate2 = "<?=$search["gd_cate2"]?>";
	var gd_cate3 = "<?=$search["gd_cate3"]?>";
	
	$(document).ready(function(){
		$("#page_size").change(function(){
			$("#frm-search").attr("action","/admin/goods/goods_list");
			$("#frm-search").submit();
		});
		
		//1차 카테고리 로드 
		getCategoryList(1,0,true);
		
		$("#search_value").keypress(function(e){
			if (e.keyCode === 13) { 
				e.preventDefault();
				$("#frm-search").attr("action","/admin/goods/goods_list");
				$("#frm-search").submit();
			}
		});
		
		//2차 카테고리 로드
		$("#gd_cate1").change(function(){
			var idx = $(this).val();
			getCategoryList(2,idx,false);
		});
		
		//3차 카테고리 로드
		$("#gd_cate2").change(function(){
			var idx = $(this).val();
			getCategoryList(3,idx,false);
		});
		
		//등록 이동
		$(".btnNew").click(function(){
			location.href="/admin/goods/goods_edit";
		});
		
		//수정 이동
		$(".btnEdit").click(function(){
			var idx = $(this).data("idx");
			location.href="/admin/goods/goods_edit?idx=" + idx;
		});
		
		$(".btnSearch").click(function(){
			$("#frm-search").attr("action","/admin/goods/goods_list");
			$("#frm-search").submit();
		});

		$(".btnDelete").click(function(){
			var idx = $(this).data("idx");
			COMMON.confirm("삭제 하시겠습니까?",function(){
				var params = {};
				params["idx"] = idx;
				params["proc_mode"] = "delete";
		        comAjax("/admin/goods/goods_edit_proc",params,function(result){
		            if(result.code == "00"){
		                COMMON.alert(result.msg,function(){
		                    location.reload();
		                });
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			},function(){
				
			});
		});

		//일괄처리 팝업
		$(".btnChkSave").click(function(){
			var chk_list = "";
			
			for(var icnt=0;icnt < $("[name='chk[]']:checked").length;icnt++){
				if(chk_list==""){
					chk_list = $("[name='chk[]']:checked").eq(icnt).val() + ":^:" + $("[name='ls_gd_sort[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_status[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_name[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_p_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_s_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_stock[]']").eq(icnt).val();
				}else{
					chk_list += ":,:" + $("[name='chk[]']:checked").eq(icnt).val() + ":^:" + $("[name='ls_gd_sort[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_status[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_name[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_p_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_s_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_stock[]']").eq(icnt).val();
				}
			}
			
			if(chk_list==""){
				COMMON.alert("처리하실 상품을 선택해 주세요.");
				return;
			}
			
			COMMON.confirm("선택하신 상품을 수정 하시겠습니까?",function(){
				var params = {};
				params["proc_mode"] = "chk_save";
				params["chk_list"] = chk_list;
		        comAjax("/admin/goods/goods_list_proc",params,function(result){
		            if(result.code == "00"){
		                location.reload();
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			});
		});

		//엑셀 다운로드
		$(".btnExcelDownload").click(function(){
			$("#frm-search").attr("action","/admin/excelproc/goods_list_excel");
			$("#frm-search").submit();
		});

		//엑셀 다운로드
		$(".btnExcelRegist").click(function(){
			$(".popRegistGoods").show();
		});

		//엑셀 다운로드
		$(".btnExcelUpload").click(function(){
			$(".popEditGoods").show();
		});
		
		//상품일괄등록 파일검사
	    $(".btnRegistChk").click(function(){
	        if($('[name=chk_file_goods_regist]').val()==""){
	            COMMON.alert("검사하실 파일을 선택해주세요.");
	            return;
	        }
	
	        var formData = new FormData();
	        var files = $('[name=chk_file_goods_regist]')[0].files[0];
	        formData.append('chk_file',files);
	        formData.append("proc_type","goods_regist_check");
	
	        $pop_bottom = $(".pop_regist_bottom");
	        $btn_upload = $(".btnPopRegistUpload");
	
	        $("[name=upload_file_name]").val("");
	        fileAjaxBigLoading('/admin/excelproc/goods_list_filechk',formData,function(result){
	            //초기화
	            $('[name=chk_file_goods_regist]').val("");
	            $('[name=chk_file_goods_regist]').closest(".js-input-file").find("span").text("");
	            $("[name=upload_file_name]").val(result.data.path);
				$(".error_list").html("");
				
	            if(result.code=="00"){
	                $pop_bottom.removeClass("hidden");
	                $pop_bottom.find(".inline-wrap").removeClass("hidden");
	                $pop_bottom.find(".error_cont").removeClass("hidden");
	
	                $pop_bottom.find(".txt_total").text(result.data.row_cnt);
	                $pop_bottom.find(".txt_error").text(result.data.error_cnt);
	
	                if(result.data.error_cnt > 0){
	                    var lis_html = result.data.error;
	                    var html = "";
	                    for(var icnt=0;icnt < lis_html.length;icnt++){
	                    	html += "<tr><td>오류</td><td>"+lis_html[icnt]+"</td></tr>";
	                    }
	                    
	                    $pop_bottom.find(".error_cont").removeClass("hidden");
	                    $pop_bottom.find(".error_list").html("");
	                    $pop_bottom.find(".error_list").html(html);
	                    $btn_upload.addClass("hidden");
	                    $pop_bottom.find(".txt_error_msg").text("잘못된 정보가 입력 되었습니다. 파일 수정 후 재등록이 필요합니다..");
	                }else{
	                    $pop_bottom.find(".error_cont").addClass("hidden");
	                    $pop_bottom.find(".txt_error_msg").text("검사가 정상적으로 완료되었습니다. 등록 버튼을 클릭하여 상품을 등록하세요.");
	                    $btn_upload.removeClass("hidden");
	                }
	            }else{
	                $pop_bottom.removeClass("hidden");
	                $btn_upload.addClass("hidden");
	                $pop_bottom.find(".inline-wrap").addClass("hidden");
	                $pop_bottom.find(".error_cont").addClass("hidden");
	               	$pop_bottom.find(".txt_error_msg").text("처리할 수 없는 엑셀 양식입니다. 업로드한 엑셀 양식을 다시 확인해 주세요.");
	                COMMON.alert("처리할 수 없는 엑셀 양식입니다. 업로드한 엑셀 양식을 다시 확인해 주세요.");
	            }
	        });
	    });
	
	    //상품일괄 등록 처리
	    $(".btnPopRegistUpload").click(function(){
	        if($('[name=upload_file_name]').val()==""){
	            COMMON.alert("등록하실 파일을 선택해주세요.");
	            return;
	        }
	
	        var formData = new FormData();
	        formData.append('upload_file_name',$('[name=upload_file_name]').val());
	        formData.append("proc_type","goods_regist_upload");
	
	        fileAjaxBigLoading('/admin/excelproc/goods_list_filechk',formData,function(result){
	            if(result.code=="00"){
	                COMMON.alert(result.msg,function(){
	                    location.reload();
	                });
	            }else{
	                COMMON.alert(result.data.msg);
	            }
	        });
	    });
	    
	    //상품수정 다운로드
	    $(".btnModifySample").click(function(){
			$("#frm-search").attr("action","/admin/excelproc/goods_list_upload_excel");
			$("#frm-search").submit();
		});
		
		//상품일괄수정 파일검사
	    $(".btnModifyChk").click(function(){
	        if($('[name=chk_file_goods_modify]').val()==""){
	            COMMON.alert("검사하실 파일을 선택해주세요.");
	            return;
	        }
	
	        var formData = new FormData();
	        var files = $('[name=chk_file_goods_modify]')[0].files[0];
	        formData.append('chk_file',files);
	        formData.append("proc_type","goods_update_check");
	
	        $pop_bottom = $(".pop_modify_bottom");
	        $btn_upload = $(".btnPopModifyUpload");
	
	        $("[name=upload_file_name]").val("");
	        fileAjaxBigLoading('/admin/excelproc/goods_update_filechk',formData,function(result){
	            //초기화
	            $('[name=chk_file_goods_modify]').val("");
	            $('[name=chk_file_goods_modify]').closest(".js-input-file").find("span").text("");
	            $("[name=upload_file_name]").val(result.data.path);
				$(".error_list").html("");
				
	            if(result.code=="00"){
	                $pop_bottom.removeClass("hidden");
	                $pop_bottom.find(".inline-wrap").removeClass("hidden");
	                $pop_bottom.find(".error_cont").removeClass("hidden");
	
	                $pop_bottom.find(".txt_total").text(result.data.row_cnt);
	                $pop_bottom.find(".txt_error").text(result.data.error_cnt);
	
	                if(result.data.error_cnt > 0){
	                    var lis_html = result.data.error;
	                    var html = "";
	                    for(var icnt=0;icnt < lis_html.length;icnt++){
	                    	html += "<tr><td>오류</td><td>"+lis_html[icnt]+"</td></tr>";
	                    }
	                    
	                    $pop_bottom.find(".error_cont").removeClass("hidden");
	                    $pop_bottom.find(".error_list").html("");
	                    $pop_bottom.find(".error_list").html(html);
	                    $btn_upload.addClass("hidden");
	                    $pop_bottom.find(".txt_error_msg").text("잘못된 정보가 입력 되었습니다. 파일 수정 후 재등록이 필요합니다..");
	                }else{
	                    $pop_bottom.find(".error_cont").addClass("hidden");
	                    $pop_bottom.find(".txt_error_msg").text("검사가 정상적으로 완료되었습니다. 등록 버튼을 클릭하여 상품을 등록하세요.");
	                    $btn_upload.removeClass("hidden");
	                }
	            }else{
	                $pop_bottom.removeClass("hidden");
	                $btn_upload.addClass("hidden");
	                $pop_bottom.find(".inline-wrap").addClass("hidden");
	                $pop_bottom.find(".error_cont").addClass("hidden");
	               	$pop_bottom.find(".txt_error_msg").text("처리할 수 없는 엑셀 양식입니다. 업로드한 엑셀 양식을 다시 확인해 주세요.");
	                COMMON.alert("처리할 수 없는 엑셀 양식입니다. 업로드한 엑셀 양식을 다시 확인해 주세요.");
	            }
	        });
	    });
	
	    //상품일괄 등록 처리
	    $(".btnPopModifyUpload").click(function(){
	        if($('[name=upload_file_name]').val()==""){
	            COMMON.alert("등록하실 파일을 선택해주세요.");
	            return;
	        }
	
	        var formData = new FormData();
	        formData.append('upload_file_name',$('[name=upload_file_name]').val());
	        formData.append("proc_type","goods_upload_upload");
	
	        fileAjaxBigLoading('/admin/excelproc/goods_update_filechk',formData,function(result){
	            if(result.code=="00"){
	                COMMON.alert(result.msg,function(){
	                    location.reload();
	                });
	            }else{
	                COMMON.alert(result.data.msg);
	            }
	        });
	    });
	    
	    
	    //업로드 대상 지정
		$(".btnTarget").click(function(){
			var chk_list = "";
			for(var icnt=0;icnt < $("[name='chk[]']:checked").length;icnt++){
				if(chk_list==""){
					chk_list = $("[name='chk[]']:checked").eq(icnt).val() + ":^:" + $("[name='ls_gd_sort[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_status[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_name[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_p_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_s_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_stock[]']").eq(icnt).val();
				}else{
					chk_list += ":,:" + $("[name='chk[]']:checked").eq(icnt).val() + ":^:" + $("[name='ls_gd_sort[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_status[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_name[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_p_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_s_price[]']").eq(icnt).val() + ":^:" + $("[name='ls_gd_stock[]']").eq(icnt).val();
				}
			}
			
			if(chk_list==""){
				COMMON.alert("처리하실 상품을 선택해 주세요.");
				return;
			}
			
			COMMON.confirm("선택하신 상품을 강제 업르도 대상으로<br/>지정하시겠습니까?<br/><span style='color:#f33;'>지정된 상품의 옵션,이미지,상품명은<br/>상점 각각 상품정보를 강제 수정 처리 합니다.</span>",function(){
				var params = {};
				params["proc_mode"] = "up_target";
				params["chk_list"] = chk_list;
		        comAjax("/admin/goods/goods_list_proc",params,function(result){
		            if(result.code == "00"){
		                location.reload();
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			});
		});
	});
	
	function getCategoryList(depth,parent_id,is_init){
		var params = {};
		params["depth"] = depth;
		params["parent_id"] = parent_id;
		
		comAjax("/admin/goods/goods_category",params,function(result){
			if(result.code == "00"){
				$('#gd_cate' + depth).find('option').remove().end().append(result.data);
				if(is_init==true){
					if(depth==1){
						if(gd_cate1!=""){
							$('#gd_cate1').val(gd_cate1);
							getCategoryList(2,gd_cate1,true);
						}
					}else if(depth==2){
						if(gd_cate2!=""){
							$('#gd_cate2').val(gd_cate2);
							getCategoryList(3,gd_cate2,true);
						}
					}else if(depth==3){
						if(gd_cate3!=""){
							$('#gd_cate3').val(gd_cate3);
						}
					}
				}
			}else{
				COMMON.alert(result.msg);
			}
		});
	}
</script>