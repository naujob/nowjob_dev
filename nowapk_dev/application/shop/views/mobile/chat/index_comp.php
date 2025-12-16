<div class="page page-chat">
  <div class="header">
    <h1 class="title">채팅방</h1>
  </div><!-- //header -->
  <div class="page-content">
    <div class="tab-container">
      <ul class="tab-list">
        <li class="on"><a href="/chat/index_comp" class="tab">채용공고</a></li>
        <li><a href="/chat/chat_list3" class="tab">제안보낸인재</a></li>
        <!--<li><a href="/chat/trade_list" class="tab">중고거래</a></li>-->
      </ul>
    </div>

    <style>
      .angle-link {
        background: none;
      }

      .angle-link::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 0.2rem;
        transform: translateY(-50%) rotate(90deg);
        /* 아이콘을 아래로 회전 */
        background: url(/resource/shop/mobile/img/ic_angle_link.svg) no-repeat center;
        width: 1rem;
        /* 아이콘의 너비 */
        height: 1rem;
        /* 아이콘의 높이 */
        display: block;
        background-size: contain;
      }

      .room-item .angle-link::after {
        transform: translateY(-50%) rotate(0deg);
        /* 아이콘을 아래로 회전 */
      }
    </style>
    <style>
      .chat-empty {
        position: absolute;
        top: 28.518%;
        left: 0;
        right: 0;
        font-size: 0;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        display: none;
      }

      .chat-empty:before {
        content: "";
        display: inline-block;
        height: 100%;
        vertical-align: middle;
      }

      .chat-empty p {
        display: inline-block;
        padding: 138px 0 0;
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        background: url(/resource/shop/web/img/empty_chat.svg) no-repeat 50% 0;
        background-size: 115px auto;
        color: #777;
      }
    </style>
    <style>
      .room_rec_unread_cnt {
        position: absolute;
        width: 26px;
        height: 26px;
        background-color: #F86048;
        top: 49px;
        right: 30px;
        border-radius: 100%;
        color: #fff;
        text-align: center;
        line-height: 25px;
        display: none;
      }

      .room_unread_cnt {
        position: absolute;
        width: 26px;
        height: 26px;
        background-color: #F86048;
        top: 55px;
        right: 30px;
        border-radius: 100%;
        color: #fff;
        text-align: center;
        line-height: 25px;
        display: none;
      }

      html,
      body {
        overflow: auto;
        /* 스크롤 허용 */
        -webkit-overflow-scrolling: touch;
        /* 부드러운 스크롤 */
      }

      #app {
        overflow: auto;
        /* 스크롤 허용 */
        -webkit-overflow-scrolling: touch;
        /* 부드러운 스크롤 */
      }

      /*
        .page page-chat{
          overflow: auto; / * 스크롤 허용 * /
          -webkit-overflow-scrolling: touch; / * 부드러운 스크롤 * /
        }
        .page-content{
          overflow: auto; / * 스크롤 허용 * /
          -webkit-overflow-scrolling: touch; / * 부드러운 스크롤 * /
        }
        */
    </style>
    <div class="tab-panel">
      <div class="container">
        <h2 class="sr-text">인재정보</h2>
        <div class="chat-list">
          <ol class="list" id="chat_list">
            <?php /* foreach($list as $row) : ?>
                        <li class="list-item">
                            <div class="item-info person">
                                <div class="thumb-wrap">
                                    <button type="button" data-bk_type="RESUME" data-bk_key="<?=$row["ru_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                    <!-- 썸네일 -->
                                    <div class="thumb">
                                        <a href="/resume/hr_post/<?=$row["ru_idx"]?>"><img data-src="<?=getResumeImg($row)?>" alt="" class="lozad"></a>
                                    </div>
                                    <!-- 평점 -->
                                    <p class="star-rating">
                                        <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                        <span class="star" style="width:100%;"></span>
                                        <span class="sr-text">평점</span>
                                        <span class="sr-text">5</span>
                                    </p>
                                    <a href="/compage/recruit_view/<?=$row["rc_idx"]?>" class="btn-line">지원한 공고</a>
                                </div>

                                <div class="content-wrap">
                                    <div class="meta top">
                                        <!-- 직종라벨 -->
                                        <p class="category">
                                            <?=getWorkingImg($row["ru_work"])?>
                                        </p>
                                        <!-- 조건 -->
                                        <p class="condition">
                                        	<em class="period"><?=(strpos($row["ru_price_type"],",")!==false ? "협의" : ($row["ru_price_type"]))?></em>,
                                        	<span class="working-time"><?=(strpos($row["ru_work_type"],",")!==false ? "협의" : ($row["ru_work_type"]))?></span>
                                        </p>
                                    </div>
                                    <div class="title-wrap">
                                        <!-- 이름 -->
                                        <p class="title"><strong><a href="/resume/hr_post/<?=$row["ru_idx"]?>"><?=$row["mm_name"]?></a></strong></p>
                                        <!-- 추가사항 -->
                                        <div class="meta">
                                            <p><?=$row["mm_nation"]?></p>
                                            <span>/</span>
                                            <p><?=$row["ma_age"]?>세</p>
                                        </div>
                                    </div>
                                    <ul class="working-day">
                                        <?=getWeekYoil($row["ru_week"])?>
                                    </ul>
                                    <!-- 해시태그 -->
                                    <div class="hashtag">
                                        <?=getMyType($row["ru_my_type"])?>
                                    </div>
                                </div>
                            </div>
                            <!-- //인재정보 -->
                            <div class="inline-group">
                                <a href="/resume/hr_post/<?=$row["ru_idx"]?>" class="btn-view-resume">이력서 보기</a>
                                <a href="#" aria-haspopup="dialog"
                                	data-ru_idx="<?=$row["ru_idx"]?>"
                                	data-rc_idx="<?=$row["rc_idx"]?>"
                                	data-target_id="<?=$row["target_id"]?>"
                                	data-user_id="<?=$row["user_id"]?>"
                                	data-target_img="<?=$row["target_img"]?>"
                                	data-user_img="<?=$row["user_img"]?>"
                                    data-target_name="<?=$row["target_name"]?>"
                                	data-user_name="<?=$row["user_name"]?>"
                                	data-subject="<?=$row["rc_name"]?>" class="btn-view-chat">1:1 채팅하기</a>
                            </div>
                        </li>
                        <?php endforeach; */ ?>
          </ol>

          <div class="chat-empty" id="chat_empty1" style="">
            <p>목록이 없습니다.</p>
          </div>

        </div>

      </div>
    </div>

  </div><!-- //page-content -->

  <!-- bottom-nav -->
  <?php $select_menu = "chat"; ?>
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
  <!-- //bottom-nav -->
