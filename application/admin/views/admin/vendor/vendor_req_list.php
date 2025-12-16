<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/goods/list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">상품 목록</h3>
        	
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
                            <?=inputSelect("search_type","search_type",getOptionList("search_member"),$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">상품 상태</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("ad_id","ad_id",getOptionList("display_yn"),$search["use_yn"])?>
					</td>
                </tr>
                 <tr>
                     <th scope="row">판매자</th>
                    <td class="inline-wrap">
                       	<div>
                            <?=inputSelect("search_type","search_type",getOptionList("search_member"),$search["search_type"])?>
                        </div>
					</td>
                    <th scope="row">카테고리</th>
                    <td class="inline-wrap">
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
					</td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>
    </form>
    <div class="search-result">
    	
        <table class="table-type2">
            <colgroup>
                <col width="50" />
                <col width="100" />
                <col width="*" />
                <col width="100" />
                <col width="120" />
                <col width="120" />
                <col width="100" />
                <col width="100" />
           		<col width="100" />
                <col width="150" />
                <col width="150" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">아이디</th>
                <th scope="col">상호명</th>
                <th scope="col">담당자명</th>
                <th scope="col">연락처</th>
                <th scope="col">업태</th>
                <th scope="col">사업자명</th>
                <th scope="col">사업자번호</th>
                <th scope="col">등록상품수</th>
                <th scope="col">요청일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td>141</td>
	                <td>
	                	testmoall01
	                </td>
	                <td class="tl">
	                	테스트몰01
	                </td>
	                <td>
	                	박현종
	                </td>
	                <td>
	                	010-0000-0000
	                </td>
	                <td>쇼핑몰</td>
	                <td>테스트몰1</td>
	                <td>137-000-0000</td>
	                <td>20개</td>
	                <td>2021-01-26 12:01:00</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type1 btnEdit" data-idx="" style="width:auto;">승인</a>
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
<script>
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/vendor/vendor_req_edit";
	});
</script>