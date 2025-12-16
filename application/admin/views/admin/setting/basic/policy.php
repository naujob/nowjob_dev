<form id="frm" class="controls" method="post">
    <div class="article">
        <div class="tab policy js-tab">
            <a data-tab_id="#tab_1" href="javascript:void(0);" class="on">개인회원 이용약관</a>
            <a data-tab_id="#tab_5" href="javascript:void(0);">기업회원 이용약관</a>
            <a data-tab_id="#tab_2" href="javascript:void(0);">개인정보처리방침</a>
            <a data-tab_id="#tab_3" href="javascript:void(0);">개인정보 수집 및 이용동의</a>
            <a data-tab_id="#tab_4" href="javascript:void(0);">위치기반서비스 이용약관</a>
            <a data-tab_id="#tab_9" href="javascript:void(0);">광고성 정보 수신 동의</a>
            <!--a data-tab_id="#tab_6" href="javascript:void(0);">중고거래</a-->
            <a data-tab_id="#tab_7" href="javascript:void(0);">중고거래-상품등록</a>
            <a data-tab_id="#tab_8" href="javascript:void(0);">중고거래-개인정보</a>
        </div>
        <div class="tab-content-wrap">
            <div id="tab_1" class="tab-article on">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">이용약관(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("policy_pc","policy_pc",getData($info,"policy_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">이용약관(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("policy_m","policy_m",getData($info,"policy_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_5" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">이용약관 기업(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("policy2_pc","policy2_pc",getData($info,"policy2_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">이용약관 기업(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("policy2_m","policy2_m",getData($info,"policy2_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_6" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">중거거래(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_pc","trade_pc",getData($info,"trade_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">중거거래(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_m","trade_m",getData($info,"trade_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_7" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">중거거래-상품등록(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_goods_pc","trade_goods_pc",getData($info,"trade_goods_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">중거거래-상품등록(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_goods_m","trade_goods_m",getData($info,"trade_goods_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_8" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">중거거래-개인정보(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_term_pc","trade_term_pc",getData($info,"trade_term_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">중거거래-개인정보(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("trade_term_m","trade_term_m",getData($info,"trade_term_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_2" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">개인정보처리방침(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("privacy_pc","privacy_pc",getData($info,"privacy_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                 <tr>
	                	<th scope="col">개인정보처리방침(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("privacy_m","privacy_m",getData($info,"privacy_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_3" class="tab-article">
            	<table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">제3자 개인정보 수집동의(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("privacy3_pc","privacy3_pc",getData($info,"privacy3_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">제3자 개인정보 수집동의(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("privacy3_m","privacy3_m",getData($info,"privacy3_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_4" class="tab-article">
            	<table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">위치정보 이용(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("location_pc","location_pc",getData($info,"location_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">위치정보 이용(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("location_m","location_m",getData($info,"location_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
           <div id="tab_9" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>

	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">이용약관 기업(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("adv_pc","adv_pc",getData($info,"adv_pc"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">이용약관 기업(App)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("adv_m","adv_m",getData($info,"adv_m"),"","",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
        </div>
    </div>

    <div class="btn-wrap inline-wrap mt-40 tc">
        <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
    </div>
</form>


<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		var params = {};
		params["policy_pc"] = $("#policy_pc").val();
		params["policy_m"] = $("#policy_m").val();
		params["privacy_pc"] = $("#privacy_pc").val();
		params["privacy_m"] = $("#privacy_m").val();
		params["privacy3_pc"] = $("#privacy3_pc").val();
		params["privacy3_m"] = $("#privacy3_m").val();
		params["location_pc"] = $("#location_pc").val();
		params["location_m"] = $("#location_m").val();
		params["policy2_pc"] = $("#policy2_pc").val();
		params["policy2_m"] = $("#policy2_m").val();
		params["trade_pc"] = $("#trade_pc").val();
		params["trade_m"] = $("#trade_m").val();

		params["adv_pc"] = $("#adv_pc").val();
		params["adv_m"] = $("#adv_m").val();

		params["trade_goods_pc"] = $("#trade_goods_pc").val();
		params["trade_goods_m"] = $("#trade_goods_m").val();

		params["trade_term_pc"] = $("#trade_term_pc").val();
		params["trade_term_m"] = $("#trade_term_m").val();

		comAjaxLoading("/admin/setting/basic/policy_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});

	loadEditor("#policy_pc",500);
	loadEditor("#policy_m",500);
	loadEditor("#privacy_pc",500);
	loadEditor("#privacy_m",500);
	loadEditor("#privacy3_pc",500);
	loadEditor("#privacy3_m",500);
	loadEditor("#location_pc",500);
	loadEditor("#location_m",500);
	loadEditor("#policy2_pc",500);
	loadEditor("#policy2_m",500);
	loadEditor("#trade_pc",500);
	loadEditor("#trade_m",500);
	loadEditor("#trade_goods_pc",500);
	loadEditor("#trade_goods_m",500);
	loadEditor("#trade_term_pc",500);
	loadEditor("#trade_term_m",500);

	loadEditor("#adv_pc",500);
	loadEditor("#adv_m",500);
});
</script>