</div>

<script>
  var chat_point = "<?= $point_info["cd_val"] ?>";
</script>
<?php if ($this->common->getSession("os_type") == "android") : ?>
  <script>
    // 안드로이드에서도 채팅방 목록을 가져오도록 추가
    window.addEventListener('DOMContentLoaded', function() {
      getChatTargetList(1);
    });

    //방생성 확인 및 방번호 확인
    function room_insert_return(data) {
      var ret = JSON.parse(data);
      //채팅방 실시간 쏘기
      if (first_yn == "Y") {
        APP_NOWAPK.message_insert(JSON.stringify(first_message), JSON.stringify(first_users));
      }

      if (ret.code == "00") {
        location.href = "/chat/chat_list?view=true&rm_idx=" + ret.data;
      } else {
        commonAlert(ret.msg, function() {});
      }
    }
  </script>

  <script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

    var g_rm_type = "recruit";
    var g_rc_idx = 0;
    var g_ru_idx = 0;
    var g_td_idx = 0;

    //룸생성처리
    $(document).on("click", ".btn-view-chat", function() {
      first_yn = "N";
      var target_id = $(this).data("target_id");
      var user_id = $(this).data("user_id");
      var target_img = $(this).data("target_img");
      var user_img = $(this).data("user_img");
      var target_name = $(this).data("target_name");
      var user_name = $(this).data("user_name");
      var subject = $(this).data("subject");

      //키값 설정
      g_rc_idx = $(this).data("rc_idx");
      g_ru_idx = $(this).data("ru_idx");

      var params = {};
      params["rm_type"] = g_rm_type;
      params["rc_idx"] = g_rc_idx;
      params["ru_idx"] = g_ru_idx;
      params["td_idx"] = g_td_idx;

      comAjax("/chat_proc/insert_room_check", params, function(chk) {
        if (chk.code == "10") {
          commonAlert(chk.msg, function() {});
          return;
        }

        if (chk.code == "90") {
          openPopup('confirm', {
            content: "포인트가 부족합니다.<br/><em>포인트 구매</em>를 하시겠습니까?",
            secondCallback: function() {
              location.href("/compage/point_charge_step1");
            }
          });
          return;
        }

        if (chk.data.first_yn == "Y") {
          openPopup('confirm', {
            content: '1:1채팅시 <em>-' + chat_point + '</em>포인트가 차감됩니다.<br> 채팅을 진행하시겠습니까?',
            secondCallback: function() {
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

                  //기본 문구 전송
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

                  //회원정보
                  first_users.push({
                    user_id: user_id,
                    user_nick: user_name
                  });
                  first_users.push({
                    user_id: target_id,
                    user_nick: target_name
                  });

                  //방생성
                  click_type = "chat";
                  APP_NOWAPK.room_insert(JSON.stringify(room_info));
                } else {
                  commonAlert(ret2.msg, function() {});
                  return;
                }
              });
            }
          });
        } else {
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

              //기본 문구 전송
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

              //회원정보
              first_users.push({
                user_id: user_id,
                user_nick: user_name
              });
              first_users.push({
                user_id: target_id,
                user_nick: target_name
              });

              //방생성
              click_type = "chat";
              APP_NOWAPK.room_insert(JSON.stringify(room_info));
            } else {
              commonAlert(ret2.msg, function() {});
              return;
            }
          });
        }
      });
    });

    // 안드로이드에서도 사용할 함수들 추가
    function getChatTargetList(arg) {
      //지원자목록-공고목록
      var getUrl = "get_chat_target_list_recruit";
      <?php if ($user_type == "MEM") { ?>
        getUrl = "get_chat_target_list_recruit_mem";
      <?php } ?>
      <?php if ($user_type == "COMP") { ?>
        getUrl = "get_chat_target_list_recruit";
      <?php } ?>

      comAjax("/chat_proc/" + getUrl, {}, function(ret) {
        $("#chat_list").html("");

        if (ret.code == "00") {
          var list = ret.data.list;
          list.forEach(function(row) {
            <?php if ($user_type == "MEM") { ?>
              var $clone = $("#tpl_chat_mem").clone();
            <?php } ?>
            <?php if ($user_type == "COMP") { ?>
              var $clone = $("#tpl_chat").clone();
            <?php } ?>

            var $content = $clone.html();

            $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
            $content = $content.replaceAll("{{room_idx_list}}", row.rm_idx_list);
            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_week}}", row.ru_week);
            $content = $content.replaceAll("{{distance}}", row.distance + "km");
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            $content = $content.replaceAll("{{ma_age}}", row.ma_age);
            $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
            $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
            $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
            $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
            $content = $content.replaceAll("{{mm_id}}", row.mm_id);
            $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
            $content = $content.replaceAll("{{target_id}}", row.target_id);
            $content = $content.replaceAll("{{user_id}}", row.user_id);
            $content = $content.replaceAll("{{target_img}}", row.target_img);
            $content = $content.replaceAll("{{user_img}}", row.user_img);
            $content = $content.replaceAll("{{target_name}}", row.target_name);
            $content = $content.replaceAll("{{user_name}}", row.user_name);

            $content = $content.replaceAll("{{rc_name}}", row.rc_name);
            $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb);
            $content = $content.replaceAll("{{reg_dt}}", row.reg_dt);
            $content = $content.replaceAll("{{rc_work}}", row.rc_work);

            var nationStr = '';
            nationStr = row.rc_nation;
            if (row.rc_nation != "" && (row.rc_sage != "0" || row.rc_eage != "0")) {
              nationStr += " | ";
            }
            if (row.rc_sage == "0") {
              nationStr += row.rc_sage + "세";
            }
            if (row.rc_sage == "0" && row.rc_eage == "0") {
              nationStr += "";
            } else {
              nationStr += " ~ ";
            }
            if (row.rc_eage == "0") {
              nationStr += "";
            } else {
              nationStr += row.rc_eage + "세";
            }
            $content = $content.replaceAll("{{rc_nation}}", nationStr);

            //복사한 내용 다시 넣기
            $clone.html($content);
            $("#chat_list").append($clone.html());
          });

          if ($("#chat_list").find("li").length == 0) {
            $(".chat-empty").show();
            $("#chat_list").hide();
          }

        } else {
          commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
          return;
        }

        fSearchNoReadCount();
      });
    }

    function fSearchNoReadCount() {
      $('.content-wrap').each(function() {
        $(this).attr('data-noreadcnt', '0');
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        var myId = $("#user_id").val();
        let result = ret.data.result;

        $.each(result, function(key, room) {
          let count = 0;
          $.each(room.rm_messages, function(index, message) {
            $.each(message.mg_reads, function(i, readEntry) {
              if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                count++;
              }
            });
          });

          $('#room_idx_unread_cnt_' + room.rm_idx).html(count);
          if (count > 0) {
            $('#room_idx_unread_cnt_' + room.rm_idx).show();
            fSetNoReadCntRecruit(room.rm_idx, count);
          }
        });

        $('.content-wrap').each(function() {
          let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;
          if (noReadCnt > 0) {
            let parentLi = $(this).closest('li');
            parentLi.find('.room_rec_unread_cnt').text(noReadCnt);
            parentLi.find('.room_rec_unread_cnt').show();
          }
        });
      });
    }

    function fSetNoReadCntRecruit(room_idx, cnt) {
      $('.content-wrap').each(function() {
        let roomIdxList = $(this).attr('data-room_idx_list');
        if (roomIdxList) {
          let roomIdxArray = roomIdxList.replace(/[(),]/g, '').split(' ').map(Number);
          if (roomIdxArray.includes(room_idx)) {
            let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;
            let additionalValue = cnt;
            let newNoReadCnt = noReadCnt + additionalValue;
            $(this).attr('data-noreadcnt', newNoReadCnt);
          }
        }
      });
    }
  </script>
