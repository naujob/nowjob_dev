<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">게시판 기본 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">쿠폰번호<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                 CP200392
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">쿠폰명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                             	회원가입 감사쿠폰
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">금액<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					        5,000원
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">최소사용금액<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					        50,000원
					    </div>
					</td>
                </tr>
                
                </tbody>
            </table>
        </div>
		<div class="section">
            <h3 class="sub-title mb-10">쿠폰발급 리스트</h3>
            <div class="btn-table inline-wrap">
	    		<div class="left">
		    		<button type="button" class="btn-xs btn-type1 w100 btnSearch">전체선택</button>
		            <button type="button" class="btn-xs btn-type1 w100 btnSearch">선택해제</button>
		            <button type="button" class="btn-xs btn-type4 w100 btnSearch">선택삭제</button>
	    		</div>
	    		<div class="right">
		    		<button type="button" class="btn-xs btn-type1 w100 btnSearch">쿠폰발행</button>
	    		</div>
	        </div>
            <table class="table-type2 mt-10">
        		<colgroup>
        			<col width="50px" />
        			<col width="120px" />
        			<col width="100px" />
        			<col width="*" />
        			<col width="100px" />
        			<col width="100px" />
        			<col width="120px" />
        			<col width="100px" />
        			<col width="100px" />
        		</colgroup>
        		<tr>
        			<th>
        				<div class="input-text">
				            <label class="inline-wrap">
				                <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
				            </label>
				        </div>
        			</th>
        			<th>쿠폰코드</th>
        			<th>쿠폰유형</th>
        			<th>쿠폰명</th>
        			<th>할인금액(%)</th>
        			<th>최소사용금액</th>
        			<th>사용가능기간</th>
        			<th>상태</th>
        			<th>관리</th>
        		</tr>
        		<tr>
        			<td>
        				 <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
        			</td>
        			<td>CP0000001</td>
        			<td>주문서쿠폰</td>
        			<td class="tl">
        				<div class="txt_eli">
							회원가입 감사쿠폰
						</div>
					</td>
        			<td>3,000원</td>
        			<td>50,000원</td>
        			<td>2021-01-01 ~ 2021-03-01</td>
        			<td><span class="status-able">사용가능</span></td>
        			<td><a href="" class="btn-xs btn-type4">삭제</span></td>
        		</tr>
        		<tr>
        			<td>
        				 <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
        			</td>
        			<td>CP0000001</td>
        			<td>주문서쿠폰</td>
        			<td class="tl">
        				<div class="txt_eli">
							회원가입 감사쿠폰
						</div>
					</td>
        			<td>3,000원</td>
        			<td>50,000원</td>
        			<td>2021-01-01 ~ 2021-03-01</td>
        			<td><span class="status-able">사용가능</span></td>
        			<td><a href="" class="btn-xs btn-type4">삭제</span></td>
        		</tr>
        		<tr>
        			<td>
        				 <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
        			</td>
        			<td>CP0000001</td>
        			<td>주문서쿠폰</td>
        			<td class="tl">
        				<div class="txt_eli">
							회원가입 감사쿠폰
						</div>
					</td>
        			<td>3,000원</td>
        			<td>50,000원</td>
        			<td>2021-01-01 ~ 2021-03-01</td>
        			<td><span class="status-able">사용가능</span></td>
        			<td><a href="" class="btn-xs btn-type4">삭제</span></td>
        		</tr>
        		<tr>
        			<td>
        				 <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
        			</td>
        			<td>CP0000001</td>
        			<td>주문서쿠폰</td>
        			<td class="tl">
        				<div class="txt_eli">
							회원가입 감사쿠폰
						</div>
					</td>
        			<td>3,000원</td>
        			<td>50,000원</td>
        			<td>2021-01-01 ~ 2021-03-01</td>
        			<td><span class="status-able">사용가능</span></td>
        			<td><a href="" class="btn-xs btn-type4">삭제</span></td>
        		</tr>
        		<tr>
        			<td>
        				 <?=inputCheck("ad_id","ad_id",["Y"=>""],"")?>
        			</td>
        			<td>CP0000001</td>
        			<td>주문서쿠폰</td>
        			<td class="tl">
        				<div class="txt_eli">
							회원가입 감사쿠폰
						</div>
					</td>
        			<td>3,000원</td>
        			<td>50,000원</td>
        			<td>2021-01-01 ~ 2021-03-01</td>
        			<td><span class="status-able">사용가능</span></td>
        			<td><a href="" class="btn-xs btn-type4">삭제</span></td>
        		</tr>
                </tbody>
            </table>
        </div>
        <div>
		    <?=$pagenation?>
		</div>
        
    </div>
</form>
<script>
	$(".js-tab").click(function(){
		$('.SEditor').each(function(){
			oEditors.getById[$(this).attr("id")].exec('RESIZE_EDITING_AREA_BY',[true]);
		});
	});
</script>