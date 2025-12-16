 <div class="page page-mypage-view">
     <div class="top-bar">
         <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
         <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
     </div><!-- //top-bar -->

     <div class="page-content">
         <?php $tab_on = "tab1"; ?>
         <?php include_once(get_base_url() . '/application/shop/views/mobile/mypage/top_line.php'); ?>
         <div role="tabpanel" class="tab-panel">

             <div class="section point-charge step1">
                 <h2 class="sr-text">포인트충전</h3>
                     <div class="container">
                         <form action="/">

                             <div class="total-mypoint">
                                 <table>
                                     <caption><span class="sr-text">내포인트정보</span></caption>
                                     <colgroup>
                                         <col>
                                         <col>
                                     </colgroup>
                                     <tbody>
                                         <tr>
                                             <td class="label">나의 포인트</td>
                                             <td class="value"><em><?= number_format($mem_info["mm_point"]) ?></em>P</td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </div>
                             <div class="form-toggle-group card-group">
                                 <?php foreach ($point_list as $row) : ?>
                                     <label class="form-toggle-btn card">
                                         <input type="radio" name="point" data-point_key="<?= ($row["cd_key"]) ?>" value="<?= ($row["cd_val"]) ?>">
                                         <span class="card-body">
                                             <span class="ico"></span>
                                             <span class="point"><em><?= number_format($row["cd_val2"]) ?></em>P</span>
                                             <?php if ($row["cd_val2"] != $row["cd_val"]) : ?>
                                                 <span class="discount"><?= (round((($row["cd_val2"] - $row["cd_val"]) / $row["cd_val2"]) * 100) - 0) ?>%할인</span>
                                             <?php endif; ?>
                                         </span>
                                         <span class="card-footer">￦<em><?= number_format($row["cd_val"]) ?></em></span>
                                     </label>
                                 <?php endforeach; ?>

                             </div>
                             <div class="sticky-bottom">
                                 <button id="btnNext" type="button" class="btn btn-primary">충전하기</button>
                             </div>
                         </form>
                     </div>
             </div>
         </div>
     </div><!-- //page-content -->
 </div>

 <script>
     window.addEventListener('DOMContentLoaded', function() {
         var os_type = "<?= $os_type ?>";
         $("#btnNext").click(function() {
             if ($("[name=point]").is(":checked") == false) {
                 commonAlert("결제하실 포인트를 선택해주세요.", function() {});
                 return;
             }

             var params = {};
             params["point_key"] = $("[name=point]:checked").attr("data-point_key");
             params["point_price"] = $("[name=point]:checked").val();
             comAjax("/mypage_proc/point_charge_step1", params, function(ret) {
                 if (ret.code == "00") {
                     showAjaxLoading();
                     if (os_type == "ios") {
                         //window.webkit.messageHandlers.inAppRequest.postMessage("NOWJOB_PC_" + params["point_price"]);
                         _replace("/mypage/point_charge_step2");
                     } else {
                         _replace("/mypage/point_charge_step2");
                     }
                 } else {
                     hideAjaxLoading();
                     commonAlert(ret.msg, function() {});
                 }
             });
         });
     });

     function returnPurchase(code, msg) {
         hideAjaxLoading();
         if (code == "90") {
             commonAlert("결제진행시 오류가 발생하였습니다. 관리자에 문의 부탁드립니다.[오류메시지:" + msg + "]", function() {});
         } else if (code == "10") {
             commonAlert("결제를진행을 취소 하였습니다. 다시 진행해주세요.", function() {
                 location.reload();
             });
         } else if (code == "00") {
             var params = {};
             params["pay_method"] = "IOS";
             params["o_pg_name"] = "IOS";
             params["coupon_idx"] = "";
             params["point_key"] = $("[name=point]:checked").attr("data-point_key");
             comAjax("/order_proc/order_insert_proc", params, function(ret) {
                 if (ret.code == "00") {
                     _replace("/order_proc/order_online_proc/" + ret.data.o_num);
                     return;
                 } else {
                     commonAlert(ret.msg, function() {});
                 }
             });
         }
     }
 </script>