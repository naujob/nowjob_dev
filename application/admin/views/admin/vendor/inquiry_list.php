<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/goods/list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">1:1문의 관리</h3>
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
                <col width="150" />
                <col width="*" />
        		<col width="100" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">업체아이디</th>
                <th scope="col">제목</th>
                <th scope="col">답변여부</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td>141</td>
	                <td>
	                	입점몰1<br/>
	                	test001
	                </td>
	                <td class="tl">1:1문의 제목입니다.</td>
	                <td><span class="status-able">답변완료</span></td>
	                <td>2021-01-26</td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">답변</a>
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
		location.href="/admin/vendor/inquiry_edit";
	});

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/vendor/inquiry_edit";
	});
</script>