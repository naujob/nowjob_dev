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
    	<div class="btn-table inline-wrap">
    		<div class="left">
	    		<button type="button" class="btn-xs btn-type1 w100 btnSearch">전체선택</button>
	            <button type="button" class="btn-xs btn-type1 w100 btnSearch">선택해제</button>
	            <button type="button" class="btn-xs btn-type1 w100 btnSearch">선택삭제</button>
    		</div>
        </div>
        <table class="table-type2">
            <colgroup>
                <col width="50" />
                <col width="100" />
                <col width="100" />
                <col width="120" />
                <col width="120" />
                <col width="100" />
                <col width="*" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">업체명</th>
                <th scope="col">노출여부</th>
                <th scope="col">상품코드</th>
                <th scope="col">이미지</th>
                <th scope="col">브랜드</th>
                <th scope="col">상품명</th>
                <th scope="col">판매가</th>
                <th scope="col">재고수량</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td>141</td>
	                <td>
	                	<div class="input-text">
                        		입점몰1
                        </div>
	                </td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td>GD00001010</td>
	                <td>
	                	<img style="width:100px;" src="/resource/admin/img/sample/sample.jpg" />
	                </td>
	                 <td>
	                	<span class="status-primary">시스넷</span>
	                </td>
	                <td class="tl">
	                	<div style="color:#888;">
	                		카테고리 : 신발 > 안전화 > 밀레
	                	</div>
	                	<div class="fs14 bold">
	                		밀래 안전화
	                	</div>
	                </td>
	                <td>12,000원</td>
	                <td>999개</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnGoLogin"  data-mem_id="" style="width:auto;">삭제</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type1 btnGoLogin"  data-mem_id="" style="width:auto;">미리보기</a>
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
		location.href="/admin/vendor/vendor_goods_edit";
	});
</script>