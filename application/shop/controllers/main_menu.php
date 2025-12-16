<div class="bottom-nav">
    <!-- 활성화된 메뉴 : active 클래스 -->
    <a href="/" class="btn menu-home <?=$select_menu=="home" ? "active":""?>">메인</a>
    <button type="button" aria-controls="menu" aria-expanded="false" class="btn-toggle-menu js-toggle-menu"><span class="sr-text">메뉴열기</span></button>
    <nav id="menu" class="menu">
        <!-- 개인 사용자 -->
        <ul>
          <?php if($this->user->getUserType()=="COMP"){ ?>
            <style>
            .bottom-nav .sub-menu1:before { background-image : url( '/resource/shop/web/img/rating_stars_on.svg' ); background-size: cover!important; }
            /* .bottom-nav .menu a:before { background-size: cover; } */
            </style>
            <li><a href="/compage/bookmark_person1" class="sub-menu1">관심인재</a></li>
            <li><a href="/recruit" class="sub-menu2">채용정보</a></li>
            <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
            <li><a href="/resume" class="sub-menu4">인재정보</a></li>
            <?php if($this->user->isLogin()) : ?>
            	<li><a href="/<?=($this->user->getUserType()=="COMP") ? "compage":"mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
            <?php else : ?>
            	<li><a href="/member/login" class="sub-menu5">로그인</a></li>
            <?php endif; ?>

          <?php }else if($this->user->getUserType()=="MEM"){ ?>
            <style>
            .bottom-nav .sub-menu1:before { background-image : url( '/resource/shop/web/img/ic_top_search_on.svg' ); }
            </style>
            <li><a href="/recruit/recruit_view_list" class="sub-menu1">최근본공고</a></li>
            <li><a href="/resume" class="sub-menu2">인재정보</a></li>
            <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
            <li><a href="/recruit" class="sub-menu4">채용정보</a></li>
            <?php if($this->user->isLogin()) : ?>
            	<li><a href="/<?=($this->user->getUserType()=="COMP") ? "compage":"mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
            <?php else : ?>
            	<li><a href="/member/login" class="sub-menu5">로그인</a></li>
            <?php endif; ?>

          <?php }else{ ?>

            <li><a href="/member/login" class="sub-menu1">로그인</a></li>
            <li><a href="/resume" class="sub-menu2">인재정보</a></li>
            <li><a href="/trade" class="sub-menu3" onclick="event.preventDefault(); $('#messageOverlay').fadeIn(300); setTimeout(function() { $('#messageOverlay').fadeOut(300); }, 2000);return false;">중고거래</a></li>
            <li><a href="/recruit" class="sub-menu4">채용정보</a></li>
            <li><a href="/recruit/recruit_view_list" class="sub-menu5">최근본공고</a></li>

          <?php } ?>
          <?php /* //org ?>
            <li><a href="/" class="sub-menu1">HOME</a></li>
            <?php if($this->user->getUserType()=="COMP") : ?>
            	<li><a href="/resume" class="sub-menu2">인재정보</a></li>
            <?php else : ?>
            	<li><a href="/recruit" class="sub-menu2">채용정보</a></li>
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

    <a href="/chat" class="btn menu-chat <?=$select_menu=="chat" ? "active":""?>">채팅방</a>
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
      display:none;
    }
    </style>
    <span id="msgNoReadCountMainBottom"></span>
</div>

<script>
navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

$(".js-toggle-menu").click(function(){
    var os_type ="<?=$os_type?>";

    $("#menu").addClass("open");
	if(os_type=="android"){
		APP_NOWAPK.callVibrate();
    }else if(os_type=="ios"){
		window.webkit.messageHandlers.callVibrate.postMessage({});
	}else{
        if (navigator.vibrate) {
            navigator.vibrate(20); // 진동을 울리게 한다. 1000ms = 1초
        }
    }
});
</script>
<script>
$(document).ready(function(){
  fSearchNoReadCountMainBottom();
});
function fSearchNoReadCountMainBottom(){

  $.ajax({
        url: '/chat/index_comp',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log('index_comp');
          console.log(response);
        }
  });

  $.ajax({
        url: '/chat_proc/fSearchNoReadCount',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          //console.log(response);
          var myId = $("#user_id").val();

          let result = response.data.result;

          let countAll = 0;
          $.each(result, function(key, room) {
              //console.log(room);
              $.each(room.rm_messages, function(index, message) {
                  $.each(message.mg_reads, function(i, readEntry) {
                      if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                          console.log(room);
                          console.log(message);
                          countAll++;
                      }
                  });
              });

          });
          if(countAll > 0){
            $('#msgNoReadCountMainBottom').html(countAll);
            $('#msgNoReadCountMainBottom').show();
          }
        },
        error: function(xhr, status, error) {
            console.error('AJAX 호출 중 오류 발생:', error);
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
</script>
