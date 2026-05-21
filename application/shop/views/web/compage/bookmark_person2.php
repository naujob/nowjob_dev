<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu2"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">제안받은 인재</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list">
              <?php foreach ($list as $row) : ?>
                <li class="list-item" data-ru_idx="<?= $row["ru_idx"] ?>" data-mq_idx="<?= $row["mq_idx"] ?>">
                  <div class="item-info">
                    <div class="img">
                      <img src="<?= getResumeImg($row) ?>">
                      <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row["ru_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                      <p class="star-rating">
                        <span class="star" style="width:100%;"></span>
                        <span class="sr-text">평점</span>
                        <span class="sr-text">5</span>
                      </p>
                    </div>
                    <div class="desc">
                      <div class="content-wrap">
                        <div class="inline-group"><p class="category"><?= getWorkingImg($row["ru_work"]) ?></p></div>
                        <div class="title-wrap"><p class="title"><?= $row["ru_name"] ?></p></div>
                        <div class="meta">
                          <p class="national"><?= $row["mm_nation"] ?></p>
                          <p class="age"><?= getKoreanAge($row["mm_birth"]) ?>대</p>
                        </div>
                      </div>
                      <div class="price_type_wrap" style="width:290px;">
                        <span class="type price_type" data-work-type="<?= htmlspecialchars($row["ru_work_type"], ENT_QUOTES, 'UTF-8') ?>"></span>
                        <span class="area" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"></span>
                        <span class="ru_price_type" data-price-type="<?= htmlspecialchars($row["ru_price_type"], ENT_QUOTES, 'UTF-8') ?>"></span>
                      </div>
                      <ul class="working-day">
                        <?= getWeekYoil($row["ru_week"]) ?>
                      </ul>
                    </div>
                    <div class="action-wrap">
                      <div class="form-action-wrap align-right">
                        <button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnChat"
                          data-mq_idx="<?= $row["mq_idx"] ?>"
                          data-ru_idx="<?= $row["ru_idx"] ?>"
                          data-rc_idx="<?= $row["rc_idx"] ?>"
                          data-target_id="<?= $row["target_id"] ?>"
                          data-user_id="<?= $row["user_id"] ?>"
                          data-target_img="<?= $row["target_img"] ?>"
                          data-user_img="<?= $row["user_img"] ?>"
                          data-target_name="<?= $row["target_name"] ?>"
                          data-user_name="<?= $row["user_name"] ?>"
                          data-subject="<?= $row["rc_name"] ?>">1:1채팅하기</button>
                      </div>
                    </div>
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
  var first_yn = "N";
  var first_message = {};
  var first_users = new Array();

  var g_rm_type = "recruit";
  var g_rc_idx = 0;
  var g_ru_idx = 0;
  var g_td_idx = 0;

  $(document).on("click", ".btnChat", function(e) {
    e.stopPropagation();
    first_yn = "N";
    var target_id = $(this).data("target_id");
    var user_id = $(this).data("user_id");
    var target_img = $(this).data("target_img");
    var user_img = $(this).data("user_img");
    var target_name = $(this).data("target_name");
    var user_name = $(this).data("user_name");
    var subject = $(this).data("subject");

    g_rc_idx = $(this).data("rc_idx");
    g_ru_idx = $(this).data("ru_idx");
    var mq_idx = $(this).data("mq_idx");

    if (!mq_idx || mq_idx == "") {
      console.error("mq_idx가 없습니다:", mq_idx);
      commonAlert("지원 정보를 확인할 수 없습니다.", function() {});
      return;
    }

    var checkParams = {};
    checkParams["mq_idx"] = mq_idx;
    checkParams["ru_idx"] = g_ru_idx;
    checkParams["rc_idx"] = g_rc_idx;

    console.log("지원 상태 확인 요청:", checkParams);

    comAjax("/chat_proc/check_request_status", checkParams, function(checkRet) {
      console.log("지원 상태 확인 응답:", checkRet);

      if (!checkRet) {
        console.error("지원 상태 확인 실패: 응답 없음");
        commonAlert("지원 상태를 확인할 수 없습니다.", function() {});
        return;
      }

      if (!checkRet.code) {
        console.error("지원 상태 확인 실패: 코드 없음", checkRet);
        commonAlert("지원 상태를 확인할 수 없습니다.", function() {});
        return;
      }

      if (checkRet.code != "00") {
        console.log("지원취소 감지됨, 코드:", checkRet.code, "메시지:", checkRet.msg);
        commonAlert("해당 인재가 지원을 취소했습니다.<br/>채팅을 시작할 수 없습니다.", function() {
          location.reload();
        });
        return;
      }

      console.log("지원 상태 정상, 채팅방 생성 진행");

      var params = {};
      params["rm_type"] = g_rm_type;
      params["rc_idx"] = g_rc_idx;
      params["ru_idx"] = g_ru_idx;
      params["td_idx"] = g_td_idx;

      console.log("채팅방 생성 요청:", params);

      comAjax("/chat_proc/insert_room", params, function(ret2) {
        if (ret2.code == "00") {
          var chat_suer = [];
          chat_suer.push({
            user_id: user_id,
            img: user_img,
            user_name: user_name
          });
          chat_suer.push({
            user_id: target_id,
            img: target_img,
            user_name: target_name
          });

          var room_info = {
            user_id: user_id,
            user_list: chat_suer,
            rm_idx: Number(ret2.data.rm_idx),
            rm_type: "chat",
            rm_img: "",
            rm_subject: subject,
            rm_desc: "",
          };

          first_yn = ret2.data.first_yn;
          first_message = {
            rm_idx: Number(ret2.data.rm_idx),
            user_id: user_id,
            chat_type: "recruit",
            mg_type: "text",
            mg_text: "1:1 채팅을 시작합니다!",
            mg_req_dt: "",
            mg_img: "",
            mg_file: "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: new Array(),
          };
          first_message.mg_reads.push({
            read_yn: "Y",
            user_id: user_id
          });
          first_message.mg_reads.push({
            read_yn: "N",
            user_id: target_id
          });

          first_users.push({
            user_id: user_id,
            user_nick: user_name
          });
          first_users.push({
            user_id: target_id,
            user_nick: target_name
          });

          click_type = "chat";
          socket.emit("room_insert", room_info);

          $('#bottomchatbtn').trigger('click');

          setTimeout(function() {
            $('.list-item a').each(function() {
              if ($(this).attr('onclick') === "fShowChatListByRecruite('" + g_rc_idx + "',this); return false;") {
                $(this).trigger('click');
                return false;
              }
            });
          }, 500);

          setTimeout(function() {
            $('.list-item a').each(function() {
              if ($(this).data('ru_idx') == g_ru_idx && $(this).data('rc_idx') == g_rc_idx) {
                $(this).parent().trigger('click');
                $('.chat-view').removeAttr('hidden');
              }
            });
          }, 1000);

        } else {
          commonAlert(ret2.msg, function() {});
          return;
        }
      });
    });
  });

  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.list-item').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });

  $(document).ready(function() {
    // 주소 정리
    $('.area[data-addr]').each(function() {
      var $this = $(this);
      var originalAddr = $this.data('addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var formattedAddr = fRemoveBigAdrJS(originalAddr);
        $this.text(formattedAddr);
      }
    });

    // 근무형태 정리
    $('.price_type[data-work-type]').each(function() {
      var $this = $(this);
      var workType = $this.data('work-type');
      if (workType && typeof getWorkTypeFirst === 'function') {
        var formattedWorkType = getWorkTypeFirst(workType);
        if (formattedWorkType) {
          $this.html(formattedWorkType);
        }
      }
    });

    // 급여형태 정리
    $('.ru_price_type[data-price-type]').each(function() {
      var $this = $(this);
      var priceType = $this.data('price-type');
      if (priceType && typeof getPriceTypeJSFirst === 'function') {
        var formattedPriceType = getPriceTypeJSFirst(priceType);
        if (formattedPriceType) {
          $this.html(formattedPriceType);
        }
      }
    });
  });
</script>
