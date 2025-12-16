<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">SNS 로그인 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">카카오 SNS 로그인</th>
                    <td>
                    	<p class="exc2 pb-5">카카오 로그인,공유하기 기능은 카카오 계발계정생성후 사용 가능합니다.</p>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>로그인 사용</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_kakao_login_yn","st_kakao_login_yn",['Y'=>'로그인 사용하기'],getData($info,"st_kakao_login_yn","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th>REST</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputText("st_kakao_rest_id","st_kakao_rest_id",getData($info,"st_kakao_rest_id"),"","",50)?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th>javascript</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputText("st_kakao_javascript_id","st_kakao_javascript_id",getData($info,"st_kakao_javascript_id"),"","",50)?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                        <p class="exc1">발급 URL : <a href="https://developers.kakao.com/">https://developers.kakao.com/</a></p>
                        <p class="exc1">위 링크를 통해 카카오톡의 새 앱 만들기를 진행합니다.</p>
                        <p class="exc1">적용할 사이트의 도메인을 등록한 후 발급된 API key를 등록하시면 됩니다. 단, 등록한 도메인에서만 카카오톡이 정상적으로 작동합니다.</p>
                        <p class="exc1">발급받은 카카오톡 API 정보를 입력하세요.</p>
                        <p class="exc1">발급위치 : 카카오톡설정 > 설정 > 일반 > 앱키 > REST API, JAVASCRIPT API 키</p>
                        <p class="exc1">Redirect URI: http://koreagoodfood.com/addons/sns_login/kakao/callback.php, https://koreagoodfood.com:443/addons/sns_login/kakao/callback.php</p>
                        <p class="exc1">공유 기능을 사용할 경우 상품 상세페이지에 해당 SNS로 상품을 공유할 수 있는 버튼이 노출됩니다.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">네이버 SNS 로그인</th>
                    <td>
                    	<p class="exc2 pb-5">네이버 기능은 API 계정생성후 사용가능합니다.</p>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>로그인 사용</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_naver_login_yn","st_naver_login_yn",['Y'=>'로그인 사용하기'],getData($info,"st_naver_login_yn","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th>Client ID</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputText("st_naver_client_id","st_naver_client_id",getData($info,"st_naver_client_id"),"","",50)?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    		<tr>
                    			<th>Client Secret</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputText("st_naver_client_secret_id","st_naver_client_secret_id",getData($info,"st_naver_client_secret_id"),"","",50)?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                        <p class="exc1">발급 URL : <a href="https://nid.naver.com/devcenter/main.nhn">https://nid.naver.com/devcenter/main.nhn</a></p>
                        <p class="exc1">네이버 로그인 등록' 버튼을 통해 등록할 수 있습니다.</p>
                        <p class="exc1">서비스 URL : http://koreagoodfood.com</p>
                        <p class="exc1">Callback URL http://koreagoodfood.com/addons/sns_login/naver/callback.php, https://koreagoodfood.com:443/addons/sns_login/naver/callback.php</p>
                        <p class="exc2">API 상태를 개발중에서 서비스 적용으로 변경해주시고 로그인, 회원가입, 정보수정 페이지를 스크린샷 하여 하나의 파일로 만든 다음 검수 파일 첨부를 하시면됩니다.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!--
        	공유하기 기능 확인
        	https://bssow.tistory.com/116
        	-->
        <div class="section">
            <h3 class="sub-title mb-10">SNS 공유 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">카카오</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_kakao","st_share_use_kakao",['Y'=>'사용하기'],getData($info,"st_share_use_kakao","Y"))?>
			                            </label>
			                        </div>
			                        <p class="exc1">카카오 SNS 로그인 연동이 필수 입니다.</p>
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">카카오 스토리</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_kakaostory","st_share_use_kakaostory",['Y'=>'사용하기'],getData($info,"st_share_use_kakaostory","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">페이스북</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_facebook","st_share_use_facebook",['Y'=>'사용하기'],getData($info,"st_share_use_facebook","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">트위터</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_twiter","st_share_use_twiter",['Y'=>'사용하기'],getData($info,"st_share_use_twiter","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
               
                <tr>
                    <th scope="col">네이버</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_naver","st_share_use_naver",['Y'=>'사용하기'],getData($info,"st_share_use_naver","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
                <tr>
                    <th scope="col">밴드</th>
                    <td>
                    	<table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>사용여부</th>
                    			<td>
                    				<div class="input-text w300">
			                            <label>
			                                <?=inputCheck("st_share_use_band","st_share_use_band",['Y'=>'사용하기'],getData($info,"st_share_use_band","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
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
$(document).ready(function(){
	$("#btnSave").click(function(){
		var params = $("#frm").serialize();
		comAjaxLoading("/admin/setting/manage/sns_api_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});

});
</script>