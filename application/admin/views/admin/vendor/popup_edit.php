<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">팝업 등록/수정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">팝업명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출 업체 설정<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                        <a href="javascript:void(0);" class="ml-5 btn-ss btn-type2 btnEdit" data-idx="" style="width:auto;">선택 </a>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					            <?=inputRadio("ad_id","ad_id",getOptionList("use_yn"),"")?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">기간설정<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	<?=inputRadio("ad_id","ad_id",["01"=>"무한노출","02"=>"기간설정"],"")?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출기간</th>
                    <td  class="inline-wrap">
					  	<div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					    <span> - </span>
					    <div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">링크설정</th>
                    <td>
					  	<div class="inline-wrap">
					            <?=inputRadio("ad_id","ad_id",["01"=>"링크없음","02"=>"동일창","03"=>"새창"],"")?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">링크주소</th>
                    <td>
					  	<div class="input-text w500">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					    
					</td>
                </tr>
                 <tr>
                    <th scope="col">배경색 컬러코드<em class="ess"></em></th>
                    <td>
					  	<div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"#FFFFFF","",200)?>
                            </label>
                        </div>
                        <p class="exc1">예시 : #0099ff</p>
					</td>
                </tr>
                <tr>
                    <th scope="col">권장 사이즈</th>
                    <td>
					    <p class="exc1">권장 포멧 : png</p>
						<p class="exc1">웹 권장 사이즈 : 400 x 400 px </p>
						<p class="exc1">모바일 권장 사이즈 : 800 x 800 px (기기에 맞게 자동 조절 됩니다.) </p>
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
        
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>
<script>
	
</script>