<?php else: ?>
  <script>
    socket.on("room_list_return", function(ret) {
      alert('return');
    });

    window.addEventListener('DOMContentLoaded', function() {

      getChatTargetList(1);
      //룸정보 가져오기
      //g_user_id = $("#user_id").val();
      //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

      document.addEventListener("touchmove", function(e) {
        // 기본 동작을 차단하지 않음
      }, {
        passive: true
      }); // passive: true로 설정하면 기본 스크롤 허용

    });

    function getChatTargetList(arg) {
      //지원자목록-공고목록
      //alert(arg);
      var getUrl = "get_chat_target_list_recruit";
      <?php if ($user_type == "MEM") { ?>
        getUrl = "get_chat_target_list_recruit_mem";
      <?php } ?>
      <?php if ($user_type == "COMP") { ?>
        getUrl = "get_chat_target_list_recruit";
      <?php } ?>
      //alert(getUrl);
      comAjax("/chat_proc/" + getUrl, {}, function(ret) { //_recruit

        //$("#chat_list"+arg).html("");
        $("#chat_list").html("");

        if (ret.code == "00") {
          var list = ret.data.list;
          list.forEach(function(row) {

            <?php if ($user_type == "MEM") { ?>
              var $clone = $("#tpl_chat_mem").clone();
            <?php } ?>
            <?php if ($user_type == "COMP") { ?>
              var $clone = $("#tpl_chat").clone();
            <?php } ?>

            var $content = $clone.html();

            $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
            $content = $content.replaceAll("{{room_idx_list}}", row.rm_idx_list);
            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_week}}", row.ru_week);
            $content = $content.replaceAll("{{distance}}", row.distance + "km");
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            $content = $content.replaceAll("{{ma_age}}", row.ma_age);
            $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
            $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
            $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
            $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
            $content = $content.replaceAll("{{mm_id}}", row.mm_id);
            $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
            $content = $content.replaceAll("{{target_id}}", row.target_id);
            $content = $content.replaceAll("{{user_id}}", row.user_id);
            $content = $content.replaceAll("{{target_img}}", row.target_img);
            $content = $content.replaceAll("{{user_img}}", row.user_img);
            $content = $content.replaceAll("{{target_name}}", row.target_name);
            $content = $content.replaceAll("{{user_name}}", row.user_name);

            $content = $content.replaceAll("{{rc_name}}", row.rc_name);
            $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb);
            $content = $content.replaceAll("{{reg_dt}}", row.reg_dt);
            $content = $content.replaceAll("{{rc_work}}", row.rc_work);

            var nationStr = '';
            nationStr = row.rc_nation;
            if (row.rc_nation != "" && (row.rc_sage != "0" || row.rc_eage != "0")) {
              nationStr += " | ";
            }
            if (row.rc_sage == "0") {
              nationStr += row.rc_sage + "세";
            }
            if (row.rc_sage == "0" && row.rc_eage == "0") {
              nationStr += "";
            } else {
              nationStr += " ~ ";
            }
            if (row.rc_eage == "0") {
              nationStr += "";
            } else {
              nationStr += row.rc_eage + "세";
            }
            $content = $content.replaceAll("{{rc_nation}}", nationStr);

            //복사한 내용 다시 넣기
            $clone.html($content);

            //alert($clone.html());

            //$("#chat_list"+arg).append($clone.html());
            $("#chat_list").append($clone.html());

          });

          if ($("#chat_list").find("li").length == 0) {
            $(".chat-empty").show();
            $("#chat_list").hide();
          }

        } else {
          commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
          return;
        }

        //fSearchNoReadCount();
        //alert('call roomlist:'+$("#user_id").val());
        //socket.emit("room_list",{user_id : $("#user_id").val(), rm_type : "chat"});
        fSearchNoReadCount();


      });

    } //getChatTargetList

    function fSearchNoReadCount() {

      $('.content-wrap').each(function() {
        $(this).attr('data-noreadcnt', '0');
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        //console.log(ret);
        var myId = $("#user_id").val();

        let result = ret.data.result;

        $.each(result, function(key, room) {
          //console.log(room);
          let count = 0;
          $.each(room.rm_messages, function(index, message) {
            $.each(message.mg_reads, function(i, readEntry) {
              if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                count++;
              }
            });
          });
          //console.log(`rm_idx ${room.rm_idx}: ${count} unread messages`);
          //alert(room.rm_idx);

          $('#room_idx_unread_cnt_' + room.rm_idx).html(count);
          //alert($('#room_idx_unread_cnt_'+room.rm_idx).html());
          if (count > 0) {
            $('#room_idx_unread_cnt_' + room.rm_idx).show();
            fSetNoReadCntRecruit(room.rm_idx, count);
          }
        });

        $('.content-wrap').each(function() {
          // 현재 div의 data-noreadcnt 값을 가져오기
          let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

          // data-noreadcnt 값이 0보다 큰 경우
          if (noReadCnt > 0) {
            // 상위 li 요소 찾기
            let parentLi = $(this).closest('li');

            // li 내의 room_idx_unread_cnt 클래스를 가진 요소에 data-noreadcnt 값을 설정
            parentLi.find('.room_rec_unread_cnt').text(noReadCnt);
            parentLi.find('.room_rec_unread_cnt').show();
          }
        });

      });
    }

    function fSetNoReadCntRecruit(room_idx, cnt) {

      $('.content-wrap').each(function() {
        // data-room_idx_list 속성 값 가져오기
        let roomIdxList = $(this).attr('data-room_idx_list');

        if (roomIdxList) {
          // 괄호와 쉼표 제거 후 공백으로 분할하여 숫자 배열 생성
          let roomIdxArray = roomIdxList.replace(/[(),]/g, '').split(' ').map(Number);

          // 배열에 125가 포함되어 있는지 확인
          if (roomIdxArray.includes(room_idx)) {
            // 현재 div의 data-noreadcnt 값을 가져와 정수로 변환
            let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

            let additionalValue = cnt;

            // 계산된 값을 다시 data-noreadcnt에 설정
            let newNoReadCnt = noReadCnt + additionalValue;
            $(this).attr('data-noreadcnt', newNoReadCnt);

          }
        }
      });
    }

    /*
    //뒤로왔을때 상품 유지
    window.addEventListener('pageshow', function (event) {
        alert('뒤로가기 캐치');
        location.reload();
        if (event.persisted || window.performance && window.performance.navigation.type == 2) {
            alert('뒤로가기 캐치');
        }
    }, false);
    */
    window.onpageshow = function(event) {
      // 앞/뒤로가기 버튼 클릭 시
      if (
        window.performance &&
        window.performance.navigation &&
        window.performance.navigation.type == 2
      ) {
        // 처리 로직
        //alert('111');
      }
    };

    window.onpageshow = function(event) {
      // BFCache를 통해 페이지 접근할 경우
      if (event.persisted) {
        //alert('22');
        window.location.reload(); // 페이지 새로고침
      }
    };

    //방생성 확인 및 방번호 확인
    socket.on("room_insert_return", function(ret) {
      //채팅방 실시간 쏘기
      if (first_yn == "Y") {
        socket.emit("message_insert", first_message, first_users);
      }

      if (ret.code == "00") {
        location.href = "/chat/chat_list?view=true&rm_idx=" + ret.data;
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  </script>

  <script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

    var g_rm_type = "recruit";
    var g_rc_idx = 0;
    var g_ru_idx = 0;
    var g_td_idx = 0;

    //룸생성처리
    $(document).on("click", ".btn-view-chat", function() {
      first_yn = "N";
      var target_id = $(this).data("target_id");
      var user_id = $(this).data("user_id");
      var target_img = $(this).data("target_img");
      var user_img = $(this).data("user_img");
      var target_name = $(this).data("target_name");
      var user_name = $(this).data("user_name");
      var subject = $(this).data("subject");

      //키값 설정
      g_rc_idx = $(this).data("rc_idx");
      g_ru_idx = $(this).data("ru_idx");

      var params = {};
      params["rm_type"] = g_rm_type;
      params["rc_idx"] = g_rc_idx;
      params["ru_idx"] = g_ru_idx;
      params["td_idx"] = g_td_idx;

      comAjax("/chat_proc/insert_room_check", params, function(chk) {
        if (chk.code == "10") {
          commonAlert(chk.msg, function() {});
          return;
        }

        if (chk.code == "90") {
          openPopup('confirm', {
            content: "포인트가 부족합니다.<br/><em>포인트 구매</em>를 하시겠습니까?",
            secondCallback: function() {
              location.href("/compage/point_charge_step1");
            }
          });
          return;
        }

        if (chk.data.first_yn == "Y") {
          openPopup('confirm', {
            content: '1:1채팅시 <em>-' + chat_point + '</em>포인트가 차감됩니다.<br> 채팅을 진행하시겠습니까?',
            secondCallback: function() {
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

                  //기본 문구 전송
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

                  //회원정보
                  first_users.push({
                    user_id: user_id,
                    user_nick: user_name
                  });
                  first_users.push({
                    user_id: target_id,
                    user_nick: target_name
                  });

                  //방생성
                  click_type = "chat";
                  socket.emit("room_insert", room_info);
                } else {
                  commonAlert(ret2.msg, function() {});
                  return;
                }
              });
            }
          });
        } else {
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

              //기본 문구 전송
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

              //회원정보
              first_users.push({
                user_id: user_id,
                user_nick: user_name
              });
              first_users.push({
                user_id: target_id,
                user_nick: target_name
              });

              //방생성
              click_type = "chat";
              socket.emit("room_insert", room_info);
            } else {
              commonAlert(ret2.msg, function() {});
              return;
            }
          });
        }
      });
    });
  </script>
