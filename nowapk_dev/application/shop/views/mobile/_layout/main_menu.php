<div class="bottom-nav">
  <!-- 활성화된 메뉴 : active 클래스 -->
  <a href="/" class="btn menu-home <?= $select_menu == "home" ? "active" : "" ?>">메인</a>
  <button type="button" aria-controls="menu" aria-expanded="false" class="btn-toggle-menu js-toggle-menu"><span class="sr-text">메뉴열기</span></button>
  <nav id="menu" class="menu">
    <!-- 개인 사용자 -->
    <ul>
      <?php if ($this->user->getUserType() == "COMP") { ?>
        <style>
          .bottom-nav .sub-menu1:before {
            background-image: url('/resource/shop/web/img/rating_stars_on.svg');
            background-size: cover !important;
          }

          /* .bottom-nav .menu a:before { background-size: cover; } */
        </style>
        <li><a href="/compage/bookmark_person1" class="sub-menu1">관심인재</a></li>
        <li><a href="/recruit/job_list" class="sub-menu2">채용정보</a></li>
        <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
        <li><a href="/resume/hr_list" class="sub-menu4">인재정보</a></li>
        <?php if ($this->user->isLogin()) : ?>
          <li><a href="/<?= ($this->user->getUserType() == "COMP") ? "compage" : "mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
        <?php else : ?>
          <li><a href="/member/login" class="sub-menu5">로그인</a></li>
        <?php endif; ?>

      <?php } else if ($this->user->getUserType() == "MEM") { ?>
        <style>
          .bottom-nav .sub-menu1:before {
            background-image: url('/resource/shop/web/img/ic_top_search_on.svg');
          }
        </style>
        <li><a href="/recruit/job_view_list" class="sub-menu1">최근본공고</a></li>
        <li><a href="/resume/hr_list" class="sub-menu2">인재정보</a></li>
        <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
        <li><a href="/recruit/job_list" class="sub-menu4">채용정보</a></li>
        <?php if ($this->user->isLogin()) : ?>
          <li><a href="/<?= ($this->user->getUserType() == "COMP") ? "compage" : "mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
        <?php else : ?>
          <li><a href="/member/login" class="sub-menu5">로그인</a></li>
        <?php endif; ?>

      <?php } else { ?>

        <li><a href="/member/login" class="sub-menu1">로그인</a></li>
        <li><a href="/resume/hr_list" class="sub-menu2">인재정보</a></li>
        <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
        <li><a href="/recruit/job_list" class="sub-menu4">채용정보</a></li>
        <li><a href="/recruit/job_view_list" class="sub-menu5">최근본공고</a></li>

      <?php } ?>
      <?php /* //org ?>
            <li><a href="/" class="sub-menu1">HOME</a></li>
            <?php if($this->user->getUserType()=="COMP") : ?>
            	<li><a href="/resume/hr_list" class="sub-menu2">인재정보</a></li>
            <?php else : ?>
            	<li><a href="/recruit/job_list" class="sub-menu2">채용정보</a></li>
            <?php endif; ?>
            <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
            <li><a href="/chat" class="sub-menu4">채팅</a></li>
            <?php if($this->user->isLogin()) : ?>
            	<li><a href="/<?=($this->user->getUserType()=="COMP") ? "compage":"mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
            <?php else : ?>
            	<li><a href="/member/login" class="sub-menu5">로그인</a></li>
            <?php endif; ?>
          <?php */ ?>
    </ul>
    <div class="dimmed"></div>
  </nav>
  <div id="messageOverlay" style="display:none; position: fixed; bottom: 90px; right: 0; width: 180px; height: 70px; background-color: transparent; z-index: 9999;">
    <div id="messageBox" style="position: absolute; top: 0%; left: 0%; background-color: #ccc; padding: 20px; border-radius: 10px; font-size: 13px; color: #000; border: 1px solid #ccc;">준비중 입니다</div>
  </div>

  <a href="#" class="btn menu-chat <?= $select_menu == "chat" ? "active" : "" ?>" id="btnChat">채팅방</a>
  <style>
    #msgNoReadCountMainBottom {
      position: absolute;
      top: -3px;
      right: 12%;
      background-color: #F86048;
      width: 24px;
      height: 24px;
      text-align: center;
      line-height: 24px;
      color: #fff;
      font-size: 0.8rem;
      border-radius: 100%;
      display: none;
    }
  </style>
  <span id="msgNoReadCountMainBottom"></span>
</div>

<script>
  navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

  $(".js-toggle-menu").click(function() {
    var os_type = "<?= $os_type ?>";

    $("#menu").addClass("open");
    if (os_type == "android") {
      APP_NOWAPK.callVibrate();
    } else if (os_type == "ios") {
      window.webkit.messageHandlers.callVibrate.postMessage({});
    } else {
      if (navigator.vibrate) {
        navigator.vibrate(20); // 진동을 울리게 한다. 1000ms = 1초
      }
    }
  });
