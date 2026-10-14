<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab1"; ?>
      <?php $tab_on2 = "menu4"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="purchage-promo">
          <h2 class="sr-text">광고구매</h3>
            <ul class="promo-list">
              <li style="width:calc(33% - 30px);">
                <div class="promo-card" style="padding: 0px; border: 3px solid #57CCAF; border-radius: 10px;">
                  <div class="card-body" style="padding: 40px 54px; border-radius: 10px;">
                    <p class="title"><strong>추천인재등록</strong></p>
                    <p class="title" style="margin: 5px 0 20px 0;font-size: 16px; height: 48px;">* 인재정보 상단 추천인재란에 노출됩니다.</p>
                    <p class="required-point">차감포인트 : <em>2일 / <?= number_format($point1["cd_val3"]) ?></em>P</p>
                    <p class="required-point">* 최소 2일 이상 신청 가능</p>
                  </div>
                  <div class="card-footer" style="top: 146px; right: 50px; width: auto; height: auto;">
                    <a href="javascript:$('.adinfo1').css('padding-top','500px');$('.adinfo1').show();$('.adinfo1').animate({paddingTop: '0px'},600); $('.adinfo2').hide();$('.adinfo3').hide();" class="btn-round btn-tertiary" style="width: 80px; height: 40px; line-height: 2;">구매</a>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo1Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">추천인재등록</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point1["cd_val3"] ?></em>P</p>

                        <p class="desc" style="display:none;">포인트는 500P가 차감되며,<br> 지정한 등록지역, 공고, 등록시작에 7일간 추천인재로 표시됩니다.</p>

                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">*광고지역:<span id="ad_area_pop"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop1"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop1"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="M001" data-point="<?= $point1["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
              <li style="width:calc(33% - 30px);">
                <div class="promo-card" style="padding: 0px; border: 3px solid #57CCAF; border-radius: 10px;">
                  <div class="card-body" style="padding: 40px 54px; border-radius: 10px;">
                    <p class="title"><strong>점프업(4시간)</strong></p>
                    <p class="title" style="margin: 5px 0 20px 0; font-size: 16px;">* 인재정보 이력서를 4시간마다 인재정보 리스트 상위에 노출시킵니다.</p>
                    <p class="required-point">차감포인트 : <em>2일 / <?= number_format($point2["cd_val3"]) ?></em>P</p>
                    <p class="required-point">* 최소 2일 이상 신청 가능</p>
                  </div>
                  <div class="card-footer" style="top: 146px; right: 50px; width: auto; height: auto;">
                    <a href="javascript:$('.adinfo2').css('padding-top','500px');$('.adinfo2').show();$('.adinfo2').animate({paddingTop: '0px'},600); $('.adinfo1').hide();$('.adinfo3').hide();" class="btn-round btn-tertiary" style="width: 80px; height: 40px; line-height: 2;">구매</a>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo2Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">점프업(4시간)</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point2["cd_val3"] ?></em>P</p>


                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr2" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">*광고지역:<span id="ad_area_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="M002" data-point="<?= $point2["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
              <li style="width:calc(33% - 30px);">
                <div class="promo-card" style="padding: 0px; border: 3px solid #57CCAF; border-radius: 10px;">
                  <div class="card-body" style="padding: 40px 54px; border-radius: 10px;">
                    <p class="title"><strong>점프업(8시간)</strong></p>
                    <p class="title" style="margin: 5px 0 20px 0; font-size: 16px;">* 인재정보 이력서를 8시간마다 인재정보 리스트 상위에 노출시킵니다.</p>
                    <p class="required-point">차감포인트 : <em>2일 / <?= number_format($point3["cd_val3"]) ?></em>P</p>
                    <p class="required-point">* 최소 2일 이상 신청 가능</p>
                  </div>
                  <div class="card-footer" style="top: 146px; right: 50px; width: auto; height: auto;">
                    <a href="javascript:$('.adinfo3').css('padding-top','500px');$('.adinfo3').show();$('.adinfo3').animate({paddingTop: '0px'},600); $('.adinfo1').hide();$('.adinfo2').hide();" class="btn-round btn-tertiary" style="width: 80px; height: 40px; line-height: 2;">구매</a>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo3Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">점프업(8시간)</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point3["cd_val3"] ?></em>P</p>


                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr2" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">*광고지역:<span id="ad_area_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="M003" data-point="<?= $point3["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
            </ul>
            <p class="help-msg" style="display:none;">메인배너 광고건은 나우잡 고객센터 032-833-0018에 문의바랍니다. (별도협의필요)</p>
            <div class="adinfo1" style="display:none; margin-top: 50px; border-top: 3px solid #57CCAF;">
              <div class="adboxtitle" style="background-color: #ededed; padding: 10px; margin: 20px auto; width: 400px; border-radius: 15px;">
                <div style="width: 140px; font-size: 20px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  추천인재등록
                </div>
                <div style="width: 240px; font-size: 18px; display: block; margin: 20px auto; text-align: center; font-weight: 600;">인재정보 상단 추천인재에 노출</div>
                <div style="width: 240px; font-size: 19px; display: block; margin: 20px auto; text-align: center; font-weight: 600; color: #57ccaf;">"가장 광고 효과가 좋은 위치"</div>
              </div>
              <div class="adbox1" style="display: inline-block; width: 33%; float: left; margin-bottom: 50px; background-color: #ededed; border-radius: 15px 0 0 15px; height: 680px;">
                <div style="width: 140px; font-size: 20px; display: block; margin: 18px auto 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  광고노출위치
                </div>
                <div style="display: block; width: 100%; text-align: center;"><img style="width: 410px;" src="/resource/shop/web/img/ad_img1.png"></div>
              </div>
              <div class="adbox2" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px;">
                <div style="width: 130px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  추천인재등록
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">이력서 선택</span>
                  <select id="ad_resume1" style="display: block; width: 100%; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px;">
                    <?php
                    foreach ($resume_list as $resume) {
                      echo "<option value=\"" . $resume['ru_idx'] . "\">" . $resume['ru_name'] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div style="position:relative; display: none; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고지역</span>
                  <input id="ad_addr1" name="ad_addr1" style="display:block; width: 300px; height: 36px; margin-top: 10px; border:1px solid #57ccaf;" onclick="$('#changeArea').addClass('on');">
                  <button type="button" id="btnTopSearch1" class="btn-search" onclick="$('#changeArea').addClass('on');"
                    style="position: absolute;top: 49px;right: 20px;width: 36px;height: 36px;border: 0;border-radius: 25px;background: #57CCAF url(/resource/shop/web/img/ic_search.svg) no-repeat;background-size: 15px auto;background-position: center;">
                  </button>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고시작일</span><br>
                  <input id="ad_startdate1" name="ad_startdate1" class="form-text js-datepicker" style="display: inline-block; width: 130px; height: 36px; margin-top: 10px; border: 1px solid #57ccaf; text-align: left; background-color: #fff; background-size: 10px auto; padding: 0 0 0 16px; background-position: 112px 14px;" value="2024-05-23" onchange="fMakeDuration();"><span style="display: inline-block; width: 30px; text-align: center;">일</span>
                  <select id="ad_starthour1" name="ad_starthour1" style="display: inline-block; width: 50px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px; text-align:center;">
                    <?php
                    for ($h = 0; $h <= 23; $h++) {
                      echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                    }
                    ?>
                  </select><span style="display: inline-block; width: 30px; text-align: center;">시</span>
                  <input id="ad_startmin1" name="ad_startmin1" style="display:inline-block; width: 50px; height: 36px; margin-top: 10px; border:1px solid #57ccaf; text-align:center;" value="0"><span style="display: inline-block; width: 30px; text-align: center;">분</span>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고기간</span><br>
                  <select id="ad_duration1" name="ad_duration1" style="display: inline-block; width: 60px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px; margin-right: 15px;" onchange="fMakeDuration();">
                    <option value="2">2일</option>
                    <option value="5">5일</option>
                    <option value="10">10일</option>
                    <option value="20">20일</option>
                    <option value="30">30일</option>
                  </select>
                  <span id="ad_duration_txt1"></span>
                </div>
              </div>
              <div class="adbox3" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px; border-radius: 0 15px 15px 0;">
                <div style="width: 130px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px; text-align: center;">
                  추가혜택
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; text-align: center;">
                  <style>
                    .adinfo_tab tr td {
                      padding: 10px;
                    }

                    .adinfo_tab tr td span {
                      color: #fff;
                      background-color: #57ccaf;
                      border-radius: 10px;
                      width: 75px;
                      display: block;
                      margin: 0 auto;
                      height: 26px;
                      line-height: 26px;
                    }

                    .adinfo_tab tr td.adusepoint {
                      color: #57ccaf;
                    }
                  </style>
                  <table class="adinfo_tab">
                    <tr style="border-top: 1px solid #57ccaf; border-bottom: 1px solid #57ccaf;">
                      <td>광고기간</td>
                      <td>추가혜택</td>
                      <td>차감포인트</td>
                    </tr>
                    <tr>
                      <td><span>2일</span></td>
                      <td>+1일</td>
                      <td class="adusepoint">-2,000</td>
                    </tr>
                    <tr>
                      <td><span>5일</span></td>
                      <td>+2일</td>
                      <td class="adusepoint">-5,000</td>
                    </tr>
                    <tr>
                      <td><span>10일</span></td>
                      <td>+5일</td>
                      <td class="adusepoint">-10,000</td>
                    </tr>
                    <tr>
                      <td><span>20일</span></td>
                      <td>+10일</td>
                      <td class="adusepoint">-20,000</td>
                    </tr>
                    <tr>
                      <td><span>30일</span></td>
                      <td>+15일</td>
                      <td class="adusepoint">-30,000</td>
                    </tr>
                  </table>
                </div>
                <div style="display: block; width: 400px; height: 80px; background-color: #57ccaf; margin: 20px auto; color: #fff; text-align: right; padding: 20px;">
                  <div><span style="margin-right: 10px;">보유포인트</span><span><?= number_format($mem_info["mm_point"]) ?></span></div>
                  <div style="font-size: 18px;"><span style="margin-right: 10px;">총 결제포인트</span><span id="usepoint1">0</span></div>
                </div>
                <div style="display: block; width: 440px; height: 80px; margin: 20px auto; text-align: right; padding: 20px;">
                  <a href="/mypage/point_charge_step1" style="color: #57ccaf; border: 1px solid #57ccaf; padding: 10px 20px; border-radius: 10px; margin-right: 20px;">충전하기</a>
                  <a href="#promo1" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo" style="background-color: #57ccaf; color: #fff; padding: 10px 20px; border-radius: 10px;">구매</a>
                </div>
              </div>
            </div><!-- // adinfo1 -->

            <div class="adinfo2" style="display:none; margin-top: 50px; border-top: 3px solid #57CCAF;">
              <div class="adboxtitle" style="background-color: #ededed; padding: 10px; margin: 20px auto; width: 400px; border-radius: 15px;">
                <div style="width: 150px; font-size: 20px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  점프업(4시간)
                </div>
                <div style="width: 240px; font-size: 18px; display: block; margin: 20px auto; text-align: center; font-weight: 600;">인재정보 상단 리스트에 노출</div>
                <div style="width: 240px; font-size: 19px; display: block; margin: 20px auto; text-align: center; font-weight: 600; color: #57ccaf;">"이력서 리스트 상단으로 점프업!"</div>
              </div>
              <div class="adbox1" style="display: inline-block; width: 33%; float: left; margin-bottom: 50px; background-color: #ededed; border-radius: 15px 0 0 15px; height: 680px;">
                <div style="width: 140px; font-size: 20px; display: block; margin: 18px auto 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  광고노출위치
                </div>
                <div style="display: block; width: 100%; text-align: center;"><img style="width: 410px;" src="/resource/shop/web/img/ad_img2.png"></div>
              </div>
              <div class="adbox2" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px;">
                <div style="width: 150px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  점프업(4시간)
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">이력서 선택</span>
                  <select id="ad_resume2" style="display: block; width: 100%; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px;">
                    <?php
                    foreach ($resume_list as $resume) {
                      echo "<option value=\"" . $resume['ru_idx'] . "\">" . $resume['ru_name'] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div style="position:relative; display: none; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고지역</span>
                  <input id="ad_addr2" name="ad_addr2" style="display:block; width: 300px; height: 36px; margin-top: 10px; border:1px solid #57ccaf;" onclick="$('#changeArea').addClass('on');">
                  <button type="button" id="btnTopSearch2" class="btn-search" onclick="$('#changeArea').addClass('on');"
                    style="position: absolute;top: 49px;right: 20px;width: 36px;height: 36px;border: 0;border-radius: 25px;background: #57CCAF url(/resource/shop/web/img/ic_search.svg) no-repeat;background-size: 15px auto;background-position: center;">
                  </button>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고시작일</span><br>
                  <input id="ad_startdate2" name="ad_startdate2" class="form-text js-datepicker" style="display: inline-block; width: 130px; height: 36px; margin-top: 10px; border: 1px solid #57ccaf; text-align: left; background-color: #fff; background-size: 10px auto; padding: 0 0 0 16px; background-position: 112px 14px;" value="2024-05-23" onchange="fMakeDuration();"><span style="display: inline-block; width: 30px; text-align: center;">일</span>
                  <select id="ad_starthour2" name="ad_starthour2" style="display: inline-block; width: 50px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px; text-align:center;">
                    <?php
                    for ($h = 0; $h <= 23; $h++) {
                      echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                    }
                    ?>
                  </select><span style="display: inline-block; width: 30px; text-align: center;">시</span>
                  <input id="ad_startmin2" name="ad_startmin2" style="display:inline-block; width: 50px; height: 36px; margin-top: 10px; border:1px solid #57ccaf; text-align:center;" value="0"><span style="display: inline-block; width: 30px; text-align: center;">분</span>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고기간</span><br>
                  <select id="ad_duration2" name="ad_duration2" style="display: inline-block; width: 60px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px;margin-right: 15px;" onchange="fMakeDuration2();">
                    <option value="2">2일</option>
                    <option value="5">5일</option>
                    <option value="10">10일</option>
                    <option value="20">20일</option>
                    <option value="30">30일</option>
                  </select>
                  <span id="ad_duration_txt2"></span>
                </div>
              </div>
              <div class="adbox3" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px; border-radius: 0 15px 15px 0;">
                <div style="width: 130px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px; text-align: center;">
                  추가혜택
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; text-align: center;">
                  <style>
                    .adinfo_tab tr td {
                      padding: 10px;
                    }

                    .adinfo_tab tr td span {
                      color: #fff;
                      background-color: #57ccaf;
                      border-radius: 10px;
                      width: 75px;
                      display: block;
                      margin: 0 auto;
                      height: 26px;
                      line-height: 26px;
                    }

                    .adinfo_tab tr td.adusepoint {
                      color: #57ccaf;
                    }
                  </style>
                  <table class="adinfo_tab">
                    <tr style="border-top: 1px solid #57ccaf; border-bottom: 1px solid #57ccaf;">
                      <td>광고기간</td>
                      <td>추가혜택</td>
                      <td>차감포인트</td>
                    </tr>
                    <tr>
                      <td><span>2일</span></td>
                      <td>+1일</td>
                      <td class="adusepoint">-1,000</td>
                    </tr>
                    <tr>
                      <td><span>5일</span></td>
                      <td>+2일</td>
                      <td class="adusepoint">-2,500</td>
                    </tr>
                    <tr>
                      <td><span>10일</span></td>
                      <td>+5일</td>
                      <td class="adusepoint">-5,000</td>
                    </tr>
                    <tr>
                      <td><span>20일</span></td>
                      <td>+10일</td>
                      <td class="adusepoint">-10,000</td>
                    </tr>
                    <tr>
                      <td><span>30일</span></td>
                      <td>+15일</td>
                      <td class="adusepoint">-15,000</td>
                    </tr>
                  </table>
                </div>
                <div style="display: block; width: 400px; height: 80px; background-color: #57ccaf; margin: 20px auto; color: #fff; text-align: right; padding: 20px;">
                  <div><span style="margin-right: 10px;">보유포인트</span><span><?= number_format($mem_info["mm_point"]) ?></span></div>
                  <div style="font-size: 18px;"><span style="margin-right: 10px;">총 결제포인트</span><span id="usepoint2">0</span></div>
                </div>
                <div style="display: block; width: 440px; height: 80px; margin: 20px auto; text-align: right; padding: 20px;">
                  <a href="/mypage/point_charge_step1" style="color: #57ccaf; border: 1px solid #57ccaf; padding: 10px 20px; border-radius: 10px; margin-right: 20px;">충전하기</a>
                  <a href="#promo2" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo" style="background-color: #57ccaf; color: #fff; padding: 10px 20px; border-radius: 10px;">구매</a>
                </div>
              </div>
            </div><!-- // adinfo2 -->

            <div class="adinfo3" style="display:none; margin-top: 50px; border-top: 3px solid #57CCAF;">
              <div class="adboxtitle" style="background-color: #ededed; padding: 10px; margin: 20px auto; width: 400px; border-radius: 15px;">
                <div style="width: 150px; font-size: 20px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  점프업(8시간)
                </div>
                <div style="width: 240px; font-size: 18px; display: block; margin: 20px auto; text-align: center; font-weight: 600;">인재정보 상단 리스트에 노출</div>
                <div style="width: 240px; font-size: 19px; display: block; margin: 20px auto; text-align: center; font-weight: 600; color: #57ccaf;">"이력서 리스트 상단으로 점프업!"</div>
              </div>
              <div class="adbox1" style="display: inline-block; width: 33%; float: left; margin-bottom: 50px; background-color: #ededed; border-radius: 15px 0 0 15px; height: 680px;">
                <div style="width: 140px; font-size: 20px; display: block; margin: 18px auto 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  광고노출위치
                </div>
                <div style="display: block; width: 100%; text-align: center;"><img style="width: 410px;" src="/resource/shop/web/img/ad_img2.png"></div>
              </div>
              <div class="adbox2" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px;">
                <div style="width: 150px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px;text-align:center;">
                  점프업(8시간)
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">이력서 선택</span>
                  <select id="ad_resume3" style="display: block; width: 100%; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px;">
                    <?php
                    foreach ($resume_list as $resume) {
                      echo "<option value=\"" . $resume['ru_idx'] . "\">" . $resume['ru_name'] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div style="position:relative; display: none; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고지역</span>
                  <input id="ad_addr3" name="ad_addr3" style="display:block; width: 300px; height: 36px; margin-top: 10px; border:1px solid #57ccaf;" onclick="$('#changeArea').addClass('on');">
                  <button type="button" id="btnTopSearch3" class="btn-search" onclick="$('#changeArea').addClass('on');"
                    style="position: absolute;top: 49px;right: 20px;width: 36px;height: 36px;border: 0;border-radius: 25px;background: #57CCAF url(/resource/shop/web/img/ic_search.svg) no-repeat;background-size: 15px auto;background-position: center;">
                  </button>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고시작일</span><br>
                  <input id="ad_startdate3" name="ad_startdate3" class="form-text js-datepicker" style="display: inline-block; width: 130px; height: 36px; margin-top: 10px; border: 1px solid #57ccaf; text-align: left; background-color: #fff; background-size: 10px auto; padding: 0 0 0 16px; background-position: 112px 14px;" value="2024-05-23" onchange="fMakeDuration();"><span style="display: inline-block; width: 30px; text-align: center;">일</span>
                  <select id="ad_starthour3" name="ad_starthour3" style="display: inline-block; width: 50px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px; text-align:center;">
                    <?php
                    for ($h = 0; $h <= 23; $h++) {
                      echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                    }
                    ?>
                  </select><span style="display: inline-block; width: 30px; text-align: center;">시</span>
                  <input id="ad_startmin3" name="ad_startmin3" style="display:inline-block; width: 50px; height: 36px; margin-top: 10px; border:1px solid #57ccaf; text-align:center;" value="0"><span style="display: inline-block; width: 30px; text-align: center;">분</span>
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; border: 2px solid #57ccaf; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                  <span style="color: #57ccaf;">광고기간</span><br>
                  <select id="ad_duration3" name="ad_duration3" style="display: inline-block; width: 60px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 10px;margin-right: 15px;" onchange="fMakeDuration3();">
                    <option value="2">2일</option>
                    <option value="5">5일</option>
                    <option value="10">10일</option>
                    <option value="20">20일</option>
                    <option value="30">30일</option>
                  </select>
                  <span id="ad_duration_txt3"></span>
                </div>
              </div>
              <div class="adbox3" style="display: inline-block; width: 33%; float: left; background-color: #ededed; height: 680px; border-radius: 0 15px 15px 0;">

                <div style="width: 130px; font-size: 18px; display: block; margin: 20px auto; color: #fff; background-color: #57ccaf; padding: 10px 20px; border-radius: 10px; text-align: center;">
                  추가혜택
                </div>
                <div style="display: block; width: 400px; margin: 0 auto; text-align: center;">
                  <style>
                    .adinfo_tab tr td {
                      padding: 10px;
                    }

                    .adinfo_tab tr td span {
                      color: #fff;
                      background-color: #57ccaf;
                      border-radius: 10px;
                      width: 75px;
                      display: block;
                      margin: 0 auto;
                      height: 26px;
                      line-height: 26px;
                    }

                    .adinfo_tab tr td.adusepoint {
                      color: #57ccaf;
                    }
                  </style>
                  <table class="adinfo_tab">
                    <tr style="border-top: 1px solid #57ccaf; border-bottom: 1px solid #57ccaf;">
                      <td>광고기간</td>
                      <td>추가혜택</td>
                      <td>차감포인트</td>
                    </tr>
                    <tr>
                      <td><span>2일</span></td>
                      <td>+1일</td>
                      <td class="adusepoint">-800</td>
                    </tr>
                    <tr>
                      <td><span>5일</span></td>
                      <td>+2일</td>
                      <td class="adusepoint">-2,000</td>
                    </tr>
                    <tr>
                      <td><span>10일</span></td>
                      <td>+5일</td>
                      <td class="adusepoint">-4,000</td>
                    </tr>
                    <tr>
                      <td><span>20일</span></td>
                      <td>+10일</td>
                      <td class="adusepoint">-8,000</td>
                    </tr>
                    <tr>
                      <td><span>30일</span></td>
                      <td>+15일</td>
                      <td class="adusepoint">-12,000</td>
                    </tr>
                  </table>
                </div>
                <div style="display: block; width: 400px; height: 80px; background-color: #57ccaf; margin: 20px auto; color: #fff; text-align: right; padding: 20px;">
                  <div><span style="margin-right: 10px;">보유포인트</span><span><?= number_format($mem_info["mm_point"]) ?></span></div>
                  <div style="font-size: 18px;"><span style="margin-right: 10px;">총 결제포인트</span><span id="usepoint3">0</span></div>
                </div>
                <div style="display: block; width: 440px; height: 80px; margin: 20px auto; text-align: right; padding: 20px;">
                  <a href="/mypage/point_charge_step1" style="color: #57ccaf; border: 1px solid #57ccaf; padding: 10px 20px; border-radius: 10px; margin-right: 20px;">충전하기</a>
                  <a href="#promo3" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo" style="background-color: #57ccaf; color: #fff; padding: 10px 20px; border-radius: 10px;">구매</a>
                </div>
              </div>
            </div><!-- // adinfo3 -->

        </div>

      </div>

    </div>

  </main><!-- //page-content -->
</div><!-- //page -->


<input type="hidden" name="mm_point" value="<?= $mem_info["mm_point"] ?>" />

<script>
  $(document).ready(function() {
    $(document).on("click", ".btnBuy", function() {
      var code = $(this).attr("data-code");
      var point = $(this).attr("data-point");
      var mm_point = $("[name=mm_point]").val();
      var sdate = "";
      var edate = "";
      var addr = $("[name=ad_addr1]").val();
      var addr2 = $("[name=ad_addr2]").val();
      var addr3 = $("[name=ad_addr3]").val();

      var sdate = $("[name=ad_startdate1]").val();
      var sdate2 = $("[name=ad_startdate2]").val();
      var sdate3 = $("[name=ad_startdate3]").val();

      var hour = document.getElementById('ad_starthour1').value;
      var minute = document.getElementById('ad_startmin1').value;
      var hour2 = document.getElementById('ad_starthour2').value;
      var minute2 = document.getElementById('ad_startmin2').value;
      var hour3 = document.getElementById('ad_starthour3').value;
      var minute3 = document.getElementById('ad_startmin3').value;

      // 두 자리 숫자 형식으로 변환
      var formattedHour = padNumber(hour);
      var formattedMinute = padNumber(minute);
      var formattedHour2 = padNumber(hour2);
      var formattedMinute2 = padNumber(minute2);
      var formattedHour3 = padNumber(hour3);
      var formattedMinute3 = padNumber(minute3);

      var params = {};
      params["pm_code"] = code;
      if (code == "M001") {
        params["pm_sdate"] = sdate + ' ' + formattedHour + ':' + formattedMinute;
        params["pm_resume"] = $('#ad_resume1').val();
        params["pm_duration"] = $('#ad_duration1 option:selected').val();
        params["pm_addr"] = addr;
      }
      if (code == "M002") {
        params["pm_sdate"] = sdate2 + ' ' + formattedHour2 + ':' + formattedMinute2;
        params["pm_resume"] = $('#ad_resume2').val();
        params["pm_duration"] = $('#ad_duration2 option:selected').val();
        params["pm_addr"] = addr2;
      }
      if (code == "M003") {
        params["pm_sdate"] = sdate3 + ' ' + formattedHour3 + ':' + formattedMinute3;
        params["pm_resume"] = $('#ad_resume3').val();
        params["pm_duration"] = $('#ad_duration3 option:selected').val();
        params["pm_addr"] = addr3;
      }

      comAjax("/mypage_proc/point_promotion", params, function(ret) {
        if (ret.code == "00") {
          location.href = "/mypage/point_promotion_list"
        } else {
          commonAlert(ret.msg, function() {});
        }
      });

      return;
      /*
		if(parseInt(point) > parseInt(mm_point)){
			commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(),function(){});
			return;
		}

    if(code=="M001"){
        	sdate = $("[name=pm_sdate]").val();
    			edate = getDateAdd(sdate,6,'d');

        	if($("[name=pm_addr]").val()==""){
        		commonAlert("등록지역을 선택해주세요.",function(){
        			$("[name=pm_addr]").focus();
        		});
        		return;
        	}

        	if($("[name=pm_sdate]").val()==""){
        		commonAlert("등록 시작일을 선택해주세요.",function(){
        			$("[name=pm_sdate]").focus();
        		});
        		return;
        	}

        	confirm_txt = '<p><span>“'+addr+'”</span> <em>추천인재</em>로<br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(7일간)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '<li>등록지역 : <span>'+addr+'</span></li>'+
            '</ul>';
    	}else if(code=="M002"){
    		sdate = getToday("y-m-d");
			edate = getDateAdd(sdate,1,'d');
    		confirm_txt = '<p><span>점프업</span><br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(1일간)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '</ul>';
    	}

        openPopup('confirm', {
            content: confirm_txt,
            secondCallback: function () {
                var params = {};
		        params["pm_code"] = code;
		        params["pm_sdate"] = sdate;
		        params["pm_addr"] = addr;

		        comAjax("/mypage_proc/point_promotion",params,function(ret){
		            if(ret.code=="00"){
		                location.href="/mypage/point_promotion_list"
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });
    */
    });

    // 0~59분만 입력되도록
    $('[name^="ad_startmin1"], [name^="ad_startmin2"], [name^="ad_startmin3"]').on('input', function() {
      // 숫자만 입력되도록 처리
      $(this).val($(this).val().replace(/[^0-9]/g, ''));

      // 입력된 값이 0에서 59 범위를 벗어난 경우 조정
      let value = parseInt($(this).val(), 10);
      if (!isNaN(value)) {
        if (value < 0) {
          $(this).val(0);
        } else if (value > 59) {
          $(this).val(59);
        }
      }
    });

  }); //ready

  // 검색 완료 선택 처리
  function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;
    console.log(addr);
    $('#ad_addr1').val(addr);
    $('#ad_addr2').val(addr);
    $('#ad_addr3').val(addr);
    $('#changeArea').removeClass('on');
  }
</script>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>

<script>
  //구매 내용 팝업
  (function() {

    //달력 제어
    function chgMonth(instance) {
      var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
        month = Number(instance.currentMonth) + 1,
        year = instance.currentYear;
      $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
    }

    function initDatepicker() {
      if (typeof flatpickr == 'undefined') return false;

      flatpickr('.js-datepicker', {
        locale: 'ko',
        dateFormat: 'Y-m-d',
        ariaDateFormat: 'Y-m-d',
        altFormat: 'Y.m.d',
        altInput: true,
        position: 'auto right',
        nextArrow: '<span class="sr-text">다음</span>',
        prevArrow: '<span class="sr-text">이전</span>',
        disableMobile: true,
        position: 'above center',
        defaultDate: new Date(),
        minDate: 'today', // 오늘 날짜 이전은 선택할 수 없도록 설정
        onMonthChange: function(selectedDates, dateStr, instance) {
          chgMonth(instance);
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          var day = new Date(dayElem.getAttribute('aria-label')).getDay();
          if (day == 0) {
            dayElem.classList.add('sun');
          } else if (day == 6) {
            dayElem.classList.add('sat');
          }
        },
        onReady: function() {
          chgMonth(this);
          //console.log($('.form-control'));
          $('.form-control').css('display', 'inline-block');
          $('.form-control').css('width', '130px');
          $('.form-control').css('height', '36px');
          $('.form-control').css('margin-top', '10px');
          $('.form-control').css('border', '1px solid #57ccaf');
          $('.form-control').css('text-align', 'left');
          $('.form-control').css('background-color', '#fff');
          $('.form-control').css('background-size', '10px auto');
          $('.form-control').css('padding', '0 0 0 16px');
          $('.form-control').css('background-position', '112px 14px');

          fMakeDuration();
          fMakeDuration2();
          fMakeDuration3();
        }
      });
    }

    //프로모 구매 안내 팝업
    window.addEventListener('DOMContentLoaded', function(e) {
      var viewBtns = document.querySelectorAll('.js-view-promo');

      if (!viewBtns) return;

      initDatepicker();

      viewBtns.forEach(function(btn) {

        btn.addEventListener('click', function(e) {
          e.preventDefault();

          var id = e.target.getAttribute('href');
          var content = document.querySelector(id + 'Temp') ? document.querySelector(id + 'Temp').innerHTML : '';
          console.log(content);

          var adNo = id.replace('#promo', '');
          content = content.replace('<span id="ad_area_pop' + adNo + '"></span>', ' <span id="ad_area_pop' + adNo + '">' + $('#ad_addr' + adNo).val() + '</span>');

          var point = 0;
          var pointTxt = 0;
          if (adNo == '1') {
            if ($('#ad_duration1 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 2000;
              pointTxt = '2,000';
            } else if ($('#ad_duration1 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">5일 결제(+2일)</span>' + '</span>');
              point = 5000;
              pointTxt = '5,000';
            } else if ($('#ad_duration1 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">10일 결제(+5일)</span>' + '</span>');
              point = 10000;
              pointTxt = '10,000';
            } else if ($('#ad_duration1 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">20일 결제(+10일)</span>' + '</span>');
              point = 20000;
              pointTxt = '20,000';
            } else if ($('#ad_duration1 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">30일 결제(+15일)</span>' + '</span>');
              point = 30000;
              pointTxt = '30,000';
            }
          } else if (adNo == '2') {
            if ($('#ad_duration2 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 1000;
              pointTxt = '1,000';
            } else if ($('#ad_duration2 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">5일 결제(+2일)</span>' + '</span>');
              point = 2500;
              pointTxt = '2,500';
            } else if ($('#ad_duration2 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">10일 결제(+5일)</span>' + '</span>');
              point = 50000;
              pointTxt = '5,000';
            } else if ($('#ad_duration2 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">20일 결제(+10일)</span>' + '</span>');
              point = 10000;
              pointTxt = '10,000';
            } else if ($('#ad_duration2 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">30일 결제(+15일)</span>' + '</span>');
              point = 15000;
              pointTxt = '15,000';
            }
          } else if (adNo == '3') {
            if ($('#ad_duration3 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 800;
              pointTxt = '800';
            } else if ($('#ad_duration3 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">5일 결제(+2일)</span>' + '</span>');
              point = 2000;
              pointTxt = '2,000';
            } else if ($('#ad_duration3 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">10일 결제(+5일)</span>' + '</span>');
              point = 40000;
              pointTxt = '4,000';
            } else if ($('#ad_duration3 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">20일 결제(+10일)</span>' + '</span>');
              point = 8000;
              pointTxt = '8,000';
            } else if ($('#ad_duration3 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt' + adNo).html() + '<br><span style="margin-left:73px;">30일 결제(+15일)</span>' + '</span>');
              point = 12000;
              pointTxt = '12,000';
            }
          }
          content = content.replace('<span id="ad_point_pop' + adNo + '"></span>', ' <span id="ad_point_pop' + adNo + '">' + pointTxt + 'P</span>');

          if ($('#ad_resume' + adNo + '').val() == '') {
            commonAlert("광고하실 이력서를 선택하세요", function() {});
            return;
          }

          if ($('#ad_addr' + adNo + '').val() == '') {
            //commonAlert("광고하실 지역을 선택하세요",function(){});
            //return;
          }

          var hour = document.getElementById('ad_starthour' + adNo + '').value;
          var minute = document.getElementById('ad_startmin' + adNo + '').value;

          // 두 자리 숫자 형식으로 변환
          var formattedHour = padNumber(hour);
          var formattedMinute = padNumber(minute);
          if (formattedHour < 00 || formattedHour > 23) {
            commonAlert("광고시작 시간을 0~23시로 선택해주세요", function() {});
            return;
          }
          if (formattedMinute < 00 || formattedMinute > 59) {
            commonAlert("광고시작 시간을 0~59분으로 입력해주세요", function() {});
            return;
          }

          var mm_point = $("[name=mm_point]").val();
          if (parseInt(point) > parseInt(mm_point)) {
            commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(), function() {});
            return;
          }

          if (!id) return;

          openPopup('modal', {
            id: id + 'Dialog',
            title: '광고구매',
            content: content,
          });

          /*
          $('#ad_area_pop').html($('#ad_addr').val());
          if($('#ad_duration option:selected').val()=='2'){
            $('#ad_duration_pop').html($('#ad_duration_txt').html()+'<br><span style="margin-left:69px;">2일 결제(+1일)</span>');
            $('#ad_point_pop').html('2,000P');
          }else if($('#ad_duration option:selected').val()=='5'){
            $('#ad_duration_pop').html($('#ad_duration_txt').html()+'<br><span style="margin-left:69px;">5일 결제(+2일)</span>');
            $('#ad_point_pop').html('5,000P');
          }else if($('#ad_duration option:selected').val()=='10'){
            $('#ad_duration_pop').html($('#ad_duration_txt').html()+'<br><span style="margin-left:69px;">10일 결제(+5일)</span>');
            $('#ad_point_pop').html('10,000P');
          }else if($('#ad_duration option:selected').val()=='20'){
            $('#ad_duration_pop').html($('#ad_duration_txt').html()+'<br><span style="margin-left:69px;">20일 결제(+10일)</span>');
            $('#ad_point_pop').html('20,000P');
          }else if($('#ad_duration option:selected').val()=='30'){
            $('#ad_duration_pop').html($('#ad_duration_txt').html()+'<br><span style="margin-left:69px;">30일 결제(+15일)</span>');
            $('#ad_point_pop').html('30,000P');
          }
          */

          //달력 생성
          if (content.indexOf('js-datepicker') > -1) {
            initDatepicker();
          }

          //커스텀 목록 박스 생성
          if (content.indexOf('form-selectbox') > -1) {
            initSelectbox(document.querySelector('.layer-popup.in .form-selectbox'));
          }

          //팝업내 추가 스크립트 생성
          if (content.indexOf('script') > -1) {
            var currentEl = document.querySelector('.layer-popup.in script');
            var newEl = document.createElement('SCRIPT');

            newEl.innerHTML = currentEl.innerHTML;
            currentEl.remove();
            document.querySelector('.layer-popup.in').appendChild(newEl);
          }
        });
      });
    });

  }());

  function fMakeDuration() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate1').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration1').value);

    if (duration == 2) {
      duration += 1;
      $('#usepoint1').html('-2,000');
    } else if (duration == 5) {
      duration += 2;
      $('#usepoint1').html('-5,000');
    } else if (duration == 10) {
      duration += 5;
      $('#usepoint1').html('-10,000');
    } else if (duration == 20) {
      duration += 10;
      $('#usepoint1').html('-20,000');
    } else if (duration == 30) {
      duration += 15;
      $('#usepoint1').html('-30,000');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt1').innerHTML = result;
  }

  function fMakeDuration2() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate2').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration2').value);

    if (duration == 2) {
      duration += 1;
      $('#usepoint2').html('-1,000');
    } else if (duration == 5) {
      duration += 2;
      $('#usepoint2').html('-2,500');
    } else if (duration == 10) {
      duration += 5;
      $('#usepoint2').html('-5,000');
    } else if (duration == 20) {
      duration += 10;
      $('#usepoint2').html('-10,000');
    } else if (duration == 30) {
      duration += 15;
      $('#usepoint2').html('-15,000');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt2').innerHTML = result;
  }

  function fMakeDuration3() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate3').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration3').value);

    if (duration == 2) {
      duration += 1;
      $('#usepoint3').html('-800');
    } else if (duration == 5) {
      duration += 2;
      $('#usepoint3').html('-2,000');
    } else if (duration == 10) {
      duration += 5;
      $('#usepoint3').html('-4,000');
    } else if (duration == 20) {
      duration += 10;
      $('#usepoint3').html('-8,000');
    } else if (duration == 30) {
      duration += 15;
      $('#usepoint3').html('-12,000');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt3').innerHTML = result;
  }

  /* 추가혜택 표의 기간 행을 선택 UI로 사용: 행 클릭 시 같은 광고 블록의 기간 select 값을 맞추고 행을 강조 */
  (function () {
    $('<style>.adinfo_tab tr { cursor: pointer; } .adinfo_tab tr.on td { background-color: #e6f7f3; font-weight: 600; }</style>').appendTo('head');

    // 광고 블록 번호(1~3) 기준으로 기간 행 강조 표시
    function fSyncDurationRow(n, days) {
      $('.adinfo' + n + ' .adinfo_tab tr').removeClass('on').filter(function () {
        return parseInt($(this).find('td span').text(), 10) == days;
      }).addClass('on');
    }

    // 표의 기간 행 클릭 → select 값 변경 (인라인 onchange로 fMakeDuration 계열 호출)
    $(document).on('click', '.adinfo_tab tr', function () {
      var days = parseInt($(this).find('td span').text(), 10);
      if (isNaN(days)) return; // 헤더 행 제외

      var $block = $(this).closest('.adinfo1, .adinfo2, .adinfo3');
      var n = $block.hasClass('adinfo1') ? '1' : ($block.hasClass('adinfo2') ? '2' : '3');
      $('#ad_duration' + n).val(String(days)).trigger('change');
      fSyncDurationRow(n, days);
    });

    // select 직접 변경 시에도 표 강조 동기화
    $(document).on('change', 'select[id^="ad_duration"]', function () {
      var n = this.id.replace('ad_duration', '');
      fSyncDurationRow(n, $(this).val());
    });

    // 초기 기본값(2일) 강조
    [1, 2, 3].forEach(function (n) {
      fSyncDurationRow(n, $('#ad_duration' + n).val());
    });
  }());

  function padNumber(num) {
    return num.toString().padStart(2, '0');
  }
</script>