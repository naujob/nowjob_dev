<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">사업자 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">입점사명<em class="ess"></em></th>
                    <td colspan="3">
					    <div class="input-text w200">
                            <label>
                                테스트몰1
                            </label>
                        </div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">입점사아이디<em class="ess"></em></th>
                    <td class="inline-wrap" colspan="3">
					    <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","readonly",200)?>
						    </label>
						</div>
					</td>
                    
                </tr>
                 <tr>
                    <th scope="col">비밀번호<em class="ess"></em></th>
                    <td colspan="3">
					    <button type="button" id="chg_password" class="btn-s btn-type6">비밀번호변경</button>
					    <button type="button" id="chg_password" class="btn-s btn-type2">비밀번호초기화</button>
					</td>
                    
                </tr>
                 <tr>
                    <th scope="col">판매상태<em class="ess"></em></th>
                    <td colspan="3">
					    <div>
						    <?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
						</div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">판매상품<em class="ess"></em></th>
                    <td class="inline-wrap" colspan="3">
                    	<a href="javascript:void(0);" class="btn-xs btn-type1 btnEdit" data-idx="" style="width:auto;">확인하기</a>
                        <div class="input-text ml-5">
                            <label>
                                총 상품수 : 200개 / 판매중 : 120개
                            </label>
                        </div>
					</td>
                    
                </tr>
               <tr>
                    <th scope="col">판매등급</th>
                     <td colspan="3">
				         <div>
						    <?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
						</div>
				    </td>
                </tr>
                <tr>
                    <th scope="col">구분</th>
                     <td>
				        <div class="input-text">
				            <label>
				               	입정 완료
				               	<a href="javascript:void(0);" class="btn-xs btn-type6 btnEdit" data-idx="" style="width:auto;">입정 승인 처리</a>
				            </label>
				        </div>
				    </td>
				    <th scope="col">입점일시</th>
                     <td>
				        <div class="input-text">
				            <label>2021-02-01 12:00:10</label>
				        </div>
				    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<div class="section">
            <h3 class="sub-title mb-10">판매자 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">상호(회사명)<em class="ess"></em></th>
                    <td >
					     <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                    <th scope="col">대표자 이름<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">사업자 번호<em class="ess"></em></th>
                    <td >
					     <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                    <th scope="col">주민/법인(법인등록번호)<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">업태<em class="ess"></em></th>
                    <td>
					     <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
					 <th scope="col">종목<em class="ess"></em></th>
                    <td>
					     <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">연락처<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
					 <th scope="col">팩스<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
						    <label>
						        <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
						    </label>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">사업자 등록증 사본<em class="ess"></em></th>
                    <td colspan="3">
					    <?=inputRadio("ad_id","ad_id",getOptionList("display_yn"),"Y")?>
					</td>
                </tr>
                <tr>
                    <th scope="col">사업장 주소<em class="ess"></em></th>
                    <td colspan="3">
					    <table class="table-form">
			                <colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tbody>
			                	<tr>
			                		<th scope="col">우편번호</th>
				                    <td class="inline-wrap">
				                        <div class="input-text w100">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"00000","",200)?>
				                            </label>
				                        </div>
				                        <a href="javascript:void(0);" class="btn-xs btn-type2 btnEdit" data-idx="" style="width:auto;">주소검색</a>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">주소</th>
				                    <td >
				                        <div class="input-text w300">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"00도 00시 00로 ..","",200)?>
				                            </label>
				                        </div>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">지번주소</th>
				                    <td >
				                        <div class="input-text w300">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"00도 00시 00동..","",200)?>
				                            </label>
				                        </div>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">상세주소</th>
				                    <td >
				                        <div class="input-text w300">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"000동 000호","",200)?>
				                            </label>
				                        </div>
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
            <h3 class="sub-title mb-10">정산 기준</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">수수료율</th>
                    <td class="inline-wrap">
                    	<a href="javascript:void(0);" class="btn-xs btn-type1 btnEdit" data-idx="" style="width:auto;">설정하기</a>
                        <div class="input-text ml-5">
                            <label>
                                1.0%
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">정산주기</th>
                    <td class="inline-wrap">
                        <a href="javascript:void(0);" class="btn-xs btn-type1 btnEdit" data-idx="" style="width:auto;">설정하기</a>
                        <div class="input-text ml-5">
                            <label>
                        		당월: 월 0회, 익월: 월 회
                            </label>
                        </div>
                    </td>
                </tr>
             	<tr>
                    <th scope="col">입금 계좌 정보</th>
                    <td>
                        <table class="table-form">
			                <colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tbody>
			                	<tr>
			                		<th scope="col">은행</th>
				                    <td>
				                        <div>
										    <?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
										</div>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">예금주</th>
				                    <td >
				                        <div class="input-text w200">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"홍길동","",200)?>
				                            </label>
				                        </div>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">계좌번호</th>
				                    <td >
				                        <div class="input-text w200">
				                            <label>
				                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"137-00000-00000","",200)?>
				                            </label>
				                        </div>
				                    </td>
			                	</tr>
			                	<tr>
			                		<th scope="col">계좌번호 사본(첨부파일)</th>
				                    <td >
				                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
										    <?=inputFile("icon_1","icon_1","","<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
										</div>
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
            <h3 class="sub-title mb-10">담당자 정보</h3>
            <table class="table-type2" style="width:100%;">
                <colgroup>
                    <col width="100px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">구분</th>
                    <th scope="col">이름</th>
                    <th scope="col">이메일</th>
                    <th scope="col">휴대폰번호</th>
                    <th scope="col">전화번호</th>
                </tr>
                <tr>
                    <th class="inline-wrap tl">
                   		<div class="input-text w100p">
                            <label>
                                CS담당자
                            </label>
                        </div>
                    </th>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="inline-wrap tl">
                   		<div class="input-text w100p">
                            <label>
                                물류 담당자
                            </label>
                        </div>
                    </th>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="inline-wrap tl">
                   		<div class="input-text w100p">
                            <label>
                                MD 담당
                            </label>
                        </div>
                    </th>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="inline-wrap tl">
                   		<div class="input-text w100p">
                            <label>
                                정산담당
                            </label>
                        </div>
                    </th>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <td class="inline-wrap">
                   		<div class="input-text w100p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
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