</script>
<script>
  $(document).ready(function() {
    fSearchNoReadCountMainBottom();
  });

  function fSearchNoReadCountMainBottom() {

    //alert('fSearchNoReadCountMainBottom');
    if ($("#user_id").val() == '') return;

    var room_alert_check_url = '';
    <?php if ($this->user->getUserType() == "COMP") { ?>
      room_alert_check_url = '/chat/index_comp_ajax';
    <?php } else { ?>
      room_alert_check_url = '/chat_proc/get_chat_target_list_recruit_mem';
    <?php } ?>
    room_alert_check_url = '/chat_proc/getRoomListActaive';

    var rmIdxArray = [];
    $.ajax({
      url: room_alert_check_url,
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        //console.log('index_comp');
        //console.log(response);

        //방목록 확인
        var roomCnt = 0;
        $.each(response.data.list, function(key, item) {
          //console.log(item);
          //console.log('1:'+item.rm_idx);
          //console.log('1:'+item.rm_idx_list);
          rmIdxArray.push(item.rm_idx);
          roomCnt++;

          if (item.rm_idx !== undefined) {
            rmIdxArray.push(item.rm_idx);
          }

          /*
          // item.rm_idx_list가 undefined가 아니고 "(338, 344, 347)" 형태라면 처리
          if (item.rm_idx_list !== undefined && /^\(\d+(,\s*\d+)*\)$/.test(item.rm_idx_list)) {
            // 괄호를 제거하고 콤마로 분리
            var idxList = item.rm_idx_list.replace(/[()]/g, "").split(',');

            // 각 숫자를 rmIdxArray에 추가
            idxList.forEach(function(idx) {
              rmIdxArray.push(idx.trim());
            });
          }
          */

        });
        //console.log('111:roomCnt:'+roomCnt);

        //node 방 확인 시작
        $.ajax({
          url: '/chat_proc/fSearchNoReadCount',
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            //console.log(response);
            var myId = $("#user_id").val();
            //console.log(myId);

            let result = response.data.result;

            let countAll = 0;

            //console.log(rmIdxArray);
            //console.log(result);

            $.each(result, function(key, room) {
              //console.log(room);
              //console.log('2:'+room.rm_idx);
              //console.log($.inArray(''+room.rm_idx, rmIdxArray));

              var exists = false;
              if ($.inArray('' + room.rm_idx, rmIdxArray) > -1) {
                exists = true;
              } else {
                exists = false;
              }
              //console.log(exists);
              //var exists = $.inArray(room.rm_idx, rmIdxArray) !== -1;
              //console.log("Value exists in array:", exists);

              //var fexists = rmIdxArray.some(function(rmIdx) {
              //    return rmIdx === room.rm_idx;
              //});
              //console.log("Value exists in array:", fexists);

              //console.log(myId);

              $.each(room.rm_messages, function(index, message) {
                $.each(message.mg_reads, function(i, readEntry) {
                  if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                    //console.log(room);
                    //console.log(message);
                    if (exists) {
                      countAll++;
                      //console.log('bottom count up');
                    }
                  }
                });
              });

            });
            if (countAll > 0) {
              $('#msgNoReadCountMainBottom').html(countAll);
              $('#msgNoReadCountMainBottom').show();
            }
          },
          error: function(xhr, status, error) {
            console.error('AJAX 호출 중 오류 발생:', error);
          }
        });
        //nopde 방 확인 종료

      }
    });


    /*
      comAjax("/chat_proc/fSearchNoReadCount",{},function(ret){
        //console.log(ret);
        var myId = $("#user_id").val();

        let result = ret.data.result;

        let countAll = 0;
        $.each(result, function(key, room) {
            //console.log(room);
            $.each(room.rm_messages, function(index, message) {
                $.each(message.mg_reads, function(i, readEntry) {
                    if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                        countAll++;
                    }
                });
            });

        });
        $('#msgNoReadCountMainBottom').html(countAll);
        $('#msgNoReadCountMainBottom').show();

      });
      */
  }

  // 채팅방 버튼 클릭 이벤트
  window.addEventListener('DOMContentLoaded', function() {
    $("#btnChat").click(function(e) {
      e.preventDefault();

      // 로그인 여부 확인
      var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
      if (is_login != "Y") {
        commonAlert("로그인 후 이용해주세요.", function() {
          location.href = '/member/login';
        });
        return;
      }

      // 채팅방 이동 확인 팝업
      openPopup('confirm', {
        content: '채팅방 페이지로<br> 이동하시겠습니까?',
        firstCallback: function() {
          $('.btn-close-popup').show();
        },
        secondCallback: function() {
          // 채팅방 페이지로 이동
          location.href = '/chat';

        }
      });
      $('.btn-close-popup').hide();
    });
  });
</script>