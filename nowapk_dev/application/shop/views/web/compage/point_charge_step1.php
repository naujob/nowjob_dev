<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php $tab_on2 = "menu1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>

            <div class="tab-panel on">
                <div class="point-charge step1">
                    <h3 class="sr-text">포인트충전</h3>
                    <form action="/">

                        <div class="total-mypoint">
                            <table>
                                <caption><span class="sr-text">내포인트정보</span></caption>
                                <colgroup><col><col></colgroup>
                                <tbody>
                                    <tr>
                                        <td class="label">
                                            <strong>나의 포인트</strong>
                                            <span>포인트를 구매하시면 추천인재 등록및 점프업이 가능합니다.</span>
                                        </td>
                                        <td class="value"><em><?=number_format($mem_info["mm_point"])?></em>P</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group card-group">
                            <?php foreach($point_list as $row) : ?>
                              <?php //$row["cd_val"] = 3500;//test ?>
                            <label class="form-toggle-btn card">
                                <input type="radio" name="point" data-point_key="<?=($row["cd_key"])?>" value="<?=($row["cd_val"])?>">
                                <span class="card-body">
                                    <span class="ico"></span>
                                    <span class="point"><em><?=number_format($row["cd_val2"])?></em>P</span>
                                </span>
                                <span class="card-footer">
                                    <span class="price">￦<em><?=number_format($row["cd_val"])?></em></span>
                                    <?php if($row["cd_val2"]!=$row["cd_val"]) : ?>
                                    <span class="discount"><?=(round((($row["cd_val2"] - $row["cd_val"])/$row["cd_val2"]) * 100) - 0)?>%할인</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="help-msg">(주)나우디앤아이는 통신판매중개자이며, 통신판매의 당사자가 아닙니다.전자상거래 등에서의 소비자보호에 관한 법률 등 관련 법령 및 (주)나우디앤아이의 약관에 따라 상품, 상품정보, 거래에 관한 책임은 개별 판매자에게 귀속하고,<br/>
		 					(주)나우디앤아이는 원칙적으로 회원간 거래에 대하여 책임을 지지 않습니다. 다만, (주)나우디앤아이가 직접 판매하는 상품에 대한 책임은 (주)나우디앤아이에게 있습니다.<br/>
		 					민원 담당자 : 박해찬   /   연락처 : 031-255-0119
                        </p>
                        <p class="help-msg">
                            취소/환불 규정<br/>
                            > 판매상품 내 서비스 제공기간 : 1년<br/>
                            > 서비스 이용기간 및 정책에 대한 내용은 이용약관을 참고하여 주시기 바랍니다.<br/>
                        </p>
                        <div class="form-action-wrap align-right">
                            <button id="btnNext" type="button" class="btn-round btn-tertiary">충전하기</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->


<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
        if($("[name=point]").is(":checked")==false){
            commonAlert("결제하실 포인트를 선택해주세요.",function(){});
            return;
        }

        var params = {};
        params["point_key"] = $("[name=point]:checked").attr("data-point_key");
        params["point_price"] = $("[name=point]:checked").val();
        comAjax("/mypage_proc/point_charge_step1",params,function(ret){
            if(ret.code=="00"){
                location.href="/compage/point_charge_step2"
            }else{
            	commonAlert(ret.msg,function(){});
            }
        });
	});
});
</script>