<?php endif; ?>

<script>
  $(document).on("click", ".room-item", function(e) {
    // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
    if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
      return; // fShowChatListByRecruite 함수가 처리하도록 함
    }

    var target_id = $(this).data("target_id");
    location.href = "/chat/chat_view?rm_idx=" + target_id;
  });

  function fShowChatListByRecruite(ru_idx, obj) {
    //console.log('fShowChatListByRecruite:'+ru_idx);
    //console.log($(obj).parent().parent().parent().attr('id'));

    chatDetailShow = true;

    //지원자목록-공고목록
    var params = {};
    params["ru_idx"] = ru_idx;
    params["chatgbn"] = 'chatPanel1'; //$(obj).parent().parent().parent().attr('id');
    //console.log(params);
    //alert(params);

    comAjax("/chat_proc/get_chat_target_list", params, function(ret) {
      //alert(ret);
      $("#chat_list1_1").html("");
      $(".resumebox_" + ru_idx + "").remove();

      //console.log("get_chat_target_list");
      //console.log(ret);


      if (ret.code == "00") {
        var list = ret.data.list;
        list.forEach(function(row) {
          //console.log(row);
          //console.log('req:'+ru_idx+',res:'+row.rc_idx);

          if (ru_idx == row.rc_idx) {
            //요청한 공고와 채팅방 공고번호가 일치하면 리스트 표시

            var $clone = $("#tpl_chat_by_recruite").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_week}}", row.ru_week);
            $content = $content.replaceAll("{{distance}}", row.distance + "km");
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            $content = $content.replaceAll("{{ma_age}}", row.ma_age);
            $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
            $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
            $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
            $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
            $content = $content.replaceAll("{{mm_id}}", row.mm_id);
            $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
            $content = $content.replaceAll("{{target_id}}", row.target_id);
            $content = $content.replaceAll("{{user_id}}", row.user_id);
            $content = $content.replaceAll("{{target_img}}", row.target_img);
            $content = $content.replaceAll("{{user_img}}", row.user_img);
            $content = $content.replaceAll("{{target_name}}", row.target_name);
            $content = $content.replaceAll("{{user_name}}", row.user_name);

            $content = $content.replaceAll("{{rc_name}}", row.rc_name);
            $content = $content.replaceAll("{{room_idx}}", row.rm_idx); //test row.room_idx 204
            //$content = $content.replaceAll("{{rc_thumb}}",'/resource/uploads/member/58ae42eee368a36e2c2de2694d3d94c3.png'); //row.rc_thumb
            $content = $content.replaceAll("{{target_name}}", row.target_name);
            $content = $content.replaceAll("{{up_dt}}", row.up_dt);

            var meminfo = "";
            if (row.ru_work != "") meminfo = row.ru_work + "<br>";
            if (row.mm_nation != "") meminfo += row.mm_nation;
            if (row.mm_nation != "" && row.ma_age != "" && row.ma_age != "0") meminfo += " | ";
            if (row.ma_age != "" && row.ma_age != "0") meminfo += row.ma_age + "세";
            if (meminfo == "") meminfo = "&nbsp;";
            $content = $content.replaceAll("{{meminfo}}", meminfo);

            //복사한 내용 다시 넣기
            $clone.html($content);

            //$("#chat_list1_1").append($clone.html());
            $(obj).parent().append("<div class='resumebox_" + ru_idx + "'>" + $clone.html() + "</div>");

          } //if

        });

      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        return;
      }

      //안 읽은 글 수 표시
      fSearchNoReadCount();

    });
  }



  /*
  document.addEventListener("mousedown", (e) => {
    console.log('mousedown');
  });

  document.addEventListener("mousemove", (e) => {
    console.log('mousemove');
  });

  document.addEventListener("mouseup", () => {
    console.log('mouseup');
  });
  */
  let startY = 0;
  let scrollY = 0;
  /*
  // 터치 이벤트 (모바일 지원)
  document.addEventListener("touchstart", (e) => {
    console.log('touchstart');
    startY = e.touches[0].clientY;
    scrollY = window.scrollY;
  });

  document.addEventListener("touchmove", (e) => {
    console.log('touchmove:'+e.touches[0].clientY);
    const deltaY = startY - e.touches[0].clientY;

    window.scrollTo(0, scrollY + deltaY);
  });

  document.addEventListener("touchend", () => {
    console.log('touchend');
  });
  */
  window.onscroll = function() {
    console.log('onscroll');
  };
