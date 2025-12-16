<?php include("./layout_header.php")?>
<div id="wrap_login">
    <div class="main">
        <div class="login-wrap">
            <div class="inner">
                <div class="icon">
                    <img src="/chatting/img/bot2.png" style="width:160px;" />
                </div>
                <input id="user_id" class="input-txt" type="text" name="text" placeholder="아이디(4자이상)" />
                <input id="user_pwd" class="input-txt" type="password" name="password" placeholder="비밀번호(4자이상)" />
                <button class="btn_login" id="btn_login">로그인</button>
                <button class="btn_join" id="btn_join" onclick="location.href='/chatting/join.php';">회원가입</button>
           </div>
        </div>

    </div>
</div>

<script>
    $("#btn_login").click(function(){
        loginProc();
    });

    //엔터키 처리
    $("[name=password]").keyup(function(key){
    	  if(key.keyCode==13) {
            loginProc();
        }
    });

    socket.on("user_login_return", function(ret) {
       	if(ret.code=="00"){
       		localStorage.setItem("user_info",JSON.stringify((ret.data)));
       		location.href="/chatting/main.php";
       	}else{
       		alert(ret.msg);
       		$("#user_id").focus();
       	}
    });

    function loginProc(){
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

        socket.emit("user_login", {
	          user_id : $("#user_id").val()
	        , user_pwd : $("#user_pwd").val()
	    });
    }

</script>
<?php include("./layout_footer.php")?>
