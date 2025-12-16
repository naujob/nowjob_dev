<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">상품 노출 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품 노출 여부<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <?=inputRadio("ad_id","ad_id",getOptionList("display_yn"),"Y")?>
					</td>
                    
                </tr>
               <tr>
                    <th scope="col">상품 아이콘</th>
                     <td>
				        <div class="input-text">
				            <label class="inline-wrap">
				                <?=inputCheck("ad_id","ad_id",getOptionList("goods_icon"),getData($info,"ad_id","Y"))?>
				            </label>
				        </div>
				    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<div class="section">
            <h3 class="sub-title mb-10">카테고리 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품 노출 여부<em class="ess"></em></th>
                    <td class="inline-wrap">
                    	<span>
                    		1차 카테고리 : 
                    	</span>
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
                       	<span class="ml-10">
                    		2차 카테고리 : 
                    	</span>
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
                       	<span class="ml-10">
                    		3차 카테고리 : 
                    	</span>
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
                       	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="" style="width:auto;">카테고리 추가</a>
					</td>
                </tr>
               <tr>
                    <th scope="col">선택 카테고리 리스트</th>
                     <td>
				        <table class="table-type2">
				            <colgroup>
				            	<col width="80px" />
				                <col width="*" />
				                <col width="100" />
				            </colgroup>
				            <thead>
				            <tr>
				                <th scope="col" class="tc pl-0">번호</th>
				                <th scope="col" class="tc pl-0">카테고리명</th>
				                <th scope="col" class="tc pl-0">관리</th>
							</tr>
				            </thead>
				            <tbody>
								<tr>
					                <td class="tc">1</td>
					                <td>신발 > 안전화</td>
					                <td class="tc"><a href="javascript:void(0);" class="btn-xs btn-type4 btnGoLogin"  data-mem_id="" style="width:auto;">삭제</a></td>
					            </tr>
					            <tr>
					                <td class="tc">1</td>
					                <td>신발 > 안전화 > 운동화</td>
					                <td class="tc"><a href="javascript:void(0);" class="btn-xs btn-type4 btnGoLogin"  data-mem_id="" style="width:auto;">삭제</a></td>
					            </tr>
					            <tr>
					                <td class="tc">1</td>
					                <td>신발 > 안전화 > 운동화</td>
					                <td class="tc"><a href="javascript:void(0);" class="btn-xs btn-type4 btnGoLogin"  data-mem_id="" style="width:auto;">삭제</a></td>
					            </tr>
					            <tr>
					                <td class="tc">1</td>
					                <td>신발 > 안전화 > 운동화</td>
					                <td class="tc"><a href="javascript:void(0);" class="btn-xs btn-type4 btnGoLogin"  data-mem_id="" style="width:auto;">삭제</a></td>
					            </tr>
							</tbody>
				        </table>
				    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">상품 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품 관리(자동발행)</th>
                    <td class="inline-wrap">
                        <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","readonly",200)?>
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="btn-xs btn-type1 btnEdit" data-idx="" style="width:auto;">복사</a>
                    </td>
                     <th scope="col">자체 상품코드</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"PXC-0000-0000","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">상품명</th>
                    <td>
                        <table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            
				            <tbody>
								<tr>
					                <th>상품명<em class="ess"></em></td>
					                <td>
					                	<div class="input-text">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"상품명을 입력해주세요.","",200)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					                <th>부가 설명</th>
					                <td>
					                	<div class="input-text ">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"부가설명","",200)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                    <th scope="col">가격</th>
                    <td>
                        <table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>정상가<em class="ess"></em></td>
					                <td class="inline-wrap">
					                	<div class="input-text w120">
				                            <label>
				                                <?=inputNumber("ad_id","ad_id",getData($info,"ad_id"),"","",10)?>
				                            </label>
				                        </div>
				                        <span class="ml-5">원</span>
					                </td>
					            </tr>
					            <tr>
					                <th>판매가<em class="ess"></em></th>
					                <td class="inline-wrap">
					                	<div class="input-text w120">
				                            <label>
				                                <?=inputNumber("ad_id","ad_id",getData($info,"ad_id"),"","",10)?>
				                            </label>
				                        </div>
				                        <span class="ml-5">원</span>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
             	<tr>
             		<th scope="col">브랜드</th>
                    <td class="">
                       	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
					</td>
                    <th scope="col">회원등급 추가혜택</th>
                    <td class="inline-wrap">
					    <?=inputRadio("ad_id","ad_id",getOptionList("do_yn"),"")?>
					</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">추가 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상품 순위</th>
                    <td>
                        <div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"0","",200)?>
                            </label>
                        </div>
                    </td>
                    
	                <th>상품 전체 중량</th>
	                <td class="inline-wrap">
	                	<div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"0.55","",200)?>
                            </label>
                        </div>
                        <span>kg</span>
	                </td>
                </tr>
                <tr>
                    
                </tr>
                <tr>
                    <th scope="col">제조사</th>
                    <td class="inline-wrap">
                   		<div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">원산지</th>
                    <td class="inline-wrap">
                   		<div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">재고관리</th>
                    <td colspan="3">
                    	<table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>재고 관리여부</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("ad_id","ad_id",getOptionList("use_yn"),"")?>
				                   		</div>
					                </td>
					            </tr>
					            <tr>
					                <th>재고수량</th>
					                <td class="inline-wrap">
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"0","",200)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">옵션 설정</th>
                    <td colspan="3">
                    	<table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>옵션 설정여부 </td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("ad_id","ad_id",getOptionList("use_yn"),"")?>
				                   		</div>
					                </td>
					            </tr>
					            <tr>
					                <th>옵션 설정</th>
					                <td class="inline-wrap">
					                	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">옵션 관리창 열기</a>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">정보재공 고시</th>
                    <td colspan="3">
                   		<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">정보제공고시 관리창 열기</a>
                   		<p class="exc1">정보재공 상품 상세 정보에 노출됩니다.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">관련 상품 설정</th>
                    <td colspan="3">
                   		<table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>관련상품 설정</td>
					                <td class="inline-wrap">
					                	<div class="inline-wrap">
				                   			<?=inputRadio("ad_id","ad_id",getOptionList("goods_rel_product"),"")?>
				                   		</div>
					                </td>
					            </tr>
					            <tr>
					                <th>수동상품 선택</th>
					                <td class="inline-wrap">
					                	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">관련상품 선택창 열기</a>
					                </td>
					            </tr>
					             <tr>
					                <th>수동상품 선택</th>
					                <td>
					                	<div class="input-text">
				                            <label>
				                                <?=inputTextArea("ad_id","ad_id",getData($info,"ad_id"),"","style='background:#f7f7f7;'",200)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">검색어 설정</th>
                    <td colspan="3">
                   		<div class="input-text">
	                        <label>
	                            <?=inputTextArea("ad_id","ad_id",getData($info,"ad_id"),"","style='height:60px;'",200)?>
	                        </label>
	                    </div>
	                    <p class="exc1">검색어는 ', (콤마)'로 구분해주시기 바랍니다.</p>
                    </td>
                </tr>
                
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">배송 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">배송비 설정</th>
                    <td>
                        <div class="inline-wrap">
	               			<?=inputRadio("ad_id","ad_id",getOptionList("goods_delivery_setting"),"")?>
	               		</div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">배송비  개별설정</th>
                    <td>
                        <table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>배송 그룹 선택</td>
					                <td class="inline-wrap">
					                	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
					                	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">배송 그룹 관리</a>
					                </td>
					            </tr>
					            <tr>
					                <th>배송방법</th>
					                <td >
					                	<?=inputSelect("search_type","search_type",getOptionList("delivery_type"),"")?>
					                </td>
					            </tr>
					            <tr>
					                <th>배송기간</th>
					                <td class="inline-wrap">
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>일 ~ </span>
				                        <div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>일정도 소요됩니다.</span>
					                </td>
					            </tr>
					            <tr>
					                <th>배송비</th>
					                <td class="inline-wrap">
					                	<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
					                </td>
					            </tr>
					            <tr>
					                <th>무료배송</th>
					                <td class="inline-wrap">
					                	없음
					                </td>
					            </tr>
					             
					            <tr>
					                <th>고정배송비</th>
					                <td class="inline-wrap">
					                	<span>구매 금액에 상관없이 배송비 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>원을 부과합니다.</span>
					                </td>
					            </tr>
					            <tr>
					                <th>구매 금액에 따른 부과</th>
					                <td class="inline-wrap">
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>원 미만일때 </span>
					                	<div class="input-text w100">
				                            <label>
				                               <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>원을 부과합니다.</span>
					                </td>
					            </tr>
					            <tr>
					                <th>구매  금액별 차등 배송비</th>
					                <td class="inline-wrap">
					                	
				                		<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>원  이상 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>원  미만일경우 배송비 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>원을 부과합니다.</span>
				                      	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">추가</a>
					                	
					                	<div class="mt-10">
					                		<table class="table-type2">
									            <colgroup>
									                <col width="50" />
									                <col width="*" />
									                <col width="100" />
									                <col width="100" />
									            </colgroup>
									            <thead>
									            <tr>
									                <th scope="col">번호</th>
									                <th scope="col">구매금액</th>
									                <th scope="col">배송비</th>
									                <th scope="col">삭제</th>
												</tr>
									            </thead>
									            <tbody>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            </tbody>
									         </table>
					                	</div>
					                </td>
					            </tr>
					            <tr>
					                <th>무게별 차등 배송비</th>
					                <td class="inline-wrap">
					                	
				                		<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>kg  이상 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>kg  미만일경우 배송비 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>원을 부과합니다.</span>
				                      	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">추가</a>
					                	
					                	<div class="mt-10">
					                		<table class="table-type2">
									            <colgroup>
									                <col width="50" />
									                <col width="*" />
									                <col width="100" />
									                <col width="100" />
									            </colgroup>
									            <thead>
									            <tr>
									                <th scope="col">번호</th>
									                <th scope="col">상품무게</th>
									                <th scope="col">배송비</th>
									                <th scope="col">삭제</th>
												</tr>
									            </thead>
									            <tbody>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            </tbody>
									         </table>
					                	</div>
					                </td>
					            </tr>
					            <tr>
					                <th>상품수량별 차등 배송비</th>
					                <td class="inline-wrap">
				                		<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>개  이상 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>개  미만일경우 배송비 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <span>원을 부과합니다.</span>
				                      	<a href="javascript:void(0);" class="btn-xs btn-type7 btnEdit" data-idx="" style="width:auto;">추가</a>
					                	<div class="mt-10">
					                		<table class="table-type2">
									            <colgroup>
									                <col width="50" />
									                <col width="*" />
									                <col width="100" />
									                <col width="100" />
									            </colgroup>
									            <thead>
									            <tr>
									                <th scope="col">번호</th>
									                <th scope="col">상품수량</th>
									                <th scope="col">배송비</th>
									                <th scope="col">삭제</th>
												</tr>
									            </thead>
									            <tbody>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            	<tr>
									            		<td>1</td>
									            		<td>200  원이상 2000원 미만</td>
									            		<td>2000원</td>
									            		<td>삭제</td>
									            	</tr>
									            </tbody>
									         </table>
					                	</div>
					                </td>
					            </tr>
					            <tr>
					                <th>상품 수량에 비례하여 배송비</th>
					                <td class="inline-wrap">
				                		
					                	<span>주문 금액에 상관없이 상품 수량마다 배송비 </span>
					                	<div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
					                	<span>원을 부과합니다.</span>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">상품 이미지</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">이미지 등록 설정</th>
                    <td>
                        <div class="inline-wrap">
						    <?=inputRadio("ad_id","ad_id",getOptionList("goods_regist_image_type"),"")?>
						</div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">대표 이미지</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("icon_1","icon_1","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png</p>
                    	<p class="exc1">권장 사이즈 : 600 x 600 px </p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">개별 이미지</th>
                    <td>
                       <p class="exc1">대표이미지 썸네일 자동 설정됩니다. 200px x 200px</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">개별 이미지</th>
                    <td>
                       <table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>목록 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
				                        	<?=inputImage("icon_2","icon_2","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
				                        </div>
				                        <p class="exc1 ml-10">권장 포멧 : png , 사이즈 600 x 600 px </p>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">대표 이미지</th>
                    <td class="inline-wrap">
                        <div class="input-text w400">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
                    </td>
                </tr>
                <tr>
                    <th scope="col">개별 이미지</th>
                    <td>
                       <table class="table-form">
				            <colgroup>
				            	<col width="120px" />
				                <col width="*" />
				            </colgroup>
				            <tbody>
								<tr>
					                <th>목록 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
					            <tr>
					                <th>상세 이미지</td>
					                <td class="inline-wrap">
					                	<div class="input-text w400">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">미리보기</a>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
        	<h3 class="sub-title mb-10">상품 상세 정보</h3>
        	<table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">PC,모바일 상세 함께 사용</th>
                    <td>
                        <div class="input-text">
				            <label class="inline-wrap">
				                <?=inputCheck("ad_id","ad_id",['Y'=>'함께 사용시 체크'],getData($info,"ad_id","Y"))?>
				            </label>
				        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">상세정보</th>
                    <td>
                        <div class="tab policy js-tab">
				            <a data-tab_id="#tab_1" href="javascript:void(0);" class="on">제품상세(PC)</a>
				            <a data-tab_id="#tab_2" href="javascript:void(0);">제품상세(모바일)</a>
				        </div>
				        <div class="tab-content-wrap">
				            <div id="tab_1" class="tab-article on">
				                <div class="input-text">
			                    	<?=inputTextArea("ad_pl_policy1","ad_pl_policy1",getData($info,"ad_pl_policy1"),"","style='height:500px;' class='SEditor'",10000)?>
			                    </div>
				            </div>
				            <div id="tab_2" class="tab-article">
				                <div class="input-text">
			                    	<?=inputTextArea("ad_pl_policy2","ad_pl_policy2",getData($info,"ad_pl_policy2"),"","style='height:500px;' class='SEditor'",10000)?>
			                    </div>
				            </div>
				        </div>
                    </td>
                </tr>
                </tbody>
            </table>
	    </div>
	    <div class="section">
        	<h3 class="sub-title mb-10">이용안내</h3>
        	<table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상세정보</th>
                    <td>
                        <div class="tab policy js-tab">
				            <a data-tab_id="#tab_11" href="javascript:void(0);" class="on">상품결제 안내</a>
				            <a data-tab_id="#tab_12" href="javascript:void(0);">상품배송 안내</a>
				            <a data-tab_id="#tab_13" href="javascript:void(0);">교환/반품  안내</a>
				        </div>
				        <div class="tab-content-wrap">
				            <div id="tab_11" class="tab-article on">
				            	<div class="inline-wrap">
					            	<div class="inline-wrap">
									    <?=inputRadio("ad_id","ad_id",getOptionList("goods_auto_setting"),"")?>
									</div>
									<div class="inline-wrap ml-10">
										<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
									</div>
								</div>
				                <div class="input-text mt-10">
			                    	<?=inputTextArea("test1","test1",getData($info,"test1"),"","style='height:500px;' class='SEditor'",10000)?>
			                    </div>
				            </div>
				            <div id="tab_12" class="tab-article">
				            	<div class="inline-wrap">
					            	<div class="inline-wrap">
									    <?=inputRadio("ad_id","ad_id",getOptionList("goods_auto_setting"),"")?>
									</div>
									<div class="inline-wrap ml-10">
										<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
									</div>
								</div>
				                <div class="input-text mt-10">
			                    	<?=inputTextArea("test2","test2",getData($info,"test2"),"","style='height:500px;' class='SEditor'",10000)?>
			                    </div>
				            </div>
				             <div id="tab_13" class="tab-article">
				             	<div class="inline-wrap">
					            	<div class="inline-wrap">
									    <?=inputRadio("ad_id","ad_id",getOptionList("goods_auto_setting"),"")?>
									</div>
									<div class="inline-wrap ml-10">
										<?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
									</div>
								</div>
				                <div class="input-text mt-10">
			                    	<?=inputTextArea("test3","test3",getData($info,"test3"),"","style='height:500px;' class='SEditor'",10000)?>
			                    </div>
				            </div>
				        </div>
                    </td>
                </tr>
                </tbody>
            </table>
	    </div>
        <div class="btn-wrap inline-wrap mt-40 tc">
            <!--button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button-->
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
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