</script>

<template id="tpl_chat">
  <li class="list-item">
    <a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
      <div class="item-info person" style="width:100%;">
        <div class="thumb-wrap">
          <div class="thumb" style="border-radius: 0;">
            <img src="{{rc_thumb}}" alt="">
          </div>
        </div>
        <div class="content-wrap" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
          <div class="timestamp" style="font-size: 12px; color:#777;">등록일 : {{reg_dt}}</div>
          <div class="inline-group" style="height: 20px; font-size: 12px; color:#777;">
            <!-- 직종라벨 -->
            <p class="category">{{rc_work}}</p>
          </div>
          <div class="title-wrap">
            <p class="title"><strong>{{rc_name}}</strong></p>
          </div>
          <!-- 추가조건 -->
          <div class="meta" style="font-size: 12px;">
            <!-- 근무시간 -->
            <p>{{rc_nation}}</p>
          </div>
        </div>
      </div>
    </a>
    <span class="room_rec_unread_cnt"></span>
  </li>
</template>

<template id="tpl_chat_by_recruite">
  <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}">
    <a href="#" class="angle-link js-open-view angle-link-resume"
      aria-haspopup="dialog"
      data-ru_idx="{{ru_idx}}"
      data-rc_idx="{{rc_idx}}"
      data-target_id="{{target_id}}"
      data-user_id="{{user_id}}"
      data-target_img="{{target_img}}"
      data-user_img="{{user_img}}"
      data-target_name="{{target_name}}"
      data-user_name="{{user_name}}"
      data-subject="{{rc_name}}">

      <div class="thumb" style="border-radius: unset;"><img src="{{ru_thumb}}" alt=""></div>
      <div class="mid" style="padding-top: 0.3rem;">
        <p class="name">{{target_name}}</p>
        <p class="latest" style="height: 39px;">{{meminfo}}</p>
        <!-- <p class="latest"></p> -->
        <p class="time" style="color:#777; font-size: 0.8rem; font-weight: 400; letter-spacing: 0px;">{{up_dt}}</p>
      </div>
    </a>
    <button type="button" onclick="deleteItem(this.parentNode);" class="btn-primary btn-trash js-del-item">
      <span class="sr-text">삭제</span>
    </button>
    <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
  </li>
</template>