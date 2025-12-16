<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab6";?>
            <?php $tab_on2 = "menu1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="my-coupon">
                    <h3 class="sr-text">쿠폰 관리</h3>
                    <div class="result-header">
                        <p class="total">보유쿠폰 <em><?=count($coupon_list)?></em>건</p>
                    </div>
                    <div class="result-wrap">
                        <!-- 목록 -->
                        <ol class="result-list">
                            <?php foreach($coupon_list as $row) : ?>
	                        <li>
	                            <div class="coupon">
	                                <div class="content-wrap" style="padding-top: 48px;">
	                                    <p class="title"><strong><?=$row["cp_name"]?></strong></p>
	                                    <p class="benefit"><em><?=$row["cl_price"]?></em><?=$row["cl_dan"]=="won" ? "원":"%"?></p>
	                                    <p class="date">사용기간 : <em><?=str_replace("-",".",substr($row["cl_use_sdate"],0,10))?>~<?=str_replace("-",".",substr($row["cl_use_edate"],0,10))?></em></p>
	                                    <p class="date" style="color:#aaa;">최소사용금액 : <em><?=$row["cl_limit_price"]?></em></p>
                                      <a href="" onclick="fGoPointPay('<?=$row["cl_price"]?>','<?=$row["cl_dan"]?>','<?=$row["cl_idx"]?>');" style="color: #fff; background-color: #57CCAF; padding: 5px 10px; border-radius: 7px; margin-top: 6px; display: inline-block;">사용하기</a>
	                                </div>
                                  <?php if($row["cl_use_yn"]=="Y"){ ?>
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.7; text-align: center; padding-top: 23%; font-size: 33px; color: #fff;">사용 쿠폰</div>
                                  <?php }else if($row["cl_use_edate"] < date("Y-m-d H:i:s")){ ?>
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.7; text-align: center; padding-top: 23%; font-size: 33px; color: #fff;">기간 만료</div>
                                  <?php } ?>
	                            </div>
	                        </li>
	                        <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
function fGoPointPay(cl_price,cl_dan, cl_idx){
  var params = {};
  if(cl_dan=='won'){
    if(cl_price < 0 || cl_price > 999999999){
      alert('금액이 맞지 않습니다.');
      return;
    }
    if(cl_price < 2000){
      params["point_key"] = "P001";
      params["point_price"] = "1000";
    }else if(cl_price < 5000){
      params["point_key"] = "P002";
      params["point_price"] = "2000";
    }else if(cl_price < 10000){
      params["point_key"] = "P003";
      params["point_price"] = "5000";
    }else if(cl_price < 50000){
      params["point_key"] = "P004";
      params["point_price"] = "10000";
    }else if(cl_price < 100000){
      params["point_key"] = "P005";
      params["point_price"] = "50000";
    }else{
      params["point_key"] = "P006";
      params["point_price"] = "100000";
    }
    comAjax("/mypage_proc/point_charge_step1",params,function(ret){
        if(ret.code=="00"){
            location.href="/compage/point_charge_step2/"+cl_idx
        }else{
          commonAlert(ret.msg,function(){});
        }
    });
  }else{
    alert('단위가 맞지 않습니다.');
  }

}
</script>
