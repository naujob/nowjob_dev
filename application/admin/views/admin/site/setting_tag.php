<form id="frm" class="controls" method="post">    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">검색 키워드를 입력해주세요.</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                 <tr>
                    <th scope="col">추천 검색어</th>
                    <td>
                        <?=inputText("search","search",getData($info,"search"),"","data-role='tagsinput' style='text-align:left;display:none;' data-addclass='big_size' ",20000)?>
                        <p class="exc1">인기 검색으에 리스팅 됩니다.</p>
                        <p class="exc1">#없이 입력해주세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">채용 추천 태그</th>
                    <td>
                        <?=inputText("comp","comp",getData($info,"comp"),"","data-role='tagsinput' style='text-align:left;display:none;' data-addclass='big_size' ",20000)?>
                        <p class="exc1">채용정보 상단 추천 키워드로 등록됩니다.</p>
                        <p class="exc1">검색 태그에 있는 키워드만 등록해주세요.</p>
                        <p class="exc1">#없이 입력해주세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">인재 추천 태그</th>
                    <td>
                        <?=inputText("mem","mem",getData($info,"mem"),"","data-role='tagsinput' style='text-align:left;display:none;' data-addclass='big_size' ",20000)?>
                        <p class="exc1">인재정보 상단 추천 키워드로 등록됩니다.</p>
                        <p class="exc1">검색 태그에 있는 키워드만 등록해주세요.</p>
                        <p class="exc1">#없이 입력해주세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">중고상품 추천 태그</th>
                    <td>
                        <?=inputText("trade","trade",getData($info,"trade"),"","data-role='tagsinput' style='text-align:left;display:none;' data-addclass='big_size' ",20000)?>
                        <p class="exc1">중고거래 상단 추천 키워드로 등록됩니다.</p>
                        <p class="exc1">검색 태그에 있는 키워드만 등록해주세요.</p>
                        <p class="exc1">#없이 입력해주세요.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <!--button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button-->
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10 btnSave">저장하기</button>
        </div>

    </div>
</form>

<script>
	$("#btnSave").click(function(){
		var params = {};
		params["mem"] = $("#mem").val();
		params["comp"] = $("#comp").val();
		params["trade"] = $("#trade").val();
		params["search"] = $("#search").val();
		comAjax("/admin/site/setting_tag_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
</script>