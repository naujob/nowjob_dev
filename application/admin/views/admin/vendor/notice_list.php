<!--iframe src="https://docs.google.com/gview?url=http://sysnet99.cafe24.com/resource/admin/file/excel.xlsx&embedded=true" frameborder="0">
</iframe-->
<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/goods/list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">공지사항 리스트</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnSearch">공지사항 등록</button>
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
				<col width="100" />
                <col width="*" />
                <col width="120" />
                <col width="100" />
                <col width="150" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">정렬순서</th>
                <th scope="col">공지여부</th>
                <th scope="col">제목</th>
    
                <th scope="col">노출업체</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td>141</td>
	                <td>1</td>
	                <td>
	                	<span class="status-able">공지</span>
					</td>
					
	                <td class="tl">테스트 게시팔 글입니다.</td>
	                
	                <td>
	                	20개업체
					</td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4" data-idx="" style="width:auto;">삭제</a>
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
	//등록 이동
	$(".btnNew").click(function(){
		location.href="/admin/vendor/notice_edit";
	});

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/vendor/notice_edit";
	});
</script>