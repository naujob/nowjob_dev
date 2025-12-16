<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/goods/list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">메인상단 배너 관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">팝업 등록</button>
	        </div>
	        <table>
                <colgroup>
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
                   <th scope="row">노출여부</th>
                    <td class="inline-wrap">
                         <div class="inline-wrap">
                           <?=inputRadio("ad_id","ad_id",getOptionList("display_yn"),$search["use_yn"])?>
                        </div>
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
                <col width="120" />
                <col width="120" />
                <col width="200" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">노출 여부</th>
                <th scope="col">팝업명</th>
                <th scope="col">설정업체</th>
                <th scope="col">노출 상태</th>
                <th scope="col">기간</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<tr>
	                <td>141</td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td class="tl">메인 상단배너1</td>
	                <td>
	                	20개업체
					</td>
	                <td>
	                	<span class="status-able">진행중</span>
					</td>
					<td>2020-01-01 ~<br/>2020-01-01</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <tr>
	                <td>141</td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td class="tl">메인 상단배너1</td>
	                <td>
	                	20개업체
					</td>
	                <td>
	                	<span class="status-able">진행중</span>
					</td>
					<td>2020-01-01 ~<br/>2020-01-01</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <tr>
	                <td>141</td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td class="tl">메인 상단배너1</td>
	                <td>
	                	20개업체
					</td>
	                <td>
	                	<span class="status-able">진행중</span>
					</td>
					<td>2020-01-01 ~<br/>2020-01-01</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <tr>
	                <td>141</td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td class="tl">메인 상단배너1</td>
	                <td>
	                	20개업체
					</td>
	                <td>
	                	<span class="status-able">진행중</span>
					</td>
					<td>2020-01-01 ~<br/>2020-01-01</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <tr>
	                <td>141</td>
	                <td>
	                	<span class="status-able">노출</span>
	                </td>
	                <td class="tl">메인 상단배너1</td>
	                <td>
	                	20개업체
					</td>
	                <td>
	                	<span class="status-able">진행중</span>
					</td>
					<td>2020-01-01 ~<br/>2020-01-01</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="" style="width:auto;">삭제</a>
	                </td>
	            </tr>
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
		location.href="/admin/vendor/popup_edit";
	});
</script>