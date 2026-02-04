<?php
	header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$chat_port = getenv('CHAT_SERVER_PORT') ? getenv('CHAT_SERVER_PORT') : '3030';
	$chat_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$chat_host = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '' ? $_SERVER['HTTP_HOST'] : 'localhost';
	$chat_host_no_port = preg_replace('/:\d+$/', '', $chat_host);
	$chat_base_url = $chat_scheme . '://' . $chat_host_no_port . ':' . $chat_port;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="naver-site-verification" content="856b3094cd5577eeb70089717a6db38c3cd334f8"/>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="Cache-Control" CONTENT="no-cache">
	<meta http-equiv="Pragma" CONTENT="no-cache">
	
    <title>채팅룸</title>
    <link rel="stylesheet" href="/chatting/css/font.css">
    <link rel="stylesheet" href="/chatting/css/common.css">
    <link rel="stylesheet" href="/chatting/css/chatting.css">
    <link type="text/css" rel="stylesheet" href="/chatting/icon/remixicon.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="<?= $chat_base_url ?>/socket.io/socket.io.js"></script>
    <script src="/chatting/js/common.js"></script>
    <script src="/chatting/js/chatting.js"></script>
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
	<script src="https://unpkg.com/tippy.js@6"></script>
	
</head>
<body>


<div id="wrap_login">
    <div class="main">
        <div class="login-wrap">
            <div class="inner">
                <div class="title">
                    	회원가입
                </div>
                <input id="user_id" class="input-txt" type="text" name="text" placeholder="아이디(4자이상)" />
                <input id="user_pwd" class="input-txt" type="password" name="password" placeholder="비밀번호(4자이상)" />
                <input id="user_pwd_c" class="input-txt" type="password" name="password" placeholder="비밀번호 확인" />
                <input id="user_nick" class="input-txt" type="text" name="text" placeholder="닉네임" />
                <input id="user_desc" class="input-txt" type="text" name="text" placeholder="상세정보(미필수)" />
 				<div class="char_select">
                	<li class="box select">
                       <label for="user_img" class="content">
                       	<i class="ri-add-line"></i>
                       </label>
                       <input type="file" name="user_img" id="user_img" onchange="preview_image(event,'out_img')"  accept="image/gif, image/jpeg, image/png" style="display:none;" />
                    </li>
                    <li class="box char" id="out_img">
                       <div class="content">
                       		
                       </div>
                    </li>
                </div>
                <div class="char_list">
                    <li class="char on">
                        <img src="/chatting/img/char/char1.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char2.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char3.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char4.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char5.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char6.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char7.png"  />
                    </li>
                    <li class="char">
                        <img src="/chatting/img/char/char8.png"  />
                    </li>
                </div>
               
                <button class="btn_login" id="btn_login">회원가입</button>
           </div>
        </div>

    </div>
</div>

<script>

    $(".char_list .char").click(function(){
        $(".char_list .char").removeClass("on");
        $(this).addClass("on");
    });

    $("#btn_login").click(function(){
        
        if($("#user_id").val()==""){
            alert("아이디를 입력해주세요.");
            $("#user_id").focus();
            return;
        }

        if($("#user_pwd").val()==""){
            alert("비밀번호를 입력해주세요.");
            $("#user_pwd").focus();
            return;
        }

        if($("#user_pwd").val().length < 4){
            alert("비밀번호는 4자 이상 입력해주세요.");
            $("#user_pwd").focus();
            return;
        }

        if($("#user_pwd_c").val()==""){
            alert("비밀번호 확인을 입력해주세요.");
            $("#user_pwd_c").focus();
            return;
        }

        if($("#user_pwd").val()!=$("#user_pwd_c").val()){
            alert("비밀번호와 비밀번호 확인이 다릅니다.");
            $("#user_pwd_c").focus();
            return;
        }
        
        if($("#user_nick").val()==""){
            alert("닉네임을 입력해주세요.");
            $("#user_nick").focus();
            return;
        }

        socket.emit("user_insert", {
              user_nick : $("#user_nick").val()
            , user_id : $("#user_id").val()
            , user_pwd : $("#user_pwd").val()
            , user_desc : $("#user_desc").val()
            , user_img : $(".char_list .char.on").find("img").attr("src")
        });
    });

    // 회원생성후 콜백
    socket.on("user_insert_return", function(ret) {
       	if(ret.code=="00"){
       		localStorage.setItem("user_info",JSON.stringify((ret.data)));
       		location.href="/chatting/main.php";	
       	}else{
       		alert(ret.msg);
       	}
    });
    
    /*
     var user_data = {
    	user_nick : $("#user_nick").val()
        , user_id : $("#user_id").val()
        , user_pwd : $("#user_pwd").val()
        , user_desc : $("#user_desc").val()
        , user_img : $(".char_list .char.on").find("img").attr("src")
    };
    
    nodeAjax(base_url + "/user_insert",user_data,function(ret){
    	if(ret.code=="00"){
       		localStorage.setItem("user_info",JSON.stringify((ret.data)));
       		location.href="/chatting/user_list.php";	
       	}else{
       		alert(ret.msg);
       	}
    });
    */
</script>
</body>
</html>
